<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Kelurahan;

class TPSSeeder extends Seeder
{
    public function run(): void
    {
        // Get all kelurahan IDs
        $kelurahanIds = Kelurahan::pluck('id')->toArray();

        // Sample data for TPS
        foreach ($kelurahanIds as $kelurahanId) {
            // Generate 5-10 TPS for each kelurahan
            $numTPS = rand(5, 10);
            
            for ($i = 1; $i <= $numTPS; $i++) {
                DB::table('tps')->insert([
                    'nama' => 'TPS ' . $i,
                    'dpt' => rand(200, 400), // Typical DPT range for a TPS
                    'kelurahan_id' => $kelurahanId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}