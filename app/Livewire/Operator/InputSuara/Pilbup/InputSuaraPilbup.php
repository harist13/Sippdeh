<?php

namespace App\Livewire\Operator\InputSuara\Pilbup;

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
use App\Exports\InputSuaraPilbupExport;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;
use Sentry\SentrySdk;
use Exception;

class InputSuaraPilbup extends Component
{
    use WithPagination, WithoutUrlPagination;

    public string $posisi = 'BUPATI';

    public string $keyword = '';

    public int $perPage = 10;

    public array $selectedKecamatan = [];
    public array $selectedKelurahan = [];
    public array $includedColumns = ['KABUPATEN', 'KECAMATAN', 'KELURAHAN', 'TPS', 'CALON'];
    public array $partisipasi = ['HIJAU', 'KUNING', 'MERAH'];

    public function render()
    {
        $paslon = $this->getCalon();
        $tps = $this->getTPS();

        return view('operator.input-suara.pilbup.livewire', compact('tps', 'paslon'));
    }

    private function getTps()
    {
        try {
            $builder = $this->getBaseTPSBuilder();
    
            $this->filterKeyword($builder);
            $this->filterKelurahan($builder);
            $this->filterKecamatan($builder);
            $this->filterPartisipasi($builder);
    
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
            return ResumeSuaraPilbupTPS::whereHas('tps', function(Builder $builder) {
                $builder->whereHas('kelurahan', function (Builder $builder) {
                    $builder->whereHas('kecamatan', function(Builder $builder) {
                        $builder->whereHas('kabupaten', function (Builder $builder) {
                            $builder->whereNama(session('user_wilayah'));
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
                    $builder->orWhereRaw('partisipasi BETWEEN 80 AND 100');
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
            $builder = Calon::with('suaraCalon')->wherePosisi($this->posisi);
            $builder->whereHas('kabupaten', fn (Builder $builder) => $builder->whereNama(session('user_wilayah')));
            
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

    #[On('submit-tps')]
    public function submit($data)
    {
        try {
            DB::beginTransaction();

            foreach ($data as $datum) {
                $this->insertSuaraTps($datum['id'], $datum['kotak_kosong'], $datum['suara_tidak_sah']);
                foreach ($datum['suara_calon'] as $suaraCalonDatum) {
                    $this->insertSuaraCalon($datum['id'], $suaraCalonDatum['id'], $suaraCalonDatum['suara']);
                }
            }

            DB::commit();

            session()->flash('pesan_sukses', 'Berhasil menyimpan data.');
            $this->dispatch('data-stored', status: 'sukses');
        } catch (Exception $exception) {
            DB::rollBack();

            Log::error($exception);
            SentrySdk::getCurrentHub()->captureException($exception);

            session()->flash('pesan_gagal', 'Gagal menyimpan data.');
            $this->dispatch('data-stored', status: 'gagal');
        }
    }

    private function insertSuaraTps(int $tpsId, int $kotakKosong, int $suaraTidakSah): void
    {
        try {
            SuaraTPS::updateOrCreate(
                [
                    'tps_id' => $tpsId,
                    'posisi' => $this->posisi
                ],
                [
                    'kotak_kosong' => $kotakKosong,
                    'suara_tidak_sah' => $suaraTidakSah,
                    'operator_id' => Auth::id()
                ]
            );
        } catch (Exception $exception) {
            throw $exception;
        }
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
        try {
            $sheet = new InputSuaraPilbupExport(
                $this->keyword,
                $this->selectedKecamatan,
                $this->selectedKelurahan,
                $this->includedColumns,
                $this->partisipasi
            );
    
            return Excel::download($sheet, 'resume-suara-pemilihan-bupati.xlsx');
        } catch (Exception $exception) {
            Log::error($exception);
            SentrySdk::getCurrentHub()->captureException($exception);

            throw $exception;
        }
    }
}