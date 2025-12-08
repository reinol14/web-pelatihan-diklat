<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('pelatihan_5_pertanyaan', function (Blueprint $table) {
            $table->id();
            $table->enum('kode_jenispelatihan', ['JP001', 'JP002', 'JP003', 'JP004']);
            $table->string('kode_kategoripertanyaan');
            $table->string('pertanyaan');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pelatihan_5_pertanyaan');
    }
};

