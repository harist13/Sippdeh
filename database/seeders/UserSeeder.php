<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Petugas;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Ensure the roles are created with the correct guard
        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);

        // Buat pengguna superadmin
        $superadmin = Petugas::create([
            'username' => 'admin',
            'password' => bcrypt('12345678'),
            'email' => 'harist@gmail.com',
            'wilayah' => 'samarinda',
            'role' => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Berikan role superadmin kepada pengguna tersebut
        $superadmin->assignRole('admin');
    }
}
