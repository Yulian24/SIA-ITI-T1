@extends('layouts.app')

@section('title', 'Manajemen Pengguna')

@section('content')

<div class="page-heading">

    <div>
        <span class="eyebrow purple">DATA MASTER</span>
        <h1>Manajemen Pengguna</h1>
        <p>Kelola akun dan hak akses pengguna SIA ITI.</p>
    </div>

    <a href="{{ route('users.create') }}" class="btn btn-primary">
        + Tambah Pengguna
    </a>

</div>

<div class="stats-grid">

    <article class="stat-card">
        <span class="stat-icon violet">♙</span>
        <div>
            <small>Total Pengguna</small>
            <strong>{{ count($users) }}</strong>
        </div>
    </article>

    <article class="stat-card">
        <span class="stat-icon green">✓</span>
        <div>
            <small>Pengguna Aktif</small>
            <strong>{{ collect($users)->where('status', 'Aktif')->count() }}</strong>
        </div>
    </article>

    <article class="stat-card">
        <span class="stat-icon orange">◷</span>
        <div>
            <small>Nonaktif</small>
            <strong>{{ collect($users)->where('status', 'Nonaktif')->count() }}</strong>
        </div>
    </article>

</div>

<section class="panel">

    <div class="panel-head">
        <div>
            <h2>Daftar Pengguna</h2>
            <p>Informasi akun yang terdaftar di dalam sistem.</p>
        </div>

        <div class="search-box">
            <span>⌕</span>
            <input type="search"
                placeholder="Cari nama, email, atau NIM..."
                data-table-search>
        </div>
    </div>

    <div class="table-scroll">

        <table class="data-table" id="users-table">

            <thead>
                <tr>
                    <th>Nama Pengguna</th>
                    <th>NIM / ID</th>
                    <th>Hak Akses</th>
                    <th>Status</th>
                    <th class="align-right">Aksi</th>
                </tr>
            </thead>

            <tbody>

                @foreach($users as $user)

                <tr>

                    <td>
                        <div class="person-cell">

                            <span class="avatar table-avatar">
                                {{ strtoupper(substr($user['name'], 0, 1)) }}
                            </span>

                            <span>
                                <b>{{ $user['name'] }}</b>
                                <small>{{ $user['email'] }}</small>
                            </span>

                        </div>
                    </td>

                    <td>{{ $user['nim'] ?: '—' }}</td>

                    <td>
                        <span class="role-pill">
                            {{ $user['role'] }}
                        </span>
                    </td>

                    <td>
                        <span class="status-pill {{ $user['status'] == 'Aktif' ? 'is-active' : 'is-inactive' }}">
                            <i></i>
                            {{ $user['status'] }}
                        </span>
                    </td>

                    <td>
                        <div class="action-group">

                            <a href="{{ route('users.edit', $user['id']) }}"
                                class="btn btn-light btn-small">
                                Edit
                            </a>

                            <form method="POST"
                                action="{{ route('users.destroy', $user['id']) }}"
                                data-confirm="Yakin ingin menghapus pengguna ini?">

                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                    class="btn btn-danger-ghost btn-small">
                                    Hapus
                                </button>

                            </form>

                        </div>
                    </td>

                </tr>

                @endforeach

            </tbody>

        </table>

    </div>

    <div class="table-foot">
        <span>Menampilkan {{ count($users) }} pengguna</span>
        <span>Data contoh</span>
    </div>

</section>

@endsection