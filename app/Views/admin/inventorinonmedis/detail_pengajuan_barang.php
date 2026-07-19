<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<div class="max-w-[85rem] py-6 lg:py-3 px-8 mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-7 dark:bg-slate-900 dark:border-gray-800">

        <?= view('components/form/judul', ['judul' => $judul]) ?>

        <div class="space-y-1">

            <!-- No. Pengajuan + Tanggal -->
            <div class="sm:block md:flex items-center py-3">
                <span class="block mb-1 md:mb-0 text-sm font-medium text-gray-500 dark:text-gray-500 md:w-1/4">No. Pengajuan</span>
                <div class="w-full lg:w-1/4">
                    <span class="text-sm font-semibold text-gray-900 dark:text-white"><?= esc($baris['no_pengajuan'] ?? '-') ?></span>
                </div>
                <span class="block mt-4 md:my-0 md:ml-10 mb-1 text-sm font-medium text-gray-500 dark:text-gray-500 md:w-1/4">Tanggal Pengajuan</span>
                <div class="w-full lg:w-1/4">
                    <span class="text-sm font-semibold text-gray-900 dark:text-white"><?= !empty($baris['tanggal']) ? date('d/m/Y, H:i', strtotime($baris['tanggal'])) : '-' ?></span>
                </div>
            </div>

            <!-- Pemohon + Status -->
            <div class="sm:block md:flex items-center py-3">
                <span class="block mb-1 md:mb-0 text-sm font-medium text-gray-500 dark:text-gray-500 md:w-1/4">Pemohon</span>
                <div class="w-full lg:w-1/4">
                    <span class="text-sm font-semibold text-gray-900 dark:text-white"><?= esc($baris['nama'] ?? '-') ?></span>
                </div>
                <span class="block mt-4 md:my-0 md:ml-10 mb-1 text-sm font-medium text-gray-500 dark:text-gray-500 md:w-1/4">Status</span>
                <div class="w-full lg:w-1/4">
                    <span class="inline-flex items-center py-1 px-2.5 rounded-full text-xs font-semibold" style="background-color: #FEF3C7; color: #92400E;">
                        <?= esc($baris['nama_status_pengajuan_barang'] ?? '-') ?>
                    </span>
                </div>
            </div>

        </div>

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
                        <th class="text-center py-2 font-medium">Qty</th>
                        <th class="text-right py-2 font-medium">Harga</th>
                    </tr>
                </thead>
                <tbody class="text-slate-700 dark:text-slate-300">
                    <?php foreach ($detail_items as $item): ?>
                    <tr class="border-t border-slate-100 dark:border-slate-700/50">
                        <td class="py-2 font-mono text-sm"><?= esc($item['kode_barang'] ?? '-') ?></td>
                        <td class="py-2 font-semibold"><?= esc($item['nama_barang'] ?? '-') ?></td>
                        <td class="py-2 text-center"><?= esc($item['nama_satuan'] ?? '-') ?></td>
                        <td class="py-2 text-center font-semibold"><?= $item['qty'] ?? 0 ?></td>
                        <td class="py-2 text-right font-semibold"><?= number_format((float)($item['harga'] ?? 0), 0, ',', '.') ?></td>
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
