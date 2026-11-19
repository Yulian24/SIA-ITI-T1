<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KrsController extends Controller
{
    public function index(Request $request)
    {
        $state = $request->query('state', 'disetujui');

        $allowedStates = [
            'default',
            'disetujui',
            'periode_dibuka',
            'menunggu_persetujuan',
            'ditolak',
            'periode_ditutup',
            'loading',
            'semester_antara',
            'semester_antara_kosong',
        ];

        if (!in_array($state, $allowedStates, true)) {
            $state = 'disetujui';
        }

        $authUser = session('auth_user', []);

        $student = [
            'nim' => ($authUser['nim'] ?? null) && $authUser['nim'] !== '-' ? $authUser['nim'] : '312020001',
            'name' => $authUser['name'] ?? 'Budi Santoso',
            'program' => $authUser['program'] ?? 'Teknik Informatika (S1)',
            'angkatan' => '2025',
            'semester' => '3 · Ganjil 2026/2027',
            'status' => 'Aktif',
            'kurikulum' => '2024',
            'dosen_pa' => 'Dr. Rina Kartika, M.Kom.',
            'ipk' => '3,45',
            'sks_lulus' => 42,
            'batas_sks' => 24,
            'periode_lalu' => '20252',
            'ipk_lalu' => '3,45',
        ];

        $periodes = [
            '20261' => '20261 · Ganjil 2026/2027',
            '20252' => '20252 · Genap 2025/2026',
            '20251' => '20251 · Ganjil 2025/2026',
        ];

        $selectedPeriode = $request->query('periode', $state === 'semester_antara' || $state === 'semester_antara_kosong' ? '20252' : '20261');

        $courses = [
            ['code' => 'IF201', 'name' => 'Pemrograman Web', 'schedule' => 'Senin, 07.30–10.00', 'semester' => 3, 'sks' => 3],
            ['code' => 'IF203', 'name' => 'Basis Data', 'schedule' => 'Senin, 13.00–15.30', 'semester' => 3, 'sks' => 3],
            ['code' => 'IF205', 'name' => 'Interaksi Manusia dan Komputer', 'schedule' => 'Rabu, 10.30–12.00', 'semester' => 3, 'sks' => 3],
            ['code' => 'IF207', 'name' => 'Rekayasa Perangkat Lunak', 'schedule' => 'Kamis, 08.00–10.30', 'semester' => 3, 'sks' => 3],
            ['code' => 'IF209', 'name' => 'Jaringan Komputer', 'schedule' => 'Selasa, 08.00–10.30', 'semester' => 3, 'sks' => 3],
            ['code' => 'IF211', 'name' => 'Statistika', 'schedule' => 'Sabtu, 08.00–10.00', 'semester' => 3, 'sks' => 3],
        ];

        if ($state === 'ditolak') {
            $courses[] = ['code' => 'IF213', 'name' => 'Pemrograman Perangkat Bergerak', 'schedule' => 'Selasa, 13.00–15.30', 'semester' => 3, 'sks' => 3];
        }

        if ($state === 'semester_antara') {
            $courses = [
                ['code' => 'IF102', 'name' => 'Kalkulus II', 'schedule' => 'Senin, 08.00–10.30', 'semester' => 2, 'sks' => 3],
                ['code' => 'IF104', 'name' => 'Algoritma dan Struktur Data', 'schedule' => 'Selasa, 08.00–10.30', 'semester' => 2, 'sks' => 3],
                ['code' => 'IF106', 'name' => 'Arsitektur Komputer', 'schedule' => 'Selasa, 13.00–15.30', 'semester' => 2, 'sks' => 3],
                ['code' => 'IF108', 'name' => 'Pemrograman Berorientasi Objek', 'schedule' => 'Rabu, 08.00–10.30', 'semester' => 2, 'sks' => 3],
                ['code' => 'IF110', 'name' => 'Sistem Digital', 'schedule' => 'Kamis, 08.00–10.30', 'semester' => 2, 'sks' => 3],
                ['code' => 'IF112', 'name' => 'Aljabar Linear', 'schedule' => 'Kamis, 13.00–15.30', 'semester' => 2, 'sks' => 3],
                ['code' => 'IF114', 'name' => 'Bahasa Inggris', 'schedule' => 'Jumat, 08.00–09.40', 'semester' => 2, 'sks' => 2],
            ];
        }

        return view('krs.index', compact('state', 'student', 'periodes', 'selectedPeriode', 'courses'));
    }

    public function pilihKelas(Request $request)
    {
        $state = $request->query('state', 'default');

        $allowedStates = [
            'default',
            'konfirmasi_pengajuan',
            'validasi_gagal',
            'duplikasi_ditolak',
            'waiting_list',
            'kosong',
            'loading',
            'semester_antara',
        ];

        if (!in_array($state, $allowedStates, true)) {
            $state = 'default';
        }

        $authUser = session('auth_user', []);

        $meta = [
            'periode' => $state === 'semester_antara' ? 'Semester antara 2026' : '20261 · Ganjil 2026/2027',
            'dosen_pa' => 'Dr. Rina Kartika, M.Kom.',
            'batas_waktu' => $state === 'semester_antara' ? '7 Jul 2026, 23.59 WIB' : '21 Agu 2026, 23.59 WIB',
            'batas_sks' => 24,
            'periode_lalu' => '20252',
            'ipk_lalu' => '3,45',
            'nim' => ($authUser['nim'] ?? null) && $authUser['nim'] !== '-' ? $authUser['nim'] : '312020001',
            'name' => $authUser['name'] ?? 'Budi Santoso',
        ];

        $offerings = [
            ['code' => 'IF201', 'name' => 'Pemrograman Web', 'schedule' => 'Senin, 07.30–10.00', 'sks' => 3, 'semester' => 3, 'status' => 'in_draft', 'note' => null],
            ['code' => 'IF203', 'name' => 'Basis Data', 'schedule' => 'Senin, 13.00–15.30', 'sks' => 3, 'semester' => 3, 'status' => 'in_draft', 'note' => null],
            ['code' => 'IF205', 'name' => 'Interaksi Manusia dan Komputer', 'schedule' => 'Rabu, 10.30–12.00', 'sks' => 3, 'semester' => 3, 'status' => 'in_draft', 'note' => null],
            ['code' => 'IF207', 'name' => 'Rekayasa Perangkat Lunak', 'schedule' => 'Kamis, 08.00–10.30', 'sks' => 3, 'semester' => 3, 'status' => 'in_draft', 'note' => null],
            ['code' => 'IF209', 'name' => 'Jaringan Komputer', 'schedule' => 'Selasa, 08.00–10.30', 'sks' => 3, 'semester' => 3, 'status' => 'in_draft', 'note' => null],
            ['code' => 'IF211', 'name' => 'Statistika', 'schedule' => 'Sabtu, 08.00–10.00', 'sks' => 3, 'semester' => 3, 'status' => 'in_draft', 'note' => null],
            ['code' => 'IF212', 'name' => 'Sistem Operasi', 'schedule' => 'Jumat, 08.00–10.30', 'sks' => 3, 'semester' => 3, 'status' => 'full', 'note' => 'Kelas penuh (40/40)'],
            ['code' => 'IF215', 'name' => 'Pemrograman Perangkat Bergerak', 'schedule' => 'Selasa, 13.00–15.30', 'sks' => 3, 'semester' => 3, 'status' => 'available', 'note' => null],
            ['code' => 'IF217', 'name' => 'Etika Profesi', 'schedule' => 'Kamis, 13.00–15.00', 'sks' => 2, 'semester' => 3, 'status' => 'available', 'note' => null],
            ['code' => 'IF220', 'name' => 'Kecerdasan Buatan', 'schedule' => 'Rabu, 07.30–10.00', 'sks' => 3, 'semester' => 4, 'status' => 'locked', 'note' => 'Prasyarat belum terpenuhi: IF204 Matematika Diskrit'],
        ];

        if ($state === 'duplikasi_ditolak') {
            array_splice($offerings, 6, 0, [
                ['code' => 'IF201-B', 'name' => 'Pemrograman Web', 'schedule' => 'Rabu, 13.00–15.30', 'sks' => 3, 'semester' => 3, 'status' => 'available', 'note' => null],
            ]);
        }

        if ($state === 'semester_antara') {
            $offerings = [
                ['code' => 'IF102', 'name' => 'Kalkulus II', 'schedule' => 'Senin, 08.00–10.30', 'sks' => 3, 'semester' => 2, 'status' => 'in_draft', 'note' => null],
                ['code' => 'IF104', 'name' => 'Algoritma dan Struktur Data', 'schedule' => 'Selasa, 08.00–10.30', 'sks' => 3, 'semester' => 2, 'status' => 'available', 'note' => null],
            ];
        }

        return view('krs.pilih-kelas', compact('state', 'meta', 'offerings'));
    }
}