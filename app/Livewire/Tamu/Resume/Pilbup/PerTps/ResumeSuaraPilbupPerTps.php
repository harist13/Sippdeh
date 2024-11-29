<?php

namespace App\Livewire\Tamu\Resume\Pilbup\PerTps;

// Laravel Facades
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

// Models
use App\Models\Calon;
use App\Models\ResumeSuaraPilbupTPS;
use App\Models\Kabupaten;

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

class ResumeSuaraPilbupPerTps extends Component
{
    use SortResumeColumns, WithPagination, WithoutUrlPagination;

    public string $posisi = 'BUPATI';

    public string $keyword = '';
    public int $perPage = 10;

    public array $selectedKecamatan = [];
    public array $selectedKelurahan = [];

    public array $includedColumns = ['KABUPATEN/KOTA', 'KECAMATAN', 'KELURAHAN', 'TPS', 'CALON'];
    public array $partisipasi = ['HIJAU', 'MERAH'];

    public function render(): View
    {
        try {
            $paslon = $this->getPaslon();
            $tps = $this->getTps();
            return view('Tamu.resume.pilbup.per-tps.livewire', compact('tps', 'paslon'));
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

     public function exportPdf()
    {
        try {
            // Get all data without pagination
            $builder = $this->getBaseTPSBuilder();
            
            $this->filterKeyword($builder);
            $this->filterKelurahan($builder);
            $this->filterKecamatan($builder);
            $this->filterPartisipasi($builder);

            $data = $builder->get();

            if ($data->isEmpty()) {
                $this->dispatch('showAlert', [
                    'type' => 'error',
                    'message' => 'Tidak ada data untuk di-export'
                ]);
                return;
            }

            $kabupaten = Kabupaten::whereId(session('Tamu_kabupaten_id'))->first();
            $paslon = $this->getPaslon();

            $pdf = PDF::loadView('exports.resume-suara-pilbup-tps-pdf', [
                'data' => $data,
                'logo' => $kabupaten->logo ?? null,
                'kabupaten' => $kabupaten,
                'paslon' => $paslon,
                'includedColumns' => $this->includedColumns,
                'isPilkadaTunggal' => count($paslon) === 1
            ]);

            $pdf->setPaper('A4', 'landscape');

            return response()->streamDownload(function() use ($pdf) {
                echo $pdf->output();
            }, 'resume-suara-pilbup-per-tps.pdf', [
                'Content-Type' => 'application/pdf',
            ]);

        } catch (Exception $e) {
            Log::error('PDF Export Error: ' . $e->getMessage());
            $this->dispatch('showAlert', [
                'type' => 'error',
                'message' => 'Gagal mengekspor PDF'
            ]);
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
                                $builder->whereId(session('Tamu_kabupaten_id'));
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
                ->whereKabupatenId(session('Tamu_kabupaten_id'))
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
        $this->partisipasi = ['HIJAU', 'MERAH'];
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
            $sheet = new InputSuaraPilbupExport(
                $this->keyword,
                $this->selectedKecamatan,
                $this->selectedKelurahan,
                $this->includedColumns,
                $this->partisipasi,

                $this->dptSort,
                $this->suaraSahSort,
                $this->suaraTidakSahSort,
                $this->suaraMasukSort,
                $this->abstainSort,
                $this->partisipasiSort,

                $this->paslonIdSort,
                $this->paslonSort,
            );
    
            return Excel::download($sheet, 'resume-suara-pemilihan-bupkota.xlsx');
        } catch (Exception $exception) {
            Log::error($exception);
            SentrySdk::getCurrentHub()->captureException($exception);

            throw $exception;
        }
    }
}