<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Kabupaten extends Model
{
    use HasFactory;

    protected $table = 'kabupaten';
    
    protected $fillable = ['nama', 'provinsi_id'];

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
}
