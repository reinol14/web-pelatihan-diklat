<?php

// app/Models/PegawaiRegistration.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PegawaiRegistration extends Model
{
    protected $fillable = [
      'nama','nip','email','no_hp','tempat_lahir','tanggal_lahir','pangkat','golongan',
      'jabatan','jenis_asn','kategori_jabatanasn','kode_unitkerja','alamat','tmt',
      'status','approved_by','approved_at','approval_note','foto'
    ];

    protected $casts = [
      'tanggal_lahir' => 'date',
      'tmt'           => 'date',
      'approved_at'   => 'datetime',
    ];
}

