<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DataAppController;
use App\Http\Controllers\AbsensiController;
use App\Models\Karyawan;

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->name('login.post')->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

/*
|--------------------------------------------------------------------------
| Protected Routes (Require Authentication)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/', function () {
        $totalKaryawan = Karyawan::count();
        $totalLakiLaki = Karyawan::where('jenisKelamin', 'Laki-laki')->count();
        $totalPerempuan = Karyawan::where('jenisKelamin', 'Perempuan')->count();
        $pegawaiTetap = Karyawan::where('statusPegawai', 'Tetap')->count();
        $pegawaiPKWT = Karyawan::where('statusPegawai', 'PKWT')->count();
        $pegawaiKontrak = Karyawan::where('statusPegawai', 'Kontrak')->count();

        $pageTitle = 'Dashboard';
        $breadcrumbs = [
            ['title' => 'Dashboard']
        ];

        return view('dashboard', compact(
            'totalKaryawan',
            'totalLakiLaki',
            'totalPerempuan',
            'pegawaiTetap',
            'pegawaiPKWT',
            'pegawaiKontrak',
            'pageTitle',
            'breadcrumbs'
        ));
    })->name('dashboard');

    // Karyawan Routes
    Route::prefix('karyawan')->name('karyawan.')->group(function () {
        Route::get('/', [KaryawanController::class, 'index'])->name('index');
        Route::get('/getData', [KaryawanController::class, 'getData'])->name('getData');
        Route::post('/', [KaryawanController::class, 'store'])->name('store');
        Route::delete('/delete/all', [KaryawanController::class, 'deleteAll'])->name('delete-all');
        Route::get('/export/excel', [KaryawanController::class, 'export'])->name('export');
        Route::post('/import/excel', [KaryawanController::class, 'import'])->name('import');
        Route::get('/download/template', [KaryawanController::class, 'downloadTemplate'])->name('download-template');
        // Routes dengan parameter harus di bawah
        Route::get('/{id}', [KaryawanController::class, 'show'])->name('show');
        Route::put('/{id}', [KaryawanController::class, 'update'])->name('update');
        Route::delete('/{id}', [KaryawanController::class, 'destroy'])->name('destroy');
    });

    // User Management Routes
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/data', [UserController::class, 'getData'])->name('data');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::get('/{id}', [UserController::class, 'show'])->name('show');
        Route::put('/{id}', [UserController::class, 'update'])->name('update');
        Route::delete('/{id}', [UserController::class, 'destroy'])->name('destroy');
    });

    // Settings Routes
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/data-app', [DataAppController::class, 'index'])->name('data-app.index');
        Route::put('/data-app', [DataAppController::class, 'update'])->name('data-app.update');
        Route::post('/data-app/remove-logo', [DataAppController::class, 'removeLogo'])->name('data-app.remove-logo');
        Route::post('/data-app/remove-favicon', [DataAppController::class, 'removeFavicon'])->name('data-app.remove-favicon');
    });

    // Absensi Monitoring Routes
    Route::prefix('absensi')->name('absensi.')->group(function () {
        // Halaman utama monitoring absensi harian
        Route::get('/', [AbsensiController::class, 'index'])->name('index');
        Route::post('/getData', [AbsensiController::class, 'getDataHarian'])->name('getData');
        
        // Halaman lembur
        Route::get('/lembur', [AbsensiController::class, 'lembur'])->name('lembur');
        Route::post('/getDataLembur', [AbsensiController::class, 'getDataLembur'])->name('getDataLembur');
        
        // Halaman rekapitulasi
        Route::get('/rekapitulasi', [AbsensiController::class, 'rekapitulasi'])->name('rekapitulasi');
        Route::get('/rekapitulasi/data', [AbsensiController::class, 'getRekapitulasiUser'])->name('rekapitulasi.data');
        Route::get('/bulanan', [AbsensiController::class, 'getAbsenBulanan'])->name('bulanan');
        
        // Get status list
        Route::get('/status-list', [AbsensiController::class, 'getStatusList'])->name('status-list');
        
        // Input/Update/Delete Absen Manual
        Route::post('/input', [AbsensiController::class, 'inputAbsen'])->name('input');
        Route::post('/update', [AbsensiController::class, 'updateAbsen'])->name('update');
        Route::post('/delete', [AbsensiController::class, 'deleteAbsen'])->name('delete');
        
        // Input/Update Lembur
        Route::post('/input-lembur', [AbsensiController::class, 'inputLembur'])->name('inputLembur');
        Route::post('/update-lembur', [AbsensiController::class, 'updateLembur'])->name('updateLembur');
        
        // Export data
        Route::get('/export', [AbsensiController::class, 'exportData'])->name('export');
    });

});
