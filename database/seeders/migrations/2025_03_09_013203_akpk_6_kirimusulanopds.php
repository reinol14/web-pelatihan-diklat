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
        Schema::create('akpk_6_kirimusulanopds', function (Blueprint $table) {
            $table->id();
            $table->integer('tahun');
            $table->integer('jenis_usulan');//usulan baru/revisi
            $table->string('nama_usulan');
            $table->string('keterangan');
            $table->string('file_pdf');
            $table->enum('status',['proses verifikasi','diterima','ditolak']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('akpk_6_kirimusulanopds');
    }
};
