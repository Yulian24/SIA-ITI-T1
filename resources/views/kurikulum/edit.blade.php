{{-- resources/views/kurikulum/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Edit Kurikulum - SIA ITI')

@section('content')
@include('kurikulum._styles')
<div class="kmk-wrap">
    <div class="kmk-top">
        <div>
            <div class="kmk-bc"><a href="{{ route('dashboard') }}">Beranda</a> / <a href="{{ route('kurikulum.index') }}">Program Studi</a> / <a href="{{ route('kurikulum.index') }}">Kurikulum</a> / <span>Edit</span></div>
            <h1 class="kmk-h1">Edit Kurikulum</h1>
            <div class="kmk-sub">Perbarui informasi {{ $curriculum['code'] }} · {{ $curriculum['name'] }}.</div>
        </div>
    </div>
    <div style="margin-bottom:1rem">
        <a href="{{ route('kurikulum.index') }}" class="kmk-btn-sec">← Kembali ke Kurikulum</a>
    </div>
    <form method="POST" action="{{ route('kurikulum.update', $curriculum['code']) }}" class="kmk-card">
        @csrf
        @method('PUT')
        <div class="kmk-form-grid">
            <div>
                <label class="kmk-lbl">Kode kurikulum</label>
                <input type="text" value="{{ $curriculum['code'] }}" disabled class="kmk-in" style="background:#f8fafc">
            </div>
            <div>
                <label class="kmk-lbl">Nama kurikulum *</label>
                <input type="text" name="name" value="{{ old('name', $curriculum['name']) }}" required class="kmk-in">
            </div>
            <div>
                <label class="kmk-lbl">Program studi *</label>
                <input type="text" name="prodi" value="{{ old('prodi', $curriculum['prodi']) }}" required class="kmk-in">
            </div>
            <div>
                <label class="kmk-lbl">Tahun mulai berlaku *</label>
                <input type="number" name="year" value="{{ old('year', $curriculum['year']) }}" required class="kmk-in">
            </div>
            <div>
                <label class="kmk-lbl">Target SKS kurikulum *</label>
                <input type="number" name="target_sks" value="{{ old('target_sks', $curriculum['target_sks']) }}" required class="kmk-in">
            </div>
            <div>
                <label class="kmk-lbl">Status *</label>
                <select name="status" class="kmk-sel">
                    @foreach(['Draft', 'Aktif', 'Arsip'] as $st)
                        <option value="{{ $st }}" {{ old('status', $curriculum['status']) === $st ? 'selected' : '' }}>{{ $st }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="kmk-actions-end">
            <a href="{{ route('kurikulum.index') }}" class="kmk-btn-sec">Batal</a>
            <button type="submit" class="kmk-btn-pri">Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection