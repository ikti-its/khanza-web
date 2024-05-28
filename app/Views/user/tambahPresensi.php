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
        <div class="relative flex justify-center bg-blue-50 overflow-hidden">
            <!-- Video Stream -->
            <video id="camera" autoplay class="w-full"></video>
            <!-- Scanning Animation -->
            <div id="scanAnimation" class="absolute top-0 left-0 w-full h-1 bg-blue-300"></div>
        </div>
        <!-- End Camera Display -->

        <!-- Buttons -->
        <div class="mt-5 flex justify-end gap-x-2">
            <button id="cancelBtn" type="button" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-800">
                Cancel
            </button>
            <button id="saveBtn" type="button" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none">
                Save changes
            </button>
        </div>
        <!-- End Buttons -->
    </div>
    <!-- End Card -->
</div>
<!-- End Card Section -->

<?= $this->endSection(); ?>

<!-- JavaScript to access the user's camera and handle animations -->
<script>
    const video = document.getElementById('camera');
    const scanAnimation = document.getElementById('scanAnimation');
    const cancelBtn = document.getElementById('cancelBtn');
    const saveBtn = document.getElementById('saveBtn');

    // Function to start the camera and show the scanning animation
    async function startCamera() {
        try {
            // Get user media stream
            const stream = await navigator.mediaDevices.getUserMedia({ video: true });
            // Assign the stream to the video element
            video.srcObject = stream;
            // Show the scanning animation
            scanAnimation.style.display = 'block';
            // Start the scanning animation
            animateScan();
        } catch (err) {
            console.error('Error accessing camera:', err);
        }
    }

    // Function to stop the camera and hide the scanning animation
    function stopCamera() {
        const stream = video.srcObject;
        const tracks = stream.getTracks();

        tracks.forEach(track => {
            track.stop();
        });

        video.srcObject = null;
        // Hide the scanning animation
        scanAnimation.style.display = 'none';
    }

    // Function to animate the scanning animation
    function animateScan() {
        scanAnimation.style.top = '0';
        // Set the animation duration
        const animationDuration = 1500; // in milliseconds
        // Animate the scanning animation
        setTimeout(() => {
            scanAnimation.style.top = '100%';
            // Repeat the animation
            setTimeout(animateScan, animationDuration);
        }, animationDuration);
    }

    // Event listener for cancel button
    cancelBtn.addEventListener('click', stopCamera);

    // Event listener for save button
    saveBtn.addEventListener('click', () => {
        // You can add your save functionality here
    });

    // Start the camera when the page loads
    startCamera();
</script>
