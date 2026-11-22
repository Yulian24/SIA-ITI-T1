@extends('layouts.app')

@section('title', 'Nilai Mahasiswa - SIA ITI')

@section('content')
@php
    $state = $state ?? 'default';
    $tab = $tab ?? 'khs';
    $periode = $periode ?? '20252';
    $periods = $periods ?? [];
    $currentKhs = $currentKhs ?? [];
    $transcriptGroups = $transcriptGroups ?? [];

    $isLoading = $state === 'loading';
    $isError500 = $state === 'error_500';
    $isEmptyKhs = $tab === 'khs' && count($currentKhs['rows'] ?? []) === 0;
@endphp

<style>
.nl-wrap{max-width:1200px;margin:0 auto;padding:1.5rem 1rem;color:#1e293b}
.nl-bc{font-size:.75rem;color:#64748b;margin-bottom:.25rem}.nl-bc a{color:#64748b;text-decoration:none}
.nl-h1{font-size:1.5rem;font-weight:700;color:#0f172a;margin:0}.nl-sub{font-size:.75rem;color:#64748b;margin-top:.2rem;margin-bottom:1rem}
.nl-tabs{display:flex;gap:1.25rem;border-bottom:1px solid #e2e8f0;margin-bottom:1.1rem}
.nl-tab{padding:.55rem .2rem;font-size:.875rem;font-weight:600;text-decoration:none;color:#64748b;border-bottom:2px solid transparent;margin-bottom:-1px}.nl-tab.active{color:#1d4ed8;border-bottom-color:#1d4ed8}
.nl-filter-row{display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:.75rem;margin-bottom:1rem}.nl-filter-left{display:flex;flex-wrap:wrap;align-items:center;gap:.5rem;font-size:.8125rem}
.nl-select{padding:.38rem .7rem;border:1px solid #cbd5e1;border-radius:.5rem;background:#fff;color:#0f172a;font-size:.8125rem;font-weight:500}.nl-btn-dl{padding:.4rem .8rem;border:1px solid #cbd5e1;border-radius:.5rem;background:#fff;color:#334155;font-size:.75rem;font-weight:600;cursor:pointer}.nl-btn-dl:disabled{opacity:.5;cursor:not-allowed;background:#f8fafc}
.nl-btn-sec{display:inline-block;padding:.38rem .75rem;border:1px solid #cbd5e1;border-radius:.5rem;background:#fff;color:#334155;font-size:.75rem;font-weight:600;text-decoration:none}
.nl-pill-ok{background:#ecfdf5;color:#047857;border:1px solid #a7f3d0;padding:.15rem .55rem;border-radius:9999px;font-size:.72rem;font-weight:600}.nl-pill-warn{background:#fffbeb;color:#b45309;border:1px solid #fde68a;padding:.15rem .55rem;border-radius:9999px;font-size:.72rem;font-weight:600}.nl-pill-muted{background:#f1f5f9;color:#475569;border:1px solid #cbd5e1;padding:.15rem .55rem;border-radius:9999px;font-size:.72rem;font-weight:600}
.nl-kpi-grid{display:grid;grid-template-columns:1fr;gap:.875rem;margin-bottom:1.15rem}@media(min-width:768px){.nl-kpi-grid{grid-template-columns:repeat(3,1fr)}}.nl-kpi,.nl-card{background:#fff;border:1px solid #e2e8f0;border-radius:.75rem}.nl-kpi{padding:1rem}.nl-card{padding:1.25rem;margin-bottom:1rem}.nl-kpi-lbl{font-size:.75rem;color:#64748b;font-weight:600}.nl-kpi-val{font-size:1.55rem;font-weight:700;color:#0f172a;margin:.25rem 0}.nl-kpi-sub{font-size:.72rem;color:#64748b}
.nl-tbl{width:100%;border-collapse:collapse;font-size:.8125rem;margin-top:.75rem}.nl-tbl th{text-align:left;padding:.55rem .45rem;background:#f8fafc;color:#475569;font-size:.75rem;font-weight:600;border-bottom:1px solid #e2e8f0}.nl-tbl td{padding:.6rem .45rem;border-bottom:1px solid #f1f5f9;vertical-align:middle}.nl-group-row td{background:#f1f5f9;font-weight:700;color:#334155}.nl-foot{display:flex;justify-content:space-between;gap:1rem;margin-top:.75rem;padding-top:.75rem;border-top:1px solid #e2e8f0;font-size:.7rem;color:#64748b}
.nl-empty{border:1px dashed #cbd5e1;border-radius:.625rem;padding:1.25rem;background:#f8fafc;text-align:center;margin-top:.75rem}.nl-fail{color:#b91c1c;font-size:.72rem;font-weight:700}.nl-err{background:#fef2f2;border:1px solid #fecaca;color:#991b1b;border-radius:.75rem;padding:.875rem 1rem;font-size:.8125rem;margin-bottom:1rem}.nl-skel{height:2.25rem;background:#f1f5f9;border-radius:.45rem;margin-bottom:.5rem}
@media(max-width:900px){.nl-tbl{display:block;overflow-x:auto}.nl-foot{flex-direction:column}}
</style>

<div class="nl-wrap">
    <div class="nl-bc"><a href="{{ route('dashboard') }}">Beranda</a> / <span>Nilai</span></div>
    <h1 class="nl-h1">Nilai</h1>
    <div class="nl-sub">Teknik Informatika (S1) · Angkatan 2025 · data per Senin, 2 Nov 2026, 10.15 WIB</div>

    <div class="nl-tabs">
        <a href="{{ route('nilai.index', ['tab' => 'khs', 'periode' => $periode, 'state' => $state]) }}" class="nl-tab {{ $tab === 'khs' ? 'active' : '' }}">KHS</a>
        <a href="{{ route('nilai.index', ['tab' => 'transkrip', 'state' => $state]) }}" class="nl-tab {{ $tab === 'transkrip' ? 'active' : '' }}">Transkrip</a>
    </div>

    @if($isError500)
        <div class="nl-err">
            <div style="font-weight:700;margin-bottom:.2rem">⚠ Gagal memuat nilai</div>
            <div style="margin-bottom:.65rem">Server gagal memproses permintaan. Coba lagi beberapa saat.</div>
            <a href="{{ route('nilai.index', ['tab' => $tab, 'periode' => $periode]) }}" class="nl-btn-sec">↻ Coba lagi</a>
        </div>
    @elseif($isLoading)
        <div class="nl-kpi-grid">@for($i = 0; $i < 3; $i++)<div class="nl-card"><div class="nl-skel"></div></div>@endfor</div>
        <div class="nl-card">@for($i = 0; $i < 7; $i++)<div class="nl-skel"></div>@endfor</div>
    @elseif($tab === 'khs')
        <form method="GET" action="{{ route('nilai.index') }}" class="nl-filter-row">
            <input type="hidden" name="tab" value="khs">
            @if($state !== 'default')<input type="hidden" name="state" value="{{ $state }}">@endif
            <div class="nl-filter-left">
                <label for="periodeSelect" style="font-weight:600;color:#334155">Periode</label>
                <select id="periodeSelect" name="periode" class="nl-select" onchange="this.form.submit()">
                    @foreach($periods as $pKey => $pInfo)
                        <option value="{{ $pKey }}" {{ $periode === $pKey ? 'selected' : '' }}>{{ $pInfo['label'] }}</option>
                    @endforeach
                </select>
                @if(($currentKhs['pill_type'] ?? '') === 'ok')
                    <span class="nl-pill-ok">• {{ $currentKhs['pill'] }}</span>
                @elseif(($currentKhs['pill_type'] ?? '') === 'warn')
                    <span class="nl-pill-warn">• {{ $currentKhs['pill'] }}</span>
                @else
                    <span class="nl-pill-muted">• {{ $currentKhs['pill'] ?? 'Belum ada nilai' }}</span>
                @endif
                @if(!($currentKhs['can_download'] ?? false))<span style="font-size:.72rem;color:#64748b">Unduh tersedia setelah semua nilai final</span>@endif
            </div>
            <button type="button" class="nl-btn-dl" {{ ($currentKhs['can_download'] ?? false) ? '' : 'disabled' }} onclick="window.print()">↓ Unduh KHS (PDF)</button>
        </form>

        <div class="nl-kpi-grid">
            <div class="nl-kpi"><div class="nl-kpi-lbl">IP semester {{ $periode }}</div><div class="nl-kpi-val">{{ $currentKhs['ips'] ?? '–' }}</div><div class="nl-kpi-sub">{{ $currentKhs['ips_sub'] ?? '' }}</div></div>
            <div class="nl-kpi"><div class="nl-kpi-lbl">IPK</div><div class="nl-kpi-val">{{ $currentKhs['ipk'] ?? '3,45' }}</div><div class="nl-kpi-sub">{{ $currentKhs['ipk_sub'] ?? '' }}</div></div>
            <div class="nl-kpi"><div class="nl-kpi-lbl">SKS lulus</div><div class="nl-kpi-val">{{ $currentKhs['sks_lulus'] ?? '42' }}</div><div class="nl-kpi-sub">{{ $currentKhs['sks_sub'] ?? '' }}</div></div>
        </div>

        <div class="nl-card">
            <div style="font-weight:700;font-size:.9375rem;color:#0f172a">{{ $currentKhs['title'] ?? 'Kartu Hasil Studi' }}</div>
            <div style="font-size:.75rem;color:#64748b;margin-top:.15rem">{{ $currentKhs['sub'] ?? '' }}</div>
            @if($isEmptyKhs)
                <div class="nl-empty">
                    <div style="font-weight:700;font-size:.875rem;color:#0f172a;margin-bottom:.2rem">Belum ada nilai periode {{ $periode }}</div>
                    <div style="font-size:.75rem;color:#64748b;margin-bottom:.75rem">Perkuliahan periode ini masih berjalan. Nilai tampil setelah dosen pengampu menginputnya.</div>
                    <a href="{{ route('nilai.index', ['tab' => 'khs', 'periode' => '20252']) }}" class="nl-btn-sec">Lihat KHS 20252</a>
                </div>
            @else
                <table class="nl-tbl">
                    <thead><tr><th>Kode</th><th>Mata kuliah</th><th>SKS</th><th>Nilai</th><th>Bobot</th><th>SKS × bobot</th><th>Status</th><th>Keterangan</th></tr></thead>
                    <tbody>
                        @foreach($currentKhs['rows'] as $row)
                            <tr>
                                <td style="font-family:monospace;color:#64748b">{{ $row['code'] }}</td>
                                <td><strong>{{ $row['name'] }}</strong></td>
                                <td>{{ $row['sks'] }}</td><td><strong>{{ $row['grade'] }}</strong></td><td>{{ $row['weight'] }}</td><td>{{ $row['total'] }}</td>
                                <td>@if($row['status'] === 'Final')<span class="nl-pill-ok">Final</span>@else<span class="nl-pill-warn">• Belum final</span>@endif</td>
                                <td>@if(!empty($row['note']))<span class="nl-fail">{{ $row['note'] }}</span>@endif</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="nl-foot"><div style="max-width:760px">{{ $currentKhs['footer_left'] ?? '' }}</div><strong style="color:#0f172a">{{ $currentKhs['footer_right'] ?? '' }}</strong></div>
            @endif
        </div>
    @else
        <div class="nl-filter-row"><span style="font-size:.8125rem;color:#64748b">Semua periode · hanya nilai final</span><button type="button" class="nl-btn-dl" onclick="window.print()">↓ Unduh transkrip (PDF)</button></div>
        <div class="nl-kpi-grid">
            <div class="nl-kpi"><div class="nl-kpi-lbl">IPK</div><div class="nl-kpi-val">3,45</div><div class="nl-kpi-sub">Sampai periode 20252 · 42 SKS</div></div>
            <div class="nl-kpi"><div class="nl-kpi-lbl">SKS lulus</div><div class="nl-kpi-val">42</div><div class="nl-kpi-sub">Dari 42 SKS yang diambil</div></div>
            <div class="nl-kpi"><div class="nl-kpi-lbl">Mata kuliah</div><div class="nl-kpi-val">15</div><div class="nl-kpi-sub">Semua lulus · 2 periode</div></div>
        </div>
        <div class="nl-card">
            <div style="font-weight:700;font-size:.9375rem;color:#0f172a">Riwayat nilai</div>
            <div style="font-size:.75rem;color:#64748b;margin-top:.15rem">2 periode · 15 mata kuliah · 42 SKS · periode 20261 belum masuk karena nilainya belum final</div>
            <table class="nl-tbl">
                <thead><tr><th>Kode</th><th>Mata kuliah</th><th>SKS</th><th>Nilai</th><th>Bobot</th><th>SKS × bobot</th><th>Keterangan</th></tr></thead>
                <tbody>
                    @foreach($transcriptGroups as $group)
                        <tr class="nl-group-row"><td colspan="5">{{ $group['heading'] }}</td><td colspan="2" style="text-align:right">{{ $group['summary'] }}</td></tr>
                        @foreach($group['rows'] as $row)
                            <tr><td style="font-family:monospace;color:#64748b">{{ $row['code'] }}</td><td><strong>{{ $row['name'] }}</strong></td><td>{{ $row['sks'] }}</td><td><strong>{{ $row['grade'] }}</strong></td><td>{{ $row['weight'] }}</td><td>{{ $row['total'] }}</td><td>{{ $row['note'] }}</td></tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
            <div class="nl-foot"><div style="max-width:760px">IPK = jumlah (SKS × bobot) semua periode ÷ jumlah SKS, dari nilai final. Skala nilai huruf, bobot, dan pembulatan 2 desimal adalah USULAN; aturannya belum ditetapkan PO.</div><strong style="color:#0f172a">145,00 ÷ 42 SKS = IPK 3,45</strong></div>
        </div>
    @endif
</div>
@endsection
