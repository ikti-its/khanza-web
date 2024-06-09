<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<!-- Table Section -->
<div class="overflow overflow-auto px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto">
    <!-- Card -->
    <div class="flex flex-col">
        <div class="-m-3.5 overflow-x-auto">
            <div class="p-1.5 w-full inline-block align-middle">
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden dark:bg-neutral-900 dark:border-neutral-700">
                    <div class="px-6 pt-4 md:flex md:justify-between dark:border-neutral-700">
                        <div class="grid gap-3 md:flex md:justify-between md:items-center">
                            <div class="sm:col-span-12">
                                <h2 class="text-lg font-semibold text-gray-800 dark:text-neutral-200">
                                    Manual Swafoto
                                </h2>
                                <p class="text-sm py-2">Pastikan wajah Anda ada di tengah frame</p>
                            </div>
                        </div>
                    </div>
                    <!-- Camera Frame -->
                    <div class="px-6 py-4">
                        <div class="border-2 border-dashed border-gray-400 rounded-lg h-96 flex justify-center items-center">
                            <video id="camera" class="rounded-lg h-full w-full object-cover"></video>
                        </div>
                    </div>
                    <!-- Buttons -->
                    <div class="py-4 flex gap-3 w-full justify-center items-center">
                        <a class="w-48 py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-black shadow-sm hover:bg-gray-600 disabled:opacity-50 disabled:pointer-events-none text-center" href="#">
                            Batal
                        </a>
                        <a class="w-48 py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-[#0A2D27] text-[#ACF2E7] shadow-sm hover:bg-gray-600 disabled:opacity-50 disabled:pointer-events-none text-center" href="#">
                            Contact Sales Team
                        </a>
                    </div>
                    <!-- End Buttons -->
                </div>
            </div>
        </div>
    </div>
    <!-- End Card -->
</div>
<!-- End Table Section -->

<!-- Add the script for camera access -->
<script>
    const video = document.getElementById('camera');

    // Access the camera
    if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
        navigator.mediaDevices.getUserMedia({ video: true }).then(function(stream) {
            video.srcObject = stream;
            video.play();
        }).catch(function(err) {
            console.error("Error accessing the camera: " + err);
        });
    } else {
        console.error("getUserMedia not supported by this browser.");
    }
</script>

<?= $this->endSection(); ?>
