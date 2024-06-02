<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<!-- Table Section -->
<div class="max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto">
    <!-- Card -->
    <div class="flex flex-col ">
        <div class="-m-1.5 overflow-y-auto">
            <div class="p-1.5 min-w-full inline-block align-middle">
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden dark:bg-slate-900 dark:border-gray-700">
                    <!-- Header -->
                    <div class="px-6 py-4 grid gap-3 md:flex md:justify-between md:items-center border-b border-gray-200 dark:border-gray-700">
                        <div>
                            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">
                                Persetujuan Barang Medis
                            </h2>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                Add Persetujuan Barang Medis, edit and more.
                            </p>
                        </div>

                        <div>
                            <div class="inline-flex gap-x-2">
                                <a class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-white dark:hover:bg-gray-800 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" href="#">
                                    View all
                                </a>

                                <a href='/tambahpengajuanmedis' class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" href="#">
                                    <svg class="flex-shrink-0 size-3" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                        <path d="M2.63452 7.50001L13.6345 7.5M8.13452 13V2" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                                    </svg>
                                    Add
                                </a>
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

                                <th scope="col" class="ps-6 lg:ps-3 xl:ps-0 pe-6 py-3 text-start">
                                    <div class="flex items-center gap-x-2">
                                        <span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-gray-200">
                                            Tanggal
                                        </span>
                                    </div>
                                </th>

                                <th scope="col" class="px-6 py-3 text-start">
                                    <div class="flex items-center gap-x-2">
                                        <span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-gray-200">
                                            Nomor Persetujuan
                                        </span>
                                    </div>
                                </th>

                                <th scope="col" class="px-6 py-3 text-start">
                                    <div class="flex items-center gap-x-2">
                                        <span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-gray-200">
                                            Kategori
                                        </span>
                                    </div>
                                </th>

                                <th scope="col" class="px-6 py-3 text-center">
                                    <div class="flex items-center gap-x-2 justify-center">
                                        <span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-gray-200">
                                            Action
                                        </span>
                                    </div>
                                </th>



                                <!-- <th scope="col" class="px-6 py-3 text-end"></th>
                                <th scope="col" class="px-6 py-3 text-end"></th>
                                <th scope="col" class="px-6 py-3 text-end"></th> -->
                            </tr>
                        </thead>




                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            <?php foreach ($pengajuan_medis_data as $pengajuan) : ?>
                                <div id="hs-vertically-centered-scrollable-modal-<?= $pengajuan['id'] ?>" class="hs-overlay hidden size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto pointer-events-none">
                                    <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto h-[calc(100%-3.5rem)] min-h-[calc(100%-3.5rem)] flex items-center">
                                        <div class="w-full max-h-full overflow-hidden flex flex-col bg-white border shadow-sm rounded-xl pointer-events-auto dark:bg-neutral-800 dark:border-neutral-700 dark:shadow-neutral-700/70">
                                            <div class="flex justify-between items-center py-3 px-4 border-b dark:border-neutral-700">
                                                <h3 class="font-bold text-gray-800 dark:text-white">
                                                    Persetujuan: <?= $pengajuan['nomor_pengajuan'] ?>
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
                                                <div class="space-y-4">
                                                    <div>
                                                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white"></h3>

                                                        <p><strong>Tanggal Persetujuan:</strong> <?= $pengajuan['tanggal_pengajuan'] ?></p>
                                                        <p><strong>Supplier:</strong> <?= $pengajuan['id_supplier'] ?></p>
                                                        <p><strong>Pegawai:</strong> <?= $pengajuan['id_pegawai'] ?></p>

                                                        <p><strong>Catatan:</strong> <?= $pengajuan['catatan'] ?></p>
                                                        <p><strong>Status Persetujuan Apoteker:</strong> <?php foreach ($persetujuan_data as $persetujuan) {
                                                                                                                if ($persetujuan['id_pengajuan'] === $pengajuan['id']) {
                                                                                                                    echo $persetujuan['status_apoteker'];
                                                                                                                }
                                                                                                            }  ?></p>
                                                        <p><strong>Status Persetujuan Keuangan:</strong> <?php foreach ($persetujuan_data as $persetujuan) {
                                                                                                                if ($persetujuan['id_pengajuan'] === $pengajuan['id']) {
                                                                                                                    echo $persetujuan['status_keuangan'];
                                                                                                                }
                                                                                                            }  ?></p>

                                                    </div>

                                                    <div>
                                                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Pesanan</h3>
                                                        <p class="mt-1 text-gray-800 dark:text-neutral-400">
                                                            <?php foreach ($pesanan_data as $pesanan) {
                                                                if ($pesanan['id_pengajuan'] === $pengajuan['id']) { ?>
                                                                    <span class="flex justify-between">
                                                                        <span><?= $pesanan['id_barang_medis'] ?><br><small>Jumlah: <?= $pesanan['jumlah_pesanan'] . " kapsul" ?></small></span>
                                                                        <span><?= $pesanan['harga_satuan'] ?></span>
                                                                    </span>
                                                                    <br>
                                                            <?php }
                                                            } ?>
                                                        <div class="text-right">
                                                            <p><strong>Diskon Persen (Jumlah):</strong> <?= $pengajuan['diskon_persen'] ?>% (<?= $pengajuan['diskon_jumlah'] ?>) </p>
                                                            <p><strong>Pajak Persen (Jumlah):</strong> <?= $pengajuan['pajak_persen'] ?>% (<?= $pengajuan['pajak_jumlah'] ?>) </p>
                                                            <p><strong>Materai:</strong> <?= $pengajuan['materai'] ?></p>
                                                            </p>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="flex justify-end items-center gap-x-2 py-3 px-4 border-t dark:border-neutral-700">
                                                <form action="/submitpersetujuan/<?= $pengajuan['id'] ?>" method="POST">
                                                    <?php foreach ($persetujuan_data as $persetujuan) {
                                                        if ($persetujuan['id_pengajuan'] === $pengajuan['id']) {
                                                            echo '<input type="hidden" value="' . $persetujuan['status'] . '" name="statuspersetujuan">';

                                                            if ($user_data['role'] === 1 || $user_data['role'] === 2) {
                                                                echo '<input type="hidden" value="' . $pengajuan['id'] . '" name="idpengajuan">';
                                                                if ($user_data['role'] === 1) {
                                                                    echo '<input type="hidden" value="' . $user_data['id'] . '" name="idapoteker">';
                                                                    echo '<input type="hidden" value="' . $persetujuan['status_keuangan'] . '" name="statuskeuangan">';
                                                                    echo '<input type="hidden" value="' . $persetujuan['id_keuangan'] . '" name="idkeuangan">';
                                                                } elseif ($user_data['role'] === 2) {
                                                                    echo '<input type="hidden" value="' . $user_data['id'] . '" name="idkeuangan">';
                                                                    echo '<input type="hidden" value="' . $persetujuan['status_apoteker'] . '" name="statusapoteker">';
                                                                    echo '<input type="hidden" value="' . $persetujuan['id_apoteker'] . '" name="idapoteker">';
                                                                } else {
                                                                    echo '<p>Hanya apoteker atau keuangan yang bisa melakukan persetujuan.</p>';
                                                                }
                                                            }
                                                        }
                                                    } ?>
                                                    <button type="submit" value="Disetujui" name="<?php echo ($user_data['role'] === 1 ? 'statusapoteker' : 'statuskeuangan'); ?>" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-green-500 text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-800">
                                                        Disetujui
                                                    </button>
                                                    <button type="submit" value="Ditolak" name="<?php echo ($user_data['role'] === 1 ? 'statusapoteker' : 'statuskeuangan'); ?>" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-red-600 text-white shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-800">
                                                        Ditolak
                                                    </button>
                                                </form>

                                                <button class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-800" data-hs-overlay="#hs-vertically-centered-scrollable-modal-<?= $pengajuan['id'] ?>">
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
                                    <td class="size-px whitespace-nowrap">
                                        <div class="ps-6 lg:ps-3 xl:ps-0 pe-6 py-3">
                                            <div class="flex items-center gap-x-3">
                                                <span class="block text-sm font-semibold text-gray-800 dark:text-gray-200"><?php
                                                                                                                            $tanggal_pengajuan = $pengajuan['tanggal_pengajuan'] ?? 'N/A'; // Assuming $pengajuan['tanggal_pengajuan'] contains the date string
                                                                                                                            $date_unix_timestamp = strtotime($tanggal_pengajuan);
                                                                                                                            $formatted_date = date("d-m-Y", $date_unix_timestamp); // Change the format inside date() function as needed
                                                                                                                            echo $formatted_date;
                                                                                                                            ?></span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="h-px w-72 whitespace-nowrap">
                                        <div class="px-6 py-3">
                                            <span class="block text-sm font-semibold text-gray-800 dark:text-gray-200">
                                                <a class="pengajuan-link hover:underline" data-hs-overlay="#hs-vertically-centered-scrollable-modal-<?= $pengajuan['id'] ?>" data-id="<?= $pengajuan['id'] ?>">
                                                    <?= $pengajuan['nomor_pengajuan'] ?? 'N/A' ?>
                                                </a>
                                            </span>
                                        </div>
                                    </td>
                                    <td class="size-px whitespace-nowrap">
                                        <div class="px-6 py-3">
                                            <?php
                                            switch ($pengajuan['status_pesanan']) {
                                                case '0':
                                                    echo '<span class="py-1 px-1.5 inline-flex items-center gap-x-1 text-xs font-medium bg-yellow-100 text-teal-800 rounded-full dark:bg-teal-500/10 dark:text-teal-500">
                                                            <svg class="size-2.5" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                                                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
                                                            </svg>
                                                            Menunggu Persetujuan
                                                        </span>';
                                                    break;
                                                case '1':
                                                    echo '<span class="py-1 px-1.5 inline-flex items-center gap-x-1 text-xs font-medium bg-red-500 text-white rounded-full dark:bg-teal-500/10 dark:text-teal-500">
                                                            <svg class="size-2.5" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                                                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
                                                            </svg>
                                                            Pengajuan Ditolak
                                                        </span>';
                                                    break;
                                                case '2':
                                                    echo '<span class="py-1 px-1.5 inline-flex items-center gap-x-1 text-xs font-medium bg-green-500 text-white rounded-full dark:bg-teal-500/10 dark:text-teal-500">
                                                            <svg class="size-2.5" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                                                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
                                                            </svg>
                                                            Pengajuan Disetujui
                                                        </span>';
                                                    break;
                                                case '3':
                                                    echo '<span class="py-1 px-1.5 inline-flex items-center gap-x-1 text-xs font-medium bg-yellow-100 text-teal-800 rounded-full dark:bg-teal-500/10 dark:text-teal-500">
                                                    <svg class="size-2.5" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
                                                    </svg>
                                                    Dalam Pemesanan
                                                </span>';
                                                    break;
                                                case '4':
                                                    echo '<span class="py-1 px-1.5 inline-flex items-center gap-x-1 text-xs font-medium bg-teal-100 text-teal-800 rounded-full dark:bg-teal-500/10 dark:text-teal-500">
                                                    <svg class="size-2.5" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
                                                    </svg>
                                                    Barang telah Diterima
                                                </span>';
                                                    break;
                                                case '5':
                                                    echo '<span class="py-1 px-1.5 inline-flex items-center gap-x-1 text-xs font-medium bg-yellow-100 text-teal-800 rounded-full dark:bg-teal-500/10 dark:text-teal-500">
                                                    <svg class="size-2.5" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
                                                    </svg>
                                                    Tagihan belum lunas
                                                </span>';
                                                    break;
                                                case '6':
                                                    echo '<span class="py-1 px-1.5 inline-flex items-center gap-x-1 text-xs font-medium bg-yellow-100 text-teal-800 rounded-full dark:bg-teal-500/10 dark:text-teal-500">
                                                        <svg class="size-2.5" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                                            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
                                                        </svg>
                                                        Tagihan telah Dibayar
                                                    </span>';
                                                    break;
                                                default:
                                                    echo '<span class="py-1 px-1.5 inline-flex items-center gap-x-1 text-xs font-medium bg-yellow-100 text-teal-800 rounded-full dark:bg-teal-500/10 dark:text-teal-500">
                                                    <svg class="size-2.5" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
                                                    </svg>
                                                    Pengajuan
                                                </span>';
                                                    break;
                                            }
                                            ?>

                                        </div>
                                    </td>
                                    <!-- <td class="size-px whitespace-nowrap">
                                        <div class="px-6 py-1.5">
                                            <button type="button" class="inline-flex items-center gap-x-1 text-sm text-blue-600 decoration-2 hover:underline font-medium dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" data-hs-overlay="#hs-basic-modal-<?= $pengajuan['id'] ?>">
                                                More Info
                                            </button>
                                    </td>
                                    <td class="size-px whitespace-nowrap">
                                        <div class="px-6 py-1.5">
                                            <a href="/editpengajuanmedis/<?= $pengajuan['id'] ?>" class="inline-flex items-center gap-x-1 text-sm text-blue-600 decoration-2 hover:underline font-medium dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                                                Edit
                                            </a>
                                        </div>
                                    </td>

                                    <td class="size-px whitespace-nowrap">
                                        <div class="px-6 py-1.5">
                                            <a href="/hapuspengajuanmedis/<?= $pengajuan['id'] ?>" class="inline-flex items-center gap-x-1 text-sm text-red-600 decoration-2 hover:underline font-medium dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                                                Delete
                                            </a>
                                        </div>
                                    </td> -->
                                    <td class="size-px whitespace-nowrap">
                                        <div class="px-6 py-1.5 inline-flex">
                                            <div class="px-3 py-1.5">
                                                <button type="button" class="items-center gap-x-1 text-sm text-blue-600 decoration-2 hover:underline font-medium dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" data-hs-overlay="#hs-vertically-centered-scrollable-modal-<?= $pengajuan['id'] ?>">
                                                    More Info
                                                </button>
                                            </div>
                                            <div class="px-3 py-1.5">
                                                <a href="/editpengajuanmedis/<?= $pengajuan['id'] ?>" class="items-center gap-x-1 text-sm text-blue-600 decoration-2 hover:underline font-medium dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                                                    Edit
                                                </a>
                                            </div>
                                            <div class="px-3 py-1.5">
                                                <a href="/hapuspengajuanmedis/<?= $pengajuan['id'] ?>" class="items-center gap-x-1 text-sm text-red-600 decoration-2 hover:underline font-medium dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                                                    Delete
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
                    <div class="px-6 py-4 grid gap-3 md:flex md:justify-between md:items-center border-t border-gray-200 dark:border-gray-700">
                        <!-- Pagination -->
                        <nav class="flex items-center gap-x-1">
                            <?php if ($meta_data['page'] > 1) : ?>
                                <a href="/datamedis?page=<?= $meta_data['page'] - 1 ?>&size=5" class="min-h-[38px] min-w-[38px] py-2 px-2.5 inline-flex justify-center items-center gap-x-2 text-sm rounded-lg text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:text-white dark:hover:bg-white/10 dark:focus:bg-white/10">
                                    <svg class="flex-shrink-0 size-3.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="m15 18-6-6 6-6" />
                                    </svg>
                                    <span aria-hidden="true" class="sr-only">Previous</span>
                                </a>
                            <?php endif; ?>

                            <div class="flex items-center gap-x-1">
                                <span class="min-h-[38px] min-w-[38px] flex justify-center items-center border border-gray-200 text-gray-800 py-2 px-3 text-sm rounded-lg focus:outline-none focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:border-gray-700 dark:text-white dark:focus:bg-white/10"><?= $meta_data['page'] ?></span>
                                <span class="min-h-[38px] flex justify-center items-center text-gray-500 py-2 px-1.5 text-sm dark:text-gray-500">of</span>
                                <span class="min-h-[38px] flex justify-center items-center text-gray-500 py-2 px-1.5 text-sm dark:text-gray-500"><?= $meta_data['total'] ?></span>
                            </div>

                            <?php if ($meta_data['page'] < $meta_data['total']) : ?>
                                <a href="/datamedis?page=<?= $meta_data['page'] + 1 ?>&size=5" class="min-h-[38px] min-w-[38px] py-2 px-2.5 inline-flex justify-center items-center gap-x-2 text-sm rounded-lg text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:text-white dark:hover:bg-white/10 dark:focus:bg-white/10">
                                    <span aria-hidden="true" class="sr-only">Next</span>
                                    <svg class="flex-shrink-0 size-3.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="m9 18 6-6-6-6" />
                                    </svg>
                                </a>
                            <?php endif; ?>

                        </nav>


                        <!-- Dropdown -->
                        <div class="hs-dropdown relative inline-flex [--placement:top-left]">
                            <button id="dropDownButton" type="button" class="hs-dropdown-toggle min-h-[32px] py-1 px-2 inline-flex items-center gap-x-1 text-sm rounded-lg border border-gray-200 text-gray-800 shadow-sm hover:bg-gray-50 focus:outline-none focus:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:border-gray-700 dark:text-white dark:hover:bg-gray-700 dark:focus:bg-gray-700">
                                <?= $meta_data['size'] ?> page
                                <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="m6 9 6 6 6-6" />
                                </svg>
                            </button>
                            <div id="dropdown" class="hs-dropdown-menu hs-dropdown-open:opacity-100 w-48 hidden z-50 transition-[margin,opacity] opacity-0 duration-300 mb-2 bg-white shadow-md rounded-lg p-2 dark:bg-gray-800 dark:border dark:border-gray-700 dark:divide-gray-700" aria-labelledby="hs-small-pagination-dropdown">

                                <a href="/datamedis?page=1&size=5">
                                    <button type="button" class="w-full flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-300 dark:focus:bg-gray-700">
                                        5 page
                                    </button>
                                </a>

                                <a href="/datamedis?page=1&size=10">
                                    <button type="button" class="w-full flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-300 dark:focus:bg-gray-700">
                                        10 page
                                    </button>
                                </a>

                            </div>
                        </div>
                        <!-- End Dropdown -->


                        <!-- End Pagination -->
                    </div>

                    <!-- End Footer -->
                </div>
            </div>
        </div>
    </div>
    <!-- End Card -->
</div>


<script>

</script>
<?= $this->endSection(); ?>