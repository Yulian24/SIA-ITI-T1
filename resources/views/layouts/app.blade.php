
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - SIA ITI</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="app-body">

<header class="sia-topbar">
    <a href="{{ route('dashboard') }}" class="sia-topbar-brand">
        <span class="sia-topbar-brand-title">SIA ITI</span>
        <span class="sia-topbar-brand-subtitle">Institut Teknologi Indonesia</span>
    </a>

    @php
        $periodOptions = [
            '20261' => ['label' => '20261', 'status' => 'Aktif'],
            '20252' => ['label' => '20252', 'status' => 'Selesai'],
            '20251' => ['label' => '20251', 'status' => 'Selesai'],
        ];
        $requestedPeriod = request()->query('periode', session('sia_periode', '20261'));
        $activePeriod = is_string($requestedPeriod) && array_key_exists($requestedPeriod, $periodOptions)
            ? $requestedPeriod
            : '20261';
        $currentPeriod = $periodOptions[$activePeriod];
    @endphp

    <div class="sia-topbar-tools">
    <div class="sia-period-picker">
        <span class="sia-period-icon" aria-hidden="true">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="4" width="18" height="17" rx="2" />
                <path d="M16 2v4M8 2v4M3 10h18" />
            </svg>
        </span>
        <span class="sia-period-label">Periode</span>
        <span class="sia-period-code">{{ $currentPeriod['label'] }}</span>
        <span class="sia-period-status">• {{ $currentPeriod['status'] }}</span>
        <span class="sia-period-caret" aria-hidden="true">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="m6 9 6 6 6-6" />
            </svg>
        </span>
        <select class="sia-period-select" aria-label="Pilih periode akademik" onchange="window.location.href=this.value">
            @foreach($periodOptions as $code => $option)
                <option value="{{ request()->fullUrlWithQuery(['periode' => $code]) }}" {{ $activePeriod === $code ? 'selected' : '' }}>
                    Periode {{ $option['label'] }} ({{ $option['status'] }})
                </option>
            @endforeach
        </select>
    </div>

    <div class="sia-topbar-right sia-header-actions">
        <button type="button" class="sia-icon-btn sia-header-icon" aria-label="Bantuan">
            <svg viewBox="0 0 24 24" aria-hidden="true">
                <circle cx="12" cy="12" r="9" />
                <path d="M9.8 9a2.3 2.3 0 1 1 3.8 1.7c-1 .9-1.6 1.2-1.6 2.8M12 17h.01" />
            </svg>
        </button>

        <button type="button" class="sia-icon-btn sia-header-icon" aria-label="Notifikasi">
            <svg viewBox="0 0 24 24" aria-hidden="true">
                <path d="M18 9a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9M10 21h4"/>
            </svg>
        </button>

        @php
            $activeRole = session('sia_role', 'mahasiswa');
            $availableRoles = session('sia_roles', ['dosen', 'dosen_pa', 'kaprodi']);

            $dosenMenus = [
                ['key' => 'home', 'label' => 'Beranda', 'url' => route('dashboard'), 'active' => request()->routeIs('dashboard')],
                ['key' => 'schedule', 'label' => 'Jadwal Mengajar', 'url' => route('jadwal.index'), 'active' => request()->routeIs('jadwal.*')],
                ['key' => 'classes', 'label' => 'Kelas Diampu', 'url' => '#', 'active' => false],
                ['key' => 'attendance', 'label' => 'Presensi', 'url' => route('presensi.index'), 'active' => request()->routeIs('presensi.*')],
                ['key' => 'grades', 'label' => 'Nilai', 'url' => route('nilai.index'), 'active' => request()->routeIs('nilai.*')],
                ['key' => 'materials', 'label' => 'Materi dan Tugas', 'url' => '#', 'active' => false],
                ['key' => 'profile', 'label' => 'Profil', 'url' => route('profile.show'), 'active' => request()->routeIs('profile.*')],
            ];
            $baakMenus = [
                ['key' => 'home', 'label' => 'Dashboard', 'url' => route('dashboard'), 'active' => request()->routeIs('dashboard')],
                ['key' => 'students', 'label' => 'Mahasiswa', 'url' => '#', 'active' => false],
                ['key' => 'lecturers', 'label' => 'Dosen', 'url' => '#', 'active' => false],
                ['key' => 'prodi', 'label' => 'Program Studi', 'url' => '#', 'active' => false],
                ['key' => 'period', 'label' => 'Periode Akademik', 'url' => '#', 'active' => false],
                ['key' => 'curriculum', 'label' => 'Mata Kuliah dan Kurikulum', 'url' => '#', 'active' => false],
                ['key' => 'offerings', 'label' => 'Penawaran dan Kelas', 'url' => '#', 'active' => false],
                ['key' => 'schedule', 'label' => 'Jadwal', 'url' => route('jadwal.index'), 'active' => request()->routeIs('jadwal.*')],
                ['key' => 'monitoring', 'label' => 'Pemantauan', 'url' => '#', 'active' => false],
                ['key' => 'attendance', 'label' => 'Laporan Kehadiran', 'url' => route('presensi.index'), 'active' => request()->routeIs('presensi.*')],
            ];
            $mahasiswaMenus = [
                ['key' => 'home', 'label' => 'Beranda', 'url' => route('dashboard'), 'active' => request()->routeIs('dashboard')],
                ['key' => 'krs', 'label' => 'KRS', 'url' => route('krs.index'), 'active' => request()->routeIs('krs.*')],
                ['key' => 'schedule', 'label' => 'Jadwal', 'url' => route('jadwal.index'), 'active' => request()->routeIs('jadwal.*')],
                ['key' => 'attendance', 'label' => 'Presensi', 'url' => route('presensi.index'), 'active' => request()->routeIs('presensi.*')],
                ['key' => 'grades', 'label' => 'Nilai', 'url' => route('nilai.index'), 'active' => request()->routeIs('nilai.*')],
                ['key' => 'profile', 'label' => 'Profil', 'url' => '#', 'active' => false],
            ];
            $launcherMenus = $activeRole === 'baak' ? $baakMenus : (in_array($activeRole, ['dosen', 'dosen_pa', 'kaprodi'], true) ? $dosenMenus : $mahasiswaMenus);
            $launcherHeading = $activeRole === 'baak' ? 'Modul BAAK / Admin akademik' : (in_array($activeRole, ['dosen', 'dosen_pa', 'kaprodi'], true) ? 'Modul Dosen' : 'Modul Mahasiswa');
        @endphp

        <style>
            .sia-launcher-wrap{position:relative;display:inline-block}
            .sia-launcher-btn{width:2rem;height:2rem;border-radius:.5rem;border:none;background:transparent;color:#fff;cursor:pointer;display:inline-flex;align-items:center;justify-content:center}
            .sia-launcher-btn:hover{background:rgba(255,255,255,.24)}
            .sia-launcher-pop{display:none;position:absolute;right:0;top:2.65rem;width:292px;background:#fff;color:#1e293b;border:1px solid #e2e8f0;border-radius:.75rem;box-shadow:0 14px 30px rgba(15,23,42,.18);padding:.9rem;z-index:60;text-align:left}
            .sia-launcher-pop.open{display:block}
            .sia-launcher-title{font-size:.875rem;font-weight:700;color:#0f172a;margin-bottom:.7rem}
            .sia-launcher-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:.45rem}
            .sia-launcher-item{display:flex;flex-direction:column;align-items:center;justify-content:flex-start;gap:.35rem;text-align:center;padding:.55rem .3rem;border-radius:.55rem;text-decoration:none;color:#334155;font-size:.7rem;font-weight:500;line-height:1.2}
            .sia-launcher-item:hover{background:#f8fafc;color:#0f172a}
            .sia-launcher-item.active{background:#eff6ff;color:#1d4ed8;font-weight:600}
            .sia-launcher-divider{border-top:1px solid #e2e8f0;margin:.7rem 0}
            .sia-role-row{display:flex;align-items:center;justify-content:space-between;gap:.4rem;font-size:.75rem;color:#334155;padding:.35rem .25rem;cursor:pointer;width:100%;background:none;border:none;text-align:left}
            .sia-role-row:hover{color:#0f172a}
            .sia-role-menu{display:none;position:absolute;left:-11.5rem;bottom:.35rem;width:172px;background:#fff;border:1px solid #e2e8f0;border-radius:.625rem;box-shadow:0 10px 24px rgba(15,23,42,.14);padding:.4rem;z-index:70}
            .sia-role-menu.open{display:block}
            .sia-role-opt{display:flex;align-items:center;justify-content:space-between;width:100%;padding:.4rem .55rem;border:0;border-radius:.4rem;font-size:.75rem;color:#1e293b;text-decoration:none;background:#fff;cursor:pointer}
            .sia-role-opt:hover{background:#f1f5f9}
        </style>

        <div class="sia-launcher-wrap">
            <button type="button" class="sia-icon-btn sia-launcher-btn" aria-label="Peluncur modul" data-module-toggle aria-expanded="false">
                <svg width="14" height="16" viewBox="0 0 14 16" fill="currentColor" aria-hidden="true">
                    <circle cx="3.5" cy="3" r="1.4"/><circle cx="10.5" cy="3" r="1.4"/>
                    <circle cx="3.5" cy="8" r="1.4"/><circle cx="10.5" cy="8" r="1.4"/>
                    <circle cx="3.5" cy="13" r="1.4"/><circle cx="10.5" cy="13" r="1.4"/>
                </svg>
            </button>

            <div id="siaLauncherPop" class="sia-launcher-pop" data-module-dropdown>
                <div class="sia-launcher-title">{{ $launcherHeading }}</div>
                <div class="sia-launcher-grid">
                    @foreach($launcherMenus as $menu)
                        <a href="{{ $menu['url'] }}" class="sia-launcher-item {{ $menu['active'] ? 'active' : '' }}">
                            @switch($menu['key'])
                                @case('home')
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 10.5 12 3l9 7.5"/><path d="M5 9.5V20h14V9.5"/></svg>
                                    @break
                                @case('schedule')
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="17" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                                    @break
                                @case('classes')
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="15" rx="2"/><path d="M8 21h8M12 19v2"/></svg>
                                    @break
                                @case('attendance')
                                @case('krs')
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="3" width="16" height="18" rx="2"/><path d="m9 12 2 2 4-4"/></svg>
                                    @break
                                @case('grades')
                                @case('monitoring')
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M18 20V10M12 20V4M6 20v-6"/></svg>
                                    @break
                                @case('materials')
                                @case('curriculum')
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 7a2 2 0 0 1 2-2h4l2 2h8a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
                                    @break
                                @default
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="4"/><path d="M5 20a7 7 0 0 1 14 0"/></svg>
                            @endswitch
                            <span>{{ $menu['label'] }}</span>
                        </a>
                    @endforeach
                </div>

                @if(in_array($activeRole, ['dosen', 'dosen_pa', 'kaprodi'], true) && count($availableRoles) > 1)
                    <div class="sia-launcher-divider"></div>
                    <button type="button" class="sia-role-row" onclick="document.getElementById('siaRoleMenu').classList.toggle('open')">
                        <span style="display:inline-flex;align-items:center;gap:.4rem">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="9"/><path d="M3.6 9h16.8M3.6 15h16.8"/><path d="M12 3a15 15 0 0 1 0 18M12 3a15 15 0 0 0 0 18"/></svg>
                            <span>Masuk sebagai <strong style="color:#1d4ed8">{{ $activeRole === 'dosen_pa' ? 'Dosen PA' : ucfirst($activeRole) }}</strong></span>
                        </span>
                        <span>⌄</span>
                    </button>
                    <div id="siaRoleMenu" class="sia-role-menu">
                        <div style="font-size:.68rem;color:#64748b;padding:.25rem .55rem">Masuk sebagai</div>
                        @foreach($availableRoles as $role)
                            @php $roleLabels = ['dosen' => 'Dosen', 'dosen_pa' => 'Dosen PA', 'kaprodi' => 'Kaprodi']; @endphp
                            <form method="POST" action="{{ route('role.switch') }}">
                                @csrf
                                <input type="hidden" name="role" value="{{ $roleLabels[$role] }}">
                                <button type="submit" class="sia-role-opt">
                                    <span>{{ $roleLabels[$role] }}</span>
                                    @if($activeRole === $role)<span style="color:#1d4ed8;font-weight:700">✓</span>@endif
                                </button>
                            </form>
                        @endforeach
                    </div>
                @endif

                <div class="sia-launcher-divider"></div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="sia-role-row" style="font-weight:600;color:#334155">
                        <span style="display:inline-flex;align-items:center;gap:.4rem">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><path d="m16 17 5-5-5-5"/><path d="M21 12H9"/></svg>
                            <span>Keluar</span>
                        </span>
                    </button>
                </form>
            </div>
        </div>

        <a href="{{ route('profile.show') }}"
           class="sia-icon-btn sia-header-icon sia-profile-icon"
           aria-label="Profil">
            <svg viewBox="0 0 24 24" aria-hidden="true">
                <circle cx="12" cy="8" r="4"/>
                <path d="M4 21a8 8 0 0 1 16 0"/>
            </svg>
        </a>

    </div>
    </div>
</header>

<main class="page-wrap">

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
            <button type="button" data-dismiss aria-label="Tutup">×</button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error">
            {{ session('error') }}
            <button type="button" data-dismiss aria-label="Tutup">×</button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-error">
            <b>Periksa kembali data:</b>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @yield('content')

</main>

<footer class="sia-footer">
    <div>
        <strong>SIA ITI</strong>
        <small>Institut Teknologi Indonesia</small>
    </div>
</footer>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const toggle = document.querySelector('[data-module-toggle]');
    const launcher = document.getElementById('siaLauncherPop');
    const roleMenu = document.getElementById('siaRoleMenu');
    const wrapper = toggle?.closest('.sia-launcher-wrap');

    if (!wrapper || !toggle) return;

    toggle.addEventListener('click', function () {
        const isOpen = wrapper.classList.toggle('is-open');
        launcher?.classList.toggle('open', isOpen);
        toggle.classList.toggle('is-active', isOpen);
        toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
    });

    document.addEventListener('click', function (event) {
        if (!wrapper.contains(event.target)) {
            wrapper.classList.remove('is-open');
            launcher?.classList.remove('open');
            roleMenu?.classList.remove('open');
            toggle.classList.remove('is-active');
            toggle.setAttribute('aria-expanded', 'false');
        }
    });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            wrapper.classList.remove('is-open');
            launcher?.classList.remove('open');
            roleMenu?.classList.remove('open');
            toggle.classList.remove('is-active');
            toggle.setAttribute('aria-expanded', 'false');
        }
    });
});
</script>

</body>
</html>
