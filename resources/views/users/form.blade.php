@extends('layouts.app')

@section('title', $pageTitle)

@section('content')

<div class="breadcrumb">
    <a href="{{ route('dashboard') }}">Manajemen Pengguna</a>
    <span>/</span>
    <span>{{ $pageTitle }}</span>
</div>

<div class="page-heading compact">
    <div>
        <span class="eyebrow purple">DATA MASTER</span>
        <h1>{{ $pageTitle }}</h1>
        <p>Lengkapi informasi pengguna di bawah ini.</p>
    </div>
</div>

<section class="panel form-panel">

    <form method="POST"
        action="{{ $user ? route('users.update', $user['id']) : route('users.store') }}"
        class="record-form">

        @csrf

        @if($user)
            @method('PUT')
        @endif

        <div class="section-title">
            <span class="section-number">01</span>

            <div>
                <h2>Informasi Pengguna</h2>
                <p>Data identitas dan akun pengguna.</p>
            </div>
        </div>

        <div class="form-grid">

            <div class="form-field">
                <label>Nama lengkap *</label>
                <input class="field" name="name"
                    value="{{ old('name', $user['name'] ?? '') }}"
                    placeholder="Masukkan nama lengkap" required>
            </div>

            <div class="form-field">
                <label>Alamat email *</label>
                <input class="field" type="email" name="email"
                    value="{{ old('email', $user['email'] ?? '') }}"
                    placeholder="nama@iti.ac.id" required>
            </div>

            <div class="form-field">
                <label>NIM / ID Pengguna</label>
                <input class="field" name="nim"
                    value="{{ old('nim', $user['nim'] ?? '') }}"
                    placeholder="Masukkan NIM atau ID">
            </div>

            <div class="form-field">
                <label>Hak akses *</label>
                <select class="field" name="role" required>
                    @foreach(['Administrator', 'Dosen', 'Mahasiswa', 'Staff'] as $role)
                        <option value="{{ $role }}"
                            @selected(old('role', $user['role'] ?? 'Mahasiswa') == $role)>
                            {{ $role }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-field">
                <label>Status akun *</label>
                <select class="field" name="status" required>
                    @foreach(['Aktif', 'Nonaktif'] as $status)
                        <option value="{{ $status }}"
                            @selected(old('status', $user['status'] ?? 'Aktif') == $status)>
                            {{ $status }}
                        </option>
                    @endforeach
                </select>
            </div>

        </div>

        <div class="form-actions">
            <a href="{{ route('dashboard') }}" class="btn btn-light">
                Batal
            </a>

            <button type="submit" class="btn btn-primary">
                Simpan Data →
            </button>
        </div>

    </form>

</section>

@endsection