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
        Schema::create('brosur_2_masuks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nama_penyelenggara');
            $table->string('alamat');
            $table->string('nama_sales');
            $table->string('no_hp');
            $table->string('no_surat');
            $table->string('tanggal_surat');
            $table->string('brosur_excel');
            $table->string('brosur_pdf');
            $table->enum('status',['proses verifikasi','diterima','ditolak']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('brosur_2_masuks');
    }
};