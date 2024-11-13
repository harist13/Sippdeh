<?php

namespace App\Exports;

use App\Models\Calon;
use App\Models\ResumeSuaraPilgubTPS;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromView;

class InputSuaraPilgubExport implements FromView
{
    public string $keyword = '';

    public array $selectedKabupaten = [];
    public array $selectedKecamatan = [];
    public array $selectedKelurahan = [];
    public array $includedColumns = ['KECAMATAN', 'KELURAHAN', 'CALON'];
    public array $partisipasi = ['HIJAU', 'KUNING', 'MERAH'];
    
    public function __construct($keyword, $selectedKecamatan, $selectedKelurahan, $includedColumns, $partisipasi)
    {
        $this->keyword = $keyword;
        $this->selectedKecamatan = $selectedKecamatan;
        $this->selectedKelurahan = $selectedKelurahan;
        $this->includedColumns = $includedColumns;
        $this->partisipasi = $partisipasi;
    }

    /**
    * @return \Illuminate\Support\View
    */
    public function view(): View
    {
        $includedColumns = $this->includedColumns;
        $paslon = $this->getCalon();
        $tps = $this->getTPS();
        
        if (!empty($this->selectedKelurahan)) {
            return view('exports.input-suara.pilgub.kelurahan-table', compact('tps', 'paslon', 'includedColumns'));
        }
        
        return view('exports.input-suara.pilgub.kecamatan-table', compact('tps', 'paslon', 'includedColumns'));
    }

    private function getTPS()
    {
        $userWilayah = session('user_wilayah');

        $builder = ResumeSuaraPilgubTPS::whereHas('tps', function(Builder $builder) use ($userWilayah) {
            $builder->whereHas('kelurahan', function (Builder $builder) use ($userWilayah) {
                $builder->whereHas('kecamatan', function(Builder $builder) use ($userWilayah) {
                    $builder->whereHas('kabupaten', function (Builder $builder) use ($userWilayah) {
                        $builder->whereNama($userWilayah);
                    });
                });
            });
        });

        if (!empty($this->selectedKelurahan)) {
            $builder->whereHas('tps', function(Builder $builder) use ($userWilayah) {
                $builder->whereHas('kelurahan', function (Builder $builder) {
                    if (!empty($this->selectedKelurahan)) {
                        $builder->whereIn('id', $this->selectedKelurahan);
                    }
                });
            });
        }

        if (!empty($this->selectedKecamatan)) {
            $builder->whereHas('tps', function(Builder $builder) use ($userWilayah) {
                $builder->whereHas('kelurahan', function (Builder $builder) {
                    $builder->whereHas('kecamatan', function(Builder $builder) {
                        $builder->whereIn('id', $this->selectedKecamatan);
                    });
                });
            });
        }

        $builder->where(function (Builder $builder) {
            if (in_array('MERAH', $this->partisipasi)) {
                $builder->where(function (Builder $builder) {
                    $builder
                        ->whereHas('suara', function (Builder $builder) {
                            $builder->whereRaw('partisipasi BETWEEN 0 AND 59.9');
                        })
                        ->orWhereDoesntHave('suara');
                });
            }
        
            if (in_array('KUNING', $this->partisipasi)) {
                $builder->orWhereHas('suara', function (Builder $builder) {
                    $builder->whereRaw('partisipasi BETWEEN 60 AND 79.9');
                });
            }
            
            if (in_array('HIJAU', $this->partisipasi)) {
                $builder->orWhereHas('suara', function (Builder $builder) {
                    $builder->whereRaw('partisipasi BETWEEN 80 AND 100');
                });
            }
        });

        if ($this->keyword) {
            $builder->whereHas('tps', function(Builder $builder) {
                $builder->whereRaw('LOWER(nama) LIKE ?', ['%' . strtolower($this->keyword) . '%']);
                
                $builder->orWhereHas('kelurahan', function (Builder $builder) {
                    $builder->whereRaw('LOWER(nama) LIKE ?', ['%' . strtolower($this->keyword) . '%']);
                });

                $builder->orWhereHas('kelurahan', function (Builder $builder) {
                    $builder->whereHas('kecamatan', function (Builder $builder) {
                        $builder->whereRaw('LOWER(nama) LIKE ?', ['%' . strtolower($this->keyword) . '%']);
                    });
                });
            });
        }

        return $builder->get();
    }

    private function addPartisipasiFilter(Builder $builder)
    {
        $builder->where(function (Builder $builder) {
            if (in_array('MERAH', $this->partisipasi)) {
                $builder->orWhereRaw('partisipasi BETWEEN 0 AND 59.9');
            }
        
            if (in_array('KUNING', $this->partisipasi)) {
                $builder->orWhereRaw('partisipasi BETWEEN 60 AND 79.9');
            }
            
            if (in_array('HIJAU', $this->partisipasi)) {
                $builder->orWhereRaw('partisipasi BETWEEN 80 AND 100');
            }
        });
    }

    private function getCalon()
    {
        $builder = Calon::wherePosisi('GUBERNUR');
        $builder->whereHas('provinsi', function (Builder $builder) {
            $builder->whereHas('kabupaten', fn (Builder $builder) => $builder->whereNama(session('user_wilayah')));
        });

        return $builder->get();
    }
}
