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
        return redirect()
            ->route('pegawai.login', ['return_to' => url()->previous(), 'require_email' => 1])
            ->with('error', 'Silakan login sebagai pegawai terlebih dahulu.');
    }

    return DB::transaction(function () use ($id, $pegawai) {

        // Kunci sesi
        $sesi = pbj_1_pelatihan::whereKey($id)->lockForUpdate()->first();
        if (!$sesi) {
            return back()->with('error', 'Pelatihan tidak tersedia.');
        }
        if (($sesi->status ?? '') !== 'aktif') {
            return back()->with('warning', 'Pendaftaran ditutup.');
        }

        // Cutoff H-7: mulai H-7 (00:00) sudah tidak bisa daftar
        if (!empty($sesi->tanggal_mulai)) {
            $today  = Carbon::today();
            $start  = Carbon::parse($sesi->tanggal_mulai)->startOfDay();
            $cutoff = $start->copy()->subDays(7);
            if ($today->greaterThanOrEqualTo($cutoff)) {
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
            switch ($last->status) {
                case 'menunggu':
                    return back()->with('info', 'Permohonan Anda sudah dikirim dan sedang menunggu verifikasi.');
                case 'diterima':
                    return back()->with('info', 'Anda sudah diterima pada pelatihan ini.');
                case 'berjalan':
                    return back()->with('info', 'Pelatihan ini sedang Anda ikuti.');
                case 'menunggu_laporan':
                    return back()->with('info', 'Pelatihan selesai, silakan unggah laporan.');
            }
        }

        // Jika attempt terakhir sudah lulus / tidak_lulus → kebijakan: blok daftar ulang
        if ($last && in_array($last->status, ['lulus','tidak_lulus'], true)) {
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

        return back()->with('success', 'Permohonan pendaftaran terkirim. Menunggu verifikasi admin.');
    });
}


// Batalkan (leave) — ubah ke "dibatalkan" bila masih menunggu/diterima
public function leave($id)
{
    $pegawai = Auth::guard('pegawais')->user();
    if (!$pegawai) {
        return redirect()
            ->route('pegawai.login', ['return_to' => url()->current()])
            ->with('error', 'Silakan login sebagai pegawai terlebih dahulu.');
    }

    return DB::transaction(function () use ($id, $pegawai) {
        // Kunci baris yang relevan (status yang boleh dibatalkan)
        $row = DB::table('peserta_pelatihan')
            ->where('pelatihan_id', $id)
            ->where('nip', $pegawai->nip)
            ->whereIn('status', ['menunggu'])
            ->lockForUpdate()
            ->first();

        if (!$row) {
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

        return back()->with('success', 'Pengajuan/pendaftaran berhasil dibatalkan.');
    });
}


}
