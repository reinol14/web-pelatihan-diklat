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
        Schema::create('akpk_4_usulanpelatihans', function (Blueprint $table) {
            $table->id();
            $table->integer('nip');
            $table->integer('tahun');
            $table->string('nama');
            $table->string('pangkat_golongan');
            $table->string('jabatan');
            $table->string('unitkerja');
            $table->enum('usulansolowasis',['on progres','diterima','tolak']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('akpk_4_usulanpelatihans');
    }
};