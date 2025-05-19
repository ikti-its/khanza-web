<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<!-- Table Section -->
<div class="overflow overflow-auto px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto">
    <!-- Card -->
    <div class="flex flex-col">
        <div class="-m-3.5 overflow-x-auto">
            <div class="p-1.5 w-full inline-block align-middle">

                <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden dark:bg-neutral-900 dark:border-neutral-700">

                    <form method="post" action="/submittambahcuti" onsubmit="return validateForm()">


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
                                <input type="hidden" name="id_pegawai" id="id_pegawai" value="<?= $pegawai ?? '' ?>">

                            </div>
                        </div>

                        <div class="py-2 mb-2 mx-6 flex justify-end items-center">
                            <!-- Buttons -->
                            <div class="mt-6 flex justify-end gap-x-3">
                                <a href="javascript:history.back()" class="py-2 px-3 inline-flex justify-center items-center gap-2 rounded-lg border font-medium bg-white text-gray-700 shadow-sm align-middle hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-white focus:ring-teal-600 transition-all text-sm dark:bg-neutral-800 dark:hover:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-400 dark:hover:text-white dark:focus:ring-offset-gray-800">
                                    Batal
                                </a>

                                <div class="text-center">
                                    <button id="ajukan-button" type="button" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-[#0A2D27] text-[#ACF2E7] hover:bg-teal-700 disabled:opacity-50 disabled:pointer-events-none " disabled data-hs-overlay="#hs-cuti-alert">
                                        Ajukan
                                    </button>
                                </div>

                                <div id="hs-cuti-alert" class="hs-overlay hidden size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto">
                                    <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto">
                                        <div class="relative flex flex-col bg-white shadow-lg rounded-xl dark:bg-neutral-900">
                                            <div class="absolute top-2 end-2">
                                                <button type="button" class="flex justify-center items-center size-7 text-sm font-semibold rounded-lg border border-transparent text-gray-800 hover:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:border-transparent dark:hover:bg-neutral-700" data-hs-overlay="#hs-cuti-alert">
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
                                                    Ajukan Cuti
                                                </h3>
                                                <p class="text-gray-500 dark:text-neutral-500">
                                                    Apakah Anda Yakin untuk mengajukan izin cuti?
                                                </p>

                                                <div class="mt-6 flex justify-center gap-x-4">

                                                    <button type="button" data-hs-overlay="#hs-cuti-alert" href="javascript:history.back()" class="py-2 px-3 inline-flex justify-center items-center gap-2 rounded-lg border font-medium bg-white text-gray-700 shadow-sm align-middle hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-white focus:ring-teal-600 transition-all text-sm dark:bg-neutral-800 dark:hover:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-400 dark:hover:text-white dark:focus:ring-offset-gray-800">
                                                        Batal
                                                    </button>
                                                    <button type="submit" id="submitButton" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-[#0A2D27] text-[#ACF2E7] hover:bg-teal-700 disabled:opacity-50 disabled:pointer-events-none ">
                                                        Setuju
                                                    </button>

                                                </div>


                                            </div>
                                        </div>
                                    </div>
                                </div>
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
        // Get the current date in GMT+7 timezone
        var currentDate = new Date();
        var currentDateGMT7 = new Date(currentDate.toLocaleString('en-US', { timeZone: 'Asia/Bangkok' }));

        // Format the date to YYYY-MM-DD
        var year = currentDateGMT7.getFullYear();
        var month = ("0" + (currentDateGMT7.getMonth() + 1)).slice(-2);
        var day = ("0" + currentDateGMT7.getDate()).slice(-2);
        var minDate = `${year}-${month}-${day}`;

        var ajukanButton = document.getElementById('ajukan-button');
        var tanggalMulaiFlatpickr = flatpickr('#selected-date-mulai', {
            altInput: true,
            altFormat: 'Y-m-d',
            dateFormat: 'Y-m-d',
            minDate: minDate,  // Set the minimum date to today in GMT+7 timezone
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

    function validateForm() {
        var submitButton = document.getElementById('submitButton');
        submitButton.setAttribute('disabled', true);
        submitButton.innerHTML = 'Mengajukan...';
    }
</script>

    <?= $this->endSection(); ?>