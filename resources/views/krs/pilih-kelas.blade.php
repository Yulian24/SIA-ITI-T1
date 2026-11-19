@extends('layouts.app')

@section('title', 'Pilih Kelas KRS - SIA ITI')

@section('content')
@php
    $isLoading = $state === 'loading';
    $isKonfirmasi = $state === 'konfirmasi_pengajuan';
    $isValidasiGagal = $state === 'validasi_gagal';
    $isDuplikasi = $state === 'duplikasi_ditolak';
    $isWaitingList = $state === 'waiting_list';
    $isKosong = $state === 'kosong';
    $isSemesterAntara = $state === 'semester_antara';

    $draftItems = collect($offerings)->where('status', 'in_draft')->values();
    $usedSks = match ($state) {
        'validasi_gagal' => 26,
        'semester_antara' => 3,
        default => $draftItems->sum('sks'),
    };
    $maxSks = $meta['batas_sks'];
    $progressPercent = min(100, (int) round(($usedSks / max(1, $maxSks)) * 100));
    $canSubmit = !$isLoading && !$isValidasiGagal && !$isSemesterAntara;
@endphp

<style>
.pk-wrap{max-width:1200px;margin:0 auto;padding:1.5rem 1rem;color:#1e293b}
.pk-top{display:flex;flex-wrap:wrap;align-items:flex-start;justify-content:space-between;gap:1rem;margin-bottom:1rem}
.pk-bc{font-size:.75rem;color:#64748b;margin-bottom:.25rem}
.pk-bc a{color:#64748b;text-decoration:none}
.pk-h1{font-size:1.5rem;font-weight:700;color:#0f172a;margin:0}
.pk-sub{font-size:.75rem;color:#64748b;margin-top:.25rem}
.pk-act{display:flex;align-items:center;gap:.5rem}
.pk-sec{padding:.5rem .9rem;font-size:.8125rem;font-weight:500;border-radius:.5rem;border:1px solid #cbd5e1;background:#fff;color:#334155;text-decoration:none}
.pk-pri{padding:.5rem 1.15rem;font-size:.8125rem;font-weight:600;border-radius:.5rem;border:none;background:#701a75;color:#fff;text-decoration:none;display:inline-flex;align-items:center}
.pk-dis{padding:.5rem 1.15rem;font-size:.8125rem;font-weight:500;border-radius:.5rem;border:1px solid #e2e8f0;background:#f1f5f9;color:#94a3b8;cursor:not-allowed}

.pk-states{display:flex;flex-wrap:wrap;align-items:center;gap:.375rem;background:#f1f5f9;border:1px solid #e2e8f0;padding:.5rem;border-radius:.75rem;margin-bottom:1rem;font-size:.75rem}
.pk-st{padding:.25rem .625rem;border-radius:.5rem;text-decoration:none;color:#334155;background:#fff;border:1px solid #cbd5e1;font-weight:500}
.pk-st.active{background:#0f172a;color:#fff;border-color:#0f172a}

.pk-grid{display:grid;grid-template-columns:1fr;gap:1.25rem;align-items:start}
@media(min-width:1024px){.pk-grid{grid-template-columns:2fr 1fr}}
.pk-card{background:#fff;border:1px solid #e2e8f0;border-radius:.75rem;padding:1.25rem}
.pk-filters{display:grid;grid-template-columns:1fr;gap:.5rem;margin-bottom:1rem}
@media(min-width:640px){.pk-filters{grid-template-columns:1.4fr 1fr 1fr}}
.pk-in{width:100%;padding:.5rem .75rem;border-radius:.5rem;border:1px solid #cbd5e1;font-size:.75rem;background:#fff;box-sizing:border-box}

.pk-row{padding:.75rem .625rem;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;justify-content:space-between;gap:1rem;border-radius:.5rem}
.pk-row.is-draft{background:#fdf2f8;border-bottom-color:#fbcfe8}
.pk-del-link{color:#701a75;font-size:.75rem;font-weight:600;text-decoration:none;white-space:nowrap}
.pk-btn-sm{padding:.35rem .7rem;font-size:.75rem;font-weight:500;border-radius:.5rem;border:1px solid #cbd5e1;background:#fff;color:#0f172a;text-decoration:none;cursor:pointer;white-space:nowrap}

.pk-bar{width:100%;height:.5rem;background:#e2e8f0;border-radius:9999px;overflow:hidden;margin:.5rem 0}
.pk-fill{height:100%;border-radius:9999px}
.pk-banner{padding:.75rem 1rem;border-radius:.75rem;font-size:.75rem;margin-bottom:1rem;line-height:1.45}
.pk-ok{background:#ecfdf5;border:1px solid #a7f3d0;color:#065f46}
.pk-err{background:#fef2f2;border:1px solid #fecaca;color:#991b1b}
.pk-info{background:#eff6ff;border:1px solid #bfdbfe;color:#1e40af}

.pk-pager{display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:.75rem;margin-top:1rem;padding-top:.75rem;border-top:1px solid #f1f5f9;font-size:.75rem;color:#64748b}
.pk-page-btns{display:flex;align-items:center;gap:.25rem}
.pk-pg{padding:.25rem .55rem;border:1px solid #cbd5e1;border-radius:.375rem;background:#fff;color:#334155}
.pk-pg.active{background:#701a75;color:#fff;border-color:#701a75;font-weight:600}

.pk-draft-item{display:flex;justify-content:space-between;align-items:flex-start;gap:.5rem;padding:.5rem 0;border-bottom:1px solid #f1f5f9;font-size:.75rem}
.pk-draft-item:last-child{border-bottom:none}

.pk-modal-bg{position:fixed;inset:0;background:rgba(15,23,42,.45);z-index:50;display:flex;align-items:center;justify-content:center;padding:1rem}
.pk-modal{background:#fff;border-radius:.875rem;max-width:26rem;width:100%;padding:1.5rem;box-shadow:0 20px 25px -5px rgba(0,0,0,.15)}
.pk-toast{position:fixed;bottom:1.25rem;right:1.25rem;max-width:22rem;background:#fff;border:1px solid #fecaca;border-radius:.75rem;padding:.875rem 1rem;box-shadow:0 10px 15px -3px rgba(0,0,0,.12);z-index:40;display:flex;align-items:flex-start;justify-content:space-between;gap:.75rem;font-size:.75rem}
</style>

<div class="pk-wrap">
    <div class="pk-top">
        <div>
            <div class="pk-bc">
                <a href="{{ route('dashboard') }}">Beranda</a> / <a href="{{ route('krs.index') }}">KRS</a> / <span>Pilih kelas</span>
            </div>
            <h1 class="pk-h1">Pilih kelas</h1>
            <div class="pk-sub">
                {{ $meta['periode'] }} · Dosen PA: {{ $meta['dosen_pa'] }} · Pengisian sampai {{ $meta['batas_waktu'] }}
            </div>
        </div>

        <div class="pk-act">
            <a href="{{ route('krs.index') }}" class="pk-sec">Kembali ke KRS</a>
            @if($canSubmit)
                <a href="{{ route('krs.pilih-kelas', ['state' => 'konfirmasi_pengajuan']) }}" class="pk-pri">Ajukan KRS</a>
            @else
                <button type="button" disabled class="pk-dis">Ajukan KRS</button>
            @endif
        </div>
    </div>

    <div class="pk-states">
        <span style="font-weight:600;color:#475569;padding:0 .375rem">State:</span>
        @foreach([
            'default' => 'Default',
            'konfirmasi_pengajuan' => 'Konfirmasi pengajuan',
            'validasi_gagal' => 'Validasi gagal',
            'duplikasi_ditolak' => 'Duplikasi ditolak',
            'waiting_list' => 'Waiting list',
            'kosong' => 'Kosong',
            'loading' => 'Loading',
            'semester_antara' => 'Semester antara',
        ] as $key => $label)
            <a href="{{ route('krs.pilih-kelas', ['state' => $key]) }}" class="pk-st {{ $state === $key ? 'active' : '' }}">{{ $label }}</a>
        @endforeach
    </div>

    @if($isSemesterAntara)
        <div class="pk-banner pk-info">
            <strong>KRS semester antara terpisah dari KRS reguler.</strong> Hanya mata kuliah yang disetujui prodi untuk semester antara yang ditampilkan.
        </div>
    @endif

    @if($isWaitingList)
        <div class="pk-banner pk-info" style="display:flex;justify-content:space-between;align-items:center">
            <span><strong>Anda masuk antrean kelas Sistem Operasi (IF213).</strong> Bila ada kursi kosong, Anda dipindahkan ke kelas ini secara otomatis.</span>
            <a href="{{ route('krs.pilih-kelas') }}" style="color:inherit;font-weight:700;text-decoration:none">Tutup</a>
        </div>
    @endif

    @if($isLoading)
        <div class="pk-card" style="text-align:center;padding:3rem;color:#64748b;font-size:.8125rem">
            Memuat katalog mata kuliah, batas SKS, dan draft...
        </div>
    @else
        <div class="pk-grid">
            <div class="pk-card">
                <h2 style="font-size:1rem;font-weight:700;margin:0;color:#0f172a">
                    {{ $isSemesterAntara ? 'Mata kuliah semester antara' : 'Mata kuliah ditawarkan' }}
                </h2>
                <div style="font-size:.75rem;color:#64748b;margin:.2rem 0 1rem">
                    {{ $isSemesterAntara ? '2 kelas disetujui prodi' : '24 kelas aktif untuk Teknik Informatika pada periode 20261' }}
                </div>

                <div class="pk-filters">
                    <input type="text" value="{{ $isKosong ? 'Robotika' : '' }}" placeholder="Cari kode atau nama mata kuliah" class="pk-in">
                    <select class="pk-in"><option>Prodi: Teknik Informatika</option></select>
                    <select class="pk-in"><option>{{ $isSemesterAntara ? 'Periode: Semester antara 2026' : 'Periode: 20261' }}</option></select>
                </div>

                @if($isKosong)
                    <div style="text-align:center;padding:2rem;background:#f8fafc;border-radius:.625rem;border:1px dashed #cbd5e1">
                        <div style="font-weight:700;font-size:.875rem;color:#0f172a">Tidak ada mata kuliah yang cocok</div>
                        <div style="font-size:.75rem;color:#64748b;margin-top:.25rem">
                            Tidak ada kelas "Robotika" yang ditawarkan untuk Teknik Informatika pada periode 20261. Coba kata kunci lain atau ubah filter.
                        </div>
                    </div>
                @else
                    <div>
                        @foreach($offerings as $item)
                            @php
                                $st = $item['status'] ?? 'available';
                                $isDraftRow = $st === 'in_draft';
                            @endphp
                            <div class="pk-row {{ $isDraftRow ? 'is-draft' : '' }}">
                                <div>
                                    <div>
                                        <span style="font-family:monospace;font-size:.75rem;color:#64748b">{{ $item['code'] }}</span>
                                        <strong style="font-size:.875rem;color:#0f172a;margin-left:.45rem">{{ $item['name'] }}</strong>
                                    </div>
                                    <div style="font-size:.75rem;color:#64748b;margin-top:.15rem">
                                        {{ $item['schedule'] }} · {{ $item['sks'] }} SKS · Semester {{ $item['semester'] }}
                                        @if($st === 'full')
                                            · {{ $isWaitingList ? 'Penuh · Anda di antrean' : 'Kuota penuh (40/40)' }}
                                        @endif
                                    </div>
                                    @if(!empty($item['note']))
                                        <div style="font-size:.75rem;font-weight:600;margin-top:.15rem;color:{{ $st === 'locked' ? '#dc2626' : '#b45309' }}">
                                            {{ $st === 'locked' ? '⚠ ' : '' }}{{ $item['note'] }}
                                        </div>
                                    @endif
                                </div>

                                <div>
                                    @if($isDraftRow)
                                        <a href="{{ route('krs.pilih-kelas', ['state' => $state]) }}" class="pk-del-link">Hapus dari draft</a>
                                    @elseif($st === 'full')
                                        <a href="{{ route('krs.pilih-kelas', ['state' => $isWaitingList ? 'default' : 'waiting_list']) }}" class="pk-btn-sm">
                                            {{ $isWaitingList ? 'Keluar dari antrean' : 'Masuk waiting list' }}
                                        </a>
                                    @elseif($st === 'locked')
                                        <button type="button" disabled class="pk-dis" style="padding:.3rem .65rem;font-size:.75rem">Tambah</button>
                                    @else
                                        <a href="{{ route('krs.pilih-kelas', ['state' => 'duplikasi_ditolak']) }}" class="pk-btn-sm">Tambah</a>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="pk-pager">
                        <span>Menampilkan 1–10 dari 24 kelas</span>
                        <div class="pk-page-btns">
                            <span>Baris per halaman</span>
                            <select class="pk-pg" style="margin-right:.35rem"><option>10</option></select>
                            <span class="pk-pg">‹</span>
                            <span class="pk-pg active">1</span>
                            <span class="pk-pg">2</span>
                            <span class="pk-pg">3</span>
                            <span class="pk-pg">›</span>
                        </div>
                    </div>
                @endif
            </div>

            <div style="display:flex;flex-direction:column;gap:1rem">
                <div class="pk-card">
                    <div style="display:flex;justify-content:space-between;align-items:center;font-size:.75rem;font-weight:600">
                        <span>SKS terpakai</span>
                        <strong style="font-size:.875rem;color:{{ $isValidasiGagal ? '#dc2626' : '#0f172a' }}">{{ $usedSks }} dari {{ $maxSks }}</strong>
                    </div>
                    <div class="pk-bar">
                        <div class="pk-fill" style="width:{{ $progressPercent }}%;background:{{ $isValidasiGagal ? '#dc2626' : '#701a75' }}"></div>
                    </div>
                    <div style="font-size:.75rem;color:#64748b">
                        Batas SKS semester ini {{ $maxSks }}, dari IPK periode {{ $meta['periode_lalu'] }} ({{ $meta['ipk_lalu'] }}).
                    </div>
                </div>

                @if($isValidasiGagal)
                    <div class="pk-banner pk-err">
                        <div style="font-weight:700;margin-bottom:.25rem">Draft belum bisa diajukan · 2 masalah</div>
                        <ol style="margin:0;padding-left:1.1rem;font-size:.75rem">
                            <li>SKS yang diambil (26) melebihi jatah maksimum berdasarkan IPK lalu (24). Hapus minimal 2 SKS.</li>
                            <li>Jadwal IF307 Komputasi Awan bentrok dengan IF207 Rekayasa Perangkat Lunak: Kamis, 08.00–10.30.</li>
                        </ol>
                    </div>
                @elseif(!$isSemesterAntara)
                    <div class="pk-banner pk-ok">
                        <div style="font-weight:700">✓ Draft siap diajukan</div>
                        <div style="margin-top:.15rem">Prasyarat, batas SKS, dan jadwal sudah diperiksa. Tidak ada masalah.</div>
                    </div>
                @endif

                <div class="pk-card">
                    <h3 style="font-size:.875rem;font-weight:700;margin:0;color:#0f172a">Draft KRS</h3>
                    <div style="font-size:.75rem;color:#64748b;margin:.2rem 0 .75rem">{{ $draftItems->count() }} mata kuliah · {{ $usedSks }} SKS</div>

                    <div>
                        @foreach($draftItems as $draft)
                            <div class="pk-draft-item">
                                <div>
                                    <div style="font-weight:600;color:#0f172a">{{ $draft['code'] }} · {{ $draft['name'] }}</div>
                                    <div style="color:#64748b">{{ $draft['schedule'] }}</div>
                                </div>
                                <strong style="color:#334155;white-space:nowrap">{{ $draft['sks'] }} SKS</strong>
                            </div>
                        @endforeach
                    </div>

                    <div style="font-size:.7rem;color:#64748b;margin-top:.75rem;padding-top:.6rem;border-top:1px solid #f1f5f9">
                        Tersimpan otomatis · 12 Agu 2026, 09.20 WIB
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if($isDuplikasi)
        <div class="pk-toast">
            <div>
                <div style="font-weight:700;color:#991b1b">Duplikasi ditolak</div>
                <div style="color:#475569;margin-top:.15rem">
                    Pemrograman Web sudah ada di draft (Senin, 07.30–10.00). Hapus dulu bila ingin pindah ke jadwal Rabu.
                </div>
            </div>
            <a href="{{ route('krs.pilih-kelas') }}" style="color:#94a3b8;text-decoration:none;font-weight:700">✕</a>
        </div>
    @endif

    @if($isKonfirmasi)
        <div class="pk-modal-bg">
            <div class="pk-modal">
                <h3 style="font-size:1rem;font-weight:700;margin:0 0 .5rem;color:#0f172a">Ajukan KRS periode 20261?</h3>
                <p style="font-size:.8125rem;color:#475569;line-height:1.5;margin:0 0 1.25rem">
                    {{ $draftItems->count() }} mata kuliah, {{ $usedSks }} SKS akan dikirim ke <strong>{{ $meta['dosen_pa'] }}</strong> (Dosen PA). Setelah diajukan, KRS tidak bisa diubah sampai ada keputusan.
                </p>
                <div style="display:flex;justify-content:flex-end;gap:.5rem">
                    <a href="{{ route('krs.pilih-kelas') }}" class="pk-sec">Batal</a>
                    <a href="{{ route('krs.index', ['state' => 'menunggu_persetujuan']) }}" class="pk-pri">Ajukan KRS</a>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection