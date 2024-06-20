<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>




<!-- Table Section -->
<div class="overflow overflow-auto px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto">
    <!-- Card -->
    <div class="flex flex-col">
        <div class="-m-3.5 overflow-y-auto">
            <div class="p-1.5 w-full inline-block align-middle">
                <div class="border border-gray-200 rounded-xl shadow-sm overflow-hidden dark:bg-neutral-900 dark:border-neutral-700">
                    <!-- Header -->
                    <div class="px-6 grid gap-3 md:flex md:justify-between md:items-center border-b border-gray-200 dark:border-neutral-700">
                        <div class="border-b border-gray-200 dark:border-neutral-700">
                            <nav class="flex space-x-1" aria-label="Tabs" role="tablist">
                                <button type="button" class="hs-tab-active:font-semibold hs-tab-active:border-gray-800 hs-tab-active:text-gray-800 py-4 px-1 inline-flex items-center gap-x-2 border-b-2 border-transparent text-sm whitespace-nowrap text-gray-500 hover:text-gray-600 focus:outline-none focus:text-gray-600 disabled:opacity-50 disabled:pointer-events-none dark:text-neutral-400 dark:hover:text-gray-500 active" id="tabs-with-icons-item-1" data-hs-tab="#tabs-with-icons-1" aria-controls="tabs-with-icons-1" role="tab">
                                    <svg class="flex-shrink-0 size-4" fill="#00000" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                        <g id="SVGRepo_iconCarrier">

                                            <path d="M9.6,3.32a3.86,3.86,0,1,0,3.86,3.85A3.85,3.85,0,0,0,9.6,3.32M16.35,11a.26.26,0,0,0-.25.21l-.18,1.27a4.63,4.63,0,0,0-.82.45l-1.2-.48a.3.3,0,0,0-.3.13l-1,1.66a.24.24,0,0,0,.06.31l1,.79a3.94,3.94,0,0,0,0,1l-1,.79a.23.23,0,0,0-.06.3l1,1.67c.06.13.19.13.3.13l1.2-.49a3.85,3.85,0,0,0,.82.46l.18,1.27a.24.24,0,0,0,.25.2h1.93a.24.24,0,0,0,.23-.2l.18-1.27a5,5,0,0,0,.81-.46l1.19.49c.12,0,.25,0,.32-.13l1-1.67a.23.23,0,0,0-.06-.3l-1-.79a4,4,0,0,0,0-.49,2.67,2.67,0,0,0,0-.48l1-.79a.25.25,0,0,0,.06-.31l-1-1.66c-.06-.13-.19-.13-.31-.13L19.5,13a4.07,4.07,0,0,0-.82-.45l-.18-1.27a.23.23,0,0,0-.22-.21H16.46M9.71,13C5.45,13,2,14.7,2,16.83v1.92h9.33a6.65,6.65,0,0,1,0-5.69A13.56,13.56,0,0,0,9.71,13m7.6,1.43a1.45,1.45,0,1,1,0,2.89,1.45,1.45,0,0,1,0-2.89Z"></path>
                                        </g>
                                    </svg>
                                    Daftar Pegawai
                                </button>
                                <button type="button" class="hs-tab-active:font-semibold hs-tab-active:border-gray-800 hs-tab-active:text-gray-800 py-4 px-1 inline-flex items-center gap-x-2 border-b-2 border-transparent text-sm whitespace-nowrap text-gray-500 hover:text-gray-600 focus:outline-none focus:text-gray-600 disabled:opacity-50 disabled:pointer-events-none dark:text-neutral-400 dark:hover:text-gray-500" id="tabs-with-icons-item-2" data-hs-tab="#tabs-with-icons-2" aria-controls="tabs-with-icons-2" role="tab">
                                    <svg class="flex-shrink-0 size-4" fill="#00000" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <g id="SVGRepo_iconCarrier">
                                            <path d="M2,21H8a1,1,0,0,0,0-2H3.071A7.011,7.011,0,0,1,10,13a5.044,5.044,0,1,0-3.377-1.337A9.01,9.01,0,0,0,1,20,1,1,0,0,0,2,21ZM10,5A3,3,0,1,1,7,8,3,3,0,0,1,10,5ZM20.207,9.293a1,1,0,0,0-1.414,0l-6.25,6.25a1.011,1.011,0,0,0-.241.391l-1.25,3.75A1,1,0,0,0,12,21a1.014,1.014,0,0,0,.316-.051l3.75-1.25a1,1,0,0,0,.391-.242l6.25-6.25a1,1,0,0,0,0-1.414Zm-5,8.583-1.629.543.543-1.629L19.5,11.414,20.586,12.5Z"></path>
                                        </g>
                                    </svg>
                                    Ketersediaan Pegawai
                                </button>
                            </nav>
                        </div>

                    </div>
                    <!-- End Header -->





                    <div id="tabs-with-icons-1" role="tabpanel" aria-labelledby="tabs-with-icons-item-1">

                        <!-- Table -->
                        <div class="overflow-x-auto">
                            <table id="myTable" class="min-w-full divide-y divide-gray-50 dark:divid e-neutral-700 text-xs">
                                <thead class="bg-gray-50 divide-y divide-gray-200 dark:bg-neutral-800 dark:divide-neutral-700">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-start border-s border-gray-200 dark:border-neutral-700">
                                            <span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200">
                                                Nama
                                            </span>
                                        </th>

                                        <th scope="col" class="px-6 py-3 text-start">
                                            <span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200">
                                                NIP
                                            </span>
                                        </th>

                                        <th scope="col" class="px-6 py-3 text-start">
                                            <span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200">
                                                Jenis Kelamin
                                            </span>
                                        </th>

                                        <th scope="col" class="px-6 py-3 text-start">
                                            <span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200">
                                                Jabatan
                                            </span>
                                        </th>

                                        <th scope="col" class="px-6 py-3 text-start">
                                            <span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200">
                                                Departemen
                                            </span>
                                        </th>

                                        <th scope="col" class="px-6 py-3 text-start">
                                            <span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200">
                                                Status
                                            </span>
                                        </th>

                                        <th scope="col" class="px-6 py-3 text-start">
                                            <span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200">
                                                Jenis Pegawai
                                            </span>
                                        </th>

                                        <th scope="col" class="px-6 py-3 text-start">
                                            <span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200">
                                                No. Telepon
                                            </span>
                                        </th>

                                        <th scope="col" class="px-6 py-3 text-start">
                                            <span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200">
                                                Awal Masuk
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
                                    <?php foreach ($pegawai_data as $pegawaiEntry) : ?>
                                        <tr>
                                            <td class="h-px w-auto whitespace-nowrap">
                                                <div class="px-6 py-2 flex items-center gap-x-3">                 
                                                        <span class="font-semibold hover:underline"><?= $pegawaiEntry['nama'] ?? 'N/A' ?></span>
                                                </div>
                                            </td>
                                            <td class="h-px w-auto whitespace-nowrap">
                                                <div class="px-6 py-2">
                                                    <span class="font-semibold text-gray-800 dark:text-neutral-200"><?= $pegawaiEntry['nip'] ?? 'N/A' ?></span>
                                                </div>
                                            </td>
                                            <td class="h-px w-auto whitespace-nowrap">
                                                <div class="px-6 py-2">
                                                    <span class="font-semibold text-gray-800 dark:text-neutral-200"><?= $pegawaiEntry['jenis_kelamin'] ?? 'N/A' ?></span>
                                                </div>
                                            </td>
                                            <td class="h-px w-auto whitespace-nowrap">
                                                <div class="px-6 py-2">
                                                    <span class="font-semibold text-gray-800 dark:text-neutral-200"><?= $pegawaiEntry['jabatan'] ?? 'N/A' ?></span>
                                                </div>
                                            </td>
                                            <td class="h-px w-auto whitespace-nowrap">
                                                <div class="px-6 py-2">
                                                    <span class="font-semibold text-gray-800 dark:text-neutral-200"><?= $pegawaiEntry['departemen'] ?? 'N/A' ?></span>
                                                </div>
                                            </td>
                                            <td class="h-px w-auto whitespace-nowrap">
                                                <div class="px-6 py-2">
                                                    <span class="font-semibold text-gray-800 dark:text-neutral-200"><?= $pegawaiEntry['status_aktif'] ?? 'N/A' ?></span>
                                                </div>
                                            </td>
                                            <td class="h-px w-auto whitespace-nowrap">
                                                <div class="px-6 py-2">
                                                    <span class="font-semibold text-gray-800 dark:text-neutral-200"><?= $pegawaiEntry['jenis_pegawai'] ?? 'N/A' ?></span>
                                                </div>
                                            </td>
                                            <td class="h-px w-auto whitespace-nowrap">
                                                <div class="px-6 py-2">
                                                    <span class="font-semibold text-gray-800 dark:text-neutral-200"><?= $pegawaiEntry['telepon'] ?? 'N/A' ?></span>
                                                </div>
                                            </td>
                                            <td class="h-px w-auto whitespace-nowrap">
                                                <div class="px-6 py-2">
                                                    <span class="font-semibold text-gray-800 dark:text-neutral-200"><?= $pegawaiEntry['tanggal_masuk'] ?? 'N/A' ?></span>
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
    </div>
    <!-- End Card -->
</div>
<!-- End Table Section -->

<?= $this->endSection(); ?>