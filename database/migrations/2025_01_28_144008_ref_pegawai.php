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
        Schema::create('ref_pegawais', function (Blueprint $table) {
            $table->id();
            $table->integer('nip');
            $table->string('nama');
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->string('pangkat');
            $table->string('golongan');
            $table->string('jabatan');
            $table->string('jenis_asn');
            $table->string('kategori_jabatanasn');
            $table->unsignedBigInteger('kode_unitkerja'); // Foreign key kolom
            $table->string('email'); 
            $table->string('no_hp');
            $table->string('alamat');
            $table->date('tmt');
            $table->string('foto');
            $table->BigInteger('id_atasan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ref_pegawais');
    }
};
