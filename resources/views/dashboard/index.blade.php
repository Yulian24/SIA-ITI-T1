
@extends('layouts.app')

@section('title', 'Beranda')

@section('content')

@php
    $name = $user['name'] ?? 'Nama Pengguna';
    $nim = $user['nim'] ?? '-';
    $role = $user['role'] ?? 'Mahasiswa';
    $period = $period ?? '20261';

    $identity = strtolower($role) === 'mahasiswa'
        ? 'NIM ' . $nim
        : $role;
@endphp

<div class="sia-home">

    <div class="sia-home-breadcrumb">
        <span>Beranda</span>
    </div>

    <section class="sia-home-content">
        <div class="sia-home-welcome">
            <div class="sia-home-eyebrow">SISTEM INFORMASI AKADEMIK</div>

            <h1>Beranda</h1>

            <p class="sia-home-description">
                Selamat datang di Sistem Informasi Akademik
                Institut Teknologi Indonesia.
            </p>

            <div class="sia-user-summary">
                <div class="sia-user-avatar">
                    {{ strtoupper(substr($name, 0, 1)) }}
                </div>

                <div class="sia-user-information">
                    <strong>{{ $name }}</strong>
                    <span>{{ $identity }}</span>
                </div>

                <span class="sia-user-status">
                    <span></span>
                    Aktif
                </span>
            </div>
        </div>

        <div class="sia-home-period-card">
            <div class="sia-period-card-icon">
                <svg viewBox="0 0 24 24" aria-hidden="true">
                    <rect x="3" y="5" width="18" height="16" rx="2"/>
                    <path d="M16 3v4M8 3v4M3 10h18"/>
                </svg>
            </div>

            <div>
                <span>PERIODE AKADEMIK AKTIF</span>
                <strong>{{ $period }}</strong>
                <small>Semester akademik sedang berlangsung</small>
            </div>
        </div>
    </section>

</div>

@endsection
