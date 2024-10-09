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
            ['nama' => 'Tanjung Redeb', 'kabupaten_id' => 15],
            ['nama' => 'Gunung Tabur', 'kabupaten_id' => 15],
            ['nama' => 'Sambaliung', 'kabupaten_id' => 15],
            ['nama' => 'Segah', 'kabupaten_id' => 15],
            ['nama' => 'Teluk Bayur', 'kabupaten_id' => 15],
            ['nama' => 'Biduk-Biduk', 'kabupaten_id' => 15],
            ['nama' => 'Kelay', 'kabupaten_id' => 15],
            ['nama' => 'Tabalar', 'kabupaten_id' => 15],
            ['nama' => 'Maratua', 'kabupaten_id' => 15],

            // Kecamatan di Kabupaten Kutai Barat
            ['nama' => 'Barong Tongkok', 'kabupaten_id' => 16],
            ['nama' => 'Melak', 'kabupaten_id' => 16],
            ['nama' => 'Long Iram', 'kabupaten_id' => 16],
            ['nama' => 'Damai', 'kabupaten_id' => 16],
            ['nama' => 'Muara Pahu', 'kabupaten_id' => 16],
            ['nama' => 'Bongan', 'kabupaten_id' => 16],
            ['nama' => 'Jempang', 'kabupaten_id' => 16],
            ['nama' => 'Penyinggahan', 'kabupaten_id' => 16],
            ['nama' => 'Siluq Ngurai', 'kabupaten_id' => 16],
            ['nama' => 'Mook Manaar Bulatn', 'kabupaten_id' => 16],

            // Kecamatan di Kabupaten Kutai Kartanegara
            ['nama' => 'Tenggarong', 'kabupaten_id' => 17],
            ['nama' => 'Samboja', 'kabupaten_id' => 17],
            ['nama' => 'Muara Badak', 'kabupaten_id' => 17],
            ['nama' => 'Anggana', 'kabupaten_id' => 17],
            ['nama' => 'Loa Janan', 'kabupaten_id' => 17],
            ['nama' => 'Loa Kulu', 'kabupaten_id' => 17],
            ['nama' => 'Muara Jawa', 'kabupaten_id' => 17],
            ['nama' => 'Kenohan', 'kabupaten_id' => 17],
            ['nama' => 'Kembang Janggut', 'kabupaten_id' => 17],
            ['nama' => 'Tabang', 'kabupaten_id' => 17],

            // Kecamatan di Kabupaten Kutai Timur
            ['nama' => 'Sangatta Utara', 'kabupaten_id' => 18],
            ['nama' => 'Sangatta Selatan', 'kabupaten_id' => 18],
            ['nama' => 'Teluk Pandan', 'kabupaten_id' => 18],
            ['nama' => 'Bengalon', 'kabupaten_id' => 18],
            ['nama' => 'Kaliorang', 'kabupaten_id' => 18],
            ['nama' => 'Kaubun', 'kabupaten_id' => 18],
            ['nama' => 'Kongbeng', 'kabupaten_id' => 18],
            ['nama' => 'Muara Wahau', 'kabupaten_id' => 18],
            ['nama' => 'Rantau Pulung', 'kabupaten_id' => 18],
            ['nama' => 'Telen', 'kabupaten_id' => 18],
            ['nama' => 'Sandaran', 'kabupaten_id' => 18],

            // Kecamatan di Kabupaten Mahakam Ulu
            ['nama' => 'Long Bagun', 'kabupaten_id' => 19],
            ['nama' => 'Long Hubung', 'kabupaten_id' => 19],
            ['nama' => 'Laham', 'kabupaten_id' => 19],
            ['nama' => 'Long Apari', 'kabupaten_id' => 19],
            ['nama' => 'Long Pahangai', 'kabupaten_id' => 19],

            // Kecamatan di Kabupaten Paser
            ['nama' => 'Tanah Grogot', 'kabupaten_id' => 20],
            ['nama' => 'Batu Sopang', 'kabupaten_id' => 20],
            ['nama' => 'Muara Komam', 'kabupaten_id' => 20],
            ['nama' => 'Pasir Belengkong', 'kabupaten_id' => 20],
            ['nama' => 'Long Ikis', 'kabupaten_id' => 20],
            ['nama' => 'Kuaro', 'kabupaten_id' => 20],
            ['nama' => 'Long Kali', 'kabupaten_id' => 20],

            // Kecamatan di Kabupaten Penajam Paser Utara
            ['nama' => 'Penajam', 'kabupaten_id' => 21],
            ['nama' => 'Waru', 'kabupaten_id' => 21],
            ['nama' => 'Babulu', 'kabupaten_id' => 21],
            ['nama' => 'Sepaku', 'kabupaten_id' => 21],
            
            // Kecamatan di Kota Balikpapan
            ['nama' => 'Balikpapan Selatan', 'kabupaten_id' => 22],
            ['nama' => 'Balikpapan Utara', 'kabupaten_id' => 22],
            ['nama' => 'Balikpapan Tengah', 'kabupaten_id' => 22],
            ['nama' => 'Balikpapan Barat', 'kabupaten_id' => 22],
            ['nama' => 'Balikpapan Timur', 'kabupaten_id' => 22],
            ['nama' => 'Balikpapan Kota', 'kabupaten_id' => 22],

            // Kecamatan di Kota Bontang
            ['nama' => 'Bontang Utara', 'kabupaten_id' => 23],
            ['nama' => 'Bontang Barat', 'kabupaten_id' => 23],
            ['nama' => 'Bontang Selatan', 'kabupaten_id' => 23],

            // Kecamatan di Kota Samarinda
            ['nama' => 'Samarinda Kota', 'kabupaten_id' => 24],
            ['nama' => 'Sungai Kunjang', 'kabupaten_id' => 24],
            ['nama' => 'Samarinda Seberang', 'kabupaten_id' => 24],
            ['nama' => 'Samarinda Ulu', 'kabupaten_id' => 24],
            ['nama' => 'Samarinda Ilir', 'kabupaten_id' => 24],
            ['nama' => 'Loa Janan Ilir', 'kabupaten_id' => 24],
        ];

        foreach ($kecamatan as $data) {
            Kecamatan::create($data);
        }
    }
}
