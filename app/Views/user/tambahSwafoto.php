<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<div class="max-w-2xl px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto">
    <div class="bg-white rounded-xl shadow p-4 sm:p-7 dark:bg-neutral-900">
        <div class="text-center mb-8">
            <h2 class="text-2xl md:text-3xl font-bold text-gray-800 dark:text-neutral-200">
                Swafoto
            </h2>
            <p class="text-sm text-gray-600 dark:text-neutral-400">
                Pastikan wajah Anda berada di tengah frame
            </p>
        </div>

        <!-- Camera Frame -->
        <div class="px-6 py-4">
            <div class="border-2 border-dashed border-gray-400 rounded-lg h-auto flex justify-center items-center relative">
                <video id="video" class="rounded-lg" autoplay muted></video>
                <canvas id="overlay" class="absolute"></canvas>
            </div>
        </div>
        <!-- End Camera Frame -->

        <div class="py-2 mb-2 mx-6 flex justify-center items-center">
            <!-- Buttons -->
            <div class="mt-6 flex justify-center gap-x-3 w-full">
                <button class="flex-1 py-2 px-3 inline-flex justify-center items-center gap-2 rounded-lg border font-medium bg-white text-gray-700 shadow-sm align-middle text-center hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-white focus:ring-teal-600 transition-all text-sm dark:bg-neutral-800 dark:hover:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-400 dark:hover:text-white dark:focus:ring-offset-gray-800">
                    Batal
                </button>
                <button id="capture-btn" data-hs-overlay="#hs-basic-modal" class=" flex-1 py-2 px-3 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-[#0A2D27] text-[#ACF2E7] text-center hover:bg-teal-700 disabled:opacity-50 disabled:pointer-events-none">
                    Gunakan Foto
                </button>

                <div id="hs-basic-modal" class="hs-overlay hs-overlay-open:opacity-100 hs-overlay-open:duration-500 hidden size-full fixed top-0 start-0 z-[80] opacity-0 overflow-x-hidden transition-all overflow-y-auto pointer-events-none">
                    <div class="sm:max-w-lg sm:w-full m-3 sm:mx-auto">
                        <div class="flex flex-col bg-white border shadow-sm rounded-xl pointer-events-auto dark:bg-neutral-800 dark:border-neutral-700 dark:shadow-neutral-700/70">
                            <div class="flex justify-between items-center py-3 px-4 border-b dark:border-neutral-700">
                                <h3 class="font-bold text-gray-800 dark:text-white">
                                    Hasil swafoto
                                </h3>
                                <button type="button" class="flex justify-center items-center size-7 text-sm font-semibold rounded-full border border-transparent text-gray-800 hover:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-neutral-700" data-hs-overlay="#hs-basic-modal">
                                    <span class="sr-only">Close</span>
                                    <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M18 6 6 18"></path>
                                        <path d="m6 6 12 12"></path>
                                    </svg>
                                </button>
                            </div>
                            <div class="p-4 overflow-y-auto">
                                <img id="photo-preview" class="rounded-lg">
                            </div>
                            <div class="flex justify-end items-center gap-x-2 py-3 px-4 border-t dark:border-neutral-700">
                                <button type="button" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-800" data-hs-overlay="#hs-basic-modal">
                                    Batal
                                </button>
                                <form id="photo-form" action="/submittambahabsenswafoto" method="post" enctype="multipart/form-data">
                                    <button id="save-changes-btn" type="submit" class="flex-1 py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-[#0A2D27] text-[#ACF2E7] hover:bg-teal-700 disabled:opacity-50 disabled:pointer-events-none">
                                        Simpan
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Buttons -->
        </div>

    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const video = document.getElementById('video');
        const canvas = document.getElementById('overlay');
        const photoPreview = document.getElementById('photo-preview');
        const captureButton = document.getElementById('capture-btn');
        const saveChangesButton = document.getElementById('save-changes-btn');
        const photoForm = document.getElementById('photo-form');
        const hiddenInput = document.createElement('input');

        hiddenInput.type = 'hidden';
        hiddenInput.name = 'photo';
        photoForm.appendChild(hiddenInput);

        let stream = null;
        let photo = null;

        // Access webcam
        navigator.mediaDevices.getUserMedia({
                video: true
            })
            .then(function(mediaStream) {
                video.srcObject = mediaStream;
                stream = mediaStream;
            })
            .catch(function(err) {
                console.log("Unable to access webcam: " + err);
            });

        captureButton.addEventListener('click', function() {
            const context = canvas.getContext('2d');
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            context.drawImage(video, 0, 0, canvas.width, canvas.height);

            // Convert canvas to image data
            photo = canvas.toDataURL('image/png');

            // Display captured photo in modal preview
            photoPreview.src = photo;
            photoPreview.classList.remove('hidden');

            // Clear the canvas
            context.clearRect(0, 0, canvas.width, canvas.height);

            // Log the value of photo to console
            // console.log('Value of photo:', photo);
        });

        saveChangesButton.addEventListener('click', function() {
            if (photo) {
                // Set the value of hidden input field with the base64 data
                hiddenInput.value = photo;

                console.log('Base64 data set to hidden input:', photo);
            } else {
                console.log("No photo captured yet.");
            }
        });
    });
</script>

<?= $this->endSection(); ?>
