<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>
<!-- Table Section -->
<div class="max-w-[85rem] py-6 lg:py-3 px-8 mx-auto">
    <!-- Card -->
    <div class="flex flex-col">

        <div class="-m-1.5 overflow-y-auto">
            <div class="sm:px-6 min-w-full inline-block align-middle">


                <div class="p-5 bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-slate-900 dark:border-gray-700">
                    <!-- Header -->
                    <div class="py-1 flex justify-between items-center border-gray-200 dark:border-gray-700">
                        <div>
                            <h2 class="mb-2 text-xl font-extrabold text-gray-800 dark:text-gray-200">
                                Data Batch Barang Medis
                            </h2>


                        </div>
                        <div>
                            <a href='/batchmedis/tambah' class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-[#0A2D27] text-[#ACF2E7] hover:bg-[#13594E] disabled:opacity-50 disabled:pointer-events-none dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" href="#">
                                <svg class="flex-shrink-0 size-3" xmlns="http://www.w3.org/2000/svg" width="16" height="1" viewBox="0 0 16 16" fill="none">
                                    <path d="M2.63452 7.50001L13.6345 7.5M8.13452 13V2" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                                </svg>
                                Tambah
                            </a>
                        </div>
                    </div>


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


                    <!-- End Header -->

                    <!-- Table -->

                    <table id="myTable" class="overflow-x-auto min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <colgroup>
                            <col width="14%">
                            <col width="20%">
                            <col width="16%">
                            <col width="14%">
                            <col width="11%">
                            <col width="11%">
                            <col width="14%">
                        </colgroup>
                        <thead class="bg-gray-50 dark:bg-slate-800">
                            <tr>

                                <th scope="col" class="px-6 py-3 text-center">
                                    <div class="flex items-center justify-center">
                                        <span class="text-xs tracking-wide text-[#666]">
                                            No Batch
                                        </span>
                                    </div>
                                </th>

                                <th scope="col" class="px-6 py-3">
                                    <div class="flex items-center justify-center">
                                        <span class="text-xs tracking-wide text-[#666]">
                                            Nama Barang
                                        </span>
                                    </div>
                                </th>

                                <th scope="col" class="px-6 py-3 text-center">
                                    <div class="flex items-center justify-center">
                                        <span class="text-xs tracking-wide text-[#666]">
                                            Tanggal Kadaluwarsa
                                        </span>
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-3 text-center">
                                    <div class="flex items-center justify-center">
                                        <span class="text-xs tracking-wide text-[#666]">
                                            No Faktur
                                        </span>
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-3 text-center">
                                    <div class="flex items-center justify-center">
                                        <span class="text-xs tracking-wide text-[#666]">
                                            Jumlah
                                        </span>
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-3 text-center">
                                    <div class="flex items-center justify-center">
                                        <span class="text-xs tracking-wide text-[#666]">
                                            Sisa
                                        </span>
                                    </div>
                                </th>


                                <th scope="col" class="px-6 py-3 text-center">
                                    <div class="flex items-center justify-center">
                                        <span class="text-xs tracking-wide text-[#666]">
                                            Aksi
                                        </span>
                                    </div>
                                </th>

                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            <?php foreach ($batch_data as $batch) { ?>
                                <div id="hs-vertically-centered-scrollable-modal-<?= $batch['no_batch'] . $batch['no_faktur'] . $batch['id_barang_medis'] ?>" class="hs-overlay hidden size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto pointer-events-none">
                                    <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto h-[calc(100%-3.5rem)] min-h-[calc(100%-3.5rem)] flex items-center">
                                        <div class="w-full max-h-full overflow-hidden flex flex-col bg-white border shadow-sm rounded-xl pointer-events-auto dark:bg-neutral-800 dark:border-neutral-700 dark:shadow-neutral-700/70">
                                            <div class="flex justify-between items-center py-3 px-4  dark:border-neutral-700">
                                                <h3 class="font-bold text-gray-800 dark:text-white">
                                                    <?= $batch['no_batch'] ?>
                                                </h3>
                                                <button type="button" class="flex justify-center items-center size-7 text-sm font-semibold rounded-full border border-transparent text-gray-800 hover:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-neutral-700" data-hs-overlay="#hs-vertically-centered-scrollable-modal-<?= $batch['no_batch'] . $batch['no_faktur'] . $batch['id_barang_medis'] ?>">
                                                    <span class="sr-only">Close</span>
                                                    <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <path d="M18 6 6 18"></path>
                                                        <path d="m6 6 12 12"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                            <div class="px-4 overflow-y-auto">
                                                <div class="space-y-12">
                                                    <div>
                                                        <div class="mb-5 sm:block md:flex items-center">
                                                            <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Nomor Batch</label>
                                                            <input type="text" name="" value="<?= $batch['no_batch'] ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
                                                        </div>
                                                        <div class="mb-5 sm:block md:flex items-center">
                                                            <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Nomor Faktur</label>
                                                            <input type="text" name="" value="<?= $batch['no_faktur'] ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
                                                        </div>
                                                        <div class="mb-5 sm:block md:flex items-center">
                                                            <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Tanggal Datang</label>
                                                            <input type="text" name="" value="<?= $batch['tanggal_datang'] ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
                                                        </div>
                                                        <div class="mb-5 sm:block md:flex items-center">
                                                            <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Nama</label>
                                                            <input type="text" name="" value="<?php foreach ($barang_data as $barang) {
                                                                                                    if ($barang['id'] === $batch['id_barang_medis']) {
                                                                                                        echo $barang['nama'];
                                                                                                    }
                                                                                                } ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
                                                        </div>
                                                        <div class="mb-5 sm:block md:flex items-center">
                                                            <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Tanggal Kadaluwarsa</label>
                                                            <input type="text" name="" value="<?= $batch['kadaluwarsa'] ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
                                                        </div>
                                                        <div class="mb-5 sm:block md:flex items-center">
                                                            <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Harga Dasar</label>
                                                            <input type="text" name="" value="<?= $batch['h_dasar'] ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
                                                        </div>
                                                        <div class="mb-5 sm:block md:flex items-center">
                                                            <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Harga Beli</label>
                                                            <input type="text" name="" value="<?= $batch['h_beli'] ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
                                                        </div>
                                                        <div class="mb-5 sm:block md:flex items-center">
                                                            <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Harga Ralan</label>
                                                            <input type="text" name="" value="<?= $batch['h_ralan'] ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
                                                        </div>
                                                        <div class="mb-5 sm:block md:flex items-center">
                                                            <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Harga Kelas 1</label>
                                                            <input type="text" name="" value="<?= $batch['h_kelas1'] ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
                                                        </div>
                                                        <div class="mb-5 sm:block md:flex items-center">
                                                            <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Harga Kelas 2</label>
                                                            <input type="text" name="" value="<?= $batch['h_kelas2'] ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
                                                        </div>
                                                        <div class="mb-5 sm:block md:flex items-center">
                                                            <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Harga Kelas 3</label>
                                                            <input type="text" name="" value="<?= $batch['h_kelas3'] ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
                                                        </div>
                                                        <div class="mb-5 sm:block md:flex items-center">
                                                            <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Harga Utama</label>
                                                            <input type="text" name="" value="<?= $batch['h_utama'] ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
                                                        </div>
                                                        <div class="mb-5 sm:block md:flex items-center">
                                                            <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Harga VIP</label>
                                                            <input type="text" name="" value="<?= $batch['h_vip'] ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
                                                        </div>
                                                        <div class="mb-5 sm:block md:flex items-center">
                                                            <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Harga VVIP</label>
                                                            <input type="text" name="" value="<?= $batch['h_vvip'] ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
                                                        </div>
                                                        <div class="mb-5 sm:block md:flex items-center">
                                                            <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Harga Beli Luar</label>
                                                            <input type="text" name="" value="<?= $batch['h_beliluar'] ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
                                                        </div>
                                                        <div class="mb-5 sm:block md:flex items-center">
                                                            <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Harga Jual Bebas</label>
                                                            <input type="text" name="" value="<?= $batch['h_jualbebas'] ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
                                                        </div>
                                                        <div class="mb-5 sm:block md:flex items-center">
                                                            <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Harga Karyawan</label>
                                                            <input type="text" name="" value="<?= $batch['h_karyawan'] ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
                                                        </div>
                                                        <div class="mb-5 sm:block md:flex items-center">
                                                            <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Jumlah Beli</label>
                                                            <input type="text" name="" value="<?= $batch['jumlahbeli'] ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
                                                        </div>
                                                        <div class="mb-5 sm:block md:flex items-center">
                                                            <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Sisa</label>
                                                            <input type="text" name="" value="<?= $batch['sisa'] ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
                                                        </div>


                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <tr>

                                    <td>
                                        <div class="px-6 py-3">
                                            <div class="flex items-center justify-center gap-x-3">
                                                <span class="block text-sm font-semibold text-gray-800 dark:text-gray-200"><?= $batch['no_batch'] ?></span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="px-6 py-3">
                                            <div class="flex items-center justify-center gap-x-3">
                                                <span class="block text-sm font-semibold text-gray-800 dark:text-gray-200" data-hs-overlay="#hs-vertically-centered-scrollable-modal-<?= $batch['no_batch'] . $batch['no_faktur'] . $batch['id_barang_medis'] ?>"><?php foreach ($barang_data as $barang) {
                                                                                                                                                                                                                                                                        if ($barang['id'] === $batch['id_barang_medis']) {
                                                                                                                                                                                                                                                                            echo $barang['nama'];
                                                                                                                                                                                                                                                                        }
                                                                                                                                                                                                                                                                    } ?></span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="px-6 py-3">
                                            <div class="flex items-center justify-center gap-x-3">
                                                <span class="block text-sm font-semibold text-gray-800 dark:text-gray-200"><?= $batch['kadaluwarsa'] ?></span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="px-6 py-3">
                                            <div class="flex items-center justify-center gap-x-3">
                                                <span class="block text-sm font-semibold text-gray-800 dark:text-gray-200"><?= $batch['no_faktur'] ?></span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="px-6 py-3">
                                            <div class="flex items-center justify-center gap-x-3">
                                                <span class="block text-sm font-semibold text-gray-800 dark:text-gray-200"><?= $batch['jumlahbeli'] ?></span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="px-6 py-3">
                                            <div class="flex items-center justify-center gap-x-3">
                                                <span class="block text-sm font-semibold text-gray-800 dark:text-gray-200"><?= $batch['sisa'] ?></span>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="size-px whitespace-nowrap">
                                        <div class="px-3 py-1.5 text-center inline-flex">
                                            <div class="px-3 py-1.5">
                                                <button type="button" class="gap-x-1 text-sm decoration-2 hover:underline font-semibold dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" data-hs-overlay="#hs-vertically-centered-scrollable-modal-<?= $batch['no_batch'] . $batch['no_faktur'] . $batch['id_barang_medis'] ?>">
                                                    Lihat Detail
                                                </button>
                                            </div>
                                            <div class="px-3 py-1.5">
                                                <a href="/batchmedis/edit/<?= $batch['no_batch'] ?>" class="gap-x-1 text-sm text-blue-600 decoration-2 hover:underline font-semibold dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" href="#">
                                                    Ubah
                                                </a>
                                            </div>
                                            <div class="px-3 py-1.5">
                                                <button class="gap-x-1 text-sm text-red-600 decoration-2 hover:underline font-semibold dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" onclick="openModal('modelConfirm-')" href="#">
                                                    Hapus
                                                </button>
                                                <div id="modelConfirm-" class="fixed hidden z-[70] inset-0 bg-gray-900 bg-opacity-60 overflow-y-auto h-full w-full px-4 ">
                                                    <div class="relative top-40 mx-auto shadow-xl rounded-md bg-white max-w-md">

                                                        <div class="flex justify-end p-2">
                                                            <button onclick="closeModal('modelConfirm-')" type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center">
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
                                                            <form action="/batchmedis/hapus/" method="POST">
                                                                <?= csrf_field() ?>
                                                                <div class="w-full sm:flex justify-center">
                                                                    <input type="hidden" name="_method" value="DELETE">
                                                                    <button onclick="closeModal('modelConfirm-')" class="w-full text-white bg-red-600 hover:bg-red-800 focus:ring-4 font-medium rounded-lg text-base inline-flex items-center justify-center px-3 py-2.5 text-center mr-2">
                                                                        Hapus
                                                                    </button>

                                                                    <a href="#" onclick="closeModal('modelConfirm-')" class="w-full text-gray-900 bg-white hover:bg-gray-100 focus:ring-4 border border-gray-200 font-medium inline-flex items-center justify-center rounded-lg text-base px-3 py-2.5 text-center" data-modal-toggle="delete-user-modal">
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
                            <?php } ?>
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