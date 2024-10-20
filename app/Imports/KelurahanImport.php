<?php

namespace App\Imports;

use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\Provinsi;
use App\Traits\WilayahImport;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Row;
use Exception;

class KelurahanImport implements SkipsOnFailure, OnEachRow
{
    use WilayahImport, Importable, SkipsFailures;

    private ?Provinsi $provinsi = null;
    private ?Kabupaten $kabupaten = null;
    private ?Kecamatan $kecamatan = null;

    /**
     * Proses setiap baris data.
     */
    public function onRow(Row $row): void
    {
        try {
            if (isset($row[1])) {
                // Impor dari format 'semua-kelurahan.blade.php'
                $this->importAllKelurahan($row);
            } else {
                // Impor dari format 'kelurahan-kabupaten.blade.php'
                $this->importKelurahanByAnotherWilayah($row);
            }
        } catch (Exception $exception) {
            throw new Exception("Error in onRow: " . $exception->getMessage(), 0, $exception);
        }
    }

    /**
     * Impor semua kelurahan yang memiliki nama kecamatan, kabupaten, dan provinsi pada baris yang sama.
     */
    private function importAllKelurahan(Row $row): void
    {
        try {
            $rowIndex = $row->getIndex();

            // Skip header baris pertama
            if ($rowIndex == 1) {
                return;
            }

            $row = $row->toArray();
            $namaKelurahan = $row[0];
            $namaKecamatan = $row[1];
            $namaKabupaten = $row[2];
            $namaProvinsi = $row[3];

            $kelurahanList = $this->getAllKelurahan();

            // Tambahkan catatan jika kelurahan sudah ada, jika belum buat kelurahan baru
            if (in_array(strtoupper(trim($namaKecamatan)), $kelurahanList)) {
                $this->catatan[] = "Kelurahan '<b>$namaKecamatan</b>' sebelumnya sudah ada di database.";
            } else {
                $this->getKelurahan($namaKelurahan, $namaKecamatan, $namaKabupaten, $namaProvinsi);
            }
        } catch (Exception $exception) {
            throw new Exception("Error in importAllKelurahan: " . $exception->getMessage(), 0, $exception);
        }
    }

    /**
     * Impor kelurahan berdasarkan provinsi, kabupaten, dan kecamatan yang tertera di header yang telah ditemukan sebelumnya.
     */
    private function importKelurahanByAnotherWilayah(Row $row): void
    {
        try {
            $rowIndex = $row->getIndex();
            $row = $row->toArray();

            // Ambil atau buat provinsi baru pada baris ke-1
            if ($rowIndex == 1 && is_null($this->provinsi)) {
                $namaProvinsi = $row[0];
                $this->provinsi = $this->getProvinsi($namaProvinsi);
            }
            
            // Ambil atau buat kabupaten baru pada baris ke-2
            if ($rowIndex == 2 && is_null($this->kabupaten)) {
                $namaKabupaten = $row[0];
                $this->kabupaten = $this->getKabupaten($namaKabupaten, $this->provinsi->nama);
            }

            // Ambil atau buat kecamatan baru pada baris ke-3
            if ($rowIndex == 3 && is_null($this->kecamatan)) {
                $namaKecamatan = $row[0];
                $this->kecamatan = $this->getKecamatan($namaKecamatan, $this->kabupaten->nama, $this->provinsi->nama);
            }

            // Skip header 'KELURAHAN' di baris ke-4
            if ($rowIndex == 4) {
                return;
            }

            // Tambahkan kelurahan pada baris ke-5 dan seterusnya
            if ($rowIndex >= 5) {
                $kelurahanList = $this->getAllKelurahan();
                $namaKelurahan = $row[0];

                if (in_array(strtoupper(trim($namaKelurahan)), $kelurahanList)) {
                    $this->catatan[] = "Kelurahan '<b>$namaKelurahan</b>' sebelumnya sudah ada di database.";
                } else {
                    $this->getKecamatan($namaKelurahan, $this->kabupaten->nama, $this->provinsi->nama);
                }
            }
        } catch (Exception $exception) {
            throw new Exception("Error in importKelurahanByAnotherWilayah: " . $exception->getMessage(), 0, $exception);
        }
    }

    /**
     * Mengambil semua kelurahan dari database.
     */
    private function getAllKelurahan(): array
    {
        try {
            return Kelurahan::selectRaw('UPPER(nama) AS nama')->get()->pluck('nama')->toArray();
        } catch (Exception $exception) {
            throw new Exception("Error in getAllKelurahan: " . $exception->getMessage(), 0, $exception);
        }
    }
}