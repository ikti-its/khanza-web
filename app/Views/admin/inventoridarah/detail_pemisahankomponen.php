<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<div class="max-w-[85rem] py-6 lg:py-3 px-8 mx-auto animate-fade-in">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-7 dark:bg-slate-900 dark:border-gray-800">
        
        <div class="flex flex-col items-center text-center pb-5 border-b border-gray-200 dark:border-gray-800">
            <div>
                <?= view('components/form/judul', [
                    'judul' => 'Detail Pemisahan Komponen'
                ]) ?>
            </div>
            <div class="flex flex-col items-center">
                <span class="text-sm font-medium text-gray-500 dark:text-gray-500 block mb-1">Nomor Bag</span>
                <span class="inline-flex items-center px-4 py-1.5 rounded-lg text-sm font-mono font-bold bg-teal-50 text-teal-700 border border-teal-200 dark:bg-teal-950/40 dark:text-teal-400 dark:border-teal-900 shadow-sm">
                    <?= esc($baris['no_bag'] ?? '-') ?>
                </span>
            </div>
        </div>
        
        <div class="space-y-6">
            <div class="sm:block md:flex items-center py-3">
                <span class="block mb-1 md:mb-0 text-sm font-medium text-gray-500 dark:text-gray-500 md:w-1/4">Nomor Pengambilan</span>
                <div class="w-full lg:w-1/4">
                    <span class="text-sm font-semibold text-gray-900 dark:text-white">
                        <?= esc($baris['nomor_pengambilan'] ?? '-') ?>
                    </span>
                </div>

                <span class="block mt-4 md:my-0 md:ml-10 md:mr-12 mb-1 text-sm font-medium text-gray-500 dark:text-gray-500 md:w-1/4">Tanggal Pemisahan</span>
                <div class="w-full lg:w-1/4">
                    <span class="text-sm font-semibold text-gray-900 dark:text-white">
                        <?= !empty($baris['tanggal_pemisahan']) ? (new IntlDateFormatter('id_ID', IntlDateFormatter::LONG, IntlDateFormatter::NONE))->format(strtotime($baris['tanggal_pemisahan'])) : '-' ?>
                    </span>
                </div>
            </div>

            <div class="sm:block md:flex items-center py-3">
                <span class="block mb-1 md:mb-0 text-sm font-medium text-gray-500 dark:text-gray-500 md:w-1/4">Shift</span>
                <div class="w-full lg:w-1/4">
                    <span class="text-sm font-semibold text-gray-900 dark:text-white">
                        <?= esc($baris['id_shift'] ?? '-') ?>
                    </span>
                </div>

                <span class="block mt-4 md:my-0 md:ml-10 md:mr-12 mb-1 text-sm font-medium text-gray-500 dark:text-gray-500 md:w-1/4">Petugas Pelaksana</span>
                <div class="w-full lg:w-1/4">
                    <span class="text-sm font-semibold text-gray-900 dark:text-white">
                        <?= esc($baris['nama_petugas'] ?? '-') ?>
                    </span>
                </div>
            </div>

            <div class="mt-4 mb-4">
                <h4 class="text-sm font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500 flex items-center gap-x-2">
                    <svg class="w-4 h-4 text-blue-500 overflow-visible flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M4 5a2 2 0 012-2h12a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V5z" />
                        
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3.5" 
                              class="text-blue-500 dark:text-blue-400"
                              d="M7 11.5l3.5 3.5L22.5 3.5" />
                    </svg>
                    <span class="ml-1">Hasil Komponen Darah</span>
                </h4>
            </div>
            
            <?php if (!empty($komponen_terpilih)) : ?>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-8">
                    <?php foreach ($komponen_terpilih as $komponen) : ?>
                        <div class="relative group overflow-hidden bg-gradient-to-br from-white to-slate-50/50 p-4 rounded-xl border border-gray-200 shadow-sm transition-all duration-200 hover:shadow-md hover:border-blue-300 dark:from-slate-900 dark:to-slate-900/50 dark:border-gray-800 dark:hover:border-blue-900">
                            
                            <div class="absolute top-0 bottom-0 left-0 w-1 bg-blue-500 rounded-l-xl"></div>
                            
                            <div class="pl-2 flex flex-col h-full justify-between gap-y-3">
                                <div>
                                    <div class="mb-1.5">
                                        <span class="text-xs font-mono font-bold uppercase px-2 py-0.5 bg-blue-50 text-blue-700 rounded-md border border-blue-100 dark:bg-blue-950/40 dark:text-blue-400 dark:border-blue-900/60 shadow-sm">
                                            <?= esc($komponen['kode_komponen']) ?>
                                        </span>
                                    </div>
                                    <h5 class="text-sm font-bold text-gray-800 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                                        <?= esc($komponen['nama_komponen']) ?>
                                    </h5>
                                </div>
                                
                                <div class="pt-2.5 border-t border-gray-100 dark:border-gray-800 flex items-center justify-between text-xs">
                                    <span class="text-gray-400 dark:text-gray-500 font-medium">Masa Berlaku:</span>
                                    <span class="font-bold text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-slate-800 px-2 py-1 rounded-md">
                                        <?= esc($komponen['masa_berlaku_hari']) ?> Hari
                                    </span>
                                </div>
                            </div>

                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else : ?>
                <div class="flex flex-col items-center justify-center p-8 mb-8 border border-dashed border-gray-200 rounded-xl bg-gray-50/50 dark:border-gray-800 dark:bg-slate-900/40">
                    <svg class="w-8 h-8 text-gray-300 dark:text-gray-600 mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 13.5h3.86a2.25 2.25 0 012.008 1.24l.885 1.77a2.25 2.25 0 002.007 1.24h1.98a2.25 2.25 0 002.007-1.24l.885-1.77a2.25 2.25 0 012.007-1.24h3.86m-18 0h18m-18 0V9A2.25 2.25 0 014.5 6.75h15A2.25 2.25 0 0121.75 9v4.5m-18 0V19.5A2.25 2.25 0 004.5 21.75h15a2.25 2.25 0 002.25-2.25V13.5M9 7.5h6" />
                    </svg>
                    <span class="text-sm text-gray-400 italic dark:text-gray-500">Tidak ada komponen darah yang dihasilkan dari pemisahan ini.</span>
                </div>
            <?php endif; ?>

            <div class="flex items-center justify-between mt-10 mb-4">
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

        </div>

        <div class="mt-5 pt-5 border-t border-gray-200 dark:border-gray-800 flex justify-end gap-x-2">
            <a href="javascript:history.back()" class="py-2.5 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-white dark:hover:bg-gray-800 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                Kembali
            </a>
        </div>

    </div>
</div>

<?= $this->endSection(); ?>