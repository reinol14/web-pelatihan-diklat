<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiklatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('diklats', function (Blueprint $table) {
            $table->id();
            $table->string('jenis_diklat');
            $table->string('nama_diklat');
            $table->string('rumpun');
            $table->string('kode_jabatan');
            $table->string('penyelenggara');
            $table->date('tanggal_pelaksanaan'); // Kolom tanggal_pelaksanaan
            $table->string('tempat_pelaksanaan'); // Kolom tempat_pelaksanaan
            $table->string('metode_pelaksanaan'); // Kolom metode_pelaksanaan
            $table->string('jenis_biaya'); // Kolom jenis_biaya
            $table->decimal('biaya_per_orang', 10, 2); // Kolom biaya_per_orang
            $table->string('link_katalog')->nullable(); // Kolom link_katalog
            $table->timestamps(); // Menambahkan kolom created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('diklats');
    }
}

