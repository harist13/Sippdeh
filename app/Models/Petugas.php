<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class Petugas extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $table = 'petugas';
    protected $guard_name = 'web'; 

    protected $fillable = [
        'username',
        'password',
        'email',
        'kabupaten_id',
        'role',
        'is_forced_logout',
        'limit',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function loginHistories()
    {
        return $this->hasMany(LoginHistory::class, 'user_id');
    }

    public function wilayah(): BelongsTo
    {
        return $this->belongsTo(Kabupaten::class, 'kabupaten_id');
    }

    public function kabupaten(): BelongsTo
    {
        return $this->belongsTo(Kabupaten::class, 'kabupaten_id');
    }
}