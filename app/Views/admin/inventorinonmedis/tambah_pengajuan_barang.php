<?php
$isEdit   = !empty($baris ?? []);
$baris    = $baris ?? [];
$readonly = $readonly ?? false;
?>
<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<?= $this->include('components/modal/modalPemohon') ?>
<?= $this->include('components/modal/modalbarang') ?>

<div class="max-w-[85rem] py-6 lg:py-3 px-8 mx-auto">
    <div class="bg-white rounded-xl shadow p-4 sm:p-7 dark:bg-slate-900">
        <?= view('components/form/judul', ['judul' => $judul]) ?>

        <form action="<?= $modul_path . $form_action ?>" id="myForm" onsubmit="return validateForm()" method="post">
            <?= csrf_field() ?>

            <input type="hidden" name="petugas_gudang" id="petugas_gudang" value="<?= $baris['petugas_gudang'] ?? '' ?>">

            <!-- No. Pengajuan (auto) + Tanggal Pengajuan (input) -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">
                    No. Pengajuan
                </label>
                <input type="text" readonly placeholder="Terisi otomatis..." value="<?= $baris['no_pengajuan'] ?? '' ?>"
                       class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white bg-gray-100 cursor-not-allowed">

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">
                    Tanggal Pengajuan<span class="text-red-600">*</span>
                </label>
                <input type="datetime-local" name="tanggal" id="tanggal"
                       value="<?= !empty($baris['tanggal']) ? date('Y-m-d\TH:i', strtotime($baris['tanggal'])) : date('Y-m-d\TH:i') ?>"
                       class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white dark:bg-slate-800" required <?= $readonly ? 'disabled' : '' ?>>
            </div>

            <!-- Status + Pemohon -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">
                    Status
                </label>
                <?php if (!$isEdit): ?>
                    <input type="text" readonly value="Draf"
                           class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white bg-gray-100 cursor-not-allowed">
                <?php else: ?>
                    <select name="id_status_pengajuan_barang"
                            class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white dark:bg-slate-800">
                        <option value="1" <?= (($baris['id_status_pengajuan_barang'] ?? '') == 1) ? 'selected' : '' ?>>Draf</option>
                        <option value="4" <?= (($baris['id_status_pengajuan_barang'] ?? '') == 4) ? 'selected' : '' ?>>Proses Pengajuan</option>
                    </select>
                <?php endif; ?>

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">
                    Pemohon<span class="text-red-600">*</span>
                </label>
                <div class="w-full lg:w-1/4 flex gap-x-2">
                    <input type="text" id="petugas_gudang_display"
                           placeholder="Klik cari pemohon..."
                           value="<?= $baris['nama'] ?? '' ?>"
                           onclick="open_modalPemohon()"
                           onkeydown="return false"
                           class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full dark:border-gray-600 dark:text-white cursor-pointer bg-white" required>
                    <button type="button" onclick="open_modalPemohon()"
                            class="inline-flex justify-center items-center p-2 text-sm font-medium text-white bg-blue-600 rounded-lg border border-transparent hover:bg-blue-700 focus:outline-none transition-all w-10 h-[38px] flex-shrink-0 shadow-sm">
                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Detail Barang -->
            <div class="mt-8 mb-4 border-t pt-5">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-base font-semibold text-gray-800 dark:text-white">Detail Barang <span class="text-red-600">*</span></h3>
                    <button type="button" onclick="open_modalBarang()"
                            class="inline-flex items-center gap-x-1.5 py-2 px-3 text-sm font-semibold rounded-lg border border-transparent bg-[#0A2D27] text-[#ACF2E7] hover:bg-[#13594E] transition-all shadow-sm <?= $readonly ? 'hidden' : '' ?>">
                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                        </svg>
                        Pilih Item
                    </button>
                </div>

                <div class="border rounded-lg overflow-hidden">
                    <table class="w-full text-sm text-gray-700 dark:text-gray-300">
                        <thead style="background-color: #E6F2EF;">
                            <tr>
                                <th class="p-3 border text-center font-semibold">Kode</th>
                                <th class="p-3 border text-center font-semibold">Nama Barang</th>
                                <th class="p-3 border text-center font-semibold">Satuan</th>
                                <th class="p-3 border text-center font-semibold w-28">Qty</th>
                                <th class="p-3 border text-center font-semibold w-36">Harga</th>
                                <th class="p-3 border text-center font-semibold w-20">Hapus</th>
                            </tr>
                        </thead>
                        <tbody id="detailTableBody">
                            <?php if ($isEdit && !empty($detail_items ?? [])): ?>
                                <?php foreach ($detail_items as $item): ?>
                                <tr data-id="<?= $item['id_barang'] ?>">
                                    <td class="p-3 border text-center"><?= esc($item['kode_barang'] ?? '-') ?></td>
                                    <td class="p-3 border"><?= esc($item['nama_barang'] ?? '-') ?></td>
                                    <td class="p-3 border text-center"><?= esc($item['nama_satuan'] ?? '-') ?></td>
                                    <td class="p-3 border text-center">
                                        <input type="number" name="detail_qty[]" value="<?= $item['qty'] ?? 1 ?>" min="1"
                                               class="border border-gray-300 rounded-lg p-1 w-full text-center text-sm" required <?= $readonly ? 'disabled' : '' ?>>
                                        <input type="hidden" name="detail_id_barang[]" value="<?= $item['id_barang'] ?>">
                                    </td>
                                    <td class="p-3 border text-center">
                                        <input type="number" name="detail_harga[]" value="<?= $item['harga'] ?? 0 ?>" min="0" step="any"
                                               class="border border-gray-300 rounded-lg p-1 w-full text-center text-sm" <?= $readonly ? 'disabled' : '' ?>>
                                    </td>
                                    <td class="p-3 border text-center">
                                        <?php if (!$readonly): ?>
                                        <button type="button" onclick="hapusItem(this)" class="text-red-600 hover:underline text-sm">Hapus</button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr id="emptyRow"><td colspan="6" class="p-4 text-center text-gray-400 italic">Belum ada item dipilih</td></tr>
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
    function autofillFields(map) {
        var idBarang   = map.id_barang ?? '';
        var namaBarang = map.id_barang_display ?? '';
        var namaSatuan = map.nama_satuan ?? '';
        var kodeBarang = map.kode_barang ?? '';
        var harga      = map.harga ?? '0';

        if (!idBarang) return;

        var existing = document.querySelector('#detailTableBody tr[data-id="' + idBarang + '"]');
        if (existing) { Swal.fire({ icon: 'warning', title: 'Perhatian', text: 'Barang ini sudah ada di daftar.', confirmButtonText: 'Tutup', customClass: { confirmButton: 'bg-[#0A2D27] text-[#ACF2E7] hover:bg-[#13594E] font-medium rounded-lg px-4 py-2' }, buttonsStyling: false }); return; }

        var emptyRow = document.getElementById('emptyRow');
        if (emptyRow) emptyRow.remove();

        var tbody = document.getElementById('detailTableBody');
        var tr = document.createElement('tr');
        tr.dataset.id = idBarang;
        tr.innerHTML = `
            <td class="p-3 border text-center">${kodeBarang}</td>
            <td class="p-3 border">${namaBarang}</td>
            <td class="p-3 border text-center">${namaSatuan}</td>
            <td class="p-3 border text-center">
                <input type="number" name="detail_qty[]" value="1" min="1"
                       class="border border-gray-300 rounded-lg p-1 w-full text-center text-sm" required>
                <input type="hidden" name="detail_id_barang[]" value="${idBarang}">
            </td>
            <td class="p-3 border text-center">
                <input type="number" name="detail_harga[]" value="${harga}" min="0" step="any"
                       class="border border-gray-300 rounded-lg p-1 w-full text-center text-sm">
            </td>
            <td class="p-3 border text-center">
                <button type="button" onclick="hapusItem(this)" class="text-red-600 hover:underline text-sm">Hapus</button>
            </td>`;
        tbody.appendChild(tr);
    }

    function hapusItem(btn) {
        btn.closest('tr').remove();
        var tbody = document.getElementById('detailTableBody');
        if (tbody.querySelectorAll('tr[data-id]').length === 0) {
            tbody.innerHTML = '<tr id="emptyRow"><td colspan="6" class="p-4 text-center text-gray-400 italic">Belum ada item dipilih</td></tr>';
        }
    }

    function validateForm() {
        var form = document.getElementById('myForm');
        if (!form.reportValidity()) return false;

        // Item wajib hanya jika status bukan Draf (1)
        var statusSelect = document.querySelector('select[name="id_status_pengajuan_barang"]');
        var status = statusSelect ? statusSelect.value : '1';
        if (status !== '1') {
            if (document.querySelectorAll('#detailTableBody tr[data-id]').length === 0) {
                Swal.fire({ icon: 'warning', title: 'Perhatian', text: 'Tambahkan minimal satu item barang sebelum mengajukan.', confirmButtonText: 'Tutup', customClass: { confirmButton: 'bg-[#0A2D27] text-[#ACF2E7] hover:bg-[#13594E] font-medium rounded-lg px-4 py-2' }, buttonsStyling: false });
                return false;
            }
        }

        var btn = document.getElementById('submitButton');
        if (btn) { btn.disabled = true; btn.innerHTML = 'Menyimpan...'; }
        return true;
    }
</script>

<?= $this->endSection(); ?>
