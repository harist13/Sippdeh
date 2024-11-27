<?php

namespace App\Livewire\Superadmin;

use App\Models\Kabupaten;
use App\Models\Kelurahan;
use App\Models\TPS as Model;
use Livewire\Features\SupportPagination\WithoutUrlPagination;
use Livewire\WithPagination;
use Livewire\Component;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class TPS extends Component
{
    use WithPagination, WithoutUrlPagination;
    
    public int $perPage = 10;

    public string $keyword = '';
    
    public function render()
    {
        $kabupaten = Kabupaten::all();
        $kelurahan = Kelurahan::all();
        $tps = $this->getTps();
        return view('superadmin.tps.livewire', compact('kabupaten', 'kelurahan', 'tps'));
    }

    private function getTps(): LengthAwarePaginator
    {
        $tpsQuery = Model::query();

        if ($this->keyword) {
            $keyword = '%' . strtolower($this->keyword) . '%';
            $tpsQuery
                ->whereRaw('LOWER(nama) LIKE ?', [$keyword])
                ->orWhereHas('kelurahan', fn (Builder $builder) => $builder->whereRaw('LOWER(nama) LIKE ?', [$keyword]));
        }

        return $tpsQuery->paginate($this->perPage);
    }
}
