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
        Schema::create('solowasis_1_infos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->longText('info_solowasis');
            $table->string('gambar_solowasis');
            $table->string('link_solowasis');
            $table->string('keterangan');
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solowasis_1_infos');
    }
};