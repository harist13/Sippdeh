<?php

namespace App\Traits;

use Livewire\Attributes\On;

trait InputSuara {
	public int $perPage = 10;

    public string $keyword = '';
    
    public array $partisipasi = ['HIJAU', 'MERAH'];

    #[On('reset-filter')]
	public function applyFilter() {
        // TODO: Nothing
    }

    #[On('apply-filter')]
    public function resetFilter()
    {
        $this->includedColumns = ['KECAMATAN', 'KELURAHAN', 'TPS', 'CALON'];
        $this->partisipasi = ['HIJAU', 'MERAH'];
    }
}