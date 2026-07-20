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
$renderSelect = function (string $name, array $opts, string $selected) {
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
<?= $this->include('components/modal/modalwilayah') ?>

<div class="max-w-[85rem] py-6 lg:py-3 px-8 mx-auto">
    <div class="bg-white rounded-xl shadow p-4 sm:p-7 dark:bg-slate-900">
        <?= view('components/form/judul', ['judul' => $judul]) ?>

        <form action="<?= $modul_path . $form_action ?>" id="myForm" onsubmit="return validateForm()" method="post">
            <?= csrf_field() ?>

            <input type="hidden" name="redirect_to" value="<?= esc($baris['redirect_to'] ?? '') ?>">
            <input type="hidden" name="id_orang" id="id_orang" value="<?= $val('id_orang') ?>">

            <!-- No. Rekam Medis + NIK -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="<?= $labelLeft ?>">No. Rekam Medis <span class="text-red-600">*</span></label>
                <input type="text" name="nomor_rm" value="<?= $val('nomor_rm') ?>" readonly required
                       placeholder="Terisi otomatis..." class="<?= $readonlyClass ?> lg:w-1/4">

                <label class="<?= $labelRight ?>">NIK <span class="text-red-600">*</span></label>
                <input type="text" name="nik" id="nik" value="<?= $val('nik') ?>" required
                       minlength="16" maxlength="16" inputmode="numeric" pattern="[0-9]{16}"
                       oninput="this.value = this.value.replace(/[^0-9]/g, '');"
                       class="<?= $editClass ?> lg:w-1/4">
            </div>

            <!-- Nama + Jenis Kelamin -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="<?= $labelLeft ?>">Nama Lengkap <span class="text-red-600">*</span></label>
                <input type="text" name="nama" id="nama" value="<?= $val('nama') ?>" required
                       maxlength="60" pattern="[A-Za-z .'\-]+"
                       oninput="this.value = this.value.replace(/[^A-Za-z .'\-]/g, '');"
                       class="<?= $editClass ?> lg:w-1/4">

                <label class="<?= $labelRight ?>">Jenis Kelamin <span class="text-red-600">*</span></label>
                <select name="id_jenis_kelamin" required class="<?= $selectClass ?> lg:w-1/4">
                    <?= $renderSelect('id_jenis_kelamin', $options('id_jenis_kelamin'), $val('id_jenis_kelamin')) ?>
                </select>
            </div>

            <!-- Tempat Lahir + Tanggal Lahir -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="<?= $labelLeft ?>">Tempat Lahir <span class="text-red-600">*</span></label>
                <input type="hidden" name="tempat_lahir_kota" id="tempat_lahir_kota" value="<?= $val('tempat_lahir_kota') ?>" required>
                <div class="flex gap-x-2 lg:w-1/4">
                    <input type="text" id="nama_kota" readonly required
                           value="<?= $val('nama_kota') ?>" placeholder="Klik cari..."
                           onclick="open_modalKota()" class="<?= $inputClass ?>">
                    <button type="button" onclick="open_modalKota()" class="<?= $btnClass ?>"><?= $searchIcon ?></button>
                </div>

                <label class="<?= $labelRight ?>">Tanggal Lahir <span class="text-red-600">*</span></label>
                <input type="date" name="tanggal_lahir" id="tanggal_lahir" value="<?= $val('tanggal_lahir') ?>"
                       max="<?= date('Y-m-d') ?>" required class="<?= $editClass ?> lg:w-1/4">
            </div>

            <!-- Agama + Status Pernikahan -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="<?= $labelLeft ?>">Agama <span class="text-red-600">*</span></label>
                <select name="id_agama" required class="<?= $selectClass ?> lg:w-1/4">
                    <?= $renderSelect('id_agama', $options('id_agama'), $val('id_agama')) ?>
                </select>

                <label class="<?= $labelRight ?>">Status Pernikahan <span class="text-red-600">*</span></label>
                <select name="id_pernikahan" required class="<?= $selectClass ?> lg:w-1/4">
                    <?= $renderSelect('id_pernikahan', $options('id_pernikahan'), $val('id_pernikahan')) ?>
                </select>
            </div>

            <!-- Golongan Darah -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="<?= $labelLeft ?>">Golongan Darah <span class="text-red-600">*</span></label>
                <select name="id_golongan_darah" required class="<?= $selectClass ?> lg:w-1/4">
                    <?= $renderSelect('id_golongan_darah', $options('id_golongan_darah'), $val('id_golongan_darah')) ?>
                </select>
            </div>

            <!-- Alamat Lengkap -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="<?= $labelLeft ?>">Alamat Lengkap <span class="text-red-600">*</span></label>
                <textarea name="alamat_lengkap" rows="2" required
                          class="<?= $editClass ?> lg:w-1/4 resize-y"><?= $val('alamat_lengkap') ?></textarea>
            </div>

            <!-- Kelurahan/Desa + Kecamatan -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="<?= $labelLeft ?>">Kelurahan / Desa <span class="text-red-600">*</span></label>
                <input type="hidden" name="id_provinsi" id="id_provinsi" value="<?= $val('id_provinsi') ?>" required>
                <input type="hidden" name="id_kota_lokal" id="id_kota_lokal" value="<?= $val('id_kota_lokal') ?>" required>
                <input type="hidden" name="id_kec_lokal" id="id_kec_lokal" value="<?= $val('id_kec_lokal') ?>" required>
                <input type="hidden" name="id_desa_lokal" id="id_desa_lokal" value="<?= $val('id_desa_lokal') ?>" required>
                <div class="flex gap-x-2 lg:w-1/4">
                    <input type="text" id="nama_desa" readonly
                           value="<?= $val('nama_desa') ?>" placeholder="Klik cari..."
                           onclick="open_modalWilayah()" class="<?= $inputClass ?>">
                    <button type="button" onclick="open_modalWilayah()" class="<?= $btnClass ?>"><?= $searchIcon ?></button>
                </div>

                <label class="<?= $labelRight ?>">Kecamatan</label>
                <input type="text" id="nama_kecamatan" readonly placeholder="Terisi otomatis..."
                       value="<?= $val('nama_kecamatan') ?>" class="<?= $readonlyClass ?> lg:w-1/4">
            </div>

            <!-- Kota + Provinsi -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="<?= $labelLeft ?>">Kota / Kabupaten</label>
                <input type="text" id="nama_kota_wilayah" readonly placeholder="Terisi otomatis..."
                       value="<?= $val('nama_kota_wilayah') ?>" class="<?= $readonlyClass ?> lg:w-1/4">

                <label class="<?= $labelRight ?>">Provinsi</label>
                <input type="text" id="nama_provinsi" readonly placeholder="Terisi otomatis..."
                       value="<?= $val('nama_provinsi') ?>" class="<?= $readonlyClass ?> lg:w-1/4">
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

    function autofillWilayah(item) {
        document.getElementById('id_provinsi').value   = item.id_provinsi;
        document.getElementById('id_kota_lokal').value = item.id_kota_lokal;
        document.getElementById('id_kec_lokal').value  = item.id_kec_lokal;
        document.getElementById('id_desa_lokal').value = item.id_desa_lokal;

        document.getElementById('nama_desa').value        = item.nama_desa;
        document.getElementById('nama_kecamatan').value    = item.nama_kecamatan;
        document.getElementById('nama_kota_wilayah').value = item.nama_kota;
        document.getElementById('nama_provinsi').value     = item.nama_provinsi;
    }

    function validateForm() {
        var requiredFields = document.querySelectorAll('select[required], input[required], textarea[required]');
        for (var i = 0; i < requiredFields.length; i++) {
            if (!requiredFields[i].value) {
                alert("Mohon isi semua field yang bertanda bintang.");
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