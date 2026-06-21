<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>
<?= $this->include('components/modal/modaldokter') ?>
<?= $this->include('components/modal/modalpasienrole') ?>

<?php
$baseInput     = 'border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full dark:border-gray-600 dark:text-white';
$inputClass    = "$baseInput cursor-pointer bg-slate-50";
$readonlyClass = "$baseInput bg-gray-100 cursor-not-allowed lg:w-1/4";
$labelLeft     = 'block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4';
$labelRight    = 'block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5';
$btnClass      = 'inline-flex justify-center items-center p-2 text-sm font-medium text-white bg-blue-600 rounded-lg border border-transparent hover:bg-blue-700 focus:outline-none transition-all w-10 h-[38px] flex-shrink-0 shadow-sm';
$searchIcon    = '<svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>';
?>

<div class="max-w-[85rem] py-6 lg:py-3 px-8 mx-auto">
    <div class="bg-white rounded-xl shadow p-4 sm:p-7 dark:bg-slate-900">
        <?= view('components/form/judul', ['judul' => $judul]) ?>

        <form action="<?= $modul_path . $form_action ?>" id="myForm" onsubmit="return validateForm()" method="post">
            <?= csrf_field() ?>

            <input type="hidden" name="nomor_reg"  value="<?= esc($baris['nomor_reg']) ?>">
            <input type="hidden" name="nomor_rawat" value="<?= esc($baris['nomor_rawat']) ?>">
            <input type="hidden" name="id_pasien" id="id_pasien" value="<?= esc((string) ($baris['id_pasien'] ?? '')) ?>">
            <input type="hidden" name="id_dokter" id="id_dokter" value="">

            <div class="mb-5 sm:block md:flex items-center">
                <label class="<?= $labelLeft ?>">Nomor Registrasi</label>
                <input type="text" value="<?= esc($baris['nomor_reg']) ?>" class="<?= $readonlyClass ?>" readonly>

                <label class="<?= $labelRight ?>">Nomor Rawat</label>
                <input type="text" value="<?= esc($baris['nomor_rawat']) ?>" class="<?= $readonlyClass ?>" readonly>
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="<?= $labelLeft ?>">No. Rekam Medis <span class="text-red-600">*</span></label>
                <div class="flex gap-x-2 lg:w-1/4">
                    <input type="text" id="no_rm_display" readonly required
                           value="<?= esc($data_pasien['nomor_rm'] ?? '') ?>"
                           placeholder="Klik cari pasien..."
                           onclick="open_modalPasienRole()" class="<?= $inputClass ?>">
                    <button type="button" onclick="open_modalPasienRole()" class="<?= $btnClass ?>"><?= $searchIcon ?></button>
                </div>
                <label class="<?= $labelRight ?>">Nama Pasien</label>
                <input type="text" id="nm_pasien" readonly
                       value="<?= esc($data_pasien['nama'] ?? '') ?>"
                       placeholder="Terisi otomatis..." class="<?= $readonlyClass ?>">
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="<?= $labelLeft ?>">Kode Dokter<span class="text-red-600">*</span></label>
                <div class="flex gap-x-2 lg:w-1/4">
                    <input type="text" id="kode_dokter" readonly required placeholder="Klik cari dokter..."
                           onclick="open_modalDokter()" class="<?= $inputClass ?>">
                    <button type="button" onclick="open_modalDokter()" class="<?= $btnClass ?>"><?= $searchIcon ?></button>
                </div>
                <label class="<?= $labelRight ?>">Nama Dokter</label>
                <input type="text" id="nama_dokter" readonly placeholder="Terisi otomatis..." class="<?= $readonlyClass ?>">
            </div>

            <?= view('components/form/isian', ['konfig' => $konfig, 'baris' => $baris]) ?>

            <div class="flex justify-end gap-x-2 mt-8 border-t border-gray-100 pt-4 dark:border-gray-700">
                <?= view('components/form/submit_button') ?>
            </div>
        </form>
    </div>
</div>

<?php
$included_modals = [];
foreach ($konfig as $field) {
    if (!isset($field[5]['modal'])) continue;
    $modal_name = $field[5]['modal'];
    if (in_array($modal_name, $included_modals, true)) continue;
    $included_modals[] = $modal_name;
    echo view('components/modal/' . $modal_name);
}
?>

<script>
function autofillFields(item) {
    document.getElementById('id_dokter').value   = item.id_dokter   ?? '';
    document.getElementById('kode_dokter').value = item.kode_dokter ?? '';
    document.getElementById('nama_dokter').value = item.nama_dokter ?? '';
}

function validateForm() {
    if (!document.getElementById('id_pasien').value) {
        alert('Pilih pasien terlebih dahulu.');
        return false;
    }
    if (!document.getElementById('id_dokter').value) {
        alert('Pilih dokter terlebih dahulu.');
        return false;
    }
    var requiredFields = document.querySelectorAll('select[required], input[required]');
    for (var i = 0; i < requiredFields.length; i++) {
        if (!requiredFields[i].value) {
            alert("Isi semua field.");
            return false;
        }
    }
    var submitButton = document.getElementById('submitButton');
    submitButton.setAttribute('disabled', true);
    submitButton.innerHTML = 'Menyimpan...';
    return true;
}
</script>
<?= $this->endSection(); ?>
