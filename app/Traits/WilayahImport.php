<?php

namespace App\Traits;

use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\Provinsi;
use App\Models\TPS;
use Exception;

trait WilayahImport {
	private array $catatan = [];

    /**
     * Mengambil catatan dari proses impor.
     */
    public function getCatatan(): array
    {
        try {
            $failureMessages = $this->failures()
                ->map(fn ($failure) => $failure->errors())
                ->filter(fn ($errors) => count($errors) > 0 && $errors[0] !== '')
                ->map(fn ($errors) => $errors[0])
                ->toArray();

            return array_merge($failureMessages, $this->catatan);
        } catch (Exception $exception) {
            throw new Exception("Error in getCatatan: " . $exception->getMessage(), 0, $exception);
        }
    }

	/**
     * Membuat provinsi baru.
     */
    private function getProvinsi(string $namaProvinsi): Provinsi
    {
        try {
            $provinsi = Provinsi::where(['nama' => $namaProvinsi])->first();

            if ($provinsi == null) {
                $provinsi = Provinsi::create(['nama' => $namaProvinsi]);
                $this->catatan[] = "Provinsi '<b>$namaProvinsi</b>' baru saja ditambahkan ke database.";
            }

            return $provinsi;
        } catch (Exception $exception) {
            throw new Exception("Error in getProvinsi: " . $exception->getMessage(), 0, $exception);
        }
    }
    
    /**
     * Membuat kabupaten baru.
     */
    private function getKabupaten(string $namaKabupaten, string $namaProvinsi): Kabupaten
    {
        try {
            $provinsi = $this->getProvinsi($namaProvinsi);
            $kabupaten = Kabupaten::where(['nama' => $namaKabupaten, 'provinsi_id' => $provinsi->id])->first();

            if ($kabupaten == null) {
                $kabupaten = Kabupaten::create(['nama' => $namaKabupaten, 'provinsi_id' => $provinsi->id]);
                $this->catatan[] = "Kabupaten '<b>$namaKabupaten</b>' di Provinsi '<b>$namaProvinsi</b>' baru saja ditambahkan ke database.";
            }

            return $kabupaten;
        } catch (Exception $exception) {
            throw new Exception("Error in getKabupaten: " . $exception->getMessage(), 0, $exception);
        }
    }

    /**
     * Membuat kecamatan baru.
     */
    private function getKecamatan(string $namaKecamatan, string $namaKabupaten, string $namaProvinsi): Kecamatan
    {
        try {
            $kabupaten = $this->getKabupaten($namaKabupaten, $namaProvinsi);
            $kecamatan = Kecamatan::where(['nama' => $namaKecamatan, 'kabupaten_id' => $kabupaten->id])->first();

            if ($kecamatan == null) {
                $kecamatan = Kecamatan::create(['nama' => $namaKecamatan, 'kabupaten_id' => $kabupaten->id]);
                $this->catatan[] = "Kecamatan '<b>$namaKecamatan</b>' di Kabupaten '<b>$namaKabupaten</b>' baru saja ditambahkan ke database.";
            }

            return $kecamatan;
        } catch (Exception $exception) {
            throw new Exception("Error in getKecamatan: " . $exception->getMessage(), 0, $exception);
        }
    }

    /**
     * Membuat kecamatan baru.
     */
    private function getKelurahan(string $namaKelurahan, string $namaKecamatan, string $namaKabupaten, string $namaProvinsi): Kelurahan
    {
        try {
            $kecamatan = $this->getKecamatan($namaKecamatan, $namaKabupaten, $namaProvinsi);
            $kelurahan = Kelurahan::where(['nama' => $namaKecamatan, 'kecamatan_id' => $kecamatan->id])->first();

            if ($kelurahan == null) {
                $kelurahan = Kelurahan::create(['nama' => $namaKelurahan, 'kecamatan_id' => $kecamatan->id]);
                $this->catatan[] = "Kelurahan '<b>$namaKelurahan</b>' di Kecamatan '<b>$namaKecamatan</b>' baru saja ditambahkan ke database.";
            }

            return $kelurahan;
        } catch (Exception $exception) {
            throw new Exception("Error in getKecamatan: " . $exception->getMessage(), 0, $exception);
        }
    }

    /**
     * Membuat kecamatan baru.
     */
    private function getTPSModel(string $namaTPS, string $alamat, int $kelurahanId): ?TPS
    {
        try {
            $tps = new TPS(['nama' => $namaTPS, 'alamat' => $alamat, 'dpt' => rand(300, 400), 'kelurahan_id' => $kelurahanId]);
            return $tps;
        } catch (Exception $exception) {
            throw new Exception("Error in getKelurahan: " . $exception->getMessage(), 0, $exception);
        }
    }
}