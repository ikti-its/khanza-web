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
            
            <!-- Petugas Penanggung Jawab & Tanggal Rusak -->
            <div class="sm:block md:flex items-center py-3">
                <span class="block mb-1 md:mb-0 text-sm font-medium text-gray-500 dark:text-gray-500 md:w-1/4">Petugas Penanggung Jawab</span>
                <div class="w-full lg:w-1/4">
                    <span class="text-sm font-semibold text-gray-900 dark:text-white">
                        <?= esc($baris['nama_petugas'] ?? '-') ?>
                    </span>
                </div>

                <span class="block mt-4 md:my-0 md:ml-10 md:mr-12 mb-1 text-sm font-medium text-gray-500 dark:text-gray-500 md:w-1/4">Tanggal Rusak</span>
                <div class="w-full lg:w-1/4">
                    <span class="text-sm font-semibold text-gray-900 dark:text-white">
                        <?= !empty($baris['tanggal_rusak']) ? 
                            (new IntlDateFormatter('id_ID', IntlDateFormatter::LONG, IntlDateFormatter::NONE))->format(strtotime($baris['tanggal_rusak'])) . ' ' . date('H:i', strtotime($baris['tanggal_rusak'])) 
                            : '-' ?>
                    </span>
                </div>
            </div>

            <!-- Keterangan / Alasan Kerusakan -->
            <div class="sm:block md:flex items-center py-3">
                <span class="block mb-1 md:mb-0 text-sm font-medium text-gray-500 dark:text-gray-500 md:w-1/4">Keterangan / Alasan</span>
                <div class="w-full lg:w-1/4">
                    <span class="text-sm font-semibold text-gray-900 dark:text-white">
                        <?= esc($baris['keterangan'] ?: '-') ?>
                    </span>
                </div>

                <span class="block mt-4 md:my-0 md:ml-10 md:mr-12 mb-1 md:w-1/4"></span>
                <div class="w-full lg:w-1/4"></div>
            </div>

            <!-- TABEL DETAIL: DAFTAR BHP MEDIS RUSAK -->
            <div class="pt-4 mt-8">
                <h3 class="text-sm font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-4 flex items-center gap-x-2">
                    Daftar BHP Medis Rusak
                </h3>
            </div>

            <div class="overflow-hidden border border-gray-200 rounded-xl bg-white dark:bg-slate-900 dark:border-gray-700 shadow-sm">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400 border-collapse">
                    <thead class="bg-gray-50 text-gray-700 dark:bg-slate-800 dark:text-gray-400">
                        <tr class="text-xs font-bold uppercase text-gray-400 border-b border-gray-200 dark:border-gray-700">
                            <th class="p-3 w-[18%] text-center">Kode Barang</th>
                            <th class="p-3 w-[30%]">Nama Barang</th>
                            <th class="p-3 text-center w-[18%]">Harga Beli</th>
                            <th class="p-3 text-center w-[12%]">Jumlah</th>
                            <th class="p-3 text-center w-[22%]">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-800 text-gray-800 dark:text-gray-200">
                        <?php if (!empty($detail_rusak)) : foreach ($detail_rusak as $item) : 
                            $hargaBeli = (float)$item['harga_beli'];
                            $jumlah = (int)$item['jumlah'];
                            $totalHarga = $hargaBeli * $jumlah;
                        ?>
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-slate-800/30 transition">
                                <td class="p-3 text-center font-medium text-gray-500">#<?= esc((string)$item['kode_barang']) ?></td>
                                <td class="p-3 font-semibold text-gray-900 dark:text-white"><?= esc($item['nama_barang']) ?></td>
                                <td class="p-3 text-center font-mono font-semibold text-gray-900 dark:text-white">Rp <?= number_format($hargaBeli, 0, ',', '.') ?></td>
                                <td class="p-3 text-center font-bold text-gray-900 dark:text-white"><?= esc((string)$jumlah) ?></td>
                                <td class="p-3 text-center font-mono font-bold text-gray-900 dark:text-white">Rp <?= number_format($totalHarga, 0, ',', '.') ?></td>
                            </tr>
                        <?php endforeach; else : ?>
                            <tr>
                                <td colspan="5" class="text-center py-6 text-gray-400 italic bg-gray-50/10">Belum ada daftar item barang rusak yang ditambahkan</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </div>

        <!-- Tombol Navigasi Kembali -->
        <div class="mt-8 pt-5 border-t border-gray-200 dark:border-gray-800 flex justify-end gap-x-2">
            <a href="javascript:history.back()" class="py-2.5 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-white dark:hover:bg-gray-800 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                Kembali
            </a>
        </div>

    </div>
</div>

<?= $this->endSection(); ?>