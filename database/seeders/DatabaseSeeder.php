<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Petugas;
use Illuminate\Support\Facades\Artisan;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
   {
        // User::factory(10)->create();

        $this->call([
            ProvinsiSeeder::class,
            KabupatenSeeder::class,
            // KecamatanSeeder::class,
            // KelurahanSeeder::class,

            RolesAndPermissionsSeeder::class,
            UserSeeder::class,
        ]);

        $this->command->info('Sedang mengimpor data Kecamatan, Kelurahan, dan TPS dari berkas CSV...');
        
        Artisan::call('import:tps');

        $this->command->info('Seeding selesai.');
    }
}
