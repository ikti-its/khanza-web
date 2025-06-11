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
                                    Catatan Kehadiran
                                </h2>
                            </div>

                        </div>


                    </div>

                    <?= view('components/data_search_bar') ?>


                    <div class="overflow-x-auto">
                        <!-- Table -->
                        <table id="myTable" class="min-w-full divide-y divide-gray-50 dark:divid e-neutral-700 text-xs">
                            <thead class="bg-gray-50 divide-y divide-gray-200 dark:bg-neutral-800 dark:divide-neutral-700">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-start border-s border-gray-200 dark:border-neutral-700">
                                        <span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200">
                                            Tanggal
                                        </span>
                                    </th>

                                    <th scope="col" class="px-6 py-3 text-start">
                                        <span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200">
                                            Status Hadir
                                        </span>
                                    </th>

                                    <th scope="col" class="px-6 py-3 text-start">
                                        <span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200">
                                            Jam Hadir
                                        </span>
                                    </th>

                                    <th scope="col" class="px-6 py-3 text-start">
                                        <span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200">
                                            Jam Pulang
                                        </span>
                                    </th>

                                </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-200 dark:divide-neutral-700 text-xs">
                                <?php foreach ($kehadiran_data as $kehadiranEntry) : ?>
                                    <tr>
                                        <td class="h-px w-auto whitespace-nowrap">
                                            <div class="px-6 py-2 flex items-center gap-x-3">
                                                <a class="flex items-center gap-x-2" href="">
                                                    <span class="font-semibold hover:underline"><?= $kehadiranEntry['tanggal'] ?? 'N/A' ?></span>
                                                </a>
                                            </div>
                                        </td>
                                        <td class="h-px w-auto whitespace-nowrap">
                                            <div class="px-6 py-2">
                                                <?php if (isset($kehadiranEntry['keterangan'])) : ?>
                                                    <?php if ($kehadiranEntry['keterangan'] == 'Terlambat') : ?>
                                                        <span class="py-1 px-2 inline-flex items-center gap-x-1 text-xs font-medium bg-red-100 text-red-800 rounded-full dark:bg-red-500/10 dark:text-red-500">
                                                            <svg class="w-2 h-2.5" xmlns="http://www.w3.org/2000/svg" width="17" height="16" viewBox="0 0 17 16" fill="none">
                                                                <path d="M8.62504 14.6666C12.3069 14.6666 15.2917 11.6818 15.2917 7.99992C15.2917 4.31802 12.3069 1.33325 8.62504 1.33325C4.94314 1.33325 1.95837 4.31802 1.95837 7.99992C1.95837 11.6818 4.94314 14.6666 8.62504 14.6666Z" fill="#991B1B" />
                                                                <path d="M9.33166 7.99999L10.865 6.46666C11.0583 6.27332 11.0583 5.95332 10.865 5.75999C10.6717 5.56666 10.3517 5.56666 10.1583 5.75999L8.62499 7.29332L7.09166 5.75999C6.89832 5.56666 6.57832 5.56666 6.38499 5.75999C6.19166 5.95332 6.19166 6.27332 6.38499 6.46666L7.91832 7.99999L6.38499 9.53332C6.19166 9.72666 6.19166 10.0467 6.38499 10.24C6.48499 10.34 6.61166 10.3867 6.73832 10.3867C6.86499 10.3867 6.99166 10.34 7.09166 10.24L8.62499 8.70666L10.1583 10.24C10.2583 10.34 10.385 10.3867 10.5117 10.3867C10.6383 10.3867 10.765 10.34 10.865 10.24C11.0583 10.0467 11.0583 9.72666 10.865 9.53332L9.33166 7.99999Z" fill="#FEE2E2" />
                                                            </svg>
                                                            <?= $kehadiranEntry['keterangan'] ?>
                                                        </span>
                                                    <?php elseif ($kehadiranEntry['keterangan'] == 'Darurat') : ?>
                                                        <span class="py-1 px-2 inline-flex items-center gap-x-1 text-xs font-medium bg-yellow-100 text-yellow-800 rounded-full dark:bg-yellow-500/10 dark:text-yellow-500">
                                                            <svg class="w-2 h-2.5" xmlns="http://www.w3.org/2000/svg" width="17" height="16" viewBox="0 0 17 16" fill="none">
                                                                <path d="M8.62504 14.6666C12.3069 14.6666 15.2917 11.6818 15.2917 7.99992C15.2917 4.31802 12.3069 1.33325 8.62504 1.33325C4.94314 1.33325 1.95837 4.31802 1.95837 7.99992C1.95837 11.6818 4.94314 14.6666 8.62504 14.6666Z" fill="#F59E0B" />
                                                                <path d="M8.625 5.5V8.25H11.375L11.25 9.5H8.625V12H7.25V9.5H4.625V8.25H7.25V5.5H8.625Z" fill="#FEE2E2" />
                                                            </svg>
                                                            <?= $kehadiranEntry['keterangan'] ?>
                                                        </span>
                                                    <?php else : ?>
                                                        <span class="py-1 px-2 inline-flex items-center gap-x-1 text-xs font-medium bg-teal-100 text-teal-800 rounded-full dark:bg-teal-500/10 dark:text-teal-500">
                                                            <svg class="w-2 h-2.5" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                                                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
                                                            </svg>
                                                            <?= $kehadiranEntry['keterangan'] ?>
                                                        </span>
                                                    <?php endif; ?>
                                                <?php else : ?>
                                                    <span class="py-1 px-2 inline-flex items-center gap-x-1 text-xs font-medium bg-gray-100 text-gray-800 rounded-full dark:bg-gray-500/10 dark:text-gray-500">
                                                        <?= "Belum presensi pulang" ?>
                                                    </span>
                                                <?php endif; ?>

                                            </div>

                                        </td>


                                        <td class="h-px w-auto whitespace-nowrap">
                                            <div class="px-6 py-2">
                                                <span class="font-semibold text-gray-800 dark:text-neutral-200"><?= $kehadiranEntry['jam_masuk'] ?? 'N/A' ?></span>
                                            </div>
                                        </td>
                                        <td class="h-px w-auto whitespace-nowrap">
                                            <div class="px-6 py-2">
                                                <span class="font-semibold text-gray-800 dark:text-neutral-200"><?= $kehadiranEntry['jam_pulang'] ?? 'N/A' ?></span>
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