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
        Schema::create('akpk_11_hasilakhirs', function (Blueprint $table) {
            $table->id();
            $table->integer('tahun');
            $table->integer('nip');
            $table->integer('nip_atasan');
            $table->string('kategori_jabatan');
            $table->integer('standarmin_integritas');
            $table->integer('standarmin_kerjasama');
            $table->integer('standarmin_komunikasi');
            $table->integer('standarmin_orientasi_pada_hasil');
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
            $table->string('self_kompetensi_ditingkatkan');
            $table->string('self_pelatihan_dibutuhkan');
            $table->integer('atasan_integritas');
            $table->integer('atasan_kerjasama');
            $table->integer('atasan_komunikasi');
            $table->integer('atasan_orientasi_pada_hasil');
            $table->integer('atasan_pelayanan_publik');
            $table->integer('atasan_pengembangan_diri');
            $table->integer('atasan_mengelola_perubahan');
            $table->integer('atasan_pengambilan_keputusan');
            $table->integer('atasan_penguasaan_teknologi');
            $table->integer('atasan_keahlian_spesifik');
            $table->integer('atasan_penerapan_prosedur');
            $table->integer('atasan_kemajemukan');
            $table->integer('atasan_menghargai');
            $table->integer('atasan_tolerasi');
            $table->integer('atasan_daya_guna');
            $table->integer('atasan_hubungan_sosial');
            $table->string('atasan_kompetensi_dikembangkan');
            $table->string('atasan_rekomendari_pelatihan');
            $table->string('atasan_alasan_rekomendasi');
            $table->integer('jml_integritas');
            $table->integer('jml_kerjasama');
            $table->integer('jml_komunikasi');
            $table->integer('jml_orientasi_pada_hasil');
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
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('akpk_11_hasilakhirs');
    }
};
