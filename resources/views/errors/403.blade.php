
@extends('layouts.app')

@section('title', 'Role dan Hak Akses')

@section('content')
<div class="access-denied-page">

    <div class="breadcrumb">
        <a href="{{ route('dashboard') }}">Beranda</a>
        <span>/</span>
        <span>Role dan Hak Akses</span>
    </div>

    <div class="access-denied-card">

        <div class="access-denied-code">403</div>

        <h1>Tidak berwenang</h1>

        <p>
            Akun Anda tidak memiliki hak akses ke halaman ini.
        </p>

        <a href="{{ route('dashboard') }}" class="btn btn-primary">
            Kembali ke Beranda
        </a>

    </div>

</div>
@endsection
