<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ref_unitkerjas extends Model
{
    use HasFactory;

    protected $table = 'ref_unitkerjas';
    protected $primaryKey = 'id_unitkerja';
    public $incrementing = true;
    protected $keyType = 'int';
    protected $fillable = ['nama'];
    public function pegawais()
    {
        return $this->hasMany(ref_pegawais::class, 'kode_unitkerja', 'id_unitkerja');
    }

    public function getAuthIdentifierName()
    {
        return 'nip';
    }

}
