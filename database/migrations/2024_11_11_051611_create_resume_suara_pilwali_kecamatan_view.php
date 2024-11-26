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
        DB::statement("CREATE VIEW resume_suara_pilwali_kecamatan AS
            SELECT
                kecamatan.id AS id,
                kecamatan.nama AS nama,
                kecamatan.kabupaten_id AS kabupaten_id,

                -- DPT
                COALESCE(SUM(resume_suara_pilwali_kelurahan.dpt), 0) AS dpt,

                -- Kotak Kosong
                (
                    COALESCE(SUM(resume_suara_pilwali_kelurahan.kotak_kosong), 0)
                    +
                    COALESCE(daftar_pemilih_pilwali_kecamatan_view.kotak_kosong, 0)
                ) AS kotak_kosong,

                -- Suara Sah
                (
                    COALESCE(SUM(resume_suara_pilwali_kelurahan.suara_sah), 0)
                    +
                    COALESCE(daftar_pemilih_pilwali_kecamatan_view.suara_sah, 0)
                ) AS suara_sah,

                -- Suara Tidak Sah
                (
                    COALESCE(SUM(resume_suara_pilwali_kelurahan.suara_tidak_sah), 0)
                    +
                    COALESCE(daftar_pemilih_pilwali_kecamatan_view.suara_tidak_sah, 0)
                ) AS suara_tidak_sah,

                -- Suara Masuk
                (
                    COALESCE(SUM(resume_suara_pilwali_kelurahan.suara_masuk), 0)
                    +
                    COALESCE(daftar_pemilih_pilwali_kecamatan_view.suara_masuk, 0)
                ) AS suara_masuk,
                
                COALESCE(SUM(resume_suara_pilwali_kelurahan.abstain), 0) AS abstain,

                CASE 
                    WHEN COALESCE(SUM(resume_suara_pilwali_kelurahan.dpt), 0) > 0 
                    THEN ROUND(
                        (
                            (
                                -- (Suara Sah + Suara Tidak Sah) / (DPT + DPTb + DPK)
                                (
                                    COALESCE(SUM(resume_suara_pilwali_kelurahan.suara_sah), 0)
                                    +
                                    COALESCE(daftar_pemilih_pilwali_kecamatan_view.suara_sah, 0)
                                )
                                +
                                (
                                    COALESCE(SUM(resume_suara_pilwali_kelurahan.suara_tidak_sah), 0)
                                    +
                                    COALESCE(daftar_pemilih_pilwali_kecamatan_view.suara_tidak_sah, 0)
                                )
                            )
                            /
                            (
                                COALESCE(SUM(resume_suara_pilwali_kelurahan.dpt), 0)
                                +
                                COALESCE(daftar_pemilih_pilwali_kecamatan_view.dptb, 0)
                                +
                                COALESCE(daftar_pemilih_pilwali_kecamatan_view.dpk, 0)
                            )
                        ) * 100, 1
                    )
                    ELSE 0 
                END AS partisipasi
            FROM 
                kecamatan
            LEFT JOIN 
                resume_suara_pilwali_kelurahan ON resume_suara_pilwali_kelurahan.kecamatan_id = kecamatan.id
            LEFT JOIN 
                daftar_pemilih_pilwali_kecamatan_view ON daftar_pemilih_pilwali_kecamatan_view.id = kecamatan.id
            GROUP BY 
                kecamatan.id,
                daftar_pemilih_pilwali_kecamatan_view.dptb,
                daftar_pemilih_pilwali_kecamatan_view.dpk,
                daftar_pemilih_pilwali_kecamatan_view.kotak_kosong,
                daftar_pemilih_pilwali_kecamatan_view.suara_sah,
                daftar_pemilih_pilwali_kecamatan_view.suara_tidak_sah,
                daftar_pemilih_pilwali_kecamatan_view.suara_masuk;
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP VIEW resume_suara_pilwali_kecamatan");
    }
};