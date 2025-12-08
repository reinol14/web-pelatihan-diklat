<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\ref_pegawais;

class ProfilController extends Controller
{
    public function edit()
    {
        $pegawai = Auth::guard('pegawais')->user();
        return view('pegawai.profil.edit', compact('pegawai'));
    }

    public function update(Request $request)
    {
        $pegawai = Auth::guard('pegawais')->user();

        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email',
            'jabatan' => 'nullable|string|max:255',
        ]);

        $pegawai->update($request->only('nama','email','jabatan'));

        return redirect()->route('pegawai.dashboard')->with('success', 'Profil berhasil diperbarui');
    }
}
