<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('pelatihan_5_pascadiklat_alumni', function (Blueprint $table) {
            $table->bigIncrements('alumni_id'); // ✅ benar, ini bikin kolom auto-increment primary key
            $table->integer('nip');
            $table->string('nama');
            $table->string('pangkat');
            $table->string('golongan');
            $table->string('jabatan');
            $table->string('unit_kerja');
            $table->string('nama_pelatihan');
            $table->enum('kode_jenispelatihan', ['JP001', 'JP002', 'JP003', 'JP004']);
            $table->integer('nip_atasan');
            $table->string('nama_atasan');
            $table->integer('nip_rekankerja');
            $table->string('nama_rekankerja');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pelatihan_5_pascadiklat_alumni');
    }
};
