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
            
            <!-- Baris 1: Nomor Pengambilan & Tanggal Hasil Uji -->
            <div class="sm:block md:flex items-center py-3">
                <span class="block mb-1 md:mb-0 text-sm font-medium text-gray-500 dark:text-gray-500 md:w-1/4">Nomor Pengambilan</span>
                <div class="w-full lg:w-1/4">
                    <span class="text-sm font-semibold text-gray-900 dark:text-white">
                        <?= esc($baris['nomor_pengambilan'] ?? '-') ?>
                    </span>
                </div>

                <span class="block mt-4 md:my-0 md:ml-10 md:mr-12 mb-1 text-sm font-medium text-gray-500 dark:text-gray-500 md:w-1/4">Tanggal Hasil Uji</span>
                <div class="w-full lg:w-1/4">
                    <span class="text-sm font-semibold text-gray-900 dark:text-white">
                        <?= !empty($baris['tanggal_uji']) ? (new IntlDateFormatter('id_ID', IntlDateFormatter::LONG, IntlDateFormatter::NONE))->format(strtotime($baris['tanggal_uji'])) : '-' ?>
                    </span>
                </div>
            </div>

            <!-- Baris 2: Metode Uji & Petugas Pelaksana -->
            <div class="sm:block md:flex items-center py-3">
                <span class="block mb-1 md:mb-0 text-sm font-medium text-gray-500 dark:text-gray-500 md:w-1/4">Metode Uji</span>
                <div class="w-full lg:w-1/4">
                    <span class="text-sm font-semibold text-gray-900 dark:text-white">
                        <?= esc($baris['id_metode_uji'] ?? '-') ?>
                    </span>
                </div>

                <span class="block mt-4 md:my-0 md:ml-10 md:mr-12 mb-1 text-sm font-medium text-gray-500 dark:text-gray-500 md:w-1/4">Petugas Pelaksana</span>
                <div class="w-full lg:w-1/4">
                    <span class="text-sm font-semibold text-gray-900 dark:text-white">
                        <?= esc($baris['nama_petugas'] ?? '-') ?>
                    </span>
                </div>
            </div>

            <!-- Pembatas Visual Grid Parameter IMLTD -->
            <div class="pt-4 mt-6 border-t border-gray-100 dark:border-gray-800">
                <h4 class="text-sm font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-4">
                    Parameter Uji Saring IMLTD
                </h4>
            </div>

            <!-- Baris IMLTD 1: HBsAg & HCV -->
            <div class="sm:block md:flex items-center py-3">
                <span class="block mb-1 md:mb-0 text-sm font-medium text-gray-500 dark:text-gray-500 md:w-1/4">HBsAg (Hepatitis B)</span>
                <div class="w-full lg:w-1/4">
                    <?php if (isset($baris['hbsag']) && $baris['hbsag'] === '1'): ?>
                        <span class="inline-flex items-center gap-x-1.5 py-1 px-2.5 rounded-lg text-sm font-semibold bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-400">
                            <span class="w-1.5 h-1.5 inline-block rounded-lg bg-red-500"></span> Reaktif
                        </span>
                    <?php else: ?>
                        <span class="inline-flex items-center gap-x-1.5 py-1 px-2.5 rounded-lg text-sm font-semibold bg-teal-100 text-teal-800 dark:bg-teal-900/40 dark:text-teal-400">
                            <span class="w-1.5 h-1.5 inline-block rounded-lg bg-teal-500"></span> Non Reaktif
                        </span>
                    <?php endif; ?>
                </div>

                <span class="block mt-4 md:my-0 md:ml-10 md:mr-12 mb-1 text-sm font-medium text-gray-500 dark:text-gray-500 md:w-1/4">HCV (Hepatitis C)</span>
                <div class="w-full lg:w-1/4">
                    <?php if (isset($baris['hcv']) && $baris['hcv'] === '1'): ?>
                        <span class="inline-flex items-center gap-x-1.5 py-1 px-2.5 rounded-lg text-sm font-semibold bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-400">
                            <span class="w-1.5 h-1.5 inline-block rounded-lg bg-red-500"></span> Reaktif
                        </span>
                    <?php else: ?>
                        <span class="inline-flex items-center gap-x-1.5 py-1 px-2.5 rounded-lg text-sm font-semibold bg-teal-100 text-teal-800 dark:bg-teal-900/40 dark:text-teal-400">
                            <span class="w-1.5 h-1.5 inline-block rounded-lg bg-teal-500"></span> Non Reaktif
                        </span>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Baris IMLTD 2: HIV & Sifilis -->
            <div class="sm:block md:flex items-center py-3">
                <span class="block mb-1 md:mb-0 text-sm font-medium text-gray-500 dark:text-gray-500 md:w-1/4">HIV</span>
                <div class="w-full lg:w-1/4">
                    <?php if (isset($baris['hiv']) && $baris['hiv'] === '1'): ?>
                        <span class="inline-flex items-center gap-x-1.5 py-1 px-2.5 rounded-lg text-sm font-semibold bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-400">
                            <span class="w-1.5 h-1.5 inline-block rounded-lg bg-red-500"></span> Reaktif
                        </span>
                    <?php else: ?>
                        <span class="inline-flex items-center gap-x-1.5 py-1 px-2.5 rounded-lg text-sm font-semibold bg-teal-100 text-teal-800 dark:bg-teal-900/40 dark:text-teal-400">
                            <span class="w-1.5 h-1.5 inline-block rounded-lg bg-teal-500"></span> Non Reaktif
                        </span>
                    <?php endif; ?>
                </div>

                <span class="block mt-4 md:my-0 md:ml-10 md:mr-12 mb-1 text-sm font-medium text-gray-500 dark:text-gray-500 md:w-1/4">Sifilis</span>
                <div class="w-full lg:w-1/4">
                    <?php if (isset($baris['sifilis']) && $baris['sifilis'] === '1'): ?>
                        <span class="inline-flex items-center gap-x-1.5 py-1 px-2.5 rounded-lg text-sm font-semibold bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-400">
                            <span class="w-1.5 h-1.5 inline-block rounded-lg bg-red-500"></span> Reaktif
                        </span>
                    <?php else: ?>
                        <span class="inline-flex items-center gap-x-1.5 py-1 px-2.5 rounded-lg text-sm font-semibold bg-teal-100 text-teal-800 dark:bg-teal-900/40 dark:text-teal-400">
                            <span class="w-1.5 h-1.5 inline-block rounded-lg bg-teal-500"></span> Non Reaktif
                        </span>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Baris IMLTD 3: Malaria -->
            <div class="sm:block md:flex items-center py-3">
                <span class="block mb-1 md:mb-0 text-sm font-medium text-gray-500 dark:text-gray-500 md:w-1/4">Malaria</span>
                <div class="w-full lg:w-1/4">
                    <?php if (isset($baris['malaria']) && $baris['malaria'] === '1'): ?>
                        <span class="inline-flex items-center gap-x-1.5 py-1 px-2.5 rounded-lg text-sm font-semibold bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-400">
                            <span class="w-1.5 h-1.5 inline-block rounded-lg bg-red-500"></span> Reaktif
                        </span>
                    <?php elseif (isset($baris['malaria']) && $baris['malaria'] === '0'): ?>
                        <span class="inline-flex items-center gap-x-1.5 py-1 px-2.5 rounded-lg text-sm font-semibold bg-teal-100 text-teal-800 dark:bg-teal-900/40 dark:text-teal-400">
                            <span class="w-1.5 h-1.5 inline-block rounded-lg bg-teal-500"></span> Non Reaktif
                        </span>
                    <?php else: ?>
                        <span class="text-sm font-semibold text-gray-400 dark:text-gray-500">- (Tidak Diperiksa)</span>
                    <?php endif; ?>
                </div>

                <span class="block mt-4 md:my-0 md:ml-10 md:mr-12 mb-1 md:w-1/4"></span>
                <div class="w-full lg:w-1/4"></div>
            </div>

        </div>

        <!-- Tombol Navigasi Kembali -->
        <div class="mt-5 pt-5 border-t border-gray-200 dark:border-gray-800 flex justify-end gap-x-2">
            <a href="javascript:history.back()" class="py-2 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-white dark:hover:bg-gray-800 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                Kembali
            </a>
        </div>

    </div>
</div>

<?= $this->endSection(); ?>