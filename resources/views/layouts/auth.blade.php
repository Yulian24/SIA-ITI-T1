
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - SIA ITI</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="auth-body">

    <main class="auth-shell">

        <aside class="auth-brand">
            <div class="auth-brand-top">
                <div class="brand-name">SIA ITI</div>
                <p class="brand-subtitle">Institut Teknologi Indonesia</p>
            </div>

            <div class="brand-copy">
                <h1>Sistem Informasi Akademik</h1>
                <p>
                    Data diri, KRS, jadwal, presensi, dan hasil studi
                    dalam satu akun.
                </p>
            </div>
        </aside>

        <section class="auth-content">
            <div class="auth-card">
                @yield('content')
            </div>
        </section>

    </main>

</body>
</html>
