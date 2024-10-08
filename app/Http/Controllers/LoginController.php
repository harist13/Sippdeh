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
            'email' => ['required', 'email'],
            'password' => ['required', 'min:6'],
        ], [
            'email.required' => 'Email harus diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password harus diisi.',
            'password.min' => 'Password harus minimal 6 karakter.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();
            
            if ($user->is_forced_logout) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Akun Anda telah dikeluarkan oleh admin. Silakan hubungi admin untuk informasi lebih lanjut.',
                ])->withInput($request->only('email'));
            }

            // Check the number of active devices
            $activeDevices = LoginHistory::where('user_id', $user->id)->count();

            if ($activeDevices >= $user->limit) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Maaf, Anda telah mencapai batas maksimum login (' . $user->limit . ' device). Silakan logout dari salah satu device untuk melanjutkan.',
                ])->withInput($request->only('email'));
            }

            $request->session()->regenerate();

            // Simpan riwayat login
            LoginHistory::create([
                'user_id' => $user->id,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'login_at' => now(),
            ]);

            if ($user->hasRole('admin')) {
                return redirect()->route('Dashboard')->with('success', 'Login berhasil!');
            } elseif ($user->hasRole('operator')) {
                return redirect()->route('operator.dashboard')->with('success', 'Login berhasil!');
            }

            return redirect()->intended('/')->with('success', 'Login berhasil!');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->withInput($request->only('email'));
    }

    public function logout(Request $request)
    {
        // Hapus entri login history untuk user dan device ini
        LoginHistory::where('user_id', Auth::id())
            ->where('ip_address', $request->ip())
            ->where('user_agent', $request->userAgent())
            ->delete();

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/')->with('success', 'Anda telah berhasil logout.');
    }
}