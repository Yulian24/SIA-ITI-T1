<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        View::composer('layouts.app', function ($view) {

            $user = session('auth_user', []);

            $role = strtolower(trim($user['role'] ?? 'mahasiswa'));

            // Penyesuaian nama role.
            $roleAliases = [
                'administrator' => 'admin sistem',
                'admin' => 'admin sistem',
                'baak' => 'admin akademik',
                'baak / admin akademik' => 'admin akademik',
            ];

            $role = $roleAliases[$role] ?? $role;

            $modulesByRole = [

                'mahasiswa' => [
                    ['label' => 'Beranda', 'icon' => 'B', 'route' => 'dashboard'],
                    ['label' => 'KRS', 'icon' => 'K', 'route' => '#'],
                    ['label' => 'Jadwal', 'icon' => 'J', 'route' => '#'],
                    ['label' => 'Presensi', 'icon' => 'P', 'route' => '#'],
                    ['label' => 'Nilai dan KHS', 'icon' => 'N', 'route' => '#'],
                    ['label' => 'Materi dan Tugas', 'icon' => 'M', 'route' => '#'],
                    ['label' => 'Profil', 'icon' => 'A', 'route' => 'profile.show'],
                ],

                'dosen' => [
                    ['label' => 'Beranda', 'icon' => 'B', 'route' => 'dashboard'],
                    ['label' => 'Jadwal Mengajar', 'icon' => 'J', 'route' => '#'],
                    ['label' => 'Kelas Diampu', 'icon' => 'K', 'route' => '#'],
                    ['label' => 'Presensi', 'icon' => 'P', 'route' => '#'],
                    ['label' => 'Nilai', 'icon' => 'N', 'route' => '#'],
                    ['label' => 'Materi dan Tugas', 'icon' => 'M', 'route' => '#'],
                    ['label' => 'Profil', 'icon' => 'A', 'route' => 'profile.show'],
                ],

                'dosen pa' => [
                    ['label' => 'Beranda', 'icon' => 'B', 'route' => 'dashboard'],
                    ['label' => 'Persetujuan KRS', 'icon' => 'K', 'route' => '#'],
                    ['label' => 'Mahasiswa Bimbingan', 'icon' => 'M', 'route' => '#'],
                    ['label' => 'Riwayat KRS', 'icon' => 'R', 'route' => '#'],
                    ['label' => 'Profil', 'icon' => 'A', 'route' => 'profile.show'],
                ],

                'kaprodi' => [
                    ['label' => 'Beranda', 'icon' => 'B', 'route' => 'dashboard'],
                    ['label' => 'Persetujuan KRS', 'icon' => 'K', 'route' => '#'],
                    ['label' => 'Monitoring Prodi', 'icon' => 'M', 'route' => '#'],
                    ['label' => 'Profil', 'icon' => 'A', 'route' => 'profile.show'],
                ],

                'admin akademik' => [
                    ['label' => 'Beranda', 'icon' => 'B', 'route' => 'dashboard'],
                    ['label' => 'Mahasiswa', 'icon' => 'M', 'route' => '#'],
                    ['label' => 'Dosen', 'icon' => 'D', 'route' => '#'],
                    ['label' => 'Program Studi', 'icon' => 'P', 'route' => '#'],
                    ['label' => 'Periode Akademik', 'icon' => 'A', 'route' => '#'],
                    ['label' => 'Mata Kuliah dan Kurikulum', 'icon' => 'K', 'route' => '#'],
                    ['label' => 'Penawaran dan Kelas', 'icon' => 'K', 'route' => '#'],
                    ['label' => 'Jadwal', 'icon' => 'J', 'route' => '#'],
                    ['label' => 'Pembayaran', 'icon' => 'P', 'route' => '#'],
                    ['label' => 'Laporan Akademik', 'icon' => 'L', 'route' => '#'],
                ],

                'admin sistem' => [
                    ['label' => 'Beranda', 'icon' => 'B', 'route' => 'dashboard'],
                    ['label' => 'Role dan Hak Akses', 'icon' => 'R', 'route' => 'roles.index'],
                    ['label' => 'Pengguna', 'icon' => 'P', 'route' => '#'],
                    ['label' => 'Audit Perubahan', 'icon' => 'A', 'route' => '#'],
                    ['label' => 'Kesehatan Sistem', 'icon' => 'K', 'route' => '#'],
                ],
            ];

            $roleLabels = [
                'mahasiswa' => 'Mahasiswa',
                'dosen' => 'Dosen',
                'dosen pa' => 'Dosen PA',
                'kaprodi' => 'Kaprodi',
                'admin akademik' => 'BAAK / Admin akademik',
                'admin sistem' => 'Admin Sistem',
            ];

            // Gunakan Mahasiswa jika role belum terdaftar.
            $moduleRole = isset($modulesByRole[$role])
                ? $role
                : 'mahasiswa';

            $view->with([
                'modules' => $modulesByRole[$moduleRole],
                'moduleRole' => $roleLabels[$moduleRole],
            ]);
        });
    }
}
