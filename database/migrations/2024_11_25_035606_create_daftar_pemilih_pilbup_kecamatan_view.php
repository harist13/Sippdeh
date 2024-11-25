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
                COALESCE(daftar_pemilih.dptb, 0) AS dptb,
                COALESCE(daftar_pemilih.dpk, 0) AS dpk,
                (COALESCE(daftar_pemilih.dptb, 0) + COALESCE(daftar_pemilih.dpk, 0)) AS dpt,
                COALESCE(daftar_pemilih.kotak_kosong, 0) AS kotak_kosong,
                (
                    COALESCE(SUM(suara_calon_daftar_pemilih.suara), 0) +
                    COALESCE(daftar_pemilih.kotak_kosong, 0)
                ) AS suara_sah,
                COALESCE(daftar_pemilih.suara_tidak_sah, 0) AS suara_tidak_sah,
                (
                    (COALESCE(daftar_pemilih.dptb, 0) + COALESCE(daftar_pemilih.dpk, 0)) -
                    (
                        COALESCE(SUM(suara_calon_daftar_pemilih.suara), 0) +
                        COALESCE(daftar_pemilih.kotak_kosong, 0) +
                        COALESCE(daftar_pemilih.suara_tidak_sah, 0)
                    )
                ) AS abstain,
                (
                    COALESCE(SUM(suara_calon_daftar_pemilih.suara), 0) +
                    COALESCE(daftar_pemilih.kotak_kosong, 0) +
                    COALESCE(daftar_pemilih.suara_tidak_sah, 0)
                ) AS suara_masuk,
                CASE
                    WHEN (COALESCE(daftar_pemilih.dptb, 0) + COALESCE(daftar_pemilih.dpk, 0)) > 0
                    THEN
                        ROUND(
                            (
                                COALESCE(SUM(suara_calon_daftar_pemilih.suara), 0) +
                                COALESCE(daftar_pemilih.kotak_kosong, 0) +
                                COALESCE(daftar_pemilih.suara_tidak_sah, 0)
                            ) /
                            (COALESCE(daftar_pemilih.dptb, 0) + COALESCE(daftar_pemilih.dpk, 0)) * 100,
                            1
                        )
                    ELSE 0
                END AS partisipasi,
                kabupaten.id AS kabupaten_id,
                daftar_pemilih.id AS daftar_pemilih_id
            FROM
                kecamatan
            LEFT JOIN
                kabupaten ON kabupaten.id = kecamatan.kabupaten_id
            LEFT JOIN
                daftar_pemilih ON daftar_pemilih.kecamatan_id = kecamatan.id
                AND daftar_pemilih.posisi = 'BUPATI'
            LEFT JOIN
                suara_calon_daftar_pemilih ON suara_calon_daftar_pemilih.kecamatan_id = kecamatan.id
                AND suara_calon_daftar_pemilih.calon_id IN (
                    SELECT id
                    FROM calon
                    WHERE posisi = 'BUPATI'
                )
            GROUP BY
                kecamatan.id, kecamatan.nama, daftar_pemilih.dptb, daftar_pemilih.dpk,
                daftar_pemilih.kotak_kosong, daftar_pemilih.suara_tidak_sah, kabupaten.id, daftar_pemilih.id;
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