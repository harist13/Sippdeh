<?php

namespace Database\Seeders;

use App\Models\Kecamatan;
use Illuminate\Database\Seeder;

class KecamatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kecamatan = [
            // Kecamatan di Kabupaten Berau
            ['nama' => 'Tanjung Redeb', 'kabupaten_id' => 1],
            ['nama' => 'Gunung Tabur', 'kabupaten_id' => 1],
            ['nama' => 'Sambaliung', 'kabupaten_id' => 1],
            ['nama' => 'Segah', 'kabupaten_id' => 1],
            ['nama' => 'Teluk Bayur', 'kabupaten_id' => 1],
            ['nama' => 'Biduk-Biduk', 'kabupaten_id' => 1],
            ['nama' => 'Kelay', 'kabupaten_id' => 1],
            ['nama' => 'Tabalar', 'kabupaten_id' => 1],
            ['nama' => 'Maratua', 'kabupaten_id' => 1],

            // Kecamatan di Kabupaten Kutai Barat
            ['nama' => 'Barong Tongkok', 'kabupaten_id' => 2],
            ['nama' => 'Melak', 'kabupaten_id' => 2],
            ['nama' => 'Long Iram', 'kabupaten_id' => 2],
            ['nama' => 'Damai', 'kabupaten_id' => 2],
            ['nama' => 'Muara Pahu', 'kabupaten_id' => 2],
            ['nama' => 'Bongan', 'kabupaten_id' => 2],
            ['nama' => 'Jempang', 'kabupaten_id' => 2],
            ['nama' => 'Penyinggahan', 'kabupaten_id' => 2],
            ['nama' => 'Siluq Ngurai', 'kabupaten_id' => 2],
            ['nama' => 'Mook Manaar Bulatn', 'kabupaten_id' => 2],

            // Kecamatan di Kabupaten Kutai Kartanegara
            ['nama' => 'Tenggarong', 'kabupaten_id' => 3],
            ['nama' => 'Samboja', 'kabupaten_id' => 3],
            ['nama' => 'Muara Badak', 'kabupaten_id' => 3],
            ['nama' => 'Anggana', 'kabupaten_id' => 3],
            ['nama' => 'Loa Janan', 'kabupaten_id' => 3],
            ['nama' => 'Loa Kulu', 'kabupaten_id' => 3],
            ['nama' => 'Muara Jawa', 'kabupaten_id' => 3],
            ['nama' => 'Kenohan', 'kabupaten_id' => 3],
            ['nama' => 'Kembang Janggut', 'kabupaten_id' => 3],
            ['nama' => 'Tabang', 'kabupaten_id' => 3],

            // Kecamatan di Kabupaten Kutai Timur
            ['nama' => 'Sangatta Utara', 'kabupaten_id' => 4],
            ['nama' => 'Sangatta Selatan', 'kabupaten_id' => 4],
            ['nama' => 'Teluk Pandan', 'kabupaten_id' => 4],
            ['nama' => 'Bengalon', 'kabupaten_id' => 4],
            ['nama' => 'Kaliorang', 'kabupaten_id' => 4],
            ['nama' => 'Kaubun', 'kabupaten_id' => 4],
            ['nama' => 'Kongbeng', 'kabupaten_id' => 4],
            ['nama' => 'Muara Wahau', 'kabupaten_id' => 4],
            ['nama' => 'Rantau Pulung', 'kabupaten_id' => 4],
            ['nama' => 'Telen', 'kabupaten_id' => 4],
            ['nama' => 'Sandaran', 'kabupaten_id' => 4],

            // Kecamatan di Kabupaten Mahakam Ulu
            ['nama' => 'Long Bagun', 'kabupaten_id' => 5],
            ['nama' => 'Long Hubung', 'kabupaten_id' => 5],
            ['nama' => 'Laham', 'kabupaten_id' => 5],
            ['nama' => 'Long Apari', 'kabupaten_id' => 5],
            ['nama' => 'Long Pahangai', 'kabupaten_id' => 5],

            // Kecamatan di Kabupaten Paser
            ['nama' => 'Tanah Grogot', 'kabupaten_id' => 6],
            ['nama' => 'Batu Sopang', 'kabupaten_id' => 6],
            ['nama' => 'Muara Komam', 'kabupaten_id' => 6],
            ['nama' => 'Pasir Belengkong', 'kabupaten_id' => 6],
            ['nama' => 'Long Ikis', 'kabupaten_id' => 6],
            ['nama' => 'Kuaro', 'kabupaten_id' => 6],
            ['nama' => 'Long Kali', 'kabupaten_id' => 6],

            // Kecamatan di Kabupaten Penajam Paser Utara
            ['nama' => 'Penajam', 'kabupaten_id' => 7],
            ['nama' => 'Waru', 'kabupaten_id' => 7],
            ['nama' => 'Babulu', 'kabupaten_id' => 7],
            ['nama' => 'Sepaku', 'kabupaten_id' => 7],
            
            // Kecamatan di Kota Balikpapan
            ['nama' => 'Balikpapan Selatan', 'kabupaten_id' => 8],
            ['nama' => 'Balikpapan Utara', 'kabupaten_id' => 8],
            ['nama' => 'Balikpapan Tengah', 'kabupaten_id' => 8],
            ['nama' => 'Balikpapan Barat', 'kabupaten_id' => 8],
            ['nama' => 'Balikpapan Timur', 'kabupaten_id' => 8],
            ['nama' => 'Balikpapan Kota', 'kabupaten_id' => 8],

            // Kecamatan di Kota Bontang
            ['nama' => 'Bontang Utara', 'kabupaten_id' => 9],
            ['nama' => 'Bontang Barat', 'kabupaten_id' => 9],
            ['nama' => 'Bontang Selatan', 'kabupaten_id' => 9],

            // Kecamatan di Kota Samarinda
            ['nama' => 'Samarinda Kota', 'kabupaten_id' => 10],
            ['nama' => 'Sungai Kunjang', 'kabupaten_id' => 10],
            ['nama' => 'Samarinda Seberang', 'kabupaten_id' => 10],
            ['nama' => 'Samarinda Ulu', 'kabupaten_id' => 10],
            ['nama' => 'Samarinda Ilir', 'kabupaten_id' => 10],
            ['nama' => 'Loa Janan Ilir', 'kabupaten_id' => 10],
        ];

        foreach ($kecamatan as $data) {
            Kecamatan::create($data);
        }
    }
}
