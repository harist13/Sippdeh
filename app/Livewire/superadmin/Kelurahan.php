<?php

namespace App\Livewire\superadmin;

use App\Models\Kecamatan;
use App\Models\Kelurahan as Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class Kelurahan extends Component
{
    use WithPagination, WithoutUrlPagination;
    
    public int $perPage = 10;

    public string $keyword = '';
    
    public function render()
    {
        $kecamatan = Kecamatan::all();
        $kelurahan = $this->getKelurahan();
        return view('superadmin.kelurahan.livewire', compact('kecamatan', 'kelurahan'));
    }

    private function getKelurahan(): LengthAwarePaginator
    {
        $kelurahanQuery = Model::query();

        if ($this->keyword) {
            $keyword = '%' . strtolower($this->keyword) . '%';
            $kelurahanQuery
                ->whereRaw('LOWER(nama) LIKE ?', [$keyword])
                ->orWhereHas('kecamatan', fn (Builder $builder) => $builder->whereRaw('LOWER(nama) LIKE ?', [$keyword]));
        }

        return $kelurahanQuery->paginate($this->perPage);
    }
}
