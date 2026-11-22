@php
    $requestedPeriod = request()->query('periode', '20261');
    $periodeCode = is_string($requestedPeriod) && in_array($requestedPeriod, ['20261', '20252', '20251'], true)
        ? $requestedPeriod
        : '20261';
    $jumlahMk = $jumlahMk ?? 6;
    $totalSks = $totalSks ?? 18;
    $dosenPa = $dosenPa ?? 'Dr. Rina Kartika, M.Kom.';
    $autoOpenModal = request()->query('state') === 'konfirmasi-ajukan';
@endphp

<style>
.krs-modal-backdrop {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(15, 23, 42, 0.45);
    z-index: 1100;
    align-items: center;
    justify-content: center;
    padding: 1rem;
}

.krs-modal-backdrop.is-open {
    display: flex;
}

.krs-modal-card {
    width: 100%;
    max-width: 440px;
    background: #ffffff;
    border: 1.5px dashed #3b82f6;
    border-radius: 0.5rem;
    padding: 1.35rem 1.5rem;
    box-shadow: 0 20px 40px rgba(15, 23, 42, 0.22);
    color: #0f172a;
}

.krs-modal-title {
    margin: 0 0 0.6rem;
    font-size: 1rem;
    font-weight: 700;
    color: #0f172a;
}

.krs-modal-desc {
    margin: 0 0 1.25rem;
    font-size: 0.84rem;
    line-height: 1.5;
    color: #334155;
}

.krs-modal-actions {
    display: flex;
    justify-content: flex-end;
    gap: 0.6rem;
}

.krs-btn-cancel {
    padding: 0.45rem 0.95rem;
    border-radius: 0.375rem;
    border: 1px solid #cbd5e1;
    background: #ffffff;
    color: #0f172a;
    font-size: 0.8125rem;
    font-weight: 600;
    cursor: pointer;
}

.krs-btn-cancel:hover {
    background: #f8fafc;
}

.krs-btn-submit {
    padding: 0.45rem 1rem;
    border-radius: 0.375rem;
    border: none;
    background: #6d285a;
    color: #ffffff;
    font-size: 0.8125rem;
    font-weight: 600;
    cursor: pointer;
}

.krs-btn-submit:hover {
    background: #581c48;
}
</style>

<div id="krsSubmitModal" class="krs-modal-backdrop {{ $autoOpenModal ? 'is-open' : '' }}" role="dialog" aria-modal="true" aria-labelledby="krsModalTitle" aria-hidden="{{ $autoOpenModal ? 'false' : 'true' }}" data-krs-modal>
    <div class="krs-modal-card">
        <h2 id="krsModalTitle" class="krs-modal-title">Ajukan KRS periode {{ $periodeCode }}?</h2>
        <p class="krs-modal-desc">
            <span data-krs-modal-count>{{ $jumlahMk }}</span> mata kuliah, <span data-krs-modal-sks>{{ $totalSks }}</span> SKS akan dikirim ke {{ $dosenPa }} (Dosen PA). Setelah diajukan, KRS tidak bisa diubah sampai ada keputusan.
        </p>

        <div class="krs-modal-actions">
            <button type="button" class="krs-btn-cancel" onclick="closeKrsSubmitModal()">Batal</button>
            <button type="submit" name="action" value="submit_krs" class="krs-btn-submit">Ajukan KRS</button>
        </div>
    </div>
</div>

<script>
function openKrsSubmitModal() {
    const modal = document.getElementById('krsSubmitModal');
    modal?.classList.add('is-open');
    modal?.setAttribute('aria-hidden', 'false');
    modal?.querySelector('.krs-btn-cancel')?.focus();
}

function closeKrsSubmitModal() {
    const modal = document.getElementById('krsSubmitModal');
    if (!modal?.classList.contains('is-open')) return;

    modal?.classList.remove('is-open');
    modal?.setAttribute('aria-hidden', 'true');
    document.querySelector('[data-krs-modal-trigger]')?.focus();
}

if (document.getElementById('krsSubmitModal')?.classList.contains('is-open')) {
    document.querySelector('#krsSubmitModal .krs-btn-cancel')?.focus();
}

document.addEventListener('keydown', function (event) {
    if (event.key === 'Escape') {
        closeKrsSubmitModal();
    }
});

document.getElementById('krsSubmitModal')?.addEventListener('click', function (event) {
    if (event.target === this) {
        closeKrsSubmitModal();
    }
});
</script>
