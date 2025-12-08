<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
     public function up(): void
    {
        Schema::create('laporan_pelatihan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pelatihan_id');
            $table->string('nip', 50);
            $table->string('judul')->nullable();
            $table->text('ringkasan')->nullable();
            $table->string('file_path')->nullable(); // path file laporan (pdf/doc/docx)
            $table->enum('status', ['pending','approved','rejected'])->default('pending');
            $table->text('catatan_verifikator')->nullable();
            $table->string('verified_by')->nullable(); // simpan nama/ID admin yang verifikasi
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();

            $table->unique(['pelatihan_id', 'nip'], 'uk_laporan_unique_per_user_per_pelatihan');
            $table->index(['pelatihan_id', 'status']);
            // optional FK (boleh diaktifkan kalau sudah siap)
            // $table->foreign('pelatihan_id')->references('id')->on('pbj_1_pelatihans')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan_pelatihan');
    }
};
