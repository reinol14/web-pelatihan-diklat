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
        Schema::create('ipasn_3_nilaiperasns', function (Blueprint $table) {
            $table->id();
            $table->string('tahun');
            $table->integer('nip');
            $table->string('nama');
            $table->string('jabatan');
            $table->string('unit_kerja');
            $table->integer('nilai_pendidikan');
            $table->integer('nilai_kinerja');
            $table->integer('nilai_disiplin');
            $table->integer('nilai_bangkom');
            $table->integer('nilai_totalipasn');
            $table->integer('link_filepenetapanbkd');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ipasn_3_nilaiperasns');
    }
};
