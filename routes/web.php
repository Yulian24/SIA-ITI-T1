<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\DemoAuth;

// TES SEMENTARA
Route::get('/tes', function () {
    return response('LARAVEL BERHASIL MENAMPILKAN HALAMAN', 200);
});

// Halaman utama
Route::get('/', function () {
    return redirect()->route('login');
});

// Login
Route::get('/login', [AuthController::class, 'showLogin'])
    ->name('login');

Route::post('/login', [AuthController::class, 'login'])
    ->name('login.submit');

// Lupa password
Route::get('/forgot-password', [AuthController::class, 'showForgot'])
    ->name('password.request');

Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])
    ->name('password.email');

// Reset password
Route::get('/reset-password', [AuthController::class, 'showReset'])
    ->name('password.reset');

Route::post('/reset-password', [AuthController::class, 'reset'])
    ->name('password.update');

// Halaman yang memerlukan login
Route::middleware(DemoAuth::class)->group(function () {

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // Pergantian role sementara untuk preview desain
    Route::post('/switch-role', [AuthController::class, 'switchRole'])
        ->name('role.switch');

    // Manajemen pengguna
    Route::resource('users', UserController::class);

    // Profil
    Route::get('/profile', [ProfileController::class, 'show'])
        ->name('profile.show');

    Route::get('/profile/edit', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::put('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    // Manajemen data mahasiswa - khusus BAAK / Admin Akademik
    Route::get('/mahasiswa', function () {

        $user = session('auth_user', []);
        $role = strtolower(trim($user['role'] ?? ''));

        $allowedRoles = [
            'admin akademik',
            'baak',
            'baak / admin akademik',
        ];

        if (!in_array($role, $allowedRoles, true)) {
            abort(403, 'Anda tidak memiliki hak akses ke halaman ini.');
        }

        return view('mahasiswa.index');

    })->name('mahasiswa.index');

    // Role dan Hak Akses - khusus Admin Sistem
    Route::get('/roles', function () {

        $user = session('auth_user', []);
        $role = strtolower(trim($user['role'] ?? ''));

        $allowedRoles = [
            'admin sistem',
            'administrator',
            'admin',
        ];

        if (!in_array($role, $allowedRoles, true)) {
            abort(403, 'Anda tidak memiliki hak akses ke halaman ini.');
        }

        return view('roles.index');

    })->name('roles.index');

});
