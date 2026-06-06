<body class="bg-gray-50 dark:bg-slate-900">
    <?php
    $userDetails = session()->get('user_details');

    // dd($userDetails);
    // Initialize defaults
    $role = null;
    $foto = '/img/akun-icon.png';
    $email = 'Guest';

    $persetujuanrole   = [1337, 1, 2, 4001, 5001];
    $petugasrole       = [1337, 1, 2, 4001, 5001];
    $petugasdokterrole = [1337, 1, 2, 3, 4001, 5001];
    $dokterrole        = [1337, 1, 3, 4001, 5001];
    $loginadmin        = [1337, 1];
    $loginpetugas      = 2;
    $logindokter       = 3;

    if (is_array($userDetails)) {
        $role  = $userDetails['role']  ?? null;
        $foto  = $userDetails['foto']  ?? $foto;
        $email = $userDetails['email'] ?? $email;
    }
    ?>

    <!-- ========== HEADER ========== -->
    <header class="sticky top-0 inset-x-0 flex flex-wrap sm:justify-start sm:flex-nowrap z-[48] w-full bg-white border-b text-sm py-2.5 sm:py-4 lg:ps-64 dark:bg-gray-800 dark:border-gray-700">
        <nav class="flex basis-full items-center w-full mx-auto px-4 sm:px-6 md:px-8" aria-label="Global">
            <div class="me-5 lg:me-0 lg:hidden">
                <a class="flex-none text-xl font-semibold dark:text-white" href="#" aria-label="Brand">
                    <img src="/img/logo-omnia.png" class="h-4">
                </a>
            </div>

            <div class="w-full flex items-center justify-end ms-auto sm:justify-between sm:gap-x-3 sm:order-3">


                <div class="">
                    <label for="icon" class="sr-only">Search</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none z-20 ps-4">
                            <svg class="flex-shrink-0 size-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="11" cy="11" r="8" />
                                <path d="m21 21-4.3-4.3" />
                            </svg>
                        </div>
                        <input type="text" id="search" name="search" class="py-2 px-4 ps-11 block w-full xl:w-96 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-teal-500 focus:ring-teal-500 disabled:opacity-50 disabled:pointer-events-none" placeholder="Cari">
                        <div id="suggestions" class="absolute z-10 bg-white border border-gray-200 rounded-lg shadow-lg mt-1 w-full max-h-60 overflow-y-auto hidden dark:bg-slate-900 dark:border-gray-700">
                            <!-- Suggestions will be injected here -->
                        </div>
                    </div>
                </div>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const searchInput = document.getElementById('search');
                        const suggestionsContainer = document.getElementById('suggestions');

                        const suggestions = [
                            <?php
                            $userData = session('user_specific_data');
                            $userDetails = session('user_details');
                            $isArray = is_array($userData);
                            $pegawaiId = $isArray && isset($userData['pegawai']) ? $userData['pegawai'] : '';
                            $status = $isArray && isset($userData['status']) ? $userData['status'] : null;
                            $role = isset($userDetails['role']) ? $userDetails['role'] : null;

                            echo "{ name: 'Akun', url: '/profile' },\n";

                            if ($status === false) {
                                echo "{ name: 'Presensi Masuk', url: '/menukehadiran' },\n";
                            } else {
                                echo "{ name: 'Presensi Pulang', url: '/absenpulang/$pegawaiId' },\n";
                            }

                            echo "{ name: 'Pengajuan Izin Cuti', url: '/izincuti' },\n";
                            echo "{ name: 'Peninjauan Catatan Kehadiran', url: '/catatankehadiran/$pegawaiId' },\n";
                            echo "{ name: 'Peninjauan Jadwal Kerja', url: '/lihatjadwal/$pegawaiId' },\n";
                            echo "{ name: 'Peninjauan Daftar Pengajuan Cuti', url: '/lihatizincuti/$pegawaiId' },\n";

                            if ($role === 1) {
                                echo "{ name: 'Data Pegawai', url: '/datauserpegawai' }\n";
                            } else {
                                echo "{ name: 'Data Pegawai', url: '/detailberkaspegawai/$pegawaiId' }\n";
                            }
                            ?>
                        ];

                        searchInput.addEventListener('input', function() {
                            const query = searchInput.value.toLowerCase();

                            // Clear previous suggestions
                            suggestionsContainer.innerHTML = '';

                            if (query.length > 1) {
                                const filteredSuggestions = suggestions.filter(suggestion => suggestion.name.toLowerCase().includes(query));

                                if (filteredSuggestions.length > 0) {
                                    filteredSuggestions.forEach(suggestion => {
                                        const suggestionElement = document.createElement('div');
                                        suggestionElement.className = 'px-4 py-2 cursor-pointer hover:bg-gray-200 dark:hover:bg-gray-800';
                                        suggestionElement.textContent = suggestion.name;

                                        suggestionElement.addEventListener('click', function() {
                                            searchInput.value = suggestion.name;
                                            suggestionsContainer.classList.add('hidden');
                                            window.location.href = suggestion.url; // Redirect to the corresponding URL
                                        });

                                        suggestionsContainer.appendChild(suggestionElement);
                                    });

                                    suggestionsContainer.classList.remove('hidden');
                                } else {
                                    suggestionsContainer.classList.add('hidden');
                                }
                            } else {
                                suggestionsContainer.classList.add('hidden');
                            }
                        });

                        document.addEventListener('click', function(event) {
                            if (!suggestionsContainer.contains(event.target) && event.target !== searchInput) {
                                suggestionsContainer.classList.add('hidden');
                            }
                        });
                    });
                </script>

                <!-- <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const searchInput = document.getElementById('search');
                        const suggestionsContainer = document.getElementById('suggestions');

                        // Example data for suggestions and their corresponding URLs
                        const suggestions = [{
                                name: 'Akun',
                                url: '/profile'
                            },
                            <?php //if (session('user_specific_data')['status'] === false) : 
                            ?> {
                                    name: 'Presensi Masuk',
                                    url: '/menukehadiran'
                                },
                            <?php //else : 
                            ?> {
                                    name: 'Presensi Pulang',
                                    url: '/absenpulang/<?php //echo session('user_specific_data')['pegawai'] 
                                                        ?>'
                                },
                            <?php //endif; 
                            ?> {
                                name: 'Pengajuan Izin Cuti',
                                url: '/izincuti'
                            },
                            {
                                name: 'Peninjauan Catatan Kehadiran',
                                url: '/catatankehadiran/<?php //echo session('user_specific_data')['pegawai'] 
                                                        ?>'
                            },
                            {
                                name: 'Peninjauan Jadwal Kerja',
                                url: '/lihatjadwal/<?php //echo session('user_specific_data')['pegawai'] 
                                                    ?>'
                            },
                            {
                                name: 'Peninjauan Daftar Pengajuan Cuti',
                                url: '/lihatizincuti/<?php //echo session('user_specific_data')['pegawai'] 
                                                        ?>'
                            },
                            <?php //if (session('user_details')['role'] === 1) : 
                            ?> {
                                    name: 'Data Pegawai',
                                    url: '/datauserpegawai'
                                }
                            <?php //else : 
                            ?> {
                                    name: 'Data Pegawai',
                                    url: '/detailberkaspegawai/<?php //echo session('user_specific_data')['pegawai'] 
                                                                ?>'
                                }
                            <?php //endif; 
                            ?>
                        ];

                        searchInput.addEventListener('input', function() {
                            const query = searchInput.value.toLowerCase();

                            // Clear previous suggestions
                            suggestionsContainer.innerHTML = '';

                            if (query.length > 1) {
                                const filteredSuggestions = suggestions.filter(suggestion => suggestion.name.toLowerCase().includes(query));

                                if (filteredSuggestions.length > 0) {
                                    filteredSuggestions.forEach(suggestion => {
                                        const suggestionElement = document.createElement('div');
                                        suggestionElement.className = 'px-4 py-2 cursor-pointer hover:bg-gray-200 dark:hover:bg-gray-800';
                                        suggestionElement.textContent = suggestion.name;

                                        suggestionElement.addEventListener('click', function() {
                                            searchInput.value = suggestion.name;
                                            suggestionsContainer.classList.add('hidden');
                                            window.location.href = suggestion.url; // Redirect to the corresponding URL
                                        });

                                        suggestionsContainer.appendChild(suggestionElement);
                                    });

                                    suggestionsContainer.classList.remove('hidden');
                                } else {
                                    suggestionsContainer.classList.add('hidden');
                                }
                            } else {
                                suggestionsContainer.classList.add('hidden');
                            }
                        });

                        document.addEventListener('click', function(event) {
                            if (!suggestionsContainer.contains(event.target) && event.target !== searchInput) {
                                suggestionsContainer.classList.add('hidden');
                            }
                        });
                    });
                </script> -->

                <div class="flex flex-row items-center justify-end gap-2">

                    <div class="hs-dropdown relative inline-flex [--placement:bottom-right]">
                        <button id="hs-dropdown-with-header" type="button" class="w-[2.375rem] h-[2.375rem] inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-full border border-transparent text-gray-800 hover:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-gray-700 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                            <svg width="36" height="36" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M18 12.4438V15.7738" stroke="#666666" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" />
                                <path d="M18.02 8.00391C14.34 8.00391 11.36 10.9839 11.36 14.6639V16.7639C11.36 17.4439 11.08 18.4639 10.73 19.0439L9.46002 21.1639C8.68002 22.4739 9.22002 23.9339 10.66 24.4139C15.44 26.0039 20.61 26.0039 25.39 24.4139C26.74 23.9639 27.32 22.3839 26.59 21.1639L25.32 19.0439C24.97 18.4639 24.69 17.4339 24.69 16.7639V14.6639C24.68 11.0039 21.68 8.00391 18.02 8.00391Z" stroke="#666666" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" />
                                <path d="M21.33 24.8237C21.33 26.6537 19.83 28.1537 18 28.1537C17.09 28.1537 16.25 27.7737 15.65 27.1737C15.05 26.5737 14.67 25.7337 14.67 24.8237" stroke="#666666" stroke-width="1.5" stroke-miterlimit="10" />
                            </svg>

                            <?php $notification_count = session('notification_count'); ?>
                            <?php if ($notification_count > 0) : ?>
                                <span id="notification-badge" class="absolute top-0 right-0 bg-red-500 text-white text-xs rounded-full px-1"><?php echo $notification_count; ?></span>
                            <?php endif; ?>
                        </button>

                        <div class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden min-w-60 bg-white shadow-md rounded-lg p-2 dark:bg-gray-800 dark:border dark:border-gray-700" aria-labelledby="hs-dropdown-with-header">
                            <div class="py-3 px-5 -m-2 bg-gray-100 rounded-t-lg dark:bg-gray-700">
                                <p class="text-sm text-gray-500 dark:text-gray-400">Notifikasi</p>
                            </div>
                            <div class="mt-2 py-2 first:pt-0 last:pb-0">
                                <div class="mt-2">
                                    <ul class="max-h-52 overflow-y-auto">
                                        <?php $notifications = session('notif_data'); ?>
                                        <?php if (!empty($notifications)) : ?>
                                            <?php foreach ($notifications as $notification) : ?>
                                                <li class="flex  hover:bg-gray-100 rounded-lg">
                                                    <div class="flex-shrink-0">
                                                        <svg class="flex-shrink-0 size-4 text-teal-600 mt-1" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                            <circle cx="12" cy="12" r="10"></circle>
                                                            <path d="M12 16v-4"></path>
                                                            <path d="M12 8h.01"></path>
                                                        </svg>
                                                    </div>
                                                    <div class="ms-3">
                                                        <h2 class="text-gray-800 font-bold dark:text-white">
                                                            <?php echo htmlspecialchars($notification['judul']); ?>
                                                        </h2>
                                                        <p class="mt-2 text-sm text-gray-700 dark:text-neutral-400">
                                                            <?php echo htmlspecialchars($notification['pesan']); ?>
                                                        </p>
                                                    </div>
                                                </li>
                                            <?php endforeach; ?>
                                        <?php else : ?>
                                            <li class="py-2 px-3 rounded-lg text-sm text-gray-800 dark:text-gray-400">
                                                No notifications found.
                                            </li>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <script>
                        document.getElementById('hs-dropdown-with-header').addEventListener('click', function() {
                            const badge = document.getElementById('notification-badge');
                            if (badge) {
                                badge.style.display = 'none';
                            }
                        });
                    </script>



                    <div class="hs-dropdown relative inline-flex [--placement:bottom-right]">

                        <button id="hs-dropdown-with-header" type="button" class="w-[2.375rem] h-[2.375rem] inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-full border border-transparent text-gray-800 hover:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-gray-700 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                            <img class="inline-block size-[38px] rounded-full ring-2 ring-white dark:ring-gray-800" src="<?= base_url($foto) ?>" alt="Image Description">
                        </button>

                        <div class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden min-w-60 bg-white shadow-md rounded-lg p-2 dark:bg-gray-800 dark:border dark:border-gray-700" aria-labelledby="hs-dropdown-with-header">
                            <div class="py-3 px-5 -m-2 bg-gray-100 rounded-t-lg dark:bg-gray-700">
                                <p class="text-sm text-gray-500 dark:text-gray-400">Masuk sebagai</p>
                                <p class="text-sm font-medium text-gray-800 dark:text-gray-300">
                                    <?= esc($email) ?>
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    (<?= match ($role) {
                                            1337 => 'Super Admin',
                                            1    => 'Admin',
                                            2    => 'Petugas',
                                            3    => 'Dokter',
                                            4001 => 'Role 4001',
                                            5001 => 'Role 5001',
                                            default => 'Guest'
                                        } ?>)
                                </p>
                            </div>
                            <div class="mt-2 py-2 first:pt-0 last:pb-0">
                                <a class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:ring-2 focus:ring-blue-500 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-300" href="#">
                                    <img src="<?= base_url('/svg/profile/newsletter.svg') ?>">
                                    Newsletter
                                </a>
                                <a class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:ring-2 focus:ring-blue-500 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-300" href="#">
                                    <img src="<?= base_url('svg/profile/purchases.svg') ?>">
                                    Purchases
                                </a>
                                <a class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:ring-2 focus:ring-blue-500 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-300" href="/profile">
                                    <img src="<?= base_url('svg/profile/profile.svg') ?>">
                                    Lihat profil
                                </a>
                                <a class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:ring-2 focus:ring-blue-500 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-300" href="/logout">
                                    <img src="<?= base_url('svg/profile/logout.svg') ?>">
                                    Keluar akun
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </header>
    <!-- ========== END HEADER ========== -->

    <!-- ========== MAIN CONTENT ========== -->
    <!-- Sidebar Toggle -->

    <!-- Breadcrumb -->
    <ol class="ms-3 flex items-center whitespace-nowrap" aria-label="Breadcrumb">
        <li class="flex items-center text-sm text-gray-800 dark:text-gray-400">
            Omnia
            <!-- <svg class="flex-shrink-0 mx-3 overflow-visible size-2.5 text-gray-400 dark:text-gray-600" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M5 1L10.6869 7.16086C10.8637 7.35239 10.8637 7.64761 10.6869 7.83914L5 14" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                    </svg> -->
        </li>
        <!-- <li class="text-sm font-semibold text-gray-800 truncate dark:text-gray-400" aria-current="page">
                    Dashboard
                </li> -->
    </ol>
    <!-- End Breadcrumb -->
    </div>
    </div>
    <!-- End Sidebar Toggle -->

    <!-- Sidebar -->
    <div id="application-sidebar" class="hs-overlay hs-overlay-open:translate-x-0 -translate-x-full transition-all duration-300 transform hidden fixed top-0 start-0 bottom-0 z-[60] w-64 bg-white border-e border-gray-200 pt-7 pb-10 overflow-y-auto lg:block lg:translate-x-0 lg:end-auto lg:bottom-0 [&::-webkit-scrollbar]:w-2 [&::-webkit-scrollbar-thumb]:rounded-full [&::-webkit-scrollbar-track]:bg-gray-100 [&::-webkit-scrollbar-thumb]:bg-gray-300 dark:[&::-webkit-scrollbar-track]:bg-slate-700 dark:[&::-webkit-scrollbar-thumb]:bg-slate-500 dark:bg-gray-800 dark:border-gray-700">
        <div class="px-6">
            <a class="flex-none text-xl font-semibold dark:text-white dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" href="/dashboard">
                <img src="/img/logo-omnia.png" class=" h-12">
            </a>
        </div>

        <nav class="hs-accordion-group p-6 w-full flex flex-col flex-wrap" data-hs-accordion-always-open>
            <ul class="space-y-1.5">
                <a class="w-full flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-lg hover:bg-gray-100 dark:hover:bg-teal-900 dark:text-slate-400 dark:hover:text-slate-300 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" href="/dashboard">
                    <img src="<?= base_url('svg/icons/beranda.svg') ?>">
                    Beranda
                </a>
                </li>

                <li>
                    <a class="w-full flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-lg hover:bg-gray-100 dark:hover:bg-teal-900 dark:text-slate-400 dark:hover:text-slate-300 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" href="/profile">
                        <img src="<?= base_url('svg/icons/akun.svg') ?>">
                        Akun
                    </a>

                </li>

                <li class="hs-accordion" id="account-accordion">
                    <button type="button" class="hs-accordion-toggle hs-accordion-active:bg-gray-100 w-full flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-lg hover:bg-gray-100 dark:hover:bg-teal-900 dark:text-slate-400 dark:hover:text-slate-300 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                        <img src="<?= base_url('svg/icons/kehadiran.svg') ?>">
                        Kehadiran

                        <svg class="hs-accordion-active:block ms-auto hidden size-4 text-gray-600 group-hover:text-gray-500 dark:text-neutral-400" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m18 15-6-6-6 6" />
                        </svg>

                        <svg class="hs-accordion-active:hidden ms-auto block size-4 text-gray-600 group-hover:text-gray-500 dark:text-neutral-400" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m6 9 6 6 6-6" />
                        </svg>
                    </button>

                    <div id="account-accordion" class="hs-accordion-content w-full overflow-hidden transition-[height] duration-300 hidden">
                        <ul class="pt-2 ps-2">

                            <li class="hs-accordion" id="account-accordion">
                                <button type="button" class="hs-accordion-toggle hs-accordion-active:bg-gray-100 w-full text-start flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-700 rounded-lg hover:bg-gray-100 dark:bg-neutral-800 dark:hover:bg-neutral-700 dark:text-neutral-400 dark:hover:text-neutral-300 dark:hs-accordion-active:text-white">
                                    <img src="<?= base_url('svg/icons/presensi.svg') ?>">
                                    Presensi

                                    <svg class="hs-accordion-active:block ms-auto hidden size-4 text-gray-600 group-hover:text-gray-500 dark:text-neutral-400" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="m18 15-6-6-6 6" />
                                    </svg>

                                    <svg class="hs-accordion-active:hidden ms-auto block size-4 text-gray-600 group-hover:text-gray-500 dark:text-neutral-400" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="m6 9 6 6 6-6" />
                                    </svg>
                                </button>

                                <div id="account-accordion" class="hs-accordion-content w-full overflow-hidden transition-[height] duration-300 hidden">
                                    <ul class="pt-2 ps-2">
                                        <?php
                                        $userData = session('user_specific_data') ?? [];
                                        $status = $userData['status'] ?? null;
                                        ?>
                                        <?php if ($status === false) : ?>
                                            <li>
                                                <a class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-lg hover:bg-gray-100 dark:bg-gray-800 dark:text-slate-400 dark:hover:text-slate-300 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" href="/menukehadiran">
                                                    Masuk
                                                </a>
                                            </li>
                                        <?php elseif ($status === true) : ?>
                                            <li>
                                                <div class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-700 rounded-lg" href="/swafoto">
                                                    Pulang
                                                </div>
                                            </li>
                                        <?php else: ?>
                                            <li>
                                                <div class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-lg hover:bg-gray-100 dark:bg-gray-800 dark:text-slate-400 dark:hover:text-slate-300 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" href="/menukehadiran">
                                                    Masuk
                                                </div>
                                            </li>
                                            <?php
                                            $userData = session('user_specific_data') ?? [];
                                            $pegawai = $userData['pegawai'] ?? 'unknown';  // Default value if 'pegawai' is not set
                                            ?>
                                            <li>
                                                <a class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-lg hover:bg-gray-100 dark:bg-gray-800 dark:text-slate-400 dark:hover:text-slate-300 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600"
                                                    href="/absenpulang/<?php echo $pegawai; ?>">
                                                    Pulang
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                            </li>

                            <li class="hs-accordion" id="account-accordion">
                                <button type="button" class="hs-accordion-toggle hs-accordion-active:bg-gray-100 w-full text-start flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-700 rounded-lg hover:bg-gray-100 dark:bg-neutral-800 dark:hover:bg-neutral-700 dark:text-neutral-400 dark:hover:text-neutral-300 dark:hs-accordion-active:text-white">
                                    <img src="<?= base_url('svg/icons/pengajuan.svg') ?>">
                                    Pengajuan

                                    <svg class="hs-accordion-active:block ms-auto hidden size-4 text-gray-600 group-hover:text-gray-500 dark:text-neutral-400" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="m18 15-6-6-6 6" />
                                    </svg>

                                    <svg class="hs-accordion-active:hidden ms-auto block size-4 text-gray-600 group-hover:text-gray-500 dark:text-neutral-400" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="m6 9 6 6 6-6" />
                                    </svg>
                                </button>

                                <div id="account-accordion" class="hs-accordion-content w-full overflow-hidden transition-[height] duration-300 hidden">
                                    <ul class="pt-2 ps-2">
                                        <li>
                                            <a class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-lg hover:bg-gray-100 dark:bg-gray-800 dark:text-slate-400 dark:hover:text-slate-300 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" href="/izincuti">
                                                Izin Cuti
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>

                            <li class="hs-accordion" id="account-accordion">
                                <button type="button" class="hs-accordion-toggle hs-accordion-active:bg-gray-100 w-full text-start flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-700 rounded-lg hover:bg-gray-100 dark:bg-neutral-800 dark:hover:bg-neutral-700 dark:text-neutral-400 dark:hover:text-neutral-300 dark:hs-accordion-active:text-white">
                                    <img src="<?= base_url('svg/icons/peninjauan.svg') ?>">
                                    Peninjauan

                                    <svg class="hs-accordion-active:block ms-auto hidden size-4 text-gray-600 group-hover:text-gray-500 dark:text-neutral-400" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="m18 15-6-6-6 6" />
                                    </svg>

                                    <svg class="hs-accordion-active:hidden ms-auto block size-4 text-gray-600 group-hover:text-gray-500 dark:text-neutral-400" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="m6 9 6 6 6-6" />
                                    </svg>
                                </button>

                                <div id="account-accordion" class="hs-accordion-content w-full overflow-hidden transition-[height] duration-300 hidden">
                                    <ul class="pt-2 ps-2">
                                        <?php
                                        $userData = session('user_specific_data') ?? [];
                                        $pegawai = $userData['pegawai'] ?? 'unknown';  // Provide a default value if 'pegawai' is missing
                                        ?>
                                        <li>
                                            <a class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-lg hover:bg-gray-100 dark:bg-gray-800 dark:text-slate-400 dark:hover:text-slate-300 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600"
                                                href="/catatankehadiran/<?php echo $pegawai; ?>">
                                                Catatan Kehadiran
                                            </a>
                                        </li>
                                        <li>
                                            <?php if (isset(session('user_details')['role']) && session('user_details')['role'] === 2) : ?>
                                                <?php
                                                $user_specific_data = session('user_specific_data');
                                                $pegawai = isset($user_specific_data['pegawai']) ? $user_specific_data['pegawai'] : '';
                                                ?>
                                                <a class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-lg hover:bg-gray-100 dark:bg-gray-800 dark:text-slate-400 dark:hover:text-slate-300 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" href="/lihatjadwal/<?php echo $pegawai; ?>">
                                                    Jadwal Kerja
                                                </a>
                                            <?php else : ?>
                                                <a class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-lg hover:bg-gray-100 dark:bg-gray-800 dark:text-slate-400 dark:hover:text-slate-300 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" href="/lihatjadwal">
                                                    Jadwal Kerja
                                                </a>
                                            <?php endif; ?>
                                        </li>
                                        <li>
                                            <a class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-lg hover:bg-gray-100 dark:bg-gray-800 dark:text-slate-400 dark:hover:text-slate-300 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600"
                                                href="/lihatizincuti/<?php echo $pegawai; ?>">
                                                Daftar Pengajuan Cuti
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                            <?php if (isset(session('user_details')['role']) && session('user_details')['role'] === 1) : ?>
                                <li class="hs-accordion" id="account-accordion">
                                    <button type="button" class="hs-accordion-toggle hs-accordion-active:bg-gray-100 w-full text-start flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-700 rounded-lg hover:bg-gray-100 dark:bg-neutral-800 dark:hover:bg-neutral-700 dark:text-neutral-400 dark:hover:text-neutral-300 dark:hs-accordion-active:text-white">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                            <path d="M4.61666 16.2666C4.10832 16.2666 3.63332 16.0916 3.29166 15.7666C2.85832 15.3583 2.64999 14.7416 2.72499 14.075L3.03332 11.375C3.09166 10.8666 3.39999 10.1916 3.75832 9.82496L10.6 2.58329C12.3083 0.774959 14.0917 0.72496 15.9 2.43329C17.7083 4.14163 17.7583 5.92496 16.05 7.73329L9.20832 14.975C8.85832 15.35 8.20832 15.7 7.69999 15.7833L5.01666 16.2416C4.87499 16.25 4.74999 16.2666 4.61666 16.2666ZM13.275 2.42496C12.6333 2.42496 12.075 2.82496 11.5083 3.42496L4.66666 10.675C4.49999 10.85 4.30832 11.2666 4.27499 11.5083L3.96666 14.2083C3.93332 14.4833 3.99999 14.7083 4.14999 14.85C4.29999 14.9916 4.52499 15.0416 4.79999 15L7.48332 14.5416C7.72499 14.5 8.12499 14.2833 8.29166 14.1083L15.1333 6.86663C16.1667 5.76663 16.5417 4.74996 15.0333 3.33329C14.3667 2.69163 13.7917 2.42496 13.275 2.42496Z" fill="#272727" />
                                            <path d="M14.45 9.12504C14.4333 9.12504 14.4083 9.12504 14.3916 9.12504C11.7916 8.8667 9.69996 6.8917 9.29996 4.30837C9.24996 3.9667 9.48329 3.65004 9.82496 3.5917C10.1666 3.5417 10.4833 3.77504 10.5416 4.1167C10.8583 6.13337 12.4916 7.68337 14.525 7.88337C14.8666 7.9167 15.1166 8.22504 15.0833 8.5667C15.0416 8.88337 14.7666 9.12504 14.45 9.12504Z" fill="#272727" />
                                        </svg>
                                        Ubah


                                        <svg class="hs-accordion-active:block ms-auto hidden size-4 text-gray-600 group-hover:text-gray-500 dark:text-neutral-400" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="m18 15-6-6-6 6" />
                                        </svg>

                                        <svg class="hs-accordion-active:hidden ms-auto block size-4 text-gray-600 group-hover:text-gray-500 dark:text-neutral-400" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="m6 9 6 6 6-6" />
                                        </svg>
                                    </button>


                                    <div id="account-accordion" class="hs-accordion-content w-full overflow-hidden transition-[height] duration-300 hidden">
                                        <ul class="pt-2 ps-2">
                                            <li>
                                                <a class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-lg hover:bg-gray-100 dark:bg-gray-800 dark:text-slate-400 dark:hover:text-slate-300 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" href="/lihatstatuscuti">
                                                    Status Pengajuan Cuti
                                                </a>
                                            </li>
                                        </ul>
                                    </div>

                                </li>
                            <?php endif; ?>
                            <!-- <li>
                                <a class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-700 rounded-lg hover:bg-teal-200 dark:bg-neutral-800 dark:text-neutral-400 dark:hover:text-neutral-300" href="/presensi">
                                    Face Recognition
                                </a>
                            </li>
                            <li>
                                <a class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-700 rounded-lg hover:bg-teal-200 dark:bg-neutral-800 dark:text-neutral-400 dark:hover:text-neutral-300" href="/swafoto">
                                    Swafoto
                                </a>
                            </li>

                            <li>
                                <a class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-700 rounded-lg hover:bg-teal-200 dark:bg-neutral-800 dark:text-neutral-400 dark:hover:text-neutral-300" href="/tesmenukehadiran">
                                    Tes Menu Kehadiran
                                </a>
                            </li>

                            <li>
                                <a class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-700 rounded-lg hover:bg-teal-200 dark:bg-neutral-800 dark:text-neutral-400 dark:hover:text-neutral-300" href="/kehadiranmanual">
                                    Tes Kehadiran Manual
                                </a>
                            </li> -->
                        </ul>
                    </div>
                </li>


                <li>
                    <?php if (isset(session('user_details')['role']) && (session('user_details')['role'] === 2)) : ?>
                        <a class="w-full flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-lg hover:bg-teal-200 dark:hover:bg-teal-900 dark:text-slate-400 dark:hover:text-slate-300 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" href="/detailberkaspegawai/<?php //echo session('user_specific_data')['pegawai']                                                                                                                                                                                                                                                                                  ?>">
                        <img src="<?= base_url('svg/icons/pegawai.svg') ?>">
                            Pegawai
                        </a>
                    <?php else : ?>

                <li class="hs-accordion" id="account-accordion">
                    <button type="button" class="hs-accordion-toggle hs-accordion-active:text-slate-700 hs-accordion-active:hover:bg-teal-200 w-full text-start flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-700 rounded-lg hover:bg-teal-200 dark:bg-neutral-800 dark:hover:bg-neutral-700 dark:text-neutral-400 dark:hover:text-neutral-300 dark:hs-accordion-active:text-white">
                        <img src="<?= base_url('svg/icons/pegawai.svg') ?>">
                        Pegawai

                        <svg class="hs-accordion-active:block ms-auto hidden size-4 text-gray-600 group-hover:text-gray-500 dark:text-neutral-400" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m18 15-6-6-6 6" />
                        </svg>

                        <svg class="hs-accordion-active:hidden ms-auto block size-4 text-gray-600 group-hover:text-gray-500 dark:text-neutral-400" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m6 9 6 6 6-6" />
                        </svg>
                    </button>

                    <div id="account-accordion-content" class="hs-accordion-content w-full overflow-hidden transition-[height] duration-300 hidden">
                        <ul class="pt-2 ps-2">
                            <li>
                                <a class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-700 rounded-lg hover:bg-teal-200 dark:bg-neutral-800 dark:text-neutral-400 dark:hover:text-neutral-300" href="/detailberkaspegawai/<?php echo session('user_specific_data') //['pegawai']                                                                                                                                                                                                   ?>">
                                    Data Pegawai
                                </a>
                            </li>

                            <li>
                                <a class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-700 rounded-lg hover:bg-teal-200 dark:bg-neutral-800 dark:text-neutral-400 dark:hover:text-neutral-300" href="/datauserpegawai"">
                                    Ketersediaan Pegawai
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

            <?php endif; ?>
    
                <?php
                $menu_list = [
                    /*
                        **** Format  *******
                        ['Nama menu', 'Link menu', 'Icon menu', 'Prefiks', $rolelist, [
                            ['Nama submenu 1' , 'Link submenu 1', 'Icon submenu 1'],
                            ['Nama submenu 2' , 'Link submenu 2', 'Icon submenu 2'],
                            ['Nama submenu 3' , 'Link submenu 3', 'Icon submenu 3'],
                            dst
                        ]]

                        **** Keterangan *****
                        Nama menu => Nama yang akan muncul di tampilan user di sidebar sebelah kiri
                        Link menu => Link sesuai routes yang telah dibuat di app/Config/Routes.php
                                     Jika menu ini memiliki submenu, maka link menu WAJIB diisi '' (empty string)
                        Icon menu => Nama file icon menu dalam bentuk file svg yang disimpan di public/svg
                                     Nama Icon menu pada halaman ini TIDAK BOLEH mencantumkan ekstensi .svg
                                     Apabila icon tidak ada, Icon menu WAJIB diisi '' (empty string)
                                     Tetapi Icon menu DISARANKAN ada supaya tampilan terlihat rapi
                        Prefiks   => Prefiks url yang ditambahkan sebelum Link Menu
                                     Apabila tidak menggunakan prefiks, Prefiks diisi '' (empty string)
                        Rolelist  => List role user yang dapat melihat dan mengakses menu ini

                        Submenu      => Opsi submenu yang dikelompokkan dalam 1 menu untuk memudahkan navigasi
                                        Apabila tidak ada submenu, maka submenu WAJIB diisi [] (empty array)
                                        Submenu dan Link menu bersifat mutually exclusive sehingga
                                        apabila Link Menu diisi, maka Submenu WAJIB kosong dan sebaliknya
                        Nama submenu => Nama yang akan muncul di tampilan user ketika menu diklik
                        Link submenu => Link sesuai routes yang telah dibuat di app/Config/Routes.php
                        Icon submenu => Nama file icon menu dalam bentuk file svg yang disimpan di public/svg
                                        Nama Icon menu pada halaman ini TIDAK BOLEH mencantumkan ekstensi .svg
                                        Apabila icon tidak ada, Icon submenu WAJIB diisi '' (empty string)
                                        Pada 1 menu, DISARANKAN submenu memiliki icon seluruhnya atau 
                                        tidak memiliki icon seluruhnya supaya tampilan terlihat rapi
                        */
                    // ['Data Penggajian', '', 'data_penggajian', '/data-penggajian', $petugasrole, [
                    // ]],
                    ['Inventaris Medis', '', 'inventaris_medis', '',  $petugasrole, [
                        ['Data', '/datamedis', ''],
                        ['Stok Opname', '/stokopnamemedis', ''],
                        ['Mutasi Antar Gudang', '/mutasimedis', ''],
                        ['Penerimaan Obat & BHP', '/penerimaanmedis', ''],
                        ['Stok Keluar', '/stokkeluarmedis', ''],
                        ['Sisa Stok',  '/sisastokmedis', ''],
                        ['Data Batch', '/batchmedis', '']
                    ]],
                    ['Rujukan', '', 'rujukan', '', $petugasdokterrole, [
                        ['Rujukan Masuk', '/rujukanmasuk', ''],
                        ['Rujukan Keluar', '/rujukankeluar', ''],
                    ]], #allrole
                    ['Persetujuan', '/persetujuanpengajuan', 'persetujuan', '', $persetujuanrole, []],
                    ['Registrasi', '/registrasi', 'registrasi', '', $petugasrole, []],
                    ['Data Pasien', '', 'olah_data_pasien', '', $petugasrole, [
                        ['Daftar Pasien', '/masterpasien', ''],
                        ['Kelahiran Bayi', '/kelahiranbayi', ''],
                        ['Pasien Meninggal', '/pasienmeninggal', ''],
                        ['Asuransi Pasien', '/asuransi', ''],
                        ['Instansi Pasien', '/instansi', ''],
                    ]],
                    ['Dokter', '', 'dokter_jaga', '', $petugasrole, [
                        ['Daftar Dokter', '/dokter', ''],
                        ['Dokter Jaga', '/dokterjaga', ''],
                    ]],
                    ['Rawat Inap', '/rawatinap', 'rawat_inap', '', $petugasdokterrole, []],
                    ['Ruangan', '/kamar', 'kamar', '', $petugasrole, []],
                    ['Unit Gawat Darurat', '/ugd', 'ugd', '', $petugasdokterrole, []],
                    ['Triase UGD', '', 'triase_ugd', '/triase-ugd', $petugasrole, [
                        ['Data Triase', '/data-triase', ''],
                        ['Data Triase Detail', '/data-triase-detail', ''],
                        ['Data Triase Primer', '/data-triase-primer', ''],
                        ['Data Triase Sekunder', '/data-triase-sekunder', ''],
                        ['Triase Macam Kasus', '/triase-macam-kasus', ''],
                        ['Triase Pemeriksaan', '/triase-pemeriksaan', ''],
                        ['Triase Skala', '/triase-skala', ''],
                        ['Cara Masuk', '/cara-masuk', ''],
                        ['Alat Transportasi', '/alat-transportasi', ''],
                        ['Alasan Kedatangan', '/alasan-kedatangan', ''],
                        ['Kebutuhan Khusus', '/kebutuhan-khusus', ''],
                        ['Plan Primer', '/plan-primer', ''],
                        ['Plan Sekunder', '/plan-sekunder', ''],
                        ['Tingkat Skala', '/tingkat-skala', '']
                    ]],
                    ['Ambulans', '/ambulans', 'ambulans', '', $petugasrole, []],
                    ['Tindakan', '/tindakan', 'tindakan', '', $petugasrole, []],
                    ['Pemeriksaan', '/pemeriksaanranap', 'pemeriksaan', '', $petugasrole, []],
                    ['Resep Obat', '/resepobat', 'resep_obat', '', $petugasdokterrole, []],
                    ['Pemberian Obat', '/pemberianobat', 'pemberian_obat', '', $petugasrole, []],
                    ['Resep Pulang', '', 'resep_pulang', '', $petugasrole, [
                        ['Permintaan Resep Pulang', '/permintaanreseppulang', ''],
                        ['Resep Pulang', '/reseppulang', ''],
                    ]],
                    ['Rekam Medis', '', 'rekam_medis', '', $dokterrole, [
                        ['Daftar Rekam Medis', '/rekam-medis', ''],
                        ['Observasi Rawat Inap', '/catatanobservasiranap', ''],
                        ['Observasi Rawat Inap Kebidanan', '/catatanobservasikebidanan', ''],
                        ['Observasi Rawat Inap Post Partum', '/catatanobservasipostpartum', ''],
                    ]],
                    ['Pendidikan', '', 'pendidikan', '/pendidikan', $petugasrole, [
                        ['Jenjang Pendidikan', '/jenjang-pendidikan', ''],
                        ['Jenis Pendidikan', '/jenis-pendidikan', ''],
                        ['Sekolah', '/sekolah', ''],
                        ['Gelar', '/gelar', ''],
                    ]],
                    ['Person', '', 'person', '/person', $petugasrole, [
                        ['Orang', '/orang', ''],
                        ['Agama', '/agama', ''],
                        ['Pernikahan', '/pernikahan', ''],
                        ['Jenis Kelamin', '/jenis-kelamin', ''],
                    ]],
                    ['Role', '', 'role', '/role', $petugasrole, [
                        ['Pasien', '/pasien', ''],
                        ['Pendonor', '/pendonor', ''],
                        ['Dokter', '/dokter', ''],
                        ['Petugas', '/petugas', '']
                    ]],
                    ['Kontak', '', 'kontak', '/kontak', $petugasrole, [
                        ['Jenis Telepon', '/jenis-telepon', ''],
                        ['Provider', '/provider', ''],
                        ['Telepon', '/telepon', ''],
                        ['Email', '/email', ''],
                    ]],
                    ['Donor', '', 'donor', '/donor', $petugasrole, [
                        ['Kunjungan', '/kunjungan', ''],
                        ['Skrining Donor', '/skrining-donor', ''],
                        ['Pengambilan Darah', '/pengambilan-darah', ''],
                        ['Hasil Anamnesis', '/hasil-anamnesis', ''],
                        ['Jenis Bag', '/jenis-bag', ''],
                        ['Jenis Donor', '/jenis-donor', ''],
                        ['Lokasi Pengambilan Darah', '/lokasi-pengambilan-darah', ''],
                        ['Status Pengambilan', '/status-pengambilan', '']
                    ]],
                    ['Inventaris Darah', '', 'inventaris_darah', '/inventaris-darah', $petugasrole, [
                        ['Pemisahan Komponen', '/pemisahan-komponen', ''],
                        ['Pemisahan Komponen Detail', '/pemisahan-komponen-detail', ''],
                        ['Stok Darah', '/stok-darah', ''],
                        ['Status Stok', '/status-stok', ''],
                        ['Sumber Darah', '/sumber-darah', ''],
                        ['Tarif Komponen', '/tarif-komponen', '']
                    ]],
                    ['Uji Darah', '', 'uji_darah', '/uji-darah', $petugasrole, [
                        ['Hasil Uji Saring', '/hasil-uji-saring', ''],
                        ['Metode Uji', '/metode-uji', '']
                    ]],
                    ['Pelayanan Darah', '', 'pelayanan_darah', '/pelayanan-darah', $petugasrole, [
                        ['Permintaan Darah', '/permintaan-darah', ''],
                        ['Permintaan Darah Detail', '/permintaan-darah-detail', ''],
                        ['Penyerahan Darah', '/penyerahan-darah', ''],
                        ['Penyerahan Darah Detail', '/penyerahan-darah-detail', ''],
                        ['Status Permintaan', '/status-permintaan', ''],
                        ['Status Pembayaran', '/status-pembayaran', '']
                    ]],
                    ['Logistik UTD', '', 'logistik_utd', '/logistik-utd', $petugasrole, [
                        ['BHP Medis Donor', '/medis-donor', ''],
                        ['BHP Non Medis Donor', '/non-medis-donor', ''],
                        ['BHP Medis Pemisahan', '/medis-pemisahan', ''],
                        ['BHP Non Medis Pemisahan', '/non-medis-pemisahan', ''],
                        ['BHP Medis Penyerahan', '/medis-penyerahan', ''],
                        ['BHP Non Medis Penyerahan', '/non-medis-penyerahan', ''],
                        ['BHP Medis Rusak', '/medis-rusak', ''],
                        ['BHP Non Medis Rusak', '/non-medis-rusak', ''],
                        ['Pengambilan BHP Medis', '/pengambilan-medis', ''],
                        ['Pengambilan BHP Non Medis', '/pengambilan-non-medis', '']
                    ]],
                    ['Penanganan Donor', '', 'penanganan_donor', '/penanganan-donor', $petugasrole, [
                        ['Pencekalan', '/pencekalan', ''],
                        ['Kasus Reaktif', '/kasus-reaktif', ''],
                        ['Jenis Pencekalan', '/jenis-pencekalan', '']
                    ]],
                ];
                echo view('components/menu/menu', ['menu_list' => $menu_list]);

                $new_menu_list = new \App\Features\AllRoutes()->create_header();
                echo view('components/menu/menu', ['menu_list' => $new_menu_list]);
                ?>
            </ul>
        </nav>
    </div>
    <!-- End Sidebar -->


    <!-- End Content -->
    <!-- ========== END MAIN CONTENT ========== -->
</body>