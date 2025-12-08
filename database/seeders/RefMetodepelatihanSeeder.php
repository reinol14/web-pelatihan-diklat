<?php

namespace Database\Seeders;

use App\Models\ref_metodepelatihans;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class RefMetodepelatihanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ref_metodepelatihans::create([
            'kode_metodepelatihan'=> 'MP001',
            'metode_pelatihan' => 'Blended Learning',
        ]);

        ref_metodepelatihans::create([
            'kode_metodepelatihan'=> 'MP002',
            'metode_pelatihan' => 'E-Learning',
            
        ]);

        ref_metodepelatihans::create([
            'kode_metodepelatihan'=> 'MP003',
            'metode_pelatihan' => 'Klasikal',
        ]);
    }
}
