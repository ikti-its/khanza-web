<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>
<?= $this->include('components/modal/modalpasienrole') ?>
<?= $this->include('components/modal/modalpetugas') ?>

<?php
$baseInput     = 'border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full dark:border-gray-600 dark:text-white';
$inputClass    = "$baseInput cursor-pointer bg-slate-50";
$readonlyClass = "$baseInput bg-gray-100 cursor-not-allowed lg:w-1/4";

$labelLeft  = 'block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4';
$labelRight = 'block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5';
$btnClass   = 'inline-flex justify-center items-center p-2 text-sm font-medium text-white bg-blue-600 rounded-lg border border-transparent hover:bg-blue-700 focus:outline-none transition-all w-10 h-[38px] flex-shrink-0 shadow-sm';
$searchIcon = '<svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>';

$konfigTanpaRM = array_values(array_filter($konfig, fn($f) => !in_array($f[2], ['no_rm', 'id_skrining', 'id_petugas'])));
?>

<div class="max-w-[85rem] py-6 lg:py-3 px-8 mx-auto">
    <div class="bg-white rounded-xl shadow p-4 sm:p-7 dark:bg-slate-900">
        <?= view('components/form/judul', ['judul' => $judul]) ?>

       <form action="<?= $modul_path . $form_action ?>" id="myForm" onsubmit="return validateForm()" method="post">
            <?= csrf_field() ?>

            <input type="hidden" name="no_rm" id="no_rm" value="<?= esc($baris['no_rm'] ?? '') ?>" required>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="<?= $labelLeft ?>">No. Rekam Medis <span class="text-red-600">*</span></label>
                <div class="w-full lg:w-1/4 flex gap-x-2">
                    <input type="text" id="no_rm_display" value="<?= esc($baris['no_rm'] ?? '') ?>" readonly required placeholder="Klik cari pasien..." onclick="open_modalPasienRole()" class="<?= $inputClass ?>">
                    <button type="button" onclick="open_modalPasienRole()" class="<?= $btnClass ?>"><?= $searchIcon ?></button>
                </div>
                
                <label class="<?= $labelRight ?>">Nama Pasien</label>
                <input type="text" id="nm_pasien" value="<?= esc($data_pasien['nama'] ?? '') ?>" readonly placeholder="Terisi otomatis..." class="<?= $readonlyClass ?>">
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="<?= $labelLeft ?>">Tanggal Lahir</label>
                <input type="text" id="tgl_lahir" value="<?= esc($data_pasien['tanggal_lahir'] ?? '') ?>" readonly placeholder="Terisi otomatis..." class="<?= $readonlyClass ?>">
                
                <label class="<?= $labelRight ?>">Jenis Kelamin</label>
                <input type="text" id="jenis_kelamin" value="<?= esc($data_pasien['jenis_kelamin'] ?? '') ?>" readonly placeholder="Terisi otomatis..." class="<?= $readonlyClass ?>">
            </div>
                
            <div class="mb-5 sm:block md:flex items-center">
                <label class="<?= $labelLeft ?>">Petugas <span class="text-red-600">*</span></label>
                <div class="w-full lg:w-1/4 flex gap-x-2">
                    <input type="hidden" name="id_petugas" id="id_petugas" value="<?= esc($baris['id_petugas'] ?? '') ?>">
                    <input type="text" name="nama_petugas" id="nama_petugas" value="<?= esc($baris['nama'] ?? '') ?>" readonly placeholder="Klik cari petugas..." onclick="open_modalPetugas()" class="<?= $inputClass ?>">
                    <button type="button" onclick="open_modalPetugas()" class="<?= $btnClass ?>"><?= $searchIcon ?></button>
                </div>
            </div>

            <?= view('components/form/isian', ['konfig' => $konfigTanpaRM, 'baris' => $baris]) ?>

            <?= view('components/form/submit_button') ?>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const tglInput = document.querySelector('[name="tgl_skrining"]');
        if (tglInput) tglInput.max = new Date().toISOString().split('T')[0];
    });

    function autofillPetugas(item) {
        document.getElementById('id_petugas').value   = item.id_petugas ?? '';
        document.getElementById('nama_petugas').value = item.nama       ?? '';
    }

    function validateForm() {
        if (!document.getElementById('no_rm').value) {
            alert('Silakan pilih pasien terlebih dahulu melalui modal pencarian.');
            return false;
        }

        if (!document.getElementById('id_petugas').value) {
            alert('Silakan pilih petugas terlebih dahulu.');
            return false;
        }

        const requiredFields = document.querySelectorAll('select[required], input[required]');
        for (const field of requiredFields) {
            if (!field.value) {
                alert('Isi semua field yang wajib diisi.');
                return false;
            }
        }

        const btn = document.getElementById('submitButton');
        if (btn) { btn.disabled = true; btn.innerHTML = 'Menyimpan...'; }
        return true;
    }
</script>

<?= $this->endSection(); ?>