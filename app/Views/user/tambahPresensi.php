<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<div class="max-w-2xl px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto">
    <div class="bg-white rounded-xl shadow p-4 sm:p-7 dark:bg-neutral-900">
        <div class="text-center mb-8">
            <h2 class="text-2xl md:text-3xl font-bold text-gray-800 dark:text-neutral-200">
                Face Recognition
            </h2>
            <p class="text-sm text-gray-600 dark:text-neutral-400">
                Ensure your face is centered in the frame.
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
    </div>
</div>

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
                    overlay.width = displaySize.width;
                    overlay.height = displaySize.height;
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
