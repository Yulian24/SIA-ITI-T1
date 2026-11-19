@extends('layouts.app')

@section('title', 'Manajemen Kurikulum - SIA ITI')

@section('content')
@php
    $curriculums = $curriculums ?? [];
    $stats = array_merge(['total' => 3, 'aktif' => 1, 'draft' => 1, 'arsip' => 1], $stats ?? []);
    $filters = array_merge(['q' => '', 'prodi' => 'Teknik Informatika (S1)', 'status' => 'Semua status'], $filters ?? []);
@endphp
@include('kurikulum._styles')

<div class="kmk-wrap">
    <div class="kmk-top">
        <div>
            <div class="kmk-bc"><a href="{{ route('dashboard') }}">Beranda</a> / <a href="{{ route('kurikulum.index') }}">Program Studi</a> / <span>Manajemen Kurikulum</span></div>
            <h1 class="kmk-h1">Manajemen Kurikulum</h1>
            <div class="kmk-sub">Kelola kurikulum, masa berlaku, dan susunan mata kuliah program studi.</div>
        </div>
        <a href="{{ route('kurikulum.create') }}" class="kmk-btn-pri">+ Kurikulum Baru</a>
    </div>

    <div class="kmk-tabs">
        <a href="{{ route('kurikulum.index') }}" class="kmk-tab active">Manajemen Kurikulum</a>
        <a href="{{ route('mata-kuliah.index') }}" class="kmk-tab">Manajemen Mata Kuliah</a>
    </div>

    @if(session('success'))
        <div class="kmk-flash">{{ session('success') }}</div>
    @endif

    <div class="kmk-stats">
        <div class="kmk-stat"><span>Total kurikulum</span><strong>{{ $stats['total'] }}</strong><small>Teknik Informatika · S1</small></div>
        <div class="kmk-stat"><span>Kurikulum aktif</span><strong>{{ $stats['aktif'] }}</strong><small>Digunakan angkatan 2024–2026</small></div>
        <div class="kmk-stat"><span>Draft</span><strong>{{ $stats['draft'] }}</strong><small>Belum dipublikasikan</small></div>
        <div class="kmk-stat"><span>Diarsipkan</span><strong>{{ $stats['arsip'] }}</strong><small>Tetap tersimpan sebagai riwayat</small></div>
    </div>

    <div class="kmk-card">
        <h2 style="font-size:1rem;font-weight:700;margin:0 0 .875rem">Daftar Kurikulum</h2>

        <form method="GET" action="{{ route('kurikulum.index') }}" class="kmk-filters">
            <div>
                <label class="kmk-lbl" style="visibility:hidden">Cari</label>
                <input type="text" name="q" value="{{ $filters['q'] }}" placeholder="Cari kode atau nama..." class="kmk-in">
            </div>
            <div>
                <label class="kmk-lbl">Program studi</label>
                <select name="prodi" class="kmk-sel">
                    <option {{ $filters['prodi'] === 'Teknik Informatika (S1)' ? 'selected' : '' }}>Teknik Informatika (S1)</option>
                    <option {{ $filters['prodi'] === 'Semua prodi' ? 'selected' : '' }}>Semua prodi</option>
                </select>
            </div>
            <div>
                <label class="kmk-lbl">Status</label>
                <select name="status" class="kmk-sel">
                    @foreach(['Semua status', 'Draft', 'Aktif', 'Arsip'] as $st)
                        <option value="{{ $st }}" {{ $filters['status'] === $st ? 'selected' : '' }}>{{ $st }}</option>
                    @endforeach
                </select>
            </div>
        </form>

        <table class="kmk-tbl">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Nama kurikulum</th>
                    <th>Tahun</th>
                    <th>MK</th>
                    <th>SKS</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($curriculums as $row)
                    <tr>
                        <td style="font-family:monospace;color:#475569">{{ $row['code'] }}</td>
                        <td><strong>{{ $row['name'] }}</strong></td>
                        <td>{{ $row['year'] }}</td>
                        <td>{{ $row['mk_count'] }}</td>
                        <td>{{ $row['sks_total'] }}</td>
                        <td>
                            @if($row['status'] === 'Aktif')
                                <span class="kmk-badge-ok">• Aktif</span>
                            @elseif($row['status'] === 'Draft')
                                <span class="kmk-badge-draft">• Draft</span>
                            @else
                                <span class="kmk-badge-off">• Arsip</span>
                            @endif
                        </td>
                        <td style="display:flex;gap:.35rem">
                            <a href="{{ route('kurikulum.show', $row['code']) }}" class="kmk-btn-sm">Detail</a>
                            @if($row['status'] !== 'Arsip')
                                <a href="{{ route('kurikulum.edit', $row['code']) }}" class="kmk-btn-sm">Edit</a>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" style="text-align:center;color:#64748b;padding:1.5rem">Tidak ada kurikulum yang cocok.</td></tr>
                @endforelse
            </tbody>
        </table>

        <div style="font-size:.75rem;color:#64748b;margin-top:.75rem">
            Menampilkan {{ count($curriculums) }} kurikulum · Program studi: {{ $filters['prodi'] }}
        </div>
    </div>

    <div class="kmk-card" style="background:#f8fafc">
        <div style="font-weight:700;font-size:.875rem;color:#0f172a">Mulai dari mata kuliah, susun menjadi kurikulum</div>
        <div style="font-size:.75rem;color:#64748b;margin-top:.2rem">
            Kelola master mata kuliah pada tab Manajemen Mata Kuliah. Kurikulum baru disimpan sebagai draft sebelum diaktifkan.
        </div>
    </div>
</div>
@endsection