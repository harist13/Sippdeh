<?php

namespace App\Livewire\Operator\Resume\Pilwali\PerTps;

use App\Models\Kecamatan;
use App\Models\Kelurahan;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;

class FilterResumeSuaraPilwaliPerTps extends Component
{
    public $selectedKecamatan = [];
    public $selectedKelurahan = [];

    public $availableColumns = [];
    public $includedColumns = [];

    public $partisipasi = [];

    public function mount($selectedKecamatan, $selectedKelurahan, $includedColumns, $partisipasi): void
    {
        $this->selectedKecamatan = $selectedKecamatan;
        $this->selectedKelurahan = $selectedKelurahan;

        $this->availableColumns = $includedColumns;
        $this->includedColumns = $includedColumns;
        
        $this->partisipasi = $partisipasi;
    }

    public function render(): View
    {
        $kecamatan = $this->getKecamatanOptions();
        $kelurahan = $this->getKelurahanOptions();
        return view('operator.resume.pilwali.per-tps.filter-form', compact('kecamatan', 'kelurahan'));
    }

    private function getKecamatanOptions(): array
    {
        return Kecamatan::query()
            ->whereKabupatenId(session('operator_kabupaten_id'))
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
        $this->selectedKecamatan = [];
        $this->selectedKelurahan = [];
        $this->includedColumns = ['KABUPATEN/KOTA', 'KECAMATAN', 'KELURAHAN', 'TPS', 'CALON'];
        $this->partisipasi = ['HIJAU', 'KUNING', 'MERAH'];

        $this->dispatch('reset-filter')->to(ResumeSuaraPilwaliPerTps::class);
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

        $event->to(ResumeSuaraPilwaliPerTps::class);
    }
}