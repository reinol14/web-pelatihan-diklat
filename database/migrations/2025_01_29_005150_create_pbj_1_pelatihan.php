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
        Schema::create('pbj_1_pelatihans', function (Blueprint $table) {
            $table->id();
            // Session / Pelatihan metadata
            $table->unsignedBigInteger('id_katalog2')->nullable();
            $table->string('nama_pelatihan');
            $table->string('jenis_pelatihan')->nullable();
            $table->string('metode_pelatihan')->nullable();
            $table->string('penyelenggara')->nullable();
            $table->string('metode')->nullable();
            $table->integer('kuota')->default(0);
            $table->string('pelaksanaan')->nullable();
            $table->string('lokasi')->nullable();
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->enum('status', ['aktif', 'tutup'])->default('aktif');
            $table->timestamps();
        });

        // Tambahkan foreign key ke katalog_2_masuks jika tabel tersebut sudah ada
        if (Schema::hasTable('katalog_2_masuks')) {
            Schema::table('pbj_1_pelatihans', function (Blueprint $table) {
                $table->foreign('id_katalog2')->references('id')->on('katalog_2_masuks')->onDelete('set null');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pbj_1_pelatihans');
    }
};