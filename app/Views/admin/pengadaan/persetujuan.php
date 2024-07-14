<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<!-- Table Section -->
<div class="max-w-[85rem] py-6 lg:py-3 px-8 mx-auto">
    <!-- Card -->
    <div class="flex flex-col ">
        <div class="-m-1.5">
            <div class="sm:px-6 min-w-full inline-block align-middle">

                <div class="p-5 bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-slate-900 dark:border-gray-700">
                    <!-- Header -->

                    <div class="py-1 flex justify-between items-center border-gray-200 dark:border-gray-700">
                        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">
                            Persetujuan Barang Medis
                        </h2>
                    </div>
                    <!-- End Header -->
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

                    <div id="noDataFound" class="hidden">Data tidak ditemukan</div>
                    <!-- Table -->
                    <table id="myTable" class="overflow-x-auto min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <colgroup>
                            <col width="13%">
                            <col width="19%">
                            <col width="22%">
                            <col width="22%">
                            <col width="24%">
                        </colgroup>
                        <thead class="bg-gray-50 dark:bg-slate-800">
                            <tr>
                                <th scope="col" class="px-6 pe-6 py-3">
                                    <div class="flex items-center justify-center gap-x-2">
                                        <span class="text-xs font-semibold tracking-wide text-[#666] dark:text-gray-200">
                                            Tanggal
                                        </span>
                                    </div>
                                </th>

                                <th scope="col" class="px-6 py-3">
                                    <div class="flex items-center justify-center gap-x-2">
                                        <span class="text-xs font-semibold tracking-wide text-[#666] dark:text-gray-200">
                                            Nomor Pengajuan
                                        </span>
                                    </div>
                                </th>

                                <th scope="col" class="px-6 py-3">
                                    <div class="flex items-center justify-center gap-x-2">
                                        <span class="text-xs font-semibold tracking-wide text-[#666] dark:text-gray-200">
                                            Status Apoteker
                                        </span>
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    <div class="flex items-center justify-center gap-x-2">
                                        <span class="text-xs font-semibold tracking-wide text-[#666] dark:text-gray-200">
                                            Status Keuangan
                                        </span>
                                    </div>
                                </th>

                                <th scope="col" class="px-6 py-3">
                                    <div class="flex items-center justify-center gap-x-2">
                                        <span class="text-xs font-semibold tracking-wide text-[#666] dark:text-gray-200">
                                            Aksi
                                        </span>
                                    </div>
                                </th>



                                <!-- <th scope="col" class="px-6 py-3 text-end"></th>
                                <th scope="col" class="px-6 py-3 text-end"></th>
                                <th scope="col" class="px-6 py-3 text-end"></th> -->
                            </tr>
                        </thead>




                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            <?php foreach ($pengajuan_medis_data as $pengajuan) : ?>
                                <?php foreach ($persetujuan_data as $persetujuan) : ?>
                                    <?php if ($persetujuan['id_pengajuan'] === $pengajuan['id']) : ?>

                                        <tr>
                                            <td>
                                                <div class="px-6 py-3">
                                                    <div class="flex items-center gap-x-3 justify-center">
                                                        <span class="block text-sm font-semibold text-gray-800 dark:text-gray-200"><?php
                                                                                                                                    $original_date = $pengajuan['tanggal_pengajuan'];
                                                                                                                                    $day = date("d", strtotime($original_date));
                                                                                                                                    $month = date("m", strtotime($original_date));
                                                                                                                                    $year = date("Y", strtotime($original_date));

                                                                                                                                    // Daftar nama bulan dalam bahasa Indonesia
                                                                                                                                    $bulan = array(
                                                                                                                                        1 => "Januari", 2 => "Februari", 3 => "Maret", 4 => "April", 5 => "Mei", 6 => "Juni",
                                                                                                                                        7 => "Juli", 8 => "Agustus", 9 => "September", 10 => "Oktober", 11 => "November", 12 => "Desember"
                                                                                                                                    );

                                                                                                                                    // Format tanggal sesuai dengan format yang diinginkan
                                                                                                                                    $formatted_date = $day . ' ' . $bulan[(int)$month] . ' ' . $year;

                                                                                                                                    echo $formatted_date; ?></span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="px-6 py-3">
                                                    <span class="text-center block text-sm font-semibold text-gray-800 dark:text-gray-200">
                                                        <a class="pengajuan-link cursor-pointer hover:underline" data-hs-overlay="#hs-vertically-centered-scrollable-modal-<?= $pengajuan['id'] ?>" data-id="<?= $pengajuan['id'] ?>">
                                                            <?= $pengajuan['nomor_pengajuan'] ?? 'N/A' ?>
                                                        </a>
                                                    </span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="px-6 py-3 text-center">
                                                    <?php
                                                    switch ($persetujuan['status_apoteker']) {
                                                        case 'Menunggu Persetujuan':
                                                            echo '<span class="py-1 px-1.5 inline-flex items-center gap-x-1 text-xs font-semibold bg-[#FEF9C3] text-[#F49A35] rounded-full">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                                            <path d="M8.00004 14.6673C11.6819 14.6673 14.6667 11.6825 14.6667 8.00065C14.6667 4.31875 11.6819 1.33398 8.00004 1.33398C4.31814 1.33398 1.33337 4.31875 1.33337 8.00065C1.33337 11.6825 4.31814 14.6673 8.00004 14.6673Z" fill="#A46319" />
                                                            <path d="M10.4733 10.6192C10.3867 10.6192 10.3 10.5992 10.22 10.5459L8.15334 9.31253C7.64001 9.00586 7.26001 8.33253 7.26001 7.73919V5.00586C7.26001 4.73253 7.48668 4.50586 7.76001 4.50586C8.03334 4.50586 8.26001 4.73253 8.26001 5.00586V7.73919C8.26001 7.97919 8.46001 8.33253 8.66668 8.45253L10.7333 9.68586C10.9733 9.82586 11.0467 10.1325 10.9067 10.3725C10.8067 10.5325 10.64 10.6192 10.4733 10.6192Z" fill="#FEF9C3" />
                                                        </svg>
                                                        Menunggu Persetujuan
                                                        </span>';
                                                            break;
                                                        case 'Disetujui':
                                                            echo '<span class="py-1 px-1.5 inline-flex items-center gap-x-1 text-xs font-semibold bg-[#D6F9F3] text-[#13594E] rounded-full">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                                        <path d="M8.00004 14.6673C11.6819 14.6673 14.6667 11.6825 14.6667 8.00065C14.6667 4.31875 11.6819 1.33398 8.00004 1.33398C4.31814 1.33398 1.33337 4.31875 1.33337 8.00065C1.33337 11.6825 4.31814 14.6673 8.00004 14.6673Z" fill="#13594E"/>
                                                        <path d="M7.05334 10.3858C6.92 10.3858 6.79334 10.3324 6.7 10.2391L4.81333 8.35242C4.62 8.15909 4.62 7.83909 4.81333 7.64576C5.00667 7.45242 5.32667 7.45242 5.52 7.64576L7.05334 9.17909L10.48 5.75242C10.6733 5.55909 10.9933 5.55909 11.1867 5.75242C11.38 5.94576 11.38 6.26575 11.1867 6.45909L7.40667 10.2391C7.31334 10.3324 7.18667 10.3858 7.05334 10.3858Z" fill="#D6F9F3"/>
                                                        </svg>
                                                        Pengajuan Disetujui
                                                        </span>';
                                                            break;
                                                        case 'Ditolak':
                                                            echo '<span class="py-1 px-1.5 inline-flex items-center gap-x-1 text-xs font-semibold bg-[#FEE2E2] text-[#991B1B] rounded-full">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                                        <path d="M8.00004 14.6673C11.6819 14.6673 14.6667 11.6825 14.6667 8.00065C14.6667 4.31875 11.6819 1.33398 8.00004 1.33398C4.31814 1.33398 1.33337 4.31875 1.33337 8.00065C1.33337 11.6825 4.31814 14.6673 8.00004 14.6673Z" fill="#991B1B"/>
                                                        <path d="M8.70666 8.00023L10.24 6.4669C10.4333 6.27357 10.4333 5.95357 10.24 5.76023C10.0467 5.5669 9.72666 5.5669 9.53332 5.76023L7.99999 7.29357L6.46666 5.76023C6.27332 5.5669 5.95332 5.5669 5.75999 5.76023C5.56666 5.95357 5.56666 6.27357 5.75999 6.4669L7.29332 8.00023L5.75999 9.53357C5.56666 9.7269 5.56666 10.0469 5.75999 10.2402C5.85999 10.3402 5.98666 10.3869 6.11332 10.3869C6.23999 10.3869 6.36666 10.3402 6.46666 10.2402L7.99999 8.7069L9.53332 10.2402C9.63332 10.3402 9.75999 10.3869 9.88666 10.3869C10.0133 10.3869 10.14 10.3402 10.24 10.2402C10.4333 10.0469 10.4333 9.7269 10.24 9.53357L8.70666 8.00023Z" fill="#FEE2E2"/>
                                                            </svg>
                                                      Pengajuan Ditolak
                                                    </span>';
                                                            break;

                                                        default:
                                                            echo '<span class="py-1 px-1.5 inline-flex items-center gap-x-1 text-xs font-semibold bg-[#FEF9C3] text-[#F49A35] rounded-full">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                                            <path d="M8.00004 14.6673C11.6819 14.6673 14.6667 11.6825 14.6667 8.00065C14.6667 4.31875 11.6819 1.33398 8.00004 1.33398C4.31814 1.33398 1.33337 4.31875 1.33337 8.00065C1.33337 11.6825 4.31814 14.6673 8.00004 14.6673Z" fill="#A46319" />
                                                            <path d="M10.4733 10.6192C10.3867 10.6192 10.3 10.5992 10.22 10.5459L8.15334 9.31253C7.64001 9.00586 7.26001 8.33253 7.26001 7.73919V5.00586C7.26001 4.73253 7.48668 4.50586 7.76001 4.50586C8.03334 4.50586 8.26001 4.73253 8.26001 5.00586V7.73919C8.26001 7.97919 8.46001 8.33253 8.66668 8.45253L10.7333 9.68586C10.9733 9.82586 11.0467 10.1325 10.9067 10.3725C10.8067 10.5325 10.64 10.6192 10.4733 10.6192Z" fill="#FEF9C3" />
                                                        </svg>
                                                        Menunggu Persetujuan
                                                    </span>';
                                                            break;
                                                    }
                                                    ?>

                                                </div>
                                            </td>
                                            <td>
                                                <div class="px-6 py-3 text-center">
                                                    <?php
                                                    switch ($persetujuan['status_keuangan']) {
                                                        case 'Menunggu Persetujuan':
                                                            echo '<span class="py-1 px-1.5 inline-flex items-center gap-x-1 text-xs font-semibold bg-[#FEF9C3] text-[#F49A35] rounded-full">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                                            <path d="M8.00004 14.6673C11.6819 14.6673 14.6667 11.6825 14.6667 8.00065C14.6667 4.31875 11.6819 1.33398 8.00004 1.33398C4.31814 1.33398 1.33337 4.31875 1.33337 8.00065C1.33337 11.6825 4.31814 14.6673 8.00004 14.6673Z" fill="#A46319" />
                                                            <path d="M10.4733 10.6192C10.3867 10.6192 10.3 10.5992 10.22 10.5459L8.15334 9.31253C7.64001 9.00586 7.26001 8.33253 7.26001 7.73919V5.00586C7.26001 4.73253 7.48668 4.50586 7.76001 4.50586C8.03334 4.50586 8.26001 4.73253 8.26001 5.00586V7.73919C8.26001 7.97919 8.46001 8.33253 8.66668 8.45253L10.7333 9.68586C10.9733 9.82586 11.0467 10.1325 10.9067 10.3725C10.8067 10.5325 10.64 10.6192 10.4733 10.6192Z" fill="#FEF9C3" />
                                                        </svg>
                                                        Menunggu Persetujuan
                                                    </span>';
                                                            break;
                                                        case 'Disetujui':
                                                            echo '<span class="py-1 px-1.5 inline-flex items-center gap-x-1 text-xs font-semibold bg-[#D6F9F3] text-[#13594E] rounded-full">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                                        <path d="M8.00004 14.6673C11.6819 14.6673 14.6667 11.6825 14.6667 8.00065C14.6667 4.31875 11.6819 1.33398 8.00004 1.33398C4.31814 1.33398 1.33337 4.31875 1.33337 8.00065C1.33337 11.6825 4.31814 14.6673 8.00004 14.6673Z" fill="#13594E"/>
                                                        <path d="M7.05334 10.3858C6.92 10.3858 6.79334 10.3324 6.7 10.2391L4.81333 8.35242C4.62 8.15909 4.62 7.83909 4.81333 7.64576C5.00667 7.45242 5.32667 7.45242 5.52 7.64576L7.05334 9.17909L10.48 5.75242C10.6733 5.55909 10.9933 5.55909 11.1867 5.75242C11.38 5.94576 11.38 6.26575 11.1867 6.45909L7.40667 10.2391C7.31334 10.3324 7.18667 10.3858 7.05334 10.3858Z" fill="#D6F9F3"/>
                                                        </svg>
                                                       Pengajuan Disetujui
                                                    </span>';
                                                            break;
                                                        case 'Ditolak':
                                                            echo '<span class="py-1 px-1.5 inline-flex items-center gap-x-1 text-xs font-semibold bg-[#FEE2E2] text-[#991B1B] rounded-full">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M8.00004 14.6673C11.6819 14.6673 14.6667 11.6825 14.6667 8.00065C14.6667 4.31875 11.6819 1.33398 8.00004 1.33398C4.31814 1.33398 1.33337 4.31875 1.33337 8.00065C1.33337 11.6825 4.31814 14.6673 8.00004 14.6673Z" fill="#991B1B"/><path d="M8.70666 8.00023L10.24 6.4669C10.4333 6.27357 10.4333 5.95357 10.24 5.76023C10.0467 5.5669 9.72666 5.5669 9.53332 5.76023L7.99999 7.29357L6.46666 5.76023C6.27332 5.5669 5.95332 5.5669 5.75999 5.76023C5.56666 5.95357 5.56666 6.27357 5.75999 6.4669L7.29332 8.00023L5.75999 9.53357C5.56666 9.7269 5.56666 10.0469 5.75999 10.2402C5.85999 10.3402 5.98666 10.3869 6.11332 10.3869C6.23999 10.3869 6.36666 10.3402 6.46666 10.2402L7.99999 8.7069L9.53332 10.2402C9.63332 10.3402 9.75999 10.3869 9.88666 10.3869C10.0133 10.3869 10.14 10.3402 10.24 10.2402C10.4333 10.0469 10.4333 9.7269 10.24 9.53357L8.70666 8.00023Z" fill="#FEE2E2"/></svg>
                                                      Pengajuan Ditolak
                                                    </span>';
                                                            break;

                                                        default:
                                                            echo '<span class="py-1 px-1.5 inline-flex items-center gap-x-1 text-xs font-semibold bg-[#FEF9C3] text-[#F49A35] rounded-full">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                                            <path d="M8.00004 14.6673C11.6819 14.6673 14.6667 11.6825 14.6667 8.00065C14.6667 4.31875 11.6819 1.33398 8.00004 1.33398C4.31814 1.33398 1.33337 4.31875 1.33337 8.00065C1.33337 11.6825 4.31814 14.6673 8.00004 14.6673Z" fill="#A46319" />
                                                            <path d="M10.4733 10.6192C10.3867 10.6192 10.3 10.5992 10.22 10.5459L8.15334 9.31253C7.64001 9.00586 7.26001 8.33253 7.26001 7.73919V5.00586C7.26001 4.73253 7.48668 4.50586 7.76001 4.50586C8.03334 4.50586 8.26001 4.73253 8.26001 5.00586V7.73919C8.26001 7.97919 8.46001 8.33253 8.66668 8.45253L10.7333 9.68586C10.9733 9.82586 11.0467 10.1325 10.9067 10.3725C10.8067 10.5325 10.64 10.6192 10.4733 10.6192Z" fill="#FEF9C3" />
                                                        </svg>
                                                        Menunggu Persetujuan
                                                    </span>';
                                                            break;
                                                    }
                                                    ?>
                                                </div>
                                            </td>


                                            <td>
                                                <form action="/persetujuanpengajuan/submit/<?= $persetujuan['id_pengajuan'] ?>" method="POST">
                                                    <div class="pl-6 py-1.5 inline-flex">
                                                        <div class="pr-3 py-1.5">
                                                            <button type="button" class="gap-x-1 text-sm decoration-2 hover:underline font-semibold dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" data-hs-overlay="#hs-vertically-centered-scrollable-modal-<?= $persetujuan['id_pengajuan'] ?>">
                                                                Lihat Detail
                                                            </button>
                                                        </div>
                                                        <?php if ($user_data['role'] === 4001 && $persetujuan['status_apoteker'] === 'Menunggu Persetujuan' || $user_data['role'] === 5001 && $persetujuan['status_keuangan'] === 'Menunggu Persetujuan') : ?>
                                                            <?php
                                                            if ($persetujuan['id_pengajuan'] === $pengajuan['id']) {
                                                                if ($user_data['role'] === 4001 || $user_data['role'] === 5001) {
                                                                    echo '<input type="hidden" value="' . $persetujuan['status'] . '" name="statuspersetujuan">';
                                                                    echo '<input type="hidden" value="' . $pengajuan['id'] . '" name="idpengajuan">';
                                                                    if ($user_data['role'] === 4001) {
                                                                        echo '<input type="hidden" value="' . $user_data['id'] . '" name="idapoteker">';
                                                                        echo '<input type="hidden" value="' . $persetujuan['status_keuangan'] . '" name="statuskeuangan">';
                                                                        echo '<input type="hidden" value="' . $persetujuan['id_keuangan'] . '" name="idkeuangan">';
                                                                    } elseif ($user_data['role'] === 5001) {
                                                                        echo '<input type="hidden" value="' . $user_data['id'] . '" name="idkeuangan">';
                                                                        echo '<input type="hidden" value="' . $persetujuan['status_apoteker'] . '" name="statusapoteker">';
                                                                        echo '<input type="hidden" value="' . $persetujuan['id_apoteker'] . '" name="idapoteker">';
                                                                    } else {
                                                                        echo '<p>Hanya apoteker atau keuangan yang bisa melakukan persetujuan.</p>';
                                                                    }
                                                                }
                                                            }
                                                            ?>
                                                            <div class="px-3 py-1.5">
                                                                <button type="button" onclick="event.preventDefault(); openModal('modelSetuju-<?= $pengajuan['id'] ?>')" class="gap-x-1 text-sm text-[#24A793] decoration-2 hover:underline font-semibold dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                                                                    Setuju
                                                                </button>
                                                                <div id="modelSetuju-<?= $pengajuan['id'] ?>" class="fixed hidden z-[100] inset-0 bg-gray-900 bg-opacity-60 overflow-y-auto h-full w-full px-4 ">
                                                                    <div class="relative top-40 mx-auto shadow-xl rounded-md bg-white max-w-md">

                                                                        <div class="flex justify-end p-2">
                                                                            <button onclick="closeModal('modelSetuju-<?= $pengajuan['id'] ?>')" type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center">
                                                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                                                                </svg>
                                                                            </button>
                                                                        </div>

                                                                        <div class="p-6 pt-0 text-center">
                                                                            <div class="flex justify-center mb-6">
                                                                                <!-- Container for SVG, centered -->
                                                                                <svg xmlns="http://www.w3.org/2000/svg" width="43" height="42" viewBox="0 0 43 42" fill="none">
                                                                                    <path d="M23.9062 38.1938L12.75 35.0438V19.25H15.55C15.7542 19.25 15.9583 19.2722 16.1625 19.3165C16.3667 19.3608 16.5708 19.4116 16.775 19.4688L28.8937 23.975C29.3021 24.1208 29.6305 24.3833 29.879 24.7625C30.1275 25.1417 30.2512 25.5354 30.25 25.9438C30.25 26.5563 30.0382 27.0521 29.6147 27.4313C29.1912 27.8104 28.703 28 28.15 28H23.5562C23.4104 28 23.3013 27.993 23.229 27.979C23.1567 27.965 23.0616 27.9283 22.9437 27.8688L20.975 27.125C20.7417 27.0375 20.5083 27.0521 20.275 27.1688C20.0417 27.2854 19.8958 27.4458 19.8375 27.65C19.7792 27.8833 19.7937 28.1021 19.8812 28.3063C19.9687 28.5104 20.1292 28.6563 20.3625 28.7438L22.9437 29.6625C23.0021 29.6917 23.0896 29.7138 23.2062 29.729C23.3229 29.7442 23.425 29.7512 23.5125 29.75H35.5C36.4333 29.75 37.25 30.0854 37.95 30.7563C38.65 31.4271 39 32.2583 39 33.25L26.0937 38.1063C25.8021 38.2229 25.4451 38.2888 25.0227 38.304C24.6004 38.3192 24.2282 38.2824 23.9062 38.1938ZM2.25 35V22.75C2.25 21.7875 2.593 20.9638 3.279 20.279C3.965 19.5942 4.78867 19.2512 5.75 19.25C6.71133 19.2488 7.53558 19.5918 8.22275 20.279C8.90992 20.9662 9.25233 21.7898 9.25 22.75V35C9.25 35.9625 8.90758 36.7868 8.22275 37.4728C7.53792 38.1588 6.71367 38.5012 5.75 38.5C4.78633 38.4988 3.96267 38.1564 3.279 37.4728C2.59533 36.7891 2.25233 35.9648 2.25 35Z" fill="#0A2D27" />
                                                                                    <path d="M31.8248 5.72955L23.1186 14.3921L19.3998 10.6733C19.0498 10.3221 18.6415 10.1541 18.1748 10.1693C17.7081 10.1845 17.2998 10.3525 16.9498 10.6733C16.5986 11.0233 16.4161 11.4316 16.4021 11.8983C16.3881 12.365 16.5561 12.7733 16.9061 13.1233L21.8936 18.1108C22.2436 18.4608 22.6519 18.6358 23.1186 18.6358C23.5852 18.6358 23.9936 18.4608 24.3436 18.1108L34.2748 8.17955C34.5956 7.85872 34.7561 7.45038 34.7561 6.95455C34.7561 6.45872 34.5956 6.05038 34.2748 5.72955C33.926 5.37838 33.5106 5.21038 33.0288 5.22555C32.547 5.24072 32.1456 5.40872 31.8248 5.72955Z" fill="#26B29D" />
                                                                                </svg>
                                                                            </div>
                                                                            <h3 class="font-semibold">Menyetujui Pengajuan Barang Medis</h3>
                                                                            <p class="text-wrap font-normal text-gray-500 mt-5 mb-6">Apakah anda yakin
                                                                                untuk menyetujui pengajuan barang medis?</p>


                                                                            <div class="w-full sm:flex justify-center">

                                                                                <a href="#" onclick="closeModal('modelSetuju-<?= $pengajuan['id'] ?>')" class="w-full text-gray-900 bg-white hover:bg-gray-100 focus:ring-4 border border-gray-200 font-medium inline-flex items-center justify-center rounded-lg text-base px-3 py-2.5 text-center mr-2" data-modal-toggle="delete-user-modal">
                                                                                    Batal
                                                                                </a>
                                                                                <button onclick="closeModal('modelSetuju-<?= $pengajuan['id'] ?>')" value="Disetujui" name="<?php echo ($user_data['role'] === 4001 ? 'statusapoteker' : 'statuskeuangan'); ?>" class="w-full text-[#ACF2E7] bg-[#0A2D27] hover:bg-[#13594E] focus:ring-4 font-medium rounded-lg text-base inline-flex items-center justify-center px-3 py-2.5 text-center ">
                                                                                    Setuju
                                                                                </button>

                                                                            </div>

                                                                        </div>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="px-3 py-1.5">
                                                                <button href="#" onclick="event.preventDefault(); openModal('modelTolak-<?= $pengajuan['id'] ?>')" class="gap-x-1 text-sm text-[#CF5454] decoration-2 hover:underline font-semibold dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                                                                    Tolak
                                                                </button>
                                                                <div id="modelTolak-<?= $pengajuan['id'] ?>" class="fixed hidden z-[100] inset-0 bg-gray-900 bg-opacity-60 overflow-y-auto h-full w-full px-4 ">
                                                                    <div class="relative top-40 mx-auto shadow-xl rounded-md bg-white max-w-md">

                                                                        <div class="flex justify-end p-2">
                                                                            <button onclick="closeModal('modelTolak-<?= $pengajuan['id'] ?>')" type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center">
                                                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                                                                </svg>
                                                                            </button>
                                                                        </div>

                                                                        <div class="p-6 pt-0 text-center">
                                                                            <div class="flex justify-center mb-6">
                                                                                <!-- Container for SVG, centered -->
                                                                                <svg xmlns="http://www.w3.org/2000/svg" width="42" height="42" viewBox="0 0 42 42" fill="none">
                                                                                    <path d="M21 17.5C24.866 17.5 28 14.366 28 10.5C28 6.63401 24.866 3.5 21 3.5C17.134 3.5 14 6.63401 14 10.5C14 14.366 17.134 17.5 21 17.5Z" fill="#DA4141" />
                                                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M28.875 38.5C25.9875 38.5 24.5437 38.5 23.6477 37.6022C22.75 36.7062 22.75 35.2625 22.75 32.375C22.75 29.4875 22.75 28.0437 23.6477 27.1477C24.5437 26.25 25.9875 26.25 28.875 26.25C31.7625 26.25 33.2062 26.25 34.1022 27.1477C35 28.0437 35 29.4875 35 32.375C35 35.2625 35 36.7062 34.1022 37.6022C33.2062 38.5 31.7625 38.5 28.875 38.5ZM26.8748 28.931C26.6833 28.7395 26.4236 28.632 26.1529 28.632C25.8821 28.632 25.6225 28.7395 25.431 28.931C25.2395 29.1225 25.132 29.3821 25.132 29.6529C25.132 29.9236 25.2395 30.1833 25.431 30.3748L27.4313 32.375L25.431 34.3752C25.3362 34.47 25.261 34.5826 25.2097 34.7064C25.1584 34.8303 25.132 34.9631 25.132 35.0971C25.132 35.2312 25.1584 35.3639 25.2097 35.4878C25.261 35.6117 25.3362 35.7242 25.431 35.819C25.5258 35.9138 25.6383 35.989 25.7622 36.0403C25.8861 36.0916 26.0188 36.118 26.1529 36.118C26.2869 36.118 26.4197 36.0916 26.5436 36.0403C26.6674 35.989 26.78 35.9138 26.8748 35.819L28.875 33.8188L30.8752 35.819C30.97 35.9138 31.0826 35.989 31.2064 36.0403C31.3303 36.0916 31.4631 36.118 31.5971 36.118C31.7312 36.118 31.8639 36.0916 31.9878 36.0403C32.1117 35.989 32.2242 35.9138 32.319 35.819C32.4138 35.7242 32.489 35.6117 32.5403 35.4878C32.5916 35.3639 32.618 35.2312 32.618 35.0971C32.618 34.9631 32.5916 34.8303 32.5403 34.7064C32.489 34.5826 32.4138 34.47 32.319 34.3752L30.3188 32.375L32.319 30.3748C32.5105 30.1833 32.618 29.9236 32.618 29.6529C32.618 29.3821 32.5105 29.1225 32.319 28.931C32.1275 28.7395 31.8679 28.632 31.5971 28.632C31.3264 28.632 31.0667 28.7395 30.8752 28.931L28.875 30.9312L26.8748 28.931Z" fill="#DA4141" />
                                                                                    <path d="M31.6662 26.3043C30.9225 26.25 30.0107 26.25 28.875 26.25C25.9875 26.25 24.5437 26.25 23.6477 27.1477C22.75 28.0437 22.75 29.4875 22.75 32.375C22.75 34.4155 22.75 35.735 23.0667 36.6503C22.3947 36.7168 21.7052 36.75 21 36.75C14.2345 36.75 8.75 33.6175 8.75 29.75C8.75 25.8825 14.2345 22.75 21 22.75C25.5728 22.75 29.561 24.1815 31.6662 26.3043Z" fill="#FF9797" />
                                                                                </svg>
                                                                            </div>
                                                                            <h3 class="font-semibold">Menolak Pengajuan Barang Medis</h3>
                                                                            <p class="text-wrap font-normal text-gray-500 mt-5 mb-6">Apakah anda yakin
                                                                                untuk menolak pengajuan barang medis?</p>


                                                                            <div class="w-full sm:flex justify-center">

                                                                                <button onclick="closeModal('modelTolak-<?= $pengajuan['id'] ?>')" value="Ditolak" name="<?php echo ($user_data['role'] === 4001 ? 'statusapoteker' : 'statuskeuangan'); ?>" class="w-full text-white bg-red-600 hover:bg-red-800 focus:ring-4 font-medium rounded-lg text-base inline-flex items-center justify-center px-3 py-2.5 text-center mr-2">
                                                                                    Tolak
                                                                                </button>

                                                                                <a href="#" onclick="closeModal('modelTolak-<?= $pengajuan['id'] ?>')" class="w-full text-gray-900 bg-white hover:bg-gray-100 focus:ring-4 border border-gray-200 font-medium inline-flex items-center justify-center rounded-lg text-base px-3 py-2.5 text-center" data-modal-toggle="delete-user-modal">
                                                                                    Batal
                                                                                </a>
                                                                            </div>

                                                                        </div>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                </form>
                                            <?php else : ?>
                                                <div class="px-3 py-1.5">
                                                    <a href="#" class="gap-x-1 text-sm text-[#C4C4C4] decoration-2 font-semibold cursor-default dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" href="#">
                                                        Setuju
                                                    </a>
                                                </div>
                                                <div class="px-3 py-1.5">
                                                    <a href="#" class="gap-x-1 text-sm text-[#C4C4C4] decoration-2 font-semibold cursor-default dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" href="#">
                                                        Tolak
                                                    </a>
                                                </div>
                                            <?php endif; ?>


                                            </td>
                                            <div id="hs-vertically-centered-scrollable-modal-<?= $pengajuan['id'] ?>" class="hs-overlay hidden size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto pointer-events-none">
                                                <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto h-[calc(100%-3.5rem)] min-h-[calc(100%-3.5rem)] flex items-center">
                                                    <div class="w-full max-h-full overflow-hidden flex flex-col bg-white border shadow-sm rounded-xl pointer-events-auto dark:bg-neutral-800 dark:border-neutral-700 dark:shadow-neutral-700/70">
                                                        <div class="flex justify-between items-center py-3 px-4 border-b dark:border-neutral-700">
                                                            <h3 class="font-bold text-gray-800 dark:text-white">
                                                                <?= $pengajuan['nomor_pengajuan'] ?>
                                                            </h3>
                                                            <button type="button" class="flex justify-center items-center size-7 text-sm font-semibold rounded-full border border-transparent text-gray-800 hover:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-neutral-700" data-hs-overlay="#hs-vertically-centered-scrollable-modal-<?= $pengajuan['id'] ?>">
                                                                <span class="sr-only">Close</span>
                                                                <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                                    <path d="M18 6 6 18"></path>
                                                                    <path d="m6 6 12 12"></path>
                                                                </svg>
                                                            </button>
                                                        </div>
                                                        <div class="p-4 overflow-y-auto">
                                                            <div class="space-y-12">
                                                                <div>
                                                                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white"></h3>
                                                                    <div class="mb-3 sm:block md:flex items-center">
                                                                        <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Tanggal</label>
                                                                        <input type="text" name="" value="<?php
                                                                                                            // Tanggal asli dari data
                                                                                                            $original_date = $pengajuan['tanggal_pengajuan'];

                                                                                                            // Jika tanggal adalah "0001-01-01", tampilkan tanda hubung "-"
                                                                                                            if ($original_date === "0001-01-01") {
                                                                                                                echo "-";
                                                                                                            } else {
                                                                                                                // Format tanggal sebagai dd-Bulan-yyyy (misal: 27 Juni 2024)

                                                                                                                // Pisahkan tanggal, bulan, dan tahun dari tanggal asli
                                                                                                                $day = date("d", strtotime($original_date));
                                                                                                                $month = date("m", strtotime($original_date));
                                                                                                                $year = date("Y", strtotime($original_date));

                                                                                                                // Daftar nama bulan dalam bahasa Indonesia
                                                                                                                $bulan = array(
                                                                                                                    1 => "Januari", 2 => "Februari", 3 => "Maret", 4 => "April", 5 => "Mei", 6 => "Juni",
                                                                                                                    7 => "Juli", 8 => "Agustus", 9 => "September", 10 => "Oktober", 11 => "November", 12 => "Desember"
                                                                                                                );

                                                                                                                // Format tanggal sesuai dengan format yang diinginkan
                                                                                                                $formatted_date = $day . ' ' . $bulan[(int)$month] . ' ' . $year;

                                                                                                                echo $formatted_date;
                                                                                                            }
                                                                                                            ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
                                                                    </div>
                                                                    <div class="mb-3 sm:block md:flex items-center">
                                                                        <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Nomor
                                                                            Pengajuan</label>
                                                                        <input type="text" name="" value="<?= $pengajuan['nomor_pengajuan'] ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
                                                                    </div>
                                                                    <div class="mb-3 sm:block md:flex items-center">
                                                                        <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Pegawai</label>
                                                                        <input type="text" name="" value="<?php foreach ($pegawai_data as $pegawai) {
                                                                                                                if ($pegawai['id'] === $pengajuan['id_pegawai']) {
                                                                                                                    echo $pegawai['nama'];
                                                                                                                }
                                                                                                            } ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
                                                                    </div>

                                                                    <div class="mb-3 sm:block md:flex items-center">
                                                                        <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Status
                                                                            Apoteker</label>
                                                                        <input type="text" name="" value="<?= $persetujuan['status_apoteker'] ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
                                                                    </div>
                                                                    <div class="mb-3 sm:block md:flex items-center">
                                                                        <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Status
                                                                            Keuangan</label>
                                                                        <input type="text" name="" value="<?= $persetujuan['status_keuangan'] ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
                                                                    </div>
                                                                    
                                                                </div>

                                                                <div class="pt-2 border-t border-[#F1F1F1]">
                                                                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Pesanan</h3>
                                                                    <div>

                                                                        <div class="flex items-center justify-between mb-2">
                                                                            <div class="w-1/2">


                                                                            </div>
                                                                            <div class="flex justify-end w-1/2">
                                                                                <p class="font-bold mr-2 text-center text-gray-900 text-sm rounded-lg w-full">Harga/Item</p>
                                                                                <p class="font-bold text-center text-gray-900 text-sm rounded-lg w-full">Total/Item</p>
                                                                            </div>
                                                                        </div>



                                                                        <?php $subtotal = 0;
                                                                        foreach ($pesanan_data as $pesanan) {
                                                                            if ($pesanan['id_pengajuan'] === $pengajuan['id']) {
                                                                                $subtotal += $pesanan['total_per_item'] ?>

                                                                                <div class="flex items-center justify-between">
                                                                                    <div class="w-1/2 font-medium">
                                                                                        <?php foreach ($medis_data as $medis) {
                                                                                            if ($medis['id'] === $pesanan['id_barang_medis']) {
                                                                                                echo $medis['nama'];
                                                                                            }
                                                                                        } ?>
                                                                                        <br>
                                                                                    </div>
                                                                                    <div class="flex justify-end w-1/2">
                                                                                        <input type="text" name="" value="<?= "Rp " . number_format($pesanan['harga_satuan_pengajuan'], 0, ',', '.') ?>" class="text-center mr-2 bg-gray-100 text-gray-900 text-sm rounded-lg px-2 py-1 w-full dark:border-gray-600 dark:text-white" readonly>
                                                                                        <input type="text" name="" value="<?= "Rp " . number_format($pesanan['subtotal_per_item'], 0, ',', '.') ?? "Belum ada total" ?>" class="text-center bg-gray-100 font-[600] text-gray-900 text-sm rounded-lg px-2 py-1 w-full dark:border-gray-600 dark:text-white" readonly>
                                                                                    </div>
                                                                                </div>
                                                                                <div><small>Jumlah:
                                                                                        <?= $pesanan['jumlah_pesanan'] ?> <?php foreach ($satuan_data as $satuan) {
                                                                                                                                if ($satuan['id'] === $pesanan['satuan']) {
                                                                                                                                    echo $satuan['nama'];
                                                                                                                                }
                                                                                                                            } ?>
                                                                                    </small></div>
                                                                                <br>

                                                                        <?php }
                                                                        } ?>

                                                                        <div class="border-t border-[#F1F1F1] mt-2">
                                                                            <div class="flex justify-between pt-1">
                                                                                <label class="block mb-2 md:mb-0 text-sm font-[600] text-gray-900 dark:text-white md:w-1/2">Total</label>
                                                                                <label class="block mb-2 md:mb-0 text-sm font-[600] text-gray-900 dark:text-white">Rp <?= number_format($pengajuan['total_pengajuan'], 0, ',', '.') ?></label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>
                                                        <div class="flex justify-center items-center gap-x-2 py-3 px-4 border-t dark:border-neutral-700">
                                                            <form action="/persetujuanpengajuan/submit/<?= $pengajuan['id'] ?>" method="POST">
                                                                <?php if ($user_data['role'] === 4001 && $persetujuan['status_apoteker'] === 'Menunggu Persetujuan' || $user_data['role'] === 5001 && $persetujuan['status_keuangan'] === 'Menunggu Persetujuan') {
                                                                    foreach ($persetujuan_data as $p) {
                                                                        if ($p['id_pengajuan'] === $pengajuan['id']) {
                                                                            if ($user_data['role'] === 4001 || $user_data['role'] === 5001) {
                                                                                echo '<input type="hidden" value="' . $p['status'] . '" name="statuspersetujuan">';
                                                                                echo '<input type="hidden" value="' . $pengajuan['id'] . '" name="idpengajuan">';
                                                                                if ($user_data['role'] === 4001) {
                                                                                    echo '<input type="hidden" value="' . $user_data['id'] . '" name="idapoteker">';
                                                                                    echo '<input type="hidden" value="' . $p['status_keuangan'] . '" name="statuskeuangan">';
                                                                                    echo '<input type="hidden" value="' . $p['id_keuangan'] . '" name="idkeuangan">';
                                                                                } elseif ($user_data['role'] === 5001) {
                                                                                    echo '<input type="hidden" value="' . $user_data['id'] . '" name="idkeuangan">';
                                                                                    echo '<input type="hidden" value="' . $p['status_apoteker'] . '" name="statusapoteker">';
                                                                                    echo '<input type="hidden" value="' . $p['id_apoteker'] . '" name="idapoteker">';
                                                                                } else {
                                                                                    echo '<p>Hanya apoteker atau keuangan yang bisa melakukan persetujuan.</p>';
                                                                                }
                                                                            }
                                                                        }
                                                                    }
                                                                ?>
                                                                    <button type="button" onclick="event.preventDefault(); openModal('modelTolak-<?= $pengajuan['id'] ?>')" class="w-full py-2 px-3 flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg border border-[#DA4141] bg-[#FDFDFD] text-[#DA4141] shadow-sm hover:bg-[#FFC7C7] disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-800">
                                                                        Tolak
                                                                    </button>
                                                                    <button type="button" onclick="openModal('modelSetuju-<?= $pengajuan['id'] ?>')" value="Disetujui" name="<?php echo ($user_data['role'] === 4001 ? 'statusapoteker' : 'statuskeuangan'); ?>" class="w-full py-2 px-3 flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg border border-[#0A2D27] bg-[#0A2D27] text-[#ACF2E7] shadow-sm hover:bg-[#13594E] disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-800">
                                                                        Setuju
                                                                    </button>
                                                            </form>
                                                        <?php } else { ?>
                                                            <button type="button" class="w-full py-2 px-3 flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg border cursor-default border-[#F6D7D7] bg-[#FDFDFD] text-[#F6D7D7] shadow-sm disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-800" disabled>
                                                                Tolak
                                                            </button>
                                                            <button type="button" class="w-full py-2 px-3 flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg border cursor-default border-[#CCD3D2] bg-[#CCD3D2] text-[#EDFBF9] shadow-sm disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-800" disabled>
                                                                Setuju
                                                            </button>
                                                        <?php }
                                                        ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </tr>

                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php endforeach; ?>
                        </tbody>


                    </table>

                    <!-- End Table -->

                    <!-- Footer -->
                    <div class="px-6 py-4 grid gap-3 md:flex md:justify-between md:items-center border-t border-gray-200 dark:border-gray-700">
                        <!-- Pagination -->
                        <nav class="flex w-full justify-between items-center gap-x-1">
                            <!-- Previous Button -->
                            <div class="inline-flex gap-x-2">
                                <button type="button" class="min-h-[38px] min-w-[38px] py-2 px-2.5 inline-flex justify-center items-center gap-x-2 text-sm rounded-lg text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-white/10 dark:focus:bg-white/10" aria-label="Previous page" <?= $meta_data['page'] <= 1 ? 'disabled' : '' ?> onclick="window.location.href='/persetujuanpengajuan?page=<?= $meta_data['page'] - 1 ?>&size=<?= $meta_data['size'] ?>'">
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
                                        echo '<button type="button" class="min-h-[38px] min-w-[38px] flex justify-center items-center ' . ($current_page == $i ? 'bg-gray-200 text-gray-800 dark:bg-neutral-600 dark:focus:bg-neutral-500' : 'text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:text-white dark:hover:bg-white/10 dark:focus:bg-white/10') . ' py-2 px-3 text-sm rounded-lg" ' . ($current_page == $i ? 'aria-current="page"' : '') . ' onclick="window.location.href=\'/persetujuanpengajuan?page=' . $i . '&size=' . $meta_data['size'] . '\'">' . $i . '</button>';
                                    }
                                } else {
                                    if ($current_page > $range + 1) {
                                        echo '<button type="button" class="min-h-[38px] min-w-[38px] flex justify-center items-center text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:text-white dark:hover:bg-white/10 dark:focus:bg-white/10 py-2 px-3 text-sm rounded-lg" onclick="window.location.href=\'/persetujuanpengajuan?page=1&size=' . $meta_data['size'] . '\'">1</button>';
                                        if ($current_page > $range + 2) {
                                            echo '<span class="py-2 px-3 text-sm">...</span>';
                                        }
                                    }

                                    for ($i = max($current_page - $range, 1); $i <= min($current_page + $range, $total_pages); $i++) {
                                        echo '<button type="button" class="min-h-[38px] min-w-[38px] flex justify-center items-center ' . ($current_page == $i ? 'bg-gray-200 text-gray-800 dark:bg-neutral-600 dark:focus:bg-neutral-500' : 'text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:text-white dark:hover:bg-white/10 dark:focus:bg-white/10') . ' py-2 px-3 text-sm rounded-lg" ' . ($current_page == $i ? 'aria-current="page"' : '') . ' onclick="window.location.href=\'/persetujuanpengajuan?page=' . $i . '&size=' . $meta_data['size'] . '\'">' . $i . '</button>';
                                    }

                                    if ($current_page < $total_pages - $range - 1) {
                                        if ($current_page < $total_pages - $range - 2) {
                                            echo '<span class="py-2 px-3 text-sm">...</span>';
                                        }
                                        echo '<button type="button" class="min-h-[38px] min-w-[38px] flex justify-center items-center text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:text-white dark:hover:bg-white/10 dark:focus:bg-white/10 py-2 px-3 text-sm rounded-lg" onclick="window.location.href=\'/persetujuanpengajuan?page=' . $total_pages . '&size=' . $meta_data['size'] . '\'">' . $total_pages . '</button>';
                                    }
                                }
                                ?>
                            </div>

                            <!-- Next Button -->
                            <div class="inline-flex gap-x-2">
                                <button type="button" class="min-h-[38px] min-w-[38px] py-2 px-2.5 inline-flex justify-center items-center gap-x-2 text-sm rounded-lg text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-white/10 dark:focus:bg-white/10" aria-label="Next page" <?= $current_page >= $total_pages ? 'disabled' : '' ?> onclick="window.location.href='/persetujuanpengajuan?page=<?= $current_page + 1 ?>&size=<?= $meta_data['size'] ?>'">
                                    <span aria-hidden="true" class="hidden sm:block">Next</span>
                                    <svg class="flex-shrink-0 size-3.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="m9 18 6-6-6-6"></path>
                                    </svg>
                                </button>
                            </div>
                        </nav>



                        <!-- End Pagination -->
                    </div>

                    <!-- End Footer -->
                </div>
            </div>
        </div>
    </div>
    <!-- End Card -->
</div>


<script>
    function myFunction() {
        var input, filter, table, tr, td, i, j, txtValue;
        input = document.getElementById("myInput");
        filter = input.value.toUpperCase();
        table = document.getElementById("myTable"); // Pastikan ini mengacu pada ID tabel yang benar

        if (!table) return; // Pastikan tabel ada sebelum melanjutkan

        tr = table.getElementsByTagName("tr");
        var dataFound = false;

        // Iterate over all table rows (including header row)
        for (i = 0; i < tr.length; i++) {
            var found = false;

            // Check if it's a regular row (skip header row)
            if (i > 0) {
                td = tr[i].getElementsByTagName("td");

                // Iterate over all td elements in the row
                for (j = 0; j < td.length; j++) {
                    txtValue = td[j].textContent || td[j].innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        found = true;
                        break; // Break out of inner loop if match found
                    }
                }

                // Show or hide row based on search result
                if (found) {
                    tr[i].style.display = "";
                    dataFound = true;
                } else {
                    tr[i].style.display = "none";
                }
            }
        }
    }
    window.openModal = function(modalId) {
        document.getElementById(modalId).style.display = 'block'
        document.getElementsByTagName('body')[0].classList.add('overflow-y-hidden')
    }

    window.closeModal = function(modalId) {
        document.getElementById(modalId).style.display = 'none'
        document.getElementsByTagName('body')[0].classList.remove('overflow-y-hidden')
    }
</script>
<?= $this->endSection(); ?>