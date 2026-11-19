@extends('layouts.app')

@section('title', 'Kurikulum Baru - SIA ITI')

@section('content')
@php
    $courses = $courses ?? [];
    $selectedCodes = $selectedCodes ?? [];
    $semesters = $semesters ?? [];
    $selectedSks = collect($courses)->whereIn('code', $selectedCodes)->sum('sks');
@endphp
@include('kurikulum._styles')

<div class="kmk-wrap">
    <div class="kmk-top">
        <div>
            <div class="kmk-bc"><a href="{{ route('dashboard') }}">Beranda</a> / <a href="{{ route('kurikulum.index') }}">Program Studi</a> / <a href="{{ route('kurikulum.index') }}">Kurikulum</a> / <span>Baru</span></div>
            <h1 class="kmk-h1">Kurikulum Baru</h1>
            <div class="kmk-sub">Susun informasi dan distribusi mata kuliah. Simpan sebagai draft untuk dilanjutkan.</div>
        </div>
    </div>

    <div style="margin-bottom:1rem">
        <a href="{{ route('kurikulum.index') }}" class="kmk-btn-sec">← Kembali ke Kurikulum</a>
    </div>

    <form method="POST" action="{{ route('kurikulum.store') }}">
        @csrf
        <div class="kmk-2col">
            <div style="display:flex;flex-direction:column;gap:1rem">
                <div class="kmk-card" style="margin-bottom:0">
                    <h2 style="font-size:1rem;font-weight:700;margin:0 0 .875rem">Informasi Kurikulum</h2>
                    <div class="kmk-form-grid">
                        <div>
                            <label class="kmk-lbl">Kode kurikulum *</label>
                            <input type="text" name="code" value="{{ old('code', 'KUR-2027') }}" required class="kmk-in">
                        </div>
                        <div>
                            <label class="kmk-lbl">Nama kurikulum *</label>
                            <input type="text" name="name" value="{{ old('name', 'Kurikulum Informatika 2027') }}" required class="kmk-in">
                        </div>
                        <div>
                            <label class="kmk-lbl">Program studi *</label>
                            <select name="prodi" class="kmk-sel">
                                <option selected>Teknik Informatika (S1)</option>
                            </select>
                        </div>
                        <div>
                            <label class="kmk-lbl">Tahun mulai berlaku *</label>
                            <input type="number" name="year" value="{{ old('year', 2027) }}" required class="kmk-in">
                        </div>
                    </div>
                </div>

                <div class="kmk-card" style="margin-bottom:0">
                    <div style="display:flex;justify-content:space-between;align-items:center;gap:.5rem;margin-bottom:.25rem">
                        <h2 style="font-size:1rem;font-weight:700;margin:0">Susunan Mata Kuliah</h2>
                        <a href="{{ route('mata-kuliah.create') }}" class="kmk-btn-sm">+ Tambah Mata Kuliah</a>
                    </div>
                    <div style="font-size:.75rem;color:#64748b;margin-bottom:.875rem">Pilih mata kuliah aktif dan tentukan semester penempatannya.</div>

                    <table class="kmk-tbl">
                        <thead>
                            <tr>
                                <th style="width:2rem"></th>
                                <th>Kode</th>
                                <th>Mata kuliah</th>
                                <th>SKS</th>
                                <th>Semester</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($courses as $mk)
                                @php $checked = in_array($mk['code'], $selectedCodes, true); @endphp
                                <tr>
                                    <td><input type="checkbox" name="selected_codes[]" value="{{ $mk['code'] }}" {{ $checked ? 'checked' : '' }}></td>
                                    <td style="font-family:monospace;color:#64748b">{{ $mk['code'] }}</td>
                                    <td>{{ $mk['name'] }}</td>
                                    <td>{{ $mk['sks'] }}</td>
                                    <td>
                                        <select name="semesters[{{ $mk['code'] }}]" class="kmk-sel" style="padding:.25rem .45rem;font-size:.75rem">
                                            @for($s = 1; $s <= 8; $s++)
                                                <option value="Semester {{ $s }}" {{ ($semesters[$mk['code']] ?? 'Semester 1') === "Semester {$s}" ? 'selected' : '' }}>Semester {{ $s }}</option>
                                            @endfor
                                        </select>
                                    </td>
                                    <td><span style="font-size:.75rem;color:#64748b">Hapus</span></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div style="font-size:.75rem;font-weight:600;color:#334155;margin-top:.75rem">
                        {{ count($selectedCodes) }} mata kuliah terpilih · {{ $selectedSks }} SKS
                    </div>

                    <div class="kmk-actions-end">
                        <a href="{{ route('kurikulum.index') }}" class="kmk-btn-sec">Batal</a>
                        <button type="submit" class="kmk-btn-pri">Simpan Draft</button>
                    </div>
                </div>
            </div>

            <div class="kmk-card" style="margin-bottom:0">
                <div style="font-size:.875rem;font-weight:700;color:#0f172a;margin-bottom:.5rem">Ringkasan Kurikulum</div>
                <span class="kmk-badge-draft">• Draft baru</span>
                <div style="font-size:1.5rem;font-weight:700;color:#0f172a;margin:.5rem 0 .2rem">{{ $selectedSks }} / 144 SKS</div>
                <div style="font-size:.75rem;color:#64748b;margin-bottom:1rem">
                    {{ count($selectedCodes) }} mata kuliah · {{ max(0, 144 - $selectedSks) }} SKS belum disusun
                </div>

                <div style="font-size:.75rem;font-weight:700;color:#334155;margin-bottom:.35rem">Distribusi Semester</div>
                <div style="font-size:.75rem;color:#64748b;margin-bottom:1rem">
                    Semester 1 · 2 mata kuliah · 6 SKS<br>
                    Semester 3 · 1 mata kuliah · 3 SKS
                </div>

                <label class="kmk-lbl">Target kelulusan — Total SKS kurikulum *</label>
                <input type="number" name="target_sks" value="{{ old('target_sks', 144) }}" required class="kmk-in">
                <div style="font-size:.75rem;color:#64748b;margin-top:.5rem;line-height:1.45">
                    Draft dapat disimpan meskipun susunan belum lengkap. Lengkapi seluruh mata kuliah sebelum mengaktifkan kurikulum.
                </div>
            </div>
        </div>
    </form>
</div>
@endsection