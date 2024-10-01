<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\OperatorController;

Route::get('/', [LoginController::class, 'index'])->name('index');
Route::post('/login', [LoginController::class, 'login'])->name('login'); // Route untuk proses login
Route::post('/logout', [LoginController::class, 'logout'])->name('logout'); // Route untuk logout

// Middleware untuk admin
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/Dashboard', [AdminController::class, 'Dashboard'])->name('Dashboard');
    Route::get('/rangkuman', [AdminController::class, 'rangkuman'])->name('rangkuman');
    
});

// Middleware untuk operator
Route::middleware(['auth', 'role:operator'])->group(function () {
    Route::get('/operator/dashboard', [OperatorController::class, 'dashboard'])->name('operator.dashboard');
});
