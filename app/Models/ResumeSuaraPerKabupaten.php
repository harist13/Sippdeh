<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ResumeSuaraPerKabupaten extends Model
{
    protected $table = 'resume_suara_per_kabupaten';

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
}
