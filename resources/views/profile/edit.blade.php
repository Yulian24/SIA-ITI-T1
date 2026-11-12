
@extends('layouts.app')

@section('title', 'Ubah Kontak')

@section('content')

@php
    $name = $profile['name'] ?? 'Nama Mahasiswa';
@endphp

<div class="profile-page">

    <div class="breadcrumb">
        <a href="{{ route('dashboard') }}">Beranda</a>
        <span>/</span>
        <a href="{{ route('profile.show') }}">Profil</a>
        <span>/</span>
        <span>Ubah kontak</span>
    </div>

    <div class="profile-heading">
        <div>
            <h1>Ubah kontak</h1>
            <p>Akun dan identitas akademik {{ $name }}</p>
        </div>
    </div>

    <div class="profile-columns">

        {{-- IDENTITAS AKADEMIK --}}
        <section class="profile-card academic-card">

            <div class="profile-card-heading">
                <div>
                    <h2>Identitas akademik</h2>
                    <p>Dikelola oleh BAAK. Hubungi BAAK bila ada data yang keliru.</p>
                </div>
            </div>

            <div class="profile-data-list">

                <div class="profile-data">
                    <label>Nama lengkap</label>
                    <div class="profile-value">
                        {{ $profile['name'] ?? '-' }}
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
                        {{ $profile['status'] ?? 'Aktif' }}
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

        {{-- FORM KONTAK --}}
        <section class="profile-card contact-card">

            <div class="profile-card-heading contact-heading">
                <div>
                    <h2>Kontak</h2>
                    <p>Bisa Anda ubah sendiri.</p>
                </div>

                <span class="suggestion-label">USULAN</span>
            </div>

            <form
                method="POST"
                action="{{ route('profile.update') }}"
                class="profile-contact-form"
                novalidate
            >
                @csrf
                @method('PUT')

                {{-- Pesan ketika validasi gagal --}}
                @if($errors->any())
                    <div class="alert alert-error profile-validation-alert">
                        <strong>Perubahan belum disimpan</strong>
                        <p>Periksa 1 isian yang ditandai.</p>
                    </div>
                @endif

                {{-- EMAIL --}}
                <div class="profile-data">
                    <label for="email">Email</label>

                    <input
                        class="profile-input @error('email') is-invalid @enderror"
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email', $profile['email'] ?? '') }}"
                        placeholder="Masukkan alamat email"
                        maxlength="120"
                    >

                    @error('email')
                        <small class="field-error">
                            {{ $message }}
                        </small>
                    @else
                        <small class="profile-helper">
                            Dipakai untuk pemberitahuan akademik.
                        </small>
                    @enderror
                </div>

                {{-- NOMOR PONSEL --}}
                <div class="profile-data">
                    <label for="phone">Nomor ponsel</label>

                    <input
                        class="profile-input @error('phone') is-invalid @enderror"
                        type="tel"
                        id="phone"
                        name="phone"
                        value="{{ old('phone', $profile['phone'] ?? '') }}"
                        placeholder="Masukkan nomor ponsel"
                        maxlength="25"
                    >

                    @error('phone')
                        <small class="field-error">
                            {{ $message }}
                        </small>
                    @enderror
                </div>

                {{-- TOMBOL --}}
                <div class="profile-form-actions">
                    <a
                        href="{{ route('profile.show') }}"
                        class="profile-cancel-btn"
                    >
                        Batal
                    </a>

                    <button type="submit" class="profile-save-btn">
                        Simpan perubahan
                    </button>
                </div>

            </form>
        </section>

    </div>
</div>

@endsection
