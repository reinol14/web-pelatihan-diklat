<?php

namespace Database\Seeders;

use App\Models\ref_kategorijabatans;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RefkategorijabatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ref_kategorijabatans::create([
            'kode_kategorijabatan'=> 'KJ001',
            'kategori_jabatan'=> 'Esselon II',
        ]);
        ref_kategorijabatans::create([
            'kode_kategorijabatan'=> 'KJ002',
            'kategori_jabatan'=> 'Esselon III',
        ]);
        ref_kategorijabatans::create([
            'kode_kategorijabatan'=> 'KJ003',
            'kategori_jabatan'=> 'Esselon IV',
        ]);
        ref_kategorijabatans::create([
            'kode_kategorijabatan'=> 'KJ004',
            'kategori_jabatan'=> 'Pelaksana',
        ]);
}
}