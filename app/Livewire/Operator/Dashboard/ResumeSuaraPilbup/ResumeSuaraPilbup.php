<?php

namespace App\Livewire\Operator\Dashboard\ResumeSuaraPilbup;

// Laravel Facades
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

// Models
use App\Models\Calon;
use App\Models\ResumeSuaraPilbupTPS;
use App\Models\SuaraCalon;
use App\Models\SuaraTPS;

// Livewire
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithoutUrlPagination;
use Livewire\Attributes\On;

// Others
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use App\Exports\InputSuaraPilbupExport;
use App\Traits\SortResumeColumns;
use Sentry\SentrySdk;
use Exception;

class ResumeSuaraPilbup extends Component
{
    use SortResumeColumns, WithPagination, WithoutUrlPagination;

    public string $posisi = 'BUPATI';

    public string $keyword = '';

    public int $perPage = 10;

    public array $selectedKecamatan = [];
    public array $selectedKelurahan = [];
    public array $includedColumns = ['KABUPATEN/KOTA', 'KECAMATAN', 'KELURAHAN', 'TPS', 'CALON'];
    public array $partisipasi = ['HIJAU', 'KUNING', 'MERAH'];

    public function render(): View
    {
        try {
            $paslon = $this->getCalon();
            $tps = $this->getTps();
            return view('operator.dashboard.resume-suara-pilbup.livewire', compact('tps', 'paslon'));
        } catch (Exception $exception) {
            Log::error($exception);
            SentrySdk::getCurrentHub()->captureException($exception);

            throw $exception;
        }
    }

    private function getTps()
    {
        try {
            $builder = $this->getBaseTPSBuilder();
    
            $this->filterKeyword($builder);
            $this->filterKelurahan($builder);
            $this->filterKecamatan($builder);
            $this->filterPartisipasi($builder);
            $this->sortResumeSuaraPilbupTpsPaslon($builder);
            $this->sortColumns($builder);
            $this->sortResumeSuaraKotakKosong($builder);
    
            return $builder->paginate($this->perPage);
        } catch (Exception $exception) {
            Log::error($exception);
            SentrySdk::getCurrentHub()->captureException($exception);

            throw $exception;
        }
    }

    private function getBaseTPSBuilder(): Builder
    {
        try {
            return ResumeSuaraPilbupTPS::query()
                ->selectRaw('
                    resume_suara_pilbup_tps.id,
                    resume_suara_pilbup_tps.nama,
                    resume_suara_pilbup_tps.dpt,
                    resume_suara_pilbup_tps.kotak_kosong,
                    resume_suara_pilbup_tps.suara_sah,
                    resume_suara_pilbup_tps.suara_tidak_sah,
                    resume_suara_pilbup_tps.suara_masuk,
                    resume_suara_pilbup_tps.abstain,
                    resume_suara_pilbup_tps.partisipasi
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
                    $builder->orWhereRaw('partisipasi BETWEEN 0 AND 59.9');
                }
            
                if (in_array('KUNING', $this->partisipasi)) {
                    $builder->orWhereRaw('partisipasi BETWEEN 60 AND 79.9');
                }
                
                if (in_array('HIJAU', $this->partisipasi)) {
                    $builder->orWhereRaw('partisipasi >= 80');
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

    private function getCalon(): Collection
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

    #[On('reset-filter')] 
    public function resetFilter(): void
    {
        $this->includedColumns = ['KABUPATEN/KOTA', 'KECAMATAN', 'KELURAHAN', 'TPS', 'CALON'];
        $this->selectedKecamatan = [];
        $this->selectedKelurahan = [];
        $this->partisipasi = ['HIJAU', 'KUNING', 'MERAH'];
    }

    #[On('apply-filter')]
    public function applyFilter($selectedKecamatan, $selectedKelurahan, $includedColumns, $partisipasi)
    {
        $this->selectedKecamatan = $selectedKecamatan;
        $this->selectedKelurahan = $selectedKelurahan;
        $this->includedColumns = $includedColumns;
        $this->partisipasi = $partisipasi;
    }
}