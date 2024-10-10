<?php

namespace Database\Seeders;

use App\Models\Kelurahan;
use Illuminate\Database\Seeder;

class KelurahanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kelurahan = [
            // BERAU

            // Kelurahan di Kecamatan Tanjung Redeb
            ['nama' => 'Gayam', 'kecamatan_id' => 1],
            ['nama' => 'Karang Ambun', 'kecamatan_id' => 1],
            ['nama' => 'Gunung Panjang', 'kecamatan_id' => 1],
            ['nama' => 'Tanjung Redeb', 'kecamatan_id' => 1],

            // Kelurahan di Kecamatan Gunung Tabur
            ['nama' => 'Birang', 'kecamatan_id' => 2],
            ['nama' => 'Pulau Besing', 'kecamatan_id' => 2],
            ['nama' => 'Gunung Tabur', 'kecamatan_id' => 2],
            ['nama' => 'Merancang Ulu', 'kecamatan_id' => 2],

            // Kelurahan di Kecamatan Sambaliung
            ['nama' => 'Sambaliung', 'kecamatan_id' => 3],
            ['nama' => 'Penyinggahan', 'kecamatan_id' => 3],
            ['nama' => 'Limunjan', 'kecamatan_id' => 3],

            // Kelurahan di Kecamatan Segah
            ['nama' => 'Tepian Buah', 'kecamatan_id' => 4],
            ['nama' => 'Segah', 'kecamatan_id' => 4],
            ['nama' => 'Punan Segah', 'kecamatan_id' => 4],

            // Kelurahan di Kecamatan Teluk Bayur
            ['nama' => 'Teluk Bayur', 'kecamatan_id' => 5],
            ['nama' => 'Rantau Panjang', 'kecamatan_id' => 5],
            ['nama' => 'Tanjung Batu', 'kecamatan_id' => 5],

            // Kelurahan di Kecamatan Biduk-Biduk
            ['nama' => 'Biduk-Biduk', 'kecamatan_id' => 6],
            ['nama' => 'Teluk Sumbang', 'kecamatan_id' => 6],
            ['nama' => 'Giring-Giring', 'kecamatan_id' => 6],

            // Kelurahan di Kecamatan Kelay
            ['nama' => 'Kelay', 'kecamatan_id' => 7],
            ['nama' => 'Merasa', 'kecamatan_id' => 7],

            // Kelurahan di Kecamatan Tabalar
            ['nama' => 'Tabalar', 'kecamatan_id' => 8],
            ['nama' => 'Tabalar Ulu', 'kecamatan_id' => 8],

            // Kelurahan di Kecamatan Maratua
            ['nama' => 'Payung-Payung', 'kecamatan_id' => 9],
            ['nama' => 'Bohesilian', 'kecamatan_id' => 9],
            ['nama' => 'Teluk Alulu', 'kecamatan_id' => 9],

            // KUTAI BARAT

            // Kelurahan di Kecamatan Barong Tongkok
            ['nama' => 'Kelurahan Barong Tongkok', 'kecamatan_id' => 10],
            ['nama' => 'Kelurahan Linggang Bigung', 'kecamatan_id' => 10],

            // Kelurahan di Kecamatan Melak
            ['nama' => 'Kelurahan Melak Ilir', 'kecamatan_id' => 11],
            ['nama' => 'Kelurahan Melak Ulu', 'kecamatan_id' => 11],

            // Kelurahan di Kecamatan Long Iram
            ['nama' => 'Kelurahan Long Iram Seberang', 'kecamatan_id' => 12],
            ['nama' => 'Kelurahan Long Iram Kota', 'kecamatan_id' => 12],

            // Kelurahan di Kecamatan Damai
            ['nama' => 'Kelurahan Muara Lawa', 'kecamatan_id' => 13],
            ['nama' => 'Kelurahan Damai Kota', 'kecamatan_id' => 13],

            // Kelurahan di Kecamatan Muara Pahu
            ['nama' => 'Kelurahan Muara Pahu', 'kecamatan_id' => 14],
            ['nama' => 'Kelurahan Ngenyan Asa', 'kecamatan_id' => 14],

            // Kelurahan di Kecamatan Bongan
            ['nama' => 'Kelurahan Jambuk Makmur', 'kecamatan_id' => 15],
            ['nama' => 'Kelurahan Mantarip', 'kecamatan_id' => 15],

            // Kelurahan di Kecamatan Jempang
            ['nama' => 'Kelurahan Tanjung Isuy', 'kecamatan_id' => 16],
            ['nama' => 'Kelurahan Muara Nayan', 'kecamatan_id' => 16],

            // Kelurahan di Kecamatan Penyinggahan
            ['nama' => 'Kelurahan Muara Penyinggahan', 'kecamatan_id' => 17],
            ['nama' => 'Kelurahan Penyinggahan Ilir', 'kecamatan_id' => 17],

            // Kelurahan di Kecamatan Siluq Ngurai
            ['nama' => 'Kelurahan Linggang Melapeh', 'kecamatan_id' => 18],
            ['nama' => 'Kelurahan Siluq Ngurai', 'kecamatan_id' => 18],

            // Kelurahan di Kecamatan Mook Manaar Bulatn
            ['nama' => 'Kelurahan Bulatn', 'kecamatan_id' => 19],
            ['nama' => 'Kelurahan Kelumpang', 'kecamatan_id' => 19],

            // KUTAI KARTANEGARA (KUKAR)

            // Kelurahan di Kecamatan Tenggarong
            ['nama' => 'Loa Ipuh', 'kecamatan_id' => 20],
            ['nama' => 'Tenggarong Seberang', 'kecamatan_id' => 20],
            ['nama' => 'Mangkurawang', 'kecamatan_id' => 20],
            ['nama' => 'Melayu', 'kecamatan_id' => 20],
            ['nama' => 'Loa Kulu', 'kecamatan_id' => 20],

            // Kelurahan di Kecamatan Samboja
            ['nama' => 'Samboja Barat', 'kecamatan_id' => 21],
            ['nama' => 'Tanjung Harapan', 'kecamatan_id' => 21],
            ['nama' => 'Kariangau', 'kecamatan_id' => 21],
            ['nama' => 'Amborawang Laut', 'kecamatan_id' => 21],
            ['nama' => 'Samboja Kuala', 'kecamatan_id' => 21],

            // Kelurahan di Kecamatan Muara Badak
            ['nama' => 'Badak Baru', 'kecamatan_id' => 22],
            ['nama' => 'Muara Badak Ulu', 'kecamatan_id' => 22],
            ['nama' => 'Gas Alam Badak', 'kecamatan_id' => 22],
            ['nama' => 'Saliki', 'kecamatan_id' => 22],
            ['nama' => 'Santan Ilir', 'kecamatan_id' => 22],

            // Kelurahan di Kecamatan Anggana
            ['nama' => 'Anggana Ulu', 'kecamatan_id' => 23],
            ['nama' => 'Kutai Lama', 'kecamatan_id' => 23],
            ['nama' => 'Sepatin', 'kecamatan_id' => 23],
            ['nama' => 'Tani Baru', 'kecamatan_id' => 23],
            ['nama' => 'Handil Baru', 'kecamatan_id' => 23],

            // Kelurahan di Kecamatan Loa Janan
            ['nama' => 'Loa Janan Ulu', 'kecamatan_id' => 24],
            ['nama' => 'Loa Janan Ilir', 'kecamatan_id' => 24],
            ['nama' => 'Bukit Raya', 'kecamatan_id' => 24],
            ['nama' => 'Teluk Dalam', 'kecamatan_id' => 24],
            ['nama' => 'Karang Tunggal', 'kecamatan_id' => 24],

            // Kelurahan di Kecamatan Loa Kulu
            ['nama' => 'Sumber Sari', 'kecamatan_id' => 25],
            ['nama' => 'Jembayan', 'kecamatan_id' => 25],
            ['nama' => 'Sumber Rejo', 'kecamatan_id' => 25],
            ['nama' => 'Loa Kulu Kota', 'kecamatan_id' => 25],

            // Kelurahan di Kecamatan Muara Jawa
            ['nama' => 'Muara Jawa Ulu', 'kecamatan_id' => 26],
            ['nama' => 'Muara Jawa Ilir', 'kecamatan_id' => 26],
            ['nama' => 'Muara Jawa Tengah', 'kecamatan_id' => 26],
            ['nama' => 'Teluk Dalam', 'kecamatan_id' => 26],

            // Kelurahan di Kecamatan Kenohan
            ['nama' => 'Lambu Denda', 'kecamatan_id' => 27],
            ['nama' => 'Tanjung Harapan', 'kecamatan_id' => 27],
            ['nama' => 'Bukit Layang', 'kecamatan_id' => 27],
            ['nama' => 'Tabang', 'kecamatan_id' => 27],

            // Kelurahan di Kecamatan Kembang Janggut
            ['nama' => 'Jelemuq', 'kecamatan_id' => 28],
            ['nama' => 'Gunung Sari', 'kecamatan_id' => 28],
            ['nama' => 'Bukit Pariaman', 'kecamatan_id' => 28],
            ['nama' => 'Santan Tengah', 'kecamatan_id' => 28],

            // Kelurahan di Kecamatan Tabang
            ['nama' => 'Long Lees', 'kecamatan_id' => 29],
            ['nama' => 'Muara Pedohon', 'kecamatan_id' => 29],
            ['nama' => 'Muara Suan', 'kecamatan_id' => 29],
            ['nama' => 'Belayan', 'kecamatan_id' => 29],

            // KUTAI TIMUR

            // Kelurahan di Kecamatan Sangatta Utara
            ['nama' => 'Sangatta Utara', 'kecamatan_id' => 30],
            ['nama' => 'Swarga Bara', 'kecamatan_id' => 30],
            ['nama' => 'Singkuang', 'kecamatan_id' => 30],

            // Kelurahan di Kecamatan Sangatta Selatan
            ['nama' => 'Sangatta Selatan', 'kecamatan_id' => 31],
            ['nama' => 'Teluk Lingga', 'kecamatan_id' => 31],
            ['nama' => 'Teluk Singkama', 'kecamatan_id' => 31],

            // Kelurahan di Kecamatan Teluk Pandan
            ['nama' => 'Suka Rahmat', 'kecamatan_id' => 32],
            ['nama' => 'Suka Damai', 'kecamatan_id' => 32],
            ['nama' => 'Martadinata', 'kecamatan_id' => 32],

            // Kelurahan di Kecamatan Bengalon
            ['nama' => 'Sepaso', 'kecamatan_id' => 33],
            ['nama' => 'Sepaso Selatan', 'kecamatan_id' => 33],
            ['nama' => 'Sepaso Timur', 'kecamatan_id' => 33],

            // Kelurahan di Kecamatan Kaliorang
            ['nama' => 'Kaliorang', 'kecamatan_id' => 34],
            ['nama' => 'Selangkau', 'kecamatan_id' => 34],
            ['nama' => 'Batu Balai', 'kecamatan_id' => 34],

            // Kelurahan di Kecamatan Kaubun
            ['nama' => 'Batu Lepoq', 'kecamatan_id' => 35],
            ['nama' => 'Pengadan Baru', 'kecamatan_id' => 35],
            ['nama' => 'Suka Maju', 'kecamatan_id' => 35],

            // Kelurahan di Kecamatan Kongbeng
            ['nama' => 'Long Pejeng', 'kecamatan_id' => 36],
            ['nama' => 'Kongbeng Indah', 'kecamatan_id' => 36],
            ['nama' => 'Makmur Jaya', 'kecamatan_id' => 36],

            // Kelurahan di Kecamatan Muara Wahau
            ['nama' => 'Wahau Baru', 'kecamatan_id' => 37],
            ['nama' => 'Long Wehea', 'kecamatan_id' => 37],
            ['nama' => 'Nehas Liah Bing', 'kecamatan_id' => 37],

            // Kelurahan di Kecamatan Rantau Pulung
            ['nama' => 'Keraitan', 'kecamatan_id' => 38],
            ['nama' => 'Pulung Sari', 'kecamatan_id' => 38],
            ['nama' => 'Suka Maju', 'kecamatan_id' => 38],

            // Kelurahan di Kecamatan Telen
            ['nama' => 'Long Tesak', 'kecamatan_id' => 39],
            ['nama' => 'Long Segar', 'kecamatan_id' => 39],
            ['nama' => 'Marah Haloq', 'kecamatan_id' => 39],

            // Kelurahan di Kecamatan Sandaran
            ['nama' => 'Manubar', 'kecamatan_id' => 40],
            ['nama' => 'Sandaran', 'kecamatan_id' => 40],
            ['nama' => 'Tadoan', 'kecamatan_id' => 40],

            // MAHAKAM ULU

            // Kelurahan di Kecamatan Long Bagun
            ['nama' => 'Long Bagun Ulu', 'kecamatan_id' => 41],
            ['nama' => 'Long Bagun Ilir', 'kecamatan_id' => 41],
            ['nama' => 'Ujoh Bilang', 'kecamatan_id' => 41],
            
            // Kelurahan di Kecamatan Long Hubung
            ['nama' => 'Long Hubung Ulu', 'kecamatan_id' => 42],
            ['nama' => 'Long Hubung Ilir', 'kecamatan_id' => 42],
            ['nama' => 'Datah Bilang', 'kecamatan_id' => 42],
            
            // Kelurahan di Kecamatan Laham
            ['nama' => 'Laham', 'kecamatan_id' => 43],
            ['nama' => 'Tiong Ohang', 'kecamatan_id' => 43],
            ['nama' => 'Tiong Buu', 'kecamatan_id' => 43],
            
            // Kelurahan di Kecamatan Long Apari
            ['nama' => 'Long Apari', 'kecamatan_id' => 44],
            ['nama' => 'Tiong Ohang', 'kecamatan_id' => 44],
            ['nama' => 'Long Telenjau', 'kecamatan_id' => 44],
            
            // Kelurahan di Kecamatan Long Pahangai
            ['nama' => 'Long Pahangai', 'kecamatan_id' => 45],
            ['nama' => 'Long Tuyoq', 'kecamatan_id' => 45],
            ['nama' => 'Lirung Ulu', 'kecamatan_id' => 45],

            // PASER

             // Kelurahan di Kecamatan Tanah Grogot
             ['nama' => 'Tanah Grogot', 'kecamatan_id' => 46],
             ['nama' => 'Jone', 'kecamatan_id' => 46],
             ['nama' => 'Senaken', 'kecamatan_id' => 46],
             ['nama' => 'Tanah Periuk', 'kecamatan_id' => 46],
             
             // Kelurahan di Kecamatan Batu Sopang
             ['nama' => 'Batu Sopang', 'kecamatan_id' => 47],
             ['nama' => 'Sangkuriman', 'kecamatan_id' => 47],
             ['nama' => 'Siri', 'kecamatan_id' => 47],
             
             // Kelurahan di Kecamatan Muara Komam
             ['nama' => 'Muara Komam', 'kecamatan_id' => 48],
             ['nama' => 'Harapan Baru', 'kecamatan_id' => 48],
             ['nama' => 'Tanjung Pinang', 'kecamatan_id' => 48],
             
             // Kelurahan di Kecamatan Pasir Belengkong
             ['nama' => 'Pasir Belengkong', 'kecamatan_id' => 49],
             ['nama' => 'Sempulang', 'kecamatan_id' => 49],
             ['nama' => 'Kerang', 'kecamatan_id' => 49],
             
             // Kelurahan di Kecamatan Long Ikis
             ['nama' => 'Long Ikis', 'kecamatan_id' => 50],
             ['nama' => 'Rantau Buta', 'kecamatan_id' => 50],
             ['nama' => 'Long Sayo', 'kecamatan_id' => 50],
             
             // Kelurahan di Kecamatan Kuaro
             ['nama' => 'Kuaro', 'kecamatan_id' => 51],
             ['nama' => 'Bekoso', 'kecamatan_id' => 51],
             ['nama' => 'Padang Pengrapat', 'kecamatan_id' => 51],
             
             // Kelurahan di Kecamatan Long Kali
             ['nama' => 'Long Kali', 'kecamatan_id' => 52],
             ['nama' => 'Lolo', 'kecamatan_id' => 52],
             ['nama' => 'Petiku', 'kecamatan_id' => 52],
             ['nama' => 'Tebru Padan', 'kecamatan_id' => 52],

            // PENAJEM PASER UTARA

            // Kelurahan di Kecamatan Penajam
            ['nama' => 'Penajam', 'kecamatan_id' => 53],
            ['nama' => 'Nipah-Nipah', 'kecamatan_id' => 53],
            ['nama' => 'Sesumpu', 'kecamatan_id' => 53],
            ['nama' => 'Lawe-Lawe', 'kecamatan_id' => 53],
            ['nama' => 'Tanjung Tengah', 'kecamatan_id' => 53],
            
            // Kelurahan di Kecamatan Waru
            ['nama' => 'Waru', 'kecamatan_id' => 54],
            ['nama' => 'Bangun Mulya', 'kecamatan_id' => 54],
            ['nama' => 'Api-Api', 'kecamatan_id' => 54],
            ['nama' => 'Sotek', 'kecamatan_id' => 54],
            
            // Kelurahan di Kecamatan Babulu
            ['nama' => 'Babulu', 'kecamatan_id' => 55],
            ['nama' => 'Rawa Mulya', 'kecamatan_id' => 55],
            ['nama' => 'Labangka', 'kecamatan_id' => 55],
            ['nama' => 'Gunung Mulia', 'kecamatan_id' => 55],
            
            // Kelurahan di Kecamatan Sepaku
            ['nama' => 'Sepaku', 'kecamatan_id' => 56],
            ['nama' => 'Bukit Raya', 'kecamatan_id' => 56],
            ['nama' => 'Pemaluan', 'kecamatan_id' => 56],
            ['nama' => 'Maridan', 'kecamatan_id' => 56],
            ['nama' => 'Mentawir', 'kecamatan_id' => 56],

            // BALIKPAPAN

            // Kelurahan di Kecamatan Balikpapan Selatan
            ['nama' => 'Sepinggan', 'kecamatan_id' => 57],
            ['nama' => 'Sepinggan Raya', 'kecamatan_id' => 57],
            ['nama' => 'Gunung Bahagia', 'kecamatan_id' => 57],
            ['nama' => 'Damai Baru', 'kecamatan_id' => 57],

            // Kelurahan di Kecamatan Balikpapan Utara
            ['nama' => 'Graha Indah', 'kecamatan_id' => 58],
            ['nama' => 'Karang Joang', 'kecamatan_id' => 58],
            ['nama' => 'Batu Ampar', 'kecamatan_id' => 58],
            ['nama' => 'Gunung Samarinda', 'kecamatan_id' => 58],

            // Kelurahan di Kecamatan Balikpapan Tengah
            ['nama' => 'Mekar Sari', 'kecamatan_id' => 59],
            ['nama' => 'Karang Jati', 'kecamatan_id' => 59],
            ['nama' => 'Gunung Sari Ilir', 'kecamatan_id' => 59],
            ['nama' => 'Sumber Rejo', 'kecamatan_id' => 59],

            // Kelurahan di Kecamatan Balikpapan Barat
            ['nama' => 'Margo Mulyo', 'kecamatan_id' => 60],
            ['nama' => 'Karang Jati', 'kecamatan_id' => 60],
            ['nama' => 'Baru Ulu', 'kecamatan_id' => 60],
            ['nama' => 'Baru Tengah', 'kecamatan_id' => 60],

            // Kelurahan di Kecamatan Balikpapan Timur
            ['nama' => 'Manggar', 'kecamatan_id' => 61],
            ['nama' => 'Manggar Baru', 'kecamatan_id' => 61],
            ['nama' => 'Lamaru', 'kecamatan_id' => 61],
            ['nama' => 'Teritip', 'kecamatan_id' => 61],

            // Kelurahan di Kecamatan Balikpapan Kota
            ['nama' => 'Klandasan Ilir', 'kecamatan_id' => 62],
            ['nama' => 'Klandasan Ulu', 'kecamatan_id' => 62],
            ['nama' => 'Telaga Sari', 'kecamatan_id' => 62],
            ['nama' => 'Damai', 'kecamatan_id' => 62],

            // BONTANG

            // Kelurahan di Kecamatan Bontang Utara
            ['nama' => 'Bontang Kuala', 'kecamatan_id' => 63],
            ['nama' => 'Gunung Elai', 'kecamatan_id' => 63],
            ['nama' => 'Api-Api', 'kecamatan_id' => 63],
            ['nama' => 'Guntung', 'kecamatan_id' => 63],

            // Kelurahan di Kecamatan Bontang Barat
            ['nama' => 'Belimbing', 'kecamatan_id' => 64],
            ['nama' => 'Kanaan', 'kecamatan_id' => 64],

            // Kelurahan di Kecamatan Bontang Selatan
            ['nama' => 'Tanjung Laut', 'kecamatan_id' => 65],
            ['nama' => 'Tanjung Laut Indah', 'kecamatan_id' => 65],
            ['nama' => 'Berbas Pantai', 'kecamatan_id' => 65],
            ['nama' => 'Berbas Tengah', 'kecamatan_id' => 65],

            // SAMARINDA

            // Kelurahan di Kecamatan Samarinda Kota
            ['nama' => 'Bugis', 'kecamatan_id' => 66],
            ['nama' => 'Pasar Pagi', 'kecamatan_id' => 66],
            ['nama' => 'Sungai Pinang Dalam', 'kecamatan_id' => 66],
            ['nama' => 'Pelabuhan', 'kecamatan_id' => 66],

            // Kelurahan di Kecamatan Sungai Kunjang
            ['nama' => 'Karang Asam Ulu', 'kecamatan_id' => 67],
            ['nama' => 'Karang Asam Ilir', 'kecamatan_id' => 67],
            ['nama' => 'Lok Bahu', 'kecamatan_id' => 67],
            ['nama' => 'Teluk Lerong Ulu', 'kecamatan_id' => 67],

            // Kelurahan di Kecamatan Samarinda Seberang
            ['nama' => 'Mesjid', 'kecamatan_id' => 68],
            ['nama' => 'Simpang Pasir', 'kecamatan_id' => 68],
            ['nama' => 'Baqa', 'kecamatan_id' => 68],
            ['nama' => 'Harapan Baru', 'kecamatan_id' => 68],

            // Kelurahan di Kecamatan Samarinda Ulu
            ['nama' => 'Sidodadi', 'kecamatan_id' => 69],
            ['nama' => 'Air Putih', 'kecamatan_id' => 69],
            ['nama' => 'Air Hitam', 'kecamatan_id' => 69],
            ['nama' => 'Sempaja Selatan', 'kecamatan_id' => 69],
            ['nama' => 'Sempaja Utara', 'kecamatan_id' => 69],
            ['nama' => 'Gunung Kelua', 'kecamatan_id' => 69],

            // Kelurahan di Kecamatan Samarinda Ilir
            ['nama' => 'Dadi Mulya', 'kecamatan_id' => 70],
            ['nama' => 'Sidomulyo', 'kecamatan_id' => 70],
            ['nama' => 'Bandara', 'kecamatan_id' => 70],
            ['nama' => 'Sungai Dama', 'kecamatan_id' => 70],

            // Kelurahan di Kecamatan Loa Janan Ilir
            ['nama' => 'Tani Aman', 'kecamatan_id' => 71],
            ['nama' => 'Handil Bakti', 'kecamatan_id' => 71],
            ['nama' => 'Loa Janan', 'kecamatan_id' => 71],
            ['nama' => 'Teluk Lerong Ilir', 'kecamatan_id' => 71],
        ];

        foreach ($kelurahan as $data) {
            Kelurahan::create($data);
        }
    }
}