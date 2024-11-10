<?php

namespace App\Livewire;

use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\Provinsi;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;

class FilterInputSuaraPilgub extends Component
{
    public $selectedProvinsi = [];
    public $selectedKabupaten = [];
    public $selectedKecamatan = [];
    public $selectedKelurahan = [];
    public $includedColumns = [];
    public $partisipasi = [];

    public function mount($selectedProvinsi, $selectedKabupaten, $selectedKecamatan, $selectedKelurahan, $includedColumns, $partisipasi)
    {
        $this->selectedProvinsi = $selectedProvinsi;
        $this->selectedKabupaten = $selectedKabupaten;
        $this->selectedKecamatan = $selectedKecamatan;
        $this->selectedKelurahan = $selectedKelurahan;
        $this->includedColumns = $includedColumns;
        $this->partisipasi = $partisipasi;
    }

    public function render()
    {
        $provinsi = $this->getProvinsiOptions();
        $kabupaten = $this->getKabupatenOptions();
        $kecamatan = $this->getKecamatanOptions();
        $kelurahan = $this->getKelurahanOptions();
        return view('livewire.filter-input-suara-pilgub', compact('provinsi', 'kabupaten', 'kecamatan', 'kelurahan'));
    }

    private function getProvinsiOptions()
    {
        return Provinsi::all()
            ->map(fn (Provinsi $provinsi) => ['id' => $provinsi->id, 'name' => $provinsi->nama])
            ->toArray();
    }

    private function getKabupatenOptions()
    {
        if (empty($this->selectedProvinsi)) {
            return [];
        }

        return Kabupaten::query()
            ->whereHas('provinsi', fn (Builder $builder) => $builder->whereIn('id', $this->selectedProvinsi))
            ->get()
            ->map(fn (Kabupaten $kabupaten) => ['id' => $kabupaten->id, 'name' => $kabupaten->nama])
            ->toArray();
    }

    private function getKecamatanOptions()
    {
        if (empty($this->selectedKabupaten)) {
            return [];
        }

        return Kecamatan::query()
            ->whereHas('kabupaten', fn (Builder $builder) => $builder->whereIn('id', $this->selectedKabupaten))
            ->get()
            ->map(fn (Kecamatan $kecamatan) => ['id' => $kecamatan->id, 'name' => $kecamatan->nama])
            ->toArray();
    }

    private function getKelurahanOptions()
    {
        if (empty($this->selectedKecamatan)) {
            return [];
        }

        return Kelurahan::query()
            ->whereHas('kecamatan', fn (Builder $builder) => $builder->whereIn('id', $this->selectedKecamatan))
            ->get()
            ->map(fn (Kelurahan $kelurahan) => ['id' => $kelurahan->id, 'name' => $kelurahan->nama])
            ->toArray();
    }

    public function updatedSelectedProvinsi()
    {
        $this->selectedKabupaten = [];
        $this->selectedKecamatan = [];
        $this->selectedKelurahan = [];
    }

    public function updatedSelectedKabupaten()
    {
        $this->selectedKecamatan = [];
        $this->selectedKelurahan = [];
    }

    public function updatedSelectedKecamatan()
    {
        $this->selectedKelurahan = [];
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