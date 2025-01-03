<?php

namespace App\Livewire\Operator\InputSuara\Pilgub;

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

// Packages
use Sentry\SentrySdk;
use Maatwebsite\Excel\Facades\Excel;

// Others
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Exception;
use Livewire\WithFileUploads;

class InputSuaraPilgub extends Component
{
    use WithPagination, WithoutUrlPagination, WithFileUploads;

    protected $listeners = [
        '$refresh',
        'import-success' => 'handleImportSuccess',
        'import-error' => 'handleImportError'
    ];

    public function handleImportSuccess($message)
    {
        session()->flash('pesan_sukses', $message);
    }

    public function handleImportError($message)
    {
        session()->flash('pesan_gagal', $message);
    }

    public string $posisi = 'GUBERNUR';

    public string $keyword = '';
    public int $perPage = 10;

    public array $selectedKecamatan = [];
    public array $selectedKelurahan = [];
    public array $includedColumns = ['KABUPATEN', 'KECAMATAN', 'KELURAHAN', 'TPS', 'CALON'];
    public array $partisipasi = ['HIJAU', 'MERAH'];

    public string $tpsQuery;

    public function render()
    {
        $paslon = $this->getCalon();
        $tps = $this->getTps();
        return view('operator.input-suara.pilgub.input-suara', compact('tps', 'paslon'));
    }

    private function getTps()
    {
        $builder = $this->getBaseTPSBuilder();

        $this->filterKeyword($builder);
        $this->filterKelurahan($builder);
        $this->filterKecamatan($builder);
        $this->filterPartisipasi($builder);

        $this->tpsQuery = $builder->toRawSql();

        return $builder->paginate($this->perPage);
    }

    private function getBaseTPSBuilder(): Builder
    {
        return ResumeSuaraPilgubTPS::whereHas('tps.kelurahan.kecamatan.kabupaten', fn (Builder $builder) => $builder->whereId(session('operator_kabupaten_id')));
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

    private function filterKeyword(Builder $builder)
    {
        if ($this->keyword) {
            $builder->whereHas('tps', function(Builder $builder) {
                $builder->whereRaw('LOWER(nama) LIKE ?', ['%' . strtolower($this->keyword) . '%']);
                
                $builder->orWhereHas('kelurahan', function (Builder $builder) {
                    $builder->whereRaw('LOWER(nama) LIKE ?', ['%' . strtolower($this->keyword) . '%']);

                    $builder->orWhereHas('kecamatan', function (Builder $builder) {
                        $builder->whereRaw('LOWER(nama) LIKE ?', ['%' . strtolower($this->keyword) . '%']);

                        $builder->orWhereHas('kabupaten', function (Builder $builder) {
                            $builder->whereRaw('LOWER(nama) LIKE ?', ['%' . strtolower($this->keyword) . '%']);
                        });
                    });
                });
            });
        }
    }

    private function getCalon(): Collection
    {
        $builder = Calon::with('suaraCalon')
            ->whereProvinsiId(session('operator_provinsi_id'))
            ->wherePosisi($this->posisi);

        return $builder->get();
    }

    #[On('reset-filter')] 
    public function resetFilter()
    {
        $this->selectedKecamatan = [];
        $this->selectedKelurahan = [];
        $this->includedColumns = ['KABUPATEN', 'KECAMATAN', 'KELURAHAN', 'TPS', 'CALON'];
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

    #[On('submit-tps')]
    public function submit($data): void
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
}