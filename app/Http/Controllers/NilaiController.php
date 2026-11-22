<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NilaiController extends Controller
{
    private function getKhsPeriods(): array
    {
        return [
            '20252' => [
                'code' => '20252',
                'label' => '20252 · Genap 2025/2026',
                'title' => 'Kartu Hasil Studi · 20252 · Genap 2025/2026',
                'sub' => '7 mata kuliah · 20 SKS · semua nilai final',
                'pill' => 'Semua nilai final',
                'pill_type' => 'ok',
                'can_download' => true,
                'ips' => '3,55',
                'ips_sub' => '20 SKS · 7 mata kuliah',
                'ipk' => '3,45',
                'ipk_sub' => 'Sampai periode 20252 · 42 SKS',
                'sks_lulus' => '42',
                'sks_sub' => 'Dari 42 SKS yang diambil',
                'rows' => [
                    ['code' => 'IF102', 'name' => 'Kalkulus II', 'sks' => 3, 'grade' => 'B', 'weight' => '3,00', 'total' => '9,00', 'status' => 'Final', 'note' => ''],
                    ['code' => 'IF104', 'name' => 'Algoritma dan Struktur Data', 'sks' => 3, 'grade' => 'A', 'weight' => '4,00', 'total' => '12,00', 'status' => 'Final', 'note' => ''],
                    ['code' => 'IF106', 'name' => 'Pemrograman Berorientasi Objek', 'sks' => 3, 'grade' => 'A', 'weight' => '4,00', 'total' => '12,00', 'status' => 'Final', 'note' => ''],
                    ['code' => 'IF108', 'name' => 'Sistem Digital', 'sks' => 3, 'grade' => 'B', 'weight' => '3,00', 'total' => '9,00', 'status' => 'Final', 'note' => ''],
                    ['code' => 'IF110', 'name' => 'Aljabar Linear', 'sks' => 3, 'grade' => 'AB', 'weight' => '3,50', 'total' => '10,50', 'status' => 'Final', 'note' => ''],
                    ['code' => 'IF112', 'name' => 'Bahasa Inggris II', 'sks' => 2, 'grade' => 'A', 'weight' => '4,00', 'total' => '8,00', 'status' => 'Final', 'note' => ''],
                    ['code' => 'IF114', 'name' => 'Arsitektur Komputer', 'sks' => 3, 'grade' => 'AB', 'weight' => '3,50', 'total' => '10,50', 'status' => 'Final', 'note' => ''],
                ],
                'footer_left' => 'IP semester = jumlah (SKS × bobot) ÷ jumlah SKS. Skala nilai huruf, bobot, dan pembulatan 2 desimal adalah USULAN; aturannya belum ditetapkan PO.',
                'footer_right' => '71,00 ÷ 20 SKS = IP 3,55',
            ],
            '20261' => [
                'code' => '20261',
                'label' => '20261 · Ganjil 2026/2027',
                'title' => 'Kartu Hasil Studi · 20261 · Ganjil 2026/2027',
                'sub' => '6 mata kuliah · 18 SKS · KRS disetujui 14 Agu 2026',
                'pill' => 'Belum ada nilai',
                'pill_type' => 'muted',
                'can_download' => false,
                'ips' => '–',
                'ips_sub' => 'Belum ada nilai',
                'ipk' => '3,45',
                'ipk_sub' => 'Sampai periode 20252 · 42 SKS',
                'sks_lulus' => '42',
                'sks_sub' => 'Dari 42 SKS yang diambil',
                'rows' => [],
                'footer_left' => '',
                'footer_right' => '',
            ],
        ];
    }

    public function index(Request $request)
    {
        $allowedStates = ['default', 'nilai_belum_final', 'belum_ada_nilai', 'loading', 'error_500'];
        $state = (string) $request->query('state', 'default');
        if (! in_array($state, $allowedStates, true)) {
            $state = 'default';
        }

        $tab = (string) $request->query('tab', 'khs');
        if (! in_array($tab, ['khs', 'transkrip'], true)) {
            $tab = 'khs';
        }

        $defaultPeriode = $state === 'belum_ada_nilai' ? '20261' : ($state === 'nilai_belum_final' ? '20261' : '20252');
        $periode = (string) $request->query('periode', $defaultPeriode);
        $periods = $this->getKhsPeriods();
        if (! array_key_exists($periode, $periods)) {
            $periode = '20252';
        }

        $currentKhs = $periods[$periode];

        if ($state === 'nilai_belum_final' && $periode === '20261') {
            $currentKhs = [
                'code' => '20261',
                'label' => '20261 · Ganjil 2026/2027',
                'title' => 'Kartu Hasil Studi · 20261 · Ganjil 2026/2027',
                'sub' => '6 mata kuliah · 18 SKS · 3 dari 6 nilai final',
                'pill' => '3 dari 6 nilai final',
                'pill_type' => 'warn',
                'can_download' => false,
                'ips' => '–',
                'ips_sub' => 'Menunggu 3 nilai final',
                'ipk' => '3,45',
                'ipk_sub' => 'Sampai periode 20252 · 20261 belum dihitung',
                'sks_lulus' => '42',
                'sks_sub' => 'Belum termasuk periode 20261',
                'rows' => [
                    ['code' => 'IF201', 'name' => 'Pemrograman Web', 'sks' => 3, 'grade' => 'A', 'weight' => '4,00', 'total' => '12,00', 'status' => 'Final', 'note' => ''],
                    ['code' => 'IF203', 'name' => 'Basis Data', 'sks' => 3, 'grade' => '–', 'weight' => '–', 'total' => '–', 'status' => 'Belum final', 'note' => ''],
                    ['code' => 'IF205', 'name' => 'Interaksi Manusia dan Komputer', 'sks' => 3, 'grade' => '–', 'weight' => '–', 'total' => '–', 'status' => 'Belum final', 'note' => ''],
                    ['code' => 'IF207', 'name' => 'Rekayasa Perangkat Lunak', 'sks' => 3, 'grade' => 'AB', 'weight' => '3,50', 'total' => '10,50', 'status' => 'Final', 'note' => ''],
                    ['code' => 'IF209', 'name' => 'Jaringan Komputer', 'sks' => 3, 'grade' => '–', 'weight' => '–', 'total' => '–', 'status' => 'Belum final', 'note' => ''],
                    ['code' => 'IF211', 'name' => 'Statistika', 'sks' => 3, 'grade' => 'E', 'weight' => '0,00', 'total' => '0,00', 'status' => 'Final', 'note' => 'TIDAK LULUS'],
                ],
                'footer_left' => 'IP semester dihitung dari nilai final dan SKS. Nilai yang belum final ditampilkan tanpa huruf; IP semester muncul setelah semua nilai final (USULAN).',
                'footer_right' => '3 nilai belum final',
            ];
        }

        $transcriptGroups = [
            [
                'heading' => '20251 · Ganjil 2025/2026 · semester 1',
                'summary' => '22 SKS · IP 3,36',
                'rows' => [
                    ['code' => 'IF101', 'name' => 'Kalkulus I', 'sks' => 3, 'grade' => 'BC', 'weight' => '2,50', 'total' => '7,50', 'note' => ''],
                    ['code' => 'IF103', 'name' => 'Dasar Pemrograman', 'sks' => 4, 'grade' => 'A', 'weight' => '4,00', 'total' => '16,00', 'note' => ''],
                    ['code' => 'IF105', 'name' => 'Pengantar Teknologi Informasi', 'sks' => 3, 'grade' => 'AB', 'weight' => '3,50', 'total' => '10,50', 'note' => ''],
                    ['code' => 'IF107', 'name' => 'Matematika Diskrit', 'sks' => 3, 'grade' => 'B', 'weight' => '3,00', 'total' => '9,00', 'note' => ''],
                    ['code' => 'IF109', 'name' => 'Fisika Dasar', 'sks' => 3, 'grade' => 'B', 'weight' => '3,00', 'total' => '9,00', 'note' => ''],
                    ['code' => 'IF111', 'name' => 'Bahasa Inggris I', 'sks' => 2, 'grade' => 'A', 'weight' => '4,00', 'total' => '8,00', 'note' => ''],
                    ['code' => 'UN101', 'name' => 'Pendidikan Pancasila', 'sks' => 2, 'grade' => 'AB', 'weight' => '3,50', 'total' => '7,00', 'note' => ''],
                    ['code' => 'UN103', 'name' => 'Bahasa Indonesia', 'sks' => 2, 'grade' => 'AB', 'weight' => '3,50', 'total' => '7,00', 'note' => ''],
                ],
            ],
            [
                'heading' => '20252 · Genap 2025/2026 · semester 2',
                'summary' => '20 SKS · IP 3,55',
                'rows' => [
                    ['code' => 'IF102', 'name' => 'Kalkulus II', 'sks' => 3, 'grade' => 'B', 'weight' => '3,00', 'total' => '9,00', 'note' => ''],
                    ['code' => 'IF104', 'name' => 'Algoritma dan Struktur Data', 'sks' => 3, 'grade' => 'A', 'weight' => '4,00', 'total' => '12,00', 'note' => ''],
                    ['code' => 'IF106', 'name' => 'Pemrograman Berorientasi Objek', 'sks' => 3, 'grade' => 'A', 'weight' => '4,00', 'total' => '12,00', 'note' => ''],
                    ['code' => 'IF108', 'name' => 'Sistem Digital', 'sks' => 3, 'grade' => 'B', 'weight' => '3,00', 'total' => '9,00', 'note' => ''],
                    ['code' => 'IF110', 'name' => 'Aljabar Linear', 'sks' => 3, 'grade' => 'AB', 'weight' => '3,50', 'total' => '10,50', 'note' => ''],
                    ['code' => 'IF112', 'name' => 'Bahasa Inggris II', 'sks' => 2, 'grade' => 'A', 'weight' => '4,00', 'total' => '8,00', 'note' => ''],
                    ['code' => 'IF114', 'name' => 'Arsitektur Komputer', 'sks' => 3, 'grade' => 'AB', 'weight' => '3,50', 'total' => '10,50', 'note' => ''],
                ],
            ],
        ];

        return view('nilai.index', compact('state', 'tab', 'periode', 'periods', 'currentKhs', 'transcriptGroups'));
    }
}
