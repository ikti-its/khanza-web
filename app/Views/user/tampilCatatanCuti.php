<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>




<!-- Table Section -->
<div class="overflow overflow-auto px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto">
    <!-- Card -->
    <div class="flex flex-col">
        <div class="-m-3.5 overflow-x-auto">
            <div class="p-1.5 w-full inline-block align-middle">
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden dark:bg-neutral-900 dark:border-neutral-700">


                    <div class="px-6 pt-4 grid gap-3 md:flex md:justify-between md:items-center dark:border-neutral-700">


                        <div class="grid gap-3 md:flex md:justify-between md:items-center">
                            <div class="sm:col-span-12">
                                <h2 class="text-lg font-semibold text-gray-800 dark:text-neutral-200">
                                    Catatan Cuti
                                </h2>
                            </div>

                        </div>
                        <!-- 
                        <div class="justify-end items-center">
                            <a href="/izincuti">
                                <button type="button" class="py-2 px-4 my-2 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-[#0A2D27] text-[#ACF2E7] hover:bg-teal-700 disabled:opacity-50 disabled:pointer-events-none">

                                    <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M4 12H20M12 4V20" stroke="#ACF2E7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                    </svg>

                                    Tambah
                                </button>
                            </a>

                        </div> -->


                    </div>

                    <?= view('components/data_search_bar') ?>


                    <div class="overflow-x-auto">
                        <!-- Table -->
                        <table id="myTable" class="min-w-full divide-y divide-gray-50 dark:divid e-neutral-700 text-xs">
                            <thead class="bg-gray-50 divide-y divide-gray-200 dark:bg-neutral-800 dark:divide-neutral-700">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-start border-s border-gray-200 dark:border-neutral-700">
                                        <span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200">
                                            Tanggal Mulai Cuti
                                        </span>
                                    </th>

                                    <th scope="col" class="px-6 py-3 text-start">
                                        <span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200">
                                            Tanggal Selesai
                                        </span>
                                    </th>

                                    <th scope="col" class="px-6 py-3 text-start">
                                        <span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200">
                                            Status Pengajuan
                                        </span>
                                    </th>

                                    <th scope="col" class="px-6 py-3 text-start">
                                        <span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200">
                                            Alasan Cuti
                                        </span>
                                    </th>

                                </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-200 dark:divide-neutral-700 text-xs">
                                <?php

                                // Define an associative array for id_alasan_cuti
                                $alasanCuti = [
                                    'S' => 'Sakit',
                                    'I' => 'Izin',
                                    'CT' => 'Cuti Tahunan',
                                    'CB' => 'Cuti Besar',
                                    'CM' => 'Cuti Melahirkan',
                                    'CU' => 'Cuti Karena Alasan Penting'
                                ];

                                foreach ($cuti_data as $cutiEntry) : ?>
                                    <tr>
                                        <td class="h-px w-auto whitespace-nowrap">
                                            <div class="px-6 py-2 flex items-center gap-x-3">
                                                <a class="flex items-center gap-x-2" href="">
                                                    <span class="font-semibold hover:underline"><?= $cutiEntry['tanggal_mulai'] ?? 'N/A' ?></span>
                                                </a>
                                            </div>
                                        </td>
                                        <td class="h-px w-auto whitespace-nowrap">
                                            <div class="px-6 py-2">
                                                <span class="font-semibold text-gray-800 dark:text-neutral-200"> <?= $cutiEntry['tanggal_selesai'] ?></span>
                                            </div>
                                        </td>

                                        <td class="h-px w-auto whitespace-nowrap">
                                            <div class="px-6 py-2">
                                                <span class="py-1 px-2 inline-flex items-center gap-x-1 text-xs font-medium 
            <?php
                                    if ($cutiEntry['status'] == 'Diterima') {
                                        echo 'bg-[#D6F9F3] text-[#13594E]';
                                    } elseif ($cutiEntry['status'] == 'Ditolak') {
                                        echo 'bg-[#FEE2E2] text-[#991B1B]';
                                    } elseif ($cutiEntry['status'] == 'Diproses') {
                                        echo 'bg-[#FEF9C3] text-[#713F12]';
                                    }
            ?> rounded-full dark:bg-teal-500/10 dark:text-teal-500">
                                                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="size-2.5">
                                                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                                        <g id="SVGRepo_iconCarrier">
                                                            <circle cx="12" cy="12" r="9" fill="<?php
                                                                                                if ($cutiEntry['status'] == 'Diterima') {
                                                                                                    echo '#13594E';
                                                                                                } elseif ($cutiEntry['status'] == 'Ditolak') {
                                                                                                    echo '#991B1B';
                                                                                                } elseif ($cutiEntry['status'] == 'Diproses') {
                                                                                                    echo '#713F12';
                                                                                                }
                                                                                                ?>"></circle>
                                                        </g>
                                                    </svg>
                                                    <?= $cutiEntry['status'] ?>
                                                </span>
                                            </div>
                                        </td>

                                        <td class="h-px w-auto whitespace-nowrap">
                                            <div class="px-6 py-2">
                                                <span class="font-semibold text-gray-800 dark:text-neutral-200"> <?= $alasanCuti[$cutiEntry['id_alasan_cuti']] ?? 'N/A' ?></span>
                                            </div>
                                        </td>

                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <!-- End Table -->

                        </div>


                    </div>
                </div>
            </div>
        </div>
        <!-- End Card -->
    </div>
    <!-- End Table Section -->

    <script>
        function myFunction() {
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById("myInput");
            filter = input.value.toUpperCase();
            table = document.getElementById("myTable");
            tr = table.getElementsByTagName("tr");


            for (i = 0; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td")[0];
                if (td) {
                    txtValue = td.textContent || td.innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
        }
    </script>

    <?= $this->endSection(); ?>