<?php

namespace App\Livewire\Superadmin\Dashboard;

use App\Models\Kabupaten;
use Livewire\Component;
use Livewire\Attributes\Lazy;

#[Lazy]
class KabupatenButtons extends Component
{
    public function placeholder()
    {
        return <<<'HTML'
            <div class="flex justify-center my-4">
                <svg class="animate-spin h-8 w-8 text-[#3560A0]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>
        HTML;
    }

    public function getKabupatens()
    {
        return Kabupaten::orderBy('nama')->get();
    }

    public function navigateToResume($wilayah)
    {
        // Get kabupaten by slug
        $kabupaten = Kabupaten::where('slug', $wilayah)->first();
        
        if (!$kabupaten) {
            return;
        }

        // Determine if it's a city (kota) or regency (kabupaten)
        $isKota = str_contains(strtolower($kabupaten->nama), 'kota');

        return redirect()->route('Superadmin.resume', [
            'wilayah' => $wilayah,
            'kabupatenId' => $kabupaten->id,
            'showPilgub' => false,
            'showPilwali' => $isKota,
            'showPilbup' => !$isKota
        ]);
    }

    public function render()
    {
        $kabupatens = $this->getKabupatens();
        return view('Superadmin.dashboard.kabupaten-buttons', [
            'kabupatens' => $kabupatens
        ]);
    }
}