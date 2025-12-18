<?php

namespace App\Http\Controllers\Umum\Pelatihan;

use App\Http\Controllers\Controller;
use App\Models\pbj_1_pelatihan;
use App\Models\PesertaPelatihan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;


class PelatihanPendaftaranController extends Controller
{

// Daftar (join) — status awal: menunggu (review admin)
public function join(Request $request, $id)
{
    $pegawai = Auth::guard('pegawais')->user();
    if (!$pegawai) {
        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Silakan login sebagai pegawai terlebih dahulu.'
            ], 401);
        }
        return redirect()
            ->route('pegawai.login', ['return_to' => url()->previous(), 'require_email' => 1])
            ->with('error', 'Silakan login sebagai pegawai terlebih dahulu.');
    }

    return DB::transaction(function () use ($id, $pegawai, $request) {

        // Kunci sesi
        $sesi = pbj_1_pelatihan::whereKey($id)->lockForUpdate()->first();
        if (!$sesi) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Pelatihan tidak tersedia.'], 404);
            }
            return back()->with('error', 'Pelatihan tidak tersedia.');
        }
        if (($sesi->status ?? '') !== 'aktif') {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Pendaftaran ditutup.'], 400);
            }
            return back()->with('warning', 'Pendaftaran ditutup.');
        }

        // Cutoff H-7: mulai H-7 (00:00) sudah tidak bisa daftar
        if (!empty($sesi->tanggal_mulai)) {
            $today  = Carbon::today();
            $start  = Carbon::parse($sesi->tanggal_mulai)->startOfDay();
            $cutoff = $start->copy()->subDays(7);
            if ($today->greaterThanOrEqualTo($cutoff)) {
                if ($request->expectsJson()) {
                    return response()->json(['success' => false, 'message' => 'Pendaftaran ditutup mulai H-7 sebelum pelatihan dimulai.'], 400);
                }
                return back()->with('warning', 'Pendaftaran ditutup mulai H-7 sebelum pelatihan dimulai.');
            }
        }

        // Ambil attempt TERAKHIR pada sesi ini (kalau ada)
        $last = PesertaPelatihan::where('pelatihan_id', $sesi->id)
            ->where('nip', $pegawai->nip)
            ->lockForUpdate()
            ->latest('id')
            ->first();

        // Definisi status
        $activeStatuses = ['menunggu','diterima','berjalan','menunggu_laporan'];
        $finalStatuses  = ['dibatalkan','ditolak','lulus','tidak_lulus'];

        // Jika masih ada pendaftaran aktif pada sesi ini → jangan buat baru
        if ($last && in_array($last->status, $activeStatuses, true)) {
            $message = '';
            switch ($last->status) {
                case 'menunggu':
                    $message = 'Permohonan Anda sudah dikirim dan sedang menunggu verifikasi.';
                    break;
                case 'diterima':
                    $message = 'Anda sudah diterima pada pelatihan ini.';
                    break;
                case 'berjalan':
                    $message = 'Pelatihan ini sedang Anda ikuti.';
                    break;
                case 'menunggu_laporan':
                    $message = 'Pelatihan selesai, silakan unggah laporan.';
                    break;
            }
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => $message], 400);
            }
            return back()->with('info', $message);
        }

        // Jika attempt terakhir sudah lulus / tidak_lulus → kebijakan: blok daftar ulang
        if ($last && in_array($last->status, ['lulus','tidak_lulus'], true)) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Anda sudah menyelesaikan pelatihan ini. Tidak dapat mendaftar ulang.'], 400);
            }
            return back()->with('info', 'Anda sudah menyelesaikan pelatihan ini. Tidak dapat mendaftar ulang.');
        }

        // Cek bentrok jadwal dengan pelatihan lain (diterima/berjalan)
        $sesiMulai   = $sesi->tanggal_mulai;
        $sesiSelesai = $sesi->tanggal_selesai;

        if ($sesiMulai && $sesiSelesai) {
            $hasOverlapBlocking = PesertaPelatihan::query()
                ->where('nip', $pegawai->nip)
                ->whereIn('status', ['diterima','berjalan'])
                ->where('pelatihan_id', '!=', $sesi->id)
                ->whereHas('pelatihan', function ($q) use ($sesiMulai, $sesiSelesai) {
                    $q->where(function ($qq) use ($sesiMulai, $sesiSelesai) {
                        $qq->whereBetween('tanggal_mulai', [$sesiMulai, $sesiSelesai])
                           ->orWhereBetween('tanggal_selesai', [$sesiMulai, $sesiSelesai])
                           ->orWhere(function ($qqq) use ($sesiMulai, $sesiSelesai) {
                               $qqq->where('tanggal_mulai', '<=', $sesiMulai)
                                   ->where('tanggal_selesai', '>=', $sesiSelesai);
                           });
                    });
                })
                ->exists();

            if ($hasOverlapBlocking) {
                if ($request->expectsJson()) {
                    return response()->json(['success' => false, 'message' => 'Jadwal bertumpuk dengan pelatihan lain yang sudah diterima/berjalan.'], 400);
                }
                return back()->with('warning', 'Jadwal bertumpuk dengan pelatihan lain yang sudah diterima/berjalan.');
            }
        }

        // Cek kuota: status yang memakan kursi
        $kuota = (int)($sesi->kuota ?? 0);
        if ($kuota > 0) {
            $terpakai = PesertaPelatihan::where('pelatihan_id', $sesi->id)
                ->whereIn('status', ['diterima','berjalan','menunggu_laporan'])
                ->lockForUpdate()
                ->count();

            if ($terpakai >= $kuota) {
                if ($request->expectsJson()) {
                    return response()->json(['success' => false, 'message' => 'Kuota sudah penuh. Pendaftaran tidak dapat diproses.'], 400);
                }
                return back()->with('error', 'Kuota sudah penuh. Pendaftaran tidak dapat diproses.');
            }
        }

        // === INTI PERUBAHAN ===
        // Jika sebelumnya dibatalkan/ditolak → JANGAN update baris lama. Buat BARIS BARU.
        // Jika tidak ada record sama sekali → buat BARU.
        PesertaPelatihan::create([
            'pelatihan_id' => $sesi->id,
            'nip'          => $pegawai->nip,
            'nama'         => $pegawai->nama ?? null,
            'jabatan'      => $pegawai->jabatan ?? null,
            'unitkerja'    => optional($pegawai->unitKerja)->unitkerja ?? null,
            'status'       => 'menunggu',
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Permohonan pendaftaran terkirim. Menunggu verifikasi admin.'
            ]);
        }
        return back()->with('success', 'Permohonan pendaftaran terkirim. Menunggu verifikasi admin.');
    });
}


// Batalkan (leave) — ubah ke "dibatalkan" bila masih menunggu/diterima
public function leave(Request $request, $id)
{
    $pegawai = Auth::guard('pegawais')->user();
    if (!$pegawai) {
        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Silakan login sebagai pegawai terlebih dahulu.'
            ], 401);
        }
        return redirect()
            ->route('pegawai.login', ['return_to' => url()->current()])
            ->with('error', 'Silakan login sebagai pegawai terlebih dahulu.');
    }

    return DB::transaction(function () use ($id, $pegawai, $request) {
        // Kunci baris yang relevan (status yang boleh dibatalkan: menunggu atau diterima)
        $row = DB::table('peserta_pelatihan')
            ->where('pelatihan_id', $id)
            ->where('nip', $pegawai->nip)
            ->whereIn('status', ['menunggu'])
            ->lockForUpdate()
            ->first();

        if (!$row) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pelatihan tidak dapat dibatalkan dalam status ini.'
                ], 400);
            }
            return back()->with('info', 'Pelatihan tidak dapat dibatalkan dalam status ini.');
        }

        DB::table('peserta_pelatihan')
            ->where('pelatihan_id', $id)
            ->where('nip', $pegawai->nip)
            ->whereIn('status', ['menunggu','diterima'])
            ->update([
                'status'     => 'dibatalkan',
                'updated_at' => now(),
            ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Pengajuan/pendaftaran berhasil dibatalkan.'
            ]);
        }
        return back()->with('success', 'Pengajuan/pendaftaran berhasil dibatalkan.');
    });
}


}
