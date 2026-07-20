<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<div class="max-w-[85rem] py-6 lg:py-3 px-8 mx-auto animate-fade-in">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-7 dark:bg-slate-900 dark:border-gray-800">
        
        <div class="flex flex-col items-center text-center pb-5 mb-5 border-b border-gray-200 dark:border-gray-800">
            <div>
                <?= view('components/form/judul', [
                    'judul' => 'Detail Pengambilan Darah'
                ]) ?>
            </div>
            <div class="flex flex-col items-center">
                <span class="text-sm font-medium text-gray-500 dark:text-gray-500 block mb-1">Nomor Pengambilan</span>
                <span class="inline-flex items-center px-4 py-1.5 rounded-lg text-sm font-mono font-bold bg-teal-50 text-teal-700 border border-teal-200 dark:bg-teal-950/40 dark:text-teal-400 dark:border-teal-900 shadow-sm">
                    <?= esc($baris['nomor_pengambilan'] ?? '-') ?>
                </span>
            </div>
        </div>
        
        <div class="mt-5">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                
                <div class="space-y-4 p-4 rounded-xl bg-gray-50/50 dark:bg-slate-800/30 border border-gray-100 dark:border-gray-800">
                    <h3 class="text-sm font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500">Informasi Donor</h3>
                    <div>
                        <span class="block text-sm font-medium text-gray-500 dark:text-gray-400">Nomor Kunjungan</span>
                        <span class="text-sm font-semibold text-gray-900 dark:text-white"><?= esc($baris['nomor_kunjungan'] ?? '-') ?></span>
                    </div>
                    <div>
                        <span class="block text-sm font-medium text-gray-500 dark:text-gray-400">Nomor Pendonor</span>
                        <span class="text-sm font-semibold text-gray-900 dark:text-white"><?= esc($baris['nomor_pendonor'] ?? '-') ?></span>
                    </div>
                    <div>
                        <span class="block text-sm font-medium text-gray-500 dark:text-gray-400">Nama Lengkap</span>
                        <span class="text-sm font-semibold text-gray-900 dark:text-white"><?= esc($baris['nama'] ?? '-') ?></span>
                    </div>
                </div>

                <div class="space-y-4 p-4 rounded-xl bg-gray-50/50 dark:bg-slate-800/30 border border-gray-100 dark:border-gray-800">
                    <h3 class="text-sm font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500">Waktu & Tempat</h3>
                    <div>
                        <span class="block text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Pengambilan</span>
                        <span class="text-sm font-semibold text-gray-900 dark:text-white">
                            <?= !empty($baris['tanggal_pengambilan']) ? date('d F Y H:i', strtotime($baris['tanggal_pengambilan'])) : '-' ?>
                        </span>
                    </div>
                    <div>
                        <span class="block text-sm font-medium text-gray-500 dark:text-gray-400">Shift</span>
                        <span class="text-sm font-semibold text-gray-900 dark:text-white"><?= esc($baris['id_shift'] ?? '-') ?></span>
                    </div>
                    <div>
                        <span class="block text-sm font-medium text-gray-500 dark:text-gray-400">Lokasi Pengambilan</span>
                        <span class="text-sm font-semibold text-gray-900 dark:text-white"><?= esc($baris['id_lokasi_pengambilan'] ?? '-') ?></span>
                    </div>
                </div>

                <div class="space-y-4 p-4 rounded-xl bg-gray-50/50 dark:bg-slate-800/30 border border-gray-100 dark:border-gray-800">
                    <h3 class="text-sm font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500">Kantong & Petugas</h3>
                    <div class="flex justify-between">
                        <div>
                            <span class="block text-sm font-medium text-gray-500 dark:text-gray-400">Nomor Bag</span>
                            <span class="text-sm font-mono font-semibold text-gray-900 dark:text-white"><?= esc($baris['no_bag'] ?? '-') ?></span>
                        </div>
                        <div>
                            <span class="block text-sm font-medium text-gray-500 dark:text-gray-400">Jenis Bag</span>
                            <span class="text-sm font-semibold text-gray-900 dark:text-white"><?= esc($baris['id_jenis_bag'] ?? '-') ?></span>
                        </div>
                    </div>
                    <div>
                        <span class="block text-sm font-medium text-gray-500 dark:text-gray-400">Jenis Donor</span>
                        <span class="text-sm font-semibold text-gray-900 dark:text-white"><?= esc($baris['id_jenis_donor'] ?? '-') ?></span>
                    </div>
                    <div class="pt-2 border-t border-gray-200 dark:border-gray-700 flex justify-between items-center">
                        <div>
                            <span class="block text-sm font-medium text-gray-500 dark:text-gray-400">Petugas</span>
                            <span class="text-sm font-semibold text-gray-900 dark:text-white"><?= esc($baris['nama_petugas'] ?? '-') ?></span>
                        </div>
                        <div>
                            <?php if (!empty($baris['id_status_pengambilan'])): ?>
                                <?php if (isset($baris['id_status_pengambilan']) && $baris['id_status_pengambilan'] === 'Berhasil'): ?>
                                    <span class="inline-flex items-center gap-x-1.5 py-1 px-2.5 rounded-lg text-sm font-semibold bg-teal-100 text-teal-800 dark:bg-teal-900/40 dark:text-teal-400">
                                        <span class="w-1.5 h-1.5 inline-block rounded-lg bg-teal-500"></span> Berhasil
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center gap-x-1.5 py-1 px-2.5 rounded-lg text-sm font-semibold bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-400">
                                        <span class="w-1.5 h-1.5 inline-block rounded-lg bg-red-500"></span> Gagal
                                    </span>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

            </div>

            <div class="flex items-center justify-between mt-8 mb-4">
                <h4 class="text-xl font-bold text-gray-800 dark:text-white">Penggunaan BHP</h4>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <div class="flex flex-col">
                    <div class="mb-2 flex items-center gap-x-2">
                        <span class="w-2 h-2 rounded-full bg-teal-500"></span>
                        <span class="text-sm font-bold text-gray-700 dark:text-gray-300">Logistik BHP Medis</span>
                    </div>
                    <div class="overflow-hidden border border-gray-200 rounded-xl bg-white dark:bg-slate-900 dark:border-gray-800 shadow-sm h-full">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400 border-collapse">
                            <thead class="bg-gray-50 text-gray-700 dark:bg-slate-800 dark:text-gray-400">
                                <tr class="text-xs font-bold uppercase text-gray-400 border-b border-gray-200 dark:border-gray-700">
                                    <th class="p-3 w-1/3 text-center">Kode</th> 
                                    <th class="p-3 w-1/2">Nama Barang</th>
                                    <th class="p-3 w-1/4 text-center">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-800 text-gray-800 dark:text-gray-200">
                                <?php if(!empty($bhp_medis)): foreach($bhp_medis as $item): ?>
                                <tr class="hover:bg-gray-50/50 dark:hover:bg-slate-800/30 transition">
                                    <td class="p-3 text-center font-medium text-gray-500">#<?= esc((string)$item['kode_barang']) ?></td>
                                    <td class="p-3 font-semibold text-gray-900 dark:text-white"><?= esc($item['nama_barang']) ?></td>
                                    <td class="p-3 text-center font-bold text-gray-900 dark:text-white"><?= esc((string)$item['jumlah']) ?></td>
                                </tr>
                                <?php endforeach; else: ?>
                                <tr>
                                    <td colspan="3" class="text-center py-4 text-gray-400 italic dark:text-gray-500 bg-gray-50/10">
                                        Tidak ada penggunaan BHP Medis
                                    </td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="flex flex-col">
                    <div class="mb-2 flex items-center gap-x-2">
                        <span class="w-2 h-2 rounded-full bg-teal-500"></span>
                        <span class="text-sm font-bold text-gray-700 dark:text-gray-300">Logistik BHP Non-Medis</span>
                    </div>
                    <div class="overflow-hidden border border-gray-200 rounded-xl bg-white dark:bg-slate-900 dark:border-gray-800 shadow-sm h-full">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400 border-collapse">
                            <thead class="bg-gray-50 text-gray-700 dark:bg-slate-800 dark:text-gray-400">
                                <tr class="text-xs font-bold uppercase text-gray-400 border-b border-gray-200 dark:border-gray-700">
                                    <th class="p-3 w-1/3 text-center">Kode</th> 
                                    <th class="p-3 w-1/2">Nama Barang</th>
                                    <th class="p-3 w-1/4 text-center">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-800 text-gray-800 dark:text-gray-200">
                                <?php if(!empty($bhp_penunjang)): foreach($bhp_penunjang as $item): ?>
                                <tr class="hover:bg-gray-50/50 dark:hover:bg-slate-800/30 transition">
                                    <td class="p-3 text-center font-medium text-gray-500">#<?= esc((string)$item['kode_barang']) ?></td>
                                    <td class="p-3 font-semibold text-gray-900 dark:text-white"><?= esc($item['nama_barang']) ?></td>
                                    <td class="p-3 text-center font-bold text-gray-900 dark:text-white"><?= esc((string)$item['jumlah']) ?></td>
                                </tr>
                                <?php endforeach; else: ?>
                                <tr>
                                    <td colspan="3" class="text-center py-4 text-gray-400 italic dark:text-gray-500 bg-gray-50/10">
                                        Tidak ada penggunaan BHP Non-Medis
                                    </td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

            <div class="mt-5 pt-5 border-t border-gray-200 dark:border-gray-800 flex justify-end gap-x-2">
                <a href="javascript:history.back()" class="py-2 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-white dark:hover:bg-gray-800 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                    Kembali
                </a>
            </div>

        </div>
    </div>
</div>

<?= $this->endSection(); ?>