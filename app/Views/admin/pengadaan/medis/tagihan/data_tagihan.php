<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<!-- Table Section -->
<div class="max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto">
    <!-- Card -->
    <div class="flex flex-col ">
        <div class="-m-1.5 overflow-y-auto">
            <div class="sm:px-6 min-w-full inline-block align-middle">
                <div class="p-5 bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden dark:bg-slate-900 dark:border-gray-700">
                    <!-- Header -->
                    <div class="py-1 flex justify-between items-center border-gray-200 dark:border-gray-700">
                        <div>
                            <h2 class="mb-2 text-xl font-semibold text-gray-800 dark:text-gray-200">
                                Tagihan Barang Medis
                            </h2>

                        </div>

                        <div>
                            <a href='/tambahtagihanmedis' class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-[#0A2D27] text-[#ACF2E7] hover:bg-[#13594E] disabled:opacity-50 disabled:pointer-events-none dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" href="#">
                                <svg class="flex-shrink-0 size-3" xmlns="http://www.w3.org/2000/svg" width="16" height="1" viewBox="0 0 16 16" fill="none">
                                    <path d="M2.63452 7.50001L13.6345 7.5M8.13452 13V2" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                                </svg>
                                Tambah
                            </a>
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
                        </colgroup>
                        <thead class="bg-gray-50 dark:bg-slate-800">
                            <tr>
                                <!-- <th scope="col" class="ps-6 py-3 text-start">
                                    <label for="hs-at-with-checkboxes-main" class="flex">
                                        <input type="checkbox" class="shrink-0 border-gray-300 rounded text-blue-600 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-600 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800" id="hs-at-with-checkboxes-main">
                                        <span class="sr-only">Checkbox</span>
                                    </label>
                                </th> -->

                                <th scope="col" class="px-6 py-3">
                                    <div class="flex items-center gap-x-2">
                                        <span class="text-xs tracking-wide text-gray-800 dark:text-gray-200">
                                            Tanggal Bayar
                                        </span>
                                    </div>
                                </th>

                                <th scope="col" class="px-6 py-3">
                                    <div class="flex gap-x-2">
                                        <span class="text-xs tracking-wide text-gray-800 dark:text-gray-200">
                                            No Faktur
                                        </span>
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    <div class="flex items-center gap-x-2">
                                        <span class="text-xs tracking-wide text-gray-800 dark:text-gray-200">
                                            Jumlah bayar
                                        </span>
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    <div class="flex items-center gap-x-2">
                                        <span class="text-xs tracking-wide text-gray-800 dark:text-gray-200">
                                            Status
                                        </span>
                                    </div>
                                </th>

                                <th scope="col" class="px-6 py-3">
                                    <div class="flex items-center gap-x-2">
                                        <span class="text-xs tracking-wide text-gray-800 dark:text-gray-200">
                                            Aksi
                                        </span>
                                    </div>
                                </th>

                            </tr>
                        </thead>




                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            <?php foreach ($pengajuan_data as $pengajuan) : ?>
                                <?php foreach ($penerimaan_data as $penerimaan) : ?>
                                    <?php foreach ($tagihan_medis_data as $tagihan) : ?>
                                        <?php if ($tagihan['id_pengajuan'] === $pengajuan['id'] && $tagihan['id_penerimaan'] === $penerimaan['id']) : ?>
                                            <div id="hs-vertically-centered-scrollable-modal-<?= $tagihan['id'] ?>" class="hs-overlay hidden size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto pointer-events-none">
                                                <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto h-[calc(100%-3.5rem)] min-h-[calc(100%-3.5rem)] flex items-center">
                                                    <div class="w-full max-h-full overflow-hidden flex flex-col bg-white border shadow-sm rounded-xl pointer-events-auto dark:bg-neutral-800 dark:border-neutral-700 dark:shadow-neutral-700/70">
                                                        <div class="flex justify-between items-center py-3 px-4 border-b dark:border-neutral-700">
                                                            <h3 class="font-bold text-gray-800 dark:text-white">
                                                                <?= $tagihan['tanggal_bayar'] ?>
                                                            </h3>
                                                            <button type="button" class="flex justify-center items-center size-7 text-sm font-semibold rounded-full border border-transparent text-gray-800 hover:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-neutral-700" data-hs-overlay="#hs-vertically-centered-scrollable-modal-<?= $tagihan['id'] ?>">
                                                                <span class="sr-only">Close</span>
                                                                <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                                    <path d="M18 6 6 18"></path>
                                                                    <path d="m6 6 12 12"></path>
                                                                </svg>
                                                            </button>
                                                        </div>
                                                        <div class="p-4 overflow-y-auto">
                                                            <div class="space-y-4">

                                                            </div>
                                                            <div class="flex justify-end items-center gap-x-2 py-3 px-4 border-t dark:border-neutral-700">
                                                                <button type="button" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-800" data-hs-overlay="#hs-vertically-centered-scrollable-modal-<?= $tagihan['id'] ?>">
                                                                    Close
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <tr>
                                                    <td class="h-px w-64 whitespace-nowrap">
                                                        <div class="px-6 py-3">
                                                            <span class="block text-sm font-semibold text-gray-800 cursor-pointer dark:text-gray-200 hover:underline" data-hs-overlay="#hs-vertically-centered-scrollable-modal-<?= $tagihan['id'] ?>" data-id="<?= $tagihan['id'] ?>"><?= $tagihan['tanggal_bayar'] ?? 'N/A' ?></span>
                                                        </div>
                                                    </td>
                                                    <td class="h-px w-72 whitespace-nowrap">
                                                        <div class="px-6 py-3">
                                                            <span class="block cursor-default text-sm font-semibold text-gray-800 dark:text-gray-200"><?= $penerimaan['no_faktur'] ?? 'N/A' ?></span>
                                                        </div>
                                                    </td>
                                                    <td class="h-px w-72 whitespace-nowrap">
                                                        <div class="px-6 py-3">
                                                            <span class="block cursor-default text-sm font-semibold text-gray-800 dark:text-gray-200"><?= $tagihan['jumlah_bayar'] ?? 'N/A' ?></span>
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
                                                                    echo 'Sorry, there was an error processing your request. Please try again later';
                                                                    break;
                                                            }
                                                            ?>
                                                        </div>
                                                    </td>
                                                    <td class="size-px whitespace-nowrap">
                                                        <div class="px-3 py-1.5 inline-flex">
                                                            <div class="px-3 py-1.5">
                                                                <button type="button" class="gap-x-1 text-sm decoration-2 hover:underline font-medium dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" data-hs-overlay="#hs-vertically-centered-scrollable-modal-<?= $tagihan['id'] ?>">
                                                                    Lihat Detail
                                                                </button>
                                                            </div>
                                                            <div class="px-3 py-1.5">
                                                                <a href="/edittagihanmedis/<?= $tagihan['id'] ?>" class="gap-x-1 text-sm text-blue-600 decoration-2 hover:underline font-medium dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" href="#">
                                                                    Edit
                                                                </a>
                                                            </div>
                                                            <div class="px-3 py-1.5">
                                                                <button class="gap-x-1 text-sm text-red-600 decoration-2 hover:underline font-medium dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" onclick="openModal('modelConfirm')" href="#">
                                                                    Hapus
                                                                </button>
                                                                <div id="modelConfirm" class="fixed hidden z-[70] inset-0 bg-gray-900 bg-opacity-60 overflow-y-auto h-full w-full px-4 ">
                                                                    <div class="relative top-40 mx-auto shadow-xl rounded-md bg-white max-w-md">

                                                                        <div class="flex justify-end p-2">
                                                                            <button onclick="closeModal('modelConfirm')" type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center">
                                                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                                                                </svg>
                                                                            </button>
                                                                        </div>

                                                                        <div class="p-6 pt-0 text-center">
                                                                            <div class="flex justify-center mb-6"> <!-- Container for SVG, centered -->
                                                                                <svg xmlns="http://www.w3.org/2000/svg" width="49" height="48" viewBox="0 0 49 48" fill="none">
                                                                                    <path d="M8.5 11H40.5" stroke="#DA4141" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                                                                                    <path d="M18.5 5H30.5" stroke="#DA4141" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                                                                                    <path d="M12.5 17H36.5V40C36.5 41.6569 35.1569 43 33.5 43H15.5C13.8431 43 12.5 41.6569 12.5 40V17Z" fill="#FEE2E2" stroke="#DA4141" stroke-width="4" stroke-linejoin="round" />
                                                                                    <path d="M20.5 25L28.5 33" stroke="#DA4141" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                                                                                    <path d="M28.5 25L20.5 33" stroke="#DA4141" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                                                                                </svg>
                                                                            </div>
                                                                            Hapus data
                                                                            <h3 class="text-xl font-normal text-gray-500 mt-5 mb-6">Apakah anda yakin untuk menghapus data ini?</h3>
                                                                            <div class="w-full sm:flex justify-center">
                                                                                <a href="/hapustagihanmedis/<?= $tagihan['id'] ?>" onclick="closeModal('modelConfirm')" class="w-full text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-base inline-flex items-center justify-center px-3 py-2.5 text-center mr-2">
                                                                                    Hapus
                                                                                </a>
                                                                                <a href="#" onclick="closeModal('modelConfirm')" class="w-full text-gray-900 bg-white hover:bg-gray-100 focus:ring-4 focus:ring-cyan-200 border border-gray-200 font-medium inline-flex items-center justify-center rounded-lg text-base px-3 py-2.5 text-center" data-modal-toggle="delete-user-modal">
                                                                                    Batal
                                                                                </a>
                                                                            </div>
                                                                        </div>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>


                                                </tr>

                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    <?php endforeach; ?>
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