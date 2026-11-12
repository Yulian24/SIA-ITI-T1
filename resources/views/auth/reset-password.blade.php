@extends('layouts.auth')

@section('title', 'Reset Password')

@section('content')

<div class="form-heading">
    <span class="eyebrow purple">KEAMANAN AKUN</span>
    <h2>Atur Ulang Password</h2>
    <p>Buat password baru untuk akun SIA ITI Anda.</p>
</div>

<form method="POST" action="{{ route('password.update') }}" class="form-stack">
    @csrf

    <label class="field-label">Email</label>

    <input
        class="field"
        type="email"
        name="email"
        value="{{ old('email', $email) }}"
        required
    >

    <label class="field-label">Password baru</label>

    <div class="password-wrap">
        <input
            class="field"
            type="password"
            id="password"
            name="password"
            minlength="8"
            required
        >

        <button type="button" class="password-toggle"
            data-password-toggle="password">
            Lihat
        </button>
    </div>

    <label class="field-label">Konfirmasi password</label>

    <div class="password-wrap">
        <input
            class="field"
            type="password"
            id="password_confirmation"
            name="password_confirmation"
            minlength="8"
            required
        >

        <button type="button" class="password-toggle"
            data-password-toggle="password_confirmation">
            Lihat
        </button>
    </div>

    <button type="submit" class="btn btn-primary btn-full">
        Simpan Password →
    </button>
</form>

<a href="{{ route('login') }}" class="back-link">
    ← Kembali ke halaman masuk
</a>

@endsection