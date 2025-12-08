<?php

namespace Database\Seeders;

use App\Models\ref_pelaksanaanpelatihans;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RefpelaksanaanpelatihanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ref_pelaksanaanpelatihans::create([
            'kode_pelaksanaanpelatihan' => 'PP001',
            'pelaksanaan_pelatihan' => 'Pengiriman',
        ]);
        ref_pelaksanaanpelatihans::create([
            'kode_pelaksanaanpelatihan' => 'PP002',
            'pelaksanaan_pelatihan' => 'Penyelenggaraan',
        ]);
        ref_pelaksanaanpelatihans::create([
            'kode_pelaksanaanpelatihan' => 'PP003',
            'pelaksanaan_pelatihan' => 'Kerjasama',
        ]);
    }
}
