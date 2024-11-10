<?php

namespace App\Livewire;

use App\Models\Kabupaten;
use App\Models\Provinsi;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;

class FilterInputSuaraPilgub extends Component
{
    public $includedColumns = [];
    public $selectedProvinsi = [];
    public $selectedKabupaten = [];
    public $partisipasi = [];

    public function mount($includedColumns, $selectedProvinsi, $selectedKabupaten, $partisipasi)
    {
        $this->includedColumns = $includedColumns;
        $this->selectedProvinsi = $selectedProvinsi;
        $this->selectedKabupaten = $selectedKabupaten;
        $this->partisipasi = $partisipasi;
    }

    public function render()
    {
        $provinsi = $this->getProvinsiOptions();
        $kabupaten = $this->getKabupatenOptions();
        return view('livewire.filter-input-suara-pilgub', compact('provinsi', 'kabupaten'));
    }

    private function getProvinsiOptions()
    {
        return Provinsi::all()
            ->map(fn ($provinsi) => ['id' => $provinsi->id, 'name' => $provinsi->nama])
            ->toArray();
    }

    private function getKabupatenOptions()
    {
        if (empty($this->selectedProvinsi)) {
            return [];
        }

        return Kabupaten::query()
            ->whereHas('provinsi', fn (Builder $builder) =>
                $builder->whereIn('id', $this->selectedProvinsi)
            )
            ->get()
            ->map(fn ($kabupaten) => ['id' => $kabupaten->id, 'name' => $kabupaten->nama])
            ->toArray();
    }

    public function resetFilter()
    {
        $this->dispatch('reset-filter');
    }

    public function applyFilter()
    {
        $event = $this->dispatch(
            'apply-filter',
            includedColumns: $this->includedColumns,
            selectedProvinsi: $this->selectedProvinsi,
            selectedKabupaten: $this->selectedKabupaten,
            partisipasi: $this->partisipasi
        );

        $event->to(InputSuaraPilgub::class);
    }
}