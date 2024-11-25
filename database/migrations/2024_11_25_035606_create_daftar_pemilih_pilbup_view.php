<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("CREATE VIEW daftar_pemilih_pilbup_kecamatan_view AS
            SELECT
                kecamatan.id AS id,
                kecamatan.nama AS nama,
                kabupaten.id AS kabupaten_id,
                daftar_pemilih.id AS daftar_pemilih_id,
                COALESCE(daftar_pemilih.dptb, 0) AS dptb,
                COALESCE(daftar_pemilih.dpk, 0) AS dpk
            FROM
                kecamatan
            LEFT JOIN
                kabupaten ON kabupaten.id = kecamatan.kabupaten_id
            LEFT JOIN
                daftar_pemilih ON daftar_pemilih.kecamatan_id = kecamatan.id AND daftar_pemilih.posisi = 'BUPATI';
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP VIEW daftar_pemilih_pilbup_kecamatan_view");
    }
};