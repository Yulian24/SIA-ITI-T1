
@extends('layouts.auth')

@section('title', 'Masuk')

@section('content')

<div class="form-heading">
    <h2>Masuk</h2>
    <p>Gunakan akun SIA ITI Anda.</p>
</div>

{{-- Pesan login berhasil --}}
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

{{-- Pesan login terkunci --}}
@if(session('login_locked'))
    <div class="alert alert-error">
        <strong>Terlalu banyak percobaan</strong>
        <p>Tunggu sebentar sebelum mencoba masuk lagi.</p>
    </div>

{{-- Pesan login gagal --}}
@elseif(session('error'))
    <div class="alert alert-error">
        <strong>Gagal masuk</strong>
        <p>{{ session('error') }}</p>
        <p>Periksa kembali, lalu coba lagi.</p>
    </div>
@endif

{{-- Ringkasan validasi --}}
@if($errors->any())
    <div class="alert alert-error">
        <strong>Lengkapi isian</strong>
        <p>
            NIM atau username dan kata sandi wajib diisi sebelum masuk.
        </p>
    </div>
@endif

<form
    method="POST"
    action="{{ route('login.submit') }}"
    class="form-stack"
    data-login-form
    data-login-locked="{{ session('login_locked') ? 'true' : 'false' }}"
    novalidate
>
    @csrf

    {{-- NIM atau username --}}
    <div class="login-field-group">
        <label class="field-label" for="email">
            NIM atau username
        </label>

        <input
            class="field @error('email') is-invalid @enderror"
            id="email"
            name="email"
            type="text"
            placeholder="Masukkan NIM atau username"
            value="{{ old('email') }}"
            autocomplete="username"
            autofocus
            {{ session('login_locked') ? 'disabled' : '' }}
        >

        @error('email')
            <small class="field-error">
                {{ $message }}
            </small>
        @else
            <small class="login-helper">
                Mahasiswa memakai NIM.
            </small>
        @enderror
    </div>

    {{-- Kata sandi --}}
    <div class="login-field-group">
        <label class="field-label" for="password">
            Kata sandi
        </label>

        <div class="password-wrap">
            <input
                class="field @error('password') is-invalid @enderror"
                id="password"
                name="password"
                type="password"
                placeholder="Masukkan kata sandi"
                autocomplete="current-password"
                {{ session('login_locked') ? 'disabled' : '' }}
            >

            <button
                type="button"
                class="password-toggle"
                data-password-toggle="password"
                aria-label="Tampilkan atau sembunyikan kata sandi"
                {{ session('login_locked') ? 'disabled' : '' }}
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="17"
                    height="17"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.7"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                >
                    <path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7-10-7-10-7Z"/>
                    <circle cx="12" cy="12" r="3"/>
                </svg>
            </button>
        </div>

        @error('password')
            <small class="field-error">
                {{ $message }}
            </small>
        @enderror
    </div>

    {{-- Tombol masuk --}}
    <button
        type="submit"
        class="btn btn-primary btn-full login-submit"
        data-login-submit
        {{ session('login_locked') ? 'disabled' : '' }}
    >
        @if(session('login_locked'))
            Terlalu banyak percobaan
        @else
            Masuk
        @endif
    </button>

</form>

@endsection
