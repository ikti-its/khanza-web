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

<script>
    const pegawaiId = '<?= session()->get('user_specific_data')['pegawai']; ?>';
    const namaPegawai = '<?= session()->get('user_specific_data')['nama']; ?>';
</script>

<!-- Include face-api.js -->
<script src="<?= base_url('/models/face-api.min.js') ?>"></script>
<script>
    const video = document.getElementById('video');
    const overlay = document.getElementById('overlay');
    const ctx = overlay.getContext('2d');

    // Define a flag to track if redirection has occurred
    let redirectionDone = false;


    // Define a function to handle redirection after face recognition
    function redirectToHalamanPresensi() {
        window.location.href = '/absenmasuk/' + pegawaiId; // Replace with your actual URL
    }

    // Function to start webcam and face detection
    function startWebcam() {
        navigator.mediaDevices.getUserMedia({
                video: true,
                audio: false
            })
            .then((stream) => {
                video.srcObject = stream;
                video.addEventListener('play', () => {
                    const displaySize = {
                        width: video.videoWidth,
                        height: video.videoHeight
                    };
                    overlay.width = displaySize.width;
                    overlay.height = displaySize.height;
                    faceapi.matchDimensions(overlay, displaySize);

                    // Interval to continuously detect faces and draw results
                    setInterval(async () => {
                        const detections = await faceapi.detectAllFaces(video).withFaceLandmarks().withFaceDescriptors();
                        const resizedDetections = faceapi.resizeResults(detections, displaySize);
                        ctx.clearRect(0, 0, overlay.width, overlay.height);
                        faceapi.draw.drawDetections(overlay, resizedDetections);
                        faceapi.draw.drawFaceLandmarks(overlay, resizedDetections);

                        // Using labeled face descriptors for face matching
                        if (labeledFaceDescriptors) {
                            const faceMatcher = new faceapi.FaceMatcher(labeledFaceDescriptors, 0.6);
                            const results = resizedDetections.map(d => faceMatcher.findBestMatch(d.descriptor));

                            results.forEach((result, i) => {
                                const box = resizedDetections[i].detection.box;
                                const text = result.toString();
                                const drawBox = new faceapi.draw.DrawBox(box, {
                                    label: text
                                });
                                drawBox.draw(overlay);

                                // Check if recognized face matches namaPegawai
                                if (result.label === namaPegawai && redirectionDone === false) {
                                    // Redirect to halamanpresensi
                                    console.log('sesuai bro');
                                    redirectToHalamanPresensi();
                                    redirectionDone = true;
                                }
                            });
                        }
                    }, 100);
                });
            })
            .catch((error) => {
                console.error('Error accessing webcam:', error);
            });
    }

    let labeledFaceDescriptors;

    // Function to load labeled face descriptors from images
    async function loadLabeledImages() {
        const foto_data = <?= json_encode($foto_data); ?>;
        const pegawaiId = '<?= $pegawaiId ?>';
        const namaPegawai = '<?= $namaPegawai ?>';

        try {
            const fotoUrl = foto_data;

            // Fetch the image using the obtained URL
            const imgResponse = await fetch(fotoUrl);
            if (!imgResponse.ok) {
                throw new Error(`Failed to fetch image. Status: ${imgResponse.status}`);
            }

            const blob = await imgResponse.blob();
            const img = await faceapi.bufferToImage(blob);

            const detections = await faceapi.detectSingleFace(img).withFaceLandmarks().withFaceDescriptor();
            const descriptions = detections ? [detections.descriptor] : [];

            return new faceapi.LabeledFaceDescriptors(namaPegawai, descriptions);
        } catch (error) {
            console.error(`Failed to load image for ${namaPegawai}:`, error);
            throw error; // Rethrow the error to handle it further up the call stack if needed
        }
    }



    // Start loading labeled face descriptors and start webcam after loading
    Promise.all([
        faceapi.nets.ssdMobilenetv1.loadFromUri('<?= base_url('/models') ?>'),
        faceapi.nets.faceRecognitionNet.loadFromUri('<?= base_url('/models') ?>'),
        faceapi.nets.faceLandmark68Net.loadFromUri('<?= base_url('/models') ?>')
    ]).then(() => {
        loadLabeledImages().then(descriptors => {
            labeledFaceDescriptors = descriptors;
            startWebcam(); // Start webcam after loading descriptors
        }).catch(err => console.error('Error loading labeled face descriptors:', err));
    }).catch(err => console.error('Error loading face detection models:', err));
</script>

<?= $this->endSection(); ?>