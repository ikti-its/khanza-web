<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<!-- Card Section -->
<div class="max-w-xl px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto">


    <!-- Card -->
    <div class="bg-white rounded-xl shadow p-4 sm:p-7 dark:bg-neutral-900">
        <div class="space-y-2">
            <label for="af-submit-app-upload-images" class="pb-5 inline-block text-l font-medium text-gray-800 mt-2.5 dark:text-neutral-200">
                Unggah dan Lampirkan File
            </label>

            <label for="af-submit-app-upload-images" onchange="uploadFile()" class=" p-6 sm:p-7 block cursor-pointer text-center border-2 border-dashed border-[#1D8676] rounded-lg focus-within:outline-none focus-within:ring-2 focus-within:ring-[#1D8676] focus-within:ring-offset-2 dark:border-neutral-700 hover:bg-[#D6F9F3] bg-gray-100 group-hover:bg-green-600">
                <input id="af-submit-app-upload-images" name="af-submit-app-upload-images" type="file" accept=".png" class="sr-only">
                <svg class="size-10 mx-auto text-gray-400 dark:text-neutral-600" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M7.646 5.146a.5.5 0 0 1 .708 0l2 2a.5.5 0 0 1-.708.708L8.5 6.707V10.5a.5.5 0 0 1-1 0V6.707L6.354 7.854a.5.5 0 1 1-.708-.708l2-2z" />
                    <path d="M4.406 3.342A5.53 5.53 0 0 1 8 2c2.69 0 4.923 2 5.166 4.579C14.758 6.804 16 8.137 16 9.773 16 11.569 14.502 13 12.687 13H3.781C1.708 13 0 11.366 0 9.318c0-1.763 1.266-3.223 2.942-3.593.143-.863.698-1.723 1.464-2.383zm.653.757c-.757.653-1.153 1.44-1.153 2.056v.448l-.445.049C2.064 6.805 1 7.952 1 9.318 1 10.785 2.23 12 3.781 12h8.906C13.98 12 15 10.988 15 9.773c0-1.216-1.02-2.228-2.313-2.228h-.5v-.5C12.188 4.825 10.328 3 8 3a4.53 4.53 0 0 0-2.941 1.1z" />
                </svg>
                <span class="mt-2 block text-sm text-gray-800 dark:text-neutral-200">
                    <span class="text-[#24A793]">Tekan untuk Unggah</span> <span class="group-hover:text-gray-700 text-black">atau seret dan lepas</span>
                </span>
                <span class="mt-1 block text-xs text-gray-500 dark:text-neutral-500">
                    (Maks. ukuran file: 25 mb)
                </span>
            </label>

            <div class="mt-2 flex justify-center items-center border-t border-white dark:border-neutral-700"></div>

            <!-- Progress Upload -->

            <div id="uploading-section" class="hidden mt-4">

                <div class="py-1 flex flex-col text-xs font-medium">
                    1 file sedang diunggah...
                </div>

                <div class="px-2 py-2 flex flex-col bg-gray-100 border shadow-sm rounded-xl dark:bg-neutral-800 dark:border-neutral-700">
                    <div class="mb-2 flex justify-between items-center">
                        <div class="flex items-center gap-x-3">
                            <span class="size-8 flex justify-center items-center border border-gray-200 text-gray-500 rounded-lg dark:border-neutral-700 dark:text-neutral-500">
                                <svg class="flex-shrink-0 size-5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                    <polyline points="17 8 12 3 7 8"></polyline>
                                    <line x1="12" x2="12" y1="3" y2="15"></line>
                                </svg>
                            </span>
                            <div>
                                <p id="file-name" class="text-sm font-medium text-[#24A793] dark:text-white"></p>
                                <p id="file-size" class="text-xs font-medium text-gray-500 dark:text-neutral-500"></p>
                            </div>
                        </div>
                        <div class="inline-flex items-center gap-x-2">
                            <a id="trash-icon" class="text-gray-500 hover:text-gray-800 dark:text-neutral-500 dark:hover:text-neutral-200" href="#">
                                <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M3 6h18"></path>
                                    <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                                    <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                                    <line x1="10" x2="10" y1="11" y2="17"></line>
                                    <line x1="14" x2="14" y1="11" y2="17"></line>
                                </svg>
                            </a>
                        </div>
                    </div>

                    <div class="flex w-full h-2 bg-gray-200 rounded-full overflow-hidden dark:bg-neutral-700" role="progressbar" aria-valuenow="1" aria-valuemin="0" aria-valuemax="100">
                        <div id="progress-bar" class="flex flex-col justify-center rounded-full overflow-hidden bg-[#24A793] text-xs text-white text-center whitespace-nowrap transition duration-500 dark:bg-teal-500" style="width: 1%"></div>
                    </div>
                </div>
            </div>
            <!-- Progress Upload -->

            <div class="mt-2 pt-5 flex justify-center items-center border-t border-gray-200 dark:border-neutral-700">
                <div class="gap-x-2 inline-flex justify-center w-full">
                    <a class="py-2 px-14 sm:px-24 inline-flex justify-center items-center gap-2 rounded-lg border font-medium bg-white text-gray-700 shadow-sm align-middle hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-white focus:ring-teal-600 transition-all text-sm dark:bg-neutral-900 dark:hover:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-400 dark:hover:text-white dark:focus:ring-offset-gray-800" href="#">
                        Batal
                    </a>
                    <button class="py-2 px-14 sm:px-24 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-[#0A2D27] text-white hover:bg-[#839592] disabled:opacity-50 disabled:pointer-events-none">
                        Kirim
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function uploadFile() {
        const fileInput = document.getElementById('af-submit-app-upload-images');
        const file = fileInput.files[0];

        if (!file) return;

        // Show uploading section
        document.getElementById('uploading-section').classList.remove('hidden');
        document.getElementById('file-name').innerText = file.name;
        document.getElementById('file-size').innerText = (file.size / 1024).toFixed(2) + ' KB';

        const formData = new FormData();
        formData.append('af-submit-app-upload-images', file);

        const xhr = new XMLHttpRequest();
        xhr.open('POST', '/upload-endpoint'); // Change this to your actual upload endpoint
        xhr.upload.onprogress = function(event) {
            if (event.lengthComputable) {
                const percentComplete = (event.loaded / event.total) * 100;
                document.getElementById('progress-bar').style.width = percentComplete + '%';
            }
        };

        xhr.send(formData);
    }

    // Function to delete the uploaded file
    function deleteFile() {
        // Remove the uploaded file from the input
        document.getElementById('af-submit-app-upload-images').value = '';

        // Hide the uploading section
        document.getElementById('uploading-section').classList.add('hidden');
    }

    // Attach event listener to the trash bin icon
    document.getElementById('trash-icon').addEventListener('click', deleteFile);
</script>

<?= $this->endSection(); ?>