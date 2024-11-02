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
        DB::statement("CREATE VIEW ringkasan_suara_tps AS
            SELECT 
                tps.id AS id,
                tps.nama AS nama,
                COALESCE(suara_tps.dpt, 0) AS dpt,
                COALESCE(SUM(suara_calon.suara), 0) AS suara_sah,
                COALESCE(suara_tps.suara_tidak_sah, 0) AS suara_tidak_sah,
                (COALESCE(suara_tps.dpt, 0) - (COALESCE(SUM(suara_calon.suara), 0) + COALESCE(suara_tps.suara_tidak_sah, 0))) AS jumlah_pengguna_tidak_pilih,
                (COALESCE(SUM(suara_calon.suara), 0) + COALESCE(suara_tps.suara_tidak_sah, 0)) AS suara_masuk,
                CASE 
                    WHEN COALESCE(suara_tps.dpt, 0) > 0
                    THEN ROUND((COALESCE(SUM(suara_calon.suara), 0) + COALESCE(suara_tps.suara_tidak_sah, 0)) / COALESCE(suara_tps.dpt, 0) * 100, 1) 
                    ELSE 0
                END AS partisipasi
            FROM
                tps
            LEFT JOIN
                suara_tps ON suara_tps.tps_id = tps.id
            LEFT JOIN
                suara_calon ON suara_calon.tps_id = tps.id
            GROUP BY
                tps.id, tps.nama, suara_tps.dpt, suara_tps.suara_tidak_sah;
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP VIEW ringkasan_suara_tps");
    }
};