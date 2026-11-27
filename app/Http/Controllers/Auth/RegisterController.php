<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\GuruRegisterController;
use App\Http\Controllers\Auth\AdminRegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\GuruLoginController;
use App\Http\Controllers\Auth\SiswaLoginController;

// --------------------------------------------------------------------------
// Landing Page
// --------------------------------------------------------------------------
Route::get('/', function () {
    return view('home');
})->name('home');

// Logout (Wajib bisa diakses saat sudah login)
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// --------------------------------------------------------------------------
// Admin Register (Dikeluarkan dari guest agar bisa diakses kapan saja)
// --------------------------------------------------------------------------
Route::get('/admin/register', [AdminRegisterController::class, 'showRegisterForm'])->name('admin.register');
Route::post('/admin/register', [AdminRegisterController::class, 'register'])->name('admin.register.submit');

// Handler agar URL /register/admin otomatis dialihkan ke /admin/register
Route::get('/register/admin', function () {
    return redirect()->route('admin.register');
});

// --------------------------------------------------------------------------
// Auth Routes (Hanya untuk pengguna yang BELUM login)
// --------------------------------------------------------------------------
Route::middleware('guest')->group(function () {

    // Auth Admin
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [LoginController::class, 'login']);

    // Auth Guru
    Route::get('/login/guru', [GuruLoginController::class, 'showLoginForm'])->name('guru.login');
    Route::post('/login/guru', [GuruLoginController::class, 'login'])->name('guru.login.submit');

    Route::get('/register/guru', [GuruRegisterController::class, 'create'])->name('guru.register');
    Route::post('/register/guru', [GuruRegisterController::class, 'store'])->name('guru.register.store');

    // Auth Siswa
    Route::get('/login/siswa', [SiswaLoginController::class, 'showLoginForm'])->name('siswa.login');
    Route::post('/login/siswa', [SiswaLoginController::class, 'login'])->name('siswa.login.submit');

    Route::get('/register/siswa', [RegisterController::class, 'showSiswaRegisterForm'])->name('siswa.register');
    Route::post('/register/siswa', [RegisterController::class, 'registerSiswa'])->name('siswa.register.submit');

});