@extends('layouts.app')

@section('title', 'Riwayat Kehadiran - SIA ITI')

@section('content')
@php
    $recap = $recap ?? [];
    $meetings = $meetings ?? [];
@endphp

<style>
.pr-wrap{max-width:1200px;margin:0 auto;padding:1.5rem 1rem;color:#1e293b}
.pr-bc{font-size:.75rem;color:#64748b;margin-bottom:.25rem}.pr-bc a{color:#64748b;text-decoration:none}
.pr-h1{font-size:1.5rem;font-weight:700;color:#0f172a;margin:0}.pr-sub{font-size:.75rem;color:#64748b;margin-top:.25rem}
.pr-back{display:inline-block;margin:.75rem 0 1rem;padding:.4rem .8rem;border:1px solid #cbd5e1;border-radius:.5rem;background:#fff;color:#334155;text-decoration:none;font-size:.8125rem;font-weight:500}
.pr-grid{display:grid;grid-template-columns:1fr;gap:1.25rem;align-items:start}@media(min-width:1024px){.pr-grid{grid-template-columns:2fr 1fr}}
.pr-card{background:#fff;border:1px solid #e2e8f0;border-radius:.75rem;padding:1.25rem}
.pr-tbl{width:100%;border-collapse:collapse;font-size:.8125rem;margin-top:.5rem}.pr-tbl th{text-align:left;padding:.5rem .4rem;background:#f8fafc;color:#475569;font-size:.75rem;border-bottom:1px solid #e2e8f0}.pr-tbl td{padding:.55rem .4rem;border-bottom:1px solid #f1f5f9}
.pr-row-sick{background:#fffbeb}
.pr-badge-ok{background:#ecfdf5;color:#047857;border:1px solid #a7f3d0;padding:.15rem .55rem;border-radius:9999px;font-size:.75rem;font-weight:600}
.pr-badge-warn{background:#fffbeb;color:#b45309;border:1px solid #fde68a;padding:.15rem .55rem;border-radius:9999px;font-size:.75rem;font-weight:600}
.pr-sum-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:.5rem;text-align:center;margin:.75rem 0}.pr-sum-box{border:1px solid #f1f5f9;background:#f8fafc;border-radius:.5rem;padding:.5rem .25rem}.pr-sum-box strong{display:block;font-size:1rem;color:#0f172a}.pr-sum-box span{font-size:.7rem;color:#64748b}
</style>

<div class="pr-wrap">
    <div class="pr-bc"><a href="{{ route('dashboard') }}">Beranda</a> / <a href="{{ route('presensi.index') }}">Presensi</a> / <span>{{ $recap['code'] ?? 'IF201' }}</span></div>
    <h1 class="pr-h1">Riwayat kehadiran · {{ $recap['name'] ?? 'Pemrograman Web' }}</h1>
    <div class="pr-sub">Periode 20261 · Senin, 07.30–10.00 · R. 204 · Dr. Rina Kartika, M.Kom.</div>
    <a href="{{ route('presensi.index') }}" class="pr-back">← Kembali ke Presensi</a>

    <div class="pr-grid">
        <div class="pr-card">
            <h2 style="font-size:1rem;font-weight:700;margin:0;color:#0f172a">Daftar pertemuan</h2>
            <div style="font-size:.75rem;color:#64748b;margin-top:.2rem">{{ count($meetings) }} pertemuan yang sudah berlangsung</div>
            <table class="pr-tbl">
                <thead><tr><th>Pertemuan</th><th>Tanggal</th><th>Status</th></tr></thead>
                <tbody>
                    @foreach($meetings as $m)
                        <tr class="{{ $m['status'] === 'Sakit' ? 'pr-row-sick' : '' }}">
                            <td>{{ $m['no'] }}</td><td>{{ $m['date'] }}</td>
                            <td>@if($m['status'] === 'Sakit')<span class="pr-badge-warn">• Sakit</span>@else<span class="pr-badge-ok">• Hadir</span>@endif</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="pr-card">
            <h2 style="font-size:.9375rem;font-weight:700;margin:0;color:#0f172a">Presensi</h2>
            <div style="font-size:.75rem;color:#64748b;margin-top:.2rem">{{ $recap['hadir'] ?? 9 }} dari {{ $recap['pertemuan'] ?? 10 }} pertemuan · {{ $recap['kehadiran'] ?? '90,91%' }}</div>
            <div style="margin-top:.65rem"><span class="pr-badge-ok">• Memenuhi syarat ujian</span></div>
            <div class="pr-sum-grid">
                <div class="pr-sum-box"><strong>{{ $recap['hadir'] ?? 9 }}</strong><span>Hadir</span></div>
                <div class="pr-sum-box"><strong>{{ $recap['sakit'] ?? 1 }}</strong><span>Sakit</span></div>
                <div class="pr-sum-box"><strong>{{ $recap['izin'] ?? 0 }}</strong><span>Izin</span></div>
                <div class="pr-sum-box"><strong>{{ $recap['alpa'] ?? 0 }}</strong><span>Alpa</span></div>
            </div>
            <div style="font-size:.75rem;color:#64748b;line-height:1.45;border-top:1px solid #f1f5f9;padding-top:.75rem">
                <strong style="color:#334155">ⓘ Presensi dicatat oleh dosen</strong><br>
                Bila status kehadiran tidak sesuai, temui dosen pengampu secara langsung. Perbaikan dicatat oleh dosen.
            </div>
        </div>
    </div>
</div>
@endsection
