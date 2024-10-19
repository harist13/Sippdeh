<?php

namespace App\Imports;

use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\Provinsi;
use Illuminate\Support\Model;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Row;
use Exception;

class KecamatanImport implements SkipsOnFailure, OnEachRow
{
    use Importable, SkipsFailures;

    private ?Kabupaten $kabupaten = null;
    public array $catatan = [];

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
     * Proses setiap baris data.
     */
    public function onRow(Row $row): void
    {
        try {
            if (isset($row[1])) {
                $this->importSemuaKecamatan($row);
            } else {
                $this->importKecamatanByKabupaten($row);
            }
        } catch (Exception $exception) {
            throw new Exception("Error in onRow: " . $exception->getMessage(), 0, $exception);
        }
    }

    /**
     * Impor semua kabupaten yang memiliki nama kabupaten pada baris yang sama.
     */
    private function importSemuaKecamatan(Row $row): void
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

            $this->kabupaten = $this->getKabupatenByNama($namaKabupaten);

            // Buat kabupaten baru jika belum ada
            if (is_null($this->kabupaten)) {
                $this->kabupaten = $this->createKabupaten($namaKabupaten);
                $this->addCatatanKabupatenBaruDariKecamatan($namaKecamatan, $namaKabupaten);
            }

            $kacamatanList = $this->getAllKecamatan();

            // Tambahkan catatan jika kabupaten sudah ada, jika belum buat kabupaten baru
            if (in_array(strtoupper(trim($namaKecamatan)), $kacamatanList)) {
                $this->addCatatanKabupatenSudahAda($namaKecamatan);
            } else {
                $this->createKecamatan($namaKecamatan, $this->kabupaten->id);
            }
        } catch (Exception $exception) {
            throw new Exception("Error in importSemuaKecamatan: " . $exception->getMessage(), 0, $exception);
        }
    }

    /**
     * Impor kabupaten berdasarkan kabupaten yang telah ditemukan sebelumnya.
     */
    private function importKecamatanByKabupaten(Row $row): void
    {
        try {
            $rowIndex = $row->getIndex();
            $row = $row->toArray();

            // Skip header kabupaten
            if ($rowIndex == 2) {
                return;
            }

            // Ambil atau buat kabupaten baru pada baris pertama
            if ($rowIndex == 1) {
                $this->kabupaten = $this->getKabupatenByNama($row[0]);

                if (is_null($this->kabupaten)) {
                    $this->kabupaten = $this->createKabupaten($row[0]);
                    $this->addCatatanKabupatenBaru($row[0]);
                }
            }

            // Tambahkan kecamatan ke kabupaten pada baris ke-3 ke atas
            if ($rowIndex >= 3) {
                $kecamatanList = $this->getAllKecamatan();

                if (in_array(strtoupper(trim($row[0])), $kecamatanList)) {
                    $this->addCatatanKabupatenSudahAda($row[0]);
                } else {
                    $this->createKecamatan($row[0], $this->kabupaten->id);
                }
            }
        } catch (Exception $exception) {
            throw new Exception("Error in importKecamatanByKabupaten: " . $exception->getMessage(), 0, $exception);
        }
    }

    /**
     * Mencari kabupaten berdasarkan nama.
     */
    private function getKabupatenByNama(string $namaKabupaten): ?Kabupaten
    {
        try {
            return Kabupaten::whereRaw('UPPER(nama) = ?', [strtoupper($namaKabupaten)])->first();
        } catch (Exception $exception) {
            throw new Exception("Error in getKabupatenByNama: " . $exception->getMessage(), 0, $exception);
        }
    }

    /**
     * Membuat kabupaten baru.
     */
    private function createKabupaten(string $namaKabupaten): Kabupaten
    {
        try {
            // TODO: Sertakan provinsi untuk kabupatennya
            return Kabupaten::create(['nama' => $namaKabupaten]);
        } catch (Exception $exception) {
            throw new Exception("Error in createKabupaten: " . $exception->getMessage(), 0, $exception);
        }
    }

    /**
     * Membuat kecamatan baru.
     */
    private function createKecamatan(string $namaKecamatan, int $kabupatenId): Kecamatan
    {
        try {
            return Kecamatan::create([
                'nama' => $namaKecamatan,
                'kabupaten_id' => $kabupatenId
            ]);
        } catch (Exception $exception) {
            throw new Exception("Error in createKecamatan: " . $exception->getMessage(), 0, $exception);
        }
    }

    /**
     * Menambahkan catatan ketika kabupaten baru dibuat dari kabupaten.
     */
    private function addCatatanKabupatenBaruDariKecamatan(string $namaKabupaten, string $namaProvinsi): void
    {
        try {
            $pesan = "Pada penambahan kabupaten '<b>$namaKabupaten</b>', kabupaten '<b>$namaProvinsi</b>' sebelumnya belum ada di database, jadi kabupaten tersebut baru saja ditambahkan.";
            $this->catatan[] = $pesan;
        } catch (Exception $exception) {
            throw new Exception("Error in addCatatanKabupatenBaruDariKecamatan: " . $exception->getMessage(), 0, $exception);
        }
    }

    /**
     * Menambahkan catatan ketika kabupaten sudah ada.
     */
    private function addCatatanKabupatenSudahAda(string $namaKabupaten): void
    {
        try {
            $pesan = "Kabupaten '<b>$namaKabupaten</b>' sudah ada di database, jadi dilewati.";
            $this->catatan[] = $pesan;
        } catch (Exception $exception) {
            throw new Exception("Error in addCatatanKabupatenSudahAda: " . $exception->getMessage(), 0, $exception);
        }
    }

    /**
     * Menambahkan catatan ketika kabupaten baru dibuat.
     */
    private function addCatatanKabupatenBaru(string $namaKabupaten): void
    {
        try {
            $pesan = "Kabupaten '<b>$namaKabupaten</b>' baru saja ditambahkan ke database.";
            $this->catatan[] = $pesan;
        } catch (Exception $exception) {
            throw new Exception("Error in addCatatanKabupatenBaru: " . $exception->getMessage(), 0, $exception);
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