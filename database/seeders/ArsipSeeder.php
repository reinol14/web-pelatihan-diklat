<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ArsipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        \App\Models\Brosur::create([
            'nama_penyelenggara' => 'Lembaga A',
            'alamat' => 'Jl. Beo Raya',
            'no_telepon' => '0271788099',
            'no_hp' => '08575584933',
            'status_ajuan' => 'Tahap Review',
        ]);
        // Tambahkan data lain atau gunakan factory
    }

}
