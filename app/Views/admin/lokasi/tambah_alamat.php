<?php
$inputClass    = 'border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full dark:border-gray-600 dark:text-white cursor-pointer bg-slate-50';
$readonlyClass = 'border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full dark:border-gray-600 dark:text-white bg-gray-100 cursor-not-allowed';
$editClass     = 'border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full dark:border-gray-600 dark:text-white';
$labelLeft     = 'block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4';
$labelRight    = 'block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5';
$btnClass      = 'inline-flex justify-center items-center p-2 text-sm font-medium text-white bg-blue-600 rounded-lg border border-transparent hover:bg-blue-700 focus:outline-none transition-all w-10 h-[38px] flex-shrink-0 shadow-sm';
$searchIcon    = '<svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>';
$val           = fn($k) => esc($baris[$k] ?? '');
?>

<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<?= $this->include('components/modal/modalwilayah') ?>

<div class="max-w-[85rem] py-6 lg:py-3 px-8 mx-auto">
    <div class="bg-white rounded-xl shadow p-4 sm:p-7 dark:bg-slate-900">
        <?= view('components/form/judul', ['judul' => $judul]) ?>

        <form action="<?= $modul_path . $form_action ?>" id="myForm" method="post">
            <?= csrf_field() ?>

            <input type="hidden" name="redirect_to" value="<?= $val('redirect_to') ?>">
            <input type="hidden" name="id_provinsi" id="id_provinsi" value="<?= $val('id_provinsi') ?>" required>
            <input type="hidden" name="id_kota_lokal" id="id_kota_lokal" value="<?= $val('id_kota_lokal') ?>" required>
            <input type="hidden" name="id_kec_lokal" id="id_kec_lokal" value="<?= $val('id_kec_lokal') ?>" required>
            <input type="hidden" name="id_desa_lokal" id="id_desa_lokal" value="<?= $val('id_desa_lokal') ?>" required>

            <!-- Kelurahan/Desa + Kecamatan -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="<?= $labelLeft ?>">Kelurahan / Desa <span class="text-red-600">*</span></label>
                <div class="flex gap-x-2 lg:w-1/4">
                    <input type="text" id="nama_desa" readonly required
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

            <!-- RW + RT -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="<?= $labelLeft ?>">RW <span class="text-red-600">*</span></label>
                <input type="text" name="rw" value="<?= $val('rw') ?>" required
                       maxlength="3" inputmode="numeric"
                       oninput="this.value = this.value.replace(/[^0-9]/g, '');"
                       class="<?= $editClass ?> lg:w-1/4">

                <label class="<?= $labelRight ?>">RT <span class="text-red-600">*</span></label>
                <input type="text" name="rt" value="<?= $val('rt') ?>" required
                       maxlength="3" inputmode="numeric"
                       oninput="this.value = this.value.replace(/[^0-9]/g, '');"
                       class="<?= $editClass ?> lg:w-1/4">
            </div>

            <!-- Alamat Lengkap -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="<?= $labelLeft ?>">Alamat Lengkap <span class="text-red-600">*</span></label>
                <textarea name="alamat_lengkap" rows="2" required
                          class="<?= $editClass ?> lg:w-1/4 resize-y"><?= $val('alamat_lengkap') ?></textarea>
            </div>

            <?= view('components/form/submit_button') ?>
        </form>
    </div>
</div>

<script>
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
</script>

<?= $this->endSection(); ?>