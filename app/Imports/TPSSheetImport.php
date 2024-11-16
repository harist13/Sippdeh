<?php

namespace App\Imports;

use App\Traits\WilayahImport;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\ToModel;
use App\Models\TPS;
use Exception;
use Illuminate\Support\Facades\Log;

class TPSSheetImport implements ToModel, WithStartRow, WithValidation, SkipsOnFailure, WithBatchInserts, WithChunkReading
{
    use WilayahImport, Importable, SkipsFailures;

    private array $kelurahanIds = [];

    private array $catatan = [];

    /**
     * Mengambil catatan dari proses impor.
     */
    public function getCatatan(): array
    {
        return $this->catatan;
    }

    public function startRow(): int
    {
        return 2;
    }

    public function rules(): array
    {
        return [
            '0' => ['required', 'string'], // Validasi kolom kabupaten/kota
            '1' => ['required', 'string'], // Validasi kolom kecamatan
            '2' => ['required', 'string'], // Validasi desa/kelurahan
            '3' => ['required'], // Validasi nomor TPS
            '4' => ['required', 'numeric'], // Validasi DPT
        ];
    }

    /**
     * Memproses setiap batch data.
     */
    public function model(array $row)
    {
        try {
            $namaProvinsi = 'Kalimantan Timur';
            $namaKabupaten = $row[0];
            $namaKecamatan = $row[1];
            $namaKelurahan = $row[2];
            $namaTPS = $row[3];
            $dpt = $row[4];
            
            if (
                (is_null($namaKabupaten) || empty($namaKabupaten)) ||
                (is_null($namaKecamatan) || empty($namaKecamatan)) ||
                (is_null($namaKelurahan) || empty($namaKelurahan)) ||
                (is_null($namaTPS) || empty($namaTPS)) ||
                (is_null($dpt) || empty($dpt))
            ) {
                return null;
            }

            Log::debug("TPS $namaTPS berhasil ditambahkan.");

            return $this->addTPS($namaTPS, $dpt, $namaKelurahan, $namaKecamatan, $namaKabupaten, $namaProvinsi);
        } catch (Exception $exception) {
            throw new Exception("Error in model: " . $exception->getMessage(), 0, $exception);
        }
    }

    /**
     * Buat TPS.
     */
    private function addTPS(
        string $namaTPS,
        string $dpt,
        string $namaKelurahan,
        string $namaKecamatan,
        string $namaKabupaten,
        string $namaProvinsi
    )
    {
        try {
            if (!array_key_exists($namaKelurahan, $this->kelurahanIds)) {
                $kelurahan = $this->getKelurahan($namaKelurahan, $namaKecamatan, $namaKabupaten, $namaProvinsi);
                $this->kelurahanIds[$namaKelurahan] = $kelurahan->id;
            }

            return $this->getTPSModel($namaTPS, $dpt, $this->kelurahanIds[$namaKelurahan]);
        } catch (Exception $exception) {
            throw new Exception("Error in addTPS: " . $exception->getMessage(), 0, $exception);
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
     * Menambahkan catatan ketika TPS sudah ada.
     */
    private function addCatatanTPSSudahAda(string $namaTPS): void
    {
        $this->catatan[] = "TPS '<b>$namaTPS</b>' sudah ada di database.";
    }

    /**
     * Mengambil semua TPS dari database.
     */
    private function getAllTPS(): array
    {
        return TPS::selectRaw('UPPER(nama) AS nama')->get()->pluck('nama')->toArray();
    }

    /**
     * Membaca data dari berkas dalam chunk.
     */
    public function chunkSize(): int
    {
        return 100;
    }

    /**
     * Menambahkan data ke database dalam batch.
     */
    public function batchSize(): int
    {
        return 50;
    }
}