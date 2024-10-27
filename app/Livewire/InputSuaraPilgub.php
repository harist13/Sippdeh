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

        $tps = TPS::paginate(10);
        $paslon = Calon::whereHas('kabupaten', fn (Builder $builder) => $builder->whereNama($userWilayah))->get();

        return view('livewire.input-suara-pilgub', compact('tps', 'paslon'));
    }
}
