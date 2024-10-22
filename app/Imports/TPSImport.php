<?php

namespace App\Imports;

use App\Models\Kabupaten;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\SkipsUnknownSheets;
use Maatwebsite\Excel\Concerns\WithProgressBar;
use Maatwebsite\Excel\Concerns\Importable;
use Exception;

class TPSImport implements WithMultipleSheets, SkipsUnknownSheets, WithProgressBar
{
    use Importable;

    private array $importSheets = [];

    // private array $catatan = [];

    // /**
    //  * Mengambil catatan dari proses impor.
    //  */
    // public function getCatatan(): array
    // {
    //     return $this->catatan;
    // }

    public function sheets(): array
    {
        try {
            $kabupaten = Kabupaten::all();
            $kabupaten = $kabupaten
                ->mapWithKeys(function(Kabupaten $kabupaten) {
                    $tpsImport = new TPSSheetImport();
                    $sheet = [$kabupaten->nama => $tpsImport];

                    $this->importSheets[$kabupaten->nama] = $tpsImport;

                    return $sheet;
                })
                ->toArray();

            return $kabupaten;
        } catch (Exception $exception) {
            throw new Exception("Error in sheets: " . $exception->getMessage(), 0, $exception);
        }
    }
    
    public function onUnknownSheet($sheetName)
    {
        // $this->catatan[] = "Kabupaten/kota '<b>$sheetName</b>' tidak ditemukan pada berkas yang ingin diimpor.";
    }
}