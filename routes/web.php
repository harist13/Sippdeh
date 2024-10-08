<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\OperatorController;

Route::get('/', [LoginController::class, 'index'])->name('index');

Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('submitLogin'); // Route untuk proses login
Route::post('/logout', [LoginController::class, 'logout'])->name('logout'); // Route untuk logout

// Middleware untuk admin
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/Dashboard', [AdminController::class, 'Dashboard'])->name('Dashboard');
    Route::get('/rangkuman', [AdminController::class, 'rangkuman'])->name('rangkuman');
    Route::get('/calon', [AdminController::class, 'calon'])->name('calon');
    Route::get('/provinsi', [AdminController::class, 'provinsi'])->name('provinsi');
    Route::get('/kabupaten', [AdminController::class, 'kabupaten'])->name('kabupaten');
    Route::get('/kecamatan', [AdminController::class, 'kecamatan'])->name('kecamatan');
    Route::get('/kelurahan', [AdminController::class, 'kelurahan'])->name('kelurahan');
    Route::get('/tps', [AdminController::class, 'tps'])->name('tps');
    Route::get('/user', [AdminController::class, 'user'])->name('user');
    Route::post('/storeUser', [AdminController::class, 'storeUser'])->name('storeUser');
    Route::put('/updateUser/{id}', [AdminController::class, 'updateUser'])->name('updateUser');
    Route::delete('/deleteUser/{id}', [AdminController::class, 'deleteUser'])->name('deleteUser');
     Route::post('/forceLogout/{id}', [AdminController::class, 'forceLogout'])->name('forceLogout');
    Route::post('/reactivateUser/{id}', [AdminController::class, 'reactivateUser'])->name('reactivateUser');
Route::post('/forceLogoutDevice/{userId}/{loginHistoryId}', [AdminController::class, 'forceLogoutDevice'])->name('forceLogoutDevice');



});

// Middleware untuk operator
Route::middleware(['auth', 'role:operator'])->group(function () {
    Route::get('/operator/dashboard', [OperatorController::class, 'dashboard'])->name('operator.dashboard');
});
