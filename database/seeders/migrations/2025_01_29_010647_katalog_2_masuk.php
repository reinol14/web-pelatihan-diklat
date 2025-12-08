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
        Schema::create('katalog_2_masuks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nama_pelatihan');
            $table->string('nama_penyelenggara');
            $table->string('nama_CP');
            $table->string('no_HP');
            $table->string('jenis_pelatihan');
            $table->string('metode_pelatihan');
            $table->string('pelaksanaan_pelatihan');
            $table->string('rumpun_pelatihan');
            $table->text('informasi_pelatihan');
            $table->string('file_pelatihan');
            $table->string('estimasi_biaya');
            $table->enum('status',['proses verifikasi','diterima','ditolak']);
            $table->string('keterangan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('katalog_2_masuks');
    }
};
