<?php

namespace App\Imports;

use App\Models\Kabupaten;
use App\Models\Provinsi;
use Exception;
use Illuminate\Support\Model;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Validators\Failure;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithValidation;

class KabupatenImport implements ToModel, WithValidation, SkipsOnFailure
{
    use Importable, SkipsFailures;

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

    /**
    * @param Model $collection
    */
    public function model(array $row)
    {
        $namaKabupaten = $row[0];
        $namaProvinsi = $row[1];

        $provinsi = $this->_ambilProvinsiByNama($namaProvinsi);
        
        // Jika provinsi belum ada, buat provinsi baru
        if ($provinsi == null) {
            $provinsi = $this->_buatProvinsiBaru($namaProvinsi);
            $this->_tambahCatatan(namaKabupaten: $namaKabupaten, namaProvinsi: $namaProvinsi);
        }

        // Buat kabupaten dan kaitkan dengan provinsi
        return $this->_buatKabupatenBaru(namaKabupaten: $namaKabupaten, provinsiId: $provinsi->id);
    }

    private function _ambilProvinsiByNama(string $namaProvinsi): Provinsi|null
    {
        try {
            return Provinsi::whereRaw('UPPER(nama) = ?', [strtoupper($namaProvinsi)])->first();
        } catch (Exception $exception) {
            dd($exception);
            throw $exception;
        }
    }

    private function _buatProvinsiBaru(string $namaProvinsi): Provinsi
    {
        try {
            return Provinsi::create(['nama' => $namaProvinsi]);
        } catch (Exception $exception) {
            dd($exception);
            throw $exception;
        }
    }

    private function _buatKabupatenBaru(string $namaKabupaten, int $provinsiId): Kabupaten
    {
        try {
            return new Kabupaten([
                'nama' => $namaKabupaten,
                'provinsi_id' => $provinsiId
            ]);
        } catch (Exception $exception) {
            dd($exception);
            throw $exception;
        }
    }

    private function _tambahCatatan(string $namaKabupaten, string $namaProvinsi): void
    {
        try {
            $catatan = "Pada penambahan kabupaten '<b>$namaKabupaten</b>', provinsi '<b>$namaProvinsi</b>' sebelumnya belum ada di database, jadi provinsi tersebut baru saja ditambahkan ke database saat pengimporan ini.";
            array_push($this->catatan, $catatan);
        } catch (Exception $exception) {
            dd($exception);
            throw $exception;
        }
    }

    public function rules(): array
    {
        $kabupaten = Kabupaten::selectRaw('UPPER(nama) AS nama')->get()->pluck('nama')->toArray();
        $provinsi = Provinsi::selectRaw('UPPER(nama) AS nama')->get()->pluck('nama')->toArray();

        return [
            '0' => function($attribute, $value, $onFailure) use ($kabupaten, $provinsi) {
                // Header Provinsi dilewati.
                if (in_array(strtoupper(trim($value)), $provinsi)) {
                    $onFailure('');
                }

                // Header 'KABUPATEN' dilewati.
                if ($value == 'KABUPATEN') {
                    $onFailure('');
                }
                
                // Kabupaten yang sudah ada dilewati.
                if (in_array(strtoupper(trim($value)), $kabupaten)) {
                    $onFailure("Kabupaten '<b>$value</b>' telah tersedia di database sebelumnya, jadi pengimporan kabupaten ini dilewati.");
                }
            },
        ];
    }
}
