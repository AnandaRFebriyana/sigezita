<?php

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BalitaImportController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BalitaController;
use App\Http\Controllers\PengukuranController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\Admin\PosyanduController;
use App\Http\Controllers\Admin\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('landing');
});

Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Authenticated Routes
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Data Balita
    Route::get('/balita/import/template', [BalitaImportController::class, 'template'])->name('balita.import.template');
    Route::post('/balita/import/preview',  [BalitaImportController::class, 'preview'])->name('balita.import.preview');
    Route::post('/balita/import/store',    [BalitaImportController::class, 'store'])->name('balita.import.store');
    Route::resource('balita', BalitaController::class)->parameters(['balita' => 'balita']);

    // Data Pengukuran
    Route::resource('pengukuran', PengukuranController::class);
    Route::get('/pengukuran/{pengukuran}/cetak', [PengukuranController::class, 'cetak'])->name('pengukuran.cetak');

    // Laporan
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::post('/laporan/generate', [LaporanController::class, 'generate'])->name('laporan.generate');
    Route::post('/laporan/export-excel', [LaporanController::class, 'exportExcel'])->name('laporan.export-excel');

    // Admin Only Routes
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {

        // Manajemen Posyandu
        Route::resource('posyandu', PosyanduController::class);

        // Manajemen User/Petugas
        Route::resource('users', UserController::class);
    });
});