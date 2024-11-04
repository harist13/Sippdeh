<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use DivisionByZeroError;

class SuaraTPS extends Model
{
    protected $table = 'suara_tps';

    protected $fillable = ['dpt', 'suara_tidak_sah', 'operator_id', 'tps_id'];

    public function suaraSah() {
        return $this->tps->suaraCalon->reduce(function (?int $acc, SuaraCalon $sc) {
            return $acc + $sc->suara;
        });
    }

    public function abstain() {
        return $this->dpt - $this->suaraMasuk();
    }

    public function suaraMasuk() {
        return $this->suaraSah() + $this->suara_tidak_sah;
    }

    public function partisipasi() {
        try {
            return round(($this->suaraMasuk() / $this->dpt) * 100, 1);
        } catch (DivisionByZeroError $exception) {
            return 0;
        }
    }    

    public function operator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'operator_id');
    }

    public function tps(): BelongsTo
    {
        return $this->belongsTo(TPS::class, 'tps_id');
    }
}
