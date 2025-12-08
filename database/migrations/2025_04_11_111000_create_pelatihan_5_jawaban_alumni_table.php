<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pelatihan_5_jawaban_alumni', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alumni_id')->constrained('pelatihan_5_pascadiklat_alumni')->onDelete('cascade');
            $table->unsignedInteger('pertanyaan_index');
            $table->text('jawaban');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pelatihan_5_jawaban_alumni');
    }
};
