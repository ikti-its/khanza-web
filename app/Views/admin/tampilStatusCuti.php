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

                    </div>

                    <div class="px-6 py-4 grid gap-3 md:flex md:justify-between md:items-center border-gray-200 dark:border-neutral-700">
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



                    <!-- Table -->
                    <table id="myTable" class="min-w-full divide-y divide-gray-50 dark:divid e-neutral-700 text-xs">
                        <thead class="bg-gray-50 divide-y divide-gray-200 dark:bg-neutral-800 dark:divide-neutral-700">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-start border-s border-gray-200 dark:border-neutral-700">
                                    <span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200">
                                        ID Pegawai
                                    </span>
                                </th>

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

                                <th scope="col" class="px-6 py-3 text-start">
                                    <span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200">
                                        Aksi
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
                                <form action="/submiteditstatuscuti/<?= $cutiEntry['id'] ?>" method="POST">
                                    <tr>
                                        <td class="h-px w-auto whitespace-nowrap">
                                            <div class="px-6 py-2 flex items-center gap-x-3">
                                                <input type="text" id="id_pegawai" name="id_pegawai" class="font-semibold text-gray-800 bg-transparent border-none outline-none" value="<?= $cutiEntry['id'] ?>" readonly>
                                            </div>
                                        </td>

                                        <td class="h-px w-auto whitespace-nowrap">
                                            <div class="px-6 py-2 flex items-center gap-x-3">
                                                <input type="text" id="tanggal_mulai" name="tanggal_mulai" class="font-semibold text-gray-800 bg-transparent border-none outline-none" value="<?= $cutiEntry['tanggal_mulai'] ?>" readonly>
                                            </div>
                                        </td>
                                        <td class="h-px w-auto whitespace-nowrap">
                                            <div class="px-6 py-2">
                                                <input type="text" id="tanggal_selesai" name="tanggal_selesai" class="font-semibold text-gray-800 bg-transparent border-none outline-none" value="<?= $cutiEntry['tanggal_selesai'] ?>" readonly>
                                            </div>
                                        </td>

                                        <td id="status" name="status" class="h-px w-auto whitespace-nowrap">
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
                                                <span class="font-semibold text-gray-800 dark:text-neutral-200"> <?= $alasanCuti[$cutiEntry['id_alasan_cuti']] ?? 'N/A'  ?></span>
                                                <input type="hidden" name="id_alasan_cuti" id="id_alasan_cuti" value="<?= $cutiEntry['id_alasan_cuti'] ?>">
                                            </div>

                                        </td>

                                        <td class="h-px w-auto whitespace-nowrap">
                                            <div class="px-6 py-2">
                                                <?php if ($cutiEntry['status'] == 'Diproses') : ?>
                                                    <button type="submit" name="status" value="Diterima" class="py-1 px-3 bg-teal-500 text-white rounded-lg">Terima</button>
                                                    <!-- Tolak button -->
                                                    <button type="submit" name="status" value="Ditolak" class="py-1 px-3 bg-red-500 text-white rounded-lg">Tolak</button>
                                                <?php endif; ?>
                                            </div>
                                        </td>

                                </form>

                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <!-- End Table -->




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