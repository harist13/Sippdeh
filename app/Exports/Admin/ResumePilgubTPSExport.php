<?php

namespace App\Exports\Admin;

use App\Models\Calon;
use App\Models\ResumeSuaraPilgubTPS;
use App\Traits\SortResumeColumns;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromView;

class ResumePilgubTPSExport implements FromView
{
    use SortResumeColumns;

    public string $posisi = 'GUBERNUR';

    public string $keyword = '';

    public array $selectedKecamatan = [];
    public array $selectedKelurahan = [];

    public array $includedColumns = [];
    public array $partisipasi = [];
    
    public function __construct(
        $keyword,
        $selectedKecamatan,
        $selectedKelurahan,
        $includedColumns,
        $partisipasi,

        $dptSort,
        $suaraSahSort,
        $suaraTidakSahSort,
        $suaraMasukSort,
        $abstainSort,
        $partisipasiSort,

        $paslonIdSort,
        $paslonSort
    )
    {
        $this->keyword = $keyword;
        $this->selectedKecamatan = $selectedKecamatan;
        $this->selectedKelurahan = $selectedKelurahan;
        $this->includedColumns = $includedColumns;
        $this->partisipasi = $partisipasi;

        $this->dptSort = $dptSort;
        $this->suaraSahSort = $suaraSahSort;
        $this->suaraTidakSahSort = $suaraTidakSahSort;
        $this->suaraMasukSort = $suaraMasukSort;
        $this->abstainSort = $abstainSort;
        $this->partisipasiSort = $partisipasiSort;

        $this->paslonIdSort = $paslonIdSort;
        $this->paslonSort = $paslonSort;
    }

    /**
    * @return \Illuminate\Support\View
    */
    public function view(): View
    {
        $includedColumns = $this->includedColumns;
        $paslon = $this->getPaslon();
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
        
        $this->sortResumeSuaraPilgubTpsPaslon($builder);
        $this->sortColumns($builder);
        $this->sortResumeSuaraKotakKosong($builder);

        return $builder->get();
    }

    private function getBaseTPSBuilder(): Builder
    {
        return ResumeSuaraPilgubTPS::query()
            ->selectRaw('
                resume_suara_pilgub_tps.id,
                resume_suara_pilgub_tps.nama,
                resume_suara_pilgub_tps.dpt,
                resume_suara_pilgub_tps.kotak_kosong,
                resume_suara_pilgub_tps.suara_sah,
                resume_suara_pilgub_tps.suara_tidak_sah,
                resume_suara_pilgub_tps.suara_masuk,
                resume_suara_pilgub_tps.abstain,
                resume_suara_pilgub_tps.partisipasi
            ');
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
                $builder->orWhereRaw('partisipasi < 77.5');
            }
            
            if (in_array('HIJAU', $this->partisipasi)) {
                $builder->orWhereRaw('partisipasi >= 77.5');
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

    private function getPaslon(): Collection
    {
        $builder = Calon::with('suaraCalon')
            ->whereProvinsiId(session('operator_provinsi_id'))
            ->wherePosisi($this->posisi);

        return $builder->get();
    }
}
