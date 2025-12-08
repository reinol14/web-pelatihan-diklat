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
        Schema::create('akpk_8_settingusulsolowases', function (Blueprint $table) {
            $table->id();
            $table->string('tahun');
            $table->string('kode_tampil');
            $table->string('nama_pelatihan ]');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('akpk_8_settingusulsolowases');
    }
};
