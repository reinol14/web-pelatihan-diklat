<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\pbj_1_pelatihan;
use App\Models\Katalog_2_masuks;
use App\Models\Provinsi;
use App\Models\Kota;

class PelatihanSessionController extends Controller
{
    public function index(Request $req)
    {
        $today = now()->toDateString();

pbj_1_pelatihan::where('status', 'aktif')
    ->whereDate('tanggal_selesai', '<', $today)
    ->update(['status' => 'tutup']);
        // Eager load relasi untuk tampilkan nama provinsi/kota/katalog di view
        $q = pbj_1_pelatihan::with(['katalog', 'provinsi', 'kota']);

        // Filter opsional
        if ($req->filled('provinsi')) {
            $q->where('provinsi_id', $req->provinsi);
        }
        if ($req->filled('kota')) {
            $q->where('kota_id', $req->kota);
        }
        if ($req->filled('jenis_pelatihan')) {
            $q->where('jenis_pelatihan', $req->jenis_pelatihan);
        }
        // ❌ HAPUS filter metode_pelatihan
        if ($req->filled('penyelenggara')) {
            $q->where('penyelenggara', $req->penyelenggara);
        }

        $sessions = $q
    // Prioritaskan status: aktif dulu, lalu tutup
    ->orderByRaw("CASE WHEN status = 'aktif' THEN 0 ELSE 1 END")
    // Setelah status, baru urutkan berdasarkan tanggal terbaru
    ->orderByDesc('created_at')
    ->paginate(15)
    ->appends($req->query());


        // Data referensi untuk dropdown
        $provinsis = Provinsi::orderBy('nama')->get(['id','nama']);
        $kotas     = Kota::orderBy('nama')->get(['id','nama','provinsi_id']);

        // List distinct (opsional)
        $jenisList         = pbj_1_pelatihan::whereNotNull('jenis_pelatihan')->distinct()->pluck('jenis_pelatihan')->sort()->values();
        // ❌ $metodeList dihapus
        $penyelenggaraList = pbj_1_pelatihan::whereNotNull('penyelenggara')->distinct()->pluck('penyelenggara')->sort()->values();

        return view('Admin.Pelatihan.index', compact(
            'sessions','provinsis','kotas','jenisList','penyelenggaraList'
        ));
    }

    public function create()
    {
        $katalogs  = Katalog_2_masuks::where('status','diterima')->orderBy('nama_pelatihan')->get();
        $provinsis = Provinsi::orderBy('nama')->get(['id','nama']);
        $kotas     = Kota::orderBy('nama')->get(['id','nama','provinsi_id']);

        // list pilihan opsional
        $jenisList         = pbj_1_pelatihan::whereNotNull('jenis_pelatihan')->distinct()->pluck('jenis_pelatihan')->sort()->values();
        // ❌ $metodeList dihapus
        $penyelenggaraList = pbj_1_pelatihan::whereNotNull('penyelenggara')->distinct()->pluck('penyelenggara')->sort()->values();

        return view('Admin.Pelatihan.create', compact(
            'katalogs','provinsis','kotas','jenisList','penyelenggaraList'
        ));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_katalog2'      => 'nullable|exists:katalog_2_masuks,id',
            'nama_pelatihan'   => 'nullable|string|max:255',
            'jenis_pelatihan'  => 'nullable|string|max:255',
            // ❌ 'metode_pelatihan' dihapus
            'penyelenggara'    => 'nullable|string|max:255',
            // ❌ 'metode' dihapus
            'kuota'            => 'required|integer|min:0',
            'deskripsi'      => 'nullable|string|max:5000', // teks deskripsi
            'lokasi'           => 'nullable|string|max:255',
            'provinsi_id'      => 'nullable|exists:provinsis,id',
            'kota_id'          => 'nullable|exists:kotas,id',
            'tanggal_mulai'    => 'nullable|date',
            'tanggal_selesai'  => 'nullable|date|after_or_equal:tanggal_mulai',
            'status'           => 'required|in:aktif,tutup',
        ]);

        // Wajib: pilih katalog ATAU isi nama manual
        if (empty($data['id_katalog2']) && empty($data['nama_pelatihan'])) {
            return back()->withInput()->with('error','Pilih katalog atau isi nama pelatihan secara manual.');
        }

        // (Opsional) Auto-isi dari katalog jika ada id_katalog2 namun field kosong
        if (!empty($data['id_katalog2'])) {
            $k = Katalog_2_masuks::find($data['id_katalog2']);
            if ($k) {
                $data['nama_pelatihan']  = $data['nama_pelatihan']  ?? $k->nama_pelatihan;
                $data['jenis_pelatihan'] = $data['jenis_pelatihan'] ?? $k->jenis_pelatihan;
                // pelaksanaan tetap dari input (deskripsi), tidak diambil dari katalog
            }
        }

        pbj_1_pelatihan::create($data);

        return redirect()->route('Admin.Pelatihan.index')
            ->with('success', 'Sesi pelatihan berhasil dibuat.');
    }

    public function edit($id)
    {
        $session   = pbj_1_pelatihan::with(['katalog','provinsi','kota'])->findOrFail($id);
        $katalogs  = Katalog_2_masuks::where('status','diterima')->orderBy('nama_pelatihan')->get();

        $provinsis = Provinsi::orderBy('nama')->get(['id','nama']);
        $kotas     = Kota::orderBy('nama')->get(['id','nama','provinsi_id']);

        $jenisList         = pbj_1_pelatihan::whereNotNull('jenis_pelatihan')->distinct()->pluck('jenis_pelatihan')->sort()->values();
        // ❌ $metodeList dihapus
        $penyelenggaraList = pbj_1_pelatihan::whereNotNull('penyelenggara')->distinct()->pluck('penyelenggara')->sort()->values();

        return view('Admin.Pelatihan.edit', compact(
            'session','katalogs','provinsis','kotas','jenisList','penyelenggaraList'
        ));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'id_katalog2'      => 'nullable|exists:katalog_2_masuks,id',
            'nama_pelatihan'   => 'nullable|string|max:255',
            'jenis_pelatihan'  => 'nullable|string|max:255',
            'penyelenggara'    => 'nullable|string|max:255',
            'kuota'            => 'required|integer|min:0',
            'deskripsi'      => 'nullable|string|max:5000', // teks deskripsi
            'lokasi'           => 'nullable|string|max:255',
            'provinsi_id'      => 'nullable|exists:provinsis,id',
            'kota_id'          => 'nullable|exists:kotas,id',
            'tanggal_mulai'    => 'nullable|date',
            'tanggal_selesai'  => 'nullable|date|after_or_equal:tanggal_mulai',
            'status'           => 'required|in:aktif,tutup',
        ]);

        $session = pbj_1_pelatihan::findOrFail($id);

        // (Opsional) Auto-isi dari katalog saat update juga
        if (!empty($data['id_katalog2'])) {
            $k = Katalog_2_masuks::find($data['id_katalog2']);
            if ($k) {
                $data['nama_pelatihan']  = $data['nama_pelatihan']  ?? $k->nama_pelatihan;
                $data['jenis_pelatihan'] = $data['jenis_pelatihan'] ?? $k->jenis_pelatihan;
            }
        }

        $session->update($data);

        return redirect()->route('Admin.Pelatihan.index')
            ->with('success', 'Sesi pelatihan diperbarui.');
    }

    public function destroy($id)
    {
        $session = pbj_1_pelatihan::findOrFail($id);
        $session->delete();

        return back()->with('success', 'Sesi pelatihan dihapus.');
    }
}
