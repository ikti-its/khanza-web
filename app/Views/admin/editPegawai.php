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

        <form action="/submiteditpegawai/<?= $pegawaiId ?>" method="post">
            <!-- Grid -->


            <div class="sm:col-span-3">
                <label for="af-account-email" class="inline-block text-sm text-gray-800 mt-2.5 dark:text-gray-200">
                    ID Akun
                </label>
            </div>

            <!-- End Col -->

            <div class="sm:col-span-9">
                <input id="af-id-akun" name="id_akun" type="text" class="py-2 px-3 pe-11 block w-full border-gray-200 shadow-sm text-sm rounded-lg focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600" placeholder="36 characters uuid" value="<?= $userData['id_akun'] ?? '' ?>">
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
                    <input id="af-pegawai-nip" name="nip" type="text" class="py-2 px-3 pe-11 block w-full border-gray-200 shadow-sm rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600" placeholder="Enter your nip" value="<?= $userData['nip'] ?? '' ?>">
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
                    <input id="af-pegawai-nama" name="nama" type="text" class="py-2 px-3 pe-11 block w-full border-gray-200 shadow-sm rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600" placeholder="Enter your name" value="<?= $userData['nama'] ?? '' ?>">
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
                        <option value="L" <?= ($userData['jenis_kelamin'] ?? '') === 'L' ? 'selected' : '' ?>>(L) Laki-laki</option>
                        <option value="P" <?= ($userData['jenis_kelamin'] ?? '') === 'P' ? 'selected' : '' ?>>(P) Perempuan</option>
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
                        <option value="1000" <?= ($userData['jabatan']) === '1000' ? 'selected' : '' ?>>1000 Testing</option>
                        <option value="1" <?= ($userData['jabatan']) === '1' ? 'selected' : '' ?>>1 Direktur</option>
                        <option value="2" <?= ($userData['jabatan']) === '2' ? 'selected' : '' ?>>2 Manager</option>
                        <option value="3" <?= ($userData['jabatan']) === '3' ? 'selected' : '' ?>>3 Supervisor</option>
                        <option value="4" <?= ($userData['jabatan']) === '4' ? 'selected' : '' ?>>4 Staff</option>
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
                        <option value="1000" <?= ($userData['departemen']) === '1000' ? 'selected' : '' ?>>1000 Testing</option>
                        <option value="1" <?= ($userData['departemen']) === '1' ? 'selected' : '' ?>>1 HRD</option>
                        <option value="2" <?= ($userData['departemen']) === '2' ? 'selected' : '' ?>>2 Marketing</option>
                        <option value="3" <?= ($userData['departemen']) === '3' ? 'selected' : '' ?>>3 Keuangan</option>
                        <option value="4" <?= ($userData['departemen']) === '4' ? 'selected' : '' ?>>4 Operasional</option>
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
                        <option value="A" <?= ($userData['status_aktif']) === 'A' ? 'selected' : '' ?>>(A) Aktif</option>
                        <option value="BH" <?= ($userData['status_aktif']) === 'BH' ? 'selected' : '' ?>>(BH) Berhenti dengan Hormat</option>
                        <option value="C" <?= ($userData['status_aktif']) === 'C' ? 'selected' : '' ?>>(C) Cuti</option>
                        <option value="R" <?= ($userData['status_aktif']) === 'R' ? 'selected' : '' ?>>(R) Resign</option>
                        <option value="BT" <?= ($userData['status_aktif']) === 'BT' ? 'selected' : '' ?>>(BH) Berhenti dengan Tidak Hormat</option>
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
                        <option value="Tetap" <?= ($userData['jenis_pegawai']) === 'Tetap' ? 'selected' : '' ?>>Tetap</option>
                        <option value="Kontrak" <?= ($userData['jenis_pegawai']) === 'Kontrak' ? 'selected' : '' ?>>Kontrak</option>
                        <option value="Magang" <?= ($userData['jenis_pegawai']) === 'Magang' ? 'selected' : '' ?>>Magang</option>
                        <option value="Istimewa" <?= ($userData['jenis_pegawai']) === 'Istimewa' ? 'selected' : '' ?>>Istimewa</option>
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
                    <input id="af-pegawai-departemen" name="telepon" type="text" class="py-2 px-3 pe-11 block w-full border-gray-200 shadow-sm rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600" placeholder="Enter your telepon" value="<?= $userData['telepon'] ?? '' ?>">
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
                    <input id="selected-date" type="text" class="py-2 px-3 pe-11 block w-full border-gray-200 shadow-sm rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600" placeholder="Selected Date" readonly value="<?= $userData['tanggal_masuk'] ?>">
                    <!-- Hidden input field to store the original date value -->
                    <input id="original-tanggal-masuk" type="hidden" value="<?= $userData['tanggal_masuk'] ?>">
                    <!-- Hidden input field to store the updated date value -->
                    <input id="tanggal-masuk" name="tanggal_masuk" type="hidden" value="<?= $userData['tanggal_masuk'] ?>">
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

                        onChange: function(selectedDates, dateStr, instance) {
                            // Get the original date value
                            var originalDate = document.getElementById('original-tanggal-masuk').value;
                            // Check if the selected date is different from the original date
                            if (dateStr !== originalDate) {
                                // Update the hidden input field with the selected date value
                                document.getElementById('tanggal-masuk').value = dateStr;
                            } else {
                                // Keep the original date value in the hidden input field
                                document.getElementById('tanggal-masuk').value = originalDate;
                            }
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