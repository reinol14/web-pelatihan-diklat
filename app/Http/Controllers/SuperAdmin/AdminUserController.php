<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $q    = trim((string) $request->input('q'));
        $role = $request->input('role'); // 1=Superadmin, 2=Admin Unit
        $unit = $request->input('unit'); // id_unitkerja (filter)

        $base = DB::table('admin as a')
            ->leftJoin('ref_unitkerjas as u1', 'a.kode_unitkerja', '=', 'u1.id_unitkerja')
            ->leftJoin('ref_unitkerjas as u2', 'a.kode_unitkerja', '=', 'u2.kode_unitkerja')
            ->select(
                'a.id','a.name','a.email','a.is_admin','a.kode_unitkerja','a.created_at',
                DB::raw('COALESCE(u1.unitkerja, u2.unitkerja) as unitkerja'),
                DB::raw('COALESCE(u1.sub_unitkerja, u2.sub_unitkerja) as sub_unitkerja'),
                DB::raw('COALESCE(u1.kode_unitkerja, u2.kode_unitkerja, a.kode_unitkerja) as kode_uk'),
                DB::raw('COALESCE(u1.id_unitkerja, u2.id_unitkerja) as unit_id_resolved')
            )
            ->when($q !== '', function ($qr) use ($q) {
                $qr->where(function($w) use ($q){
                    $w->where('a.name','like',"%{$q}%")
                      ->orWhere('a.email','like',"%{$q}%")
                      ->orWhere('u1.unitkerja','like',"%{$q}%")
                      ->orWhere('u2.unitkerja','like',"%{$q}%")
                      ->orWhere('u1.sub_unitkerja','like',"%{$q}%")
                      ->orWhere('u2.sub_unitkerja','like',"%{$q}%")
                      ->orWhere('a.kode_unitkerja','like',"%{$q}%")
                      ->orWhere('u1.kode_unitkerja','like',"%{$q}%")
                      ->orWhere('u2.kode_unitkerja','like',"%{$q}%");
                });
            })
            ->when($role !== null && $role !== '', fn($qr) => $qr->where('a.is_admin', (int)$role))
            ->when($unit, function($qr) use ($unit){
                $qr->where(function($w) use ($unit){
                    $w->where('u1.id_unitkerja', $unit)
                      ->orWhere('u2.id_unitkerja', $unit);
                });
            })
            ->orderByRaw("CASE WHEN a.is_admin=1 THEN 1 ELSE 2 END")
            ->orderBy('a.name','asc');

        $admins = $base->paginate(15)->appends($request->query());

        $unitkerjas = DB::table('ref_unitkerjas')
            ->orderBy('unitkerja')
            ->get(['id_unitkerja','unitkerja','sub_unitkerja','kode_unitkerja']);

        return view('SuperAdmin.Admins.index', compact('admins','q','role','unit','unitkerjas'));
    }

    public function create()
    {
        $unitkerjas = DB::table('ref_unitkerjas')
            ->orderBy('unitkerja')
            ->get(['id_unitkerja','unitkerja','sub_unitkerja','kode_unitkerja']);

        return view('SuperAdmin.Admins.create', compact('unitkerjas'));
    }

    public function store(Request $request)
    {
        // Validasi dasar
        $data = $request->validate([
            'name'           => ['required','string','max:100'],
            'email'          => ['required','email','max:120', Rule::unique('admin','email')],
            'password'       => ['required','string','min:8','confirmed'],
            'is_admin'       => ['required','in:1,2'],
            'kode_unitkerja' => ['nullable','string','max:50'], // akan dicek manual di bawah
        ]);

        // Jika admin unit → wajib kode_unitkerja
        if ((int)$data['is_admin'] === 2 && empty($data['kode_unitkerja'])) {
            return back()->withInput()->with('error','Admin Unit wajib pilih Unit Kerja.');
        }

        // Normalisasi: jika user tak sengaja kirim ID, konversi ke KODE
        if (!empty($data['kode_unitkerja'])) {
            $resolvedKode = DB::table('ref_unitkerjas')
                ->where('kode_unitkerja', $data['kode_unitkerja'])
                ->orWhere('id_unitkerja', $data['kode_unitkerja'])
                ->value('kode_unitkerja'); // <-- HARUS kolom kode_unitkerja

            if (!$resolvedKode && (int)$data['is_admin'] === 2) {
                return back()->withInput()->with('error','Unit Kerja tidak valid.');
            }
            $data['kode_unitkerja'] = $resolvedKode; // boleh null utk superadmin
        }

        DB::table('admin')->insert([
            'name'           => $data['name'],
            'email'          => $data['email'],
            'password'       => Hash::make($data['password']),
            'is_admin'       => (int)$data['is_admin'],
            'kode_unitkerja' => (int)$data['is_admin'] === 1 ? null : $data['kode_unitkerja'],
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);

        return redirect()->route('SuperAdmin.Admins.index')->with('success','Admin berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $admin = DB::table('admin')->where('id',$id)->first();
        if (!$admin) abort(404);

        // Buat preselect ID (untuk kemudahan tampilan), tapi value yang akan disimpan tetap KODE
        $selectedUnitId = null;
        if (!empty($admin->kode_unitkerja)) {
            $selectedUnitId = DB::table('ref_unitkerjas')
                ->where('kode_unitkerja', $admin->kode_unitkerja)
                ->value('id_unitkerja');
        }

        $unitkerjas = DB::table('ref_unitkerjas')
            ->orderBy('unitkerja')
            ->get(['id_unitkerja','unitkerja','sub_unitkerja','kode_unitkerja']);

        return view('SuperAdmin.Admins.edit', compact('admin','unitkerjas','selectedUnitId'));
    }

    public function update(Request $request, $id)
    {
        $admin = DB::table('admin')->where('id',$id)->first();
        if (!$admin) abort(404);

        $data = $request->validate([
            'name'           => ['required','string','max:100'],
            'email'          => ['required','email','max:120', Rule::unique('admin','email')->ignore($id,'id')],
            'password'       => ['nullable','string','min:8','confirmed'],
            'is_admin'       => ['required','in:1,2'],
            'kode_unitkerja' => ['nullable','string','max:50'],
        ]);

        if ((int)$data['is_admin'] === 2 && empty($data['kode_unitkerja'])) {
            return back()->withInput()->with('error','Admin Unit wajib pilih Unit Kerja.');
        }

        // Normalisasi ke KODE (bila user kirim ID)
        $resolvedKode = null;
        if (!empty($data['kode_unitkerja'])) {
            $resolvedKode = DB::table('ref_unitkerjas')
                ->where('kode_unitkerja', $data['kode_unitkerja'])
                ->orWhere('id_unitkerja', $data['kode_unitkerja'])
                ->value('kode_unitkerja');

            if ((int)$data['is_admin'] === 2 && !$resolvedKode) {
                return back()->withInput()->with('error','Unit Kerja tidak valid.');
            }
        }

        $update = [
            'name'           => $data['name'],
            'email'          => $data['email'],
            'is_admin'       => (int)$data['is_admin'],
            'kode_unitkerja' => (int)$data['is_admin'] === 1 ? null : $resolvedKode,
            'updated_at'     => now(),
        ];
        if (!empty($data['password'])) {
            $update['password'] = Hash::make($data['password']);
        }

        DB::table('admin')->where('id',$id)->update($update);

        return redirect()->route('SuperAdmin.Admins.index')->with('success','Admin diperbarui.');
    }

    public function destroy($id)
    {
        if (auth('web')->check() && auth('web')->id() == $id) {
            return back()->with('error','Tidak dapat menghapus akun yang sedang dipakai.');
        }

        DB::table('admin')->where('id',$id)->delete();
        return back()->with('success','Admin dihapus.');
    }
}
