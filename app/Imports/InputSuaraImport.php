<?php

namespace App\Imports;

use App\Models\Calon;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\SuaraCalon;
use App\Models\SuaraTPS;
use App\Models\TPS;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Row;
use Sentry\SentrySdk;
use Exception;

class InputSuaraImport implements OnEachRow
{
    use Importable;
    
    public string $posisi;

    public array $calonIds = [];

    public ?int $suaraTidakSahIndex;

    public function __construct(string $posisi)
    {
        $this->posisi = $posisi;
    }

    public function onRow(Row $row): void
    {
        try {
            $data = $row->toArray();
            $rowIndex = $row->getIndex();

            if ($rowIndex == 1) {
                for ($i = 5; $i <= 15; $i++) {
                    if (!isset($data[$i])) {
                        break;
                    }
                    
                    $paslonName = $data[$i];

                    $names = explode('/', preg_replace('/\s+/', ' ', str_replace(["\r\n", "\r", "\n"], ' ', $paslonName)));
                        
                    if (str_contains(strtolower($names[0]), 'kotak') && str_contains(strtolower($names[0]), 'kosong')) {
                        $this->calonIds[] = 'KOTAK KOSONG';
                        continue;
                    }

                    if (strtolower($names[0]) == 'suara tidak sah') {
                        $this->suaraTidakSahIndex = $i;
                        continue;
                    }

                    $calonName = trim($names[0]);
                    $wakilCalonName = trim($names[1]);
                    
                    $calon = Calon::query()
                        ->whereRaw('LOWER(nama) = ?', [strtolower($calonName)])
                        ->whereRaw('LOWER(nama_wakil) = ?', [strtolower($wakilCalonName)])
                        ->wherePosisi($this->posisi)
                        ->first();

                    if ($calon != null) {
                        $this->calonIds[] = $calon->id;
                    }
                }
            } else {
                $kabupaten = Kabupaten::query()
                    ->whereProvinsiId(session('operator_provinsi_id'))
                    ->whereRaw('LOWER(nama) = ?', [strtolower(trim($data[0]))])
                    ->first();

                if ($kabupaten == null) {
                    return;
                }

                $kecamatan = Kecamatan::query()
                    ->whereKabupatenId($kabupaten->id)
                    ->whereRaw('LOWER(nama) = ?', [strtolower(trim($data[1]))])
                    ->first();

                if ($kecamatan == null) {
                    return;
                }

                $kelurahan = Kelurahan::query()
                    ->whereKecamatanId($kecamatan->id)
                    ->whereRaw('LOWER(nama) = ?', [strtolower(trim($data[2]))])
                    ->first();

                if ($kelurahan == null) {
                    return;
                }

                $tps = TPS::query()
                    ->whereKelurahanId($kelurahan->id)
                    ->whereRaw('LOWER(nama) = ?', [strtolower(trim($data[3]))])
                    ->first();

                if ($tps == null) {
                    return;
                }

                $suaraTidakSah = $data[$this->suaraTidakSahIndex];
                if ($suaraTidakSah) {
                    SuaraTPS::updateOrCreate(
                        [ 'tps_id' => $tps->id, 'posisi' => $this->posisi ],
                        [ 'suara_tidak_sah' => $suaraTidakSah, 'operator_id' => Auth::id() ]
                    );
                }

                foreach ($this->calonIds as $index => $calonId) {
                    if ($calonId == 'KOTAK KOSONG') {
                        $kotakKosong = $data[5];
                        if ($kotakKosong) {
                            SuaraTPS::updateOrCreate(
                                [ 'tps_id' => $tps->id, 'posisi' => $this->posisi ],
    
                                // Index dari Kotak Kosong (kalau ada) pasti selalu ada di 5
                                [ 'kotak_kosong' => $kotakKosong ]
                            );
                        }
                    } else {
                        $suaraCalon = $data[5 + $index];
                        if ($suaraCalon) {
                            SuaraCalon::updateOrCreate(
                                [ 'tps_id' => $tps->id, 'calon_id' => $calonId ],
                                [ 'suara' =>  $suaraCalon]
                            );
                        }
                    }
                }
            }
        } catch (Exception $exception) {
            Log::error($exception);
            SentrySdk::getCurrentHub()->captureException($exception);

            dump($exception);

            throw $exception;
        }
    }
}
