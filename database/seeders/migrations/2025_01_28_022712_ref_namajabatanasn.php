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
    Schema::create('ref_namajabatanasns', function (Blueprint $table) {
        $table->bigIncrements('id');
        $table->longText('kode_jabatanasn');
        $table->longText('jabatanasn');
        $table->longText('kategori_jabatanasn');
        $table->timestamps();
    });
}

/**
 * Reverse the migrations.
 */
public function down(): void
{
    Schema::dropIfExists('ref_namajabatanasns');
}
};