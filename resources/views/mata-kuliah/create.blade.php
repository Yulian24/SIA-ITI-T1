@extends('layouts.app')

@section('title', 'Tambah Mata Kuliah - SIA ITI')

@section('content')
@php $prereqOptions = $prereqOptions ?? []; @endphp
@include('kurikulum._styles')

<div class="kmk-wrap">
    <div class="kmk-top">
        <div>
            <div class="kmk-bc"><a href="{{ route('dashboard') }}">Beranda</a> / <a href="{{ route('kurikulum.index') }}">Program Studi</a> / <a href="{{ route('mata-kuliah.index') }}">Mata Kuliah</a> / <span>Tambah Mata Kuliah</span></div>
            <h1 class="kmk-h1">Tambah Mata Kuliah</h1>
            <div class="kmk-sub">Lengkapi data untuk menambahkan master mata kuliah baru.</div>
        </div>
    </div>

    <div style="margin-bottom:1rem">
        <a href="{{ route('mata-kuliah.index') }}" class="kmk-btn-sec">← Kembali ke Mata Kuliah</a>
    </div>

    <form method="POST" action="{{ route('mata-kuliah.store') }}">
        @csrf
        <div class="kmk-2col">
            <div class="kmk-card" style="margin-bottom:0">
                <h2 style="font-size:1rem;font-weight:700;margin:0 0 .2rem">Informasi Mata Kuliah</h2>
                <div style="font-size:.75rem;color:#64748b;margin-bottom:1rem">Kolom bertanda * wajib diisi.</div>

                <div class="kmk-form-grid">
                    <div>
                        <label class="kmk-lbl">Kode mata kuliah *</label>
                        <input type="text" name="code" value="{{ old('code', 'IF303') }}" required class="kmk-in">
                        <small style="color:#64748b;font-size:.7rem">Kode unik dalam program studi.</small>
                    </div>
                    <div>
                        <label class="kmk-lbl">Nama mata kuliah *</label>
                        <input type="text" name="name" value="{{ old('name', 'Kecerdasan Buatan') }}" required class="kmk-in">
                    </div>
                    <div>
                        <label class="kmk-lbl">Nama bahasa Inggris</label>
                        <input type="text" name="name_en" value="{{ old('name_en', 'Artificial Intelligence') }}" class="kmk-in">
                    </div>
                    <div>
                        <label class="kmk-lbl">Program studi *</label>
                        <select name="prodi" class="kmk-sel">
                            <option selected>Teknik Informatika (S1)</option>
                        </select>
                    </div>
                    <div>
                        <label class="kmk-lbl">SKS teori *</label>
                        <input type="number" id="mkTeori" name="sks_teori" min="0" max="20" value="{{ old('sks_teori', 3) }}" required class="kmk-in">
                    </div>
                    <div>
                        <label class="kmk-lbl">SKS praktik *</label>
                        <input type="number" id="mkPraktik" name="sks_praktik" min="0" max="20" value="{{ old('sks_praktik', 0) }}" required class="kmk-in">
                    </div>
                    <div>
                        <label class="kmk-lbl">Jenis mata kuliah *</label>
                        <select name="type" class="kmk-sel">
                            <option value="Wajib" {{ old('type') === 'Wajib' ? 'selected' : '' }}>Wajib</option>
                            <option value="Pilihan" {{ old('type', 'Pilihan') === 'Pilihan' ? 'selected' : '' }}>Pilihan</option>
                        </select>
                    </div>
                    <div>
                        <label class="kmk-lbl">Status *</label>
                        <select name="status" class="kmk-sel">
                            <option value="Aktif" {{ old('status', 'Aktif') === 'Aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="Nonaktif" {{ old('status') === 'Nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                    </div>
                    <div style="grid-column:1/-1">
                        <label class="kmk-lbl">Prasyarat</label>
                        <select name="prerequisite" class="kmk-sel">
                            <option value="Tidak ada">Tidak ada</option>
                            @foreach($prereqOptions as $opt)
                                @php $lbl = $opt['code'] . ' - ' . $opt['name']; @endphp
                                <option value="{{ $lbl }}" {{ old('prerequisite') === $lbl ? 'selected' : '' }}>{{ $lbl }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div style="grid-column:1/-1">
                        <label class="kmk-lbl">Deskripsi mata kuliah</label>
                        <textarea name="description" class="kmk-ta">{{ old('description', 'Konsep dasar kecerdasan buatan, pencarian, representasi pengetahuan, dan pengantar pembelajaran mesin.') }}</textarea>
                        <small style="color:#64748b;font-size:.7rem">Ringkasan materi dan kompetensi mata kuliah.</small>
                    </div>
                </div>

                <div class="kmk-actions-end">
                    <a href="{{ route('mata-kuliah.index') }}" class="kmk-btn-sec">Batal</a>
                    <button type="submit" class="kmk-btn-pri">Simpan Mata Kuliah</button>
                </div>
            </div>

            <div class="kmk-card" style="margin-bottom:0">
                <div style="font-size:.875rem;font-weight:700;color:#0f172a">Ringkasan Mata Kuliah</div>
                <div id="mkTotalBadge" style="font-size:1.5rem;font-weight:700;color:#0f172a;margin:.35rem 0 .15rem">3 SKS</div>
                <div id="mkSplitText" style="font-size:.75rem;color:#64748b;margin-bottom:1rem">3 SKS teori + 0 SKS praktik</div>

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
    const t = document.getElementById('mkTeori');
    const p = document.getElementById('mkPraktik');
    const badge = document.getElementById('mkTotalBadge');
    const split = document.getElementById('mkSplitText');
    if (!t || !p || !badge || !split) return;
    function sync() {
        const tv = parseInt(t.value || '0', 10);
        const pv = parseInt(p.value || '0', 10);
        badge.textContent = (tv + pv) + ' SKS';
        split.textContent = tv + ' SKS teori + ' + pv + ' SKS praktik';
    }
    t.addEventListener('input', sync);
    p.addEventListener('input', sync);
    sync();
});
</script>
@endsection