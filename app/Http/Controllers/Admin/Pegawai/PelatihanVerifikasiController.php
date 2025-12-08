<?php

namespace App\Http\Controllers\Admin\Pegawai;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PesertaPelatihan;
use App\Models\pbj_1_pelatihan;
use Illuminate\Support\Facades\DB;

class PelatihanVerifikasiController extends Controller
{
    public function index(Request $request)
    {
        $status  = $request->input('status', 'menunggu'); // default: menunggu
        $q       = trim($request->input('q', ''));
        $sesiId  = $request->input('pelatihan_id');

        $query = PesertaPelatihan::with(['pelatihan:id,nama_pelatihan,tanggal_mulai,tanggal_selesai,kuota,metode_pelatihan,lokasi'])
            ->when($status, fn($qq) => $qq->where('status', $status))
            ->when($sesiId, fn($qq) => $qq->where('pelatihan_id', $sesiId))
            ->when($q !== '', function ($qq) use ($q) {
                $qq->where(function ($x) use ($q) {
                    $x->where('nip', 'like', "%{$q}%")
                      ->orWhere('nama', 'like', "%{$q}%")
                      ->orWhere('unitkerja', 'like', "%{$q}%");
                });
            })
            ->whereHas('pelatihan') // hindari orphan
            ->orderByDesc('created_at');

        $pesertas = $query->paginate(15)->withQueryString();

        // Ambil list sesi untuk filter dropdown
        $sessions = pbj_1_pelatihan::select('id','nama_pelatihan')->orderBy('nama_pelatihan')->get();

        // Hitung okupansi per sesi (kursi terpakai)
        $terpakaiMap = PesertaPelatihan::select('pelatihan_id', DB::raw("SUM(CASE WHEN status IN ('diterima','berjalan','menunggu_laporan') THEN 1 ELSE 0 END) as used"))
            ->groupBy('pelatihan_id')
            ->pluck('used', 'pelatihan_id');

        return view('admin.pelatihan.verifikasi', compact('pesertas', 'sessions', 'status', 'q', 'sesiId', 'terpakaiMap'));
    }

    public function approve($pelatihan, $nip)
    {
        try {
            $msg = DB::transaction(function () use ($pelatihan, $nip) {

                // 1) Ambil peserta + lock (pakai Eloquent seperti reject)
                $peserta = PesertaPelatihan::where('pelatihan_id', $pelatihan)
                    ->where('nip', $nip)              // pastikan nip dipassing persis (string) agar leading zero tidak hilang
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($peserta->status !== 'menunggu') {
                    abort(400, 'Status peserta bukan "menunggu".');
                }

                // 2) Ambil sesi + lock
                $sesi = pbj_1_pelatihan::whereKey($pelatihan)
                    ->lockForUpdate()
                    ->firstOrFail();

                // 3) Cek kuota (status yang “makan kursi”)
                $kuota = (int)($sesi->kuota ?? 0);
                if ($kuota > 0) {
                    $terpakai = PesertaPelatihan::where('pelatihan_id', $sesi->id)
                        ->whereIn('status', ['diterima','berjalan','menunggu_laporan'])
                        ->lockForUpdate()
                        ->count();

                    if ($terpakai >= $kuota) {
                        abort(409, "Kuota penuh ({$terpakai}/{$kuota}). Tidak bisa menyetujui.");
                    }
                }

                // 4) Approve
                $peserta->update([
                    'status'     => 'diterima',
                    'updated_at' => now(),
                ]);

                // (opsional) info terpakai sesudah
                $info = '';
                if ($kuota > 0) {
                    $after = PesertaPelatihan::where('pelatihan_id', $sesi->id)
                        ->whereIn('status', ['diterima','berjalan','menunggu_laporan'])
                        ->count();
                    $info = " (Terpakai {$after}/{$kuota})";
                }

                return 'Pengajuan disetujui.' . $info;
            });

            // Tips UX: setelah approve, otomatis pindahkan filter ke "diterima"
            return redirect()
                ->route('admin.pelatihan.verifikasi', ['status' => 'diterima'])
                ->with('success', $msg);

        } catch (\Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }



    public function reject(Request $request, $pelatihan, $nip)
    {
        try {
            DB::transaction(function () use ($pelatihan, $nip) {
                $peserta = PesertaPelatihan::where('pelatihan_id', $pelatihan)
                    ->where('nip', $nip)
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($peserta->status !== 'menunggu') {
                    abort(400, 'Status bukan menunggu.');
                }

                $peserta->update(['status' => 'ditolak']);
            });

            return back()->with('success', 'Pengajuan ditolak.');
        } catch (\Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function bulk(Request $request)
    {
        $action   = $request->input('action'); // approve|reject
        $selected = $request->input('selected', []); // array of "pelatihan_id|nip"

        if (!in_array($action, ['approve','reject']) || empty($selected)) {
            return back()->with('warning', 'Tidak ada data dipilih atau aksi tidak valid.');
        }

        $ok = 0; $err = 0;

        foreach ($selected as $key) {
            [$pelatihan, $nip] = explode('|', $key);
            try {
                DB::transaction(function () use ($action, $pelatihan, $nip) {
                    $peserta = PesertaPelatihan::where('pelatihan_id', $pelatihan)
                        ->where('nip', $nip)
                        ->lockForUpdate()
                        ->firstOrFail();

                    if ($peserta->status !== 'menunggu') {
                        abort(400, 'Status bukan menunggu.');
                    }

                    if ($action === 'approve') {
                        $sesi   = pbj_1_pelatihan::whereKey($pelatihan)->lockForUpdate()->firstOrFail();
                        $kuota  = (int)($sesi->kuota ?? 0);
                        if ($kuota > 0) {
                            $terpakai = PesertaPelatihan::where('pelatihan_id', $sesi->id)
                                ->whereIn('status', ['diterima','berjalan','menunggu_laporan'])
                                ->lockForUpdate()
                                ->count();
                            if ($terpakai >= $kuota) {
                                abort(409, 'Kuota penuh.');
                            }
                        }
                        $peserta->update(['status' => 'diterima']);
                    } else { // reject
                        $peserta->update(['status' => 'ditolak']);
                    }
                });
                $ok++;
            } catch (\Throwable $e) {
                $err++;
            }
        }

        $msg = "Selesai. Berhasil: {$ok}".($err ? " • Gagal: {$err}" : '');
        return back()->with($err ? 'warning':'success', $msg);
    }
}
