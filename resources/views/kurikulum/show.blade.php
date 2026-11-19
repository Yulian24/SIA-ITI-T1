{{-- resources/views/kurikulum/show.blade.php --}}
@extends('layouts.app')

@section('title', 'Detail Kurikulum - SIA ITI')

@section('content')
@include('kurikulum._styles')
<div class="kmk-wrap">
    <div class="kmk-top">
        <div>
            <div class="kmk-bc"><a href="{{ route('dashboard') }}">Beranda</a> / <a href="{{ route('kurikulum.index') }}">Program Studi</a> / <a href="{{ route('kurikulum.index') }}">Kurikulum</a> / <span>{{ $curriculum['code'] }}</span></div>
            <h1 class="kmk-h1">{{ $curriculum['code'] }} · {{ $curriculum['name'] }}</h1>
            <div class="kmk-sub">{{ $curriculum['prodi'] }} · Tahun berlaku {{ $curriculum['year'] }}</div>
        </div>
    </div>
    <div style="display:flex;gap:.5rem;margin-bottom:1rem">
        <a href="{{ route('kurikulum.index') }}" class="kmk-btn-sec">← Kembali ke Kurikulum</a>
        @if($curriculum['status'] !== 'Arsip')
            <a href="{{ route('kurikulum.edit', $curriculum['code']) }}" class="kmk-btn-pri">Edit Kurikulum</a>
        @endif
    </div>
    <div class="kmk-card">
        <p style="margin:.25rem 0"><strong>Status:</strong> {{ $curriculum['status'] }}</p>
        <p style="margin:.25rem 0"><strong>Jumlah Mata Kuliah:</strong> {{ $curriculum['mk_count'] }}</p>
        <p style="margin:.25rem 0"><strong>Total SKS:</strong> {{ $curriculum['sks_total'] }} / {{ $curriculum['target_sks'] }} SKS</p>
    </div>
</div>
@endsection