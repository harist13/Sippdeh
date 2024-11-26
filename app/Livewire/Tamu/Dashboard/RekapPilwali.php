<?php

namespace App\Livewire\Tamu\Dashboard;

use App\Models\ResumeSuaraPilwaliKabupaten;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Lazy;
use Livewire\Component;

#[Lazy]
class RekapPilwali extends Component
{
    public function placeholder(): string
    {
        return <<<'HTML'
            <div class="flex justify-center my-20 w-[1080px] mx-auto">
                <svg class="animate-spin h-8 w-8 text-[#3560A0]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>
        HTML;
    }

    public function render(): View
    {
        $resumeData = $this->getResumeData();
        return view('Tamu.dashboard.rekap-pilwali', compact('resumeData'));
    }

    private function getResumeData(): array
    {
        // Get summary data for the province by summing up all kabupaten
        $resumeData = ResumeSuaraPilwaliKabupaten::query()
            ->whereId(session('Tamu_kabupaten_id'))
            ->first();

        if ($resumeData == null) {
            return [];
        }

        // Ensure no negative values
        $suaraSah = max(0, $resumeData->suara_sah ?? 0);
        $suaraTidakSah = max(0, $resumeData->suara_tidak_sah ?? 0);
        $suaraMasuk = max(0, $resumeData->suara_masuk ?? 0);
        $dpt = max(0, $resumeData->dpt ?? 0);
        $abstain = max(0, $resumeData->abstain ?? 0);
        $partisipasi = max(0, $resumeData->partisipasi ?? 0);

        return [
            'logo' => $resumeData->kabupaten->logo,
            'nama' => $resumeData->kabupaten->nama,
            'suara_sah' => $suaraSah,
            'suara_tidak_sah' => $suaraTidakSah,
            'dpt' => $dpt,
            'abstain' => $abstain,
            'suara_masuk' => $suaraMasuk,
            'partisipasi' => $partisipasi,
            'warna_partisipasi' => $this->getWarnaPartisipasi($partisipasi)
        ];
    }

    private function getWarnaPartisipasi(float $partisipasi): string
    {
        if ($partisipasi >= 77.5) {
            return 'green';
        } else {
            return 'red';
        }
    }
}
