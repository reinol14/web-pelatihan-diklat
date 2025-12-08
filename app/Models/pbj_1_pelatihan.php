<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class pbj_1_pelatihan extends Model
{
    use HasFactory;

    protected $table = 'pbj_1_pelatihans'; // pastikan nama tabel benar

    protected $fillable = [
        'id_katalog2',
        'nama_pelatihan',
        'jenis_pelatihan',
        'metode_pelatihan',
        'penyelenggara',
        'kuota',
        'deskripsi',
        'lokasi',
        'provinsi_id',
        'kota_id',
        'tanggal_mulai',
        'tanggal_selesai',
        'status',
    ];

    protected $casts = [
        'tanggal_mulai'   => 'date',
        'tanggal_selesai' => 'date',
    ];

    public function katalog()
    {
        return $this->belongsTo(Katalog_2_masuks::class, 'id_katalog2');
    }

    public function pesertas()
    {
        return $this->hasMany(PesertaPelatihan::class, 'pelatihan_id');
    }

    // relasi wilayah (opsional tapi berguna)
    public function provinsi()
    {
        return $this->belongsTo(Provinsi::class, 'provinsi_id');
    }

    public function kota()
    {
        return $this->belongsTo(Kota::class, 'kota_id');
    }
}
