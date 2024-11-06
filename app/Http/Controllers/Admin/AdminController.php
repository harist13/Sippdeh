<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Petugas;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Models\LoginHistory;
use App\Models\Kabupaten;
use App\Models\Calon;
use App\Models\SuaraCalon;
use App\Models\RingkasanSuaraTPS;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
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
        
        return view('admin.dashboard', compact('calon', 'total_suara', 'suaraPerKabupaten', 'kabupatens', 'kabupatenData', 'syncedCalonData'));
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
        return RingkasanSuaraTPS::whereHas('tps.kelurahan.kecamatan.kabupaten', function($query) use ($kabupatenId) {
            $query->where('id', $kabupatenId);
        })->select(
            \DB::raw('SUM(suara_sah) as suara_sah'),
            \DB::raw('SUM(suara_tidak_sah) as suara_tidak_sah'),
            \DB::raw('SUM(dpt) as dpt'),
            \DB::raw('SUM(abstain) as abstain')
        )->first()->toArray();
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



    public function rekapitulasi()
    {
        return view('admin.rekapitulasi');
    }

    

    public function user(Request $request)
    {
        // Get items per page from request, default to 10
        $itemsPerPage = $request->input('itemsPerPage', 10);
        
        $users = Petugas::paginate($itemsPerPage)
            ->withQueryString();
        
        $roles = Role::all();
        
        $loginHistories = LoginHistory::with('user')
            ->active()
            ->orderBy('login_at', 'desc')
            ->paginate($itemsPerPage)
            ->withQueryString();
            
        $kabupatens = Kabupaten::all();
        
        // Calculate active devices for each user
        $activeDevices = [];
        foreach ($users as $user) {
            $activeDevices[$user->id] = LoginHistory::where('user_id', $user->id)->active()->count();
        }

        return view('admin.user.index', compact('users', 'roles', 'loginHistories', 'activeDevices', 'kabupatens'));
    }
    
    public function forceLogoutDevice($userId, $loginHistoryId)
    {
        try {
            $loginHistory = LoginHistory::findOrFail($loginHistoryId);
            
            // Instead of deleting, mark this specific session as logged out
            $loginHistory->update([
                'is_logged_out' => true,
                'logged_out_at' => now(),
            ]);

            return redirect()->route('user')->with('success', 'User berhasil dikeluarkan dari device tersebut.');
        } catch (\Exception $e) {
            return redirect()->route('user')->with('error', 'Gagal mengeluarkan user dari device.');
        }
    }

    public function forceLogout($id)
    {
        try {
            $user = Petugas::findOrFail($id);
            
            // Mark user as forced logout
            $user->update(['is_forced_logout' => true]);

            // Mark all active sessions for this user as logged out
            LoginHistory::where('user_id', $id)
                ->where('is_logged_out', false)
                ->update([
                    'is_logged_out' => true,
                    'logged_out_at' => now(),
                ]);

            return redirect()->route('user')->with('success', 'User berhasil dikeluarkan dari semua device.');
        } catch (\Exception $e) {
            return redirect()->route('user')->with('error', 'Gagal mengeluarkan user.');
        }
    }

    public function reactivateUser($id)
    {
        try {
            $user = Petugas::findOrFail($id);
            $user->update(['is_forced_logout' => false]);
            return redirect()->route('user')->with('success', 'User berhasil diaktifkan kembali.');
        } catch (\Exception $e) {
            return redirect()->route('user')->with('error', 'Gagal mengaktifkan kembali user.');
        }
    }

    public function storeUser(Request $request)
    {
        try {
            $validated = $request->validate([
                'username' => 'required|unique:petugas',
                'email' => 'required|email|unique:petugas',
                'password' => 'required|min:6',
                'wilayah' => 'required|exists:kabupaten,id', // Validate that the selected wilayah exists in kabupaten table
                'role' => 'required|exists:roles,name',
            ]);

            $kabupaten = Kabupaten::findOrFail($validated['wilayah']);

            $user = Petugas::create([
                'username' => $validated['username'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'kabupaten_id' => $kabupaten->id, // Store the kabupaten name instead of ID
                'role' => $validated['role'],
                'is_forced_logout' => false,
            ]);

            $user->assignRole($validated['role']);

            return response()->json(['success' => true, 'message' => 'User berhasil ditambahkan.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal menambahkan user.'], 500);
        }
    }

    public function updateUser(Request $request, $id)
    {
        try {
            $user = Petugas::findOrFail($id);

            $validated = $request->validate([
                'username' => ['required', Rule::unique('petugas')->ignore($user->id)],
                'email' => ['required', 'email', Rule::unique('petugas')->ignore($user->id)],
                'wilayah' => 'required|exists:kabupaten,id', // Validate that the selected wilayah exists in kabupaten table
                'role' => 'required|exists:roles,name',
            ]);

            $kabupaten = Kabupaten::findOrFail($validated['wilayah']);

            $user->update([
                'username' => $validated['username'],
                'email' => $validated['email'],
                'kabupaten_id' => $kabupaten->id, // Store the kabupaten name instead of ID
            ]);

            if ($request->filled('password')) {
                $user->update(['password' => Hash::make($request->password)]);
            }

            $user->syncRoles([$validated['role']]);

            return response()->json(['success' => true, 'message' => 'User berhasil diperbarui.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal memperbarui user.'], 500);
        }
    }

    public function deleteUser($id)
    {
        try {
            $user = Petugas::findOrFail($id);
            $user->delete();
            return redirect()->route('user')->with('success', 'User berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('user')->with('error', 'Gagal menghapus user.');
        }
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        if (is_null($user)) {
            return response()->json(['success' => false]);
        }

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