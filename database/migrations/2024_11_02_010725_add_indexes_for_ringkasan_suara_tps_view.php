<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tps', function (Blueprint $table) {
            $table->index('nama'); // Index for tps.nama
        });

        Schema::table('suara_tps', function (Blueprint $table) {
            $table->index('dpt'); // Index for suara_tps.dpt
            $table->index('kotak_kosong'); // Index for suara_tps.kotak_kosong
            $table->index('suara_tidak_sah'); // Index for suara_tps.suara_tidak_sah
        });

        Schema::table('suara_calon', function (Blueprint $table) {
            $table->index('suara'); // Index for suara_calon.suara
        });

        Schema::table('kabupaten', function (Blueprint $table) {
            $table->index('nama'); // Index for kabupaten.nama
        });

        Schema::table('kecamatan', function (Blueprint $table) {
            $table->index('nama'); // Index for kecamatan.nama
        });

        Schema::table('kelurahan', function (Blueprint $table) {
            $table->index('nama'); // Index for kelurahan.nama
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tps', function (Blueprint $table) {
            $table->dropIndex(['nama']);
        });

        Schema::table('suara_tps', function (Blueprint $table) {
            $table->dropIndex(['dpt']);
            $table->dropIndex(['suara_tidak_sah']);
        });

        Schema::table('suara_calon', function (Blueprint $table) {
            $table->dropIndex(['suara']);
        });

        Schema::table('kabupaten', function (Blueprint $table) {
            $table->dropIndex(['nama']);
        });

        Schema::table('kecamatan', function (Blueprint $table) {
            $table->dropIndex(['nama']);
        });

        Schema::table('kelurahan', function (Blueprint $table) {
            $table->dropIndex(['nama']);
        });
    }
};