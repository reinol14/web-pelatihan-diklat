<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('akpk_3_penilaianbawahans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('id_atasan')->nullable();
            $table->string('nip_bawahan');
            $table->date('tanggal_pengisian');
            $table->json('manajerial_nilai');
            $table->json('teknis_nilai');
            $table->json('sosiokultural_nilai');
            $table->text('kompetensi_dibutuhkan');
            $table->text('pelatihan_dibutuhkan');
            $table->json('manajerial_keterangan')->nullable();
            $table->json('teknis_keterangan')->nullable();
            $table->json('sosiokultural_keterangan')->nullable();
            $table->timestamps();

            $table->foreign('pegawai_id')->references('id')->on('ref_pegawais')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('akpk_3_penilaianbawahans');
    }
};
