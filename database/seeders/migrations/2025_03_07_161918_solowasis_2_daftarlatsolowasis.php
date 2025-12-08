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
        Schema::create('solowasis_2_daftarlatsolowases', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->year('tahun');
            $table->string('nama_pelatihan');
            $table->string('jumlah_jp');
            $table->string('jumlah_peserta');
            $table->string('keterangan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solowasis_2_daftarlatsolowases');
    }
};