<?php


namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
public function index()
{
    $today     = Carbon::today();
    $in30Days  = $today->copy()->addDays(30);
    $hPlus7    = $today->copy()->addDays(7); // pendaftaran tutup H-7

    // KARTU
    $pegawaiCount = DB::table('ref_pegawais')->count();

    $pelatihanTotal = DB::table('pbj_1_pelatihans')->count();
    $pelatihanDapatDiakses = DB::table('pbj_1_pelatihans')
        ->where('status','aktif')
        ->whereDate('tanggal_mulai','>', $hPlus7)
        ->count();
    $pelatihanTertutup = max(0, $pelatihanTotal - $pelatihanDapatDiakses);

    // TODO: ganti nama tabel perubahan profil sesuai skema kamu
    $profilePending = DB::table('pegawai_profile_changes')->where('status','pending')->count();

    $regPending = DB::table('pegawai_registrations')->where('status','pending')->count();

    // Kalender / deadline 30 hari
    $deadlineSoon = DB::table('pbj_1_pelatihans')
        ->select('id','nama_pelatihan','tanggal_mulai','status')
        ->whereBetween('tanggal_mulai', [$today->toDateString(), $in30Days->toDateString()])
        ->orderBy('tanggal_mulai')
        ->limit(10)
        ->get();

    // Kuota & kepadatan (Top 5)
    $topPadat = DB::table('pbj_1_pelatihans as s')
        ->leftJoin(
            DB::raw("(SELECT pelatihan_id, COUNT(*) AS cnt
                      FROM peserta_pelatihan
                      WHERE status='registered'
                      GROUP BY pelatihan_id) pp"),
            'pp.pelatihan_id','=','s.id'
        )
        ->select([
            's.id','s.nama_pelatihan','s.kuota',
            DB::raw('COALESCE(pp.cnt,0) as terpakai'),
            DB::raw('CASE WHEN s.kuota>0 THEN (COALESCE(pp.cnt,0)/s.kuota) ELSE 0 END as ratio'),
        ])
        ->orderByDesc('ratio')->orderBy('s.nama_pelatihan')
        ->limit(5)
        ->get();

    // Ringkasan laporan
    $lapSumRaw = DB::table('laporan_pelatihan')
        ->select('status', DB::raw('COUNT(*) c'))
        ->groupBy('status')
        ->pluck('c','status')->all();
    $lapSum = [
        'pending'  => (int)($lapSumRaw['pending']  ?? 0),
        'approved' => (int)($lapSumRaw['approved'] ?? 0),
        'rejected' => (int)($lapSumRaw['rejected'] ?? 0),
    ];

    $lapAging = DB::table('laporan_pelatihan as l')
        ->join('pbj_1_pelatihans as s','s.id','=','l.pelatihan_id')
        ->leftJoin('ref_pegawais as p','p.nip','=','l.nip')
        ->where('l.status','pending')
        ->select('l.id','l.created_at','s.nama_pelatihan as pelatihan_nama','p.nama as pegawai_nama')
        ->orderBy('l.created_at') // tertua dulu
        ->limit(5)
        ->get();

    // Chart 8 minggu (registrasi akun, pendaftaran, laporan)
    $labels=[]; $registrations=[]; $enrollments=[]; $reports=[];
    $startWeek = Carbon::now()->startOfWeek(); // senin
    for ($i=7; $i>=0; $i--) {
        $start = $startWeek->copy()->subWeeks($i);
        $end   = $start->copy()->endOfWeek();
        $labels[] = $i===0 ? 'Minggu ini' : 'M-'.$i;

        $registrations[] = DB::table('pegawai_registrations')->whereBetween('created_at', [$start,$end])->count();
        $enrollments[]   = DB::table('peserta_pelatihan')->where('status','registered')->whereBetween('created_at', [$start,$end])->count();
        $reports[]       = DB::table('laporan_pelatihan')->whereBetween('created_at', [$start,$end])->count();
    }
    $chartWeekly = compact('labels','registrations','enrollments','reports');

    // Distribusi metode
    $metodeRaw = DB::table('pbj_1_pelatihans')
        ->select('metode_pelatihan', DB::raw('COUNT(*) c'))
        ->groupBy('metode_pelatihan')
        ->pluck('c','metode_pelatihan')->all();
    $metodeDistribusi = [
        'Online'  => (int)($metodeRaw['Online']  ?? ($metodeRaw['online']  ?? 0)),
        'Offline' => (int)($metodeRaw['Offline'] ?? ($metodeRaw['offline'] ?? 0)),
        'Hybrid'  => (int)($metodeRaw['Hybrid']  ?? ($metodeRaw['hybrid']  ?? 0)),
    ];

    // Kepatuhan laporan
    $compApproved = DB::table('laporan_pelatihan')->where('status','approved')->count();
    $compTotal    = $compApproved + DB::table('peserta_pelatihan')->where('status','menunggu_laporan')->count();

    // Akun pending untuk tabel bawah
    $pendingRegs = DB::table('pegawai_registrations')->where('status','pending')->latest()->limit(8)->get();

    // TODO: ganti nama view ke blade dashboard-mu
    return view('dashboard', compact(
        'pegawaiCount',
        'pelatihanTotal','pelatihanDapatDiakses','pelatihanTertutup',
        'profilePending','regPending',
        'deadlineSoon','topPadat','lapSum','lapAging',
        'chartWeekly','metodeDistribusi',
        'compApproved','compTotal',
        'pendingRegs'
    ));
}

}
