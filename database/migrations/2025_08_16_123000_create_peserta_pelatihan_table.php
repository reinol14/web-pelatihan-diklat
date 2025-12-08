<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('peserta_pelatihan', function (Blueprint $table) {
            // kolom baru untuk guard pegawais
            if (!Schema::hasColumn('peserta_pelatihan', 'pegawai_id')) {
                $table->unsignedBigInteger('pegawai_id')->nullable()->after('user_id');
                $table->index('pegawai_id');
                $table->foreign('pegawai_id')->references('id')->on('pegawais')->onDelete('cascade');
            }

            // unik: satu pegawai hanya boleh 1 baris per sesi
            // (biarkan unique berbasis user_id jika kamu juga pakai user biasa)
            if (!collect(Schema::getColumnListing('peserta_pelatihan'))->contains('pelatihan_id')) {
                // abaikan; hanya jaga-jaga
            }
            // beri nama index agar mudah di-drop kalau perlu
            $table->unique(['pelatihan_id','pegawai_id'], 'uniq_peserta_pelatihan_pegawai');
        });
    }

    public function down(): void {
        Schema::table('peserta_pelatihan', function (Blueprint $table) {
            $table->dropUnique('uniq_peserta_pelatihan_pegawai');
            if (Schema::hasColumn('peserta_pelatihan', 'pegawai_id')) {
                $table->dropForeign(['pegawai_id']);
                $table->dropColumn('pegawai_id');
            }
        });
    }
};