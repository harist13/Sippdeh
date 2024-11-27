<?php

namespace App\Livewire\superadmin;

use App\Models\Kabupaten;
use App\Models\Kecamatan as Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class Kecamatan extends Component
{
    use WithPagination, WithoutUrlPagination;
    
    public int $perPage = 10;

    public string $keyword = '';
    
    public function render()
    {
        $kabupaten = Kabupaten::all();
        $kecamatan = $this->getKecamatan();
        return view('superadmin.kecamatan.livewire', compact('kabupaten', 'kecamatan'));
    }

    private function getKecamatan(): LengthAwarePaginator
    {
        $kecamatanQuery = Model::query();

        if ($this->keyword) {
            $keyword = '%' . strtolower($this->keyword) . '%';
            $kecamatanQuery
                ->whereRaw('LOWER(nama) LIKE ?', [$keyword])
                ->orWhereHas('kabupaten', fn (Builder $builder) => $builder->whereRaw('LOWER(nama) LIKE ?', [$keyword]));
        }

        return $kecamatanQuery->paginate($this->perPage);
    }
}
