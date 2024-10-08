<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Petugas;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Models\LoginHistory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    //
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
    public function calon()
    {
        return view('admin.calon');
    }
    public function tps()
    {
        return view('admin.tps');
    }
    public function kabupaten()
    {
        return view('admin.kabupaten');
    }
    public function kecamatan()
    {
        return view('admin.kecamatan');
    }
    public function kelurahan()
    {
        return view('admin.kelurahan');
    }
    public function user()
    {
        $users = Petugas::all();
        $roles = Role::all();
        $loginHistories = LoginHistory::with('user')->orderBy('login_at', 'desc')->get();
        return view('admin.user', compact('users', 'roles', 'loginHistories'));
    }

    public function storeUser(Request $request)
    {
        try {
            $validated = $request->validate([
                'username' => 'required|unique:petugas',
                'email' => 'required|email|unique:petugas',
                'password' => 'required|min:6',
                'wilayah' => 'required',
                'role' => 'required|exists:roles,name',
            ]);

            $user = Petugas::create([
                'username' => $validated['username'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'wilayah' => $validated['wilayah'],
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
                'wilayah' => 'required',
                'role' => 'required|exists:roles,name',
            ]);

            $user->update([
                'username' => $validated['username'],
                'email' => $validated['email'],
                'wilayah' => $validated['wilayah'],
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

    public function forceLogout($id)
    {
        try {
            $user = Petugas::findOrFail($id);
            $user->update(['is_forced_logout' => true]);
            return redirect()->route('user')->with('success', 'User berhasil dikeluarkan.');
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

}
