<?php

namespace App\Livewire\Admin;

use App\Models\Kabupaten;
use App\Models\Kecamatan as Model;
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
        return view('admin.kecamatan.livewire', compact('kabupaten', 'kecamatan'));
    }

    private function getKecamatan(): LengthAwarePaginator
    {
        if ($this->keyword) {
            $kecamatanQuery = Model::whereRaw('LOWER(nama) LIKE ?', ['%' . strtolower($this->keyword) . '%']);
        } else {
            $kecamatanQuery = Model::query();
        }

        return $kecamatanQuery->paginate($this->perPage);
    }
}
