<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.  $table->string('info_brosur');
            $table->string('link_brosur');
            $table->string('gambar');
     */
    public function up(): void
    {
        Schema::create('brosur_1_infos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('info_brosur');
            $table->string('link_brosur');
            $table->string('gambar');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('brosur_1_infos');
    }
};