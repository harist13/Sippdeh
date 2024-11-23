<?php

namespace App\Livewire\Tamu\Dashboard;

use App\Models\Calon;
use App\Models\ResumeSuaraPilbupKabupaten;
use App\Models\SuaraCalon;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Lazy;
use Livewire\Component;

#[Lazy]
class RekapPilbup extends Component
{
    public function placeholder()
    {
        return <<<'HTML'
            <div class="flex justify-center my-20 w-[1080px]">
                <svg class="animate-spin h-8 w-8 text-[#3560A0]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>
        HTML;
    }

    public function render()
    {
        $kabupatenData = $this->getKabupatenData();
        return view('Tamu.dashboard.rekap-pilbup', compact('kabupatenData'));
    }

    private function getKabupatenData(): array
    {
        // Get summary data for the province by summing up all kabupaten
        $ringkasanData = ResumeSuaraPilbupKabupaten::whereId(session('Tamu_kabupaten_id'))->first();

        // Ensure no negative values
        $suaraSah = max(0, $ringkasanData->suara_sah ?? 0);
        $suaraTidakSah = max(0, $ringkasanData->suara_tidak_sah ?? 0);
        $suaraMasuk = max(0, $ringkasanData->suara_masuk ?? 0);
        $dpt = max(0, $ringkasanData->dpt ?? 0);
        $abstain = max(0, $ringkasanData->abstain ?? 0);

        // Calculate participation percentage
        $partisipasi = $this->hitungPartisipasi($suaraMasuk, $dpt);

        return [
            'logo' => '',
            'nama' => session('Tamu_kabupaten_name'),
            'suara_sah' => $suaraSah,
            'suara_tidak_sah' => $suaraTidakSah,
            'dpt' => $dpt,
            'abstain' => $abstain,
            'suara_masuk' => $suaraMasuk,
            'partisipasi' => $partisipasi,
            'warna_partisipasi' => $this->getWarnaPartisipasi($partisipasi)
        ];
    }

    private function hitungPartisipasi(int $suaraMasuk, int $dpt): float
    {
        if ($dpt === 0) return 0;
        
        // Calculate participation percentage
        $partisipasi = ($suaraMasuk / $dpt) * 100;
        
        // Clamp the value between 0 and 100
        return max(0, min(100, round($partisipasi, 1)));
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
