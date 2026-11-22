@extends('layouts.app')

@section('title', 'Pilih Kelas KRS - SIA ITI')

@section('content')
@php
    $state = $state ?? 'default';
    $offerings = $offerings ?? [];
    $meta = $meta ?? [
        'periode' => '20261',
        'dosen_pa' => 'Dr. Rina Kartika, M.Kom.',
        'batas_waktu' => '21 Agu 2026, 23.59 WIB',
        'batas_sks' => 24,
        'periode_lalu' => '20252',
        'ipk_lalu' => '3,20',
    ];
    $isLoading = $state === 'loading';
    $isKonfirmasi = $state === 'konfirmasi_pengajuan';
    $isValidasiGagal = $state === 'validasi_gagal';
    $isDuplikasi = $state === 'duplikasi_ditolak';
    $isWaitingList = $state === 'waiting_list';
    $isKosong = $state === 'kosong';
    $isSemesterAntara = $state === 'semester_antara';
    $requestedPeriod = request()->query('periode');
    $periodeCode = is_string($requestedPeriod) && in_array($requestedPeriod, ['20261', '20252', 'semester-antara-2026'], true)
        ? $requestedPeriod
        : ($meta['periode'] ?? '20261');
    $labelPeriode = match ($periodeCode) {
        '20252' => '20252 · Genap 2025/2026',
        'semester-antara-2026' => 'Semester antara 2026',
        default => '20261 · Ganjil 2026/2027',
    };
    $dosenPa = $meta['dosen_pa'] ?? 'Dr. Rina Kartika, M.Kom.';
    $batasWaktu = $meta['batas_waktu'] ?? '21 Agu 2026, 23.59 WIB';

    $draftItems = collect($offerings)->where('status', 'in_draft')->values();
    if ($isValidasiGagal) {
        $draftItems = collect($offerings)
            ->filter(fn ($offering) => ($offering['status'] ?? '') === 'in_draft' || $offering['code'] === 'IF207')
            ->values();
    }
    $usedSks = $draftItems->sum('sks');
    $maxSks = $meta['batas_sks'];
    $progressPercent = min(100, (int) round(($usedSks / max(1, $maxSks)) * 100));
    $canSubmit = !$isLoading && !$isValidasiGagal && !$isSemesterAntara && $draftItems->isNotEmpty();
    $isLoadingKrs = $isLoading;
    $canSubmitKrs = $canSubmit;
@endphp

<style>
.pk-wrap{max-width:1200px;margin:0 auto;padding:1.5rem 1rem;color:#1e293b}
.krs-page-head{display:flex;align-items:flex-start;justify-content:space-between;gap:1rem;margin-bottom:1.25rem}
.krs-page-title{margin:0;font-size:1.5rem;font-weight:700;color:#0f172a;line-height:1.25}
.krs-page-meta{margin-top:.3rem;font-size:.78rem;color:#64748b}
.krs-action-group{display:inline-flex;align-items:center;gap:.6rem;flex-shrink:0}
.krs-btn-primary{padding:.5rem 1.05rem;border-radius:.45rem;border:none;background:#6d285a;color:#fff;font-size:.8125rem;font-weight:600;cursor:pointer;white-space:nowrap;text-decoration:none}
.krs-btn-primary:hover:not(:disabled){background:#581c48}
.krs-btn-primary:disabled{background:#e2e8f0;color:#94a3b8;cursor:not-allowed}
.krs-btn-secondary{display:inline-flex;align-items:center;gap:.45rem;padding:.48rem .95rem;border-radius:.45rem;border:1px solid #cbd5e1;background:#fff;color:#1e293b;font-size:.8125rem;font-weight:600;cursor:pointer;white-space:nowrap;text-decoration:none}
.krs-btn-secondary:hover:not(:disabled){background:#f8fafc}
.krs-btn-secondary:disabled{background:#f1f5f9;color:#94a3b8;border-color:#e2e8f0;cursor:not-allowed}
.pk-bc{font-size:.75rem;color:#64748b;margin-bottom:.25rem}
.pk-bc a{color:#64748b;text-decoration:none}
.pk-sec{padding:.5rem .9rem;font-size:.8125rem;font-weight:500;border-radius:.5rem;border:1px solid #cbd5e1;background:#fff;color:#334155;text-decoration:none}
.pk-pri{padding:.5rem 1.15rem;font-size:.8125rem;font-weight:600;border-radius:.5rem;border:none;background:#701a75;color:#fff;text-decoration:none;display:inline-flex;align-items:center}
.pk-dis{padding:.5rem 1.15rem;font-size:.8125rem;font-weight:500;border-radius:.5rem;border:1px solid #e2e8f0;background:#f1f5f9;color:#94a3b8;cursor:not-allowed}

.pk-grid{display:grid;grid-template-columns:1fr;gap:1.25rem;align-items:start}
@media(min-width:1024px){.pk-grid{grid-template-columns:2fr 1fr}}
.pk-card{background:#fff;border:1px solid #e2e8f0;border-radius:.75rem;padding:1.25rem}
.pk-filters{display:grid;grid-template-columns:1fr;gap:.5rem;margin-bottom:1rem}
@media(min-width:640px){.pk-filters{grid-template-columns:1.4fr 1fr 1fr}}
.pk-in{width:100%;padding:.5rem .75rem;border-radius:.5rem;border:1px solid #cbd5e1;font-size:.75rem;background:#fff;box-sizing:border-box}
.pk-in:focus{outline:2px solid #c4b5fd;outline-offset:1px;border-color:#701a75}
.pk-empty{border:1px dashed #cbd5e1;border-radius:.625rem;padding:1.5rem 1rem;text-align:center;background:#f8fafc;color:#0f172a}
.pk-empty-title{font-size:.875rem;font-weight:700}
.pk-empty-desc{font-size:.75rem;color:#64748b;margin-top:.25rem}

.pk-row{padding:.75rem .625rem;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;justify-content:space-between;gap:1rem;border-radius:.5rem}
.pk-row.is-draft{background:#fdf2f8;border-bottom-color:#fbcfe8}
.pk-del-link{color:#701a75;font-size:.75rem;font-weight:600;text-decoration:none;white-space:nowrap;background:none;border:0;padding:0;cursor:pointer}
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
.pk-modal-bg[hidden]{display:none}
.pk-modal{background:#fff;border-radius:.875rem;max-width:26rem;width:100%;padding:1.5rem;box-shadow:0 20px 25px -5px rgba(0,0,0,.15)}
.pk-toast{position:fixed;bottom:1.25rem;right:1.25rem;max-width:22rem;background:#fff;border:1px solid #fecaca;border-radius:.75rem;padding:.875rem 1rem;box-shadow:0 10px 15px -3px rgba(0,0,0,.12);z-index:40;display:flex;align-items:flex-start;justify-content:space-between;gap:.75rem;font-size:.75rem}
.pk-toast[hidden]{display:none}
.pk-conflict-badge{background:#fee2e2;color:#b91c1c;font-size:.68rem;font-weight:700;padding:.1rem .42rem;border-radius:.35rem;margin-left:.3rem}
@media(max-width:640px){.krs-page-head{flex-wrap:wrap}.krs-action-group{width:100%;justify-content:flex-end}}
</style>

<div class="pk-wrap">
    <div class="krs-page-head">
        <div>
            <div class="pk-bc">
                <a href="{{ route('dashboard') }}">Beranda</a> / <a href="{{ route('krs.index') }}">KRS</a> / <span>Pilih kelas</span>
            </div>
            <h1 class="krs-page-title">Pilih kelas</h1>
            <div class="krs-page-meta">
                KRS {{ $labelPeriode }} · Dosen PA: {{ $dosenPa }} · Pengisian sampai {{ $batasWaktu }}
            </div>
        </div>

        <div class="krs-action-group">
            <a href="{{ route('krs.index') }}" class="krs-btn-secondary">Kembali ke KRS</a>
            <button type="button" class="krs-btn-primary" data-krs-open-confirm {{ !$canSubmitKrs || $isLoadingKrs ? 'disabled' : '' }}>Ajukan KRS</button>
        </div>
    </div>

    @if($isSemesterAntara)
        <div class="pk-banner pk-info">
            <strong>KRS semester antara terpisah dari KRS reguler.</strong> Hanya mata kuliah yang disetujui prodi untuk semester antara yang ditampilkan.
        </div>
    @endif

    @if($isWaitingList)
        <div class="pk-banner pk-info" style="display:flex;justify-content:space-between;align-items:center">
            <span><strong>Anda masuk antrean kelas Statistika (IF211).</strong> Bila ada kursi kosong, Anda dipindahkan ke kelas ini secara otomatis.</span>
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
                    <input id="krsClassSearch" type="search" value="{{ $isKosong ? 'Robotika' : '' }}" placeholder="Cari kode atau nama mata kuliah" class="pk-in" aria-label="Cari kode atau nama mata kuliah">
                    <select class="pk-in"><option>Prodi: Teknik Informatika</option></select>
                    @if($isSemesterAntara)
                        <select class="pk-in" aria-label="Periode" disabled><option>Periode: Semester antara 2026</option></select>
                    @else
                        <select class="pk-in" aria-label="Ganti periode" onchange="window.location.href=this.value">
                            <option value="{{ request()->fullUrlWithQuery(['periode' => '20261']) }}" {{ $periodeCode === '20261' ? 'selected' : '' }}>Periode: 20261</option>
                            <option value="{{ request()->fullUrlWithQuery(['periode' => '20252']) }}" {{ $periodeCode === '20252' ? 'selected' : '' }}>Periode: 20252</option>
                        </select>
                    @endif
                </div>

                @if($isKosong)
                    <div id="krsCatalogRows"></div>
                    <div class="pk-empty" data-krs-no-results>
                        <div class="pk-empty-title">Tidak ada mata kuliah yang cocok</div>
                        <div class="pk-empty-desc" data-krs-empty-desc>
                            Tidak ada kelas "Robotika" yang ditawarkan untuk Teknik Informatika pada periode {{ $periodeCode }}. Coba kata kunci lain atau ubah filter.
                        </div>
                    </div>
                @else
                    <div id="krsCatalogRows">
                        @foreach($offerings as $item)
                            @php
                                $st = $item['status'] ?? 'available';
                                $isDraftRow = $st === 'in_draft';
                            @endphp
                            <div class="pk-row {{ $isDraftRow ? 'is-draft' : '' }}" data-krs-class-row data-code="{{ $item['code'] }}" data-name="{{ $item['name'] }}" data-schedule="{{ $item['schedule'] }}" data-sks="{{ $item['sks'] }}" data-semester="{{ $item['semester'] }}" data-status="{{ $st }}" data-note="{{ $item['note'] ?? '' }}">
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
                                        <button type="button" class="pk-del-link" data-krs-remove="{{ $item['code'] }}" data-schedule="{{ $item['schedule'] }}">Hapus dari draft</button>
                                    @elseif($st === 'full')
                                        <button type="button" class="pk-btn-sm" data-krs-waitlist="{{ $item['code'] }}">
                                            Masuk waiting list
                                        </button>
                                    @elseif($st === 'locked')
                                        <button type="button" disabled class="pk-dis" style="padding:.3rem .65rem;font-size:.75rem">Tambah</button>
                                    @else
                                        <button type="button" class="pk-btn-sm" data-krs-add="{{ $item['code'] }}" data-schedule="{{ $item['schedule'] }}">Tambah</button>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="pk-empty" data-krs-no-results hidden>
                        <div class="pk-empty-title">Tidak ada mata kuliah yang cocok</div>
                        <div class="pk-empty-desc" data-krs-empty-desc></div>
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
                        <strong id="krsUsedSks" style="font-size:.875rem;color:{{ $isValidasiGagal ? '#dc2626' : '#0f172a' }}">{{ $usedSks }} dari {{ $maxSks }}</strong>
                    </div>
                    <div class="pk-bar">
                        <div id="krsSksBar" class="pk-fill" style="width:{{ $progressPercent }}%;background:{{ $isValidasiGagal ? '#dc2626' : '#701a75' }}"></div>
                    </div>
                    <div style="font-size:.75rem;color:#64748b">
                        Batas SKS semester ini {{ $maxSks }}, dari IPK periode {{ $meta['periode_lalu'] }} ({{ $meta['ipk_lalu'] }}).
                    </div>
                </div>

                @if($isValidasiGagal)
                    <div id="krsValidationBanner" class="pk-banner pk-err">
                        <div style="font-weight:700;margin-bottom:.25rem">Draft belum bisa diajukan · 1 masalah</div>
                        <div>Jadwal IF307 Komputasi Awan bentrok dengan IF207 Rekayasa Perangkat Lunak. Hapus salah satu kelas yang bentrok.</div>
                    </div>
                @elseif(!$isSemesterAntara)
                    <div id="krsValidationBanner" class="pk-banner pk-ok">
                        <div style="font-weight:700">✓ Draft siap diajukan</div>
                        <div style="margin-top:.15rem">Prasyarat, batas SKS, dan jadwal sudah diperiksa. Tidak ada masalah.</div>
                    </div>
                @endif

                <div class="pk-card">
                    <h3 style="font-size:.875rem;font-weight:700;margin:0;color:#0f172a">Draft KRS</h3>
                    <div id="krsDraftSummary" style="font-size:.75rem;color:#64748b;margin:.2rem 0 .75rem">{{ $draftItems->count() }} mata kuliah · {{ $usedSks }} SKS</div>

                    <div id="krsDraftList">
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

    <div id="krsToast" class="pk-toast" role="status" aria-live="polite" {{ $isDuplikasi ? '' : 'hidden' }}>
        <span id="krsToastMessage">{{ $isDuplikasi ? 'Mata kuliah sudah ada di draft. Hapus dulu bila ingin pindah ke jadwal lain.' : '' }}</span>
        <button type="button" id="krsToastClose" aria-label="Tutup notifikasi" style="border:0;background:none;color:#64748b;cursor:pointer;font-size:1rem">×</button>
    </div>

    @if(!$isLoading)
        <div id="krsConfirmModal" class="pk-modal-bg" role="dialog" aria-modal="true" aria-labelledby="krsConfirmTitle" {{ $isKonfirmasi ? '' : 'hidden' }}>
            <div class="pk-modal">
                <form action="{{ route('krs.submit') }}" method="POST">
                    @csrf
                    <input type="hidden" name="action" value="submit_krs">
                    <h3 id="krsConfirmTitle" style="font-size:1rem;font-weight:700;margin:0 0 .5rem;color:#0f172a">Ajukan KRS periode {{ $periodeCode }}?</h3>
                    <p id="krsConfirmDescription" style="font-size:.8125rem;color:#475569;line-height:1.5;margin:0 0 1.25rem">
                        {{ $draftItems->count() }} mata kuliah, {{ $usedSks }} SKS akan dikirim ke <strong>{{ $dosenPa }}</strong> (Dosen PA). Setelah diajukan, KRS tidak bisa diubah sampai ada keputusan.
                    </p>
                    <div id="krsSubmitCourses">
                        @foreach($draftItems as $draft)
                            <input type="hidden" name="courses[]" value="{{ $draft['code'] }}">
                        @endforeach
                    </div>
                    <div style="display:flex;justify-content:flex-end;gap:.5rem">
                        <button type="button" class="pk-sec" data-krs-close-confirm>Batal</button>
                        <button type="submit" class="pk-pri" id="krsModalSubmitButton">Ajukan KRS</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
@unless($isLoading)
<script>
(() => {
    const search = document.getElementById('krsClassSearch');
    const list = document.getElementById('krsCatalogRows');
    const emptyState = document.querySelector('[data-krs-no-results]');
    const emptyDescription = document.querySelector('[data-krs-empty-desc]');
    const catalog = @json($offerings).map((course) => ({
        code: course.code,
        name: course.name,
        schedule: course.schedule,
        sks: Number(course.sks),
        semester: Number(course.semester),
        status: course.status || 'available',
        note: course.note || '',
    }));
    const isValidationState = {{ $isValidasiGagal ? 'true' : 'false' }};
    const isEmptyState = {{ $isKosong ? 'true' : 'false' }};
    let draft = isEmptyState ? [] : catalog
        .filter((course) => course.status === 'in_draft')
        .map(({ code, name, schedule, sks, semester }) => ({ code, name, schedule, sks, semester }));
    if (isValidationState) {
        const conflictingCourse = catalog.find((course) => course.code === 'IF207');
        if (conflictingCourse && !draft.some((course) => course.code === conflictingCourse.code)) {
            draft.push({ ...conflictingCourse });
        }
    }
    let waitlisted = {{ $isWaitingList ? "['IF211']" : '[]' }};
    const maxSks = {{ (int) $maxSks }};
    const submitButton = document.querySelector('[data-krs-open-confirm]');
    const modal = document.getElementById('krsConfirmModal');
    const modalDescription = document.getElementById('krsConfirmDescription');
    const modalSubmitButton = document.getElementById('krsModalSubmitButton');
    const submitCourses = document.getElementById('krsSubmitCourses');

    const escapeHtml = (value) => String(value).replace(/[&<>"']/g, (character) => ({
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;',
    })[character]);

    const getConflicts = () => {
        const conflicts = new Set();
        for (let first = 0; first < draft.length; first += 1) {
            for (let second = first + 1; second < draft.length; second += 1) {
                if (draft[first].schedule === draft[second].schedule) {
                    conflicts.add(draft[first].code);
                    conflicts.add(draft[second].code);
                }
            }
        }
        return conflicts;
    };

    const renderCatalog = () => {
        const query = search.value.trim().toLocaleLowerCase();
        const filtered = catalog.filter((course) => (
            !query || `${course.code} ${course.name}`.toLocaleLowerCase().includes(query)
        ));
        let visibleCount = 0;

        list.replaceChildren();
        filtered.forEach((course) => {
            visibleCount += 1;
            const selected = draft.some((item) => item.code === course.code && item.schedule === course.schedule);
            const sameCourseOtherTime = draft.some((item) => item.code === course.code && item.schedule !== course.schedule);
            const waiting = waitlisted.includes(course.code);
            const row = document.createElement('div');
            row.className = `pk-row${selected ? ' is-draft' : ''}`;

            const details = document.createElement('div');
            const title = document.createElement('div');
            const code = document.createElement('span');
            code.style.cssText = 'font-family:monospace;font-size:.75rem;color:#64748b';
            code.textContent = course.code;
            const name = document.createElement('strong');
            name.style.cssText = 'font-size:.875rem;color:#0f172a;margin-left:.45rem';
            name.textContent = course.name;
            title.append(code, name);

            const metadata = document.createElement('div');
            metadata.style.cssText = 'font-size:.75rem;color:#64748b;margin-top:.15rem';
            const metadataParts = [course.schedule, `${course.sks} SKS`, `Semester ${course.semester}`];
            if (course.status === 'full') metadataParts.push(waiting ? 'Penuh · Anda di antrean' : course.note);
            metadata.textContent = metadataParts.filter(Boolean).join(' · ');
            details.append(title, metadata);
            if (course.note && course.status === 'locked') {
                const note = document.createElement('div');
                note.style.cssText = 'font-size:.75rem;font-weight:600;margin-top:.15rem;color:#dc2626';
                note.textContent = `⚠ ${course.note}`;
                details.append(note);
            }

            const action = document.createElement('div');
            const button = document.createElement('button');
            button.type = 'button';
            button.className = 'pk-btn-sm';
            if (course.status === 'locked') {
                button.disabled = true;
                button.textContent = 'Tambah';
            } else if (course.status === 'full') {
                button.textContent = waiting ? 'Keluar dari antrean' : 'Masuk waiting list';
                button.addEventListener('click', () => toggleWaitlist(course));
            } else if (selected) {
                button.textContent = 'Hapus dari draft';
                button.addEventListener('click', () => removeFromDraft(course));
            } else if (sameCourseOtherTime || course.status === 'duplicate') {
                button.textContent = 'Tambah';
                button.addEventListener('click', () => showKrsToast(
                    'warn',
                    `${course.name} sudah ada di draft. Hapus dulu bila ingin pindah ke jadwal ini.`
                ));
            } else {
                button.textContent = 'Tambah';
                button.addEventListener('click', () => addToDraft(course));
            }
            action.append(button);
            row.append(details, action);
            list.append(row);
        });

        emptyState.hidden = visibleCount > 0;
        if (emptyDescription) {
            emptyDescription.textContent = query
                ? `Tidak ada kelas "${search.value.trim()}" yang cocok pada periode {{ $periodeCode }}. Coba kata kunci lain atau ubah filter.`
                : 'Tidak ada kelas yang ditawarkan pada periode {{ $periodeCode }}.';
        }
    };

    const renderDraft = () => {
        const totalSks = draft.reduce((total, course) => total + course.sks, 0);
        const conflicts = getConflicts();
        const issues = [];
        if (totalSks > maxSks) {
            issues.push(`Beban SKS (${totalSks}) melebihi batas maksimum ${maxSks} SKS.`);
        }
        if (conflicts.size > 0) {
            issues.push(`Jadwal bentrok pada: ${Array.from(conflicts).join(', ')}.`);
        }

        document.getElementById('krsUsedSks').textContent = `${totalSks} dari ${maxSks}`;
        const progress = document.getElementById('krsSksBar');
        progress.style.width = `${Math.min(100, Math.round((totalSks / Math.max(1, maxSks)) * 100))}%`;
        progress.style.background = issues.length > 0 ? '#dc2626' : '#701a75';
        document.getElementById('krsDraftSummary').textContent = `${draft.length} mata kuliah · ${totalSks} SKS`;

        const draftList = document.getElementById('krsDraftList');
        draftList.replaceChildren();
        if (draft.length === 0) {
            const emptyDraft = document.createElement('div');
            emptyDraft.style.cssText = 'font-size:.75rem;color:#64748b';
            emptyDraft.textContent = 'Belum ada mata kuliah di draft.';
            draftList.append(emptyDraft);
        } else {
            draft.forEach((course) => {
                const item = document.createElement('div');
                item.className = 'pk-draft-item';
                item.innerHTML = `<div><div style="font-weight:600;color:#0f172a">${escapeHtml(course.code)} · ${escapeHtml(course.name)}${conflicts.has(course.code) ? ' <span class="pk-conflict-badge">• Bentrok</span>' : ''}</div><div style="color:#64748b">${escapeHtml(course.schedule)}</div></div><strong style="color:#334155;white-space:nowrap">${course.sks} SKS</strong>`;
                draftList.append(item);
            });
        }

        const banner = document.getElementById('krsValidationBanner');
        if (banner) {
            banner.className = `pk-banner ${issues.length ? 'pk-err' : 'pk-ok'}`;
            banner.innerHTML = issues.length
                ? `<strong>Draft belum bisa diajukan: ${issues.length} masalah</strong><br>${issues.map(escapeHtml).join('<br>')}`
                : '<strong>Draft siap diajukan</strong><br>Prasyarat, batas SKS, dan jadwal sudah diperiksa. Tidak ada masalah.';
        }

        const canSubmit = draft.length > 0 && issues.length === 0;
        submitButton.disabled = !canSubmit;
        if (modalSubmitButton) modalSubmitButton.disabled = !canSubmit;
        if (modalDescription) {
            modalDescription.textContent = `${draft.length} mata kuliah, ${totalSks} SKS akan dikirim ke {{ $dosenPa }} (Dosen PA). Setelah diajukan, KRS tidak bisa diubah sampai ada keputusan.`;
        }
        if (submitCourses) {
            submitCourses.replaceChildren(...draft.map((course) => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'courses[]';
                input.value = course.code;
                return input;
            }));
        }

        renderCatalog();
    };

    const addToDraft = (course) => {
        if (!draft.some((item) => item.code === course.code && item.schedule === course.schedule)) {
            draft.push({ ...course });
            renderDraft();
        }
    };
    const removeFromDraft = (course) => {
        draft = draft.filter((item) => !(item.code === course.code && item.schedule === course.schedule));
        renderDraft();
    };
    const toggleWaitlist = (course) => {
        if (waitlisted.includes(course.code)) {
            waitlisted = waitlisted.filter((code) => code !== course.code);
            showKrsToast('info', `Anda keluar dari antrean kelas ${course.name}.`);
        } else {
            waitlisted.push(course.code);
            showKrsToast('info', `Anda masuk antrean kelas ${course.name}.`);
        }
        renderCatalog();
    };
    const openConfirm = () => {
        if (!submitButton.disabled && modal) modal.hidden = false;
    };
    const closeConfirm = () => {
        if (modal) modal.hidden = true;
    };

    window.showKrsToast = (type, message) => {
        const toast = document.getElementById('krsToast');
        document.getElementById('krsToastMessage').textContent = message;
        toast.style.borderColor = type === 'warn' ? '#fecaca' : '#bfdbfe';
        toast.hidden = false;
        window.setTimeout(() => { toast.hidden = true; }, 3500);
    };

    search.addEventListener('input', renderCatalog);
    document.querySelector('[data-krs-open-confirm]')?.addEventListener('click', openConfirm);
    document.querySelector('[data-krs-close-confirm]')?.addEventListener('click', closeConfirm);
    modal?.addEventListener('click', (event) => {
        if (event.target === modal) closeConfirm();
    });
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') closeConfirm();
    });
    document.getElementById('krsToastClose')?.addEventListener('click', () => {
        document.getElementById('krsToast').hidden = true;
    });

    renderDraft();
    if ({{ $isKonfirmasi ? 'true' : 'false' }} && !submitButton.disabled) modal.hidden = false;
})();
</script>
@endunless
@endsection