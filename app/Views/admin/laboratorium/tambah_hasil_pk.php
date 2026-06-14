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
                           <?= $isEdit ? '' : 'onclick="open_modalPermintaanLab(' . ID_KATEGORI_PK . ')"' ?>
                           class="<?= $isEdit ? $readonlyClass : $inputClass ?>">
                    <?php if (!$isEdit) : ?>
                        <button type="button" onclick="open_modalPermintaanLab(<?= ID_KATEGORI_PK ?>)" class="<?= $btnClass ?>">
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

            <!-- tgl_jam_hasil + id_kategori_usia dari konfig -->
            <?= view('components/form/isian', ['konfig' => $konfig, 'baris' => $baris]) ?>

            <!-- ══════════════════════════════════════════════ -->
            <!-- SECTION 2: Hasil Pemeriksaan PK               -->
            <!-- ══════════════════════════════════════════════ -->
            <h4 class="text-sm font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wide mb-4 mt-8 border-b pb-2 dark:border-gray-700"></h4>

            <div id="hasilPkContainer">
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
        fetchItemPk(item.id_permintaan);
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
    // FETCH item PK dari permintaan
    // ════════════════════════════════════════════
    function fetchItemPk(idPermintaan) {
        const container = document.getElementById('hasilPkContainer');
        container.innerHTML = '<div class="text-center py-6 text-gray-400 text-sm">Memuat item pemeriksaan...</div>';

        fetch(`/laboratorium/permintaan-lab-pk/modal/list?id_permintaan=${idPermintaan}`)
            .then(res => res.json())
            .then(result => renderHasilPk(result.data || []))
            .catch(() => {
                container.innerHTML =
                    '<div class="text-center py-4 text-red-500 text-sm">Gagal memuat item pemeriksaan.</div>';
            });
    }

    // ════════════════════════════════════════════
    // RENDER tabel hasil PK
    // ════════════════════════════════════════════
    function renderHasilPk(items) {
        const container = document.getElementById('hasilPkContainer');

        if (!items || items.length === 0) {
            container.innerHTML =
                '<div class="text-center py-4 text-gray-400 italic text-sm">Tidak ada item pemeriksaan pada permintaan ini.</div>';
            return;
        }

        let html = '';

        items.forEach((item, itemIdx) => {
            const params = item.parameter ?? [];
            const pBase  = `hasil[${itemIdx}]`;

            html += `
            <div class="mb-6 border rounded-xl overflow-hidden dark:border-gray-700">
                <!-- Header item pemeriksaan -->
                <div class="flex items-center gap-x-3 px-4 py-3" style="background-color: #E6F2EF;">
                    <span class="text-sm font-bold text-gray-700 dark:text-gray-200 font-mono">${escHtml(item.kode_periksa)}</span>
                    <span class="text-sm font-semibold text-gray-800 dark:text-white">${escHtml(item.nama_item)}</span>
                    <input type="hidden" name="${pBase}[id_permintaan_pk_item]" value="${item.id_permintaan_pk_item}">
                </div>

                <!-- Tabel parameter -->
                <table class="w-full text-sm text-gray-700 dark:text-gray-300 table-fixed">
                    <colgroup>
                        <col class="w-[5%]">
                        <col class="w-[28%]">
                        <col class="w-[12%]">
                        <col class="w-[25%]">
                        <col class="w-[15%]">
                        <col class="w-[15%]">
                    </colgroup>
                    <thead style="background-color: #E6F2EF;" class="text-gray-800 font-semibold text-base">
                        <tr>
                            <th class="p-4 border text-center text-base"></th>
                            <th class="p-4 border text-center text-base">Parameter</th>
                            <th class="p-4 border text-center text-base">Satuan</th>
                            <th class="p-4 border text-center text-base">Nilai Rujukan</th>
                            <th class="p-4 border text-center text-base">Nilai Hasil</th>
                            <th class="p-4 border text-center text-base">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>`;

            if (params.length === 0) {
                html += `
                        <tr>
                            <td colspan="6" class="p-4 text-center text-gray-400 italic text-xs">
                                Tidak ada parameter untuk item ini.
                            </td>
                        </tr>`;
            } else {
                params.forEach((param, paramIdx) => {
                    const pParam    = `${pBase}[parameter][${paramIdx}]`;
                    const nilaiAwal = param.nilai_hasil      ?? '';
                    const ketAwal   = param.keterangan_hasil ?? '';

                    html += `
                        <tr class="hover:bg-gray-50 dark:hover:bg-slate-800">
                            <td class="p-2 border text-center dark:border-gray-700">${paramIdx + 1}</td>
                            <td class="p-2 border dark:border-gray-700">
                                ${escHtml(param.nama_parameter)}
                                <input type="hidden" name="${pParam}[id_parameter]" value="${param.id_parameter}">
                            </td>
                            <td class="p-2 border text-center dark:border-gray-700">${param.satuan ? escHtml(param.satuan) : '—'}</td>
                            <td class="p-2 border text-center dark:border-gray-700">${param.nilai_rujukan ? escHtml(param.nilai_rujukan) : '—'}</td>
                            <td class="p-2 border dark:border-gray-700">
                                <input type="text"
                                       name="${pParam}[nilai_hasil]"
                                       value="${escAttr(nilaiAwal)}"
                                       placeholder="Nilai hasil..."
                                       class="w-full border border-gray-300 rounded p-1 text-sm dark:bg-slate-900 dark:border-gray-600 dark:text-white">
                            </td>
                            <td class="p-2 border dark:border-gray-700">
                                <input type="text"
                                       name="${pParam}[keterangan_hasil]"
                                       value="${escAttr(ketAwal)}"
                                       placeholder="Keterangan..."
                                       class="w-full border border-gray-300 rounded p-1 text-sm dark:bg-slate-900 dark:border-gray-600 dark:text-white">
                            </td>
                        </tr>`;
                });
            }

            html += `
                    </tbody>
                </table>
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

    function escAttr(str) {
        if (str == null) return '';
        return String(str).replace(/"/g, '&quot;');
    }

    // ════════════════════════════════════════════
    // INIT
    // ════════════════════════════════════════════
    document.addEventListener('DOMContentLoaded', function () {
        if (_itemTerpilih.length > 0) {
            renderHasilPk(_itemTerpilih);
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