<?php

namespace Database\Seeders;

use App\Models\ref_jenispelatihans;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RefjenispelatihanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ref_jenispelatihans::create([
        'kode_jenispelatihan'=> 'JP001',
        'jenis_pelatihan'=> 'Diklat Dasar',
        ]);

        ref_jenispelatihans::create([
            'kode_jenispelatihan'=> 'JP002',
            'jenis_pelatihan'=> 'Diklat Fungsional',
            ]);

            
        ref_jenispelatihans::create([
            'kode_jenispelatihan'=> 'JP0033',
            'jenis_pelatihan'=> 'Diklat Struktural',
            ]);

        ref_jenispelatihans::create([
                'kode_jenispelatihan'=> 'JP004',
                'jenis_pelatihan'=> 'Diklat Teknis',
                ]);
    }

}
