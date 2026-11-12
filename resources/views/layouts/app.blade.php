
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

    <a href="{{ route('dashboard') }}" class="sia-top-brand">
        <span class="sia-brand-mark">ITI</span>
        <span class="sia-brand-text">
            <strong>SIA ITI</strong>
            <small>Institut Teknologi Indonesia</small>
        </span>
    </a>

    <div class="sia-header-actions">

        <div class="sia-period">
            <span class="sia-period-label">PERIODE</span>
            <strong>20261</strong>
            <span class="sia-status">
                <span class="sia-status-dot"></span>
                Aktif
            </span>
        </div>

        <button type="button" class="sia-header-icon" aria-label="Bantuan">?</button>

        <button type="button" class="sia-header-icon" aria-label="Notifikasi">
            <svg viewBox="0 0 24 24" aria-hidden="true">
                <path d="M18 9a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9M10 21h4"/>
            </svg>
        </button>

        <div class="sia-module-dropdown-wrapper">

            <button type="button"
                    class="sia-header-icon sia-grid-trigger"
                    data-module-toggle
                    aria-expanded="false"
                    aria-label="Buka daftar modul">
                <svg viewBox="0 0 24 24" aria-hidden="true">
                    <rect x="3" y="3" width="7" height="7" rx="1"/>
                    <rect x="14" y="3" width="7" height="7" rx="1"/>
                    <rect x="3" y="14" width="7" height="7" rx="1"/>
                    <rect x="14" y="14" width="7" height="7" rx="1"/>
                </svg>
            </button>

            <div class="sia-module-dropdown" data-module-dropdown>

                <div class="sia-dropdown-heading">
                    <div>
                        <h3>Modul {{ $moduleRole ?? 'Mahasiswa' }}</h3>
                        <p>Pilih layanan akademik</p>
                    </div>
                </div>

                <div class="sia-dropdown-grid">
                    @foreach(($modules ?? []) as $module)

                        @if($module['route'] === '#')
                            <span class="sia-dropdown-item" aria-disabled="true">
                        @else
                            <a href="{{ route($module['route']) }}"
                               class="sia-dropdown-item {{ $module['label'] === 'Beranda' ? 'active' : '' }}">
                        @endif

                            <span class="sia-module-symbol">
                                {{ $module['icon'] }}
                            </span>

                            <small>{{ $module['label'] }}</small>

                        @if($module['route'] === '#')
                            </span>
                        @else
                            </a>
                        @endif

                    @endforeach
                </div>

                <div class="sia-role-switcher">

                    <p>Masuk sebagai</p>

                    <div class="sia-role-list">
                        @php
                            $roles = [
                                'Mahasiswa',
                                'Dosen',
                                'Dosen PA',
                                'Kaprodi',
                                'Admin Akademik',
                                'Admin Sistem',
                            ];

                            $currentRole = session('auth_user.role', 'Mahasiswa');
                        @endphp

                        @foreach($roles as $role)
                            <form method="POST" action="{{ route('role.switch') }}">
                                @csrf
                                <input type="hidden" name="role" value="{{ $role }}">

                                <button type="submit"
                                    class="sia-role-option {{ $currentRole === $role ? 'selected' : '' }}">

                                    <span>{{ $role }}</span>

                                    @if($currentRole === $role)
                                        <span class="sia-role-check">✓</span>
                                    @endif

                                </button>
                            </form>
                        @endforeach
                    </div>

                </div>

                <div class="sia-dropdown-footer">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit">
                            <span>↪</span>
                            Keluar
                        </button>
                    </form>
                </div>

            </div>
        </div>

        <a href="{{ route('profile.show') }}"
           class="sia-header-icon sia-profile-icon"
           aria-label="Profil">
            <svg viewBox="0 0 24 24" aria-hidden="true">
                <circle cx="12" cy="8" r="4"/>
                <path d="M4 21a8 8 0 0 1 16 0"/>
            </svg>
        </a>

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
    const wrapper = document.querySelector('.sia-module-dropdown-wrapper');
    const toggle = document.querySelector('[data-module-toggle]');

    if (!wrapper || !toggle) return;

    toggle.addEventListener('click', function () {
        const isOpen = wrapper.classList.toggle('is-open');
        toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
    });

    document.addEventListener('click', function (event) {
        if (!wrapper.contains(event.target)) {
            wrapper.classList.remove('is-open');
            toggle.setAttribute('aria-expanded', 'false');
        }
    });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            wrapper.classList.remove('is-open');
            toggle.setAttribute('aria-expanded', 'false');
        }
    });
});
</script>

</body>
</html>
