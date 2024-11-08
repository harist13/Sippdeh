<?php

namespace App\Livewire\Admin;

use App\Models\Kabupaten;
use App\Models\Provinsi as Model;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Features\SupportPagination\WithoutUrlPagination;

class Provinsi extends Component
{
    use WithPagination, WithoutUrlPagination;
    
    public int $perPage = 10;

    public string $keyword = '';

    public ?int $kabupatenId = null;

    public function render(): View
    {
        $kabupaten = Kabupaten::all();
        $provinsi = $this->getProvinsi();

        return view('livewire.admin.provinsi', compact('kabupaten', 'provinsi'));
    }

    private function getProvinsi(): LengthAwarePaginator
    {
        if ($this->keyword) {
            $provinsiQuery = Model::whereLike('nama', "%{$this->keyword}%");
        } else {
            $provinsiQuery = Model::query();
        }

        if ($this->kabupatenId) {
            $provinsiQuery->whereHas('kabupaten', function($builder) {
                $builder->where('id', $this->kabupatenId);
            });
        }

        return $provinsiQuery->paginate($this->perPage);
    }
}
