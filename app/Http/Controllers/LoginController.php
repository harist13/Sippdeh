<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\LoginHistory;
use App\Models\Petugas;

class LoginController extends Controller
{
    public function index()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => ['required'],
            'password' => ['required', 'min:6'],
        ], [
            'username.required' => 'Username harus diisi.',
            'password.required' => 'Password harus diisi.',
            'password.min' => 'Password harus minimal 6 karakter.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        if (Auth::attempt($request->only('username', 'password'))) {
            $user = Auth::user();
            
            if ($user->is_forced_logout) {
                Auth::logout();
                return back()->withErrors([
                    'username' => 'Akun Anda telah dikeluarkan oleh admin. Silakan hubungi admin untuk mengaktifkan kembali akun Anda.',
                ])->withInput($request->only('username'));
            }

            // Check the number of active devices
            $activeDevices = LoginHistory::where('user_id', $user->id)
                                         ->where('is_logged_out', false)
                                         ->count();

            if ($activeDevices >= $user->limit) {
                Auth::logout();
                return back()->withErrors([
                    'username' => 'Maaf, Anda telah mencapai batas maksimum login (' . $user->limit . ' device). Silakan logout dari salah satu device untuk melanjutkan atau hubungi pihak admin.',
                ])->withInput($request->only('username'));
            }

            $request->session()->regenerate();
            session(['user_wilayah' => $user->wilayah?->nama ?? '-']);

            if ($user->role == 'operator') {
                session(['operator_provinsi_id' => $user->kabupaten->provinsi->id]);
                session(['operator_provinsi_name' => $user->kabupaten->provinsi->nama]);
                session(['operator_kabupaten_id' => $user->kabupaten->id]);
                session(['operator_kabupaten_name' => $user->kabupaten->nama]);
            }

            // Simpan riwayat login
            LoginHistory::create([
                'user_id' => $user->id,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'login_at' => now(),
                'is_logged_out' => false,
            ]);

            if ($user->hasRole('admin')) {
                return redirect()->route('Dashboard')->with('success', 'Login berhasil!');
            } elseif ($user->hasRole('operator')) {
                return redirect()->route('operator.dashboard')->with('success', 'Login berhasil!');
            }

            return redirect()->intended('/')->with('success', 'Login berhasil!');
        }

        return back()->withErrors([
            'username' => 'Username atau password salah.',
        ])->withInput($request->only('username'));
    }

    public function logout(Request $request)
    {
        // Find and mark only the current device session as logged out
        $currentSession = LoginHistory::where('user_id', Auth::id())
            ->where('ip_address', $request->ip())
            ->where('user_agent', $request->userAgent())
            ->where('is_logged_out', false)
            ->first();

        if ($currentSession) {
            $currentSession->update([
                'is_logged_out' => true,
                'logged_out_at' => now(),
            ]);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/')->with('success', 'Anda telah berhasil logout dari device ini.');
    }
}