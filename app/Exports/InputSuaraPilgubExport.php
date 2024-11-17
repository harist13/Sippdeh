<?php

namespace App\Exports;

use App\Models\Calon;
use App\Models\ResumeSuaraPilgubTPS;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromView;

class InputSuaraPilgubExport implements FromView
{
    public string $keyword = '';

    public array $selectedKecamatan = [];
    public array $selectedKelurahan = [];
    public array $includedColumns = [];
    public array $partisipasi = [];
    
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
        $tps = $this->getTps();
        
        return view('exports.input-suara.table', compact('tps', 'paslon', 'includedColumns'));
    }

    private function getTps(): Collection
    {
        $builder = $this->getBaseTPSBuilder();

        $this->filterKeyword($builder);
        $this->filterKelurahan($builder);
        $this->filterKecamatan($builder);
        $this->filterPartisipasi($builder);

        return $builder->get();
    }

    private function getBaseTPSBuilder(): Builder
    {
        return ResumeSuaraPilgubTPS::whereHas('tps', function(Builder $builder) {
            $builder->whereHas('kelurahan', function (Builder $builder) {
                $builder->whereHas('kecamatan', function(Builder $builder) {
                    $builder->whereHas('kabupaten', function (Builder $builder) {
                        $builder->whereNama(session('user_wilayah'));
                    });
                });
            });
        });
    }

    private function filterKelurahan(Builder $builder): void
    {
        if (!empty($this->selectedKelurahan)) {
            $builder->whereHas('tps', function(Builder $builder) {
                $builder->whereHas('kelurahan', function (Builder $builder) {
                    if (!empty($this->selectedKelurahan)) {
                        $builder->whereIn('id', $this->selectedKelurahan);
                    }
                });
            });
        }
    }

    private function filterKecamatan(Builder $builder): void
    {
        if (!empty($this->selectedKecamatan)) {
            $builder->whereHas('tps', function(Builder $builder) {
                $builder->whereHas('kelurahan', function (Builder $builder) {
                    $builder->whereHas('kecamatan', function(Builder $builder) {
                        $builder->whereIn('id', $this->selectedKecamatan);
                    });
                });
            });
        }
    }

    private function filterPartisipasi(Builder $builder): void
    {
        $builder->where(function (Builder $builder) {
            if (in_array('MERAH', $this->partisipasi)) {
                $builder->orWhereRaw('partisipasi BETWEEN 0 AND 59.9');
            }
        
            if (in_array('KUNING', $this->partisipasi)) {
                $builder->orWhereRaw('partisipasi BETWEEN 60 AND 79.9');
            }
            
            if (in_array('HIJAU', $this->partisipasi)) {
                $builder->orWhereRaw('partisipasi >= 80');
            }
        });
    }

    private function filterKeyword(Builder $builder): void
    {
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
    }

    private function getCalon(): Collection
    {
        $builder = Calon::with('suaraCalon')->wherePosisi('GUBERNUR');

        $builder->whereHas('provinsi', function (Builder $builder) {
            $builder->whereHas('kabupaten', fn (Builder $builder) => $builder->whereNama(session('user_wilayah')));
        });

        return $builder->get();
    }
}
