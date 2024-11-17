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
            ['nama' => 'Kabupaten Berau', 'provinsi_id' => 23],
            ['nama' => 'Kabupaten Kutai Barat', 'provinsi_id' => 23],
            ['nama' => 'Kabupaten Kutai Kartanegara', 'provinsi_id' => 23],
            ['nama' => 'Kabupaten Kutai Timur', 'provinsi_id' => 23],
            ['nama' => 'Kabupaten Mahakam Ulu', 'provinsi_id' => 23],
            ['nama' => 'Kabupaten Paser', 'provinsi_id' => 23],
            ['nama' => 'Kabupaten Penajam Paser Utara', 'provinsi_id' => 23],
            ['nama' => 'Kota Balikpapan', 'provinsi_id' => 23],
            ['nama' => 'Kota Bontang', 'provinsi_id' => 23],
            ['nama' => 'Kota Samarinda', 'provinsi_id' => 23],
        ];

        foreach ($kabupaten as $data) {
            Kabupaten::create($data);
        }
    }
}