<?php

namespace App\Livewire\Admin\Resume\Pilwali\PerWilayah;

use App\Models\Kecamatan;
use App\Models\Kelurahan;
use Livewire\Component;
use Illuminate\Database\Eloquent\Builder;

class FilterResumeSuaraPilwaliPerWilayah extends Component
{
    public $selectedKecamatan = [];
    public $selectedKelurahan = [];

    public $availableColumns = [];
    public $includedColumns = [];

    public $partisipasi = [];

    public function mount($selectedKecamatan, $selectedKelurahan, $includedColumns, $partisipasi)
    {
        $this->selectedKecamatan = $selectedKecamatan;
        $this->selectedKelurahan = $selectedKelurahan;

        $this->availableColumns = $includedColumns;
        $this->includedColumns = $includedColumns;

        $this->partisipasi = $partisipasi;
    }

    public function render()
    {
        $kecamatan = $this->getKecamatanOptions();
        $kelurahan = $this->getKelurahanOptions();
        return view('admin.resume.pilwali.per-wilayah.filter-form', compact('kecamatan', 'kelurahan'));
    }

    private function getKecamatanOptions()
    {
        return Kecamatan::query()
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

    private function resetWilayahColumns()
    {
        $this->availableColumns = ['KABUPATEN/KOTA', 'KECAMATAN', 'KELURAHAN', 'CALON'];
        $this->includedColumns = ['KABUPATEN/KOTA', 'KECAMATAN', 'KELURAHAN', 'CALON'];
    }

    public function updatedSelectedKecamatan()
    {
        $this->selectedKelurahan = [];
        $this->resetWilayahColumns();
    }

    public function updatedSelectedKelurahan()
    {
        $this->resetWilayahColumns();
    }

    public function resetFilter()
    {
        $this->dispatch('reset-filter')->to(ResumeSuaraPilwaliPerWilayah::class);
    }

    public function applyFilter()
    {
        $event = $this->dispatch(
            'apply-filter',
            selectedKecamatan: $this->selectedKecamatan,
            selectedKelurahan: $this->selectedKelurahan,
            includedColumns: $this->includedColumns,
            partisipasi: $this->partisipasi
        );

        $event->to(ResumeSuaraPilwaliPerWilayah::class);
    }
}