<?php

namespace App\Http\Controllers\Umum\Pelatihan;

use App\Http\Controllers\Controller;
use App\Models\PesertaPelatihan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class UmumPelatihanController extends Controller
{
    // LIST
    public function index(Request $request)
    {
        $q        = $request->input('q');
        $provinsi = $request->input('provinsi');
        $kota     = $request->input('kota');
        $jenis    = $request->input('jenis');
        $status   = $request->input('status');

        // Filter Bulan/Tahun
        $bulan = (int) $request->input('bulan');
        $tahun = (int) $request->input('tahun');
        $startDate = $endDate = null;

        if ($bulan && $tahun) {
            $startDate = Carbon::createFromDate($tahun, $bulan, 1)->startOfMonth()->toDateString();
            $endDate   = Carbon::createFromDate($tahun, $bulan, 1)->endOfMonth()->toDateString();
        } elseif ($tahun && !$bulan) {
            $startDate = Carbon::createFromDate($tahun, 1, 1)->startOfYear()->toDateString();
            $endDate   = Carbon::createFromDate($tahun, 12, 31)->endOfYear()->toDateString();
        } elseif ($bulan && !$tahun) {
            $yy        = Carbon::today()->year;
            $startDate = Carbon::createFromDate($yy, $bulan, 1)->startOfMonth()->toDateString();
            $endDate   = Carbon::createFromDate($yy, $bulan, 1)->endOfMonth()->toDateString();
        }

        // Subquery kursi
        $subCounts = DB::table('peserta_pelatihan')
            ->select(
                'pelatihan_id',
                DB::raw("SUM(CASE WHEN status IN ('diterima','berjalan','menunggu_laporan') THEN 1 ELSE 0 END) AS peserta_terpakai"),
                DB::raw("SUM(CASE WHEN status = 'registered' THEN 1 ELSE 0 END) AS peserta_registered")
            )
            ->groupBy('pelatihan_id');

        // Query utama
        $trainings = DB::table('pbj_1_pelatihans as s')
            ->leftJoinSub($subCounts, 'pp', fn($j) => $j->on('pp.pelatihan_id','=','s.id'))
            ->select([
                's.id',
                's.nama_pelatihan',
                's.jenis_pelatihan',
                's.tanggal_mulai',
                's.tanggal_selesai',
                's.status',
                's.kuota',
                's.lokasi',
                's.provinsi_id',
                's.kota_id',
                DB::raw('NULL as file_pelatihan'),
                DB::raw('COALESCE(pp.peserta_terpakai,0) AS peserta_terpakai'),
                DB::raw('COALESCE(pp.peserta_registered,0) AS peserta_registered'),
                DB::raw('(s.kuota - COALESCE(pp.peserta_terpakai,0)) AS sisa_kuota')
            ])
            ->when($q, fn($qr) => $qr->where('s.nama_pelatihan', 'like', "%{$q}%"))
            ->when($jenis, fn($qr) => $qr->where('s.jenis_pelatihan', $jenis))
            ->when($provinsi, fn($qr) => $qr->where('s.provinsi_id', $provinsi))
            ->when($kota, fn($qr) => $qr->where('s.kota_id', $kota))
            ->when($status, fn($qr) => $qr->where('s.status', $status))
            ->when($startDate && $endDate, function ($qr) use ($startDate, $endDate) {
                $qr->whereDate('s.tanggal_mulai','<=',$endDate)
                   ->whereDate('s.tanggal_selesai','>=',$startDate);
            })
            ->orderByDesc('s.created_at')
            ->paginate(12)
            ->withQueryString();

        // Dropdown data
        $provinsis = DB::table('provinsis')->orderBy('nama')->get(['id','nama']);
        $kotas     = DB::table('kotas')->orderBy('nama')->get(['id','provinsi_id','nama']);
        $jenisList = DB::table('pbj_1_pelatihans')
            ->whereNotNull('jenis_pelatihan')
            ->where('jenis_pelatihan','!=','')
            ->distinct()
            ->orderBy('jenis_pelatihan')
            ->pluck('jenis_pelatihan');

        $bulanList = [
            1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',
            7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember'
        ];
        $years = range(Carbon::today()->year - 2, Carbon::today()->year + 2);

        // Status pelatihan untuk filter
        $statusList = [
            'aktif' => 'Aktif',
            'selesai' => 'Selesai',
            'ditunda' => 'Ditunda',
            'dibatalkan' => 'Dibatalkan'
        ];

        return view('MenuUmum.index', compact(
            'trainings','q','provinsis','kotas','jenisList',
            'bulanList','years','statusList','provinsi','kota','jenis','status','bulan','tahun'
        ));
    }

    // DETAIL
    public function show($id)
    {
        $session = DB::table('pbj_1_pelatihans')->where('id', $id)->first();
        
        if (!$session) {
            abort(404, 'Pelatihan tidak ditemukan');
        }

        $provinsis = DB::table('provinsis')->orderBy('nama')->get(['id','nama']);
        $kotas     = DB::table('kotas')->orderBy('nama')->get(['id','provinsi_id','nama']);

        // Kursi terpakai
        $pesertaTerpakai = DB::table('peserta_pelatihan')
            ->where('pelatihan_id', $id)
            ->whereIn('status', ['diterima','berjalan','menunggu_laporan'])
            ->count();

        // Kursi menunggu verifikasi
        $pesertaMenunggu = DB::table('peserta_pelatihan')
            ->where('pelatihan_id', $id)
            ->where('status', 'menunggu')
            ->count();

        // Sisa kuota
        $sisaKuota = max(0, ($session->kuota ?? 0) - $pesertaTerpakai);

        // Cek apakah user sudah terdaftar
        $sudahTerdaftar = false;
        $statusPendaftaran = null;
        
        if (Auth::guard('pegawais')->check()) {
            $pegawai = Auth::guard('pegawais')->user();
            $pendaftaran = DB::table('peserta_pelatihan')
                ->where('pelatihan_id', $id)
                ->where('nip', $pegawai->nip)
                ->first();
            
            if ($pendaftaran) {
                $sudahTerdaftar = true;
                $statusPendaftaran = $pendaftaran->status;
            }
        }

        // Cek apakah masih bisa daftar (belum H-7)
        $bisaDaftar = true;
        $pesanPendaftaran = '';
        
        if (!empty($session->tanggal_mulai)) {
            $cutoff = Carbon::parse($session->tanggal_mulai)->startOfDay()->subDays(7);
            if (Carbon::today()->greaterThanOrEqualTo($cutoff)) {
                $bisaDaftar = false;
                $pesanPendaftaran = 'Pendaftaran ditutup mulai H-7 sebelum pelatihan dimulai';
            }
        }

        if ($session->status !== 'aktif') {
            $bisaDaftar = false;
            $pesanPendaftaran = 'Pendaftaran ditutup';
        }

        if ($sisaKuota <= 0) {
            $bisaDaftar = false;
            $pesanPendaftaran = 'Kuota sudah penuh';
        }

        return view('MenuUmum.show', [
            'session'           => $session,
            'pesertaTerpakai'   => $pesertaTerpakai,
            'pesertaMenunggu'   => $pesertaMenunggu,
            'sisaKuota'         => $sisaKuota,
            'provinsis'         => $provinsis,
            'kotas'             => $kotas,
            'sudahTerdaftar'    => $sudahTerdaftar,
            'statusPendaftaran' => $statusPendaftaran,
            'bisaDaftar'        => $bisaDaftar,
            'pesanPendaftaran'  => $pesanPendaftaran,
        ]);
    }

    // DAFTAR
    public function join(Request $request, $id)
    {
        $pegawai = Auth::guard('pegawais')->user();
        
        if (!$pegawai) {
            return redirect()->route('pegawai.login', ['return_to' => url()->current()])
                ->with('error', 'Silakan login sebagai pegawai terlebih dahulu.');
        }

        return DB::transaction(function () use ($id, $pegawai) {
            $sesi = DB::table('pbj_1_pelatihans')
                ->where('id', $id)
                ->lockForUpdate()
                ->first();

            if (!$sesi) {
                return back()->with('error', 'Pelatihan tidak tersedia.');
            }

            if (($sesi->status ?? '') !== 'aktif') {
                return back()->with('error', 'Pendaftaran ditutup. Status pelatihan: ' . ($sesi->status ?? 'tidak aktif'));
            }

            // Tutup H-7
            if (!empty($sesi->tanggal_mulai)) {
                $cutoff = Carbon::parse($sesi->tanggal_mulai)->startOfDay()->subDays(7);
                if (Carbon::today()->greaterThanOrEqualTo($cutoff)) {
                    return back()->with('error', 'Pendaftaran ditutup mulai H-7 sebelum pelatihan dimulai.');
                }
            }

            // Cek sudah punya row?
            $existing = PesertaPelatihan::where('pelatihan_id', $sesi->id)
                ->where('nip', $pegawai->nip)
                ->lockForUpdate()
                ->first();

            // Jika sudah aktif/menunggu, jangan gandakan
            if ($existing && in_array($existing->status, ['menunggu','diterima','berjalan','menunggu_laporan','registered'])) {
                $statusText = [
                    'menunggu' => 'menunggu verifikasi',
                    'diterima' => 'diterima',
                    'berjalan' => 'sedang berjalan',
                    'menunggu_laporan' => 'menunggu laporan',
                    'registered' => 'terdaftar'
                ];
                return back()->with('info', 'Anda sudah terdaftar pada pelatihan ini dengan status: ' . ($statusText[$existing->status] ?? $existing->status));
            }

            $today = Carbon::today()->toDateString();

            // Blokir: ada pelatihan lain yang belum selesai
            $otherTraining = DB::table('peserta_pelatihan as pp')
                ->join('pbj_1_pelatihans as s','s.id','=','pp.pelatihan_id')
                ->where('pp.nip', $pegawai->nip)
                ->whereIn('pp.status', ['diterima','berjalan','menunggu_laporan'])
                ->where('pp.pelatihan_id','!=',$sesi->id)
                ->whereDate('s.tanggal_selesai','>=',$today)
                ->select('s.nama_pelatihan', 's.tanggal_selesai')
                ->first();

            if ($otherTraining) {
                return back()->with('error','Tidak bisa mendaftar: Anda masih mengikuti pelatihan "' . $otherTraining->nama_pelatihan . '" yang belum selesai (sampai ' . Carbon::parse($otherTraining->tanggal_selesai)->format('d/m/Y') . ')');
            }

            // Blokir overlap jadwal
            $sesiStart = $sesi->tanggal_mulai ? Carbon::parse($sesi->tanggal_mulai)->toDateString() : null;
            $sesiEnd   = $sesi->tanggal_selesai ? Carbon::parse($sesi->tanggal_selesai)->toDateString() : null;
            
            if ($sesiStart && $sesiEnd) {
                $overlapTraining = DB::table('peserta_pelatihan as pp')
                    ->join('pbj_1_pelatihans as s','s.id','=','pp.pelatihan_id')
                    ->where('pp.nip',$pegawai->nip)
                    ->whereIn('pp.status',['diterima','berjalan'])
                    ->where('pp.pelatihan_id','!=',$sesi->id)
                    ->whereDate('s.tanggal_mulai','<=',$sesiEnd)
                    ->whereDate('s.tanggal_selesai','>=',$sesiStart)
                    ->select('s.nama_pelatihan', 's.tanggal_mulai', 's.tanggal_selesai')
                    ->first();

                if ($overlapTraining) {
                    return back()->with('error','Jadwal bertumpuk dengan pelatihan "' . $overlapTraining->nama_pelatihan . '" (' . Carbon::parse($overlapTraining->tanggal_mulai)->format('d/m/Y') . ' - ' . Carbon::parse($overlapTraining->tanggal_selesai)->format('d/m/Y') . ')');
                }
            }

            // Cek kuota
            $terpakai = PesertaPelatihan::where('pelatihan_id', $sesi->id)
                ->whereIn('status', ['diterima','berjalan','menunggu_laporan'])
                ->lockForUpdate()
                ->count();

            if (($sesi->kuota ?? 0) > 0 && $terpakai >= $sesi->kuota) {
                return back()->with('error', 'Kuota sudah penuh (' . $terpakai . '/' . $sesi->kuota . ')');
            }

            // Simpan
            if ($existing) {
                $existing->update([
                    'status'     => 'menunggu',
                    'updated_at' => now(),
                ]);
                $message = 'Pengajuan pendaftaran ulang berhasil dikirim. Menunggu verifikasi admin.';
            } else {
                PesertaPelatihan::create([
                    'pelatihan_id' => $sesi->id,
                    'nip'          => $pegawai->nip,
                    'nama'         => $pegawai->nama ?? null,
                    'jabatan'      => $pegawai->jabatan ?? null,
                    'unitkerja'    => optional($pegawai->unitKerja)->unitkerja ?? null,
                    'status'       => 'menunggu',
                ]);
                $message = 'Pengajuan pendaftaran berhasil dikirim. Menunggu verifikasi admin.';
            }

            return redirect()->route('umum.pelatihan.show', $id)
                ->with('success', $message);
        });
    }
}