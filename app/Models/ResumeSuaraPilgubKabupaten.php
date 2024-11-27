<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class ResumeSuaraPilgubKabupaten extends Model
{
    protected $table = 'resume_suara_pilgub_kabupaten';

    public function getThreeDigitsId(): string {
        $id = $this->getKey();
        $digits = strlen($id);

        if ($id) {
            if ($digits == 1) {
                return "00$id";
            }
    
            if ($digits == 2) {
                return "0$id";
            }
    
            return $id;
        }

        return '0';
    }

    public function provinsi(): BelongsTo {
        return $this->belongsTo(Provinsi::class, 'provinsi_id');
    }

    public function kabupaten(): BelongsTo {
        return $this->belongsTo(Kabupaten::class, 'id');
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
                DB::raw('(
                    SELECT COALESCE(SUM(suara), 0) 
                    FROM suara_calon sc
                    JOIN tps t ON sc.tps_id = t.id
                    JOIN kelurahan k ON t.kelurahan_id = k.id
                    JOIN kecamatan kc ON k.kecamatan_id = kc.id
                    WHERE sc.calon_id = calon.id 
                    AND kc.kabupaten_id = ' . $this->getKey() . '
                ) + (
                    SELECT COALESCE(SUM(suara), 0)
                    FROM suara_calon_daftar_pemilih scdp
                    JOIN kecamatan kc ON scdp.kecamatan_id = kc.id
                    WHERE scdp.calon_id = calon.id
                    AND kc.kabupaten_id = ' . $this->getKey() . '
                ) as total_suara')
            ])
            ->where('calon.id', $calonId)
            ->first();
    }
}
