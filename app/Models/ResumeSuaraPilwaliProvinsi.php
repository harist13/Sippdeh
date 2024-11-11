<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class ResumeSuaraPilwaliProvinsi extends Model
{
    protected $table = 'resume_suara_pilwali_provinsi';

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

    public function kabupaten(): HasMany {
        return $this->hasMany(Kabupaten::class, 'provinsi_id');
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
            ->leftJoin('kabupaten', 'kecamatan.kabupaten_id', '=', 'kabupaten.id')
            ->where('kabupaten.provinsi_id', $this->getKey())
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
