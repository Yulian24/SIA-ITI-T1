
@extends('layouts.app')

@section('title', 'Profil')

@section('content')

@php
    $name = $profile['name'] ?? 'Nama Mahasiswa';
@endphp

<div class="profile-page">

    {{-- Breadcrumb --}}
    <div class="breadcrumb">
        <a href="{{ route('dashboard') }}">Beranda</a>
        <span>/</span>
        <span>Profil</span>
    </div>

    {{-- Header Profil --}}
    <div class="profile-heading">
        <div>
            <h1>Profil</h1>
            <p>Akun dan identitas akademik {{ $name }}</p>
        </div>

        <a href="{{ route('profile.edit') }}" class="btn btn-primary">
            Ubah kontak
        </a>
    </div>

    {{-- Notifikasi --}}
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- Konten Profil --}}
    <div class="profile-columns">

        {{-- Identitas Akademik --}}
        <section class="profile-card academic-card">

            <div class="profile-card-heading">
                <div>
                    <h2>Identitas akademik</h2>
                    <p>
                        Dikelola oleh BAAK. Hubungi BAAK bila ada data yang keliru.
                    </p>
                </div>
            </div>

            <div class="profile-data-list">

                <div class="profile-data">
                    <label>Nama lengkap</label>
                    <div class="profile-value">
                        {{ $name }}
                    </div>
                </div>

                <div class="profile-data">
                    <label>NIM</label>
                    <div class="profile-value">
                        {{ $profile['nim'] ?? '-' }}
                    </div>
                </div>

                <div class="profile-data">
                    <label>Program studi</label>
                    <div class="profile-value">
                        {{ $profile['program'] ?? '-' }}
                    </div>
                </div>

                <div class="profile-data">
                    <label>Status</label>
                    <div class="profile-value">
                        <span class="profile-status">
                            {{ $profile['status'] ?? 'Aktif' }}
                        </span>
                    </div>
                </div>

                <div class="profile-data">
                    <label>Role</label>
                    <div class="profile-value">
                        {{ $profile['role'] ?? 'Mahasiswa' }}
                    </div>
                </div>

            </div>
        </section>

        {{-- Kontak --}}
        <section class="profile-card contact-card">

            <div class="profile-card-heading contact-heading">
                <div>
                    <h2>Kontak</h2>
                    <p>Bisa Anda ubah sendiri.</p>
                </div>

                <span class="suggestion-label">USULAN</span>
            </div>

            <div class="profile-data-list">

                <div class="profile-data">
                    <label>Email</label>
                    <div class="profile-value profile-break">
                        {{ $profile['email'] ?? '-' }}
                    </div>
                </div>

                <div class="profile-data">
                    <label>Nomor ponsel</label>
                    <div class="profile-value">
                        {{ $profile['phone'] ?? '-' }}
                    </div>
                </div>

            </div>

        </section>

    </div>
</div>

@endsection
