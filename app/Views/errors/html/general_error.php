<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= base_url() ?>css/style.css?v=1.0">
    <!-- Flatpickr CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <!-- Include TensorFlow.js library -->
    <script src="https://cdn.jsdelivr.net/npm/@tensorflow/tfjs"></script>
    

    

    <title><?= $title ?></title>
</head>

<body>
    <div class="container mx-auto ml-0">
        <div class="w-screen h-screen">
        <div class="min-h-screen bg-gradient-to-b from-white via-[#D6F9F3] to-[#24A793] flex flex-col justify-center items-center relative">
        <div class="text-center z-10 pb-16">
        <h1 class="text-6xl font-bold text-black mb-4"><?=esc($kode)?></h1>
            <h1 class="text-6xl font-bold text-black mb-4"><?= esc($errorTitle) ?></h1>
            <p class="text-2xl text-black"><?= esc($message) ?></p>
        </div>

        <svg class="w-full h-auto absolute bottom-0" viewBox="0 0 1440 346" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M720 1000C1117.64 1000 1440 776.814 1440 501.5C1440 226.186 1117.64 3 720 3C322.355 3 0 226.186 0 501.5C0 776.814 322.355 1000 720 1000Z" stroke="url(#paint0_linear_529_2237)" stroke-width="5" stroke-linecap="round" stroke-linejoin="round" />
            <path d="M720 960.327C1086 960.327 1382.7 754.903 1382.7 501.5C1382.7 248.096 1086 42.6724 720 42.6724C354.001 42.6724 57.3008 248.096 57.3008 501.5C57.3008 754.903 354.001 960.327 720 960.327Z" stroke="url(#paint1_linear_529_2237)" stroke-width="5" stroke-linecap="round" stroke-linejoin="round" />
            <path d="M720 920.641C1054.34 920.641 1325.38 732.985 1325.38 501.501C1325.38 270.016 1054.34 82.3608 720 82.3608C385.659 82.3608 114.622 270.016 114.622 501.501C114.622 732.985 385.659 920.641 720 920.641Z" stroke="url(#paint2_linear_529_2237)" stroke-width="5" stroke-linecap="round" stroke-linejoin="round" />
            <path d="M720.001 880.966C1022.7 880.966 1268.08 711.072 1268.08 501.498C1268.08 291.924 1022.7 122.031 720.001 122.031C417.306 122.031 171.923 291.924 171.923 501.498C171.923 711.072 417.306 880.966 720.001 880.966Z" stroke="url(#paint3_linear_529_2237)" stroke-width="5" stroke-linecap="round" stroke-linejoin="round" />
            <path d="M720.001 841.282C991.037 841.282 1210.76 689.157 1210.76 501.502C1210.76 313.846 991.037 161.722 720.001 161.722C448.964 161.722 229.245 313.846 229.245 501.502C229.245 689.157 448.964 841.282 720.001 841.282Z" stroke="url(#paint4_linear_529_2237)" stroke-width="5" stroke-linecap="round" stroke-linejoin="round" />
            <path d="M720 801.606C959.39 801.606 1153.45 667.244 1153.45 501.499C1153.45 335.754 959.39 201.392 720 201.392C480.609 201.392 286.545 335.754 286.545 501.499C286.545 667.244 480.609 801.606 720 801.606Z" stroke="url(#paint5_linear_529_2237)" stroke-width="5" stroke-linecap="round" stroke-linejoin="round" />
            <path d="M720 761.92C927.733 761.92 1096.13 645.326 1096.13 501.5C1096.13 357.674 927.733 241.08 720 241.08C512.268 241.08 343.867 357.674 343.867 501.5C343.867 645.326 512.268 761.92 720 761.92Z" stroke="url(#paint6_linear_529_2237)" stroke-width="5" stroke-linecap="round" stroke-linejoin="round" />
            <path d="M720 722.247C896.086 722.247 1038.83 623.415 1038.83 501.5C1038.83 379.584 896.086 280.752 720 280.752C543.913 280.752 401.167 379.584 401.167 501.5C401.167 623.415 543.913 722.247 720 722.247Z" stroke="url(#paint7_linear_529_2237)" stroke-width="5" stroke-linecap="round" stroke-linejoin="round" />
            <defs>
                <linearGradient id="paint0_linear_529_2237" x1="720" y1="3" x2="720" y2="1000" gradientUnits="userSpaceOnUse">
                    <stop stop-color="#ACF2E7" />
                    <stop offset="1" stop-color="#1D8676" />
                </linearGradient>
                <linearGradient id="paint1_linear_529_2237" x1="720" y1="42.6724" x2="720" y2="960.327" gradientUnits="userSpaceOnUse">
                    <stop stop-color="#ACF2E7" />
                    <stop offset="1" stop-color="#1D8676" />
                </linearGradient>
                <linearGradient id="paint2_linear_529_2237" x1="720" y1="82.3608" x2="720" y2="920.641" gradientUnits="userSpaceOnUse">
                    <stop stop-color="#ACF2E7" />
                    <stop offset="1" stop-color="#1D8676" />
                </linearGradient>
                <linearGradient id="paint3_linear_529_2237" x1="720.001" y1="122.031" x2="720.001" y2="880.966" gradientUnits="userSpaceOnUse">
                    <stop stop-color="#ACF2E7" />
                    <stop offset="1" stop-color="#1D8676" />
                </linearGradient>
                <linearGradient id="paint4_linear_529_2237" x1="720.001" y1="161.722" x2="720.001" y2="841.282" gradientUnits="userSpaceOnUse">
                    <stop stop-color="#ACF2E7" />
                    <stop offset="1" stop-color="#1D8676" />
                </linearGradient>
                <linearGradient id="paint5_linear_529_2237" x1="720" y1="201.392" x2="720" y2="801.606" gradientUnits="userSpaceOnUse">
                    <stop stop-color="#ACF2E7" />
                    <stop offset="1" stop-color="#1D8676" />
                </linearGradient>
                <linearGradient id="paint6_linear_529_2237" x1="720" y1="241.08" x2="720" y2="761.92" gradientUnits="userSpaceOnUse">
                    <stop stop-color="#ACF2E7" />
                    <stop offset="1" stop-color="#1D8676" />
                </linearGradient>
                <linearGradient id="paint7_linear_529_2237" x1="720" y1="280.752" x2="720" y2="722.247" gradientUnits="userSpaceOnUse">
                    <stop stop-color="#ACF2E7" />
                    <stop offset="1" stop-color="#1D8676" />
                </linearGradient>
            </defs>
        </svg>

        <div class="flex justify-center items-center z-10">
                <div class="w-full md:w-auto sm:w-auto lg:w-auto">
                    <a class="py-2 px-12 sm:px-6 inline-flex justify-center items-center gap-2 rounded-lg border font-medium bg-[#0A2D27] text-[#ACF2E7] shadow-sm align-middle hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-white focus:ring-teal-600 transition-all text-sm dark:bg-neutral-900 dark:hover:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-400 dark:hover:text-white dark:focus:ring-offset-gray-800" href="/dashboard">
                        Kembali ke Beranda
                    </a>
                </div>
            </div>


    </div>
        </div>

        <!-- Flatpickr JS -->
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
        <script src="<?= base_url('/css/preline/dist/preline.js') ?>"></script>
    </div>


</body>

</html>