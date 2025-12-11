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
    public function edit()
    {
        $unitKerjas = DB::table('ref_unitkerjas')
        ->select('kode_unitkerja','unitkerja','sub_unitkerja')
        ->orderBy('unitkerja')
        ->orderBy('sub_unitkerja')
        ->get();
        $pegawai = Auth::guard('pegawais')->user();
        $atasanCandidates = \App\Models\ref_pegawais::query()
        ->where('kode_unitkerja', $pegawai->kode_unitkerja)
        ->where('id', '!=', $pegawai->id)
        ->orderBy('nama')
        ->get(['id','nip','nama','jabatan','kode_unitkerja']);
        $golonganList = [
        '(I/a)','(I/b)','(I/c)','(I/d)',
        '(II/a)','(II/b)','(II/c)','(II/d)',
        '(III/a)','(III/b)','(III/c)','(III/d)',
        '(IV/a)','(IV/b)','(IV/c)','(IV/d)','(IV/e)',
    ];
        return view('Pegawai.profil.edit', compact('pegawai', 'unitKerjas', 'golonganList', 'atasanCandidates'));
    }

public function store(Request $request)
{
    $pegawai = Auth::guard('pegawais')->user();

    $input   = $request->except(['_token','_method']);
    $current = $pegawai->getAttributes();

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

    if (empty($changes)) {
        return back()->with('info','Tidak ada perubahan data untuk diajukan.');
    }

    $original = \Illuminate\Support\Arr::only($current, array_keys($changes));

    PegawaiProfileChange::create([
        'pegawai_id' => $pegawai->id,
        'payload'    => $changes,
        'original'   => $original,
        'status'     => 'pending',
    ]);

    return redirect()->route('Pegawai.dashboard')
        ->with('success','Perubahan profil dikirim, menunggu verifikasi admin.');
}

}
