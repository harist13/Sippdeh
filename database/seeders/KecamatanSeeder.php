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
            ['nama_kecamatan' => 'Tanjung Redeb', 'kabupaten_id' => 15],
            ['nama_kecamatan' => 'Gunung Tabur', 'kabupaten_id' => 15],
            ['nama_kecamatan' => 'Sambaliung', 'kabupaten_id' => 15],
            ['nama_kecamatan' => 'Segah', 'kabupaten_id' => 15],
            ['nama_kecamatan' => 'Teluk Bayur', 'kabupaten_id' => 15],
            ['nama_kecamatan' => 'Biduk-Biduk', 'kabupaten_id' => 15],
            ['nama_kecamatan' => 'Kelay', 'kabupaten_id' => 15],
            ['nama_kecamatan' => 'Tabalar', 'kabupaten_id' => 15],
            ['nama_kecamatan' => 'Maratua', 'kabupaten_id' => 15],

            // Kecamatan di Kabupaten Kutai Barat
            ['nama_kecamatan' => 'Barong Tongkok', 'kabupaten_id' => 16],
            ['nama_kecamatan' => 'Melak', 'kabupaten_id' => 16],
            ['nama_kecamatan' => 'Long Iram', 'kabupaten_id' => 16],
            ['nama_kecamatan' => 'Damai', 'kabupaten_id' => 16],
            ['nama_kecamatan' => 'Muara Pahu', 'kabupaten_id' => 16],
            ['nama_kecamatan' => 'Bongan', 'kabupaten_id' => 16],
            ['nama_kecamatan' => 'Jempang', 'kabupaten_id' => 16],
            ['nama_kecamatan' => 'Penyinggahan', 'kabupaten_id' => 16],
            ['nama_kecamatan' => 'Siluq Ngurai', 'kabupaten_id' => 16],
            ['nama_kecamatan' => 'Mook Manaar Bulatn', 'kabupaten_id' => 16],

            // Kecamatan di Kabupaten Kutai Kartanegara
            ['nama_kecamatan' => 'Tenggarong', 'kabupaten_id' => 17],
            ['nama_kecamatan' => 'Samboja', 'kabupaten_id' => 17],
            ['nama_kecamatan' => 'Muara Badak', 'kabupaten_id' => 17],
            ['nama_kecamatan' => 'Anggana', 'kabupaten_id' => 17],
            ['nama_kecamatan' => 'Loa Janan', 'kabupaten_id' => 17],
            ['nama_kecamatan' => 'Loa Kulu', 'kabupaten_id' => 17],
            ['nama_kecamatan' => 'Muara Jawa', 'kabupaten_id' => 17],
            ['nama_kecamatan' => 'Kenohan', 'kabupaten_id' => 17],
            ['nama_kecamatan' => 'Kembang Janggut', 'kabupaten_id' => 17],
            ['nama_kecamatan' => 'Tabang', 'kabupaten_id' => 17],

            // Kecamatan di Kabupaten Kutai Timur
            ['nama_kecamatan' => 'Sangatta Utara', 'kabupaten_id' => 18],
            ['nama_kecamatan' => 'Sangatta Selatan', 'kabupaten_id' => 18],
            ['nama_kecamatan' => 'Teluk Pandan', 'kabupaten_id' => 18],
            ['nama_kecamatan' => 'Bengalon', 'kabupaten_id' => 18],
            ['nama_kecamatan' => 'Kaliorang', 'kabupaten_id' => 18],
            ['nama_kecamatan' => 'Kaubun', 'kabupaten_id' => 18],
            ['nama_kecamatan' => 'Kongbeng', 'kabupaten_id' => 18],
            ['nama_kecamatan' => 'Muara Wahau', 'kabupaten_id' => 18],
            ['nama_kecamatan' => 'Rantau Pulung', 'kabupaten_id' => 18],
            ['nama_kecamatan' => 'Telen', 'kabupaten_id' => 18],
            ['nama_kecamatan' => 'Sandaran', 'kabupaten_id' => 18],

            // Kecamatan di Kabupaten Mahakam Ulu
            ['nama_kecamatan' => 'Long Bagun', 'kabupaten_id' => 19],
            ['nama_kecamatan' => 'Long Hubung', 'kabupaten_id' => 19],
            ['nama_kecamatan' => 'Laham', 'kabupaten_id' => 19],
            ['nama_kecamatan' => 'Long Apari', 'kabupaten_id' => 19],
            ['nama_kecamatan' => 'Long Pahangai', 'kabupaten_id' => 19],

            // Kecamatan di Kabupaten Paser
            ['nama_kecamatan' => 'Tanah Grogot', 'kabupaten_id' => 20],
            ['nama_kecamatan' => 'Batu Sopang', 'kabupaten_id' => 20],
            ['nama_kecamatan' => 'Muara Komam', 'kabupaten_id' => 20],
            ['nama_kecamatan' => 'Pasir Belengkong', 'kabupaten_id' => 20],
            ['nama_kecamatan' => 'Long Ikis', 'kabupaten_id' => 20],
            ['nama_kecamatan' => 'Kuaro', 'kabupaten_id' => 20],
            ['nama_kecamatan' => 'Long Kali', 'kabupaten_id' => 20],

            // Kecamatan di Kabupaten Penajam Paser Utara
            ['nama_kecamatan' => 'Penajam', 'kabupaten_id' => 21],
            ['nama_kecamatan' => 'Waru', 'kabupaten_id' => 21],
            ['nama_kecamatan' => 'Babulu', 'kabupaten_id' => 21],
            ['nama_kecamatan' => 'Sepaku', 'kabupaten_id' => 21],
            
            // Kecamatan di Kota Balikpapan
            ['nama_kecamatan' => 'Balikpapan Selatan', 'kabupaten_id' => 22],
            ['nama_kecamatan' => 'Balikpapan Utara', 'kabupaten_id' => 22],
            ['nama_kecamatan' => 'Balikpapan Tengah', 'kabupaten_id' => 22],
            ['nama_kecamatan' => 'Balikpapan Barat', 'kabupaten_id' => 22],
            ['nama_kecamatan' => 'Balikpapan Timur', 'kabupaten_id' => 22],
            ['nama_kecamatan' => 'Balikpapan Kota', 'kabupaten_id' => 22],

            // Kecamatan di Kota Bontang
            ['nama_kecamatan' => 'Bontang Utara', 'kabupaten_id' => 23],
            ['nama_kecamatan' => 'Bontang Barat', 'kabupaten_id' => 23],
            ['nama_kecamatan' => 'Bontang Selatan', 'kabupaten_id' => 23],

            // Kecamatan di Kota Samarinda
            ['nama_kecamatan' => 'Samarinda Kota', 'kabupaten_id' => 24],
            ['nama_kecamatan' => 'Sungai Kunjang', 'kabupaten_id' => 24],
            ['nama_kecamatan' => 'Samarinda Seberang', 'kabupaten_id' => 24],
            ['nama_kecamatan' => 'Samarinda Ulu', 'kabupaten_id' => 24],
            ['nama_kecamatan' => 'Samarinda Ilir', 'kabupaten_id' => 24],
            ['nama_kecamatan' => 'Loa Janan Ilir', 'kabupaten_id' => 24],
        ];

        foreach ($kecamatan as $data) {
            Kecamatan::create($data);
        }
    }
}
