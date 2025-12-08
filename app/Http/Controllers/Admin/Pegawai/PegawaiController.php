<?php

namespace App\Http\Controllers\Admin\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\ref_pegawais;
use App\Models\ref_unitkerjas;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Providers\AuthServiceProvider;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests; // Add this line

class PegawaiController extends Controller
{
    use AuthorizesRequests; // Add this line
    // Menampilkan daftar pegawai
    public function index()
    {
        $query = ref_pegawais::with('unitKerja');
        
        // Jika admin biasa (is_admin == 2), filter berdasarkan unit kerja
        if (Auth::user()->is_admin == 2) {
            $query->where('kode_unitkerja', Auth::user()->kode_unitkerja);
        }
    
        // Mengambil data pegawai sesuai query
        $pegawais = $query->get();
    
        // Menampilkan halaman index dengan data pegawai
        return view('Admin.Pegawai.index', compact('pegawais'));
    }
    
    // Menampilkan detail pegawai berdasarkan ID
    public function show($id)
    {
        $pegawai = ref_pegawais::findOrFail($id); // Mengambil data berdasarkan ID
        return view('Admin.Pegawai.show', compact('pegawai'));
    }
    
    public function edit($id)
{
    $pegawai = ref_pegawais::findOrFail($id);

    // Jika superadmin, lewati policy (sementara)
    if (Auth::user()->is_admin != 1) {
        $this->authorize('update', $pegawai);
    }

    $unitKerjaGrouped = ref_unitkerjas::all()->groupBy('unitkerja');
    return view('Admin.Pegawai.edit', compact('pegawai', 'unitKerjaGrouped'));
}

public function update(Request $request, $id)
{
    $pegawai = ref_pegawais::findOrFail($id);

    // Jika superadmin, lewati policy (sementara)
    if (Auth::user()->is_admin != 1) {
        $this->authorize('update', $pegawai);
    }

    $validated = $request->validate([
        'nip'                 => 'required|string|max:255',
        'nama'                => 'required|string|max:255',
        'pangkat'             => 'required|string',
        'golongan'            => 'required|string',
        'jabatan'             => 'required|string',
        'jenis_asn'           => 'required|string',
        'kategori_jabatanasn' => 'required|string',
        'kode_unitkerja'      => 'required|exists:ref_unitkerjas,kode_unitkerja',
        'email'               => 'required|email',
        'no_hp'               => 'required|string',
        'alamat'              => 'required|string',
        'tmt'                 => 'required|date',
        'foto'                => 'nullable|image|max:2048',
    ]);

    $pegawai->fill($validated);

    if ($request->hasFile('foto')) {
        $path = $request->file('foto')->store('pegawai', 'public');
        $pegawai->foto = $path;
    }

    $pegawai->save();

    return redirect()->route('Admin.Pegawai.index')->with('success', 'Data pegawai berhasil diperbarui.');
}

public function destroy($id)
{
    $pegawai = ref_pegawais::findOrFail($id);

    if (Auth::user()->is_admin != 1) {
        $this->authorize('update', $pegawai);
    }

    $pegawai->delete();
    return redirect()->route('Admin.Pegawai.index')->with('success', 'Data pegawai berhasil dihapus.');
}

}
