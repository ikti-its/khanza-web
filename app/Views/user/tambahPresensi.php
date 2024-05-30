<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<!-- Card Section -->
<div class="max-w-2xl px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto">
    <!-- Card -->
    <div class="bg-white rounded-xl shadow p-4 sm:p-7 dark:bg-neutral-900">
        <div class="text-center mb-8">
            <h2 class="text-2xl md:text-3xl font-bold text-gray-800 dark:text-neutral-200">
                Face Recognition
            </h2>
            <p class="text-sm text-gray-600 dark:text-neutral-400">
                Pastikan wajah Anda ada di tengah frame
            </p>
        </div>

        <!-- Camera Display -->
        <div class="relative flex justify-center overflow-hidden">
            <!-- Video Stream -->
            <video class="rounded-xl" id="camera" autoplay class="w-full"></video>
        </div>
        <!-- End Camera Display -->

    </div>
    <!-- End Card -->

    
    <!-- JavaScript -->
    <script>
        const video = document.getElementById('camera');
        const scanAnimation = document.getElementById('scanAnimation');

        async function startCamera() {
            try {
                const stream = await navigator.mediaDevices.getUserMedia({ video: true });
                video.srcObject = stream;
                // Start the scanning animation
                startScanAnimation();
            } catch (err) {
                console.error('Error accessing camera:', err);
            }
        }

        // Function to start the scanning animation
        function startScanAnimation() {
            scanAnimation.style.animation = 'scan 2s linear infinite';
        }

        // Call the function to start the camera when the page loads
        window.addEventListener('DOMContentLoaded', startCamera);
    </script>

    


    <?= $this->endSection(); ?>
