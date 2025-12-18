<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PegawaiProfileChange;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class ProfileChangeController extends Controller
{
    /**
     * Menampilkan form edit profil pegawai.
     */
    public function edit()
    {
        // Ambil data unit kerja
        $unitKerjas = \App\Models\ref_unitkerjas::select('kode_unitkerja', 'unitkerja', 'sub_unitkerja')
            ->orderBy('unitkerja')
            ->orderBy('sub_unitkerja')
            ->get();

        // Mendapatkan data pegawai yang sedang login
        $pegawai = Auth::guard('pegawais')->user();

        // Ambil data atasan dari unit kerja yang sama
        $atasanCandidates = \App\Models\ref_pegawais::query()
            ->where('kode_unitkerja', $pegawai->kode_unitkerja)
            ->where('id', '!=', $pegawai->id) // Jangan ambil pegawai yang sedang login
            ->orderBy('nama')
            ->get(['id', 'nip', 'nama', 'jabatan', 'kode_unitkerja']);

        // Ambil konfigurasi terpusat
        $pangkatList = config('pegawai.pangkat');
        $golonganList = config('pegawai.golongan');
        $kategoriJabatanList = config('pegawai.kategori_jabatan');
        $jenisASNList = config('pegawai.jenis_asn');

        // Definisikan foto default jika tidak tersedia
        $foto = $pegawai->foto ?? 'default-foto.png';

        // Kirim data ke view
        return view('Pegawai.profil.edit', compact(
            'pegawai',
            'unitKerjas',
            'golonganList',
            'pangkatList',
            'kategoriJabatanList',
            'jenisASNList',
            'atasanCandidates',
            'foto' // Kirim variabel foto ke view
        ));
    }

    /**
     * Menyimpan perubahan profil sebagai permohonan.
     */
    public function store(Request $request)
    {
        $pegawai = Auth::guard('pegawais')->user();

        $input   = $request->except(['_token','_method']);
        $current = $pegawai->getAttributes();

        // Bandingkan input baru dengan data lama
        $changes = [];
        foreach ($input as $key => $val) {
            if (array_key_exists($key, $current)) {
                $currVal = is_null($current[$key]) ? null : (string) $current[$key];
                $newVal  = is_null($val) ? null : (string) $val;
                if ($currVal !== $newVal) {
                    $changes[$key] = $val;
                }
            }
        }

        // Jika tidak ada perubahan
        if (empty($changes)) {
            return back()->with('info', 'Tidak ada perubahan data untuk diajukan.');
        }

        // Ambil nilai asli dari perubahan yang diajukan
        $original = Arr::only($current, array_keys($changes));

        // Simpan permohonan perubahan ke database
        PegawaiProfileChange::create([
            'pegawai_id' => $pegawai->id,
            'payload'    => $changes,
            'original'   => $original,
            'status'     => 'pending',
        ]);

        return redirect()->route('Pegawai.dashboard')
            ->with('success', 'Perubahan profil dikirim, menunggu verifikasi admin.');
    }
}