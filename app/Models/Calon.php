<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Calon extends Model
{
    use HasFactory;

    protected $table = 'calon';

    protected $fillable = ['no_urut','nama', 'nama_wakil', 'posisi', 'provinsi_id', 'kabupaten_id', 'foto'];

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

    public function getFormattedNoUrut(): string {
        $no = $this->no_urut;
        $digits = strlen($no);

        if ($digits == 1) {
            return "0$no";
        }

        if ($digits == 2) {
            return "0$no";
        }

        return $no;
    }

    public function provinsi(): BelongsTo {
        return $this->belongsTo(Provinsi::class, 'provinsi_id');
    }

    public function kabupaten(): BelongsTo {
        return $this->belongsTo(Kabupaten::class, 'kabupaten_id');
    }

    public function suaraCalon(): HasMany {
        return $this->hasMany(SuaraCalon::class, 'calon_id');
    }
}
