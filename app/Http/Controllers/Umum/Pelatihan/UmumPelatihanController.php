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

        // Filter Bulan/Tahun
        $bulan = (int) $request->input('bulan'); // 1..12
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

        // Subquery: hitung kursi terpakai (yang benar2 "makan kursi")
        // dan ikut sertakan hitungan legacy "registered" untuk fallback di Blade
        $subCounts = DB::table('peserta_pelatihan')
            ->select('pelatihan_id',
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
                's.status',        // 'aktif' atau nonaktif lain
                's.kuota',
                's.lokasi',
                DB::raw('NULL as file_pelatihan'),
                DB::raw('COALESCE(pp.peserta_terpakai,0) AS peserta_terpakai'),
                DB::raw('COALESCE(pp.peserta_registered,0) AS peserta_registered'),
            ])
            ->when($q,         fn($qr) => $qr->where('s.nama_pelatihan', 'like', "%{$q}%"))
            ->when($jenis,     fn($qr) => $qr->where('s.jenis_pelatihan', $jenis))
            ->when($provinsi,  fn($qr) => $qr->where('s.provinsi_id', $provinsi))
            ->when($kota,      fn($qr) => $qr->where('s.kota_id', $kota))
            ->when($startDate && $endDate, function ($qr) use ($startDate, $endDate) {
                // filter irisan tanggal
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
            ->whereNotNull('jenis_pelatihan')->where('jenis_pelatihan','!=','')
            ->distinct()->orderBy('jenis_pelatihan')->pluck('jenis_pelatihan');

        $bulanList = [
            1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',
            7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember'
        ];
        $years = range(Carbon::today()->year - 2, Carbon::today()->year + 2);

        // Aturan "hanya 1 pelatihan berjalan" (blokir personal)
        $hasOngoing = false;
        $myRegisteredIds = [];
        if (Auth::guard('pegawais')->check()) {
            $nip   = Auth::guard('pegawais')->user()->nip;
            $today = Carbon::today()->toDateString();

            // id sesi yang sudah didaftarkan user (semua status kemajuan yang relevan)
            $myRegisteredIds = DB::table('peserta_pelatihan')
                ->where('nip', $nip)
                ->whereIn('status', ['menunggu','diterima','berjalan','menunggu_laporan','registered']) // include legacy
                ->pluck('pelatihan_id')->all();

            // apakah ada pelatihan lain yang sedang berlangsung utk user (hanya yang sudah diterima/berjalan/menunggu_laporan atau legacy registered)
            $hasOngoing = DB::table('peserta_pelatihan as pp')
                ->join('pbj_1_pelatihans as s','s.id','=','pp.pelatihan_id')
                ->where('pp.nip',$nip)
                ->whereIn('pp.status',['diterima','berjalan','menunggu_laporan','registered']) // legacy support
                ->whereDate('s.tanggal_mulai','<=',$today)
                ->whereDate('s.tanggal_selesai','>=',$today)
                
                ->exists();
                
        }

        return view('MenuUmum.index', compact(
            'trainings','q','provinsis','kotas','jenisList',
            'hasOngoing','myRegisteredIds','bulanList','years'
            
        ));
    }

    // DETAIL
    public function show($id)
    {
        $session = DB::table('pbj_1_pelatihans')->where('id', $id)->first();
        if (!$session) abort(404);

        $provinsis = DB::table('provinsis')->orderBy('nama')->get(['id','nama']);
        $kotas     = DB::table('kotas')->orderBy('nama')->get(['id','provinsi_id','nama']);

        // kursi terpakai (pakai status makan kursi)
        $pesertaTerpakai = DB::table('peserta_pelatihan')
            ->where('pelatihan_id', $id)
            ->whereIn('status', ['diterima','berjalan','menunggu_laporan'])
            ->count();

        return view('MenuUmum.show', [
            'session'           => $session,
            'pesertaRegistered' => $pesertaTerpakai, // var lama di blade show, isinya sekarang terpakai
            'provinsis'         => $provinsis,
            'kotas'             => $kotas,
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

            if (!$sesi)         return back()->with('error', 'Pelatihan tidak tersedia.');
            if (($sesi->status ?? '') !== 'aktif') {
                return back()->with('error', 'Pendaftaran ditutup.');
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
                return back()->with('info', 'Anda sudah terdaftar pada sesi ini.');
            }

            $today = Carbon::today()->toDateString();

            // Blokir: ada pelatihan lain yang belum selesai (yang makan kursi)
            $hasOtherUnfinished = DB::table('peserta_pelatihan as pp')
                ->join('pbj_1_pelatihans as s','s.id','=','pp.pelatihan_id')
                ->where('pp.nip', $pegawai->nip)
                ->whereIn('pp.status', ['diterima','berjalan','menunggu_laporan']) // hanya yang sudah diterima/berjalan
                ->where('pp.pelatihan_id','!=',$sesi->id)
                ->whereDate('s.tanggal_selesai','>=',$today)
                ->exists();
            if ($hasOtherUnfinished) {
                return back()->with('error','Tidak bisa mendaftar: Anda masih mengikuti pelatihan lain yang belum selesai.');
            }

            // Blokir overlap jadwal (hanya yang sudah diterima/berjalan)
            $sesiStart = $sesi->tanggal_mulai ? Carbon::parse($sesi->tanggal_mulai)->toDateString() : null;
            $sesiEnd   = $sesi->tanggal_selesai ? Carbon::parse($sesi->tanggal_selesai)->toDateString() : null;
            if ($sesiStart && $sesiEnd) {
                $hasOverlap = DB::table('peserta_pelatihan as pp')
                    ->join('pbj_1_pelatihans as s','s.id','=','pp.pelatihan_id')
                    ->where('pp.nip',$pegawai->nip)
                    ->whereIn('pp.status',['diterima','berjalan'])  // yang pasti aktif
                    ->where('pp.pelatihan_id','!=',$sesi->id)
                    ->whereDate('s.tanggal_mulai','<=',$sesiEnd)
                    ->whereDate('s.tanggal_selesai','>=',$sesiStart)
                    ->exists();
                if ($hasOverlap) {
                    return back()->with('error','Jadwal bertumpuk dengan pelatihan lain yang sudah Anda ikuti.');
                }
            }

            // Cek kuota pakai status makan kursi
            $terpakai = PesertaPelatihan::where('pelatihan_id', $sesi->id)
                ->whereIn('status', ['diterima','berjalan','menunggu_laporan'])
                ->lockForUpdate()
                ->count();
            if (($sesi->kuota ?? 0) > 0 && $terpakai >= $sesi->kuota) {
                return back()->with('error', 'Kuota sudah penuh.');
            }

            // Simpan: status awal "menunggu" (untuk diverifikasi admin)
            if ($existing) {
                // Jika sebelumnya ditolak/dibatalkan, izinkan ajukan ulang
                $existing->update([
                    'status'     => 'menunggu',
                    'updated_at' => now(),
                ]);
            } else {
                PesertaPelatihan::create([
                    'pelatihan_id' => $sesi->id,
                    'nip'          => $pegawai->nip,
                    'nama'         => $pegawai->nama ?? null,
                    'jabatan'      => $pegawai->jabatan ?? null,
                    'unitkerja'    => optional($pegawai->unitKerja)->unitkerja ?? null,
                    'status'       => 'menunggu', // <- kunci ke alur verifikasi admin
                ]);
            }

            return back()->with('success', 'Pengajuan pendaftaran dikirim. Menunggu verifikasi admin.');
        });
    }

}
