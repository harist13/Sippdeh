<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
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
        'wilayah',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

}
