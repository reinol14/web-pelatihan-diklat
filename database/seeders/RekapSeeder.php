<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RekapSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('rekap')->insert([
            ['tahun' => 2024, 'nama_pelatihan' => 'Pelatihan A', 'jumlah' => 21],
            ['tahun' => 2023, 'nama_pelatihan' => 'Pelatihan A', 'jumlah' => 245],
            ['tahun' => 2022, 'nama_pelatihan' => 'Pelatihan A', 'jumlah' => 542],
            ['tahun' => 2021, 'nama_pelatihan' => 'Pelatihan A', 'jumlah' => 632],
            ['tahun' => 2020, 'nama_pelatihan' => 'Pelatihan A', 'jumlah' => 321],
            ['tahun' => 2019, 'nama_pelatihan' => 'Pelatihan A', 'jumlah' => 213],
            ['tahun' => 2018, 'nama_pelatihan' => 'Pelatihan A', 'jumlah' => 221],
            ['tahun' => 2017, 'nama_pelatihan' => 'Pelatihan A', 'jumlah' => 327],
            ['tahun' => 2016, 'nama_pelatihan' => 'Pelatihan A', 'jumlah' => 643],
            ['tahun' => 2015, 'nama_pelatihan' => 'Pelatihan A', 'jumlah' => 123],
        ]);
    }
}
