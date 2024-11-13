<?php

namespace App\Livewire\Operator\InputSuara\Pilgub;

use App\Models\Kecamatan;
use App\Models\Kelurahan;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;

class FilterInputSuaraPilgub extends Component
{
    public $selectedKecamatan = [];
    public $selectedKelurahan = [];
    public $includedColumns = [];
    public $partisipasi = [];

    public function mount($selectedKecamatan, $selectedKelurahan, $includedColumns, $partisipasi): void
    {
        $this->selectedKecamatan = $selectedKecamatan;
        $this->selectedKelurahan = $selectedKelurahan;
        $this->includedColumns = $includedColumns;
        $this->partisipasi = $partisipasi;
    }

    public function render(): View
    {
        $kecamatan = $this->getKecamatanOptions();
        $kelurahan = $this->getKelurahanOptions();
        return view('operator.input-suara.pilgub.filter-form', compact('kecamatan', 'kelurahan'));
    }

    private function getKecamatanOptions(): array
    {
        return Kecamatan::query()
            ->whereHas('kabupaten', fn (Builder $builder) => $builder->whereNama(session('user_wilayah')))
            ->get()
            ->map(fn (Kecamatan $kecamatan) => ['id' => $kecamatan->id, 'name' => $kecamatan->nama])
            ->toArray();
    }

    private function getKelurahanOptions(): array
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

    public function updatedSelectedKecamatan(): void
    {
        $this->selectedKelurahan = [];
    }

    public function resetFilter(): void
    {
        $this->dispatch('reset-filter')->to(InputSuaraPilgub::class);
    }

    public function applyFilter(): void
    {
        $event = $this->dispatch(
            'apply-filter',
            selectedKecamatan: $this->selectedKecamatan,
            selectedKelurahan: $this->selectedKelurahan,
            includedColumns: $this->includedColumns,
            partisipasi: $this->partisipasi
        );

        $event->to(InputSuaraPilgub::class);
    }
}