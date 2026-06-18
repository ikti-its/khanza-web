<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<?= $this->include('components/modal/modaldokter') ?>

<?php
$baseInput     = 'border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full dark:border-gray-600 dark:text-white';
$inputClass    = "$baseInput cursor-pointer bg-slate-50";
$readonlyClass = "$baseInput bg-gray-100 cursor-not-allowed lg:w-1/4";
$textareaClass = "$baseInput bg-slate-50";

$labelLeft  = 'block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4';
$labelRight = 'block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5';
$btnClass   = 'inline-flex justify-center items-center p-2 text-sm font-medium text-white bg-blue-600 rounded-lg border border-transparent hover:bg-blue-700 focus:outline-none transition-all w-10 h-[38px] flex-shrink-0';
$searchIcon = '<svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>';

$instruksiFields = [
    ['name' => 'instruksi_rawat',         'label' => 'Instruksi Rawat'],
    ['name' => 'instruksi_cairan',        'label' => 'Instruksi Cairan'],
    ['name' => 'instruksi_antibiotik',    'label' => 'Instruksi Antibiotik'],
    ['name' => 'instruksi_analgetik',     'label' => 'Instruksi Analgetik'],
    ['name' => 'instruksi_medikamentosa', 'label' => 'Instruksi Medikamentosa'],
    ['name' => 'instruksi_diet',          'label' => 'Instruksi Diet'],
    ['name' => 'instruksi_penunjang',     'label' => 'Instruksi Penunjang'],
    ['name' => 'instruksi_transfusi',     'label' => 'Instruksi Transfusi'],
    ['name' => 'instruksi_lainnya',       'label' => 'Instruksi Lainnya'],
];
?>

<div class="max-w-[85rem] py-6 lg:py-3 px-8 mx-auto">
    <div class="bg-white rounded-xl shadow-sm p-4 sm:p-7 dark:bg-slate-900 border border-gray-100 dark:border-gray-800">
        <?= view('components/form/judul', ['judul' => $judul]) ?>

        <form action="<?= $modul_path . $form_action ?>" id="myForm" onsubmit="return validateForm()" method="post">
            <?= csrf_field() ?>
            <input type="hidden" name="id_jadwal"       value="<?= esc((string) ($baris['id_jadwal']       ?? '')) ?>">
            <input type="hidden" name="id_dokter_bedah" id="id_dokter_bedah" value="<?= esc((string) ($baris['id_dokter_bedah'] ?? '')) ?>">

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
                <input type="text" value="<?= esc($jadwal['nama_tindakan'] ?? '-') ?>"
                       readonly class="<?= $readonlyClass ?> lg:w-1/4">
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="<?= $labelLeft ?>">Dokter Bedah <span class="text-red-500">*</span></label>
                <div class="flex gap-x-2 lg:w-1/4">
                    <input type="text" id="nama_dokter_bedah"
                           value="<?= esc($baris['nama_dokter_bedah'] ?? '') ?>"
                           readonly required placeholder="Klik cari dokter..."
                           onclick="open_modalDokter()"
                           class="<?= $inputClass ?>">
                    <button type="button" onclick="open_modalDokter()" class="<?= $btnClass ?>">
                        <?= $searchIcon ?>
                    </button>
                </div>
            </div>

            <!-- ── Instruksi ── -->
            <?php foreach ($instruksiFields as $field): ?>
            <div class="mb-5">
                <label class="block mb-2 text-sm text-gray-900 dark:text-white"><?= $field['label'] ?> <span class="text-red-500">*</span></label>
                <textarea name="<?= $field['name'] ?>" rows="2" required class="<?= $textareaClass ?>"><?= esc($baris[$field['name']] ?? '') ?></textarea>
            </div>
            <?php endforeach; ?>

            <?= view('components/form/submit_button') ?>
        </form>
    </div>
</div>

<script>
    function autofillFields(item) {
        document.getElementById('id_dokter_bedah').value   = item.id_dokter   ?? '';
        document.getElementById('nama_dokter_bedah').value = item.nama_dokter ?? '';
    }

    function validateForm() {
        if (!document.getElementById('id_dokter_bedah').value) {
            alert('Silakan pilih dokter bedah terlebih dahulu.');
            return false;
        }
        const btn = document.getElementById('submitButton');
        if (btn) { btn.disabled = true; btn.innerHTML = 'Menyimpan...'; }
        return true;
    }
</script>

<?= $this->endSection(); ?>
