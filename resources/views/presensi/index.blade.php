@extends('layouts.app')

@section('title', 'Presensi - SIA ITI')

@section('content')
@php
    $state = $state ?? 'default';
    $subtitle = $subtitle ?? 'Periode 20261 · Senin, 2 Nov 2026, 10.15 WIB';
    $todayClasses = $todayClasses ?? [];
    $recaps = $recaps ?? [];

    $isLoading = $state === 'loading';
    $isError500 = $state === 'error_500';
    $isNoClass = $state === 'tidak_ada_kelas';
@endphp

<style>
.pr-wrap{max-width:1200px;margin:0 auto;padding:1.5rem 1rem;color:#1e293b}
.pr-bc{font-size:.75rem;color:#64748b;margin-bottom:.25rem}
.pr-bc a{color:#64748b;text-decoration:none}
.pr-h1{font-size:1.5rem;font-weight:700;color:#0f172a;margin:0}
.pr-sub{font-size:.75rem;color:#64748b;margin-top:.25rem;margin-bottom:1.25rem}

.pr-card{background:#fff;border:1px solid #e2e8f0;border-radius:.75rem;padding:1.25rem;margin-bottom:1.25rem}
.pr-card-head{display:flex;flex-wrap:wrap;align-items:baseline;justify-content:space-between;gap:.5rem;margin-bottom:.875rem}
.pr-card-title{font-size:1rem;font-weight:700;color:#0f172a;margin:0}
.pr-card-sub{font-size:.75rem;color:#64748b}

.pr-cls-item{padding:.75rem .875rem;border:1px solid #f1f5f9;border-left:3px solid #2563eb;border-radius:.5rem;background:#fafafa;margin-bottom:.625rem;display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:.75rem}
.pr-cls-item:last-child{margin-bottom:0}
.pr-badge-ok{background:#ecfdf5;color:#047857;border:1px solid #a7f3d0;padding:.15rem .55rem;border-radius:9999px;font-size:.75rem;font-weight:600}
.pr-badge-warn{background:#fffbeb;color:#b45309;border:1px solid #fde68a;padding:.15rem .55rem;border-radius:9999px;font-size:.75rem;font-weight:600}
.pr-badge-err{background:#fef2f2;color:#b91c1c;border:1px solid #fecaca;padding:.15rem .55rem;border-radius:9999px;font-size:.75rem;font-weight:600}
.pr-badge-muted{background:#f1f5f9;color:#475569;border:1px solid #cbd5e1;padding:.15rem .55rem;border-radius:9999px;font-size:.75rem;font-weight:600}

.pr-tbl{width:100%;border-collapse:collapse;font-size:.8125rem}
.pr-tbl th{text-align:left;padding:.55rem .45rem;background:#f8fafc;color:#475569;font-size:.75rem;font-weight:600;border-bottom:1px solid #e2e8f0}
.pr-tbl td{padding:.6rem .45rem;border-bottom:1px solid #f1f5f9;vertical-align:middle}
.pr-link{color:#1d4ed8;font-size:.75rem;font-weight:600;text-decoration:none}
.pr-foot-note{font-size:.7rem;color:#64748b;margin-top:.75rem;line-height:1.45}

.pr-err{background:#fef2f2;border:1px solid #fecaca;color:#991b1b;border-radius:.75rem;padding:.875rem 1rem;font-size:.8125rem;margin-bottom:1rem}
.pr-btn{display:inline-block;margin-top:.5rem;padding:.35rem .75rem;border:1px solid #cbd5e1;border-radius:.5rem;background:#fff;color:#334155;text-decoration:none;font-size:.75rem;font-weight:600}
.pr-skel{height:2rem;background:#f1f5f9;border-radius:.45rem;margin-bottom:.5rem}

@media(max-width:900px){.pr-tbl{display:block;overflow-x:auto}}
</style>

<div class="pr-wrap">
    <div class="pr-bc"><a href="{{ route('dashboard') }}">Beranda</a> / <span>Presensi</span></div>
    <h1 class="pr-h1">Presensi</h1>
    <div class="pr-sub">{{ $subtitle }}</div>

    @if($isError500)
        <div class="pr-err">
            <div style="font-weight:700">⚠ Gagal memuat presensi</div>
            <div>Server gagal memproses permintaan. Coba lagi beberapa saat.</div>
            <a href="{{ route('presensi.index', ['state' => 'default']) }}" class="pr-btn">Coba lagi</a>
        </div>
    @elseif($isLoading)
        <div class="pr-card">
            <div class="pr-card-title" style="margin-bottom:.75rem">Kelas hari ini</div>
            @for($i = 0; $i < 2; $i++) <div class="pr-skel"></div> @endfor
        </div>
        <div class="pr-card">
            <div class="pr-card-title" style="margin-bottom:.75rem">Riwayat per mata kuliah</div>
            @for($i = 0; $i < 6; $i++) <div class="pr-skel"></div> @endfor
        </div>
    @else
        <div class="pr-card">
            <div class="pr-card-head">
                <div>
                    <h2 class="pr-card-title">Kelas hari ini</h2>
                    <div class="pr-card-sub">{{ $isNoClass ? 'Jumat, 6 Nov 2026' : 'Senin, 2 Nov 2026 · presensi dicatat oleh dosen' }}</div>
                </div>
            </div>

            @if($isNoClass || count($todayClasses) === 0)
                <div style="padding:1rem;background:#f8fafc;border:1px dashed #cbd5e1;border-radius:.5rem">
                    <div style="font-weight:700;font-size:.875rem;color:#0f172a">Tidak ada kelas hari ini</div>
                    <div style="font-size:.75rem;color:#64748b;margin-top:.2rem">Kelas berikutnya: Sabtu, 7 Nov 2026, 08.00 · Statistika · R. 103.</div>
                </div>
            @else
                @foreach($todayClasses as $cls)
                    <div class="pr-cls-item">
                        <div>
                            <div style="font-weight:700;font-size:.875rem;color:#0f172a">{{ $cls['name'] }}</div>
                            <div style="font-size:.75rem;color:#475569;margin-top:.15rem">{{ $cls['meta'] }}</div>
                            <div style="font-size:.75rem;color:#64748b;margin-top:.15rem">{{ $cls['note'] }}</div>
                        </div>
                        <div>
                            @if($cls['badge_type'] === 'ok')
                                <span class="pr-badge-ok">• {{ $cls['badge'] }}</span>
                            @elseif($cls['badge_type'] === 'warn')
                                <span class="pr-badge-warn">• {{ $cls['badge'] }}</span>
                            @elseif($cls['badge_type'] === 'err')
                                <span class="pr-badge-err">• {{ $cls['badge'] }}</span>
                            @else
                                <span class="pr-badge-muted">• {{ $cls['badge'] }}</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            @endif
        </div>

        <div class="pr-card">
            <div class="pr-card-head">
                <div>
                    <h2 class="pr-card-title">Riwayat per mata kuliah</h2>
                    <div class="pr-card-sub">Periode 20261 · pertemuan yang sudah berlangsung per kelas</div>
                </div>
                <a href="{{ route('presensi.show', 'IF201') }}" class="pr-link">Lihat riwayat Pemrograman Web →</a>
            </div>

            <table class="pr-tbl">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Mata kuliah</th>
                        <th>Hadir</th>
                        <th>Sakit</th>
                        <th>Izin</th>
                        <th>Alpa</th>
                        <th>Pertemuan</th>
                        <th>Kehadiran</th>
                        <th>Syarat ujian</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recaps as $r)
                        <tr>
                            <td style="font-family:monospace;color:#64748b">{{ $r['code'] }}</td>
                            <td>
                                <a href="{{ route('presensi.show', $r['code']) }}" style="font-weight:600;color:#0f172a;text-decoration:none">
                                    {{ $r['name'] }}
                                </a>
                            </td>
                            <td>{{ $r['hadir'] }}</td>
                            <td>{{ $r['sakit'] }}</td>
                            <td>{{ $r['izin'] }}</td>
                            <td>{{ $r['alpa'] }}</td>
                            <td>{{ $r['pertemuan'] }}</td>
                            <td><strong>{{ $r['kehadiran'] }}</strong></td>
                            <td>
                                @if($r['syarat'] === 'Memenuhi')
                                    <span class="pr-badge-ok">• Memenuhi</span>
                                @else
                                    <span class="pr-badge-err">• Belum memenuhi</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="pr-foot-note">
                Kehadiran = hadir ÷ pertemuan yang sudah berlangsung. Status syarat ujian dan perlakuan sakit/izin belum ditetapkan PO. Bila status kehadiran tidak sesuai, temui dosen pengampu secara langsung. Perbaikan dicatat oleh dosen.
            </div>
        </div>
    @endif
</div>
@endsection
