<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Panitia\PanitiaController;
use App\Http\Controllers\LogsController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\Mahasiswa\MahasiswaController;
use App\Http\Controllers\Mahasiswa\PengajuanController;
use App\Http\Controllers\Mahasiswa\KelompokController;
use App\Http\Controllers\Panitia\PanitiaPengajuanController;
use App\Http\Controllers\Panitia\PanitiaJadwalController;



// Halaman login (GET)
Route::get('/', function () {
    return view('pages.auth.auth-login', ['type_menu' => '']);
})->name('auth.login');

// Login
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Password reset & register (akses sebelum login)
Route::get('password/request', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [RegisterController::class, 'register']);

// Setelah login
Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', function () {
        return 'Dashboard untuk semua pengguna';
    })->name('dashboard');

    Route::middleware(['role:admin'])->group(function () {
        Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
        Route::get('/user', [UsersController::class, 'index'])->name('admin.users');
        Route::get('/admin/create', [UsersController::class, 'create'])->name('admin.create');
        Route::post('/admin', [UsersController::class, 'store'])->name('admin.store');

        Route::get('/admin/{id}/edit', [UsersController::class, 'edit'])->name('admin.edit');
        Route::put('/admin/{id}', [UsersController::class, 'update'])->name('admin.update');
        Route::delete('/admin/{id}', [UsersController::class, 'destroy'])->name('admin.destroy');
        Route::get('/admin/import', [UsersController::class, 'importForm'])->name('admin.import');
        Route::post('/admin/import', [UsersController::class, 'importStore'])->name('admin.import.store');

    });

    Route::middleware(['role:panitia'])->group(function () {
        Route::get('/panitia/dashboard', [PanitiaController::class, 'index'])->name('panitia.dashboard');

        // Gunakan prefix URL panitia
        Route::get('/panitia/pengajuan', [PanitiaPengajuanController::class, 'index'])->name('panitia.pengajuan.index');
        Route::get('/panitia/pengajuan/{id}/edit', [PanitiaPengajuanController::class, 'edit'])->name('panitia.pengajuan.edit');
        Route::put('/panitia/pengajuan/{id}', [PanitiaPengajuanController::class, 'update'])->name('panitia.pengajuan.update');
    });


    Route::middleware(['role:dosen'])->group(function () {
        Route::get('/dosen/dashboard', [DosenController::class, 'index'])->name('dosen.dashboard');
    });

    Route::middleware(['role:mahasiswa'])->group(function () {
        Route::get('/mahasiswa/dashboard', [MahasiswaController::class, 'index'])->name('mahasiswa.dashboard');

        Route::get('/pengajuan', [PengajuanController::class, 'index'])->name('pengajuan.index');
        Route::get('/pengajuan/create', [PengajuanController::class, 'create'])->name('pengajuan.create');
        Route::post('/pengajuan', [PengajuanController::class, 'store'])->name('pengajuan.store');

        Route::get('/kelompok', [KelompokController::class, 'index'])->name('kelompok.index');
        Route::get('/kelompok/create', [KelompokController::class, 'create'])->name('kelompok.create');
        Route::post('/kelompok', [KelompokController::class, 'store'])->name('kelompok.store');
    });

    // Upload Excel Jadwal
    Route::post('/jadwal/import', [PanitiaJadwalController::class, 'importJadwal'])->name('jadwal.import');
    Route::get('/jadwal/import', [PanitiaJadwalController::class, 'importForm'])->name('jadwal.import.form');
    // Input satu-satu
    Route::get('/jadwal/create', [PanitiaJadwalController::class, 'create'])->name('jadwal.create');
    Route::post('/jadwal', [PanitiaJadwalController::class, 'store'])->name('jadwal.store');
    // Tampilkan semua jadwal
    Route::get('/jadwal', [PanitiaJadwalController::class, 'index'])->name('jadwal.index');
    // Tampilkan form upload
    Route::get('/jadwal/import', [PanitiaJadwalController::class, 'importView'])->name('jadwal.import.view');
    // Proses import
    Route::post('/jadwal/import', [PanitiaJadwalController::class, 'importJadwal'])->name('jadwal.import');
    // Tampilkan semua jadwal


});
