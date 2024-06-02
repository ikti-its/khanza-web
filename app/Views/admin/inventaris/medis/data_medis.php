<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<!-- Table Section -->
<div class="max-w-[85rem] py-6 mx-auto">
    <div class="px-4 mb-4">
        <!-- breadcrumbs -->
    </div>
    <!-- Card -->
    <div class="flex flex-col">
        <div class="-m-1.5 overflow-y-auto">
            <div class="sm:px-6 min-w-full inline-block align-middle">
                <div class="p-5 bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden dark:bg-slate-900 dark:border-gray-700">
                    <!-- Header -->
                    <div class="py-1 flex justify-between items-center border-gray-200 dark:border-gray-700">
                        <div>
                            <h2 class="mb-2 text-xl font-semibold text-gray-800 dark:text-gray-200">
                                Barang Medis
                            </h2>

                        </div>
                        <div class="flex gap-x-2 md:items-start">
                            <?php

                            // Count notifications
                            $today = new DateTime();
                            $notification_count = 0;
                            $notification_period = 30;

                            foreach ($medis_tanpa_params_data as $medis_tanpa_params) {
                                $combined_data = array_combine(['obat', 'bhp', 'alkes', 'darah'], [$obat_data, $bhp_data, $alkes_data, $darah_data]);
                                foreach ($combined_data as $jenis => $data) {
                                    foreach ($data as $item) {
                                        if ($medis_tanpa_params['id'] === $item['id_barang_medis']) {
                                            if ($jenis !== 'alkes') {
                                                $kadaluwarsa = new DateTime($item['kadaluwarsa']);
                                                $interval = $today->diff($kadaluwarsa);
                                                $days_left = (int)$interval->format('%r%a'); // %r is for the sign and %a is for the total number of days

                                                if ($days_left <= $notification_period && $days_left >= 0) {
                                                    // Increment the notification count
                                                    $notification_count++;
                                                }
                                            }
                                        }
                                    }
                                }
                            }

                            ?>

                            <div class="px-2 hs-dropdown relative inline-flex [--placement:bottom-right]">
                                <button id="reset-notification" id="hs-dropdown-with-header" type="button" class="w-[2.375rem] h-[2.375rem] inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-full border border-transparent text-gray-800 hover:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-gray-700 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600 relative">
                                    <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9" />
                                        <path d="M10.3 21a1.94 1.94 0 0 0 3.4 0" />
                                    </svg>
                                    <span id="notification-count" class="absolute top-[-8px] right-[-8px] bg-red-500 rounded-full w-5 h-5 flex items-center justify-center text-white text-xs"><?= $notification_count ?></span>
                                </button>

                                <div class="border z-20 hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden min-w-60 bg-white shadow-md rounded-lg p-2 dark:bg-gray-800 dark:border dark:border-gray-700" aria-labelledby="hs-dropdown-with-header">
                                    <div class="first:pt-0 last:pb-0">
                                        <?php
                                        $today = new DateTime();
                                        // Number of days before expiration to notify
                                        $notification_period = 30;
                                        foreach ($medis_tanpa_params_data as $medis_tanpa_params) {
                                            $combined_data = array_combine(['obat', 'bhp', 'alkes', 'darah'], [$obat_data, $bhp_data, $alkes_data, $darah_data]);
                                            foreach ($combined_data as $jenis => $data) {
                                                foreach ($data as $item) {
                                                    if ($medis_tanpa_params['id'] === $item['id_barang_medis']) {
                                                        if ($jenis !== 'alkes') {
                                                            $kadaluwarsa = new DateTime($item['kadaluwarsa']);
                                                            $interval = $today->diff($kadaluwarsa);
                                                            $days_left = (int)$interval->format('%r%a'); // %r is for the sign and %a is for the total number of days

                                                            if ($days_left <= $notification_period && $days_left >= 0) {
                                        ?>
                                                                <a class="notification-item flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:ring-2 focus:ring-blue-500 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-300" href="#">
                                                                    <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                                        <path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9" />
                                                                        <path d="M10.3 21a1.94 1.94 0 0 0 3.4 0" />
                                                                    </svg>
                                                                    Stok <?= $medis_tanpa_params['nama'] ?> kadaluwarsa <?= $item['kadaluwarsa'] ?>
                                                                </a>
                                        <?php
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                        ?>

                                    </div>
                                </div>
                            </div>

                            <div>
                                <a href='/tambahmedis' class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-teal-900 text-teal-200 hover:bg-teal-800 disabled:opacity-50 disabled:pointer-events-none dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" href="#">
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
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-slate-800">
                            <tr>
                                <th scope="col" class="ps-6 py-3 text-start">
                                    <label for="hs-at-with-checkboxes-main" class="flex">
                                        <input type="checkbox" class="shrink-0 border-gray-300 rounded text-blue-600 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-600 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800" id="hs-at-with-checkboxes-main">
                                        <span class="sr-only">Checkbox</span>
                                    </label>
                                </th>

                                <th scope="col" class="px-6 py-3 text-start">
                                    <div class="flex items-center gap-x-2">
                                        <span class="text-xs tracking-wide text-gray-800 dark:text-gray-200">
                                            Nama
                                        </span>
                                    </div>
                                </th>

                                <th scope="col" class="px-6 py-3 text-start">
                                    <div class="flex items-center gap-x-2">
                                        <span class="text-xs tracking-wide text-gray-800 dark:text-gray-200">
                                            Jenis Barang Medis
                                        </span>
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    <div class="flex items-center gap-x-2 justify-center">
                                        <span class="text-xs tracking-wide text-gray-800 dark:text-gray-200">
                                            Stok
                                        </span>
                                    </div>
                                </th>

                                <th scope="col" class="px-6 py-3">
                                    <div class="flex items-center gap-x-2">
                                        <span class="text-xs tracking-wide text-gray-800 dark:text-gray-200">
                                            Aksi
                                        </span>
                                    </div>
                                </th>

                            </tr>
                        </thead>




                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            <?php foreach ($medis_data as $medis) : ?>
                                <div id="hs-vertically-centered-scrollable-modal-<?= $medis['id'] ?>" class="hs-overlay hidden size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto pointer-events-none">
                                    <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto h-[calc(100%-3.5rem)] min-h-[calc(100%-3.5rem)] flex items-center">
                                        <div class="w-full max-h-full overflow-hidden flex flex-col bg-white border shadow-sm rounded-xl pointer-events-auto dark:bg-neutral-800 dark:border-neutral-700 dark:shadow-neutral-700/70">
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
                                            <div class="p-4 overflow-y-auto">
                                                <div class="space-y-4">
                                                    <div>
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
                                                                                                            ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
                                                                    </div>

                                                                    <div class="mb-5 sm:block md:flex items-center">
                                                                        <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Kandungan</label>
                                                                        <input type="text" name="kandungan" value="<?= $jenis['kandungan'] ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
                                                                    </div>
                                                                    <div class="mb-5 sm:block md:flex items-center">
                                                                        <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Isi</label>
                                                                        <input type="text" name="" value="<?= $jenis['isi'] ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>

                                                                    </div>


                                                                    <div class="mb-5 sm:block md:flex items-center">
                                                                        <label class="block mt-5 md:my-0 text-sm text-gray-900 dark:text-white md:w-1/2">Satuan Besar</label>
                                                                        <input type="text" name="" value="<?php
                                                                                                            foreach ($satuan_data as $satuan) {
                                                                                                                if ($satuan['id'] === $medis['satuan']) {
                                                                                                                    echo $satuan['nama'];
                                                                                                                }
                                                                                                            }
                                                                                                            ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
                                                                    </div>
                                                                    <div class="mb-5 sm:block md:flex items-center">
                                                                        <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Kapasitas</label>
                                                                        <input type="text" name="" value="<?= $jenis['kapasitas'] ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>

                                                                    </div>
                                                                    <div class="mb-5 sm:block md:flex items-center">
                                                                        <label class="block mt-5 md:my-0 text-sm text-gray-900 dark:text-white md:w-1/2">Satuan Kecil</label>
                                                                        <input type="text" name="" value="<?php
                                                                                                            foreach ($satuan_data as $satuan) {
                                                                                                                if ($satuan['id'] === $jenis['satuan']) {
                                                                                                                    echo $satuan['nama'];
                                                                                                                }
                                                                                                            }
                                                                                                            ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
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
                                                                                                            ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
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
                                                                                                            ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
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
                                                                                                            ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
                                                                    </div>
                                                                    <div class="mb-5 sm:block md:flex items-center">
                                                                        <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Tanggal Kadaluwarsa</label>
                                                                        <input type="date" name="" value="<?= $jenis['kadaluwarsa'] ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
                                                                    </div>
                                                                    <div class="mb-5 sm:block md:flex items-center">
                                                                        <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Stok</label>
                                                                        <input type="text" name="" value="<?= $medis['stok'] ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
                                                                    </div>
                                                                    <div class="mb-5 sm:block md:flex items-center">
                                                                        <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Harga</label>
                                                                        <input type="text" name="" value="<?= $medis['harga'] ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
                                                                    </div>
                                                        <?php }
                                                            }
                                                        } ?>
                                                        <?php foreach ($alkes_data as $jenis) {
                                                            if ($medis['id'] === $jenis['id_barang_medis']) {
                                                                if ($medis['jenis'] === 'Alat Kesehatan') { ?>
                                                                    <div class="mb-5 sm:block md:flex items-center">
                                                                        <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Jenis Barang Medis</label>
                                                                        <input type="text" name="" value="<?= $medis['jenis'] ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
                                                                    </div>
                                                                    <div class="mb-5 sm:block md:flex items-center">
                                                                        <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Satuan</label>
                                                                        <input type="text" name="" value="<?php
                                                                                                            foreach ($satuan_data as $satuan) {
                                                                                                                if ($satuan['id'] === $medis['satuan']) {
                                                                                                                    echo $satuan['nama'];
                                                                                                                }
                                                                                                            }
                                                                                                            ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
                                                                    </div>

                                                                    <div class="mb-5 sm:block md:flex items-center">
                                                                        <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Merek</label>
                                                                        <input type="text" name="merekalkes" value="<?php
                                                                                                                    $companies = array(
                                                                                                                        'Omron', 'Philips', 'GE Healthcare', 'Siemens Healthineers', 'Medtronic',
                                                                                                                        'Johnson & Johnson', 'Becton', 'Dickinson and Company (BD)', 'Stryker',
                                                                                                                        'Boston Scientific', 'Olympus Corporation', 'Roche Diagnostics'
                                                                                                                    );
                                                                                                                    foreach ($companies as $company) {
                                                                                                                        if ($company === $jenis['merek']) {
                                                                                                                            echo $company;
                                                                                                                        }
                                                                                                                    }
                                                                                                                    ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
                                                                    </div>
                                                                    <div class="mb-5 sm:block md:flex items-center">
                                                                        <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Stok</label>
                                                                        <input type="text" name="" value="<?= $medis['stok'] ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
                                                                    </div>
                                                                    <div class="mb-5 sm:block md:flex items-center">
                                                                        <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Harga</label>
                                                                        <input type="text" name="" value="<?= $medis['harga'] ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
                                                                    </div>
                                                        <?php }
                                                            }
                                                        } ?>
                                                        <?php foreach ($bhp_data as $jenis) {
                                                            if ($medis['id'] === $jenis['id_barang_medis']) {
                                                                if ($medis['jenis'] === 'Bahan Habis Pakai') { ?>
                                                                    <div class="mb-5 sm:block md:flex items-center">
                                                                        <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Jenis Barang Medis</label>
                                                                        <input type="text" name="" value="<?= $medis['jenis'] ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
                                                                    </div>
                                                                    <div class="mb-5 sm:block md:flex items-center">
                                                                        <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Jumlah</label>
                                                                        <input type="text" name="" value="<?= $jenis['jumlah'] ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
                                                                    </div>
                                                                    <div class="mb-5 sm:block md:flex items-center">
                                                                        <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Satuan</label>
                                                                        <input type="text" name="" value="<?php
                                                                                                            foreach ($satuan_data as $satuan) {
                                                                                                                if ($satuan['id'] === $medis['satuan']) {
                                                                                                                    echo $satuan['nama'];
                                                                                                                }
                                                                                                            }
                                                                                                            ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
                                                                    </div>
                                                                    <div class="mb-5 sm:block md:flex items-center">
                                                                        <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Tanggal Kadaluwarsa</label>
                                                                        <input type="date" name="" value="<?= $jenis['kadaluwarsa'] ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
                                                                    </div>
                                                                    <div class="mb-5 sm:block md:flex items-center">
                                                                        <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Stok</label>
                                                                        <input type="text" name="" value="<?= $medis['stok'] ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
                                                                    </div>
                                                                    <div class="mb-5 sm:block md:flex items-center">
                                                                        <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Harga</label>
                                                                        <input type="text" name="" value="<?= $medis['harga'] ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
                                                                    </div>
                                                        <?php }
                                                            }
                                                        } ?>
                                                        <?php foreach ($darah_data as $jenis) {
                                                            if ($medis['id'] === $jenis['id_barang_medis']) {
                                                                if ($medis['jenis'] === 'Darah') { ?>
                                                                    <div class="mb-5 sm:block md:flex items-center">
                                                                        <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Jenis Barang Medis</label>
                                                                        <input type="text" name="" value="<?= $medis['jenis'] ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
                                                                    </div>
                                                                    <div class="mb-5 sm:block md:flex items-center">
                                                                        <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Satuan</label>
                                                                        <input type="text" name="" value="<?php
                                                                                                            foreach ($satuan_data as $satuan) {
                                                                                                                if ($satuan['id'] === $medis['satuan']) {
                                                                                                                    echo $satuan['nama'];
                                                                                                                }
                                                                                                            }
                                                                                                            ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
                                                                    </div>
                                                                    <div class="mb-5 sm:block md:flex items-center">
                                                                        <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Jenis Darah</label>
                                                                        <input type="text" name="" value="<?= $jenis['jenis'] ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
                                                                    </div>
                                                                    <div class="mb-5 sm:block md:flex items-center">
                                                                        <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Keterangan</label>
                                                                        <input type="text" name="" value="<?= $jenis['keterangan'] ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
                                                                    </div>
                                                                    <div class="mb-5 sm:block md:flex items-center">
                                                                        <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Tanggal Kadaluwarsa</label>
                                                                        <input type="text" name="" value="<?php
                                                                                                            // Original date from the data
                                                                                                            $original_date = $jenis['kadaluwarsa'];
                                                                                                            // Format the date for comparison
                                                                                                            $kadaluwarsa_date = date("Y-m-d", strtotime($original_date));
                                                                                                            // Check if the date is 27 May 2024
                                                                                                            if ($kadaluwarsa_date === "0001-01-01") {
                                                                                                                echo "-";
                                                                                                            } else {
                                                                                                                // Format the date as dd-mm-yyyy
                                                                                                                echo date("d-F-Y", strtotime($original_date));
                                                                                                            }
                                                                                                            ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
                                                                    </div>
                                                                    <div class="mb-5 sm:block md:flex items-center">
                                                                        <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Stok</label>
                                                                        <input type="text" name="" value="<?= $medis['stok'] ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
                                                                    </div>
                                                                    <div class="mb-5 sm:block md:flex items-center">
                                                                        <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Harga</label>
                                                                        <input type="text" name="" value="<?= $medis['harga'] ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
                                                                    </div>

                                                        <?php }
                                                            }
                                                        } ?>
                                                    </div>
                                                </div>
                                                <div class="flex justify-end items-center gap-x-2 py-3 px-4 border-t dark:border-neutral-700">
                                                    <button type="button" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-800" data-hs-overlay="#hs-vertically-centered-scrollable-modal-<?= $medis['id'] ?>">
                                                        Close
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <tr>
                                        <td class="size-px whitespace-nowrap">
                                            <div class="ps-6 py-3">
                                                <label for="hs-at-with-checkboxes-1" class="flex">
                                                    <input type="checkbox" class="shrink-0 border-gray-300 rounded text-blue-600 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-600 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800" id="hs-at-with-checkboxes-1">
                                                    <span class="sr-only">Checkbox</span>
                                                </label>
                                            </div>
                                        </td>

                                        <td class="h-px w-64 whitespace-nowrap">
                                            <div class="px-6 py-3">
                                                <span class="block text-sm font-semibold text-gray-800 cursor-pointer dark:text-gray-200 hover:underline" data-hs-overlay="#hs-vertically-centered-scrollable-modal-<?= $medis['id'] ?>" data-id="<?= $medis['id'] ?>"><?= $medis['nama'] ?? 'N/A' ?></span>
                                            </div>
                                        </td>
                                        <td class="size-px w-48 whitespace-nowrap">
                                            <div class="px-6 py-3">
                                                <span class="py-1 px-1.5 cursor-default inline-flex items-center gap-x-1 text-xs font-medium bg-teal-100 text-teal-800 rounded-full dark:bg-teal-500/10 dark:text-teal-500">
                                                    <svg class="size-2.5" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
                                                    </svg>
                                                    <?= $medis['jenis'] ?? 'N/A' ?>
                                                </span>
                                            </div>
                                        </td>
                                        <td class="h-px w-72 whitespace-nowrap">
                                            <div class="px-6 py-3 text-center">
                                                <span class="block cursor-default text-sm font-semibold text-gray-800 dark:text-gray-200"><?= $medis['stok'] ?? 'N/A' ?></span>
                                            </div>
                                        </td>
                                        <td class="size-px whitespace-nowrap">
                                            <div class="px-3 py-1.5 inline-flex">
                                                <div class="px-3 py-1.5">
                                                    <button type="button" class="gap-x-1 text-sm decoration-2 hover:underline font-medium dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" data-hs-overlay="#hs-vertically-centered-scrollable-modal-<?= $medis['id'] ?>">
                                                        Lihat Detail
                                                    </button>
                                                </div>
                                                <div class="px-3 py-1.5">
                                                    <a href="/editmedis/<?= $medis['id'] ?>" class="gap-x-1 text-sm text-blue-600 decoration-2 hover:underline font-medium dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" href="#">
                                                        Edit
                                                    </a>
                                                </div>
                                                <div class="px-3 py-1.5">
                                                    <a href="/hapusmedis/<?= $medis['id'] ?>" class="gap-x-1 text-sm text-red-600 decoration-2 hover:underline font-medium dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" href="#">
                                                        Hapus
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
                                <?php for ($i = 1; $i <= $meta_data['total']; $i++) : ?>
                                    <button type="button" class="min-h-[38px] min-w-[38px] flex justify-center items-center <?= $meta_data['page'] == $i ? 'bg-gray-200 text-gray-800 dark:bg-neutral-600 dark:focus:bg-neutral-500' : 'text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:text-white dark:hover:bg-white/10 dark:focus:bg-white/10' ?> py-2 px-3 text-sm rounded-lg" <?= $meta_data['page'] == $i ? 'aria-current="page"' : '' ?> onclick="window.location.href='/datamedis?page=<?= $i ?>&size=<?= $meta_data['size'] ?>'">
                                        <?= $i ?>
                                    </button>
                                <?php endfor; ?>
                            </div>

                            <!-- Next Button -->
                            <div class="inline-flex gap-x-2">
                                <button type="button" class="min-h-[38px] min-w-[38px] py-2 px-2.5 inline-flex justify-center items-center gap-x-2 text-sm rounded-lg text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-white/10 dark:focus:bg-white/10" aria-label="Next page" <?= $meta_data['page'] >= $meta_data['total'] ? 'disabled' : '' ?> onclick="window.location.href='/datamedis?page=<?= $meta_data['page'] + 1 ?>&size=<?= $meta_data['size'] ?>'">
                                    <span aria-hidden="true" class="hidden sm:block">Next</span>
                                    <svg class="flex-shrink-0 size-3.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="m9 18 6-6-6-6"></path>
                                    </svg>
                                </button>
                            </div>
                        </nav>
                    </div>


                    <!-- End Footer -->
                </div>
            </div>
        </div>
    </div>
    <!-- End Card -->
</div>
<!-- End Table Section -->
<script>
    // document.addEventListener("DOMContentLoaded", function() {
    //     const resetButton = document.getElementById("reset-notification");

    //     resetButton.addEventListener("click", function() {
    //         // Reset the notification count to 0
    //         document.getElementById("notification-count").innerText = "0";
    //         // Redirect to the same page with reset parameter
    //         window.location.href = window.location.pathname + "?reset=true";
    //     });
    // });
</script>
<?= $this->endSection(); ?>