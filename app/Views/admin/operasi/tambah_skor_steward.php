<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<?= $this->include('components/modal/modaldokter') ?>
<?= $this->include('components/modal/modalpetugas') ?>

<?php
$baseInput     = 'border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full dark:border-gray-600 dark:text-white';
$inputClass    = "$baseInput cursor-pointer bg-slate-50";
$readonlyClass = "$baseInput bg-gray-100 cursor-not-allowed lg:w-1/4";
$textareaClass = "$baseInput bg-slate-50";

$labelLeft  = 'block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4';
$labelRight = 'block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5';
$btnClass   = 'inline-flex justify-center items-center p-2 text-sm font-medium text-white bg-blue-600 rounded-lg border border-transparent hover:bg-blue-700 focus:outline-none transition-all w-10 h-[38px] flex-shrink-0';
$searchIcon = '<svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>';

$skorCategories = [
    ['key' => 'kesadaran', 'label' => 'Kesadaran', 'name' => 'skor_kesadaran', 'id_field' => 'id_kesadaran'],
    ['key' => 'respirasi', 'label' => 'Respirasi', 'name' => 'skor_respirasi', 'id_field' => 'id_respirasi'],
    ['key' => 'motorik',   'label' => 'Motorik',   'name' => 'skor_motorik',   'id_field' => 'id_motorik'],
];
?>

<div class="max-w-[85rem] py-6 lg:py-3 px-8 mx-auto">
    <div class="bg-white rounded-xl shadow-sm p-4 sm:p-7 dark:bg-slate-900 border border-gray-100 dark:border-gray-800">
        <?= view('components/form/judul', ['judul' => $judul]) ?>

        <form action="<?= $modul_path . $form_action ?>" id="myForm" onsubmit="return validateForm()" method="post">
            <?= csrf_field() ?>
            <input type="hidden" name="id_jadwal"          value="<?= esc((string) ($baris['id_jadwal']          ?? '')) ?>">
            <input type="hidden" name="id_tindakan"        value="<?= esc((string) ($baris['id_tindakan']        ?? '')) ?>">
            <input type="hidden" name="id_petugas"         id="id_petugas"         value="<?= esc((string) ($baris['id_petugas']         ?? '')) ?>">
            <input type="hidden" name="id_dokter_anestesi" id="id_dokter_anestesi" value="<?= esc((string) ($baris['id_dokter_anestesi'] ?? '')) ?>">
            <input type="hidden" name="is_boleh_pindah"    id="is_boleh_pindah"    value="<?= esc((string) ($baris['is_boleh_pindah']    ?? '0')) ?>">

            <!-- ── Info Pasien ── -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="<?= $labelLeft ?>">No. Registrasi</label>
                <input type="text" value="<?= esc($jadwal['nomor_reg'] ?? '-') ?>"
                       readonly class="<?= $readonlyClass ?> lg:w-1/4">

                <label class="<?= $labelRight ?>">Nama Pasien</label>
                <input type="text" value="<?= esc($jadwal['nama_pasien'] ?? '-') ?>"
                       readonly class="<?= $readonlyClass ?> lg:w-1/4">
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="<?= $labelLeft ?>">Waktu Penilaian <span class="text-red-500">*</span></label>
                <input type="datetime-local" name="waktu_penilaian"
                       value="<?= esc(substr(str_replace(' ', 'T', $baris['waktu_penilaian'] ?? ''), 0, 16)) ?>"
                       max="<?= date('Y-m-d\TH:i') ?>" required class="<?= $inputClass ?> lg:w-1/4">

                <label class="<?= $labelRight ?>">Tindakan Operasi</label>
                <input type="text" value="<?= esc($baris['nama_tindakan'] ?? $jadwal['nama_tindakan'] ?? '-') ?>"
                       readonly class="<?= $readonlyClass ?> lg:w-1/4">
            </div>

            <!-- ── Petugas & Dokter ── -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="<?= $labelLeft ?>">Petugas <span class="text-red-500">*</span></label>
                <div class="flex gap-x-2 lg:w-1/4">
                    <input type="text" id="nama_petugas"
                           value="<?= esc($baris['nama_petugas'] ?? '') ?>"
                           readonly required placeholder="Klik cari petugas..."
                           onclick="open_modalPetugas()"
                           class="<?= $inputClass ?>">
                    <button type="button" onclick="open_modalPetugas()" class="<?= $btnClass ?>">
                        <?= $searchIcon ?>
                    </button>
                </div>

                <label class="<?= $labelRight ?>">Dokter Anestesi <span class="text-red-500">*</span></label>
                <div class="flex gap-x-2 lg:w-1/4">
                    <input type="text" id="nama_dokter_anestesi"
                           value="<?= esc($baris['nama_dokter_anestesi'] ?? $jadwal['nama_dokter_anestesi'] ?? '') ?>"
                           readonly required placeholder="Klik cari dokter..."
                           onclick="open_modalDokter()"
                           class="<?= $inputClass ?>">
                    <button type="button" onclick="open_modalDokter()" class="<?= $btnClass ?>">
                        <?= $searchIcon ?>
                    </button>
                </div>
            </div>

            <!-- ── Skor Steward ── -->
            <div class="border-t border-gray-200 dark:border-gray-700 my-5 pt-5">
                <p class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4">Penilaian Skor Steward</p>

                <?php foreach ($skorCategories as $cat): ?>
                <?php $selectedId = (int) ($baris[$cat['name']] ?? 0); ?>
                <div class="mb-5 sm:block md:flex items-center">
                    <label class="<?= $labelLeft ?>"><?= esc($cat['label']) ?> <span class="text-red-500">*</span></label>
                    <select name="<?= $cat['name'] ?>" id="sel_<?= $cat['key'] ?>"
                            onchange="recalculate()" required
                            class="<?= $inputClass ?> lg:w-2/5">
                        <option value="">-- Pilih Skala --</option>
                        <?php foreach ($options[$cat['key']] as $item): ?>
                        <option value="<?= esc((string) $item[$cat['id_field']]) ?>"
                                data-nilai="<?= esc((string) $item['nilai']) ?>"
                                <?= $selectedId === (int) $item[$cat['id_field']] ? 'selected' : '' ?>>
                            <?= esc($item['nama_skala']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>

                    <label class="<?= $labelRight ?>">Nilai</label>
                    <span id="val_<?= $cat['key'] ?>"
                          class="text-sm font-bold text-gray-900 dark:text-white ml-2 w-6 text-center">-</span>
                </div>
                <?php endforeach; ?>

                <!-- Total & Keputusan -->
                <div class="mt-4 p-4 rounded-lg bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center gap-x-4 mb-3">
                        <span class="text-sm text-gray-700 dark:text-gray-300 font-semibold">Total Skor:</span>
                        <span id="total_skor" class="text-2xl font-bold text-gray-900 dark:text-white">0</span>
                        <span class="text-sm text-gray-500 dark:text-gray-400">/ 6</span>
                    </div>
                    <p id="narasi_keputusan" class="text-sm text-gray-500 dark:text-gray-400">
                        Pilih semua skala untuk melihat keputusan.
                    </p>
                </div>
            </div>

            <!-- ── Catatan ── -->
            <div class="mt-5 mb-5">
                <label class="block mb-2 text-sm text-gray-900 dark:text-white">
                    Catatan Keluar <span class="text-red-500">*</span>
                </label>
                <textarea name="catatan_keluar" rows="3" required
                          class="<?= $textareaClass ?>"><?= esc($baris['catatan_keluar'] ?? '') ?></textarea>
            </div>

            <div class="mb-5">
                <label class="block mb-2 text-sm text-gray-900 dark:text-white">
                    Instruksi RR <span class="text-red-500">*</span>
                </label>
                <textarea name="instruksi_rr" rows="3" required
                          class="<?= $textareaClass ?>"><?= esc($baris['instruksi_rr'] ?? '') ?></textarea>
            </div>

            <?= view('components/form/submit_button') ?>
        </form>
    </div>
</div>

<script>
    // ── Skor Steward ───────────────────────────────────────────────────────────
    const SKOR_KEYS = ['kesadaran', 'respirasi', 'motorik'];
    const THRESHOLD = 5;

    function recalculate() {
        let total       = 0;
        let allSelected = true;

        SKOR_KEYS.forEach(key => {
            const sel   = document.getElementById('sel_' + key);
            const opt   = sel.options[sel.selectedIndex];
            const nilai = opt && opt.dataset.nilai !== undefined ? parseInt(opt.dataset.nilai, 10) : NaN;
            const valEl = document.getElementById('val_' + key);

            if (isNaN(nilai)) {
                valEl.textContent = '-';
                allSelected = false;
            } else {
                valEl.textContent = nilai;
                total += nilai;
            }
        });

        document.getElementById('total_skor').textContent = allSelected ? total : '-';

        const narasiEl = document.getElementById('narasi_keputusan');
        if (allSelected) {
            const boleh = total >= THRESHOLD;
            document.getElementById('is_boleh_pindah').value = boleh ? '1' : '0';
            narasiEl.textContent = boleh
                ? 'Pasien bisa dipindahkan ke ruang perawatan bila skor minimal 5'
                : 'Pasien tidak dapat dipindahkan ke ruang perawatan karena kondisi yang lemah';
            narasiEl.className = 'text-sm font-medium ' + (boleh ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400');
        } else {
            narasiEl.textContent = 'Pilih semua skala untuk melihat keputusan.';
            narasiEl.className   = 'text-sm text-gray-500 dark:text-gray-400';
        }
    }

    // ── Dokter (single slot) ───────────────────────────────────────────────────
    function autofillFields(item) {
        document.getElementById('id_dokter_anestesi').value   = item.id_dokter   ?? '';
        document.getElementById('nama_dokter_anestesi').value = item.nama_dokter ?? '';
    }

    // ── Petugas (single slot) ─────────────────────────────────────────────────
    function autofillPetugas(item) {
        document.getElementById('id_petugas').value   = item.id_petugas ?? '';
        document.getElementById('nama_petugas').value = item.nama       ?? '';
    }

    // ── Validate ───────────────────────────────────────────────────────────────
    function validateForm() {
        if (!document.getElementById('id_petugas').value) {
            alert('Silakan pilih petugas terlebih dahulu.');
            return false;
        }
        if (!document.getElementById('id_dokter_anestesi').value) {
            alert('Silakan pilih dokter anestesi terlebih dahulu.');
            return false;
        }
        const btn = document.getElementById('submitButton');
        if (btn) { btn.disabled = true; btn.innerHTML = 'Menyimpan...'; }
        return true;
    }

    // ── Init ───────────────────────────────────────────────────────────────────
    document.addEventListener('DOMContentLoaded', recalculate);
</script>

<?= $this->endSection(); ?>
