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
        Schema::create('akpk_9_standarmins', function (Blueprint $table) {
            $table->id();
            $table->string('kategori_jabatan');
            $table->integer('standarmin_integeregritas');
            $table->integer('standarmin_kerjasama');
            $table->integer('standarmin_komunikasi');
            $table->integer('standarmin_orientasipada_hasil');
            $table->integer('standarmin_pelayanan_publik');
            $table->integer('standarmin_pengembangan_diri');
            $table->integer('standarmin_mengelola_perubahan');
            $table->integer('standarmin_pengambilan_keputusan');
            $table->integer('standarmin_penguasaan_teknologi');
            $table->integer('standarmin_keahlian_spesifik');
            $table->integer('standarmin_penerapan_prosedur');
            $table->integer('standarmin_kemajemukan');
            $table->integer('standarmin_menghargai');
            $table->integer('standarmin_tolerasi');
            $table->integer('standarmin_daya_guna');
            $table->integer('standarmin_hubungan_sosial');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('akpk_9_standarmins');
    }
};
