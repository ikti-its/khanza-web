<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<div class="max-w-2xl px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto">
    <div class="bg-white rounded-xl shadow p-4 sm:p-7 dark:bg-neutral-900">
        <div class="text-center mb-8">
            <h2 class="text-2xl md:text-3xl font-bold text-gray-800 dark:text-neutral-200">
                Face Recognition
            </h2>
            <p class="text-sm text-gray-600 dark:text-neutral-400">
                Pastikan wajah Anda berada di tengah frame <?= $kehadiran_data[0]['id_shift'] ?> <?= $kehadiran_data[0]['id_hari'] ?>
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

    <!-- Modal for recognized face -->
    <div id="recognizedModal" class="hidden z-20 fixed top-0 left-0 w-full h-full flex items-center justify-center bg-black bg-opacity-50 lg:pl-72">
        <div class="bg-white shadow-lg rounded-lg overflow-hidden max-w-sm mx-auto">
            <div class="px-6 py-4">
                <div class="flex justify-center mb-4">
                    <svg width="44" height="44" viewBox="0 0 44 44" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="4.99994" y="5" width="34" height="34" rx="6" fill="#ACF2E7" />
                        <path d="M15.75 30.3335C17.5208 31.646 19.6771 32.4168 22 32.4168C24.3229 32.4168 26.4792 31.646 28.25 30.3335" stroke="#24A793" stroke-width="2" stroke-linecap="round" />
                        <path d="M28.25 22C29.4006 22 30.3334 20.6009 30.3334 18.875C30.3334 17.1491 29.4006 15.75 28.25 15.75C27.0994 15.75 26.1667 17.1491 26.1667 18.875C26.1667 20.6009 27.0994 22 28.25 22Z" fill="#24A793" />
                        <path d="M15.75 22C16.9006 22 17.8334 20.6009 17.8334 18.875C17.8334 17.1491 16.9006 15.75 15.75 15.75C14.5994 15.75 13.6667 17.1491 13.6667 18.875C13.6667 20.6009 14.5994 22 15.75 22Z" fill="#24A793" />
                        <path d="M42.6666 26C42.6666 33.8563 42.6666 37.7854 40.2249 40.225C37.7854 42.6667 33.8562 42.6667 25.9999 42.6667M17.6666 42.6667C9.81036 42.6667 5.88119 42.6667 3.44161 40.225C0.999939 37.7854 0.999939 33.8563 0.999939 26M17.6666 1C9.81036 1 5.88119 1 3.44161 3.44167C0.999939 5.88125 0.999939 9.81042 0.999939 17.6667M25.9999 1C33.8562 1 37.7854 1 40.2249 3.44167C42.6666 5.88125 42.6666 9.81042 42.6666 17.6667" stroke="#0A2D27" stroke-width="2" stroke-linecap="round" />
                    </svg>
                </div>
                <p class="text-gray-700 text-center">Wajah Anda berhasil dikenali. Silakan lanjutkan.</p>
            </div>
            <form action="/submittambahabsenmasuk" method="POST" onsubmit="return validateForm()">
                <div class="px-6 py-4 bg-gray-100 text-right">
                    <a href="javascript:history.back()" class="py-2 px-3 inline-flex justify-center items-center gap-2 rounded-lg border font-medium bg-white text-gray-700 shadow-sm align-middle hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-white focus:ring-teal-600 transition-all text-sm dark:bg-neutral-800 dark:hover:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-400 dark:hover:text-white dark:focus:ring-offset-gray-800">
                        Batal
                    </a>
                    <button type="submit" id="submitButton" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-[#0A2D27] text-[#ACF2E7] hover:bg-teal-700 disabled:opacity-50 disabled:pointer-events-none">

                        Lanjutkan
                    </button>
                </div>
        </div>

        <input type="hidden" name="id_pegawai" id="id_pegawai" value="<?= $kehadiran_data[0]['id_pegawai'] ?>">
        <input type="hidden" id="id_jadwal" name="id_jadwal" placeholder="Selected Date" value="<?= $kehadiran_data[0]['id'] ?>" readonly>
        <input type="hidden" id="tanggal" name="tanggal" placeholder="Selected Date" value="<?= date('Y-m-d') ?>" readonly>
        <input type="hidden" id="foto" name="foto" value="<?= isset($foto_data['foto']) ? esc($foto_data['foto']) : '' ?>" readonly>
        </form>
    </div>

</div>

<script>
    const pegawaiId = '<?= session()->get('user_specific_data')['pegawai']; ?>';
    const namaPegawai = '<?= session()->get('user_specific_data')['nama']; ?>';
</script>

<!-- Include face-api.js -->
<script src="<?= base_url('/models/face-api.min.js') ?>"></script>

<script>
    // Function to close the recognized face modal
    function closeModal() {
        document.getElementById('recognizedModal').classList.add('hidden');
    }
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
                        // faceapi.draw.drawFaceLandmarks(overlay, resizedDetections);

                        // Using labeled face descriptors for face matching
                        if (labeledFaceDescriptors) {
                            const faceMatcher = new faceapi.FaceMatcher(labeledFaceDescriptors, 0.4);
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
                                    document.getElementById('recognizedModal').classList.remove('hidden');
                                    redirectionDone = true;
                                }
                            });
                        }
                    }, 100);
                });
            })
            .catch(handleCameraError);
    }

    function handleCameraError(error) {
        if (error.name === 'NotAllowedError' || error.name === 'PermissionDeniedError') {
            var alertDiv = document.createElement('div');
            alertDiv.className = 'z-20 fixed top-0 left-0 w-full h-full flex items-center justify-center bg-black bg-opacity-50 lg:pl-72';

            alertDiv.innerHTML = `
            <div id="cameraDeniedModal" class="bg-white shadow-lg rounded-lg overflow-hidden max-w-sm mx-auto">
                <div class="px-6 py-4">
                    <div class="text-center mb-4">
                        <span class="text-red-500 text-4xl">&#x26D4;</span>
                        <h2 class="text-xl font-bold text-gray-800">Akses Kamera Ditolak</h2>
                    </div>
                    <p class="text-gray-700 text-center">Anda telah menolak akses ke kamera Anda. Harap aktifkan akses kamera untuk menggunakan fitur ini.</p>
                </div>
                <div class="px-6 py-4 bg-gray-100 text-right">
                    <a href="/dashboard" type="button" class="py-2 px-4 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors">Batal</a>
                </div>
            </div>
        `;

            document.body.appendChild(alertDiv);

            alertDiv.scrollIntoView({
                behavior: 'smooth'
            });
        } else {
            console.error('Error accessing webcam:', error);
        }
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


    function validateForm() {
        var submitButton = document.getElementById('submitButton');
        submitButton.setAttribute('disabled', true);
        submitButton.innerHTML = 'Mengajukan...';
    }
</script>

<?= $this->endSection(); ?>