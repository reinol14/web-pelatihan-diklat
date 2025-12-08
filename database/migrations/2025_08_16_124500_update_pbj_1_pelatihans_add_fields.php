<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pbj_1_pelatihans', function (Blueprint $table) {
            if (!Schema::hasColumn('pbj_1_pelatihans', 'id_katalog2')) {
                $table->unsignedBigInteger('id_katalog2')->nullable()->after('id');
            }
            if (!Schema::hasColumn('pbj_1_pelatihans', 'kuota')) {
                $table->integer('kuota')->default(0)->after('id_katalog2');
            }
            if (!Schema::hasColumn('pbj_1_pelatihans', 'pelaksanaan')) {
                $table->string('pelaksanaan')->nullable()->after('kuota');
            }

            // tambahkan foreign key jika tabel katalog_2_masuks ada
            if (Schema::hasTable('katalog_2_masuks')) {
                $table->foreign('id_katalog2')->references('id')->on('katalog_2_masuks')->onDelete('set null');
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
            if (Schema::hasColumn('pbj_1_pelatihans', 'id_katalog2')) {
                // drop foreign key first if exists
                $sm = Schema::getConnection()->getDoctrineSchemaManager();
                $doctrineTable = $sm->listTableDetails('pbj_1_pelatihans');
                if ($doctrineTable->hasForeignKey('pbj_1_pelatihans_id_katalog2_foreign')) {
                    $table->dropForeign('pbj_1_pelatihans_id_katalog2_foreign');
                }
                $table->dropColumn('id_katalog2');
            }
            if (Schema::hasColumn('pbj_1_pelatihans', 'kuota')) {
                $table->dropColumn('kuota');
            }
            if (Schema::hasColumn('pbj_1_pelatihans', 'pelaksanaan')) {
                $table->dropColumn('pelaksanaan');
            }
        });
    }
};
