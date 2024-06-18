<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>


<!-- Card Blog -->
<div class=" px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto">
    <!-- Grid -->
    <div class="grid sm:grid-cols-2 gap-6">
        <!-- Card -->
        <div class="h-72 flex flex-col justify-center items-center border-2 bg-[#FDFDFD] rounded-xl relative overflow-hidden">
            <!-- SVG for the blur effect -->

            <svg class="absolute bottom-0 right-0 w-full h-48" viewBox="0 0 432 170" fill="none" xmlns="http://www.w3.org/2000/svg">
                <g filter="url(#filter0_f_1725_22124)">
                    <ellipse cx="350" cy="223.5" rx="200" ry="73.5" fill="#83ECDC" />
                </g>
                <defs>
                    <filter id="filter0_f_1725_22124" x="0" y="0" width="700" height="447" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                        <feFlood flood-opacity="0" result="BackgroundImageFix" />
                        <feBlend mode="normal" in="SourceGraphic" in2="BackgroundImageFix" result="shape" />
                        <feGaussianBlur stdDeviation="75" result="effect1_foregroundBlur_1725_22124" />
                    </filter>
                </defs>
            </svg>

            <svg xmlns="http://www.w3.org/2000/svg" width="51" height="50" viewBox="0 0 51 50" fill="none">
                <rect x="8.49988" y="8" width="34" height="34" rx="6" fill="#ACF2E7" />
                <path d="M19.25 33.3335C21.0208 34.646 23.1771 35.4168 25.5 35.4168C27.8229 35.4168 29.9792 34.646 31.75 33.3335" stroke="#24A793" stroke-width="2" stroke-linecap="round" />
                <path d="M33.3333 21.875C33.3333 22.653 33.1222 23.3347 32.8071 23.8074C32.4897 24.2835 32.1061 24.5 31.75 24.5C31.3939 24.5 31.0103 24.2835 30.6928 23.8074C30.3778 23.3347 30.1666 22.653 30.1666 21.875C30.1666 21.097 30.3778 20.4153 30.6928 19.9426C31.0103 19.4665 31.3939 19.25 31.75 19.25C32.1061 19.25 32.4897 19.4665 32.8071 19.9426C33.1222 20.4153 33.3333 21.097 33.3333 21.875Z" fill="#24A793" stroke="#24A793" />
                <path d="M20.8333 21.875C20.8333 22.653 20.6222 23.3347 20.3071 23.8074C19.9897 24.2835 19.6061 24.5 19.25 24.5C18.8939 24.5 18.5103 24.2835 18.1928 23.8074C17.8778 23.3347 17.6666 22.653 17.6666 21.875C17.6666 21.097 17.8778 20.4153 18.1928 19.9426C18.5103 19.4665 18.8939 19.25 19.25 19.25C19.6061 19.25 19.9897 19.4665 20.3071 19.9426C20.6222 20.4153 20.8333 21.097 20.8333 21.875Z" fill="#24A793" stroke="#24A793" />
                <path d="M46.1665 29C46.1665 36.8563 46.1665 40.7854 43.7249 43.225C41.2853 45.6667 37.3561 45.6667 29.4999 45.6667M21.1665 45.6667C13.3103 45.6667 9.38113 45.6667 6.94154 43.225C4.49988 40.7854 4.49988 36.8563 4.49988 29M21.1665 4C13.3103 4 9.38113 4 6.94154 6.44167C4.49988 8.88125 4.49988 12.8104 4.49988 20.6667M29.4999 4C37.3561 4 41.2853 4 43.7249 6.44167C46.1665 8.88125 46.1665 12.8104 46.1665 20.6667" stroke="#0A2D27" stroke-width="2" stroke-linecap="round" />
            </svg>

            <div class="flex justify-center items-center z-10">
                <div class="w-full md:w-auto sm:w-auto lg:w-auto">
                  
                        <!-- Hidden input fields for latitude and longitude -->
                        <input type="hidden" id="latitude" name="latitude">
                        <input type="hidden" id="longitude" name="longitude">
                        <button type="button" id="authButton" class="py-2 px-12 sm:px-6 inline-flex justify-center items-center gap-2 rounded-lg border font-medium bg-[#0A2D27] text-[#ACF2E7] shadow-sm align-middle hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-white focus:ring-blue-600 transition-all text-sm dark:bg-neutral-900 dark:hover:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-400 dark:hover:text-white dark:focus:ring-offset-gray-800" >
                            <a href="/absenmasuk/<?php echo session('user_specific_data')['pegawai'] ?>">
                            Absen masuk
                            </a>
                        </button>
                </div>
            </div>
        </div>


        <!-- End Card -->

        <!-- Card -->
        <div class="h-72 flex flex-col justify-center items-center border-2 bg-[#FDFDFD] rounded-xl relative overflow-hidden">

            <!-- SVG for the blur effect -->

            <svg class="absolute bottom-0 right-0 w-full h-48" viewBox="0 0 432 170" fill="none" xmlns="http://www.w3.org/2000/svg">
                <g filter="url(#filter0_f_1725_22127)">
                    <ellipse cx="350" cy="223.5" rx="200" ry="73.5" fill="#26B29D" />
                </g>
                <defs>
                    <filter id="filter0_f_1725_22127" x="0" y="0" width="700" height="447" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                        <feFlood flood-opacity="0" result="BackgroundImageFix" />
                        <feBlend mode="normal" in="SourceGraphic" in2="BackgroundImageFix" result="shape" />
                        <feGaussianBlur stdDeviation="75" result="effect1_foregroundBlur_1725_22127" />
                    </filter>
                </defs>
            </svg>

            <svg width="44" height="44" viewBox="0 0 44 44" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M42.6665 26C42.6665 33.8563 42.6665 37.7854 40.2249 40.225C37.7853 42.6667 33.8561 42.6667 25.9999 42.6667M17.6665 42.6667C9.8103 42.6667 5.88113 42.6667 3.44154 40.225C0.999878 37.7854 0.999878 33.8563 0.999878 26M17.6665 1C9.8103 1 5.88113 1 3.44154 3.44167C0.999878 5.88125 0.999878 9.81042 0.999878 17.6667M25.9999 1C33.8561 1 37.7853 1 40.2249 3.44167C42.6665 5.88125 42.6665 9.81042 42.6665 17.6667" stroke="#272727" stroke-width="2" stroke-linecap="round" />
                <path d="M18.4801 36.2501H25.5164C30.458 36.2501 32.9296 36.2501 34.7045 35.0863C35.4703 34.5844 36.1299 33.9366 36.6457 33.18C37.8316 31.4383 37.8316 29.0111 37.8316 24.1597C37.8316 19.3068 37.8316 16.8812 36.6457 15.1395C36.13 14.3828 35.4704 13.7351 34.7045 13.2332C33.5645 12.4842 32.1363 12.2167 29.9497 12.1217C28.9063 12.1217 28.0086 11.3458 27.8043 10.3404C27.6482 9.60395 27.2427 8.94396 26.6562 8.472C26.0697 8.00003 25.3382 7.74502 24.5854 7.75007H19.4111C17.8468 7.75007 16.4993 8.83466 16.1922 10.3404C15.9879 11.3458 15.0902 12.1217 14.0468 12.1217C11.8618 12.2167 10.4336 12.4858 9.292 13.2332C8.52665 13.7352 7.86759 14.3829 7.35242 15.1395C6.16492 16.8812 6.16492 19.3068 6.16492 24.1597C6.16492 29.0111 6.16492 31.4367 7.35083 33.18C7.86383 33.9337 8.5225 34.5812 9.292 35.0863C11.0669 36.2501 13.5385 36.2501 18.4801 36.2501Z" fill="#ACF2E7" />
                <path d="M30.7954 17.6807C30.6236 17.6793 30.4533 17.7116 30.294 17.776C30.1348 17.8404 29.9898 17.9355 29.8673 18.0559C29.7448 18.1763 29.6473 18.3197 29.5802 18.4778C29.5132 18.6359 29.4779 18.8057 29.4764 18.9775C29.4764 19.6931 30.067 20.2726 30.7954 20.2726H32.5544C33.2828 20.2726 33.8749 19.6916 33.8749 18.9775C33.8735 18.8056 33.8382 18.6357 33.771 18.4775C33.7038 18.3192 33.6061 18.1758 33.4835 18.0554C33.3609 17.9349 33.2157 17.8399 33.0563 17.7756C32.8968 17.7113 32.7263 17.6791 32.5544 17.6807H30.7954Z" fill="#24A793" />
                <path fill-rule="evenodd" clip-rule="evenodd" d="M21.9984 17.6807C18.3567 17.6807 15.4022 20.5813 15.4022 24.1581C15.4022 27.7364 18.3551 30.6355 22 30.6355C25.6416 30.6355 28.5961 27.7364 28.5961 24.1597C28.5961 20.5813 25.6432 17.6807 22 17.6807M22 20.2726C19.815 20.2726 18.0416 22.0127 18.0416 24.1581C18.0416 26.3051 19.815 28.0452 22 28.0452C24.1866 28.0452 25.9583 26.3051 25.9583 24.1581C25.9583 22.0127 24.1866 20.2726 22 20.2726Z" fill="#24A793" />
            </svg>

            <div class="flex justify-center items-center z-10">
                <div class="w-full md:w-auto sm:w-auto lg:w-auto">
                    <a class="py-2 px-12 sm:px-6 inline-flex justify-center items-center gap-2 rounded-lg border font-medium bg-[#0A2D27] text-[#ACF2E7] shadow-sm align-middle hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-white focus:ring-blue-600 transition-all text-sm" href="/swafoto">
                        Absen pulang
                    </a>
                </div>
            </div>


        </div>

        <!-- End Card -->
    </div>
    <!-- End Grid -->
</div>
<!-- End Card Blog -->



<?= $this->endSection(); ?>