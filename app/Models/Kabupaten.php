<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Kabupaten extends Model
{
    use HasFactory;

    protected $table = 'kabupaten';
    
    protected $fillable = ['nama', 'logo', 'provinsi_id', 'slug'];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($kabupaten) {
            $kabupaten->slug = Str::slug($kabupaten->nama);
        });

        static::updating(function ($kabupaten) {
            if ($kabupaten->isDirty('nama')) {
                $kabupaten->slug = Str::slug($kabupaten->nama);
            }
        });
    }

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

    public function kecamatan(): HasMany {
        return $this->hasMany(Kecamatan::class, 'kabupaten_id');
    }
    
    public function paslon(): HasMany {
        return $this->hasMany(Calon::class, 'kabupaten_id');
    }

    public function isKota(): bool
    {
        return str_contains(strtolower($this->nama), 'kota');
    }
}