<?php

namespace App\Imports;

use App\Models\Provinsi;
use App\Traits\WilayahImport;
use Illuminate\Support\Model;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Row;
use Exception;

class ProvinsiImport implements SkipsOnFailure, OnEachRow
{
    use WilayahImport, Importable, SkipsFailures;

    private ?string $namaKabupaten = null;

    private array $catatan = [];

    /**
     * Mengambil catatan dari proses impor.
     */
    public function getCatatan(): array
    {
        return $this->catatan;
    }

    /**
     * Proses setiap baris data.
     */
    public function onRow(Row $row): void
    {
        try {
            $this->importProvinsi($row);
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    /**
     * Impor provinsi pada setiap baris.
     */
    private function importProvinsi(Row $row): void
    {
        $rowIndex = $row->getIndex();
        $row = $row->toArray();

        // Dapatkan nama kabupaten
        if ($rowIndex == 1 && $row[0] != 'PROVINSI') {
            $this->namaKabupaten = $row[0];
        }

        // Skip header 'PROVINSI' indeks ke-2
        if ($row[0] == 'PROVINSI') {
            return;
        }

        if ($rowIndex >= 2 && $row[0] != 'PROVINSI') {
            $namaProvinsi = $row[0];
            
            // Tambahkan catatan jika provinsi sudah ada, jika belum buat provinsi baru
            if ($this->checkProvinsiExistence($namaProvinsi)) {
                $this->addCatatanProvinsiSudahAda($namaProvinsi);
            } else {
                if (is_null($this->namaKabupaten)) {
                    $this->getProvinsi($namaProvinsi);
                } else {
                    $this->getKabupaten($this->namaKabupaten, $namaProvinsi);
                }
            }
        }
    }

    /**
     * Mengecek apakah provinsi sudah ada atau belum.
     */
    private function checkProvinsiExistence(string $namaProvinsi)
    {
        try {
            $provinsiList = $this->getAllProvinsi();
            return in_array(strtoupper(trim($namaProvinsi)), $provinsiList);
        } catch (Exception $exception) {
            throw new Exception("Error in checkProvinsiExistence: " . $exception->getMessage(), 0, $exception);
        }
    }

    /**
     * Menambahkan catatan ketika provinsi sudah ada.
     */
    private function addCatatanProvinsiSudahAda(string $namaProvinsi): void
    {
        $pesan = "Provinsi '<b>$namaProvinsi</b>' sudah ada di database.";
        $this->catatan[] = $pesan;
    }

    /**
     * Mengambil semua provinsi dari database.
     */
    private function getAllProvinsi(): array
    {
        return Provinsi::selectRaw('UPPER(nama) AS nama')->get()->pluck('nama')->toArray();
    }
}