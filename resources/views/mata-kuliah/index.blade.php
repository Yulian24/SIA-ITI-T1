@extends('layouts.app')

@section('title', 'Manajemen Mata Kuliah - SIA ITI')

@section('content')
@php
    $courses = $courses ?? [];
    $stats = array_merge(['total' => 8, 'aktif' => 7, 'wajib' => 6, 'pilihan' => 2], $stats ?? []);
    $filters = array_merge(['q' => '', 'type' => 'Semua jenis', 'status' => 'Semua status'], $filters ?? []);
    $deleteTarget = $deleteTarget ?? null;
@endphp
@include('kurikulum._styles')

<div class="kmk-wrap">
    <div class="kmk-top">
        <div>
            <div class="kmk-bc"><a href="{{ route('dashboard') }}">Beranda</a> / <a href="{{ route('kurikulum.index') }}">Program Studi</a> / <span>Manajemen Mata Kuliah</span></div>
            <h1 class="kmk-h1">Manajemen Mata Kuliah</h1>
            <div class="kmk-sub">Kelola master mata kuliah untuk penyusunan kurikulum Teknik Informatika.</div>
        </div>
        <a href="{{ route('mata-kuliah.create') }}" class="kmk-btn-pri">+ Tambah Mata Kuliah</a>
    </div>

    <div class="kmk-tabs">
        <a href="{{ route('kurikulum.index') }}" class="kmk-tab">Manajemen Kurikulum</a>
        <a href="{{ route('mata-kuliah.index') }}" class="kmk-tab active">Manajemen Mata Kuliah</a>
    </div>

    @if(session('success'))
        <div class="kmk-flash">{{ session('success') }}</div>
    @endif

    <div class="kmk-stats">
        <div class="kmk-stat"><span>Total mata kuliah</span><strong>{{ $stats['total'] }}</strong><small>Seluruh master mata kuliah</small></div>
        <div class="kmk-stat"><span>Aktif</span><strong>{{ $stats['aktif'] }}</strong><small>Dapat dipilih ke kurikulum</small></div>
        <div class="kmk-stat"><span>Wajib</span><strong>{{ $stats['wajib'] }}</strong><small>Kompetensi inti program studi</small></div>
        <div class="kmk-stat"><span>Pilihan</span><strong>{{ $stats['pilihan'] }}</strong><small>Pengayaan bidang keahlian</small></div>
    </div>

    <div class="kmk-card">
        <h2 style="font-size:1rem;font-weight:700;margin:0 0 .875rem">Daftar Mata Kuliah</h2>

        <form method="GET" action="{{ route('mata-kuliah.index') }}" class="kmk-filters">
            <div>
                <label class="kmk-lbl" style="visibility:hidden">Cari</label>
                <input type="text" name="q" value="{{ $filters['q'] }}" placeholder="Cari kode atau nama..." class="kmk-in">
            </div>
            <div>
                <label class="kmk-lbl">Jenis</label>
                <select name="type" class="kmk-sel">
                    @foreach(['Semua jenis', 'Wajib', 'Pilihan'] as $t)
                        <option value="{{ $t }}" {{ $filters['type'] === $t ? 'selected' : '' }}>{{ $t }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="kmk-lbl">Status</label>
                <select name="status" class="kmk-sel">
                    @foreach(['Semua status', 'Aktif', 'Nonaktif'] as $st)
                        <option value="{{ $st }}" {{ $filters['status'] === $st ? 'selected' : '' }}>{{ $st }}</option>
                    @endforeach
                </select>
            </div>
        </form>

        <table class="kmk-tbl">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Mata kuliah</th>
                    <th>SKS</th>
                    <th>Jenis</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($courses as $mk)
                    <tr>
                        <td style="font-family:monospace;color:#475569">{{ $mk['code'] }}</td>
                        <td>{{ $mk['name'] }}</td>
                        <td>{{ $mk['sks'] }}</td>
                        <td>{{ $mk['type'] }}</td>
                        <td>
                            @if($mk['status'] === 'Aktif')
                                <span class="kmk-badge-ok">• Aktif</span>
                            @else
                                <span class="kmk-badge-off">• Nonaktif</span>
                            @endif
                        </td>
                        <td style="display:flex;gap:.35rem;flex-wrap:wrap">
                            <a href="{{ route('mata-kuliah.show', $mk['code']) }}" class="kmk-btn-sm">Detail</a>
                            <a href="{{ route('mata-kuliah.edit', $mk['code']) }}" class="kmk-btn-sm">Edit</a>
                            <a href="{{ route('mata-kuliah.index', array_merge(request()->query(), ['delete' => $mk['code']])) }}" class="kmk-btn-del">Hapus</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" style="text-align:center;color:#64748b;padding:1.5rem">Tidak ada mata kuliah yang cocok.</td></tr>
                @endforelse
            </tbody>
        </table>

        <div style="font-size:.75rem;color:#64748b;margin-top:.75rem">
            Menampilkan 1–{{ count($courses) }} dari {{ $stats['total'] }} mata kuliah
        </div>
    </div>

    @if($deleteTarget)
        <div class="kmk-modal-bg">
            <div class="kmk-modal">
                <h3 style="font-size:1rem;font-weight:700;margin:0 0 .5rem;color:#0f172a">Hapus mata kuliah?</h3>
                <p style="font-size:.8125rem;color:#475569;line-height:1.5;margin:0 0 1.25rem">
                    <strong>{{ $deleteTarget['code'] }} · {{ $deleteTarget['name'] }}</strong> akan dihapus permanen. Tindakan ini tidak dapat dibatalkan.
                </p>
                <div style="display:flex;justify-content:flex-end;gap:.5rem">
                    <a href="{{ route('mata-kuliah.index', request()->except('delete')) }}" class="kmk-btn-sec">Batal</a>
                    <form method="POST" action="{{ route('mata-kuliah.destroy', $deleteTarget['code']) }}" style="display:inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="kmk-btn-del">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection