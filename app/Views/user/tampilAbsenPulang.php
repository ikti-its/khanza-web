<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>


<?php
date_default_timezone_set('Asia/Bangkok'); // Set default timezone to Asia/Bangkok
?>

<!-- Table Section -->
<div class="overflow overflow-auto px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto">
    <!-- Card -->
    <div class="flex flex-col">
        <div class="-m-3.5 overflow-x-auto">
            <div class="p-1.5 w-full inline-block align-middle">

                <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden dark:bg-neutral-900 dark:border-neutral-700">

                    <form id="absenForm" method="post" action="/submittambahabsenpulang">

                        <div class="px-6 py-5 grid gap-3 md:flex md:justify-between md:items-center">
                            <div class="sm:col-span-12">
                                <h2 class="text-lg font-bold text-gray-800 dark:text-neutral-200">
                                    Absen Pulang
                                </h2>
                            </div>
                        </div>

                        <div class="py-4 mx-6 flex justify-between items-center border-b border-gray-200 dark:border-neutral-700">

                            <div class="grid sm:grid-cols-12 gap-2 sm:gap-6">

                                <div class="sm:col-span-3">
                                    <label for="af-account-role" class="inline-block text-sm text-gray-800 mt-2.5 dark:text-neutral-200">
                                        Shift
                                    </label>
                                </div>
                                <!-- End Col -->

                                <div class="sm:col-span-9">
                                    <!-- Input field to display the selected date -->
                                    <input id="id_shift" name="id_shift" type="text" class="py-2 px-3 pe-11 block w-full border-gray-200 shadow-sm rounded-lg text-sm focus:outline-teal-500 focus:ring-teal-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600" placeholder="Shift Kerja" value="<?= $kehadiran_data[0]['id_shift'] ?>" readonly>
                                </div>
                                <!-- End Col -->

                                <div class="sm:col-span-3">
                                    <label for="af-account-role" class="inline-block text-sm text-gray-800 mt-2.5 dark:text-neutral-200">
                                        Jam Masuk
                                    </label>
                                </div>
                                <!-- End Col -->

                                <div class="sm:col-span-9">
                                    <!-- Input field to display the selected date -->
                                    <input id="jam_masuk" name="jam_masuk" type="text" class="py-2 px-3 pe-11 block w-full border-gray-200 shadow-sm rounded-lg text-sm focus:outline-teal-500 focus:ring-teal-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600" placeholder="Jam Masuk" value="<?= $kehadiran_data[0]['jam_masuk'] ?>" readonly>
                                </div>
                                <!-- End Col -->

                                <div class="sm:col-span-3">
                                    <label for="af-account-alasan" class="inline-block text-sm text-gray-800 mt-2.5 dark:text-neutral-200">
                                        Jam Pulang
                                    </label>
                                </div>
                                <!-- End Col -->
                                <div class="sm:col-span-9">
                                    <!-- Input field to display the selected date -->
                                    <input id="jam_pulang" name="jam_pulang" type="text" class="py-2 px-3 pe-11 block w-full border-gray-200 shadow-sm rounded-lg text-sm focus:outline-teal-500 focus:ring-teal-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600" placeholder="Selected Date" value="<?= $kehadiran_data[0]['jam_pulang'] ?>" readonly>
                                </div>

                                <div class="sm:col-span-3">
                                    <label for="af-account-alasan" class="inline-block text-sm text-gray-800 mt-2.5 dark:text-neutral-200">
                                        Absen Darurat
                                    </label>
                                </div>
                                <!-- End Col -->
                                <div class="sm:col-span-9">
                                    <!-- Switch component -->
                                    <label class="mt-2 relative inline-flex cursor-pointer items-center">
                                        <input id="emergencySwitch" name="emergencySwitch" type="checkbox" class="peer sr-only" />
                                        <label for="switch-2" class="hidden"></label>
                                        <div class="peer h-4 w-11 rounded-full border bg-gray-200 dark:bg-gray-600 after:absolute after:-top-1 after:left-0 after:h-6 after:w-6 after:rounded-full after:border after:border-gray-300 after:bg-white after:transition-all after:content-[''] peer-checked:bg-teal-700 peer-checked:after:translate-x-full peer-focus:ring-green-300"></div>
                                    </label>
                                </div>
                                <!-- End Col -->

                                <!-- Hidden input field for pegawai_id -->
                                <input type="hidden" name="id_pegawai" id="id_pegawai" value="<?= $kehadiran_data[0]['id_pegawai'] ?>">
                                <input type="hidden" id="id_jadwal" name="id_jadwal" placeholder="Selected Date" value="<?= $kehadiran_data[0]['id'] ?>" readonly>
                                <input type="hidden" id="tanggal" name="tanggal" placeholder="Selected Date" value="<?= date('Y-m-d') ?>" readonly>
                            </div>

                        </div>

                        <div class=" py-2 mb-2 mx-6 flex justify-end items-center">
                            <!-- Buttons -->
                            <div class="mt-6 flex justify-end gap-x-3">
                                <a href="javascript:history.back()" type="button" id="submitBtn" class="py-2 px-3 inline-flex justify-center items-center gap-2 rounded-lg border font-medium bg-white text-gray-700 shadow-sm align-middle hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-white focus:ring-teal-600 transition-all text-sm dark:bg-neutral-800 dark:hover:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-400 dark:hover:text-white dark:focus:ring-offset-gray-800">
                                    Batal
</a>
                                <button type="submit" id="ajukanBtn" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-[#0A2D27] text-[#ACF2E7] hover:bg-teal-500 disabled:opacity-50 disabled:pointer-events-none">
                                    Ajukan
                                </button>
                            </div>
                            <!-- End Buttons -->
                        </div>

                    </form>

                </div>
            </div>
        </div>
        <!-- End Card -->
    </div>
    <!-- End Table Section -->
</div>


<?= $this->endSection(); ?>
