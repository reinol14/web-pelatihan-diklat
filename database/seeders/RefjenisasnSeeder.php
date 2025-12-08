<?php

namespace Database\Seeders;

use App\Models\ref_jenisasn;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RefjenisasnSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ref_jenisasn::create([
            'kode_jenisasn'=> 'JA001',
            'jenis_asn'=> 'PNS',
            ]);

        ref_jenisasn::create([
            'kode_jenisasn'=> 'JA002',
            'jenis_asn'=> 'PPPK',
            ]);
    }
}
