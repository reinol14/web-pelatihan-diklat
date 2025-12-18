<?php

namespace App\Http\Controllers\Admin\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\ref_pegawais;
use App\Models\ref_unitkerjas;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Providers\AuthServiceProvider;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class PegawaiController extends Controller
{
    use AuthorizesRequests;

    /**
     * Menampilkan daftar pegawai.
     */
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

    /**
     * Menampilkan detail pegawai berdasarkan ID.
     */
    public function show($id)
    {
        $pegawai = ref_pegawais::findOrFail($id); // Mengambil data berdasarkan ID
        return view('Admin.Pegawai.show', compact('pegawai'));
    }

    /**
     * Menampilkan form edit pegawai.
     */
    public function edit($id)
    {
        $pegawai = ref_pegawais::findOrFail($id);

        // Jika admin biasa, gunakan policy untuk otorisasi
        if (Auth::user()->is_admin !== 1) {
            $this->authorize('update', $pegawai);
        }

        $unitKerjaGrouped = ref_unitkerjas::all()->groupBy('unitkerja');

        // Ambil data konfigurasi terpusat
        $pangkatList = config('pegawai.pangkat');
        $golonganList = config('pegawai.golongan');
        $jenisASNList = config('pegawai.jenis_asn');
        $kategoriJabatanList = config('pegawai.kategori_jabatan');

        return view('Admin.Pegawai.edit', compact(
            'pegawai',
            'unitKerjaGrouped',
            'pangkatList',
            'golonganList',
            'jenisASNList',
            'kategoriJabatanList'
        ));
    }

    /**
     * Memperbarui data pegawai.
     */
    public function update(Request $request, $id)
    {
        $pegawai = ref_pegawais::findOrFail($id);

        // Jika admin biasa, gunakan policy untuk otorisasi
        if (Auth::user()->is_admin !== 1) {
            $this->authorize('update', $pegawai);
        }

        // Validasi input data
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

        // Perbarui data pegawai di model
        $pegawai->fill($validated);

        // Jika ada file foto, simpan di storage
        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('pegawai', 'public');
            $pegawai->foto = $path;
        }

        $pegawai->save();

        return redirect()->route('Admin.Pegawai.index')->with('success', 'Data pegawai berhasil diperbarui.');
    }

    /**
     * Menampilkan duplikasi data pegawai.
     */
    public function checkDuplicates()
    {
        // Query untuk duplikasi data
        $nipDuplicates = \DB::table('ref_pegawais')
            ->whereNotNull('nip')
            ->where('nip', '!=', '')
            ->whereIn('nip', function ($query) {
                $query->select('nip')
                    ->from('ref_pegawais')
                    ->groupBy('nip')
                    ->havingRaw('COUNT(*) > 1');
            })
            ->get();

        $emailDuplicates = \DB::table('ref_pegawais')
            ->whereNotNull('email')
            ->where('email', '!=', '')
            ->whereIn('email', function ($query) {
                $query->select('email')
                    ->from('ref_pegawais')
                    ->groupBy('email')
                    ->havingRaw('COUNT(*) > 1');
            })
            ->get();

        $phoneDuplicates = \DB::table('ref_pegawais')
            ->whereNotNull('no_hp')
            ->where('no_hp', '!=', '')
            ->whereIn('no_hp', function ($query) {
                $query->select('no_hp')
                    ->from('ref_pegawais')
                    ->groupBy('no_hp')
                    ->havingRaw('COUNT(*) > 1');
            })
            ->get();

        // Query untuk data kosong
        $emptyFields = \DB::table('ref_pegawais')
            ->whereNull('nip')
            ->orWhere('nip', '')
            ->orWhereNull('email')
            ->orWhere('email', '')
            ->orWhereNull('no_hp')
            ->orWhere('no_hp', '')
            ->get();

        // Return ke view
        return view('Admin.Pegawai.checkDuplicates', compact('nipDuplicates', 'emailDuplicates', 'phoneDuplicates', 'emptyFields'));
    }

    /**
     * Menghapus data pegawai berdasarkan ID.
     */
    public function destroy($id)
    {
        \Log::info("Memproses permintaan DELETE untuk ID pegawai: {$id}");

        $pegawai = ref_pegawais::find($id);

        if (!$pegawai) {
            \Log::error("Pegawai tidak ditemukan dengan ID: {$id}");
            return response()->json([
                'success' => false,
                'message' => "Pegawai tidak ditemukan."
            ], 404);
        }

        // Lakukan penghapusan
        $pegawai->delete();
        \Log::info("Pegawai dengan ID: {$id} berhasil dihapus.");

        return response()->json([
            'success' => true,
            'message' => 'Pegawai berhasil dihapus.'
        ]);
    }
public function store(Request $request)
{
    // Validasi input data
    $validated = $request->validate([
        'nip'                 => 'required|string|max:255|unique:ref_pegawais,nip',
        'nama'                => 'required|string|max:255',
        'tempat_lahir'        => 'nullable|string|max:255',
        'tanggal_lahir'       => 'nullable|date',
        'pangkat'             => 'required|string',
        'golongan'            => 'required|string',
        'jabatan'             => 'required|string',
        'jenis_asn'           => 'required|string',
        'kategori_jabatanasn' => 'required|string',
        'kode_unitkerja'      => 'required|exists:ref_unitkerjas,kode_unitkerja',
        'email'               => 'required|email|unique:ref_pegawais,email',
        'no_hp'               => 'required|string',
        'no_wa'               => 'nullable|string',
        'alamat'              => 'required|string',
        'tmt'                 => 'required|date',
        'foto'                => 'nullable|image|max:2048',
    ]);

    // Jika admin biasa (is_admin == 2), paksa unit kerja sama dengan admin yang login
    if (Auth::user()->is_admin == 2) {
        $validated['kode_unitkerja'] = Auth::user()->kode_unitkerja;
    }

    // Buat instance pegawai baru
    $pegawai = new ref_pegawais();
    $pegawai->fill($validated);

    // Jika ada file foto, simpan di storage
    if ($request->hasFile('foto')) {
        $path = $request->file('foto')->store('pegawai', 'public');
        $pegawai->foto = $path;
    }

    $pegawai->save();

    return redirect()->route('Admin.Pegawai.index')->with('success', 'Data pegawai berhasil ditambahkan.');
}

public function create()
{
    $unitKerjaGrouped = ref_unitkerjas::all()->groupBy('unitkerja');

    // Ambil data konfigurasi terpusat
    $pangkatList = config('pegawai.pangkat');
    $golonganList = config('pegawai.golongan');
    $jenisASNList = config('pegawai.jenis_asn');
    $kategoriJabatanList = config('pegawai.kategori_jabatan');

    return view('Admin.Pegawai.create', compact(
        'unitKerjaGrouped',
        'pangkatList',
        'golonganList',
        'jenisASNList',
        'kategoriJabatanList'
    ));
}
}