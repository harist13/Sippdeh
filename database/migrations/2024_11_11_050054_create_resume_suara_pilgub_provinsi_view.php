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
        DB::statement("CREATE VIEW resume_suara_pilgub_provinsi AS
            SELECT
                provinsi.id AS id,
                provinsi.nama AS nama,
                COALESCE(SUM(resume_suara_pilgub_tps.dpt), 0) AS dpt,
                COALESCE(SUM(resume_suara_pilgub_tps.kotak_kosong), 0) AS kotak_kosong,
                COALESCE(SUM(resume_suara_pilgub_tps.suara_sah), 0) AS suara_sah,
                COALESCE(SUM(resume_suara_pilgub_tps.suara_tidak_sah), 0) AS suara_tidak_sah,
                COALESCE(SUM(resume_suara_pilgub_tps.suara_masuk), 0) AS suara_masuk,
                COALESCE(SUM(resume_suara_pilgub_tps.abstain), 0) AS abstain,
                CASE 
                    WHEN COALESCE(SUM(resume_suara_pilgub_tps.dpt), 0) > 0
                    THEN ROUND(((COALESCE(SUM(resume_suara_pilgub_tps.suara_sah), 0) + COALESCE(SUM(resume_suara_pilgub_tps.suara_tidak_sah), 0)) / COALESCE(SUM(resume_suara_pilgub_tps.dpt), 0)) * 100, 1)
                    ELSE 0 
                END AS partisipasi
            FROM 
                provinsi
            LEFT JOIN 
                kabupaten ON kabupaten.provinsi_id = provinsi.id
            LEFT JOIN 
                kecamatan ON kecamatan.kabupaten_id = kabupaten.id
            LEFT JOIN 
                kelurahan ON kelurahan.kecamatan_id = kecamatan.id
            LEFT JOIN 
                tps ON tps.kelurahan_id = kelurahan.id
            LEFT JOIN 
                resume_suara_pilgub_tps ON resume_suara_pilgub_tps.id = tps.id
            GROUP BY 
                provinsi.id;
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP VIEW resume_suara_pilgub_provinsi");
    }
};