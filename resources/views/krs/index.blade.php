@extends('layouts.app')

@section('title', 'Kartu Rencana Studi - SIA ITI')

@section('content')
@php
    $state = $state ?? 'disetujui';
    $student = array_merge([
        'nim' => '312020001',
        'name' => 'Budi Santoso',
        'program' => 'Teknik Informatika (S1)',
        'angkatan' => '2025',
        'semester' => '3 · Ganjil 2026/2027',
        'status' => 'Aktif',
        'kurikulum' => '2024',
        'dosen_pa' => 'Dr. Rina Kartika, M.Kom.',
        'ipk' => '3,45',
        'sks_lulus' => 42,
        'periode_lalu' => '20252',
        'batas_sks' => 24,
    ], $student ?? []);

    $courses = $courses ?? [];

    $isDisetujui = $state === 'disetujui';
    $isPeriodeDibuka = $state === 'periode_dibuka';
    $isMenunggu = $state === 'menunggu_persetujuan';
    $isDitolak = $state === 'ditolak';
    $isDitutup = $state === 'periode_ditutup';
    $isLoading = $state === 'loading';
    $isSemesterAntara = $state === 'semester_antara';

    $canChooseClass = $isPeriodeDibuka || $isDitolak || $isSemesterAntara;
    $primaryBtnLabel = $isDitolak ? 'Perbaiki KRS' : 'Pilih kelas';
    $totalSks = collect($courses)->sum('sks');
@endphp

<style>
.krs-main{max-width:1200px;margin:0 auto;padding:1.5rem 1rem;color:#1e293b}
.krs-top{display:flex;flex-wrap:wrap;align-items:flex-start;justify-content:space-between;gap:1rem;margin-bottom:1rem}
.krs-bc{font-size:.75rem;color:#64748b;margin-bottom:.25rem}
.krs-bc a{color:#64748b;text-decoration:none}
.krs-h1{font-size:1.5rem;font-weight:700;color:#0f172a;margin:0}
.krs-submeta{font-size:.75rem;color:#64748b;margin-top:.25rem}
.krs-btn-row{display:flex;align-items:center;gap:.5rem;position:relative}
.krs-btn-sec{padding:.5rem .875rem;font-size:.8125rem;font-weight:500;border-radius:.5rem;border:1px solid #cbd5e1;background:#fff;color:#334155;cursor:pointer}
.krs-btn-pri{padding:.5rem 1.125rem;font-size:.8125rem;font-weight:600;border-radius:.5rem;border:none;background:#701a75;color:#fff;text-decoration:none;display:inline-flex;align-items:center}
.krs-btn-pri:hover{background:#581c87}
.krs-btn-dis{padding:.5rem 1.125rem;font-size:.8125rem;font-weight:500;border-radius:.5rem;border:1px solid #e2e8f0;background:#f1f5f9;color:#94a3b8;cursor:not-allowed}
.krs-menu{position:absolute;right:0;top:110%;min-width:12rem;background:#fff;border:1px solid #e2e8f0;border-radius:.625rem;box-shadow:0 10px 15px -3px rgba(0,0,0,.1);z-index:30;padding:.375rem 0;display:none}
.krs-menu.open{display:block}
.krs-menu a,.krs-menu span{display:block;padding:.5rem .875rem;font-size:.8125rem;color:#1e293b;text-decoration:none}
.krs-menu a:hover{background:#f8fafc}
.krs-menu .muted{color:#64748b;font-size:.75rem}

.krs-states{display:flex;flex-wrap:wrap;align-items:center;gap:.375rem;background:#f1f5f9;border:1px solid #e2e8f0;padding:.5rem;border-radius:.75rem;margin-bottom:1rem;font-size:.75rem}
.krs-st{padding:.25rem .625rem;border-radius:.5rem;text-decoration:none;color:#334155;background:#fff;border:1px solid #cbd5e1;font-weight:500}
.krs-st.active{background:#0f172a;color:#fff;border-color:#0f172a}

.krs-info-top{display:grid;grid-template-columns:1fr;gap:1rem;margin-bottom:1rem}
@media(min-width:1024px){.krs-info-top{grid-template-columns:2fr 1fr}}
.krs-box{background:#fff;border:1px solid #e2e8f0;border-radius:.75rem;padding:1.125rem 1.25rem}
.krs-bio-grid{display:grid;grid-template-columns:1fr 1fr;gap:.5rem 1.5rem;font-size:.8125rem}
.krs-bio-item{display:flex;justify-content:space-between;gap:.5rem}
.krs-bio-lbl{color:#64748b}
.krs-bio-val{font-weight:600;color:#0f172a;text-align:right}
.krs-ipk-num{font-size:1.75rem;font-weight:700;color:#0f172a;line-height:1.15;margin:.25rem 0}

.krs-period-bar{display:flex;flex-wrap:wrap;align-items:center;gap:.625rem;margin-bottom:1rem;font-size:.8125rem}
.krs-select{padding:.375rem .75rem;border:1px solid #cbd5e1;border-radius:.5rem;background:#fff;font-size:.8125rem;color:#0f172a}
.krs-pill-open{background:#ecfdf5;color:#047857;border:1px solid #a7f3d0;padding:.2rem .6rem;border-radius:9999px;font-size:.75rem;font-weight:600}
.krs-pill-closed{background:#f1f5f9;color:#475569;border:1px solid #cbd5e1;padding:.2rem .6rem;border-radius:9999px;font-size:.75rem;font-weight:600}

.krs-alert{padding:.875rem 1rem;border-radius:.75rem;font-size:.8125rem;margin-bottom:1rem;line-height:1.45}
.krs-al-ok{background:#ecfdf5;border:1px solid #a7f3d0;color:#065f46}
.krs-al-wait{background:#fffbeb;border:1px solid #fde68a;color:#92400e}
.krs-al-err{background:#fef2f2;border:1px solid #fecaca;color:#991b1b}
.krs-al-info{background:#eff6ff;border:1px solid #bfdbfe;color:#1e40af}

.krs-body-grid{display:grid;grid-template-columns:1fr;gap:1rem;align-items:start}
@media(min-width:1024px){.krs-body-grid{grid-template-columns:2fr 1fr}}
.krs-tbl{width:100%;border-collapse:collapse;font-size:.8125rem;margin-top:.5rem}
.krs-tbl th{text-align:left;padding:.5rem .375rem;border-bottom:1px solid #e2e8f0;color:#64748b;font-size:.75rem;font-weight:600}
.krs-tbl td{padding:.625rem .375rem;border-bottom:1px solid #f1f5f9;vertical-align:top}
.krs-empty{border:1px dashed #cbd5e1;background:#f8fafc;border-radius:.625rem;padding:1.5rem;text-align:center;margin-top:.5rem}
.krs-step{padding-left:1.125rem;border-left:2px solid #cbd5e1;margin-left:.25rem;padding-bottom:.875rem;font-size:.8125rem}
.krs-step:last-child{border-left-color:transparent;padding-bottom:0}
</style>

<div class="krs-main">
    <div class="krs-top">
        <div>
            <div class="krs-bc"><a href="{{ route('dashboard') }}">Beranda</a> / <span>KRS</span></div>
            <h1 class="krs-h1">Kartu Rencana Studi</h1>
            <div class="krs-submeta">
                Periode {{ $isSemesterAntara ? '20252' : '20261' }} · {{ $student['program'] }} · {{ $student['name'] }} ({{ $student['nim'] }}) · Dosen PA: {{ $student['dosen_pa'] }}
            </div>
        </div>

        <div class="krs-btn-row">
            <button type="button" class="krs-btn-sec" id="krsMoreBtn" aria-expanded="false">••• Lainnya</button>
            <div class="krs-menu" id="krsMoreMenu">
                <a href="{{ route('dashboard') }}">Lihat jadwal kuliah</a>
                <a href="{{ route('dashboard') }}">Rekap presensi</a>
                <span class="muted">Cetak KRS (PDF) · USULAN</span>
            </div>

            @if($isLoading)
                <button type="button" disabled class="krs-btn-dis">Pilih kelas</button>
            @elseif($canChooseClass)
                <a href="{{ route('krs.pilih-kelas', ['state' => $isSemesterAntara ? 'semester_antara' : 'default']) }}" class="krs-btn-pri">
                    {{ $primaryBtnLabel }}
                </a>
            @else
                <button type="button" disabled class="krs-btn-dis">{{ $primaryBtnLabel }}</button>
            @endif
        </div>
    </div>

    <div class="krs-states">
        <span style="font-weight:600;color:#475569;padding:0 .375rem">State:</span>
        @foreach([
            'disetujui' => 'Disetujui',
            'periode_dibuka' => 'Periode dibuka',
            'menunggu_persetujuan' => 'Menunggu persetujuan',
            'ditolak' => 'Ditolak',
            'periode_ditutup' => 'Periode ditutup',
            'loading' => 'Loading',
            'semester_antara' => 'Semester antara',
        ] as $key => $label)
            <a href="{{ route('krs.index', ['state' => $key]) }}" class="krs-st {{ $state === $key ? 'active' : '' }}">{{ $label }}</a>
        @endforeach
    </div>

    <div class="krs-info-top">
        <div class="krs-box">
            <div class="krs-bio-grid">
                <div class="krs-bio-item"><span class="krs-bio-lbl">NIM</span><span class="krs-bio-val">{{ $student['nim'] }}</span></div>
                <div class="krs-bio-item"><span class="krs-bio-lbl">Semester</span><span class="krs-bio-val">{{ $isSemesterAntara ? '2 · Genap 2025/2026' : $student['semester'] }}</span></div>
                <div class="krs-bio-item"><span class="krs-bio-lbl">Nama</span><span class="krs-bio-val">{{ $student['name'] }}</span></div>
                <div class="krs-bio-item"><span class="krs-bio-lbl">Status</span><span class="krs-bio-val">{{ $student['status'] }}</span></div>
                <div class="krs-bio-item"><span class="krs-bio-lbl">Program studi</span><span class="krs-bio-val">{{ $student['program'] }}</span></div>
                <div class="krs-bio-item"><span class="krs-bio-lbl">Kurikulum</span><span class="krs-bio-val">{{ $student['kurikulum'] }}</span></div>
                <div class="krs-bio-item"><span class="krs-bio-lbl">Angkatan</span><span class="krs-bio-val">{{ $student['angkatan'] }}</span></div>
                <div class="krs-bio-item"><span class="krs-bio-lbl">Dosen PA</span><span class="krs-bio-val">{{ $student['dosen_pa'] }}</span></div>
            </div>
        </div>

        <div class="krs-box">
            <div style="font-size:.75rem;color:#64748b;font-weight:600">IPK</div>
            <div class="krs-ipk-num">{{ $student['ipk'] }}</div>
            <div style="font-size:.75rem;color:#64748b">Sampai periode {{ $student['periode_lalu'] }} · {{ $student['sks_lulus'] }} SKS lulus</div>
            <div style="font-size:.75rem;color:#334155;font-weight:600;margin-top:.25rem">
                {{ $isSemesterAntara ? 'Batas SKS semester antara ditetapkan prodi' : 'Batas SKS periode 20261: ' . $student['batas_sks'] . ' SKS' }}
            </div>
        </div>
    </div>

    <div class="krs-period-bar">
        <span style="font-weight:600;color:#475569">Periode</span>
        <select class="krs-select" aria-label="Pilih periode KRS">
            @if($isSemesterAntara)
                <option selected>20252 · Genap 2025/2026</option>
                <option>Semester antara 2026</option>
                <option>20261 · Ganjil 2026/2027</option>
            @else
                <option selected>20261 · Ganjil 2026/2027</option>
                <option>20252 · Genap 2025/2026</option>
                <option>Semester antara 2026</option>
            @endif
        </select>

        @if($isPeriodeDibuka || $isDitolak)
            <span class="krs-pill-open">• KRS dibuka</span>
            <span style="font-size:.75rem;color:#64748b">Sampai 21 Agu 2026, 23.59 WIB</span>
        @elseif($isSemesterAntara)
            <span class="krs-pill-closed">• KRS ditutup</span>
            <span style="font-size:.75rem;color:#64748b">Pengisian KRS 26 Jan–6 Feb 2026</span>
        @else
            <span class="krs-pill-closed">• KRS ditutup</span>
            <span style="font-size:.75rem;color:#64748b">Pengisian KRS 10–21 Agu 2026</span>
        @endif
    </div>

    @if($isDisetujui)
        <div class="krs-alert krs-al-ok">
            <strong>KRS 20261 sudah disetujui</strong><br>
            Disetujui {{ $student['dosen_pa'] }} (Dosen PA) pada 14 Agu 2026, 10.02 WIB. Periode pengisian KRS sudah ditutup, jadi KRS tidak bisa diubah dan Pilih kelas nonaktif.
        </div>
    @elseif($isPeriodeDibuka)
        <div class="krs-alert krs-al-info">
            <strong>KRS 20261 sedang dibuka</strong><br>
            10 Agu 2026, KRS belum diisi. Pilih kelas aktif sampai 21 Agu 2026, 23.59 WIB.
        </div>
    @elseif($isMenunggu)
        <div class="krs-alert krs-al-wait">
            <strong>KRS 20261 menunggu persetujuan Dosen PA</strong><br>
            Diajukan 13 Agu 2026, 20.41 WIB ke {{ $student['dosen_pa'] }}. KRS tidak bisa diubah dan Pilih kelas nonaktif sampai ada keputusan.
        </div>
    @elseif($isDitolak)
        <div class="krs-alert krs-al-err">
            <strong>KRS 20261 ditolak Dosen PA</strong><br>
            Catatan {{ $student['dosen_pa'] }}: kurangi beban menjadi 18 SKS, tunda IF215 Pemrograman Perangkat Bergerak ke semester depan, lalu ajukan ulang sebelum 21 Agu 2026.
        </div>
    @elseif($isDitutup)
        <div class="krs-alert krs-al-wait">
            <strong>KRS 20261 tidak diajukan sebelum periode ditutup</strong><br>
            Draft 6 mata kuliah (18 SKS) belum diajukan sampai 21 Agu 2026, 23.59 WIB, sehingga belum menjadi KRS. Hubungi Dosen PA atau BAAK.
        </div>
    @elseif($isSemesterAntara)
        <div class="krs-alert krs-al-info" style="display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:.75rem">
            <div>
                <strong>Periode KRS semester antara 2026 dibuka</strong><br>
                Pengisian 1–7 Jul 2026. Semester antara tidak wajib dan KRS-nya terpisah dari KRS reguler.
            </div>
            <a href="{{ route('krs.pilih-kelas', ['state' => 'semester_antara']) }}" class="krs-btn-pri">Isi KRS semester antara</a>
        </div>
    @endif

    <div class="krs-body-grid">
        <div class="krs-box">
            <h2 style="font-size:1rem;font-weight:700;margin:0;color:#0f172a">
                @if($isDitutup)
                    Draft KRS 20261 (tidak diajukan)
                @elseif($isSemesterAntara)
                    KRS 20252
                @else
                    KRS 20261
                @endif
            </h2>
            <div style="font-size:.75rem;color:#64748b;margin-top:.2rem">
                @if($isPeriodeDibuka)
                    Ganjil 2026/2027 · belum ada mata kuliah
                @elseif($isSemesterAntara)
                    Genap 2025/2026 · {{ count($courses) }} mata kuliah · {{ $totalSks }} SKS
                @else
                    Ganjil 2026/2027 · {{ count($courses) }} mata kuliah · {{ $totalSks }} SKS
                @endif
            </div>

            @if($isLoading)
                <div style="padding:2rem;text-align:center;color:#64748b;font-size:.8125rem">Memuat data KRS...</div>
            @elseif($isPeriodeDibuka || count($courses) === 0)
                <div class="krs-empty">
                    <div style="font-weight:700;font-size:.875rem;color:#0f172a">Belum ada mata kuliah di KRS 20261</div>
                    <div style="font-size:.75rem;color:#64748b;margin-top:.25rem">
                        Klik Pilih kelas untuk melihat kelas yang ditawarkan. Batas SKS Anda periode ini {{ $student['batas_sks'] }}, dari IPK periode {{ $student['periode_lalu'] }} ({{ $student['ipk'] }}).
                    </div>
                </div>
            @else
                <table class="krs-tbl">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Mata kuliah</th>
                            <th>Hari &amp; jam</th>
                            <th>Semester</th>
                            <th style="text-align:right">SKS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($courses as $c)
                            <tr>
                                <td style="font-family:monospace;color:#64748b">{{ $c['code'] }}</td>
                                <td><strong>{{ $c['name'] }}</strong></td>
                                <td style="color:#64748b">{{ $c['schedule'] }}</td>
                                <td style="color:#64748b">{{ $c['semester'] }}</td>
                                <td style="text-align:right;font-weight:700">{{ $c['sks'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div style="display:flex;justify-content:space-between;flex-wrap:wrap;gap:.5rem;font-size:.75rem;color:#64748b;margin-top:.75rem">
                    <span>Batas SKS periode ini {{ $student['batas_sks'] }}, ditentukan dari IPK periode {{ $student['periode_lalu'] }} ({{ $student['ipk'] }}).</span>
                    <strong style="color:#0f172a;font-size:.8125rem">Total {{ $totalSks }} SKS</strong>
                </div>
            @endif
        </div>

        <div class="krs-box">
            <h2 style="font-size:1rem;font-weight:700;margin:0;color:#0f172a">Status KRS</h2>
            <div style="font-size:.75rem;color:#64748b;margin-top:.2rem;margin-bottom:1rem">Periode {{ $isSemesterAntara ? '20252' : '20261' }}</div>

            @if($isPeriodeDibuka)
                <div class="krs-step">
                    <strong>Pilih kelas</strong><br>
                    <span style="color:#64748b;font-size:.75rem">Kelas masuk draft dan tersimpan otomatis</span>
                </div>
                <div class="krs-step">
                    <strong>Ajukan ke Dosen PA</strong><br>
                    <span style="color:#64748b;font-size:.75rem">{{ $student['dosen_pa'] }}</span>
                </div>
                <div class="krs-step">
                    <strong>Keputusan Dosen PA</strong><br>
                    <span style="color:#64748b;font-size:.75rem">Disetujui, atau ditolak dengan catatan</span>
                </div>
            @elseif($isMenunggu)
                <div class="krs-step">
                    <strong>Draft dibuat</strong><br>
                    <span style="color:#64748b;font-size:.75rem">{{ $student['name'] }} · 12 Agu 2026, 09.14 WIB</span>
                </div>
                <div class="krs-step">
                    <strong>Diajukan</strong><br>
                    <span style="color:#64748b;font-size:.75rem">{{ $student['name'] }} · 13 Agu 2026, 20.41 WIB</span>
                </div>
                <div class="krs-step">
                    <strong>Keputusan Dosen PA</strong><br>
                    <span style="color:#64748b;font-size:.75rem">{{ $student['dosen_pa'] }}</span>
                </div>
            @elseif($isDitolak)
                <div class="krs-step">
                    <strong>Draft dibuat</strong><br>
                    <span style="color:#64748b;font-size:.75rem">{{ $student['name'] }} · 12 Agu 2026, 09.14 WIB</span>
                </div>
                <div class="krs-step">
                    <strong>Diajukan</strong><br>
                    <span style="color:#64748b;font-size:.75rem">{{ $student['name'] }} · 13 Agu 2026, 20.41 WIB</span>
                </div>
                <div class="krs-step">
                    <strong style="color:#dc2626">Ditolak</strong><br>
                    <span style="color:#64748b;font-size:.75rem">{{ $student['dosen_pa'] }} (Dosen PA) · 14 Agu 2026, 10.02 WIB</span>
                </div>
            @elseif($isDitutup)
                <div class="krs-step">
                    <strong>Draft dibuat</strong><br>
                    <span style="color:#64748b;font-size:.75rem">{{ $student['name'] }} · 12 Agu 2026, 09.14 WIB</span>
                </div>
                <div class="krs-step">
                    <strong style="color:#64748b">Tidak diajukan</strong><br>
                    <span style="color:#64748b;font-size:.75rem">Periode KRS ditutup 21 Agu 2026, 23.59 WIB</span>
                </div>
            @else
                <div class="krs-step">
                    <strong>Draft dibuat</strong><br>
                    <span style="color:#64748b;font-size:.75rem">{{ $student['name'] }} · 12 Agu 2026, 09.14 WIB</span>
                </div>
                <div class="krs-step">
                    <strong>Diajukan</strong><br>
                    <span style="color:#64748b;font-size:.75rem">{{ $student['name'] }} · 13 Agu 2026, 20.41 WIB</span>
                </div>
                <div class="krs-step">
                    <strong style="color:#047857">Disetujui</strong><br>
                    <span style="color:#64748b;font-size:.75rem">{{ $student['dosen_pa'] }} (Dosen PA) · 14 Agu 2026, 10.02 WIB</span>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const btn = document.getElementById('krsMoreBtn');
    const menu = document.getElementById('krsMoreMenu');
    if (!btn || !menu) return;
    btn.addEventListener('click', function (e) {
        e.stopPropagation();
        const open = menu.classList.toggle('open');
        btn.setAttribute('aria-expanded', open ? 'true' : 'false');
    });
    document.addEventListener('click', function () {
        menu.classList.remove('open');
        btn.setAttribute('aria-expanded', 'false');
    });
});
</script>
@endsection