<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('directory_2_laporans', function (Blueprint $table) {
            $table->id();
            $table->integer('nip');
            $table->string('nama');
            $table->string('golongan_ruang');
            $table->string('jabatan');
            $table->string('unit_kerja');
            $table->string('email');
            $table->string('foto');
            $table->string('nama_pelatihan');
            $table->string('pelaksanaan_pelatihan');
            $table->string('jenis_pelatihan');
            $table->string('metode_pelatihan');
            $table->string('rumpun_pelatihan');
            $table->string('penyelenggara_pelatihan');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->enum('hasil_pelatihan',['lulus','tidak lulus']);
            $table->string('sertifikat');
            $table->string('judul_laporan');
            $table->string('abstrak_laporan');
            $table->string('link_laporan');
            $table->enum('Status_peserta',['Alumni',' Non Alumni']);
            $table->string('keterangan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('directory_2_laporans');
    }
};