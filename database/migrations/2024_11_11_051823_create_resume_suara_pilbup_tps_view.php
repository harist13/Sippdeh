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
        DB::statement("CREATE VIEW resume_suara_pilbup_tps AS
            SELECT
                tps.id AS id,
                tps.nama AS nama,
                COALESCE(tps.kelurahan_id, null) AS kelurahan_id,
                COALESCE(tps.dpt, 0) AS dpt,
                COALESCE(suara_tps.kotak_kosong, 0) AS kotak_kosong,
                (COALESCE(SUM(suara_calon.suara), 0) + COALESCE(suara_tps.kotak_kosong, 0)) AS suara_sah,
                COALESCE(suara_tps.suara_tidak_sah, 0) AS suara_tidak_sah,
                (COALESCE(tps.dpt, 0) - ((COALESCE(SUM(suara_calon.suara), 0) + COALESCE(suara_tps.kotak_kosong, 0)) + COALESCE(suara_tps.suara_tidak_sah, 0))) AS abstain,
                ((COALESCE(SUM(suara_calon.suara), 0) + COALESCE(suara_tps.kotak_kosong, 0)) + COALESCE(suara_tps.suara_tidak_sah, 0)) AS suara_masuk,
                CASE
                    WHEN COALESCE(tps.dpt, 0) > 0
                    THEN ROUND(((COALESCE(SUM(suara_calon.suara), 0) + COALESCE(suara_tps.kotak_kosong, 0) + COALESCE(suara_tps.suara_tidak_sah, 0)) / COALESCE(tps.dpt, 0)) * 100, 1)
                    ELSE 0
                END AS partisipasi
            FROM
                tps
            LEFT JOIN
                suara_tps ON suara_tps.tps_id = tps.id AND suara_tps.posisi = 'BUPATI'
            LEFT JOIN
                suara_calon ON suara_calon.tps_id = tps.id
                AND suara_calon.calon_id IN (SELECT id FROM calon WHERE posisi = 'BUPATI')
            GROUP BY
                tps.id, tps.nama, tps.dpt, suara_tps.kotak_kosong, suara_tps.suara_tidak_sah;
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP VIEW resume_suara_pilbup_tps");
    }
};