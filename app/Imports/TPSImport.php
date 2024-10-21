<?php

namespace App\Imports;

use App\Traits\WilayahImport;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Row;
use App\Models\TPS;
use Exception;

class TPSImport implements SkipsOnFailure, OnEachRow
{
    use WilayahImport, Importable, SkipsFailures;

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
            $this->importTPS($row);
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    /**
     * Impor TPS pada setiap baris.
     */
    private function importTPS(Row $row): void
    {
        $row = $row->toArray();
        
        $namaKelurahan = $row[2];
        $namaTPS = $row[3];
        $alamat = $row[4];
            
        // Tambahkan catatan jika TPS sudah ada, jika belum buat TPS baru
        if ($this->checkTPSExistence($namaTPS)) {
            $this->addCatatanTPSSudahAda($namaTPS);
        } else {
            $this->getOrCreateTPS($namaKelurahan, $namaTPS, $alamat);
        }
    }

    /**
     * Mengecek apakah TPS sudah ada atau belum.
     */
    private function checkTPSExistence(string $namaTPS)
    {
        try {
            $tpsList = $this->getAllTPS();
            return in_array(strtoupper(trim($namaTPS)), $tpsList);
        } catch (Exception $exception) {
            throw new Exception("Error in checkTPSExistence: " . $exception->getMessage(), 0, $exception);
        }
    }

    /**
     * Menambahkan catatan ketika provinsi sudah ada.
     */
    private function addCatatanTPSSudahAda(string $namaTPS): void
    {
        $pesan = "TPS '<b>$namaTPS</b>' sudah ada di database.";
        $this->catatan[] = $pesan;
    }

    /**
     * Mengambil semua TPS dari database.
     */
    private function getAllTPS(): array
    {
        return TPS::selectRaw('UPPER(nama) AS nama')->get()->pluck('nama')->toArray();
    }
}