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
        Schema::create('ref_golongans', function (Blueprint $table) {
            $table->id();
            $table->string('kode_golongan');
            $table->string('jenis_asn');
            $table->string('golongan');
            $table->string('pangkat');
            $table->string('pangkat_golongan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ref_golongans');
    }
};
