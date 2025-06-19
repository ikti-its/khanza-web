<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<!-- Table Section -->
<div class="max-w-[85rem] py-6 lg:py-3 px-8 mx-auto">
    <!-- Card -->
    <div class="flex flex-col">
        <div class="-m-1.5 overflow-y-auto">
            <div class="sm:px-6 min-w-full inline-block align-middle">
                <div class="p-5 bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden dark:bg-slate-900 dark:border-gray-700">
                    <!-- Header -->
                    <div class="py-1 flex justify-between items-center border-gray-200 dark:border-gray-700">
                        <div>
                            <h2 class="mb-2 text-xl font-semibold text-gray-800 dark:text-gray-200">
                                Stok Keluar Barang Medis
                            </h2>

                        </div>
                        <div class="flex gap-x-2 md:items-start">
                            <?= view('components/tambah_button', [
                                'link' => '/stokkeluarmedis/tambah'
                            ]) ?>
                        </div>
                    </div>
                    <?= view('components/search_bar') ?>
                    <!-- End Header -->

                    <!-- Table -->
                    <table id="myTable" class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <?php 
                            $widths  = [20, 20, 20, 20, 20];
                            echo view('components/tabel_colgroup',['widths' => $widths]);
                            
                            $columns = [
                                'Tanggal',
                                'No Keluar',
                                'Asal Lokasi',
                                'Pegawai',
                                'Aksi'
                            ];
                            echo view('components/tabel_thead',['kolom' => $columns]);
                        ?>
        
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            <?php foreach ($stok_keluar_medis_data as $stok) : ?>
                                <div id="hs-vertically-centered-scrollable-modal-<?= $stok['id'] ?>" class="hs-overlay hidden size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto pointer-events-none">
                                    <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto h-[calc(100%-3.5rem)] min-h-[calc(100%-3.5rem)] flex items-center">
                                        <div class="w-full max-h-full overflow-hidden flex flex-col bg-white border shadow-sm rounded-xl pointer-events-auto dark:bg-neutral-800 dark:border-neutral-700 dark:shadow-neutral-700/70">
                                            <div class="flex justify-between items-center py-3 px-4 border-b dark:border-neutral-700">
                                                <h3 class="font-bold text-gray-800 dark:text-white">
                                                    <?= $stok['no_keluar'] ?>
                                                </h3>
                                                <button type="button" class="flex justify-center items-center size-7 text-sm font-semibold rounded-full border border-transparent text-gray-800 hover:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-neutral-700" data-hs-overlay="#hs-vertically-centered-scrollable-modal-<?= $stok['id'] ?>">
                                                    <span class="sr-only">Close</span>
                                                    <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <path d="M18 6 6 18"></path>
                                                        <path d="m6 6 12 12"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                            <div class="p-4 overflow-y-auto">
                                                <div class="space-y-4">
                                                    <div>
                                                        <div class="mb-5 sm:block md:flex items-center">
                                                            <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Nomor Keluar</label>
                                                            <input type="text" name="" value="<?= $stok['no_keluar'] ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
                                                        </div>

                                                        <div class="mb-5 sm:block md:flex items-center">
                                                            <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Tanggal Stok Keluar</label>
                                                            <input type="text" name="" value="<?php
                                                                                                // Tanggal asli dari data
                                                                                                $original_date = $stok['tanggal_stok_keluar'];


                                                                                                // Format tanggal sebagai dd-Bulan-yyyy (misal: 27 Juni 2024)

                                                                                                // Pisahkan tanggal, bulan, dan tahun dari tanggal asli
                                                                                                $day = date("d", strtotime($original_date));
                                                                                                $month = date("m", strtotime($original_date));
                                                                                                $year = date("Y", strtotime($original_date));

                                                                                                // Daftar nama bulan dalam bahasa Indonesia
                                                                                                $bulan = array(
                                                                                                    1 => "Januari", 2 => "Februari", 3 => "Maret", 4 => "April", 5 => "Mei", 6 => "Juni",
                                                                                                    7 => "Juli", 8 => "Agustus", 9 => "September", 10 => "Oktober", 11 => "November", 12 => "Desember"
                                                                                                );

                                                                                                // Format tanggal sesuai dengan format yang diinginkan
                                                                                                $formatted_date = $day . ' ' . $bulan[(int)$month] . ' ' . $year;

                                                                                                echo $formatted_date;

                                                                                                ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
                                                        </div>


                                                        <div class="mb-5 sm:block md:flex items-center">
                                                            <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Pegawai</label>
                                                            <input type="text" name="" value="<?php foreach ($pegawai_data as $pegawai) {
                                                                                                    if ($pegawai['id'] === $stok['id_pegawai']) {
                                                                                                        echo $pegawai['nama'];
                                                                                                    }
                                                                                                } ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
                                                        </div>
                                                        <div class="mb-5 sm:block md:flex items-center">
                                                            <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Asal Lokasi</label>
                                                            <input type="text" name="" value="<?php foreach ($ruangan_data as $ruangan) {
                                                                                                    if ($ruangan['id'] === $stok['id_ruangan']) {
                                                                                                        echo $ruangan['nama'];
                                                                                                    }
                                                                                                } ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
                                                        </div>

                                                        <div class="mb-5 sm:block md:flex items-center">
                                                            <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Keterangan</label>
                                                            <input type="text" name="" value="<?= $stok['keterangan'] ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="pt-2 border-t border-[#F1F1F1]">
                                                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Barang Medis</h3>
                                                        <div>

                                                            <div class="flex items-center justify-between mb-2">
                                                                <div class="w-1/2">


                                                                </div>
                                                                <div class="flex justify-end w-1/2">
                                                                    <p class="font-bold mr-2 text-center text-gray-900 text-sm rounded-lg w-full">Jumlah keluar</p>
                                                                </div>
                                                            </div>



                                                            <?php
                                                            foreach ($transaksi_keluar_data as $transaksi) {
                                                                if ($transaksi['id_stok_keluar'] === $stok['id']) {
                                                            ?>

                                                                    <div class="flex items-center justify-between">
                                                                        <div class="w-1/2 font-medium">
                                                                            <?php foreach ($medis_data as $medis) {
                                                                                if ($medis['id'] === $transaksi['id_barang_medis']) {
                                                                                    echo $medis['nama'];
                                                                                }
                                                                            } ?>
                                                                            <br>
                                                                        </div>
                                                                        <div class="flex justify-end w-1/2">
                                                                            <input type="text" name="" value="<?php foreach ($medis_data as $medis) {
                                                                                                                    if ($medis['id'] === $transaksi['id_barang_medis']) {

                                                                                                                        foreach ($satuan_data as $satuan) {
                                                                                                                            if ($medis['id_satbesar'] === $satuan['id']) {
                                                                                                                                if ($satuan['id'] === 1) {
                                                                                                                                    echo $transaksi['jumlah_keluar'];
                                                                                                                                } else {
                                                                                                                                    echo $transaksi['jumlah_keluar'] . " " . $satuan['nama'];
                                                                                                                                }
                                                                                                                            }
                                                                                                                        }
                                                                                                                    }
                                                                                                                } ?>" class="text-center bg-gray-100 font-[600] text-gray-900 text-sm rounded-lg px-2 py-1 w-full dark:border-gray-600 dark:text-white" readonly>
                                                                        </div>
                                                                    </div>

                                                                    <br>

                                                            <?php }
                                                            } ?>

                                                        </div>
                                                    </div>



                                                </div>
                                            </div>
                                            <div class="flex justify-end items-center gap-x-2 py-3 px-4 border-t dark:border-neutral-700">
                                                <button type="button" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-800" data-hs-overlay="#hs-vertically-centered-scrollable-modal-<?= $stok['id'] ?>">
                                                    Tutup
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <tr>
                                    <td class="h-px w-64 whitespace-nowrap">
                                        <div class="px-6 py-3 text-center">
                                            <span class="block text-sm font-semibold text-gray-800 cursor-pointer dark:text-gray-200 hover:underline"><?php $original_date = $stok['tanggal_stok_keluar'];
                                                                                                                                                        $day = date("d", strtotime($original_date));
                                                                                                                                                        $month = date("m", strtotime($original_date));
                                                                                                                                                        $year = date("Y", strtotime($original_date));

                                                                                                                                                        // Daftar nama bulan dalam bahasa Indonesia
                                                                                                                                                        $bulan = array(
                                                                                                                                                            1 => "Januari", 2 => "Februari", 3 => "Maret", 4 => "April", 5 => "Mei", 6 => "Juni",
                                                                                                                                                            7 => "Juli", 8 => "Agustus", 9 => "September", 10 => "Oktober", 11 => "November", 12 => "Desember"
                                                                                                                                                        );

                                                                                                                                                        // Format tanggal sesuai dengan format yang diinginkan
                                                                                                                                                        $formatted_date = $day . ' ' . $bulan[(int)$month] . ' ' . $year;

                                                                                                                                                        echo $formatted_date; ?></span>
                                        </div>
                                    </td>
                                    <td class="h-px w-72 whitespace-nowrap">
                                        <div class="px-6 py-3 text-center">
                                            <span class="block text-sm font-semibold cursor-pointer hover:underline text-gray-800 dark:text-gray-200" data-hs-overlay="#hs-vertically-centered-scrollable-modal-<?= $stok['id'] ?>"><?= $stok['no_keluar'] ?? 'N/A' ?></span>
                                        </div>
                                    </td>
                                    <td class="h-px w-72 whitespace-nowrap">
                                        <div class="px-6 py-3 text-center">
                                            <span class="block cursor-default text-sm font-semibold text-gray-800 dark:text-gray-200"><?php foreach ($ruangan_data as $ruangan) {
                                                                                                                                            if ($ruangan['id'] === $stok['id_ruangan']) {
                                                                                                                                                echo $ruangan['nama'];
                                                                                                                                            }
                                                                                                                                        } ?></span>
                                        </div>
                                    </td>
                                    <td class="h-px w-72 whitespace-nowrap">
                                        <div class="px-6 py-3 text-center">
                                            <span class="block cursor-default text-sm font-semibold text-gray-800 dark:text-gray-200"><?php foreach ($pegawai_data as $pegawai) {
                                                                                                                                            if ($pegawai['id'] === $stok['id_pegawai']) {
                                                                                                                                                echo $pegawai['nama'];
                                                                                                                                            }
                                                                                                                                        } ?></span>
                                        </div>
                                    </td>
                                    <td class="size-px whitespace-nowrap">
                                        <div class="px-3 py-1.5 text-center flex justify-center">
                                            <?php
                                                $row_id  = $stok['id'];
                                                $api_url = '/stokkeluarmedis';
                                                echo view('components/aksi_ubah',[
                                                    'row_id'  => $row_id,
                                                    'api_url' => $api_url   
                                                ]);
                                                echo view('components/aksi_hapus',[
                                                    'row_id'  => $row_id,
                                                    'api_url' => $api_url   
                                                ]); 
                                            ?>
                                        </div>
                                    </td>


                                </tr>

                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <!-- End Table -->

                    <!-- Footer -->
                    <div class="px-6 py-4 grid gap-3 md:flex md:justify-between md:items-center border-t border-gray-200 dark:border-neutral-700">
                        <!-- Pagination -->

                    </div>


                    <!-- End Footer -->
                </div>
            </div>
        </div>
    </div>
    <!-- End Card -->
</div>
<!-- End Table Section -->

<?= $this->endSection(); ?>