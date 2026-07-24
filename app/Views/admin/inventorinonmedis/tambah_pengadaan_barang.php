<?php
$isEdit   = !empty($baris ?? []);
$baris    = $baris ?? [];
$readonly = $readonly ?? false;
?>
<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<?= $this->include('components/modal/modalPengajuan') ?>
<?= $this->include('components/modal/modalSuplier') ?>

<div class="max-w-[85rem] py-6 lg:py-3 px-8 mx-auto">
    <div class="bg-white rounded-xl shadow p-4 sm:p-7 dark:bg-slate-900">
        <?= view('components/form/judul', ['judul' => $judul]) ?>

        <form action="<?= $modul_path . $form_action ?>" id="myForm" onsubmit="return validateForm()" method="post">
            <?= csrf_field() ?>

            <input type="hidden" name="id_pengajuan" id="id_pengajuan" value="<?= $baris['id_pengajuan'] ?? '' ?>">
            <input type="hidden" name="id_suplier" id="id_suplier" value="<?= $baris['id_suplier'] ?? '' ?>">

            <!-- No. Pengadaan (auto) + Tanggal Pengadaan (input) -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">
                    No. Pengadaan
                </label>
                <input type="text" readonly placeholder="Terisi otomatis..." value="<?= $baris['no_pengadaan'] ?? '' ?>"
                       class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white bg-gray-100 cursor-not-allowed">

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">
                    Tanggal Pengadaan<span class="text-red-600">*</span>
                </label>
                <input type="datetime-local" name="tanggal" id="tanggal"
                       value="<?= !empty($baris['tanggal']) ? date('Y-m-d\TH:i', strtotime($baris['tanggal'])) : date('Y-m-d\TH:i') ?>"
                       class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white dark:bg-slate-800" required <?= $readonly ? 'disabled' : '' ?>>
            </div>

            <!-- Pengajuan (modal) + Suplier (modal) -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">
                    Pengajuan<span class="text-red-600">*</span>
                </label>
                <div class="w-full lg:w-1/4 flex gap-x-2">
                    <input type="text" id="id_pengajuan_display"
                           placeholder="Klik cari pengajuan..."
                           value="<?= $baris['no_pengajuan'] ?? '' ?>"
                           onclick="open_modalPengajuan()"
                           onkeydown="return false"
                           class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full dark:border-gray-600 dark:text-white cursor-pointer bg-white" required>
                    <button type="button" onclick="open_modalPengajuan()"
                            class="inline-flex justify-center items-center p-2 text-sm font-medium text-white bg-blue-600 rounded-lg border border-transparent hover:bg-blue-700 focus:outline-none transition-all w-10 h-[38px] flex-shrink-0 shadow-sm">
                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </button>
                </div>

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">
                    Suplier
                </label>
                <div class="w-full lg:w-1/4 flex gap-x-2">
                    <input type="text" id="id_suplier_display"
                           placeholder="Klik cari suplier..."
                           value="<?= $baris['nama_suplier'] ?? '' ?>"
                           onclick="open_modalSuplier()"
                           onkeydown="return false"
                           class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full dark:border-gray-600 dark:text-white cursor-pointer bg-white">
                    <button type="button" onclick="open_modalSuplier()"
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
                <select name="id_status_pengadaan_barang"
                        class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white dark:bg-slate-800" required <?= $readonly ? 'disabled' : '' ?>>
                    <option value="1" <?= (($baris['id_status_pengadaan_barang'] ?? 1) == 1) ? 'selected' : '' ?>>Proses Pengadaan</option>
                    <option value="2" <?= (($baris['id_status_pengadaan_barang'] ?? '') == 2) ? 'selected' : '' ?>>Selesai</option>
                    <option value="3" <?= (($baris['id_status_pengadaan_barang'] ?? '') == 3) ? 'selected' : '' ?>>Dibatalkan</option>
                </select>

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">
                    Catatan
                </label>
                <input type="text" name="catatan" id="catatan" placeholder="Catatan (opsional)..." maxlength="500"
                       value="<?= $baris['catatan'] ?? '' ?>"
                       class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white dark:bg-slate-800" <?= $readonly ? 'disabled' : '' ?>>
            </div>

            <!-- Detail Barang -->
            <div class="mt-8 mb-4 border-t pt-5">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-base font-semibold text-gray-800 dark:text-white">Detail Barang Pengadaan</h3>
                </div>

                <div class="border rounded-lg overflow-hidden">
                    <table class="w-full text-sm text-gray-700 dark:text-gray-300">
                        <thead style="background-color: #E6F2EF;">
                            <tr>
                                <th class="p-3 border text-center font-semibold">Kode</th>
                                <th class="p-3 border text-center font-semibold">Nama Barang</th>
                                <th class="p-3 border text-center font-semibold">Satuan</th>
                                <th class="p-3 border text-center font-semibold w-24">Qty Disetujui</th>
                                <th class="p-3 border text-center font-semibold w-24">Sudah Dipesan</th>
                                <th class="p-3 border text-center font-semibold w-28">Qty Pesan</th>
                                <th class="p-3 border text-center font-semibold w-36">Harga Satuan</th>
                            </tr>
                        </thead>
                        <tbody id="detailTableBody">
                            <?php if ($isEdit && !empty($detail_items ?? [])): ?>
                                <?php foreach ($detail_items as $item): ?>
                                <tr data-id="<?= $item['id_barang'] ?>">
                                    <td class="p-3 border text-center"><?= esc($item['kode_barang'] ?? '-') ?></td>
                                    <td class="p-3 border"><?= esc($item['nama_barang'] ?? '-') ?></td>
                                    <td class="p-3 border text-center"><?= esc($item['nama_satuan'] ?? '-') ?></td>
                                    <td class="p-3 border text-center text-gray-500">-</td>
                                    <td class="p-3 border text-center text-gray-500">-</td>
                                    <td class="p-3 border text-center">
                                        <input type="number" name="detail_qty[]" value="<?= $item['qty'] ?? 0 ?>" min="0"
                                               class="border border-gray-300 rounded-lg p-1 w-full text-center text-sm" <?= $readonly ? 'disabled' : '' ?>>
                                        <input type="hidden" name="detail_id_barang[]" value="<?= $item['id_barang'] ?>">
                                    </td>
                                    <td class="p-3 border text-center">
                                        <input type="number" name="detail_harga[]" value="<?= $item['harga_satuan'] ?? 0 ?>" min="0" step="any"
                                               class="border border-gray-300 rounded-lg p-1 w-full text-center text-sm" <?= $readonly ? 'disabled' : '' ?>>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr id="emptyRow"><td colspan="7" class="p-4 text-center text-gray-400 italic">Pilih pengajuan untuk menampilkan item</td></tr>
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
    // Override autofillFields untuk modal Pengajuan — fetch detail items
    var _originalAutofillFields = window.autofillFields;
    window.autofillFields = function(map) {
        // Pengajuan selected
        if (map.id_pengajuan) {
            document.getElementById('id_pengajuan').value = map.id_pengajuan;
            document.getElementById('id_pengajuan_display').value = map.id_pengajuan_display ?? '';
            loadPengajuanItems(map.id_pengajuan);
            return;
        }
        // Suplier selected (dari modalSuplier)
        if (map.id_suplier !== undefined) {
            document.getElementById('id_suplier').value = map.id_suplier ?? '';
            document.getElementById('id_suplier_display').value = map.id_suplier_display ?? '';
            return;
        }
    };

    function autofillSuplier(item) {
        document.getElementById('id_suplier').value = item.id_suplier ?? '';
        document.getElementById('id_suplier_display').value = item.nama_suplier ?? '';
    }

    function loadPengajuanItems(idPengajuan) {
        var tbody = document.getElementById('detailTableBody');
        tbody.innerHTML = '<tr><td colspan="7" class="p-4 text-center text-gray-500">Memuat item...</td></tr>';

        fetch('<?= site_url('inventori-non-medis/pengadaan-barang/modal/list') ?>?id_pengajuan=' + idPengajuan)
            .then(r => r.json())
            .then(json => {
                var data = json.data || [];
                if (data.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="7" class="p-4 text-center text-gray-400 italic">Tidak ada item pada pengajuan ini</td></tr>';
                    return;
                }
                tbody.innerHTML = '';
                data.forEach(item => {
                    var sisa = item.qty_disetujui - item.qty_sudah_dipesan;
                    var tr = document.createElement('tr');
                    tr.dataset.id = item.id_barang;
                    tr.innerHTML = `
                        <td class="p-3 border text-center">${item.kode_barang ?? '-'}</td>
                        <td class="p-3 border">${item.nama_barang ?? '-'}</td>
                        <td class="p-3 border text-center">${item.nama_satuan ?? '-'}</td>
                        <td class="p-3 border text-center text-gray-500">${item.qty_disetujui}</td>
                        <td class="p-3 border text-center text-gray-500">${item.qty_sudah_dipesan}</td>
                        <td class="p-3 border text-center">
                            <input type="number" name="detail_qty[]" value="${sisa > 0 ? sisa : 0}" min="0" max="${sisa}"
                                   class="border border-gray-300 rounded-lg p-1 w-full text-center text-sm">
                            <input type="hidden" name="detail_id_barang[]" value="${item.id_barang}">
                        </td>
                        <td class="p-3 border text-center">
                            <input type="number" name="detail_harga[]" value="${item.harga ?? 0}" min="0" step="any"
                                   class="border border-gray-300 rounded-lg p-1 w-full text-center text-sm">
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

        // Pastikan ada pengajuan dipilih
        if (!document.getElementById('id_pengajuan').value) {
            Swal.fire({ icon: 'warning', title: 'Perhatian', text: 'Pilih pengajuan terlebih dahulu.', confirmButtonText: 'Tutup', customClass: { confirmButton: 'bg-[#0A2D27] text-[#ACF2E7] hover:bg-[#13594E] font-medium rounded-lg px-4 py-2' }, buttonsStyling: false });
            return false;
        }

        // Jika status Dibatalkan (3), tidak perlu validasi item
        var statusSelect = document.querySelector('select[name="id_status_pengadaan_barang"]');
        var isCancelled = statusSelect && statusSelect.value === '3';

        if (!isCancelled) {
            // Pastikan minimal 1 item punya qty > 0
            var qtyInputs = document.querySelectorAll('input[name="detail_qty[]"]');
            var hasItem = false;
            qtyInputs.forEach(function(input) { if (parseInt(input.value) > 0) hasItem = true; });
            if (!hasItem) {
                Swal.fire({ icon: 'warning', title: 'Perhatian', text: 'Isi qty pesan minimal untuk satu item.', confirmButtonText: 'Tutup', customClass: { confirmButton: 'bg-[#0A2D27] text-[#ACF2E7] hover:bg-[#13594E] font-medium rounded-lg px-4 py-2' }, buttonsStyling: false });
                return false;
            }
        }

        var btn = document.getElementById('submitButton');
        if (btn) { btn.disabled = true; btn.innerHTML = 'Menyimpan...'; }
        return true;
    }
</script>

<?= $this->endSection(); ?>
