<?php

namespace App\Imports;

use App\Models\Kabupaten;
use App\Models\Provinsi;
use Illuminate\Support\Model;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithValidation;
use Exception;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Row;

class KabupatenImport implements SkipsOnFailure, OnEachRow
{
    use Importable, SkipsFailures;

    private Provinsi|null $_provinsi = null;

    public array $catatan = [];

    public function catatan()
    {
        $failures = $this->failures()
            ->map(fn ($failure) => $failure->errors()) // kumpulkan error-error pada satu row
            ->filter(fn ($errors) => count($errors) > 0) // filter row yang ada error-nya aja
            ->filter(fn ($errors) => $errors[0] != '') // filter row yang error-nya ga kosong
            ->map(fn ($errors) => $errors[0])
            ->toArray(); // kumpulin error pertamanya aja
        
        return array_merge($failures, $this->catatan);
    }

    public function onRow(Row $row)
    {
        try {
            if (isset($row[1])) {
                $this->_imporSemuaKabupaten($row);
            } else {
                $this->_imporKabupatenByProvinsi($row);
            }
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    private function _imporSemuaKabupaten(Row $row): void
    {
        try {
            $rowIndex = $row->getIndex();

            if ($rowIndex == 1) {
                return;
            }

            $row = $row->toArray();

            $namaKabupaten = $row[0];
            $namaProvinsi = $row[1];

            $this->_provinsi = $this->_ambilProvinsiByNama($namaProvinsi);
            
            // Jika provinsi belum ada, buat provinsi baru
            if ($this->_provinsi == null) {
                $this->_provinsi = $this->_buatProvinsiBaru($namaProvinsi);
                $this->_tambahCatatanProvinsiBaruDariKabupaten(
                    namaKabupaten: $namaKabupaten,
                    namaProvinsi: $namaProvinsi
                );
            }

            $kabupaten = $this->_ambilSemuaKabupaten();

            if (in_array(strtoupper(trim($namaKabupaten)), $kabupaten)) {
                $this->_tambahCatatanKabupatenBaru(namaKabupaten: $namaKabupaten);
            } else {
                // Buat kabupaten dan kaitkan dengan provinsi
                $this->_buatKabupatenBaru(namaKabupaten: $namaKabupaten, provinsiId: $this->_provinsi->id);
            }
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    private function _imporKabupatenByProvinsi(Row $row): void
    {
        try {
            $rowIndex = $row->getIndex();
            $row = $row->toArray();

            // Skip header 'KABUPATEN'
            if ($rowIndex == 2) {
                return;
            }

            // Nama provinsi
            if ($rowIndex == 1) {
                $this->_provinsi = $this->_ambilProvinsiByNama($row[0]);
            
                // Jika provinsi belum ada, buat provinsi baru
                if ($this->_provinsi == null) {
                    $this->_provinsi = $this->_buatProvinsiBaru($row[0]);
                    $this->_tambahCatatanProvinsiBaru($row[0]);
                }
            }
            
            if ($rowIndex >= 3) {
                $kabupaten = $this->_ambilSemuaKabupaten();

                if (in_array(strtoupper(trim($row[0])), $kabupaten)) {
                    $this->_tambahCatatanKabupatenBaru(namaKabupaten: $row[0]);
                } else {
                    // Buat kabupaten dan kaitkan dengan provinsi
                    $this->_buatKabupatenBaru(namaKabupaten: $row[0], provinsiId: $this->_provinsi->id);
                }
            }
        } catch (Exception $exception) {
            // dd($exception);
            throw $exception;
        }
    }

    private function _ambilProvinsiByNama(string $namaProvinsi): Provinsi|null
    {
        try {
            return Provinsi::whereRaw('UPPER(nama) = ?', [strtoupper($namaProvinsi)])->first();
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    private function _buatProvinsiBaru(string $namaProvinsi): Provinsi
    {
        try {
            return Provinsi::create(['nama' => $namaProvinsi]);
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    private function _buatKabupatenBaru(string $namaKabupaten, int $provinsiId): Kabupaten
    {
        try {
            return Kabupaten::create([
                'nama' => $namaKabupaten,
                'provinsi_id' => $provinsiId
            ]);
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    private function _tambahCatatanProvinsiBaru(string $namaProvinsi): void
    {
        try {
            $pesan = "Provinsi '<b>$namaProvinsi</b>' sebelumnya belum ada di database, jadi provinsi tersebut baru saja ditambahkan ke database saat pengimporan ini.";
            array_push($this->catatan, $pesan);
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    private function _tambahCatatanProvinsiBaruDariKabupaten(string $namaKabupaten, string $namaProvinsi): void
    {
        try {
            $pesan = "Pada penambahan kabupaten '<b>$namaKabupaten</b>', provinsi '<b>$namaProvinsi</b>' sebelumnya belum ada di database, jadi provinsi tersebut baru saja ditambahkan ke database saat pengimporan ini.";
            array_push($this->catatan, $pesan);
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    private function _tambahCatatanKabupatenBaru(string $namaKabupaten): void
    {
        try {
            $pesan = "Kabupaten '<b>$namaKabupaten</b>' sebelumnya sudah ada di database, jadi kabupaten tersebut dilewati saat pengimporan ini.";
            array_push($this->catatan, $pesan);
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    private function _ambilSemuaKabupaten(): array
    {
        try {
            return Kabupaten::selectRaw('UPPER(nama) AS nama')->get()->pluck('nama')->toArray();
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    private function _ambilSemuaProvinsi(): array
    {
        try {
            return Provinsi::selectRaw('UPPER(nama) AS nama')->get()->pluck('nama')->toArray();
        } catch (Exception $exception) {
            throw $exception;
        }
    }
}
