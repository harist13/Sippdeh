<?php

namespace App\Livewire\Admin\Resume\Pilgub\PerWilayah;

use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use Livewire\Component;
use Illuminate\Database\Eloquent\Builder;

class FilterResumeSuaraPilgubPerWilayah extends Component
{
    public $selectedKabupaten = [];
    public $selectedKecamatan = [];
    public $selectedKelurahan = [];

    public $availableColumns = [];
    public $includedColumns = [];

    public $partisipasi = [];

    public function mount($selectedKabupaten, $selectedKecamatan, $selectedKelurahan, $includedColumns, $partisipasi)
    {
        $this->selectedKabupaten = $selectedKabupaten;
        $this->selectedKecamatan = $selectedKecamatan;
        $this->selectedKelurahan = $selectedKelurahan;

        $this->availableColumns = $includedColumns;
        $this->includedColumns = $includedColumns;

        $this->partisipasi = $partisipasi;
    }

    public function render()
    {
        $kabupaten = $this->getKabupatenOptions();
        $kecamatan = $this->getKecamatanOptions();
        $kelurahan = $this->getKelurahanOptions();
        return view('admin.resume.pilgub.per-wilayah.filter-form', compact('kabupaten', 'kecamatan', 'kelurahan'));
    }

    private function getKabupatenOptions()
    {
        return Kabupaten::query()
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

    private function resetAvailableColumns()
    {
        $this->availableColumns = ['KABUPATEN/KOTA', 'KECAMATAN', 'KELURAHAN', 'CALON'];
        $this->includedColumns = ['KABUPATEN/KOTA', 'KECAMATAN', 'KELURAHAN', 'CALON'];
    }

    public function updatedSelectedKabupaten()
    {
        $this->selectedKecamatan = [];
        $this->selectedKelurahan = [];
        $this->resetAvailableColumns();
    }

    public function updatedSelectedKecamatan()
    {
        $this->selectedKelurahan = [];
        $this->resetAvailableColumns();
    }

    public function updatedSelectedKelurahan()
    {
        $this->resetAvailableColumns();
    }

    public function resetFilter()
    {
        $this->dispatch('reset-filter')->to(ResumeSuaraPilgubPerWilayah::class);
    }

    public function applyFilter()
    {
        $event = $this->dispatch(
            'apply-filter',
            selectedKabupaten: $this->selectedKabupaten,
            selectedKecamatan: $this->selectedKecamatan,
            selectedKelurahan: $this->selectedKelurahan,
            includedColumns: $this->includedColumns,
            partisipasi: $this->partisipasi
        );

        $event->to(ResumeSuaraPilgubPerWilayah::class);
    }
}