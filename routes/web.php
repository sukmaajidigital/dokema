
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Magang\ProfilPesertaController;
use App\Http\Controllers\Magang\DataMagangController;
use App\Http\Controllers\Magang\LaporanKegiatanController;
use App\Http\Controllers\Magang\LogBimbinganController;
use App\Http\Controllers\Magang\PenilaianAkhirController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Magang\WorkflowMagangController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\SettingsController;

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// Waiting for Approval Page (accessible without full auth)
Route::get('/waiting-approval', [AuthController::class, 'showWaitingApproval'])->name('waiting-approval');

// Protected Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

    // Workflow Management (STRICT: HR ONLY) - Issue #2
    Route::middleware(['role:hr'])->group(function () {
        Route::get('/workflow/approval', [WorkflowMagangController::class, 'index'])->name('workflow.approval');
        Route::post('/workflow/process/{magangId}', [WorkflowMagangController::class, 'processApplication'])->name('workflow.process');
    });

    // Settings Management (untuk HR)
    Route::middleware(['role:hr'])->group(function () {
        Route::get('/admin/settings', [SettingsController::class, 'index'])->name('settings.index');
        Route::post('/admin/settings', [SettingsController::class, 'update'])->name('settings.update');
    });

    // User Management
    Route::get('/user', [UserController::class, 'index'])->name('user.index');
    Route::get('/user/create', [UserController::class, 'create'])->name('user.create');
    Route::post('/user', [UserController::class, 'store'])->name('user.store');
    Route::get('/user/{user}/edit', [UserController::class, 'edit'])->name('user.edit');
    Route::put('/user/{user}', [UserController::class, 'update'])->name('user.update');
    Route::delete('/user/{user}', [UserController::class, 'destroy'])->name('user.destroy');

    // My Profile - Personal profile for logged-in user (all roles)
    Route::get('/my-profile', [ProfilPesertaController::class, 'myProfile'])->name('profil.my-profile');

    // Profil Peserta Management (HR & Pembimbing only)
    Route::get('/profil', [ProfilPesertaController::class, 'index'])->name('profil.index');
    Route::get('/profil/create', [ProfilPesertaController::class, 'create'])->name('profil.create');
    Route::post('/profil', [ProfilPesertaController::class, 'store'])->name('profil.store');
    Route::get('/profil/{id}/edit', [ProfilPesertaController::class, 'edit'])->name('profil.edit');
    Route::put('/profil/{id}', [ProfilPesertaController::class, 'update'])->name('profil.update');
    Route::delete('/profil/{id}', [ProfilPesertaController::class, 'destroy'])->name('profil.destroy');

    // Data Magang
    Route::get('/magang', [DataMagangController::class, 'index'])->name('magang.index');
    Route::get('/magang/create', [DataMagangController::class, 'create'])->name('magang.create');
    Route::post('/magang', [DataMagangController::class, 'store'])->name('magang.store');
    Route::get('/magang/{id}/edit', [DataMagangController::class, 'edit'])->name('magang.edit');
    Route::put('/magang/{id}', [DataMagangController::class, 'update'])->name('magang.update');
    Route::delete('/magang/{id}', [DataMagangController::class, 'destroy'])->name('magang.destroy');

    // Laporan Kegiatan
    Route::get('/magang/laporan', [LaporanKegiatanController::class, 'index'])->name('laporan.index');
    Route::get('/magang/laporan/create', [LaporanKegiatanController::class, 'create'])->name('laporan.create');
    Route::post('/magang/laporan', [LaporanKegiatanController::class, 'store'])->name('laporan.store');
    Route::middleware(['ownership'])->group(function () {
        Route::get('/magang/laporan/{id}/edit', [LaporanKegiatanController::class, 'edit'])->name('laporan.edit');
        Route::put('/magang/laporan/{id}', [LaporanKegiatanController::class, 'update'])->name('laporan.update');
        Route::delete('/magang/laporan/{id}', [LaporanKegiatanController::class, 'destroy'])->name('laporan.destroy');
    });

    // Report Approval Routes (Issue #6) - PEMBIMBING ONLY
    Route::middleware(['role:pembimbing'])->group(function () {
        Route::post('/laporan/{id}/approve', [LaporanKegiatanController::class, 'approve'])->name('laporan.approve');
        Route::post('/laporan/{id}/reject', [LaporanKegiatanController::class, 'reject'])->name('laporan.reject');
    });

    // Log Bimbingan - Flat routes (filter di controller berdasarkan role)
    Route::get('/bimbingan', [LogBimbinganController::class, 'index'])->name('bimbingan.index');
    Route::get('/bimbingan/create', [LogBimbinganController::class, 'create'])->name('bimbingan.create');
    Route::post('/bimbingan', [LogBimbinganController::class, 'store'])->name('bimbingan.store');
    Route::get('/bimbingan/{id}/edit', [LogBimbinganController::class, 'edit'])->name('bimbingan.edit');
    Route::put('/bimbingan/{id}', [LogBimbinganController::class, 'update'])->name('bimbingan.update');
    Route::delete('/bimbingan/{id}', [LogBimbinganController::class, 'destroy'])->name('bimbingan.destroy');

    // Penilaian Akhir - Accessible by all roles (filter di controller)
    // Magang: lihat penilaian sendiri (read-only)
    // Pembimbing: lihat & buat penilaian untuk peserta yang dibimbing
    // HR: lihat semua penilaian
    Route::get('/penilaian', [PenilaianAkhirController::class, 'index'])->name('penilaian.index');
    Route::get('/penilaian/{id}', [PenilaianAkhirController::class, 'show'])->name('penilaian.show');

    // Create/Edit/Delete only for Pembimbing & HR
    Route::middleware(['role:pembimbing,hr'])->group(function () {
        Route::get('/penilaian/create', [PenilaianAkhirController::class, 'create'])->name('penilaian.create');
        Route::post('/penilaian', [PenilaianAkhirController::class, 'store'])->name('penilaian.store');
        Route::get('/penilaian/{id}/edit', [PenilaianAkhirController::class, 'edit'])->name('penilaian.edit');
        Route::put('/penilaian/{id}', [PenilaianAkhirController::class, 'update'])->name('penilaian.update');
        Route::delete('/penilaian/{id}', [PenilaianAkhirController::class, 'destroy'])->name('penilaian.destroy');
    });
});
