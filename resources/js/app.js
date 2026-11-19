
// import './bootstrap';

document.addEventListener('DOMContentLoaded', () => {

    // Tampilkan dan sembunyikan password
    document.querySelectorAll('[data-password-toggle]')
        .forEach(button => {
            button.addEventListener('click', () => {
                const input = document.getElementById(
                    button.dataset.passwordToggle
                );

                if (!input) return;

                const showing = input.type === 'text';
                input.type = showing ? 'password' : 'text';

                button.textContent = showing
                    ? 'Lihat'
                    : 'Sembunyikan';
            });
        });

    // Menu navigasi mobile
    const menuButton = document.querySelector('[data-menu-toggle]');
    const nav = document.querySelector('[data-top-nav]');

    menuButton?.addEventListener('click', () => {
        nav?.classList.toggle('open');
    });

    // Tutup notifikasi
    document.querySelectorAll('[data-dismiss]')
        .forEach(button => {
            button.addEventListener('click', () => {
                button.closest('.alert')?.remove();
            });
        });

    // Pencarian pengguna
    const search = document.querySelector('[data-table-search]');

    search?.addEventListener('input', () => {
        const query = search.value.toLowerCase();

        document.querySelectorAll('#users-table tbody tr')
            .forEach(row => {
                row.hidden = !row.innerText
                    .toLowerCase()
                    .includes(query);
            });
    });

    // Konfirmasi sebelum menghapus
    document.querySelectorAll('form[data-confirm]')
        .forEach(form => {
            form.addEventListener('submit', event => {
                if (!confirm(form.dataset.confirm)) {
                    event.preventDefault();
                }
            });
        });

    // Animasi proses login
    const loginForm = document.querySelector('[data-login-form]');

    if (loginForm) {
        const loginButton = loginForm.querySelector('[data-login-submit]');

        loginForm.addEventListener('submit', () => {
            if (loginForm.dataset.loginLocked === 'true') {
                return;
            }

            if (loginButton) {
                loginButton.disabled = true;
                loginButton.textContent = 'Memproses...';
                loginButton.classList.add('is-processing');
            }

            loginForm.querySelectorAll('input').forEach(input => {
                input.readOnly = true;
            });
        });
    }

    // Skeleton loading halaman profil
    const profilePage = document.querySelector('[data-profile-page]');

    if (profilePage) {
        const skeleton = profilePage.querySelector('[data-profile-skeleton]');
        const content = profilePage.querySelector('[data-profile-loaded]');

        if (skeleton && content) {
            skeleton.style.display = 'grid';
            content.style.display = 'none';

            window.setTimeout(() => {
                skeleton.style.display = 'grid';
                content.style.display = 'grid';

                skeleton.classList.add('skeleton-hidden');
                content.classList.add('profile-visible');

                window.setTimeout(() => {
                    skeleton.remove();
                }, 250);
            }, 650);
        }
    }

    // Dropdown modul
    const toggle = document.querySelector('[data-module-toggle]');
    const dropdown = document.querySelector('[data-module-dropdown]');

    if (toggle && dropdown) {
        toggle.addEventListener('click', event => {
            event.stopPropagation();

            dropdown.classList.toggle('show');

            toggle.setAttribute(
                'aria-expanded',
                dropdown.classList.contains('show')
            );
        });

        dropdown.addEventListener('click', event => {
            event.stopPropagation();
        });

        document.addEventListener('click', () => {
            dropdown.classList.remove('show');
            toggle.setAttribute('aria-expanded', 'false');
        });

        document.addEventListener('keydown', event => {
            if (event.key === 'Escape') {
                dropdown.classList.remove('show');
                toggle.setAttribute('aria-expanded', 'false');
            }
        });
    }
});
