<?php

namespace App\Http\Controllers\Admin\Laporan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class LaporanPelatihanApprovalController extends Controller
{
    public function index(Request $request)
    {
        $status    = $request->input('status'); // pending|approved|rejected|null
        $q         = trim((string) $request->input('q'));
        $dateFrom  = $request->input('date_from');
        $dateTo    = $request->input('date_to');

        $base = DB::table('laporan_pelatihan as l')
            ->join('pbj_1_pelatihans as s', 's.id', '=', 'l.pelatihan_id')
            ->leftJoin('ref_pegawais as p', 'p.nip', '=', 'l.nip')
            ->leftJoin('admin as a', 'a.id', '=', 'l.reviewed_by')
            ->select([
                'l.id','l.nip','l.judul','l.status','l.keterangan','l.created_at','l.updated_at',
                DB::raw('l.file_path as file_path'),
                DB::raw('l.sertifikat as sertifikat'),
                DB::raw('l.reviewed_by'),
                DB::raw('l.reviewed_at'),
                's.nama_pelatihan',
                'p.nama as nama_pegawai','p.kode_unitkerja',
                DB::raw('a.name as reviewer_name'),
                DB::raw('a.is_admin as reviewer_is_admin'),
            ]);

        if ($status) {
            $base->where('l.status', $status);
        }
        if ($q !== '') {
            $base->where(function($qq) use ($q){
                $qq->where('s.nama_pelatihan','like',"%{$q}%")
                   ->orWhere('p.nama','like',"%{$q}%")
                   ->orWhere('l.nip','like',"%{$q}%")
                   ->orWhere('l.judul','like',"%{$q}%");
            });
        }
        if ($dateFrom) $base->whereDate('l.created_at','>=',$dateFrom);
        if ($dateTo)   $base->whereDate('l.created_at','<=',$dateTo);

        $items = $base
            ->orderByRaw("CASE WHEN l.status='pending' THEN 1 WHEN l.status='approved' THEN 2 ELSE 3 END")
            ->orderByDesc('l.created_at')
            ->paginate(15)
            ->withQueryString();

        // counts untuk quick tabs
        $counts = DB::table('laporan_pelatihan')
            ->selectRaw("SUM(status='pending')  as pending")
            ->selectRaw("SUM(status='approved') as approved")
            ->selectRaw("SUM(status='rejected') as rejected")
            ->first();
        $counts = [
            'pending'  => (int)($counts->pending  ?? 0),
            'approved' => (int)($counts->approved ?? 0),
            'rejected' => (int)($counts->rejected ?? 0),
        ];

        return view('Admin.Laporan.approvelaporan', compact('items','counts','status','q','dateFrom','dateTo'));
    }

    
    public function approve(Request $request, $id)
    {
        $keterangan = $request->filled('keterangan') ? trim($request->input('keterangan')) : null;
        
        // Dapatkan ID admin yang sedang login
        $user = Auth::guard('web')->user();
        $reviewedBy = $user ? $user->id : null;

        DB::transaction(function() use ($id, $keterangan, $reviewedBy) {
            // Set laporan => approved + simpan catatan
            $lap = DB::table('laporan_pelatihan')->where('id',$id)->lockForUpdate()->first();
            if (!$lap) abort(404);

            DB::table('laporan_pelatihan')->where('id',$id)->update([
                'status'      => 'approved',
                'keterangan'  => $keterangan,
                'reviewed_by' => $reviewedBy,
                'reviewed_at' => now(),
                'updated_at'  => now(),
            ]);

            // Otomatis luluskan peserta pada pelatihan tsb
            DB::table('peserta_pelatihan')
                ->where('pelatihan_id', $lap->pelatihan_id)
                ->where('nip', $lap->nip)
                ->update([
                    'status'     => 'lulus',
                    'updated_at' => now(),
                ]);
        });

        return back()->with('success','Laporan disetujui. Status peserta diubah menjadi lulus.');
    }

    public function reject(Request $request, $id)
    {
        $keterangan = $request->filled('keterangan') ? trim($request->input('keterangan')) : null;
        
        // Dapatkan ID admin yang sedang login
        $user = Auth::guard('web')->user();
        $reviewedBy = $user ? $user->id : null;

        DB::transaction(function() use ($id, $keterangan, $reviewedBy) {
            $lap = DB::table('laporan_pelatihan')->where('id',$id)->lockForUpdate()->first();
            if (!$lap) abort(404);

            DB::table('laporan_pelatihan')->where('id',$id)->update([
                'status'      => 'rejected',
                'keterangan'  => $keterangan,
                'reviewed_by' => $reviewedBy,
                'reviewed_at' => now(),
                'updated_at'  => now(),
            ]);
            // status peserta tetap "menunggu_laporan" (tidak diubah)
        });

        return back()->with('success','Laporan ditolak. Pegawai dapat memperbaiki dan mengirim ulang.');
    }

}