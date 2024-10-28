<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SuaraTPS extends Model
{
    protected $table = 'suara_tps';

    protected $fillable = ['dpt', 'suara_tidak_sah', 'operator_id', 'tps_id'];

    public function dpt() {
        return 0;
    }

    public function suaraSah() {
        return 0;
    }

    public function jumlahPenggunaTidakPilih() {
        return 0;
    }

    public function suaraMasuk() {
        return 0;
    }

    public function partisipasi() {
        return 0;
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
