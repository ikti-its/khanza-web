<?php
$inputClass    = 'border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full dark:border-gray-600 dark:text-white cursor-pointer bg-slate-50';
$readonlyClass = 'border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full dark:border-gray-600 dark:text-white bg-gray-100 cursor-not-allowed';
$editClass     = 'border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full dark:border-gray-600 dark:text-white';
$selectClass   = 'border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full dark:border-gray-600 dark:text-white dark:bg-slate-800';
$labelLeft     = 'block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4';
$labelRight    = 'block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5';
$btnClass      = 'inline-flex justify-center items-center p-2 text-sm font-medium text-white bg-blue-600 rounded-lg border border-transparent hover:bg-blue-700 focus:outline-none transition-all w-10 h-[38px] flex-shrink-0 shadow-sm';
$searchIcon    = '<svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>';
$val           = fn($k) => esc($baris[$k] ?? '');

$options = function (string $column) use ($konfig): array {
    foreach ($konfig as $field) {
        if ($field[2] === $column) {
            return $field[5] ?? [];
        }
    }
    return [];
};
$renderSelect = function (array $opts, string $selected) {
    $html = '<option value="">-- Pilih --</option>';
    foreach ($opts as $opt) {
        $sel   = ((string) $selected === (string) $opt[1]) ? 'selected' : '';
        $html .= '<option value="' . esc($opt[1]) . '" ' . $sel . '>' . esc($opt[0]) . '</option>';
    }
    return $html;
};
?>

<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<?= $this->include('components/modal/modalkota') ?>
<?= $this->include('components/modal/modalalamat') ?>

<div class="max-w-[85rem] py-6 lg:py-3 px-8 mx-auto">
    <div class="bg-white rounded-xl shadow p-4 sm:p-7 dark:bg-slate-900">
        <?= view('components/form/judul', ['judul' => $judul]) ?>

        <form action="<?= $modul_path . $form_action ?>" id="myForm" onsubmit="return validateForm()" method="post">
            <?= csrf_field() ?>

            <input type="hidden" name="redirect_to" value="<?= esc($baris['redirect_to'] ?? '') ?>">

            <!-- NIK + Nama -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="<?= $labelLeft ?>">NIK <span class="text-red-600">*</span></label>
                <input type="text" name="nik" id="nik" value="<?= $val('nik') ?>" required data-label="NIK"
                       minlength="16" maxlength="16" inputmode="numeric" pattern="[0-9]{16}"
                       oninput="this.value = this.value.replace(/[^0-9]/g, '');"
                       class="<?= $editClass ?> lg:w-1/4">

                <label class="<?= $labelRight ?>">Nama Lengkap <span class="text-red-600">*</span></label>
                <input type="text" name="nama" id="nama" value="<?= $val('nama') ?>" required data-label="Nama Lengkap"
                       maxlength="60" pattern="[A-Za-z .'\-]+"
                       oninput="this.value = this.value.replace(/[^A-Za-z .'\-]/g, '');"
                       class="<?= $editClass ?> lg:w-1/4">
            </div>

            <!-- Jenis Kelamin + Tanggal Lahir -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="<?= $labelLeft ?>">Jenis Kelamin <span class="text-red-600">*</span></label>
                <select name="id_jenis_kelamin" required data-label="Jenis Kelamin" class="<?= $selectClass ?> lg:w-1/4">
                    <?= $renderSelect($options('id_jenis_kelamin'), $val('id_jenis_kelamin')) ?>
                </select>

                <label class="<?= $labelRight ?>">Tanggal Lahir <span class="text-red-600">*</span></label>
                <input type="date" name="tanggal_lahir" id="tanggal_lahir" value="<?= $val('tanggal_lahir') ?>"
                       max="<?= date('Y-m-d') ?>" required data-label="Tanggal Lahir" class="<?= $editClass ?> lg:w-1/4">
            </div>

            <!-- Tempat Lahir + Agama -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="<?= $labelLeft ?>">Tempat Lahir <span class="text-red-600">*</span></label>
                <input type="hidden" name="tempat_lahir_kota" id="tempat_lahir_kota" value="<?= $val('tempat_lahir_kota') ?>" required data-label="Tempat Lahir">
                <div class="flex gap-x-2 lg:w-1/4">
                    <input type="text" id="nama_kota" readonly
                           value="<?= $val('nama_kota') ?>" placeholder="Klik cari..."
                           onclick="open_modalKota()" class="<?= $inputClass ?>">
                    <button type="button" onclick="open_modalKota()" class="<?= $btnClass ?>"><?= $searchIcon ?></button>
                </div>

                <label class="<?= $labelRight ?>">Agama <span class="text-red-600">*</span></label>
                <select name="id_agama" required data-label="Agama" class="<?= $selectClass ?> lg:w-1/4">
                    <?= $renderSelect($options('id_agama'), $val('id_agama')) ?>
                </select>
            </div>

            <!-- Status Pernikahan + Golongan Darah -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="<?= $labelLeft ?>">Status Pernikahan <span class="text-red-600">*</span></label>
                <select name="id_pernikahan" required data-label="Status Pernikahan" class="<?= $selectClass ?> lg:w-1/4">
                    <?= $renderSelect($options('id_pernikahan'), $val('id_pernikahan')) ?>
                </select>

                <label class="<?= $labelRight ?>">Golongan Darah <span class="text-red-600">*</span></label>
                <select name="id_golongan_darah" required data-label="Golongan Darah" class="<?= $selectClass ?> lg:w-1/4">
                    <?= $renderSelect($options('id_golongan_darah'), $val('id_golongan_darah')) ?>
                </select>
            </div>

            <!-- Alamat -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="<?= $labelLeft ?>">Alamat <span class="text-red-600">*</span></label>
                <input type="hidden" name="id_alamat" id="id_alamat" value="<?= $val('id_alamat') ?>" required data-label="Alamat">
                <div class="flex gap-x-2 lg:w-1/4">
                    <input type="text" id="alamat_lengkap_display" readonly
                           value="<?= $val('alamat_lengkap') ?>" placeholder="Klik cari alamat..."
                           onclick="open_modalAlamat()" class="<?= $inputClass ?>">
                    <button type="button" onclick="open_modalAlamat()" class="<?= $btnClass ?>"><?= $searchIcon ?></button>
                </div>
            </div>

            <?= view('components/form/submit_button') ?>
        </form>
    </div>
</div>

<script>
    function autofillKota(item) {
        document.getElementById('tempat_lahir_kota').value = item.id_kota;
        document.getElementById('nama_kota').value = item.nama_kota;
    }

    function autofillAlamatFields(item) {
        document.getElementById('id_alamat').value = item.id_alamat;
        document.getElementById('alamat_lengkap_display').value = item.alamat_lengkap;
    }

    function validateForm() {
        var requiredFields = document.querySelectorAll('select[required], input[required], textarea[required]');
        for (var i = 0; i < requiredFields.length; i++) {
            if (!requiredFields[i].value) {
                var label = requiredFields[i].dataset.label || 'Field ini';
                alert(label + ' wajib diisi.');
                requiredFields[i].focus();
                return false;
            }
        }

        const inputNik = document.getElementById('nik').value;
        if (inputNik.length !== 16) {
            alert(`Gagal Menyimpan! NIK yang Anda masukkan saat ini berjumlah ${inputNik.length} digit.\nNIK wajib berjumlah tepat 16 digit angka.`);
            document.getElementById('nik').focus();
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