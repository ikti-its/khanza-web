<?php
$inputClass    = 'border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full dark:border-gray-600 dark:text-white cursor-pointer bg-slate-50';
$readonlyClass = 'border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full dark:border-gray-600 dark:text-white bg-gray-100 cursor-not-allowed';
$labelLeft     = 'block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4';
$labelRight    = 'block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5';
$btnClass      = 'inline-flex justify-center items-center p-2 text-sm font-medium text-white bg-blue-600 rounded-lg border border-transparent hover:bg-blue-700 focus:outline-none transition-all w-10 h-[38px] flex-shrink-0 shadow-sm';
$searchIcon    = '<svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>';
?>

<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<?= $this->include('components/modal/modalpermintaanlab') ?>
<?= $this->include('components/modal/modaldokter') ?>
<?= $this->include('components/modal/modalpetugas') ?>

<div class="max-w-[85rem] py-6 lg:py-3 px-8 mx-auto">
    <div class="bg-white rounded-xl shadow-sm p-4 sm:p-7 dark:bg-slate-900 border border-gray-100 dark:border-gray-800">
        <?= view('components/form/judul', ['judul' => $judul]) ?>

        <form action="<?= $modul_path . $form_action ?>" id="myForm" onsubmit="return validateForm()" method="post">
            <?= csrf_field() ?>

            <input type="hidden" name="id_permintaan_lab" id="id_permintaan_lab" value="<?= esc($baris['id_permintaan_lab'] ?? '') ?>">
            <input type="hidden" name="id_dokter_pj"      id="id_dokter_pj"      value="<?= esc($baris['id_dokter_pj']      ?? '') ?>">
            <input type="hidden" name="id_petugas_lab"    id="id_petugas_lab"    value="<?= esc($baris['id_petugas_lab']    ?? '') ?>">

            <?php $isEdit = str_contains($judul, 'Ubah'); ?>

            <!-- ══════════════════════════════════════════════ -->
            <!-- SECTION 1: Header                              -->
            <!-- ══════════════════════════════════════════════ -->

            <!-- No. Permintaan -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="<?= $labelLeft ?>">
                    No. Permintaan <span class="text-red-500">*</span>
                </label>
                <div class="flex gap-x-2 lg:w-1/4">
                    <input type="text" id="no_permintaan_display"
                           value="<?= esc($baris['no_permintaan'] ?? '') ?>"
                           readonly required
                           placeholder="Klik cari permintaan..."
                           <?= $isEdit ? '' : 'onclick="open_modalPermintaanLab(' . ID_KATEGORI_PA . ')"' ?>
                           class="<?= $isEdit ? $readonlyClass : $inputClass ?>">
                    <?php if (!$isEdit) : ?>
                        <button type="button" onclick="open_modalPermintaanLab(<?= ID_KATEGORI_PA ?>)" class="<?= $btnClass ?>">
                            <?= $searchIcon ?>
                        </button>
                    <?php endif; ?>
                </div>
            </div>

            <!-- No. Registrasi + Nama Pasien -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="<?= $labelLeft ?>">No. Registrasi</label>
                <input type="text" id="nomor_reg_display"
                       value="<?= esc($baris['nomor_reg'] ?? '') ?>"
                       readonly placeholder="Terisi otomatis..."
                       class="<?= $readonlyClass ?> lg:w-1/4">
                <label class="<?= $labelRight ?>">Nama Pasien</label>
                <input type="text" id="nama_pasien_display"
                       value="<?= esc($baris['nama_pasien'] ?? '') ?>"
                       readonly placeholder="Terisi otomatis..."
                       class="<?= $readonlyClass ?> lg:w-1/4">
            </div>

            <!-- Dokter Perujuk -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="<?= $labelLeft ?>">Dokter Perujuk</label>
                <input type="text" id="nama_dokter_perujuk_display"
                       value="<?= esc($baris['nama_dokter_perujuk'] ?? '') ?>"
                       readonly placeholder="Terisi otomatis..."
                       class="<?= $readonlyClass ?> lg:w-1/4">
            </div>

            <!-- Dokter PJ + Petugas -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="<?= $labelLeft ?>">
                    Dokter PJ <span class="text-red-500">*</span>
                </label>
                <div class="flex gap-x-2 lg:w-1/4">
                    <input type="text" id="nama_dokter_pj_display"
                           value="<?= esc($baris['nama_dokter_pj'] ?? '') ?>"
                           readonly required
                           placeholder="Klik cari dokter..."
                           onclick="open_modalDokterPJ()"
                           class="<?= $inputClass ?>">
                    <button type="button" onclick="open_modalDokterPJ()" class="<?= $btnClass ?>">
                        <?= $searchIcon ?>
                    </button>
                </div>
                <label class="<?= $labelRight ?>">
                    Petugas <span class="text-red-500">*</span>
                </label>
                <div class="flex gap-x-2 lg:w-1/4">
                    <input type="text" id="nama_petugas_display"
                           value="<?= esc($baris['nama_petugas'] ?? '') ?>"
                           readonly required
                           placeholder="Klik cari petugas..."
                           onclick="open_modalPetugas()"
                           class="<?= $inputClass ?>">
                    <button type="button" onclick="open_modalPetugas()" class="<?= $btnClass ?>">
                        <?= $searchIcon ?>
                    </button>
                </div>
            </div>

            <?= view('components/form/isian', ['konfig' => $konfig, 'baris' => $baris]) ?>

            <!-- ══════════════════════════════════════════════ -->
            <!-- SECTION 2: Hasil Pemeriksaan PA               -->
            <!-- ══════════════════════════════════════════════ -->
            <h4 class="text-sm font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wide mb-4 mt-8 border-b pb-2 dark:border-gray-700"></h4>

            <div id="hasilPaContainer">
                <div class="text-center py-6 text-gray-400 italic text-sm dark:text-gray-500">
                    Pilih permintaan terlebih dahulu untuk mengisi hasil pemeriksaan.
                </div>
            </div>

            <div class="flex justify-end gap-x-2 mt-8">
                <?= view('components/form/submit_button') ?>
            </div>
        </form>
    </div>
</div>

<script>
    const _itemTerpilih = <?= json_encode($item_terpilih ?? []) ?>;

    // ════════════════════════════════════════════
    // AUTOFILL dari modal permintaan
    // ════════════════════════════════════════════
    function autofillPermintaanLab(item) {
        document.getElementById('no_permintaan_display').value       = item.no_permintaan       ?? '';
        document.getElementById('nomor_reg_display').value           = item.nomor_reg           ?? '';
        document.getElementById('nama_pasien_display').value         = item.nama                ?? '';
        document.getElementById('nama_dokter_perujuk_display').value = item.nama_dokter_perujuk ?? '';
        document.getElementById('id_permintaan_lab').value           = item.id_permintaan       ?? '';
        fetchItemPa(item.id_permintaan);
    }

    // ════════════════════════════════════════════
    // AUTOFILL dokter PJ
    // ════════════════════════════════════════════
    let _modalDokterTarget = null;

    function open_modalDokterPJ() {
        _modalDokterTarget = 'pj';
        open_modalDokter();
    }

    function autofillFields(item) {
        if (_modalDokterTarget === 'pj') {
            document.getElementById('nama_dokter_pj_display').value = item.nama_dokter ?? '';
            document.getElementById('id_dokter_pj').value           = item.id_dokter   ?? '';
        }
        _modalDokterTarget = null;
    }

    // ════════════════════════════════════════════
    // AUTOFILL petugas
    // ════════════════════════════════════════════
    function autofillPetugas(item) {
        document.getElementById('nama_petugas_display').value = item.nama       ?? '';
        document.getElementById('id_petugas_lab').value       = item.id_petugas ?? '';
    }

    // ════════════════════════════════════════════
    // FETCH item PA dari permintaan
    // ════════════════════════════════════════════
    function fetchItemPa(idPermintaan) {
        const container = document.getElementById('hasilPaContainer');
        container.innerHTML = '<div class="text-center py-6 text-gray-400 text-sm">Memuat item pemeriksaan...</div>';

        fetch(`/laboratorium/permintaan-lab-pa/modal/list?id_permintaan=${idPermintaan}`)
            .then(res => res.json())
            .then(result => renderHasilPa(result.data || []))
            .catch(() => {
                container.innerHTML =
                    '<div class="text-center py-4 text-red-500 text-sm">Gagal memuat item pemeriksaan.</div>';
            });
    }

    // ════════════════════════════════════════════
    // RENDER form naratif PA per item
    // ════════════════════════════════════════════
    function renderHasilPa(items) {
        const container = document.getElementById('hasilPaContainer');

        if (!items || items.length === 0) {
            container.innerHTML =
                '<div class="text-center py-4 text-gray-400 italic text-sm">Tidak ada item pemeriksaan pada permintaan ini.</div>';
            return;
        }

        const taClass = 'w-full border border-gray-300 rounded-lg p-2 text-sm dark:bg-slate-900 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-emerald-400 focus:outline-none';
        const lblClass = 'block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1';

        let html = '';

        items.forEach((item, itemIdx) => {
            const p = `hasil[${itemIdx}]`;

            const dkAwal  = item.diagnosa_klinis ?? '';
            const mkAwal  = item.makroskopik     ?? '';
            const miAwal  = item.mikroskopik     ?? '';
            const ksAwal  = item.kesimpulan      ?? '';
            const knAwal  = item.kesan           ?? '';

            html += `
            <div class="mb-6 border rounded-xl overflow-hidden dark:border-gray-700">
                <!-- Header item -->
                <div class="flex items-center gap-x-3 px-4 py-3" style="background-color: #E6F2EF;">
                    <span class="text-sm font-bold text-gray-700 dark:text-gray-200 font-mono">${escHtml(item.kode_periksa)}</span>
                    <span class="text-sm font-semibold text-gray-800 dark:text-white">${escHtml(item.nama_item)}</span>
                    <input type="hidden" name="${p}[id_permintaan_pa_item]" value="${item.id_permintaan_pa_item}">
                </div>

                <!-- Form naratif -->
                <div class="p-5 grid grid-cols-1 gap-y-4">
                    <div>
                        <label class="${lblClass}">Diagnosa Klinis <span class="text-red-500">*</span></label>
                        <textarea name="${p}[diagnosa_klinis]" rows="3"
                                  placeholder="Tuliskan diagnosa klinis..."
                                  class="${taClass}">${escHtml(dkAwal)}</textarea>
                    </div>
                    <div>
                        <label class="${lblClass}">Makroskopik <span class="text-red-500">*</span></label>
                        <textarea name="${p}[makroskopik]" rows="4"
                                  placeholder="Tuliskan deskripsi makroskopik..."
                                  class="${taClass}">${escHtml(mkAwal)}</textarea>
                    </div>
                    <div>
                        <label class="${lblClass}">Mikroskopik <span class="text-red-500">*</span></label>
                        <textarea name="${p}[mikroskopik]" rows="4"
                                  placeholder="Tuliskan deskripsi mikroskopik..."
                                  class="${taClass}">${escHtml(miAwal)}</textarea>
                    </div>
                    <div>
                        <label class="${lblClass}">Kesimpulan <span class="text-red-500">*</span></label>
                        <textarea name="${p}[kesimpulan]" rows="3"
                                  placeholder="Tuliskan kesimpulan..."
                                  class="${taClass}">${escHtml(ksAwal)}</textarea>
                    </div>
                    <div>
                        <label class="${lblClass}">Kesan <span class="text-gray-400 text-xs">(opsional)</span></label>
                        <textarea name="${p}[kesan]" rows="2"
                                  placeholder="Tuliskan kesan (opsional)..."
                                  class="${taClass}">${escHtml(knAwal)}</textarea>
                    </div>
                </div>
            </div>`;
        });

        container.innerHTML = html;
    }

    // ════════════════════════════════════════════
    // HELPER
    // ════════════════════════════════════════════
    function escHtml(str) {
        if (str == null) return '';
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }

    // ════════════════════════════════════════════
    // INIT
    // ════════════════════════════════════════════
    document.addEventListener('DOMContentLoaded', function () {
        if (_itemTerpilih.length > 0) {
            renderHasilPa(_itemTerpilih);
        }
    });

    // ════════════════════════════════════════════
    // VALIDASI
    // ════════════════════════════════════════════
    function validateForm() {
        if (!document.getElementById('id_permintaan_lab').value) {
            alert('Silakan pilih permintaan laboratorium terlebih dahulu.');
            return false;
        }
        if (!document.getElementById('id_dokter_pj').value) {
            alert('Silakan pilih Dokter PJ.');
            return false;
        }
        if (!document.getElementById('id_petugas_lab').value) {
            alert('Silakan pilih Petugas Lab.');
            return false;
        }

        const requiredFields = document.querySelectorAll('select[required], input[required]');
        for (const field of requiredFields) {
            if (!field.value) {
                alert('Isi semua field yang wajib diisi.');
                field.focus();
                return false;
            }
        }

        const submitButton = document.getElementById('submitButton');
        if (submitButton) {
            submitButton.disabled = true;
            submitButton.innerHTML = 'Menyimpan...';
        }
        return true;
    }
</script>

<?= $this->endSection(); ?>