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
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function Dashboard()
    {
        return view('admin.dashboard');
    }

    public function rekapitulasi()
    {
        return view('admin.rekapitulasi');
    }

    public function rangkuman()
    {
        return view('admin.rangkuman');
    }

    public function user()
    {
        $users = Petugas::paginate(10);
        $roles = Role::all();
        $loginHistories = LoginHistory::with('user')->active()->orderBy('login_at', 'desc')->paginate(10);
        $kabupatens = Kabupaten::all();
        
        // Calculate active devices for each user
        $activeDevices = [];
        foreach ($users as $user) {
            $activeDevices[$user->id] = LoginHistory::where('user_id', $user->id)->active()->count();
        }
        
        return view('admin.user', compact('users', 'roles', 'loginHistories', 'activeDevices', 'kabupatens'));
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
                'wilayah' => $kabupaten->nama, // Store the kabupaten name instead of ID
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
                'wilayah' => $kabupaten->nama, // Store the kabupaten name instead of ID
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