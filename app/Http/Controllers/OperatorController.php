<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Petugas;
use App\Models\Calon;
use App\Models\Kabupaten;
use App\Models\Provinsi;
use App\Models\ResumeSuaraPilgubKabupaten;
use App\Models\ResumeSuaraPilgubTPS;
use App\Models\ResumeSuaraTPS;
use App\Models\SuaraCalon;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OperatorController extends Controller
{
    //
    public function Dashboard()
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
        $chartData = $this->getChartData();
        $provinsiData = $this->getProvinsiData();
        
        return view('operator.dashboard', compact('calon', 'total_suara', 'suaraPerKabupaten', 'kabupatens', 'kabupatenData', 'syncedCalonData', 'dptAbstainData', 'chartData', 'provinsiData'));
    }

    private function getProvinsiData(): array
    {
        // Get provinsi Kalimantan Timur
        $provinsi = Provinsi::where('nama', 'LIKE', '%Kalimantan Timur%')->first();
        
        if (!$provinsi) {
            return [];
        }

        // Get summary data for the province by summing up all kabupaten
        $ringkasanData = ResumeSuaraTPS::whereHas('tps.kelurahan.kecamatan.kabupaten.provinsi', function($query) use ($provinsi) {
            $query->where('id', $provinsi->id);
        })->select(
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
        $totalSuaraKabupaten = SuaraCalon::whereHas('tps.kelurahan.kecamatan.kabupaten', function($query) use ($kabupatenId) {
            $query->where('id', $kabupatenId);
        })->sum('suara');

        if ($totalSuaraKabupaten === 0) return 0;

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


    private function getChartData(): array
    {
        $kabupatens = Kabupaten::orderBy('nama', 'asc')->get();
        $gubernurCalon = Calon::where('posisi', 'GUBERNUR')->get();
        
        if ($gubernurCalon->count() < 2) {
            return [];
        }
        
        $paslon1 = $gubernurCalon[0];
        $paslon2 = $gubernurCalon[1];
        
        $labels = [];
        $paslon1Data = [];
        $paslon2Data = [];
        $totalSuarahSahPerKabupaten = []; // Array untuk menyimpan total suara sah per kabupaten
        
        foreach ($kabupatens as $kabupaten) {
            $namaKabupaten = str_replace(['Kota ', 'Kabupaten '], '', $kabupaten->nama);
            $labels[] = $namaKabupaten;
            
            $suaraPaslon1 = SuaraCalon::whereHas('tps.kelurahan.kecamatan.kabupaten', function($query) use ($kabupaten) {
                $query->where('id', $kabupaten->id);
            })->where('calon_id', $paslon1->id)->sum('suara');
            
            $suaraPaslon2 = SuaraCalon::whereHas('tps.kelurahan.kecamatan.kabupaten', function($query) use ($kabupaten) {
                $query->where('id', $kabupaten->id);
            })->where('calon_id', $paslon2->id)->sum('suara');
            
            // Hitung total suara sah per kabupaten
            $totalSuarahSahPerKabupaten[] = $suaraPaslon1 + $suaraPaslon2;
            
            $paslon1Data[] = $suaraPaslon1;
            $paslon2Data[] = $suaraPaslon2;
        }
        
        // Calculate the maximum value from both datasets
        $maxValue = max(
            max($paslon1Data ?: [0]),
            max($paslon2Data ?: [0])
        );
        
        // Calculate the dynamic max range
        $dynamicMaxRange = $this->calculateDynamicMaxRange($maxValue);
        
        return [
            'title' => "Perolehan Suara Gubernur Per Kabupaten/Kota",
            'data' => [
                'labels' => $labels,
                'datasets' => [
                    [
                        'label' => "{$paslon1->nama}",
                        'data' => $paslon1Data,
                        'backgroundColor' => '#3560A0',
                        'barPercentage' => 0.98,
                        'categoryPercentage' => 0.5,
                    ],
                    [
                        'label' => "{$paslon2->nama}",
                        'data' => $paslon2Data,
                        'backgroundColor' => '#F9D926',
                        'barPercentage' => 0.98,
                        'categoryPercentage' => 0.5,
                    ]
                ],
                'maxRange' => $dynamicMaxRange,
                'totalSuarahSah' => $totalSuarahSahPerKabupaten // Mengirim total suara sah ke frontend
            ]
        ];
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



    public function updateoperator(Request $request)
    {
        $user = Auth::user();
        
        $validatedData = $request->validate([
            'email' => 'required|email|unique:petugas,email,'.$user->id,
            'password' => 'nullable|min:6|confirmed',
        ]);

        $user->email = $validatedData['email'];
        
        if (!empty($validatedData['password'])) {
            $user->password = Hash::make($validatedData['password']);
        }

        $user->save();

        return response()->json(['success' => true]);
    }
}
