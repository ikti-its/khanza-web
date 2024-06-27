<body class="bg-gray-50 dark:bg-slate-900">
    <!-- ========== HEADER ========== -->
    <header class="sticky top-0 inset-x-0 flex flex-wrap sm:justify-start sm:flex-nowrap z-[48] w-full bg-white border-b text-sm py-2.5 sm:py-4 lg:ps-64 dark:bg-gray-800 dark:border-gray-700">
        <nav class="flex basis-full items-center w-full mx-auto px-4 sm:px-6 md:px-8" aria-label="Global">
            <div class="me-5 lg:me-0 lg:hidden">
                <a class="flex-none text-xl font-semibold dark:text-white" href="#" aria-label="Brand">
                    <img src="/img/logo-union.png" class="h-4">
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
                        <input type="text" id="icon" name="icon" class="py-2 px-4 ps-11 block w-full xl:w-96 border border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600" placeholder="Search">
                    </div>
                </div>

                <div class="flex flex-row items-center justify-end gap-2">

                    <div class="hs-dropdown relative inline-flex [--placement:bottom-right]">
                        <button id="hs-dropdown-with-header" type="button" class="w-[2.375rem] h-[2.375rem] inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-full border border-transparent text-gray-800 hover:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-gray-700 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                            <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9" />
                                <path d="M10.3 21a1.94 1.94 0 0 0 3.4 0" />
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
                                                        <svg class="flex-shrink-0 size-4 text-blue-600 mt-1" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                            <circle cx="12" cy="12" r="10"></circle>
                                                            <path d="M12 16v-4"></path>
                                                            <path d="M12 8h.01"></path>
                                                        </svg>
                                                    </div>
                                                    <div class="ms-3">
                                                        <h2 class="text-gray-800 font-semibold dark:text-white">
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
                            <img class="inline-block size-[38px] rounded-full ring-2 ring-white dark:ring-gray-800" src="<?php echo session('user_details')['foto'] ?>" alt="Image Description">
                        </button>

                        <div class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden min-w-60 bg-white shadow-md rounded-lg p-2 dark:bg-gray-800 dark:border dark:border-gray-700" aria-labelledby="hs-dropdown-with-header">
                            <div class="py-3 px-5 -m-2 bg-gray-100 rounded-t-lg dark:bg-gray-700">
                                <p class="text-sm text-gray-500 dark:text-gray-400">Signed in as</p>
                                <p class="text-sm font-medium text-gray-800 dark:text-gray-300"><?php echo session('user_details')['email'] ?></p>
                            </div>
                            <div class="mt-2 py-2 first:pt-0 last:pb-0">
                                <a class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:ring-2 focus:ring-blue-500 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-300" href="#">
                                    <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M4 14.899A7 7 0 1 1 15.71 8h1.79a4.5 4.5 0 0 1 2.5 8.242" />
                                        <path d="M12 12v9" />
                                        <path d="m8 17 4 4 4-4" />
                                    </svg>
                                    Downloads
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
    <div class="sticky top-0 inset-x-0 z-20 bg-white border-y px-4 sm:px-6 md:px-8 lg:hidden dark:bg-gray-800 dark:border-gray-700">
        <div class="flex items-center py-4">
            <!-- Navigation Toggle -->
            <button type="button" class="text-gray-500 hover:text-gray-600" data-hs-overlay="#application-sidebar" aria-controls="application-sidebar" aria-label="Toggle navigation">
                <span class="sr-only">Toggle Navigation</span>
                <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="3" x2="21" y1="6" y2="6" />
                    <line x1="3" x2="21" y1="12" y2="12" />
                    <line x1="3" x2="21" y1="18" y2="18" />
                </svg>
            </button>
            <!-- End Navigation Toggle -->

            <!-- Breadcrumb -->
            <ol class="ms-3 flex items-center whitespace-nowrap" aria-label="Breadcrumb">
                <li class="flex items-center text-sm text-gray-800 dark:text-gray-400">
                    Union
                    <svg class="flex-shrink-0 mx-3 overflow-visible size-2.5 text-gray-400 dark:text-gray-600" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M5 1L10.6869 7.16086C10.8637 7.35239 10.8637 7.64761 10.6869 7.83914L5 14" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                    </svg>
                </li>
                <li class="text-sm font-semibold text-gray-800 truncate dark:text-gray-400" aria-current="page">
                    Dashboard
                </li>
            </ol>
            <!-- End Breadcrumb -->
        </div>
    </div>
    <!-- End Sidebar Toggle -->

    <!-- Sidebar -->
    <div id="application-sidebar" class="hs-overlay hs-overlay-open:translate-x-0 -translate-x-full transition-all duration-300 transform hidden fixed top-0 start-0 bottom-0 z-[60] w-64 bg-white border-e border-gray-200 pt-7 pb-10 overflow-y-auto lg:block lg:translate-x-0 lg:end-auto lg:bottom-0 [&::-webkit-scrollbar]:w-2 [&::-webkit-scrollbar-thumb]:rounded-full [&::-webkit-scrollbar-track]:bg-gray-100 [&::-webkit-scrollbar-thumb]:bg-gray-300 dark:[&::-webkit-scrollbar-track]:bg-slate-700 dark:[&::-webkit-scrollbar-thumb]:bg-slate-500 dark:bg-gray-800 dark:border-gray-700">
        <div class="px-6">
            <a class="flex-none text-xl font-semibold dark:text-white dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" href="#">

                <img src="/img/logo-union.png" class=" h-12">

            </a>
        </div>

        <nav class="hs-accordion-group p-6 w-full flex flex-col flex-wrap" data-hs-accordion-always-open>
            <ul class="space-y-1.5">
                <a class="w-full flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-lg hover:bg-teal-200 dark:hover:bg-teal-900 dark:text-slate-400 dark:hover:text-slate-300 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" href="/dashboard">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                        <path d="M14.8251 18.9584H5.17508C2.89175 18.9584 1.04175 17.1001 1.04175 14.8167V8.64173C1.04175 7.50839 1.74175 6.08339 2.64175 5.38339L7.13342 1.88339C8.48342 0.833393 10.6417 0.783393 12.0417 1.76673L17.1917 5.37506C18.1834 6.06673 18.9584 7.55006 18.9584 8.75839V14.8251C18.9584 17.1001 17.1084 18.9584 14.8251 18.9584ZM7.90008 2.86673L3.40841 6.36673C2.81675 6.83339 2.29175 7.89173 2.29175 8.64173V14.8167C2.29175 16.4084 3.58341 17.7084 5.17508 17.7084H14.8251C16.4167 17.7084 17.7084 16.4167 17.7084 14.8251V8.75839C17.7084 7.95839 17.1334 6.85006 16.4751 6.40006L11.3251 2.79173C10.3751 2.12506 8.80841 2.15839 7.90008 2.86673Z" fill="#272727" />
                        <path d="M10 15.625C9.65833 15.625 9.375 15.3417 9.375 15V12.5C9.375 12.1583 9.65833 11.875 10 11.875C10.3417 11.875 10.625 12.1583 10.625 12.5V15C10.625 15.3417 10.3417 15.625 10 15.625Z" fill="#272727" />
                    </svg>
                    Beranda
                </a>
                </li>

                <li>
                    <a class="w-full flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-lg hover:bg-teal-200 dark:hover:bg-teal-900 dark:text-slate-400 dark:hover:text-slate-300 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" href="/profile">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                            <path d="M3.10488 12.8234L3.10488 12.8234L3.10661 12.8224C4.56789 11.9517 6.26747 11.4292 8.08886 11.3861C7.83131 12.1848 7.73369 13.0293 7.80483 13.8709C7.87103 14.654 8.08168 15.4151 8.4232 16.1166H2.50008C2.3455 16.1166 2.21675 15.9878 2.21675 15.8333V14.4583C2.21675 13.7752 2.55497 13.1484 3.10488 12.8234Z" stroke="#272727" stroke-width="1.1" />
                            <path d="M11.1167 6.66659C11.1167 8.20378 9.87053 9.44992 8.33333 9.44992C6.79614 9.44992 5.55 8.20378 5.55 6.66659C5.55 5.12939 6.79614 3.88325 8.33333 3.88325C9.87053 3.88325 11.1167 5.12939 11.1167 6.66659Z" stroke="#272727" stroke-width="1.1" />
                            <path d="M17.2917 13.3334C17.2917 13.15 17.2667 12.9834 17.2417 12.8084L17.9417 12.2C18.0917 12.0667 18.125 11.85 18.025 11.675L17.5333 10.825C17.4862 10.7414 17.411 10.677 17.321 10.6435C17.231 10.6099 17.1321 10.6093 17.0417 10.6417L16.1583 10.9417C15.8917 10.7167 15.5917 10.5417 15.2583 10.4167L15.075 9.50838C15.0553 9.41448 15.0041 9.33016 14.9298 9.2695C14.8555 9.20883 14.7626 9.1755 14.6667 9.17505H13.6833C13.4833 9.17505 13.3167 9.31672 13.275 9.50838L13.0917 10.4167C12.7583 10.5417 12.4583 10.7167 12.1917 10.9417L11.3083 10.6417C11.2178 10.6106 11.1193 10.6119 11.0297 10.6454C10.94 10.6788 10.8647 10.7423 10.8166 10.825L10.325 11.675C10.225 11.85 10.2583 12.0667 10.4083 12.2L11.1083 12.8084C11.0833 12.9834 11.0583 13.15 11.0583 13.3334C11.0583 13.5167 11.0833 13.6834 11.1083 13.8584L10.4083 14.4667C10.2583 14.6 10.225 14.8167 10.325 14.9917L10.8166 15.8417C10.9166 16.0167 11.125 16.0917 11.3083 16.025L12.1917 15.725C12.4583 15.95 12.7583 16.125 13.0917 16.25L13.275 17.1584C13.3167 17.35 13.4833 17.4917 13.6833 17.4917H14.6667C14.8667 17.4917 15.0333 17.35 15.075 17.1584L15.2583 16.25C15.5917 16.125 15.8917 15.95 16.1583 15.725L17.0417 16.025C17.2333 16.0917 17.4333 16.0084 17.5333 15.8417L18.025 14.9917C18.125 14.8167 18.0917 14.6 17.9417 14.4667L17.2417 13.8584C17.2667 13.6834 17.2917 13.5167 17.2917 13.3334ZM14.1667 15C13.25 15 12.5 14.25 12.5 13.3334C12.5 12.4167 13.25 11.6667 14.1667 11.6667C15.0833 11.6667 15.8333 12.4167 15.8333 13.3334C15.8333 14.25 15.0833 15 14.1667 15Z" fill="#272727" />
                        </svg>
                        Akun
                    </a>

                </li>


                <li class="hs-accordion" id="account-accordion">
                    <button type="button" class="hs-accordion-toggle hs-accordion-active:text-slate-700 hs-accordion-active:hover:bg-teal-200 w-full text-start flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-700 rounded-lg hover:bg-teal-200 dark:bg-neutral-800 dark:hover:bg-neutral-700 dark:text-neutral-400 dark:hover:text-neutral-300 dark:hs-accordion-active:text-white">
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
                                <button type="button" class="hs-accordion-toggle hs-accordion-active:text-slate-700 hs-accordion-active:hover:bg-teal-200 w-full text-start flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-700 rounded-lg hover:bg-teal-200 dark:bg-neutral-800 dark:hover:bg-neutral-700 dark:text-neutral-400 dark:hover:text-neutral-300 dark:hs-accordion-active:text-white">
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
                                        <?php if (session('user_specific_data')['status'] === false) : ?>
                                            <li>
                                                <a class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-700 rounded-lg hover:bg-teal-200 dark:bg-neutral-800 dark:text-neutral-400 dark:hover:text-neutral-300" href="/tesmenukehadiran">
                                                    Masuk
                                                </a>
                                            </li>
                                            <li>
                                                <div class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-700 rounded-lg" href="/swafoto">
                                                    Pulang
                                                </div>
                                            </li>

                                        <?php else : ?>
                                            <li>
                                                <div class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-700 rounded-lg" href="/tesmenukehadiran">
                                                    Masuk
                                                </div>
                                            </li>

                                            <li>
                                                <a class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-700 rounded-lg hover:bg-teal-200 dark:bg-neutral-800 dark:text-neutral-400 dark:hover:text-neutral-300" href="/absenpulang/<?php echo session('user_specific_data')['pegawai'] ?>">
                                                    Pulang
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                            </li>

                            <li class="hs-accordion" id="account-accordion">
                                <button type="button" class="hs-accordion-toggle hs-accordion-active:text-slate-700 hs-accordion-active:hover:bg-teal-200 w-full text-start flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-700 rounded-lg hover:bg-teal-200 dark:bg-neutral-800 dark:hover:bg-neutral-700 dark:text-neutral-400 dark:hover:text-neutral-300 dark:hs-accordion-active:text-white">
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
                                            <a class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-700 rounded-lg hover:bg-teal-200 dark:bg-neutral-800 dark:text-neutral-400 dark:hover:text-neutral-300" href="/izincuti">
                                                Izin Cuti
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>

                            <li class="hs-accordion" id="account-accordion">
                                <button type="button" class="hs-accordion-toggle hs-accordion-active:text-slate-700 hs-accordion-active:hover:bg-teal-200 w-full text-start flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-700 rounded-lg hover:bg-teal-200 dark:bg-neutral-800 dark:hover:bg-neutral-700 dark:text-neutral-400 dark:hover:text-neutral-300 dark:hs-accordion-active:text-white">
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
                                        <li>
                                            <a class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-700 rounded-lg hover:bg-teal-200 dark:bg-neutral-800 dark:text-neutral-400 dark:hover:text-neutral-300" href="/catatankehadiran/<?php echo session('user_specific_data')['pegawai'] ?>">
                                                Catatan Kehadiran
                                            </a>
                                        </li>
                                        <li>
                                            <a class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-700 rounded-lg hover:bg-teal-200 dark:bg-neutral-800 dark:text-neutral-400 dark:hover:text-neutral-300" href="/lihatjadwal/<?php echo session('user_specific_data')['pegawai'] ?>">
                                                Jadwal Kerja
                                            </a>
                                        </li>
                                        <li>
                                            <a class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-700 rounded-lg hover:bg-teal-200 dark:bg-neutral-800 dark:text-neutral-400 dark:hover:text-neutral-300" href="/lihatizincuti/<?php echo session('user_specific_data')['pegawai'] ?>">
                                                Daftar Pengajuan Cuti
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>

                            <li class="hs-accordion" id="account-accordion">
                                <button type="button" class="hs-accordion-toggle hs-accordion-active:text-slate-700 hs-accordion-active:hover:bg-teal-200 w-full text-start flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-700 rounded-lg hover:bg-teal-200 dark:bg-neutral-800 dark:hover:bg-neutral-700 dark:text-neutral-400 dark:hover:text-neutral-300 dark:hs-accordion-active:text-white">
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
                                            <a class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-700 rounded-lg hover:bg-teal-200 dark:bg-neutral-800 dark:text-neutral-400 dark:hover:text-neutral-300" href="/lihatstatuscuti">
                                                Status Pengajuan Cuti
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>

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
                            <svg class="flex-shrink-0 size-4" fill="#000000" viewBox="0 0 32 32" version="1.1" xmlns="http://www.w3.org/2000/svg">
                                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                <g id="SVGRepo_iconCarrier">
                                    <title>suitcase1</title>
                                    <path d="M27 29h-23c-1.105 0-2-0.896-2-2v-12c0 0 5.221 2.685 10 3.784v1.216c0 0.553 0.447 1 1 1h5c0.552 0 1-0.447 1-1v-1.216c4.778-1.099 10-3.784 10-3.784v12c0 1.104-0.896 2-2 2zM17 17c0.552 0 1 0.447 1 1v1c0 0.553-0.448 1-1 1h-3c-0.553 0-1-0.447-1-1v-1c0-0.553 0.447-1 1-1h3zM19 17c0-0.553-0.448-1-1-1h-5c-0.553 0-1 0.447-1 1v0.896c-4.779-1.132-10-3.896-10-3.896v-4c0-1.104 0.895-2 2-2h6v-2c0-1.104 0.896-2 2-2h7c1.104 0 2 0.896 2 2v2h6c1.104 0 2 0.896 2 2v4c0 0-5.222 2.764-10 3.896v-0.896zM19 7c0-0.553-0.448-1-1-1h-5c-0.553 0-1 0.447-1 1 0 0.552 0 1 0 1h7c0 0 0-0.448 0-1z"></path>
                                </g>
                            </svg>
                            Pegawai
                        </a>
                    <?php else : ?>
                        <a class="w-full flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-lg hover:bg-teal-200 dark:hover:bg-teal-900 dark:text-slate-400 dark:hover:text-slate-300 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" href="/datauserpegawai">
                            <svg class="flex-shrink-0 size-4" fill="#000000" viewBox="0 0 32 32" version="1.1" xmlns="http://www.w3.org/2000/svg">
                                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                <g id="SVGRepo_iconCarrier">
                                    <title>suitcase1</title>
                                    <path d="M27 29h-23c-1.105 0-2-0.896-2-2v-12c0 0 5.221 2.685 10 3.784v1.216c0 0.553 0.447 1 1 1h5c0.552 0 1-0.447 1-1v-1.216c4.778-1.099 10-3.784 10-3.784v12c0 1.104-0.896 2-2 2zM17 17c0.552 0 1 0.447 1 1v1c0 0.553-0.448 1-1 1h-3c-0.553 0-1-0.447-1-1v-1c0-0.553 0.447-1 1-1h3zM19 17c0-0.553-0.448-1-1-1h-5c-0.553 0-1 0.447-1 1v0.896c-4.779-1.132-10-3.896-10-3.896v-4c0-1.104 0.895-2 2-2h6v-2c0-1.104 0.896-2 2-2h7c1.104 0 2 0.896 2 2v2h6c1.104 0 2 0.896 2 2v4c0 0-5.222 2.764-10 3.896v-0.896zM19 7c0-0.553-0.448-1-1-1h-5c-0.553 0-1 0.447-1 1 0 0.552 0 1 0 1h7c0 0 0-0.448 0-1z"></path>
                                </g>
                            </svg>
                            Pegawai
                        </a>
                    <?php endif; ?>



                </li>

                <li class="hs-accordion" id="account-accordion">
                    <button type="button" class="hs-accordion-toggle hs-accordion-active:text-slate-700 hs-accordion-active:hover:bg-teal-200 w-full text-start flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-700 rounded-lg hover:bg-teal-200 dark:bg-neutral-800 dark:hover:bg-neutral-700 dark:text-neutral-400 dark:hover:text-neutral-300 dark:hs-accordion-active:text-white">
                        <svg class="flex-shrink-0 size-4" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                            <g id="SVGRepo_iconCarrier">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M0 4.6A2.6 2.6 0 0 1 2.6 2h18.8A2.6 2.6 0 0 1 24 4.6v.8A2.6 2.6 0 0 1 21.4 8H21v10.6c0 1.33-1.07 2.4-2.4 2.4H5.4C4.07 21 3 19.93 3 18.6V8h-.4A2.6 2.6 0 0 1 0 5.4v-.8ZM2.6 4a.6.6 0 0 0-.6.6v.8a.6.6 0 0 0 .6.6h18.8a.6.6 0 0 0 .6-.6v-.8a.6.6 0 0 0-.6-.6H2.6ZM8 10a1 1 0 1 0 0 2h8a1 1 0 1 0 0-2H8Z" fill="#000000"></path>
                            </g>
                        </svg>

                        Inventaris

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
                                <a class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-700 rounded-lg hover:bg-teal-200 dark:bg-neutral-800 dark:text-neutral-400 dark:hover:text-neutral-300" href="#">
                                    <svg class="flex-shrink-0 size-4" fill="#000000" viewBox="0 0 256 256" id="Flat" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M144.48633,136.48438l-41.98926,42a12.0001,12.0001,0,0,1-16.97266-16.96875L107.03467,140H24a12,12,0,0,1,0-24h83.03467L85.52441,94.48438a12.0001,12.0001,0,0,1,16.97266-16.96875l41.98926,42A12.00093,12.00093,0,0,1,144.48633,136.48438ZM192,28H136a12,12,0,0,0,0,24h52V204H136a12,12,0,0,0,0,24h56a20.02229,20.02229,0,0,0,20-20V48A20.02229,20.02229,0,0,0,192,28Z" />
                                    </svg>
                                    Presensi
                                </a>
                            </li>
                            <li>
                                <a class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-700 rounded-lg hover:bg-teal-200 dark:bg-neutral-800 dark:text-neutral-400 dark:hover:text-neutral-300" href="#">
                                    Link 2
                                </a>
                            </li>
                            <li>
                                <a class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-700 rounded-lg hover:bg-teal-200 dark:bg-neutral-800 dark:text-neutral-400 dark:hover:text-neutral-300" href="#">
                                    Link 3
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li><a href="<?= base_url('/logout') ?>" class="w-full flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-lg hover:bg-teal-200 dark:hover:bg-teal-900 dark:text-slate-400 dark:hover:text-slate-300 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                        <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z" />
                            <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z" />
                        </svg>
                        Logout
                    </a>
                </li>
            </ul>
        </nav>
    </div>
    <!-- End Sidebar -->


    <!-- End Content -->
    <!-- ========== END MAIN CONTENT ========== -->
</body>