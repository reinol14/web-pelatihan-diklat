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
        Schema::create('metode_pelatihan', function (Blueprint $table) {
            $table->id('id_metodepelatihan'); // Primary key
            $table->string('kode_pelatihan', 5); // Kolom kode_pelatihan
            $table->enum('metode_pelatihan', ['Blended Learning', 'Klasikal', 'E-learning', ''])->default(''); // Kolom metode_pelatihan
            $table->timestamps(); // Kolom created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('metode_pelatihan');
    }
};
