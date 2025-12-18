<?php

namespace App\Http\Controllers\Umum\Pegawai;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PegawaiRegistration;

class PegawaiSelfRegisterController extends Controller
{
public function showForm()
{
    // ambil referensi unit kerja dari tabel ref_unitkerjas
    $unitKerjas = \DB::table('ref_unitkerjas')
        ->orderBy('unitkerja')
        ->get(['kode_unitkerja','unitkerja']);

    return view('Pegawai.registrasi', compact('unitKerjas'));
}


    public function submit(Request $r)
{
    // Validasi data
    $data = $r->validate([
        'nama'                  => 'required|string|max:150',
        'nip'                   => 'required|string|max:30|unique:ref_pegawais,nip|unique:pegawai_registrations,nip',
        'email'                 => 'required|email:rfc,dns|unique:ref_pegawais,email|unique:pegawai_registrations,email',
        'no_hp'                 => 'nullable|string|max:30|unique:ref_pegawais,no_hp|unique:pegawai_registrations,no_hp',
        'tempat_lahir'          => 'nullable|string|max:150',
        'tanggal_lahir'         => 'nullable|date',
        'pangkat'               => 'nullable|string|max:150',
        'golongan'              => 'nullable|string|max:50',
        'jabatan'               => 'nullable|string|max:150',
        'jenis_asn'             => 'nullable|string|max:100',
        'kategori_jabatanasn'   => 'nullable|string|max:150',
        'kode_unitkerja'        => 'required|integer',
        'alamat'                => 'nullable|string|max:255',
        'tmt'                   => 'nullable|date',
        'foto'                  => 'nullable|image|max:2048',
    ]);

    // Simpan foto jika ada
    if ($r->hasFile('foto')) {
        $data['foto'] = $r->file('foto')->store('registrations/foto', 'public');
    }

    // Periksa NIP, email, dan nomor HP untuk keunikan di `ref_pegawais` dan `pegawai_registrations`

    $duplicated = [];
    // Cek duplikasi NIP
    if (\DB::table('ref_pegawais')->where('nip', $data['nip'])->exists() || PegawaiRegistration::where('nip', $data['nip'])->exists()) {
        $duplicated[] = 'NIP sudah terdaftar';
    }
    // Cek duplikasi email
    if (\DB::table('ref_pegawais')->where('email', $data['email'])->exists() || PegawaiRegistration::where('email', $data['email'])->exists()) {
        $duplicated[] = 'Email sudah terdaftar';
    }
    // Cek duplikasi nomor HP
    if ($data['no_hp'] && (\DB::table('ref_pegawais')->where('no_hp', $data['no_hp'])->exists() || PegawaiRegistration::where('no_hp', $data['no_hp'])->exists())) {
        $duplicated[] = 'Nomor HP sudah terdaftar';
    }

    // Jika ada duplikasi, kembalikan pesan error
    if (!empty($duplicated)) {
        return back()->withInput()->withErrors(['error' => implode(', ', $duplicated)]);
    }

    // Simpan data registrasi
    $reg = PegawaiRegistration::create($data + ['status' => 'pending']);

    return redirect()->route('Pegawai.login')
        ->with('success', 'Registrasi berhasil dikirim. Akun Anda menunggu persetujuan admin.');
}
}