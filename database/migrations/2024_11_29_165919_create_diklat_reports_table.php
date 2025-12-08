<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiklatReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('diklat_reports', function (Blueprint $table) {
            $table->id();
            $table->string('nip')->nullable(); // Nomor Induk Pegawai
            $table->string('nama_penulis')->nullable();
            $table->string('jabatan')->nullable();
            $table->string('golongan')->nullable();
            $table->string('unit_kerja')->nullable();
            $table->string('jenis_pelatihan')->nullable();
            $table->string('nama_pelatihan')->nullable();
            $table->year('tahun_pelatihan')->nullable();
            $table->string('metode_pelatihan')->nullable();
            $table->date('tanggal_pelaksanaan')->nullable();
            $table->string('tempat_pelaksanaan')->nullable(); // Tambahkan kolom tempat pelaksanaan
            $table->string('judul_laporan')->nullable();
            $table->text('latar_belakang')->nullable();
            $table->decimal('biaya_per_orang', 10, 2); // Kolom biaya_per_orang
            $table->string('link_katalog')->nullable(); // Kolom link_katalog
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('diklat_reports');
    }
}
