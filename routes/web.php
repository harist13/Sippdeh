<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CalonController;
use App\Http\Controllers\OperatorController;
use App\Http\Controllers\ProvinsiController;
use App\Http\Controllers\KabupatenController;
use App\Http\Controllers\KecamatanController;
use App\Http\Controllers\KelurahanController;
use App\Http\Controllers\PaslonController;
use App\Http\Controllers\TPSController;
use App\Http\Controllers\InputPaslonController;
use App\Http\Controllers\InputTpsController;
use App\Http\Controllers\PilgubController;
use App\Http\Controllers\PilkadaController;

Route::get('/', [LoginController::class, 'index'])->name('index');

Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('submitLogin'); // Route untuk proses login
Route::post('/logout', [LoginController::class, 'logout'])->name('logout'); // Route untuk logout

Route::middleware(['auth', 'checkForcedLogout'])->group(function () {
// Middleware untuk admin
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/Dashboard', [AdminController::class, 'Dashboard'])->name('Dashboard');
    Route::get('/rangkuman', [AdminController::class, 'rangkuman'])->name('rangkuman');
    
    Route::get('provinsi/ekspor', [ProvinsiController::class, 'export'])->name('provinsi.export');
    Route::post('provinsi/impor', [ProvinsiController::class, 'import'])->name('provinsi.import');
    Route::resource('provinsi', ProvinsiController::class)->names([
        'index' => 'provinsi'
    ]);

    Route::get('kabupaten/ekspor', [KabupatenController::class, 'export'])->name('kabupaten.export');
    Route::post('kabupaten/impor', [KabupatenController::class, 'import'])->name('kabupaten.import');
    Route::resource('kabupaten', KabupatenController::class)->names([
        'index' => 'kabupaten'
    ]);

    Route::get('kecamatan/ekspor', [KecamatanController::class, 'export'])->name('kecamatan.export');
    Route::get('kecamatan/impor', [KecamatanController::class, 'impor'])->name('kecamatan.import');
    Route::resource('kecamatan', KecamatanController::class)->names([
        'index' => 'kecamatan'
    ]);

    Route::resource('kelurahan', KelurahanController::class)->names([
        'index' => 'kelurahan'
    ]);

    Route::resource('tps', TPSController::class)->names([
        'index' => 'tps'
    ]);

    Route::resource('calon', CalonController::class)->names([
        'index' => 'calon'
    ]);
    Route::delete('calon/{id}/gambar', [CalonController::class, 'destroyGambar'])->name('calon.destroy-gambar');

    Route::get('/user', [AdminController::class, 'user'])->name('user');
    Route::post('/storeUser', [AdminController::class, 'storeUser'])->name('storeUser');
    Route::put('/updateUser/{id}', [AdminController::class, 'updateUser'])->name('updateUser');
    Route::delete('/deleteUser/{id}', [AdminController::class, 'deleteUser'])->name('deleteUser');
     Route::post('/forceLogout/{id}', [AdminController::class, 'forceLogout'])->name('forceLogout');
    Route::post('/reactivateUser/{id}', [AdminController::class, 'reactivateUser'])->name('reactivateUser');
Route::post('/forceLogoutDevice/{userId}/{loginHistoryId}', [AdminController::class, 'forceLogoutDevice'])->name('forceLogoutDevice');
Route::post('/updateProfile', [AdminController::class, 'updateProfile'])->name('updateProfile');


});

// Middleware untuk operator
Route::middleware(['auth', 'role:operator'])->group(function () {
    Route::get('/operator/dashboard', [OperatorController::class, 'dashboard'])->name('operator.dashboard');
    Route::get('/operator/pilkada', [PilkadaController::class, 'index'])->name('operator.pilkada');
    Route::get('/operator/pilgub', [PilgubController::class, 'index'])->name('operator.pilgub');
    Route::post('/updateoperator', [OperatorController::class, 'updateoperator'])->name('updateoperator');

});
});

