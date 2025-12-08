<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('usulan_brosur', function (Blueprint $table) {
            $table->id();
            $table->string('nama_penyelenggara');
            $table->string('alamat');
            $table->string('no_hp');
            $table->string('no_telepon');
            $table->string('nomor_surat');
            $table->enum('status_ajuan', ['Pending', 'Approved', 'Rejected'])->nullable();
            $table->string('katalog_excel', 100)->nullable();  // VARCHAR(100)
            $table->string('katalog_pdf', 100)->nullable();
            $table->timestamps(); // created_at & updated_at
        });
    }


    /**d
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usulan_brosur');
    }
};
