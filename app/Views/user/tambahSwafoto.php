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
                        <div class="border-2 border-dashed border-gray-400 rounded-lg h-96 flex justify-center items-center relative">
                            <video id="video" class="rounded-lg object-cover" autoplay muted></video>
                            <canvas id="overlay" class="absolute top-0 left-0"></canvas>
                        </div>
                    </div>
                    <!-- End Camera Frame -->
                </div>
            </div>
        </div>
    </div>
    <!-- End Card -->
</div>
<!-- End Table Section -->

<!-- Include face-api.js -->
<script src="<?= base_url('/models/face-api.min.js') ?>"></script>
<script>
    const video = document.getElementById('video');
    const overlay = document.getElementById('overlay');
    const ctx = overlay.getContext('2d');

    Promise.all([
        faceapi.nets.tinyFaceDetector.loadFromUri('/models'),
        faceapi.nets.faceLandmark68Net.loadFromUri('/models'),
        faceapi.nets.faceRecognitionNet.loadFromUri('/models'),
        faceapi.nets.faceExpressionNet.loadFromUri('/models')
    ]).then(startVideo);

    function startVideo() {
        navigator.getUserMedia(
            { video: {} },
            stream => {
                video.srcObject = stream;
                video.addEventListener('play', () => {
                    const displaySize = { width: video.videoWidth, height: video.videoHeight };
                    overlay.width = video.videoWidth;
                    overlay.height = video.videoHeight;
                    overlay.style.width = video.videoWidth + 'px';
                    overlay.style.height = video.videoHeight + 'px';
                    faceapi.matchDimensions(overlay, displaySize);
                    setInterval(async () => {
                        const detections = await faceapi.detectAllFaces(video, new faceapi.TinyFaceDetectorOptions()).withFaceLandmarks().withFaceExpressions();
                        const resizedDetections = faceapi.resizeResults(detections, displaySize);
                        ctx.clearRect(0, 0, overlay.width, overlay.height);
                        faceapi.draw.drawDetections(overlay, resizedDetections);
                        faceapi.draw.drawFaceLandmarks(overlay, resizedDetections);
                        faceapi.draw.drawFaceExpressions(overlay, resizedDetections);
                    }, 100);
                });
            },
            err => console.error(err)
        );
    }
</script>

<?= $this->endSection(); ?>
