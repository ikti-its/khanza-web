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


                            <div>
                                <a href='/datamedis/tambah' class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-[#0A2D27] text-[#ACF2E7] hover:bg-[#13594E] disabled:opacity-50 disabled:pointer-events-none dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" href="#">
                                    <svg class="flex-shrink-0 size-3" xmlns="http://www.w3.org/2000/svg" width="16" height="1" viewBox="0 0 16 16" fill="none">
                                        <path d="M2.63452 7.50001L13.6345 7.5M8.13452 13V2" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                                    </svg>
                                    Tambah
                                </a>
                            </div>

                        </div>
                    </div>
                    <div class="py-4 grid gap-3 md:items-start">
                        <div class="sm:col-span-1">
                            <label for="hs-as-table-product-review-search" class="sr-only">Search</label>
                            <div class="relative">
                                <input type="text" id="myInput" onkeyup="myFunction()" class="py-2 px-4 ps-11 block border w-full xl:w-96 border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600" placeholder="Search">
                                <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none ps-4">
                                    <svg class="size-4 text-gray-400 dark:text-neutral-500" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- End Header -->

                    <!-- Table -->

                    <table id="myTable" class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <colgroup>
                            <!-- <col width="5%"> -->
                            <col width="30%">
                            <col width="25%">
                            <!-- <col width="20%"> -->
                            <col width="20%">
                            <col width="25%">
                        </colgroup>
                        <thead class="bg-gray-50 dark:bg-slate-800">
                            <tr>
                                <!-- <th scope="col" class="ps-6 py-3 text-start">
                                    <label for="hs-at-with-checkboxes-main" class="flex">
                                        <input type="checkbox" class="shrink-0 border-gray-300 rounded text-blue-600 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-600 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800" id="hs-at-with-checkboxes-main">
                                        <span class="sr-only">Checkbox</span>
                                    </label>
                                </th> -->

                                <th scope="col" class="px-6 py-3">
                                    <div class="flex items-center justify-center gap-x-2">
                                        <span class="text-xs tracking-wide text-[#666] dark:text-gray-200">
                                            Nama
                                        </span>
                                    </div>
                                </th>

                                <th scope="col" class="px-6 py-3">
                                    <div class="flex justify-center gap-x-2">
                                        <span class="text-xs tracking-wide text-[#666] dark:text-gray-200">
                                            Jenis Barang Medis
                                        </span>
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    <div class="flex items-center justify-center gap-x-2">
                                        <span class="text-xs tracking-wide text-[#666] dark:text-gray-200">
                                            Stok
                                        </span>
                                    </div>
                                </th>

                                <th scope="col" class="px-6 py-3">
                                    <div class="flex items-center justify-center gap-x-2">
                                        <span class="text-xs tracking-wide text-[#666] dark:text-gray-200">
                                            Aksi
                                        </span>
                                    </div>
                                </th>

                            </tr>
                        </thead>




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
                                                        <?php foreach ($obat_data as $jenis) {
                                                            if ($medis['id'] === $jenis['id_barang_medis']) {
                                                                if ($medis['jenis'] === 'Obat') { ?>
                                                                    <div class="mb-5 sm:block md:flex items-center">
                                                                        <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Industri Farmasi</label>
                                                                        <input type="text" name="" value="<?php
                                                                                                            $optionsIF = [
                                                                                                                "1000" => "Kalbe Farma"
                                                                                                            ];
                                                                                                            foreach ($optionsIF as $valueIF => $textIF) {
                                                                                                                if ($valueIF === $jenis['industri_farmasi']) {
                                                                                                                    echo $textIF;
                                                                                                                    break; // Stop the loop once a match is found
                                                                                                                }
                                                                                                            }
                                                                                                            ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
                                                                    </div>

                                                                    <div class="mb-5 sm:block md:flex items-center">
                                                                        <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Kandungan</label>
                                                                        <input type="text" name="kandungan" value="<?= $jenis['kandungan'] ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
                                                                    </div>
                                                                    <div class="mb-5 sm:block md:flex items-center">
                                                                        <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Isi</label>
                                                                        <input type="text" name="" value="<?= $jenis['isi'] ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>

                                                                    </div>


                                                                    <div class="mb-5 sm:block md:flex items-center">
                                                                        <label class="block mt-5 md:my-0 text-sm text-gray-900 dark:text-white md:w-1/2">Satuan Besar</label>
                                                                        <input type="text" name="" value="<?php
                                                                                                            foreach ($satuan_data as $satuan) {
                                                                                                                if ($satuan['id'] === $medis['satuan']) {
                                                                                                                    echo $satuan['nama'];
                                                                                                                }
                                                                                                            }
                                                                                                            ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
                                                                    </div>
                                                                    <div class="mb-5 sm:block md:flex items-center">
                                                                        <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Kapasitas</label>
                                                                        <input type="text" name="" value="<?= $jenis['kapasitas'] ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>

                                                                    </div>
                                                                    <div class="mb-5 sm:block md:flex items-center">
                                                                        <label class="block mt-5 md:my-0 text-sm text-gray-900 dark:text-white md:w-1/2">Satuan Kecil</label>
                                                                        <input type="text" name="" value="<?php
                                                                                                            foreach ($satuan_data as $satuan) {
                                                                                                                if ($satuan['id'] === $jenis['satuan']) {
                                                                                                                    echo $satuan['nama'];
                                                                                                                }
                                                                                                            }
                                                                                                            ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
                                                                    </div>
                                                                    <div class="mb-5 sm:block md:flex items-center">
                                                                        <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Jenis</label>
                                                                        <input type="text" name="" value="<?php
                                                                                                            $optionsjenis = [
                                                                                                                "1000" => "Obat Oral",
                                                                                                                "2000" => "Obat Topikal",
                                                                                                                "3000" => "Obat Injeksi",
                                                                                                                "4000" => "Obat Sublingual"
                                                                                                            ];
                                                                                                            foreach ($optionsjenis as $valuejenis => $textjenis) {
                                                                                                                if ($valuejenis === $jenis['jenis']) {
                                                                                                                    echo $textjenis;
                                                                                                                }
                                                                                                            }
                                                                                                            ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
                                                                    </div>
                                                                    <div class="mb-5 sm:block md:flex items-center">
                                                                        <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Kategori</label>
                                                                        <input type="text" name="" value="<?php
                                                                                                            $optionskategori = [
                                                                                                                "1000" => "Obat Paten",
                                                                                                                "2000" => "Obat Generik",
                                                                                                                "3000" => "Obat Merek",
                                                                                                                "4000" => "Obat Eksklusif"
                                                                                                            ];
                                                                                                            foreach ($optionskategori as $valuekategori => $textkategori) {
                                                                                                                if ($valuekategori === $jenis['kategori']) {
                                                                                                                    echo $textkategori;
                                                                                                                    break; // Stop the loop once a match is found
                                                                                                                }
                                                                                                            }
                                                                                                            ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
                                                                    </div>

                                                                    <div class="mb-5 sm:block md:flex items-center">
                                                                        <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Golongan</label>
                                                                        <input type="text" name="" value="<?php
                                                                                                            $optionsgolongan = [
                                                                                                                "1000" => "Analgesik",
                                                                                                                "2000" => "Antibiotik",
                                                                                                                "3000" => "Antijamur",
                                                                                                                "4000" => "Antivirus"
                                                                                                            ];
                                                                                                            foreach ($optionsgolongan as $valuegolongan => $textgolongan) {
                                                                                                                if ($valuegolongan === $jenis['golongan']) {
                                                                                                                    echo $textgolongan;
                                                                                                                    break; // Stop the loop once a match is found
                                                                                                                }
                                                                                                            }
                                                                                                            ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
                                                                    </div>
                                                                    <div class="mb-5 sm:block md:flex items-center">
                                                                        <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Tanggal Kadaluwarsa</label>
                                                                        <input type="date" name="" value="<?= $jenis['kadaluwarsa'] ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
                                                                    </div>


                                                        <?php }
                                                            }
                                                        } ?>

                                                        <div class="mb-5 sm:block md:flex items-center">
                                                            <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Harga</label>
                                                            <input type="text" name="" value="<?= $medis['harga'] ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
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
                                    <td class="size-px w-48 whitespace-nowrap">
                                        <div class="px-6 py-3 text-center">
                                            <span class="inline-flex items-center gap-x-1.5 py-1.5 px-3 rounded-full text-xs font-semibold bg-[#F1F1F1]">
                                                <span class="size-1.5 inline-block rounded-full bg-[#535353]"></span>
                                                <?= $medis['jenis'] ?? 'N/A' ?>
                                            </span>
                                        </div>
                                    </td>
                                    <td class="h-px w-72 whitespace-nowrap">
                                        <div class="px-6 py-3">
                                            <span class="text-center block cursor-default text-sm font-semibold text-gray-800 dark:text-gray-200"><?= $medis['stok'] ?? 'N/A' ?></span>
                                        </div>
                                    </td>
                                    <td class="size-px whitespace-nowrap">
                                        <div class="px-3 py-1.5 text-center inline-flex">
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
                                            <div class="px-3 py-1.5">
                                                <button class="gap-x-1 text-sm text-red-600 decoration-2 hover:underline font-semibold dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" onclick="openModal('modelConfirm-<?= $medis['id'] ?>')" href="#">
                                                    Hapus
                                                </button>
                                                <div id="modelConfirm-<?= $medis['id'] ?>" class="fixed hidden z-[70] inset-0 bg-gray-900 bg-opacity-60 overflow-y-auto h-full w-full px-4 ">
                                                    <div class="relative top-40 mx-auto shadow-xl rounded-md bg-white max-w-md">

                                                        <div class="flex justify-end p-2">
                                                            <button onclick="closeModal('modelConfirm-<?= $medis['id'] ?>')" type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center">
                                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                                                </svg>
                                                            </button>
                                                        </div>

                                                        <div class="p-6 pt-0 text-center">
                                                            <div class="flex justify-center mb-6">
                                                                <!-- Container for SVG, centered -->
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="49" height="48" viewBox="0 0 49 48" fill="none">
                                                                    <path d="M8.5 11H40.5" stroke="#DA4141" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                                                                    <path d="M18.5 5H30.5" stroke="#DA4141" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                                                                    <path d="M12.5 17H36.5V40C36.5 41.6569 35.1569 43 33.5 43H15.5C13.8431 43 12.5 41.6569 12.5 40V17Z" fill="#FEE2E2" stroke="#DA4141" stroke-width="4" stroke-linejoin="round" />
                                                                    <path d="M20.5 25L28.5 33" stroke="#DA4141" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                                                                    <path d="M28.5 25L20.5 33" stroke="#DA4141" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                                                                </svg>
                                                            </div>
                                                            Hapus data
                                                            <h3 class="text-xl text-wrap font-normal text-gray-500 mt-5 mb-6">Apakah anda yakin
                                                                untuk menghapus data ini?</h3>
                                                            <form action="/datamedis/hapus/<?= $medis['id'] ?>" method="POST">
                                                                <?= csrf_field() ?>
                                                                <div class="w-full sm:flex justify-center">
                                                                    <input type="hidden" name="_method" value="DELETE">
                                                                    <button onclick="closeModal('modelConfirm-<?= $medis['id'] ?>')" class="w-full text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-base inline-flex items-center justify-center px-3 py-2.5 text-center mr-2">
                                                                        Hapus
                                                                    </button>
                                                                    <a href="#" onclick="closeModal('modelConfirm-<?= $medis['id'] ?>')" class="w-full text-gray-900 bg-white hover:bg-gray-100 focus:ring-4 focus:ring-cyan-200 border border-gray-200 font-medium inline-flex items-center justify-center rounded-lg text-base px-3 py-2.5 text-center" data-modal-toggle="delete-user-modal">
                                                                        Batal
                                                                    </a>
                                                                </div>
                                                            </form>
                                                        </div>

                                                    </div>
                                                </div>
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
                        <nav class="flex w-full justify-between items-center gap-x-1">
                            <!-- Previous Button -->
                            <div class="inline-flex gap-x-2">
                                <button type="button" class="min-h-[38px] min-w-[38px] py-2 px-2.5 inline-flex justify-center items-center gap-x-2 text-sm rounded-lg text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-white/10 dark:focus:bg-white/10" aria-label="Previous page" <?= $meta_data['page'] <= 1 ? 'disabled' : '' ?> onclick="window.location.href='/datamedis?page=<?= $meta_data['page'] - 1 ?>&size=<?= $meta_data['size'] ?>'">
                                    <svg class="flex-shrink-0 size-3.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="m15 18-6-6 6-6"></path>
                                    </svg>
                                    <span aria-hidden="true" class="hidden sm:block">Previous</span>
                                </button>
                            </div>

                            <!-- Page Numbers -->
                            <div class="flex items-center gap-x-1">
                                <?php
                                $total_pages = $meta_data['total'];
                                $current_page = $meta_data['page'];
                                $range = 2; // Number of pages to show before and after the current page
                                $show_items = ($range * 2) + 1;

                                if ($total_pages <= $show_items) {
                                    for ($i = 1; $i <= $total_pages; $i++) {
                                        echo '<button type="button" class="min-h-[38px] min-w-[38px] flex justify-center items-center ' . ($current_page == $i ? 'bg-gray-200 text-gray-800 dark:bg-neutral-600 dark:focus:bg-neutral-500' : 'text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:text-white dark:hover:bg-white/10 dark:focus:bg-white/10') . ' py-2 px-3 text-sm rounded-lg" ' . ($current_page == $i ? 'aria-current="page"' : '') . ' onclick="window.location.href=\'/datamedis?page=' . $i . '&size=' . $meta_data['size'] . '\'">' . $i . '</button>';
                                    }
                                } else {
                                    if ($current_page > $range + 1) {
                                        echo '<button type="button" class="min-h-[38px] min-w-[38px] flex justify-center items-center text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:text-white dark:hover:bg-white/10 dark:focus:bg-white/10 py-2 px-3 text-sm rounded-lg" onclick="window.location.href=\'/datamedis?page=1&size=' . $meta_data['size'] . '\'">1</button>';
                                        if ($current_page > $range + 2) {
                                            echo '<span class="py-2 px-3 text-sm">...</span>';
                                        }
                                    }

                                    for ($i = max($current_page - $range, 1); $i <= min($current_page + $range, $total_pages); $i++) {
                                        echo '<button type="button" class="min-h-[38px] min-w-[38px] flex justify-center items-center ' . ($current_page == $i ? 'bg-gray-200 text-gray-800 dark:bg-neutral-600 dark:focus:bg-neutral-500' : 'text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:text-white dark:hover:bg-white/10 dark:focus:bg-white/10') . ' py-2 px-3 text-sm rounded-lg" ' . ($current_page == $i ? 'aria-current="page"' : '') . ' onclick="window.location.href=\'/datamedis?page=' . $i . '&size=' . $meta_data['size'] . '\'">' . $i . '</button>';
                                    }

                                    if ($current_page < $total_pages - $range - 1) {
                                        if ($current_page < $total_pages - $range - 2) {
                                            echo '<span class="py-2 px-3 text-sm">...</span>';
                                        }
                                        echo '<button type="button" class="min-h-[38px] min-w-[38px] flex justify-center items-center text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:text-white dark:hover:bg-white/10 dark:focus:bg-white/10 py-2 px-3 text-sm rounded-lg" onclick="window.location.href=\'/datamedis?page=' . $total_pages . '&size=' . $meta_data['size'] . '\'">' . $total_pages . '</button>';
                                    }
                                }
                                ?>
                            </div>

                            <!-- Next Button -->
                            <div class="inline-flex gap-x-2">
                                <button type="button" class="min-h-[38px] min-w-[38px] py-2 px-2.5 inline-flex justify-center items-center gap-x-2 text-sm rounded-lg text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-white/10 dark:focus:bg-white/10" aria-label="Next page" <?= $current_page >= $total_pages ? 'disabled' : '' ?> onclick="window.location.href='/datamedis?page=<?= $current_page + 1 ?>&size=<?= $meta_data['size'] ?>'">
                                    <span aria-hidden="true" class="hidden sm:block">Next</span>
                                    <svg class="flex-shrink-0 size-3.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="m9 18 6-6-6-6"></path>
                                    </svg>
                                </button>
                            </div>
                        </nav>
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


    window.openModal = function(modalId) {
        document.getElementById(modalId).style.display = 'block'
        document.getElementsByTagName('body')[0].classList.add('overflow-y-hidden')
    }

    window.closeModal = function(modalId) {
        document.getElementById(modalId).style.display = 'none'
        document.getElementsByTagName('body')[0].classList.remove('overflow-y-hidden')
    }
</script>
<?= $this->endSection(); ?>