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
        Role::firstOrCreate(['name' => 'petugas', 'guard_name' => 'web']);

        foreach (Kabupaten::all() as $kabupaten) {
            $namaKabupaten = strtolower($kabupaten->nama);

            // Buat pengguna admin
            $admin = Petugas::create([
                'username' => "admin_$namaKabupaten",
                'password' => bcrypt('12345678'),
                'email' => "admin_$namaKabupaten@sipppdeh.designforus.id",
                'wilayah' => $namaKabupaten,
                'role' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
    
            // Berikan role admin kepada pengguna tersebut
            $admin->assignRole('admin');

            for ($i = 1; $i <= 5; $i++) {
                // Buat pengguna petugas
                $petugas = Petugas::create([
                    'username' => "petugas_{$namaKabupaten}_$i",
                    'password' => bcrypt('12345678'),
                    'email' => "petugas_{$namaKabupaten}_$i@sipppdeh.designforus.id",
                    'wilayah' => $namaKabupaten,
                    'role' => 'petugas',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
        
                // Berikan role petugas kepada pengguna tersebut
                $petugas->assignRole('petugas');
            }
        }
    }
}
