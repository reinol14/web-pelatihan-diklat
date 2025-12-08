<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Katalog_2_masuks extends Model
{
    use HasFactory;

    protected $table = 'katalog_2_masuks';
    protected $primaryKey = 'id';
    public $timestamps = true;
    
    protected $fillable = [
        'rumpun_pelatihan', 'jenis_pelatihan', 'nama_pelatihan', 'informasi_pelatihan',
        'file_pelatihan', 'estimasi_biaya', 'nama_penyelenggara', 'nama_CP', 'no_HP',
        'metode_pelatihan', 'pelaksanaan_pelatihan', 'status', 'keterangan'
    ];
}
