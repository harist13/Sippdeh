<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        // Validasi input dari form login
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Autentikasi pengguna
        if (Auth::attempt($credentials)) {
            // Regenerasi sesi
            $request->session()->regenerate();

            // Mendapatkan pengguna yang sedang login
            $user = Auth::user();

            // Mengarahkan berdasarkan peran (role)
            if ($user->hasRole('admin')) {
                return redirect()->route('Dashboard'); // Admin dashboard route
            } elseif ($user->hasRole('operator')) {
                return redirect()->route('operator.dashboard'); // Operator dashboard route
            }

            // Redirect default jika role tidak dikenali
            return redirect()->intended('/');
        }

        // Mengembalikan error jika login gagal
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
