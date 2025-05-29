<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Dosen\DosenController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Admin\SuratController as AdminSuratController;
use App\Http\Controllers\Panitia\PanitiaController;
use App\Http\Controllers\LogsController;
use App\Http\Controllers\Mahasiswa\MahasiswaController;
use App\Http\Controllers\Mahasiswa\PengajuanController;
use App\Http\Controllers\Mahasiswa\KelompokController;
use App\Http\Controllers\Mahasiswa\BerkasController;
use App\Http\Controllers\Mahasiswa\SuratController as MahasiswaSuratController;
use App\Http\Controllers\Panitia\PanitiaPengajuanController;
use App\Http\Controllers\Panitia\BerkasController as PanitiaBerkasController;
use App\Http\Controllers\Panitia\PanitiaJadwalController;
use App\Exports\TemplateUserExport;
use App\Exports\TemplateJadwalExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Dosen\ProfileController;
use App\Http\Controllers\Dosen\ValidasiPengajuanController;
use App\Http\Controllers\Panitia\DosenKuotaController;
use App\Http\Controllers\Dosen\BimbinganController;

// Halaman login
Route::get('/', function () {
    return view('pages.auth.auth-login', ['type_menu' => '']);
})->name('auth.login');

Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Optional auth route
Route::get('password/request', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [RegisterController::class, 'register']);

// Setelah login
Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', function () {
        return 'Dashboard untuk semua pengguna';
    })->name('dashboard');

    // ADMIN
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

        // Pengelolaan Surat Penelitian
        Route::get('/admin/surat', [AdminSuratController::class, 'index'])->name('admin.surat.index');
        Route::get('/admin/surat/{id}/edit', [AdminSuratController::class, 'edit'])->name('admin.surat.edit');
        Route::put('/admin/surat/{id}/update', [AdminSuratController::class, 'update'])->name('admin.surat.update');
        Route::get('/admin/surat/download/{id}', [AdminSuratController::class, 'download'])->name('admin.surat.download');
    });

    // PANITIA
    Route::middleware(['role:panitia'])->prefix('panitia')->group(function () {
        Route::get('/dashboard', [PanitiaController::class, 'index'])->name('panitia.dashboard');

        // Pengajuan
        Route::get('/pengajuan', [PanitiaPengajuanController::class, 'index'])->name('panitia.pengajuan.index');
        Route::get('/pengajuan/{id}/edit', [PanitiaPengajuanController::class, 'edit'])->name('panitia.pengajuan.edit');
        Route::put('/pengajuan/{id}', [PanitiaPengajuanController::class, 'update'])->name('panitia.pengajuan.update');

        // Berkas
        Route::get('/berkas', [PanitiaBerkasController::class, 'index'])->name('pages.panitia.berkas.index');
        Route::get('/berkas/create', [PanitiaBerkasController::class, 'create'])->name('pages.panitia.berkas.create');
        Route::post('/berkas', [PanitiaBerkasController::class, 'store'])->name('panitia.berkas.store');
        Route::get('/berkas/{id}/edit', [PanitiaBerkasController::class, 'edit'])->name('pages.panitia.berkas.edit');
        Route::put('/berkas/{id}', [PanitiaBerkasController::class, 'update'])->name('panitia.berkas.update');
        Route::delete('/berkas/{id}', [PanitiaBerkasController::class, 'destroy'])->name('panitia.berkas.destroy');

        // JADWAL
    Route::prefix('jadwal')->name('jadwal.')->group(function () {
        Route::get('/', [PanitiaJadwalController::class, 'index'])->name('index');

        Route::get('/seminar', [PanitiaJadwalController::class, 'seminar'])->name('seminar.index');
        Route::get('/sidang', [PanitiaJadwalController::class, 'sidang'])->name('sidang.index');
        Route::get('/yudisium', [PanitiaJadwalController::class, 'yudisium'])->name('yudisium.index');

        // Tambah
        Route::get('/create', [PanitiaJadwalController::class, 'create'])->name('create'); // gunakan ?jenis=seminar
        Route::post('/', [PanitiaJadwalController::class, 'store'])->name('store');

        // Edit dan Hapus dinamis
        Route::get('/{id}/edit', [PanitiaJadwalController::class, 'edit'])->name('edit');
        Route::put('/{id}', [PanitiaJadwalController::class, 'update'])->name('update');
        Route::delete('/{id}', [PanitiaJadwalController::class, 'destroy'])->name('destroy');

        // Import
        Route::get('/import', [PanitiaJadwalController::class, 'importForm'])->name('import.form');
        Route::post('/import', [PanitiaJadwalController::class, 'importJadwal'])->name('import');

        // Template
        Route::get('/template/download', function (Request $request) {
            $jenis = $request->get('jenis', 'seminar');
            $filename = 'template_jadwal_' . $jenis . '.xlsx';
            return Excel::download(new TemplateJadwalExport, $filename);
        })->name('template');
    });

        // Kuota Dosen Management
        Route::get('/kuota-dosen', [DosenKuotaController::class, 'index'])->name('panitia.kuota.index');
        Route::post('/kuota-dosen/{id}', [DosenKuotaController::class, 'update'])->name('panitia.kuota.update');
    });

    // DOSEN
    Route::middleware(['role:dosen'])->prefix('dosen')->group(function () {
        Route::get('/dosen/dashboard', [DosenController::class, 'index'])->name('dosen.dashboard');
        Route::get('/dosen/profile', [ProfileController::class, 'index'])->name('dosen.profile');
        Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('dosen.profile_edit');
        Route::post('/dosen/profile', [ProfileController::class, 'update'])->name('dosen.profile.update');

        // validasi pengajuan pembimbing 1
        Route::get('/validasi-pengajuan', [\App\Http\Controllers\Dosen\ValidasiPengajuanController::class, 'index'])->name('dosen.validasi');
        Route::post('/validasi-pengajuan/{id}', [\App\Http\Controllers\Dosen\ValidasiPengajuanController::class, 'validasi'])->name('dosen.validasi.submit');

        // bimbingan mahasiswa
        Route::get('/bimbingan', [DosenController::class, 'bimbingan'])->name('dosen.bimbingan');

    });

    // MAHASISWA
    Route::middleware(['role:mahasiswa'])->prefix('mahasiswa')->group(function () {
        Route::get('/mahasiswa/dashboard', [MahasiswaController::class, 'index'])->name('mahasiswa.dashboard');
        Route::get('/mahasiswa/profile', [MahasiswaController::class, 'profile'])->name('mahasiswa.profile');
        Route::post('/mahasiswa/profile/update', [MahasiswaController::class, 'updateProfile'])->name('mahasiswa.profile.update');
        Route::get('/profile/edit', [MahasiswaController::class, 'editProfile'])->name('mahasiswa.profile_edit');


        // Berkas
        Route::get('/mahasiswa/berkas', [BerkasController::class, 'index'])->name('mahasiswa.berkas.index');
        Route::get('/mahasiswa/berkas/create', [BerkasController::class, 'create'])->name('mahasiswa.berkas.create');
        Route::post('/mahasiswa/berkas', [BerkasController::class, 'store'])->name('mahasiswa.berkas.store');
        Route::get('/mahasiswa/berkas/{id}/edit', [BerkasController::class, 'edit'])->name('mahasiswa.berkas.edit');
        Route::put('/mahasiswa/berkas/{id}', [BerkasController::class, 'update'])->name('mahasiswa.berkas.update');
        Route::delete('/mahasiswa/berkas/{id}', [BerkasController::class, 'destroy'])->name('mahasiswa.berkas.destroy');
        Route::get('/mahasiswa/berkas/download/{id}', [BerkasController::class, 'download'])->name('mahasiswa.berkas.download');

        // Pengajuan
        Route::get('/pengajuan', [PengajuanController::class, 'index'])->name('pengajuan.index');
        Route::get('/pengajuan/create', [PengajuanController::class, 'create'])->name('pengajuan.create');
        Route::post('/pengajuan', [PengajuanController::class, 'store'])->name('pengajuan.store');

        // Kelompok
        Route::get('/kelompok', [KelompokController::class, 'index'])->name('kelompok.index');
        Route::get('/kelompok/create', [KelompokController::class, 'create'])->name('kelompok.create');
        Route::post('/kelompok', [KelompokController::class, 'store'])->name('kelompok.store');
        Route::get('/kelompok/fetch-nama', [KelompokController::class, 'fetchNama'])->name('mahasiswa.fetchNama');

        // Surat Penelitian Mahasiswa
        Route::get('/mahasiswa/surat', [MahasiswaSuratController::class, 'index'])->name('mahasiswa.surat.index');
        Route::get('/mahasiswa/surat/create', [MahasiswaSuratController::class, 'create'])->name('mahasiswa.surat.create');
        Route::post('/mahasiswa/surat', [MahasiswaSuratController::class, 'store'])->name('mahasiswa.surat.store');
        Route::get('/mahasiswa/surat/download/{id}', [MahasiswaSuratController::class, 'download'])->name('mahasiswa.surat.download');

        // jadwal mahasiswa
        Route::get('/jadwal', [MahasiswaController::class, 'jadwal'])->name('mahasiswa.jadwal.index');

    });

    // Template user
    Route::get('/template-user', function () {
        return Excel::download(new TemplateUserExport, 'template_user.xlsx');
    })->name('template.user');
});
