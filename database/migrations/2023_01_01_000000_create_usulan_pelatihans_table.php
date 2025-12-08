<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsulanPelatihansTable extends Migration
{
    public function up()
    {
        Schema::create('akpk_5_usulankebutuhanpelatihans', function (Blueprint $table) {
            $table->id();
            $table->integer('tahun');
            $table->string('nama_pelatihan');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('usulan_kebutuhan_pelatihans');
    }
}
