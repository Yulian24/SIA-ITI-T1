
@extends('layouts.app')

@section('title', 'Tambah Pengguna')

@section('content')
<div class="page-header">
    <h1>Tambah Pengguna</h1>
</div>

<div class="content-card">
    <form action="{{ route('users.store') }}" method="POST">
        @csrf

        <label>Nama Lengkap</label>
        <input type="text" name="name" value="{{ old('name') }}" required>

        <label>Email</label>
        <input type="email" name="email" value="{{ old('email') }}" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <label>Role</label>
        <select name="role" required>
            <option value="admin">Admin</option>
            <option value="user">User</option>
        </select>

        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('users.index') }}" class="btn">Batal</a>
    </form>
</div>
@endsection
