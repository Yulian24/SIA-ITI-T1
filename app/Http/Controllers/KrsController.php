<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KrsController extends Controller
{
    private array $courses = [
        ['code' => 'IF201', 'name' => 'Pemrograman Web', 'sks' => 3, 'schedule' => 'Senin, 07.30–10.00 · R. 204', 'lecturer' => 'Dr. Rina Kartika, M.Kom.', 'selected' => true],
        ['code' => 'IF203', 'name' => 'Basis Data', 'sks' => 3, 'schedule' => 'Senin, 13.00–15.30 · Lab. Komputer 2', 'lecturer' => 'Agus Prasetyo, S.T., M.T.', 'selected' => true],
        ['code' => 'IF209', 'name' => 'Jaringan Komputer', 'sks' => 3, 'schedule' => 'Selasa, 08.00–10.30 · R. 208', 'lecturer' => 'Yusuf Maulana, S.T., M.Kom.', 'selected' => true],
        ['code' => 'IF205', 'name' => 'Interaksi Manusia dan Komputer', 'sks' => 3, 'schedule' => 'Rabu, 10.20–12.00 · R. 301', 'lecturer' => 'Nur Aini Lubis, S.Kom., M.Sc.', 'selected' => true],
        ['code' => 'IF207', 'name' => 'Rekayasa Perangkat Lunak', 'sks' => 3, 'schedule' => 'Kamis, 08.00–10.30 · R. 206', 'lecturer' => 'Ir. Bambang Setiawan, M.Kom.', 'selected' => true],
        ['code' => 'IF211', 'name' => 'Statistika', 'sks' => 3, 'schedule' => 'Sabtu, 08.00–10.00 · R. 103', 'lecturer' => 'Dewi Kurniasari, S.Si., M.Stat.', 'selected' => true],
        ['code' => 'IF215', 'name' => 'Pemrograman Perangkat Bergerak', 'sks' => 3, 'schedule' => 'Jumat, 13.00–15.30 · R. 305', 'lecturer' => 'Muhamad Ramli, S.Kom., M.Kom.', 'selected' => false],
    ];

    public function index(Request $request)
    {
        $allowed = ['pengisian_krs', 'menunggu_persetujuan', 'ditolak_dosen_pa', 'disetujui'];
        $state = (string) $request->query('state', 'pengisian_krs');
        if ($state === 'konfirmasi-ajukan') {
            $state = 'pengisian_krs';
        } elseif (! in_array($state, $allowed, true)) {
            $state = 'pengisian_krs';
        }

        $courses = $this->courses;
        if ($state === 'ditolak_dosen_pa') {
            foreach ($courses as &$course) {
                if ($course['code'] === 'IF215') {
                    $course['selected'] = true;
                }
            }
            unset($course);
        } elseif ($state === 'disetujui') {
            foreach ($courses as &$course) {
                if ($course['code'] === 'IF215') {
                    $course['selected'] = false;
                }
            }
            unset($course);
        }

        return view('krs.index', [
            'state' => $state,
            'courses' => $courses,
        ]);
    }

    public function pilihKelas(Request $request)
    {
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
        $requestedState = $request->query('state', 'default');
        $state = is_string($requestedState) && in_array($requestedState, $allowedStates, true)
            ? $requestedState
            : 'default';

        $offerings = [
            ['code' => 'IF201', 'name' => 'Pemrograman Web', 'schedule' => 'Senin, 07.30–10.00 · R. 204', 'sks' => 3, 'semester' => 4, 'status' => 'in_draft'],
            ['code' => 'IF203', 'name' => 'Basis Data', 'schedule' => 'Senin, 13.00–15.30 · Lab. Komputer 2', 'sks' => 3, 'semester' => 4, 'status' => 'in_draft'],
            ['code' => 'IF209', 'name' => 'Jaringan Komputer', 'schedule' => 'Selasa, 08.00–10.30 · R. 208', 'sks' => 3, 'semester' => 4, 'status' => 'available'],
            ['code' => 'IF205', 'name' => 'Interaksi Manusia dan Komputer', 'schedule' => 'Rabu, 10.20–12.00 · R. 301', 'sks' => 3, 'semester' => 4, 'status' => 'available'],
            ['code' => 'IF207', 'name' => 'Rekayasa Perangkat Lunak', 'schedule' => 'Kamis, 08.00–10.30 · R. 206', 'sks' => 3, 'semester' => 4, 'status' => 'available'],
            ['code' => 'IF211', 'name' => 'Statistika', 'schedule' => 'Sabtu, 08.00–10.00 · R. 103', 'sks' => 3, 'semester' => 4, 'status' => 'full'],
            ['code' => 'IF307', 'name' => 'Komputasi Awan', 'schedule' => 'Kamis, 08.00–10.30 · R. 210', 'sks' => 4, 'semester' => 6, 'status' => 'locked', 'note' => 'Prasyarat IF207 belum terpenuhi.'],
        ];

        if ($state === 'semester_antara') {
            $offerings = array_slice($offerings, 0, 2);
            foreach ($offerings as $index => &$offering) {
                $offering['status'] = $index === 0 ? 'in_draft' : 'available';
            }
            unset($offering);
        }

        if ($state === 'validasi_gagal') {
            foreach ($offerings as &$offering) {
                if ($offering['code'] === 'IF307') {
                    $offering['status'] = 'in_draft';
                }
            }
            unset($offering);
        }

        return view('krs.pilih-kelas', [
            'state' => $state,
            'offerings' => $offerings,
            'meta' => [
                'periode' => $state === 'semester_antara' ? 'semester-antara-2026' : '20261',
                'dosen_pa' => 'Dr. Rina Kartika, M.Kom.',
                'batas_waktu' => '21 Agu 2026, 23.59 WIB',
                'batas_sks' => $state === 'semester_antara' ? 9 : 24,
                'periode_lalu' => '20252',
                'ipk_lalu' => '3,20',
            ],
        ]);
    }

    public function submit(Request $request)
    {
        $nextState = $request->input('action') === 'approve_sim' ? 'disetujui' : 'menunggu_persetujuan';

        if ($nextState === 'disetujui') {
            return redirect()->route('dashboard', ['state' => 'default']);
        }

        return redirect()->route('krs.index', ['state' => 'menunggu_persetujuan'])
            ->with('krs_notice', 'KRS periode 20261 berhasil diajukan ke Dosen PA (Dr. Rina Kartika, M.Kom.).');
    }
}
