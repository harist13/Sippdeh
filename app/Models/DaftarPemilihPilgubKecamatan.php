<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DaftarPemilihPilgubKecamatan extends Model
{
    protected $table = 'daftar_pemilih_pilgub_kecamatan_view';

    public function kecamatan(): BelongsTo
    {
        return $this->belongsTo(Kecamatan::class, 'id');
    }

    public function kabupaten(): BelongsTo
    {
        return $this->belongsTo(Kabupaten::class, 'kabupaten_id');
    }

    public function daftarPemilih(): BelongsTo
    {
        return $this->belongsTo(DaftarPemilih::class, 'daftar_pemilih_id');
    }
}
