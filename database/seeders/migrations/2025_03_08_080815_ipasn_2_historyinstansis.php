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
        Schema::create('ipasn_2_historyinstansis', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->date('tanggal_penetapan');
            $table->integer('nilai');
            $table->string('link_bkn');
            $table->string('link_bkpsdm');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ipasn_2_historyinstansis');
    }
};
