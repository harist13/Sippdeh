<?php

namespace App\Livewire\Admin\Resume\Pilgub\PerTps;

// Models
use App\Models\ResumeSuaraPilgubTPS;
use App\Models\Calon;
use App\Models\SuaraCalon;
use App\Models\SuaraTPS;

// Livewire
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithoutUrlPagination;
use Livewire\Attributes\On;

// Facades
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

// Export
use App\Exports\InputSuaraPilgubExport;
use App\Traits\SortResumeColumns;

// Packages
use Sentry\SentrySdk;
use Maatwebsite\Excel\Facades\Excel;

// Others
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Exception;

class ResumeSuaraPilgubPerTps extends Component
{
    use SortResumeColumns, WithPagination, WithoutUrlPagination;

    public string $posisi = 'GUBERNUR';
    public string $keyword = '';
    public int $perPage = 10;
    public ?int $kabupatenId = null;

    public array $selectedKecamatan = [];
    public array $selectedKelurahan = [];
    public array $includedColumns = ['KABUPATEN', 'KECAMATAN', 'KELURAHAN', 'TPS', 'CALON'];
    public array $partisipasi = ['HIJAU', 'KUNING', 'MERAH'];

    public function mount($kabupatenId = null)
    {
        $this->kabupatenId = $kabupatenId;
    }

    public function render()
    {
        try {
            $paslon = $this->getCalon();
            $tps = $this->getTps();
            return view('admin.resume.pilgub.per-tps.livewire', compact('tps', 'paslon'));
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
            $this->sortResumeSuaraPilgubTpsPaslon($builder);
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
                        $builder->whereIn('id', $this->selectedKelurahan);
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
                            if ($this->kabupatenId) {
                                $builder->where('kabupaten_id', $this->kabupatenId);
                            }
                            if (!empty($this->selectedKecamatan)) {
                                $builder->whereIn('id', $this->selectedKecamatan);
                            }
                        });
                    });
                });
            } elseif ($this->kabupatenId) {
                $builder->whereHas('tps', function(Builder $builder) {
                    $builder->whereHas('kelurahan', function (Builder $builder) {
                        $builder->whereHas('kecamatan', function(Builder $builder) {
                            $builder->where('kabupaten_id', $this->kabupatenId);
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
            $query = Calon::with('suaraCalon')
                ->wherePosisi($this->posisi);

            if ($this->kabupatenId) {
                $query->where('kabupaten_id', $this->kabupatenId);
            }

            return $query->get();
        } catch (Exception $exception) {
            Log::error($exception);
            SentrySdk::getCurrentHub()->captureException($exception);
            throw $exception;
        }
    }

    #[On('reset-filter')] 
    public function resetFilter(): void
    {
        $this->includedColumns = ['KABUPATEN', 'KECAMATAN', 'KELURAHAN', 'TPS', 'CALON'];
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

    public function export(): BinaryFileResponse
    {
        try {
            $sheet = new InputSuaraPilgubExport(
                $this->keyword,
                $this->selectedKecamatan,
                $this->selectedKelurahan,
                $this->includedColumns,
                $this->partisipasi,
                $this->kabupatenId
            );
    
            return Excel::download($sheet, 'resume-suara-pemilihan-gubernur.xlsx');
        } catch (Exception $exception) {
            Log::error($exception);
            SentrySdk::getCurrentHub()->captureException($exception);
            throw $exception;
        }
    }
}