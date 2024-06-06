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
                                    Jadwal Kerja
                                </h2>
                            </div>

                        </div>

                    </div>

                    <!-- Table -->
                    <table id="myTable" class="min-w-full divide-y divide-gray-50 dark:divid e-neutral-700 text-xs">
                        <thead class="bg-gray-50 divide-y divide-gray-200 dark:bg-neutral-800 dark:divide-neutral-700">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-start border-s border-gray-200 dark:border-neutral-700">
                                    <span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200">
                                        Jadwal Hari
                                    </span>
                                </th>

                                <th scope="col" class="px-6 py-3 text-start">
                                    <span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200">
                                        Shift
                                    </span>
                                </th>

                                <th scope="col" class="px-6 py-3 text-start">
                                    <span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200">
                                        Jam Masuk
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

                            <?php
                            $days = [
                                1 => 'Senin',
                                2 => 'Selasa',
                                3 => 'Rabu',
                                4 => 'Kamis',
                                5 => 'Jumat',
                                6 => 'Sabtu',
                                7 => 'Minggu'
                            ];

                            $shift = [
                                'P' => 'Pagi',
                                'S' => 'Siang', 
                                'M' => 'Malam'

                            ];

                            foreach ($kehadiran_data as $kehadiranEntry) : ?>
                                <tr>
                                    <td class="h-px w-auto whitespace-nowrap">
                                        <div class="px-6 py-2 flex items-center gap-x-3">
                                            <a class="flex items-center gap-x-2" href="">
                                                <span class="font-semibold hover:underline"><?= $days[$kehadiranEntry['id_hari']] ?? 'N/A' ?></span>
                                            </a>
                                        </div>
                                    </td>
                                    <td class="h-px w-auto whitespace-nowrap">
                                        <div class="px-6 py-2">
                                            <span class="font-semibold text-gray-800 dark:text-neutral-200"><?= $shift[$kehadiranEntry['id_shift']] ?? 'N/A' ?></span>
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
    <!-- End Card -->
</div>
<!-- End Table Section -->


<?= $this->endSection(); ?>