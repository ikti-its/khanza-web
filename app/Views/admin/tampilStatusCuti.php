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

                    <?= view('components/search_bar') ?>



                    <!-- Table -->
                    <div class="overflow-x-auto">
                        <table id="myTable" class="min-w-full divide-y divide-gray-50 dark:divid e-neutral-700 text-xs">
                            <thead class="bg-gray-50 divide-y divide-gray-200 dark:bg-neutral-800 dark:divide-neutral-700">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-start border-s border-gray-200 dark:border-neutral-700">
                                        <span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200">
                                            Nama Pegawai
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

                                    <form action="/submiteditstatuscuti/<?= $cutiEntry['id'] ?>" method="POST" onsubmit="return validateForm()">
                                    <input type="hidden" id="status_input" name="status" value="">
                                        <tr>
                                            <td class="h-px w-auto whitespace-nowrap">
                                                <div class="px-6 py-2 flex items-center gap-x-3">
                                                    <input type="text" id="nama_pegawai" name="nama_pegawai" class="font-semibold text-gray-800 bg-transparent border-none outline-none" value="<?= $cutiEntry['nama_pegawai'] ?>" readonly>
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

                                            <input type="hidden" name="id_pegawai" id="id_pegawai" value="<?= $cutiEntry['id'] ?>">

                                            <td class="h-px w-auto whitespace-nowrap">
                                                <div class="px-6 py-2">
                                                    <?php if ($cutiEntry['status'] == 'Diproses') : ?>
                                                        <button data-hs-overlay="#hs-cuti-alert-<?= $cutiEntry['id'] ?>" type="button" class="py-1 px-3 bg-teal-500 text-white rounded-lg">Terima</button>


                                                        <div id="hs-cuti-alert-<?= $cutiEntry['id'] ?>" class="hs-overlay hidden size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto">
                                                            <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto">
                                                                <div class="relative flex flex-col bg-white shadow-lg rounded-xl dark:bg-neutral-900">
                                                                    <div class="absolute top-2 end-2">
                                                                        <button type="button" class="flex justify-center items-center size-7 text-sm font-semibold rounded-lg border border-transparent text-gray-800 hover:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:border-transparent dark:hover:bg-neutral-700" data-hs-overlay="#hs-cuti-alert-<?= $cutiEntry['id'] ?>">
                                                                            <span class="sr-only">Close</span>
                                                                            <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                                                <path d="M18 6 6 18" />
                                                                                <path d="m6 6 12 12" />
                                                                            </svg>
                                                                        </button>
                                                                    </div>

                                                                    <div class="p-4 sm:p-10 text-center overflow-y-auto">
                                                                        <!-- Icon -->
                                                                        <span class="mb-4 inline-flex justify-center items-center size-[62px] rounded-full border-4">
                                                                            <svg width="42" height="42" viewBox="0 0 42 42" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                                <path d="M21 17.5C24.866 17.5 28 14.366 28 10.5C28 6.63401 24.866 3.5 21 3.5C17.134 3.5 14 6.63401 14 10.5C14 14.366 17.134 17.5 21 17.5Z" fill="#0A2D27" />
                                                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M28.875 38.5C25.9875 38.5 24.5437 38.5 23.6477 37.6022C22.75 36.7062 22.75 35.2625 22.75 32.375C22.75 29.4875 22.75 28.0437 23.6477 27.1477C24.5437 26.25 25.9875 26.25 28.875 26.25C31.7625 26.25 33.2062 26.25 34.1022 27.1477C35 28.0437 35 29.4875 35 32.375C35 35.2625 35 36.7062 34.1022 37.6022C33.2062 38.5 31.7625 38.5 28.875 38.5ZM32.319 31.0555C32.5105 30.864 32.618 30.6044 32.618 30.3336C32.618 30.0629 32.5105 29.8032 32.319 29.6117C32.1275 29.4203 31.8679 29.3127 31.5971 29.3127C31.3264 29.3127 31.0667 29.4203 30.8752 29.6117L27.5152 32.9717L26.8748 32.333C26.78 32.2382 26.6674 32.163 26.5436 32.1117C26.4197 32.0604 26.2869 32.034 26.1529 32.034C26.0188 32.034 25.8861 32.0604 25.7622 32.1117C25.6383 32.163 25.5258 32.2382 25.431 32.333C25.3362 32.4278 25.261 32.5403 25.2097 32.6642C25.1584 32.7881 25.132 32.9208 25.132 33.0549C25.132 33.1889 25.1584 33.3217 25.2097 33.4456C25.261 33.5694 25.3362 33.682 25.431 33.7768L26.7925 35.1383C26.8873 35.2331 26.9998 35.3084 27.1236 35.3597C27.2475 35.4111 27.3803 35.4375 27.5144 35.4375C27.6485 35.4375 27.7812 35.4111 27.9051 35.3597C28.029 35.3084 28.1415 35.2331 28.2363 35.1383L32.319 31.0555Z" fill="#0A2D27" />
                                                                                <path d="M31.6662 26.3043C30.9225 26.25 30.0107 26.25 28.875 26.25C25.9875 26.25 24.5437 26.25 23.6477 27.1477C22.75 28.0437 22.75 29.4875 22.75 32.375C22.75 34.4155 22.75 35.735 23.0667 36.6503C22.3947 36.7168 21.7052 36.75 21 36.75C14.2345 36.75 8.75 33.6175 8.75 29.75C8.75 25.8825 14.2345 22.75 21 22.75C25.5728 22.75 29.561 24.1815 31.6662 26.3043Z" fill="#24A793" />
                                                                            </svg>

                                                                        </span>
                                                                        <!-- End Icon -->

                                                                        <h3 class="mb-2 text-2xl font-bold text-gray-800 dark:text-neutral-200">
                                                                            Menyetujui Pengajuan Cuti
                                                                        </h3>
                                                                        <p class="text-gray-500 dark:text-neutral-500">
                                                                            Apakah Anda Yakin untuk menyetujui ajuan cuti?
                                                                        </p>

                                                                        <div class="mt-6 flex justify-center gap-x-4">

                                                                            <button type="button" data-hs-overlay="#hs-cuti-alert-<?= $cutiEntry['id'] ?>" href="javascript:history.back()" class="py-2 px-3 inline-flex justify-center items-center gap-2 rounded-lg border font-medium bg-white text-gray-700 shadow-sm align-middle hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-white focus:ring-teal-600 transition-all text-sm dark:bg-neutral-800 dark:hover:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-400 dark:hover:text-white dark:focus:ring-offset-gray-800">
                                                                                Batal
                                                                            </button>
                                                                            <button type="submit" name="status" id="submitButton" value="Diterima" onclick="updateStatus('Diterima')" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-[#0A2D27] text-[#ACF2E7] hover:bg-teal-700 disabled:opacity-50 disabled:pointer-events-none ">
                                                                                Setuju
                                                                            </button>

                                                                        </div>


                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Tolak button -->
                                                        <button data-hs-overlay="#hs-cuti-tolak-alert-<?= $cutiEntry['id'] ?>" type="button" class="py-1 px-3 bg-red-500 text-white rounded-lg">Tolak</button>

                                                        <div id="hs-cuti-tolak-alert-<?= $cutiEntry['id'] ?>" class="hs-overlay hidden size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto">
                                                            <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto">
                                                                <div class="relative flex flex-col bg-white shadow-lg rounded-xl dark:bg-neutral-900">
                                                                    <div class="absolute top-2 end-2">
                                                                        <button type="button" class="flex justify-center items-center size-7 text-sm font-semibold rounded-lg border border-transparent text-gray-800 hover:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:border-transparent dark:hover:bg-neutral-700" data-hs-overlay="#hs-cuti-tolak-alert-<?= $cutiEntry['id'] ?>">
                                                                            <span class="sr-only">Close</span>
                                                                            <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                                                <path d="M18 6 6 18" />
                                                                                <path d="m6 6 12 12" />
                                                                            </svg>
                                                                        </button>
                                                                    </div>

                                                                    <div class="p-4 sm:p-10 text-center overflow-y-auto">
                                                                        <!-- Icon -->
                                                                        <span class="mb-4 inline-flex justify-center items-center size-[62px] rounded-full border-4">
                                                                            <svg width="42" height="42" viewBox="0 0 42 42" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                                <path d="M28 10.5C28 12.3565 27.2625 14.137 25.9497 15.4497C24.637 16.7625 22.8565 17.5 21 17.5C19.1435 17.5 17.363 16.7625 16.0503 15.4497C14.7375 14.137 14 12.3565 14 10.5C14 8.64348 14.7375 6.86301 16.0503 5.55025C17.363 4.2375 19.1435 3.5 21 3.5C22.8565 3.5 24.637 4.2375 25.9497 5.55025C27.2625 6.86301 28 8.64348 28 10.5Z" fill="#DA4141" />
                                                                                <path d="M25.3348 38.36C24.0643 38.4528 22.6275 38.5 21 38.5C7 38.5 7 34.9738 7 30.625C7 26.2763 13.2685 22.75 21 22.75C26.04 22.75 30.4605 24.248 32.9245 26.4967C32.0215 26.25 30.7545 26.25 28.875 26.25C25.9875 26.25 24.5438 26.25 23.6478 27.1477C22.75 28.0437 22.75 29.4875 22.75 32.375C22.75 35.2625 22.75 36.7062 23.6478 37.6022C24.0625 38.0187 24.5963 38.2393 25.3348 38.36Z" fill="#FF9797" />
                                                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M28.875 38.5C25.9875 38.5 24.5437 38.5 23.6477 37.6022C22.75 36.7062 22.75 35.2625 22.75 32.375C22.75 29.4875 22.75 28.0437 23.6477 27.1477C24.5437 26.25 25.9875 26.25 28.875 26.25C31.7625 26.25 33.2062 26.25 34.1022 27.1477C35 28.0437 35 29.4875 35 32.375C35 35.2625 35 36.7062 34.1022 37.6022C33.2062 38.5 31.7625 38.5 28.875 38.5ZM26.8748 28.931C26.6833 28.7395 26.4236 28.632 26.1529 28.632C25.8821 28.632 25.6225 28.7395 25.431 28.931C25.2395 29.1225 25.132 29.3821 25.132 29.6529C25.132 29.9236 25.2395 30.1833 25.431 30.3748L27.4313 32.375L25.431 34.3752C25.3362 34.47 25.261 34.5826 25.2097 34.7064C25.1584 34.8303 25.132 34.9631 25.132 35.0971C25.132 35.2312 25.1584 35.3639 25.2097 35.4878C25.261 35.6117 25.3362 35.7242 25.431 35.819C25.5258 35.9138 25.6383 35.989 25.7622 36.0403C25.8861 36.0916 26.0188 36.118 26.1529 36.118C26.2869 36.118 26.4197 36.0916 26.5436 36.0403C26.6674 35.989 26.78 35.9138 26.8748 35.819L28.875 33.8188L30.8752 35.819C30.97 35.9138 31.0826 35.989 31.2064 36.0403C31.3303 36.0916 31.4631 36.118 31.5971 36.118C31.7312 36.118 31.8639 36.0916 31.9878 36.0403C32.1117 35.989 32.2242 35.9138 32.319 35.819C32.4138 35.7242 32.489 35.6117 32.5403 35.4878C32.5916 35.3639 32.618 35.2312 32.618 35.0971C32.618 34.9631 32.5916 34.8303 32.5403 34.7064C32.489 34.5826 32.4138 34.47 32.319 34.3752L30.3188 32.375L32.319 30.3748C32.5105 30.1833 32.618 29.9236 32.618 29.6529C32.618 29.3821 32.5105 29.1225 32.319 28.931C32.1275 28.7395 31.8679 28.632 31.5971 28.632C31.3264 28.632 31.0667 28.7395 30.8752 28.931L28.875 30.9312L26.8748 28.931Z" fill="#DA4141" />
                                                                            </svg>


                                                                        </span>
                                                                        <!-- End Icon -->

                                                                        <h3 class="mb-2 text-2xl font-bold text-gray-800 dark:text-neutral-200">
                                                                            Menolak Pengajuan Cuti
                                                                        </h3>
                                                                        <p class="text-gray-500 dark:text-neutral-500">
                                                                            Apakah Anda Yakin untuk menolak ajuan cuti?
                                                                        </p>

                                                                        <div class="mt-6 flex justify-center gap-x-4">
                                                                            <button type="submit" name="status" value="Ditolak" id="submitButton2" onclick="updateStatus('Ditolak')" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-[#DA4141] text-[#FDFDFD] hover:bg-red-700 disabled:opacity-50 disabled:pointer-events-none ">
                                                                                Tolak
                                                                            </button>
                                                                            <button type="button"  data-hs-overlay="#hs-cuti-tolak-alert-<?= $cutiEntry['id'] ?>" href="javascript:history.back()" class="py-2 px-3 inline-flex justify-center items-center gap-2 rounded-lg border font-medium bg-white text-gray-700 shadow-sm align-middle hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-white focus:ring-teal-600 transition-all text-sm dark:bg-neutral-800 dark:hover:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-400 dark:hover:text-white dark:focus:ring-offset-gray-800">
                                                                                Batal
                                                                            </button>


                                                                        </div>


                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    <?php endif; ?>
                                                </div>
                                            </td>

                                    </form>

                                    </tr>

                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- End Table -->




                </div>
            </div>
        </div>
    </div>
    <!-- End Card -->
</div>
<!-- End Table Section -->

<script>

function updateStatus(status) {
        document.getElementById('status_input').value = status;
    }

    function myFunction() {
        var input, filter, table, tr, td, i, j, txtValue, found;
        input = document.getElementById("myInput");
        filter = input.value.toUpperCase();
        table = document.getElementById("myTable");
        tr = table.getElementsByTagName("tr");

        for (i = 1; i < tr.length; i++) { // Start from 1 to skip the header row
            tr[i].style.display = "none"; // Hide the row initially
            td = tr[i].getElementsByTagName("td");
            found = false;
            for (j = 0; j < td.length; j++) {
                if (td[j]) {
                    txtValue = td[j].textContent || td[j].innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        found = true;
                        break;
                    }
                }
            }
            if (found) {
                tr[i].style.display = "";
            }
        }
    }

    function validateForm() {
    var submitButton = document.getElementById('submitButton');
    submitButton.disabled = true; // Disable the button
    submitButton.textContent = 'Mengajukan...'; // Change the text content

    return true; // Return true to allow form submission
}
</script>


<?= $this->endSection(); ?>