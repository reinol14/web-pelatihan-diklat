<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsulanLaporanDiklatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('usulan_laporan_diklat')->insert([
            [
                'nama_penulis' => 'Meutya',
                'judul_laporan' => 'Urgensi Pembelajaran Secara Offline',
                'nama_pelatihan' => 'Matematika, Statistik',
                'unit_kerja' => '0101',
                'status' => 'In Progress',
            ],
            [
                'nama_penulis' => 'Rilse',
                'judul_laporan' => 'Lembaga B',
                'nama_pelatihan' => 'Kesehatan',
                'unit_kerja' => '0231',
                'status' => 'In Progress',
            ],
            [
                'nama_penulis' => 'Amalia',
                'judul_laporan' => 'Lembaga C',
                'nama_pelatihan' => 'Kekomputeran',
                'unit_kerja' => '0111',
                'status' => 'In Progress',
            ],
            [
                'nama_penulis' => 'Christo',
                'judul_laporan' => 'Lembaga D',
                'nama_pelatihan' => 'Manajemen',
                'unit_kerja' => '0222',
                'status' => 'In Progress',
            ],
            [
                'nama_penulis' => 'Gustawan',
                'judul_laporan' => 'Lembaga E',
                'nama_pelatihan' => 'Ilmu Sosial',
                'unit_kerja' => '0103',
                'status' => 'In Progress',
            ],
            [
                'nama_penulis' => 'Nugraha',
                'judul_laporan' => 'Lembaga A',
                'nama_pelatihan' => 'Imigrasi, Pajak',
                'unit_kerja' => '0421',
                'status' => 'In Progress',
            ],
            [
                'nama_penulis' => 'Reinol',
                'judul_laporan' => 'Lembaga B',
                'nama_pelatihan' => 'Matematika, Statistik',
                'unit_kerja' => '0212',
                'status' => 'In Progress',
            ],
            [
                'nama_penulis' => 'Bennuli',
                'judul_laporan' => 'Lembaga C',
                'nama_pelatihan' => 'Fisika, Kimia',
                'unit_kerja' => '0412',
                'status' => 'In Progress',
            ],
            [
                'nama_penulis' => 'Simagunsong',
                'judul_laporan' => 'Lembaga D',
                'nama_pelatihan' => 'Kekomputeran',
                'unit_kerja' => '0942',
                'status' => 'In Progress',
            ],
            [
                'nama_penulis' => 'Satria Ganteng',
                'judul_laporan' => 'Lembaga E',
                'nama_pelatihan' => 'Kestressan',
                'unit_kerja' => '0078',
                'status' => 'In Progress',
            ],
        ]);
    }
}

