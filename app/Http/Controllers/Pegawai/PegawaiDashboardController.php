<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\PesertaPelatihan;
use Illuminate\Support\Carbon;

class PegawaiDashboardController extends Controller
{
public function index()
{
    $pegawai = Auth::guard('pegawais')->user();
    $nip     = $pegawai->nip ?? null;

    // eager load agar aman dipakai di Blade
    if ($pegawai) {
        $pegawai->load([
            'unitKerja:kode_unitkerja,unitkerja,sub_unitkerja',
            'atasan:id,nip,nama,jabatan,kode_unitkerja,foto',
            'atasan.unitKerja:kode_unitkerja,unitkerja,sub_unitkerja',
        ]);
    }
    $atasan = $pegawai?->atasan;

    if (!$nip) {
        return view('Pegawai.dashboard', [
            'pegawai' => $pegawai,
            'atasan'  => $atasan,
            'ongoing' => collect(),
            'history' => collect(),
            'stats'   => [
                'aktif_count'         => 0,
                'completed_count'     => 0,
                'sertifikat_count'    => 0,
                'perlu_laporan_count' => 0,
            ],
        ]);
    }

    $today = \Illuminate\Support\Carbon::today();

    // ====== AUTO PROGRESS (khusus milik pegawai ini) ======
    // diterima -> berjalan ketika sudah masuk rentang tanggal
    \App\Models\PesertaPelatihan::where('nip', $nip)
        ->where('status', 'diterima')
        ->whereHas('pelatihan', function ($q) use ($today) {
            $q->whereDate('tanggal_mulai', '<=', $today)
              ->whereDate('tanggal_selesai', '>=', $today);
        })
        ->update(['status' => 'berjalan', 'updated_at' => now()]);

    // diterima/berjalan -> menunggu_laporan ketika sudah lewat tanggal selesai
    \App\Models\PesertaPelatihan::where('nip', $nip)
        ->whereIn('status', ['diterima','berjalan'])
        ->whereHas('pelatihan', fn($q) => $q->whereDate('tanggal_selesai', '<', $today))
        ->update(['status' => 'menunggu_laporan', 'updated_at' => now()]);

    // ====== DATA UNTUK TAMPILAN ======
    $ongoingStatuses = ['menunggu','diterima','berjalan','menunggu_laporan'];
    $historyStatuses = ['lulus','tidak_lulus','dibatalkan','ditolak'];

    $withCols = ['id','nama_pelatihan','tanggal_mulai','tanggal_selesai','metode_pelatihan','lokasi'];

    // daftar aktif/proses
    $ongoing = \App\Models\PesertaPelatihan::with(['pelatihan' => fn($q) => $q->select($withCols)])
        ->where('nip', $nip)
        ->whereIn('status', $ongoingStatuses)
        ->whereHas('pelatihan')
        ->orderByRaw("FIELD(status,'menunggu','diterima','berjalan','menunggu_laporan')")
        ->orderBy('created_at')
        ->limit(5)
        ->get();

    // riwayat (lulus/tidak_lulus/dibatalkan/ditolak)
    $history = \App\Models\PesertaPelatihan::with(['pelatihan' => fn($q) => $q->select($withCols)])
        ->where('nip', $nip)
        ->whereIn('status', $historyStatuses)
        ->whereHas('pelatihan')
        ->orderByDesc('created_at')
        ->limit(5)
        ->get();

    // ====== INJEK SERTIFIKAT KE RIWAYAT (laporan approved terakhir per sesi) ======
    $pelatihanIds = $history->pluck('pelatihan_id')->filter()->unique()->values()->all();
    

    if (!empty($pelatihanIds)) {
        // ambil id laporan terakhir (approved) per (pelatihan_id, nip)
        $lastApprovedIds = \DB::table('laporan_pelatihan')
            ->select('pelatihan_id', \DB::raw('MAX(id) as last_id'))
            ->where('nip', $nip)
            ->where('status', 'approved')
            ->whereIn('pelatihan_id', $pelatihanIds)
            ->groupBy('pelatihan_id')
            ->pluck('last_id', 'pelatihan_id');

        // ambil detail file sertifikat untuk id2 tersebut
        $approvedMap = collect();
        if ($lastApprovedIds->isNotEmpty()) {
            $approvedRows = \DB::table('laporan_pelatihan')
                ->whereIn('id', $lastApprovedIds->values())
                ->get(['id','pelatihan_id','sertifikat','file_path']);

            $approvedMap = $approvedRows->keyBy('pelatihan_id');
        }

        // inject properti $row->sertifikat (dinormalisasi "storage/...") agar Blade bisa asset($row->sertifikat)
        $history->transform(function ($row) use ($approvedMap) {
            $sert = optional($approvedMap->get($row->pelatihan_id))->sertifikat;
            if ($sert) {
                // normalisasi ke "storage/..." bila perlu
                $sert = ltrim($sert, '/');
                if (!\Illuminate\Support\Str::startsWith($sert, 'storage/')) {
                    $sert = 'storage/'.$sert;
                }
                $row->sertifikat = $sert;
            } else {
                $row->sertifikat = null;
            }
            return $row;
            $lampiran = optional($approvedMap->get($row->pelatihan_id))->file_path;
            if ($lampiran) {
                // normalisasi ke "storage/..." bila perlu
                $lampiran = ltrim($lampiran, '/');
                if (!\Illuminate\Support\Str::startsWith($lampiran, 'storage/')) {
                    $lampiran = 'storage/'.$lampiran;
                }
                $row->lampiran = $lampiran;
            } else {
                $row->lampiran = null;
            }
            return $row;
            
        });
    }

    // ====== STATISTIK ======
    $aktifCount = \App\Models\PesertaPelatihan::where('nip', $nip)
        ->whereIn('status', $ongoingStatuses)
        ->count();

    $completedCount = \App\Models\PesertaPelatihan::where('nip', $nip)
        ->whereIn('status', ['lulus','tidak_lulus'])
        ->count();

    // sertifikat dihitung dari laporan_pelatihan (approved & sertifikat IS NOT NULL)
    $sertifikatCount = \DB::table('laporan_pelatihan')
        ->where('nip', $nip)
        ->where('status', 'approved')
        ->whereNotNull('sertifikat')
        ->count();

    $perluLaporanCount = \App\Models\PesertaPelatihan::where('nip', $nip)
        ->where(function ($q) use ($today) {
            $q->where('status', 'menunggu_laporan')
              ->orWhere(function ($qq) use ($today) {
                  $qq->where('status', 'berjalan')
                     ->whereHas('pelatihan', fn($r) => $r->whereDate('tanggal_selesai', '<', $today));
              });
        })
        ->count();

    $stats = [
        'aktif_count'         => $aktifCount,
        'completed_count'     => $completedCount,
        'sertifikat_count'    => $sertifikatCount,
        'perlu_laporan_count' => $perluLaporanCount,
    ];

    return view('Pegawai.dashboard', compact('pegawai','atasan','ongoing','history','stats'));
}


    public function profil()
    {
        $pegawai = auth('pegawais')->user();
        abort_if(!$pegawai, 403);

        $pegawai->load([
            'unitKerja:kode_unitkerja,unitkerja,sub_unitkerja',
            'atasan:id,nip,nama,jabatan,kode_unitkerja,foto',
            'atasan.unitKerja:kode_unitkerja,unitkerja,sub_unitkerja',
        ]);

        return view('Pegawai.profil.index', [
            'pegawai' => $pegawai,
            'atasan'  => $pegawai->atasan,
        ]);
    }
}
