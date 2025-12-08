<?php

namespace App\Policies;

use App\Models\admin;
use App\Models\ref_pegawais;

class PegawaiPolicy
{
    protected $policies = [
        ref_pegawais::class => PegawaiPolicy::class,
    ];
    /**
     * Tentukan apakah admin dapat mengupdate data pegawai.
     */
    public function update(admin $user, ref_pegawais $pegawai): bool
    {
        return $this->canAccess($user, $pegawai);
    }

    /**
     * Tentukan apakah admin dapat menghapus data pegawai.
     */
    public function delete(admin $user, ref_pegawais $pegawai): bool
    {
        return $this->canAccess($user, $pegawai);
    }

    /**
     * Cek apakah admin boleh akses data pegawai tertentu.
     */
    private function canAccess(admin $user, ref_pegawais $pegawai): bool
    {
        // Superadmin (id_admin = 1) bisa akses semua
        if ($user->id_admin === 1) {
            return true;
        }

        // Admin biasa hanya bisa akses pegawai dari unit kerjanya sendiri
        return $user->kode_unitkerja === $pegawai->kode_unitkerja;
    }
}
