<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<!-- Card Section -->
<div class="max-w-4xl px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto overflow-auto max-h-full">
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

        <form action="/submiteditberkas/<?= $pegawaiId ?>" method="post" enctype="multipart/form-data">
            <!-- Grid -->


            <div class="sm:col-span-3">
                <label for="af-pegawai-id" class="inline-block text-sm text-gray-800 mt-2.5 dark:text-gray-200">
                    ID Pegawai
                </label>
            </div>
            <!-- End Col -->

            <div class="sm:col-span-9">
                <div class="space-y-2">
                    <input id="af-pegawai-id" name="id_pegawai" type="text" class="py-2 px-3 pe-11 block w-full border-gray-200 shadow-sm rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600" placeholder="Enter your ID Pegawai" value="<?= $userData['id_pegawai'] ?? '' ?>">
                </div>
            </div>
            <!-- End Col -->

            <div class="sm:col-span-3">
                <label for="af-pegawai-nik" class="inline-block text-sm text-gray-800 mt-2.5 dark:text-gray-200">
                    NIK
                </label>
            </div>
            <!-- End Col -->

            <div class="sm:col-span-9">
                <div class="space-y-2">
                    <input id="af-pegawai-nik" name="nik" type="text" class="py-2 px-3 pe-11 block w-full border-gray-200 shadow-sm rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600" placeholder="Enter your NIK" value="<?= $userData['nik'] ?? '' ?>">
                </div>
            </div>
            <!-- End Col -->

            <div class="sm:col-span-3">
                <label for="af-pegawai-tempat-lahir" class="inline-block text-sm text-gray-800 mt-2.5 dark:text-gray-200">
                    Tempat Lahir
                </label>
            </div>
            <!-- End Col -->

            <div class="sm:col-span-9">
                <div class="space-y-2">
                    <input id="af-pegawai-tempat-lahir" name="tempat_lahir" type="text" class="py-2 px-3 pe-11 block w-full border-gray-200 shadow-sm rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600" placeholder="Enter your tempat lahir" value="<?= $userData['tempat_lahir'] ?? '' ?>">
                </div>
            </div>
            <!-- End Col -->

            <div class=" sm:col-span-3">
                <label for="af-tanggal-lahir" class="inline-block text-sm text-gray-800 mt-2.5 dark:text-gray-200">
                    Tanggal Lahir
                </label>
            </div>
            <!-- End Col -->

            <div class="sm:col-span-9">
                <div class="space-y-2">
                    <!-- Input field to display the selected date -->
                    <input id="selected-date" type="text" class="py-2 px-3 pe-11 block w-full border-gray-200 shadow-sm rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600" placeholder="Selected Date" readonly value="<?= $userData['tanggal_lahir'] ?>">
                    <!-- Hidden input field to store the original date value -->
                    <input id="original-tanggal-lahir" type="hidden" value="<?= $userData['tanggal_lahir'] ?>">
                    <!-- Hidden input field to store the updated date value -->
                    <input id="tanggal-lahir" name="tanggal_lahir" type="hidden" value="<?= $userData['tanggal_lahir'] ?>">
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
                            var originalDate = document.getElementById('original-tanggal-lahir').value;
                            // Check if the selected date is different from the original date
                            if (dateStr !== originalDate) {
                                // Update the hidden input field with the selected date value
                                document.getElementById('tanggal-lahir').value = dateStr;
                            } else {
                                // Keep the original date value in the hidden input field
                                document.getElementById('tanggal-lahir').value = originalDate;
                            }
                        }
                    });
                });
            </script>

            <div class="sm:col-span-3">
                <div class="inline-block">
                    <label for="af-pegawai-agama" class="inline-block text-sm text-gray-800 mt-2.5 dark:text-gray-200">
                        Agama
                    </label>
                </div>
            </div>
            <!-- End Col -->

            <div class="sm:col-span-9">
                <div class="sm:flex">
                    <select name="agama" class="py-2 px-3 pe-9 block w-full sm:w-auto border-gray-200 shadow-sm -mt-px -ms-px first:rounded-t-lg last:rounded-b-lg sm:first:rounded-s-lg sm:mt-0 sm:first:ms-0 sm:first:rounded-se-none sm:last:rounded-es-none sm:last:rounded-e-lg text-sm relative focus:z-10 focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600">

                        <option value="Lainnya" <?= ($userData['agama']) === 'Lainnya' ? 'selected' : '' ?>>Lainnya</option>
                        <option value="Kristen" <?= ($userData['agama']) === 'Kristen' ? 'selected' : '' ?>>Kristen</option>
                        <option value="Khatolik" <?= ($userData['agama']) === 'Khatolik' ? 'selected' : '' ?>>Khatolik</option>
                        <option value="Buddha" <?= ($userData['agama']) === 'Buddha' ? 'selected' : '' ?>>Buddha</option>
                        <option value="Islam" <?= ($userData['agama']) === 'Islam' ? 'selected' : '' ?>>Islam</option>
                        <option value="Hindu" <?= ($userData['agama']) === 'Hindu' ? 'selected' : '' ?>>Hindu</option>
                        <option value="Konghucu" <?= ($userData['agama']) === 'Konghucu' ? 'selected' : '' ?>>Konghucu</option>

                    </select>
                </div>
            </div>
            <!-- End Col -->

            <div class="sm:col-span-3">
                <div class="inline-block">
                    <label for="af-pegawai-pendidikan" class="inline-block text-sm text-gray-800 mt-2.5 dark:text-gray-200">
                        Pendidikan
                    </label>
                </div>
            </div>
            <!-- End Col -->

            <div class="sm:col-span-9">
                <div class="sm:flex">
                    <select name="pendidikan" class="py-2 px-3 pe-9 block w-full sm:w-auto border-gray-200 shadow-sm -mt-px -ms-px first:rounded-t-lg last:rounded-b-lg sm:first:rounded-s-lg sm:mt-0 sm:first:ms-0 sm:first:rounded-se-none sm:last:rounded-es-none sm:last:rounded-e-lg text-sm relative focus:z-10 focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600">

                        <option value="Tidak Sekolah" <?= ($userData['pendidikan']) === 'Tidak Sekolah' ? 'selected' : '' ?>>Tidak Sekolah</option>
                        <!-- <option value="1">1 Direktur</option>
                        <option value="2">2 Manager</option>
                        <option value="3">3 Supervisor</option>
                        <option value="4">4 Staff</option> -->
                    </select>
                </div>
            </div>

            <input type="hidden" name="ktp_url" id="ktp_url" value="<?= isset($userData['ktp']) ? $userData['ktp'] : '' ?>">
            <input type="hidden" name="kk_url" id="kk_url" value="<?= isset($userData['kk']) ? $userData['kk'] : '' ?>">
            <input type="hidden" name="npwp_url" id="npwp_url" value="<?= isset($userData['npwp']) ? $userData['npwp'] : '' ?>">
            <input type="hidden" name="bpjs_url" id="bpjs_url" value="<?= isset($userData['bpjs']) ? $userData['bpjs'] : '' ?>">
            <input type="hidden" name="ijazah_url" id="ijazah_url" value="<?= isset($userData['ijazah']) ? $userData['ijazah'] : '' ?>">
            <input type="hidden" name="skck_url" id="skck_url" value="<?= isset($userData['skck']) ? $userData['skck'] : '' ?>">
            <input type="hidden" name="str_url" id="str_url" value="<?= isset($userData['str']) ? $userData['str'] : '' ?>">
            <input type="hidden" name="serkom_url" id="serkom_url" value="<?= isset($userData['serkom']) ? $userData['serkom'] : '' ?>">

            <div class="mt-5 flex justify-end gap-x-2">
                <button type="button" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-white dark:hover:bg-gray-800 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                    Cancel
                </button>
                <button type="submit" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                    Save changes
                </button>
            </div>
        </form>

        <form action="/submiteditktp/<?= $pegawaiId ?>" method="post" enctype="multipart/form-data">

            <div class="space-y-2">
                <label for="af-submit-ktp-upload-images" class="inline-block text-sm font-medium text-gray-800 mt-2.5 dark:text-neutral-200">
                    KTP Image
                </label>

                <label for="af-submit-ktp-upload-images" class="group p-4 sm:p-7 block cursor-pointer text-center border-2 border-dashed border-gray-200 rounded-lg focus-within:outline-none focus-within:ring-2 focus-within:ring-blue-500 focus-within:ring-offset-2 dark:border-neutral-700">
                    <input id="af-submit-ktp-upload-images" name="af-submit-ktp-upload-images" type="file" class="sr-only" onchange="previewImage(event)">
                    <img id="ktp-image-preview" class="mx-auto size-40" src="<?= isset($userData['ktp']) ? $userData['ktp'] : '#' ?>" alt="KTP Image Preview"> <!-- Hidden by default -->
                    <svg class="size-10 mx-auto text-gray-400 dark:text-neutral-600" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M7.646 5.146a.5.5 0 0 1 .708 0l2 2a.5.5 0 0 1-.708.708L8.5 6.707V10.5a.5.5 0 0 1-1 0V6.707L6.354 7.854a.5.5 0 1 1-.708-.708l2-2z" />
                        <path d="M4.406 3.342A5.53 5.53 0 0 1 8 2c2.69 0 4.923 2 5.166 4.579C14.758 6.804 16 8.137 16 9.773 16 11.569 14.502 13 12.687 13H3.781C1.708 13 0 11.366 0 9.318c0-1.763 1.266-3.223 2.942-3.593.143-.863.698-1.723 1.464-2.383zm.653.757c-.757.653-1.153 1.44-1.153 2.056v.448l-.445.049C2.064 6.805 1 7.952 1 9.318 1 10.785 2.23 12 3.781 12h8.906C13.98 12 15 10.988 15 9.773c0-1.216-1.02-2.228-2.313-2.228h-.5v-.5C12.188 4.825 10.328 3 8 3a4.53 4.53 0 0 0-2.941 1.1z" />
                    </svg>
                    <span class="mt-2 block text-sm text-gray-800 dark:text-neutral-200">
                        Browse your device or <span class="group-hover:text-blue-700 text-blue-600">drag 'n drop'</span>
                    </span>
                    <span class="mt-1 mb-4 block text-xs text-gray-500 dark:text-neutral-500">
                        Maximum file size is 2 MB
                    </span>
                    <button type="submit" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                        Save changes
                    </button>

                </label>

                <input type="hidden" name="id_pegawai_hidden" id="id_pegawai_hidden" value="<?= isset($userData['id_pegawai']) ? $userData['id_pegawai'] : '' ?>">
                <input type="hidden" name="nik_hidden" id="nik_hidden" value="<?= isset($userData['nik']) ? $userData['nik'] : '' ?>">
                <input type="hidden" name="tempat_lahir_hidden" id="tempat_lahir_hidden" value="<?= isset($userData['tempat_lahir']) ? $userData['tempat_lahir'] : '' ?>">
                <input type="hidden" name="tanggal_lahir_hidden" id="tanggal_lahir_hidden" value="<?= isset($userData['tanggal_lahir']) ? $userData['tanggal_lahir'] : '' ?>">
                <input type="hidden" name="agama_hidden" id="agama_hidden" value="<?= isset($userData['agama']) ? $userData['agama'] : '' ?>">
                <input type="hidden" name="pendidikan_hidden" id="pendidikan_hidden" value="<?= isset($userData['pendidikan']) ? $userData['pendidikan'] : '' ?>">

                <input type="hidden" name="ktp_url" id="ktp_url" value="<?= isset($userData['ktp']) ? $userData['ktp'] : '' ?>">
                <input type="hidden" name="kk_url" id="kk_url" value="<?= isset($userData['kk']) ? $userData['kk'] : '' ?>">
                <input type="hidden" name="npwp_url" id="npwp_url" value="<?= isset($userData['npwp']) ? $userData['npwp'] : '' ?>">
                <input type="hidden" name="bpjs_url" id="bpjs_url" value="<?= isset($userData['bpjs']) ? $userData['bpjs'] : '' ?>">
                <input type="hidden" name="ijazah_url" id="ijazah_url" value="<?= isset($userData['ijazah']) ? $userData['ijazah'] : '' ?>">
                <input type="hidden" name="skck_url" id="skck_url" value="<?= isset($userData['skck']) ? $userData['skck'] : '' ?>">
                <input type="hidden" name="str_url" id="str_url" value="<?= isset($userData['str']) ? $userData['str'] : '' ?>">
                <input type="hidden" name="serkom_url" id="serkom_url" value="<?= isset($userData['serkom']) ? $userData['serkom'] : '' ?>">

            </div>

            <script>
                function previewImage(event) {
                    const input = event.target;
                    const reader = new FileReader();

                    reader.onload = function() {
                        const imgElement = document.getElementById('ktp-image-preview');
                        imgElement.src = reader.result;

                    };

                    reader.readAsDataURL(input.files[0]);
                }
            </script>


        </form>

        <form action="/submiteditkk/<?= $pegawaiId ?>" method="post" enctype="multipart/form-data">
            <div class="space-y-2">
                <label for="af-submit-kk-upload-images" class="inline-block text-sm font-medium text-gray-800 mt-2.5 dark:text-neutral-200">
                    KK Image
                </label>

                <label for="af-submit-kk-upload-images" class="group p-4 sm:p-7 block cursor-pointer text-center border-2 border-dashed border-gray-200 rounded-lg focus-within:outline-none focus-within:ring-2 focus-within:ring-blue-500 focus-within:ring-offset-2 dark:border-neutral-700">
                    <input id="af-submit-kk-upload-images" name="af-submit-kk-upload-images" type="file" class="sr-only" onchange="previewImageKK(event)">
                    <img id="kk-image-preview" class=" mx-auto size-40" src="<?= isset($userData['kk']) ? $userData['kk'] : '#' ?>" alt="KK Image Preview"> <!-- Hidden by default -->
                    <svg class="size-10 mx-auto text-gray-400 dark:text-neutral-600" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M7.646 5.146a.5.5 0 0 1 .708 0l2 2a.5.5 0 0 1-.708.708L8.5 6.707V10.5a.5.5 0 0 1-1 0V6.707L6.354 7.854a.5.5 0 1 1-.708-.708l2-2z" />
                        <path d="M4.406 3.342A5.53 5.53 0 0 1 8 2c2.69 0 4.923 2 5.166 4.579C14.758 6.804 16 8.137 16 9.773 16 11.569 14.502 13 12.687 13H3.781C1.708 13 0 11.366 0 9.318c0-1.763 1.266-3.223 2.942-3.593.143-.863.698-1.723 1.464-2.383zm.653.757c-.757.653-1.153 1.44-1.153 2.056v.448l-.445.049C2.064 6.805 1 7.952 1 9.318 1 10.785 2.23 12 3.781 12h8.906C13.98 12 15 10.988 15 9.773c0-1.216-1.02-2.228-2.313-2.228h-.5v-.5C12.188 4.825 10.328 3 8 3a4.53 4.53 0 0 0-2.941 1.1z" />
                    </svg>
                    <span class="mt-2 block text-sm text-gray-800 dark:text-neutral-200">
                        Browse your device or <span class="group-hover:text-blue-700 text-blue-600">drag 'n drop'</span>
                    </span>
                    <span class="mt-1 mb-4 block text-xs text-gray-500 dark:text-neutral-500">
                        Maximum file size is 2 MB
                    </span>
                    <button type="submit" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                        Save changes
                    </button>

                    <input type="hidden" name="id_pegawai_hidden" id="id_pegawai_hidden" value="<?= isset($userData['id_pegawai']) ? $userData['id_pegawai'] : '' ?>">
                    <input type="hidden" name="nik_hidden" id="nik_hidden" value="<?= isset($userData['nik']) ? $userData['nik'] : '' ?>">
                    <input type="hidden" name="tempat_lahir_hidden" id="tempat_lahir_hidden" value="<?= isset($userData['tempat_lahir']) ? $userData['tempat_lahir'] : '' ?>">
                    <input type="hidden" name="tanggal_lahir_hidden" id="tanggal_lahir_hidden" value="<?= isset($userData['tanggal_lahir']) ? $userData['tanggal_lahir'] : '' ?>">
                    <input type="hidden" name="agama_hidden" id="agama_hidden" value="<?= isset($userData['agama']) ? $userData['agama'] : '' ?>">
                    <input type="hidden" name="pendidikan_hidden" id="pendidikan_hidden" value="<?= isset($userData['pendidikan']) ? $userData['pendidikan'] : '' ?>">

                    <input type="hidden" name="ktp_url" id="ktp_url" value="<?= isset($userData['ktp']) ? $userData['ktp'] : '' ?>">
                    <input type="hidden" name="kk_url" id="kk_url" value="<?= isset($userData['kk']) ? $userData['kk'] : '' ?>">
                    <input type="hidden" name="npwp_url" id="npwp_url" value="<?= isset($userData['npwp']) ? $userData['npwp'] : '' ?>">
                    <input type="hidden" name="bpjs_url" id="bpjs_url" value="<?= isset($userData['bpjs']) ? $userData['bpjs'] : '' ?>">
                    <input type="hidden" name="ijazah_url" id="ijazah_url" value="<?= isset($userData['ijazah']) ? $userData['ijazah'] : '' ?>">
                    <input type="hidden" name="skck_url" id="skck_url" value="<?= isset($userData['skck']) ? $userData['skck'] : '' ?>">
                    <input type="hidden" name="str_url" id="str_url" value="<?= isset($userData['str']) ? $userData['str'] : '' ?>">
                    <input type="hidden" name="serkom_url" id="serkom_url" value="<?= isset($userData['serkom']) ? $userData['serkom'] : '' ?>">

                </label>
            </div>

            <script>
                function previewImageKK(event) {
                    const input = event.target;
                    const reader = new FileReader();

                    reader.onload = function() {
                        const imgElement = document.getElementById('kk-image-preview');
                        imgElement.src = reader.result;

                    };

                    reader.readAsDataURL(input.files[0]);
                }
            </script>

        </form>



        <form action="/submiteditnpwp/<?= $pegawaiId ?>" method="post" enctype="multipart/form-data">
            <div class="space-y-2">
                <label for="af-submit-npwp-upload-images" class="inline-block text-sm font-medium text-gray-800 mt-2.5 dark:text-neutral-200">
                    NPWP Image
                </label>

                <label for="af-submit-npwp-upload-images" class="group p-4 sm:p-7 block cursor-pointer text-center border-2 border-dashed border-gray-200 rounded-lg focus-within:outline-none focus-within:ring-2 focus-within:ring-blue-500 focus-within:ring-offset-2 dark:border-neutral-700">
                    <input id="af-submit-npwp-upload-images" name="af-submit-npwp-upload-images" type="file" class="sr-only" onchange="previewImageNPWP(event)">
                    <img id="npwp-image-preview" class="mx-auto size-40" src="<?= isset($userData['npwp']) ? $userData['npwp'] : '#' ?>" alt="NPWP Image Preview"> <!-- Hidden by default -->
                    <svg class="size-10 mx-auto text-gray-400 dark:text-neutral-600" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M7.646 5.146a.5.5 0 0 1 .708 0l2 2a.5.5 0 0 1-.708.708L8.5 6.707V10.5a.5.5 0 0 1-1 0V6.707L6.354 7.854a.5.5 0 1 1-.708-.708l2-2z" />
                        <path d="M4.406 3.342A5.53 5.53 0 0 1 8 2c2.69 0 4.923 2 5.166 4.579C14.758 6.804 16 8.137 16 9.773 16 11.569 14.502 13 12.687 13H3.781C1.708 13 0 11.366 0 9.318c0-1.763 1.266-3.223 2.942-3.593.143-.863.698-1.723 1.464-2.383zm.653.757c-.757.653-1.153 1.44-1.153 2.056v.448l-.445.049C2.064 6.805 1 7.952 1 9.318 1 10.785 2.23 12 3.781 12h8.906C13.98 12 15 10.988 15 9.773c0-1.216-1.02-2.228-2.313-2.228h-.5v-.5C12.188 4.825 10.328 3 8 3a4.53 4.53 0 0 0-2.941 1.1z" />
                    </svg>
                    <span class="mt-2 block text-sm text-gray-800 dark:text-neutral-200">
                        Browse your device or <span class="group-hover:text-blue-700 text-blue-600">drag 'n drop'</span>
                    </span>
                    <span class="mt-1 block text-xs text-gray-500 dark:text-neutral-500">
                        Maximum file size is 2 MB
                    </span>


                    <button type="submit" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                        Save changes
                    </button>

                    <input type="hidden" name="id_pegawai_hidden" id="id_pegawai_hidden" value="<?= isset($userData['id_pegawai']) ? $userData['id_pegawai'] : '' ?>">
                    <input type="hidden" name="nik_hidden" id="nik_hidden" value="<?= isset($userData['nik']) ? $userData['nik'] : '' ?>">
                    <input type="hidden" name="tempat_lahir_hidden" id="tempat_lahir_hidden" value="<?= isset($userData['tempat_lahir']) ? $userData['tempat_lahir'] : '' ?>">
                    <input type="hidden" name="tanggal_lahir_hidden" id="tanggal_lahir_hidden" value="<?= isset($userData['tanggal_lahir']) ? $userData['tanggal_lahir'] : '' ?>">
                    <input type="hidden" name="agama_hidden" id="agama_hidden" value="<?= isset($userData['agama']) ? $userData['agama'] : '' ?>">
                    <input type="hidden" name="pendidikan_hidden" id="pendidikan_hidden" value="<?= isset($userData['pendidikan']) ? $userData['pendidikan'] : '' ?>">

                    <input type="hidden" name="ktp_url" id="ktp_url" value="<?= isset($userData['ktp']) ? $userData['ktp'] : '' ?>">
                    <input type="hidden" name="kk_url" id="kk_url" value="<?= isset($userData['kk']) ? $userData['kk'] : '' ?>">
                    <input type="hidden" name="npwp_url" id="npwp_url" value="<?= isset($userData['npwp']) ? $userData['npwp'] : '' ?>">
                    <input type="hidden" name="bpjs_url" id="bpjs_url" value="<?= isset($userData['bpjs']) ? $userData['bpjs'] : '' ?>">
                    <input type="hidden" name="ijazah_url" id="ijazah_url" value="<?= isset($userData['ijazah']) ? $userData['ijazah'] : '' ?>">
                    <input type="hidden" name="skck_url" id="skck_url" value="<?= isset($userData['skck']) ? $userData['skck'] : '' ?>">
                    <input type="hidden" name="str_url" id="str_url" value="<?= isset($userData['str']) ? $userData['str'] : '' ?>">
                    <input type="hidden" name="serkom_url" id="serkom_url" value="<?= isset($userData['serkom']) ? $userData['serkom'] : '' ?>">

                </label>
            </div>

            <script>
                function previewImageNPWP(event) {
                    const input = event.target;
                    const reader = new FileReader();

                    reader.onload = function() {
                        const imgElement = document.getElementById('npwp-image-preview');
                        imgElement.src = reader.result;

                    };

                    reader.readAsDataURL(input.files[0]);
                }
            </script>
        </form>

        <form action="/submiteditbpjs/<?= $pegawaiId ?>" method="post" enctype="multipart/form-data">
            <div class="space-y-2">
                <label for="af-submit-bpjs-upload-images" class="inline-block text-sm font-medium text-gray-800 mt-2.5 dark:text-neutral-200">
                    BPJS Image
                </label>

                <label for="af-submit-bpjs-upload-images" class="group p-4 sm:p-7 block cursor-pointer text-center border-2 border-dashed border-gray-200 rounded-lg focus-within:outline-none focus-within:ring-2 focus-within:ring-blue-500 focus-within:ring-offset-2 dark:border-neutral-700">
                    <input id="af-submit-bpjs-upload-images" name="af-submit-bpjs-upload-images" type="file" class="sr-only" onchange="previewImageBPJS(event)">
                    <img id="bpjs-image-preview" class="mx-auto size-40" src="<?= isset($userData['bpjs']) ? $userData['bpjs'] : '#' ?>" alt="NPWP Image Preview"> <!-- Hidden by default -->
                    <svg class="size-10 mx-auto text-gray-400 dark:text-neutral-600" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M7.646 5.146a.5.5 0 0 1 .708 0l2 2a.5.5 0 0 1-.708.708L8.5 6.707V10.5a.5.5 0 0 1-1 0V6.707L6.354 7.854a.5.5 0 1 1-.708-.708l2-2z" />
                        <path d="M4.406 3.342A5.53 5.53 0 0 1 8 2c2.69 0 4.923 2 5.166 4.579C14.758 6.804 16 8.137 16 9.773 16 11.569 14.502 13 12.687 13H3.781C1.708 13 0 11.366 0 9.318c0-1.763 1.266-3.223 2.942-3.593.143-.863.698-1.723 1.464-2.383zm.653.757c-.757.653-1.153 1.44-1.153 2.056v.448l-.445.049C2.064 6.805 1 7.952 1 9.318 1 10.785 2.23 12 3.781 12h8.906C13.98 12 15 10.988 15 9.773c0-1.216-1.02-2.228-2.313-2.228h-.5v-.5C12.188 4.825 10.328 3 8 3a4.53 4.53 0 0 0-2.941 1.1z" />
                    </svg>
                    <span class="mt-2 block text-sm text-gray-800 dark:text-neutral-200">
                        Browse your device or <span class="group-hover:text-blue-700 text-blue-600">drag 'n drop'</span>
                    </span>
                    <span class="mt-1 block text-xs text-gray-500 dark:text-neutral-500">
                        Maximum file size is 2 MB
                    </span>


                    <button type="submit" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                        Save changes
                    </button>

                    <input type="hidden" name="id_pegawai_hidden" id="id_pegawai_hidden" value="<?= isset($userData['id_pegawai']) ? $userData['id_pegawai'] : '' ?>">
                    <input type="hidden" name="nik_hidden" id="nik_hidden" value="<?= isset($userData['nik']) ? $userData['nik'] : '' ?>">
                    <input type="hidden" name="tempat_lahir_hidden" id="tempat_lahir_hidden" value="<?= isset($userData['tempat_lahir']) ? $userData['tempat_lahir'] : '' ?>">
                    <input type="hidden" name="tanggal_lahir_hidden" id="tanggal_lahir_hidden" value="<?= isset($userData['tanggal_lahir']) ? $userData['tanggal_lahir'] : '' ?>">
                    <input type="hidden" name="agama_hidden" id="agama_hidden" value="<?= isset($userData['agama']) ? $userData['agama'] : '' ?>">
                    <input type="hidden" name="pendidikan_hidden" id="pendidikan_hidden" value="<?= isset($userData['pendidikan']) ? $userData['pendidikan'] : '' ?>">

                    <input type="hidden" name="ktp_url" id="ktp_url" value="<?= isset($userData['ktp']) ? $userData['ktp'] : '' ?>">
                    <input type="hidden" name="kk_url" id="kk_url" value="<?= isset($userData['kk']) ? $userData['kk'] : '' ?>">
                    <input type="hidden" name="npwp_url" id="npwp_url" value="<?= isset($userData['npwp']) ? $userData['npwp'] : '' ?>">
                    <input type="hidden" name="bpjs_url" id="bpjs_url" value="<?= isset($userData['bpjs']) ? $userData['bpjs'] : '' ?>">
                    <input type="hidden" name="ijazah_url" id="ijazah_url" value="<?= isset($userData['ijazah']) ? $userData['ijazah'] : '' ?>">
                    <input type="hidden" name="skck_url" id="skck_url" value="<?= isset($userData['skck']) ? $userData['skck'] : '' ?>">
                    <input type="hidden" name="str_url" id="str_url" value="<?= isset($userData['str']) ? $userData['str'] : '' ?>">
                    <input type="hidden" name="serkom_url" id="serkom_url" value="<?= isset($userData['serkom']) ? $userData['serkom'] : '' ?>">

                </label>
            </div>

            <script>
                function previewImageBPJS(event) {
                    const input = event.target;
                    const reader = new FileReader();

                    reader.onload = function() {
                        const imgElement = document.getElementById('bpjs-image-preview');
                        imgElement.src = reader.result;

                    };

                    reader.readAsDataURL(input.files[0]);
                }
            </script>
        </form>

        <form action="/submiteditijazah/<?= $pegawaiId ?>" method="post" enctype="multipart/form-data">
            <div class="space-y-2">
                <label for="af-submit-ijazah-upload-images" class="inline-block text-sm font-medium text-gray-800 mt-2.5 dark:text-neutral-200">
                    Ijazah
                </label>

                <label for="af-submit-ijazah-upload-images" class="group p-4 sm:p-7 block cursor-pointer text-center border-2 border-dashed border-gray-200 rounded-lg focus-within:outline-none focus-within:ring-2 focus-within:ring-blue-500 focus-within:ring-offset-2 dark:border-neutral-700">
                    <input id="af-submit-ijazah-upload-images" name="af-submit-ijazah-upload-images" type="file" class="sr-only" onchange="previewImageIjazah(event)">
                    <img id="ijazah-image-preview" class="mx-auto size-40" src="<?= isset($userData['ijazah']) ? $userData['ijazah'] : '#' ?>" alt="NPWP Image Preview"> <!-- Hidden by default -->
                    <svg class="size-10 mx-auto text-gray-400 dark:text-neutral-600" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M7.646 5.146a.5.5 0 0 1 .708 0l2 2a.5.5 0 0 1-.708.708L8.5 6.707V10.5a.5.5 0 0 1-1 0V6.707L6.354 7.854a.5.5 0 1 1-.708-.708l2-2z" />
                        <path d="M4.406 3.342A5.53 5.53 0 0 1 8 2c2.69 0 4.923 2 5.166 4.579C14.758 6.804 16 8.137 16 9.773 16 11.569 14.502 13 12.687 13H3.781C1.708 13 0 11.366 0 9.318c0-1.763 1.266-3.223 2.942-3.593.143-.863.698-1.723 1.464-2.383zm.653.757c-.757.653-1.153 1.44-1.153 2.056v.448l-.445.049C2.064 6.805 1 7.952 1 9.318 1 10.785 2.23 12 3.781 12h8.906C13.98 12 15 10.988 15 9.773c0-1.216-1.02-2.228-2.313-2.228h-.5v-.5C12.188 4.825 10.328 3 8 3a4.53 4.53 0 0 0-2.941 1.1z" />
                    </svg>
                    <span class="mt-2 block text-sm text-gray-800 dark:text-neutral-200">
                        Browse your device or <span class="group-hover:text-blue-700 text-blue-600">drag 'n drop'</span>
                    </span>
                    <span class="mt-1 block text-xs text-gray-500 dark:text-neutral-500">
                        Maximum file size is 2 MB
                    </span>

                    <button type="submit" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                        Save changes
                    </button>

                    <input type="hidden" name="id_pegawai_hidden" id="id_pegawai_hidden" value="<?= isset($userData['id_pegawai']) ? $userData['id_pegawai'] : '' ?>">
                    <input type="hidden" name="nik_hidden" id="nik_hidden" value="<?= isset($userData['nik']) ? $userData['nik'] : '' ?>">
                    <input type="hidden" name="tempat_lahir_hidden" id="tempat_lahir_hidden" value="<?= isset($userData['tempat_lahir']) ? $userData['tempat_lahir'] : '' ?>">
                    <input type="hidden" name="tanggal_lahir_hidden" id="tanggal_lahir_hidden" value="<?= isset($userData['tanggal_lahir']) ? $userData['tanggal_lahir'] : '' ?>">
                    <input type="hidden" name="agama_hidden" id="agama_hidden" value="<?= isset($userData['agama']) ? $userData['agama'] : '' ?>">
                    <input type="hidden" name="pendidikan_hidden" id="pendidikan_hidden" value="<?= isset($userData['pendidikan']) ? $userData['pendidikan'] : '' ?>">

                    <input type="hidden" name="ktp_url" id="ktp_url" value="<?= isset($userData['ktp']) ? $userData['ktp'] : '' ?>">
                    <input type="hidden" name="kk_url" id="kk_url" value="<?= isset($userData['kk']) ? $userData['kk'] : '' ?>">
                    <input type="hidden" name="npwp_url" id="npwp_url" value="<?= isset($userData['npwp']) ? $userData['npwp'] : '' ?>">
                    <input type="hidden" name="bpjs_url" id="bpjs_url" value="<?= isset($userData['bpjs']) ? $userData['bpjs'] : '' ?>">
                    <input type="hidden" name="ijazah_url" id="ijazah_url" value="<?= isset($userData['ijazah']) ? $userData['ijazah'] : '' ?>">
                    <input type="hidden" name="skck_url" id="skck_url" value="<?= isset($userData['skck']) ? $userData['skck'] : '' ?>">
                    <input type="hidden" name="str_url" id="str_url" value="<?= isset($userData['str']) ? $userData['str'] : '' ?>">
                    <input type="hidden" name="serkom_url" id="serkom_url" value="<?= isset($userData['serkom']) ? $userData['serkom'] : '' ?>">

                </label>
            </div>

            <script>
                function previewImageIjazah(event) {
                    const input = event.target;
                    const reader = new FileReader();

                    reader.onload = function() {
                        const imgElement = document.getElementById('ijazah-image-preview');
                        imgElement.src = reader.result;
                    };

                    reader.readAsDataURL(input.files[0]);
                }
            </script>
        </form>

        <form action="/submiteditskck/<?= $pegawaiId ?>" method="post" enctype="multipart/form-data">
            <div class="space-y-2">
                <label for="af-submit-skck-upload-images" class="inline-block text-sm font-medium text-gray-800 mt-2.5 dark:text-neutral-200">
                    SKCK
                </label>

                <label for="af-submit-skck-upload-images" class="group p-4 sm:p-7 block cursor-pointer text-center border-2 border-dashed border-gray-200 rounded-lg focus-within:outline-none focus-within:ring-2 focus-within:ring-blue-500 focus-within:ring-offset-2 dark:border-neutral-700">
                    <input id="af-submit-skck-upload-images" name="af-submit-skck-upload-images" type="file" class="sr-only" onchange="previewImageSkck(event)">
                    <img id="skck-image-preview" class="mx-auto size-40" src="<?= isset($userData['skck']) ? $userData['skck'] : '#' ?>" alt="NPWP Image Preview"> <!-- Hidden by default -->
                    <svg class="size-10 mx-auto text-gray-400 dark:text-neutral-600" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M7.646 5.146a.5.5 0 0 1 .708 0l2 2a.5.5 0 0 1-.708.708L8.5 6.707V10.5a.5.5 0 0 1-1 0V6.707L6.354 7.854a.5.5 0 1 1-.708-.708l2-2z" />
                        <path d="M4.406 3.342A5.53 5.53 0 0 1 8 2c2.69 0 4.923 2 5.166 4.579C14.758 6.804 16 8.137 16 9.773 16 11.569 14.502 13 12.687 13H3.781C1.708 13 0 11.366 0 9.318c0-1.763 1.266-3.223 2.942-3.593.143-.863.698-1.723 1.464-2.383zm.653.757c-.757.653-1.153 1.44-1.153 2.056v.448l-.445.049C2.064 6.805 1 7.952 1 9.318 1 10.785 2.23 12 3.781 12h8.906C13.98 12 15 10.988 15 9.773c0-1.216-1.02-2.228-2.313-2.228h-.5v-.5C12.188 4.825 10.328 3 8 3a4.53 4.53 0 0 0-2.941 1.1z" />
                    </svg>
                    <span class="mt-2 block text-sm text-gray-800 dark:text-neutral-200">
                        Browse your device or <span class="group-hover:text-blue-700 text-blue-600">drag 'n drop'</span>
                    </span>
                    <span class="mt-1 block text-xs text-gray-500 dark:text-neutral-500">
                        Maximum file size is 2 MB
                    </span>
                    <button type="submit" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                        Save changes
                    </button>

                    <input type="hidden" name="id_pegawai_hidden" id="id_pegawai_hidden" value="<?= isset($userData['id_pegawai']) ? $userData['id_pegawai'] : '' ?>">
                    <input type="hidden" name="nik_hidden" id="nik_hidden" value="<?= isset($userData['nik']) ? $userData['nik'] : '' ?>">
                    <input type="hidden" name="tempat_lahir_hidden" id="tempat_lahir_hidden" value="<?= isset($userData['tempat_lahir']) ? $userData['tempat_lahir'] : '' ?>">
                    <input type="hidden" name="tanggal_lahir_hidden" id="tanggal_lahir_hidden" value="<?= isset($userData['tanggal_lahir']) ? $userData['tanggal_lahir'] : '' ?>">
                    <input type="hidden" name="agama_hidden" id="agama_hidden" value="<?= isset($userData['agama']) ? $userData['agama'] : '' ?>">
                    <input type="hidden" name="pendidikan_hidden" id="pendidikan_hidden" value="<?= isset($userData['pendidikan']) ? $userData['pendidikan'] : '' ?>">

                    <input type="hidden" name="ktp_url" id="ktp_url" value="<?= isset($userData['ktp']) ? $userData['ktp'] : '' ?>">
                    <input type="hidden" name="kk_url" id="kk_url" value="<?= isset($userData['kk']) ? $userData['kk'] : '' ?>">
                    <input type="hidden" name="npwp_url" id="npwp_url" value="<?= isset($userData['npwp']) ? $userData['npwp'] : '' ?>">
                    <input type="hidden" name="bpjs_url" id="bpjs_url" value="<?= isset($userData['bpjs']) ? $userData['bpjs'] : '' ?>">
                    <input type="hidden" name="ijazah_url" id="ijazah_url" value="<?= isset($userData['ijazah']) ? $userData['ijazah'] : '' ?>">
                    <input type="hidden" name="skck_url" id="skck_url" value="<?= isset($userData['skck']) ? $userData['skck'] : '' ?>">
                    <input type="hidden" name="str_url" id="str_url" value="<?= isset($userData['str']) ? $userData['str'] : '' ?>">
                    <input type="hidden" name="serkom_url" id="serkom_url" value="<?= isset($userData['serkom']) ? $userData['serkom'] : '' ?>">

                </label>
            </div>

            <script>
                function previewImageSkck(event) {
                    const input = event.target;
                    const reader = new FileReader();

                    reader.onload = function() {
                        const imgElement = document.getElementById('skck-image-preview');
                        imgElement.src = reader.result;
                    };

                    reader.readAsDataURL(input.files[0]);
                }
            </script>
        </form>

        <form action="/submiteditstr/<?= $pegawaiId ?>" method="post" enctype="multipart/form-data">
            <div class="space-y-2">
                <label for="af-submit-str-upload-images" class="inline-block text-sm font-medium text-gray-800 mt-2.5 dark:text-neutral-200">
                    STR
                </label>

                <label for="af-submit-str-upload-images" class="group p-4 sm:p-7 block cursor-pointer text-center border-2 border-dashed border-gray-200 rounded-lg focus-within:outline-none focus-within:ring-2 focus-within:ring-blue-500 focus-within:ring-offset-2 dark:border-neutral-700">
                    <input id="af-submit-str-upload-images" name="af-submit-str-upload-images" type="file" class="sr-only" onchange="previewImageStr(event)">
                    <img id="str-image-preview" class="mx-auto size-40" src="<?= isset($userData['str']) ? $userData['str'] : '#' ?>" alt="NPWP Image Preview"> <!-- Hidden by default -->
                    <svg class="size-10 mx-auto text-gray-400 dark:text-neutral-600" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M7.646 5.146a.5.5 0 0 1 .708 0l2 2a.5.5 0 0 1-.708.708L8.5 6.707V10.5a.5.5 0 0 1-1 0V6.707L6.354 7.854a.5.5 0 1 1-.708-.708l2-2z" />
                        <path d="M4.406 3.342A5.53 5.53 0 0 1 8 2c2.69 0 4.923 2 5.166 4.579C14.758 6.804 16 8.137 16 9.773 16 11.569 14.502 13 12.687 13H3.781C1.708 13 0 11.366 0 9.318c0-1.763 1.266-3.223 2.942-3.593.143-.863.698-1.723 1.464-2.383zm.653.757c-.757.653-1.153 1.44-1.153 2.056v.448l-.445.049C2.064 6.805 1 7.952 1 9.318 1 10.785 2.23 12 3.781 12h8.906C13.98 12 15 10.988 15 9.773c0-1.216-1.02-2.228-2.313-2.228h-.5v-.5C12.188 4.825 10.328 3 8 3a4.53 4.53 0 0 0-2.941 1.1z" />
                    </svg>
                    <span class="mt-2 block text-sm text-gray-800 dark:text-neutral-200">
                        Browse your device or <span class="group-hover:text-blue-700 text-blue-600">drag 'n drop'</span>
                    </span>
                    <span class="mt-1 block text-xs text-gray-500 dark:text-neutral-500">
                        Maximum file size is 2 MB
                    </span>

                    <button type="submit" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                        Save changes
                    </button>

                    <input type="hidden" name="id_pegawai_hidden" id="id_pegawai_hidden" value="<?= isset($userData['id_pegawai']) ? $userData['id_pegawai'] : '' ?>">
                    <input type="hidden" name="nik_hidden" id="nik_hidden" value="<?= isset($userData['nik']) ? $userData['nik'] : '' ?>">
                    <input type="hidden" name="tempat_lahir_hidden" id="tempat_lahir_hidden" value="<?= isset($userData['tempat_lahir']) ? $userData['tempat_lahir'] : '' ?>">
                    <input type="hidden" name="tanggal_lahir_hidden" id="tanggal_lahir_hidden" value="<?= isset($userData['tanggal_lahir']) ? $userData['tanggal_lahir'] : '' ?>">
                    <input type="hidden" name="agama_hidden" id="agama_hidden" value="<?= isset($userData['agama']) ? $userData['agama'] : '' ?>">
                    <input type="hidden" name="pendidikan_hidden" id="pendidikan_hidden" value="<?= isset($userData['pendidikan']) ? $userData['pendidikan'] : '' ?>">

                    <input type="hidden" name="ktp_url" id="ktp_url" value="<?= isset($userData['ktp']) ? $userData['ktp'] : '' ?>">
                    <input type="hidden" name="kk_url" id="kk_url" value="<?= isset($userData['kk']) ? $userData['kk'] : '' ?>">
                    <input type="hidden" name="npwp_url" id="npwp_url" value="<?= isset($userData['npwp']) ? $userData['npwp'] : '' ?>">
                    <input type="hidden" name="bpjs_url" id="bpjs_url" value="<?= isset($userData['bpjs']) ? $userData['bpjs'] : '' ?>">
                    <input type="hidden" name="ijazah_url" id="ijazah_url" value="<?= isset($userData['ijazah']) ? $userData['ijazah'] : '' ?>">
                    <input type="hidden" name="skck_url" id="skck_url" value="<?= isset($userData['skck']) ? $userData['skck'] : '' ?>">
                    <input type="hidden" name="str_url" id="str_url" value="<?= isset($userData['str']) ? $userData['str'] : '' ?>">
                    <input type="hidden" name="serkom_url" id="serkom_url" value="<?= isset($userData['serkom']) ? $userData['serkom'] : '' ?>">
                </label>
            </div>

            <script>
                function previewImageStr(event) {
                    const input = event.target;
                    const reader = new FileReader();

                    reader.onload = function() {
                        const imgElement = document.getElementById('str-image-preview');
                        imgElement.src = reader.result;
                    };

                    reader.readAsDataURL(input.files[0]);
                }
            </script>

        </form>


        <form action="/submiteditserkom/<?= $pegawaiId ?>" method="post" enctype="multipart/form-data">
            <div class="space-y-2">
                <label for="af-submit-serkom-upload-images" class="inline-block text-sm font-medium text-gray-800 mt-2.5 dark:text-neutral-200">
                    SERKOM
                </label>

                <label for="af-submit-serkom-upload-images" class="group p-4 sm:p-7 block cursor-pointer text-center border-2 border-dashed border-gray-200 rounded-lg focus-within:outline-none focus-within:ring-2 focus-within:ring-blue-500 focus-within:ring-offset-2 dark:border-neutral-700">
                    <input id="af-submit-serkom-upload-images" name="af-submit-serkom-upload-images" type="file" class="sr-only" onchange="previewImageSerkom(event)">
                    <img id="serkom-image-preview" class="mx-auto size-40" src="<?= isset($userData['serkom']) ? $userData['serkom'] : '#' ?>" alt="NPWP Image Preview"> <!-- Hidden by default -->
                    <svg class="size-10 mx-auto text-gray-400 dark:text-neutral-600" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M7.646 5.146a.5.5 0 0 1 .708 0l2 2a.5.5 0 0 1-.708.708L8.5 6.707V10.5a.5.5 0 0 1-1 0V6.707L6.354 7.854a.5.5 0 1 1-.708-.708l2-2z" />
                        <path d="M4.406 3.342A5.53 5.53 0 0 1 8 2c2.69 0 4.923 2 5.166 4.579C14.758 6.804 16 8.137 16 9.773 16 11.569 14.502 13 12.687 13H3.781C1.708 13 0 11.366 0 9.318c0-1.763 1.266-3.223 2.942-3.593.143-.863.698-1.723 1.464-2.383zm.653.757c-.757.653-1.153 1.44-1.153 2.056v.448l-.445.049C2.064 6.805 1 7.952 1 9.318 1 10.785 2.23 12 3.781 12h8.906C13.98 12 15 10.988 15 9.773c0-1.216-1.02-2.228-2.313-2.228h-.5v-.5C12.188 4.825 10.328 3 8 3a4.53 4.53 0 0 0-2.941 1.1z" />
                    </svg>
                    <span class="mt-2 block text-sm text-gray-800 dark:text-neutral-200">
                        Browse your device or <span class="group-hover:text-blue-700 text-blue-600">drag 'n drop'</span>
                    </span>
                    <span class="mt-1 block text-xs text-gray-500 dark:text-neutral-500">
                        Maximum file size is 2 MB
                    </span>

                    <button type="submit" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                        Save changes
                    </button>

                    <input type="hidden" name="id_pegawai_hidden" id="id_pegawai_hidden" value="<?= isset($userData['id_pegawai']) ? $userData['id_pegawai'] : '' ?>">
                    <input type="hidden" name="nik_hidden" id="nik_hidden" value="<?= isset($userData['nik']) ? $userData['nik'] : '' ?>">
                    <input type="hidden" name="tempat_lahir_hidden" id="tempat_lahir_hidden" value="<?= isset($userData['tempat_lahir']) ? $userData['tempat_lahir'] : '' ?>">
                    <input type="hidden" name="tanggal_lahir_hidden" id="tanggal_lahir_hidden" value="<?= isset($userData['tanggal_lahir']) ? $userData['tanggal_lahir'] : '' ?>">
                    <input type="hidden" name="agama_hidden" id="agama_hidden" value="<?= isset($userData['agama']) ? $userData['agama'] : '' ?>">
                    <input type="hidden" name="pendidikan_hidden" id="pendidikan_hidden" value="<?= isset($userData['pendidikan']) ? $userData['pendidikan'] : '' ?>">

                    <input type="hidden" name="ktp_url" id="ktp_url" value="<?= isset($userData['ktp']) ? $userData['ktp'] : '' ?>">
                    <input type="hidden" name="kk_url" id="kk_url" value="<?= isset($userData['kk']) ? $userData['kk'] : '' ?>">
                    <input type="hidden" name="npwp_url" id="npwp_url" value="<?= isset($userData['npwp']) ? $userData['npwp'] : '' ?>">
                    <input type="hidden" name="bpjs_url" id="bpjs_url" value="<?= isset($userData['bpjs']) ? $userData['bpjs'] : '' ?>">
                    <input type="hidden" name="ijazah_url" id="ijazah_url" value="<?= isset($userData['ijazah']) ? $userData['ijazah'] : '' ?>">
                    <input type="hidden" name="skck_url" id="skck_url" value="<?= isset($userData['skck']) ? $userData['skck'] : '' ?>">
                    <input type="hidden" name="str_url" id="str_url" value="<?= isset($userData['str']) ? $userData['str'] : '' ?>">
                    <input type="hidden" name="serkom_url" id="serkom_url" value="<?= isset($userData['serkom']) ? $userData['serkom'] : '' ?>">
                </label>
            </div>

            <script>
                function previewImageSerkom(event) {
                    const input = event.target;
                    const reader = new FileReader();

                    reader.onload = function() {
                        const imgElement = document.getElementById('serkom-image-preview');
                        imgElement.src = reader.result;
                    };

                    reader.readAsDataURL(input.files[0]);
                }
            </script>

        </form>

    </div>
    <!-- End Grid -->


</div>
<!-- End Card -->
</div>
<!-- End Card Section -->

<?= $this->endSection(); ?>