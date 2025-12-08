<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Provinsi extends Model
{
    use HasFactory;
    protected $fillable = ['nama'];
    public function kotas()
    {
        return $this->hasMany(Kota::class, 'provinsi_id');
    }
}
