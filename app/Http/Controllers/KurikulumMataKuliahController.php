<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KurikulumMataKuliahController extends Controller
{
    private function seedMataKuliah(): array
    {
        return [
            [
                'code' => 'IF181',
                'name' => 'Algoritma dan Pemrograman',
                'name_en' => 'Algorithms and Programming',
                'prodi' => 'Teknik Informatika (S1)',
                'sks_teori' => 2,
                'sks_praktik' => 1,
                'sks' => 3,
                'type' => 'Wajib',
                'status' => 'Aktif',
                'prerequisite' => 'Tidak ada',
                'description' => 'Dasar logika pemrograman, struktur kontrol, fungsi, dan penyusunan algoritma.',
                'curriculum' => ['Kurikulum Informatika 2024 (Semester 1 · Wajib · 3 SKS)'],
            ],
            [
                'code' => 'IF182',
                'name' => 'Matematika Diskrit',
                'name_en' => 'Discrete Mathematics',
                'prodi' => 'Teknik Informatika (S1)',
                'sks_teori' => 3,
                'sks_praktik' => 0,
                'sks' => 3,
                'type' => 'Wajib',
                'status' => 'Aktif',
                'prerequisite' => 'Tidak ada',
                'description' => 'Logika proposisional, himpunan, relasi, fungsi, graf, dan kombinatorial.',
                'curriculum' => ['Kurikulum Informatika 2024 (Semester 1 · Wajib · 3 SKS)'],
            ],
            [
                'code' => 'IF201',
                'name' => 'Pemrograman Web',
                'name_en' => 'Web Programming',
                'prodi' => 'Teknik Informatika (S1)',
                'sks_teori' => 2,
                'sks_praktik' => 1,
                'sks' => 3,
                'type' => 'Wajib',
                'status' => 'Aktif',
                'prerequisite' => 'IF181 - Algoritma dan Pemrograman',
                'description' => 'Konsep dan praktik pengembangan aplikasi web, mulai dari antarmuka hingga integrasi basis data.',
                'curriculum' => ['Kurikulum Informatika 2024 (Semester 3 · Wajib · 3 SKS)'],
            ],
            [
                'code' => 'IF203',
                'name' => 'Basis Data',
                'name_en' => 'Database Systems',
                'prodi' => 'Teknik Informatika (S1)',
                'sks_teori' => 2,
                'sks_praktik' => 1,
                'sks' => 3,
                'type' => 'Wajib',
                'status' => 'Aktif',
                'prerequisite' => 'IF181 - Algoritma dan Pemrograman',
                'description' => 'Pemodelan data relasional, normalisasi, SQL, dan manajemen transaksi basis data.',
                'curriculum' => ['Kurikulum Informatika 2024 (Semester 3 · Wajib · 3 SKS)'],
            ],
            [
                'code' => 'IF205',
                'name' => 'Interaksi Manusia dan Komputer',
                'name_en' => 'Human Computer Interaction',
                'prodi' => 'Teknik Informatika (S1)',
                'sks_teori' => 2,
                'sks_praktik' => 1,
                'sks' => 3,
                'type' => 'Wajib',
                'status' => 'Aktif',
                'prerequisite' => 'Tidak ada',
                'description' => 'Prinsip perancangan antarmuka pengguna, usability, dan evaluasi pengalaman pengguna.',
                'curriculum' => ['Kurikulum Informatika 2024 (Semester 3 · Wajib · 3 SKS)'],
            ],
            [
                'code' => 'IF207',
                'name' => 'Rekayasa Perangkat Lunak',
                'name_en' => 'Software Engineering',
                'prodi' => 'Teknik Informatika (S1)',
                'sks_teori' => 3,
                'sks_praktik' => 0,
                'sks' => 3,
                'type' => 'Wajib',
                'status' => 'Aktif',
                'prerequisite' => 'IF203 - Basis Data',
                'description' => 'Siklus hidup pengembangan perangkat lunak, analisis kebutuhan, perancangan arsitektur, dan pengujian.',
                'curriculum' => ['Kurikulum Informatika 2024 (Semester 3 · Wajib · 3 SKS)'],
            ],
            [
                'code' => 'IF215',
                'name' => 'Pemrograman Perangkat Bergerak',
                'name_en' => 'Mobile Programming',
                'prodi' => 'Teknik Informatika (S1)',
                'sks_teori' => 2,
                'sks_praktik' => 1,
                'sks' => 3,
                'type' => 'Pilihan',
                'status' => 'Aktif',
                'prerequisite' => 'IF201 - Pemrograman Web',
                'description' => 'Pengembangan aplikasi mobile native/cross-platform, manajemen state, dan konsumsi API.',
                'curriculum' => ['Kurikulum Informatika 2024 (Semester 5 · Pilihan · 3 SKS)'],
            ],
            [
                'code' => 'IF301',
                'name' => 'Komputasi Awan',
                'name_en' => 'Cloud Computing',
                'prodi' => 'Teknik Informatika (S1)',
                'sks_teori' => 2,
                'sks_praktik' => 1,
                'sks' => 3,
                'type' => 'Pilihan',
                'status' => 'Nonaktif',
                'prerequisite' => 'Tidak ada',
                'description' => 'Arsitektur komputasi awan, virtualisasi, kontainerisasi, dan layanan terkelola.',
                'curriculum' => [],
            ],
        ];
    }

    private function seedKurikulum(): array
    {
        return [
            [
                'code' => 'KUR-2026',
                'name' => 'Kurikulum Informatika 2026',
                'prodi' => 'Teknik Informatika (S1)',
                'year' => 2026,
                'mk_count' => 0,
                'sks_total' => 0,
                'target_sks' => 144,
                'status' => 'Draft',
                'items' => [],
            ],
            [
                'code' => 'KUR-2024',
                'name' => 'Kurikulum Informatika 2024',
                'prodi' => 'Teknik Informatika (S1)',
                'year' => 2024,
                'mk_count' => 48,
                'sks_total' => 144,
                'target_sks' => 144,
                'status' => 'Aktif',
                'items' => [],
            ],
            [
                'code' => 'KUR-2020',
                'name' => 'Kurikulum Informatika 2020',
                'prodi' => 'Teknik Informatika (S1)',
                'year' => 2020,
                'mk_count' => 50,
                'sks_total' => 144,
                'target_sks' => 144,
                'status' => 'Arsip',
                'items' => [],
            ],
        ];
    }

    private function getMataKuliahList(): array
    {
        if (!session()->has('sia_mk_list')) {
            session(['sia_mk_list' => $this->seedMataKuliah()]);
        }

        return session('sia_mk_list', []);
    }

    private function getKurikulumList(): array
    {
        if (!session()->has('sia_kurikulum_list')) {
            session(['sia_kurikulum_list' => $this->seedKurikulum()]);
        }

        return session('sia_kurikulum_list', []);
    }

    public function indexKurikulum(Request $request)
    {
        $all = $this->getKurikulumList();
        $q = strtolower(trim((string) $request->query('q', '')));
        $prodi = (string) $request->query('prodi', 'Teknik Informatika (S1)');
        $status = (string) $request->query('status', 'Semua status');

        $filtered = collect($all)->filter(function ($item) use ($q, $prodi, $status) {
            if ($q !== '' && !str_contains(strtolower($item['code']), $q) && !str_contains(strtolower($item['name']), $q)) {
                return false;
            }
            if ($prodi !== '' && $prodi !== 'Semua prodi' && $item['prodi'] !== $prodi) {
                return false;
            }
            if ($status !== '' && $status !== 'Semua status' && $item['status'] !== $status) {
                return false;
            }
            return true;
        })->values()->all();

        $stats = [
            'total' => count($all),
            'aktif' => collect($all)->where('status', 'Aktif')->count(),
            'draft' => collect($all)->where('status', 'Draft')->count(),
            'arsip' => collect($all)->where('status', 'Arsip')->count(),
        ];

        return view('kurikulum.index', [
            'curriculums' => $filtered,
            'stats' => $stats,
            'filters' => compact('q', 'prodi', 'status'),
        ]);
    }

    public function createKurikulum()
    {
        $courses = $this->getMataKuliahList();
        $selectedCodes = ['IF181', 'IF182', 'IF201'];
        $semesters = [
            'IF181' => 'Semester 1',
            'IF182' => 'Semester 1',
            'IF201' => 'Semester 3',
        ];

        return view('kurikulum.create', [
            'courses' => $courses,
            'selectedCodes' => $selectedCodes,
            'semesters' => $semesters,
        ]);
    }

    public function storeKurikulum(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:30',
            'name' => 'required|string|max:150',
            'prodi' => 'required|string|max:100',
            'year' => 'required|integer|min:2000|max:2099',
            'target_sks' => 'required|integer|min:1|max:300',
            'selected_codes' => 'nullable|array',
            'semesters' => 'nullable|array',
        ]);

        $coursesByCode = collect($this->getMataKuliahList())->keyBy('code');
        $selectedCodes = $validated['selected_codes'] ?? [];
        $semesters = $validated['semesters'] ?? [];

        $sksSum = 0;
        $items = [];
        foreach ($selectedCodes as $code) {
            if ($coursesByCode->has($code)) {
                $mk = $coursesByCode->get($code);
                $sksSum += (int) $mk['sks'];
                $items[] = [
                    'code' => $code,
                    'name' => $mk['name'],
                    'sks' => (int) $mk['sks'],
                    'semester' => $semesters[$code] ?? 'Semester 1',
                ];
            }
        }

        $list = $this->getKurikulumList();
        array_unshift($list, [
            'code' => strtoupper(trim($validated['code'])),
            'name' => trim($validated['name']),
            'prodi' => $validated['prodi'],
            'year' => (int) $validated['year'],
            'mk_count' => count($items),
            'sks_total' => $sksSum,
            'target_sks' => (int) $validated['target_sks'],
            'status' => 'Draft',
            'items' => $items,
        ]);
        session(['sia_kurikulum_list' => $list]);

        return redirect()->route('kurikulum.index')->with('success', 'Kurikulum baru berhasil disimpan sebagai draft.');
    }

    public function showKurikulum(string $code)
    {
        $item = collect($this->getKurikulumList())->firstWhere('code', $code);
        abort_if(!$item, 404);

        return view('kurikulum.show', ['curriculum' => $item]);
    }

    public function editKurikulum(string $code)
    {
        $item = collect($this->getKurikulumList())->firstWhere('code', $code);
        abort_if(!$item, 404);

        return view('kurikulum.edit', [
            'curriculum' => $item,
            'courses' => $this->getMataKuliahList(),
        ]);
    }

    public function updateKurikulum(Request $request, string $code)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'prodi' => 'required|string|max:100',
            'year' => 'required|integer|min:2000|max:2099',
            'target_sks' => 'required|integer|min:1|max:300',
            'status' => 'required|in:Draft,Aktif,Arsip',
        ]);

        $list = collect($this->getKurikulumList())->map(function ($row) use ($code, $validated) {
            if ($row['code'] === $code) {
                $row['name'] = trim($validated['name']);
                $row['prodi'] = $validated['prodi'];
                $row['year'] = (int) $validated['year'];
                $row['target_sks'] = (int) $validated['target_sks'];
                $row['status'] = $validated['status'];
            }
            return $row;
        })->values()->all();

        session(['sia_kurikulum_list' => $list]);

        return redirect()->route('kurikulum.index')->with('success', "Kurikulum {$code} berhasil diperbarui.");
    }

    public function indexMataKuliah(Request $request)
    {
        $all = $this->getMataKuliahList();
        $q = strtolower(trim((string) $request->query('q', '')));
        $type = (string) $request->query('type', 'Semua jenis');
        $status = (string) $request->query('status', 'Semua status');
        $deleteCode = (string) $request->query('delete', '');

        $filtered = collect($all)->filter(function ($item) use ($q, $type, $status) {
            if ($q !== '' && !str_contains(strtolower($item['code']), $q) && !str_contains(strtolower($item['name']), $q)) {
                return false;
            }
            if ($type !== '' && $type !== 'Semua jenis' && $item['type'] !== $type) {
                return false;
            }
            if ($status !== '' && $status !== 'Semua status' && $item['status'] !== $status) {
                return false;
            }
            return true;
        })->values()->all();

        $deleteTarget = $deleteCode !== ''
            ? collect($all)->firstWhere('code', $deleteCode)
            : null;

        $stats = [
            'total' => count($all),
            'aktif' => collect($all)->where('status', 'Aktif')->count(),
            'wajib' => collect($all)->where('type', 'Wajib')->count(),
            'pilihan' => collect($all)->where('type', 'Pilihan')->count(),
        ];

        return view('mata-kuliah.index', [
            'courses' => $filtered,
            'stats' => $stats,
            'filters' => compact('q', 'type', 'status'),
            'deleteTarget' => $deleteTarget,
        ]);
    }

    public function createMataKuliah()
    {
        $courses = $this->getMataKuliahList();

        return view('mata-kuliah.create', [
            'prereqOptions' => $courses,
        ]);
    }

    public function storeMataKuliah(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:25',
            'name' => 'required|string|max:150',
            'name_en' => 'nullable|string|max:150',
            'prodi' => 'required|string|max:100',
            'sks_teori' => 'required|integer|min:0|max:20',
            'sks_praktik' => 'required|integer|min:0|max:20',
            'type' => 'required|in:Wajib,Pilihan',
            'status' => 'required|in:Aktif,Nonaktif',
            'prerequisite' => 'nullable|string|max:150',
            'description' => 'nullable|string|max:1000',
        ]);

        $code = strtoupper(trim($validated['code']));
        $teori = (int) $validated['sks_teori'];
        $praktik = (int) $validated['sks_praktik'];

        $list = $this->getMataKuliahList();
        $list[] = [
            'code' => $code,
            'name' => trim($validated['name']),
            'name_en' => trim((string) ($validated['name_en'] ?? '')),
            'prodi' => $validated['prodi'],
            'sks_teori' => $teori,
            'sks_praktik' => $praktik,
            'sks' => $teori + $praktik,
            'type' => $validated['type'],
            'status' => $validated['status'],
            'prerequisite' => $validated['prerequisite'] ?: 'Tidak ada',
            'description' => trim((string) ($validated['description'] ?? '')),
            'curriculum' => [],
        ];

        session(['sia_mk_list' => $list]);

        return redirect()->route('mata-kuliah.index')->with('success', "Mata kuliah {$code} berhasil ditambahkan.");
    }

    public function showMataKuliah(string $code)
    {
        $course = collect($this->getMataKuliahList())->firstWhere('code', $code);
        abort_if(!$course, 404);

        return view('mata-kuliah.show', ['course' => $course]);
    }

    public function editMataKuliah(string $code)
    {
        $all = $this->getMataKuliahList();
        $course = collect($all)->firstWhere('code', $code);
        abort_if(!$course, 404);

        return view('mata-kuliah.edit', [
            'course' => $course,
            'prereqOptions' => $all,
        ]);
    }

    public function updateMataKuliah(Request $request, string $code)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'name_en' => 'nullable|string|max:150',
            'prodi' => 'required|string|max:100',
            'sks_teori' => 'required|integer|min:0|max:20',
            'sks_praktik' => 'required|integer|min:0|max:20',
            'type' => 'required|in:Wajib,Pilihan',
            'status' => 'required|in:Aktif,Nonaktif',
            'prerequisite' => 'nullable|string|max:150',
            'description' => 'nullable|string|max:1000',
        ]);

        $teori = (int) $validated['sks_teori'];
        $praktik = (int) $validated['sks_praktik'];

        $list = collect($this->getMataKuliahList())->map(function ($row) use ($code, $validated, $teori, $praktik) {
            if ($row['code'] === $code) {
                $row['name'] = trim($validated['name']);
                $row['name_en'] = trim((string) ($validated['name_en'] ?? ''));
                $row['prodi'] = $validated['prodi'];
                $row['sks_teori'] = $teori;
                $row['sks_praktik'] = $praktik;
                $row['sks'] = $teori + $praktik;
                $row['type'] = $validated['type'];
                $row['status'] = $validated['status'];
                $row['prerequisite'] = $validated['prerequisite'] ?: 'Tidak ada';
                $row['description'] = trim((string) ($validated['description'] ?? ''));
            }
            return $row;
        })->values()->all();

        session(['sia_mk_list' => $list]);

        return redirect()->route('mata-kuliah.index')->with('success', "Perubahan mata kuliah {$code} berhasil disimpan.");
    }

    public function destroyMataKuliah(string $code)
    {
        $list = collect($this->getMataKuliahList())
            ->reject(fn ($row) => $row['code'] === $code)
            ->values()
            ->all();

        session(['sia_mk_list' => $list]);

        return redirect()->route('mata-kuliah.index')->with('success', "Mata kuliah {$code} telah dihapus.");
    }
}