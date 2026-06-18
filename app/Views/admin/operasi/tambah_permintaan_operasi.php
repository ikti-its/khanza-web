<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<?= $this->include('components/modal/modalregistrasi') ?>
<?= $this->include('components/modal/modaldokter') ?>
<?= $this->include('components/modal/modaltindakanoperasi') ?>

<?php
$baseInput     = 'border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full dark:border-gray-600 dark:text-white';
$inputClass    = "$baseInput cursor-pointer bg-slate-50";
$readonlyClass = "$baseInput bg-gray-100 cursor-not-allowed";

$labelLeft     = 'block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4';
$labelRight    = 'block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5';
$btnClass      = 'inline-flex justify-center items-center p-2 text-sm font-medium text-white bg-blue-600 rounded-lg border border-transparent hover:bg-blue-700 focus:outline-none transition-all w-10 h-[38px] flex-shrink-0 shadow-sm';
$searchIcon    = '<svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>';

$isEdit = str_contains($judul, 'Ubah');
$isCito = filter_var($baris['is_cito'] ?? false, FILTER_VALIDATE_BOOL);
?>

<div class="max-w-[85rem] py-6 lg:py-3 px-8 mx-auto">
    <div class="bg-white rounded-xl shadow-sm p-4 sm:p-7 dark:bg-slate-900 border border-gray-100 dark:border-gray-800">
        <?= view('components/form/judul', ['judul' => $judul]) ?>

        <form action="<?= $modul_path . $form_action ?>" id="myForm" onsubmit="return validateForm()" method="post">
            <?= csrf_field() ?>

            <input type="hidden" name="nomor_reg"   id="nomor_reg"   value="<?= esc($baris['nomor_reg']   ?? '') ?>">
            <input type="hidden" name="id_dokter"   id="id_dokter"   value="<?= esc($baris['id_dokter']   ?? '') ?>">
            <input type="hidden" name="id_tindakan" id="id_tindakan" value="<?= esc($baris['id_tindakan'] ?? '') ?>">

            <!-- Baris 1: No. Registrasi | Nama Pasien -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="<?= $labelLeft ?>">
                    No. Registrasi <span class="text-red-500">*</span>
                </label>
                <div class="flex gap-x-2 lg:w-1/4">
                    <input type="text" id="nomor_reg_display"
                           value="<?= esc($baris['nomor_reg'] ?? '') ?>"
                           readonly required
                           placeholder="Klik cari registrasi..."
                           <?= $isEdit ? '' : 'onclick="open_modalRegistrasi()"' ?>
                           class="<?= $isEdit ? $readonlyClass : $inputClass ?>">
                    <?php if (!$isEdit): ?>
                        <button type="button" onclick="open_modalRegistrasi()" class="<?= $btnClass ?>">
                            <?= $searchIcon ?>
                        </button>
                    <?php endif; ?>
                </div>

                <label class="<?= $labelRight ?>">Nama Pasien</label>
                <input type="text" id="nama_pasien"
                       value="<?= esc($baris['nama_pasien'] ?? '') ?>"
                       readonly placeholder="Terisi otomatis..."
                       class="<?= $readonlyClass ?> lg:w-1/4">
            </div>

            <!-- Baris 2: Dokter Peminta | Nama Dokter -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="<?= $labelLeft ?>">
                    Dokter Peminta <span class="text-red-500">*</span>
                </label>
                <div class="flex gap-x-2 lg:w-1/4">
                    <input type="text" id="kode_dokter_display"
                           value="<?= esc($baris['kode_dokter'] ?? '') ?>"
                           readonly required
                           placeholder="Klik cari dokter..."
                           onclick="open_modalDokter()"
                           class="<?= $inputClass ?>">
                    <button type="button" onclick="open_modalDokter()" class="<?= $btnClass ?>">
                        <?= $searchIcon ?>
                    </button>
                </div>

                <label class="<?= $labelRight ?>">Nama Dokter</label>
                <input type="text" id="nama_dokter"
                       value="<?= esc($baris['nama_dokter'] ?? '') ?>"
                       readonly placeholder="Terisi otomatis..."
                       class="<?= $readonlyClass ?> lg:w-1/4">
            </div>

            <!-- Baris 3: Tindakan Operasi | Tanggal Minta -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="<?= $labelLeft ?>">
                    Tindakan Operasi <span class="text-red-500">*</span>
                </label>
                <div class="flex gap-x-2 lg:w-1/4">
                    <input type="text" id="nama_tindakan"
                           value="<?= esc($baris['nama_tindakan'] ?? '') ?>"
                           readonly required
                           placeholder="Klik cari tindakan..."
                           onclick="open_modalTindakanOperasi()"
                           class="<?= $inputClass ?>">
                    <button type="button" onclick="open_modalTindakanOperasi()" class="<?= $btnClass ?>">
                        <?= $searchIcon ?>
                    </button>
                </div>

                <label class="<?= $labelRight ?>">
                    Tanggal Minta <span class="text-red-500">*</span>
                </label>
                <input type="datetime-local" name="tanggal_minta" id="tanggal_minta"
                       value="<?= esc(substr(str_replace(' ', 'T', $baris['tanggal_minta'] ?? date('Y-m-d\TH:i')), 0, 16)) ?>"
                       required
                       class="<?= $inputClass ?> lg:w-1/4">
            </div>

            <!-- Baris 4: CITO -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="<?= $labelLeft ?>">CITO</label>
                <div class="flex items-center gap-x-3 lg:w-1/4">
                    <input type="hidden" name="is_cito" id="is_cito_value" value="<?= $isCito ? '1' : '0' ?>">
                    <button type="button" id="cito_btn"
                            onclick="toggleCito()"
                            class="inline-flex items-center rounded-full focus:outline-none"
                            style="width:52px; height:28px; background-color:<?= $isCito ? '#EF4444' : '#D1D5DB' ?>; padding:3px;">
                        <span id="cito_thumb"
                              class="inline-block bg-white rounded-full shadow"
                              style="width:22px; height:22px; margin-left:<?= $isCito ? '24px' : '0' ?>; transition:margin-left 0.2s;">
                        </span>
                    </button>
                    <span id="cito_label" class="text-sm text-gray-700 dark:text-gray-300">
                        <?= $isCito ? 'Ya' : 'Tidak' ?>
                    </span>
                </div>
            </div>

            <!-- Alert CITO -->
            <div id="cito_alert"
                 class="<?= $isCito ? '' : 'hidden' ?> mb-5 flex items-center gap-x-2 p-3 text-sm text-red-800 bg-red-50 rounded-lg border border-red-200 dark:bg-red-900/20 dark:text-red-400 dark:border-red-800">
                <svg class="w-4 h-4 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                </svg>
                Permintaan CITO akan diprioritaskan di antrian jadwal operasi.
            </div>

            <?= view('components/form/submit_button') ?>
        </form>
    </div>
</div>

<script>
    function autofillRegistrasi(item) {
        document.getElementById('nomor_reg_display').value   = item.nomor_reg   ?? '';
        document.getElementById('nama_pasien').value         = item.nama        ?? '';
        document.getElementById('nomor_reg').value           = item.nomor_reg   ?? '';
        document.getElementById('kode_dokter_display').value = item.kode_dokter ?? '';
        document.getElementById('nama_dokter').value         = item.nama_dokter ?? '';
        document.getElementById('id_dokter').value           = item.id_dokter   ?? '';
    }

    function autofillFields(item) {
        document.getElementById('kode_dokter_display').value = item.kode_dokter ?? '';
        document.getElementById('nama_dokter').value         = item.nama_dokter ?? '';
        document.getElementById('id_dokter').value           = item.id_dokter   ?? '';
    }

    function autofillTindakan(item) {
        document.getElementById('nama_tindakan').value = item.nama_tindakan ?? '';
        document.getElementById('id_tindakan').value   = item.id_tindakan   ?? '';
    }

    let _citoState = <?= $isCito ? 'true' : 'false' ?>;

    function toggleCito() {
        _citoState = !_citoState;
        const btn   = document.getElementById('cito_btn');
        const thumb = document.getElementById('cito_thumb');
        const label = document.getElementById('cito_label');
        const alert = document.getElementById('cito_alert');
        const input = document.getElementById('is_cito_value');

        btn.style.backgroundColor = _citoState ? '#EF4444' : '#D1D5DB';
        thumb.style.marginLeft    = _citoState ? '24px' : '0';
        label.textContent         = _citoState ? 'Ya' : 'Tidak';
        input.value               = _citoState ? '1' : '0';
        _citoState
            ? alert.classList.remove('hidden')
            : alert.classList.add('hidden');
    }

    function validateForm() {
        const validations = [
            { id: 'nomor_reg', msg: 'Silakan pilih registrasi pasien terlebih dahulu.' },
            { id: 'id_dokter', msg: 'Silakan pilih dokter peminta terlebih dahulu.' },
            { id: 'id_tindakan', msg: 'Silakan pilih tindakan operasi terlebih dahulu.' }
        ];

        for (let v of validations) {
            if (!document.getElementById(v.id).value) {
                alert(v.msg);
                return false;
            }
        }

        const btn = document.getElementById('submitButton');
        if (btn) { btn.disabled = true; btn.innerHTML = 'Menyimpan...'; }
        return true;
    }
</script>

<?= $this->endSection(); ?>