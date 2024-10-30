<?php

namespace Database\Seeders;

use App\Models\Kabupaten;
use Illuminate\Database\Seeder;

class KabupatenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kabupaten = [
            // Sesuaikan 'provinsi_id' dengan ID provinsi Kalimantan Timur
            ['nama' => 'BERAU', 'provinsi_id' => 23],
            ['nama' => 'KUTAI BARAT', 'provinsi_id' => 23],
            ['nama' => 'KUTAI KARTANEGARA', 'provinsi_id' => 23],
            ['nama' => 'KUTAI TIMUR', 'provinsi_id' => 23],
            ['nama' => 'MAHAKAM ULU', 'provinsi_id' => 23],
            ['nama' => 'PASER', 'provinsi_id' => 23],
            ['nama' => 'PENAJEM PASER UTARA', 'provinsi_id' => 23],
            ['nama' => 'BALIKPAPAN', 'provinsi_id' => 23],
            ['nama' => 'BONTANG', 'provinsi_id' => 23],
            ['nama' => 'SAMARINDA', 'provinsi_id' => 23],
        ];

        foreach ($kabupaten as $data) {
            Kabupaten::create($data);
        }
    }
}