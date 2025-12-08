<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('unit_kerja', function (Blueprint $table) {
            $table->id('id');
            $table->integer('kode_unitkerja')->length(6);
            $table->string('sub_unitkerja', 200);
            $table->string('unitkerja', 200);
            $table->string('singkatan', 100);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unit_kerja');
    }
};
