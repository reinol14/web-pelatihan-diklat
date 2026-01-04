<?php

namespace App\Http\Controllers\Umum\Pegawai;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

class LaporanPelatihanController extends Controller
{
    public function index(Request $request)
    {
        $pegawai = Auth::guard('pegawais')->user();
        if (!$pegawai) abort(403);

        $nip   = (string) $pegawai->nip;
        $today = Carbon::today()->toDateString();

        // Subquery: ambil ID laporan terakhir per pelatihan untuk NIP ini
        $lt = DB::table('laporan_pelatihan')
            ->select('pelatihan_id', 'nip', DB::raw('MAX(id) AS last_id'))
            ->where('nip', $nip)
            ->groupBy('pelatihan_id', 'nip');

        $items = DB::table('peserta_pelatihan as pp')
        
            ->join('pbj_1_pelatihans as s', 's.id', '=', 'pp.pelatihan_id')
            // join ke subquery (samakan kolasi pada join NIP untuk hindari mix-collation)
            ->leftJoinSub($lt, 'lt', function ($join) {
                $join->on('lt.pelatihan_id', '=', 'pp.pelatihan_id')
                     ->whereRaw("lt.nip COLLATE utf8mb4_unicode_ci = pp.nip COLLATE utf8mb4_unicode_ci");
            })
            ->leftJoin('laporan_pelatihan as l', 'l.id', '=', 'lt.last_id')
            ->where('pp.nip', $nip)
            ->whereIn('pp.status', ['menunggu', 'diterima', 'berjalan', 'menunggu_laporan'])
            ->select([
                's.id',
                's.nama_pelatihan',
                's.jenis_pelatihan',
                's.tanggal_mulai',
                's.tanggal_selesai',
                // status_laporan: belum/pending/approved/rejected
                DB::raw("CASE WHEN l.id IS NULL THEN 'belum' ELSE l.status END AS status_laporan"),
                DB::raw("l.id AS laporan_id"),
            ])
            ->orderByRaw("CASE WHEN pp.status = 'menunggu_laporan' THEN 0 ELSE 1 END")
            ->orderBy('s.tanggal_selesai')
            ->paginate(10)
            ->withQueryString();

        return view('Pegawai.Laporan.index', compact('items', 'today'));
    }

    public function create($id)
    {
        $pegawai = Auth::guard('pegawais')->user();
        if (!$pegawai) abort(403);

        $nip   = (string) $pegawai->nip;
        $today = Carbon::today()->toDateString();

        $sesi = DB::table('pbj_1_pelatihans')->where('id', $id)->first();
        if (!$sesi) abort(404);

        $isRegistered = DB::table('peserta_pelatihan')
            ->where('pelatihan_id', $id)
            ->where('nip', $nip)
            ->where('status', 'menunggu_laporan')
            ->exists();

        if (!$isRegistered) {
            return back()->with('error', 'Anda tidak terdaftar pada pelatihan ini.');
        }

        if ($sesi->tanggal_selesai && Carbon::parse($sesi->tanggal_selesai)->toDateString() > $today) {
            return back()->with('error', 'Laporan hanya bisa dikirim setelah pelatihan selesai.');
        }

        // Cek laporan terakhir untuk sesi ini
        $last = DB::table('laporan_pelatihan')
            ->where('pelatihan_id', $id)
            ->where('nip', $nip)
            ->orderByDesc('id')
            ->first();

        if ($last) {
            if ($last->status === 'rejected') {
                return redirect()
                    ->route('Pegawai.laporan.edit', $last->id)
                    ->with('info', 'Perbaiki laporan yang ditolak lalu kirim ulang.');
            }
            if ($last->status === 'pending') {
                return redirect()->route('Pegawai.laporan.index')->with('info', 'Laporan sudah diajukan dan menunggu verifikasi.');
            }
            if ($last->status === 'approved') {
                return redirect()->route('Pegawai.laporan.index')->with('info', 'Laporan sudah disetujui.');
            }
        }

        return view('Pegawai.laporan.create', compact('sesi'));
    }

public function store(Request $request, $id)
{
    $pegawai = Auth::guard('pegawais')->user();
    if (!$pegawai) abort(403);

    $nip   = (string) $pegawai->nip;
    $today = \Illuminate\Support\Carbon::today()->toDateString();

    $sesi = DB::table('pbj_1_pelatihans')->where('id', $id)->first();
    if (!$sesi) abort(404);

    // harus status 'menunggu_laporan'
    $isRegistered = DB::table('peserta_pelatihan')
        ->where('pelatihan_id', $id)
        ->where('nip', $nip)
        ->where('status', 'menunggu_laporan')
        ->exists();
    if (!$isRegistered) {
        return back()->with('error', 'Anda tidak terdaftar pada pelatihan ini.');
    }

    // pelatihan harus sudah selesai
    if ($sesi->tanggal_selesai && \Illuminate\Support\Carbon::parse($sesi->tanggal_selesai)->toDateString() > $today) {
        return back()->with('error', 'Laporan hanya bisa dikirim setelah pelatihan selesai.');
    }

    // validasi: sertifikat wajib
    $data = $request->validate([
        'judul'      => 'required|string|max:150',
        'ringkasan'  => 'required|string|max:5000',
        'file'       => 'nullable|file|mimes:pdf,doc,docx|max:5120',
        'sertifikat' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
    ]);

    // kalau sudah ada record laporan sebelumnya → arahkan ke create() (satu pintu)
    $last = DB::table('laporan_pelatihan')
        ->where('pelatihan_id', $id)
        ->where('nip', $nip)
        ->orderByDesc('id')
        ->first();
    if ($last) {
        return redirect()->route('Pegawai.laporan.create', $id);
    }

    // simpan lampiran laporan (opsional) ke kolom file_path
    $lampiranPath = null;
    if ($request->hasFile('file')) {
        $fname        = 'laporan_' . $id . '_' . $nip . '_' . \Illuminate\Support\Str::random(6) . '.' .
                         $request->file('file')->getClientOriginalExtension();
        $stored       = $request->file('file')->storeAs('public/laporan_pelatihan/lampiran', $fname);
        $lampiranPath = $stored ? str_replace('public/', 'storage/', $stored) : null;
    }

    // simpan sertifikat (WAJIB) ke kolom `sertifikat`
    $sertifikatPath = null;
    if ($request->hasFile('sertifikat')) {
        $cname          = 'sertifikat_' . $id . '_' . $nip . '_' . \Illuminate\Support\Str::random(6) . '.' .
                           $request->file('sertifikat')->getClientOriginalExtension();
        $storedCert     = $request->file('sertifikat')->storeAs('public/laporan_pelatihan/sertifikat', $cname);
        $sertifikatPath = $storedCert ? str_replace('public/', 'storage/', $storedCert) : null;
    }

    DB::table('laporan_pelatihan')->insert([
        'pelatihan_id' => $id,
        'nip'          => $nip,
        'judul'        => $data['judul'],
        'ringkasan'    => $data['ringkasan'],
        'file_path'    => $lampiranPath,   // tetap pakai file_path utk lampiran
        'sertifikat'   => $sertifikatPath, // <<— kolom sesuai permintaan
        'status'       => 'pending',
        'created_at'   => now(),
        'updated_at'   => now(),
    ]);

    return redirect()->route('Pegawai.laporan.index')
        ->with('success', 'Laporan & sertifikat berhasil dikirim. Menunggu verifikasi.');
}


    public function edit($laporanId)
    {
        $pegawai = Auth::guard('pegawais')->user();
        if (!$pegawai) abort(403);

        $laporan = DB::table('laporan_pelatihan')->where('id', $laporanId)->first();
        if (!$laporan || $laporan->nip !== (string)$pegawai->nip) abort(404);

        $sesi = DB::table('pbj_1_pelatihans')->where('id', $laporan->pelatihan_id)->first();

        return view('Pegawai.laporan.edit', [
            'sesi'    => $sesi,
            'laporan' => $laporan,
        ]);
    }

public function update(Request $request, $laporanId)
{
    $pegawai = Auth::guard('pegawais')->user();
    if (!$pegawai) abort(403);

    // Validasi: sertifikat boleh diganti saat perbaikan (opsional)
    $data = $request->validate([
        'judul'      => 'required|string|max:150',
        'ringkasan'  => 'required|string|max:5000',
        'file'       => 'nullable|file|mimes:pdf,doc,docx|max:5120',
        'sertifikat' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
    ]);

    $lap = DB::table('laporan_pelatihan')->where('id', $laporanId)->first();
    if (!$lap || $lap->nip !== (string) $pegawai->nip) abort(404);

    // Tidak boleh perbaiki jika sudah disetujui
    if ($lap->status === 'approved') {
        return back()->with('info', 'Laporan sudah disetujui dan tidak dapat diperbarui.');
    }

    // Simpan lampiran laporan (opsional) → kolom file_path
    $lampiranPath = $lap->file_path;
    if ($request->hasFile('file')) {
        $fname  = 'laporan_' . $lap->pelatihan_id . '_' . $lap->nip . '_' . Str::random(6) . '.' .
                  $request->file('file')->getClientOriginalExtension();
        $stored = $request->file('file')->storeAs('public/laporan_pelatihan/lampiran', $fname);
        $lampiranPath = $stored ? str_replace('public/', 'storage/', $stored) : $lampiranPath;
    }

    // Simpan sertifikat (opsional saat perbaikan) → kolom sertifikat
    $sertifikatPath = $lap->sertifikat;
    if ($request->hasFile('sertifikat')) {
        $cname  = 'sertifikat_' . $lap->pelatihan_id . '_' . $lap->nip . '_' . Str::random(6) . '.' .
                  $request->file('sertifikat')->getClientOriginalExtension();
        $cstore = $request->file('sertifikat')->storeAs('public/laporan_pelatihan/sertifikat', $cname);
        $sertifikatPath = $cstore ? str_replace('public/', 'storage/', $cstore) : $sertifikatPath;
    }

    DB::table('laporan_pelatihan')->where('id', $laporanId)->update([
        'judul'       => $data['judul'],
        'ringkasan'   => $data['ringkasan'],
        'file_path'   => $lampiranPath,    // lampiran laporan
        'sertifikat'  => $sertifikatPath,  // path sertifikat
        'status'      => 'pending',        // perbaikan → kembali pending
        'updated_at'  => now(),
    ]);

    return redirect()->route('Pegawai.laporan.index')
        ->with('success', 'Perbaikan terkirim. Menunggu verifikasi.');
}

}
