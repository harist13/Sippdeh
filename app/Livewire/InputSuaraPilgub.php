<?php

namespace App\Livewire;

use App\Models\Calon;
use App\Models\SuaraCalon;
use App\Models\SuaraTPS;
use App\Models\TPS;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
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

    #[On('submit')]
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

            Session::flash('pesan_sukses', 'Berhasil menyimpan data.');

            $this->dispatch('data-stored', status: 'sukses');
        } catch (Exception $exception) {
            DB::rollBack();

            Log::error($exception);
            SentrySdk::getCurrentHub()->captureException($exception);

            Session::flash('pesan_gagal', 'Gagal menyimpan data.');

            $this->dispatch('data-stored', status: 'gagal');
        }
    }

    public function render()
    {
        $userWilayah = session('user_wilayah');

        $tps = TPS::with(['suara', 'suaraCalon'])
            ->whereHas('kelurahan', function (Builder $builder) use ($userWilayah) {
                $builder->whereHas('kecamatan', function(Builder $builder) use ($userWilayah) {
                    $builder->whereHas('kabupaten', fn (Builder $builder) => $builder->whereNama($userWilayah));
                });
            });

        $tps->orWhere(function(Builder $builder) {
            $builder->orWhereHas('kelurahan', function (Builder $builder) {
                if ($this->keyword) {
                    $builder->whereRaw('LOWER(nama) LIKE ?', ['%' . strtolower($this->keyword) . '%']);
                }
            });

            $builder->orWhereHas('kelurahan', function (Builder $builder) {
                $builder->whereHas('kecamatan', function (Builder $builder) {
                    if ($this->keyword) {
                        $builder->whereRaw('LOWER(nama) LIKE ?', ['%' . strtolower($this->keyword) . '%']);
                    }
                });
            });
        });

        if ($this->keyword) {
            $tps->orWhereRaw('LOWER(nama) LIKE ?', ['%' . strtolower($this->keyword) . '%']);
        }

        $tps = $tps->paginate($this->perPage);
        
        $paslon = Calon::with('suaraCalon')
            ->wherePosisi('GUBERNUR')
            ->whereHas('provinsi', function (Builder $builder) use ($userWilayah) {
                $builder->whereHas('kabupaten', fn (Builder $builder) => $builder->whereNama($userWilayah));
            })
            ->get();

        return view('livewire.input-suara-pilgub', compact('tps', 'paslon'));
    }
}
