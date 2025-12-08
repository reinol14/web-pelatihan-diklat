<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLaporanArsipTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('laporan_arsip', function (Blueprint $table) {
            $table->id(); // Auto incrementing primary key (corresponds to No.)
            $table->string('nip'); // NIP
            $table->string('nama_penulis'); // Corresponds to Nama Penulis
            $table->string('jabatan'); // Position
            $table->string('golongan'); // Rank
            $table->string('judul_laporan'); // Corresponds to Judul Laporan
            $table->string('jenis_pelatihan'); // Type of training
            $table->string('nama_pelatihan'); // Corresponds to Nama Pelatihan
            $table->year('tahun_pelatihan'); // Year of training
            $table->string('pelaksanaan'); // Implementation
            $table->string('mode_pelatihan'); // Mode of training (e.g., online, offline)
            $table->string('waktu_pelaksanaan'); // Execution time
            $table->text('latar_belakang'); // Background
            $table->string('unit_kerja'); // Corresponds to Unit Kerja
            $table->string('unggah_laporan')->nullable(); // Uploaded report file path
            $table->enum('status_ajuan', ['Approved', 'In Progress']);
            $table->timestamps(); // Created_at and updated_at fields
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('laporan_arsip');
    }
}

