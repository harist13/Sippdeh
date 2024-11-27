<?php

namespace App\Livewire\Superadmin\Resume\Pilbup\PerTps;

use App\Models\Kecamatan;
use App\Models\Kelurahan;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;

class FilterResumeSuaraPilbupPerTps extends Component
{
    public $selectedKecamatan = [];
    public $selectedKelurahan = [];
    public $availableColumns = [];
    public $includedColumns = [];
    public $partisipasi = [];
    public ?int $kabupatenId = null;

    public function mount($kabupatenId = null, $selectedKecamatan = [], $selectedKelurahan = [], $includedColumns = [], $partisipasi = []): void
    {
        $this->kabupatenId = $kabupatenId;
        $this->selectedKecamatan = $selectedKecamatan;
        $this->selectedKelurahan = $selectedKelurahan;
        $this->availableColumns = $includedColumns ?: ['KABUPATEN', 'KECAMATAN', 'KELURAHAN', 'TPS', 'CALON'];
        $this->includedColumns = $includedColumns ?: ['KABUPATEN', 'KECAMATAN', 'KELURAHAN', 'TPS', 'CALON'];
        $this->partisipasi = $partisipasi ?: ['HIJAU', 'KUNING', 'MERAH'];
    }

    public function render(): View
    {
        $kecamatan = $this->getKecamatanOptions();
        $kelurahan = $this->getKelurahanOptions();
        return view('Superadmin.resume.pilbup.per-tps.filter-form', compact('kecamatan', 'kelurahan'));
    }

    private function getKecamatanOptions(): array
    {
        $query = Kecamatan::query();
        
        if ($this->kabupatenId) {
            $query->where('kabupaten_id', $this->kabupatenId);
        }

        return $query->get()
            ->map(fn (Kecamatan $kecamatan) => [
                'id' => $kecamatan->id, 
                'name' => $kecamatan->nama
            ])
            ->toArray();
    }

    private function getKelurahanOptions(): array
    {
        if (empty($this->selectedKecamatan)) {
            return [];
        }

        $query = Kelurahan::query()
            ->whereHas('kecamatan', function(Builder $builder) {
                $builder->whereIn('id', $this->selectedKecamatan);
                if ($this->kabupatenId) {
                    $builder->where('kabupaten_id', $this->kabupatenId);
                }
            });

        return $query->get()
            ->map(fn (Kelurahan $kelurahan) => [
                'id' => $kelurahan->id, 
                'name' => $kelurahan->nama
            ])
            ->toArray();
    }

    public function updatedSelectedKecamatan(): void
    {
        $this->selectedKelurahan = [];
    }

    public function resetFilter(): void
    {
        $kecamatanQuery = Kecamatan::query();
        if ($this->kabupatenId) {
            $kecamatanQuery->where('kabupaten_id', $this->kabupatenId);
        }

        $this->selectedKecamatan = $kecamatanQuery->pluck('id')->toArray();
        $this->selectedKelurahan = [];
        $this->includedColumns = ['KABUPATEN', 'KECAMATAN', 'KELURAHAN', 'TPS', 'CALON'];
        $this->partisipasi = ['HIJAU', 'KUNING', 'MERAH'];

        $this->dispatch('reset-filter')->to(ResumeSuaraPilbupPerTps::class);
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

        $event->to(ResumeSuaraPilbupPerTps::class);
    }
}