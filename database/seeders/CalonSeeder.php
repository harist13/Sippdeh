<?php

namespace Database\Seeders;

use App\Models\Calon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CalonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Calon::create([
            'nama' => 'Isran Noor',
            'nama_wakil' => 'Hadi Mulyadi',
            'posisi' => 'GUBERNUR',
            'provinsi_id' => 23, // Kalimantan Timur
        ]);

        Calon::create([
            'nama' => "Rudi Mas'ud",
            'nama_wakil' => 'Seno Aji',
            'posisi' => 'GUBERNUR',
            'provinsi_id' => 23, // Kalimantan Timur
        ]);

        Calon::create([
            'nama' => 'Andi Harun',
            'nama_wakil' => 'Saefuddin Zuhri',
            'posisi' => 'WALIKOTA',
            'kabupaten_id' => 10, // Kota Samarinda
        ]);
    }
}
