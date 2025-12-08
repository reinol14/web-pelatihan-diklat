<?php

namespace Database\Seeders;

use App\Models\Diklat;
use Illuminate\Database\Seeder;
use App\Models\Ekatalog; // Replace with your actual model namespace

class EkatalogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            // Add your sample data here
            ['jenis_diklat' => 'Diklat A', 'nama_diklat' => 'Diklat Pemrograman Web', 'rumpun' => 'IT', 'kode_jabatan' => '001', 'penyelenggara' => 'Lembaga A', 'link_katalog' => 'https://example.com/diklat1'],
            ['jenis_diklat' => 'Diklat B', 'nama_diklat' => 'Diklat Desain Grafis', 'rumpun' => 'Desain', 'kode_jabatan' => '002', 'penyelenggara' => 'Lembaga B', 'link_katalog' => 'https://example.com/diklat2'],
            // ... Add more data as needed
        ];

        foreach ($data as $item) {
            Diklat::create($item);
        }
    }
}