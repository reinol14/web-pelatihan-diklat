<?php

namespace App\Http\Controllers\Admin\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\PegawaiProfileChange;
use App\Models\ref_pegawais;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PegawaiProfileApprovalController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status');   // pending/approved/rejected/null
        $q      = $request->get('q');        // cari nama/nip

        $items = PegawaiProfileChange::with('pegawai')
            ->when($status, fn($w) => $w->where('status',$status))
            ->when($q, function($w) use ($q) {
                $w->whereHas('pegawai', function($x) use ($q) {
                    $x->where('nama','like',"%$q%")->orWhere('nip','like',"%$q%");
                });
            })
            ->orderByRaw("FIELD(status,'pending','approved','rejected')")
            ->orderByDesc('created_at')
            ->paginate(20)
            ->appends($request->query());

        $counts = [
            'pending'  => PegawaiProfileChange::where('status','pending')->count(),
            'approved' => PegawaiProfileChange::where('status','approved')->count(),
            'rejected' => PegawaiProfileChange::where('status','rejected')->count(),
        ];

        return view('Admin.pegawai_profile.index', compact('items','counts','status','q'));
    }

    public function show($id)
    {
        $item = PegawaiProfileChange::with('pegawai')->findOrFail($id);
        return view('Admin.pegawai_profile.show', compact('item'));
    }

public function approve(Request $request, $id)
{
    $item = \App\Models\PegawaiProfileChange::where('status','pending')->findOrFail($id);

    // HANYA field ini yang boleh diterapkan
    $allowed = ['nama','email','no_hp','alamat','id_atasan','tanggal_lahir'];

    // Ambil payload sebagai array
    $payload = (array) $item->payload;

    // Ambil hanya field yang diizinkan (whitelist)
    $data = array_intersect_key($payload, array_flip($allowed));

    \DB::transaction(function () use ($item, $data, $request) {
        $pegawai = \App\Models\ref_pegawais::findOrFail($item->pegawai_id);

        // Terapkan perubahan — aman karena sudah di-whitelist
        $pegawai->fill($data)->save(); // pastikan kolom2 ini ada di $fillable model ref_pegawais

        $item->update([
            'status'      => 'approved',
            'reviewed_by' => \Auth::id(),
            'reviewed_at' => now(),
            'review_note' => $request->input('review_note'),
        ]);
    });

    return back()->with('success','Perubahan disetujui & diterapkan.');
}



    public function reject(Request $request, $id)
    {
        $item = PegawaiProfileChange::where('status','pending')->findOrFail($id);

        $item->update([
            'status'      => 'rejected',
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
            'review_note' => $request->input('review_note'),
        ]);

        return back()->with('success','Pengajuan ditolak.');
    }
}
