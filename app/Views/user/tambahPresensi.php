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
        const pegawaiId = '<?= session()->get('user_specific_data')['pegawai']; ?>';
        const namaPegawai = '<?= session()->get('user_specific_data')['nama']; ?>';
        const apiUrl = '<?= $api_url; ?>'; // Get api_url from passed variable
        const jwtToken = '<?= session()->get('jwt_token'); ?>'; // Get JWT token from session

        const descriptions = [];
        try {
            // Fetch data from API to get the image URL
            const response = await fetch(`${apiUrl}/${pegawaiId}`, {
                headers: {
                    'Authorization': `Bearer ${jwtToken}`
                }
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();
            console.log(data);
            const imgUrl = data.data.foto; // Extract the image URL from the API response
            

            // Fetch the image using the obtained URL
            const imgResponse = await fetch(imgUrl);
            if (!imgResponse.ok) {
                throw new Error(`Failed to fetch image. Status: ${imgResponse.status}`);
            }

            const blob = await imgResponse.blob();
            const img = await faceapi.bufferToImage(blob);

            const detections = await faceapi.detectSingleFace(img).withFaceLandmarks().withFaceDescriptor();
            descriptions.push(detections.descriptor);
        } catch (error) {
            console.error(`Failed to load image for ${namaPegawai}:`, error);
        }
        return new faceapi.LabeledFaceDescriptors(namaPegawai, descriptions);
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