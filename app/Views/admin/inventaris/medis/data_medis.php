<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<!-- Table Section -->
<div class="max-w-[85rem] py-6 lg:py-3 mx-auto">
    <!-- <div class="max-w-[85rem] w-full py-6 lg:py-3"> -->
    <!-- Card -->
    <div class="flex flex-col">
        <div class="-m-1.5 overflow-y-hidden">
            <div class="sm:px-20 min-w-full inline-block align-middle">
                <div class="p-5 bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-slate-900 dark:border-gray-700">

                    <!-- Header -->
                    <div class="py-1 flex justify-between items-center border-gray-200 dark:border-gray-700">
                        <div>
                            <h2 class="mb-2 text-xl font-black text-gray-800 dark:text-gray-200">
                                Barang Medis
                            </h2>

                        </div>
                        <div class="flex gap-x-6 justify-center items-center">
                            <?= view('components/data_tambah_button', [
                                'link' => '/datamedis/tambah'
                            ]) ?>

                        </div>
                    </div>
                    <?= view('components/data_search_bar') ?>

                    <!-- End Header -->

                    <!-- Table -->

                    <table id="myTable" class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <?php 
                            $widths  = [20, 20, 10, 15, 15, 20];
                            echo view('components/data_tabel_colgroup',['widths' => $widths]);
                            
                            $columns = [
                                'Nama',
                                'Jenis',
                                'Isi',
                                'Harga Dasar',
                                'Harga Beli',
                                'Aksi'
                            ];
                            echo view('components/data_tabel_thead',['columns' => $columns]);
                        ?>

                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            <?php foreach ($medis_data as $medis) : ?>
                                <div id="hs-vertically-centered-scrollable-modal-<?= $medis['id'] ?>" class="hs-overlay hidden size-full fixed top-0 start-0 z-[80] pointer-events-none">
                                    <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto h-[calc(100%-3.5rem)] min-h-[calc(100%-3.5rem)] flex items-center ">
                                        <div class="overflow-y-auto w-full max-h-full flex flex-col bg-white border shadow-sm rounded-xl pointer-events-auto dark:bg-neutral-800 dark:border-neutral-700 dark:shadow-neutral-700/70">
                                            <div class="flex justify-between items-center py-3 px-4 border-b dark:border-neutral-700">
                                                <h3 class="font-bold text-gray-800 dark:text-white">
                                                    <?= $medis['nama'] ?>
                                                </h3>
                                                <button type="button" class="flex justify-center items-center size-7 text-sm font-semibold rounded-full border border-transparent text-gray-800 hover:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-neutral-700" data-hs-overlay="#hs-vertically-centered-scrollable-modal-<?= $medis['id'] ?>">
                                                    <span class="sr-only">Close</span>
                                                    <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <path d="M18 6 6 18"></path>
                                                        <path d="m6 6 12 12"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                            <div class="p-4">
                                                <div class="space-y-4">
                                                    <div>
                                                        <div class="mb-5 sm:block md:flex items-center">
                                                            <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Nama</label>
                                                            <input type="text" name="kandungan" value="<?= $medis['nama'] ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
                                                        </div>

                                                        <div class="mb-5 sm:block md:flex items-center">
                                                            <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Industri Farmasi</label>
                                                            <input type="text" name="" value="<?php

                                                                                                foreach ($industri_data as $industri) {
                                                                                                    if ($industri['id'] === $medis['id_industri']) {
                                                                                                        echo $industri['nama'];
                                                                                                        break; // Stop the loop once a match is found
                                                                                                    }
                                                                                                }
                                                                                                ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
                                                        </div>

                                                        <div class="mb-5 sm:block md:flex items-center">
                                                            <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Kandungan</label>
                                                            <input type="text" name="kandungan" value="" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
                                                        </div>
                                                        <div class="mb-5 sm:block md:flex items-center">
                                                            <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Isi</label>
                                                            <input type="text" name="" value="<?= $medis['isi'] ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>

                                                        </div>


                                                        <div class="mb-5 sm:block md:flex items-center">
                                                            <label class="block mt-5 md:my-0 text-sm text-gray-900 dark:text-white md:w-1/2">Satuan Besar</label>
                                                            <input type="text" name="" value="<?php
                                                                                                foreach ($satuan_data as $satuan) {
                                                                                                    if ($satuan['id'] === $medis['id_satbesar']) {
                                                                                                        echo $satuan['nama'];
                                                                                                    }
                                                                                                }
                                                                                                ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
                                                        </div>
                                                        <div class="mb-5 sm:block md:flex items-center">
                                                            <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Kapasitas</label>
                                                            <input type="text" name="" value="<?= $medis['kapasitas'] ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>

                                                        </div>
                                                        <div class="mb-5 sm:block md:flex items-center">
                                                            <label class="block mt-5 md:my-0 text-sm text-gray-900 dark:text-white md:w-1/2">Satuan Kecil</label>
                                                            <input type="text" name="" value="<?php
                                                                                                foreach ($satuan_data as $satuan) {
                                                                                                    if ($satuan['id'] === $medis['id_satuan']) {
                                                                                                        echo $satuan['nama'];
                                                                                                    }
                                                                                                }
                                                                                                ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
                                                        </div>
                                                        <div class="mb-5 sm:block md:flex items-center">
                                                            <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Jenis</label>
                                                            <input type="text" name="" value="<?php
                                                                                                foreach ($jenis_data as $jenis) {
                                                                                                    if ($jenis['id'] === $medis['id_jenis']) {
                                                                                                        echo $jenis['nama'];
                                                                                                        break; // Stop the loop once a match is found
                                                                                                    }
                                                                                                }
                                                                                                ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
                                                        </div>
                                                        <div class="mb-5 sm:block md:flex items-center">
                                                            <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Kategori</label>
                                                            <input type="text" name="" value="<?php
                                                                                                foreach ($kategori_data as $kategori) {
                                                                                                    if ($kategori['id'] === $medis['id_kategori']) {
                                                                                                        echo $kategori['nama'];
                                                                                                        break; // Stop the loop once a match is found
                                                                                                    }
                                                                                                }
                                                                                                ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
                                                        </div>

                                                        <div class="mb-5 sm:block md:flex items-center">
                                                            <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Golongan</label>
                                                            <input type="text" name="" value="<?php
                                                                                                foreach ($golongan_data as $golongan) {
                                                                                                    if ($golongan['id'] === $medis['id_golongan']) {
                                                                                                        echo $golongan['nama'];
                                                                                                        break; // Stop the loop once a match is found
                                                                                                    }
                                                                                                }
                                                                                                ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
                                                        </div>
                                                        <div class="mb-5 sm:block md:flex items-center">
                                                            <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Tanggal Kadaluwarsa</label>
                                                            <input type="text" name="" value="<?= $medis['kadaluwarsa'] ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
                                                        </div>



                                                        <div class="mb-5 sm:block md:flex items-center">
                                                            <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Harga Dasar</label>
                                                            <input type="text" name="" value="<?= $medis['h_dasar'] ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
                                                        </div>
                                                        <div class="mb-5 sm:block md:flex items-center">
                                                            <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Harga Beli</label>
                                                            <input type="text" name="" value="<?= $medis['h_beli'] ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
                                                        </div>
                                                        <div class="mb-5 sm:block md:flex items-center">
                                                            <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Harga Ralan</label>
                                                            <input type="text" name="" value="<?= $medis['h_ralan'] ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
                                                        </div>
                                                        <div class="mb-5 sm:block md:flex items-center">
                                                            <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Harga Rnp Kelas 1</label>
                                                            <input type="text" name="" value="<?= $medis['h_kelas1'] ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
                                                        </div>
                                                        <div class="mb-5 sm:block md:flex items-center">
                                                            <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Harga Rnp Kelas 2</label>
                                                            <input type="text" name="" value="<?= $medis['h_kelas2'] ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
                                                        </div>
                                                        <div class="mb-5 sm:block md:flex items-center">
                                                            <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Harga Rnp Kelas 3</label>
                                                            <input type="text" name="" value="<?= $medis['h_kelas3'] ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
                                                        </div>
                                                        <div class="mb-5 sm:block md:flex items-center">
                                                            <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Harga Rnp Utama/BPJS</label>
                                                            <input type="text" name="" value="<?= $medis['h_utama'] ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
                                                        </div>
                                                        <div class="mb-5 sm:block md:flex items-center">
                                                            <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Harga Rnp Kelas VIP</label>
                                                            <input type="text" name="" value="<?= $medis['h_vip'] ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
                                                        </div>
                                                        <div class="mb-5 sm:block md:flex items-center">
                                                            <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Harga Rnp Kelas VVIP</label>
                                                            <input type="text" name="" value="<?= $medis['h_vvip'] ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
                                                        </div>
                                                        <div class="mb-5 sm:block md:flex items-center">
                                                            <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Harga Apotek Luar</label>
                                                            <input type="text" name="" value="<?= $medis['h_beliluar'] ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
                                                        </div>
                                                        <div class="mb-5 sm:block md:flex items-center">
                                                            <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Harga Jual Obat Bebas</label>
                                                            <input type="text" name="" value="<?= $medis['h_jualbebas'] ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
                                                        </div>
                                                        <div class="mb-5 sm:block md:flex items-center">
                                                            <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Harga Karyawan</label>
                                                            <input type="text" name="" value="<?= $medis['h_karyawan'] ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
                                                        </div>

                                                        <div class="mb-5 sm:block md:flex items-center">
                                                            <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Stok minimum</label>
                                                            <input type="text" name="" value="<?= $medis['stok_minimum'] ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
                                                        </div>

                                                    </div>
                                                </div>
                                                <div class="flex justify-end items-center gap-x-2 py-3 px-4 border-t dark:border-neutral-700">
                                                    <button type="button" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-800" data-hs-overlay="#hs-vertically-centered-scrollable-modal-<?= $medis['id'] ?>">
                                                        Tutup
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <tr>
                                    <td class="h-px w-64 whitespace-nowrap">
                                        <div class="px-6 py-3">
                                            <span class="text-center block text-sm font-semibold text-gray-800 cursor-pointer dark:text-gray-200 hover:underline" data-hs-overlay="#hs-vertically-centered-scrollable-modal-<?= $medis['id'] ?>" data-id="<?= $medis['id'] ?>"><?= $medis['nama'] ?? 'N/A' ?></span>
                                        </div>
                                    </td>
                                    <td class="h-px w-72 whitespace-nowrap">
                                        <div class="px-6 py-3">
                                            <span class=" text-center block cursor-default text-sm font-semibold text-gray-800 dark:text-gray-200"><?php foreach ($jenis_data as $jenis) {
                                                                                                                                                        if ($jenis['id'] === $medis['id_jenis']) {
                                                                                                                                                            echo $jenis['nama'];
                                                                                                                                                        }
                                                                                                                                                    } ?></span>
                                        </div>
                                    </td>
                                    <td class="h-px w-72 whitespace-nowrap">
                                        <div class="px-6 py-3">
                                            <span class=" text-center block cursor-default text-sm font-semibold text-gray-800 dark:text-gray-200"><?= $medis['isi'] ?? 'N/A' ?></span>
                                        </div>
                                    </td>
                                    <td class="h-px w-72 whitespace-nowrap">
                                        <div class="px-6 py-3">
                                            <span class="text-center block cursor-default text-sm font-semibold text-gray-800 dark:text-gray-200"><?= $medis['h_dasar'] ?? 'N/A' ?></span>
                                        </div>
                                    </td>
                                    <td class="h-px w-72 whitespace-nowrap">
                                        <div class="px-6 py-3">
                                            <span class="text-center block cursor-default text-sm font-semibold text-gray-800 dark:text-gray-200"><?= $medis['h_beli'] ?? 'N/A' ?></span>
                                        </div>
                                    </td>
                                    <td class="size-px whitespace-nowrap">
                                        <div class="px-3 py-1.5 text-center flex justify-center">
                                            <div class="px-3 py-1.5">
                                                <button type="button" class="gap-x-1 text-sm decoration-2 hover:underline font-semibold dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" data-hs-overlay="#hs-vertically-centered-scrollable-modal-<?= $medis['id'] ?>">
                                                    Lihat Detail
                                                </button>
                                            </div>
                                            <div class="px-3 py-1.5">
                                                <a href="/datamedis/edit/<?= $medis['id'] ?>" class="gap-x-1 text-sm text-blue-600 decoration-2 hover:underline font-semibold dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" href="#">
                                                    Ubah
                                                </a>
                                            </div>
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

                </div>
                <!-- End Footer -->
            </div>
        </div>
    </div>
</div>
</div>
<!-- End Card -->

<!-- End Table Section -->
<script>
    function myFunction() {
        var input, filter, table, tr, td, i, j, txtValue;
        input = document.getElementById("myInput");
        filter = input.value.toUpperCase();
        table = document.getElementById("myTable"); // Pastikan ini mengacu pada ID tabel yang benar

        if (!table) return; // Pastikan tabel ada sebelum melanjutkan

        tr = table.getElementsByTagName("tr");
        var dataFound = false;

        // Iterate over all table rows (including header row)
        for (i = 0; i < tr.length; i++) {
            var found = false;

            // Check if it's a regular row (skip header row)
            if (i > 0) {
                td = tr[i].getElementsByTagName("td");

                // Iterate over all td elements in the row
                for (j = 0; j < td.length; j++) {
                    txtValue = td[j].textContent || td[j].innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        found = true;
                        break; // Break out of inner loop if match found
                    }
                }

                // Show or hide row based on search result
                if (found) {
                    tr[i].style.display = "";
                    dataFound = true;
                } else {
                    tr[i].style.display = "none";
                }
            }
        }
    }


    
</script>
<?= $this->endSection(); ?>