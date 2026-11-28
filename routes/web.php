<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\SiswaRegisterController;
use App\Http\Controllers\Auth\GuruRegisterController;
use App\Http\Controllers\Auth\AdminRegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardOverviewController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\DashboardStudentController;
use App\Http\Controllers\DashboardClassController;
use App\Http\Controllers\LeaveApprovalController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\ProfileGuruController;
use App\Http\Controllers\AdminReportController;
use App\Http\Controllers\SystemSettingController;
use App\Http\Controllers\SiswaProfileController;
use App\Http\Controllers\ScanSiswaController;
use App\Http\Controllers\LeaveSiswaController; // BARU

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ==========================================
// 1. PUBLIC ROUTES
// ==========================================
Route::get('/', function () {
    return view('home');
})->name('home');

Route::post('/check-account', [LoginController::class, 'checkAccount'])->name('check.account');


// ==========================================
// 2. GUEST ROUTES (Hanya Akses Saat Belum Login)
// ==========================================
Route::middleware('guest')->group(function () {

    // --- FORM LOGIN UTAMA ---
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.submit');

    // --- FORM LOGIN KHUSUS PER ROLE ---
    Route::get('/login/siswa', function () {
        return view('loginSiswa');
    })->name('siswa.login');

    Route::get('/login/guru', function () {
        return view('loginGuru');
    })->name('guru.login');

    Route::get('/login/admin', function () {
        return view('loginAdmin');
    })->name('admin.login');

    // --- PROSES SUBMIT LOGIN PER ROLE ---
    Route::post('/login/guru', [LoginController::class, 'login'])->name('guru.login.submit');
    Route::post('/login/siswa', [LoginController::class, 'login'])->name('siswa.login.submit');
    Route::post('/login/admin', [LoginController::class, 'login'])->name('admin.login.submit');

    // --- FORM & PROSES REGISTER SISWA ---
    Route::get('/register/siswa', [SiswaRegisterController::class, 'showSiswaRegisterForm'])->name('register.siswa');
    Route::post('/register/siswa', [SiswaRegisterController::class, 'registerSiswa'])->name('siswa.register.submit');
    Route::post('/register/siswa/submit', [SiswaRegisterController::class, 'registerSiswa'])->name('register.siswa.submit');

    // --- FORM & PROSES REGISTER GURU ---
    Route::get('/register/guru', [GuruRegisterController::class, 'create'])->name('register.guru');
    Route::post('/register/guru', [GuruRegisterController::class, 'store'])->name('register.guru.store');

    // --- FORM & PROSES REGISTER ADMIN ---
    Route::get('/register/admin', [AdminRegisterController::class, 'showRegisterForm'])->name('register.admin');
    Route::post('/register/admin', [AdminRegisterController::class, 'register'])->name('register.admin.submit');
});


// ==========================================
// 3. AUTHENTICATED ROUTES (Harus Login)
// ==========================================
Route::middleware(['auth'])->group(function () {

    // LOGOUT
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // DEFAULT DASHBOARD ROUTE
    Route::get('/dashboard', [DashboardOverviewController::class, 'index'])->name('dashboard');
    Route::get('/dasbor', [DashboardOverviewController::class, 'index'])->name('dasbor');

    // Unduh dokumen pendukung izin (pemilik pengajuan atau admin)
    Route::get('/leave/{id}/document', [LeaveSiswaController::class, 'document'])->name('leave.document'); // BARU

    // ------------------------------------------
    // ADMIN ROUTES
    // ------------------------------------------
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/overview', [DashboardOverviewController::class, 'index'])->name('overview');
        Route::get('/dashboard', [DashboardOverviewController::class, 'index'])->name('dashboard');

        // User Management (Guru & Staf)
        Route::get('/user-management', [UserManagementController::class, 'index'])->name('user-management');
        Route::post('/user-management', [UserManagementController::class, 'store'])->name('user-management.store');
        Route::put('/user-management/{id}', [UserManagementController::class, 'update'])->name('user-management.update');
        Route::delete('/user-management/{id}', [UserManagementController::class, 'destroy'])->name('user-management.destroy');

        // Student Management
        Route::get('/students', [DashboardStudentController::class, 'index'])->name('students.index');
        Route::get('/students/create', [DashboardStudentController::class, 'create'])->name('students.create');
        Route::post('/students', [DashboardStudentController::class, 'store'])->name('students.store');
        Route::put('/students/{id}', [DashboardStudentController::class, 'update'])->name('students.update');
        Route::delete('/students/{id}', [DashboardStudentController::class, 'destroy'])->name('students.destroy');

        // Class Management
        Route::get('/classes', [DashboardClassController::class, 'index'])->name('classes.index');
        Route::post('/classes', [DashboardClassController::class, 'store'])->name('classes.store');
        Route::put('/classes/{id}', [DashboardClassController::class, 'update'])->name('classes.update');
        Route::delete('/classes/{id}', [DashboardClassController::class, 'destroy'])->name('classes.destroy');

        // Leave Approvals
        Route::get('/leave-approvals', [LeaveApprovalController::class, 'index'])->name('leave-approvals.index');
        Route::post('/leave-approvals/{id}/update-status', [LeaveApprovalController::class, 'updateStatus'])->name('leave-approvals.update-status');

        // Reports & Analytics Admin
        Route::get('/reports', [AdminReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/export-download', [AdminReportController::class, 'exportData'])->name('reports.export.download');

        // System Settings
        Route::get('/settings', [SystemSettingController::class, 'index'])->name('settings.index');
        Route::post('/settings', [SystemSettingController::class, 'update'])->name('settings.update');

        // QR & Reports Export Lainnya
        Route::get('/qr/generate', [DashboardOverviewController::class, 'generateQr'])->name('qr.generate');
        Route::get('/reports/export', [DashboardOverviewController::class, 'exportReport'])->name('reports.export');
    });

    // ------------------------------------------
    // GURU ROUTES
    // ------------------------------------------
    Route::prefix('guru')->name('guru.')->group(function () {
        Route::get('/overview', function () {
            return method_exists(DashboardOverviewController::class, 'guruOverview')
                ? app(DashboardOverviewController::class)->guruOverview()
                : app(DashboardOverviewController::class)->index();
        })->name('overview');

        Route::get('/dashboard', function () {
            return method_exists(DashboardOverviewController::class, 'guruOverview')
                ? app(DashboardOverviewController::class)->guruOverview()
                : app(DashboardOverviewController::class)->index();
        })->name('dashboard');

        // Sesi Presensi: Create, Store, Active & End
        Route::get('/sessions/create', [SessionController::class, 'create'])->name('sessions.create');
        Route::post('/sessions/store', [SessionController::class, 'store'])->name('sessions.store');
        Route::get('/sessions/active/{id?}', [SessionController::class, 'active'])->name('sessions.active');
        Route::get('/sessions/{id}/feed', [SessionController::class, 'feed'])->name('sessions.feed'); // BARU
        Route::patch('/sessions/{id}/end', [SessionController::class, 'end'])->name('sessions.end');

        // History
        Route::get('/history', [HistoryController::class, 'index'])->name('history');

        // Profile Guru
        Route::get('/profile', [ProfileGuruController::class, 'index'])->name('profile');
        Route::put('/profile/update', [ProfileGuruController::class, 'update'])->name('profile.update');
    });

    // ------------------------------------------
    // SISWA ROUTES
    // ------------------------------------------
    Route::prefix('siswa')->name('siswa.')->group(function () {
        Route::get('/overview', function () {
            return method_exists(DashboardOverviewController::class, 'siswaOverview')
                ? app(DashboardOverviewController::class)->siswaOverview()
                : app(DashboardOverviewController::class)->index();
        })->name('overview');

        Route::get('/dashboard', function () {
            return method_exists(DashboardOverviewController::class, 'siswaOverview')
                ? app(DashboardOverviewController::class)->siswaOverview()
                : app(DashboardOverviewController::class)->index();
        })->name('dashboard');

        // Profile Siswa
        Route::get('/profile', [SiswaProfileController::class, 'index'])->name('profile');
        Route::put('/profile', [SiswaProfileController::class, 'update'])->name('profile.update'); // BARU

        // Scan QR Siswa
        Route::get('/scan', [ScanSiswaController::class, 'index'])->name('scan');
        Route::post('/scan', [ScanSiswaController::class, 'store'])->name('scan.store'); // BARU

        // Submit Leave Siswa
        Route::get('/leave', [LeaveSiswaController::class, 'index'])->name('leave'); // BARU
        Route::post('/leave', [LeaveSiswaController::class, 'store'])->name('leave.store'); // BARU
    });

    // Alias Route student.*
    Route::prefix('student')->name('student.')->group(function () {
        Route::get('/overview', function () {
            return method_exists(DashboardOverviewController::class, 'siswaOverview')
                ? app(DashboardOverviewController::class)->siswaOverview()
                : app(DashboardOverviewController::class)->index();
        })->name('overview');

        Route::get('/dashboard', function () {
            return method_exists(DashboardOverviewController::class, 'siswaOverview')
                ? app(DashboardOverviewController::class)->siswaOverview()
                : app(DashboardOverviewController::class)->index();
        })->name('dashboard');
    });
});