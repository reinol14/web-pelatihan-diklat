<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('pegawai_registrations', function (Blueprint $t) {
      $t->id();

      // Data dasar pendaftar (mirror sebagian ref_pegawais)
      $t->string('nama');
      $t->string('nip', 30)->index();             // simpan sebagai string di staging
      $t->string('email')->index();
      $t->string('no_hp')->nullable();

      $t->string('tempat_lahir')->nullable();
      $t->date('tanggal_lahir')->nullable();
      $t->string('pangkat')->nullable();
      $t->string('golongan')->nullable();
      $t->string('jabatan')->nullable();
      $t->string('jenis_asn')->nullable();
      $t->string('kategori_jabatanasn')->nullable();
      $t->unsignedBigInteger('kode_unitkerja')->nullable()->index();
      $t->string('alamat')->nullable();
      $t->date('tmt')->nullable();

      // status approval
      $t->enum('status', ['pending','approved','rejected'])->default('pending')->index();
      $t->unsignedBigInteger('approved_by')->nullable();
      $t->timestamp('approved_at')->nullable();
      $t->string('approval_note', 500)->nullable();

      // (opsional) simpan path foto upload saat daftar
      $t->string('foto')->nullable();

      $t->timestamps();

      // FK opsional (sesuaikan jika ada tabel unit kerja/admin)
      // $t->foreign('kode_unitkerja')->references('id')->on('unit_kerjas')->nullOnDelete();
      // $t->foreign('approved_by')->references('id')->on('admins')->nullOnDelete();
    });
  }

  public function down(): void {
    Schema::dropIfExists('pegawai_registrations');
  }
};

