<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<!-- Table Section -->
<div class="max-w-[85rem] py-6 lg:py-3 px-8 mx-auto">
    <!-- Card -->
    <div class="flex flex-col">
        <div class="-m-1.5 overflow-y-auto">
            <div class="sm:px-6 min-w-full inline-block align-middle">
                <?php if (session()->getFlashdata('warning')) : ?>
                    <div id="warningMessage" class="flex items-center my-2 bg-[#FFF5CF] text-sm font-semibold text-[#D97706] rounded-lg p-4" role="alert">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                            <path d="M10 7.5V11.6667" stroke="#D97706" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M9.99986 17.8414H4.94986C2.0582 17.8414 0.849863 15.7747 2.24986 13.2497L4.84986 8.56641L7.29986 4.16641C8.7832 1.49141 11.2165 1.49141 12.6999 4.16641L15.1499 8.57474L17.7499 13.2581C19.1499 15.7831 17.9332 17.8497 15.0499 17.8497H9.99986V17.8414Z" stroke="#D97706" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M9.99561 14.166H10.0031" stroke="#D97706" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <span class="mx-1 font-semibold"></span><?= session()->getFlashdata('warning') ?>
                    </div>
                <?php endif; ?>
                <div class="p-5 bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden dark:bg-slate-900 dark:border-gray-700">
                    <!-- Header -->
                    <div class="py-1 flex justify-between items-center border-gray-200 dark:border-gray-700">
                        <div>
                            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">
                                Penerimaan Barang Medis
                            </h2>

                        </div>
                        <div>
                            <?= view('components/data_tambah_button', [
                                'link' => '/penerimaanmedis/tambah'
                            ]) ?>
                        </div>
                    </div>
                    <!-- End Header -->
                    <?= view('components/data_search_bar') ?>

                    <div id="noDataFound" style="display: none;">Data tidak ditemukan</div>
                    <!-- Table -->
                    <table id="myTable" class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <colgroup>
                            <col width="17%">
                            <col width="18%">
                            <col width="18%">
                            <col width="23%">
                            <col width="24%">
                        </colgroup>
                        <thead class="bg-gray-50 dark:bg-slate-800">
                            <tr>
                                <!-- <th scope="col" class="ps-6 py-3 text-start">
                                    <label for="hs-at-with-checkboxes-main" class="flex">
                                        <input type="checkbox" class="shrink-0 border-gray-300 rounded text-blue-600 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-600 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800" id="hs-at-with-checkboxes-main">
                                        <span class="sr-only">Checkbox</span>
                                    </label>
                                </th> -->

                                <th scope="col" class="px-6 py-3 text-start">
                                    <div class="flex items-center justify-center">
                                        <span class="text-xs tracking-wide text-[#666666]">
                                            Tanggal Datang
                                        </span>
                                    </div>
                                </th>

                                <th scope="col" class="px-6 py-3 text-start">
                                    <div class="flex items-center justify-center">
                                        <span class="text-xs tracking-wide text-[#666666]">
                                            Tanggal Jatuh Tempo
                                        </span>
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-3 text-start">
                                    <div class="flex items-center justify-center">
                                        <span class="text-xs tracking-wide text-[#666666]">
                                            Nomor Faktur
                                        </span>
                                    </div>
                                </th>
                                <!-- <th scope="col" class="px-6 py-3 text-start">
                                    <div class="flex items-center">
                                        <span class="text-xs tracking-wide text-gray-800 dark:text-gray-200">
                                            Nomor Faktur
                                        </span>
                                    </div>
                                </th> -->
                                <th scope="col" class="px-6 py-3 text-start">
                                    <div class="flex items-center justify-center">
                                        <span class="text-xs tracking-wide text-[#666666]">
                                            Supplier
                                        </span>
                                    </div>
                                </th>

                                <th scope="col" class="px-6 py-3">
                                    <div class="flex items-center justify-center">
                                        <span class="text-xs tracking-wide text-[#666666]">
                                            Aksi
                                        </span>
                                    </div>
                                </th>

                            </tr>
                        </thead>




                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">

                            <?php foreach ($penerimaan_data as $penerimaan) : ?>


                                <div id="hs-vertically-centered-scrollable-modal-<?= $penerimaan['id'] ?>" class="hs-overlay hidden size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto pointer-events-none">
                                    <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto h-[calc(100%-3.5rem)] min-h-[calc(100%-3.5rem)] flex items-center">
                                        <div class="w-full max-h-full overflow-hidden flex flex-col bg-white border shadow-sm rounded-xl pointer-events-auto dark:bg-neutral-800 dark:border-neutral-700 dark:shadow-neutral-700/70">
                                            <div class="flex justify-between items-center py-3 px-4 border-b dark:border-neutral-700">
                                                <h3 class="font-bold text-gray-800 dark:text-white">
                                                    <?= $penerimaan['no_faktur'] ?>
                                                </h3>
                                                <button type="button" class="flex justify-center items-center size-7 text-sm font-semibold rounded-full border border-transparent text-gray-800 hover:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-neutral-700" data-hs-overlay="#hs-vertically-centered-scrollable-modal-<?= $penerimaan['id'] ?>">
                                                    <span class="sr-only">Close</span>
                                                    <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <path d="M18 6 6 18"></path>
                                                        <path d="m6 6 12 12"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                            <div class="p-4 overflow-y-auto">
                                                <div class="space-y-12">
                                                    <div>
                                                        <div class="mb-5 sm:block md:flex items-center">
                                                            <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Nomor Faktur</label>
                                                            <input type="text" name="" value="<?= $penerimaan['no_faktur'] ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
                                                        </div>
                                                        <div class="mb-5 sm:block md:flex items-center">
                                                            <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Nomor Pemesanan</label>
                                                            <input type="text" name="" value="<?= $penerimaan['no_pemesanan'] ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
                                                        </div>

                                                        <div class="mb-5 sm:block md:flex items-center">
                                                            <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Tanggal Penerimaan</label>
                                                            <input type="text" name="" value="<?php
                                                                                                // Tanggal asli dari data
                                                                                                $original_date = $penerimaan['tanggal_datang'];

                                                                                                // Jika tanggal adalah "0001-01-01", tampilkan tanda hubung "-"
                                                                                                if ($original_date === "0001-01-01") {
                                                                                                    echo "-";
                                                                                                } else {
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
                                                                                                }
                                                                                                ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
                                                        </div>
                                                        <div class="mb-5 sm:block md:flex items-center">
                                                            <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Tanggal Faktur</label>
                                                            <input type="text" name="" value="<?php
                                                                                                // Tanggal asli dari data
                                                                                                $original_date = $penerimaan['tanggal_faktur'];


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
                                                            <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Tanggal Jatuh Tempo</label>
                                                            <input type="text" name="" value="<?php
                                                                                                // Tanggal asli dari data
                                                                                                $original_date = $penerimaan['tanggal_jthtempo'];


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
                                                                                                    if ($pegawai['id'] === $penerimaan['id_pegawai']) {
                                                                                                        echo $pegawai['nama'];
                                                                                                    }
                                                                                                } ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
                                                        </div>
                                                        <div class="mb-5 sm:block md:flex items-center">
                                                            <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Ruangan</label>
                                                            <input type="text" name="" value="<?php foreach ($ruangan_data as $ruangan) {
                                                                                                    if ($ruangan['id'] === $penerimaan['id_ruangan']) {
                                                                                                        echo $ruangan['nama'];
                                                                                                    }
                                                                                                } ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>

                                                        </div>
                                                    </div>
                                                    <div class="pt-2 border-t border-[#F1F1F1]">
                                                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Pesanan</h3>
                                                        <div>

                                                            <div class="flex items-center justify-between mb-2">
                                                                <div class="w-1/2">


                                                                </div>
                                                                <div class="flex justify-end w-1/2">
                                                                    <p class="font-bold mr-2 text-center text-gray-900 text-sm rounded-lg w-full">Harga/Item</p>
                                                                    <p class="font-bold text-center text-gray-900 text-sm rounded-lg w-full">Subtotal/Item</p>
                                                                </div>
                                                            </div>

                                                            <?php $subtotal = 0;
                                                            foreach ($detail_data as $detail) {
                                                                if ($detail['id_penerimaan'] === $penerimaan['id']) {
                                                                    $subtotal += $detail['total_per_item'] ?>

                                                                    <div class="flex items-center justify-between">
                                                                        <div class="w-1/2 font-medium">
                                                                            <?php foreach ($medis_data as $medis) {
                                                                                if ($medis['id'] === $detail['id_barang_medis']) {
                                                                                    echo $medis['nama'];
                                                                                }
                                                                            } ?>
                                                                            <br>
                                                                        </div>
                                                                        <div class="flex justify-end w-1/2">
                                                                            <input type="text" name="" value="<?= "Rp " . number_format($detail['h_pesan'], 0, ',', '.') ?? "Belum ada total" ?>" class="text-center mr-2 bg-gray-100 text-gray-900 text-sm rounded-lg px-2 py-1 w-full dark:border-gray-600 dark:text-white" readonly>
                                                                            <input type="text" name="" value="<?= "Rp " . number_format($detail['subtotal_per_item'], 0, ',', '.') ?? "Belum ada total" ?>" class="text-center bg-gray-100 font-[600] text-gray-900 text-sm rounded-lg px-2 py-1 w-full dark:border-gray-600 dark:text-white" readonly>
                                                                        </div>
                                                                    </div>
                                                                    <div><small>Jumlah:
                                                                            <?= $detail['jumlah'] ?> <?php foreach ($satuan_data as $satuan) {
                                                                                                            if ($satuan['id'] === $detail['id_satuan'] && $detail['id_satuan'] !== 1) {
                                                                                                                echo $satuan['nama'];
                                                                                                            } else {
                                                                                                                echo '';
                                                                                                            }
                                                                                                        } ?>
                                                                        </small></div>
                                                                    <div class="flex justify-between py-1">
                                                                        <label class="block mb-2 md:mb-0 text-sm font-[600] text-gray-900 dark:text-white md:w-1/2">Diskon Persen (Jumlah)</label>
                                                                        <label class="block mb-2 md:mb-0 text-sm font-[600] text-gray-900 dark:text-white"><?= number_format($detail['diskon_persen'], 0, ',', '.') . "% (Rp" . number_format($detail['diskon_jumlah'], 0, ',', '.') . ")" ?></label>
                                                                    </div>
                                                                    <div class="flex justify-between py-1">
                                                                        <label class="block mb-2 md:mb-0 text-sm font-[600] text-gray-900 dark:text-white md:w-1/2">Total/Item</label>
                                                                        <label class="block mb-2 md:mb-0 text-sm font-[600] text-gray-900 dark:text-white">Rp <?= number_format($detail['total_per_item'], 0, ',', '.'); ?></label>
                                                                    </div>
                                                                    <br>

                                                            <?php }
                                                            } ?>
                                                            <div>
                                                                <div class="flex justify-between py-1">
                                                                    <label class="block mb-2 md:mb-0 text-sm font-[600] text-gray-900 dark:text-white md:w-1/2">Total (Sblm Pajak)</label>
                                                                    <label class="block mb-2 md:mb-0 text-sm font-[600] text-gray-900 dark:text-white">Rp <?= number_format($subtotal, 0, ',', '.') ?> </label>
                                                                </div>

                                                                <div class="flex justify-between py-1">
                                                                    <label class="block mb-2 md:mb-0 text-sm font-[600] text-gray-900 dark:text-white md:w-1/2">Pajak Persen (Jumlah)</label>
                                                                    <label class="block mb-2 md:mb-0 text-sm font-[600] text-gray-900 dark:text-white"> <?= $penerimaan['pajak_persen'] ?>% (<?= number_format($penerimaan['pajak_jumlah'], 0, ',', '.') ?>)</label>
                                                                </div>
                                                                <div class="flex justify-between py-1">
                                                                    <label class="block mb-2 md:mb-0 text-sm font-[600] text-gray-900 dark:text-white md:w-1/2">Materai</label>
                                                                    <label class="block mb-2 md:mb-0 text-sm font-[600] text-gray-900 dark:text-white">Rp <?= number_format($penerimaan['materai'], 0, ',', '.') ?></label>
                                                                </div>
                                                            </div>
                                                            <div class="border-t border-[#F1F1F1] my-2">
                                                                <div class="flex justify-between pt-1">
                                                                    <label class="block mb-2 md:mb-0 text-sm font-[600] text-gray-900 dark:text-white md:w-1/2">Total Keseluruhan</label>
                                                                    <label class="block mb-2 md:mb-0 text-sm font-[600] text-gray-900 dark:text-white">Rp <?= number_format($penerimaan['tagihan'], 0, ',', '.') ?></label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex justify-end items-center gap-x-2 py-3 px-4 border-t dark:border-neutral-700">


                                        </div>
                                    </div>
                                </div>
                </div>
                <tr>
                    <td>
                        <div class="px-6 py-3">
                            <div class="flex items-center justify-center gap-x-3">
                                <span class="block text-sm font-semibold text-gray-800 dark:text-gray-200"><?php $original_date = $penerimaan['tanggal_datang'];
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
                        </div>
                    </td>
                    <td>
                        <div class="px-6 py-3">
                            <span class="text-center block text-sm font-semibold text-gray-800 dark:text-gray-200"><?php $original_jthtempo_date = $penerimaan['tanggal_jthtempo'];
                                                                                                                    $day = date("d", strtotime($original_jthtempo_date));
                                                                                                                    $month = date("m", strtotime($original_jthtempo_date));
                                                                                                                    $year = date("Y", strtotime($original_jthtempo_date));

                                                                                                                    // Daftar nama bulan dalam bahasa Indonesia
                                                                                                                    $bulan = array(
                                                                                                                        1 => "Januari", 2 => "Februari", 3 => "Maret", 4 => "April", 5 => "Mei", 6 => "Juni",
                                                                                                                        7 => "Juli", 8 => "Agustus", 9 => "September", 10 => "Oktober", 11 => "November", 12 => "Desember"
                                                                                                                    );

                                                                                                                    // Format tanggal sesuai dengan format yang diinginkan
                                                                                                                    $formatted_jthtempo_date = $day . ' ' . $bulan[(int)$month] . ' ' . $year;

                                                                                                                    echo $formatted_jthtempo_date; ?></span>
                        </div>
                    </td>
                    <td>
                        <div class="px-6 py-3">
                            <span class="text-center block text-sm font-semibold cursor-pointer hover:underline text-gray-800 dark:text-gray-200" data-hs-overlay="#hs-vertically-centered-scrollable-modal-<?= $penerimaan['id'] ?>"><?= $penerimaan['no_faktur'] ?? '-' ?></span>
                        </div>
                    </td>

                    <td>
                        <div class="px-6 py-3 text-center">
                            <span class="text-center block text-sm font-semibold cursor-pointer hover:underline text-gray-800 dark:text-gray-200"><?php foreach ($supplier_data as $supplier) {
                                                                                                                                                        if ($supplier['id'] === $penerimaan['id_supplier']) {
                                                                                                                                                            echo $supplier['nama'];
                                                                                                                                                        }
                                                                                                                                                    } ?></span>
                        </div>
                    </td>
                    <td>
                        <div class="pl-6 py-1.5 flex justify-center">
                            <div class="pr-3 py-1.5">
                                <button type="button" class="gap-x-1 text-sm decoration-2 hover:underline font-semibold dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" data-hs-overlay="#hs-vertically-centered-scrollable-modal-<?= $penerimaan['id'] ?>">
                                    Lihat Detail
                                </button>
                            </div>
                            <div class="px-3 py-1.5">
                                <a href="/penerimaanmedis/edit/<?= $penerimaan['id'] ?>" class="gap-x-1 text-sm text-blue-600 decoration-2 hover:underline font-semibold dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" href="#">
                                    Ubah
                                </a>
                            </div>
                            <div class="px-3 py-1.5">
                                <button class="gap-x-1 text-sm text-red-600 decoration-2 hover:underline font-semibold dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" onclick="openModal('modelConfirm-<?= $penerimaan['id'] ?>')" href="#">
                                    Hapus
                                </button>
                                <div id="modelConfirm-<?= $penerimaan['id'] ?>" class="fixed hidden z-[70] inset-0 bg-gray-900 bg-opacity-60 overflow-y-auto h-full w-full px-4 ">
                                    <div class="relative top-40 mx-auto shadow-xl rounded-md bg-white max-w-md">

                                        <div class="flex justify-end p-2">
                                            <button onclick="closeModal('modelConfirm-<?= $penerimaan['id'] ?>')" type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                                </svg>
                                            </button>
                                        </div>

                                        <div class="p-6 pt-0 text-center">
                                            <div class="flex justify-center mb-6"> <!-- Container for SVG, centered -->
                                                <svg xmlns="http://www.w3.org/2000/svg" width="49" height="48" viewBox="0 0 49 48" fill="none">
                                                    <path d="M8.5 11H40.5" stroke="#DA4141" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                                                    <path d="M18.5 5H30.5" stroke="#DA4141" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                                                    <path d="M12.5 17H36.5V40C36.5 41.6569 35.1569 43 33.5 43H15.5C13.8431 43 12.5 41.6569 12.5 40V17Z" fill="#FEE2E2" stroke="#DA4141" stroke-width="4" stroke-linejoin="round" />
                                                    <path d="M20.5 25L28.5 33" stroke="#DA4141" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                                                    <path d="M28.5 25L20.5 33" stroke="#DA4141" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </div>
                                            Hapus data
                                            <h3 class="text-xl text-wrap font-normal text-gray-500 mt-5 mb-6">Apakah anda yakin untuk menghapus data ini?</h3>
                                            <form action="/penerimaanmedis/hapus/<?= $penerimaan['id'] ?>" method="POST">
                                                <?= csrf_field() ?>
                                                <div class="w-full sm:flex justify-center">
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <button onclick="closeModal('modelConfirm-<?= $penerimaan['id'] ?>')" class="w-full text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-base inline-flex items-center justify-center px-3 py-2.5 text-center mr-2">
                                                        Hapus
                                                    </button>
                                                    <a href="#" onclick="closeModal('modelConfirm-<?= $penerimaan['id'] ?>')" class="w-full text-gray-900 bg-white hover:bg-gray-100 focus:ring-4 focus:ring-cyan-200 border border-gray-200 font-medium inline-flex items-center justify-center rounded-lg text-base px-3 py-2.5 text-center" data-modal-toggle="delete-user-modal">
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

    document.addEventListener("DOMContentLoaded", function() {
        // Select the warning message element
        var warningMessage = document.getElementById('warningMessage');

        // Check if the warning message exists
        if (warningMessage) {
            // Fade out the warning message after 5 seconds (5000 milliseconds)
            setTimeout(function() {
                warningMessage.style.opacity = '0';
                // Optionally, remove the element from the DOM after fading out
                setTimeout(function() {
                    warningMessage.remove();
                }, 1000); // 1 second delay after fading out
            }, 5000); // 5 seconds delay before fading out
        }
    });
</script>
<?= $this->endSection(); ?>