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
        Schema::create('draft_katalog', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->integer('no_telp');
            $table->integer('no_hp');
            $table->text('alamat');
            $table->enum('status_ajuan',['sudah','belum']);
            $table->timestamps();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('draft_katalog');
    }
};
