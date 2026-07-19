<?php
$isEdit   = !empty($baris ?? []);
$baris    = $baris ?? [];
$readonly = $readonly ?? false;
?>
<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<?= $this->include('components/modal/modalPemohon') ?>
<?= $this->include('components/modal/modalPengadaan') ?>

<div class="max-w-[85rem] py-6 lg:py-3 px-8 mx-auto">
    <div class="bg-white rounded-xl shadow p-4 sm:p-7 dark:bg-slate-900">
        <?= view('components/form/judul', ['judul' => $judul]) ?>

        <form action="<?= $modul_path . $form_action ?>" id="myForm" onsubmit="return validateForm()" method="post">
            <?= csrf_field() ?>

            <input type="hidden" name="petugas" id="petugas" value="<?= $baris['petugas'] ?? '' ?>">
            <input type="hidden" name="id_pengadaan" id="id_pengadaan" value="<?= $baris['id_pengadaan'] ?? '' ?>">

            <!-- No. Penerimaan (auto) + Tanggal Penerimaan (input) -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">
                    No. Penerimaan
                </label>
                <input type="text" readonly placeholder="Terisi otomatis..." value="<?= $baris['no_penerimaan'] ?? '' ?>"
                       class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white bg-gray-100 cursor-not-allowed">

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">
                    Tanggal Penerimaan<span class="text-red-600">*</span>
                </label>
                <input type="datetime-local" name="tanggal" id="tanggal"
                       value="<?= !empty($baris['tanggal']) ? date('Y-m-d\TH:i', strtotime($baris['tanggal'])) : date('Y-m-d\TH:i') ?>"
                       class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white dark:bg-slate-800" required <?= $readonly ? 'disabled' : '' ?>>
            </div>

            <!-- No. Pengadaan (modal) + Penerima (modal) -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">
                    No. Pengadaan<span class="text-red-600">*</span>
                </label>
                <div class="w-full lg:w-1/4 flex gap-x-2">
                    <input type="text" id="id_pengadaan_display"
                           placeholder="Klik cari pengadaan..."
                           value="<?= $baris['no_pengadaan'] ?? '' ?>"
                           onclick="open_modalPengadaan()"
                           onkeydown="return false"
                           class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full dark:border-gray-600 dark:text-white cursor-pointer bg-white" required>
                    <button type="button" onclick="open_modalPengadaan()"
                            class="inline-flex justify-center items-center p-2 text-sm font-medium text-white bg-blue-600 rounded-lg border border-transparent hover:bg-blue-700 focus:outline-none transition-all w-10 h-[38px] flex-shrink-0 shadow-sm">
                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </button>
                </div>

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">
                    Penerima
                </label>
                <div class="w-full lg:w-1/4 flex gap-x-2">
                    <input type="text" id="petugas_display"
                           placeholder="Klik cari penerima..."
                           value="<?= $baris['nama'] ?? '' ?>"
                           onclick="open_modalPemohon()"
                           onkeydown="return false"
                           class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full dark:border-gray-600 dark:text-white cursor-pointer bg-white">
                    <button type="button" onclick="open_modalPemohon()"
                            class="inline-flex justify-center items-center p-2 text-sm font-medium text-white bg-blue-600 rounded-lg border border-transparent hover:bg-blue-700 focus:outline-none transition-all w-10 h-[38px] flex-shrink-0 shadow-sm">
                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Status + Catatan -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">
                    Status
                </label>
                <select name="id_status_penerimaan_barang"
                        class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white dark:bg-slate-800" required <?= $readonly ? 'disabled' : '' ?>>
                    <option value="1" <?= (($baris['id_status_penerimaan_barang'] ?? 1) == 1) ? 'selected' : '' ?>>Proses Penerimaan</option>
                    <option value="2" <?= (($baris['id_status_penerimaan_barang'] ?? '') == 2) ? 'selected' : '' ?>>Diterima</option>
                    <option value="3" <?= (($baris['id_status_penerimaan_barang'] ?? '') == 3) ? 'selected' : '' ?>>Ditolak</option>
                </select>

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">
                    Catatan
                </label>
                <input type="text" name="catatan" id="catatan" placeholder="Catatan (opsional)..." maxlength="500"
                       value="<?= $baris['catatan'] ?? '' ?>"
                       class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white dark:bg-slate-800" <?= $readonly ? 'disabled' : '' ?>>
            </div>

            <!-- Detail Barang Penerimaan -->
            <div class="mt-8 mb-4 border-t pt-5">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-base font-semibold text-gray-800 dark:text-white">Detail Barang Diterima</h3>
                </div>

                <div class="border rounded-lg overflow-hidden">
                    <table class="w-full text-sm text-gray-700 dark:text-gray-300">
                        <thead style="background-color: #E6F2EF;">
                            <tr>
                                <th class="p-3 border text-center font-semibold">Kode</th>
                                <th class="p-3 border text-center font-semibold">Nama Barang</th>
                                <th class="p-3 border text-center font-semibold">Satuan</th>
                                <th class="p-3 border text-center font-semibold w-20">Qty Dipesan</th>
                                <th class="p-3 border text-center font-semibold w-24">Sudah Diterima</th>
                                <th class="p-3 border text-center font-semibold w-20">Sisa</th>
                                <th class="p-3 border text-center font-semibold w-28">Qty Diterima</th>
                            </tr>
                        </thead>
                        <tbody id="detailTableBody">
                            <?php if ($isEdit && !empty($detail_items ?? [])): ?>
                                <?php foreach ($detail_items as $item): ?>
                                <?php $sisa = max(0, (int)($item['qty_dipesan'] ?? 0) - (int)($item['sudah_diterima'] ?? 0)); ?>
                                <tr data-id="<?= $item['id_barang'] ?>">
                                    <td class="p-3 border text-center"><?= esc($item['kode_barang'] ?? '-') ?></td>
                                    <td class="p-3 border"><?= esc($item['nama_barang'] ?? '-') ?></td>
                                    <td class="p-3 border text-center"><?= esc($item['nama_satuan'] ?? '-') ?></td>
                                    <td class="p-3 border text-center text-gray-500"><?= $item['qty_dipesan'] ?? '-' ?></td>
                                    <td class="p-3 border text-center text-gray-500"><?= $item['sudah_diterima'] ?? 0 ?></td>
                                    <td class="p-3 border text-center font-semibold"><?= $sisa ?></td>
                                    <td class="p-3 border text-center">
                                        <input type="number" name="detail_qty[]" value="<?= $item['qty_diterima'] ?? 0 ?>" min="0" max="<?= $sisa + (int)($item['qty_diterima'] ?? 0) ?>"
                                               class="border border-gray-300 rounded-lg p-1 w-full text-center text-sm" <?= $readonly ? 'disabled' : '' ?>>
                                        <input type="hidden" name="detail_id_barang[]" value="<?= $item['id_barang'] ?>">
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr id="emptyRow"><td colspan="7" class="p-4 text-center text-gray-400 italic">Pilih pengadaan untuk menampilkan item</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <?php if (!$readonly): ?>
            <?= view('components/form/submit_button') ?>
            <?php else: ?>
            <div class="mt-5 pt-5 border-t flex justify-end">
                <a href="javascript:history.back()" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50">Kembali</a>
            </div>
            <?php endif; ?>
        </form>
    </div>
</div>

<script>
    function loadPengadaanItems(idPengadaan) {
        document.getElementById('id_pengadaan').value = idPengadaan;

        var tbody = document.getElementById('detailTableBody');
        tbody.innerHTML = '<tr><td colspan="7" class="p-4 text-center text-gray-500">Memuat item...</td></tr>';

        fetch('<?= site_url('inventori-non-medis/pengadaan-barang/modal/list') ?>?id_pengadaan=' + idPengadaan + '&mode=penerimaan')
            .then(r => r.json())
            .then(json => {
                var data = json.data || [];
                if (data.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="7" class="p-4 text-center text-gray-400 italic">Tidak ada item pada pengadaan ini</td></tr>';
                    return;
                }
                tbody.innerHTML = '';
                data.forEach(item => {
                    var qty = parseInt(item.qty) || 0;
                    var sudah = parseInt(item.sudah_diterima) || 0;
                    var sisa = parseInt(item.sisa) || 0;
                    var defaultVal = sisa;
                    var tr = document.createElement('tr');
                    tr.dataset.id = item.id_barang;
                    tr.innerHTML = `
                        <td class="p-3 border text-center">${item.kode_barang ?? '-'}</td>
                        <td class="p-3 border">${item.nama_barang ?? '-'}</td>
                        <td class="p-3 border text-center">${item.nama_satuan ?? '-'}</td>
                        <td class="p-3 border text-center text-gray-500">${qty}</td>
                        <td class="p-3 border text-center text-gray-500">${sudah}</td>
                        <td class="p-3 border text-center font-semibold">${sisa}</td>
                        <td class="p-3 border text-center">
                            <input type="number" name="detail_qty[]" value="${defaultVal}" min="0" max="${sisa}"
                                   class="border border-gray-300 rounded-lg p-1 w-full text-center text-sm">
                            <input type="hidden" name="detail_id_barang[]" value="${item.id_barang}">
                        </td>`;
                    tbody.appendChild(tr);
                });
            })
            .catch(() => {
                tbody.innerHTML = '<tr><td colspan="7" class="p-4 text-center text-red-500">Gagal memuat item</td></tr>';
            });
    }

    function validateForm() {
        var form = document.getElementById('myForm');
        if (!form.reportValidity()) return false;

        if (!document.getElementById('id_pengadaan').value) {
            Swal.fire({ icon: 'warning', title: 'Perhatian', text: 'Pilih pengadaan terlebih dahulu.', confirmButtonText: 'Tutup', customClass: { confirmButton: 'bg-[#0A2D27] text-[#ACF2E7] hover:bg-[#13594E] font-medium rounded-lg px-4 py-2' }, buttonsStyling: false });
            return false;
        }

        var btn = document.getElementById('submitButton');
        if (btn) { btn.disabled = true; btn.innerHTML = 'Menyimpan...'; }
        return true;
    }
</script>

<?= $this->endSection(); ?>
