<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DaftarPemilih extends Model
{
    protected $table = 'daftar_pemilih';

    protected $fillable = ['dptb', 'dpk', 'kotak_kosong', 'suara_tidak_sah', 'posisi', 'kecamatan_id', 'operator_id'];

    public function kabupaten(): BelongsTo
    {
        return $this->belongsTo(Kabupaten::class, 'kabupaten_id');
    }
}
