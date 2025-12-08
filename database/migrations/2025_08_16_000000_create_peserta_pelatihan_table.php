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
        Schema::create('peserta_pelatihan', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('id_katalog2');
            $table->string('status')->default('terdaftar');
            $table->timestamps();

            $table->unique(['user_id', 'id_katalog2']);
            // Jika ingin menambahkan foreign key, bisa diaktifkan nanti jika tabel users & katalog_2_masuks sesuai
            // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            // $table->foreign('id_katalog2')->references('id')->on('katalog_2_masuks')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peserta_pelatihan');
    }
};
