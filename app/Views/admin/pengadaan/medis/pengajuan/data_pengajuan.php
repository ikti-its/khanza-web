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
                <div class="p-5 bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-slate-900 dark:border-gray-700">
                    <!-- Header -->
                    <div class="py-1 flex justify-between items-center border-gray-200 dark:border-gray-700">
                        <div>
                            <h2 class="mb-2 text-xl font-extrabold text-gray-800 dark:text-gray-200">
                                Pengajuan Barang Medis
                            </h2>


                        </div>
                        <div>
                            <a href='/pengajuanmedis/tambah' class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-[#0A2D27] text-[#ACF2E7] hover:bg-[#13594E] disabled:opacity-50 disabled:pointer-events-none dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" href="#">
                                <svg class="flex-shrink-0 size-3" xmlns="http://www.w3.org/2000/svg" width="16" height="1" viewBox="0 0 16 16" fill="none">
                                    <path d="M2.63452 7.50001L13.6345 7.5M8.13452 13V2" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                                </svg>
                                Tambah
                            </a>
                        </div>
                    </div>

                    <!-- <form href="/pengajuanmedis" class="max-w-md" method="POST">
                        <label for="hs-as-table-product-review-search" class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white">Search</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                                </svg>
                            </div>
                            <input type="search" id="myInput" onkeyup="<?php if ($search !== null) {
                                                                            echo 'myFunction();';
                                                                        } ?>" name="search" class="block w-full p-4 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Search..." />
                            <button type="submit" class="text-white absolute end-2.5 bottom-2.5 bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Search</button>
                        </div>
                    </form> -->

                    <div class="py-4 grid gap-3 md:items-start">
                        <div class="sm:col-span-1">
                            <label for="hs-as-table-product-review-search" class="sr-only">Search</label>
                            <div class="relative">
                                <input type="text" class="py-2 px-4 ps-11 block border w-full xl:w-96 border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600" placeholder="Search">
                                <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none ps-4">
                                    <svg class="size-4 text-gray-400 dark:text-neutral-500" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z" />
                                    </svg>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div id="noDataFound" style="display: none;">Data tidak ditemukan</div>
                    <!-- End Header -->

                    <!-- Table -->

                    <table id="myTable" class="overflow-x-auto min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <?php 
                            $widths  = [20, 25, 30, 25];
                            echo view('components/tabel_colgroup',['widths' => $widths]);
                            
                            $columns = [
                                'Tanggal',
                                'Nomor Pengajuan',
                                'Status',
                                'Aksi'
                            ];
                            echo view('components/tabel_thead',['kolom' => $columns]);
                        ?>

                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            <?php
                            foreach ($pengajuan_medis_data as $pengajuan) {
                                if ($pengajuan['status_pesanan'] === '0' || $pengajuan['status_pesanan'] === '1' || $pengajuan['status_pesanan'] === '2') {
                            ?>
                                    <div id="hs-vertically-centered-scrollable-modal-<?= $pengajuan['id'] ?>" class="hs-overlay hidden size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto pointer-events-none">
                                        <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto h-[calc(100%-3.5rem)] min-h-[calc(100%-3.5rem)] flex items-center">
                                            <div class="w-full max-h-full overflow-hidden flex flex-col bg-white border shadow-sm rounded-xl pointer-events-auto dark:bg-neutral-800 dark:border-neutral-700 dark:shadow-neutral-700/70">
                                                <div class="flex justify-between items-center py-3 px-4  dark:border-neutral-700">
                                                    <h3 class="font-bold text-gray-800 dark:text-white">
                                                        <?= $pengajuan['nomor_pengajuan'] ?>
                                                    </h3>
                                                    <button type="button" class="flex justify-center items-center size-7 text-sm font-semibold rounded-full border border-transparent text-gray-800 hover:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-neutral-700" data-hs-overlay="#hs-vertically-centered-scrollable-modal-<?= $pengajuan['id'] ?>">
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
                                                                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Tanggal Pengajuan</label>
                                                                <input type="text" name="" value="<?php $original_date = $pengajuan['tanggal_pengajuan'];
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

                                                                                                    echo $formatted_date; ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
                                                            </div>
                                                            <div class="mb-5 sm:block md:flex items-center">
                                                                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Nomor
                                                                    Pengajuan</label>
                                                                <input type="text" name="" value="<?= $pengajuan['nomor_pengajuan'] ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
                                                            </div>
                                                            <div class="mb-5 sm:block md:flex items-center">
                                                                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Pegawai</label>
                                                                <input type="text" name="" value="<?php foreach ($pegawai_data as $pegawai) {
                                                                                                        if ($pegawai['id'] === $pengajuan['id_pegawai']) {
                                                                                                            echo $pegawai['nama'];
                                                                                                        }
                                                                                                    } ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
                                                            </div>
                                                            <div class="mb-5 sm:block md:flex items-center">
                                                                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Status Apoteker</label>
                                                                <input type="text" name="" value="<?php foreach ($persetujuan_data as $persetujuan) {
                                                                                                        if ($persetujuan['id_pengajuan'] === $pengajuan['id']) {
                                                                                                            echo $persetujuan['status_apoteker'];
                                                                                                        }
                                                                                                    }
                                                                                                    ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
                                                            </div>
                                                            <div class="mb-5 sm:block md:flex items-center">
                                                                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Status Keuangan</label>
                                                                <input type="text" name="" value="<?php foreach ($persetujuan_data as $persetujuan) {
                                                                                                        if ($persetujuan['id_pengajuan'] === $pengajuan['id']) {
                                                                                                            echo $persetujuan['status_keuangan'];
                                                                                                        }
                                                                                                    }
                                                                                                    ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
                                                            </div>
                                                            <div class="mb-5 sm:block md:flex items-center">
                                                                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Catatan</label>
                                                                <input type="text" name="" value="<?= $pengajuan['catatan'] ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
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
                                                                        <p class="font-bold text-center text-gray-900 text-sm rounded-lg w-full">Total/Item</p>
                                                                    </div>
                                                                </div>



                                                                <?php $subtotal = 0;
                                                                foreach ($pesanan_data as $pesanan) {
                                                                    if ($pesanan['id_pengajuan'] === $pengajuan['id']) {
                                                                        $subtotal += $pesanan['total_per_item'] ?>

                                                                        <div class="flex items-center justify-between">
                                                                            <div class="w-1/2 font-medium">
                                                                                <?php foreach ($medis_data as $medis) {
                                                                                    if ($medis['id'] === $pesanan['id_barang_medis']) {
                                                                                        echo $medis['nama'];
                                                                                    }
                                                                                } ?>
                                                                                <br>
                                                                            </div>
                                                                            <div class="flex justify-end w-1/2">
                                                                                <input type="text" name="" value="<?= "Rp " . number_format($pesanan['harga_satuan_pengajuan'], 0, ',', '.') ?>" class="text-center mr-2 bg-gray-100 text-gray-900 text-sm rounded-lg px-2 py-1 w-full dark:border-gray-600 dark:text-white" readonly>
                                                                                <input type="text" name="" value="<?= "Rp " . number_format($pesanan['subtotal_per_item'], 0, ',', '.') ?? "Belum ada total" ?>" class="text-center bg-gray-100 font-[600] text-gray-900 text-sm rounded-lg px-2 py-1 w-full dark:border-gray-600 dark:text-white" readonly>
                                                                            </div>
                                                                        </div>
                                                                        <div><small>Jumlah:
                                                                                <?= $pesanan['jumlah_pesanan'] ?> <?php foreach ($satuan_data as $satuan) {
                                                                                                                        if ($satuan['id'] === $pesanan['satuan'] && $pesanan['satuan'] !== 1) {
                                                                                                                            echo $satuan['nama'];
                                                                                                                        } else {
                                                                                                                            echo '';
                                                                                                                        }
                                                                                                                    } ?>
                                                                            </small></div>
                                                                        <br>

                                                                <?php }
                                                                } ?>

                                                                <div class="border-t border-[#F1F1F1] my-2">
                                                                    <div class="flex justify-between pt-1">
                                                                        <label class="block mb-2 md:mb-0 text-sm font-[600] text-gray-900 dark:text-white md:w-1/2">Total</label>
                                                                        <label class="block mb-2 md:mb-0 text-sm font-[600] text-gray-900 dark:text-white">Rp <?= number_format($pengajuan['total_pengajuan'], 0, ',', '.') ?></label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="flex justify-end items-center gap-x-2 py-3 px-4 w-full border-t dark:border-neutral-700">
                                                    <?php if ($pengajuan['status_pesanan'] === '2') { ?>
                                                        <a href="/pemesananmedis/tambah/<?= $pengajuan['id'] ?>" class="w-full py-2 px-3 flex justify-center items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-[#0A2D27] text-[#ACF2E7] shadow-sm hover:bg-[#13594E] disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-800">
                                                            Lanjutkan Pemesanan
                                                        </a>
                                                    <?php } else { ?>
                                                        <button class="w-full py-2 px-3 flex justify-center items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-[#CCD3D2] text-[#EDFBF9] shadow-sm cursor-default">
                                                            Lanjutkan Pemesanan
                                                        </button>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <tr>
                                        <td>
                                            <div class="px-6 py-3">
                                                <div class="flex items-center justify-center gap-x-3">
                                                    <span class="block text-sm font-semibold text-gray-800 dark:text-gray-200"><?php echo $formatted_date; ?></span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="px-6 py-3 text-center">
                                                <span class="text-center block text-sm font-semibold cursor-pointer hover:underline text-gray-800 dark:text-gray-200" data-hs-overlay="#hs-vertically-centered-scrollable-modal-<?= $pengajuan['id'] ?>" data-id="<?= $pengajuan['id'] ?>"><?= $pengajuan['nomor_pengajuan'] ?? '-' ?></span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="px-6 py-3 text-center">
                                                <?php
                                                switch ($pengajuan['status_pesanan']) {
                                                    case '0':
                                                        echo '<span class="px-2 py-1.5 inline-flex items-center gap-x-1 text-xs font-semibold bg-[#FEF9C3] text-[#F49A35] rounded-full dark:bg-teal-500/10 dark:text-teal-500">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                                                <path d="M8.00004 14.6663C11.6819 14.6663 14.6667 11.6816 14.6667 7.99967C14.6667 4.31778 11.6819 1.33301 8.00004 1.33301C4.31814 1.33301 1.33337 4.31778 1.33337 7.99967C1.33337 11.6816 4.31814 14.6663 8.00004 14.6663Z" fill="#F49A35"/>
                                                                <path d="M10.4733 10.6202C10.3867 10.6202 10.3 10.6002 10.22 10.5468L8.15334 9.3135C7.64001 9.00684 7.26001 8.3335 7.26001 7.74017V5.00684C7.26001 4.7335 7.48668 4.50684 7.76001 4.50684C8.03334 4.50684 8.26001 4.7335 8.26001 5.00684V7.74017C8.26001 7.98017 8.46001 8.3335 8.66668 8.4535L10.7333 9.68684C10.9733 9.82684 11.0467 10.1335 10.9067 10.3735C10.8067 10.5335 10.64 10.6202 10.4733 10.6202Z" fill="#FFF5CF"/>
                                                                </svg>
                                                            Menunggu Persetujuan
                                                        </span>';
                                                        break;
                                                    case '1':
                                                        echo '<span class="px-2 py-1.5 inline-flex items-center gap-x-1 text-xs font-semibold bg-[#FEE2E2] text-[#991B1B] rounded-full dark:bg-teal-500/10 dark:text-teal-500">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                                            <path d="M8.00004 14.6663C11.6819 14.6663 14.6667 11.6816 14.6667 7.99967C14.6667 4.31778 11.6819 1.33301 8.00004 1.33301C4.31814 1.33301 1.33337 4.31778 1.33337 7.99967C1.33337 11.6816 4.31814 14.6663 8.00004 14.6663Z" fill="#991B1B"/>
                                                            <path d="M8.70666 8.00023L10.24 6.4669C10.4333 6.27357 10.4333 5.95357 10.24 5.76023C10.0467 5.5669 9.72666 5.5669 9.53332 5.76023L7.99999 7.29357L6.46666 5.76023C6.27332 5.5669 5.95332 5.5669 5.75999 5.76023C5.56666 5.95357 5.56666 6.27357 5.75999 6.4669L7.29332 8.00023L5.75999 9.53357C5.56666 9.7269 5.56666 10.0469 5.75999 10.2402C5.85999 10.3402 5.98666 10.3869 6.11332 10.3869C6.23999 10.3869 6.36666 10.3402 6.46666 10.2402L7.99999 8.7069L9.53332 10.2402C9.63332 10.3402 9.75999 10.3869 9.88666 10.3869C10.0133 10.3869 10.14 10.3402 10.24 10.2402C10.4333 10.0469 10.4333 9.7269 10.24 9.53357L8.70666 8.00023Z" fill="#FEE2E2"/>
                                                            </svg>
                                                            Pengajuan Ditolak
                                                        </span>';
                                                        break;
                                                    case '2':
                                                        echo '<span class="px-2 py-1.5 inline-flex items-center gap-x-1 text-xs font-semibold bg-[#D6F9F3] text-[#13594E] rounded-full dark:bg-teal-500/10 dark:text-teal-500">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                                            <path d="M8.00004 14.6663C11.6819 14.6663 14.6667 11.6816 14.6667 7.99967C14.6667 4.31778 11.6819 1.33301 8.00004 1.33301C4.31814 1.33301 1.33337 4.31778 1.33337 7.99967C1.33337 11.6816 4.31814 14.6663 8.00004 14.6663Z" fill="#13594E"/>
                                                            <path d="M7.05334 10.3867C6.92 10.3867 6.79334 10.3334 6.7 10.2401L4.81333 8.3534C4.62 8.16006 4.62 7.84007 4.81333 7.64673C5.00667 7.4534 5.32667 7.4534 5.52 7.64673L7.05334 9.18007L10.48 5.7534C10.6733 5.56007 10.9933 5.56007 11.1867 5.7534C11.38 5.94673 11.38 6.26673 11.1867 6.46006L7.40667 10.2401C7.31334 10.3334 7.18667 10.3867 7.05334 10.3867Z" fill="#D6F9F3"/>
                                                            </svg>
                                                            Pengajuan Disetujui
                                                        </span>';
                                                        break;
                                                    default:
                                                        echo '<span class="inline-flex items-center gap-x-1.5 py-1.5 px-3 rounded-full text-xs font-medium bg-[#F1F1F1]">
                                                    <span class="size-1.5 inline-block rounded-full bg-[#535353]"></span>
                                                         Tidak ada status
                                                    </span>';
                                                        break;
                                                }
                                                ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="pl-6 py-1.5 inline-flex">
                                                <?php if (
                                                    $pengajuan['status_pesanan'] === '0' &&
                                                    ($persetujuan['status_apoteker'] === 'Menunggu Persetujuan' &&
                                                        $persetujuan['status_keuangan'] === 'Menunggu Persetujuan')
                                                ) {  ?>
                                                    <?php
                                                        $row_id  = $pengajuan['id'];
                                                        $api_url = '/pengajuanmedis';
                                                        echo view('components/aksi/detail',[
                                                            'row_id'  => $row_id,
                                                            'api_url' => $api_url   
                                                        ]);
                                                        echo view('components/aksi/ubah',[
                                                            'row_id'  => $row_id,
                                                            'api_url' => $api_url   
                                                        ]);
                                                        echo view('components/aksi/hapus',[
                                                            'row_id'  => $row_id,
                                                            'api_url' => $api_url   
                                                        ]); 
                                                    ?>
                                                <?php } else { ?>
                                                    <div class="px-3 py-1.5">
                                                        <button class="cursor-default gap-x-1 text-sm text-[#C4C4C4] decoration-2 font-semibold dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                                                            Ubah
                                                        </button>
                                                    </div>

                                                    <div class="px-3 py-1.5">
                                                        <button class="cursor-default gap-x-1 text-sm text-[#C4C4C4] decoration-2 font-semibold dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                                                            Hapus
                                                        </button>

                                                    </div>
                                                <?php } ?>
                                            </div>
                                        </td>

                                    </tr>
                            <?php }
                            }
                            ?>
                        </tbody>
                    </table>
                    <!-- End Table -->

                    <!-- Footer -->
                    <div class="px-6 py-4 grid gap-3 md:flex md:justify-between md:items-center border-t border-gray-200 dark:border-neutral-700">
                        <!-- Pagination -->

                        <nav class="flex w-full justify-between items-center gap-x-1">
                            <!-- Previous Button -->
                            <div class="inline-flex gap-x-2">
                                <button type="button" class="min-h-[38px] min-w-[38px] py-2 px-2.5 inline-flex justify-center items-center gap-x-2 text-sm rounded-lg text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-white/10 dark:focus:bg-white/10" aria-label="Previous page" <?= $meta_data['page'] <= 1 ? 'disabled' : '' ?> onclick="window.location.href='/pengajuanmedis?page=<?= $meta_data['page'] - 1 ?>&size=<?= $meta_data['size'] ?>'">
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
                                        echo '<button type="button" class="min-h-[38px] min-w-[38px] flex justify-center items-center ' . ($current_page == $i ? 'bg-gray-200 text-gray-800 dark:bg-neutral-600 dark:focus:bg-neutral-500' : 'text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:text-white dark:hover:bg-white/10 dark:focus:bg-white/10') . ' py-2 px-3 text-sm rounded-lg" ' . ($current_page == $i ? 'aria-current="page"' : '') . ' onclick="window.location.href=\'/pengajuanmedis?page=' . $i . '&size=' . $meta_data['size'] . '\'">' . $i . '</button>';
                                    }
                                } else {
                                    if ($current_page > $range + 1) {
                                        echo '<button type="button" class="min-h-[38px] min-w-[38px] flex justify-center items-center text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:text-white dark:hover:bg-white/10 dark:focus:bg-white/10 py-2 px-3 text-sm rounded-lg" onclick="window.location.href=\'/pengajuanmedis?page=1&size=' . $meta_data['size'] . '\'">1</button>';
                                        if ($current_page > $range + 2) {
                                            echo '<span class="py-2 px-3 text-sm">...</span>';
                                        }
                                    }

                                    for ($i = max($current_page - $range, 1); $i <= min($current_page + $range, $total_pages); $i++) {
                                        echo '<button type="button" class="min-h-[38px] min-w-[38px] flex justify-center items-center ' . ($current_page == $i ? 'bg-gray-200 text-gray-800 dark:bg-neutral-600 dark:focus:bg-neutral-500' : 'text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:text-white dark:hover:bg-white/10 dark:focus:bg-white/10') . ' py-2 px-3 text-sm rounded-lg" ' . ($current_page == $i ? 'aria-current="page"' : '') . ' onclick="window.location.href=\'/pengajuanmedis?page=' . $i . '&size=' . $meta_data['size'] . '\'">' . $i . '</button>';
                                    }

                                    if ($current_page < $total_pages - $range - 1) {
                                        if ($current_page < $total_pages - $range - 2) {
                                            echo '<span class="py-2 px-3 text-sm">...</span>';
                                        }
                                        echo '<button type="button" class="min-h-[38px] min-w-[38px] flex justify-center items-center text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:text-white dark:hover:bg-white/10 dark:focus:bg-white/10 py-2 px-3 text-sm rounded-lg" onclick="window.location.href=\'/pengajuanmedis?page=' . $total_pages . '&size=' . $meta_data['size'] . '\'">' . $total_pages . '</button>';
                                    }
                                }
                                ?>
                            </div>

                            <!-- Next Button -->
                            <div class="inline-flex gap-x-2">
                                <button type="button" class="min-h-[38px] min-w-[38px] py-2 px-2.5 inline-flex justify-center items-center gap-x-2 text-sm rounded-lg text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-white/10 dark:focus:bg-white/10" aria-label="Next page" <?= $current_page >= $total_pages ? 'disabled' : '' ?> onclick="window.location.href='/pengajuanmedis?page=<?= $current_page + 1 ?>&size=<?= $meta_data['size'] ?>'">
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
    let currentIndex = 0;
    const cardsPerPage = 4;

    function showCards(index) {
        const cards = document.querySelectorAll('#cardContainer a');
        cards.forEach((card, i) => {
            card.style.display = (i >= index && i < index + cardsPerPage) ? 'flex' : 'none';
        });
        updateSliderIndicator(index / cardsPerPage);
    }

    function nextCards() {
        const totalCards = document.querySelectorAll('#cardContainer a').length;
        if (currentIndex + cardsPerPage < totalCards) {
            currentIndex += cardsPerPage;
            showCards(currentIndex);
        }
    }

    function prevCards() {
        if (currentIndex - cardsPerPage >= 0) {
            currentIndex -= cardsPerPage;
            showCards(currentIndex);
        }
    }

    function updateSliderIndicator(activeIndex) {
        const indicators = document.querySelectorAll('.flex.justify-center.mt-4 span');
        indicators.forEach((indicator, i) => {
            if (i === activeIndex) {
                indicator.classList.add('bg-black');
                indicator.classList.add('px-3');
                indicator.classList.remove('bg-gray-400');
            } else {
                indicator.classList.add('bg-gray-400');
                indicator.classList.remove('bg-black');
                indicator.classList.remove('px-3');
            }
        });
    }

    // Initially show the first 4 cards
    showCards(currentIndex);

    //search
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

    //delete confirmation
    

    // Close all modals when press ESC
    document.onkeyup = function(event) {
        event = event || window.event;
        if (event.keyCode === 27) {
            document.getElementsByTagName('body')[0].classList.remove('overflow-y-hidden')
            let modals = document.getElementsByClassName('modal');
            Array.prototype.slice.call(modals).forEach(i => {
                i.style.display = 'none'
            })
        }
    };

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