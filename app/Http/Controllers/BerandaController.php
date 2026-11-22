<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BerandaController extends Controller
{
    public function index(Request $request)
    {
        $allowedStates = [
            'default',
            'menunggu_persetujuan',
            'ditolak',
            'kosong',
            'tidak_ada_kelas_hari_ini',
            'loading',
            'error_500',
        ];

        $state = (string) $request->query('state', 'default');
        if (! in_array($state, $allowedStates, true)) {
            $state = 'default';
        }

        $selectedDay = (int) $request->query('day', $state === 'tidak_ada_kelas_hari_ini' ? 6 : 2);
        if ($selectedDay < 2 || $selectedDay > 8) {
            $selectedDay = 2;
        }

        $weekDays = [
            ['day' => 2, 'short' => 'Sen', 'has_dot' => true],
            ['day' => 3, 'short' => 'Sel', 'has_dot' => true],
            ['day' => 4, 'short' => 'Rab', 'has_dot' => true],
            ['day' => 5, 'short' => 'Kam', 'has_dot' => true],
            ['day' => 6, 'short' => 'Jum', 'has_dot' => false],
            ['day' => 7, 'short' => 'Sab', 'has_dot' => true],
            ['day' => 8, 'short' => 'Min', 'has_dot' => false],
        ];

        $scheduleByDay = [
            2 => [
                ['title' => 'Pemrograman Web', 'meta' => '07.30–10.00 · R. 204', 'color' => '#2563eb'],
                ['title' => 'Basis Data', 'meta' => '13.00–15.30 · Lab. Komputer 2', 'color' => '#059669'],
            ],
            3 => [['title' => 'Jaringan Komputer', 'meta' => '08.00–10.30 · R. 208', 'color' => '#0d9488']],
            4 => [['title' => 'Interaksi Manusia dan Komputer', 'meta' => '10.20–12.00 · R. 301', 'color' => '#db2777']],
            5 => [['title' => 'Rekayasa Perangkat Lunak', 'meta' => '08.00–10.30 · R. 206', 'color' => '#d97706']],
            6 => [],
            7 => [['title' => 'Statistika', 'meta' => '08.00–10.00 · R. 103', 'color' => '#7c3aed']],
            8 => [],
        ];

        $classes = [
            ['code' => 'IF201', 'name' => 'Pemrograman Web', 'schedule' => 'Senin, 07.30–10.00', 'room' => 'R. 204', 'lecturer' => 'Dr. Rina Kartika, M.Kom.', 'attendance' => 'Hadir 13/14', 'color' => '#2563eb', 'badge' => 'PW'],
            ['code' => 'IF203', 'name' => 'Basis Data', 'schedule' => 'Senin, 13.00–15.30', 'room' => 'Lab. Komputer 2', 'lecturer' => 'Agus Prasetyo, S.T., M.T.', 'attendance' => 'Hadir 14/14', 'color' => '#059669', 'badge' => 'BD'],
            ['code' => 'IF209', 'name' => 'Jaringan Komputer', 'schedule' => 'Selasa, 08.00–10.30', 'room' => 'R. 208', 'lecturer' => 'Yusuf Maulana, S.T., M.Kom.', 'attendance' => 'Hadir 12/14', 'color' => '#0d9488', 'badge' => 'JK'],
            ['code' => 'IF205', 'name' => 'Interaksi Manusia dan Komputer', 'schedule' => 'Rabu, 10.20–12.00', 'room' => 'R. 301', 'lecturer' => 'Nur Aini Lubis, S.Kom., M.Sc.', 'attendance' => 'Hadir 11/14', 'color' => '#db2777', 'badge' => 'IMK'],
            ['code' => 'IF207', 'name' => 'Rekayasa Perangkat Lunak', 'schedule' => 'Kamis, 08.00–10.30', 'room' => 'R. 206', 'lecturer' => 'Ir. Bambang Setiawan, M.Kom.', 'attendance' => 'Hadir 14/14', 'color' => '#d97706', 'badge' => 'RPL'],
            ['code' => 'IF211', 'name' => 'Statistika', 'schedule' => 'Sabtu, 08.00–10.00', 'room' => 'R. 103', 'lecturer' => 'Dewi Kurniasari, S.Si., M.Stat.', 'attendance' => 'Hadir 12/14', 'color' => '#7c3aed', 'badge' => 'ST'],
        ];

        $tasks = ($state === 'default' || $state === 'error_500')
            ? [
                ['course' => 'Pemrograman Web', 'title' => 'Tugas 2: REST API Katalog Buku', 'due' => 'Tenggat Senin, 2 Nov 2026, 23.59 WIB', 'color' => '#2563eb'],
                ['course' => 'Basis Data', 'title' => 'Laporan Praktikum 5: Normalisasi', 'due' => 'Tenggat Rabu, 4 Nov 2026, 12.00 WIB', 'color' => '#059669'],
                ['course' => 'Interaksi Manusia dan Komputer', 'title' => 'Rancangan Wireframe Aplikasi', 'due' => 'Tenggat Minggu, 15 Nov 2026, 23.59 WIB', 'color' => '#db2777'],
            ]
            : [];

        return view('dashboard', [
            'state' => $state,
            'selectedDay' => $selectedDay,
            'weekDays' => $weekDays,
            'daySchedule' => $scheduleByDay[$selectedDay] ?? [],
            'classes' => $classes,
            'tasks' => $tasks,
        ]);
    }
}
