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
use App\Http\Controllers\Mahasiswa\UndanganController;
use App\Http\Controllers\Mahasiswa\MahasiswaSemproController;
use App\Http\Controllers\Mahasiswa\MahasiswaSidangController;
use App\Http\Controllers\Panitia\PanitiaPengajuanController;
use App\Http\Controllers\Panitia\BerkasController as PanitiaBerkasController;
use App\Http\Controllers\Panitia\PanitiaJadwalController;
use App\Http\Controllers\Panitia\PanitiaSemproController;
use App\Http\Controllers\Panitia\PanitiaSidangController;
use App\Http\Controllers\Panitia\PanitiaDashboardController;
use App\Exports\TemplateUserExport;
use App\Exports\TemplateJadwalExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Dosen\ProfileController;
use App\Http\Controllers\Dosen\ValidasiPengajuanController;
use App\Http\Controllers\Panitia\DosenKuotaController;
use App\Http\Controllers\Dosen\BimbinganController;
use App\Http\Controllers\Dosen\DosenSemproController;
use App\Http\Controllers\Pimpinan\PimpinanPengajuanController;
use App\Http\Controllers\Pimpinan\PimpinanKuotaController;
use App\Http\Controllers\Pimpinan\PimpinanJadwalController;
use App\Http\Controllers\Pimpinan\PimpinanSuratController;
use App\Http\Controllers\Pimpinan\PimpinanController;

// Halaman login
Route::get('/', function () {
    return view('pages.auth.auth-login', ['type_menu' => '']);
})->name('auth.login');

Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// // Optional auth route
// Route::get('password/request', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
// Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
// Route::post('register', [RegisterController::class, 'register']);

// Setelah login
Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', function () {
        return 'Dashboard untuk semua pengguna';
    })->name('dashboard');

// ADMIN
Route::prefix('admin')->middleware(['role:admin'])->group(function () {

    // Dashboard & Profile
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/profile', [AdminController::class, 'profile'])->name('admin.profile');
    Route::get('/profile/edit', [AdminController::class, 'editProfile'])->name('admin.profile_edit');
    Route::post('/profile/update', [AdminController::class, 'updateProfile'])->name('admin.profile.update');

    // User Management
    Route::get('/user', [UsersController::class, 'index'])->name('admin.users');
    Route::get('/create', [UsersController::class, 'create'])->name('admin.create');
    Route::post('/', [UsersController::class, 'store'])->name('admin.store');
    Route::get('/{id}/edit', [UsersController::class, 'edit'])->name('admin.edit');
    Route::put('/{id}', [UsersController::class, 'update'])->name('admin.update');
    Route::delete('/{id}', [UsersController::class, 'destroy'])->name('admin.destroy');
    Route::get('/import', [UsersController::class, 'importForm'])->name('admin.import');
    Route::post('/import', [UsersController::class, 'importStore'])->name('admin.import.store');

    // Pengelolaan Surat
    Route::get('/surat', [AdminSuratController::class, 'index'])->name('admin.surat.index');
    Route::get('/surat/{id}/edit', [AdminSuratController::class, 'edit'])->name('admin.surat.edit');
    Route::put('/surat/{id}/update', [AdminSuratController::class, 'update'])->name('admin.surat.update');
    Route::get('/surat/download/{id}', [AdminSuratController::class, 'download'])->name('admin.surat.download');
});


// PANITIA
Route::middleware(['role:panitia'])->prefix('panitia')->name('panitia.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [PanitiaController::class, 'index'])->name('dashboard');
    Route::get('/profile', [PanitiaController::class, 'profile'])->name('profile');
    Route::post('/profile/update', [PanitiaController::class, 'updateProfile'])->name('profile.update');
    Route::get('/profile/edit', [PanitiaController::class, 'editProfile'])->name('profile_edit');

    // Pengajuan
    Route::get('/pengajuan', [PanitiaPengajuanController::class, 'index'])->name('pengajuan.index');
    Route::get('/pengajuan/{id}/edit', [PanitiaPengajuanController::class, 'edit'])->name('pengajuan.edit');
    Route::put('/pengajuan/{id}', [PanitiaPengajuanController::class, 'update'])->name('pengajuan.update');
    Route::get('/pengajuan/export', [PanitiaPengajuanController::class, 'export'])->name('pengajuan.export');

    // Berkas
    Route::get('/berkas', [PanitiaBerkasController::class, 'index'])->name('berkas.index');
    Route::get('/berkas/create', [PanitiaBerkasController::class, 'create'])->name('berkas.create');
    Route::post('/berkas', [PanitiaBerkasController::class, 'store'])->name('berkas.store');
    Route::get('/berkas/{id}/edit', [PanitiaBerkasController::class, 'edit'])->name('berkas.edit');
    Route::put('/berkas/{id}', [PanitiaBerkasController::class, 'update'])->name('berkas.update');
    Route::delete('/berkas/{id}', [PanitiaBerkasController::class, 'destroy'])->name('berkas.destroy');
    Route::get('/berkas/{id}/download', [PanitiaBerkasController::class, 'download'])->name('berkas.download');

    // JADWAL
    Route::prefix('jadwal/yudisium')->name('jadwal.yudisium.')->group(function () {
        Route::get('/', [PanitiaJadwalController::class, 'indexYudisium'])->name('index');
        Route::post('/', [PanitiaJadwalController::class, 'storeYudisium'])->name('store');
        Route::get('/{id}/edit', [PanitiaJadwalController::class, 'editYudisium'])->name('edit');
        Route::put('/{id}', [PanitiaJadwalController::class, 'updateYudisium'])->name('update');
        Route::delete('/{id}', [PanitiaJadwalController::class, 'destroyYudisium'])->name('destroy');
    });

    // Route Seminar & Sidang (dinamis pakai jenis)
    Route::prefix('jadwal')->name('jadwal.')->group(function () {
        Route::get('/{jenis}', [PanitiaJadwalController::class, 'indexJenis'])->name('jenis.index');
        Route::get('/create/{jenis}', [PanitiaJadwalController::class, 'create'])->name('create');
        Route::post('/', [PanitiaJadwalController::class, 'store'])->name('store');
        Route::get('/edit/{jadwal}', [PanitiaJadwalController::class, 'edit'])->name('edit');
        Route::put('/{jadwal}', [PanitiaJadwalController::class, 'update'])->name('update');
        Route::delete('/{jadwal}', [PanitiaJadwalController::class, 'destroy'])->name('destroy');
        Route::get('/export/{jenis}', [PanitiaJadwalController::class, 'export'])->name('export');
        Route::get('/import/{jenis}', [PanitiaJadwalController::class, 'importForm'])->name('import.form');
        Route::post('/import', [PanitiaJadwalController::class, 'importJadwal'])->name('import');
    });

    // SEMPRO
    Route::get('/sempro/create', [PanitiaSemproController::class, 'create'])->name('sempro.create');
    Route::post('/sempro/store', [PanitiaSemproController::class, 'store'])->name('sempro.store');
    Route::get('/berkas-sempro', [PanitiaSemproController::class, 'index'])->name('sempro.index');

    // SIDANG
    Route::prefix('sidang')->name('sidang.')->group(function () {
        Route::get('/draft', [PanitiaSidangController::class, 'draft'])->name('draft');
        Route::get('/revisi', [PanitiaSidangController::class, 'revisi'])->name('revisi');
        Route::get('/final', [PanitiaSidangController::class, 'final'])->name('final');
        Route::post('/final/{id}/submit', [PanitiaSidangController::class, 'submitFinal'])->name('final.submit');
        Route::post('/final/upload', [MahasiswaSidangController::class, 'uploadFinal'])->name('mahasiswa.sidang.final.upload');
    });

    // KUOTA
    Route::get('/kuota-dosen', [DosenKuotaController::class, 'index'])->name('kuota.index');
    Route::post('/kuota-dosen/{id}', [DosenKuotaController::class, 'update'])->name('kuota.update');
});


    // DOSEN
    Route::middleware(['role:dosen'])->prefix('dosen')->group(function () {
        Route::get('/dosen/dashboard', [DosenController::class, 'index'])->name('dosen.dashboard');
        Route::get('/dosen/profile', [ProfileController::class, 'index'])->name('dosen.profile');
        Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('dosen.profile_edit');
        Route::post('/dosen/profile', [ProfileController::class, 'update'])->name('dosen.profile.update');

        // validasi pengajuan pembimbing 1
        Route::get('/validasi-pengajuan', [ValidasiPengajuanController::class, 'index'])->name('dosen.validasi');
        Route::post('/validasi-pengajuan/{id}', [ValidasiPengajuanController::class, 'validasi'])->name('dosen.validasi.submit');

        Route::get('sidang/verifikasi', [\App\Http\Controllers\Dosen\SidangController::class, 'index'])->name('dosen.sidang.index');
        Route::post('sidang/verifikasi/{id}', [\App\Http\Controllers\Dosen\SidangController::class, 'verifikasi'])->name('dosen.sidang.verifikasi');

        // bimbingan mahasiswa
        Route::get('/bimbingan', [DosenController::class, 'bimbingan'])->name('dosen.bimbingan');
Route::get('/mahasiswa/{id}', [\App\Http\Controllers\Dosen\MahasiswaController::class, 'show'])->name('dosen.mahasiswa.show');

        // PERSETUJUAN SEMPRO
        Route::get('sempro', [DosenSemproController::class, 'index'])->name('dosen.sempro.index');
        Route::post('sempro/submit/{id}', [DosenSemproController::class, 'submit'])->name('dosen.sempro.submit');

        // DRAFT
        Route::get('/draft', [App\Http\Controllers\Dosen\DosenSidangController::class, 'draft'])->name('dosen.sidang.draft');
        Route::post('/draft/{id_sidang}/update', [App\Http\Controllers\Dosen\DosenSidangController::class, 'updateStatusDraft'])->name('dosen.sidang.updateStatusDraft');

        // REVISI
        Route::get('/revisi', [App\Http\Controllers\Dosen\DosenSidangController::class, 'revisi'])->name('dosen.sidang.revisi');
        Route::post('/revisi/{id_sidang}/{penguji_ke}/update', [App\Http\Controllers\Dosen\DosenSidangController::class, 'updateStatusRevisi'])->name('dosen.sidang.updateStatusRevisi');
            });

    // MAHASISWA
    Route::middleware(['role:mahasiswa'])->prefix('mahasiswa')->group(function () {
        Route::get('/mahasiswa/dashboard', [MahasiswaController::class, 'index'])->name('mahasiswa.dashboard');
        Route::get('/mahasiswa/profile', [MahasiswaController::class, 'profile'])->name('mahasiswa.profile');
        Route::post('/mahasiswa/profile/update', [MahasiswaController::class, 'updateProfile'])->name('mahasiswa.profile.update');
        Route::get('/profile/edit', [MahasiswaController::class, 'editProfile'])->name('mahasiswa.profile_edit');
        Route::get('/mahasiswa/dosen/{id}', [MahasiswaController::class, 'showDosen'])->name('mahasiswa.dosen.show');

        // Draft

    Route::get('/draft', [MahasiswaSidangController::class, 'draft'])->name('mahasiswa.sidang.draft');
    Route::get('/draft/create', [MahasiswaSidangController::class, 'createDraft'])->name('mahasiswa.sidang.draft.create');
    Route::post('/upload-draft', [MahasiswaSidangController::class, 'uploadDraft'])->name('mahasiswa.sidang.uploadDraft');

        // REVISI
        Route::get('/revisi', [App\Http\Controllers\Mahasiswa\MahasiswaSidangController::class, 'revisi'])->name('mahasiswa.sidang.revisi');
        Route::get('/revisi/create', [App\Http\Controllers\Mahasiswa\MahasiswaSidangController::class, 'createRevisi'])->name('mahasiswa.sidang.revisi.create');
        Route::post('/upload-revisi', [App\Http\Controllers\Mahasiswa\MahasiswaSidangController::class, 'uploadRevisi'])->name('mahasiswa.sidang.uploadRevisi');

        // FINAL
        Route::get('/final', [MahasiswaSidangController::class, 'final'])->name('mahasiswa.sidang.final');
        Route::get('/final/create', [MahasiswaSidangController::class, 'createFinal'])->name('mahasiswa.sidang.final.create');
        Route::post('/final/upload', [MahasiswaSidangController::class, 'uploadFinal'])->name('mahasiswa.sidang.final.upload');
        Route::delete('/final/delete/{jenis}', [MahasiswaSidangController::class, 'deleteFinal'])->name('mahasiswa.sidang.final.delete');
        Route::get('/final/edit/{jenis}', [MahasiswaSidangController::class, 'editFinal'])->name('mahasiswa.sidang.final.edit');
        Route::post('/final/update/{jenis}', [MahasiswaSidangController::class, 'updateFinal'])->name('mahasiswa.sidang.final.update');

        Route::get('/jadwal', [MahasiswaController::class, 'jadwal'])->name('mahasiswa.jadwal.index');

        // JADWAL
        Route::get('/mahasiswa/jadwal/seminar', [MahasiswaController::class, 'jadwalSeminar'])->name('mahasiswa.jadwal.seminar');
        Route::get('/mahasiswa/jadwal/sidang', [MahasiswaController::class, 'jadwalSidang'])->name('mahasiswa.jadwal.sidang');

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



        // Upload undangan oleh mahasiswa
        Route::get('/undangan', [UndanganController::class, 'index'])->name('mahasiswa.undangan.index');
        Route::get('/undangan/create', [UndanganController::class, 'create'])->name('mahasiswa.undangan.create');
        Route::post('/undangan/store', [UndanganController::class, 'store'])->name('mahasiswa.undangan.store');

        // SEMPRO
        Route::get('sempro', [App\Http\Controllers\Mahasiswa\MahasiswaSemproController::class, 'index'])->name('mahasiswa.sempro.index');
        Route::post('sempro/upload-form', [App\Http\Controllers\Mahasiswa\MahasiswaSemproController::class, 'uploadForm'])->name('mahasiswa.sempro.uploadForm');
        Route::post('sempro/upload-hasil', [App\Http\Controllers\Mahasiswa\MahasiswaSemproController::class, 'uploadHasil'])->name('mahasiswa.sempro.uploadHasil');
        Route::post('sempro/upload-laporan-ta', [App\Http\Controllers\Mahasiswa\MahasiswaSemproController::class, 'uploadLaporanTa'])->name('mahasiswa.sempro.uploadLaporanTa');
        Route::delete('sempro/deleteForm', [App\Http\Controllers\Mahasiswa\MahasiswaSemproController::class, 'deleteForm'])->name('mahasiswa.sempro.deleteForm');
        Route::delete('sempro/deleteHasil', [App\Http\Controllers\Mahasiswa\MahasiswaSemproController::class, 'deleteHasil'])->name('mahasiswa.sempro.deleteHasil');
        Route::delete('sempro/delete-laporan-ta', [App\Http\Controllers\Mahasiswa\MahasiswaSemproController::class, 'deleteLaporanTa'])->name('mahasiswa.sempro.deleteLaporanTa');

    });

    // ROUTE PIMPINAN
Route::middleware(['auth', 'role:pimpinan'])->prefix('pimpinan')->name('pimpinan.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [PimpinanController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile', [PimpinanController::class, 'profile'])->name('profile');
    Route::get('/profile/edit', [PimpinanController::class, 'editProfile'])->name('profile_edit');
    Route::post('/profile/update', [PimpinanController::class, 'updateProfile'])->name('profile.update');

    // Pengajuan
    Route::get('/pengajuan', [PimpinanPengajuanController::class, 'index'])->name('pengajuan.index');

    // Kuota
    Route::get('/kuota', [PimpinanKuotaController::class, 'index'])->name('kuota.index');

    // Jadwal
    Route::get('/jadwal/seminar', [PimpinanJadwalController::class, 'seminar'])->name('jadwal.seminar');
    Route::get('/jadwal/sidang', [PimpinanJadwalController::class, 'sidang'])->name('jadwal.sidang');
    Route::get('/jadwal/yudisium', [PimpinanJadwalController::class, 'yudisium'])->name('jadwal.yudisium');

    // Surat
    Route::get('/surat', [PimpinanSuratController::class, 'index'])->name('surat.index');
});


    // Template user
    Route::get('/template-user', function () {
        return Excel::download(new TemplateUserExport, 'template_user.xlsx');
    })->name('template.user');

});
