<?php

namespace App\Livewire\Tamu\Dashboard;

use App\Models\Calon;
use App\Models\Kabupaten;
use App\Models\SuaraCalon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\Attributes\Lazy;
use Sentry\SentrySdk;
use Exception;
use Illuminate\Support\Collection;

#[Lazy]
class DiagramBarPilgub extends Component
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
        $data = $this->getData();
        return view('Tamu.dashboard.diagram-bar-pilgub', compact('data'));
    }

    private function getData(): array
    {
        $calonGubernurs = $this->getCalonGubernurs();
        
        if ($calonGubernurs->count() < 2) {
            return [];
        }

        $calonGubernur1 = $calonGubernurs[0];
        $calonGubernur2 = $calonGubernurs[1];
        
        $calonGubernur1Data = [];
        $calonGubernur2Data = [];

        $labels = [];
        $suaraSahPerKabupaten = []; // Array untuk menyimpan total suara sah per kabupaten
        
        foreach ($this->getKabupatens() as $kabupaten) {
            // Ambil suara calon gubernur ke-1
            $suaraCalonGubernur1 = $this->getSuaraCalonGubernurInKabupaten($calonGubernur1->id, $kabupaten->id);
            $calonGubernur1Data[] = $suaraCalonGubernur1;
            
            // Ambil suara calon gubernur ke-2
            $suaracalonGubernur2 = $this->getSuaraCalonGubernurInKabupaten($calonGubernur2->id, $kabupaten->id);
            $calonGubernur2Data[] = $suaracalonGubernur2;

            // Menghilangkan kata 'Kabupaten' atau 'Kota' dan memasukkannya ke labels
            $namaKabupaten = str_replace(['Kota ', 'Kabupaten '], '', $kabupaten->nama);
            $labels[] = $namaKabupaten;
            
            // Hitung total suara sah per kabupaten
            $suaraSah = $suaraCalonGubernur1 + $suaracalonGubernur2;
            $suaraSahPerKabupaten[] = $suaraSah;
        }
        
        // Calculate the maximum value from both datasets
        $maxValue = max(
            max($calonGubernur1Data ?: [0]),
            max($calonGubernur2Data ?: [0])
        );
        
        // Calculate the dynamic max range
        $dynamicMaxRange = $this->calculateDynamicMaxRange($maxValue);
        
        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => $calonGubernur1->nama,
                    'data' => $calonGubernur1Data,
                    'backgroundColor' => '#3560A0',
                ],
                [
                    'label' => $calonGubernur2->nama,
                    'data' => $calonGubernur2Data,
                    'backgroundColor' => '#F9D926',
                ]
            ],
            'maxRange' => $dynamicMaxRange,
            'totalSuarahSah' => $suaraSahPerKabupaten
        ];
    }

    private function getCalonGubernurs(): Collection
    {
        try {
            return Calon::wherePosisi('GUBERNUR')->get();
        } catch (Exception $exception) {
            Log::error($exception);
            SentrySdk::getCurrentHub()->captureException($exception);

            return collect([]);
        }
    }

    private function getKabupatens(): Collection
    {
        try {
            return Kabupaten::orderBy('nama')->get();
        } catch (Exception $exception) {
            Log::error($exception);
            SentrySdk::getCurrentHub()->captureException($exception);

            return collect([]);
        }
    }

    private function getSuaraCalonGubernurInKabupaten(int $calonId, int $kabupatenId): int
    {
        try {
            return SuaraCalon::query()
                ->whereHas('tps.kelurahan.kecamatan.kabupaten', fn (Builder $builder) => $builder->whereId($kabupatenId))
                ->where('calon_id', $calonId)
                ->sum('suara');
        } catch (Exception $exception) {
            Log::error($exception);
            SentrySdk::getCurrentHub()->captureException($exception);

            return 0;
        }
    }

    private function calculateDynamicMaxRange($maxValue): int
    {
        // Base step size for ranges (e.g., 500, 1000, 1500, etc.)
        $baseStep = 500;
        
        // Calculate how many steps we need to accommodate the max value
        $steps = ceil($maxValue / $baseStep);
        
        // Return the next range that would fully contain the max value
        return $steps * $baseStep;
    }
}
