{{-- resources/views/mata-kuliah/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Edit Mata Kuliah - SIA ITI')

@section('content')
@php $prereqOptions = $prereqOptions ?? []; @endphp
@include('kurikulum._styles')

<div class="kmk-wrap">
    <div class="kmk-top">
        <div>
            <div class="kmk-bc"><a href="{{ route('dashboard') }}">Beranda</a> / <a href="{{ route('kurikulum.index') }}">Program Studi</a> / <a href="{{ route('mata-kuliah.index') }}">Mata Kuliah</a> / <span>Edit Mata Kuliah</span></div>
            <h1 class="kmk-h1">Edit Mata Kuliah</h1>
            <div class="kmk-sub">Perbarui informasi {{ $course['code'] }} · {{ $course['name'] }}.</div>
        </div>
    </div>

    <div style="margin-bottom:1rem">
        <a href="{{ route('mata-kuliah.index') }}" class="kmk-btn-sec">← Kembali ke Mata Kuliah</a>
    </div>

    <form method="POST" action="{{ route('mata-kuliah.update', $course['code']) }}">
        @csrf
        @method('PUT')
        <div class="kmk-2col">
            <div class="kmk-card" style="margin-bottom:0">
                <h2 style="font-size:1rem;font-weight:700;margin:0 0 .2rem">Informasi Mata Kuliah</h2>
                <div style="font-size:.75rem;color:#64748b;margin-bottom:1rem">Kolom bertanda * wajib diisi.</div>

                <div class="kmk-form-grid">
                    <div>
                        <label class="kmk-lbl">Kode mata kuliah *</label>
                        <input type="text" value="{{ $course['code'] }}" disabled class="kmk-in" style="background:#f8fafc">
                        <small style="color:#64748b;font-size:.7rem">Kode tidak dapat diubah.</small>
                    </div>
                    <div>
                        <label class="kmk-lbl">Nama mata kuliah *</label>
                        <input type="text" name="name" value="{{ old('name', $course['name']) }}" required class="kmk-in">
                    </div>
                    <div>
                        <label class="kmk-lbl">Nama bahasa Inggris</label>
                        <input type="text" name="name_en" value="{{ old('name_en', $course['name_en']) }}" class="kmk-in">
                    </div>
                    <div>
                        <label class="kmk-lbl">Program studi *</label>
                        <select name="prodi" class="kmk-sel">
                            <option selected>{{ $course['prodi'] }}</option>
                        </select>
                    </div>
                    <div>
                        <label class="kmk-lbl">SKS teori *</label>
                        <input type="number" id="mkEditTeori" name="sks_teori" min="0" max="20" value="{{ old('sks_teori', $course['sks_teori']) }}" required class="kmk-in">
                    </div>
                    <div>
                        <label class="kmk-lbl">SKS praktik *</label>
                        <input type="number" id="mkEditPraktik" name="sks_praktik" min="0" max="20" value="{{ old('sks_praktik', $course['sks_praktik']) }}" required class="kmk-in">
                    </div>
                    <div>
                        <label class="kmk-lbl">Jenis mata kuliah *</label>
                        <select name="type" class="kmk-sel">
                            @foreach(['Wajib', 'Pilihan'] as $t)
                                <option value="{{ $t }}" {{ old('type', $course['type']) === $t ? 'selected' : '' }}>{{ $t }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="kmk-lbl">Status *</label>
                        <select name="status" class="kmk-sel">
                            @foreach(['Aktif', 'Nonaktif'] as $st)
                                <option value="{{ $st }}" {{ old('status', $course['status']) === $st ? 'selected' : '' }}>{{ $st }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div style="grid-column:1/-1">
                        <label class="kmk-lbl">Prasyarat</label>
                        <select name="prerequisite" class="kmk-sel">
                            <option value="Tidak ada">Tidak ada</option>
                            @foreach($prereqOptions as $opt)
                                @php $lbl = $opt['code'] . ' - ' . $opt['name']; @endphp
                                <option value="{{ $lbl }}" {{ old('prerequisite', $course['prerequisite']) === $lbl ? 'selected' : '' }}>{{ $lbl }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div style="grid-column:1/-1">
                        <label class="kmk-lbl">Deskripsi mata kuliah</label>
                        <textarea name="description" class="kmk-ta">{{ old('description', $course['description']) }}</textarea>
                    </div>
                </div>

                <div class="kmk-actions-end">
                    <a href="{{ route('mata-kuliah.index') }}" class="kmk-btn-sec">Batal</a>
                    <button type="submit" class="kmk-btn-pri">Simpan Perubahan</button>
                </div>
            </div>

            <div class="kmk-card" style="margin-bottom:0">
                <div style="font-size:.875rem;font-weight:700;color:#0f172a">Ringkasan Mata Kuliah</div>
                <div id="mkEditTotal" style="font-size:1.5rem;font-weight:700;color:#0f172a;margin:.35rem 0 .15rem">{{ $course['sks'] }} SKS</div>
                <div id="mkEditSplit" style="font-size:.75rem;color:#64748b;margin-bottom:1rem">{{ $course['sks_teori'] }} SKS teori + {{ $course['sks_praktik'] }} SKS praktik</div>

                <div style="font-size:.75rem;font-weight:700;color:#334155;margin-bottom:.35rem">Ketentuan pengisian</div>
                <ul style="margin:0;padding-left:1.1rem;font-size:.75rem;color:#64748b;line-height:1.5">
                    <li>Gunakan kode mata kuliah yang unik.</li>
                    <li>Total SKS = SKS teori + SKS praktik.</li>
                    <li>Mata kuliah aktif dapat ditambahkan ke kurikulum.</li>
                    <li>Prasyarat tidak boleh sama dengan mata kuliah ini.</li>
                </ul>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const t = document.getElementById('mkEditTeori');
    const p = document.getElementById('mkEditPraktik');
    const tot = document.getElementById('mkEditTotal');
    const spl = document.getElementById('mkEditSplit');
    if (!t || !p || !tot || !spl) return;
    function sync() {
        const tv = parseInt(t.value || '0', 10);
        const pv = parseInt(p.value || '0', 10);
        tot.textContent = (tv + pv) + ' SKS';
        spl.textContent = tv + ' SKS teori + ' + pv + ' SKS praktik';
    }
    t.addEventListener('input', sync);
    p.addEventListener('input', sync);
    sync();
});
</script>
@endsection