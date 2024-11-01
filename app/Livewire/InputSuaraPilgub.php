<?php

namespace App\Livewire;

use App\Models\Calon;
use App\Models\RingkasanSuaraTPS;
use App\Models\SuaraCalon;
use App\Models\SuaraTPS;
use App\Models\TPS;
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

class InputSuaraPilgub extends Component
{
    use WithPagination, WithoutUrlPagination;

    public int $perPage = 10;

    public string $keyword = '';

    public string $posisi = 'GUBERNUR';

    public array $ignoredColumns = ['KECAMATAN', 'KELURAHAN', 'TPS', 'CALON'];

    public array $partisipasi = ['HIJAU', 'KUNING', 'MERAH'];

    public function render()
    {
        $userWilayah = session('user_wilayah');
        $paslon = $this->getCalon();

        if (count($this->partisipasi) > 0) {
            $tps = $this->getTPSBasedOnPartisipasi();
            return view('livewire.input-suara-pilgub', compact('tps', 'paslon'));
        }
        
        $tps = $this->getTPS();
        return view('livewire.input-suara-pilgub', compact('tps', 'paslon'));
    }

    public function applyFilter() {
        // TODO: Nothing
    }

    public function resetFilter()
    {
        $this->ignoredColumns = ['KECAMATAN', 'KELURAHAN', 'TPS', 'CALON'];
        $this->partisipasi = ['HIJAU', 'KUNING', 'MERAH'];
    }

    private function getTPSBasedOnPartisipasi()
    {
        $userWilayah = session('user_wilayah');

        $builder = RingkasanSuaraTPS::whereHas('tps', function(Builder $builder) use ($userWilayah) {
                $builder->whereHas('kelurahan', function (Builder $builder) use ($userWilayah) {
                    $builder->whereHas('kecamatan', function(Builder $builder) use ($userWilayah) {
                        $builder->whereHas('kabupaten', fn (Builder $builder) => $builder->whereNama($userWilayah));
                    });
                });
            });

        $builder->whereHas('suara', function(Builder $builder) {
            if (in_array('HIJAU', $this->partisipasi)) {
                $builder->orWhereRaw('partisipasi BETWEEN 80 AND 100');
            }

            if (in_array('KUNING', $this->partisipasi)) {
                $builder->orWhereRaw('partisipasi BETWEEN 60 AND 79');
            }

            if (in_array('MERAH', $this->partisipasi)) {
                $builder->orWhereRaw('partisipasi BETWEEN 0 AND 59');
            }
        });

        if ($this->keyword) {
            $builder
                ->whereRaw('LOWER(nama) LIKE ?', ['%' . strtolower($this->keyword) . '%'])
                ->orWhere(function(Builder $builder) {
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

    private function getTPS()
    {
        $userWilayah = session('user_wilayah');

        $builder = RingkasanSuaraTPS::whereHas('tps', function(Builder $builder) use ($userWilayah) {
            $builder->whereHas('kelurahan', function (Builder $builder) use ($userWilayah) {
                $builder->whereHas('kecamatan', function(Builder $builder) use ($userWilayah) {
                    $builder->whereHas('kabupaten', fn (Builder $builder) => $builder->whereNama($userWilayah));
                });
            });
        });
            
        if ($this->keyword) {
            $builder
                ->whereRaw('LOWER(nama) LIKE ?', ['%' . strtolower($this->keyword) . '%'])
                ->orWhere(function(Builder $builder) {
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

        return Calon::with('suaraCalon')
            ->wherePosisi($this->posisi)
            ->whereHas('provinsi', function (Builder $builder) use ($userWilayah) {
                $builder->whereHas('kabupaten', fn (Builder $builder) => $builder->whereNama($userWilayah));
            })
            ->get();
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
