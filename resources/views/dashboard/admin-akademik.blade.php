
@extends('layouts.app')

@section('title', 'Dashboard Admin Akademik')

@section('content')

@php
    $name = $user['name'] ?? 'Wulan Sari Dewi';
    $period = $period ?? '20261';

    $activities = [
        [
            'title' => 'Periode 20261 diaktifkan',
            'time' => 'Hari ini, 09.42',
        ],
        [
            'title' => 'Data mahasiswa Aditya Pratama ditambahkan',
            'time' => 'Hari ini, 09.30',
        ],
        [
            'title' => 'Email dosen Budi Santoso diperbarui',
            'time' => 'Hari ini, 08.54',
        ],
        [
            'title' => 'Akun Pengelola Prodi diberikan akses',
            'time' => 'Kemarin, 15.20',
        ],
    ];

    $masterData = [
        [
            'title' => 'Mahasiswa',
            'description' => 'Data dan status mahasiswa',
            'url' => route('mahasiswa.index'),
        ],
        [
            'title' => 'Dosen',
            'description' => 'Profil dan data dosen',
            'url' => '#',
        ],
        [
            'title' => 'Program Studi',
            'description' => 'Kode, jenjang, dan status',
            'url' => '#',
        ],
        [
            'title' => 'Periode Akademik',
            'description' => 'Jadwal dan periode aktif',
            'url' => '#',
        ],
        [
            'title' => 'Akun & Role',
            'description' => 'Akun dan hak akses',
            'url' => '#',
        ],
    ];
@endphp

<div class="baak-page">

    <div class="baak-breadcrumb">Dashboard</div>

    <div class="baak-heading">
        <h1>Dashboard</h1>
        <p>
            {{ $name }} · BAAK / Admin akademik · Periode {{ $period }}
        </p>
    </div>

    <nav class="baak-tabs">
        <a href="{{ route('dashboard') }}" class="active">Dashboard</a>
        <a href="{{ route('mahasiswa.index') }}">Mahasiswa</a>
        <a href="#">Dosen</a>
        <a href="#">Program Studi</a>
        <a href="#">Periode Akademik</a>
        <a href="#">Akun & Role</a>
    </nav>

    <div class="baak-stat-grid">
        <div class="baak-stat-card">
            <span>Mahasiswa</span>
            <strong>4</strong>
            <small>3 aktif · 1 nonaktif</small>
        </div>

        <div class="baak-stat-card">
            <span>Dosen</span>
            <strong>4</strong>
            <small>3 aktif · 1 nonaktif</small>
        </div>

        <div class="baak-stat-card">
            <span>Program studi</span>
            <strong>4</strong>
            <small>Seluruh program studi aktif</small>
        </div>

        <div class="baak-stat-card">
            <span>Periode aktif</span>
            <strong>{{ $period }}</strong>
            <small>Ganjil · 2026/2027</small>
        </div>
    </div>

    <div class="baak-dashboard-grid">

        <section class="baak-activity-card">
            <h2>Aktivitas terbaru</h2>
            <p class="baak-section-description">
                Riwayat perubahan data akademik
            </p>

            <div class="baak-activity-list">
                @foreach ($activities as $activity)
                    <div class="baak-activity-item">
                        <strong>{{ $activity['title'] }}</strong>
                        <small>{{ $name }} · {{ $activity['time'] }}</small>
                    </div>
                @endforeach
            </div>
        </section>

        <aside class="baak-master-card">
            <h2>Kelola master data</h2>

            <div class="baak-master-list">
                @foreach ($masterData as $item)
                    <a href="{{ $item['url'] }}" class="baak-master-item">
                        <strong>{{ $item['title'] }} →</strong>
                        <small>{{ $item['description'] }}</small>
                    </a>
                @endforeach
            </div>
        </aside>

    </div>

    <div class="baak-page-footer">
        <span>SIA ITI · Tim 1 — User Management & Master Data</span>
        <span>Prototype UI/UX · Data contoh</span>
    </div>

</div>

@endsection
