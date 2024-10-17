<?php

namespace App\Imports;

use App\Models\Kabupaten;
use App\Models\Provinsi;
use Illuminate\Support\Model;
use Maatwebsite\Excel\Validators\Failure;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Validation\Rule;

class ProvinsiImport implements ToModel, WithValidation, SkipsOnFailure
{
    /**
    * @param Model $collection
    */
    public function model(array $row)
    {
        return new Provinsi([
            'nama' => $row[0]
        ]);
    }

    public function rules(): array
    {
        $kabupaten = Kabupaten::selectRaw('UPPER(nama) AS nama')->get()->pluck('nama')->toArray();
        return [
            '0' => function($attribute, $value, $onFailure) use ($kabupaten) {
                if ($value == 'PROVINSI') {
                    $onFailure('Provinsi value is not valid.');
                }
                
                if (in_array(strtoupper($value), $kabupaten)) {
                    $onFailure('Value already exists as a kabupaten.');
                }
            },
        ];
    }

    /**
     * @param Failure[] $failures
     */
    public function onFailure(Failure ...$failures)
    {
        // Handle the failures how you'd like.
    }
}
