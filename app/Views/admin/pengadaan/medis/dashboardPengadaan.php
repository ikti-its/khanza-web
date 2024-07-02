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
                    <div class="items-center mb-5 grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="h-full flex flex-col bg-white border shadow-[0px_2px_6px_0px_rgba(13,10,44,0.08)] rounded-[1.25rem] dark:bg-neutral-900 dark:border-neutral-700 dark:shadow-neutral-700/70">
                            <?php
                            $countStatus0 = 0;
                            $countStatus1 = 0;
                            $countStatus2 = 0;
                            $countStatus3 = 0;
                            foreach ($pengajuan_data as $pengajuan) {
                                if ($pengajuan['status_pesanan'] === '0') {
                                    $countStatus0++;
                                } elseif ($pengajuan['status_pesanan'] === '1') {
                                    $countStatus1++;
                                } elseif ($pengajuan['status_pesanan'] === '2') {
                                    $countStatus2++;
                                } elseif ($pengajuan['status_pesanan'] === '3') {
                                    $countStatus3++;
                                }
                            }

                            // Data awal untuk chart doughnut
                            $dataDoughnut = [
                                'labels' => ["Menunggu Persetujuan", "Pengajuan Disetujui", "Pengajuan Ditolak", "Dalam Pemesanan"],
                                'datasets' => [
                                    [
                                        'label' => "Total",
                                        'data' => [$countStatus0, $countStatus2, $countStatus1, $countStatus3], // Ubah nilai data untuk status '3'
                                        'backgroundColor' => [
                                            "#ACF2E7",
                                            "#30DFC4",
                                            "#2DBFA9",
                                            "#24A793",
                                        ],
                                        'hoverOffset' => 4,
                                    ],
                                ],
                            ];

                            // Konversi dataDoughnut ke format JSON
                            $dataDoughnutJSON = json_encode($dataDoughnut);
                            ?>
                            <div class="mx-auto p-4 w-[240px] h-[240px]">
                                <canvas class="p-1" id="chartDoughnut"></canvas>
                            </div>
                            <div class="mx-auto p-5 grid grid-cols-2 gap-x-14 gap-y-4">
                                <div class="flex justify-between items-center">
                                    <div class="flex items-center">
                                        <div class="w-[0.75rem] h-[0.75rem] bg-[#ACF2E7] rounded-[1.5rem]"></div>
                                        <p class="ml-2">Menunggu Persetujuan</p>
                                    </div>
                                    <p class="pl-3"><?= $countStatus0 ?></p>
                                </div>
                                <div class="flex justify-between items-center">
                                    <div class="flex items-center">
                                        <div class="w-[0.75rem] h-[0.75rem] bg-[#2DBFA9] rounded-[1.5rem]"></div>
                                        <p class="ml-2">Pengajuan Ditolak</p>
                                    </div>
                                    <p class="pl-3"><?= $countStatus1 ?></p>
                                </div>
                                <div class="flex justify-between items-center">
                                    <div class="flex items-center">
                                        <div class="w-[0.75rem] h-[0.75rem] bg-[#30DFC4] rounded-[1.5rem]"></div>
                                        <p class="ml-2">Pengajuan Disetujui</p>
                                    </div>
                                    <p class="pl-3"><?= $countStatus2 ?></p>
                                </div>

                                <div class="flex justify-between items-center">
                                    <div class="flex items-center">
                                        <div class="w-[0.75rem] h-[0.75rem] bg-[#24A793] rounded-[1.5rem]"></div>
                                        <p class="ml-2">Dalam Pemesanan</p>
                                    </div>
                                    <p class="pl-3"><?= $countStatus3 ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="h-full flex flex-col bg-white border shadow-[0px_2px_6px_0px_rgba(13,10,44,0.08)] rounded-[1.25rem] dark:bg-neutral-900 dark:border-neutral-700 dark:shadow-neutral-700/70">
                            <?php
                            $countStatus4 = 0;
                            $countStatus5 = 0;
                            $countStatus6 = 0;
                            $countStatus7 = 0;
                            foreach ($pengajuan_data as $pengajuan) {
                                if ($pengajuan['status_pesanan'] === '4') {
                                    $countStatus4++;
                                } elseif ($pengajuan['status_pesanan'] === '5') {
                                    $countStatus5++;
                                } elseif ($pengajuan['status_pesanan'] === '6') {
                                    $countStatus6++;
                                } elseif ($pengajuan['status_pesanan'] === '7') {
                                    $countStatus7++;
                                }
                            }

                            // Data awal untuk chart doughnut
                            $dataBaru = [
                                'labels' => ["Barang Belum Diterima Sepenuhnya", "Barang Telah Diterima", "Tagihan Belum Lunas", "Tagihan Telah Dibayar"],
                                'datasets' => [
                                    [
                                        'label' => "Total",
                                        'data' => [$countStatus4, $countStatus5, $countStatus6, $countStatus7], // Ubah nilai sesuai dengan data yang sesuai
                                        'backgroundColor' => [
                                            "#D6F3F9",
                                            "#83D9EC",
                                            "#3DAFC9",
                                            "#248FA7",
                                        ],
                                        'hoverOffset' => 4,
                                    ],
                                ],
                            ];

                            // Konversi dataBaru ke format JSON
                            $dataBaruJSON = json_encode($dataBaru);
                            ?>
                            <div class="mx-auto p-4 w-[240px] h-[240px]">
                                <canvas class="p-1" id="chartBaru"></canvas>
                            </div>
                            <div class="mx-auto p-5 grid grid-cols-2 gap-x-14 gap-y-4">
                                <div class="flex justify-between items-center">
                                    <div class="flex items-center">
                                        <div class="w-[0.75rem] h-[0.75rem] bg-[#D6F3F9] rounded-[1.5rem]"></div>
                                        <p class="ml-2">Barang Belum Diterima Sepenuhnya</p>
                                    </div>
                                    <p class="pl-3"><?= $countStatus4 ?></p>
                                </div>
                                <div class="flex justify-between items-center">
                                    <div class="flex items-center">
                                        <div class="w-[0.75rem] h-[0.75rem] bg-[#3DAFC9] rounded-[1.5rem]"></div>
                                        <p class="ml-2">Tagihan Belum Lunas</p>
                                    </div>
                                    <p class="pl-3"><?= $countStatus6 ?></p>
                                </div>
                                <div class="flex justify-between items-center">
                                    <div class="flex items-center">
                                        <div class="w-[0.75rem] h-[0.75rem] bg-[#83D9EC] rounded-[1.5rem]"></div>
                                        <p class="ml-2">Barang Telah Diterima</p>
                                    </div>
                                    <p class="pl-3"><?= $countStatus5 ?></p>
                                </div>

                                <div class="flex justify-between items-center">
                                    <div class="flex items-center">
                                        <div class="w-[0.75rem] h-[0.75rem] bg-[#248FA7] rounded-[1.5rem]"></div>
                                        <p class="ml-2">Tagihan Telah Dibayar</p>
                                    </div>
                                    <p class="pl-3"><?= $countStatus7 ?></p>
                                </div>
                            </div>
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
                                <p class="font-bold"><?php $count = 0;
                                                        foreach ($pengajuan_data as $pengajuan) {
                                                            if ($pengajuan['status_pesanan'] !== "-1") {
                                                                $count++;
                                                            }
                                                        }
                                                        echo $count; ?></p>
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
                                <p class="font-bold"><?php $count = 0;
                                                        foreach ($pengajuan_data as $pengajuan) {
                                                            if ($pengajuan['status_pesanan'] === "2") {
                                                                $count++;
                                                            }
                                                        }
                                                        echo $count; ?></p>
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
                                <p class="font-bold"><?php $count = 0;
                                                        foreach ($pengajuan_data as $pengajuan) {
                                                            if ($pengajuan['status_pesanan'] === "1") {
                                                                $count++;
                                                            }
                                                        }
                                                        echo $count; ?></p>
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
                                <p class="font-bold"><?php $count = 0;
                                                        foreach ($pengajuan_data as $pengajuan) {
                                                            if ($pengajuan['status_pesanan'] === "3") {
                                                                $count++;
                                                            }
                                                        }
                                                        echo $count; ?></p>
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
                                <p class="font-bold"><?php $count = 0;
                                                        foreach ($pengajuan_data as $pengajuan) {
                                                            if ($pengajuan['status_pesanan'] >= "4" && $pengajuan['status_pesanan'] <= "7") {
                                                                $count++;
                                                            }
                                                        }
                                                        echo $count; ?></p>
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
                                <p class="font-bold"><?php $count = 0;
                                                        foreach ($pengajuan_data as $pengajuan) {
                                                            if ($pengajuan['status_pesanan'] === "6" || $pengajuan['status_pesanan'] === "7") {
                                                                $count++;
                                                            }
                                                        }
                                                        echo $count; ?></p>
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
                                <p class="font-bold"><?php $count = 0;
                                                        foreach ($pengajuan_data as $pengajuan) {
                                                            if ($pengajuan['status_pesanan'] === "6") {
                                                                $count++;
                                                            }
                                                        }
                                                        echo $count; ?></p>
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
                                <p class="font-bold"><?php $count = 0;
                                                        foreach ($pengajuan_data as $pengajuan) {
                                                            if ($pengajuan['status_pesanan'] === "7") {
                                                                $count++;
                                                            }
                                                        }
                                                        echo $count; ?></p>
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

                    <div id="noDataFound" style="display: none;">Data tidak ditemukan</div>
                    <!-- End Header -->

                    <!-- Table -->
                    <table id="myTable" class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <colgroup>
                            <col width="19%">
                            <col width="19%">
                            <col width="19%">
                            <col width="19%">
                            <col width="24%">
                            <!-- <col width="23%"> -->
                        </colgroup>
                        <thead class="bg-gray-50 dark:bg-slate-800">
                            <tr>


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
                            <?php foreach ($pengajuan_data as $pengajuan) : ?>
                                <tr>
                                    <td>
                                        <div class="px-6 py-3">
                                            <div class="flex items-center gap-x-3 text-start">
                                                <span class="block text-sm font-semibold text-gray-800 dark:text-gray-200"><?= $pengajuan['tanggal_pengajuan'] ?? '-' ?></span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="px-6 py-3">
                                            <span class="text-start block text-sm font-semibold text-gray-800 dark:text-gray-200"><?= $pengajuan['nomor_pengajuan'] ?? '-' ?></span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="px-6 py-3">
                                            <span class="text-start block text-sm font-semibold text-gray-800 dark:text-gray-200">
                                                <?php
                                                $pemesanan_found = false;
                                                foreach ($pemesanan_data as $pemesanan) {
                                                    if ($pengajuan['id'] === $pemesanan['id_pengajuan']) {
                                                        echo $pemesanan['no_pemesanan'] ?? '-';
                                                        $pemesanan_found = true;
                                                        break;
                                                    }
                                                }
                                                if (!$pemesanan_found) echo '-';
                                                ?>
                                            </span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="px-6 py-3">
                                            <span class="text-start block text-sm font-semibold text-gray-800 dark:text-gray-200">
                                                <?php
                                                $penerimaan_found = false;
                                                foreach ($penerimaan_data as $penerimaan) {
                                                    if ($pengajuan['id'] === $penerimaan['id_pengajuan']) { // Assuming id_pengajuan links penerimaan to pengajuan
                                                        echo $penerimaan['no_faktur'] ?? '-';
                                                        $penerimaan_found = true;
                                                        break;
                                                    }
                                                }
                                                if (!$penerimaan_found) echo '-';
                                                ?>
                                            </span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="px-6 py-3">
                                            <?php
                                            switch ($pengajuan['status_pesanan']) {
                                                case '0':
                                                    echo '<span class="px-2 py-1.5 inline-flex items-center gap-x-1 text-xs font-medium bg-[#FEF9C3] text-[#A46319] rounded-full dark:bg-teal-500/10 dark:text-teal-500">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                                        <path d="M8.00004 14.6663C11.6819 14.6663 14.6667 11.6816 14.6667 7.99967C14.6667 4.31778 11.6819 1.33301 8.00004 1.33301C4.31814 1.33301 1.33337 4.31778 1.33337 7.99967C1.33337 11.6816 4.31814 14.6663 8.00004 14.6663Z" fill="#A46319"/>
                                                        <path d="M10.4733 10.6202C10.3867 10.6202 10.3 10.6002 10.22 10.5468L8.15334 9.3135C7.64001 9.00684 7.26001 8.3335 7.26001 7.74017V5.00684C7.26001 4.7335 7.48668 4.50684 7.76001 4.50684C8.03334 4.50684 8.26001 4.7335 8.26001 5.00684V7.74017C8.26001 7.98017 8.46001 8.3335 8.66668 8.4535L10.7333 9.68684C10.9733 9.82684 11.0467 10.1335 10.9067 10.3735C10.8067 10.5335 10.64 10.6202 10.4733 10.6202Z" fill="#FEF9C3"/>
                                                        </svg>
                                                            Menunggu Persetujuan
                                                        </span>';
                                                    break;
                                                case '1':
                                                    echo '<span class="px-2 py-1.5 inline-flex items-center gap-x-1 text-xs font-medium bg-[#FEE2E2] text-[#991B1B] rounded-full dark:bg-teal-500/10 dark:text-teal-500">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                                        <path d="M8.00004 14.6663C11.6819 14.6663 14.6667 11.6816 14.6667 7.99967C14.6667 4.31778 11.6819 1.33301 8.00004 1.33301C4.31814 1.33301 1.33337 4.31778 1.33337 7.99967C1.33337 11.6816 4.31814 14.6663 8.00004 14.6663Z" fill="#991B1B"/>
                                                        <path d="M8.70666 8.00023L10.24 6.4669C10.4333 6.27357 10.4333 5.95357 10.24 5.76023C10.0467 5.5669 9.72666 5.5669 9.53332 5.76023L7.99999 7.29357L6.46666 5.76023C6.27332 5.5669 5.95332 5.5669 5.75999 5.76023C5.56666 5.95357 5.56666 6.27357 5.75999 6.4669L7.29332 8.00023L5.75999 9.53357C5.56666 9.7269 5.56666 10.0469 5.75999 10.2402C5.85999 10.3402 5.98666 10.3869 6.11332 10.3869C6.23999 10.3869 6.36666 10.3402 6.46666 10.2402L7.99999 8.7069L9.53332 10.2402C9.63332 10.3402 9.75999 10.3869 9.88666 10.3869C10.0133 10.3869 10.14 10.3402 10.24 10.2402C10.4333 10.0469 10.4333 9.7269 10.24 9.53357L8.70666 8.00023Z" fill="#FEE2E2"/>
                                                        </svg>
                                                            Pengajuan Ditolak
                                                        </span>';
                                                    break;
                                                case '2':
                                                    echo '<span class="px-2 py-1.5 inline-flex items-center gap-x-1 text-xs font-medium bg-[#D6F9F3] text-[#13594E] rounded-full dark:bg-teal-500/10 dark:text-teal-500">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                                        <path d="M8.00004 14.6663C11.6819 14.6663 14.6667 11.6816 14.6667 7.99967C14.6667 4.31778 11.6819 1.33301 8.00004 1.33301C4.31814 1.33301 1.33337 4.31778 1.33337 7.99967C1.33337 11.6816 4.31814 14.6663 8.00004 14.6663Z" fill="#13594E"/>
                                                        <path d="M7.05334 10.3867C6.92 10.3867 6.79334 10.3334 6.7 10.2401L4.81333 8.3534C4.62 8.16006 4.62 7.84007 4.81333 7.64673C5.00667 7.4534 5.32667 7.4534 5.52 7.64673L7.05334 9.18007L10.48 5.7534C10.6733 5.56007 10.9933 5.56007 11.1867 5.7534C11.38 5.94673 11.38 6.26673 11.1867 6.46006L7.40667 10.2401C7.31334 10.3334 7.18667 10.3867 7.05334 10.3867Z" fill="#D6F9F3"/>
                                                        </svg>
                                                            Pengajuan Disetujui
                                                        </span>';
                                                    break;
                                                case '3':
                                                    echo '<span class="px-2 py-1.5 inline-flex items-center gap-x-1 text-xs font-medium bg-[#D4DEFA] text-[#17358B] rounded-full dark:bg-teal-500/10 dark:text-teal-500">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                                        <path d="M14.3334 10.333C14.52 10.333 14.6667 10.4797 14.6667 10.6663V11.333C14.6667 12.4397 13.7734 13.333 12.6667 13.333C12.6667 12.233 11.7667 11.333 10.6667 11.333C9.56671 11.333 8.66671 12.233 8.66671 13.333H7.33337C7.33337 12.233 6.43337 11.333 5.33337 11.333C4.23337 11.333 3.33337 12.233 3.33337 13.333C2.22671 13.333 1.33337 12.4397 1.33337 11.333V9.99967C1.33337 9.63301 1.63337 9.33301 2.00004 9.33301H8.33337C9.25337 9.33301 10 8.58634 10 7.66634V3.99967C10 3.63301 10.3 3.33301 10.6667 3.33301H11.2267C11.7067 3.33301 12.1467 3.59301 12.3867 4.00634L12.8134 4.75301C12.8734 4.85967 12.7934 4.99967 12.6667 4.99967C11.7467 4.99967 11 5.74634 11 6.66634V8.66634C11 9.58634 11.7467 10.333 12.6667 10.333H14.3334Z" fill="#17358B"/>
                                                        <path d="M5.33333 14.6667C6.06971 14.6667 6.66667 14.0697 6.66667 13.3333C6.66667 12.597 6.06971 12 5.33333 12C4.59695 12 4 12.597 4 13.3333C4 14.0697 4.59695 14.6667 5.33333 14.6667Z" fill="#17358B"/>
                                                        <path d="M10.6667 14.6667C11.4031 14.6667 12 14.0697 12 13.3333C12 12.597 11.4031 12 10.6667 12C9.93033 12 9.33337 12.597 9.33337 13.3333C9.33337 14.0697 9.93033 14.6667 10.6667 14.6667Z" fill="#17358B"/>
                                                        <path d="M14.6667 8.35333V9.33333H12.6667C12.3 9.33333 12 9.03333 12 8.66667V6.66667C12 6.3 12.3 6 12.6667 6H13.5267L14.4933 7.69333C14.6067 7.89333 14.6667 8.12 14.6667 8.35333Z" fill="#17358B"/>
                                                        <path d="M8.71992 1.33301H3.79325C2.59992 1.33301 1.59992 2.18634 1.37992 3.31967H4.29325C4.54659 3.31967 4.74659 3.52634 4.74659 3.77967C4.74659 4.03301 4.54659 4.23301 4.29325 4.23301H1.33325V5.15301H3.06659C3.31992 5.15301 3.52659 5.35967 3.52659 5.61301C3.52659 5.86634 3.31992 6.06634 3.06659 6.06634H1.33325V6.98634H1.84659C2.09992 6.98634 2.30659 7.19301 2.30659 7.44634C2.30659 7.69967 2.09992 7.89967 1.84659 7.89967H1.33325V8.05301C1.33325 8.41967 1.63325 8.71967 1.99992 8.71967H8.09992C8.77992 8.71967 9.33325 8.16634 9.33325 7.48634V1.94634C9.33325 1.60634 9.05992 1.33301 8.71992 1.33301Z" fill="#17358B"/>
                                                        <path d="M1.37996 3.31934H1.27996H0.626626C0.373293 3.31934 0.166626 3.526 0.166626 3.77934C0.166626 4.03267 0.373293 4.23267 0.626626 4.23267H1.23329H1.33329V3.79267C1.33329 3.63267 1.35329 3.47267 1.37996 3.31934Z" fill="#17358B"/>
                                                        <path d="M1.23329 5.15332H0.626626C0.373293 5.15332 0.166626 5.35999 0.166626 5.61332C0.166626 5.86665 0.373293 6.06665 0.626626 6.06665H1.23329H1.33329V5.15332H1.23329Z" fill="#17358B"/>
                                                        <path d="M1.23329 6.98633H0.626626C0.373293 6.98633 0.166626 7.193 0.166626 7.44633C0.166626 7.69966 0.373293 7.89966 0.626626 7.89966H1.23329H1.33329V6.98633H1.23329Z" fill="#17358B"/>
                                                        </svg>
                                                    Dalam Pemesanan
                                                </span>';
                                                    break;
                                                case '4':
                                                    echo '<span class="px-2 py-1.5 inline-flex items-center gap-x-1 text-xs font-medium bg-[#FEE2E2] text-[#991B1B] rounded-full dark:bg-teal-500/10 dark:text-teal-500">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                                        <path d="M11.7334 3.54018L7.96669 1.51352C7.56669 1.30018 7.09336 1.30018 6.69336 1.51352L2.93336 3.54018C2.66003 3.69352 2.48669 3.98685 2.48669 4.30685C2.48669 4.63352 2.65336 4.92685 2.93336 5.07352L6.70003 7.10018C6.90003 7.20685 7.12003 7.26018 7.33336 7.26018C7.54669 7.26018 7.77336 7.20685 7.96669 7.10018L11.7334 5.07352C12.0067 4.92685 12.18 4.63352 12.18 4.30685C12.18 3.98685 12.0067 3.69352 11.7334 3.54018Z" fill="#991B1B"/>
                                                        <path d="M6.08004 7.8077L2.58004 6.06104C2.30671 5.92104 2.00004 5.94104 1.74004 6.09437C1.48671 6.25437 1.33337 6.5277 1.33337 6.8277V10.1344C1.33337 10.7077 1.65337 11.221 2.16671 11.481L5.66671 13.2277C5.78671 13.2877 5.92004 13.321 6.05337 13.321C6.20671 13.321 6.36671 13.2744 6.50671 13.1944C6.76004 13.0344 6.91337 12.761 6.91337 12.461V9.15437C6.90671 8.58104 6.58671 8.0677 6.08004 7.8077Z" fill="#991B1B"/>
                                                        <path d="M13.3333 6.8277V8.4677C13.0133 8.37437 12.6733 8.33437 12.3333 8.33437C11.4267 8.33437 10.54 8.6477 9.84001 9.2077C8.88001 9.96104 8.33334 11.101 8.33334 12.3344C8.33334 12.661 8.37334 12.9877 8.46001 13.301C8.36001 13.2877 8.26001 13.2477 8.16668 13.1877C7.91334 13.0344 7.76001 12.761 7.76001 12.461V9.15437C7.76001 8.58104 8.08001 8.0677 8.58668 7.8077L12.0867 6.06104C12.36 5.92104 12.6667 5.94104 12.9267 6.09437C13.18 6.25437 13.3333 6.5277 13.3333 6.8277Z" fill="#991B1B"/>
                                                        <path d="M13.1882 14C12.9776 14 12.7669 13.9224 12.6006 13.7561L10.2609 11.4165C9.93939 11.095 9.93939 10.5627 10.2609 10.2412C10.5825 9.91961 11.1147 9.91961 11.4363 10.2412L13.7759 12.5808C14.0975 12.9023 14.0975 13.4345 13.7759 13.7561C13.6096 13.9224 13.3989 14 13.1882 14Z" fill="#991B1B"/>
                                                        <path d="M10.8288 14.0195C10.6182 14.0195 10.4075 13.9419 10.2412 13.7756C9.91961 13.454 9.91961 12.9218 10.2412 12.6002L12.5807 10.2607C12.9023 9.93914 13.4345 9.93914 13.7561 10.2607C14.0776 10.5822 14.0776 11.1145 13.7561 11.436L11.4165 13.7756C11.2502 13.9419 11.0395 14.0195 10.8288 14.0195Z" fill="#991B1B"/>
                                                        </svg>
                                                    Barang Belum Diterima
                                                </span>';
                                                    break;
                                                case '5':
                                                    echo '<span class="px-2 py-1.5 inline-flex items-center gap-x-1 text-xs font-medium bg-[#E9EEFD] text-[#1F46B9] rounded-full dark:bg-teal-500/10 dark:text-teal-500">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                                        <path d="M13.4734 5.21427L8.34002 8.1876C8.13335 8.3076 7.87335 8.3076 7.66002 8.1876L2.52669 5.21427C2.16002 5.00094 2.06669 4.50094 2.34669 4.1876C2.54002 3.9676 2.76002 3.7876 2.99335 3.66094L6.60669 1.66094C7.38002 1.2276 8.63335 1.2276 9.40669 1.66094L13.02 3.66094C13.2534 3.7876 13.4734 3.97427 13.6667 4.1876C13.9334 4.50094 13.84 5.00094 13.4734 5.21427Z" fill="#1F46B9"/>
                                                        <path d="M7.62003 9.42724V13.9739C7.62003 14.4806 7.10669 14.8139 6.65336 14.5939C5.28003 13.9206 2.96669 12.6606 2.96669 12.6606C2.15336 12.2006 1.48669 11.0406 1.48669 10.0872V6.64724C1.48669 6.12057 2.04003 5.78724 2.49336 6.04724L7.28669 8.82724C7.48669 8.9539 7.62003 9.18057 7.62003 9.42724Z" fill="#1F46B9"/>
                                                        <path d="M8.38 9.42724V13.9739C8.38 14.4806 8.89334 14.8139 9.34667 14.5939C10.72 13.9206 13.0333 12.6606 13.0333 12.6606C13.8467 12.2006 14.5133 11.0406 14.5133 10.0872V6.64724C14.5133 6.12057 13.96 5.78724 13.5067 6.04724L8.71334 8.82724C8.51334 8.9539 8.38 9.18057 8.38 9.42724Z" fill="#1F46B9"/>
                                                        </svg>
                                                    Barang telah Diterima
                                                </span>';
                                                    break;
                                                case '6':
                                                    echo '<span class="px-2 py-1.5 inline-flex items-center gap-x-1 text-xs font-medium bg-[#FEF9C3] text-[#A46319] rounded-full dark:bg-teal-500/10 dark:text-teal-500">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                                        <path d="M2 2V14.6667L4 13.3333L6 14.6667L8 13.3333L8.86667 13.9067C8.73333 13.52 8.66667 13.1 8.66667 12.6667C8.66708 12.0268 8.82086 11.3964 9.1151 10.8282C9.40935 10.2601 9.83549 9.77073 10.3578 9.40118C10.8801 9.03163 11.4834 8.79267 12.1171 8.70431C12.7509 8.61594 13.3965 8.68077 14 8.89333V2H2ZM11.3333 4.66667V6H4.66667V4.66667H11.3333ZM10 7.33333V8.66667H4.66667V7.33333H10ZM10.3333 12.6667L12.1667 14.6667L15.3333 11.4867L14.56 10.5467L12.1667 12.94L11.1067 11.88L10.3333 12.6667Z" fill="#A46319"/>
                                                        </svg>
                                                    Tagihan belum lunas
                                                </span>';
                                                    break;
                                                case '7':
                                                    echo '<span class="px-2 py-1.5 inline-flex items-center gap-x-1 text-xs font-medium bg-[#D6F9F3] text-[#13594E] rounded-full dark:bg-teal-500/10 dark:text-teal-500">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                                        <path d="M10 11.1267V8.66667H11V10.5467L12.6267 11.4867L12.1267 12.3533L10 11.1267ZM2 14.6667V2H14V7.4C14.8267 8.24 15.3333 9.39333 15.3333 10.6667C15.3333 13.2467 13.2467 15.3333 10.6667 15.3333C10.0242 15.3351 9.38844 15.2033 8.79967 14.9462C8.21091 14.6891 7.68205 14.3124 7.24667 13.84L6 14.6667L4 13.3333L2 14.6667ZM6.44667 8.66667C6.68667 8.16667 7 7.71333 7.4 7.33333H4.66667V8.66667H6.44667ZM11.3333 6V4.66667H4.66667V6H11.3333ZM10.6667 14C12.5067 14 14 12.5067 14 10.6667C14 8.82667 12.5067 7.33333 10.6667 7.33333C8.82667 7.33333 7.33333 8.82667 7.33333 10.6667C7.33333 12.5067 8.82667 14 10.6667 14Z" fill="#13594E"/>
                                                        </svg>
                                                        Tagihan telah Dibayar
                                                    </span>';
                                                    break;
                                                default:
                                                    echo '<span class="inline-flex items-center gap-x-1.5 py-1.5 px-3 rounded-full text-xs font-medium bg-[#F1F1F1]">
                                                    <span class="size-1.5 inline-block rounded-full bg-[#535353]"></span>
                                                         Tidak ada status
                                                    </span>';
                                                    break;
                                            }
                                            ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <!-- End Table -->

                    <!-- Footer -->
                    <div class="px-6 py-4 grid gap-3 md:flex md:justify-between md:items-center border-t border-gray-200 dark:border-neutral-700">
                        <!-- Pagination -->
                        <nav class="flex w-full justify-between items-center gap-x-1">
                            <!-- Previous Button -->
                            <div class="inline-flex gap-x-2">
                                <button type="button" class="min-h-[38px] min-w-[38px] py-2 px-2.5 inline-flex justify-center items-center gap-x-2 text-sm rounded-lg text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-white/10 dark:focus:bg-white/10" aria-label="Previous page" <?= $meta_data['page'] <= 1 ? 'disabled' : '' ?> onclick="window.location.href='/datamedis?page=<?= $meta_data['page'] - 1 ?>&size=<?= $meta_data['size'] ?>'">
                                    <svg class="flex-shrink-0 size-3.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="m15 18-6-6 6-6"></path>
                                    </svg>
                                    <span aria-hidden="true" class="hidden sm:block">Previous</span>
                                </button>
                            </div>

                            <!-- Page Numbers -->
                            <div class="flex items-center gap-x-1">
                                <?php
                                $total_pages = $meta_data['total'];
                                $current_page = $meta_data['page'];
                                $range = 2; // Number of pages to show before and after the current page
                                $show_items = ($range * 2) + 1;

                                if ($total_pages <= $show_items) {
                                    for ($i = 1; $i <= $total_pages; $i++) {
                                        echo '<button type="button" class="min-h-[38px] min-w-[38px] flex justify-center items-center ' . ($current_page == $i ? 'bg-gray-200 text-gray-800 dark:bg-neutral-600 dark:focus:bg-neutral-500' : 'text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:text-white dark:hover:bg-white/10 dark:focus:bg-white/10') . ' py-2 px-3 text-sm rounded-lg" ' . ($current_page == $i ? 'aria-current="page"' : '') . ' onclick="window.location.href=\'/datamedis?page=' . $i . '&size=' . $meta_data['size'] . '\'">' . $i . '</button>';
                                    }
                                } else {
                                    if ($current_page > $range + 1) {
                                        echo '<button type="button" class="min-h-[38px] min-w-[38px] flex justify-center items-center text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:text-white dark:hover:bg-white/10 dark:focus:bg-white/10 py-2 px-3 text-sm rounded-lg" onclick="window.location.href=\'/datamedis?page=1&size=' . $meta_data['size'] . '\'">1</button>';
                                        if ($current_page > $range + 2) {
                                            echo '<span class="py-2 px-3 text-sm">...</span>';
                                        }
                                    }

                                    for ($i = max($current_page - $range, 1); $i <= min($current_page + $range, $total_pages); $i++) {
                                        echo '<button type="button" class="min-h-[38px] min-w-[38px] flex justify-center items-center ' . ($current_page == $i ? 'bg-gray-200 text-gray-800 dark:bg-neutral-600 dark:focus:bg-neutral-500' : 'text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:text-white dark:hover:bg-white/10 dark:focus:bg-white/10') . ' py-2 px-3 text-sm rounded-lg" ' . ($current_page == $i ? 'aria-current="page"' : '') . ' onclick="window.location.href=\'/datamedis?page=' . $i . '&size=' . $meta_data['size'] . '\'">' . $i . '</button>';
                                    }

                                    if ($current_page < $total_pages - $range - 1) {
                                        if ($current_page < $total_pages - $range - 2) {
                                            echo '<span class="py-2 px-3 text-sm">...</span>';
                                        }
                                        echo '<button type="button" class="min-h-[38px] min-w-[38px] flex justify-center items-center text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:text-white dark:hover:bg-white/10 dark:focus:bg-white/10 py-2 px-3 text-sm rounded-lg" onclick="window.location.href=\'/datamedis?page=' . $total_pages . '&size=' . $meta_data['size'] . '\'">' . $total_pages . '</button>';
                                    }
                                }
                                ?>
                            </div>

                            <!-- Next Button -->
                            <div class="inline-flex gap-x-2">
                                <button type="button" class="min-h-[38px] min-w-[38px] py-2 px-2.5 inline-flex justify-center items-center gap-x-2 text-sm rounded-lg text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-white/10 dark:focus:bg-white/10" aria-label="Next page" <?= $current_page >= $total_pages ? 'disabled' : '' ?> onclick="window.location.href='/datamedis?page=<?= $current_page + 1 ?>&size=<?= $meta_data['size'] ?>'">
                                    <span aria-hidden="true" class="hidden sm:block">Next</span>
                                    <svg class="flex-shrink-0 size-3.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="m9 18 6-6-6-6"></path>
                                    </svg>
                                </button>
                            </div>
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
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    function myFunction() {
        var input, filter, table, tr, td, i, j, txtValue;
        input = document.getElementById("myInput");
        filter = input.value.toUpperCase();
        table = document.getElementById("myTable");
        tr = table.getElementsByTagName("tr");
        th = table.getElementsByTagName("th"); // Get all th elements

        var dataFound = false;

        // Iterate over table rows (including header row)
        for (i = 0; i < tr.length; i++) {
            var found = false;

            // Check if it's a header row (th elements)
            if (i === 0) {
                // Iterate over th elements
                for (j = 0; j < th.length; j++) {
                    txtValue = th[j].textContent || th[j].innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        found = true;
                        break;
                    }
                }
            } else {
                // Iterate over td elements in regular rows
                td = tr[i].getElementsByTagName("td");
                for (j = 0; j < td.length; j++) {
                    txtValue = td[j].textContent || td[j].innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        found = true;
                        break;
                    }
                }
            }

            if (found) {
                tr[i].style.display = "";
                dataFound = true;
            } else {
                tr[i].style.display = "none";
            }
        }

        // Show/hide message if no data found
        if (!dataFound) {
            document.getElementById("noDataFound").style.display = "block";
        } else {
            document.getElementById("noDataFound").style.display = "none";
        }
    }

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

    // Donat chart
    var dataDoughnut = <?php echo $dataDoughnutJSON; ?>;

    const configDoughnut = {
        type: "doughnut",
        data: dataDoughnut,
        options: {
            cutout: '60%',
            plugins: {
                legend: {
                    display: false,
                },
            },
        },
    };

    var chartBar = new Chart(
        document.getElementById("chartDoughnut"),
        configDoughnut
    );

    var dataBaru = <?php echo $dataBaruJSON; ?>;

    // Konfigurasi Chart.js untuk chart baru
    var configBaru = {
        type: "doughnut",
        data: dataBaru,
        options: {
            plugins: {
                legend: {
                    display: false,
                },
            },
        },
    };

    // Gambar chart baru menggunakan Chart.js
    var chartBaru = new Chart(
        document.getElementById("chartBaru"),
        configBaru
    );
</script>
<?= $this->endSection(); ?>