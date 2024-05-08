<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<!-- Card Section -->
<div class="max-w-4xl px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto">
    <!-- Card -->
    <div class="bg-white rounded-xl shadow p-4 sm:p-7 dark:bg-slate-900">
        <div class="mb-8">
            <h2 class="text-xl font-bold text-gray-800 dark:text-gray-200">
                Profile
            </h2>
            <p class="text-sm text-gray-600 dark:text-gray-400">
                Manage your name, password and account settings.
            </p>
        </div>

        <form action="/submittambahpegawai" method="post">
            <!-- Grid -->


            <div class="sm:col-span-3">
                <label for="af-account-email" class="inline-block text-sm text-gray-800 mt-2.5 dark:text-gray-200">
                    ID Akun
                </label>
            </div>

            <!-- End Col -->

            <div class="sm:col-span-9">
                <input id="af-id-akun" name="id_akun" type="text" class="py-2 px-3 pe-11 block w-full border-gray-200 shadow-sm text-sm rounded-lg focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600" placeholder="36 characters uuid">
            </div>
            <!-- End Col -->

            <div class="sm:col-span-3">
                <label for="af-pegawai-nip" class="inline-block text-sm text-gray-800 mt-2.5 dark:text-gray-200">
                    NIP
                </label>
            </div>
            <!-- End Col -->

            <div class="sm:col-span-9">
                <div class="space-y-2">
                    <input id="af-pegawai-nip" name="nip" type="text" class="py-2 px-3 pe-11 block w-full border-gray-200 shadow-sm rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600" placeholder="Enter your nip">
                </div>
            </div>
            <!-- End Col -->

            <div class="sm:col-span-3">
                <label for="af-pegawai-nama" class="inline-block text-sm text-gray-800 mt-2.5 dark:text-gray-200">
                    Nama
                </label>
            </div>
            <!-- End Col -->

            <div class="sm:col-span-9">
                <div class="space-y-2">
                    <input id="af-pegawai-nama" name="nama" type="text" class="py-2 px-3 pe-11 block w-full border-gray-200 shadow-sm rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600" placeholder="Enter your name">
                </div>
            </div>
            <!-- End Col -->

            <div class="sm:col-span-3">
                <div class="inline-block">
                    <label for="af-pegawai-kelamin" class="inline-block text-sm text-gray-800 mt-2.5 dark:text-gray-200">
                        Jenis Kelamin
                    </label>
                </div>
            </div>
            <!-- End Col -->

            <div class="sm:col-span-9">
                <div class="sm:flex">
                    <select name="jenis_kelamin" class="py-2 px-3 pe-9 block w-full sm:w-auto border-gray-200 shadow-sm -mt-px -ms-px first:rounded-t-lg last:rounded-b-lg sm:first:rounded-s-lg sm:mt-0 sm:first:ms-0 sm:first:rounded-se-none sm:last:rounded-es-none sm:last:rounded-e-lg text-sm relative focus:z-10 focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600">
                        <option value="L" selected>(L) Laki-laki</option>
                        <option value="P">(P) Perempuan</option>
                    </select>
                </div>
            </div>
            <!-- End Col -->

            <div class="sm:col-span-3">
                <div class="inline-block">
                    <label for="af-pegawai-jabatan" class="inline-block text-sm text-gray-800 mt-2.5 dark:text-gray-200">
                        Jabatan
                    </label>
                </div>
            </div>
            <!-- End Col -->

            <div class="sm:col-span-9">
                <div class="sm:flex">
                    <select name="jabatan" class="py-2 px-3 pe-9 block w-full sm:w-auto border-gray-200 shadow-sm -mt-px -ms-px first:rounded-t-lg last:rounded-b-lg sm:first:rounded-s-lg sm:mt-0 sm:first:ms-0 sm:first:rounded-se-none sm:last:rounded-es-none sm:last:rounded-e-lg text-sm relative focus:z-10 focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600">
                        <option value="1000" selected>1000 Testing</option>
                        <option value="1">1 Direktur</option>
                        <option value="2">2 Manager</option>
                        <option value="3">3 Supervisor</option>
                        <option value="4">4 Staff</option>
                    </select>
                </div>
            </div>
            <!-- End Col -->

            <div class="sm:col-span-3">
                <div class="inline-block">
                    <label for="af-pegawai-departemen" class="inline-block text-sm text-gray-800 mt-2.5 dark:text-gray-200">
                        Departemen
                    </label>
                </div>
            </div>
            <!-- End Col -->

            <div class="sm:col-span-9">
                <div class="sm:flex">
                    <select name="departemen" class="py-2 px-3 pe-9 block w-full sm:w-auto border-gray-200 shadow-sm -mt-px -ms-px first:rounded-t-lg last:rounded-b-lg sm:first:rounded-s-lg sm:mt-0 sm:first:ms-0 sm:first:rounded-se-none sm:last:rounded-es-none sm:last:rounded-e-lg text-sm relative focus:z-10 focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600">
                        <option value="1000" selected>1000 Testing</option>
                        <option value="1">1 HRD</option>
                        <option value="2">2 Marketing</option>
                        <option value="3">3 Keuangan</option>
                        <option value="4">4 Operasional</option>
                    </select>
                </div>
            </div>
            <!-- End Col -->

            <div class="sm:col-span-3">
                <div class="inline-block">
                    <label for="af-pegawai-status" class="inline-block text-sm text-gray-800 mt-2.5 dark:text-gray-200">
                        Status Aktif
                    </label>
                </div>
            </div>
            <!-- End Col -->

            <div class="sm:col-span-9">
                <div class="sm:flex">
                    <select name="status_aktif" class="py-2 px-3 pe-9 block w-full sm:w-auto border-gray-200 shadow-sm -mt-px -ms-px first:rounded-t-lg last:rounded-b-lg sm:first:rounded-s-lg sm:mt-0 sm:first:ms-0 sm:first:rounded-se-none sm:last:rounded-es-none sm:last:rounded-e-lg text-sm relative focus:z-10 focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600">
                        <option value="A" selected>(A) Aktif</option>
                        <option value="BH">(BH) Berhenti dengan Hormat</option>
                        <option value="C">(C) Cuti</option>
                        <option value="R">(R) Resign</option>
                        <option value="BT">(BH) Berhenti dengan Tidak Hormat</option>
                    </select>
                </div>
            </div>
            <!-- End Col -->

            <div class="sm:col-span-3">
                <div class="inline-block">
                    <label for="af-pegawai-jenis" class="inline-block text-sm text-gray-800 mt-2.5 dark:text-gray-200">
                        Jenis Pegawai
                    </label>
                </div>
            </div>
            <!-- End Col -->

            <div class="sm:col-span-9">
                <div class="sm:flex">
                    <select name="jenis_pegawai" class="py-2 px-3 pe-9 block w-full sm:w-auto border-gray-200 shadow-sm -mt-px -ms-px first:rounded-t-lg last:rounded-b-lg sm:first:rounded-s-lg sm:mt-0 sm:first:ms-0 sm:first:rounded-se-none sm:last:rounded-es-none sm:last:rounded-e-lg text-sm relative focus:z-10 focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600">
                        <option value="Tetap" selected>Tetap</option>
                        <option value="Kontrak">Kontrak</option>
                        <option value="Magang">Magang</option>
                        <option value="Istimewa">Istimewa</option>
                    </select>
                </div>
            </div>
            <!-- End Col -->

            <div class="sm:col-span-3">
                <label for="af-pegawai-departemen" class="inline-block text-sm text-gray-800 mt-2.5 dark:text-gray-200">
                    Telepon
                </label>
            </div>
            <!-- End Col -->

            <div class="sm:col-span-9">
                <div class="space-y-2">
                    <input id="af-pegawai-departemen" name="telepon" type="text" class="py-2 px-3 pe-11 block w-full border-gray-200 shadow-sm rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600" placeholder="Enter your telepon">
                </div>
            </div>
            <!-- End Col -->

            <div class="sm:col-span-3">
                <label for="af-tanggal-masuk" class="inline-block text-sm text-gray-800 mt-2.5 dark:text-gray-200">
                    Tanggal Masuk
                </label>
            </div>
            <!-- End Col -->

            <div class="sm:col-span-9">
                <div class="space-y-2">
                    <!-- Input field to display the selected date -->
                    <input id="selected-date" type="text" class="py-2 px-3 pe-11 block w-full border-gray-200 shadow-sm rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600" placeholder="Selected Date" readonly>
                    <!-- Hidden input field to store the selected date value -->
                    <input id="tanggal-masuk" name="tanggal_masuk" type="hidden">
                </div>
            </div>
            <!-- End Col -->

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    // Initialize Flatpickr
                    flatpickr('#selected-date', {
                        altInput: true, // Enable to use an alternative input field
                        altFormat: 'Y-m-d', // Format of the alternative input field
                        dateFormat: 'Y-m-d', // Date format

                        onClose: function(selectedDates, dateStr, instance) {
                            // Update the hidden input field with the selected date value
                            document.getElementById('tanggal-masuk').value = dateStr;
                        }
                    });
                });
            </script>

    </div>
    <!-- End Grid -->

    <div class="mt-5 flex justify-end gap-x-2">
        <button type="button" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-white dark:hover:bg-gray-800 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
            Cancel
        </button>
        <button type="submit" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
            Save changes
        </button>
    </div>
    </form>
</div>
<!-- End Card -->
</div>
<!-- End Card Section -->

<?= $this->endSection(); ?>