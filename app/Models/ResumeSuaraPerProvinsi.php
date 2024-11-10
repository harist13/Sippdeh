<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ResumeSuaraPerProvinsi extends Model
{
    protected $table = 'resume_suara_per_provinsi';

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
}
