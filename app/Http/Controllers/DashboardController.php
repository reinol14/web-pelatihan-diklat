<?php


namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
class DashboardController extends Controller
{
public function index()
{

    $admin = Auth::user();
    $isSuperAdmin = $admin->id == 1;

    $today    = Carbon::today();
    $in30Days = $today->copy()->addDays(30);
    $hPlus7   = $today->copy()->addDays(7);

    /* =======================
     | KARTU DATA PEGAWAI
     ======================= */
    $pegawaiCount = DB::table('ref_pegawais')
        ->when(!$isSuperAdmin, fn ($q) =>
            $q->where('kode_unitkerja', $admin->kode_unitkerja)
        )
        ->count();

    /* =======================
     | PELATIHAN
     ======================= */
    $pelatihanTotal = DB::table('pbj_1_pelatihans')->count();

    $pelatihanDapatDiakses = DB::table('pbj_1_pelatihans')
        ->where('status','aktif')
        ->whereDate('tanggal_mulai','>', $hPlus7)
        ->count();

    $pelatihanTertutup = max(0, $pelatihanTotal - $pelatihanDapatDiakses);

    /* =======================
     | PROFILE CHANGE (FIXED)
     ======================= */
    $profilePending = DB::table('pegawai_profile_changes as c')
        ->join('ref_pegawais as p','p.id','=','c.pegawai_id')
        ->when(!$isSuperAdmin, fn ($q) =>
            $q->where('p.kode_unitkerja', $admin->kode_unitkerja)
        )
        ->where('c.status','pending')
        ->count();

    /* =======================
     | REGISTRASI PEGAWAI
     ======================= */
    $regPending = DB::table('pegawai_registrations as r')
        ->join('ref_pegawais as p','p.nip','=','r.nip')
        ->when(!$isSuperAdmin, fn ($q) =>
            $q->where('p.kode_unitkerja', $admin->kode_unitkerja)
        )
        ->where('r.status','pending')
        ->count();

    /* =======================
     | DEADLINE 30 HARI
     ======================= */
    $deadlineSoon = DB::table('pbj_1_pelatihans')
        ->select('id','nama_pelatihan','tanggal_mulai','status')
        ->whereBetween('tanggal_mulai', [$today, $in30Days])
        ->orderBy('tanggal_mulai')
        ->limit(10)
        ->get();

    return view('dashboard', compact(
        'pegawaiCount',
        'pelatihanTotal','pelatihanDapatDiakses','pelatihanTertutup',
        'profilePending','regPending',
        'deadlineSoon'
    ));
}



}
