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
        Schema::create('akpk_2_selfs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('pegawai_id');
            $table->date('tanggal_pengisian');
            $table->json('manajerial_nilai');
            $table->json('teknis_nilai');
            $table->json('sosiokultura_nilai');
            $table->text('kompetensi_dibutuhkan');
            $table->text('pelatihan_dibutuhkan');
            $table->timestamps();

            $table->foreign('pegawai_id')->references('id')->on('ref_pegawais')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('akpk_2_selfs');
    }
};