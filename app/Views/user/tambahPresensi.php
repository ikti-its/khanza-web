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

        <div class="relative flex justify-center overflow-hidden">
            <video class="rounded-xl" id="camera" autoplay width="100%"></video>
        </div>
        <canvas id="detectionCanvas" width="640" height="480" style="display: none;"></canvas>
    </div>
</div>
<script type="module">
    import {
        loadModels
    } from "./presensiJs/model.js";
    const video = document.getElementById('camera');
    const canvas = document.getElementById('detectionCanvas');
    const ctx = canvas.getContext('2d'); // 2D drawing context

    async function startCamera() {
        try {
            console.log('Loading camera...');

            // Request camera access with constraints (optional)
            const constraints = {
                video: {
                    width: 640,
                    height: 480
                }
            }; // Adjust constraints as needed

            const stream = await navigator.mediaDevices.getUserMedia(constraints);
            video.srcObject = stream;

            console.log('Camera loaded successfully');

            // Load face detection models (if not already loaded)
            await loadModels(); // Call the function from model.js

            // Function to detect faces and draw boxes (optional)
            const detectFace = async () => {
                const detections = await getFullFaceDescription(video);

                // Clear the canvas before drawing new detections
                ctx.clearRect(0, 0, canvas.width, canvas.height);

                detections.forEach(detection => {
                    const {
                        detection: {
                            box
                        }
                    } = detection;
                    // Draw a box around the detected face (optional)
                    ctx.strokeStyle = 'red';
                    ctx.lineWidth = 2;
                    ctx.strokeRect(box.x, box.y, box.width, box.height);
                });
            };

            // Detect faces periodically
            setInterval(detectFace, 100); // Adjust interval as needed

        } catch (err) {
            console.error('Error accessing camera:', err);
        }
    }

    // Call startCamera when the page loads
    window.addEventListener('DOMContentLoaded', startCamera);
</script>

<?= $this->endSection(); ?>