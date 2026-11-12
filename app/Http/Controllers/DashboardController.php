<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;

class DashboardController extends Controller
{
    public function index()
    {
        $user = session('auth_user', []);

        $role = Str::lower(trim($user['role'] ?? 'mahasiswa'));

        // Samakan beberapa variasi nama role.
        $roleAliases = [
            'administrator' => 'admin sistem',
            'admin' => 'admin sistem',
            'baak' => 'admin akademik',
            'baak / admin akademik' => 'admin akademik',
            'dosen pa' => 'dosen pa',
        ];

        $role = $roleAliases[$role] ?? $role;

        // Dashboard khusus BAAK / Admin Akademik.
        if ($role === 'admin akademik') {
            return view('dashboard.admin-akademik', [
                'user' => $user,
                'period' => '20261',
            ]);
        }

        $modulesByRole = [

            // MAHASISWA
            'mahasiswa' => [
                ['label' => 'Beranda', 'icon' => 'B', 'route' => 'dashboard'],
                ['label' => 'KRS', 'icon' => 'K', 'route' => '#'],
                ['label' => 'Jadwal', 'icon' => 'J', 'route' => '#'],
                ['label' => 'Presensi', 'icon' => 'P', 'route' => '#'],
                ['label' => 'Nilai dan KHS', 'icon' => 'N', 'route' => '#'],
                ['label' => 'Materi dan Tugas', 'icon' => 'M', 'route' => '#'],
                ['label' => 'Profil', 'icon' => 'A', 'route' => 'profile.show'],
            ],

            // DOSEN
            'dosen' => [
                ['label' => 'Beranda', 'icon' => 'B', 'route' => 'dashboard'],
                ['label' => 'Jadwal Mengajar', 'icon' => 'J', 'route' => '#'],
                ['label' => 'Kelas Diampu', 'icon' => 'K', 'route' => '#'],
                ['label' => 'Presensi', 'icon' => 'P', 'route' => '#'],
                ['label' => 'Nilai', 'icon' => 'N', 'route' => '#'],
                ['label' => 'Materi dan Tugas', 'icon' => 'M', 'route' => '#'],
                ['label' => 'Profil', 'icon' => 'A', 'route' => 'profile.show'],
            ],

            // DOSEN PA
            'dosen pa' => [
                ['label' => 'Beranda', 'icon' => 'B', 'route' => 'dashboard'],
                ['label' => 'Persetujuan KRS', 'icon' => 'K', 'route' => '#'],
                ['label' => 'Mahasiswa Bimbingan', 'icon' => 'M', 'route' => '#'],
                ['label' => 'Riwayat KRS', 'icon' => 'R', 'route' => '#'],
                ['label' => 'Profil', 'icon' => 'A', 'route' => 'profile.show'],
            ],

            // KAPRODI
            'kaprodi' => [
                ['label' => 'Beranda', 'icon' => 'B', 'route' => 'dashboard'],
                ['label' => 'Persetujuan KRS', 'icon' => 'K', 'route' => '#'],
                ['label' => 'Monitoring Prodi', 'icon' => 'M', 'route' => '#'],
                ['label' => 'Profil', 'icon' => 'A', 'route' => 'profile.show'],
            ],

            // BAAK / ADMIN AKADEMIK
            'admin akademik' => [
                ['label' => 'Beranda', 'icon' => 'B', 'route' => 'dashboard'],
                ['label' => 'Mahasiswa', 'icon' => 'M', 'route' => 'mahasiswa.index'],
                ['label' => 'Dosen', 'icon' => 'D', 'route' => '#'],
                ['label' => 'Program Studi', 'icon' => 'P', 'route' => '#'],
                ['label' => 'Periode Akademik', 'icon' => 'A', 'route' => '#'],
                ['label' => 'Mata Kuliah dan Kurikulum', 'icon' => 'K', 'route' => '#'],
                ['label' => 'Penawaran dan Kelas', 'icon' => 'K', 'route' => '#'],
                ['label' => 'Jadwal', 'icon' => 'J', 'route' => '#'],
                ['label' => 'Pembayaran', 'icon' => 'P', 'route' => '#'],
                ['label' => 'Laporan Akademik', 'icon' => 'L', 'route' => '#'],
            ],

            // ADMIN SISTEM
            'admin sistem' => [
                ['label' => 'Beranda', 'icon' => 'B', 'route' => 'dashboard'],
                ['label' => 'Role dan Hak Akses', 'icon' => 'R', 'route' => 'roles.index'],
                ['label' => 'Pengguna', 'icon' => 'P', 'route' => '#'],
                ['label' => 'Audit Perubahan', 'icon' => 'A', 'route' => '#'],
                ['label' => 'Kesehatan Sistem', 'icon' => 'K', 'route' => '#'],
            ],
        ];

        // Jika role belum terdaftar, gunakan tampilan Mahasiswa.
        $moduleRole = isset($modulesByRole[$role])
            ? $role
            : 'mahasiswa';

        $roleLabels = [
            'mahasiswa' => 'Mahasiswa',
            'dosen' => 'Dosen',
            'dosen pa' => 'Dosen PA',
            'kaprodi' => 'Kaprodi',
            'admin akademik' => 'BAAK / Admin akademik',
            'admin sistem' => 'Admin Sistem',
        ];

        return view('dashboard.index', [
            'user' => $user,
            'modules' => $modulesByRole[$moduleRole],
            'moduleRole' => $roleLabels[$moduleRole],
            'period' => '20261',
        ]);
    }
}
