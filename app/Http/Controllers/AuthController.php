<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (session()->has('auth_user')) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Validasi input
        $data = $request->validate([
            'email' => ['required', 'string', 'max:120'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'NIM atau username wajib diisi.',
            'email.max' => 'NIM atau username maksimal 120 karakter.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        $identifier = trim($data['email']);
        $password = $data['password'];

        // Kunci berdasarkan username dan alamat IP
        $key = 'login:' . strtolower($identifier) . '|' . $request->ip();

        // Periksa apakah akun sedang dikunci
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);

            return back()
                ->withInput($request->only('email'))
                ->with('login_locked', true)
                ->with(
                    'error',
                    "Terlalu banyak percobaan. Coba lagi dalam {$seconds} detik."
                );
        }

        // Akun demo administrator
        if (
            $identifier === 'admin@iti.ac.id' &&
            $password === 'password123'
        ) {
            $user = [
                'id' => 1,
                'name' => 'Administrator SIA',
                'email' => 'admin@iti.ac.id',
                'role' => 'Administrator',
                'nim' => '-',
                'phone' => '081200000000',
                'faculty' => 'Institut Teknologi Indonesia',
                'program' => 'Pengelola Sistem',
            ];
        }

        // Akun demo mahasiswa
        elseif (
            $identifier === '312020001' &&
            $password === 'password123'
        ) {
            $user = [
                'id' => 2,
                'name' => 'Budi Santoso',
                'email' => 'budi.santoso@contoh.com',
                'role' => 'Mahasiswa',
                'nim' => '312020001',
                'phone' => '0812-3456-7890',
                'faculty' => 'Institut Teknologi Indonesia',
                'program' => 'Teknik Informatika',
            ];
        }

        // Jika username atau password salah
        else {
            RateLimiter::hit($key, 60);

            // Jika sudah mencapai 5 percobaan gagal
            if (RateLimiter::tooManyAttempts($key, 5)) {
                $seconds = RateLimiter::availableIn($key);

                return back()
                    ->withInput($request->only('email'))
                    ->with('login_locked', true)
                    ->with(
                        'error',
                        "Terlalu banyak percobaan. Coba lagi dalam {$seconds} detik."
                    );
            }

            return back()
                ->withInput($request->only('email'))
                ->with('error', 'NIM/username atau kata sandi tidak sesuai.');
        }

        // Login berhasil, hapus penghitung percobaan
        RateLimiter::clear($key);

        // Regenerasi session
        $request->session()->regenerate();

        // Simpan data pengguna
        session([
            'auth_user' => $user
        ]);

        return redirect()->route('dashboard')
            ->with('success', 'Selamat datang di SIA ITI!');
    }

    public function showForgot()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email']
        ]);

        return back()->with(
            'success',
            'Instruksi reset password berhasil diminta. Ini masih simulasi.'
        );
    }

    public function showReset(Request $request)
    {
        return view('auth.reset-password', [
            'email' => $request->query('email', '')
        ]);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'min:8', 'confirmed'],
        ]);

        return redirect()->route('login')->with(
            'success',
            'Password berhasil diperbarui. Silakan masuk kembali.'
        );
    }

    public function switchRole(Request $request)
    {
        $validated = $request->validate([
            'role' => [
                'required',
                'in:Mahasiswa,Dosen,Dosen PA,Kaprodi,Admin Akademik,Admin Sistem',
            ],
        ]);

        $user = session('auth_user', []);

        $user['role'] = $validated['role'];

        session(['auth_user' => $user]);

        return redirect()->route('dashboard')
            ->with('success', 'Role berhasil diganti.');
    }

    public function logout(Request $request)
    {
        $request->session()->forget('auth_user');
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'Anda berhasil keluar dari sistem.');
    }
}