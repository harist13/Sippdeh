<?php

namespace App\Livewire\Superadmin;

use App\Models\Kabupaten;
use App\Models\Provinsi as Model;
use App\Models\Scopes\ProvinsiScope;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Features\SupportPagination\WithoutUrlPagination;

#[ScopedBy([ProvinsiScope::class])]
class Provinsi extends Component
{
    use WithPagination, WithoutUrlPagination;
    
    public int $perPage = 10;

    public string $keyword = '';

    public function render(): View
    {
        $provinsi = $this->getProvinsi();
        return view('Superadmin.provinsi.livewire', compact('provinsi'));
    }

    private function getProvinsi(): LengthAwarePaginator
    {
        if ($this->keyword) {
            $provinsiQuery = Model::whereRaw('LOWER(nama) LIKE ?', ['%' . strtolower($this->keyword) . '%']);
        } else {
            $provinsiQuery = Model::query();
        }

        return $provinsiQuery->paginate($this->perPage);
    }
}
