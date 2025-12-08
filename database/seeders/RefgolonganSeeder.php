<?php

namespace Database\Seeders;

use App\Models\ref_golongans;
use App\Models\ref_golonganss;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RefgolonganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ref_golongans::create([
            'kode_golongan' => 'PNS001',
            'jenis_asn' => 'PNS',
            'golongan' => 'IV/e',
            'pangkat' => 'Pembina Utama',
            'pangkat_golongan' => 'Pembina Utama (IV/e)',
        ]);

        ref_golongans::create([
            'kode_golongan' => 'PNS002',
            'jenis_asn' => 'PNS',
            'golongan' => 'IV/d',
            'pangkat' => 'Pembina Utama Madya',
            'pangkat_golongan' => 'Pembina Utama Madya (IV/d)',
        ]);

        ref_golongans::create([
            'kode_golongan' => 'PNS003',
            'jenis_asn' => 'PNS',
            'golongan' => 'IV/c',
            'pangkat' => 'Pembina Utama Muda',
            'pangkat_golongan' => 'Pembina Utama Muda (IV/c)',
        ]);

        ref_golongans::create([
            'kode_golongan' => 'PNS004',
            'jenis_asn' => 'PNS',
            'golongan' => 'IV/b',
            'pangkat' => 'Pembina Tk. I',
            'pangkat_golongan' => 'Pembina Tk. I (IV/b)',
        ]);
        ref_golongans::create([
            'kode_golongan' => 'PNS004',
            'jenis_asn' => 'PNS',
            'golongan' => 'IV/b',
            'pangkat' => 'Pembina Tk. I',
            'pangkat_golongan' => 'Pembina Tk. I (IV/b)',
        ]);

        ref_golongans::create([
            'kode_golongan' => 'PNS005',
            'jenis_asn' => 'PNS',
            'golongan' => 'IV/a',
            'pangkat' => 'Pembina',
            'pangkat_golongan' => 'Pembina (IV/a)',
        ]);

        ref_golongans::create([
            'kode_golongan' => 'PNS006',
            'jenis_asn' => 'PNS',
            'golongan' => 'III/d',
            'pangkat' => 'Penata Tk. I',
            'pangkat_golongan' => 'Penata Tk. I (III/d)',
        ]);

        ref_golongans::create([
            'kode_golongan' => 'PNS007',
            'jenis_asn' => 'PNS',
            'golongan' => 'III/c',
            'pangkat' => 'Penata',
            'pangkat_golongan' => 'Penata (III/c)',
        ]);

        ref_golongans::create([
            'kode_golongan' => 'PNS008',
            'jenis_asn' => 'PNS',
            'golongan' => 'III/b',
            'pangkat' => 'Penata Muda Tk. I',
            'pangkat_golongan' => 'Penata Muda Tk. I (III/b)',
        ]);









    }
}
