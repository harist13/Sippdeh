<?php

namespace App\Livewire\Operator\Resume\Pilwali;

use App\Models\Kecamatan;
use App\Models\Kelurahan;
use Livewire\Component;
use Illuminate\Database\Eloquent\Builder;

class FilterSuaraPilwali extends Component
{
    public $selectedKecamatan = [];
    public $selectedKelurahan = [];
    public $includedColumns = [];
    public $partisipasi = [];

    public function mount($selectedKecamatan, $selectedKelurahan, $includedColumns, $partisipasi)
    {
        $this->selectedKecamatan = $selectedKecamatan;
        $this->selectedKelurahan = $selectedKelurahan;
        $this->includedColumns = $includedColumns;
        $this->partisipasi = $partisipasi;
    }

    public function render()
    {
        $kecamatan = $this->getKecamatanOptions();
        $kelurahan = $this->getKelurahanOptions();
        return view('livewire.operator.resume.pilwali.filter-suara-pilwali', compact('kecamatan', 'kelurahan'));
    }

    private function getKecamatanOptions()
    {
        return Kecamatan::query()
            ->whereHas('kabupaten', fn (Builder $builder) => $builder->whereNama(session('user_wilayah')))
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
        if (!empty($this->selectedKecamatan)) {
            $this->includedColumns = ['KECAMATAN', 'CALON'];
        }

        if (!empty($this->selectedKelurahan)) {
            $this->includedColumns = ['KELURAHAN', 'CALON'];
        }
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
        $this->selectedKecamatan = [];
        $this->selectedKelurahan = [];
        $this->includedColumns = ['KECAMATAN', 'CALON'];
        $this->partisipasi = ['HIJAU', 'KUNING', 'MERAH'];

        $this->dispatch('reset-filter');
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

        $event->to(SuaraPilwali::class);
    }
}
