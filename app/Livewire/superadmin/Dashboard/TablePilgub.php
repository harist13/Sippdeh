<?php

namespace App\Livewire\superadmin\Dashboard;

use App\Models\Kabupaten;
use App\Models\Calon;
use App\Models\SuaraCalon;
use App\Models\ResumeSuaraPilgubKabupaten;
use Livewire\Component;
use Livewire\Attributes\Lazy;

#[Lazy]
class TablePilgub extends Component
{
    public $tableData;

    public function placeholder()
    {
        return <<<'HTML'
            <div class="flex justify-center my-20">
                <svg class="animate-spin h-8 w-8 text-[#3560A0]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>
        HTML;
    }

    public function mount()
    {
        $this->tableData = $this->getTableData();
    }

    public function render()
    {
        return view('superadmin.dashboard.table-pilgub');
    }

    private function getTableData(): array
    {
        $kabupatens = Kabupaten::orderBy('nama', 'asc')->get();
        $gubernurCalon = Calon::where('posisi', 'GUBERNUR')->get();
        
        if ($gubernurCalon->count() < 2) {
            return [];
        }
        
        $paslon1 = $gubernurCalon[0];
        $paslon2 = $gubernurCalon[1];
        
        // Tambahkan informasi nama paslon ke array yang akan dikembalikan
        $tableInfo = [
            'paslon1_nama' => $paslon1->nama,
            'paslon1_wakil' => $paslon1->nama_wakil,
            'paslon2_nama' => $paslon2->nama,
            'paslon2_wakil' => $paslon2->nama_wakil,
            'data' => []
        ];
        
        $unsortedData = [];
        
        foreach ($kabupatens as $index => $kabupaten) {
            // Get DPT
            $resumeData = ResumeSuaraPilgubKabupaten::where('id', $kabupaten->id)->first();

            // Get votes for Paslon 1
            $suaraPaslon1 = SuaraCalon::whereHas('tps.kelurahan.kecamatan.kabupaten', function($query) use ($kabupaten) {
                $query->where('id', $kabupaten->id);
            })->where('calon_id', $paslon1->id)
            ->sum('suara');
            
            // Get votes for Paslon 2
            $suaraPaslon2 = SuaraCalon::whereHas('tps.kelurahan.kecamatan.kabupaten', function($query) use ($kabupaten) {
                $query->where('id', $kabupaten->id);
            })->where('calon_id', $paslon2->id)
            ->sum('suara');

            // Calculate participation
            $dpt = max(0, $resumeData->dpt ?? 0);
            $suaraMasuk = max(0, $resumeData->suara_masuk ?? 0);
            $partisipasi = $resumeData->partisipasi;

            $unsortedData[] = [
                'kabupaten' => $kabupaten->nama,
                'dpt' => $dpt,
                'paslon1' => $suaraPaslon1,
                'paslon2' => $suaraPaslon2,
                'suara_masuk' => $suaraMasuk,
                'partisipasi' => $partisipasi,
                'warna_partisipasi' => $this->getWarnaPartisipasi($partisipasi)
            ];
        }

        // Sort data by partisipasi in descending order
        usort($unsortedData, function($a, $b) {
            return $b['partisipasi'] <=> $a['partisipasi'];
        });

        // Reindex with sorted numbers
        foreach ($unsortedData as $index => $data) {
            $tableInfo['data'][] = array_merge(
                ['no' => str_pad($index + 1, 2, '0', STR_PAD_LEFT)],
                $data
            );
        }

        return $tableInfo;
    }

    private function getWarnaPartisipasi(float $partisipasi): string
    {
        if ($partisipasi >= 70) {
            return 'green';
        } elseif ($partisipasi >= 50) {
            return 'yellow';
        } else {
            return 'red';
        }
    }
}