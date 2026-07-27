<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<div class="max-w-[85rem] py-6 lg:py-3 px-8 mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-7 dark:bg-slate-900 dark:border-gray-800">

        <?= view('components/form/judul', ['judul' => $judul]) ?>

        <div class="space-y-1">

            <!-- No. Permintaan + Tanggal -->
            <div class="sm:block md:flex items-center py-3">
                <span class="block mb-1 md:mb-0 text-sm font-medium text-gray-500 dark:text-gray-500 md:w-1/4">No. Permintaan</span>
                <div class="w-full lg:w-1/4">
                    <span class="text-sm font-semibold text-gray-900 dark:text-white"><?= esc($baris['no_permintaan'] ?? '-') ?></span>
                </div>
                <span class="block mt-4 md:my-0 md:ml-10 mb-1 text-sm font-medium text-gray-500 dark:text-gray-500 md:w-1/4">Tanggal Permintaan</span>
                <div class="w-full lg:w-1/4">
                    <span class="text-sm font-semibold text-gray-900 dark:text-white"><?= !empty($baris['tanggal']) ? date('d/m/Y, H:i', strtotime($baris['tanggal'])) : '-' ?></span>
                </div>
            </div>

            <!-- Pemohon + Ruangan -->
            <div class="sm:block md:flex items-center py-3">
                <span class="block mb-1 md:mb-0 text-sm font-medium text-gray-500 dark:text-gray-500 md:w-1/4">Pemohon</span>
                <div class="w-full lg:w-1/4">
                    <span class="text-sm font-semibold text-gray-900 dark:text-white"><?= esc($baris['petugas_nama'] ?? $baris['nama'] ?? '-') ?></span>
                </div>
                <span class="block mt-4 md:my-0 md:ml-10 mb-1 text-sm font-medium text-gray-500 dark:text-gray-500 md:w-1/4">Ruangan</span>
                <div class="w-full lg:w-1/4">
                    <span class="text-sm font-semibold text-gray-900 dark:text-white"><?= esc($baris['nama_ruangan'] ?? '-') ?></span>
                </div>
            </div>

            <!-- Status + Pengelola -->
            <div class="sm:block md:flex items-center py-3">
                <span class="block mb-1 md:mb-0 text-sm font-medium text-gray-500 dark:text-gray-500 md:w-1/4">Status</span>
                <div class="w-full lg:w-1/4">
                    <?php
                        $status_id = (int) ($baris['id_status_permintaan_barang'] ?? 0);
                        if (in_array($status_id, [2, 6], true)) {
                            $bg = '#D1FAE5'; $color = '#065F46';
                        } elseif ($status_id === 3) {
                            $bg = '#FEE2E2'; $color = '#991B1B';
                        } else {
                            $bg = '#FEF3C7'; $color = '#92400E';
                        }
                    ?>
                    <span class="inline-flex items-center py-1 px-2.5 rounded-full text-xs font-semibold" style="background-color: <?= $bg ?>; color: <?= $color ?>;">
                        <?= esc($baris['nama_status_permintaan_barang'] ?? '-') ?>
                    </span>
                </div>
                <span class="block mt-4 md:my-0 md:ml-10 mb-1 text-sm font-medium text-gray-500 dark:text-gray-500 md:w-1/4">Pengelola</span>
                <div class="w-full lg:w-1/4">
                    <span class="text-sm font-semibold text-gray-900 dark:text-white"><?= esc($baris['nama'] ?? '-') ?></span>
                </div>
            </div>

            <!-- No. Keluar -->
            <div class="sm:block md:flex items-center py-3">
                <span class="block mb-1 md:mb-0 text-sm font-medium text-gray-500 dark:text-gray-500 md:w-1/4">No. Keluar</span>
                <div class="w-full lg:w-1/4">
                    <span class="text-sm font-semibold text-gray-900 dark:text-white"><?= esc($baris['no_keluar'] ?? '-') ?></span>
                </div>
            </div>

        </div>

        <!-- Progress Tracking -->
        <?php
        helper('tracking');
        $tracking = get_permintaan_tracking((int) ($baris['id_permintaan'] ?? 0));
        if (!empty($tracking['steps'])):
        ?>
            <?= view('components/tracking/timeline', ['tracking' => $tracking]) ?>
        <?php endif; ?>

        <!-- Detail Barang -->
        <div class="mt-6 bg-slate-50 border border-slate-200 rounded-xl p-5 dark:bg-slate-800 dark:border-slate-700 shadow-sm">
            <div class="flex items-center gap-x-2 mb-3 border-b border-slate-200 pb-2 dark:border-slate-700">
                <svg class="w-4 h-4 text-teal-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/>
                </svg>
                <h4 class="text-xs font-bold text-slate-500 uppercase tracking-wider dark:text-slate-400">Detail Barang</h4>
            </div>

            <?php if (!empty($detail_items)): ?>
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-slate-500 dark:text-slate-400">
                        <th class="text-left py-2 font-medium">Kode</th>
                        <th class="text-left py-2 font-medium">Nama Barang</th>
                        <th class="text-center py-2 font-medium">Satuan</th>
                        <th class="text-center py-2 font-medium">Qty Diminta</th>
                        <th class="text-center py-2 font-medium">Qty Disetujui</th>
                    </tr>
                </thead>
                <tbody class="text-slate-700 dark:text-slate-300">
                    <?php foreach ($detail_items as $item): ?>
                    <?php $isBaru = empty($item['id_barang']) && !empty($item['nama_barang_baru']); ?>
                    <tr class="border-t border-slate-100 dark:border-slate-700/50">
                        <td class="py-2 font-mono text-sm">
                            <?php if ($isBaru): ?>
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-700">Baru</span>
                            <?php else: ?>
                                <?= esc($item['kode_barang'] ?? '-') ?>
                            <?php endif; ?>
                        </td>
                        <td class="py-2 font-semibold"><?= esc($isBaru ? $item['nama_barang_baru'] : ($item['nama_barang'] ?? '-')) ?></td>
                        <td class="py-2 text-center"><?= esc($item['nama_satuan'] ?? '-') ?></td>
                        <td class="py-2 text-center font-semibold"><?= $item['qty'] ?? 0 ?></td>
                        <td class="py-2 text-center font-semibold"><?= $item['qty_disetujui'] ?? 0 ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
            <p class="text-sm text-slate-400 italic text-center py-4">Tidak ada detail barang.</p>
            <?php endif; ?>
        </div>

        <!-- Tombol Kembali -->
        <div class="mt-5 pt-5 border-t border-gray-200 dark:border-gray-800 flex justify-end">
            <a href="javascript:history.back()" class="py-2 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 dark:bg-slate-900 dark:border-gray-700 dark:text-white dark:hover:bg-gray-800">
                Kembali
            </a>
        </div>

    </div>
</div>

<?= $this->endSection(); ?>
