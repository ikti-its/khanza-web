<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<div class="max-w-[85rem] py-6 lg:py-3 px-8 mx-auto">
    <div class="bg-white rounded-xl shadow p-4 sm:p-7 dark:bg-slate-900">
        <?= view('components/form/judul', [
            'judul' => $judul
        ]) ?>
        
        <form action="<?= $modul_path . $form_action ?>" id="formStokDarah" onsubmit="return validateForm()" method="post">
            <?= csrf_field() ?>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">
                    Nomor Kantong<span class="text-red-600">*</span>
                </label>
                <div class="w-full lg:w-1/4">
                    <input type="text" name="no_kantong" value="<?= old('no_kantong', $baris['no_kantong'] ?? '') ?>" required
                           maxlength="20"
                           class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full dark:border-gray-600 dark:text-white h-[38px]">
                </div>

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">
                    Komponen Darah<span class="text-red-600">*</span>
                </label>
                <select name="id_komponen" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white dark:bg-slate-800 h-[38px]" required>
                    <option value="">-- Pilih --</option>
                    <?php
                    $optionsKomp = [];
                    foreach ($konfig as $field) { if ($field[2] === 'id_komponen') { $optionsKomp = $field[5] ?? []; break; } }
                    foreach ($optionsKomp as $opt) : $selected = ((string)old('id_komponen', $baris['id_komponen'] ?? '') === (string)$opt[1]) ? 'selected' : '';
                    ?>
                        <option value="<?= $opt[1] ?>" <?= $selected ?>><?= $opt[0] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">
                    Golongan Darah<span class="text-red-600">*</span>
                </label>
                <select name="id_golongan_darah" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white dark:bg-slate-800 h-[38px]" required>
                    <option value="">-- Pilih --</option>
                    <?php
                    $optionsGol = [];
                    foreach ($konfig as $field) { if ($field[2] === 'id_golongan_darah') { $optionsGol = $field[5] ?? []; break; } }
                    foreach ($optionsGol as $opt) : $selected = ((string)old('id_golongan_darah', $baris['id_golongan_darah'] ?? '') === (string)$opt[1]) ? 'selected' : '';
                    ?>
                        <option value="<?= $opt[1] ?>" <?= $selected ?>><?= $opt[0] ?></option>
                    <?php endforeach; ?>
                </select>

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">
                    Rhesus<span class="text-red-600">*</span>
                </label>
                <select name="id_rhesus" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white dark:bg-slate-800 h-[38px]" required>
                    <option value="">-- Pilih --</option>
                    <?php
                    $optionsRhe = [];
                    foreach ($konfig as $field) { if ($field[2] === 'id_rhesus') { $optionsRhe = $field[5] ?? []; break; } }
                    foreach ($optionsRhe as $opt) : $selected = ((string)old('id_rhesus', $baris['id_rhesus'] ?? '') === (string)$opt[1]) ? 'selected' : '';
                    ?>
                        <option value="<?= $opt[1] ?>" <?= $selected ?>><?= $opt[0] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">
                    Tanggal Pengambilan<span class="text-red-600">*</span>
                </label>
                <input type="date" name="tanggal_pengambilan" id="tanggal_pengambilan" 
                       value="<?= old('tanggal_pengambilan', $baris['tanggal_pengambilan'] ?? '') ?>" 
                       max="<?= date('Y-m-d') ?>"
                       class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white h-[38px]" required>

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">
                    Tanggal Kadaluarsa<span class="text-red-600">*</span>
                </label>
                <input type="date" name="tanggal_kadaluarsa" id="tanggal_kadaluarsa" 
                       value="<?= old('tanggal_kadaluarsa', $baris['tanggal_kadaluarsa'] ?? '') ?>" 
                       min="<?= date('Y-m-d') ?>"
                       class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white h-[38px]" required>
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">
                    Sumber Darah<span class="text-red-600">*</span>
                </label>
                <select name="id_sumber_darah" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white dark:bg-slate-800 h-[38px]" required>
                    <option value="">-- Pilih --</option>
                    <?php
                    $optionsSum = [];
                    foreach ($konfig as $field) { if ($field[2] === 'id_sumber_darah') { $optionsSum = $field[5] ?? []; break; } }
                    foreach ($optionsSum as $opt) : $selected = ((string)old('id_sumber_darah', $baris['id_sumber_darah'] ?? '') === (string)$opt[1]) ? 'selected' : '';
                    ?>
                        <option value="<?= $opt[1] ?>" <?= $selected ?>><?= $opt[0] ?></option>
                    <?php endforeach; ?>
                </select>

                <div class="block mt-5 md:my-0 md:ml-10 mb-2 w-1/5"></div>
                <div class="w-full lg:w-1/4"></div>
            </div>

            <?= view('components/form/submit_button') ?>
        </form>
    </div>
</div>

<script>
    function validateForm() {
        var requiredFields = document.querySelectorAll('select[required], input[required]');
        for (var i = 0; i < requiredFields.length; i++) {
            if (!requiredFields[i].value) {
                alert("Mohon isi semua field yang bertanda bintang.");
                return false;
            }
        }

        const tglAmbil = new Date(document.getElementById('tanggal_pengambilan').value);
        const tglExp = new Date(document.getElementById('tanggal_kadaluarsa').value);
        const hariIni = new Date();

        if (tglAmbil > hariIni) {
            alert("Gagal Menyimpan! Tanggal pengambilan tidak boleh di masa depan.");
            return false;
        }

        if (tglExp < hariIni) {
            alert("Gagal Menyimpan! Tanggal kadaluarsa tidak boleh di masa lalu (minimal hari ini).");
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