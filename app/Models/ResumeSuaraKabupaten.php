<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class ResumeSuaraKabupaten extends Model
{
    protected $table = 'resume_suara_kabupaten';

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

    public function provinsi(): BelongsTo {
        return $this->belongsTo(Provinsi::class, 'provinsi_id');
    }

    public function kecamatan(): HasMany {
        return $this->hasMany(Kecamatan::class, 'kabupaten_id');
    }
    
    public function paslon(): HasMany {
        return $this->hasMany(Calon::class, 'kabupaten_id');
    }

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
            ->leftJoin('kelurahan', 'tps.kelurahan_id', '=', 'kelurahan.id')
            ->leftJoin('kecamatan', 'kelurahan.kecamatan_id', '=', 'kecamatan.id')
            ->where('kecamatan.kabupaten_id', $this->getKey())
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
