<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>




<!-- Table Section -->
<div class="overflow overflow-auto px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto">
    <!-- Card -->
    <div class="flex flex-col">
        <div class="-m-3.5 overflow-x-auto">
            <div class="p-1.5 w-full inline-block align-middle">
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden dark:bg-neutral-900 dark:border-neutral-700">
                    <!-- Header -->
                    <div class="px-6 grid gap-3 md:flex md:justify-between md:items-center border-b border-gray-200 dark:border-neutral-700">
                        <div class="border-b border-gray-200 dark:border-neutral-700">
                            <nav class="flex space-x-1" aria-label="Tabs" role="tablist">
                                <button type="button" class="hs-tab-active:font-semibold hs-tab-active:border-gray-800 hs-tab-active:text-gray-800 py-4 px-1 inline-flex items-center gap-x-2 border-b-2 border-transparent text-sm whitespace-nowrap text-gray-500 hover:text-gray-600 focus:outline-none focus:text-gray-600 disabled:opacity-50 disabled:pointer-events-none dark:text-neutral-400 dark:hover:text-gray-500 active" id="tabs-with-icons-item-1" data-hs-tab="#tabs-with-icons-1" aria-controls="tabs-with-icons-1" role="tab">
                                    <svg class="flex-shrink-0 size-4" fill="#00000" viewBox="0 0 36 36" xmlns="http://www.w3.org/2000/svg">
                                        <path class="clr-i-solid clr-i-solid-path-1" d="M12,16.14q-.43,0-.87,0a8.67,8.67,0,0,0-6.43,2.52l-.24.28v8.28H8.54v-4.7l.55-.62.25-.29a11,11,0,0,1,4.71-2.86A6.59,6.59,0,0,1,12,16.14Z"></path>
                                        <path class="clr-i-solid clr-i-solid-path-2" d="M31.34,18.63a8.67,8.67,0,0,0-6.43-2.52,10.47,10.47,0,0,0-1.09.06,6.59,6.59,0,0,1-2,2.45,10.91,10.91,0,0,1,5,3l.25.28.54.62v4.71h3.94V18.91Z"></path>
                                        <path class="clr-i-solid clr-i-solid-path-3" d="M11.1,14.19c.11,0,.2,0,.31,0a6.45,6.45,0,0,1,3.11-6.29,4.09,4.09,0,1,0-3.42,6.33Z"></path>
                                        <path class="clr-i-solid clr-i-solid-path-4" d="M24.43,13.44a6.54,6.54,0,0,1,0,.69,4.09,4.09,0,0,0,.58.05h.19A4.09,4.09,0,1,0,21.47,8,6.53,6.53,0,0,1,24.43,13.44Z"></path>
                                        <circle class="clr-i-solid clr-i-solid-path-5" cx="17.87" cy="13.45" r="4.47"></circle>
                                        <path class="clr-i-solid clr-i-solid-path-6" d="M18.11,20.3A9.69,9.69,0,0,0,11,23l-.25.28v6.33a1.57,1.57,0,0,0,1.6,1.54H23.84a1.57,1.57,0,0,0,1.6-1.54V23.3L25.2,23A9.58,9.58,0,0,0,18.11,20.3Z"></path>
                                        <rect x="0" y="0" width="36" height="36" fill-opacity="0" />
                                    </svg>
                                    Data Pegawai
                                </button>
                                <button type="button" class="hs-tab-active:font-semibold hs-tab-active:border-gray-800 hs-tab-active:text-gray-800 py-4 px-1 inline-flex items-center gap-x-2 border-b-2 border-transparent text-sm whitespace-nowrap text-gray-500 hover:text-gray-600 focus:outline-none focus:text-gray-600 disabled:opacity-50 disabled:pointer-events-none dark:text-neutral-400 dark:hover:text-gray-500" id="tabs-with-icons-item-2" data-hs-tab="#tabs-with-icons-2" aria-controls="tabs-with-icons-2" role="tab">
                                    <svg class="flex-shrink-0 size-4" fill="#00000" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M20,8.94a1.31,1.31,0,0,0-.06-.27l0-.09a1.07,1.07,0,0,0-.19-.28h0l-6-6h0a1.07,1.07,0,0,0-.28-.19l-.09,0L13.06,2H7A3,3,0,0,0,4,5V19a3,3,0,0,0,3,3H17a3,3,0,0,0,3-3V9S20,9,20,8.94ZM14,5.41,16.59,8H14ZM18,19a1,1,0,0,1-1,1H7a1,1,0,0,1-1-1V5A1,1,0,0,1,7,4h5V9a1,1,0,0,0,1,1h5Z" />
                                    </svg>
                                    Berkas Pegawai
                                </button>

                            </nav>

                        </div>
                        <!-- <button class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-[#0A2D27] text-[#ACF2E7] shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-800">
                            <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20.9888 4.28491L19.6405 2.93089C18.4045 1.6897 16.4944 1.6897 15.2584 2.93089L13.0112 5.30042L18.7416 11.055L21.1011 8.68547C21.6629 8.1213 22 7.33145 22 6.54161C22 5.75176 21.5506 4.84908 20.9888 4.28491Z" fill="#030D45" />
                                <path d="M16.2697 10.9422L11.7753 6.42877L2.89888 15.3427C2.33708 15.9069 2 16.6968 2 17.5994V21.0973C2 21.5487 2.33708 22 2.89888 22H6.49438C7.2809 22 8.06742 21.6615 8.74157 21.0973L17.618 12.1834L16.2697 10.9422Z" fill="#030D45" />
                            </svg>
                            Ubah Data Pegawai
                        </button> -->


                    </div>
                    <!-- End Header -->





                    <div id="tabs-with-icons-1" role="tabpanel" aria-labelledby="tabs-with-icons-item-1">


                        <div class="px-6 py-6 grid gap-3 md:flex md:justify-between md:items-center border-b border-gray-200 dark:border-neutral-700">

                            <div class="grid sm:grid-cols-12 gap-2 sm:gap-6">

                                <div class="sm:col-span-3">
                                    <label for="af-account-email" class="inline-block text-sm text-gray-800 mt-2.5 dark:text-neutral-200">
                                        NIP
                                    </label>
                                </div>
                                <!-- End Col -->

                                <div class="sm:col-span-9">
                                    <input id="af-account-email" type="email" class="bg-[#F6F6F6] py-2 px-3 pe-11 block w-full border-gray-200 shadow-sm text-sm rounded-lg focus:outline-teal-500 focus:ring-teal-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600" placeholder="maria@site.com" value="<?= $userData['nip'] ?? '' ?>" readonly>
                                </div>
                                <!-- End Col -->

                                <div class="sm:col-span-3">
                                    <label for="af-account-role" class="inline-block text-sm text-gray-800 mt-2.5 dark:text-neutral-200">
                                        Nama
                                    </label>
                                </div>
                                <!-- End Col -->

                                <div class="sm:col-span-9">
                                    <input id="af-account-role" type="text" class="bg-[#F6F6F6] py-2 px-3 pe-11 block w-full border-gray-200 shadow-sm text-sm rounded-lg focus:outline-teal-500 focus:ring-teal-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600" placeholder="Your role" value="<?= $userData['nama'] ?? '' ?>" readonly>
                                </div>
                                <!-- End Col -->

                                <div class="sm:col-span-3">
                                    <label for="af-account-alamat" class="inline-block text-sm text-gray-800 mt-2.5 dark:text-neutral-200">
                                        Jenis Kelamin
                                    </label>
                                </div>
                                <!-- End Col -->

                                <div class="sm:col-span-9">
                                    <input id="af-account-alamat" type="text" class="bg-[#F6F6F6] py-2 px-3 pe-11 block w-full border-gray-200 shadow-sm text-sm rounded-lg focus:outline-teal-500 focus:ring-teal-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600" placeholder="Your alamat" value="<?= $userData['jenis_kelamin'] ?? '' ?>" readonly>
                                </div>
                                <!-- End Col -->

                                <div class="sm:col-span-3">
                                    <label for="af-account-alamat" class="inline-block text-sm text-gray-800 mt-2.5 dark:text-neutral-200">
                                        Jabatan
                                    </label>
                                </div>
                                <!-- End Col -->

                                <div class="sm:col-span-9">
                                    <input id="af-account-alamat" type="text" class="bg-[#F6F6F6] py-2 px-3 pe-11 block w-full border-gray-200 shadow-sm text-sm rounded-lg focus:outline-teal-500 focus:ring-teal-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600" placeholder="Your alamat" value="<?= $userData['jabatan'] ?? '' ?>" readonly>
                                </div>
                                <!-- End Col -->

                                <div class="sm:col-span-3">
                                    <label for="af-account-alamat" class="inline-block text-sm text-gray-800 mt-2.5 dark:text-neutral-200">
                                        Departemen
                                    </label>
                                </div>
                                <!-- End Col -->

                                <div class="sm:col-span-9">
                                    <input id="af-account-alamat" type="text" class="bg-[#F6F6F6] py-2 px-3 pe-11 block w-full border-gray-200 shadow-sm text-sm rounded-lg focus:outline-teal-500 focus:ring-teal-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600" placeholder="Your alamat" value="<?= $userData['departemen'] ?? '' ?>" readonly>
                                </div>
                                <!-- End Col -->

                                <div class="sm:col-span-3">
                                    <label for="af-account-alamat" class="inline-block text-sm text-gray-800 mt-2.5 dark:text-neutral-200">
                                        Status
                                    </label>
                                </div>
                                <!-- End Col -->

                                <div class="sm:col-span-9">
                                    <input id="af-account-alamat" type="text" class="bg-[#F6F6F6] py-2 px-3 pe-11 block w-full border-gray-200 shadow-sm text-sm rounded-lg focus:outline-teal-500 focus:ring-teal-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600" placeholder="Your alamat" value="<?= $userData['status_aktif'] ?? '' ?>" readonly>
                                </div>
                                <!-- End Col -->

                                <div class="sm:col-span-3">
                                    <label for="af-account-alamat" class="inline-block text-sm text-gray-800 mt-2.5 dark:text-neutral-200">
                                        Jenis Pegawai
                                    </label>
                                </div>
                                <!-- End Col -->

                                <div class="sm:col-span-9">
                                    <input id="af-account-alamat" type="text" class="bg-[#F6F6F6] py-2 px-3 pe-11 block w-full border-gray-200 shadow-sm text-sm rounded-lg focus:outline-teal-500 focus:ring-teal-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600" placeholder="Your alamat" value="<?= $userData['jenis_pegawai'] ?? '' ?>" readonly>
                                </div>
                                <!-- End Col -->

                                <div class="sm:col-span-3">
                                    <label for="af-account-alamat" class="inline-block text-sm text-gray-800 mt-2.5 dark:text-neutral-200">
                                        Nomor Telepon
                                    </label>
                                </div>
                                <!-- End Col -->

                                <div class="sm:col-span-9">
                                    <input id="af-account-alamat" type="text" class="bg-[#F6F6F6] py-2 px-3 pe-11 block w-full border-gray-200 shadow-sm text-sm rounded-lg focus:outline-teal-500 focus:ring-teal-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600" placeholder="Your alamat" value="<?= $userData['telepon'] ?? '' ?>" readonly>
                                </div>
                                <!-- End Col -->

                                <div class="sm:col-span-3">
                                    <label for="af-account-alamat" class="inline-block text-sm text-gray-800 mt-2.5 dark:text-neutral-200">
                                        Awal Masuk
                                    </label>
                                </div>
                                <!-- End Col -->

                                <div class="sm:col-span-9">
                                    <input id="af-account-alamat" type="text" class="bg-[#F6F6F6] py-2 px-3 pe-11 block w-full border-gray-200 shadow-sm text-sm rounded-lg focus:outline-teal-500 focus:ring-teal-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600" placeholder="Your alamat" value="<?= $userData['tanggal_masuk'] ?? '' ?>" readonly>
                                </div>
                                <!-- End Col -->



                            </div>


                        </div>



                    </div>

                    <div id="tabs-with-icons-2" class="hidden" role="tabpanel" aria-labelledby="tabs-with-icons-item-2">
                        <div class="px-6 py-6 grid gap-3 md:flex md:justify-between md:items-center border-b border-gray-200 dark:border-neutral-700">

                            <div class="grid sm:grid-cols-12 gap-2 sm:gap-6">

                                <div class="sm:col-span-3">
                                    <label for="af-account-email" class="inline-block text-sm text-gray-800 mt-2.5 dark:text-neutral-200">
                                        NIK
                                    </label>
                                </div>
                                <!-- End Col -->

                                <div class="sm:col-span-9">
                                    <input id="af-account-email" type="email" class="bg-[#F6F6F6] py-2 px-3 pe-11 block w-full border-gray-200 shadow-sm text-sm rounded-lg focus:outline-teal-500 focus:ring-teal-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600" placeholder="maria@site.com" value="<?= $berkasData['nik'] ?? '' ?>" readonly>
                                </div>
                                <!-- End Col -->

                                <div class="sm:col-span-3">
                                    <label for="af-account-role" class="inline-block text-sm text-gray-800 mt-2.5 dark:text-neutral-200">
                                        Tempat Lahir
                                    </label>
                                </div>
                                <!-- End Col -->

                                <div class="sm:col-span-9">
                                    <input id="af-account-role" type="text" class="bg-[#F6F6F6] py-2 px-3 pe-11 block w-full border-gray-200 shadow-sm text-sm rounded-lg focus:outline-teal-500 focus:ring-teal-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600" placeholder="Your role" value="<?= $berkasData['tempat_lahir'] ?? '' ?>" readonly>
                                </div>
                                <!-- End Col -->

                                <div class="sm:col-span-3">
                                    <label for="af-account-alamat" class="inline-block text-sm text-gray-800 mt-2.5 dark:text-neutral-200">
                                        Tanggal Lahir
                                    </label>
                                </div>
                                <!-- End Col -->

                                <div class="sm:col-span-9">
                                    <input id="af-account-alamat" type="text" class="bg-[#F6F6F6] py-2 px-3 pe-11 block w-full border-gray-200 shadow-sm text-sm rounded-lg focus:outline-teal-500 focus:ring-teal-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600" placeholder="Your alamat" value="<?= $berkasData['tanggal_lahir'] ?? '' ?>" readonly>
                                </div>
                                <!-- End Col -->

                                <div class="sm:col-span-3">
                                    <label for="af-account-alamat" class="inline-block text-sm text-gray-800 mt-2.5 dark:text-neutral-200">
                                        Agama
                                    </label>
                                </div>
                                <!-- End Col -->

                                <div class="sm:col-span-9">
                                    <input id="af-account-alamat" type="text" class="bg-[#F6F6F6] py-2 px-3 pe-11 block w-full border-gray-200 shadow-sm text-sm rounded-lg focus:outline-teal-500 focus:ring-teal-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600" placeholder="Your alamat" value="<?= $berkasData['agama'] ?? '' ?>" readonly>
                                </div>
                                <!-- End Col -->

                                <div class="sm:col-span-3">
                                    <label for="af-account-alamat" class="inline-block text-sm text-gray-800 mt-2.5 dark:text-neutral-200">
                                        Pendidikan
                                    </label>
                                </div>
                                <!-- End Col -->

                                <div class="sm:col-span-9">
                                    <input id="af-account-alamat" type="text" class="bg-[#F6F6F6] py-2 px-3 pe-11 block w-full border-gray-200 shadow-sm text-sm rounded-lg focus:outline-teal-500 focus:ring-teal-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600" placeholder="Your alamat" value="<?= $berkasData['pendidikan'] ?? '' ?>" readonly>
                                </div>
                                <!-- End Col -->
                            
                             
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