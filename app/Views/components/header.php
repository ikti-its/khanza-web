<body class="bg-gray-50 dark:bg-slate-900">
<?php
    $userDetails = session()->get('user_details');

    // dd($userDetails);
    // Initialize defaults
    $role = null;
    $foto = '/img/default.png';
    $email = 'Guest';

    $persetujuanrole   = [1337, 1, 2, 4001, 5001];
    $petugasrole       = [1337, 1, 2, 4001, 5001];
    $petugasdokterrole = [1337, 1, 2, 3, 4001, 5001];
    $dokterrole        = [1337, 1, 3, 4001, 5001];

    if (is_array($userDetails)) {
        $role  = $userDetails['role']  ?? null;
        // $foto  = $userDetails['foto']  ?? $foto;
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
                <div class="sm:hidden">
                    <button type="button" class="w-[2.375rem] h-[2.375rem] inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-full border border-transparent text-gray-800 hover:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-gray-700 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                        <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8" />
                            <path d="m21 21-4.3-4.3" />
                        </svg>
                    </button>
                </div>

                <div class="hidden sm:block">
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
                document.addEventListener('DOMContentLoaded', function () {
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

                <div class="flex flex-row items-center justify-end gap-2">



                    <div class="hs-dropdown relative inline-flex [--placement:bottom-right]">

                        <button id="hs-dropdown-with-header" type="button" class="w-[2.375rem] h-[2.375rem] inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-full border border-transparent text-gray-800 hover:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-gray-700 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                        <img class="inline-block size-[38px] rounded-full ring-2 ring-white dark:ring-gray-800"
     src="<?= $foto ?>"
     alt="Image Description">
                        </button>

                        <div class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden min-w-60 bg-white shadow-md rounded-lg p-2 dark:bg-gray-800 dark:border dark:border-gray-700" aria-labelledby="hs-dropdown-with-header">
                            <div class="py-3 px-5 -m-2 bg-gray-100 rounded-t-lg dark:bg-gray-700">
                                <p class="text-sm text-gray-500 dark:text-gray-400">Masuk sebagai</p>
                                <p class="text-sm font-medium text-gray-800 dark:text-gray-300"><?= $email ?></p>
                            </div>
                            <div class="mt-2 py-2 first:pt-0 last:pb-0">
                                <a class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:ring-2 focus:ring-blue-500 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-300" href="#">
                                    <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9" />
                                        <path d="M10.3 21a1.94 1.94 0 0 0 3.4 0" />
                                    </svg>
                                    Newsletter
                                </a>
                                <a class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:ring-2 focus:ring-blue-500 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-300" href="#">
                                    <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z" />
                                        <path d="M3 6h18" />
                                        <path d="M16 10a4 4 0 0 1-8 0" />
                                    </svg>
                                    Purchases
                                </a>

                                <a class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:ring-2 focus:ring-blue-500 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-300" href="/profile">
                                    <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                                        <circle cx="9" cy="7" r="4" />
                                        <path d="M22 21v-2a4 4 0 0 0-3-3.87" />
                                        <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                                    </svg>
                                    Lihat profil
                                </a>
                                <a class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:ring-2 focus:ring-blue-500 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-300" href="#">
                                    <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M4 14.899A7 7 0 1 1 15.71 8h1.79a4.5 4.5 0 0 1 2.5 8.242" />
                                        <path d="M12 12v9" />
                                        <path d="m8 17 4 4 4-4" />
                                    </svg>
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
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                        <path d="M7.51675 2.36664L3.02508 5.86664C2.27508 6.44997 1.66675 7.69164 1.66675 8.63331V14.8083C1.66675 16.7416 3.24175 18.325 5.17508 18.325H14.8251C16.7584 18.325 18.3334 16.7416 18.3334 14.8166V8.74997C18.3334 7.74164 17.6584 6.44997 16.8334 5.87497L11.6834 2.26664C10.5167 1.44997 8.64175 1.49164 7.51675 2.36664Z" stroke="#272727" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M10 14.9917V12.4917" stroke="#272727" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    Beranda
                </a>
                </li>

                <li>
                    <a class="w-full flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-lg hover:bg-gray-100 dark:hover:bg-teal-900 dark:text-slate-400 dark:hover:text-slate-300 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" href="/profile">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                            <path d="M3.10488 12.8234L3.10488 12.8234L3.10661 12.8224C4.56789 11.9517 6.26747 11.4292 8.08886 11.3861C7.83131 12.1848 7.73369 13.0293 7.80483 13.8709C7.87103 14.654 8.08168 15.4151 8.4232 16.1166H2.50008C2.3455 16.1166 2.21675 15.9878 2.21675 15.8333V14.4583C2.21675 13.7752 2.55497 13.1484 3.10488 12.8234Z" stroke="#272727" stroke-width="1.1" />
                            <path d="M11.1167 6.66659C11.1167 8.20378 9.87053 9.44992 8.33333 9.44992C6.79614 9.44992 5.55 8.20378 5.55 6.66659C5.55 5.12939 6.79614 3.88325 8.33333 3.88325C9.87053 3.88325 11.1167 5.12939 11.1167 6.66659Z" stroke="#272727" stroke-width="1.1" />
                            <path d="M17.2917 13.3334C17.2917 13.15 17.2667 12.9834 17.2417 12.8084L17.9417 12.2C18.0917 12.0667 18.125 11.85 18.025 11.675L17.5333 10.825C17.4862 10.7414 17.411 10.677 17.321 10.6435C17.231 10.6099 17.1321 10.6093 17.0417 10.6417L16.1583 10.9417C15.8917 10.7167 15.5917 10.5417 15.2583 10.4167L15.075 9.50838C15.0553 9.41448 15.0041 9.33016 14.9298 9.2695C14.8555 9.20883 14.7626 9.1755 14.6667 9.17505H13.6833C13.4833 9.17505 13.3167 9.31672 13.275 9.50838L13.0917 10.4167C12.7583 10.5417 12.4583 10.7167 12.1917 10.9417L11.3083 10.6417C11.2178 10.6106 11.1193 10.6119 11.0297 10.6454C10.94 10.6788 10.8647 10.7423 10.8166 10.825L10.325 11.675C10.225 11.85 10.2583 12.0667 10.4083 12.2L11.1083 12.8084C11.0833 12.9834 11.0583 13.15 11.0583 13.3334C11.0583 13.5167 11.0833 13.6834 11.1083 13.8584L10.4083 14.4667C10.2583 14.6 10.225 14.8167 10.325 14.9917L10.8166 15.8417C10.9166 16.0167 11.125 16.0917 11.3083 16.025L12.1917 15.725C12.4583 15.95 12.7583 16.125 13.0917 16.25L13.275 17.1584C13.3167 17.35 13.4833 17.4917 13.6833 17.4917H14.6667C14.8667 17.4917 15.0333 17.35 15.075 17.1584L15.2583 16.25C15.5917 16.125 15.8917 15.95 16.1583 15.725L17.0417 16.025C17.2333 16.0917 17.4333 16.0084 17.5333 15.8417L18.025 14.9917C18.125 14.8167 18.0917 14.6 17.9417 14.4667L17.2417 13.8584C17.2667 13.6834 17.2917 13.5167 17.2917 13.3334ZM14.1667 15C13.25 15 12.5 14.25 12.5 13.3334C12.5 12.4167 13.25 11.6667 14.1667 11.6667C15.0833 11.6667 15.8333 12.4167 15.8333 13.3334C15.8333 14.25 15.0833 15 14.1667 15Z" fill="#272727" />
                        </svg>
                        Akun
                    </a>

                </li>

                <li class="hs-accordion" id="account-accordion">
                    <button type="button" class="hs-accordion-toggle hs-accordion-active:bg-gray-100 w-full flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-lg hover:bg-gray-100 dark:hover:bg-teal-900 dark:text-slate-400 dark:hover:text-slate-300 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                            <path d="M5.62505 10C5.62952 11.6394 5.25069 13.2572 4.5188 14.7242C4.48386 14.8003 4.434 14.8685 4.37218 14.925C4.31037 14.9814 4.23784 15.0248 4.15892 15.0527C4.08 15.0806 3.99628 15.0923 3.91274 15.0872C3.8292 15.0821 3.74753 15.0602 3.67259 15.023C3.59764 14.9857 3.53094 14.9338 3.47645 14.8703C3.42196 14.8067 3.38078 14.7329 3.35536 14.6532C3.32995 14.5734 3.3208 14.4894 3.32848 14.406C3.33615 14.3227 3.36049 14.2417 3.40005 14.168C4.04536 12.8736 4.37924 11.4463 4.37505 10C4.37358 9.1566 4.5625 8.32374 4.92773 7.56351C5.29297 6.80329 5.82508 6.13531 6.48442 5.60938C6.54849 5.55808 6.62204 5.5199 6.70086 5.49703C6.77969 5.47415 6.86225 5.46703 6.94382 5.47606C7.0254 5.48509 7.1044 5.5101 7.17631 5.54967C7.24822 5.58923 7.31164 5.64257 7.36294 5.70664C7.41423 5.77071 7.45241 5.84426 7.47528 5.92308C7.49816 6.00191 7.50528 6.08447 7.49625 6.16604C7.48722 6.24762 7.46221 6.32662 7.42264 6.39853C7.38308 6.47044 7.32974 6.53386 7.26567 6.58516C6.75274 6.99409 6.33878 7.5136 6.0547 8.10489C5.77062 8.69619 5.62375 9.344 5.62505 10ZM10 9.375C9.83428 9.375 9.67531 9.44085 9.5581 9.55806C9.44089 9.67527 9.37505 9.83424 9.37505 10C9.37492 12.435 8.75665 14.8301 7.57817 16.9609C7.49778 17.106 7.47829 17.277 7.524 17.4364C7.56972 17.5958 7.67688 17.7305 7.82192 17.8109C7.96696 17.8913 8.138 17.9108 8.2974 17.8651C8.45681 17.8194 8.59153 17.7122 8.67192 17.5672C9.95284 15.2507 10.6249 12.647 10.625 10C10.625 9.83424 10.5592 9.67527 10.442 9.55806C10.3248 9.44085 10.1658 9.375 10 9.375ZM10 6.875C9.17124 6.875 8.37639 7.20424 7.79034 7.79029C7.20429 8.37634 6.87505 9.1712 6.87505 10C6.87505 10.1658 6.94089 10.3247 7.0581 10.4419C7.17531 10.5592 7.33429 10.625 7.50005 10.625C7.66581 10.625 7.82478 10.5592 7.94199 10.4419C8.0592 10.3247 8.12505 10.1658 8.12505 10C8.12505 9.50272 8.32259 9.02581 8.67422 8.67417C9.02585 8.32254 9.50276 8.125 10 8.125C10.4973 8.125 10.9742 8.32254 11.3259 8.67417C11.6775 9.02581 11.875 9.50272 11.875 10C11.8815 12.4851 11.3341 14.9405 10.2727 17.1875C10.2377 17.2619 10.2176 17.3425 10.2137 17.4247C10.2099 17.5069 10.2222 17.589 10.2501 17.6664C10.2779 17.7438 10.3207 17.815 10.3761 17.8759C10.4315 17.9367 10.4983 17.9861 10.5727 18.0211C10.6471 18.0561 10.7277 18.0762 10.8099 18.08C10.8921 18.0839 10.9742 18.0716 11.0516 18.0437C11.129 18.0159 11.2002 17.973 11.2611 17.9177C11.3219 17.8623 11.3713 17.7955 11.4063 17.7211C12.5456 15.307 13.1328 12.6694 13.125 10C13.125 9.1712 12.7958 8.37634 12.2098 7.79029C11.6237 7.20424 10.8288 6.875 10 6.875ZM10 1.875C7.84586 1.87727 5.78056 2.73403 4.25732 4.25727C2.73408 5.78051 1.87732 7.84581 1.87505 10C1.87621 10.7805 1.7441 11.5554 1.48442 12.2914C1.4292 12.4477 1.43835 12.6196 1.50985 12.7692C1.58134 12.9188 1.70934 13.0338 1.86567 13.0891C2.022 13.1443 2.19387 13.1351 2.34346 13.0636C2.49305 12.9921 2.60811 12.8641 2.66333 12.7078C2.96995 11.838 3.12609 10.9223 3.12505 10C3.12505 8.17664 3.84937 6.42795 5.13869 5.13864C6.428 3.84933 8.17668 3.125 10 3.125C11.8234 3.125 13.5721 3.84933 14.8614 5.13864C16.1507 6.42795 16.875 8.17664 16.875 10C16.8754 11.428 16.7369 12.8527 16.4618 14.2539C16.4458 14.3344 16.4459 14.4173 16.4621 14.4978C16.4782 14.5783 16.51 14.6549 16.5557 14.7231C16.6014 14.7913 16.6601 14.8498 16.7285 14.8953C16.7968 14.9408 16.8734 14.9724 16.954 14.9883C16.9939 14.996 17.0344 14.9999 17.075 15C17.2197 14.9998 17.3598 14.9495 17.4715 14.8577C17.5832 14.7658 17.6595 14.638 17.6875 14.4961C17.9786 13.0151 18.1251 11.5093 18.125 10C18.1226 7.84588 17.2657 5.78069 15.7425 4.2575C14.2194 2.7343 12.1542 1.87748 10 1.875ZM7.37505 11.8883C7.21295 11.856 7.04468 11.8893 6.90705 11.9808C6.76943 12.0723 6.67365 12.2146 6.64067 12.3766C6.38707 13.6247 5.93302 14.8236 5.29614 15.9266C5.21326 16.0702 5.19081 16.2408 5.23374 16.4009C5.27667 16.5611 5.38146 16.6976 5.52505 16.7805C5.66864 16.8633 5.83927 16.8858 5.99941 16.8429C6.15955 16.7999 6.29607 16.6952 6.37895 16.5516C7.08277 15.3318 7.58407 14.006 7.86333 12.6258C7.87975 12.5453 7.88014 12.4624 7.86448 12.3817C7.84883 12.3011 7.81743 12.2243 7.77208 12.1558C7.72673 12.0873 7.66832 12.0284 7.60019 11.9825C7.53207 11.9366 7.45556 11.9046 7.37505 11.8883ZM10 4.375C9.76499 4.37505 9.53017 4.38966 9.29692 4.41875C9.13553 4.44298 8.99001 4.52937 8.89148 4.65947C8.79294 4.78957 8.74921 4.95305 8.76961 5.11497C8.79002 5.27689 8.87295 5.42441 9.00068 5.52599C9.12841 5.62757 9.29081 5.67517 9.45317 5.65859C10.0689 5.58203 10.6938 5.63712 11.2867 5.82021C11.8795 6.00331 12.4267 6.31023 12.892 6.72066C13.3573 7.13109 13.7301 7.63568 13.9858 8.20101C14.2415 8.76635 14.3741 9.37954 14.375 10C14.3749 10.8096 14.3248 11.6184 14.225 12.4219C14.2141 12.5036 14.2194 12.5867 14.2407 12.6663C14.2619 12.7459 14.2988 12.8206 14.349 12.886C14.3993 12.9513 14.462 13.0061 14.5335 13.0471C14.605 13.0881 14.6839 13.1146 14.7657 13.125C14.7916 13.1281 14.8177 13.1297 14.8438 13.1297C14.9958 13.1294 15.1425 13.0737 15.2564 12.973C15.3702 12.8723 15.4435 12.7336 15.4625 12.5828C15.5679 11.7275 15.6201 10.8665 15.6188 10.0047C15.6184 8.51362 15.0266 7.08358 13.9733 6.02821C12.92 4.97285 11.4911 4.37831 10 4.375ZM14.5258 14.3945C14.4463 14.374 14.3635 14.3693 14.2822 14.3807C14.2009 14.3922 14.1226 14.4196 14.0519 14.4613C13.9811 14.503 13.9193 14.5582 13.8699 14.6239C13.8205 14.6895 13.7846 14.7642 13.7641 14.8438C13.65 15.2883 13.518 15.7344 13.3735 16.1719C13.3206 16.3286 13.332 16.5 13.4052 16.6483C13.4785 16.7967 13.6075 16.9099 13.7641 16.9633C13.8283 16.9851 13.8956 16.9962 13.9633 16.9961C14.0943 16.996 14.2219 16.9548 14.3281 16.8782C14.4344 16.8017 14.5139 16.6937 14.5555 16.5695C14.7118 16.107 14.8516 15.632 14.9735 15.1594C14.9944 15.0799 14.9995 14.997 14.9884 14.9155C14.9772 14.8341 14.9501 14.7556 14.9086 14.6846C14.8671 14.6136 14.812 14.5515 14.7464 14.5019C14.6809 14.4523 14.6062 14.416 14.5266 14.3953L14.5258 14.3945Z" fill="#272727" />
                        </svg>
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
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                        <g clip-path="url(#clip0_2232_16231)">
                                            <path d="M1.66748 9.99902H13.3341M13.3341 9.99902L10.4175 7.49902M13.3341 9.99902L10.4175 12.499" stroke="#272727" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M7.50171 5.83317C7.51171 4.02067 7.59254 3.039 8.23254 2.399C8.96504 1.6665 10.1434 1.6665 12.5 1.6665H13.3334C15.6909 1.6665 16.8692 1.6665 17.6017 2.399C18.3334 3.13067 18.3334 4.30984 18.3334 6.6665V13.3332C18.3334 15.6898 18.3334 16.869 17.6017 17.6007C16.8684 18.3332 15.6909 18.3332 13.3334 18.3332H12.5C10.1434 18.3332 8.96504 18.3332 8.23254 17.6007C7.59254 16.9607 7.51171 15.979 7.50171 14.1665" stroke="#272727" stroke-width="1.5" stroke-linecap="round" />
                                        </g>
                                        <defs>
                                            <clipPath id="clip0_2232_16231">
                                                <rect width="20" height="20" fill="white" />
                                            </clipPath>
                                        </defs>
                                    </svg>
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
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                        <path d="M11.6667 17.7082H8.33342V18.9582H11.6667V17.7082ZM2.29175 11.6665V8.33322H1.04175V11.6665H2.29175ZM17.7084 11.3024V11.6665H18.9584V11.3024H17.7084ZM12.4092 3.84238L15.7084 6.81155L16.5442 5.88155L13.2459 2.91238L12.4092 3.84238ZM18.9584 11.3024C18.9584 9.89488 18.9709 9.00322 18.6167 8.20655L17.4742 8.71572C17.6959 9.21405 17.7084 9.78488 17.7084 11.3024H18.9584ZM15.7084 6.81155C16.8359 7.82655 17.2526 8.21822 17.4742 8.71572L18.6167 8.20655C18.2617 7.40905 17.5909 6.82322 16.5442 5.88155L15.7084 6.81155ZM8.35842 2.29155C9.67675 2.29155 10.1742 2.30155 10.6167 2.47155L11.0651 1.30488C10.3551 1.03155 9.58175 1.04155 8.35842 1.04155V2.29155ZM13.2459 2.91322C12.3409 2.09905 11.7751 1.57655 11.0651 1.30488L10.6176 2.47155C11.0609 2.64155 11.4342 2.96488 12.4092 3.84238L13.2459 2.91322ZM8.33342 17.7082C6.74425 17.7082 5.61592 17.7066 4.75842 17.5916C3.92092 17.479 3.43758 17.2674 3.08508 16.9149L2.20175 17.7982C2.82508 18.4232 3.61592 18.699 4.59258 18.8307C5.55092 18.9599 6.78008 18.9582 8.33342 18.9582V17.7082ZM1.04175 11.6665C1.04175 13.2199 1.04008 14.4482 1.16925 15.4074C1.30092 16.3841 1.57758 17.1749 2.20092 17.799L3.08425 16.9157C2.73258 16.5624 2.52092 16.079 2.40842 15.2407C2.29342 14.3849 2.29175 13.2557 2.29175 11.6665H1.04175ZM11.6667 18.9582C13.2201 18.9582 14.4484 18.9599 15.4076 18.8307C16.3842 18.699 17.1751 18.4224 17.7992 17.799L16.9159 16.9157C16.5626 17.2674 16.0792 17.479 15.2409 17.5916C14.3851 17.7066 13.2559 17.7082 11.6667 17.7082V18.9582ZM17.7084 11.6665C17.7084 13.2557 17.7067 14.3849 17.5917 15.2416C17.4792 16.0791 17.2676 16.5624 16.9151 16.9149L17.7984 17.7982C18.4234 17.1749 18.6992 16.3841 18.8309 15.4074C18.9601 14.449 18.9584 13.2199 18.9584 11.6665H17.7084ZM2.29175 8.33322C2.29175 6.74405 2.29342 5.61572 2.40842 4.75822C2.52092 3.92072 2.73258 3.43738 3.08508 3.08488L2.20175 2.20155C1.57675 2.82488 1.30092 3.61572 1.16925 4.59238C1.04008 5.55072 1.04175 6.77988 1.04175 8.33322H2.29175ZM8.35842 1.04155C6.79592 1.04155 5.56175 1.03988 4.59925 1.16905C3.61842 1.30072 2.82508 1.57738 2.20092 2.20072L3.08425 3.08405C3.43758 2.73238 3.92175 2.52072 4.76508 2.40822C5.62592 2.29322 6.76092 2.29155 8.35842 2.29155V1.04155Z" fill="#272727" />
                                        <path d="M10.8335 2.08325V4.16659C10.8335 6.13075 10.8335 7.11325 11.4435 7.72325C12.0535 8.33325 13.036 8.33325 15.0002 8.33325H18.3335" stroke="#272727" stroke-width="1.5" />
                                        <path d="M7.08341 15.4167V11.25M7.08341 11.25L5.41675 12.8125M7.08341 11.25L8.75008 12.8125" stroke="#272727" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
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
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                        <path d="M10.0001 15C14.6026 15 18.3334 10 18.3334 10C18.3334 10 14.6026 5 10.0001 5C5.39758 5 1.66675 10 1.66675 10C1.66675 10 5.39758 15 10.0001 15Z" stroke="#272727" stroke-width="2" stroke-linejoin="round" />
                                        <path d="M10.0001 12.0834C10.5526 12.0834 11.0825 11.8639 11.4732 11.4732C11.8639 11.0825 12.0834 10.5526 12.0834 10.0001C12.0834 9.44755 11.8639 8.91764 11.4732 8.52694C11.0825 8.13624 10.5526 7.91675 10.0001 7.91675C9.44755 7.91675 8.91764 8.13624 8.52694 8.52694C8.13624 8.91764 7.91675 9.44755 7.91675 10.0001C7.91675 10.5526 8.13624 11.0825 8.52694 11.4732C8.91764 11.8639 9.44755 12.0834 10.0001 12.0834Z" stroke="#272727" stroke-width="2" stroke-linejoin="round" />
                                    </svg>
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
                    <?php if (session('user_details')['role'] === 2) : ?>
                        <a class="w-full flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-lg hover:bg-teal-200 dark:hover:bg-teal-900 dark:text-slate-400 dark:hover:text-slate-300 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" href="/detailberkaspegawai/<?php echo session('user_specific_data')['pegawai'] ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                <path d="M15.8334 5H13.3334V4.16667C13.3334 3.25 12.5834 2.5 11.6667 2.5H8.33341C7.41675 2.5 6.66675 3.25 6.66675 4.16667V5H4.16675C2.75008 5 1.66675 6.08333 1.66675 7.5V15C1.66675 16.4167 2.75008 17.5 4.16675 17.5H15.8334C17.2501 17.5 18.3334 16.4167 18.3334 15V7.5C18.3334 6.08333 17.2501 5 15.8334 5ZM8.33341 4.16667H11.6667V5H8.33341V4.16667ZM16.6667 15C16.6667 15.5 16.3334 15.8333 15.8334 15.8333H4.16675C3.66675 15.8333 3.33341 15.5 3.33341 15V10.3333H7.00024C7.00024 10.3333 7.00024 10.3333 7.00024 10.3333C7.00024 10.3333 12.4169 10.3333 12.5002 10.3333C12.5836 10.3333 13.4246 10.3333 13.4246 10.3333L16.6667 10.25V15Z" fill="#272727" />
                                <path d="M15.8332 5H4.1665C3.50346 5 2.86758 5.26339 2.39874 5.73223C1.9299 6.20107 1.6665 6.83696 1.6665 7.5V9.76667L6.74797 11.4612C7.15599 11.5973 7.58328 11.6667 8.01338 11.6667H11.9863C12.4164 11.6667 12.8437 11.5973 13.2517 11.4612L18.3332 9.76667V7.5C18.3332 6.83696 18.0698 6.20107 17.6009 5.73223C17.1321 5.26339 16.4962 5 15.8332 5Z" fill="#272727" />
                            </svg>
                            Pegawai
                        </a>
                    <?php else : ?>

                <li class="hs-accordion" id="account-accordion">
                    <button type="button" class="hs-accordion-toggle hs-accordion-active:text-slate-700 hs-accordion-active:hover:bg-teal-200 w-full text-start flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-700 rounded-lg hover:bg-teal-200 dark:bg-neutral-800 dark:hover:bg-neutral-700 dark:text-neutral-400 dark:hover:text-neutral-300 dark:hs-accordion-active:text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                            <path d="M15.8334 5H13.3334V4.16667C13.3334 3.25 12.5834 2.5 11.6667 2.5H8.33341C7.41675 2.5 6.66675 3.25 6.66675 4.16667V5H4.16675C2.75008 5 1.66675 6.08333 1.66675 7.5V15C1.66675 16.4167 2.75008 17.5 4.16675 17.5H15.8334C17.2501 17.5 18.3334 16.4167 18.3334 15V7.5C18.3334 6.08333 17.2501 5 15.8334 5ZM8.33341 4.16667H11.6667V5H8.33341V4.16667ZM16.6667 15C16.6667 15.5 16.3334 15.8333 15.8334 15.8333H4.16675C3.66675 15.8333 3.33341 15.5 3.33341 15V10.3333H7.00024C7.00024 10.3333 7.00024 10.3333 7.00024 10.3333C7.00024 10.3333 12.4169 10.3333 12.5002 10.3333C12.5836 10.3333 13.4246 10.3333 13.4246 10.3333L16.6667 10.25V15Z" fill="#272727" />
                            <path d="M15.8332 5H4.1665C3.50346 5 2.86758 5.26339 2.39874 5.73223C1.9299 6.20107 1.6665 6.83696 1.6665 7.5V9.76667L6.74797 11.4612C7.15599 11.5973 7.58328 11.6667 8.01338 11.6667H11.9863C12.4164 11.6667 12.8437 11.5973 13.2517 11.4612L18.3332 9.76667V7.5C18.3332 6.83696 18.0698 6.20107 17.6009 5.73223C17.1321 5.26339 16.4962 5 15.8332 5Z" fill="#272727" />
                        </svg>
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
                                <a class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-700 rounded-lg hover:bg-teal-200 dark:bg-neutral-800 dark:text-neutral-400 dark:hover:text-neutral-300" href="/detailberkaspegawai/<?php echo session('user_specific_data')['pegawai'] ?>">
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
                <?php if (in_array($role, $petugasrole)) { ?>
                <li class="hs-accordion" id="users-accordion">
                    <button type="button" class="hs-accordion-toggle hs-accordion-active:bg-gray-100 w-full flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-lg hover:bg-gray-100 dark:hover:bg-teal-900 dark:text-slate-400 dark:hover:text-slate-300 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                            <path d="M16.6667 1.66699H3.33341C2.50008 1.66699 1.66675 2.41699 1.66675 3.33366V5.84199C1.66675 6.44199 2.02508 6.95866 2.50008 7.25033V16.667C2.50008 17.5837 3.41675 18.3337 4.16675 18.3337H15.8334C16.5834 18.3337 17.5001 17.5837 17.5001 16.667V7.25033C17.9751 6.95866 18.3334 6.44199 18.3334 5.84199V3.33366C18.3334 2.41699 17.5001 1.66699 16.6667 1.66699ZM15.8334 16.667H4.16675V7.50033H15.8334V16.667ZM16.6667 5.83366H3.33341V3.33366L16.6667 3.31699V5.83366Z" fill="#272727" />
                            <path d="M7.5 10H12.5V11.6667H7.5V10Z" fill="#272727" />
                        </svg>
                        Inventaris

                        <svg class="hs-accordion-active:block ms-auto hidden size-4 text-gray-600 group-hover:text-gray-500 dark:text-neutral-400" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m18 15-6-6-6 6" />
                        </svg>

                        <svg class="hs-accordion-active:hidden ms-auto block size-4 text-gray-600 group-hover:text-gray-500 dark:text-neutral-400" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m6 9 6 6 6-6" />
                        </svg>
                    </button>

                    <div id="users-accordion" class="hs-accordion-content w-full overflow-hidden transition-[height] duration-300 hidden">
                        <ul class="hs-accordion-group ps-3 pt-2" data-hs-accordion-always-open>
                            <li class="hs-accordion" id="users-accordion-sub-1">
                                <button type="button" class="hs-accordion-toggle hs-accordion-active:bg-gray-100 w-full text-start flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-700 rounded-lg hover:bg-gray-100 dark:bg-neutral-800 dark:hover:bg-neutral-700 dark:text-neutral-400 dark:hover:text-neutral-300 dark:hs-accordion-active:text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                        <path d="M10.0434 1.04199H9.95675C9.20758 1.04199 8.58341 1.04199 8.08841 1.10866C7.56508 1.17866 7.09258 1.33366 6.71341 1.71283C6.33341 2.09283 6.17841 2.56533 6.10841 3.08783C6.04175 3.58366 6.04175 4.20866 6.04175 4.95699V5.02199C6.42258 5.00949 6.83758 5.00449 7.29175 5.00199V5.00033C7.29175 4.19699 7.29341 3.65699 7.34758 3.25449C7.39925 2.87033 7.48841 2.70533 7.59758 2.59699C7.70591 2.48866 7.87008 2.39949 8.25425 2.34699C8.65675 2.29366 9.19675 2.29199 10.0001 2.29199C10.8034 2.29199 11.3434 2.29366 11.7459 2.34783C12.1301 2.39949 12.2951 2.48866 12.4034 2.59783C12.5117 2.70616 12.6009 2.87033 12.6534 3.25449C12.7067 3.65699 12.7084 4.19699 12.7084 5.00033V5.00199C13.1252 5.00293 13.5419 5.00959 13.9584 5.02199V4.95699C13.9584 4.20866 13.9584 3.58366 13.8917 3.08866C13.8217 2.56533 13.6667 2.09283 13.2867 1.71366C12.9076 1.33366 12.4351 1.17866 11.9117 1.10866C11.4167 1.04199 10.7917 1.04199 10.0434 1.04199Z" fill="#26B29D" />
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M1.66675 11.6667C1.66675 8.52417 1.66675 6.9525 2.64341 5.97667C3.61925 5 5.19091 5 8.33341 5H11.6667C14.8092 5 16.3809 5 17.3567 5.97667C18.3334 6.9525 18.3334 8.52417 18.3334 11.6667C18.3334 14.8092 18.3334 16.3808 17.3567 17.3567C16.3809 18.3333 14.8092 18.3333 11.6667 18.3333H8.33341C5.19091 18.3333 3.61925 18.3333 2.64341 17.3567C1.66675 16.3808 1.66675 14.8092 1.66675 11.6667ZM10.6251 10.4167C10.6251 10.2509 10.5592 10.0919 10.442 9.97473C10.3248 9.85751 10.1658 9.79167 10.0001 9.79167C9.83432 9.79167 9.67535 9.85751 9.55814 9.97473C9.44093 10.0919 9.37508 10.2509 9.37508 10.4167V11.0417H8.75008C8.58432 11.0417 8.42535 11.1075 8.30814 11.2247C8.19093 11.3419 8.12508 11.5009 8.12508 11.6667C8.12508 11.8324 8.19093 11.9914 8.30814 12.1086C8.42535 12.2258 8.58432 12.2917 8.75008 12.2917H9.37508V12.9167C9.37508 13.0824 9.44093 13.2414 9.55814 13.3586C9.67535 13.4758 9.83432 13.5417 10.0001 13.5417C10.1658 13.5417 10.3248 13.4758 10.442 13.3586C10.5592 13.2414 10.6251 13.0824 10.6251 12.9167V12.2917H11.2501C11.4158 12.2917 11.5748 12.2258 11.692 12.1086C11.8092 11.9914 11.8751 11.8324 11.8751 11.6667C11.8751 11.5009 11.8092 11.3419 11.692 11.2247C11.5748 11.1075 11.4158 11.0417 11.2501 11.0417H10.6251V10.4167Z" fill="#0A2D27" />
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M10.0001 14.9997C10.8841 14.9997 11.732 14.6485 12.3571 14.0234C12.9822 13.3982 13.3334 12.5504 13.3334 11.6663C13.3334 10.7823 12.9822 9.93444 12.3571 9.30932C11.732 8.6842 10.8841 8.33301 10.0001 8.33301C9.11603 8.33301 8.26818 8.6842 7.64306 9.30932C7.01794 9.93444 6.66675 10.7823 6.66675 11.6663C6.66675 12.5504 7.01794 13.3982 7.64306 14.0234C8.26818 14.6485 9.11603 14.9997 10.0001 14.9997ZM10.6251 10.4163C10.6251 10.2506 10.5592 10.0916 10.442 9.9744C10.3248 9.85719 10.1658 9.79134 10.0001 9.79134C9.83432 9.79134 9.67535 9.85719 9.55814 9.9744C9.44093 10.0916 9.37508 10.2506 9.37508 10.4163V11.0413H8.75008C8.58432 11.0413 8.42535 11.1072 8.30814 11.2244C8.19093 11.3416 8.12508 11.5006 8.12508 11.6663C8.12508 11.8321 8.19093 11.9911 8.30814 12.1083C8.42535 12.2255 8.58432 12.2913 8.75008 12.2913H9.37508V12.9163C9.37508 13.0821 9.44093 13.2411 9.55814 13.3583C9.67535 13.4755 9.83432 13.5413 10.0001 13.5413C10.1658 13.5413 10.3248 13.4755 10.442 13.3583C10.5592 13.2411 10.6251 13.0821 10.6251 12.9163V12.2913H11.2501C11.4158 12.2913 11.5748 12.2255 11.692 12.1083C11.8092 11.9911 11.8751 11.8321 11.8751 11.6663C11.8751 11.5006 11.8092 11.3416 11.692 11.2244C11.5748 11.1072 11.4158 11.0413 11.2501 11.0413H10.6251V10.4163Z" fill="#26B29D" />
                                    </svg>
                                    Barang Medis

                                    <svg class="hs-accordion-active:block ms-auto hidden size-4 text-gray-600 group-hover:text-gray-500 dark:text-neutral-400" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="m18 15-6-6-6 6" />
                                    </svg>

                                    <svg class="hs-accordion-active:hidden ms-auto block size-4 text-gray-600 group-hover:text-gray-500 dark:text-neutral-400" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="m6 9 6 6 6-6" />
                                    </svg>
                                </button>

                                <div id="users-accordion-sub-1" class="border-[#F1F1F1] border-l-[2px] mt-2 hs-accordion-content w-full overflow-hidden transition-[height] duration-300 hidden">
                                    <ul class="ps-2">
                                        <li>
                                            <a href="/datamedis" class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-lg hover:bg-gray-100 dark:bg-gray-800 dark:text-slate-400 dark:hover:text-slate-300 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                                                Data
                                            </a>
                                        </li>
                                        <li>
                                            <a href="/stokopnamemedis" class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-lg hover:bg-gray-100 dark:bg-gray-800 dark:text-slate-400 dark:hover:text-slate-300 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" href="#">
                                                Stok Opname
                                            </a>
                                        </li>
                                        <li>
                                            <a href="/mutasimedis" class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-lg hover:bg-gray-100 dark:bg-gray-800 dark:text-slate-400 dark:hover:text-slate-300 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" href="#">
                                                Mutasi Antar Gudang
                                            </a>
                                        </li>
                                        <!-- <li>
                                            <a href="/stokkeluarmedis" class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-lg hover:bg-gray-100 dark:bg-gray-800 dark:text-slate-400 dark:hover:text-slate-300 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" href="#">
                                               Surat Pemesanan Obat & BHP
                                            </a>
                                        </li> -->
                                        <li>
                                            <a href="/penerimaanmedis" class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-lg hover:bg-gray-100 dark:bg-gray-800 dark:text-slate-400 dark:hover:text-slate-300 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" href="#">
                                                Penerimaan Obat & BHP
                                            </a>
                                        </li>
                                        <li>
                                            <a href="/stokkeluarmedis" class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-lg hover:bg-gray-100 dark:bg-gray-800 dark:text-slate-400 dark:hover:text-slate-300 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" href="#">
                                                Stok Keluar
                                            </a>
                                        </li>
                                        <li>
                                            <a href="/sisastokmedis" class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-lg hover:bg-gray-100 dark:bg-gray-800 dark:text-slate-400 dark:hover:text-slate-300 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" href="#">
                                                Sisa Stok
                                            </a>
                                        </li>
                                        <li>
                                            <a href="/batchmedis" class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-lg hover:bg-gray-100 dark:bg-gray-800 dark:text-slate-400 dark:hover:text-slate-300 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" href="#">
                                                Data Batch
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>

                        </ul>
                    </div>
                </li>
                                <?php } ?>
                <li class="hs-accordion" id="users-accordion">
                    <button type="button" class="hs-accordion-toggle hs-accordion-active:bg-gray-100 w-full flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-lg hover:bg-gray-100 dark:hover:bg-teal-900 dark:text-slate-400 dark:hover:text-slate-300 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                    <svg class="h-8 w-8 text-slate-950"  width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">  <path stroke="none" d="M0 0h24v24H0z"/>  <path d="M11 7h-5a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-5" />  <line x1="10" y1="14" x2="20" y2="4" />  <polyline points="15 4 20 4 20 9" /></svg>
                        Rujukan
                        <svg class="hs-accordion-active:block ms-auto hidden size-4 text-gray-600 group-hover:text-gray-500 dark:text-neutral-400" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m18 15-6-6-6 6" />
                        </svg>

                        <svg class="hs-accordion-active:hidden ms-auto block size-4 text-gray-600 group-hover:text-gray-500 dark:text-neutral-400" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m6 9 6 6 6-6" />
                        </svg>
                    </button>
                    <div id="users-accordion-sub-1" class="border-[#F1F1F1] border-l-[2px] mt-2 hs-accordion-content w-full overflow-hidden transition-[height] duration-300 hidden">
                        <ul class="ps-2">
                            <li>
                                <a href="/rujukanmasuk" class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-lg hover:bg-gray-100 dark:bg-gray-800 dark:text-slate-400 dark:hover:text-slate-300 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                                    Rujukan Masuk
                                </a>
                            </li>
                            <li>
                                <a href="/rujukankeluar" class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-lg hover:bg-gray-100 dark:bg-gray-800 dark:text-slate-400 dark:hover:text-slate-300 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" href="#">
                                    Rujukan Keluar
                                </a>
                            </li>
                        </ul>
                    </div>
    
                </li>
                <?php if (in_array($role, $persetujuanrole)) { ?>
                    <li>
                        <a class="w-full flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-lg hover:bg-gray-100 dark:hover:bg-teal-900 dark:text-slate-400 dark:hover:text-slate-300 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" href="/persetujuanpengajuan">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                <path d="M11.1458 18.1869L5.83325 16.6869V9.16602H7.16659C7.26381 9.16602 7.36103 9.17657 7.45825 9.19768C7.55547 9.21879 7.6527 9.24296 7.74992 9.27018L13.5208 11.416C13.7152 11.4855 13.8716 11.6105 13.9899 11.791C14.1083 11.9716 14.1671 12.1591 14.1666 12.3535C14.1666 12.6452 14.0658 12.8813 13.8641 13.0619C13.6624 13.2424 13.4299 13.3327 13.1666 13.3327H10.9791C10.9096 13.3327 10.8577 13.3294 10.8233 13.3227C10.7888 13.316 10.7435 13.2985 10.6874 13.2702L9.74992 12.916C9.63881 12.8744 9.5277 12.8813 9.41658 12.9369C9.30547 12.9924 9.23603 13.0688 9.20825 13.166C9.18047 13.2771 9.18742 13.3813 9.22908 13.4785C9.27075 13.5757 9.34714 13.6452 9.45825 13.6869L10.6874 14.1244C10.7152 14.1382 10.7569 14.1488 10.8124 14.156C10.868 14.1632 10.9166 14.1666 10.9583 14.166H16.6666C17.111 14.166 17.4999 14.3257 17.8333 14.6452C18.1666 14.9646 18.3333 15.3605 18.3333 15.8327L12.1874 18.1452C12.0485 18.2007 11.8785 18.2321 11.6774 18.2394C11.4763 18.2466 11.2991 18.2291 11.1458 18.1869ZM0.833252 16.666V10.8327C0.833252 10.3744 0.996585 9.98213 1.32325 9.65602C1.64992 9.32991 2.04214 9.16657 2.49992 9.16602C2.9577 9.16546 3.3502 9.3288 3.67742 9.65602C4.00464 9.98324 4.1677 10.3755 4.16659 10.8327V16.666C4.16659 17.1244 4.00353 17.5169 3.67742 17.8435C3.35131 18.1702 2.95881 18.3332 2.49992 18.3327C2.04103 18.3321 1.64881 18.1691 1.32325 17.8435C0.997696 17.518 0.834363 17.1255 0.833252 16.666Z" fill="#0A2D27" />
                                <path d="M14.9166 2.72873L10.7707 6.85373L8.99991 5.0829C8.83324 4.91567 8.6388 4.83567 8.41657 4.8429C8.19435 4.85012 7.99991 4.93012 7.83324 5.0829C7.66602 5.24956 7.57907 5.44401 7.57241 5.66623C7.56574 5.88845 7.64574 6.0829 7.81241 6.24956L10.1874 8.62456C10.3541 8.79123 10.5485 8.87456 10.7707 8.87456C10.993 8.87456 11.1874 8.79123 11.3541 8.62456L16.0832 3.8954C16.236 3.74262 16.3124 3.54817 16.3124 3.31206C16.3124 3.07595 16.236 2.88151 16.0832 2.72873C15.9171 2.56151 15.7193 2.48151 15.4899 2.48873C15.2605 2.49595 15.0694 2.57595 14.9166 2.72873Z" fill="#26B29D" />
                            </svg>
                            Persetujuan
                        </a>

                    </li>
                <?php } ?>
                </li>
                <?php if (in_array($role, $petugasrole)) { ?>
                    <li>
                        <a class="w-full flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-lg hover:bg-gray-100 dark:hover:bg-teal-900 dark:text-slate-400 dark:hover:text-slate-300 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" href="/registrasi">
                        <svg class="h-8 w-8 text-slate-950"  width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">  <path stroke="none" d="M0 0h24v24H0z"/>  <path d="M5 4h4l3 3h7a2 2 0 0 1 2 2v8a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-11a2 2 0 0 1 2 -2" />  <line x1="12" y1="10" x2="12" y2="16" />  <line x1="9" y1="13" x2="15" y2="13" /></svg>
                            Registrasi
                        </a>

                    </li>
                <?php } ?>
                </li>
                <?php if (in_array($role, $petugasdokterrole)) { ?>

                    <li>
                        <a class="w-full flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-lg hover:bg-gray-100 dark:hover:bg-teal-900 dark:text-slate-400 dark:hover:text-slate-300 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" href="/rawatinap">
                        <svg class="h-8 w-8 text-slate-950"  width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">  <path stroke="none" d="M0 0h24v24H0z"/>  <path d="M3 7v11m0 -4h18m0 4v-8a2 2 0 0 0 -2 -2h-8v6" />  <circle cx="7" cy="10" r="1" /></svg>
                            Rawat Inap
                        </a>

                    </li>
                <?php } ?>
                </li>

                <?php if (in_array($role, $petugasrole)) { ?>
                    <li>
                        <a class="w-full flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-lg hover:bg-gray-100 dark:hover:bg-teal-900 dark:text-slate-400 dark:hover:text-slate-300 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" href="/kamar">
                        <svg class="h-8 w-8 text-slate-950"  width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">  <path stroke="none" d="M0 0h24v24H0z"/>  <line x1="3" y1="21" x2="21" y2="21" />  <path d="M5 21v-16a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v16" />  <path d="M9 21v-4a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v4" />  <line x1="10" y1="9" x2="14" y2="9" />  <line x1="12" y1="7" x2="12" y2="11" /></svg>
                            Ruangan
                        </a>

                    </li>
                <?php } ?>
                </li>

                <?php if (in_array($role, $petugasdokterrole)) { ?>
                    <li>
                        <a class="w-full flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-lg hover:bg-gray-100 dark:hover:bg-teal-900 dark:text-slate-400 dark:hover:text-slate-300 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" href="/ugd">
                        <svg class="h-8 w-8 text-slate-950"  width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">  <path stroke="none" d="M0 0h24v24H0z"/>  <path d="M8 16v-4a4 4 0 0 1 8 0v4" />  <path d="M3 12h1M12 3v1M20 12h1M5.6 5.6l.7 .7M18.4 5.6l-.7 .7" />  <rect x="6" y="16" width="12" height="4" rx="1" /></svg>
                            Unit Gawat Darurat
                        </a>

                    </li>
                <?php } ?>
                </li>

                 <?php if (in_array($role, $petugasrole)) { ?>
                    <li>
                        <a class="w-full flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-lg hover:bg-gray-100 dark:hover:bg-teal-900 dark:text-slate-400 dark:hover:text-slate-300 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" href="/ambulans">
                        <svg class="h-8 w-8 text-slate-950"  width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">  <path stroke="none" d="M0 0h24v24H0z"/>  <circle cx="7" cy="17" r="2" />  <circle cx="17" cy="17" r="2" />  <path d="M5 17h-2v-11a1 1 0 0 1 1 -1h9v12m-4 0h6m4 0h2v-6h-8m0 -5h5l3 5" /></svg>
                            Ambulans
                        </a>
                    </li>
                <?php } ?>
                </li>

                 <?php if (in_array($role, $petugasrole)) { ?>
                    <li>
                        <a class="w-full flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-lg hover:bg-gray-100 dark:hover:bg-teal-900 dark:text-slate-400 dark:hover:text-slate-300 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" href="/tindakan">
                        <svg class="h-8 w-8 text-slate-950" width="26" height="26" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/>
                        </svg>
                            Tindakan
                        </a>
                    </li>
                <?php } ?>
                </li>

                <?php if (in_array($role, $petugasrole)) { ?>
                    <li>
                        <a class="w-full flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-lg hover:bg-gray-100 dark:hover:bg-teal-900 dark:text-slate-400 dark:hover:text-slate-300 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" href="/pemeriksaanranap">
                        <svg class="h-8 w-8 text-slate-950"  width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">  <path stroke="none" d="M0 0h24v24H0z"/>  <path d="M8 13.5a4 4 0 1 0 4 0v-8.5a2 2 0 0 0 -4 0v8.5" />  <line x1="8" y1="9" x2="12" y2="9" />  <line x1="16" y1="9" x2="22" y2="9" />  <line x1="19" y1="6" x2="19" y2="12" /></svg>
                            Pemeriksaan
                        </a>
                    </li>
                <?php } ?>
                </li>

                 <?php if (in_array($role, $petugasrole)) { ?>
                    <li>
                        <a class="w-full flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-lg hover:bg-gray-100 dark:hover:bg-teal-900 dark:text-slate-400 dark:hover:text-slate-300 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" href="/dokterjaga">
                        <svg class="h-8 w-8 text-slate-950"  width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">  <path stroke="none" d="M0 0h24v24H0z"/>  <circle cx="9" cy="7" r="4" />  <path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />  <line x1="19" y1="7" x2="19" y2="10" />  <line x1="19" y1="14" x2="19" y2="14.01" /></svg>
                            Dokter Jaga
                        </a>
                    </li>
                <?php } ?>
                </li>

                 <?php if (in_array($role, $petugasdokterrole)) { ?>
                    <li>
                        <a class="w-full flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-lg hover:bg-gray-100 dark:hover:bg-teal-900 dark:text-slate-400 dark:hover:text-slate-300 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" href="/resepobat">
                        <svg class="h-8 w-8 text-slate-950"  width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">  <path stroke="none" d="M0 0h24v24H0z"/>  <path d="M16 6h3a1 1 0 0 1 1 1v11a2 2 0 0 1 -4 0v-13a1 1 0 0 0 -1 -1h-10a1 1 0 0 0 -1 1v12a3 3 0 0 0 3 3h11" />  <line x1="8" y1="8" x2="12" y2="8" />  <line x1="8" y1="12" x2="12" y2="12" />  <line x1="8" y1="16" x2="12" y2="16" /></svg>
                            Resep Obat
                        </a>
                    </li>
                <?php } ?>
                </li>
                <?php if (in_array($role, $petugasrole)) { ?>
                <!-- <li class="hs-accordion" id="users-accordion">
                    <button type="button" class="hs-accordion-toggle hs-accordion-active:bg-gray-100 w-full flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-lg hover:bg-gray-100 dark:hover:bg-teal-900 dark:text-slate-400 dark:hover:text-slate-300 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                    <svg class="h-8 w-8 text-slate-950"  width="23" height="23" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">  <path stroke="none" d="M0 0h24v24H0z"/>  <rect x="4" y="4" width="16" height="16" rx="2" />  <path d="M4 13h3l3 3h4l3 -3h3" /></svg>
                        Stok Obat Pasien

                        <svg class="hs-accordion-active:block ms-auto hidden size-4 text-gray-600 group-hover:text-gray-500 dark:text-neutral-400" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m18 15-6-6-6 6" />
                        </svg>

                        <svg class="hs-accordion-active:hidden ms-auto block size-4 text-gray-600 group-hover:text-gray-500 dark:text-neutral-400" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m6 9 6 6 6-6" />
                        </svg>
                    </button>
                                </button>

                                <div id="users-accordion-sub-1" class="border-[#F1F1F1] border-l-[2px] mt-2 hs-accordion-content w-full overflow-hidden transition-[height] duration-300 hidden">
                                    <ul class="ps-2">
                                        <li>
                                            <a href="/permintaanstokobat" class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-lg hover:bg-gray-100 dark:bg-gray-800 dark:text-slate-400 dark:hover:text-slate-300 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                                                Permintaan Stok Obat Pasien
                                            </a>
                                        </li>
                                        <li>
                                            <a href="/stokobatpasien" class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-lg hover:bg-gray-100 dark:bg-gray-800 dark:text-slate-400 dark:hover:text-slate-300 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" href="#">
                                                Stok Obat Pasien
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                </li> -->
                <?php } ?>
                <?php if (in_array($role, $petugasrole)) { ?>
                    <li>
                        <a class="w-full flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-lg hover:bg-gray-100 dark:hover:bg-teal-900 dark:text-slate-400 dark:hover:text-slate-300 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" href="/pemberianobat">
                        <svg class="h-8 w-8 text-slate-950"  width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">  <path stroke="none" d="M0 0h24v24H0z"/>  <path d="M4.5 12.5l8 -8a4.94 4.94 0 0 1 7 7l-8 8a4.94 4.94 0 0 1 -7 -7" />  <path d="M8.5 8.5l7 7" /></svg>
                            Pemberian Obat
                        </a>
                    </li>

                </li>
                    <?php } ?>
                 <?php if (in_array($role, $petugasrole)) { ?>
                    <li class="hs-accordion" id="users-accordion">
                        <button type="button" class="hs-accordion-toggle hs-accordion-active:bg-gray-100 w-full flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-lg hover:bg-gray-100 dark:hover:bg-teal-900 dark:text-slate-400 dark:hover:text-slate-300 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                        <svg class="h-8 w-8 text-slate-950"  width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">  <path stroke="none" d="M0 0h24v24H0z"/>  <path d="M14 8v-2a2 2 0 0 0 -2 -2h-7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2 -2v-2" />  <path d="M7 12h14l-3 -3m0 6l3 -3" /></svg>
                            Resep Pulang

                            <svg class="hs-accordion-active:block ms-auto hidden size-4 text-gray-600 group-hover:text-gray-500 dark:text-neutral-400" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="m18 15-6-6-6 6" />
                            </svg>

                            <svg class="hs-accordion-active:hidden ms-auto block size-4 text-gray-600 group-hover:text-gray-500 dark:text-neutral-400" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="m6 9 6 6 6-6" />
                            </svg>
                        </button>
                        <div id="users-accordion-sub-1" class="border-[#F1F1F1] border-l-[2px] mt-2 hs-accordion-content w-full overflow-hidden transition-[height] duration-300 hidden">
                            <ul class="ps-2">
                                <li>
                                    <a href="/permintaanreseppulang" class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-lg hover:bg-gray-100 dark:bg-gray-800 dark:text-slate-400 dark:hover:text-slate-300 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                                        Permintaan Resep Pulang
                                    </a>
                                </li>
                                <li>
                                    <a href="/reseppulang" class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-lg hover:bg-gray-100 dark:bg-gray-800 dark:text-slate-400 dark:hover:text-slate-300 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" href="#">
                                        Resep Pulang
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                <?php } ?>
                <?php if (in_array($role, $dokterrole)) { ?>
                    <li class="hs-accordion" id="users-accordion">
                        <button type="button" class="hs-accordion-toggle hs-accordion-active:bg-gray-100 w-full flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-lg hover:bg-gray-100 dark:hover:bg-teal-900 dark:text-slate-400 dark:hover:text-slate-300 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                        <svg class="h-8 w-8 text-slate-950"  width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">  <path stroke="none" d="M0 0h24v24H0z"/>  <rect x="4" y="4" width="16" height="16" rx="2" />  <path d="M4 13h3l3 3h4l3 -3h3" /></svg>
                            Rekam Medis

                            <svg class="hs-accordion-active:block ms-auto hidden size-4 text-gray-600 group-hover:text-gray-500 dark:text-neutral-400" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="m18 15-6-6-6 6" />
                            </svg>

                            <svg class="hs-accordion-active:hidden ms-auto block size-4 text-gray-600 group-hover:text-gray-500 dark:text-neutral-400" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="m6 9 6 6 6-6" />
                            </svg>
                        </button>
                        <div id="users-accordion-sub-1" class="border-[#F1F1F1] border-l-[2px] mt-2 hs-accordion-content w-full overflow-hidden transition-[height] duration-300 hidden">
                            <ul class="ps-2">
                                <li>
                                    <a href="/pasien" class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-lg hover:bg-gray-100 dark:bg-gray-800 dark:text-slate-400 dark:hover:text-slate-300 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" href="#">
                                        Daftar Rekam Medis
                                    </a>
                                </li>
                                <li>
                                    <a href="/catatanobservasiranap" class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-lg hover:bg-gray-100 dark:bg-gray-800 dark:text-slate-400 dark:hover:text-slate-300 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                                        Observasi Rawat Inap
                                    </a>
                                </li>
                                <li>
                                    <a href="/catatanobservasikebidanan" class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-lg hover:bg-gray-100 dark:bg-gray-800 dark:text-slate-400 dark:hover:text-slate-300 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                                        Observasi Rawat Inap Kebidanan
                                    </a>
                                </li>
                                <li>
                                    <a href="/catatanobservasipostpartum" class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-lg hover:bg-gray-100 dark:bg-gray-800 dark:text-slate-400 dark:hover:text-slate-300 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                                        Observasi Rawat Inap Post Partum
                                    </a>
                                </li>
                                <!-- <li>
                                    <a href="/diagnosa" class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-lg hover:bg-gray-100 dark:bg-gray-800 dark:text-slate-400 dark:hover:text-slate-300 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                                        Diagnosa
                                    </a>
                                </li> -->
                            </ul>
                        </div>
                    </li>
                <?php } ?>
                <?php if (in_array($role, $petugasrole)) { ?>
                    <li class="hs-accordion" id="users-accordion">
                        <button type="button" class="hs-accordion-toggle hs-accordion-active:bg-gray-100 w-full flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-lg hover:bg-gray-100 dark:hover:bg-teal-900 dark:text-slate-400 dark:hover:text-slate-300 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                        <svg class="h-8 w-8 text-slate-950"  width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">  <path stroke="none" d="M0 0h24v24H0z"/>  <path d="M14 8v-2a2 2 0 0 0 -2 -2h-7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2 -2v-2" />  <path d="M7 12h14l-3 -3m0 6l3 -3" /></svg>
                            Penggajian

                            <svg class="hs-accordion-active:block ms-auto hidden size-4 text-gray-600 group-hover:text-gray-500 dark:text-neutral-400" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="m18 15-6-6-6 6" />
                            </svg>

                            <svg class="hs-accordion-active:hidden ms-auto block size-4 text-gray-600 group-hover:text-gray-500 dark:text-neutral-400" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="m6 9 6 6 6-6" />
                            </svg>
                        </button>
                        <div id="users-accordion-sub-1" class="border-[#F1F1F1] border-l-[2px] mt-2 hs-accordion-content w-full overflow-hidden transition-[height] duration-300 hidden">
                            <ul class="ps-2">
                                <li>
                                    <a href="/umr" class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-lg hover:bg-gray-100 dark:bg-gray-800 dark:text-slate-400 dark:hover:text-slate-300 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                                        UMR
                                    </a>
                                </li>
                                <li>
                                    <a href="/bpjs" class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-lg hover:bg-gray-100 dark:bg-gray-800 dark:text-slate-400 dark:hover:text-slate-300 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" href="#">
                                        BPJS
                                    </a>
                                </li>
                                <li>
                                    <a href="/lembur" class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-lg hover:bg-gray-100 dark:bg-gray-800 dark:text-slate-400 dark:hover:text-slate-300 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" href="#">
                                        Lembur
                                    </a>
                                </li>
                                <li>
                                    <a href="/pph21" class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-lg hover:bg-gray-100 dark:bg-gray-800 dark:text-slate-400 dark:hover:text-slate-300 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" href="#">
                                        PPH 21
                                    </a>
                                </li>
                                <li>
                                    <a href="/ptkp" class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-lg hover:bg-gray-100 dark:bg-gray-800 dark:text-slate-400 dark:hover:text-slate-300 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" href="#">
                                        PTKP
                                    </a>
                                </li>
                                <li>
                                    <a href="/golongan" class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-lg hover:bg-gray-100 dark:bg-gray-800 dark:text-slate-400 dark:hover:text-slate-300 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" href="#">
                                        Golongan
                                    </a>
                                </li>
                                <li>
                                    <a href="/jabatan" class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-lg hover:bg-gray-100 dark:bg-gray-800 dark:text-slate-400 dark:hover:text-slate-300 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" href="#">
                                        Jabatan
                                    </a>
                                </li>
                                <li>
                                    <a href="/thr" class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-lg hover:bg-gray-100 dark:bg-gray-800 dark:text-slate-400 dark:hover:text-slate-300 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" href="#">
                                        THR
                                    </a>
                                </li>
                                <li>
                                    <a href="/pesangon" class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-lg hover:bg-gray-100 dark:bg-gray-800 dark:text-slate-400 dark:hover:text-slate-300 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" href="#">
                                        Uang Pesangon
                                    </a>
                                </li>
                                <li>
                                    <a href="/datapegawai" class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-lg hover:bg-gray-100 dark:bg-gray-800 dark:text-slate-400 dark:hover:text-slate-300 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" href="#">
                                        Data Pegawai
                                    </a>
                                </li>

                            </ul>
                        </div>
                    </li>
                <?php } ?>
                <li>
                    <button onclick="event.preventDefault(); openModal('modelLogout')" class="w-full flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-lg hover:bg-gray-100 dark:hover:bg-teal-900 dark:text-slate-400 dark:hover:text-slate-300 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="24" viewBox="0 0 25 24" fill="none">
                            <path d="M11.26 2C10.79 2 10.4 2.38 10.4 2.86V21.15C10.4 21.62 10.78 22.01 11.26 22.01C17.15 22.01 21.26 17.9 21.26 12.01C21.26 6.12 17.14 2 11.26 2Z" fill="#FEE2E2" />
                            <path d="M3.96012 11.5399L6.80012 8.68991C7.09012 8.39991 7.57012 8.39991 7.86012 8.68991C8.15012 8.97991 8.15012 9.45991 7.86012 9.74991L6.30012 11.3099H15.8701C16.2801 11.3099 16.6201 11.6499 16.6201 12.0599C16.6201 12.4699 16.2801 12.8099 15.8701 12.8099H6.30012L7.86012 14.3699C8.15012 14.6599 8.15012 15.1399 7.86012 15.4299C7.71012 15.5799 7.52012 15.6499 7.33012 15.6499C7.14012 15.6499 6.95012 15.5799 6.80012 15.4299L3.96012 12.5799C3.67012 12.2999 3.67012 11.8299 3.96012 11.5399Z" fill="#DA4141" />
                        </svg>
                        Keluar akun
                    </button>
                </li>
            </ul>

        </nav>
    </div>
    <!-- End Sidebar -->


    <!-- End Content -->
    <!-- ========== END MAIN CONTENT ========== -->
</body>