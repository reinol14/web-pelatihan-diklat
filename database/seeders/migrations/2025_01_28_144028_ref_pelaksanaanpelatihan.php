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
        Schema::create('ref_pelaksanaanpelatihans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('kode_pelaksanaanpelatihan');
            $table->string('pelaksanaan_pelatihan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ref_pelaksanaanpelatihans');
    }
};
