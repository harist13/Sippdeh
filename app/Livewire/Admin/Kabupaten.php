<?php

namespace App\Livewire\Admin;

use App\Models\Provinsi;
use App\Models\Kabupaten as Model;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Features\SupportPagination\WithoutUrlPagination;

class Kabupaten extends Component
{
    use WithPagination, WithoutUrlPagination;
    
    public int $perPage = 10;

    public string $keyword = '';

    public function render(): View
    {
        $provinsi = Provinsi::all();
        $kabupaten = $this->getKabupaten();

        return view('livewire.admin.kabupaten', compact('provinsi', 'kabupaten'));
    }

    private function getKabupaten(): LengthAwarePaginator
    {
        if ($this->keyword) {
            $kabupatenQuery = Model::whereLike('nama', "%{$this->keyword}%");
        } else {
            $kabupatenQuery = Model::query();
        }

        return $kabupatenQuery->paginate($this->perPage);
    }
}
