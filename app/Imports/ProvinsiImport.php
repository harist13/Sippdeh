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

class ProvinsiImport implements ToModel, WithValidation, SkipsOnFailure
{
    use Importable, SkipsFailures;

    /**
    * @param Model $collection
    */
    public function model(array $row)
    {
        return new Provinsi(['nama' => $row[0]]);
    }

    public function rules(): array
    {
        try {
            $kabupaten = $this->_ambilSemuaKabupaten();
            $provinsi = $this->_ambilSemuaProvinsi();

            return [
                '0' => function($attribute, $value, $onFailure) use ($kabupaten, $provinsi) {
                    // Header 'PROVINSI' dilewati.
                    if ($value == 'PROVINSI') {
                        $onFailure('');
                    }

                    // Header Kabupaten/Kota dilewati.
                    if (in_array(strtoupper(trim($value)), $kabupaten)) {
                        $onFailure('');
                    }
                    
                    // Provinsi yang sudah ada dilewati.
                    if (in_array(strtoupper(trim($value)), $provinsi)) {
                        $onFailure("Provinsi '<b>$value</b>' telah tersedia di database sebelumnya, jadi pengimporan provinsi ini dilewati.");
                    }
                },
            ];
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
