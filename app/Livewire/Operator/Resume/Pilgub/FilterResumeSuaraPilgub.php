<?php

namespace App\Livewire\Operator\Resume\Pilgub;

use App\Models\Provinsi;
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
    public $includedColumns = [];
    public $partisipasi = [];

    public function mount($selectedKabupaten, $selectedKecamatan, $selectedKelurahan, $includedColumns, $partisipasi)
    {
        $this->selectedKabupaten = $selectedKabupaten;
        $this->selectedKecamatan = $selectedKecamatan;
        $this->selectedKelurahan = $selectedKelurahan;
        $this->includedColumns = $includedColumns;
        $this->partisipasi = $partisipasi;
    }

    public function render()
    {
        $kabupaten = $this->getKabupatenOptions();
        $kecamatan = $this->getKecamatanOptions();
        $kelurahan = $this->getKelurahanOptions();
        return view('operator.resume.pilgub.filter-modal', compact('kabupaten', 'kecamatan', 'kelurahan'));
    }

    private function getKabupatenOptions()
    {
        return Kabupaten::query()
            ->whereHas('provinsi', function (Builder $builder) {
                $builder->whereHas('kabupaten', fn (Builder $builder) => $builder->whereNama(session('user_wilayah')));
            })
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

    private function syncIncludedColumnsByWilayah()
    {
        if (!empty($this->selectedKabupaten)) {
            $this->includedColumns = ['KABUPATEN', 'CALON'];
        }

        if (!empty($this->selectedKecamatan)) {
            $this->includedColumns = ['KECAMATAN', 'CALON'];
        }

        if (!empty($this->selectedKelurahan)) {
            $this->includedColumns = ['KELURAHAN', 'CALON'];
        }
    }

    public function updatedSelectedKabupaten()
    {
        $this->selectedKecamatan = [];
        $this->selectedKelurahan = [];

        $this->syncIncludedColumnsByWilayah();
    }

    public function updatedSelectedKecamatan()
    {
        $this->selectedKelurahan = [];

        $this->syncIncludedColumnsByWilayah();
    }

    public function updatedSelectedKelurahan()
    {
        $this->syncIncludedColumnsByWilayah();
    }

    public function resetFilter()
    {
        $this->dispatch('reset-filter');
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