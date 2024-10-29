<?php

namespace App\Livewire;

use App\Models\Calon;
use App\Models\TPS;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class InputSuaraPilgub extends Component
{
    use WithPagination, WithoutUrlPagination;

    public function render()
    {
        $userWilayah = session('user_wilayah');

        $tps = TPS::with(['suara', 'suaraCalon'])
            ->whereHas('kelurahan', function (Builder $builder) use ($userWilayah) {
                $builder->whereHas('kecamatan', function(Builder $builder) use ($userWilayah) {
                    $builder->whereHas('kabupaten', fn (Builder $builder) => $builder->whereNama($userWilayah));
                });
            })
            ->paginate(10);
        
        $paslon = Calon::with('suaraCalon')
            ->wherePosisi('GUBERNUR')
            ->whereHas('provinsi', function (Builder $builder) use ($userWilayah) {
                $builder->whereHas('kabupaten', fn (Builder $builder) => $builder->whereNama($userWilayah));
            })
            ->get();

        return view('livewire.input-suara-pilgub', compact('tps', 'paslon'));
    }
}
