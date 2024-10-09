<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoginHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'ip_address',
        'user_agent',
        'login_at',
        'is_logged_out',
        'logged_out_at',
    ];

    public function user()
    {
        return $this->belongsTo(Petugas::class, 'user_id');
    }

    public function scopeActive($query)
    {
        return $query->where('login_at', '>=', now()->subDays(1))
                     ->where('is_logged_out', false);
    }
}