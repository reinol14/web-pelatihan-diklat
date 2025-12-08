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
        Schema::create('pelatihan_4_alumni', function (Blueprint $table) {
            $table->id();
            $table->integer('nip');
            $table->string('nama');
            $table->string('pangkat_golongan');
            $table->string('jabatan');
            $table->string('unitkerja');
            $table->string('pelaksanaan_pelatihan');
            $table->string('jenis_pelatihan');
            $table->string('nama_pelatihan');
            $table->string('penyelenggara_pelatihan');
            $table->date('mulai_pelatihan');
            $table->date('selesai_pelatihan');
            $table->string('biaya');
            $table->string('laporan');
            $table->enum('hasil_pelatihan',['lulus','tidak lulus']);
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pelatihan_4_alumni');
    }
};