<?php

namespace App\Exports;

// Models
use App\Models\Calon;
use App\Models\ResumeSuaraPilwaliTPS;
use App\Traits\SortResumeColumns;

// Laravel Facades
use Illuminate\Support\Facades\Log;

// Others
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromView;
use Sentry\SentrySdk;
use Exception;

class InputSuaraPilwaliExport implements FromView
{
    use SortResumeColumns;
    
    public string $posisi = 'WALIKOTA';

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
        $tps = $this->getTPS();
        
        return view('exports.input-suara.table', compact('tps', 'paslon', 'includedColumns'));
    }

    private function getTps(): Collection
    {
        try {
            $builder = $this->getBaseTPSBuilder();
    
            $this->filterKeyword($builder);
            $this->filterKelurahan($builder);
            $this->filterKecamatan($builder);
            $this->filterPartisipasi($builder);

            $this->sortResumeSuaraPilwaliTpsPaslon($builder);
            $this->sortColumns($builder);
            $this->sortResumeSuaraKotakKosong($builder);
    
            return $builder->get();
        } catch (Exception $exception) {
            Log::error($exception);
            SentrySdk::getCurrentHub()->captureException($exception);

            throw $exception;
        }
    }

    private function getBaseTPSBuilder(): Builder
    {
        try {
            return ResumeSuaraPilwaliTPS::query()
                ->selectRaw('
                    resume_suara_pilwali_tps.id,
                    resume_suara_pilwali_tps.nama,
                    resume_suara_pilwali_tps.dpt,
                    resume_suara_pilwali_tps.kotak_kosong,
                    resume_suara_pilwali_tps.suara_sah,
                    resume_suara_pilwali_tps.suara_tidak_sah,
                    resume_suara_pilwali_tps.suara_masuk,
                    resume_suara_pilwali_tps.abstain,
                    resume_suara_pilwali_tps.partisipasi
                ')
                ->whereHas('tps', function(Builder $builder) {
                    $builder->whereHas('kelurahan', function (Builder $builder) {
                        $builder->whereHas('kecamatan', function(Builder $builder) {
                            $builder->whereHas('kabupaten', function (Builder $builder) {
                                $builder->whereId(session('operator_kabupaten_id'));
                            });
                        });
                    });
                });
        } catch (Exception $exception) {
            Log::error($exception);
            SentrySdk::getCurrentHub()->captureException($exception);

            throw $exception;
        }
    }

    private function filterKelurahan(Builder $builder): void
    {
        try {
            if (!empty($this->selectedKelurahan)) {
                $builder->whereHas('tps', function(Builder $builder) {
                    $builder->whereHas('kelurahan', function (Builder $builder) {
                        if (!empty($this->selectedKelurahan)) {
                            $builder->whereIn('id', $this->selectedKelurahan);
                        }
                    });
                });
            }
        } catch (Exception $exception) {
            Log::error($exception);
            SentrySdk::getCurrentHub()->captureException($exception);

            throw $exception;
        }
    }

    private function filterKecamatan(Builder $builder): void
    {
        try {
            if (!empty($this->selectedKecamatan)) {
                $builder->whereHas('tps', function(Builder $builder) {
                    $builder->whereHas('kelurahan', function (Builder $builder) {
                        $builder->whereHas('kecamatan', function(Builder $builder) {
                            $builder->whereIn('id', $this->selectedKecamatan);
                        });
                    });
                });
            }
        } catch (Exception $exception) {
            Log::error($exception);
            SentrySdk::getCurrentHub()->captureException($exception);

            throw $exception;
        }
    }

    private function filterPartisipasi(Builder $builder): void
    {
        try {
            $builder->where(function (Builder $builder) {
                if (in_array('MERAH', $this->partisipasi)) {
                    $builder->orWhereRaw('partisipasi < 77.5');
                }
                
                if (in_array('HIJAU', $this->partisipasi)) {
                    $builder->orWhereRaw('partisipasi >= 77.5');
                }
            });
        } catch (Exception $exception) {
            Log::error($exception);
            SentrySdk::getCurrentHub()->captureException($exception);

            throw $exception;
        }
    }

    private function filterKeyword(Builder $builder): void
    {
        try {
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
        } catch (Exception $exception) {
            Log::error($exception);
            SentrySdk::getCurrentHub()->captureException($exception);

            throw $exception;
        }
    }

    private function getPaslon(): Collection
    {
        try {
            $builder = Calon::with('suaraCalon')
                ->whereKabupatenId(session('operator_kabupaten_id'))
                ->wherePosisi($this->posisi);
    
            return $builder->get();
        } catch (Exception $exception) {
            Log::error($exception);
            SentrySdk::getCurrentHub()->captureException($exception);

            throw $exception;
        }
    }
}
