<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CalonController;
use App\Http\Controllers\Admin\ProvinsiController;
use App\Http\Controllers\Admin\KabupatenController;
use App\Http\Controllers\Admin\KecamatanController;
use App\Http\Controllers\Admin\KelurahanController;
use App\Http\Controllers\Admin\TPSController;
use App\Http\Controllers\Admin\RangkumanController;
use App\Http\Controllers\Operator\PilbupController;
use App\Http\Controllers\OperatorController;
use App\Http\Controllers\Operator\PilgubController;
use App\Http\Controllers\Operator\PilwaliController;
use App\Http\Controllers\Operator\ResumeController;

Route::get('/', [LoginController::class, 'index'])->name('index');

Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('submitLogin'); // Route untuk proses login
Route::post('/logout', [LoginController::class, 'logout'])->name('logout'); // Route untuk logout

Route::middleware(['auth', 'checkForcedLogout'])->group(function () {
    // Middleware untuk admin
    Route::middleware(['auth', 'role:admin'])->group(function () {
        Route::get('/Dashboard', [AdminController::class, 'Dashboard'])->name('Dashboard');
        Route::get('/rangkuman', [RangkumanController::class, 'rangkuman'])->name('rangkuman');
        Route::get('/api/kecamatan/{kabupatenId}', [RangkumanController::class, 'getKecamatan']);
        Route::get('/api/kelurahan/{kecamatanId}', [RangkumanController::class, 'getKelurahan']);
        Route::get('admin/rangkuman/export', [RangkumanController::class, 'export'])->name('admin.rangkuman.export');
        Route::get('/suara', [RangkumanController::class, 'suara'])->name('suara');
        
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
        Route::post('kecamatan/impor', [KecamatanController::class, 'import'])->name('kecamatan.import');
        Route::resource('kecamatan', KecamatanController::class)->names([
            'index' => 'kecamatan'
        ]);
        
        Route::get('kelurahan/ekspor', [KelurahanController::class, 'export'])->name('kelurahan.export');
        Route::post('kelurahan/impor', [KelurahanController::class, 'import'])->name('kelurahan.import');
        Route::resource('kelurahan', KelurahanController::class)->names([
            'index' => 'kelurahan'
        ]);
        
        Route::get('tps/ekspor', [TPSController::class, 'export'])->name('tps.export');
        Route::post('tps/impor', [TPSController::class, 'import'])->name('tps.import');
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
        Route::get('/admin/resume', [RangkumanController::class, 'resume'])->name('admin.resume');
        Route::get('/admin/pilgub', [RangkumanController::class, 'pilgub'])->name('admin.input-suara.pilgub');
        Route::get('/admin/pilwali', [RangkumanController::class, 'pilwali'])->name('admin.input-suara.pilwali');
        Route::get('/admin/pilbup', [RangkumanController::class, 'pilbub'])->name('admin.input-suara.pilbup');
        Route::get('/admin/resume/{wilayah?}', [RangkumanController::class, 'resume'])->name('admin.resume');
    });

    // Middleware untuk operator
    Route::middleware(['auth', 'role:operator'])->group(function () {
        Route::get('/operator/dashboard', [OperatorController::class, 'dashboard'])->name('operator.dashboard');
        Route::get('/operator/resume', ResumeController::class)->name('operator.resume');

        Route::get('/operator/pilgub', [PilgubController::class, 'index'])->name('operator.input-suara.pilgub');
        Route::get('/operator/pilwali', [PilwaliController::class, 'index'])->name('operator.input-suara.pilwali');
        Route::get('/operator/pilbup', [PilbupController::class, 'index'])->name('operator.input-suara.pilbup');

        Route::post('/updateoperator', [OperatorController::class, 'updateoperator'])->name('updateoperator');
    });
});