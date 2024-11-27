<?php

namespace App\Livewire\Superadmin\Dashboard;

use App\Models\ResumeSuaraPilgubProvinsi;
use Livewire\Component;
use Livewire\Attributes\Lazy;

#[Lazy]
class DiagramPieChart extends Component
{
    public $dptAbstainData;

    public function mount()
    {
        $this->dptAbstainData = $this->getTotalDptAbstainData();
    }

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

    public function render()
    {
        return view('superadmin.dashboard.diagram-pie-chart');
    }
    
    private function getTotalDptAbstainData(): array 
    {
        // Get sum of suara masuk (suara sah + tidak sah) and Abstain from all regions
        $totalData = ResumeSuaraPilgubProvinsi::query()
            ->where('id', 23) // 23 itu Kalimantan Timur, liat ProvinsiSeeder.php
            ->first();
        
        $totalSuaraMasuk = max(0, $totalData->suara_masuk ?? 0);
        $totalAbstain = max(0, $totalData->abstain ?? 0);
        
        // Calculate total for percentage calculation
        $total = $totalSuaraMasuk + $totalAbstain;

        return [
            'labels' => ['Suara Masuk', 'Abstain'],
            'values' => [$totalSuaraMasuk, $totalAbstain], 
            'percentages' => [
                round(($total > 0 ? $totalSuaraMasuk / $total * 100 : 0), 1),
                round(($total > 0 ? $totalAbstain / $total * 100 : 0), 1)
            ],
            'total_suara_masuk' => number_format($totalSuaraMasuk, 0, ',', '.'),
            'total_abstain' => number_format($totalAbstain, 0, ',', '.')
        ];
    }
}