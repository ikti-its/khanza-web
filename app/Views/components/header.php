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

    <?= $this->include('components/navbar') ?>


    <!-- End Content -->
    <!-- ========== END MAIN CONTENT ========== -->
</body>