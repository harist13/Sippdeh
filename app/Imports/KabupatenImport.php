<?php

namespace App\Imports;

use App\Models\Kabupaten;
use App\Models\Provinsi;
use Illuminate\Support\Model;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Row;
use Exception;

class KabupatenImport implements SkipsOnFailure, OnEachRow
{
    use Importable, SkipsFailures;

    private ?Provinsi $provinsi = null;

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
            if (isset($row[1])) {
                $this->importSemuaKabupaten($row);
            } else {
                $this->importKabupatenByProvinsi($row);
            }
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    /**
     * Impor semua kabupaten yang memiliki nama provinsi pada baris yang sama.
     */
    private function importSemuaKabupaten(Row $row): void
    {
        $rowIndex = $row->getIndex();

        // Skip header baris pertama
        if ($rowIndex == 1) {
            return;
        }

        $row = $row->toArray();
        $namaKabupaten = $row[0];
        $namaProvinsi = $row[1];

        $this->provinsi = $this->getProvinsiByNama($namaProvinsi);

        // Buat provinsi baru jika belum ada
        if (is_null($this->provinsi)) {
            $this->provinsi = $this->createProvinsi($namaProvinsi);
            $this->addCatatanProvinsiBaruDariKabupaten($namaKabupaten, $namaProvinsi);
        }

        $kabupatenList = $this->getAllKabupaten();

        // Tambahkan catatan jika kabupaten sudah ada, jika belum buat kabupaten baru
        if ($this->checkKabupatenExistence($namaKabupaten)) {
            $this->addCatatanKabupatenSudahAda($namaKabupaten);
        } else {
            $this->createKabupaten($namaKabupaten, $this->provinsi->id);
        }
    }

    /**
     * Mengecek apakah kabupaten sudah ada atau belum.
     */
    private function checkKabupatenExistence(string $namaKabupaten)
    {
        try {
            $kabupatenList = $this->getAllKabupaten();
            return in_array(strtoupper(trim($namaKabupaten)), $kabupatenList);
        } catch (Exception $exception) {
            throw new Exception("Error in checkKabupatenExistence: " . $exception->getMessage(), 0, $exception);
        }
    }

    /**
     * Impor kabupaten berdasarkan provinsi yang telah ditemukan sebelumnya.
     */
    private function importKabupatenByProvinsi(Row $row): void
    {
        $rowIndex = $row->getIndex();
        $row = $row->toArray();

        // Skip header kabupaten
        if ($rowIndex == 2) {
            return;
        }

        // Ambil atau buat provinsi baru pada baris pertama
        if ($rowIndex == 1) {
            $this->provinsi = $this->getProvinsiByNama($row[0]);

            if (is_null($this->provinsi)) {
                $this->provinsi = $this->createProvinsi($row[0]);
                $this->addCatatanProvinsiBaru($row[0]);
            }
        }

        // Tambahkan kabupaten ke provinsi pada baris ke-3 ke atas
        if ($rowIndex >= 3) {
            $kabupatenList = $this->getAllKabupaten();

            if (in_array(strtoupper(trim($row[0])), $kabupatenList)) {
                $this->addCatatanKabupatenSudahAda($row[0]);
            } else {
                $this->createKabupaten($row[0], $this->provinsi->id);
            }
        }
    }

    /**
     * Mencari provinsi berdasarkan nama.
     */
    private function getProvinsiByNama(string $namaProvinsi): ?Provinsi
    {
        return Provinsi::whereRaw('UPPER(nama) = ?', [strtoupper($namaProvinsi)])->first();
    }

    /**
     * Membuat provinsi baru.
     */
    private function createProvinsi(string $namaProvinsi): Provinsi
    {
        return Provinsi::create(['nama' => $namaProvinsi]);
    }

    /**
     * Membuat kabupaten baru.
     */
    private function createKabupaten(string $namaKabupaten, int $provinsiId): Kabupaten
    {
        return Kabupaten::create([
            'nama' => $namaKabupaten,
            'provinsi_id' => $provinsiId
        ]);
    }

    /**
     * Menambahkan catatan ketika provinsi baru dibuat dari kabupaten.
     */
    private function addCatatanProvinsiBaruDariKabupaten(string $namaKabupaten, string $namaProvinsi): void
    {
        $pesan = "Pada penambahan kabupaten '<b>$namaKabupaten</b>', provinsi '<b>$namaProvinsi</b>' sebelumnya belum ada di database, jadi provinsi tersebut baru saja ditambahkan.";
        $this->catatan[] = $pesan;
    }

    /**
     * Menambahkan catatan ketika kabupaten sudah ada.
     */
    private function addCatatanKabupatenSudahAda(string $namaKabupaten): void
    {
        $pesan = "Kabupaten '<b>$namaKabupaten</b>' sudah ada di database.";
        $this->catatan[] = $pesan;
    }

    /**
     * Menambahkan catatan ketika provinsi baru dibuat.
     */
    private function addCatatanProvinsiBaru(string $namaProvinsi): void
    {
        $pesan = "Provinsi '<b>$namaProvinsi</b>' baru saja ditambahkan ke database.";
        $this->catatan[] = $pesan;
    }

    /**
     * Mengambil semua kabupaten dari database.
     */
    private function getAllKabupaten(): array
    {
        return Kabupaten::selectRaw('UPPER(nama) AS nama')->get()->pluck('nama')->toArray();
    }
}