<?php
/**
 * Partial: header_permintaan_lab.php
 *
 * Dipakai oleh tambah_permintaan_pk.php, tambah_permintaan_pa.php,
 * dan tambah_permintaan_mb.php via:
 *   <?= $this->include('admin/laboratorium/header_permintaan_lab') ?>
 *
 * Variabel yang wajib tersedia dari controller:
 *   - $no_permintaan  : string  — nomor permintaan yang sudah di-generate
 *   - $konfig         : array   — field untuk komponen isian (tgl, indikasi, info, status)
 *   - $baris          : array   — data existing (kosong [] untuk tambah, terisi untuk ubah)
 *   - $judul          : string
 *   - $modul_path     : string
 *   - $form_action    : string
 */

$inputClass    = 'border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full dark:border-gray-600 dark:text-white cursor-pointer bg-slate-50';
$readonlyClass = 'border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full dark:border-gray-600 dark:text-white bg-gray-100 cursor-not-allowed';
$labelLeft     = 'block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4';
$labelRight    = 'block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5';
$btnClass      = 'inline-flex justify-center items-center p-2 text-sm font-medium text-white bg-blue-600 rounded-lg border border-transparent hover:bg-blue-700 focus:outline-none transition-all w-10 h-[38px] flex-shrink-0 shadow-sm';
$searchIcon    = '<svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>';

$isEdit = str_contains($judul, 'Ubah');
?>

<input type="hidden" name="no_permintaan"       id="no_permintaan"       value="<?= esc($no_permintaan ?? '') ?>">
<input type="hidden" name="nomor_reg"           id="nomor_reg"           value="<?= esc($baris['nomor_reg'] ?? '') ?>">
<input type="hidden" name="id_dokter_perujuk" id="id_dokter_perujuk" value="<?= esc($baris['id_dokter_perujuk'] ?? '') ?>">

<!-- Baris 1: No. Permintaan | No. Registrasi -->
<div class="mb-5 sm:block md:flex items-center">
    <label class="<?= $labelLeft ?>">No. Permintaan</label>
    <input type="text" value="<?= esc($no_permintaan ?? '') ?>"
           readonly
           class="<?= $readonlyClass ?> lg:w-1/4">

    <label class="<?= $labelRight ?>">
        No. Registrasi <span class="text-red-500">*</span>
    </label>
    <div class="flex flex-col lg:w-1/4">
        <div class="flex gap-x-2">
            <input type="text" id="nomor_reg_display"
                   value="<?= esc($baris['nomor_reg'] ?? '') ?>"
                   readonly
                   placeholder="Klik cari registrasi..."
                   <?= $isEdit ? '' : 'onclick="open_modalRegistrasi()"' ?>
                   class="<?= $isEdit ? $readonlyClass : $inputClass ?>">
            <?php if (!$isEdit) : ?>
                <button type="button" onclick="open_modalRegistrasi()" class="<?= $btnClass ?>">
                    <?= $searchIcon ?>
                </button>
            <?php endif; ?>
        </div>
        <p id="err_nomor_reg_display" class="hidden text-red-500 text-xs mt-1"></p>
    </div>
</div>

<!-- Baris 2: No. Rekam Medis | Nama Pasien -->
<div class="mb-5 sm:block md:flex items-center">
    <label class="<?= $labelLeft ?>">No. Rekam Medis</label>
    <input type="text" id="nomor_rm_display"
           value="<?= esc($baris['nomor_rm'] ?? '') ?>"
           readonly placeholder="Terisi otomatis..."
           class="<?= $readonlyClass ?> lg:w-1/4">

    <label class="<?= $labelRight ?>">Nama Pasien</label>
    <input type="text" id="nama_pasien"
           value="<?= esc($baris['nama'] ?? '') ?>"
           readonly placeholder="Terisi otomatis..."
           class="<?= $readonlyClass ?> lg:w-1/4">
</div>

<!-- Baris 3: Kode Dokter Perujuk | Nama Dokter Perujuk -->
<div class="mb-5 sm:block md:flex items-center">
    <label class="<?= $labelLeft ?>">
        Kode Dokter Perujuk <span class="text-red-500">*</span>
    </label>
    <div class="flex flex-col lg:w-1/4">
        <div class="flex gap-x-2">
            <input type="text" id="kode_dokter"
                   value="<?= esc($baris['kode_dokter'] ?? '') ?>"
                   readonly
                   placeholder="Klik cari dokter..."
                   onclick="open_modalDokter()"
                   class="<?= $inputClass ?>">
            <button type="button" onclick="open_modalDokter()" class="<?= $btnClass ?>">
                <?= $searchIcon ?>
            </button>
        </div>
        <p id="err_kode_dokter" class="hidden text-red-500 text-xs mt-1"></p>
    </div>

    <label class="<?= $labelRight ?>">Nama Dokter Perujuk</label>
    <input type="text" id="nama_dokter"
           value="<?= esc($baris['nama_dokter'] ?? '') ?>"
           readonly placeholder="Terisi otomatis..."
           class="<?= $readonlyClass ?> lg:w-1/4">
</div>

<!-- Field manual: tgl_permintaan, indikasi_klinis, informasi_tambahan, id_status_permintaan -->
<?= view('components/form/isian', ['konfig' => $konfig, 'baris' => $baris]) ?>