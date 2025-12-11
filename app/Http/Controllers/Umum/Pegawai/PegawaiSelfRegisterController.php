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
        $data = $r->validate([
            'nama'                  => 'required|string|max:150',
            'nip'                   => 'required|string|max:30',
            'email'                 => 'required|email:rfc,dns',
            'no_hp'                 => 'nullable|string|max:30',
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

        // Cek duplikasi cepat: kalau sudah ada di ref_pegawais (sudah punya akun aktif), tolak
        $already = \DB::table('ref_pegawais')->where('nip', (int) $data['nip'])->orWhere('email', $data['email'])->exists();
        if ($already) {
            return back()->withInput()->with('error', 'Data Anda sudah terdaftar aktif. Silakan hubungi admin jika perlu perubahan.');
        }

        // Cek jika sudah pernah daftar dan masih pending
        $pending = PegawaiRegistration::where('nip', $data['nip'])->where('status','pending')->exists();
        if ($pending) {
            return back()->withInput()->with('info', 'Pengajuan Anda masih menunggu persetujuan admin.');
        }

        // Simpan foto jika ada
        if ($r->hasFile('foto')) {
            $data['foto'] = $r->file('foto')->store('registrations/foto', 'public');
        }

        $reg = PegawaiRegistration::create($data + ['status' => 'pending']);

        return redirect()->route('Pegawai.login')
            ->with('success', 'Registrasi berhasil dikirim. Akun Anda menunggu persetujuan admin.');
    }
}