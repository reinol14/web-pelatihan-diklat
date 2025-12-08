<?php

namespace App\Http\Controllers\Admin\Pegawai;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ref_pegawais;
use App\Models\ref_unitkerjas;

class PegawaiRegistrationApprovalController extends Controller
{

public function index(Request $request)
{
    $admin     = Auth::guard('web')->user();               // guard admin
    $search    = trim((string) $request->input('q'));
    $status    = $request->string('status')->toString();   // pending|approved|rejected
    $unitFilter= $request->input('unit');                  // id_unitkerja
    $dateFrom  = $request->input('date_from');             // YYYY-MM-DD
    $dateTo    = $request->input('date_to');               // YYYY-MM-DD

    // Kalau admin level 2, paksa scope ke unit kerja admin & abaikan unitFilter
    $isUnitScopedAdmin = $admin && (int) $admin->is_admin === 2;

    // ---- Base (tanpa select & tanpa status/search) agar bisa direuse utk counts ----
    $base = DB::table('pegawai_registrations as pr')
        ->leftJoin('ref_unitkerjas as uk', 'pr.kode_unitkerja', '=', 'uk.id_unitkerja');

    // Scope unit kerja untuk admin level 2
    if ($isUnitScopedAdmin) {
        $base->where('pr.kode_unitkerja', '=', $admin->kode_unitkerja);
    } else {
        // Jika admin penuh & pilih unit pada filter
        if (!empty($unitFilter)) {
            $base->where('pr.kode_unitkerja', '=', $unitFilter);
        }
    }

    // Filter tanggal pengajuan (created_at)
    if (!empty($dateFrom)) {
        $base->whereDate('pr.created_at', '>=', $dateFrom);
    }
    if (!empty($dateTo)) {
        $base->whereDate('pr.created_at', '<=', $dateTo);
    }

    // ---- Counts untuk tab (pakai base scope yang sama, tanpa search) ----
    $counts = [
        'pending'  => (clone $base)->where('pr.status', 'pending')->count(),
        'approved' => (clone $base)->where('pr.status', 'approved')->count(),
        'rejected' => (clone $base)->where('pr.status', 'rejected')->count(),
    ];

    // ---- Query utama (turunan dari base) ----
    $query = (clone $base)
        ->select(
            'pr.id',
            'pr.nip',
            'pr.nama',
            'pr.email',
            'pr.kode_unitkerja',
            'pr.status',
            'pr.created_at',
            'uk.unitkerja'
        )
        // filter status (opsional)
        ->when($status !== '', fn($q) => $q->where('pr.status', $status))
        // pencarian global (nama/nip/email)
        ->when($search !== '', function ($q) use ($search) {
            $q->where(function ($qq) use ($search) {
                $qq->where('pr.nama', 'like', "%{$search}%")
                   ->orWhere('pr.nip', 'like', "%{$search}%")
                   ->orWhere('pr.email', 'like', "%{$search}%");
            });
        })
        // urutan: pending dulu, lalu approved, lalu rejected; lalu terbaru di atas
        ->orderByRaw("
            CASE 
                WHEN pr.status = 'pending' THEN 1 
                WHEN pr.status = 'approved' THEN 2 
                ELSE 3 
            END
        ")
        ->orderByDesc('pr.created_at');

    $registrations = $query->paginate(20)->withQueryString();

    // Data dropdown unit (untuk admin penuh). Admin level 2: cukup kirim 1 unit miliknya.
    if ($isUnitScopedAdmin) {
        $units = DB::table('ref_unitkerjas')
            ->where('id_unitkerja', $admin->kode_unitkerja)
            ->get(['id_unitkerja','unitkerja']);
    } else {
        $units = DB::table('ref_unitkerjas')
            ->orderBy('unitkerja')
            ->get(['id_unitkerja','unitkerja']);
    }

    // daftar status utk select
    $statuses = ['pending' => 'Pending', 'approved' => 'Approved', 'rejected' => 'Rejected'];

    return view('Admin.Pegawai.PegawaiApproval.index', [
        'registrations' => $registrations,
        'search'        => $search,
        'status'        => $status,
        'unitFilter'    => $isUnitScopedAdmin ? ($admin->kode_unitkerja ?? null) : $unitFilter,
        'dateFrom'      => $dateFrom,
        'dateTo'        => $dateTo,
        'units'         => $units,
        'statuses'      => $statuses,
        'counts'        => $counts,
        'isUnitScopedAdmin' => $isUnitScopedAdmin,
    ]);
}



public function approve($id)
{
    DB::transaction(function () use ($id) {
        // Ambil data registrasi
        $registration = DB::table('pegawai_registrations')->where('id', $id)->first();

        if (!$registration) {
            throw new \Exception("Registrasi tidak ditemukan");
        }

        // Insert ke ref_pegawais
        DB::table('ref_pegawais')->insert([
            'nip'             => $registration->nip,
            'nama'            => $registration->nama,
            'tempat_lahir'    => $registration->tempat_lahir,
            'tanggal_lahir'   => $registration->tanggal_lahir,
            'pangkat'         => $registration->pangkat,
            'golongan'        => $registration->golongan,
            'jabatan'         => $registration->jabatan,
            'jenis_asn'       => $registration->jenis_asn,
            'kategori_jabatanasn' => $registration->kategori_jabatanasn,
            'kode_unitkerja'  => $registration->kode_unitkerja,
            'email'           => $registration->email,
            'no_hp'           => $registration->no_hp,
            'alamat'          => $registration->alamat,
            'tmt'             => $registration->tmt,
            'foto'            => $registration->foto ?? null,
            'id_atasan'       => $registration->id_atasan ?? null,
            'created_at'      => now(),
            'updated_at'      => now(),
        ]);

        // Update status registrasi
        DB::table('pegawai_registrations')
            ->where('id', $id)
            ->update(['status' => 'approved', 'updated_at' => now()]);
    });

    return back()->with('success', 'Registrasi berhasil disetujui dan dimasukkan ke data pegawai.');
}


    public function reject($id)
    {
        DB::table('pegawai_registrations')
            ->where('id', $id)
            ->update(['status' => 'rejected', 'updated_at' => now()]);

        return back()->with('error', 'Registrasi ditolak.');
    }

    public function show($id)
{
    $registration = DB::table('pegawai_registrations as pr')
        ->leftJoin('ref_unitkerjas as uk', 'pr.kode_unitkerja', '=', 'uk.id_unitkerja')
        ->select(
            'pr.*',
            'uk.unitkerja'
        )
        ->where('pr.id', $id)
        ->first();

    if (!$registration) {
        return redirect()->route('Admin.Pegawai.PegawaiApproval.index')
                         ->with('error', 'Data registrasi tidak ditemukan.');
    }

    return view('Admin.Pegawai.PegawaiApproval.show', compact('registration'));
}

}
