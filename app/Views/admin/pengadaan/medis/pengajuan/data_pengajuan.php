<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<!-- Table Section -->
<div class="max-w-[85rem] w-full py-6 mx-auto">
    <div class="px-4 mb-4">
        <!-- breadcrumbs -->
    </div>

    <!-- Card -->
    <div class="flex flex-col">
        <div class="-m-1.5 overflow-y-auto">
            <div class="sm:px-6 min-w-full inline-block align-middle">
                <!-- Group Card    -->
                <div class="container mx-auto">
                    <div class="my-3 font-bold flex md:justify-between items-center">
                        <div>
                            Dashboard
                        </div>
                        <div class="flex items-center">
                            <button id="prevButton" type="button" class="mx-2 bg-white rounded-full p-1" onclick="prevCards()">
                                <span class="text-2xl" aria-hidden="true">
                                    <span class="sr-only">Previous</span>
                                    <svg class="flex-shrink-0 size-5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="m15 18-6-6 6-6"></path>
                                    </svg>
                                </span>
                            </button>

                            <button id="nextButton" type="button" class="bg-white rounded-full p-1" onclick="nextCards()">
                                <span class="sr-only">Next</span>
                                <span class="text-2xl" aria-hidden="true">
                                    <svg class="flex-shrink-0 size-5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="m9 18 6-6-6-6"></path>
                                    </svg>
                                </span>
                            </button>
                        </div>
                    </div>

                    <div id="cardContainer" class="grid lg:grid-cols-4 grid-cols-2 gap-3">
                        <a class="h-[150px] flex flex-col shadow-sm rounded-xl bg-white hover:shadow-lg dark:bg-neutral-900 dark:border-neutral-700 dark:shadow-neutral-700/70 relative" href="#">
                            <div class="m-[20px] absolute z-[1] w-10 h-10 flex-shrink-0 rounded-md bg-white shadow-sm flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path d="M12 19H5C4.46957 19 3.96086 18.7893 3.58579 18.4142C3.21071 18.0391 3 17.5304 3 17V6C3 5.46957 3.21071 4.96086 3.58579 4.58579C3.96086 4.21071 4.46957 4 5 4H9L12 7H19C19.5304 7 20.0391 7.21071 20.4142 7.58579C20.7893 7.96086 21 8.46957 21 9V12.5M19 22V16M19 16L22 19M19 16L16 19" stroke="#0D97D1" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>
                            <div class="absolute inset-0">
                                <svg xmlns="http://www.w3.org/2000/svg" width="193" height="150" viewBox="0 0 193 150" fill="none">
                                    <g filter="url(#filter0_f_1535_12483)">
                                        <path d="M55 0L102.631 82.5H7.3686L55 0Z" fill="#1DBBFF" />
                                    </g>
                                    <defs>
                                        <filter id="filter0_f_1535_12483" x="-82.6313" y="-90" width="275.263" height="262.5" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                                            <feFlood flood-opacity="0" result="BackgroundImageFix" />
                                            <feBlend mode="normal" in="SourceGraphic" in2="BackgroundImageFix" result="shape" />
                                            <feGaussianBlur stdDeviation="45" result="effect1_foregroundBlur_1535_12483" />
                                        </filter>
                                    </defs>
                                </svg>
                            </div>
                            <div class="flex flex-col mt-auto p-4 md:p-5">
                                <p class="tracking-wide">Total Pengajuan</p>
                                <p class="font-bold">22</p>
                            </div>
                        </a>
                        <a class="h-[150px] flex flex-col shadow-sm rounded-xl bg-white hover:shadow-lg dark:bg-neutral-900 dark:border-neutral-700 dark:shadow-neutral-700/70 relative" href="#">
                            <div class="m-[20px] absolute z-[1] w-10 h-10 flex-shrink-0 rounded-md bg-white shadow-sm flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path d="M11 19H5C4.46957 19 3.96086 18.7893 3.58579 18.4142C3.21071 18.0391 3 17.5304 3 17V6C3 5.46957 3.21071 4.96086 3.58579 4.58579C3.96086 4.21071 4.46957 4 5 4H9L12 7H19C19.5304 7 20.0391 7.21071 20.4142 7.58579C20.7893 7.96086 21 8.46957 21 9V13M15 19L17 21L21 17" stroke="#1D8676" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>
                            <div class="absolute inset-0">
                                <svg xmlns="http://www.w3.org/2000/svg" width="193" height="150" viewBox="0 0 193 150" fill="none">
                                    <g filter="url(#filter0_f_1535_15313)">
                                        <path d="M55 0L102.631 82.5H7.3686L55 0Z" fill="#30DFC4" />
                                    </g>
                                    <defs>
                                        <filter id="filter0_f_1535_15313" x="-82.6313" y="-90" width="275.263" height="262.5" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                                            <feFlood flood-opacity="0" result="BackgroundImageFix" />
                                            <feBlend mode="normal" in="SourceGraphic" in2="BackgroundImageFix" result="shape" />
                                            <feGaussianBlur stdDeviation="45" result="effect1_foregroundBlur_1535_15313" />
                                        </filter>
                                    </defs>
                                </svg>
                            </div>
                            <div class="flex flex-col mt-auto p-4 md:p-5">
                                <p class="tracking-wide">Pengajuan Disetujui</p>
                                <p class="font-bold">22</p>
                            </div>
                        </a>
                        <a class="h-[150px] flex flex-col shadow-sm rounded-xl bg-white hover:shadow-lg dark:bg-neutral-900 dark:border-neutral-700 dark:shadow-neutral-700/70 relative" href="#">
                            <div class="m-[20px] absolute z-[1] w-10 h-10 flex-shrink-0 rounded-md bg-white shadow-sm flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path d="M13.5 19H5C4.46957 19 3.96086 18.7893 3.58579 18.4142C3.21071 18.0391 3 17.5304 3 17V6C3 5.46957 3.21071 4.96086 3.58579 4.58579C3.96086 4.21071 4.46957 4 5 4H9L12 7H19C19.5304 7 20.0391 7.21071 20.4142 7.58579C20.7893 7.96086 21 8.46957 21 9V13M22 22L17 17M17 22L22 17" stroke="#991B1B" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>
                            <div class="absolute inset-0">
                                <svg xmlns="http://www.w3.org/2000/svg" width="193" height="150" viewBox="0 0 193 150" fill="none">
                                    <g filter="url(#filter0_f_1535_15314)">
                                        <path d="M55 0L102.631 82.5H7.3686L55 0Z" fill="#FF5959" />
                                    </g>
                                    <defs>
                                        <filter id="filter0_f_1535_15314" x="-82.6313" y="-90" width="275.263" height="262.5" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                                            <feFlood flood-opacity="0" result="BackgroundImageFix" />
                                            <feBlend mode="normal" in="SourceGraphic" in2="BackgroundImageFix" result="shape" />
                                            <feGaussianBlur stdDeviation="45" result="effect1_foregroundBlur_1535_15314" />
                                        </filter>
                                    </defs>
                                </svg>
                            </div>
                            <div class="flex flex-col mt-auto p-4 md:p-5">
                                <p class="tracking-wide">Pengajuan Ditolak</p>
                                <p class="font-bold">22</p>
                            </div>
                        </a>

                        <a class="h-[150px] flex flex-col shadow-sm rounded-xl bg-white hover:shadow-lg dark:bg-neutral-900 dark:border-neutral-700 dark:shadow-neutral-700/70 relative" href="#">
                            <div class="m-[20px] absolute z-[1] w-10 h-10 flex-shrink-0 rounded-md bg-white shadow-sm flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path d="M12 19H5C4.46957 19 3.96086 18.7893 3.58579 18.4142C3.21071 18.0391 3 17.5304 3 17V6C3 5.46957 3.21071 4.96086 3.58579 4.58579C3.96086 4.21071 4.46957 4 5 4H9L12 7H19C19.5304 7 20.0391 7.21071 20.4142 7.58579C20.7893 7.96086 21 8.46957 21 9V12.5M19 16V22M19 22L22 19M19 22L16 19" stroke="#6E43E7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>
                            <div class="absolute inset-0">
                                <svg xmlns="http://www.w3.org/2000/svg" width="193" height="150" viewBox="0 0 193 150" fill="none">
                                    <g filter="url(#filter0_f_1535_15318)">
                                        <path d="M55 0L102.631 82.5H7.3686L55 0Z" fill="#8E66FF" />
                                    </g>
                                    <defs>
                                        <filter id="filter0_f_1535_15318" x="-82.6313" y="-90" width="275.263" height="262.5" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                                            <feFlood flood-opacity="0" result="BackgroundImageFix" />
                                            <feBlend mode="normal" in="SourceGraphic" in2="BackgroundImageFix" result="shape" />
                                            <feGaussianBlur stdDeviation="45" result="effect1_foregroundBlur_1535_15318" />
                                        </filter>
                                    </defs>
                                </svg>
                            </div>
                            <div class="flex flex-col mt-auto p-4 md:p-5">
                                <p class="tracking-wide">Total Pemesanan</p>
                                <p class="font-bold">22</p>
                            </div>
                        </a>
                        <a class="h-[150px] flex flex-col shadow-sm rounded-xl bg-white hover:shadow-lg dark:bg-neutral-900 dark:border-neutral-700 dark:shadow-neutral-700/70 relative" href="#">
                            <div class="m-[20px] absolute z-[1] w-10 h-10 flex-shrink-0 rounded-md bg-white shadow-sm flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path d="M12 19H5C4.46957 19 3.96086 18.7893 3.58579 18.4142C3.21071 18.0391 3 17.5304 3 17V6C3 5.46957 3.21071 4.96086 3.58579 4.58579C3.96086 4.21071 4.46957 4 5 4H9L12 7H19C19.5304 7 20.0391 7.21071 20.4142 7.58579C20.7893 7.96086 21 8.46957 21 9V12.5M19 16V22M19 22L22 19M19 22L16 19" stroke="#0D97D1" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>
                            <div class="absolute inset-0">
                                <svg xmlns="http://www.w3.org/2000/svg" width="193" height="150" viewBox="0 0 193 150" fill="none">
                                    <g filter="url(#filter0_f_1600_30850)">
                                        <path d="M55 0L102.631 82.5H7.3686L55 0Z" fill="#1DBBFF" />
                                    </g>
                                    <defs>
                                        <filter id="filter0_f_1600_30850" x="-82.6314" y="-90" width="275.263" height="262.5" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                                            <feFlood flood-opacity="0" result="BackgroundImageFix" />
                                            <feBlend mode="normal" in="SourceGraphic" in2="BackgroundImageFix" result="shape" />
                                            <feGaussianBlur stdDeviation="45" result="effect1_foregroundBlur_1600_30850" />
                                        </filter>
                                    </defs>
                                </svg>
                            </div>
                            <div class="flex flex-col mt-auto p-4 md:p-5">
                                <p class="tracking-wide">Total Penerimaan</p>
                                <p class="font-bold">22</p>
                            </div>
                        </a>
                        <a class="h-[150px] flex flex-col shadow-sm rounded-xl bg-white hover:shadow-lg dark:bg-neutral-900 dark:border-neutral-700 dark:shadow-neutral-700/70 relative" href="#">
                            <div class="m-[20px] absolute z-[1] w-10 h-10 flex-shrink-0 rounded-md bg-white shadow-sm flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <g clip-path="url(#clip0_1600_30823)">
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M4 5C4 4.20435 4.31607 3.44129 4.87868 2.87868C5.44129 2.31607 6.20435 2 7 2H17C17.7956 2 18.5587 2.31607 19.1213 2.87868C19.6839 3.44129 20 4.20435 20 5V21C19.9999 21.1883 19.9466 21.3728 19.8462 21.5322C19.7459 21.6916 19.6025 21.8194 19.4327 21.9009C19.2629 21.9824 19.0736 22.0143 18.8864 21.9929C18.6993 21.9715 18.522 21.8977 18.375 21.78L16.5 20.28L14.625 21.78C14.4329 21.9339 14.1908 22.0115 13.945 21.9981C13.6993 21.9846 13.4671 21.881 13.293 21.707L12 20.414L10.707 21.707C10.533 21.8811 10.3009 21.9849 10.0551 21.9986C9.80938 22.0122 9.56721 21.9348 9.375 21.781L7.5 20.28L5.625 21.78C5.47797 21.8977 5.30069 21.9715 5.11356 21.9929C4.92643 22.0143 4.73707 21.9824 4.56727 21.9009C4.39747 21.8194 4.25414 21.6916 4.15378 21.5322C4.05342 21.3728 4.00012 21.1883 4 21V5ZM7 4C6.73478 4 6.48043 4.10536 6.29289 4.29289C6.10536 4.48043 6 4.73478 6 5V18.92L6.875 18.22C7.05236 18.078 7.27279 18.0006 7.5 18.0006C7.72721 18.0006 7.94764 18.078 8.125 18.22L9.925 19.66L11.293 18.293C11.4805 18.1055 11.7348 18.0002 12 18.0002C12.2652 18.0002 12.5195 18.1055 12.707 18.293L14.074 19.66L15.875 18.22C16.0524 18.078 16.2728 18.0006 16.5 18.0006C16.7272 18.0006 16.9476 18.078 17.125 18.22L18 18.92V5C18 4.73478 17.8946 4.48043 17.7071 4.29289C17.5196 4.10536 17.2652 4 17 4H7Z" fill="#DE960A" />
                                        <circle cx="16.2" cy="18.6002" r="5.4" fill="white" />
                                        <path d="M12.8636 16.164C12.6948 16.3328 12.6 16.5617 12.6 16.8004C12.6 17.0391 12.6948 17.268 12.8636 17.4368C13.0324 17.6056 13.2613 17.7004 13.5 17.7004H18.9C19.1387 17.7004 19.3676 17.6056 19.5364 17.4368C19.7052 17.268 19.8 17.0391 19.8 16.8004C19.8 16.5617 19.7052 16.3328 19.5364 16.164C19.3676 15.9952 19.1387 15.9004 18.9 15.9004H13.5C13.2613 15.9004 13.0324 15.9952 12.8636 16.164Z" fill="#DE960A" />
                                        <path d="M12.8636 19.764C13.0324 19.5952 13.2613 19.5004 13.5 19.5004H16.2C16.4387 19.5004 16.6676 19.5952 16.8364 19.764C17.0052 19.9328 17.1 20.1617 17.1 20.4004C17.1 20.6391 17.0052 20.868 16.8364 21.0368C16.6676 21.2056 16.4387 21.3004 16.2 21.3004H13.5C13.2613 21.3004 13.0324 21.2056 12.8636 21.0368C12.6948 20.868 12.6 20.6391 12.6 20.4004C12.6 20.1617 12.6948 19.9328 12.8636 19.764Z" fill="#DE960A" />
                                    </g>
                                    <defs>
                                        <clipPath id="clip0_1600_30823">
                                            <rect width="24" height="24" fill="white" />
                                        </clipPath>
                                    </defs>
                                </svg>
                            </div>
                            <div class="absolute inset-0">
                                <svg xmlns="http://www.w3.org/2000/svg" width="193" height="150" viewBox="0 0 193 150" fill="none">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="193" height="150" viewBox="0 0 193 150" fill="none">
                                        <g filter="url(#filter0_f_1600_30818)">
                                            <path d="M55 0L102.631 82.5H7.3686L55 0Z" fill="#EBA900" />
                                        </g>
                                        <defs>
                                            <filter id="filter0_f_1600_30818" x="-82.6314" y="-90" width="275.263" height="262.5" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                                                <feFlood flood-opacity="0" result="BackgroundImageFix" />
                                                <feBlend mode="normal" in="SourceGraphic" in2="BackgroundImageFix" result="shape" />
                                                <feGaussianBlur stdDeviation="45" result="effect1_foregroundBlur_1600_30818" />
                                            </filter>
                                        </defs>
                                    </svg>
                            </div>
                            <div class="flex flex-col mt-auto p-4 md:p-5">
                                <p class="tracking-wide">Total Tagihan</p>
                                <p class="font-bold">22</p>
                            </div>
                        </a>
                        <a class="h-[150px] flex flex-col shadow-sm rounded-xl bg-white hover:shadow-lg dark:bg-neutral-900 dark:border-neutral-700 dark:shadow-neutral-700/70 relative" href="#">
                            <div class="m-[20px] absolute z-[1] w-10 h-10 flex-shrink-0 rounded-md bg-white shadow-sm flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <g clip-path="url(#clip0_1600_30833)">
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M4 5C4 4.20435 4.31607 3.44129 4.87868 2.87868C5.44129 2.31607 6.20435 2 7 2H17C17.7956 2 18.5587 2.31607 19.1213 2.87868C19.6839 3.44129 20 4.20435 20 5V21C19.9999 21.1883 19.9466 21.3728 19.8462 21.5322C19.7459 21.6916 19.6025 21.8194 19.4327 21.9009C19.2629 21.9824 19.0736 22.0143 18.8864 21.9929C18.6993 21.9715 18.522 21.8977 18.375 21.78L16.5 20.28L14.625 21.78C14.4329 21.9339 14.1908 22.0115 13.945 21.9981C13.6993 21.9846 13.4671 21.881 13.293 21.707L12 20.414L10.707 21.707C10.533 21.8811 10.3009 21.9849 10.0551 21.9986C9.80938 22.0122 9.56721 21.9348 9.375 21.781L7.5 20.28L5.625 21.78C5.47797 21.8977 5.30069 21.9715 5.11356 21.9929C4.92643 22.0143 4.73707 21.9824 4.56727 21.9009C4.39747 21.8194 4.25414 21.6916 4.15378 21.5322C4.05342 21.3728 4.00012 21.1883 4 21V5ZM7 4C6.73478 4 6.48043 4.10536 6.29289 4.29289C6.10536 4.48043 6 4.73478 6 5V18.92L6.875 18.22C7.05236 18.078 7.27279 18.0006 7.5 18.0006C7.72721 18.0006 7.94764 18.078 8.125 18.22L9.925 19.66L11.293 18.293C11.4805 18.1055 11.7348 18.0002 12 18.0002C12.2652 18.0002 12.5195 18.1055 12.707 18.293L14.074 19.66L15.875 18.22C16.0524 18.078 16.2728 18.0006 16.5 18.0006C16.7272 18.0006 16.9476 18.078 17.125 18.22L18 18.92V5C18 4.73478 17.8946 4.48043 17.7071 4.29289C17.5196 4.10536 17.2652 4 17 4H7Z" fill="#991B1B" />
                                        <circle cx="16.2" cy="18.6002" r="5.4" fill="white" />
                                        <path d="M14.1423 15.3543C14.2527 15.4035 14.3521 15.4744 14.4345 15.5628L16.1985 17.3268L17.9625 15.5628C18.1331 15.4038 18.3587 15.3173 18.5919 15.3214C18.825 15.3255 19.0475 15.42 19.2124 15.5849C19.3773 15.7498 19.4718 15.9722 19.4759 16.2054C19.48 16.4386 19.3934 16.6642 19.2345 16.8348L17.4705 18.5988L19.2345 20.3628C19.3229 20.4452 19.3938 20.5446 19.443 20.655C19.4922 20.7654 19.5186 20.8845 19.5208 21.0054C19.5229 21.1262 19.5007 21.2463 19.4554 21.3583C19.4101 21.4704 19.3428 21.5722 19.2573 21.6577C19.1718 21.7431 19.07 21.8105 18.958 21.8558C18.8459 21.901 18.7259 21.9233 18.605 21.9211C18.4842 21.919 18.365 21.8925 18.2546 21.8434C18.1442 21.7942 18.0449 21.7232 17.9625 21.6348L16.1985 19.8708L14.4345 21.6348C14.3521 21.7232 14.2527 21.7942 14.1423 21.8434C14.0319 21.8925 13.9127 21.919 13.7919 21.9211C13.671 21.9233 13.551 21.901 13.4389 21.8558C13.3269 21.8105 13.2251 21.7431 13.1396 21.6577C13.0541 21.5722 12.9868 21.4704 12.9415 21.3583C12.8962 21.2463 12.874 21.1262 12.8761 21.0054C12.8783 20.8845 12.9047 20.7654 12.9539 20.655C13.0031 20.5446 13.074 20.4452 13.1625 20.3628L14.9265 18.5988L13.1625 16.8348C13.074 16.7524 13.0031 16.6531 12.9539 16.5427C12.9047 16.4323 12.8783 16.3131 12.8761 16.1922C12.874 16.0714 12.8962 15.9514 12.9415 15.8393C12.9868 15.7272 13.0541 15.6254 13.1396 15.54C13.2251 15.4545 13.3269 15.3871 13.4389 15.3419C13.551 15.2966 13.671 15.2744 13.7919 15.2765C13.9127 15.2786 14.0319 15.3051 14.1423 15.3543Z" fill="#991B1B" />
                                    </g>
                                    <defs>
                                        <clipPath id="clip0_1600_30833">
                                            <rect width="24" height="24" fill="white" />
                                        </clipPath>
                                    </defs>
                                </svg>
                            </div>
                            <div class="absolute inset-0">
                                <svg xmlns="http://www.w3.org/2000/svg" width="193" height="150" viewBox="0 0 193 150" fill="none">
                                    <g filter="url(#filter0_f_1600_30828)">
                                        <path d="M55 0L102.631 82.5H7.3686L55 0Z" fill="#FF5959" />
                                    </g>
                                    <defs>
                                        <filter id="filter0_f_1600_30828" x="-82.6314" y="-90" width="275.263" height="262.5" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                                            <feFlood flood-opacity="0" result="BackgroundImageFix" />
                                            <feBlend mode="normal" in="SourceGraphic" in2="BackgroundImageFix" result="shape" />
                                            <feGaussianBlur stdDeviation="45" result="effect1_foregroundBlur_1600_30828" />
                                        </filter>
                                    </defs>
                                </svg>
                            </div>
                            <div class="flex flex-col mt-auto p-4 md:p-5">
                                <p class="tracking-wide">Tagihan Belum Lunas</p>
                                <p class="font-bold">22</p>
                            </div>
                        </a>

                        <a class="h-[150px] flex flex-col shadow-sm rounded-xl bg-white hover:shadow-lg dark:bg-neutral-900 dark:border-neutral-700 dark:shadow-neutral-700/70 relative" href="#">
                            <div class="m-[20px] absolute z-[1] w-10 h-10 flex-shrink-0 rounded-md bg-white shadow-sm flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <g clip-path="url(#clip0_1600_30844)">
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M4 5C4 4.20435 4.31607 3.44129 4.87868 2.87868C5.44129 2.31607 6.20435 2 7 2H17C17.7956 2 18.5587 2.31607 19.1213 2.87868C19.6839 3.44129 20 4.20435 20 5V21C19.9999 21.1883 19.9466 21.3728 19.8462 21.5322C19.7459 21.6916 19.6025 21.8194 19.4327 21.9009C19.2629 21.9824 19.0736 22.0143 18.8864 21.9929C18.6993 21.9715 18.522 21.8977 18.375 21.78L16.5 20.28L14.625 21.78C14.4329 21.9339 14.1908 22.0115 13.945 21.9981C13.6993 21.9846 13.4671 21.881 13.293 21.707L12 20.414L10.707 21.707C10.533 21.8811 10.3009 21.9849 10.0551 21.9986C9.80938 22.0122 9.56721 21.9348 9.375 21.781L7.5 20.28L5.625 21.78C5.47797 21.8977 5.30069 21.9715 5.11356 21.9929C4.92643 22.0143 4.73707 21.9824 4.56727 21.9009C4.39747 21.8194 4.25414 21.6916 4.15378 21.5322C4.05342 21.3728 4.00012 21.1883 4 21V5ZM7 4C6.73478 4 6.48043 4.10536 6.29289 4.29289C6.10536 4.48043 6 4.73478 6 5V18.92L6.875 18.22C7.05236 18.078 7.27279 18.0006 7.5 18.0006C7.72721 18.0006 7.94764 18.078 8.125 18.22L9.925 19.66L11.293 18.293C11.4805 18.1055 11.7348 18.0002 12 18.0002C12.2652 18.0002 12.5195 18.1055 12.707 18.293L14.074 19.66L15.875 18.22C16.0524 18.078 16.2728 18.0006 16.5 18.0006C16.7272 18.0006 16.9476 18.078 17.125 18.22L18 18.92V5C18 4.73478 17.8946 4.48043 17.7071 4.29289C17.5196 4.10536 17.2652 4 17 4H7Z" fill="#1D8676" />
                                        <circle cx="16.2" cy="18.6002" r="5.4" fill="white" />
                                        <path d="M20.047 16.4978C20.0079 16.6095 19.9473 16.7124 19.8685 16.8007L15.5821 21.6007C15.4977 21.6953 15.3942 21.7709 15.2785 21.8227C15.1628 21.8745 15.0375 21.9013 14.9107 21.9013C14.7839 21.9013 14.6586 21.8745 14.5429 21.8227C14.4272 21.7709 14.3237 21.6953 14.2393 21.6007L12.5257 19.6807C12.3682 19.5025 12.2876 19.2693 12.3015 19.0319C12.3154 18.7945 12.4227 18.5723 12.6 18.4138C12.7772 18.2552 13.01 18.1733 13.2474 18.1858C13.4849 18.1984 13.7077 18.3044 13.8673 18.4807L14.9113 19.6495L18.5245 15.6007C18.6033 15.5124 18.6987 15.4406 18.8053 15.3892C18.9118 15.3378 19.0275 15.3079 19.1456 15.3012C19.2637 15.2946 19.382 15.3112 19.4937 15.3502C19.6054 15.3893 19.7083 15.4499 19.7965 15.5287C19.8848 15.6075 19.9566 15.7029 20.008 15.8094C20.0594 15.916 20.0893 16.0317 20.0959 16.1498C20.1026 16.2679 20.086 16.3862 20.047 16.4978Z" fill="#1D8676" />
                                    </g>
                                    <defs>
                                        <clipPath id="clip0_1600_30844">
                                            <rect width="24" height="24" fill="white" />
                                        </clipPath>
                                    </defs>
                                </svg>
                            </div>
                            <div class="absolute inset-0">
                                <svg xmlns="http://www.w3.org/2000/svg" width="193" height="150" viewBox="0 0 193 150" fill="none">
                                    <g filter="url(#filter0_f_1600_30839)">
                                        <path d="M55 0L102.631 82.5H7.3686L55 0Z" fill="#30DFC4" />
                                    </g>
                                    <defs>
                                        <filter id="filter0_f_1600_30839" x="-82.6314" y="-90" width="275.263" height="262.5" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                                            <feFlood flood-opacity="0" result="BackgroundImageFix" />
                                            <feBlend mode="normal" in="SourceGraphic" in2="BackgroundImageFix" result="shape" />
                                            <feGaussianBlur stdDeviation="45" result="effect1_foregroundBlur_1600_30839" />
                                        </filter>
                                    </defs>
                                </svg>
                            </div>
                            <div class="flex flex-col mt-auto p-4 md:p-5">
                                <p class="tracking-wide">Tagihan telah Dibayar</p>
                                <p class="font-bold">22</p>
                            </div>
                        </a>
                    </div>
                    <div class="flex justify-center mt-4">
                        <span id="indicator0" class="w-2 h-2 bg-black rounded-full inline-block mx-1"></span>
                        <span id="indicator1" class="w-2 h-2 bg-gray-400 rounded-full inline-block mx-1"></span>
                    </div>


                </div>
                <!-- End Group Card -->
                <div class="mt-5 p-5 bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden dark:bg-slate-900 dark:border-gray-700">
                    <!-- Header -->

                    <div class="py-1 flex justify-between items-center border-gray-200 dark:border-gray-700">
                        <div>
                            <h2 class="mb-2 text-xl font-semibold text-gray-800 dark:text-gray-200">
                                Data Pengadaan
                            </h2>

                        </div>

                    </div>


                    <div class="py-4 grid gap-3 md:items-start">
                        <div class="sm:col-span-1">
                            <label for="hs-as-table-product-review-search" class="sr-only">Search</label>
                            <div class="relative">
                                <input type="text" id="myInput" onkeyup="myFunction()" class="py-2 px-4 ps-11 block border w-full xl:w-96 border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600" placeholder="Search">
                                <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none ps-4">
                                    <svg class="size-4 text-gray-400 dark:text-neutral-500" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Header -->

                    <!-- Table -->
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <colgroup>
                            <col width="5%">
                            <col width="14%">
                            <col width="20%">
                            <col width="20%">
                            <col width="18%">
                            <col width="23%">
                        </colgroup>
                        <thead class="bg-gray-50 dark:bg-slate-800">
                            <tr>
                                <th scope="col" class="ps-6 py-3 text-start">
                                    <label for="hs-at-with-checkboxes-main" class="flex">
                                        <input type="checkbox" class="shrink-0 border-gray-300 rounded text-blue-600 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-600 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800" id="hs-at-with-checkboxes-main">
                                        <span class="sr-only">Checkbox</span>
                                    </label>
                                </th>

                                <th scope="col" class="px-6 py-3 text-start">
                                    <div class="flex items-center">
                                        <span class="text-xs tracking-wide text-gray-800 dark:text-gray-200">
                                            Tanggal
                                        </span>
                                    </div>
                                </th>

                                <th scope="col" class="px-6 py-3 text-start">
                                    <div class="flex items-center">
                                        <span class="text-xs tracking-wide text-gray-800 dark:text-gray-200">
                                            Nomor Pengajuan
                                        </span>
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-3 text-start">
                                    <div class="flex items-center">
                                        <span class="text-xs tracking-wide text-gray-800 dark:text-gray-200">
                                            Nomor Pemesanan
                                        </span>
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-3 text-start">
                                    <div class="flex items-center">
                                        <span class="text-xs tracking-wide text-gray-800 dark:text-gray-200">
                                            Nomor Faktur
                                        </span>
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-3 text-start">
                                    <div class="flex items-center">
                                        <span class="text-xs tracking-wide text-gray-800 dark:text-gray-200">
                                            Status
                                        </span>
                                    </div>
                                </th>

                                <!-- <th scope="col" class="px-6 py-3">
                                    <div class="flex items-center">
                                        <span class="text-xs tracking-wide text-gray-800 dark:text-gray-200">
                                            Aksi
                                        </span>
                                    </div>
                                </th> -->

                            </tr>
                        </thead>




                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            <tr>
                                <td>
                                    <div class="ps-6 py-3">
                                        <label class="flex">
                                            <input type="checkbox" class="shrink-0 border-gray-300 rounded text-blue-600 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-600 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800">
                                            <span class="sr-only">Checkbox</span>
                                        </label>
                                    </div>
                                </td>
                                <td>
                                    <div class="px-6 py-3">
                                        <div class="flex items-center gap-x-3 text-start">
                                            <span class="block text-sm font-semibold text-gray-800 dark:text-gray-200"><?= $penerimaan['tanggal_datang'] ?? '2024-15-10' ?></span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="px-6 py-3">
                                        <span class="text-start block text-sm font-semibold text-gray-800 dark:text-gray-200"><?= $penerimaan['no_faktur'] ?? 'PBM202410010001' ?></span>
                                    </div>
                                </td>
                                <td>
                                    <div class="px-6 py-3">
                                        <span class="text-start block text-sm font-semibold text-gray-800 dark:text-gray-200"><?= $penerimaan['no_faktur'] ?? 'PBM202410010001' ?></span>
                                    </div>
                                </td>
                                <td>
                                    <div class="px-6 py-3">
                                        <span class="text-start block text-sm font-semibold text-gray-800 dark:text-gray-200"><?= $penerimaan['no_faktur'] ?? 'PBM202410010001' ?></span>
                                    </div>
                                </td>
                                <td>
                                    <div class="px-6 py-3">
                                        <span class="py-1 px-1.5 inline-flex items-center gap-x-1 text-xs font-medium bg-yellow-100 text-teal-800 rounded-full dark:bg-teal-500/10 dark:text-teal-500">
                                            <svg class="size-2.5" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
                                            </svg>
                                            Menunggu Persetujuan
                                        </span>
                                    </div>
                                </td>
                                <!-- <td>
                                    <div class="pl-6 py-1.5 inline-flex">
                                        <div class="pr-3 py-1.5">
                                            <button type="button" class="gap-x-1 text-sm decoration-2 hover:underline font-medium dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" data-hs-overlay="#hs-vertically-centered-scrollable-modal-">
                                                Lihat Detail
                                            </button>
                                        </div>
                                        <div class="px-3 py-1.5">
                                            <a href="/editmedis/" class="gap-x-1 text-sm text-blue-600 decoration-2 hover:underline font-medium dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" href="#">
                                                Edit
                                            </a>
                                        </div>
                                        <div class="px-3 py-1.5">
                                            <a href="/hapusmedis/" class="gap-x-1 text-sm text-red-600 decoration-2 hover:underline font-medium dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" href="#">
                                                Hapus
                                            </a>
                                        </div>
                                    </div>
                                </td> -->

                            </tr>
                        </tbody>
                    </table>
                    <!-- End Table -->

                    <!-- Footer -->
                    <div class="px-6 py-4 grid gap-3 md:flex md:justify-between md:items-center border-t border-gray-200 dark:border-neutral-700">
                        <!-- Pagination -->
                        <nav class="flex w-full justify-between items-center gap-x-1">
                            <!-- Previous Button -->

                        </nav>
                    </div>


                    <!-- End Footer -->
                </div>
            </div>
        </div>
    </div>
    <!-- End Card -->
</div>
<!-- End Table Section -->
<script>
    let currentIndex = 0;
    const cardsPerPage = 4;

    function showCards(index) {
        const cards = document.querySelectorAll('#cardContainer a');
        cards.forEach((card, i) => {
            card.style.display = (i >= index && i < index + cardsPerPage) ? 'flex' : 'none';
        });
        updateSliderIndicator(index / cardsPerPage);
    }

    function nextCards() {
        const totalCards = document.querySelectorAll('#cardContainer a').length;
        if (currentIndex + cardsPerPage < totalCards) {
            currentIndex += cardsPerPage;
            showCards(currentIndex);
        }
    }

    function prevCards() {
        if (currentIndex - cardsPerPage >= 0) {
            currentIndex -= cardsPerPage;
            showCards(currentIndex);
        }
    }

    function updateSliderIndicator(activeIndex) {
        const indicators = document.querySelectorAll('.flex.justify-center.mt-4 span');
        indicators.forEach((indicator, i) => {
            if (i === activeIndex) {
                indicator.classList.add('bg-black');
                indicator.classList.add('px-3');
                indicator.classList.remove('bg-gray-400');
            } else {
                indicator.classList.add('bg-gray-400');
                indicator.classList.remove('bg-black');
                indicator.classList.remove('px-3');
            }
        });
    }

    // Initially show the first 4 cards
    showCards(currentIndex);
</script>
<?= $this->endSection(); ?>