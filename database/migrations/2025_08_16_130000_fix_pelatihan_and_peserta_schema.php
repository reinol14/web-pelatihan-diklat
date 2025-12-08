<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class FixPelatihanAndPesertaSchema extends Migration
{
    /**
     * Run the migrations.
     * This migration is defensive: it only adds columns if missing and copies data where appropriate.
     */
    public function up()
    {
        // Ensure pbj_1_pelatihans has the session columns we expect
        if (Schema::hasTable('pbj_1_pelatihans')) {
            Schema::table('pbj_1_pelatihans', function (Blueprint $table) {
                if (!Schema::hasColumn('pbj_1_pelatihans', 'id_katalog2')) {
                    $table->unsignedBigInteger('id_katalog2')->nullable()->after('id');
                }
                if (!Schema::hasColumn('pbj_1_pelatihans', 'nama_pelatihan')) {
                    $table->string('nama_pelatihan')->nullable()->after('id_katalog2');
                }
                if (!Schema::hasColumn('pbj_1_pelatihans', 'kuota')) {
                    $table->integer('kuota')->default(0)->after('nama_pelatihan');
                }
                if (!Schema::hasColumn('pbj_1_pelatihans', 'pelaksanaan')) {
                    $table->string('pelaksanaan')->nullable()->after('kuota');
                }
                if (!Schema::hasColumn('pbj_1_pelatihans', 'lokasi')) {
                    $table->string('lokasi')->nullable()->after('pelaksanaan');
                }
                if (!Schema::hasColumn('pbj_1_pelatihans', 'tanggal_mulai')) {
                    $table->date('tanggal_mulai')->nullable()->after('lokasi');
                }
                if (!Schema::hasColumn('pbj_1_pelatihans', 'tanggal_selesai')) {
                    $table->date('tanggal_selesai')->nullable()->after('tanggal_mulai');
                }
                if (!Schema::hasColumn('pbj_1_pelatihans', 'status')) {
                    // use string for compatibility; the app uses a few status labels
                    $table->string('status')->default('draft')->after('tanggal_selesai');
                }
            });

            // add FK to katalog_2_masuks if that table exists (best-effort)
            if (Schema::hasTable('katalog_2_masuks') && Schema::hasColumn('pbj_1_pelatihans', 'id_katalog2')) {
                try {
                    Schema::table('pbj_1_pelatihans', function (Blueprint $table) {
                        $table->foreign('id_katalog2')->references('id')->on('katalog_2_masuks')->onDelete('set null');
                    });
                } catch (\Exception $e) {
                    // ignore - FK may already exist or DB may not support duplicate FK names
                }
            }
        }

        // Ensure peserta_pelatihan exists and has the participant columns we expect
        if (!Schema::hasTable('peserta_pelatihan')) {
            Schema::create('peserta_pelatihan', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('user_id')->nullable();
                $table->unsignedBigInteger('pelatihan_id')->nullable();
                $table->string('nip')->nullable();
                $table->string('nama')->nullable();
                $table->string('pangkat_golongan')->nullable();
                $table->string('jabatan')->nullable();
                $table->string('unitkerja')->nullable();
                $table->text('hasil_pelatihan')->nullable();
                $table->string('sertifikat')->nullable();
                $table->string('status')->default('registered');
                $table->timestamps();
            });
        } else {
            Schema::table('peserta_pelatihan', function (Blueprint $table) {
                if (!Schema::hasColumn('peserta_pelatihan', 'pelatihan_id')) {
                    $table->unsignedBigInteger('pelatihan_id')->nullable()->after('user_id');
                }
                if (!Schema::hasColumn('peserta_pelatihan', 'nip')) {
                    $table->string('nip')->nullable()->after('pelatihan_id');
                }
                if (!Schema::hasColumn('peserta_pelatihan', 'nama')) {
                    $table->string('nama')->nullable()->after('nip');
                }
                if (!Schema::hasColumn('peserta_pelatihan', 'pangkat_golongan')) {
                    $table->string('pangkat_golongan')->nullable()->after('nama');
                }
                if (!Schema::hasColumn('peserta_pelatihan', 'jabatan')) {
                    $table->string('jabatan')->nullable()->after('pangkat_golongan');
                }
                if (!Schema::hasColumn('peserta_pelatihan', 'unitkerja')) {
                    $table->string('unitkerja')->nullable()->after('jabatan');
                }
                if (!Schema::hasColumn('peserta_pelatihan', 'hasil_pelatihan')) {
                    $table->text('hasil_pelatihan')->nullable()->after('unitkerja');
                }
                if (!Schema::hasColumn('peserta_pelatihan', 'sertifikat')) {
                    $table->string('sertifikat')->nullable()->after('hasil_pelatihan');
                }
                if (!Schema::hasColumn('peserta_pelatihan', 'status')) {
                    $table->string('status')->default('registered')->after('sertifikat');
                }
            });

            // If legacy id_katalog2 column exists, migrate its values into pelatihan_id and drop it
            if (Schema::hasColumn('peserta_pelatihan', 'id_katalog2')) {
                // ensure pelatihan_id exists before copying
                if (!Schema::hasColumn('peserta_pelatihan', 'pelatihan_id')) {
                    Schema::table('peserta_pelatihan', function (Blueprint $table) {
                        $table->unsignedBigInteger('pelatihan_id')->nullable()->after('user_id');
                    });
                }

                // copy values safely
                try {
                    DB::statement('UPDATE peserta_pelatihan SET pelatihan_id = id_katalog2 WHERE pelatihan_id IS NULL');
                } catch (\Exception $e) {
                    // ignore if update fails
                }

                // drop legacy column if present
                try {
                    Schema::table('peserta_pelatihan', function (Blueprint $table) {
                        if (Schema::hasColumn('peserta_pelatihan', 'id_katalog2')) {
                            $table->dropColumn('id_katalog2');
                        }
                    });
                } catch (\Exception $e) {
                    // ignore - some DBs cannot drop column in certain configurations
                }
            }

            // Add FK to pbj_1_pelatihans if possible
            if (Schema::hasTable('pbj_1_pelatihans') && Schema::hasColumn('peserta_pelatihan', 'pelatihan_id')) {
                try {
                    Schema::table('peserta_pelatihan', function (Blueprint $table) {
                        $table->foreign('pelatihan_id')->references('id')->on('pbj_1_pelatihans')->onDelete('cascade');
                    });
                } catch (\Exception $e) {
                    // ignore duplicate FK errors
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     * We intentionally keep down() minimal to avoid accidental data loss.
     */
    public function down()
    {
        // no-op: rolling back this migration may drop columns and lose data; handle manually if needed
    }
}
