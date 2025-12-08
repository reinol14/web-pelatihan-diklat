<?php

// app/Models/PesertaPelatihan.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;


class PesertaPelatihan extends Model
{
    use HasFactory;

    protected $table = 'peserta_pelatihan';

    protected $fillable = [
        'user_id', 'pegawai_id', 'pelatihan_id', 'status',
        'nip','nama','pangkat_golongan','jabatan','unitkerja','hasil_pelatihan','sertifikat'
    ];

    public function pelatihan() {
        return $this->belongsTo(pbj_1_pelatihan::class, 'pelatihan_id');
    }

        public function pegawaiById()
    {
        // Jika suatu saat kamu menyimpan pegawai_id
        return $this->belongsTo(\App\Models\ref_pegawais::class, 'pegawai_id');
    }
        public function scopeRegistered($q)
    {
        return $q->where('status', 'registered');
    }
    public function pegawai() {
        return $this->belongsTo(\App\Models\ref_pegawais::class, 'pegawai_id');
    }

     // ▶ Status berbahasa Indonesia
    public const S_MENUNGGU         = 'menunggu';            // menunggu konfirmasi admin
    public const S_DITERIMA         = 'diterima';            // sudah disetujui admin
    public const S_BERJALAN         = 'berjalan';            // sedang berlangsung (otomatis saat tanggal mulai)
    public const S_MENUNGGU_LAPORAN = 'menunggu_laporan';    // sesi selesai, wajib kirim laporan
    public const S_LULUS            = 'lulus';               // laporan disetujui (lulus)
    public const S_TIDAK_LULUS      = 'tidak_lulus';         // laporan disetujui (tidak lulus)
    public const S_DITOLAK          = 'ditolak';             // ditolak admin
    public const S_DIBATALKAN       = 'dibatalkan';          // batal oleh peserta

    // ▶ Kuota dihitung hanya utk status “mengikat kursi”
    public function scopeCountableForQuota($q)
    {
        return $q->whereIn('status', [
            self::S_DITERIMA,
            self::S_BERJALAN,
            self::S_MENUNGGU_LAPORAN,
        ]);
    }

    private function touchAutoStatusFor(string $nip): void
{
    $today = Carbon::today()->toDateString();

    // diterima -> berjalan (jika hari ini masuk rentang)
    DB::table('peserta_pelatihan as pp')
      ->join('pbj_1_pelatihans as s','s.id','=','pp.pelatihan_id')
      ->where('pp.nip',$nip)
      ->where('pp.status', PesertaPelatihan::S_DITERIMA)
      ->whereDate('s.tanggal_mulai','<=',$today)
      ->whereDate('s.tanggal_selesai','>=',$today)
      ->update(['pp.status'=>PesertaPelatihan::S_BERJALAN, 'pp.updated_at'=>now()]);

    // berjalan -> menunggu_laporan (jika sesi sudah lewat)
    DB::table('peserta_pelatihan as pp')
      ->join('pbj_1_pelatihans as s','s.id','=','pp.pelatihan_id')
      ->where('pp.nip',$nip)
      ->where('pp.status', PesertaPelatihan::S_BERJALAN)
      ->whereDate('s.tanggal_selesai','<',$today)
      ->update(['pp.status'=>PesertaPelatihan::S_MENUNGGU_LAPORAN, 'pp.updated_at'=>now()]);
}
}
