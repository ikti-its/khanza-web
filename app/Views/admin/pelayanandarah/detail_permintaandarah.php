<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<div class="max-w-[85rem] py-6 lg:py-3 px-8 mx-auto animate-fade-in">
    <!-- Card Utama -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-7 dark:bg-slate-900 dark:border-gray-800">
        
        <!-- Judul Modul -->
        <div>
            <?= view('components/form/judul', [
                'judul' => $judul
            ]) ?>
        </div>
        
        <!-- Blok Informasi Utama -->
        <div class="space-y-6">
            
            <!-- Baris 1: Nomor Permintaan & Nomor Rawat -->
            <div class="sm:block md:flex items-center py-3">
                <span class="block mb-1 md:mb-0 text-sm font-medium text-gray-500 dark:text-gray-500 md:w-1/4">Nomor Permintaan</span>
                <div class="w-full lg:w-1/4">
                    <span class="text-sm font-semibold text-gray-900 dark:text-white">
                        <?= esc($baris['no_permintaan'] ?? '-') ?>
                    </span>
                </div>

                <span class="block mt-4 md:my-0 md:ml-10 md:mr-12 mb-1 text-sm font-medium text-gray-500 dark:text-gray-500 md:w-1/4">Nomor Rawat</span>
                <div class="w-full lg:w-1/4">
                    <span class="text-sm font-mono font-semibold text-gray-900 dark:text-white">
                        <?= esc($baris['nomor_rawat'] ?? '-') ?>
                    </span>
                </div>
            </div>

            <!-- Baris 2: Nomor Rekam Medis & Nama Pasien -->
            <div class="sm:block md:flex items-center py-3">
                <span class="block mb-1 md:mb-0 text-sm font-medium text-gray-500 dark:text-gray-500 md:w-1/4">Nomor Rekam Medis</span>
                <div class="w-full lg:w-1/4">
                    <span class="text-sm font-mono font-semibold text-gray-900 dark:text-white">
                        <?= esc($baris['nomor_rm'] ?? '-') ?>
                    </span>
                </div>

                <span class="block mt-4 md:my-0 md:ml-10 md:mr-12 mb-1 text-sm font-medium text-gray-500 dark:text-gray-500 md:w-1/4">Nama Pasien</span>
                <div class="w-full lg:w-1/4">
                    <span class="text-sm font-semibold text-gray-900 dark:text-white">
                        <?= esc($baris['nama'] ?? '-') ?>
                    </span>
                </div>
            </div>

            <!-- Baris 3: Kamar Perawatan & Dokter Penanggung Jawab -->
            <div class="sm:block md:flex items-center py-3">
                <span class="block mb-1 md:mb-0 text-sm font-medium text-gray-500 dark:text-gray-500 md:w-1/4">Kamar</span>
                <div class="w-full lg:w-1/4">
                    <span class="text-sm font-semibold text-gray-900 dark:text-white">
                        <?= esc($baris['kamar'] ?? '-') ?>
                    </span>
                </div>

                <span class="block mt-4 md:my-0 md:ml-10 md:mr-12 mb-1 text-sm font-medium text-gray-500 dark:text-gray-500 md:w-1/4">Dokter Penanggung Jawab</span>
                <div class="w-full lg:w-1/4">
                    <span class="text-sm font-semibold text-gray-900 dark:text-white">
                        <?= esc($baris['nama_dokter'] ?? '-') ?>
                    </span>
                </div>
            </div>

            <!-- Baris 4: Tanggal Permintaan & Status Permintaan -->
            <div class="sm:block md:flex items-center py-3">
                <span class="block mb-1 md:mb-0 text-sm font-medium text-gray-500 dark:text-gray-500 md:w-1/4">Tanggal Permintaan</span>
                <div class="w-full lg:w-1/4">
                    <span class="text-sm font-semibold text-gray-900 dark:text-white">
                        <?= !empty($baris['tanggal_permintaan']) ? 
                            (new IntlDateFormatter('id_ID', IntlDateFormatter::LONG, IntlDateFormatter::NONE))->format(strtotime($baris['tanggal_permintaan'])) . ' ' . date('H:i', strtotime($baris['tanggal_permintaan'])) 
                            : '-' 
                        ?>
                    </span>
                </div>

                <span class="block mt-4 md:my-0 md:ml-10 md:mr-12 mb-1 text-sm font-medium text-gray-500 dark:text-gray-500 md:w-1/4">Status Permintaan</span>
                <div class="w-full lg:w-1/4">
                    <span class="text-sm font-semibold text-gray-900 dark:text-white">
                        <?= esc($baris['id_status_permintaan'] ?? '-') ?>
                    </span>
                </div>
            </div>

            <!-- Bagian Tabel Detail Kantong Darah yang Diminta -->
            <div class="pt-4">
                <h3 class="text-sm font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-4 flex items-center gap-x-2">
                    <svg class="w-4 h-4 text-blue-500 overflow-visible flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M4 5a2 2 0 012-2h12a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V5z" />
                        
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3.5" 
                              class="text-blue-500 dark:text-blue-400"
                              d="M7 11.5l3.5 3.5L22.5 3.5" />
                    </svg>
                    Detail Kantong Darah Yang Diminta
                </h3>
            </div>

            <div class="overflow-hidden border border-gray-200 rounded-xl bg-white dark:bg-slate-900 dark:border-gray-700 mb-6 shadow-sm">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="bg-gray-50 text-sm text-gray-700 dark:bg-slate-800 dark:text-gray-400">
                        <tr class="text-xs font-bold uppercase text-gray-400 border-b border-gray-200 dark:border-gray-700">
                            <th class="p-3 w-1/3">Komponen Darah</th>
                            <th class="p-3">Golongan Darah</th>
                            <th class="p-3">Rhesus</th>
                            <th class="p-3 w-32 text-center">Jumlah Kebutuhan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-slate-900 text-gray-800 dark:text-gray-200">
                        <?php if (!empty($detail_permintaan)) : ?>
                            <?php foreach ($detail_permintaan as $detail) : ?>
                                <tr class="hover:bg-gray-50/50 dark:hover:bg-slate-800/30 transition">
                                    <td class="p-3 font-semibold text-gray-900 dark:text-white">
                                        <?= esc($detail['nama_komponen']) ?>
                                    </td>
                                    <td class="p-3 font-medium text-gray-700 dark:text-gray-300">
                                        <?= esc($detail['nama_golongan_darah'] ?? '-') ?>
                                    </td>
                                    <td class="p-3 font-medium text-gray-700 dark:text-gray-300">
                                        <?= esc($detail['kode_rhesus'] ?? '-') ?>
                                    </td>
                                    <td class="p-3 text-center font-bold text-gray-900 dark:text-white">
                                        <?= esc((string)$detail['jumlah']) ?> Bag
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="4" class="text-center py-6 text-gray-400 italic bg-gray-50/10 dark:text-gray-500">
                                    Tidak ada detail komponen darah yang terlampir.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
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