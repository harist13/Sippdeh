<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class TPS extends Model
{
    use HasFactory;

    protected $table = 'tps';

    protected $fillable = ['nama', 'alamat', 'kelurahan_id'];

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

    public function kelurahan(): BelongsTo {
        return $this->belongsTo(Kelurahan::class, 'kelurahan_id');
    }

    public function suara(): HasOne {
        return $this->hasOne(SuaraTPS::class, 'tps_id');
    }

    public function suaraCalon(): HasMany {
        return $this->hasMany(SuaraCalon::class, 'tps_id');
    }

    public function suaraCalonByCalonId(int $calonId) {
        return $this->suaraCalon()->whereCalonId($calonId);
    }
}
