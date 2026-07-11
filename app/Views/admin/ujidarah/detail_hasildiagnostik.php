<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<div class="max-w-[85rem] py-6 lg:py-3 px-8 mx-auto animate-fade-in">
    <!-- Card Utama -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-7 dark:bg-slate-900 dark:border-gray-800">
        
        <!-- Judul Halaman -->
        <div>
            <?= view('components/form/judul', [
                'judul' => $judul
            ]) ?>
        </div>
        
        <!-- Blok Informasi Utama -->
        <div class="space-y-6">
            
            <!-- Baris 1: Kasus Reaktif & Tanggal Hasil -->
            <div class="sm:block md:flex items-center py-3">
                <span class="block mb-1 md:mb-0 text-sm font-medium text-gray-500 dark:text-gray-500 md:w-1/4">Nomor Kasus Reaktif</span>
                <div class="w-full lg:w-1/4">
                    <span class="text-sm font-mono font-semibold text-gray-900 dark:text-white">
                        <?= esc($baris['nomor_kasus'] ?? '-') ?>
                    </span>
                </div>

                <span class="block mt-4 md:my-0 md:ml-10 md:mr-12 mb-1 text-sm font-medium text-gray-500 dark:text-gray-500 md:w-1/4">Tanggal Hasil</span>
                <div class="w-full lg:w-1/4">
                    <span class="text-sm font-semibold text-gray-900 dark:text-white">
                        <?= !empty($baris['tanggal_hasil']) ? (new IntlDateFormatter('id_ID', IntlDateFormatter::LONG, IntlDateFormatter::NONE))->format(strtotime($baris['tanggal_hasil'])) : '-' ?>
                    </span>
                </div>
            </div>

            <!-- Baris 2: Fasyankes Rujukan & Dokter Pemeriksa -->
            <div class="sm:block md:flex items-center py-3">
                <span class="block mb-1 md:mb-0 text-sm font-medium text-gray-500 dark:text-gray-500 md:w-1/4">Fasyankes Rujukan</span>
                <div class="w-full lg:w-1/4">
                    <span class="text-sm font-semibold text-gray-900 dark:text-white">
                        <?= esc($baris['fasyankes_rujukan'] ?? '-') ?>
                    </span>
                </div>

                <span class="block mt-4 md:my-0 md:ml-10 md:mr-12 mb-1 text-sm font-medium text-gray-500 dark:text-gray-500 md:w-1/4">Dokter Pemeriksa</span>
                <div class="w-full lg:w-1/4">
                    <span class="text-sm font-semibold text-gray-900 dark:text-white">
                        <?= esc($baris['dokter_pemeriksa'] ?? '-') ?>
                    </span>
                </div>
            </div>

            <!-- Bagian Parameter Hasil Pemeriksaan Detail Rujukan -->
            <div class="pt-4">
                <h4 class="text-sm font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-4 flex items-center gap-x-2">
                    <svg class="w-4 h-4 text-blue-500 overflow-visible flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M4 5a2 2 0 012-2h12a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V5z" />
                        
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3.5" 
                              class="text-blue-500 dark:text-blue-400"
                              d="M7 11.5l3.5 3.5L22.5 3.5" />
                    </svg>
                    Hasil Tes Diagnostik
                </h4>
            </div>

            <?php if (!empty($detail_diagnostik)) : ?>
                <!-- Grid Tampilan Nilai Diagnostik -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-4">
                    <?php foreach ($detail_diagnostik as $detail) : ?>
                        <div class="relative overflow-hidden bg-gradient-to-br from-white to-slate-50/50 p-4 rounded-xl border border-gray-200 shadow-sm dark:from-slate-900 dark:to-slate-900/50 dark:border-gray-800">
                            <div class="absolute top-0 bottom-0 left-0 w-1 bg-blue-500 rounded-l-xl"></div>
                            
                            <div class="pl-2 flex flex-col justify-between h-full gap-y-2">
                                <div>
                                    <span class="block text-xs font-medium text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-1">
                                        Parameter Uji
                                    </span>
                                    <h5 class="text-sm font-bold text-gray-800 dark:text-white">
                                        <?= esc($detail['nama_parameter']) ?>
                                    </h5>
                                </div>
                                
                                <div class="pt-2 border-t border-gray-100 dark:border-gray-800 flex items-center justify-between">
                                    <span class="text-xs text-gray-400 dark:text-gray-500 font-medium">Nilai Diagnostik:</span>
                                    <?php 
                                    $nilai_clean = strtolower(trim((string)$detail['nama_nilai_diagnostik']));
                                    if (str_contains($nilai_clean, 'positif') || str_contains($nilai_clean, 'reaktif')): 
                                    ?>
                                        <span class="inline-flex items-center text-xs font-bold text-red-600 bg-red-50 px-2 py-1 rounded-md dark:bg-red-950/30 dark:text-red-400">
                                            <?= esc($detail['nama_nilai_diagnostik']) ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center text-xs font-bold text-teal-600 bg-teal-50 px-2 py-1 rounded-md dark:bg-teal-950/30 dark:text-teal-400">
                                            <?= esc($detail['nama_nilai_diagnostik']) ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else : ?>
                <!-- Tampilan Jika Detail Data Kosong -->
                <div class="flex flex-col items-center justify-center p-6 border border-dashed border-gray-200 rounded-xl bg-gray-50/50 dark:border-gray-800 dark:bg-slate-900/40">
                    <span class="text-sm text-gray-400 italic dark:text-gray-500">Tidak ada detail parameter uji yang tercatat.</span>
                </div>
            <?php endif; ?>

        </div>

        <!-- Tombol Kembali -->
        <div class="mt-5 pt-5 border-t border-gray-200 dark:border-gray-800 flex justify-end gap-x-2">
            <a href="javascript:history.back()" class="py-2 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-white dark:hover:bg-gray-800 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                Kembali
            </a>
        </div>

    </div>
</div>

<?= $this->endSection(); ?>