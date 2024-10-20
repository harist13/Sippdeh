<?php

namespace App\Imports;

use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\Provinsi;
use App\Traits\WilayahImport;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Row;
use Exception;

class KecamatanImport implements SkipsOnFailure, OnEachRow
{
    use WilayahImport, Importable, SkipsFailures;

    private ?Provinsi $provinsi = null;
    private ?Kabupaten $kabupaten = null;

    /**
     * Proses setiap baris data.
     */
    public function onRow(Row $row): void
    {
        try {
            if (isset($row[1])) {
                // Impor dari format 'semua-kecamatan.blade.php'
                $this->importAllKecamatan($row);
            } else {
                // Impor dari format 'kecamatan-kabupaten.blade.php'
                $this->importKecamatanByKabupaten($row);
            }
        } catch (Exception $exception) {
            throw new Exception("Error in onRow: " . $exception->getMessage(), 0, $exception);
        }
    }

    /**
     * Impor semua kabupaten yang memiliki nama kabupaten pada baris yang sama.
     */
    private function importAllKecamatan(Row $row): void
    {
        try {
            $rowIndex = $row->getIndex();

            // Skip header baris pertama
            if ($rowIndex == 1) {
                return;
            }

            $row = $row->toArray();
            $namaKecamatan = $row[0];
            $namaKabupaten = $row[1];
            $namaProvinsi = $row[2];

            $kacamatanList = $this->getAllKecamatan();

            // Tambahkan catatan jika kecamatan sudah ada, jika belum buat kecamatan baru
            if ($this->checkKecamatanExistence($namaKecamatan)) {
                $this->catatan[] = "Kecamatan '<b>$namaKecamatan</b>' sebelumnya sudah ada di database.";
            } else {
                $this->getKecamatan($namaKecamatan, $namaKabupaten, $namaProvinsi);
            }
        } catch (Exception $exception) {
            throw new Exception("Error in importAllKecamatan: " . $exception->getMessage(), 0, $exception);
        }
    }

    /**
     * Mengecek apakah kecamatan sudah ada atau belum.
     */
    private function checkKecamatanExistence(string $namaKecamatan)
    {
        try {
            $kecamatanList = $this->getAllKecamatan();
            return in_array(strtoupper(trim($namaKecamatan)), $kecamatanList);
        } catch (Exception $exception) {
            throw new Exception("Error in checkKecamatanExistence: " . $exception->getMessage(), 0, $exception);
        }
    }

    /**
     * Impor kecamatan berdasarkan kecamatan yang telah ditemukan sebelumnya.
     */
    private function importKecamatanByKabupaten(Row $row): void
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

            // Skip header 'KECAMATAN' di baris ke-3
            if ($rowIndex == 3) {
                return;
            }

            // Tambahkan kecamatan pada baris ke-4 dan seterusnya
            if ($rowIndex >= 4) {
                $kecamatanList = $this->getAllKecamatan();
                $namaKecamatan = $row[0];

                if (in_array(strtoupper(trim($namaKecamatan)), $kecamatanList)) {
                    $this->catatan[] = "Kecamatan '<b>$namaKecamatan</b>' sebelumnya sudah ada di database.";
                } else {
                    $this->getKecamatan($namaKecamatan, $this->kabupaten->nama, $this->provinsi->nama);
                }
            }
        } catch (Exception $exception) {
            throw new Exception("Error in importKecamatanByKabupaten: " . $exception->getMessage(), 0, $exception);
        }
    }

    /**
     * Mengambil semua kecamatan dari database.
     */
    private function getAllKecamatan(): array
    {
        try {
            return Kecamatan::selectRaw('UPPER(nama) AS nama')->get()->pluck('nama')->toArray();
        } catch (Exception $exception) {
            throw new Exception("Error in getAllKecamatan: " . $exception->getMessage(), 0, $exception);
        }
    }
}