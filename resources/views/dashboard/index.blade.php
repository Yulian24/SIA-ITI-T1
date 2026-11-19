@extends('layouts.app')

@section('title', 'Beranda')

@section('content')

@php
    $user = $user ?? [
        'name' => session('auth_user.name', 'Budi Santoso'),
        'nim' => session('auth_user.nim', '312020001'),
        'prodi' => session('auth_user.prodi', 'Teknik Informatika'),
        'role' => session('auth_user.role', 'Mahasiswa'),
    ];

    $name = $user['name'] ?? 'Nama Pengguna';
    $nim = $user['nim'] ?? '-';
    $prodi = $user['prodi'] ?? 'Teknik Informatika';
    $role = $user['role'] ?? 'Mahasiswa';
    $period = $period ?? '20261';
    $state = request('state', $state ?? 'default');

    $identity = strtolower($role) === 'mahasiswa'
        ? 'NIM ' . $nim . ' · ' . $prodi
        : $role;

    $classes = $classes ?? [
        [
            'name' => 'Pemrograman Web',
            'schedule' => 'Senin, 07.30–10.00',
            'room' => 'R. 201',
            'lecturer' => 'Dr. Ir. Ahmad Santoso, M.Kom.',
            'attendance' => 'Hadir 10/14',
            'color' => '#1d4ed8',
        ],
        [
            'name' => 'Basis Data',
            'schedule' => 'Senin, 13.00–15.30',
            'room' => 'Lab. Komputer 2',
            'lecturer' => 'Agus Prasetyo, S.T., M.T.',
            'attendance' => 'Hadir 11/14',
            'color' => '#15803d',
        ],
        [
            'name' => 'Jaringan Komputer',
            'schedule' => 'Selasa, 08.00–10.30',
            'room' => 'R. 302',
            'lecturer' => 'Yusuf Pratama, S.T., M.Kom.',
            'attendance' => 'Hadir 9/14',
            'color' => '#0f766e',
        ],
        [
            'name' => 'Interaksi Manusia dan Komputer',
            'schedule' => 'Rabu, 10.30–12.10',
            'room' => 'R. 104',
            'lecturer' => 'Nurul Huda, S.Kom., M.Cs.',
            'attendance' => 'Hadir 12/14',
            'color' => '#be185d',
        ],
        [
            'name' => 'Rekayasa Perangkat Lunak',
            'schedule' => 'Kamis, 08.00–10.30',
            'room' => 'R. 205',
            'lecturer' => 'Ir. Bambang Setiawan, M.Kom.',
            'attendance' => 'Hadir 10/14',
            'color' => '#b45309',
        ],
        [
            'name' => 'Statistika',
            'schedule' => 'Sabtu, 08.00–10.30',
            'room' => 'R. 102',
            'lecturer' => 'Dewi Kartika Sari, S.Si., M.Si.',
            'attendance' => 'Hadir 8/14',
            'color' => '#7e22ce',
        ],
    ];

    $weekDays = [
        ['label' => 'Sen', 'date' => '2', 'has_event' => true, 'active' => true],
        ['label' => 'Sel', 'date' => '3', 'has_event' => true, 'active' => false],
        ['label' => 'Rab', 'date' => '4', 'has_event' => true, 'active' => false],
        ['label' => 'Kam', 'date' => '5', 'has_event' => true, 'active' => false],
        ['label' => 'Jum', 'date' => '6', 'has_event' => false, 'active' => false],
        ['label' => 'Sab', 'date' => '7', 'has_event' => true, 'active' => false],
        ['label' => 'Min', 'date' => '8', 'has_event' => false, 'active' => false],
    ];

    $schedules = $schedules ?? [
        [
            'title' => 'Pemrograman Web',
            'time_room' => '07.30–10.00 · R. 201',
            'accent' => '#1d4ed8',
        ],
        [
            'title' => 'Basis Data',
            'time_room' => '13.00–15.30 · Lab. Komputer 2',
            'accent' => '#15803d',
        ],
    ];

    $tasks = $tasks ?? [
        [
            'course' => 'Pemrograman Web',
            'title' => 'Tugas 2: REST API Katalog Buku',
            'deadline' => 'Tenggat Senin, 2 Nov 2026, 23.59 WIB',
            'accent' => '#1d4ed8',
        ],
        [
            'course' => 'Basis Data',
            'title' => 'Laporan Praktikum 5: Normalisasi',
            'deadline' => 'Tenggat Rabu, 4 Nov 2026, 12.00 WIB',
            'accent' => '#15803d',
        ],
        [
            'course' => 'Interaksi Manusia dan Komputer',
            'title' => 'Rancangan Wireframe Aplikasi',
            'deadline' => 'Tenggat Minggu, 15 Nov 2026, 23.59 WIB',
            'accent' => '#be185d',
        ],
    ];
@endphp

<div class="sia-dashboard-page">

    <nav class="sia-breadcrumb" aria-label="Breadcrumb">
        <span>Beranda</span>
    </nav>

    <div class="sia-page-head">
        <div>
            <h1 class="sia-page-title">Beranda</h1>
            <p class="sia-page-subtitle">{{ $name }} · {{ $identity }}</p>
        </div>
    </div>

    <div class="sia-dashboard-grid">

        {{-- KOLOM KIRI: KELAS PERIODE --}}
        <section class="sia-panel sia-panel-classes">
            <div class="sia-panel-head">
                <div>
                    <h2 class="sia-panel-title">Kelas periode {{ $period }}</h2>
                    <p class="sia-panel-desc">Ringkasan mata kuliah dan kehadiran pada periode aktif</p>
                </div>

                @if($state === 'tidak_ada_kelas_hari_ini')
                    <a href="#jadwal-pekan-ini" class="sia-link-action">Lihat jadwal</a>
                @endif
            </div>

            @switch($state)
                @case('loading')
                    <div class="sia-course-grid">
                        @for($i = 0; $i < 6; $i++)
                            <div class="sia-skeleton sia-skeleton-course"></div>
                        @endfor
                    </div>
                    @break

                @case('error_500')
                    <div class="sia-banner sia-banner-danger">
                        <div>
                            <strong>Gagal memuat kelas</strong>
                            <p>Server gagal memproses permintaan. Coba lagi beberapa saat.</p>
                        </div>
                    </div>
                    <button type="button" class="sia-btn-secondary" onclick="window.location.reload()">
                        Coba lagi
                    </button>
                    @break

                @case('menunggu_persetujuan')
                    <div class="sia-banner sia-banner-info sia-banner-row">
                        <div>
                            <strong>KRS Anda menunggu persetujuan dosen PA</strong>
                            <p>Silakan pantau status persetujuan KRS secara berkala.</p>
                        </div>
                        <a href="#status-krs" class="sia-btn-primary">Lihat status KRS</a>
                    </div>

                    <div class="sia-empty-state">
                        <strong>Kelas belum tersedia</strong>
                        <p>Daftar kelas, jadwal, dan presensi muncul setelah KRS {{ $period }} disetujui dosen PA.</p>
                    </div>
                    @break

                @case('ditolak')
                    <div class="sia-banner sia-banner-danger sia-banner-row">
                        <div>
                            <strong>KRS {{ $period }} Ditolak Dosen PA</strong>
                            <p>Perbaiki KRS sesuai catatan dosen PA lalu ajukan ulang.</p>
                        </div>
                        <a href="#perbaiki-krs" class="sia-btn-primary">Perbaiki KRS</a>
                    </div>

                    <div class="sia-empty-state">
                        <strong>Kelas belum tersedia</strong>
                        <p>Perbaiki KRS sesuai catatan dosen PA lalu ajukan ulang.</p>
                    </div>
                    @break

                @case('kosong')
                    <div class="sia-banner sia-banner-info sia-banner-row">
                        <div>
                            <strong>Periode KRS {{ $period }} dibuka</strong>
                            <p>Silakan ajukan KRS untuk memilih mata kuliah.</p>
                        </div>
                        <a href="#isi-krs" class="sia-btn-primary">Isi KRS</a>
                    </div>

                    <div class="sia-empty-state">
                        <strong>Belum ada kelas</strong>
                        <p>Silakan ajukan KRS {{ $period }} untuk memilih kelas.</p>
                    </div>
                    @break

                @default
                    <div class="sia-course-grid">
                        @forelse($classes as $class)
                            <article class="sia-course-card" style="--course-color: {{ $class['color'] ?? '#1d4ed8' }};">
                                <div class="sia-course-top">
                                    <h3>{{ $class['name'] }}</h3>
                                </div>
                                <p class="sia-course-schedule">{{ $class['schedule'] }}</p>
                                <p class="sia-course-room">{{ $class['room'] }}</p>
                                <div class="sia-course-bottom">
                                    <span>{{ $class['lecturer'] }}</span>
                                    <strong>{{ $class['attendance'] }}</strong>
                                </div>
                            </article>
                        @empty
                            <div class="sia-empty-state">
                                <strong>Belum ada kelas</strong>
                                <p>Data kelas pada periode ini belum tersedia.</p>
                            </div>
                        @endforelse
                    </div>
            @endswitch
        </section>

        {{-- KOLOM KANAN: JADWAL PEKAN INI & PERLU DIKERJAKAN --}}
        <aside class="sia-dashboard-side">

            <section id="jadwal-pekan-ini" class="sia-panel">
                <div class="sia-panel-head">
                    <h2 class="sia-panel-title">Jadwal pekan ini</h2>
                    <span class="sia-panel-meta">Senin, 2 Nov 2026</span>
                </div>

                @if($state === 'loading')
                    <div class="sia-skeleton sia-skeleton-line"></div>
                    <div class="sia-skeleton sia-skeleton-line"></div>
                @else
                    <div class="sia-week-strip">
                        @foreach($weekDays as $day)
                            <div class="sia-week-day {{ !empty($day['active']) ? 'is-active' : '' }}">
                                <span class="sia-day-name">{{ $day['label'] }}</span>
                                <span class="sia-day-num">{{ $day['date'] }}</span>
                                <span class="sia-day-dot {{ !empty($day['has_event']) ? 'has-event' : '' }}"></span>
                            </div>
                        @endforeach
                    </div>

                    @if($state === 'tidak_ada_kelas_hari_ini' || empty($schedules))
                        <div class="sia-empty-state sia-empty-compact">
                            <strong>Tidak ada kelas hari ini</strong>
                            <p>Belum ada jadwal perkuliahan pada hari yang dipilih.</p>
                        </div>
                    @else
                        <ul class="sia-agenda-list">
                            @foreach($schedules as $schedule)
                                <li class="sia-agenda-item" style="--item-accent: {{ $schedule['accent'] ?? '#1d4ed8' }};">
                                    <strong>{{ $schedule['title'] }}</strong>
                                    <span>{{ $schedule['time_room'] }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                @endif
            </section>

            <section class="sia-panel">
                <div class="sia-panel-head">
                    <div class="sia-title-with-badge">
                        <h2 class="sia-panel-title">Perlu dikerjakan</h2>
                        <span class="sia-count-pill">{{ count($tasks) }}</span>
                    </div>
                    <span class="sia-tag-outline">USULAN</span>
                </div>

                @if($state === 'loading')
                    <div class="sia-skeleton sia-skeleton-line"></div>
                    <div class="sia-skeleton sia-skeleton-line"></div>
                @elseif(empty($tasks))
                    <div class="sia-empty-state sia-empty-compact">
                        <strong>Tidak ada tugas</strong>
                        <p>Belum ada tugas yang mendekati tenggat.</p>
                    </div>
                @else
                    <ul class="sia-task-list">
                        @foreach($tasks as $task)
                            <li class="sia-task-item" style="--item-accent: {{ $task['accent'] ?? '#1d4ed8' }};">
                                <small>{{ $task['course'] }}</small>
                                <strong>{{ $task['title'] }}</strong>
                                <span>{{ $task['deadline'] }}</span>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </section>

        </aside>

    </div>

</div>

@endsection