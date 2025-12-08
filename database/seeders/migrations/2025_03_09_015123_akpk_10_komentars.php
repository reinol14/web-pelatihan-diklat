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
        Schema::create('akpk_10_komentars', function (Blueprint $table) {
            $table->id();
            $table->string('kategori_jabatan');
            $table->integer('jml_integritas');
            $table->integer('jml_kerjasama');
            $table->integer('jml_komunikasi');
            $table->integer('jml_orientasipada_hasil');
            $table->integer('jml_pelayanan_publik');
            $table->integer('jml_pengembangan_diri');
            $table->integer('jml_mengelola_perubahan');
            $table->integer('jml_pengambilan_keputusan');
            $table->integer('jml_penguasaan_teknologi');
            $table->integer('jml_keahlian_spesifik');
            $table->integer('jml_penerapan_prosedur');
            $table->integer('jml_kemajemukan');
            $table->integer('jml_menghargai');
            $table->integer('jml_tolerasi');
            $table->integer('jml_daya_guna');
            $table->integer('jml_hubungan_sosial');
            $table->string('komentar_integritas');
            $table->string('komentar_kerjasama');
            $table->string('komentar_komunikasi');
            $table->string('komentar_orientasi_pada_hasil');
            $table->string('komentar_pelayanan_publik');
            $table->string('komentar_pengembangan_diri');
            $table->string('komentar_mengelola_perubahan');
            $table->string('komentar_pengambilan_keputusan');
            $table->string('komentar_penguasaan_teknologi');
            $table->string('komentar_keahlian_spesifik');
            $table->string('komentar_penerapan_prosedur');
            $table->string('komentar_kemajemukan');
            $table->string('komentar_menghargai');
            $table->string('komentar_tolerasi');
            $table->string('komentar_daya_guna');
            $table->string('komentar_hubungan_sosial');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('akpk_10_komentars');
    }
};
