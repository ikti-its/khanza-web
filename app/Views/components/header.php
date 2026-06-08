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

                    <?= $this->include('components/notif') ?>
                    <?= $this->include('components/profile') ?>

                </div>
            </div>
        </nav>
    </header>
    <!-- ========== END HEADER ========== -->

    <!-- ========== MAIN CONTENT ========== -->
    <!-- Sidebar Toggle -->

    </div>
    </div>
    <!-- End Sidebar Toggle -->

    <?= $this->include('components/navbar') ?>


    <!-- End Content -->
    <!-- ========== END MAIN CONTENT ========== -->
</body>