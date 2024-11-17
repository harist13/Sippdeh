<?php

namespace App\Livewire\Admin\Resume\Pilgub;

use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use Livewire\Component;
use Illuminate\Database\Eloquent\Builder;

class FilterResumeSuaraPilgub extends Component
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
        return view('admin.resume.pilgub.filter-form', compact('kabupaten', 'kecamatan', 'kelurahan'));
    }

    private function getKabupatenOptions()
    {
        // Get all kabupaten without filtering by user_wilayah
        return Kabupaten::query()
            ->orderBy('nama')
            ->get()
            ->map(fn (Kabupaten $kabupaten) => [
                'id' => $kabupaten->id, 
                'name' => $kabupaten->nama,
                // Include province name for better context
                'name_with_province' => "{$kabupaten->nama} ({$kabupaten->provinsi->nama})"
            ])
            ->toArray();
    }

    private function getKecamatanOptions()
    {
        if (empty($this->selectedKabupaten)) {
            return [];
        }

        return Kecamatan::query()
            ->whereIn('kabupaten_id', $this->selectedKabupaten)
            ->orderBy('nama')
            ->get()
            ->map(fn (Kecamatan $kecamatan) => [
                'id' => $kecamatan->id, 
                'name' => $kecamatan->nama,
                // Include kabupaten name for better context
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
            ->orderBy('nama')
            ->get()
            ->map(fn (Kelurahan $kelurahan) => [
                'id' => $kelurahan->id, 
                'name' => $kelurahan->nama,
                // Include kecamatan name for better context
                'name_with_kecamatan' => "{$kelurahan->nama} ({$kelurahan->kecamatan->nama})"
            ])
            ->toArray();
    }

    private function syncAvailableColumnsByWilayah()
    {
        if (!empty($this->selectedKabupaten)) {
            $this->availableColumns = ['KABUPATEN', 'CALON'];
            $this->includedColumns = ['KABUPATEN', 'CALON'];
        }

        if (!empty($this->selectedKecamatan)) {
            $this->availableColumns = ['KABUPATEN', 'KECAMATAN', 'CALON'];
            $this->includedColumns = ['KABUPATEN', 'KECAMATAN', 'CALON'];
        }

        if (!empty($this->selectedKelurahan)) {
            $this->availableColumns = ['KABUPATEN', 'KECAMATAN', 'KELURAHAN', 'CALON'];
            $this->includedColumns = ['KABUPATEN', 'KECAMATAN', 'KELURAHAN', 'CALON'];
        }
    }

    public function updatedSelectedKabupaten()
    {
        $this->selectedKecamatan = [];
        $this->selectedKelurahan = [];

        $this->syncAvailableColumnsByWilayah();
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
        $this->dispatch('reset-filter')->to(ResumeSuaraPilgub::class);
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

        $event->to(ResumeSuaraPilgub::class);
    }
}