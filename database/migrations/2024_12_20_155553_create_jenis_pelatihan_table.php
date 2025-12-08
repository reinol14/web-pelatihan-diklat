<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('jenis_pelatihan', function (Blueprint $table) {
            $table->id('id_jenispelatihan');
            $table->string('kode_pelatihan', 5);
            $table->enum('jenis_pelatihan', ['Diklat Teknis', 'Diklat Kepemimpinan', 'Diklat Dasar', ''])->default('');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jenis_pelatihan');
    }
};
