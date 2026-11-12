
@extends('layouts.app')

@section('title', 'Manajemen Data Mahasiswa')

@section('content')

@php
    $students = [
        [
            'nim' => '20261001',
            'name' => 'Aditya Pratama',
            'email' => 'aditya@student.iti.ac.id',
            'prodi' => 'Informatika',
            'angkatan' => '2026',
            'status' => 'Aktif',
            'is_new' => false,
        ],
        [
            'nim' => '20261002',
            'name' => 'Nadia Putri',
            'email' => 'nadia@student.iti.ac.id',
            'prodi' => 'Informatika',
            'angkatan' => '2026',
            'status' => 'Aktif',
            'is_new' => false,
        ],
        [
            'nim' => '202521001',
            'name' => 'Rizky Ramadhan',
            'email' => 'rizky@student.iti.ac.id',
            'prodi' => 'Teknik Sipil',
            'angkatan' => '2025',
            'status' => 'Aktif',
            'is_new' => false,
        ],
        [
            'nim' => '202431001',
            'name' => 'Salsabila Zahra',
            'email' => 'salsa@student.iti.ac.id',
            'prodi' => 'Teknik Mesin',
            'angkatan' => '2024',
            'status' => 'Nonaktif',
            'is_new' => false,
        ],
        [
            'nim' => '20261003',
            'name' => 'Aulia Rahma',
            'email' => 'aulia@student.iti.ac.id',
            'prodi' => 'Informatika',
            'angkatan' => '2026',
            'status' => 'Aktif',
            'is_new' => true,
        ],
    ];
@endphp

<div class="baak-page mhs-page">

    {{-- Notifikasi berhasil --}}
    <div class="mhs-success-banner" id="successBanner" hidden>
        <span id="successMessage"></span>
        <button type="button" id="closeSuccessBanner">Tutup</button>
    </div>

    {{-- Breadcrumb --}}
    <div class="baak-breadcrumb">
        Dashboard / Master data / Mahasiswa
    </div>

    {{-- Heading --}}
    <div class="baak-heading">
        <h1>Manajemen Data Mahasiswa</h1>
        <p>Kelola mahasiswa · Periode 20261 · BAAK / Admin akademik</p>
    </div>

    {{-- Navigasi --}}
    <nav class="baak-tabs">
        <a href="{{ route('dashboard') }}">Dashboard</a>
        <a href="{{ route('mahasiswa.index') }}" class="active">Mahasiswa</a>
        <a href="#">Dosen</a>
        <a href="#">Program Studi</a>
        <a href="#">Periode Akademik</a>
        <a href="#">Akun &amp; Role</a>
    </nav>

    {{-- Daftar mahasiswa --}}
    <section class="baak-table-card">

        <div class="baak-table-heading">
            <div>
                <h2>Data mahasiswa</h2>
                <p>Kelola data induk dan status mahasiswa.</p>
            </div>

            <button
                type="button"
                class="baak-primary-button"
                id="addStudentButton"
            >
                + Tambah Mahasiswa
            </button>
        </div>

        {{-- Filter --}}
        <div class="baak-filter-row mhs-filter-row">

            <input
                type="text"
                id="studentSearch"
                placeholder="Cari NIM atau nama mahasiswa..."
            >

            <select id="prodiFilter">
                <option value="">Semua program studi</option>
                <option value="Informatika">Informatika</option>
                <option value="Teknik Informatika">Teknik Informatika</option>
                <option value="Teknik Sipil">Teknik Sipil</option>
                <option value="Teknik Mesin">Teknik Mesin</option>
            </select>

            <select id="angkatanFilter">
                <option value="">Semua angkatan</option>
                <option value="2026">2026</option>
                <option value="2025">2025</option>
                <option value="2024">2024</option>
                <option value="2023">2023</option>
            </select>

            <select id="statusFilter">
                <option value="">Semua status</option>
                <option value="Aktif">Aktif</option>
                <option value="Nonaktif">Nonaktif</option>
            </select>

            <button
                type="button"
                class="mhs-reset-button"
                id="resetSearch"
                hidden
            >
                Reset pencarian
            </button>

        </div>

        {{-- Tabel --}}
        <div class="baak-table-wrapper">
            <table class="baak-table" id="studentTable">

                <thead>
                    <tr>
                        <th>NIM</th>
                        <th>Nama mahasiswa</th>
                        <th>Program studi</th>
                        <th>Angkatan</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody id="studentTableBody"></tbody>

            </table>
        </div>

        {{-- Footer tabel --}}
        <div class="baak-table-footer">
            <span id="studentCount">Menampilkan 5 dari 5 data contoh</span>
            <span id="studentPagination">Halaman 1 dari 1</span>
        </div>

    </section>

    {{-- Footer halaman --}}
    <div class="mhs-page-footer">
        <span>SIA ITI - Tim 1 - User Management &amp; Master Data</span>
        <span>Prototype UI/UX - Data contoh</span>
    </div>

</div>

{{-- Modal Mahasiswa --}}
<div
    class="mhs-modal-overlay"
    id="studentModal"
    aria-hidden="true"
>
    <div
        class="mhs-modal"
        role="dialog"
        aria-modal="true"
        aria-labelledby="modalTitle"
    >

        <div class="mhs-modal-header">
            <div>
                <h2 id="modalTitle">Tambah Mahasiswa</h2>
                <p id="modalDescription">
                    Lengkapi data mahasiswa. Kolom bertanda * wajib diisi.
                </p>
            </div>

            <button
                type="button"
                class="mhs-close-button"
                id="closeStudentModal"
            >
                Tutup
            </button>
        </div>

        <div id="modalBody"></div>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    let students = @json($students);
    let currentNim = null;

    const modal = document.getElementById('studentModal');
    const modalBody = document.getElementById('modalBody');
    const modalTitle = document.getElementById('modalTitle');
    const modalDescription = document.getElementById('modalDescription');

    const searchInput = document.getElementById('studentSearch');
    const prodiFilter = document.getElementById('prodiFilter');
    const angkatanFilter = document.getElementById('angkatanFilter');
    const statusFilter = document.getElementById('statusFilter');

    const tableBody = document.getElementById('studentTableBody');

    const successBanner = document.getElementById('successBanner');

    const fields = [
        {
            key: 'nim',
            label: 'NIM',
            placeholder: 'Masukkan nim'
        },
        {
            key: 'name',
            label: 'Nama mahasiswa',
            placeholder: 'Masukkan nama mahasiswa'
        },
        {
            key: 'prodi',
            label: 'Program studi',
            placeholder: 'Masukkan program studi'
        },
        {
            key: 'angkatan',
            label: 'Angkatan',
            placeholder: 'Masukkan angkatan'
        },
        {
            key: 'email',
            label: 'Email',
            placeholder: 'Masukkan email'
        },
        {
            key: 'status',
            label: 'Status',
            placeholder: 'Pilih status'
        }
    ];

    // Escape HTML untuk keamanan tampilan
    function escapeHtml(value) {
        return String(value ?? '').replace(/[&<>"']/g, function (char) {
            return {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            }[char];
        });
    }

    // Notifikasi berhasil
    function showSuccess(message) {
        document.getElementById('successMessage').textContent = message;
        successBanner.hidden = false;
    }

    document.getElementById('closeSuccessBanner')
        .addEventListener('click', function () {
            successBanner.hidden = true;
        });

    // Modal
    function openModal() {
        modal.classList.add('is-open');
        modal.setAttribute('aria-hidden', 'false');
        document.body.classList.add('mhs-modal-open');
    }

    function closeModal() {
        modal.classList.remove('is-open');
        modal.setAttribute('aria-hidden', 'true');
        document.body.classList.remove('mhs-modal-open');
        currentNim = null;
    }

    // Statistik tidak ditampilkan, tetapi tabel diperbarui otomatis.
    function renderTable() {

        const keyword = searchInput.value.toLowerCase().trim();
        const selectedProdi = prodiFilter.value;
        const selectedAngkatan = angkatanFilter.value;
        const selectedStatus = statusFilter.value;

        const filtered = students.filter(student => {

            const matchesSearch =
                student.nim.toLowerCase().includes(keyword) ||
                student.name.toLowerCase().includes(keyword);

            const matchesProdi =
                !selectedProdi || student.prodi === selectedProdi;

            const matchesAngkatan =
                !selectedAngkatan || student.angkatan === selectedAngkatan;

            const matchesStatus =
                !selectedStatus || student.status === selectedStatus;

            return matchesSearch &&
                matchesProdi &&
                matchesAngkatan &&
                matchesStatus;
        });

        tableBody.innerHTML = '';

        if (filtered.length === 0) {

            tableBody.innerHTML = `
                <tr>
                    <td colspan="7" class="mhs-empty">
                        Tidak ada data mahasiswa yang ditemukan.
                    </td>
                </tr>
            `;

        } else {

            filtered.forEach(student => {

                const row = document.createElement('tr');

                row.innerHTML = `
                    <td>${escapeHtml(student.nim)}</td>

                    <td>
                        <strong>${escapeHtml(student.name)}</strong>
                    </td>

                    <td>${escapeHtml(student.prodi)}</td>

                    <td>${escapeHtml(student.angkatan)}</td>

                    <td>
                        <small class="baak-email">
                            ${escapeHtml(student.email)}
                        </small>
                    </td>

                    <td>
                        <span class="baak-status ${
                            student.status === 'Aktif'
                                ? 'active'
                                : 'inactive'
                        }">
                            ${escapeHtml(student.status)}
                        </span>
                    </td>

                    <td>
                        <div class="mhs-row-actions">

                            <button
                                type="button"
                                data-action="detail"
                                data-nim="${escapeHtml(student.nim)}"
                            >
                                Detail
                            </button>

                            <button
                                type="button"
                                data-action="edit"
                                data-nim="${escapeHtml(student.nim)}"
                            >
                                Edit
                            </button>

                            ${
                                student.status === 'Aktif'
                                ? `
                                    <button
                                        type="button"
                                        class="danger"
                                        data-action="toggle-status"
                                        data-nim="${escapeHtml(student.nim)}"
                                    >
                                        Nonaktifkan
                                    </button>
                                `
                                : `
                                    <button
                                        type="button"
                                        data-action="toggle-status"
                                        data-nim="${escapeHtml(student.nim)}"
                                    >
                                        Aktifkan
                                    </button>
                                `
                            }

                            ${
                                student.is_new
                                ? '<small class="mhs-new-label">Baru ditambahkan</small>'
                                : ''
                            }

                        </div>
                    </td>
                `;

                tableBody.appendChild(row);

            });

        }

        document.getElementById('studentCount').textContent =
            `Menampilkan ${filtered.length} dari ${students.length} data contoh`;

        document.getElementById('studentPagination').textContent =
            'Halaman 1 dari 1';

        document.getElementById('resetSearch').hidden =
            !keyword &&
            !selectedProdi &&
            !selectedAngkatan &&
            !selectedStatus;
    }

    // Form tambah dan edit
    function getFormMarkup(student = {}) {

        return `
            <form id="studentForm" novalidate>

                <div class="mhs-form-grid">

                    ${fields.map(field => {

                        const value = student[field.key] || '';

                        if (field.key === 'status') {
                            return `
                                <div class="mhs-form-group">

                                    <label for="field-status">
                                        Status *
                                    </label>

                                    <select id="field-status" name="status">
                                        <option value="">Pilih status</option>

                                        <option value="Aktif"
                                            ${value === 'Aktif' ? 'selected' : ''}>
                                            Aktif
                                        </option>

                                        <option value="Nonaktif"
                                            ${value === 'Nonaktif' ? 'selected' : ''}>
                                            Nonaktif
                                        </option>
                                    </select>

                                    <small class="mhs-error" data-error="status"></small>

                                </div>
                            `;
                        }

                        if (field.key === 'prodi') {
                            return `
                                <div class="mhs-form-group">

                                    <label for="field-prodi">
                                        Program studi *
                                    </label>

                                    <select id="field-prodi" name="prodi">
                                        <option value="">Pilih program studi</option>

                                        ${[
                                            'Informatika',
                                            'Teknik Informatika',
                                            'Teknik Mesin',
                                            'Teknik Sipil'
                                        ].map(prodi => `
                                            <option value="${prodi}"
                                                ${value === prodi ? 'selected' : ''}>
                                                ${prodi}
                                            </option>
                                        `).join('')}

                                    </select>

                                    <small class="mhs-error" data-error="prodi"></small>

                                </div>
                            `;
                        }

                        return `
                            <div class="mhs-form-group">

                                <label for="field-${field.key}">
                                    ${field.label} *
                                </label>

                                <input
                                    type="${field.key === 'email' ? 'email' : 'text'}"
                                    id="field-${field.key}"
                                    name="${field.key}"
                                    placeholder="${field.placeholder}"
                                    value="${escapeHtml(value)}"
                                    ${field.key === 'nim' && currentNim ? 'readonly' : ''}
                                >

                                <small
                                    class="mhs-error"
                                    data-error="${field.key}"
                                ></small>

                            </div>
                        `;

                    }).join('')}

                </div>

                <p class="mhs-form-note">
                    * NIM harus unik. Format email menggunakan format student.iti.ac.id.
                </p>

                <div class="mhs-form-tools">

                    <button
                        type="button"
                        class="mhs-outline-button"
                        id="sampleDataButton"
                    >
                        Isi data contoh
                    </button>

                    <button
                        type="button"
                        class="mhs-outline-button"
                        id="duplicateNimButton"
                    >
                        Coba NIM terdaftar
                    </button>

                </div>

                <div class="mhs-modal-actions">

                    <button
                        type="button"
                        class="mhs-cancel-button"
                        id="cancelFormButton"
                    >
                        Batal
                    </button>

                    <button
                        type="submit"
                        class="mhs-submit-button"
                    >
                        ${currentNim ? 'Simpan Perubahan' : 'Simpan Mahasiswa'}
                    </button>

                </div>

            </form>
        `;
    }

    // Hapus pesan error
    function clearErrors() {

        document.querySelectorAll('.mhs-error').forEach(error => {
            error.textContent = '';
        });

        document.querySelectorAll(
            '.mhs-form-group input, .mhs-form-group select'
        ).forEach(input => {
            input.classList.remove('has-error');
        });

        const notice = document.getElementById('mhsFormNotice');

        if (notice) {
            notice.remove();
        }
    }

    // Tampilkan error
    function showError(key, message) {

        const input = modalBody.querySelector(`[name="${key}"]`);
        const error = modalBody.querySelector(`[data-error="${key}"]`);

        if (input) {
            input.classList.add('has-error');
        }

        if (error) {
            error.textContent = message;
        }
    }

    function showNotice(message) {

        let notice = document.getElementById('mhsFormNotice');

        if (!notice) {
            notice = document.createElement('div');
            notice.id = 'mhsFormNotice';
            notice.className = 'mhs-form-notice';
            modalBody.prepend(notice);
        }

        notice.textContent = message;
    }

    // Buka modal tambah
    function openAddModal() {

        currentNim = null;

        modalTitle.textContent = 'Tambah Mahasiswa';

        modalDescription.textContent =
            'Lengkapi data mahasiswa. Kolom bertanda * wajib diisi.';

        modalBody.innerHTML = getFormMarkup();

        openModal();
    }

    // Buka modal edit
    function openEditModal(student) {

        currentNim = student.nim;

        modalTitle.textContent = 'Edit Mahasiswa';

        modalDescription.textContent =
            'Perbarui data mahasiswa. Kolom bertanda * wajib diisi.';

        modalBody.innerHTML = getFormMarkup(student);

        openModal();
    }

    // Buka modal detail
    function openDetailModal(student) {

        currentNim = student.nim;

        modalTitle.textContent = 'Detail Mahasiswa';

        modalDescription.textContent =
            `Identitas dan data mahasiswa - ${student.nim}`;

        modalBody.innerHTML = `

            <div class="mhs-form-grid mhs-detail-grid">

                ${fields.map(field => `

                    <div class="mhs-form-group">

                        <label>${field.label}</label>

                        <input
                            type="text"
                            readonly
                            value="${escapeHtml(student[field.key])}"
                        >

                    </div>

                `).join('')}

            </div>

            <div class="mhs-modal-actions">

                <button
                    type="button"
                    class="mhs-cancel-button"
                    id="backDetailButton"
                >
                    Kembali
                </button>

                <button
                    type="button"
                    class="mhs-submit-button"
                    id="editDetailButton"
                >
                    Edit Mahasiswa
                </button>

            </div>

        `;

        openModal();
    }

    // Ambil data form
    function getFormData() {

        const data = {};

        fields.forEach(field => {

            const input = modalBody.querySelector(
                `[name="${field.key}"]`
            );

            data[field.key] = input ? input.value.trim() : '';

        });

        return data;
    }

    // Validasi form
    function validateForm(data) {

        clearErrors();

        let valid = true;

        fields.forEach(field => {

            if (!data[field.key]) {
                showError(field.key, `${field.label} wajib diisi.`);
                valid = false;
            }

        });

        if (
            data.email &&
            !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(data.email)
        ) {
            showError('email', 'Format email tidak valid.');
            valid = false;
        }

        if (data.angkatan && !/^\d{4}$/.test(data.angkatan)) {
            showError('angkatan', 'Angkatan harus berupa 4 digit tahun.');
            valid = false;
        }

        if (data.nim && !/^\d+$/.test(data.nim)) {
            showError('nim', 'NIM hanya boleh berisi angka.');
            valid = false;
        }

        const duplicate = students.some(student =>
            student.nim === data.nim &&
            student.nim !== currentNim
        );

        if (data.nim && duplicate) {
            showError('nim', 'NIM sudah digunakan oleh mahasiswa lain.');

            showNotice(
                'Data gagal disimpan karena NIM tersebut sudah terdaftar.'
            );

            valid = false;
        }

        return valid;
    }

    // Tombol tambah
    document.getElementById('addStudentButton')
        .addEventListener('click', openAddModal);

    // Tombol tutup modal
    document.getElementById('closeStudentModal')
        .addEventListener('click', closeModal);

    modal.addEventListener('click', function (event) {

        if (event.target === modal) {
            closeModal();
        }

    });

    document.addEventListener('keydown', function (event) {

        if (
            event.key === 'Escape' &&
            modal.classList.contains('is-open')
        ) {
            closeModal();
        }

    });

    // Aksi di dalam modal
    modalBody.addEventListener('click', function (event) {

        const target = event.target;

        if (target.id === 'cancelFormButton') {
            closeModal();
        }

        if (target.id === 'sampleDataButton') {

            const sample = {
                nim: currentNim || '20261004',
                name: 'Aulia Rahma',
                prodi: 'Informatika',
                angkatan: '2026',
                email: 'aulia@student.iti.ac.id',
                status: 'Aktif'
            };

            Object.keys(sample).forEach(key => {

                const input = modalBody.querySelector(`[name="${key}"]`);

                if (input && !input.readOnly) {
                    input.value = sample[key];
                }

            });

            clearErrors();
        }

        if (target.id === 'duplicateNimButton') {

            const nimInput = modalBody.querySelector('[name="nim"]');

            if (nimInput && !nimInput.readOnly) {
                nimInput.value = students[0]?.nim || '';
                clearErrors();
            }

        }

        if (target.id === 'backDetailButton') {
            closeModal();
        }

        if (target.id === 'editDetailButton') {

            const student = students.find(item => item.nim === currentNim);

            if (student) {
                openEditModal(student);
            }

        }

    });

    // Simpan tambah atau edit
    modalBody.addEventListener('submit', function (event) {

        if (event.target.id !== 'studentForm') {
            return;
        }

        event.preventDefault();

        const data = getFormData();

        if (!validateForm(data)) {
            return;
        }

        const editing = Boolean(currentNim);

        if (editing) {

            const index = students.findIndex(
                student => student.nim === currentNim
            );

            if (index !== -1) {
                data.is_new = students[index].is_new || false;
                students[index] = data;
            }

        } else {

            data.is_new = true;
            students.push(data);

        }

        const studentName = data.name;

        closeModal();
        renderTable();

        showSuccess(
            editing
                ? `Perubahan data mahasiswa ${studentName} berhasil disimpan (simulasi).`
                : 'Data mahasiswa berhasil ditambahkan (simulasi).'
        );

    });

    // Aksi pada tabel
    tableBody.addEventListener('click', function (event) {

        const button = event.target.closest('[data-action]');

        if (!button) {
            return;
        }

        const action = button.dataset.action;
        const nim = button.dataset.nim;

        const student = students.find(item => item.nim === nim);

        if (!student) {
            return;
        }

        if (action === 'detail') {
            openDetailModal(student);
        }

        if (action === 'edit') {
            openEditModal(student);
        }

        if (action === 'toggle-status') {

            student.status = student.status === 'Aktif'
                ? 'Nonaktif'
                : 'Aktif';

            renderTable();

            showSuccess(
                `Status mahasiswa ${student.name} berhasil diubah menjadi ${student.status} (simulasi).`
            );

        }

    });

    // Filter pencarian
    searchInput.addEventListener('input', renderTable);
    prodiFilter.addEventListener('change', renderTable);
    angkatanFilter.addEventListener('change', renderTable);
    statusFilter.addEventListener('change', renderTable);

    // Reset filter
    document.getElementById('resetSearch')
        .addEventListener('click', function () {

            searchInput.value = '';
            prodiFilter.value = '';
            angkatanFilter.value = '';
            statusFilter.value = '';

            renderTable();

        });

    // Tampilan awal
    renderTable();

});
</script>

@endsection
