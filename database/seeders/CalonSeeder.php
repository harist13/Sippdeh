<?php

namespace Database\Seeders;

use App\Models\Calon;
use Illuminate\Database\Seeder;

class CalonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pilgub Kaltim
        Calon::create([
            'nama' => 'Isran Noor',
            'nama_wakil' => 'Hadi Mulyadi',
            'posisi' => 'GUBERNUR',
            'provinsi_id' => 23, // Kalimantan Timur
            'no_urut' => 1
        ]);

        Calon::create([
            'nama' => "Rudy Mas'ud",
            'nama_wakil' => 'Seno Aji',
            'posisi' => 'GUBERNUR',
            'provinsi_id' => 23, // Kalimantan Timur
            'no_urut' => 2
        ]);

        // Pilkada Samarinda
        Calon::create([
            'nama' => 'Andi Harun',
            'nama_wakil' => 'Saefuddin Zuhri',
            'posisi' => 'WALIKOTA',
            'kabupaten_id' => 10, // Kota Samarinda
        ]);

        // Pilkada Balikpapan
        Calon::create([
            'nama' => 'Muhammad Sa’bani',
            'nama_wakil' => 'Syukri Wahid',
            'posisi' => 'WALIKOTA',
            'kabupaten_id' => 8, // Kota Balikpapan
        ]);

        Calon::create([
            'nama' => 'Rendi Susiswo Ismail',
            'nama_wakil' => 'Eddy Sunardi Darmawan',
            'posisi' => 'WALIKOTA',
            'kabupaten_id' => 8, // Kota Balikpapan
        ]);

        Calon::create([
            'nama' => 'Rahmad Mas’ud',
            'nama_wakil' => 'Bagus Susetyo',
            'posisi' => 'WALIKOTA',
            'kabupaten_id' => 8, // Kota Balikpapan
        ]);

        // Pilkada Kutai Kartanegara
        Calon::create([
            'nama' => 'Edi Damansyah',
            'nama_wakil' => 'Rendi Solihin',
            'posisi' => 'BUPATI',
            'kabupaten_id' => 3, // Kabupaten Kutai Kartanegara
        ]);

        Calon::create([
            'nama' => 'Awang Yacoub Luthman',
            'nama_wakil' => 'Akhmad Zais',
            'posisi' => 'BUPATI',
            'kabupaten_id' => 3, // Kabupaten Kutai Kartanegara
        ]);

        Calon::create([
            'nama' => 'Dendi Suryadi',
            'nama_wakil' => 'Alif Turiadi',
            'posisi' => 'BUPATI',
            'kabupaten_id' => 3, // Kabupaten Kutai Kartanegara
        ]);

        // Pilkada Kutai Barat
        Calon::create([
            'nama' => 'Sahadi',
            'nama_wakil' => 'Alexander Edmond',
            'posisi' => 'BUPATI',
            'kabupaten_id' => 2, // Kabupaten Kutai Barat
        ]);

        Calon::create([
            'nama' => 'Frederick Edwin',
            'nama_wakil' => 'Nanang Adriani',
            'posisi' => 'BUPATI',
            'kabupaten_id' => 2, // Kabupaten Kutai Barat
        ]);

        Calon::create([
            'nama' => 'Ahmad Syaiful',
            'nama_wakil' => 'Jainudin',
            'posisi' => 'BUPATI',
            'kabupaten_id' => 2, // Kabupaten Kutai Barat
        ]);

        // Pilkada Kutai Timur
        Calon::create([
            'nama' => 'Ardiansyah Sulaiman',
            'nama_wakil' => 'Mahyunadi',
            'posisi' => 'BUPATI',
            'kabupaten_id' => 4, // Kabupaten Kutai Timur
        ]);

        Calon::create([
            'nama' => 'Kasmidi Bulang',
            'nama_wakil' => 'Kinsu',
            'posisi' => 'BUPATI',
            'kabupaten_id' => 4, // Kabupaten Kutai Timur
        ]);

        // Pilkada Bontang
        Calon::create([
            'nama' => 'Basri Rase',
            'nama_wakil' => 'Chusnul Dhihin',
            'posisi' => 'WALIKOTA',
            'kabupaten_id' => 9, // Kota Bontang
        ]);

        Calon::create([
            'nama' => 'Neni Moerniaeni',
            'nama_wakil' => 'Agus Haris',
            'posisi' => 'WALIKOTA',
            'kabupaten_id' => 9, // Kota Bontang
        ]);

        Calon::create([
            'nama' => 'Najirah',
            'nama_wakil' => 'Muhammad Aswar',
            'posisi' => 'WALIKOTA',
            'kabupaten_id' => 9, // Kota Bontang
        ]);

        Calon::create([
            'nama' => 'Sutomo Jabir',
            'nama_wakil' => 'Nasrullah',
            'posisi' => 'WALIKOTA',
            'kabupaten_id' => 9, // Kota Bontang
        ]);

        // Pilkada Mahakam Ulu
        Calon::create([
            'nama' => 'Yohanes Avun',
            'nama_wakil' => 'Y. Juan Jenau',
            'posisi' => 'BUPATI',
            'kabupaten_id' => 5, // Kabupaten Mahakam Ulu
        ]);

        Calon::create([
            'nama' => 'Owena Mayang Shari Belawan',
            'nama_wakil' => 'Stanislaus Liah',
            'posisi' => 'BUPATI',
            'kabupaten_id' => 5, // Kabupaten Mahakam Ulu
        ]);

        Calon::create([
            'nama' => 'Novita Bulan',
            'nama_wakil' => 'Artya Fathra Marthin',
            'posisi' => 'BUPATI',
            'kabupaten_id' => 5, // Kabupaten Mahakam Ulu
        ]);

        // Pilkada Penajam Paser Utara
        Calon::create([
            'nama' => 'Mudyat Noor',
            'nama_wakil' => 'Abdul Waris Muin',
            'posisi' => 'BUPATI',
            'kabupaten_id' => 7, // Kabupaten Penajam Paser Utara
        ]);

        Calon::create([
            'nama' => 'Desmon Hariman Sormin',
            'nama_wakil' => 'Naspi Arsyad',
            'posisi' => 'BUPATI',
            'kabupaten_id' => 7, // Kabupaten Penajam Paser Utara
        ]);

        Calon::create([
            'nama' => 'Andi Harahap',
            'nama_wakil' => 'Dayang Dona Walfaries Tania',
            'posisi' => 'BUPATI',
            'kabupaten_id' => 7, // Kabupaten Penajam Paser Utara
        ]);

        Calon::create([
            'nama' => 'Hamdam',
            'nama_wakil' => 'Ahmad Basir',
            'posisi' => 'BUPATI',
            'kabupaten_id' => 7, // Kabupaten Penajam Paser Utara
        ]);

        // Pilkada Paser
        Calon::create([
            'nama' => 'Fahmi Fadli',
            'nama_wakil' => 'Ikhwan Antasari',
            'posisi' => 'BUPATI',
            'kabupaten_id' => 6, // Kabupaten Paser
        ]);

        Calon::create([
            'nama' => 'Syarifah Masitah Assegaf',
            'nama_wakil' => 'Denni Mappa',
            'posisi' => 'BUPATI',
            'kabupaten_id' => 6, // Kabupaten Paser
        ]);

        // Pilkada Berau
        Calon::create([
            'nama' => 'Sri Juniarsih Mas',
            'nama_wakil' => 'Gamalis',
            'posisi' => 'BUPATI',
            'kabupaten_id' => 1, // Kabupaten Berau
        ]);

        Calon::create([
            'nama' => 'Madri Pani',
            'nama_wakil' => 'Agus Wahyudi',
            'posisi' => 'BUPATI',
            'kabupaten_id' => 1, // Kabupaten Berau
        ]);
    }
}