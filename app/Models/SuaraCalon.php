<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SuaraCalon extends Model
{
    protected $table = 'suara_calon';

    protected $fillable = ['suara', 'operator_id', 'tps_id', 'calon_id'];

    public function operator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'operator_id');
    }

    public function tps(): BelongsTo
    {
        return $this->belongsTo(TPS::class, 'tps_id');
    }

    public function calon(): BelongsTo
    {
        return $this->belongsTo(Calon::class, 'calon_id');
    }
}
