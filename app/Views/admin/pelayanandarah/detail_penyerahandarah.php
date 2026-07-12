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
            
            <!-- Nomor Penyerahan & Nomor Permintaan -->
            <div class="sm:block md:flex items-center py-3">
                <span class="block mb-1 md:mb-0 text-sm font-medium text-gray-500 dark:text-gray-500 md:w-1/4">Nomor Penyerahan</span>
                <div class="w-full lg:w-1/4">
                    <span class="text-sm font-semibold text-gray-900 dark:text-white">
                        <?= esc($baris['no_penyerahan'] ?? '-') ?>
                    </span>
                </div>

                <span class="block mt-4 md:my-0 md:ml-10 md:mr-12 mb-1 text-sm font-medium text-gray-500 dark:text-gray-500 md:w-1/4">Nomor Permintaan</span>
                <div class="w-full lg:w-1/4">
                    <span class="text-sm font-semibold text-gray-900 dark:text-white">
                        <?= esc($baris['no_permintaan'] ?? '-') ?>
                    </span>
                </div>
            </div>

            <!-- Tanggal Penyerahan & Shift -->
            <div class="sm:block md:flex items-center py-3">
                <span class="block mb-1 md:mb-0 text-sm font-medium text-gray-500 dark:text-gray-500 md:w-1/4">Tanggal Penyerahan</span>
                <div class="w-full lg:w-1/4">
                    <span class="text-sm font-semibold text-gray-900 dark:text-white">
                        <?= !empty($baris['tanggal_penyerahan']) ? 
                            (new IntlDateFormatter('id_ID', IntlDateFormatter::LONG, IntlDateFormatter::NONE))->format(strtotime($baris['tanggal_penyerahan'])) . ' ' . date('H:i', strtotime($baris['tanggal_penyerahan'])) 
                            : '-' ?>
                    </span>
                </div>

                <span class="block mt-4 md:my-0 md:ml-10 md:mr-12 mb-1 text-sm font-medium text-gray-500 dark:text-gray-500 md:w-1/4">Shift</span>
                <div class="w-full lg:w-1/4">
                    <span class="text-sm font-semibold text-gray-900 dark:text-white">
                        <?= esc($baris['id_shift'] ?? '-') ?>
                    </span>
                </div>
            </div>

            <!-- Petugas Crossmatch & Keterangan -->
            <div class="sm:block md:flex items-center py-3">
                <span class="block mb-1 md:mb-0 text-sm font-medium text-gray-500 dark:text-gray-500 md:w-1/4">Petugas Crossmatch</span>
                <div class="w-full lg:w-1/4">
                    <span class="text-sm font-semibold text-gray-900 dark:text-white">
                        <?= esc($baris['nama_petugas_cross'] ?? '-') ?>
                    </span>
                </div>

                <span class="block mt-4 md:my-0 md:ml-10 md:mr-12 mb-1 text-sm font-medium text-gray-500 dark:text-gray-500 md:w-1/4">Keterangan</span>
                <div class="w-full lg:w-1/4">
                    <span class="text-sm font-semibold text-gray-900 dark:text-white">
                        <?= esc($baris['keterangan'] ?: '-') ?>
                    </span>
                </div>
            </div>

            <!-- Metode Pembayaran & Pengambil Darah -->
            <div class="sm:block md:flex items-center py-3">
                <span class="block mb-1 md:mb-0 text-sm font-medium text-gray-500 dark:text-gray-500 md:w-1/4">Metode Pembayaran</span>
                <div class="w-full lg:w-1/4">
                    <span class="text-sm font-semibold text-gray-900 dark:text-white">
                        <?= esc($baris['id_rekening'] ?? '-') ?>
                    </span>
                </div>

                <span class="block mt-4 md:my-0 md:ml-10 md:mr-12 mb-1 text-sm font-medium text-gray-500 dark:text-gray-500 md:w-1/4">Pengambil Darah</span>
                <div class="w-full lg:w-1/4">
                    <span class="text-sm font-semibold text-gray-900 dark:text-white">
                        <?= esc($baris['pengambil_darah'] ?? '-') ?>
                    </span>
                </div>
            </div>

            <!-- Alamat Pengambil & Penanggung Jawab -->
            <div class="sm:block md:flex items-center py-3">
                <span class="block mb-1 md:mb-0 text-sm font-medium text-gray-500 dark:text-gray-500 md:w-1/4">Alamat Pengambil</span>
                <div class="w-full lg:w-1/4">
                    <span class="text-sm font-semibold text-gray-900 dark:text-white">
                        <?= esc($baris['alamat_pengambil'] ?? '-') ?>
                    </span>
                </div>

                <span class="block mt-4 md:my-0 md:ml-10 md:mr-12 mb-1 text-sm font-medium text-gray-500 dark:text-gray-500 md:w-1/4">Penanggung Jawab</span>
                <div class="w-full lg:w-1/4">
                    <span class="text-sm font-semibold text-gray-900 dark:text-white">
                        <?= esc($baris['nama_pj'] ?? '-') ?>
                    </span>
                </div>
            </div>

            <!-- Hitung Akumulasi Total Tagihan -->
            <?php 
                $totalMurniDarah = 0;
                if (!empty($detail_darah)) {
                    foreach ($detail_darah as $darah) {
                        $totalMurniDarah += (float)$darah['jasa_sarana'] + (float)$darah['paket_bhp'] + (float)$darah['kso'] + (float)$darah['manajemen'];
                    }
                }
                $persentasePpn = (float)($baris['besar_ppn'] ?? 0);
                $nominalPpn = ($persentasePpn / 100) * $totalMurniDarah;
                $totalTagihanAkhir = $totalMurniDarah + $nominalPpn;
            ?>

            <!-- PPN (%) / Total Tagihan & Status Pembayaran -->
            <div class="sm:block md:flex items-center py-3">
                <span class="block mb-1 md:mb-0 text-sm font-medium text-gray-500 dark:text-gray-500 md:w-1/4">PPN (%) / Total Tagihan</span>
                <div class="w-full lg:w-1/4 flex items-center gap-x-2">
                    <span class="text-sm font-semibold text-gray-900 dark:text-white">
                        <?= esc((string)$baris['besar_ppn'] ?? '0') ?>%
                    </span>
                    <span class="text-gray-300">/</span>
                    <span class="text-sm font-bold text-gray-900 dark:text-white font-mono">
                        Rp <?= number_format($totalTagihanAkhir, 0, ',', '.') ?>
                    </span>
                </div>

                <span class="block mt-4 md:my-0 md:ml-10 md:mr-12 mb-1 text-sm font-medium text-gray-500 dark:text-gray-500 md:w-1/4">Status Pembayaran</span>
                <div class="w-full lg:w-1/4">
                    <span class="text-sm font-semibold text-gray-900 dark:text-white">
                        <?= esc($baris['id_status_pembayaran'] ?? '-') ?>
                    </span>
                </div>
            </div>

            <!-- DAFTAR KANTONG DARAH YANG DISERAHKAN -->
            <div class="pt-4 mt-8 border-t border-gray-100 dark:border-gray-800">
                <h3 class="text-sm font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-4 flex items-center gap-x-2">
                    <svg class="w-4 h-4 text-blue-500 overflow-visible flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a2 2 0 012-2h12a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V5z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3.5" class="text-blue-500 dark:text-blue-400" d="M7 11.5l3.5 3.5L22.5 3.5" />
                    </svg>
                    Daftar Kantong Darah yang Diserahkan
                </h3>
            </div>

            <div class="overflow-hidden border border-gray-200 rounded-xl bg-white dark:bg-slate-900 dark:border-gray-700 shadow-sm">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400 border-collapse">
                    <thead class="bg-gray-50 text-gray-700 dark:bg-slate-800 dark:text-gray-400">
                        <tr class="text-xs font-bold uppercase text-gray-400 border-b border-gray-200 dark:border-gray-700">
                            <th class="p-3 w-1/4 text-center">No. Kantong</th>
                            <th class="p-3 w-1/3">Komponen Darah</th>
                            <th class="p-3 text-center">Golongan Darah</th>
                            <th class="p-3 text-center">Rhesus</th>
                            <th class="p-3 text-center">Biaya</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-800 text-gray-800 dark:text-gray-200">
                        <?php if (!empty($detail_darah)) : foreach ($detail_darah as $darah) : 
                            $biayaKantong = (float)$darah['jasa_sarana'] + (float)$darah['paket_bhp'] + (float)$darah['kso'] + (float)$darah['manajemen'];
                        ?>
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-slate-800/30 transition">
                                <td class="p-3 text-center font-mono font-bold text-gray-900 dark:text-white"><?= esc($darah['no_kantong']) ?></td>
                                <td class="p-3 font-semibold text-gray-900 dark:text-white"><?= esc($darah['nama_komponen']) ?></td>
                                <td class="p-3 text-center font-semibold text-gray-900 dark:text-white"><?= esc($darah['nama_golongan_darah'] ?? '-') ?></td>
                                <td class="p-3 text-center font-semibold text-gray-900 dark:text-white"><?= esc($darah['kode_rhesus'] ?? '-') ?></td>
                                <td class="p-3 text-center font-mono font-bold text-gray-900 dark:text-white">Rp <?= number_format($biayaKantong, 0, ',', '.') ?></td>
                            </tr>
                        <?php endforeach; else : ?>
                            <tr>
                                <td colspan="5" class="text-center py-6 text-gray-400 italic bg-gray-50/10">Belum ada daftar kantong darah yang dipilih untuk diserahkan</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- SECTION PENGGUNAAN BHP -->
            <div class="flex items-center justify-between mt-10 mb-4">
                <h4 class="text-xl font-bold text-gray-800 dark:text-white">Penggunaan BHP</h4>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- BHP Medis -->
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

                <!-- BHP Non-Medis -->
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

        <!-- Tombol Navigasi Kembali -->
        <div class="mt-5 pt-5 border-t border-gray-200 dark:border-gray-800 flex justify-end gap-x-2">
            <a href="javascript:history.back()" class="py-2.5 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-white dark:hover:bg-gray-800 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                Kembali
            </a>
        </div>

    </div>
</div>

<?= $this->endSection(); ?>