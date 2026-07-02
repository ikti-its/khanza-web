<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<?= $this->include('components/modal/modaldokter') ?>
<?= $this->include('components/modal/modalruangan') ?>

<?php
$baseInput     = 'border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full dark:border-gray-600 dark:text-white';
$inputClass    = "$baseInput cursor-pointer bg-slate-50";
$readonlyClass = "$baseInput bg-gray-100 cursor-not-allowed";

$labelLeft     = 'block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4';
$labelRight    = 'block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5';
$btnClass      = 'inline-flex justify-center items-center p-2 text-sm font-medium text-white bg-blue-600 rounded-lg border border-transparent hover:bg-blue-700 focus:outline-none transition-all w-10 h-[38px] flex-shrink-0 shadow-sm';

$searchIcon    = '<svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>';

$isCito        = filter_var($baris['is_cito'] ?? false, FILTER_VALIDATE_BOOLEAN);
?>

<div class="max-w-[85rem] py-6 lg:py-3 px-8 mx-auto">
    <div class="bg-white rounded-xl shadow-sm p-4 sm:p-7 dark:bg-slate-900 border border-gray-100 dark:border-gray-800">
        <?= view('components/form/judul', ['judul' => $judul]) ?>

        <form action="<?= $modul_path . $form_action ?>" id="myForm" onsubmit="return validateForm()" method="post">
            <?= csrf_field() ?>

            <input type="hidden" name="id_ruangan"         id="id_ruangan"         value="<?= esc($baris['id_ruangan']         ?? '') ?>">
            <input type="hidden" name="id_dokter_bedah"    id="id_dokter_bedah"    value="<?= esc($baris['id_dokter_bedah']    ?? '') ?>">
            <input type="hidden" name="id_dokter_anestesi" id="id_dokter_anestesi" value="<?= esc($baris['id_dokter_anestesi'] ?? '') ?>">

            <?php if (!empty($baris['nomor_operasi'])): ?>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="<?= $labelLeft ?>">No. Operasi</label>
                <input type="text" value="<?= esc($baris['nomor_operasi']) ?>" readonly class="<?= $readonlyClass ?> lg:w-1/4 font-mono">
            </div>
            <?php endif; ?>

            <?php
            $fields = [
                ['No. Registrasi', 'nomor_reg', 'Nama Pasien', 'nama_pasien'],
                ['Dokter Peminta', 'nama_dokter_peminta', 'Tanggal Minta', 'tanggal_minta'],
                ['Tindakan Operasi', 'nama_tindakan', null, null]
            ];
            foreach ($fields as $f): ?>
                <div class="mb-5 sm:block md:flex items-center">
                    <label class="<?= $labelLeft ?>"><?= $f[0] ?></label>
                    <input type="text" value="<?= esc($baris[$f[1]] ?? '') ?>" readonly class="<?= $readonlyClass ?> lg:w-1/4">
                    
                    <?php if ($f[2]): ?>
                        <label class="<?= $labelRight ?>"><?= $f[2] ?></label>
                        <input type="text" value="<?= esc($baris[$f[3]] ?? '') ?>" readonly class="<?= $readonlyClass ?> lg:w-1/4">
                    <?php elseif ($isCito): ?>
                        <label class="<?= $labelRight ?>">CITO</label>
                        <div class="lg:w-1/4">
                            <span class="inline-flex items-center gap-x-1.5 py-1 px-3 text-sm font-semibold rounded-lg bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">
                                <svg class="w-3.5 h-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                                </svg>
                                CITO
                            </span>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>

            <!-- Baris 4: Ruangan | Dokter Bedah -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="<?= $labelLeft ?>">
                    Ruangan <span class="text-red-500">*</span>
                </label>
                <div class="flex gap-x-2 lg:w-1/4">
                    <input type="text" id="nama_ruangan"
                           value="<?= esc($baris['nama_ruangan'] ?? '') ?>"
                           readonly required
                           placeholder="Klik cari ruangan..."
                           onclick="open_modalRuangan()"
                           class="<?= $inputClass ?>">
                    <button type="button" onclick="open_modalRuangan()" class="<?= $btnClass ?>">
                        <?= $searchIcon ?>
                    </button>
                </div>

                <label class="<?= $labelRight ?>">
                    Dokter Bedah <span class="text-red-500">*</span>
                </label>
                <div class="flex gap-x-2 lg:w-1/4">
                    <input type="text" id="nama_dokter_bedah"
                           value="<?= esc($baris['nama_dokter_bedah'] ?? '') ?>"
                           readonly required
                           placeholder="Klik cari dokter bedah..."
                           onclick="bukaDokter('bedah')"
                           class="<?= $inputClass ?>">
                    <button type="button" onclick="bukaDokter('bedah')" class="<?= $btnClass ?>">
                        <?= $searchIcon ?>
                    </button>
                </div>
            </div>

            <!-- Baris 4: Dokter Anestesi | Tanggal Operasi -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="<?= $labelLeft ?>">
                    Dokter Anestesi <span class="text-red-500">*</span>
                </label>
                <div class="flex gap-x-2 lg:w-1/4">
                    <input type="text" id="nama_dokter_anestesi"
                           value="<?= esc($baris['nama_dokter_anestesi'] ?? '') ?>"
                           readonly required
                           placeholder="Klik cari dokter anestesi..."
                           onclick="bukaDokter('anestesi')"
                           class="<?= $inputClass ?>">
                    <button type="button" onclick="bukaDokter('anestesi')" class="<?= $btnClass ?>">
                        <?= $searchIcon ?>
                    </button>
                </div>

                <label class="<?= $labelRight ?>">
                    Tanggal Operasi <span class="text-red-500">*</span>
                </label>
                <input type="date" name="tanggal" id="tanggal"
                       value="<?= esc($baris['tanggal'] ?? '') ?>"
                       required class="<?= $inputClass ?> lg:w-1/4">
            </div>

            <!-- Baris 5: Waktu Mulai | Waktu Selesai -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="<?= $labelLeft ?>">
                    Waktu Mulai <span class="text-red-500">*</span>
                </label>
                <input type="time" name="waktu_mulai" id="waktu_mulai"
                       value="<?= esc($baris['waktu_mulai'] ?? '') ?>"
                       required class="<?= $inputClass ?> lg:w-1/4">
                
                <label class="<?= $labelRight ?>">
                    Waktu Selesai <span class="text-red-500">*</span>
                </label>
                <input type="time" name="waktu_selesai" id="waktu_selesai"
                       value="<?= esc($baris['waktu_selesai'] ?? '') ?>"
                       required class="<?= $inputClass ?> lg:w-1/4">
            </div>

            <?= view('components/form/submit_button') ?>
        </form>
    </div>
</div>

<script>
    let _dokterTarget = null;

    function bukaDokter(target) {
        _dokterTarget = target;
        open_modalDokter();
    }

    function autofillRuangan(item) {
        document.getElementById('nama_ruangan').value = item.nama_ruangan ?? '';
        document.getElementById('id_ruangan').value   = item.id_ruangan   ?? '';
    }

    function autofillFields(item) {
        if (_dokterTarget === 'bedah') {
            document.getElementById('nama_dokter_bedah').value = item.nama_dokter ?? '';
            document.getElementById('id_dokter_bedah').value   = item.id_dokter   ?? '';
        } else if (_dokterTarget === 'anestesi') {
            document.getElementById('nama_dokter_anestesi').value = item.nama_dokter ?? '';
            document.getElementById('id_dokter_anestesi').value   = item.id_dokter   ?? '';
        }
        _dokterTarget = null;
    }

    function validateForm() {
        if (!document.getElementById('id_ruangan').value) {
            alert('Silakan pilih ruangan terlebih dahulu.');
            return false;
        }
        if (!document.getElementById('id_dokter_bedah').value) {
            alert('Silakan pilih dokter bedah terlebih dahulu.');
            return false;
        }
        if (!document.getElementById('id_dokter_anestesi').value) {
            alert('Silakan pilih dokter anestesi terlebih dahulu.');
            return false;
        }
        const submitButton = document.getElementById('submitButton');
        if (submitButton) {
            submitButton.disabled  = true;
            submitButton.innerHTML = 'Menyimpan...';
        }
        return true;
    }
</script>

<?= $this->endSection(); ?>