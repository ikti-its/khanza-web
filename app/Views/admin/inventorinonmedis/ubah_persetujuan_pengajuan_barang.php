<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<?= $this->include('components/modal/modalPemohon') ?>

<div class="max-w-[85rem] py-6 lg:py-3 px-8 mx-auto">
    <div class="bg-white rounded-xl shadow p-4 sm:p-7 dark:bg-slate-900">
        <?= view('components/form/judul', ['judul' => $judul]) ?>

        <form action="<?= $modul_path . $form_action ?>" id="myForm" onsubmit="return validateForm()" method="post">
            <?= csrf_field() ?>

            <input type="hidden" name="atasan_logistik" id="atasan_logistik" value="<?= $baris['atasan_logistik'] ?? '' ?>">

            <!-- No. Pengajuan + Tanggal -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">
                    No. Pengajuan
                </label>
                <input type="text" readonly value="<?= esc($baris['no_pengajuan'] ?? '-') ?>"
                       class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white bg-gray-100 cursor-not-allowed">

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">
                    Tanggal Pengajuan
                </label>
                <input type="text" readonly value="<?= !empty($baris['tanggal']) ? date('d/m/Y, H:i', strtotime($baris['tanggal'])) : '-' ?>"
                       class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white bg-gray-100 cursor-not-allowed">
            </div>

            <!-- Pemohon + Total Harga -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">
                    Pemohon
                </label>
                <input type="text" readonly value="<?= esc($baris['nama'] ?? '-') ?>"
                       class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white bg-gray-100 cursor-not-allowed">

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">
                    Total Harga
                </label>
                <input type="text" readonly value="<?= number_format((float)($baris['total_harga'] ?? 0), 0, ',', '.') ?>"
                       class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white bg-gray-100 cursor-not-allowed">
            </div>

            <!-- Status -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">
                    Status<span class="text-red-600">*</span>
                </label>
                <select name="id_status_pengajuan_barang" id="id_status_pengajuan_barang"
                        class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white dark:bg-slate-800">
                    <option value="4" <?= (($baris['id_status_pengajuan_barang'] ?? '') == 4) ? 'selected' : '' ?>>Proses Pengajuan</option>
                    <option value="2" <?= (($baris['id_status_pengajuan_barang'] ?? '') == 2) ? 'selected' : '' ?>>Disetujui</option>
                    <option value="3" <?= (($baris['id_status_pengajuan_barang'] ?? '') == 3) ? 'selected' : '' ?>>Ditolak</option>
                </select>
            </div>

            <!-- Pengelola (Atasan Logistik) -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">
                    Pengelola (Atasan Logistik)<span class="text-red-600">*</span>
                </label>
                <div class="w-full lg:w-1/4 flex gap-x-2">
                    <input type="text" id="atasan_logistik_display"
                           placeholder="Klik cari pengelola..."
                           value="<?= esc($baris['atasan_logistik_nama'] ?? '') ?>"
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

            <!-- Detail Barang -->
            <div class="mt-8 mb-4 border-t pt-5">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-base font-semibold text-gray-800 dark:text-white">Detail Barang</h3>
                </div>

                <div class="border rounded-lg overflow-hidden">
                    <table class="w-full text-sm text-gray-700 dark:text-gray-300">
                        <thead style="background-color: #E6F2EF;">
                            <tr>
                                <th class="p-3 border text-center font-semibold">Kode</th>
                                <th class="p-3 border text-center font-semibold">Nama Barang</th>
                                <th class="p-3 border text-center font-semibold">Satuan</th>
                                <th class="p-3 border text-center font-semibold w-24">Stok Saat Ini</th>
                                <th class="p-3 border text-center font-semibold w-24">Qty</th>
                                <th class="p-3 border text-center font-semibold w-28">Harga</th>
                                <th class="p-3 border text-center font-semibold w-32">Qty Disetujui</th>
                            </tr>
                        </thead>
                        <tbody id="detailTableBody">
                            <?php if (!empty($detail_items ?? [])): ?>
                                <?php foreach ($detail_items as $item): ?>
                                <tr>
                                    <td class="p-3 border text-center"><?= esc($item['kode_barang'] ?? '-') ?></td>
                                    <td class="p-3 border"><?= esc($item['nama_barang'] ?? '-') ?></td>
                                    <td class="p-3 border text-center"><?= esc($item['nama_satuan'] ?? '-') ?></td>
                                    <td class="p-3 border text-center"><?= isset($item['stok']) ? esc((string) $item['stok']) : '-' ?></td>
                                    <td class="p-3 border text-center"><?= $item['qty'] ?? 0 ?></td>
                                    <td class="p-3 border text-right"><?= number_format((float)($item['harga'] ?? 0), 0, ',', '.') ?></td>
                                    <td class="p-3 border text-center">
                                        <input type="number" name="detail_qty_disetujui[]" value="<?= $item['qty_disetujui'] ?? $item['qty'] ?? 0 ?>" min="0"
                                               class="border border-gray-300 rounded-lg p-1 w-full text-center text-sm">
                                        <input type="hidden" name="detail_id_barang[]" value="<?= $item['id_barang'] ?>">
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="7" class="p-4 text-center text-gray-400 italic">Tidak ada detail barang.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <?= view('components/form/submit_button') ?>
        </form>
    </div>
</div>

<script>
    function validateForm() {
        var form = document.getElementById('myForm');
        if (!form.reportValidity()) {
            return false;
        }

        var status = document.getElementById('id_status_pengajuan_barang').value;
        if (status === '2') {
            var pengelola = document.getElementById('atasan_logistik').value;
            if (!pengelola) {
                Swal.fire({ icon: 'warning', title: 'Perhatian', text: 'Atasan logistik wajib diisi sebelum menyetujui pengajuan.', confirmButtonText: 'Tutup', customClass: { confirmButton: 'bg-[#0A2D27] text-[#ACF2E7] hover:bg-[#13594E] font-medium rounded-lg px-4 py-2' }, buttonsStyling: false });
                return false;
            }

            var hasQty = false;
            var qtyInputs = document.querySelectorAll('input[name="detail_qty_disetujui[]"]');
            for (var i = 0; i < qtyInputs.length; i++) {
                if ((parseInt(qtyInputs[i].value) || 0) > 0) hasQty = true;
            }
            if (!hasQty) {
                Swal.fire({ icon: 'warning', title: 'Perhatian', text: 'Isi minimal satu qty disetujui sebelum menyetujui pengajuan.', confirmButtonText: 'Tutup', customClass: { confirmButton: 'bg-[#0A2D27] text-[#ACF2E7] hover:bg-[#13594E] font-medium rounded-lg px-4 py-2' }, buttonsStyling: false });
                return false;
            }
        }

        var submitButton = document.getElementById('submitButton');
        if (submitButton) {
            submitButton.setAttribute('disabled', true);
            submitButton.innerHTML = 'Menyimpan...';
        }
        return true;
    }
</script>

<?= $this->endSection(); ?>
