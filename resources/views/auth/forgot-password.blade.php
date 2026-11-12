@extends('layouts.auth')

@section('title', 'Lupa Password')

@section('content')

<div class="form-heading">
    <span class="eyebrow purple">BANTUAN AKUN</span>
    <h2>Lupa Password</h2>
    <p>
        Masukkan email yang terdaftar untuk meminta
        instruksi pengaturan ulang password.
    </p>
</div>

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<form method="POST" action="{{ route('password.email') }}" class="form-stack">
    @csrf

    <label class="field-label" for="email">
        Email terdaftar
    </label>

    <input
        class="field"
        type="email"
        name="email"
        id="email"
        placeholder="nama@iti.ac.id"
        required
    >

    <button type="submit" class="btn btn-primary btn-full">
        Kirim Instruksi →
    </button>
</form>

<a href="{{ route('login') }}" class="back-link">
    ← Kembali ke halaman masuk
</a>

@endsection