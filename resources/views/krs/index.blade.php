@extends('layouts.app')

@section('title', 'KRS Mahasiswa - SIA ITI')

@section('content')
@php
    $state = $state ?? 'pengisian_krs';
    $courses = $courses ?? [];
    $requestedPeriod = request()->query('periode', '20261');
    $periode = is_string($requestedPeriod) && in_array($requestedPeriod, ['20261', '20252', 'semester-antara-2026'], true)
        ? $requestedPeriod
        : '20261';
    $requestedStatus = request()->query('status');
    $statusFromState = match ($state) {
        'menunggu_persetujuan' => 'menunggu',
        'ditolak_dosen_pa' => 'ditolak',
        'disetujui' => 'disetujui',
        default => 'kosong',
    };
    $krsStatus = is_string($requestedStatus) && in_array($requestedStatus, ['kosong', 'menunggu', 'ditolak', 'ditutup', 'disetujui', 'loading'], true)
        ? $requestedStatus
        : ($periode === 'semester-antara-2026' ? 'kosong' : ($periode === '20252' ? 'disetujui' : $statusFromState));
    $state = match ($krsStatus) {
        'menunggu' => 'menunggu_persetujuan',
        'ditolak' => 'ditolak_dosen_pa',
        'disetujui' => 'disetujui',
        default => 'pengisian_krs',
    };
    if ($krsStatus === 'kosong') {
        foreach ($courses as &$course) {
            $course['selected'] = false;
        }
        unset($course);
    } elseif ($krsStatus === 'ditolak') {
        foreach ($courses as &$course) {
            if ($course['code'] === 'IF215') {
                $course['selected'] = true;
            }
        }
        unset($course);
    }
    $isSemesterAntara = $periode === 'semester-antara-2026';
    $isLoading = $krsStatus === 'loading';
    $periodLabels = [
        '20261' => '20261 · Ganjil 2026/2027',
        '20252' => '20252 · Genap 2025/2026',
        'semester-antara-2026' => 'Semester antara 2026',
    ];
    $isReadOnly = in_array($krsStatus, ['menunggu', 'ditutup', 'disetujui', 'loading'], true);
    $selectedCourses = collect($courses)->where('selected', true);
    $totalSks = $selectedCourses->sum('sks');
    $jumlahMk = $selectedCourses->count();
    $krsState = match (true) {
        $krsStatus === 'ditutup' => 'ditutup',
        $state === 'ditolak_dosen_pa' => 'ditolak',
        $state === 'menunggu_persetujuan' => 'menunggu',
        $state === 'disetujui' => 'disetujui',
        default => 'pengisian',
    };
    $primaryLabel = $isSemesterAntara ? 'Isi KRS semester antara' : ($krsState === 'ditolak' ? 'Perbaiki KRS' : 'Pilih kelas');
    $primaryEnabled = !$isLoading && in_array($krsState, ['ditolak', 'pengisian'], true);
@endphp

<style>
.krs-wrap{max-width:1200px;margin:0 auto;padding:1.5rem 1rem;color:#1e293b}
.krs-bc{font-size:.75rem;color:#64748b;margin-bottom:.25rem}.krs-bc a{color:#64748b;text-decoration:none}
.krs-page-head{display:flex;align-items:flex-start;justify-content:space-between;gap:1rem;margin-bottom:1.25rem}
.krs-page-title{margin:0;font-size:1.5rem;font-weight:700;color:#0f172a;line-height:1.25}
.krs-page-meta{margin-top:.3rem;font-size:.78rem;color:#64748b}
.krs-action-group{display:inline-flex;align-items:center;gap:.6rem;flex-shrink:0}
.krs-btn-primary{display:inline-flex;align-items:center;justify-content:center;padding:.5rem 1.05rem;border-radius:.45rem;border:none;background:#6d285a;color:#fff;font-size:.8125rem;font-weight:600;cursor:pointer;white-space:nowrap;text-decoration:none}
.krs-btn-primary:hover:not(:disabled){background:#581c48}
.krs-btn-primary:disabled{background:#e2e8f0;color:#94a3b8;cursor:not-allowed}
.krs-btn-secondary{display:inline-flex;align-items:center;gap:.45rem;padding:.48rem .95rem;border-radius:.45rem;border:1px solid #cbd5e1;background:#fff;color:#1e293b;font-size:.8125rem;font-weight:600;cursor:pointer;white-space:nowrap;text-decoration:none}
.krs-btn-secondary:hover:not(:disabled){background:#f8fafc}
.krs-btn-secondary:disabled{background:#f1f5f9;color:#94a3b8;border-color:#e2e8f0;cursor:not-allowed}
.krs-more-menu{display:none}
.krs-more-menu.open{display:block}
.krs-banner-info{background:#eff6ff;border:1px solid #bfdbfe;color:#1e3a8a;border-radius:.75rem;padding:.875rem 1rem;font-size:.8125rem;margin-bottom:1rem;line-height:1.45}
.krs-banner-err{background:#fef2f2;border:1px solid #fecaca;color:#991b1b;border-radius:.75rem;padding:.875rem 1rem;font-size:.8125rem;margin-bottom:1rem;line-height:1.45}
.krs-banner-warning{background:#fffbeb;border:1px solid #fde68a;color:#92400e;border-radius:.75rem;padding:.875rem 1rem;font-size:.8125rem;margin-bottom:1rem;line-height:1.45}
.krs-banner-ok{background:#ecfdf5;border:1px solid #a7f3d0;color:#065f46;border-radius:.75rem;padding:.75rem 1rem;font-size:.8125rem;margin-bottom:1rem}
.krs-toolbar{display:flex;align-items:center;justify-content:space-between;gap:.75rem;flex-wrap:wrap;margin:0 0 1rem}
.krs-period-select{border:1px solid #cbd5e1;border-radius:.45rem;padding:.42rem .7rem;font-size:.8125rem;font-weight:600;color:#0f172a;background:#fff}
.krs-card{background:#fff;border:1px solid #e2e8f0;border-radius:.75rem;padding:1.25rem}
.krs-table-wrap{width:100%;overflow-x:auto}
.krs-tbl{width:100%;border-collapse:collapse;font-size:.8125rem;min-width:760px}.krs-tbl th{text-align:left;padding:.55rem .45rem;background:#f8fafc;color:#475569;font-size:.75rem;font-weight:600;border-bottom:1px solid #e2e8f0}.krs-tbl td{padding:.6rem .45rem;border-bottom:1px solid #f1f5f9;vertical-align:middle}
.krs-foot{display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:.75rem;margin-top:1.1rem;padding-top:1rem;border-top:1px solid #e2e8f0}
.krs-btn-sec{background:#fff;color:#334155;border:1px solid #cbd5e1;border-radius:.5rem;padding:.45rem .85rem;font-size:.8125rem;font-weight:600;text-decoration:none;cursor:pointer}
@media(max-width:640px){.krs-page-head{flex-wrap:wrap}.krs-action-group{width:100%;justify-content:flex-end}}
</style>

<div class="krs-wrap">
    <div class="krs-bc"><a href="{{ route('dashboard') }}">Beranda</a> / <span>KRS</span></div>

    <div class="krs-page-head">
        <div>
            <h1 class="krs-page-title">Kartu Rencana Studi</h1>
            <div class="krs-page-meta">{{ $periodLabels[$periode] }} · Dosen PA: Dr. Rina Kartika, M.Kom.</div>
        </div>
        <div class="krs-action-group">
            <div style="position:relative;display:inline-block">
                <button type="button" class="krs-btn-secondary" data-krs-more-toggle aria-expanded="false">
                    <span style="letter-spacing:.08em">•••</span>
                    <span>Lainnya</span>
                </button>
                <div id="krsMoreMenu" class="krs-more-menu" style="position:absolute;right:0;top:2.6rem;min-width:170px;background:#fff;border:1px solid #e2e8f0;border-radius:.5rem;box-shadow:0 10px 24px rgba(15,23,42,.12);padding:.35rem;z-index:40">
                    <a href="#" style="display:block;padding:.45rem .65rem;font-size:.8rem;color:#1e293b;text-decoration:none;border-radius:.35rem">Cetak / Unduh KRS</a>
                    <a href="#" style="display:block;padding:.45rem .65rem;font-size:.8rem;color:#1e293b;text-decoration:none;border-radius:.35rem">Riwayat Pengajuan</a>
                </div>
            </div>
            @if($primaryEnabled)
                <a href="{{ route('krs.pilih-kelas', ['state' => $isSemesterAntara ? 'semester_antara' : ($krsState === 'ditolak' ? 'validasi_gagal' : 'default'), 'periode' => $periode]) }}" class="krs-btn-primary">{{ $primaryLabel }}</a>
            @else
                <button type="button" class="krs-btn-secondary" disabled>{{ $primaryLabel }}</button>
            @endif
        </div>
    </div>

    <div class="krs-toolbar">
        <label for="krsPeriodSelect" style="font-size:.8rem;font-weight:600;color:#475569">Periode</label>
        <select id="krsPeriodSelect" class="krs-period-select" aria-label="Pilih periode KRS" onchange="window.location.href=this.value">
            <option value="{{ request()->fullUrlWithQuery(['periode' => '20261', 'status' => 'kosong']) }}" {{ $periode === '20261' ? 'selected' : '' }}>20261 · Ganjil 2026/2027</option>
            <option value="{{ request()->fullUrlWithQuery(['periode' => '20252', 'status' => 'disetujui']) }}" {{ $periode === '20252' ? 'selected' : '' }}>20252 · Genap 2025/2026</option>
            <option value="{{ request()->fullUrlWithQuery(['periode' => 'semester-antara-2026', 'status' => 'kosong']) }}" {{ $isSemesterAntara ? 'selected' : '' }}>Semester antara 2026</option>
        </select>
    </div>

    @if(session('krs_notice'))
        <div class="krs-banner-ok">{{ session('krs_notice') }}</div>
    @endif

    @if($isLoading)
        <div class="krs-card" role="status" aria-live="polite">
            <strong>Memuat KRS...</strong>
            <div class="krs-page-meta">Memuat mata kuliah dan status pengajuan.</div>
        </div>
    @elseif($isSemesterAntara)
        <div class="krs-banner-info">
            <strong>Periode Semester antara 2026 dibuka</strong><br>
            KRS semester antara terpisah dari KRS reguler. Gunakan tombol <strong>Isi KRS semester antara</strong> untuk memilih kelas.
        </div>
    @elseif($krsStatus === 'ditutup')
        <div class="krs-banner-warning">
            <strong>KRS {{ $periode }} tidak diajukan sebelum periode ditutup</strong><br>
            Draft belum diajukan sampai batas waktu. Hubungi Dosen PA atau BAAK untuk informasi selanjutnya.
        </div>
    @elseif($state === 'menunggu_persetujuan')
        <div class="krs-banner-info">
            <strong>ⓘ KRS {{ $periode }} menunggu persetujuan Dosen PA</strong><br>
            Diajukan 13 Agu 2026, 20.41 WIB ke Dr. Rina Kartika, M.Kom. Selama menunggu persetujuan, daftar kelas dan jadwal di Beranda belum aktif.
        </div>
    @elseif($state === 'ditolak_dosen_pa')
        <div class="krs-banner-err">
            <strong>⊗ KRS {{ $periode }} ditolak Dosen PA</strong><br>
            Dr. Rina Kartika, M.Kom. · 14 Agu 2026, 10.02 WIB. Catatan: kurangi beban menjadi 18 SKS, tunda IF215 Pemrograman Perangkat Bergerak ke semester depan, lalu ajukan ulang.
        </div>
    @elseif($krsStatus === 'disetujui')
        <div class="krs-banner-ok">
            <strong>KRS {{ $periode }} telah disetujui Dosen PA</strong><br>
            Disetujui oleh Dr. Rina Kartika, M.Kom.
        </div>
    @else
        <div class="krs-banner-info">
            <strong>ⓘ Periode KRS {{ $periode }} dibuka</strong><br>
            Pilih mata kuliah sesuai rencana studi lalu ajukan ke Dosen PA untuk disetujui.
        </div>
    @endif

    @unless($isLoading || $isSemesterAntara)
    <form id="krsSubmissionForm" action="{{ route('krs.submit') }}" method="POST" class="krs-card">
        @csrf
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:.75rem">
            <strong>Daftar Mata Kuliah Ditawarkan · Periode {{ $periode }}</strong>
            <span style="font-size:.8125rem;font-weight:700;color:#0f172a">Total beban terpilih: <span data-krs-total-sks>{{ $totalSks }}</span> SKS</span>
        </div>

        <div class="krs-table-wrap" role="region" aria-label="Daftar mata kuliah ditawarkan" tabindex="0">
            <table class="krs-tbl">
                <thead>
                    <tr>
                        <th style="width:3rem">Pilih</th>
                        <th>Kode</th>
                        <th>Mata Kuliah</th>
                        <th>SKS</th>
                        <th>Jadwal & Ruang</th>
                        <th>Dosen Pengampu</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($courses as $course)
                        <tr>
                            <td>
                                <input type="checkbox" name="courses[]" value="{{ $course['code'] }}" data-krs-course data-sks="{{ $course['sks'] }}"
                                       {{ $course['selected'] ? 'checked' : '' }}
                                       {{ $isReadOnly ? 'disabled' : '' }}>
                            </td>
                            <td style="font-family:monospace;color:#64748b">{{ $course['code'] }}</td>
                            <td><strong>{{ $course['name'] }}</strong></td>
                            <td>{{ $course['sks'] }}</td>
                            <td style="color:#475569">{{ $course['schedule'] }}</td>
                            <td style="color:#64748b">{{ $course['lecturer'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="krs-foot">
            <div style="font-size:.75rem;color:#64748b">
                @if($state === 'ditolak_dosen_pa')
                    Perbaiki pilihan mata kuliah sesuai catatan Dosen PA lalu ajukan ulang.
                @elseif($state === 'menunggu_persetujuan')
                    Menunggu verifikasi Dosen PA sebelum kelas & jadwal tampil di Beranda.
                @else
                    Pastikan SKS sesuai ketentuan akademik sebelum mengajukan KRS.
                @endif
            </div>

            <div style="display:flex;flex-wrap:wrap;gap:.5rem">
                @if($krsState === 'pengisian')
                    <button type="button" class="krs-btn-primary" data-krs-modal-trigger onclick="openKrsSubmitModal()">Ajukan KRS</button>
                @elseif($krsState === 'ditolak')
                    <button type="button" class="krs-btn-primary" data-krs-modal-trigger onclick="openKrsSubmitModal()">Ajukan Ulang KRS</button>
                @endif

                <button type="submit" name="action" value="approve_sim" class="krs-btn-sec">
                    Simulasikan Disetujui Dosen PA → Lihat Kelas di Beranda
                </button>
            </div>
        </div>

        @include('partials.krs-submit-modal', [
            'jumlahMk' => $jumlahMk,
            'totalSks' => $totalSks,
            'dosenPa' => 'Dr. Rina Kartika, M.Kom.',
        ])
    </form>
    @endunless
</div>

<script>
const krsMoreToggle = document.querySelector('[data-krs-more-toggle]');
const krsMoreMenu = document.getElementById('krsMoreMenu');

krsMoreToggle?.addEventListener('click', function () {
    const isOpen = krsMoreMenu?.classList.toggle('open') ?? false;
    krsMoreToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
});

document.addEventListener('click', function (event) {
    if (!krsMoreToggle?.contains(event.target) && !krsMoreMenu?.contains(event.target)) {
        krsMoreMenu?.classList.remove('open');
        krsMoreToggle?.setAttribute('aria-expanded', 'false');
    }
});

document.querySelectorAll('[data-krs-course]').forEach(function (checkbox) {
    checkbox.addEventListener('change', function () {
        const selected = Array.from(document.querySelectorAll('[data-krs-course]:checked'));
        const count = selected.length;
        const total = selected.reduce(function (sum, course) {
            return sum + Number(course.dataset.sks);
        }, 0);

        document.querySelectorAll('[data-krs-modal-count]').forEach(function (element) {
            element.textContent = count;
        });
        document.querySelectorAll('[data-krs-modal-sks]').forEach(function (element) {
            element.textContent = total;
        });
        document.querySelectorAll('[data-krs-total-sks]').forEach(function (element) {
            element.textContent = total;
        });
    });
});
</script>
@endsection
