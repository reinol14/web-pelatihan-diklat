<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pbj_1_pelatihans', function (Blueprint $table) {
            if (!Schema::hasColumn('pbj_1_pelatihans', 'jenis_pelatihan')) {
                $table->string('jenis_pelatihan')->nullable()->after('nama_pelatihan');
            }
            if (!Schema::hasColumn('pbj_1_pelatihans', 'metode_pelatihan')) {
                $table->string('metode_pelatihan')->nullable()->after('jenis_pelatihan');
            }
            if (!Schema::hasColumn('pbj_1_pelatihans', 'penyelenggara')) {
                $table->string('penyelenggara')->nullable()->after('metode_pelatihan');
            }
            if (!Schema::hasColumn('pbj_1_pelatihans', 'metode')) {
                $table->string('metode')->nullable()->after('penyelenggara');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pbj_1_pelatihans', function (Blueprint $table) {
            if (Schema::hasColumn('pbj_1_pelatihans', 'jenis_pelatihan')) {
                $table->dropColumn('jenis_pelatihan');
            }
            if (Schema::hasColumn('pbj_1_pelatihans', 'metode_pelatihan')) {
                $table->dropColumn('metode_pelatihan');
            }
            if (Schema::hasColumn('pbj_1_pelatihans', 'penyelenggara')) {
                $table->dropColumn('penyelenggara');
            }
            if (Schema::hasColumn('pbj_1_pelatihans', 'metode')) {
                $table->dropColumn('metode');
            }
        });
    }
};
