<?php

namespace App\Traits;

trait InputSuara {
	public int $perPage = 10;

    public string $keyword = '';
    
    public array $partisipasi = ['HIJAU', 'KUNING', 'MERAH'];

	public function applyFilter() {
        // TODO: Nothing
    }

    public function resetFilter()
    {
        $this->includedColumns = ['KECAMATAN', 'KELURAHAN', 'TPS', 'CALON'];
        $this->partisipasi = ['HIJAU', 'KUNING', 'MERAH'];
    }
}