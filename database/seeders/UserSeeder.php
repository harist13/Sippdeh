<?php

namespace Database\Seeders;

use App\Models\Kabupaten;
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
        Role::firstOrCreate(['name' => 'operator', 'guard_name' => 'web']);

        foreach (Kabupaten::all() as $kabupaten) {
            $namaKabupaten = preg_replace('/\s+/', '', strtolower($kabupaten->nama));

            // Buat pengguna admin
            $admin = Petugas::create([
                'username' => "admin$namaKabupaten",
                'password' => bcrypt('12345678'),
                'email' => "admin$namaKabupaten@sipppdeh.designforus.id",
                'kabupaten_id' => $kabupaten->id,
                'role' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
    
            // Berikan role admin kepada pengguna tersebut
            $admin->assignRole('admin');

            for ($i = 1; $i <= 5; $i++) {
                // Buat pengguna operator
                $operator = Petugas::create([
                    'username' => "operator{$namaKabupaten}$i",
                    'password' => bcrypt('12345678'),
                    'email' => "operator{$namaKabupaten}$i@sipppdeh.designforus.id",
                    'kabupaten_id' => $kabupaten->id,
                    'role' => 'operator',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
        
                // Berikan role operator kepada pengguna tersebut
                $operator->assignRole('operator');
            }
        }
    }
}
