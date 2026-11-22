<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PresensiController extends Controller
{
    private function getRecapRows(string $state): array
    {
        $webMeetings = $state === 'tercatat_tidak_hadir' ? 11 : 10;
        $webPresent = 9;
        $webSick = 1;
        $webAbsent = $state === 'tercatat_tidak_hadir' ? 1 : 0;
        $webPct = number_format(($webPresent / $webMeetings) * 100, 2, ',', '.').'%';

        return [
            ['code' => 'IF201', 'name' => 'Pemrograman Web', 'hadir' => $webPresent, 'sakit' => $webSick, 'izin' => 0, 'alpa' => $webAbsent, 'pertemuan' => $webMeetings, 'kehadiran' => $webPct, 'syarat' => 'Memenuhi'],
            ['code' => 'IF203', 'name' => 'Basis Data', 'hadir' => 10, 'sakit' => 0, 'izin' => 0, 'alpa' => 0, 'pertemuan' => 10, 'kehadiran' => '100%', 'syarat' => 'Memenuhi'],
            ['code' => 'IF209', 'name' => 'Jaringan Komputer', 'hadir' => 8, 'sakit' => 1, 'izin' => 1, 'alpa' => 0, 'pertemuan' => 10, 'kehadiran' => '80%', 'syarat' => 'Memenuhi'],
            ['code' => 'IF205', 'name' => 'Interaksi Manusia dan Komputer', 'hadir' => 6, 'sakit' => 1, 'izin' => 1, 'alpa' => 2, 'pertemuan' => 10, 'kehadiran' => '60%', 'syarat' => 'Belum memenuhi'],
            ['code' => 'IF207', 'name' => 'Rekayasa Perangkat Lunak', 'hadir' => 10, 'sakit' => 0, 'izin' => 0, 'alpa' => 0, 'pertemuan' => 10, 'kehadiran' => '100%', 'syarat' => 'Memenuhi'],
            ['code' => 'IF211', 'name' => 'Statistika', 'hadir' => 7, 'sakit' => 1, 'izin' => 0, 'alpa' => 2, 'pertemuan' => 10, 'kehadiran' => '70%', 'syarat' => 'Belum memenuhi'],
        ];
    }

    public function index(Request $request)
    {
        $allowedStates = [
            'default',
            'belum_diprensensi',
            'tercatat_tidak_hadir',
            'tidak_ada_kelas',
            'loading',
            'error_500',
        ];

        $state = (string) $request->query('state', 'default');
        if (! in_array($state, $allowedStates, true)) {
            $state = 'default';
        }

        $todayClasses = [];
        $subtitle = 'Periode 20261 · Senin, 2 Nov 2026, 10.15 WIB';

        if ($state === 'default') {
            $todayClasses = [
                ['name' => 'Pemrograman Web', 'meta' => 'Pertemuan 11 · 07.30–10.00 · R. 204 · Dr. Rina Kartika, M.Kom.', 'note' => 'Sudah dipresensi dosen', 'badge' => 'Hadir', 'badge_type' => 'ok'],
                ['name' => 'Basis Data', 'meta' => 'Pertemuan 11 · 13.00–15.30 · Lab. Komputer 2 · Agus Prasetyo, S.T., M.T.', 'note' => 'Kelas mulai 13.00', 'badge' => 'Belum dimulai', 'badge_type' => 'muted'],
            ];
        } elseif ($state === 'belum_diprensensi') {
            $subtitle = 'Periode 20261 · Senin, 2 Nov 2026, 08.10 WIB';
            $todayClasses = [
                ['name' => 'Pemrograman Web', 'meta' => 'Pertemuan 11 · 07.30–10.00 · R. 204 · Dr. Rina Kartika, M.Kom.', 'note' => 'Kelas sedang berlangsung. Dosen belum mencatat kehadiran pertemuan ini.', 'badge' => 'Belum dipresensi', 'badge_type' => 'warn'],
                ['name' => 'Basis Data', 'meta' => 'Pertemuan 11 · 13.00–15.30 · Lab. Komputer 2 · Agus Prasetyo, S.T., M.T.', 'note' => 'Kelas mulai 13.00', 'badge' => 'Belum dimulai', 'badge_type' => 'muted'],
            ];
        } elseif ($state === 'tercatat_tidak_hadir') {
            $todayClasses = [
                ['name' => 'Pemrograman Web', 'meta' => 'Pertemuan 11 · 07.30–10.00 · R. 204 · Dr. Rina Kartika, M.Kom.', 'note' => 'Dicatat tidak hadir di pertemuan 11. Temui dosen pengampu secara langsung.', 'badge' => 'Alfa', 'badge_type' => 'err'],
                ['name' => 'Basis Data', 'meta' => 'Pertemuan 11 · 13.00–15.30 · Lab. Komputer 2 · Agus Prasetyo, S.T., M.T.', 'note' => 'Kelas mulai 13.00', 'badge' => 'Belum dimulai', 'badge_type' => 'muted'],
            ];
        } elseif ($state === 'tidak_ada_kelas') {
            $subtitle = 'Periode 20261 · Jumat, 6 Nov 2026, 09.15 WIB';
        }

        return view('presensi.index', [
            'state' => $state,
            'subtitle' => $subtitle,
            'todayClasses' => $todayClasses,
            'recaps' => $this->getRecapRows($state),
        ]);
    }

    public function show(string $code)
    {
        $recap = collect($this->getRecapRows('default'))->firstWhere('code', strtoupper($code));
        abort_if(! $recap, 404);

        $meetings = [
            ['no' => 1, 'date' => 'Sen, 24 Agu 2026', 'status' => 'Hadir'],
            ['no' => 2, 'date' => 'Sen, 31 Agu 2026', 'status' => 'Hadir'],
            ['no' => 3, 'date' => 'Sen, 7 Sep 2026', 'status' => 'Hadir'],
            ['no' => 4, 'date' => 'Sen, 14 Sep 2026', 'status' => 'Sakit'],
            ['no' => 5, 'date' => 'Sen, 21 Sep 2026', 'status' => 'Hadir'],
            ['no' => 6, 'date' => 'Sen, 28 Sep 2026', 'status' => 'Hadir'],
            ['no' => 7, 'date' => 'Sen, 5 Okt 2026', 'status' => 'Hadir'],
            ['no' => 8, 'date' => 'Sen, 12 Okt 2026', 'status' => 'Hadir'],
            ['no' => 9, 'date' => 'Sen, 19 Okt 2026', 'status' => 'Hadir'],
            ['no' => 10, 'date' => 'Sen, 26 Okt 2026', 'status' => 'Hadir'],
            ['no' => 11, 'date' => 'Sen, 2 Nov 2026', 'status' => 'Hadir'],
        ];

        return view('presensi.show', [
            'recap' => $recap,
            'meetings' => $meetings,
        ]);
    }
}
