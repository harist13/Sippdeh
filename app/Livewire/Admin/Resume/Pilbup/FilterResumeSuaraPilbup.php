<?php

namespace App\Livewire\Admin\Resume\Pilbup;

use App\Models\Kecamatan;
use App\Models\Kelurahan;
use Livewire\Component;
use Illuminate\Database\Eloquent\Builder;

class FilterResumeSuaraPilbup extends Component
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
        return view('admin.resume.pilwali.filter-form', compact('kecamatan', 'kelurahan'));
    }

    private function getKecamatanOptions()
    {
        // Get all kecamatan without filtering by user_wilayah
        return Kecamatan::query()
            ->orderBy('nama')  // Optional: Add ordering for better UX
            ->get()
            ->map(fn (Kecamatan $kecamatan) => [
                'id' => $kecamatan->id, 
                'name' => $kecamatan->nama,
                // Optionally include kabupaten name for clarity
                'name_with_kabupaten' => "{$kecamatan->nama} ({$kecamatan->kabupaten->nama})"
            ])
            ->toArray();
    }

    private function getKelurahanOptions()
    {
        if (empty($this->selectedKecamatan)) {
            return [];
        }

        return Kelurahan::query()
            ->whereIn('kecamatan_id', $this->selectedKecamatan)
            ->orderBy('nama')  // Optional: Add ordering for better UX
            ->get()
            ->map(fn (Kelurahan $kelurahan) => [
                'id' => $kelurahan->id, 
                'name' => $kelurahan->nama,
                // Optionally include kecamatan name for clarity
                'name_with_kecamatan' => "{$kelurahan->nama} ({$kelurahan->kecamatan->nama})"
            ])
            ->toArray();
    }

    private function syncAvailableColumnsByWilayah()
    {
        if (!empty($this->selectedKecamatan)) {
            $this->availableColumns = ['KABUPATEN', 'KECAMATAN', 'CALON'];
            $this->includedColumns = ['KABUPATEN', 'KECAMATAN', 'CALON'];
        }

        if (!empty($this->selectedKelurahan)) {
            $this->availableColumns = ['KABUPATEN', 'KECAMATAN', 'KELURAHAN', 'CALON'];
            $this->includedColumns = ['KABUPATEN', 'KECAMATAN', 'KELURAHAN', 'CALON'];
        }
    }

    public function updatedSelectedKecamatan()
    {
        $this->selectedKelurahan = [];
        $this->syncAvailableColumnsByWilayah();
    }

    public function updatedSelectedKelurahan()
    {
        $this->syncAvailableColumnsByWilayah();
    }

    public function resetFilter()
    {
        $this->dispatch('reset-filter')->to(ResumeSuaraPilbup::class);
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

        $event->to(ResumeSuaraPilbup::class);
    }
}