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
        Schema::create('pelaksanaan_pelatihan', function (Blueprint $table) {
            $table->id('id_pelaksanaanpelatihan'); // Primary key
            $table->string('kode_pelaksanaanpelatihan', 5); // Kolom kode_pelaksanaanpelatihan
            $table->enum('pelaksanaan_pelatihan', ['Penyelenggaraan', 'Pengiriman', ''])->default(''); // Hilangkan duplikasi string kosong
            $table->timestamps(); // Kolom created_at dan updated_at
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pelaksanaan_pelatihan');
    }
};
