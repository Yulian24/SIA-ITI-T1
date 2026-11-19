{{-- resources/views/mata-kuliah/show.blade.php --}}
@extends('layouts.app')

@section('title', 'Detail Mata Kuliah - SIA ITI')

@section('content')
@include('kurikulum._styles')

<div class="kmk-wrap">
    <div class="kmk-top">
        <div>
            <div class="kmk-bc"><a href="{{ route('dashboard') }}">Beranda</a> / <a href="{{ route('kurikulum.index') }}">Program Studi</a> / <a href="{{ route('mata-kuliah.index') }}">Mata Kuliah</a> / <span>Detail Mata Kuliah</span></div>
            <h1 class="kmk-h1">Detail Mata Kuliah</h1>
            <div class="kmk-sub">Lihat informasi master dan penggunaan mata kuliah pada kurikulum.</div>
        </div>
    </div>

    <div style="margin-bottom:1rem">
        <a href="{{ route('mata-kuliah.index') }}" class="kmk-btn-sec">← Kembali ke Mata Kuliah</a>
    </div>

    <div class="kmk-2col">
        <div class="kmk-card" style="margin-bottom:0">
            <h2 style="font-size:1.125rem;font-weight:700;margin:0 0 .5rem;color:#0f172a">{{ $course['code'] }} · {{ $course['name'] }}</h2>
            <div style="margin-bottom:1rem">
                @if($course['status'] === 'Aktif')
                    <span class="kmk-badge-ok">• Aktif</span>
                @else
                    <span class="kmk-badge-off">• Nonaktif</span>
                @endif
            </div>

            <div style="display:grid;grid-template-columns:10rem 1fr;gap:.5rem .75rem;font-size:.8125rem;margin-bottom:1rem">
                <span style="color:#64748b">Nama bahasa Inggris</span>
                <strong style="color:#0f172a">{{ $course['name_en'] ?: '-' }}</strong>

                <span style="color:#64748b">Program studi</span>
                <strong style="color:#0f172a">{{ $course['prodi'] }}</strong>

                <span style="color:#64748b">Jenis mata kuliah</span>
                <strong style="color:#0f172a">{{ $course['type'] }}</strong>

                <span style="color:#64748b">Beban studi</span>
                <strong style="color:#0f172a">{{ $course['sks'] }} SKS · {{ $course['sks_teori'] }} SKS teori + {{ $course['sks_praktik'] }} SKS praktik</strong>

                <span style="color:#64748b">Prasyarat</span>
                <strong style="color:#0f172a">{{ $course['prerequisite'] }}</strong>
            </div>

            <div style="font-size:.8125rem;font-weight:700;color:#0f172a;margin-bottom:.25rem">Deskripsi</div>
            <p style="font-size:.8125rem;color:#475569;margin:0 0 1.25rem;line-height:1.5">
                {{ $course['description'] ?: 'Belum ada deskripsi.' }}
            </p>

            <div style="display:flex;gap:.5rem">
                <a href="{{ route('mata-kuliah.edit', $course['code']) }}" class="kmk-btn-pri">Edit Mata Kuliah</a>
                <a href="{{ route('mata-kuliah.index', ['delete' => $course['code']]) }}" class="kmk-btn-sec">Hapus</a>
            </div>
        </div>

        <div class="kmk-card" style="margin-bottom:0">
            <h3 style="font-size:.875rem;font-weight:700;margin:0 0 .65rem;color:#0f172a">Penggunaan pada Kurikulum</h3>
            @if(!empty($course['curriculum']))
                @foreach($course['curriculum'] as $kur)
                    <div style="font-size:.8125rem;font-weight:600;color:#0f172a;margin-bottom:.35rem">{{ $kur }}</div>
                @endforeach
            @else
                <div style="font-size:.8125rem;color:#64748b;margin-bottom:.5rem">Belum digunakan pada kurikulum aktif.</div>
            @endif
            <div style="font-size:.75rem;color:#64748b;line-height:1.45;margin-top:.5rem">
                Nonaktifkan mata kuliah jika tidak lagi ditawarkan; data kurikulum tetap tersimpan.
            </div>
        </div>
    </div>
</div>
@endsection