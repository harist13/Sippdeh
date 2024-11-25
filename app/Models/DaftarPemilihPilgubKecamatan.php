<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class DaftarPemilihPilgubKecamatan extends Model
{
    protected $table = 'daftar_pemilih_pilgub_kecamatan_view';

    public function kecamatan(): BelongsTo
    {
        return $this->belongsTo(Kecamatan::class, 'id');
    }

    public function kabupaten(): BelongsTo
    {
        return $this->belongsTo(Kabupaten::class, 'kabupaten_id');
    }

    public function daftarPemilih(): BelongsTo
    {
        return $this->belongsTo(DaftarPemilih::class, 'daftar_pemilih_id');
    }

    /**
     * Get votes for all candidates in this kelurahan
     * Returns collection with candidate info and their vote count
     */
    public function getCalonSuaraByCalonId(int $calonId)
    {
        return Calon::query()
            ->select([
                'calon.id',
                'calon.nama',
                'calon.nama_wakil',
                'calon.posisi',
                DB::raw('COALESCE(SUM(suara_calon_daftar_pemilih.suara), 0) as total_suara')
            ])
            ->leftJoin('suara_calon_daftar_pemilih', 'calon.id', '=', 'suara_calon_daftar_pemilih.calon_id')
            ->leftJoin('kecamatan', 'suara_calon_daftar_pemilih.kecamatan_id', '=', 'kecamatan.id')
            ->where('kecamatan.id', $this->getKey())
            ->where('calon.id', $calonId)
            ->groupBy([
                'calon.id',
                'calon.nama', 
                'calon.nama_wakil',
                'calon.posisi'
            ])
            ->first();
    }
}
