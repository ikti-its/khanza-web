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

        <form action="/submiteditalamat/<?= $pegawaiId ?>" method="post">
            <!-- Grid -->


            <div class="sm:col-span-3">
                <label for="af-account-id-akun" class="inline-block text-sm text-gray-800 mt-2.5 dark:text-gray-200">
                    ID Akun
                </label>
            </div>

            <!-- End Col -->

            <div class="sm:col-span-9">
                <input id="af-id-akun" name="id_akun" type="text" class="py-2 px-3 pe-11 block w-full border-gray-200 shadow-sm text-sm rounded-lg focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600" placeholder="36 characters uuid" value="<?= $userData['id_akun'] ?? '' ?>">
            </div>
            <!-- End Col -->

            <div class="sm:col-span-3">
                <label for="af-pegawai-alamat" class="inline-block text-sm text-gray-800 mt-2.5 dark:text-gray-200">
                    Alamat
                </label>
            </div>
            <!-- End Col -->

            <div class="sm:col-span-9">
                <div class="space-y-2">
                    <input id="af-pegawai-alamat" name="alamat" type="text" class="py-2 px-3 pe-11 block w-full border-gray-200 shadow-sm rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600" placeholder="Enter your alamat" value="<?= $userData['alamat'] ?? '' ?>">
                </div>
            </div>
            <!-- End Col -->

            <div class="sm:col-span-3">
                <label for="af-pegawai-alamat-lat" class="inline-block text-sm text-gray-800 mt-2.5 dark:text-gray-200">
                    Alamat_lat
                </label>
            </div>
            <!-- End Col -->

            <div class="sm:col-span-9">
                <div class="space-y-2">
                    <input id="af-pegawai-alamat-lat" name="alamat_lat" type="text" class="py-2 px-3 pe-11 block w-full border-gray-200 shadow-sm rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600" placeholder="Enter your alamat_lat" value="<?= $userData['alamat_lat'] ?? '' ?>">
                </div>
            </div>
            <!-- End Col -->

            <div class="sm:col-span-3">
                <label for="af-pegawai-alamat-lon" class="inline-block text-sm text-gray-800 mt-2.5 dark:text-gray-200">
                    Alamat_lon
                </label>
            </div>
            <!-- End Col -->

            <div class="sm:col-span-9">
                <div class="space-y-2">
                    <input id="af-pegawai-alamat-lon" name="alamat_lon" type="text" class="py-2 px-3 pe-11 block w-full border-gray-200 shadow-sm rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600" placeholder="Enter your alamat_lon" value="<?= $userData['alamat_lon'] ?? '' ?>">
                </div>
            </div>
            <!-- End Col -->


            <div class="sm:col-span-3">
                <label for="af-pegawai-kota" class="inline-block text-sm text-gray-800 mt-2.5 dark:text-gray-200">
                    Kota
                </label>
            </div>
            <!-- End Col -->

            <div class="sm:col-span-9">
                <div class="space-y-2">
                    <input id="af-pegawai-kota" name="kota" type="text" class="py-2 px-3 pe-11 block w-full border-gray-200 shadow-sm rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600" placeholder="Enter your kota" value="<?= $userData['kota'] ?? '' ?>">
                </div>
            </div>
            <!-- End Col -->

            <div class="sm:col-span-3">
                <label for="af-pegawai-kode-pos" class="inline-block text-sm text-gray-800 mt-2.5 dark:text-gray-200">
                    Kode pos
                </label>
            </div>
            <!-- End Col -->

            <div class="sm:col-span-9">
                <div class="space-y-2">
                    <input id="af-pegawai-kode-pos" name="kode_pos" type="text" class="py-2 px-3 pe-11 block w-full border-gray-200 shadow-sm rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600" placeholder="Enter your kode pos" value="<?= $userData['kode_pos'] ?? '' ?>">
                </div>
            </div>
            <!-- End Col -->
    

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