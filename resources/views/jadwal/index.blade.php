@extends('layouts.app')

@section('title', 'Jadwal Kuliah - SIA ITI')

@section('content')
@php
    $state = $state ?? 'default';
    $viewMode = $viewMode ?? 'pekan';
    $items = $items ?? [];
    $weekLabel = $weekLabel ?? '2–8 Nov 2026';

    $isChanged = $state === 'jadwal_berubah';
    $isUnapproved = $state === 'krs_belum_disetujui';
    $isUnpublished = $state === 'belum_dipublikasikan';
    $isLoading = $state === 'loading';
    $isError500 = $state === 'error_500';
    $hasScheduleData = !$isUnapproved && !$isUnpublished && !$isLoading && !$isError500;

    $days = [
        ['key' => 'Sen', 'head' => 'Sen, 2 Nov'],
        ['key' => 'Sel', 'head' => 'Sel, 3 Nov'],
        ['key' => 'Rab', 'head' => 'Rab, 4 Nov'],
        ['key' => 'Kam', 'head' => 'Kam, 5 Nov'],
        ['key' => 'Jum', 'head' => 'Jum, 6 Nov'],
        ['key' => 'Sab', 'head' => 'Sab, 7 Nov'],
        ['key' => 'Min', 'head' => 'Min, 8 Nov'],
    ];
    $hours = range(7, 17);
    $pxPerHour = 44;
@endphp

<style>
.jd-wrap{max-width:1200px;margin:0 auto;padding:1.5rem 1rem;color:#1e293b}
.jd-bc{font-size:.75rem;color:#64748b;margin-bottom:.25rem}
.jd-bc a{color:#64748b;text-decoration:none}
.jd-h1{font-size:1.5rem;font-weight:700;color:#0f172a;margin:0}
.jd-sub{font-size:.75rem;color:#64748b;margin-top:.25rem;margin-bottom:1.25rem}
.jd-toolbar{display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:.75rem;margin-bottom:1rem}
.jd-tabs{display:inline-flex;background:#f1f5f9;padding:.2rem;border-radius:.5rem;border:1px solid #e2e8f0}
.jd-tab{padding:.35rem .8rem;font-size:.8125rem;font-weight:600;border-radius:.375rem;text-decoration:none;color:#475569}
.jd-tab.active{background:#fff;color:#0f172a;box-shadow:0 1px 2px rgba(0,0,0,.06)}
.jd-nav{display:flex;align-items:center;gap:.375rem;font-size:.8125rem}
.jd-btn{padding:.35rem .7rem;border:1px solid #cbd5e1;border-radius:.5rem;background:#fff;color:#334155;text-decoration:none;font-weight:500}
.jd-alert-warn{background:#fffbeb;border:1px solid #fde68a;color:#92400e;border-radius:.75rem;padding:.75rem 1rem;font-size:.8125rem;margin-bottom:1rem;line-height:1.45}
.jd-alert-err{background:#fef2f2;border:1px solid #fecaca;color:#991b1b;border-radius:.75rem;padding:.875rem 1rem;font-size:.8125rem;margin-bottom:1rem}
.jd-card{background:#fff;border:1px solid #e2e8f0;border-radius:.75rem;padding:1.25rem}
.jd-cal-scroll{overflow-x:auto}
.jd-cal{min-width:820px;border:1px solid #e2e8f0;border-radius:.625rem;overflow:hidden}
.jd-cal-head{display:grid;grid-template-columns:4rem repeat(7,1fr);background:#f8fafc;border-bottom:1px solid #e2e8f0;font-size:.75rem;font-weight:600;color:#475569}
.jd-cal-head div{padding:.6rem .5rem;border-right:1px solid #e2e8f0;text-align:center}
.jd-cal-head div:last-child{border-right:none}
.jd-cal-body{display:grid;grid-template-columns:4rem repeat(7,1fr);position:relative}
.jd-time-col{border-right:1px solid #e2e8f0;background:#fafafa}
.jd-time-slot{height:44px;padding:.2rem .35rem;font-size:.7rem;color:#64748b;border-bottom:1px solid #f1f5f9;box-sizing:border-box}
.jd-day-col{position:relative;border-right:1px solid #e2e8f0;background-image:repeating-linear-gradient(to bottom,transparent 0,transparent 43px,#f1f5f9 43px,#f1f5f9 44px)}
.jd-day-col:last-child{border-right:none}
.jd-ev{position:absolute;left:.25rem;right:.25rem;border-radius:.4rem;padding:.35rem .45rem;font-size:.7rem;line-height:1.3;border-left:3px solid;box-sizing:border-box;overflow:hidden}
.jd-list-day{margin-bottom:1rem}
.jd-list-day:last-child{margin-bottom:0}
.jd-list-title{font-size:.8125rem;font-weight:700;color:#0f172a;padding:.4rem 0;border-bottom:1px solid #e2e8f0;margin-bottom:.35rem}
.jd-list-row{display:grid;grid-template-columns:6.5rem 5rem 1fr 9rem 12rem 7rem;gap:.5rem;align-items:center;padding:.55rem .4rem;border-bottom:1px solid #f8fafc;font-size:.8125rem}
@media(max-width:900px){.jd-list-row{grid-template-columns:1fr;gap:.2rem}}
.jd-tag-ok{background:#ecfdf5;color:#047857;border:1px solid #a7f3d0;padding:.15rem .5rem;border-radius:9999px;font-size:.7rem;font-weight:600;display:inline-block}
.jd-tag-warn{background:#fffbeb;color:#b45309;border:1px solid #fde68a;padding:.15rem .5rem;border-radius:9999px;font-size:.7rem;font-weight:600;display:inline-block}
.jd-skel{height:2.25rem;background:#f1f5f9;border-radius:.45rem;margin-bottom:.5rem}
</style>

<div class="jd-wrap">
    <div class="jd-bc"><a href="{{ route('dashboard') }}">Beranda</a> / <span>Jadwal</span></div>
    <h1 class="jd-h1">Jadwal Kuliah</h1>
    <div class="jd-sub">Periode 20261 · 6 mata kuliah · 18 SKS · sesuai KRS yang disetujui 14 Agu 2026</div>

    <div class="jd-toolbar">
        <div class="jd-tabs">
            <a href="{{ route('jadwal.index', ['state' => $state, 'view' => 'pekan']) }}" class="jd-tab {{ $viewMode === 'pekan' ? 'active' : '' }}">Pekan</a>
            <a href="{{ route('jadwal.index', ['state' => $state, 'view' => 'daftar']) }}" class="jd-tab {{ $viewMode === 'daftar' ? 'active' : '' }}">Daftar</a>
        </div>

        <div class="jd-nav">
            <a href="{{ route('jadwal.index', ['state' => $state, 'view' => $viewMode]) }}" class="jd-btn">‹</a>
            <span style="font-weight:600;color:#0f172a;padding:0 .35rem">{{ $weekLabel }}</span>
            <a href="{{ route('jadwal.index', ['state' => $state, 'view' => $viewMode]) }}" class="jd-btn">›</a>
            <a href="{{ route('jadwal.index', ['state' => $state, 'view' => $viewMode]) }}" class="jd-btn">Pekan ini</a>
        </div>
    </div>

    @if($isChanged)
        <div class="jd-alert-warn">
            <strong>⚠ Ada 2 perubahan jadwal pekan ini</strong><br>
            • Senin, 9 Nov: Pemrograman Web pindah dari R. 204 ke R. 302 (ruang dipakai ujian susulan).<br>
            • Statistika pindah dari Sabtu, 14 Nov 08.00 ke Jumat, 13 Nov 13.00–15.10 (dosen bertugas di luar kampus).
        </div>
    @endif

    @if($isError500)
        <div class="jd-alert-err">
            <div style="font-weight:700;margin-bottom:.2rem">⚠ Gagal memuat jadwal</div>
            <div style="margin-bottom:.65rem">Server gagal memproses permintaan. Coba lagi beberapa saat.</div>
            <a href="{{ route('jadwal.index', ['state' => 'default', 'view' => $viewMode]) }}" class="jd-btn">Coba lagi</a>
        </div>
    @endif

    @if($isUnapproved)
        <div class="jd-card">
            <div style="font-weight:700;font-size:.9375rem;color:#0f172a;margin-bottom:.25rem">Jadwal belum tersedia</div>
            <div style="font-size:.8125rem;color:#64748b;margin-bottom:.85rem">
                Jadwal muncul setelah KRS periode 20261 disetujui Dosen PA. KRS Anda diajukan 13 Agu 2026 dan masih menunggu keputusan.
            </div>
            <a href="{{ route('krs.index', ['state' => 'menunggu_persetujuan']) }}" class="jd-btn">Lihat status KRS</a>
        </div>
    @elseif($isUnpublished)
        <div class="jd-card">
            <div style="font-weight:700;font-size:.9375rem;color:#0f172a;margin-bottom:.25rem">Jadwal periode 20261 belum dipublikasikan</div>
            <div style="font-size:.8125rem;color:#64748b">
                KRS Anda sudah disetujui. Jadwal resmi (hari, jam, ruang, dan dosen) akan tampil di sini setelah BAAK mempublikasikannya.
            </div>
        </div>
    @elseif($isLoading)
        <div class="jd-card">
            @for($i = 0; $i < 6; $i++)
                <div class="jd-skel"></div>
            @endfor
        </div>
    @elseif($hasScheduleData)
        <div class="jd-card">
            @if($viewMode === 'pekan')
                <div class="jd-cal-scroll">
                    <div class="jd-cal">
                        <div class="jd-cal-head">
                            <div>Jam</div>
                            @foreach($days as $d)
                                <div>{{ $d['head'] }}</div>
                            @endforeach
                        </div>
                        <div class="jd-cal-body" style="height:{{ count($hours) * $pxPerHour }}px">
                            <div class="jd-time-col">
                                @foreach($hours as $h)
                                    <div class="jd-time-slot">{{ sprintf('%02d.00', $h) }}</div>
                                @endforeach
                            </div>
                            @foreach($days as $d)
                                <div class="jd-day-col">
                                    @foreach($items as $ev)
                                        @if($ev['day'] === $d['key'])
                                            @php
                                                $topPx = max(0, ($ev['start_hour'] - 7) * $pxPerHour);
                                                $heightPx = max(34, $ev['duration_hours'] * $pxPerHour - 3);
                                            @endphp
                                            <div class="jd-ev" style="top:{{ $topPx }}px;height:{{ $heightPx }}px;background:{{ $ev['bg'] }};border-left-color:{{ $ev['accent'] }}">
                                                <strong style="color:#0f172a;display:block">{{ $ev['name'] }}</strong>
                                                <span style="color:#475569;display:block">{{ $ev['start'] }}–{{ $ev['end'] }} · {{ $ev['room'] }}</span>
                                                <span style="color:#64748b;display:block">{{ $ev['lecturer'] }}</span>
                                                @if($ev['status'] !== 'Terjadwal')
                                                    <span style="color:#b45309;font-weight:700;display:block;margin-top:.15rem">⚠ {{ $ev['status'] }}</span>
                                                @endif
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @else
                @foreach($days as $d)
                    @php $dayItems = collect($items)->where('day', $d['key'])->values(); @endphp
                    <div class="jd-list-day">
                        <div class="jd-list-title">{{ $d['head'] }} 2026</div>
                        @if($dayItems->isEmpty())
                            <div style="font-size:.75rem;color:#94a3b8;padding:.4rem">Tidak ada kelas</div>
                        @else
                            @foreach($dayItems as $row)
                                <div class="jd-list-row">
                                    <span style="font-weight:600;color:#334155">{{ $row['start'] }}–{{ $row['end'] }}</span>
                                    <span style="font-family:monospace;color:#64748b">{{ $row['code'] }}</span>
                                    <strong style="color:#0f172a">{{ $row['name'] }}</strong>
                                    <span style="color:#475569">{{ $row['room'] }}</span>
                                    <span style="color:#64748b">{{ $row['lecturer'] }}</span>
                                    <span>
                                        @if($row['status'] === 'Terjadwal')
                                            <span class="jd-tag-ok">• Terjadwal</span>
                                        @else
                                            <span class="jd-tag-warn">⚠ {{ $row['status'] }}</span>
                                        @endif
                                    </span>
                                </div>
                            @endforeach
                        @endif
                    </div>
                @endforeach
            @endif
        </div>
    @endif
</div>
@endsection
