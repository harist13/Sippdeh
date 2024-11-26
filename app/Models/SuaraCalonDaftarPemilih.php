<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SuaraCalonDaftarPemilih extends Model
{
    protected $table = 'suara_calon_daftar_pemilih';

    protected $fillable = ['suara', 'operator_id', 'kecamatan_id', 'calon_id'];

    public function operator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'operator_id');
    }

    public function kecamatan(): BelongsTo
    {
        return $this->belongsTo(Kecamatan::class, 'kecamatan_id');
    }

    public function calon(): BelongsTo
    {
        return $this->belongsTo(Calon::class, 'calon_id');
    }
}
