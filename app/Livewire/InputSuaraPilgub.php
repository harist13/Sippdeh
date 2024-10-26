<?php

namespace App\Livewire;

use App\Models\TPS;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class InputSuaraPilgub extends Component
{
    use WithPagination, WithoutUrlPagination;

    public function render()
    {
        $tps = TPS::paginate(10);
        return view('livewire.input-suara-pilgub', compact('tps'));
    }
}
