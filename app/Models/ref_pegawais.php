<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ref_pegawais extends Authenticatable
{
    use HasFactory;

    protected $table = 'ref_pegawais';
    protected $primaryKey = 'id';
    public $incrementing = true;     // ubah ke false jika id bukan auto-increment
    protected $keyType = 'int';      // ubah ke 'string' jika id string

    protected $fillable = [
        // identitas
        'nip',
        'nama',
        'tempat_lahir',
        'tanggal_lahir',
        'pangkat',
        'golongan',
        'jabatan',

        // organisasi
        'kode_unitkerja',     // <— ini yang benar, bukan unitkerja_id
        'id_atasan',          // <— tambahkan ini agar bisa mass-assign

        // kontak
        'no_wa',
        'no_hp',
        'email',
        'alamat',

        // lain-lain
        'foto',
        'jenis_asn',
        'kategori_jabatanasn',
        'pengelola_kepegawaian',
        'uraian_tugas',
    ];

    protected $hidden = [
        'remember_token',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date:Y-m-d',
    ];

    /** Relasi ke tabel unit kerja via kode_unitkerja */
    public function unitKerja()
    {
        return $this->belongsTo(
            ref_unitkerjas::class,   // pastikan model ini ada
            'kode_unitkerja',        // FK di tabel pegawai
            'kode_unitkerja'         // PK/unique di tabel unit kerja
        );
    }

    /** Atasan langsung: id_atasan menyimpan ID pegawai (ref_pegawais.id) */
   public function atasan()
    {
        // self-join: id_atasan -> id pegawai lain
        return $this->belongsTo(self::class, 'id_atasan', 'id');
    }

    /**
     * (Opsional) Atasan yang dipastikan satu kode_unitkerja.
     * Catatan: memakai nilai instance saat ini (bukan whereColumn).
     */
    public function atasanDalamUnit()
    {
        return $this->belongsTo(self::class, 'id_atasan', 'id')
            ->where('kode_unitkerja', $this->kode_unitkerja);
    }
}
