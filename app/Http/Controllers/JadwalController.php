<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class JadwalController extends Controller
{
    public function index(Request $request)
    {
        $allowedStates = [
            'default',
            'jadwal_berubah',
            'krs_belum_disetujui',
            'belum_dipublikasikan',
            'loading',
            'error_500',
        ];

        $state = (string) $request->query('state', 'default');
        if (! in_array($state, $allowedStates, true)) {
            $state = 'default';
        }

        $viewMode = (string) $request->query('view', 'pekan');
        if (! in_array($viewMode, ['pekan', 'daftar'], true)) {
            $viewMode = 'pekan';
        }

        $isChanged = $state === 'jadwal_berubah';

        $items = [
            [
                'day' => 'Sen',
                'date_label' => 'Senin, 2 Nov 2026',
                'start' => '07.30',
                'end' => '10.00',
                'start_hour' => 7.5,
                'duration_hours' => 2.5,
                'code' => 'IF201',
                'name' => 'Pemrograman Web',
                'room' => $isChanged ? 'R. 302' : 'R. 204',
                'lecturer' => 'Dr. Rina Kartika, M.Kom.',
                'status' => $isChanged ? 'Ruang berubah' : 'Terjadwal',
                'accent' => '#2563eb',
                'bg' => $isChanged ? '#fffbeb' : '#eff6ff',
            ],
            [
                'day' => 'Sen',
                'date_label' => 'Senin, 2 Nov 2026',
                'start' => '13.00',
                'end' => '15.30',
                'start_hour' => 13.0,
                'duration_hours' => 2.5,
                'code' => 'IF203',
                'name' => 'Basis Data',
                'room' => 'Lab. Komputer 2',
                'lecturer' => 'Agus Prasetyo, S.T., M.T.',
                'status' => 'Terjadwal',
                'accent' => '#059669',
                'bg' => '#ecfdf5',
            ],
            [
                'day' => 'Sel',
                'date_label' => 'Selasa, 3 Nov 2026',
                'start' => '08.00',
                'end' => '10.30',
                'start_hour' => 8.0,
                'duration_hours' => 2.5,
                'code' => 'IF209',
                'name' => 'Jaringan Komputer',
                'room' => 'R. 208',
                'lecturer' => 'Yusuf Maulana, S.T., M.Kom.',
                'status' => 'Terjadwal',
                'accent' => '#0d9488',
                'bg' => '#f0fdfa',
            ],
            [
                'day' => 'Rab',
                'date_label' => 'Rabu, 4 Nov 2026',
                'start' => '10.20',
                'end' => '12.00',
                'start_hour' => 10.33,
                'duration_hours' => 1.67,
                'code' => 'IF205',
                'name' => 'Interaksi Manusia dan Komputer',
                'room' => 'R. 301',
                'lecturer' => 'Nur Aini Lubis, S.Kom., M.Sc.',
                'status' => 'Terjadwal',
                'accent' => '#db2777',
                'bg' => '#fdf2f8',
            ],
            [
                'day' => 'Kam',
                'date_label' => 'Kamis, 5 Nov 2026',
                'start' => '08.00',
                'end' => '10.30',
                'start_hour' => 8.0,
                'duration_hours' => 2.5,
                'code' => 'IF207',
                'name' => 'Rekayasa Perangkat Lunak',
                'room' => 'R. 206',
                'lecturer' => 'Ir. Bambang Setiawan, M.Kom.',
                'status' => 'Terjadwal',
                'accent' => '#d97706',
                'bg' => '#fffbeb',
            ],
            [
                'day' => 'Sab',
                'date_label' => 'Sabtu, 7 Nov 2026',
                'start' => '08.00',
                'end' => '10.00',
                'start_hour' => 8.0,
                'duration_hours' => 2.0,
                'code' => 'IF211',
                'name' => 'Statistika',
                'room' => $isChanged ? 'R. 103 (Jumat, 13.00–15.10)' : 'R. 103',
                'lecturer' => 'Dewi Kurniasari, S.Si., M.Stat.',
                'status' => $isChanged ? 'Jadwal berubah' : 'Terjadwal',
                'accent' => '#7c3aed',
                'bg' => $isChanged ? '#fffbeb' : '#f5f3ff',
            ],
        ];

        return view('jadwal.index', [
            'state' => $state,
            'viewMode' => $viewMode,
            'items' => $items,
            'weekLabel' => $isChanged ? '9–15 Nov 2026' : '2–8 Nov 2026',
        ]);
    }
}
