<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class ResumeSuaraPilbupKecamatan extends Model
{
    protected $table = 'resume_suara_pilbup_kecamatan';

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

    public function kabupaten(): BelongsTo {
        return $this->belongsTo(Kabupaten::class, 'kabupaten_id');
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
                DB::raw('(
                    SELECT COALESCE(SUM(suara), 0) 
                    FROM suara_calon sc
                    JOIN tps t ON sc.tps_id = t.id
                    JOIN kelurahan k ON t.kelurahan_id = k.id
                    WHERE sc.calon_id = calon.id 
                    AND k.kecamatan_id = ' . $this->getKey() . '
                ) + (
                    SELECT COALESCE(SUM(suara), 0)
                    FROM suara_calon_daftar_pemilih scdp
                    WHERE scdp.calon_id = calon.id
                    AND scdp.kecamatan_id = ' . $this->getKey() . '
                ) as total_suara')
            ])
            ->where('calon.id', $calonId)
            ->first();
    }
}
