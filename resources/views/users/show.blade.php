
@extends('layouts.app')

@section('title', 'Detail Pengguna')

@section('content')
<div class="page-header">
    <h1>Detail Pengguna</h1>
</div>

<div class="content-card">
    <p><strong>Nama:</strong> {{ $user['name'] }}</p>
    <p><strong>Email:</strong> {{ $user['email'] }}</p>
    <p><strong>Role:</strong> {{ $user['role'] }}</p>

    <a href="{{ route('users.index') }}" class="btn">Kembali</a>
    <a href="{{ route('users.edit', $user['id']) }}" class="btn btn-primary">
        Edit Pengguna
    </a>
</div>
@endsection
