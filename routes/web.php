<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\KrsController;
use App\Http\Controllers\KurikulumMataKuliahController;
use App\Http\Controllers\NilaiController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\DemoAuth;
use Illuminate\Support\Facades\Route;

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
    Route::get('/dashboard', [BerandaController::class, 'index'])->name('dashboard');

    Route::get('/jadwal', [JadwalController::class, 'index'])->name('jadwal.index');

    Route::get('/presensi', [PresensiController::class, 'index'])->name('presensi.index');
    Route::get('/presensi/{code}', [PresensiController::class, 'show'])->name('presensi.show');

    Route::get('/nilai', [NilaiController::class, 'index'])->name('nilai.index');

    // KRS Mahasiswa
    Route::get('/krs', [KrsController::class, 'index'])->name('krs.index');
    Route::post('/krs/ajukan', [KrsController::class, 'submit'])->name('krs.submit');
    Route::get('/krs/pilih-kelas', [KrsController::class, 'pilihKelas'])->name('krs.pilih-kelas');

    // Kurikulum dan mata kuliah program studi
    Route::prefix('prodi')->group(function () {
        Route::get('/kurikulum', [KurikulumMataKuliahController::class, 'indexKurikulum'])->name('kurikulum.index');
        Route::get('/kurikulum/baru', [KurikulumMataKuliahController::class, 'createKurikulum'])->name('kurikulum.create');
        Route::post('/kurikulum', [KurikulumMataKuliahController::class, 'storeKurikulum'])->name('kurikulum.store');
        Route::get('/kurikulum/{code}', [KurikulumMataKuliahController::class, 'showKurikulum'])->name('kurikulum.show');
        Route::get('/kurikulum/{code}/edit', [KurikulumMataKuliahController::class, 'editKurikulum'])->name('kurikulum.edit');
        Route::put('/kurikulum/{code}', [KurikulumMataKuliahController::class, 'updateKurikulum'])->name('kurikulum.update');

        Route::get('/mata-kuliah', [KurikulumMataKuliahController::class, 'indexMataKuliah'])->name('mata-kuliah.index');
        Route::get('/mata-kuliah/tambah', [KurikulumMataKuliahController::class, 'createMataKuliah'])->name('mata-kuliah.create');
        Route::post('/mata-kuliah', [KurikulumMataKuliahController::class, 'storeMataKuliah'])->name('mata-kuliah.store');
        Route::get('/mata-kuliah/{code}', [KurikulumMataKuliahController::class, 'showMataKuliah'])->name('mata-kuliah.show');
        Route::get('/mata-kuliah/{code}/edit', [KurikulumMataKuliahController::class, 'editMataKuliah'])->name('mata-kuliah.edit');
        Route::put('/mata-kuliah/{code}', [KurikulumMataKuliahController::class, 'updateMataKuliah'])->name('mata-kuliah.update');
        Route::delete('/mata-kuliah/{code}', [KurikulumMataKuliahController::class, 'destroyMataKuliah'])->name('mata-kuliah.destroy');
    });

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

        if (! in_array($role, $allowedRoles, true)) {
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

        if (! in_array($role, $allowedRoles, true)) {
            abort(403, 'Anda tidak memiliki hak akses ke halaman ini.');
        }

        return view('roles.index');

    })->name('roles.index');

});
