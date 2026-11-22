@extends('layouts.app')

@section('title', 'Beranda - SIA ITI')

@section('content')
@php
    $state = $state ?? 'default';
    $selectedDay = (int) ($selectedDay ?? 2);
    $weekDays = $weekDays ?? [];
    $daySchedule = $daySchedule ?? [];
    $classes = $classes ?? [];
    $tasks = $tasks ?? [];

    $isApprovedClasses = in_array($state, ['default', 'tidak_ada_kelas_hari_ini'], true);
    $isLoading = $state === 'loading';
    $isError500 = $state === 'error_500';
@endphp

<style>
.db-wrap{max-width:1200px;margin:0 auto;padding:1.5rem 1rem;color:#1e293b}
.db-bc{font-size:.75rem;color:#64748b;margin-bottom:.25rem}
.db-h1{font-size:1.5rem;font-weight:700;color:#0f172a;margin:0}
.db-sub{font-size:.75rem;color:#64748b;margin-top:.2rem}
.db-banner{display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:.75rem;border-radius:.75rem;padding:.875rem 1rem;margin:1rem 0;font-size:.8125rem;line-height:1.45}
.db-banner-info{background:#eff6ff;border:1px solid #bfdbfe;color:#1e3a8a}
.db-banner-warn{background:#fffbeb;border:1px solid #fde68a;color:#92400e}
.db-banner-err{background:#fef2f2;border:1px solid #fecaca;color:#991b1b}
.db-cta-purple{background:#6d28d9;color:#fff;text-decoration:none;padding:.4rem .85rem;border-radius:.5rem;font-size:.75rem;font-weight:600;white-space:nowrap}
.db-btn{background:#fff;border:1px solid #cbd5e1;color:#334155;text-decoration:none;padding:.38rem .75rem;border-radius:.5rem;font-size:.75rem;font-weight:600;white-space:nowrap}
.db-grid{display:grid;grid-template-columns:1fr;gap:1.25rem;margin-top:1rem;align-items:start}
@media(min-width:1024px){.db-grid{grid-template-columns:1.65fr 1fr}}
.db-card{background:#fff;border:1px solid #e2e8f0;border-radius:.75rem;padding:1.25rem;margin-bottom:1.25rem}
.db-card:last-child{margin-bottom:0}
.db-card-head{display:flex;align-items:baseline;justify-content:space-between;gap:.5rem;margin-bottom:.875rem}
.db-card-title{font-size:1rem;font-weight:700;color:#0f172a;margin:0}
.db-card-sub{font-size:.75rem;color:#64748b}
.db-link{font-size:.75rem;font-weight:600;color:#1d4ed8;text-decoration:none}
.db-cls-grid{display:grid;grid-template-columns:1fr;gap:.875rem}
@media(min-width:640px){.db-cls-grid{grid-template-columns:repeat(2,1fr)}}
.db-cls-card{border:1px solid #e2e8f0;border-radius:.625rem;overflow:hidden;background:#fff}
.db-cls-top{padding:.75rem .875rem;color:#fff;display:flex;align-items:flex-start;justify-content:space-between;gap:.5rem}
.db-cls-top strong{display:block;font-size:.875rem}
.db-cls-top span{display:block;font-size:.7rem;opacity:.92;margin-top:.15rem}
.db-avatar{width:1.75rem;height:1.75rem;border-radius:9999px;background:rgba(255,255,255,.22);display:flex;align-items:center;justify-content:center;font-size:.65rem;font-weight:700;flex-shrink:0}
.db-cls-foot{padding:.6rem .875rem;display:flex;align-items:center;justify-content:space-between;font-size:.72rem;color:#475569;background:#fafafa}
.db-empty{border:1px dashed #cbd5e1;border-radius:.625rem;padding:1rem;background:#f8fafc}
.db-empty strong{display:block;font-size:.875rem;color:#0f172a;margin-bottom:.2rem}
.db-empty span{display:block;font-size:.75rem;color:#64748b}
.db-days{display:grid;grid-template-columns:repeat(7,1fr);gap:.25rem;text-align:center;margin-bottom:.875rem}
.db-day-link{display:flex;flex-direction:column;align-items:center;padding:.35rem .15rem;border-radius:.5rem;text-decoration:none;color:#334155}
.db-day-short{font-size:.68rem;color:#64748b}
.db-day-num{width:1.75rem;height:1.75rem;line-height:1.75rem;border-radius:9999px;font-size:.78rem;font-weight:600;margin-top:.15rem}
.db-day-link.active .db-day-num{background:#1d4ed8;color:#fff}
.db-dot{width:.28rem;height:.28rem;border-radius:9999px;background:#2563eb;margin-top:.2rem}
.db-dot-empty{width:.28rem;height:.28rem;margin-top:.2rem}
.db-sched-item{border-left:3px solid #2563eb;padding:.45rem .65rem;background:#f8fafc;border-radius:0 .45rem .45rem 0;margin-bottom:.5rem}
.db-sched-item:last-child{margin-bottom:0}
.db-sched-item strong{display:block;font-size:.8125rem;color:#0f172a}
.db-sched-item span{display:block;font-size:.72rem;color:#64748b;margin-top:.1rem}
.db-task-item{border-left:3px solid #2563eb;padding:.45rem .65rem;background:#fafafa;border-radius:0 .45rem .45rem 0;margin-bottom:.625rem}
.db-task-item:last-child{margin-bottom:0}
.db-task-course{font-size:.68rem;font-weight:700;color:#475569}
.db-task-title{font-size:.8rem;font-weight:600;color:#0f172a;margin-top:.1rem}
.db-task-due{font-size:.7rem;color:#64748b;margin-top:.1rem}
.db-skel{height:2.2rem;background:#f1f5f9;border-radius:.45rem;margin-bottom:.5rem}
</style>

<div class="db-wrap">
    <div class="db-bc">Beranda</div>
    <h1 class="db-h1">Beranda</h1>
    <div class="db-sub">Budi Santoso · NIM 312020001 · Teknik Informatika</div>

    @if($state === 'kosong')
        <div class="db-banner db-banner-info">
            <div><strong>ⓘ Periode KRS 20261 dibuka</strong><br>Pilih dan ajukan KRS sebelum periode ditutup. Tanggal penutupan mengikuti kalender akademik.</div>
            <a href="{{ route('krs.index', ['state' => 'pengisian_krs']) }}" class="db-cta-purple">Isi KRS</a>
        </div>
    @elseif($state === 'ditolak')
        <div class="db-banner db-banner-err">
            <div><strong>⊗ KRS 20261 ditolak Dosen PA</strong><br>Dr. Rina Kartika, M.Kom. · 14 Agu 2026, 10.02 WIB. Catatan: kurangi beban menjadi 18 SKS, tunda KRS Pemrograman Perangkat Bergerak ke semester depan, lalu ajukan ulang.</div>
            <a href="{{ route('krs.index', ['state' => 'ditolak_dosen_pa']) }}" class="db-cta-purple">Perbaiki KRS</a>
        </div>
    @elseif($state === 'menunggu_persetujuan')
        <div class="db-banner db-banner-info">
            <div><strong>ⓘ KRS 20261 menunggu persetujuan Dosen PA</strong><br>Diajukan 13 Agu 2026, 14.20 WIB ke Dr. Rina Kartika, M.Kom.</div>
            <a href="{{ route('krs.index', ['state' => 'menunggu_persetujuan']) }}" class="db-cta-purple">Lihat status KRS</a>
        </div>
    @endif

    @if($isLoading)
        <div class="db-grid">
            <div class="db-card">
                <div class="db-card-title" style="margin-bottom:.75rem">Kelas periode 20261</div>
                @for($i = 0; $i < 4; $i++) <div class="db-skel"></div> @endfor
            </div>
            <div>
                <div class="db-card">
                    <div class="db-card-title" style="margin-bottom:.75rem">Jadwal pekan ini</div>
                    @for($i = 0; $i < 2; $i++) <div class="db-skel"></div> @endfor
                </div>
                <div class="db-card">
                    <div class="db-card-title" style="margin-bottom:.75rem">Perlu dikerjakan</div>
                    @for($i = 0; $i < 2; $i++) <div class="db-skel"></div> @endfor
                </div>
            </div>
        </div>
    @else
        <div class="db-grid">
            <div class="db-card">
                <div class="db-card-head">
                    <div>
                        <h2 class="db-card-title">Kelas periode 20261</h2>
                        <div class="db-card-sub">
                            @if($isApprovedClasses)
                                6 mata kuliah · 18 SKS · KRS disetujui 14 Agu 2026
                            @elseif($state === 'kosong')
                                Belum ada kelas untuk periode ini
                            @else
                                Belum ada kelas tersedia
                            @endif
                        </div>
                    </div>
                    @if($isApprovedClasses)
                        <a href="{{ route('jadwal.index') }}" class="db-link">Lihat jadwal →</a>
                    @endif
                </div>

                @if($isError500)
                    <div class="db-banner db-banner-err" style="margin:0">
                        <div><strong>⚠ Gagal memuat kelas</strong><br>Server gagal memproses permintaan. Coba lagi beberapa saat.</div>
                        <a href="{{ route('dashboard', ['state' => 'default', 'day' => $selectedDay]) }}" class="db-btn">↻ Coba lagi</a>
                    </div>
                @elseif($isApprovedClasses)
                    <div class="db-cls-grid">
                        @foreach($classes as $c)
                            <div class="db-cls-card">
                                <div class="db-cls-top" style="background:{{ $c['color'] }}">
                                    <div><strong>{{ $c['name'] }}</strong><span>{{ $c['schedule'] }}</span><span>{{ $c['room'] }}</span></div>
                                    <div class="db-avatar">{{ $c['badge'] }}</div>
                                </div>
                                <div class="db-cls-foot"><span>{{ $c['lecturer'] }}</span><strong>{{ $c['attendance'] }}</strong></div>
                            </div>
                        @endforeach
                    </div>
                @elseif($state === 'kosong')
                    <div class="db-empty"><strong>Belum ada kelas</strong><span>KRS periode 20261 belum diisi atau belum disetujui.</span></div>
                @else
                    <div class="db-empty">
                        <strong>Kelas belum tersedia</strong>
                        <span>@if($state === 'ditolak')Perbaiki KRS sesuai catatan Dosen PA, lalu ajukan ulang.@else Daftar kelas, jadwal, dan presensi muncul setelah KRS 20261 disetujui Dosen PA.@endif</span>
                    </div>
                @endif
            </div>

            <div>
                <div class="db-card">
                    <div class="db-card-head">
                        <h2 class="db-card-title">Jadwal pekan ini</h2>
                        <span class="db-card-sub">Senin, 2 Nov 2026</span>
                    </div>
                    <div class="db-days">
                        @foreach($weekDays as $wd)
                            <a href="{{ route('dashboard', ['state' => $state, 'day' => $wd['day']]) }}" class="db-day-link {{ $selectedDay === $wd['day'] ? 'active' : '' }}">
                                <span class="db-day-short">{{ $wd['short'] }}</span>
                                <span class="db-day-num">{{ $wd['day'] }}</span>
                                @if($wd['has_dot'])<span class="db-dot"></span>@else<span class="db-dot-empty"></span>@endif
                            </a>
                        @endforeach
                    </div>
                    @if(!$isApprovedClasses)
                        <div style="font-size:.75rem;color:#64748b;padding:.35rem 0"><strong style="color:#0f172a;display:block">Belum ada jadwal</strong>Jadwal muncul setelah KRS 20261 disetujui Dosen PA.</div>
                    @elseif(count($daySchedule) === 0)
                        <div style="font-size:.75rem;color:#64748b;padding:.35rem 0">
                            <strong style="color:#0f172a;display:block">Tidak ada kelas hari ini</strong>
                            @if($selectedDay === 6)Kelas berikutnya: Sabtu, 7 Nov 2026, 08.00 · R. 103.@else Tidak ada jadwal perkuliahan pada tanggal ini.@endif
                        </div>
                    @else
                        @foreach($daySchedule as $item)
                            <div class="db-sched-item" style="border-left-color:{{ $item['color'] }}"><strong>{{ $item['title'] }}</strong><span>{{ $item['meta'] }}</span></div>
                        @endforeach
                    @endif
                </div>

                <div class="db-card">
                    <div class="db-card-head">
                        <div style="display:flex;align-items:center;gap:.4rem">
                            <h2 class="db-card-title">Perlu dikerjakan</h2>
                            @if(count($tasks) > 0)<span style="background:#f1f5f9;color:#334155;font-size:.7rem;font-weight:700;padding:.1rem .45rem;border-radius:9999px">+ {{ count($tasks) }}</span>@endif
                        </div>
                        <span style="font-size:.65rem;font-weight:700;color:#6d28d9;background:#f5f3ff;border:1px solid #ddd6fe;padding:.1rem .45rem;border-radius:.375rem">USULAN</span>
                    </div>
                    @if(count($tasks) === 0)
                        <div style="font-size:.75rem;color:#64748b"><strong style="color:#0f172a;display:block">Tidak ada tugas</strong>Belum ada tugas yang mendekati tenggat.</div>
                    @else
                        @foreach($tasks as $t)
                            <div class="db-task-item" style="border-left-color:{{ $t['color'] }}"><div class="db-task-course">{{ $t['course'] }}</div><div class="db-task-title">{{ $t['title'] }}</div><div class="db-task-due">{{ $t['due'] }}</div></div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
