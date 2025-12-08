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
        Schema::create('pelatihan_2_usulans', function (Blueprint $table) {
            $table->id();
            $table->integer('nip');
            $table->string('nama');
            $table->string('pangkat_golongan');
            $table->string('jabatan');
            $table->string('unitkerja');
            $table->string('no_hp');
            $table->string('nama_pelatihan');
            $table->string('pelaksanaan_pelatihan');
            $table->string('metode_pelatihan');
            $table->string('jenis_pelatihan');
            $table->string('penyelenggara_pelatihan');
            $table->string('tempat_pelatihan');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->string('estimasi_biaya');
            $table->string('file_penawaran');
            $table->string('file_usulan');
            $table->string('keterangan');
            $table->enum('status',['proses verifikasi','diterima','ditolak']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pelatihan_2_registers');
    }
};