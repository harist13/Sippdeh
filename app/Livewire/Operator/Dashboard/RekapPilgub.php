<?php

namespace App\Livewire\Operator\Dashboard;

use App\Models\Calon;
use App\Models\Kabupaten;
use App\Models\Provinsi;
use App\Models\ResumeSuaraPilgubKabupaten;
use App\Models\ResumeSuaraPilgubProvinsi;
use App\Models\ResumeSuaraTPS;
use App\Models\SuaraCalon;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Lazy;
use Livewire\Component;

#[Lazy]
class RekapPilgub extends Component
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
        // Ambil paslon gubernur
        $calon = Calon::where('posisi', 'gubernur')->get();
        
        // Ambil semua kabupaten
        $kabupatens = Kabupaten::all();
        
        // Siapkan data suara per kabupaten untuk setiap paslon
        $suaraPerKabupaten = [];
        
        foreach ($kabupatens as $kabupaten) {
            $suaraPaslon = [];
            $totalSuaraKabupaten = 0;

            // Hitung total suara semua paslon di kabupaten ini
            foreach ($calon as $paslon) {
                $totalSuara = SuaraCalon::whereHas('tps.kelurahan.kecamatan.kabupaten', function($q) use ($kabupaten) {
                    $q->where('id', $kabupaten->id);
                })->where('calon_id', $paslon->id)->sum('suara');
                
                $suaraPaslon[$paslon->id] = $totalSuara;
                $totalSuaraKabupaten += $totalSuara;
            }

            // Hitung persentase suara untuk setiap paslon di kabupaten ini
            foreach ($suaraPaslon as $paslonId => $suara) {
                $suaraPaslon[$paslonId] = $totalSuaraKabupaten > 0 ? 
                    round(($suara / $totalSuaraKabupaten) * 100, 1) : 0;
            }

            $suaraPerKabupaten[$kabupaten->id] = $suaraPaslon;
        }
        
        // Hitung total suara untuk masing-masing paslon (keseluruhan)
        foreach ($calon as $paslon) {
            $paslon->total_suara = $paslon->suaraCalon()->sum('suara');
        }
        
        // Hitung total semua suara
        $total_suara = $calon->sum('total_suara');
        
        // Hitung persentase untuk masing-masing paslon
        foreach ($calon as $paslon) {
            $paslon->persentase = $total_suara > 0 ? 
                round(($paslon->total_suara / $total_suara) * 100, 1) : 0;
        }

        $kabupatenData = $this->getKabupatenData();
        $syncedCalonData = $this->getCalonDataByWilayah($kabupatens);
        $dptAbstainData = $this->getTotalDptAbstainData();
        $provinsiData = $this->getProvinsiData();
        
        return view('operator.dashboard.rekap-pilgub', compact('calon', 'total_suara', 'suaraPerKabupaten', 'kabupatens', 'kabupatenData', 'syncedCalonData', 'dptAbstainData', 'provinsiData'));
    }

    private function getProvinsiData(): array
    {
        // Get provinsi Kalimantan Timur
        $provinsi = Provinsi::where('nama', 'LIKE', '%Kalimantan Timur%')->first();
        
        if (!$provinsi) {
            return [];
        }

        // Get summary data for the province by summing up all kabupaten
        $ringkasanData = ResumeSuaraPilgubProvinsi::where('id', $provinsi->id)
        ->select(
            DB::raw('SUM(suara_sah) as suara_sah'),
            DB::raw('SUM(suara_tidak_sah) as suara_tidak_sah'),
            DB::raw('SUM(dpt) as dpt'),
            DB::raw('SUM(abstain) as abstain')
        )->first();

        // Ensure no negative values
        $suaraSah = max(0, $ringkasanData->suara_sah ?? 0);
        $suaraTidakSah = max(0, $ringkasanData->suara_tidak_sah ?? 0);
        $dpt = max(0, $ringkasanData->dpt ?? 0);
        $abstain = max(0, $ringkasanData->abstain ?? 0);

        // Calculate suara masuk
        $suaraMasuk = $suaraSah + $suaraTidakSah;

        // Calculate participation percentage
        $partisipasi = $this->hitungPartisipasi($suaraMasuk, $dpt);

        // Get only Gubernur candidates for Kaltim
        $gubernurCalon = Calon::where('posisi', 'GUBERNUR')
            ->where('provinsi_id', $provinsi->id)
            ->get();

        $candidates = [];
        foreach ($gubernurCalon as $index => $cal) {
            // Get total votes from all kabupaten for this candidate
            $totalSuara = SuaraCalon::whereHas('tps.kelurahan.kecamatan.kabupaten.provinsi', function($query) use ($provinsi) {
                $query->where('id', $provinsi->id);
            })->where('calon_id', $cal->id)
            ->sum('suara');

            $persentase = $suaraSah > 0 ? round(($totalSuara / $suaraSah) * 100, 2) : 0;

            $candidates[] = [
                'id' => $cal->id,
                'nama' => $cal->nama,
                'nama_wakil' => $cal->nama_wakil,
                'foto' => $cal->foto,
                'posisi' => $cal->posisi,
                'nomor_urut' => $index + 1,
                'total_suara' => $totalSuara,
                'persentase' => $persentase,
                'wilayah' => $provinsi->nama
            ];
        }

        return [
            'logo' => $provinsi->logo,
            'nama' => $provinsi->nama,
            'suara_sah' => $suaraSah,
            'suara_tidak_sah' => $suaraTidakSah,
            'dpt' => $dpt,
            'abstain' => $abstain,
            'suara_masuk' => $suaraMasuk,
            'partisipasi' => $partisipasi,
            'warna_partisipasi' => $this->getWarnaPartisipasi($partisipasi),
            'candidates' => $candidates
        ];
    }

    private function getTotalDptAbstainData(): array 
    {
        // Get sum of suara masuk (suara sah + tidak sah) and Abstain from all regions
        $totalData = ResumeSuaraTPS::select(
            DB::raw('SUM(suara_sah + suara_tidak_sah) as total_suara_masuk'),
            DB::raw('SUM(abstain) as total_abstain')
        )->first();
        
        $totalSuaraMasuk = max(0, $totalData->total_suara_masuk ?? 0);
        $totalAbstain = max(0, $totalData->total_abstain ?? 0);
        
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
    
    private function getCalonDataByWilayah($kabupatens): array
    {
        $syncedData = [];
        
        foreach ($kabupatens as $kabupaten) {
            // Get Gubernur
            $gubernurCalon = $this->getCalonByPosisi('GUBERNUR', $kabupaten->provinsi_id);
            
            // Get Walikota/Bupati based on kabupaten
            $lokalCalon = $this->getCalonByPosisi(['WALIKOTA', 'BUPATI'], null, $kabupaten->id);
            
            // Merge and calculate suara for semua calon
            $allCalon = $gubernurCalon->concat($lokalCalon);
            
            $calonData = [];
            foreach ($allCalon as $index => $cal) {
                $totalSuara = $this->getSuaraCalonByWilayah($cal->id, $kabupaten->id);
                $persentase = $this->hitungPersentaseSuaraCalon($totalSuara, $kabupaten->id);
                
                $calonData[] = [
                    'id' => $cal->id,
                    'nama' => $cal->nama,
                    'nama_wakil' => $cal->nama_wakil,
                    'foto' => $cal->foto,
                    'posisi' => $cal->posisi,
                    'nomor_urut' => $index + 1,
                    'total_suara' => $totalSuara,
                    'persentase' => $persentase,
                    'wilayah' => $cal->posisi == 'GUBERNUR' ? $cal->provinsi->nama : $kabupaten->nama
                ];
            }
            
            $syncedData[$kabupaten->id] = $calonData;
        }

        return $syncedData;
    }

    private function getCalonByPosisi(string|array $posisi, ?int $provinsiId = null, ?int $kabupatenId = null)
    {
        $query = Calon::query();
        
        if (is_array($posisi)) {
            $query->whereIn('posisi', $posisi);
        } else {
            $query->where('posisi', $posisi);
        }

        if ($provinsiId) {
            $query->where('provinsi_id', $provinsiId);
        }

        if ($kabupatenId) {
            $query->where('kabupaten_id', $kabupatenId);
        }

        return $query->get();
    }

    private function getSuaraCalonByWilayah(int $calonId, int $kabupatenId): int
    {
        return SuaraCalon::whereHas('tps.kelurahan.kecamatan.kabupaten', function($query) use ($kabupatenId) {
            $query->where('id', $kabupatenId);
        })->where('calon_id', $calonId)->sum('suara') ?? 0;
    }

    private function hitungPersentaseSuaraCalon(int $totalSuara, int $kabupatenId): float
    {
        $totalSuaraKabupaten = ResumeSuaraPilgubKabupaten::where('id', $kabupatenId);

        if ($totalSuaraKabupaten->count() <= 0) return 0;

        $totalSuaraKabupaten = $totalSuaraKabupaten->first()->suara_sah;

        if ($totalSuaraKabupaten <= 0) return 0;

        return round(($totalSuara / $totalSuaraKabupaten) * 100, 2);
    }

    private function getKabupatenData(): array
    {
        $kabupatens = Kabupaten::all();
        $data = [];

        foreach ($kabupatens as $kabupaten) {
            $ringkasanData = $this->getRingkasanDataByKabupaten($kabupaten->id);
            
            // Ensure no negative values
            $suaraSah = max(0, $ringkasanData['suara_sah'] ?? 0);
            $suaraTidakSah = max(0, $ringkasanData['suara_tidak_sah'] ?? 0);
            $dpt = max(0, $ringkasanData['dpt'] ?? 0);
            $abstain = max(0, $ringkasanData['abstain'] ?? 0);
            
            // Calculate suara masuk (total votes cast)
            $suaraMasuk = $suaraSah + $suaraTidakSah;
            
            // Calculate and clamp participation percentage between 0 and 100
            $partisipasi = $this->hitungPartisipasi($suaraMasuk, $dpt);
            
            $data[$kabupaten->id] = [
                'logo' => $kabupaten->logo,
                'nama' => $kabupaten->nama,
                'suara_sah' => $suaraSah,
                'suara_tidak_sah' => $suaraTidakSah,
                'dpt' => $dpt,
                'abstain' => $abstain,
                'suara_masuk' => $suaraMasuk,
                'partisipasi' => $partisipasi,
                'warna_partisipasi' => $this->getWarnaPartisipasi($partisipasi)
            ];
        }

        return $data;
    }

    private function getRingkasanDataByKabupaten(int $kabupatenId): array
    {
        $ringkasan = ResumeSuaraPilgubKabupaten::where('id', $kabupatenId)->select(
            DB::raw('SUM(suara_sah) as suara_sah'),
            DB::raw('SUM(suara_tidak_sah) as suara_tidak_sah'),
            DB::raw('SUM(dpt) as dpt'),
            DB::raw('SUM(abstain) as abstain')
        );

        if ($ringkasan->count()) {
            return $ringkasan->first()->toArray();
        }

        return [];
    }

    private function hitungPartisipasi(int $suaraMasuk, int $dpt): float
    {
        if ($dpt === 0) return 0;
        
        // Calculate participation percentage
        $partisipasi = ($suaraMasuk / $dpt) * 100;
        
        // Clamp the value between 0 and 100
        return max(0, min(100, round($partisipasi, 2)));
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
