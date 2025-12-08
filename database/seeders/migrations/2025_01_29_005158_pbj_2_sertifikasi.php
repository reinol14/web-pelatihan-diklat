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
        Schema::create('pbj_2_sertifikasis', function (Blueprint $table) {
            $table->id();
            $table->integer('nip');
            $table->string('nama');
            $table->string('pangkat_golongan');
            $table->string('jabatan');
            $table->string('unitkerja');
            $table->string('nama_pelatihan');
            $table->string('nama_sertifikasi');
            $table->date('tanggal_sertifikasi');
            $table->enum('hasil_sertifikasi',['lulus','tidaklulus']);
            $table->string('sertifikat');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pbj_2_sertifikasis');
    }
};