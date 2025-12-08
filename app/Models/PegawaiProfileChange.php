<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PegawaiProfileChange extends Model
{
    protected $table = 'pegawai_profile_changes';

    protected $fillable = [
        'pegawai_id',
        'payload',
        'original',
        'status',
        'reviewed_by',
        'reviewed_at',
        'review_note',
    ];

    protected $casts = [
        'payload' => 'array',
        'original' => 'array',
        'reviewed_at' => 'datetime',
    ];

    public function pegawai()
    {
        return $this->belongsTo(\App\Models\ref_pegawais::class, 'pegawai_id');
    }
}
