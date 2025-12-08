<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('arsip_brosur', function (Blueprint $table) {
            $table->id();
            $table->string('nama_penyelenggara');
            $table->string('alamat');
            $table->string('no_telepon');
            $table->string('nomor_surat');
            $table->string('no_hp');
            $table->string('katalog_pdf');
            $table->string('katalog_excel');
            $table->string('status_ajuan');
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('brosurs');
    }
};
