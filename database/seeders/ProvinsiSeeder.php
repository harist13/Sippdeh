<?php

namespace Database\Seeders;

use App\Models\Provinsi;
use Illuminate\Database\Seeder;

class ProvinsiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $provinsi = [
            ['nama' => 'Aceh'],
            ['nama' => 'Sumatera Utara'],
            ['nama' => 'Sumatera Barat'],
            ['nama' => 'Riau'],
            ['nama' => 'Kepulauan Riau'],
            ['nama' => 'Jambi'],
            ['nama' => 'Sumatera Selatan'],
            ['nama' => 'Bengkulu'],
            ['nama' => 'Lampung'],
            ['nama' => 'Bangka Belitung'],
            ['nama' => 'DKI Jakarta'],
            ['nama' => 'Jawa Barat'],
            ['nama' => 'Banten'],
            ['nama' => 'Jawa Tengah'],
            ['nama' => 'DI Yogyakarta'],
            ['nama' => 'Jawa Timur'],
            ['nama' => 'Bali'],
            ['nama' => 'Nusa Tenggara Barat'],
            ['nama' => 'Nusa Tenggara Timur'],
            ['nama' => 'Kalimantan Barat'],
            ['nama' => 'Kalimantan Tengah'],
            ['nama' => 'Kalimantan Selatan'],
            ['nama' => 'Kalimantan Timur'],
            ['nama' => 'Kalimantan Utara'],
            ['nama' => 'Sulawesi Utara'],
            ['nama' => 'Gorontalo'],
            ['nama' => 'Sulawesi Tengah'],
            ['nama' => 'Sulawesi Barat'],
            ['nama' => 'Sulawesi Selatan'],
            ['nama' => 'Sulawesi Tenggara'],
            ['nama' => 'Maluku'],
            ['nama' => 'Maluku Utara'],
            ['nama' => 'Papua'],
            ['nama' => 'Papua Barat'],
            ['nama' => 'Papua Selatan'],
            ['nama' => 'Papua Tengah'],
            ['nama' => 'Papua Pegunungan'],
        ];

        foreach ($provinsi as $data) {
            Provinsi::create($data);
        }
    }
}