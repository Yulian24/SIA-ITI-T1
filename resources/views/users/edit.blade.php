
@extends('layouts.app')

@section('title', 'Edit Pengguna')

@section('content')
<div class="page-header">
    <h1>Edit Pengguna</h1>
</div>

<div class="content-card">
    <form action="{{ route('users.update', $user['id']) }}" method="POST">
        @csrf
        @method('PUT')

        <label>Nama Lengkap</label>
        <input type="text" name="name" value="{{ $user['name'] }}" required>

        <label>Email</label>
        <input type="email" name="email" value="{{ $user['email'] }}" required>

        <label>Role</label>
        <select name="role">
            <option value="admin" {{ $user['role'] == 'admin' ? 'selected' : '' }}>Admin</option>
            <option value="user" {{ $user['role'] == 'user' ? 'selected' : '' }}>User</option>
        </select>

        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        <a href="{{ route('users.index') }}" class="btn">Batal</a>
    </form>
</div>
@endsection
