<?php

use Illuminate\Database\Seeder;
use App\Models\Pelatihan;

class PelatihanSeeder extends Seeder
{
    public function run()
    {
        Pelatihan::create([
            'jenis_pelatihan' => 'Jenis A',
            'nama_lembaga' => 'Lembaga A',
        ]);

        Pelatihan::create([
            'jenis_pelatihan' => 'Jenis B',
            'nama_lembaga' => 'Lembaga B',
        ]);

        // Tambahkan lebih banyak data jika diperlukan
    }
}
