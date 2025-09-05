
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Magang\ProfilPesertaController;
use App\Http\Controllers\Magang\DataMagangController;
use App\Http\Controllers\Magang\LaporanKegiatanController;
use App\Http\Controllers\Magang\LogBimbinganController;
use App\Http\Controllers\Magang\PenilaianAkhirController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('welcome');
});
// User Management
Route::get('/user', [UserController::class, 'index'])->name('user.index');
Route::get('/user/create', [UserController::class, 'create'])->name('user.create');
Route::post('/user', [UserController::class, 'store'])->name('user.store');
Route::get('/user/{user}/edit', [UserController::class, 'edit'])->name('user.edit');
Route::put('/user/{user}', [UserController::class, 'update'])->name('user.update');
Route::delete('/user/{user}', [UserController::class, 'destroy'])->name('user.destroy');

// Profil Peserta
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
Route::get('/magang/laporan/{id}/edit', [LaporanKegiatanController::class, 'edit'])->name('laporan.edit');
Route::put('/magang/laporan/{id}', [LaporanKegiatanController::class, 'update'])->name('laporan.update');
Route::delete('/magang/laporan/{id}', [LaporanKegiatanController::class, 'destroy'])->name('laporan.destroy');

// Log Bimbingan
Route::get('/magang/{magangId}/bimbingan', [LogBimbinganController::class, 'index'])->name('bimbingan.index');
Route::get('/magang/{magangId}/bimbingan/create', [LogBimbinganController::class, 'create'])->name('bimbingan.create');
Route::post('/magang/{magangId}/bimbingan', [LogBimbinganController::class, 'store'])->name('bimbingan.store');
Route::get('/magang/{magangId}/bimbingan/{id}/edit', [LogBimbinganController::class, 'edit'])->name('bimbingan.edit');
Route::put('/magang/{magangId}/bimbingan/{id}', [LogBimbinganController::class, 'update'])->name('bimbingan.update');
Route::delete('/magang/{magangId}/bimbingan/{id}', [LogBimbinganController::class, 'destroy'])->name('bimbingan.destroy');

// Penilaian Akhir
Route::get('/magang/{magangId}/penilaian', [PenilaianAkhirController::class, 'index'])->name('penilaian.index');
Route::get('/magang/{magangId}/penilaian/create', [PenilaianAkhirController::class, 'create'])->name('penilaian.create');
Route::post('/magang/{magangId}/penilaian', [PenilaianAkhirController::class, 'store'])->name('penilaian.store');
Route::get('/magang/{magangId}/penilaian/{id}/edit', [PenilaianAkhirController::class, 'edit'])->name('penilaian.edit');
Route::put('/magang/{magangId}/penilaian/{id}', [PenilaianAkhirController::class, 'update'])->name('penilaian.update');
Route::delete('/magang/{magangId}/penilaian/{id}', [PenilaianAkhirController::class, 'destroy'])->name('penilaian.destroy');
