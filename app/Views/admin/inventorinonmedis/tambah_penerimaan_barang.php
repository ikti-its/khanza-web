<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<?= $this->include('components/modal/modalPemohon') ?>

<div class="max-w-[85rem] py-6 lg:py-3 px-8 mx-auto">
    <div class="bg-white rounded-xl shadow p-4 sm:p-7 dark:bg-slate-900">
        <?= view('components/form/judul', [
            'judul' => $judul
        ]) ?>

        <form action="<?= $modul_path . $form_action ?>" id="myForm" onsubmit="return validateForm()" method="post">
            <?= csrf_field() ?>
            <?php $baris = $baris ?? []; ?>

            <input type="hidden" name="petugas" id="petugas" value="<?= $baris['petugas'] ?? '' ?>">

            <!-- No. Penerimaan (auto) + Tanggal Penerimaan (input) -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">
                    No. Penerimaan
                </label>
                <input type="text" readonly placeholder="Terisi otomatis..." value="<?= $baris['no_penerimaan'] ?? '' ?>"
                       class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white bg-gray-100 cursor-not-allowed">

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">
                    Tanggal Penerimaan<span class="text-red-600">*</span>
                </label>
                <input type="datetime-local" name="tanggal" id="tanggal"
                       value="<?= $baris['tanggal'] ?? date('Y-m-d\TH:i') ?>"
                       class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white dark:bg-slate-800" required>
            </div>

            <!-- Status (auto) + No. Pengadaan (select) -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">
                    Status
                </label>
                <?php if (empty($baris)): ?>
                    <input type="text" readonly value="Proses Penerimaan"
                           class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white bg-gray-100 cursor-not-allowed">
                <?php else: ?>
                    <select name="id_status_penerimaan_barang"
                            class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white dark:bg-slate-800" required>
                        <option value="1" <?= (($baris['id_status_penerimaan_barang'] ?? '') == 1) ? 'selected' : '' ?>>Proses Penerimaan</option>
                        <option value="2" <?= (($baris['id_status_penerimaan_barang'] ?? '') == 2) ? 'selected' : '' ?>>Dikonfirmasi</option>
                        <option value="3" <?= (($baris['id_status_penerimaan_barang'] ?? '') == 3) ? 'selected' : '' ?>>Dibatalkan</option>
                    </select>
                <?php endif; ?>

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">
                    No. Pengadaan<span class="text-red-600">*</span>
                </label>
                <select name="id_pengadaan" id="id_pengadaan"
                        class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white dark:bg-slate-800" required>
                    <option value="">-- Pilih Pengadaan --</option>
                    <?php foreach ($options_pengadaan as $opt): ?>
                        <option value="<?= $opt[1] ?>" <?= (isset($baris['id_pengadaan']) && (string)$baris['id_pengadaan'] === (string)$opt[1]) ? 'selected' : '' ?>><?= esc($opt[0]) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- No. Masuk (auto) + Penerima (input) -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">
                    No. Masuk
                </label>
                <input type="text" readonly placeholder="Terisi otomatis..." value="<?= $baris['no_masuk'] ?? '' ?>"
                       class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white bg-gray-100 cursor-not-allowed">

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">
                    Penerima
                </label>
                <div class="w-full lg:w-1/4 flex gap-x-2">
                    <input type="text" id="petugas_display"
                           placeholder="Klik cari penerima..."
                           value="<?= $baris['nama'] ?? '' ?>"
                           onclick="open_modalPemohon()"
                           onkeydown="return false"
                           class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full dark:border-gray-600 dark:text-white cursor-pointer bg-white">
                    <button type="button" onclick="open_modalPemohon()"
                            class="inline-flex justify-center items-center p-2 text-sm font-medium text-white bg-blue-600 rounded-lg border border-transparent hover:bg-blue-700 focus:outline-none transition-all w-10 h-[38px] flex-shrink-0 shadow-sm">
                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Catatan -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">
                    Catatan
                </label>
                <input type="text" name="catatan" id="catatan" placeholder="Catatan (opsional)..." maxlength="500"
                       value="<?= $baris['catatan'] ?? '' ?>"
                       class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white dark:bg-slate-800">
            </div>

            <?= view('components/form/submit_button') ?>
        </form>
    </div>
</div>

<script>
    function validateForm() {
        var form = document.getElementById('myForm');
        if (!form.reportValidity()) {
            return false;
        }

        var submitButton = document.getElementById('submitButton');
        if (submitButton) {
            submitButton.setAttribute('disabled', true);
            submitButton.innerHTML = 'Menyimpan...';
        }
        return true;
    }
</script>

<?= $this->endSection(); ?>
