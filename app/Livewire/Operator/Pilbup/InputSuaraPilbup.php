<?php

namespace App\Livewire\Operator\Pilbup;

use App\Models\Calon;
use App\Models\ResumeSuaraPilbupTPS;
use App\Models\SuaraCalon;
use App\Models\SuaraTPS;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;
use Livewire\Component;
use Livewire\Attributes\On;
use Sentry\SentrySdk;
use Exception;

class InputSuaraPilbup extends Component
{
    use WithPagination, WithoutUrlPagination;

    public string $posisi = 'BUPATI';

    public string $keyword = '';

    public int $perPage = 10;

    public array $selectedKabupaten = [];
    public array $selectedKecamatan = [];
    public array $selectedKelurahan = [];
    public array $includedColumns = ['KECAMATAN', 'KELURAHAN', 'TPS', 'CALON'];
    public array $partisipasi = ['HIJAU', 'KUNING', 'MERAH'];

    public function render()
    {
        $paslon = $this->getCalon();
        $tps = $this->getTPS();

        return view('livewire.operator.pilbup.input-suara-pilbup', compact('tps', 'paslon'));
    }

    private function getTPS()
    {
        $userWilayah = session('user_wilayah');

        $builder = ResumeSuaraPilbupTPS::whereHas('tps', function(Builder $builder) use ($userWilayah) {
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

        return $builder->paginate($this->perPage);
    }

    private function getCalon()
    {
        $userWilayah = session('user_wilayah');
        $builder = Calon::with('suaraCalon')->wherePosisi($this->posisi);

        $builder->whereHas('kabupaten', fn (Builder $builder) => $builder->whereNama($userWilayah));

        return $builder->get();
    }

    #[On('reset-filter')] 
    public function resetFilter()
    {
        $this->includedColumns = ['KECAMATAN', 'KELURAHAN', 'TPS', 'CALON'];
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
        
            $operatorId = Auth::id();

            foreach ($data as $datum) {
                // Simpan atau update data di tabel suara_tps
                SuaraTPS::updateOrCreate(
                    [
                        'tps_id' => $datum['id'],
                    ],
                    [
                        'dpt' => $datum['dpt'],
                        'kotak_kosong_pilbup' => $datum['kotak_kosong'],
                        'suara_tidak_sah' => $datum['suara_tidak_sah'],
                        'operator_id' => $operatorId,
                    ]
                );
        
                // Simpan atau update data di tabel suara_calon
                foreach ($datum['suara_calon'] as $suaraCalonData) {
                    SuaraCalon::updateOrCreate(
                        [
                            'tps_id' => $datum['id'],
                            'calon_id' => $suaraCalonData['id'],
                        ],
                        [
                            'suara' => $suaraCalonData['suara'],
                            'operator_id' => $operatorId,
                        ]
                    );
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
}