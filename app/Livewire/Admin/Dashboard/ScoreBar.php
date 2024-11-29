<?php

namespace App\Livewire\Admin\Dashboard;

use App\Models\Calon;
use App\Models\SuaraCalon;
use Livewire\Component;
use Livewire\Attributes\Lazy;

#[Lazy]
class ScoreBar extends Component
{
    public $calon;
    public $total_suara;
    public $paslon1;
    public $paslon2;
    public $paslon1Wins;

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
        // Ambil paslon gubernur
        $this->calon = Calon::where('posisi', 'gubernur')->get();
        
        // Urutkan paslon berdasarkan total suara tertinggi
        $paslon_sorted = $this->calon->sortByDesc('total_suara');
        
        // Ambil paslon pertama dan kedua
        $this->paslon1 = $this->calon->first(); // Selalu ambil paslon pertama
        $this->paslon2 = $this->calon->skip(1)->first(); // Selalu ambil paslon kedua

        // Jika tidak ada data, berikan nilai default
        if (!$this->paslon1) {
            $this->paslon1 = new \stdClass();
            $this->paslon1->nama = 'Belum ada data';
            $this->paslon1->nama_wakil = '';
            $this->paslon1->foto = '/placeholder.jpg';
            $this->paslon1->total_suara = 0;
            $this->paslon1->persentase = 0;
        }
        if (!$this->paslon2) {
            $this->paslon2 = new \stdClass();
            $this->paslon2->nama = 'Belum ada data';
            $this->paslon2->nama_wakil = '';
            $this->paslon2->foto = '/placeholder.jpg';
            $this->paslon2->total_suara = 0;
            $this->paslon2->persentase = 0;
        }

        // Hitung total suara untuk masing-masing paslon
        foreach ($this->calon as $paslon) {
            $paslon->total_suara = $paslon->suaraCalon()->sum('suara') + $paslon->suaraCalonTambahan()->sum('suara');
        }
        
        // Hitung total semua suara
        $this->total_suara = $this->calon->sum('total_suara');
        
        // Hitung persentase untuk masing-masing paslon
        foreach ($this->calon as $paslon) {
            $paslon->persentase = $this->total_suara > 0 ? 
                round(($paslon->total_suara / $this->total_suara) * 100, 1) : 0;
        }

        // Tentukan arah scorebar berdasarkan perbandingan suara
        $this->paslon1Wins = $this->paslon1->total_suara >= $this->paslon2->total_suara;
    }

    public function render()
    {
        return view('admin.dashboard.score-bar');
    }
}