<?php

namespace App\Livewire\Admin;

use App\Models\Provinsi;
use App\Models\Kabupaten;
use App\Models\Calon as Model;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Features\SupportPagination\WithoutUrlPagination;
use Illuminate\Support\Facades\Storage;

class Calon extends Component
{
    use WithPagination, WithoutUrlPagination;
    
    public int $perPage = 10;
    public string $keyword = '';
    public ?string $selectedKabupaten = null;

    private string $diskName = 'foto_calon_lokal';

    public function mount(): void
    {
        $this->selectedKabupaten = request('kabupaten');
    }

    public function render(): View
    {
        $provinsi = Provinsi::all();
        $kabupaten = Kabupaten::all();
        $calon = $this->getCalon();
        $disk = Storage::disk($this->diskName);

        return view('admin.calon.livewire', compact('provinsi', 'kabupaten', 'calon', 'disk'));
    }

    private function getCalon()
    {
        $query = Model::query();

        if ($this->keyword) {
            $query->where(function($q) {
                $q->whereLike('nama', "%{$this->keyword}%")
                  ->orWhereLike('nama_wakil', "%{$this->keyword}%");
            });
        }

        if ($this->selectedKabupaten) {
            $query->where('kabupaten_id', $this->selectedKabupaten);
        }

        return $query->orderByDesc('id')->paginate($this->perPage);
    }

    public function updatedSelectedKabupaten($value)
    {
        $this->resetPage();
    }

    public function updatedKeyword()
    {
        $this->resetPage();
    }
}