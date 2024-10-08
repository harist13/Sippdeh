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
            ['nama' => 'Kabupaten Bengkayang', 'provinsi_id' => 20], // Sesuaikan 'provinsi_id' dengan ID provinsi Kalimantan Barat
            ['nama' => 'Kabupaten Kapuas Hulu', 'provinsi_id' => 20],
            ['nama' => 'Kabupaten Kayong Utara', 'provinsi_id' => 20],
            ['nama' => 'Kabupaten Ketapang', 'provinsi_id' => 20],
            ['nama' => 'Kabupaten Kubu Raya', 'provinsi_id' => 20],
            ['nama' => 'Kabupaten Landak', 'provinsi_id' => 20],
            ['nama' => 'Kabupaten Melawi', 'provinsi_id' => 20],
            ['nama' => 'Kabupaten Mempawah', 'provinsi_id' => 20],
            ['nama' => 'Kabupaten Sambas', 'provinsi_id' => 20],
            ['nama' => 'Kabupaten Sanggau', 'provinsi_id' => 20],
            ['nama' => 'Kabupaten Sekadau', 'provinsi_id' => 20],
            ['nama' => 'Kabupaten Sintang', 'provinsi_id' => 20],
            ['nama' => 'Kota Pontianak', 'provinsi_id' => 20],
            ['nama' => 'Kota Singkawang', 'provinsi_id' => 20],
            
            ['nama' => 'Kabupaten Berau', 'provinsi_id' => 23], // Sesuaikan 'provinsi_id' dengan ID provinsi Kalimantan Timur
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