<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class ResumeSuaraPilbupKelurahan extends Model
{
    protected $table = 'resume_suara_pilbup_kelurahan';

    public function getThreeDigitsId(): string {
        $id = $this->getKey();
        $digits = strlen($id);

        if ($digits == 1) {
            return "00$id";
        }

        if ($digits == 2) {
            return "0$id";
        }

        return $id;
    }

    public function tps(): HasMany {
        return $this->hasMany(TPS::class, 'kelurahan_id');
    }

    public function kecamatan(): BelongsTo {
        return $this->belongsTo(Kecamatan::class, 'kecamatan_id');
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
                DB::raw('COALESCE(SUM(suara_calon.suara), 0) as total_suara')
            ])
            ->leftJoin('suara_calon', 'calon.id', '=', 'suara_calon.calon_id')
            ->leftJoin('tps', 'suara_calon.tps_id', '=', 'tps.id')
            ->where('tps.kelurahan_id', $this->getKey())
            ->where('calon.id', $calonId)  // Add this line to filter by calon_id
            ->groupBy([
                'calon.id',
                'calon.nama', 
                'calon.nama_wakil',
                'calon.posisi'
            ])
            ->first();
    }
}
