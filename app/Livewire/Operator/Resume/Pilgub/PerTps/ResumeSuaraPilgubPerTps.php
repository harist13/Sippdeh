<?php

namespace App\Livewire\Operator\Resume\Pilgub\PerTps;

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

    public array $selectedKecamatan = [];
    public array $selectedKelurahan = [];
    public array $includedColumns = ['KABUPATEN', 'KECAMATAN', 'KELURAHAN', 'TPS', 'CALON'];
    public array $partisipasi = ['HIJAU', 'KUNING', 'MERAH'];

    public function render()
    {
        $paslon = $this->getCalon();
        $tps = $this->getTps();
        return view('operator.resume.pilgub.per-tps.livewire', compact('tps', 'paslon'));
    }

    private function getTps()
    {
        $builder = $this->getBaseTPSBuilder();

        $this->filterKeyword($builder);
        $this->filterKelurahan($builder);
        $this->filterKecamatan($builder);
        $this->filterPartisipasi($builder);
        $this->sortResumeSuaraPilgubTpsPaslon($builder);
        $this->sortColumns($builder);

        return $builder->paginate($this->perPage);
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

    private function filterKeyword(Builder $builder)
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
        $builder = Calon::with('suaraCalon')->wherePosisi($this->posisi);

        $builder->whereHas('provinsi', function (Builder $builder) {
            $builder->whereHas('kabupaten', fn (Builder $builder) => $builder->whereNama(session('user_wilayah')));
        });

        return $builder->get();
    }

    #[On('reset-filter')] 
    public function resetFilter()
    {
        $this->selectedKecamatan = [];
        $this->selectedKelurahan = [];
        $this->includedColumns = ['KABUPATEN', 'KECAMATAN', 'KELURAHAN', 'TPS', 'CALON'];
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

    private function insertSuaraCalon(int $tpsId, int $calonId, int $suara): void
    {
        try {
            SuaraCalon::updateOrCreate(
                [
                    'tps_id' => $tpsId,
                    'calon_id' => $calonId,
                ],
                [
                    'suara' => $suara,
                    'operator_id' => Auth::id()
                ]
            );
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function export(): BinaryFileResponse
    {
        $sheet = new InputSuaraPilgubExport(
            $this->keyword,
            $this->selectedKecamatan,
            $this->selectedKelurahan,
            $this->includedColumns,
            $this->partisipasi
        );

        return Excel::download($sheet, 'resume-suara-pemilihan-gubernur.xlsx');
    }
}