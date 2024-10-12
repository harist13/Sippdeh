<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Petugas;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class OperatorController extends Controller
{
    //
    public function dashboard()
    {
        return view('operator.dashboard');
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
