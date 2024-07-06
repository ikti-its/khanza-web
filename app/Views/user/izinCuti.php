<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<!-- Table Section -->
<div class="overflow overflow-auto px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto">
    <!-- Card -->
    <div class="flex flex-col">
        <div class="-m-3.5 overflow-x-auto">
            <div class="p-1.5 w-full inline-block align-middle">

                <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden dark:bg-neutral-900 dark:border-neutral-700">

                    <form method="post" action="/submittambahcuti">

                        <div class="px-6 py-5 grid gap-3 md:flex md:justify-between md:items-center">
                            <div class="sm:col-span-12">
                                <h2 class="text-lg font-semibold text-gray-800 dark:text-neutral-200">
                                    Pengajuan Cuti
                                </h2>
                            </div>

                        </div>

                        <div class="py-4 mx-6 flex justify-between items-center border-b border-gray-200 dark:border-neutral-700">

                            <div class="grid sm:grid-cols-12 gap-2 sm:gap-6">

                                <div class="sm:col-span-3">
                                    <label for="af-account-role" class="inline-block text-sm text-gray-800 mt-2.5 dark:text-neutral-200">
                                        Tanggal Mulai
                                    </label>
                                </div>
                                <!-- End Col -->

                                <div class="sm:col-span-9">
                                    <!-- Input field to display the selected date -->
                                    <input id="selected-date-mulai" type="text" class="py-2 px-3 pe-11 block w-full border-gray-200 shadow-sm rounded-lg text-sm focus:outline-teal-500 focus:ring-teal-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600" placeholder="Pilih tanggal awal cuti" readonly>
                                    <!-- Hidden input field to store the selected date value -->
                                    <input id="tanggal_mulai" name="tanggal_mulai" type="hidden">
                                </div>
                                <!-- End Col -->

                                <div class="sm:col-span-3">
                                    <label for="af-account-role" class="inline-block text-sm text-gray-800 mt-2.5 dark:text-neutral-200">
                                        Tanggal Selesai
                                    </label>
                                </div>
                                <!-- End Col -->

                                <div class="sm:col-span-9">
                                    <!-- Input field to display the selected date -->
                                    <input id="selected-date-selesai" type="text" class="py-2 px-3 pe-11 block w-full border-gray-200 shadow-sm rounded-lg text-sm focus:outline-teal-500 focus:ring-teal-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600" placeholder="Pilih tanggal selesai cuti" readonly>
                                    <!-- Hidden input field to store the selected date value -->
                                    <input id="tanggal_selesai" name="tanggal_selesai" type="hidden">
                                </div>
                                <!-- End Col -->

                                <div class="sm:col-span-3">
                                    <label for="af-account-alasan" class="inline-block text-sm text-gray-800 mt-2.5 dark:text-neutral-200">
                                        Alasan Cuti
                                    </label>
                                </div>
                                <!-- End Col -->

                                <div class="sm:col-span-9">
                                    <div class="sm:flex">
                                        <select name="id_alasan_cuti" class="py-2 px-3 pe-9 block w-full sm:w-auto border-gray-200 shadow-sm -mt-px -ms-px first:rounded-t-lg last:rounded-b-lg sm:first:rounded-s-lg sm:mt-0 sm:first:ms-0 sm:first:rounded-se-none sm:last:rounded-es-none sm:last:rounded-e-lg text-sm relative focus:z-10 focus:border-teal-500 focus:ring-teal-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600">
                                            <option value="S" selected>Sakit</option>
                                            <option value="I">Izin</option>
                                            <option value="CT">Cuti Tahunan</option>
                                            <option value="CB">Cuti Besar</option>
                                            <option value="CM">Cuti Melahirkan</option>
                                            <option value="CU">Cuti karena Alasan Penting</option>
                                        </select>
                                    </div>
                                </div>
                                <!-- End Col -->
                                <!-- Hidden input field for pegawai_id -->
                                <input type="hidden" name="id_pegawai" id="id_pegawai" value="<?php echo session('user_specific_data')['pegawai'] ?>">
                            </div>
                        </div>

                        <div class="py-2 mb-2 mx-6 flex justify-end items-center">
                            <!-- Buttons -->
                            <div class="mt-6 flex justify-end gap-x-3">
                                <a href="javascript:history.back()" class="py-2 px-3 inline-flex justify-center items-center gap-2 rounded-lg border font-medium bg-white text-gray-700 shadow-sm align-middle hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-white focus:ring-teal-600 transition-all text-sm dark:bg-neutral-800 dark:hover:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-400 dark:hover:text-white dark:focus:ring-offset-gray-800">
                                    Batal
                                </a>
                                <button id="ajukan-button" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-[#0A2D27] text-[#ACF2E7] hover:bg-teal-700 disabled:opacity-50 disabled:pointer-events-none " disabled>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var ajukanButton = document.getElementById('ajukan-button');
            var tanggalMulaiFlatpickr = flatpickr('#selected-date-mulai', {
                altInput: true,
                altFormat: 'Y-m-d',
                dateFormat: 'Y-m-d',
                onClose: function(selectedDates, dateStr, instance) {
                    document.getElementById('tanggal_mulai').value = dateStr;
                    tanggalSelesaiFlatpickr.set('minDate', dateStr);
                    toggleAjukanButton();
                }
            });

            var tanggalSelesaiFlatpickr = flatpickr('#selected-date-selesai', {
                altInput: true,
                altFormat: 'Y-m-d',
                dateFormat: 'Y-m-d',
                onClose: function(selectedDates, dateStr, instance) {
                    document.getElementById('tanggal_selesai').value = dateStr;
                    toggleAjukanButton();
                }
            });

            function toggleAjukanButton() {
                var tanggalMulai = document.getElementById('tanggal_mulai').value;
                var tanggalSelesai = document.getElementById('tanggal_selesai').value;
                if (tanggalMulai && tanggalSelesai) {
                    ajukanButton.disabled = false;
                } else {
                    ajukanButton.disabled = true;
                }
            }
        });
    </script>

<?= $this->endSection(); ?>
