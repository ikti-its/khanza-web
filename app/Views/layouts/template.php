<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= base_url() ?>css/style.css?v=1.0">
    <!-- Flatpickr CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <!-- Include TensorFlow.js library -->
    <script src="https://cdn.jsdelivr.net/npm/@tensorflow/tfjs"></script>

    <title><?= $title ?? "SIMKES Khanza" ?></title>
</head>

<body>
    <?= $this->include('components/header') ?>
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

                <?php if (isset($breadcrumbs) && is_array($breadcrumbs)) : ?>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb" style="display: flex; flex-wrap: nowrap; list-style: none; padding: 0; margin: 0;">
                            <?php foreach ($breadcrumbs as $index => $breadcrumb) : ?>
                                <?php $isLast = ($index === count($breadcrumbs) - 1); ?>
                                <li class="breadcrumb-item" style="display: flex; align-items: center; <?= $isLast ? 'color: #6c757d; cursor: default;' : '' ?>">
                                    <?php if (!empty($breadcrumb['icon'])) : ?>
                                        <?php if ($breadcrumb['icon'] == 'medis') : ?>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none" style="margin-right: 8px;">
                                                <g clip-path="url(#clip0_1297_19722)">
                                                    <path d="M10.6666 3.99967C10.6666 2.74234 10.6666 2.11434 10.2759 1.72367C9.88525 1.33301 9.25725 1.33301 7.99992 1.33301C6.74258 1.33301 6.11459 1.33301 5.72392 1.72367C5.33325 2.11434 5.33325 2.74234 5.33325 3.99967M1.33325 9.33301C1.33325 6.81901 1.33325 5.56167 2.11459 4.78101C2.89525 3.99967 4.15259 3.99967 6.66658 3.99967H9.33325C11.8473 3.99967 13.1046 3.99967 13.8853 4.78101C14.6666 5.56167 14.6666 6.81901 14.6666 9.33301C14.6666 11.847 14.6666 13.1043 13.8853 13.885C13.1046 14.6663 11.8473 14.6663 9.33325 14.6663H6.66658C4.15259 14.6663 2.89525 14.6663 2.11459 13.885C1.33325 13.1043 1.33325 11.847 1.33325 9.33301Z" stroke="#666666" />
                                                    <path d="M9 9.33301H7M8 8.33301V10.333" stroke="#666666" stroke-linecap="round" />
                                                    <path d="M7.99992 11.9993C9.47268 11.9993 10.6666 10.8054 10.6666 9.33268C10.6666 7.85992 9.47268 6.66602 7.99992 6.66602C6.52716 6.66602 5.33325 7.85992 5.33325 9.33268C5.33325 10.8054 6.52716 11.9993 7.99992 11.9993Z" stroke="#666666" />
                                                </g>
                                                <defs>
                                                    <clipPath id="clip0_1297_19722">
                                                        <rect width="16" height="16" fill="white" />
                                                    </clipPath>
                                                </defs>
                                            </svg>
                                        <?php elseif ($breadcrumb['icon'] == 'inventarismedis') : ?>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none" style="margin-right: 8px;">
                                                <path d="M13.3333 1.33301H2.66659C1.99992 1.33301 1.33325 1.93301 1.33325 2.66634V4.67301C1.33325 5.15301 1.61992 5.56634 1.99992 5.79967V13.333C1.99992 14.0663 2.73325 14.6663 3.33325 14.6663H12.6666C13.2666 14.6663 13.9999 14.0663 13.9999 13.333V5.79967C14.3799 5.56634 14.6666 5.15301 14.6666 4.67301V2.66634C14.6666 1.93301 13.9999 1.33301 13.3333 1.33301ZM12.6666 13.333H3.33325V5.99967H12.6666V13.333ZM13.3333 4.66634H2.66659V2.66634L13.3333 2.65301V4.66634Z" fill="#666666" />
                                                <path d="M6 8H10V9.33333H6V8Z" fill="#666666" />
                                            </svg>
                                        <?php elseif ($breadcrumb['icon'] == 'pengadaanmedis') : ?>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none" style="margin-right: 8px;">
                                                <g clip-path="url(#clip0_2239_43117)">
                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M15.1667 4.66652C15.1664 4.08876 15.0355 3.51852 14.7839 2.99845C14.5322 2.47839 14.1662 2.02194 13.7133 1.66323C13.2603 1.30452 12.7322 1.05284 12.1683 0.926997C11.6044 0.801152 11.0193 0.804402 10.4569 0.936503C9.89441 1.0686 9.36909 1.32614 8.92017 1.68985C8.47126 2.05357 8.11037 2.51406 7.86448 3.03689C7.61859 3.55972 7.49407 4.13137 7.50022 4.70911C7.50637 5.28684 7.64304 5.85571 7.9 6.37319C7.90433 6.38154 7.90726 6.39055 7.90867 6.39986V6.40452L7.71067 7.14652C7.66839 7.3047 7.66847 7.47122 7.7109 7.62936C7.75333 7.7875 7.83661 7.93169 7.95239 8.04747C8.06816 8.16324 8.21236 8.24653 8.3705 8.28895C8.52864 8.33138 8.69515 8.33146 8.85333 8.28919L9.59533 8.09052C9.594 8.09052 9.59467 8.09052 9.59533 8.09052H9.6C9.60935 8.0921 9.61837 8.09526 9.62667 8.09985C10.2112 8.39048 10.8602 8.52703 11.5123 8.49656C12.1643 8.46609 12.7978 8.26961 13.3526 7.92575C13.9075 7.58189 14.3654 7.10203 14.6828 6.53166C15.0003 5.96128 15.1668 5.31929 15.1667 4.66652ZM11.3333 1.83319C11.7603 1.83347 12.1817 1.93025 12.5661 2.1163C12.9504 2.30235 13.2877 2.57286 13.5528 2.90762C13.8178 3.24237 14.0038 3.6327 14.0968 4.04945C14.1898 4.46619 14.1874 4.89856 14.0898 5.31424C13.9922 5.72993 13.8019 6.11818 13.5331 6.44997C13.2643 6.78176 12.9241 7.04851 12.5377 7.23029C12.1513 7.41206 11.7289 7.50416 11.3019 7.4997C10.8749 7.49524 10.4545 7.39433 10.072 7.20452C9.84504 7.08923 9.58379 7.06079 9.33733 7.12452L8.70667 7.29319L8.87533 6.66252C8.93913 6.41607 8.91068 6.1548 8.79533 5.92786C8.58054 5.49581 8.47963 5.01607 8.50218 4.53411C8.52473 4.05214 8.66999 3.58392 8.92418 3.17382C9.17837 2.76372 9.53309 2.42532 9.95469 2.19071C10.3763 1.95609 10.8508 1.83303 11.3333 1.83319Z" fill="#666666" />
                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M11.3333 2.83301C11.4659 2.83301 11.5931 2.88569 11.6869 2.97945C11.7806 3.07322 11.8333 3.2004 11.8333 3.33301V4.16634H12.6666C12.7992 4.16634 12.9264 4.21902 13.0202 4.31279C13.114 4.40656 13.1666 4.53373 13.1666 4.66634C13.1666 4.79895 13.114 4.92613 13.0202 5.01989C12.9264 5.11366 12.7992 5.16634 12.6666 5.16634H11.8333V5.99967C11.8333 6.13228 11.7806 6.25946 11.6869 6.35323C11.5931 6.447 11.4659 6.49967 11.3333 6.49967C11.2007 6.49967 11.0735 6.447 10.9797 6.35323C10.886 6.25946 10.8333 6.13228 10.8333 5.99967V5.16634H9.99997C9.86736 5.16634 9.74018 5.11366 9.64642 5.01989C9.55265 4.92613 9.49997 4.79895 9.49997 4.66634C9.49997 4.53373 9.55265 4.40656 9.64642 4.31279C9.74018 4.21902 9.86736 4.16634 9.99997 4.16634H10.8333V3.33301C10.8333 3.2004 10.886 3.07322 10.9797 2.97945C11.0735 2.88569 11.2007 2.83301 11.3333 2.83301ZM2.00464 4.27101C3.12464 3.15101 5.0153 3.23634 5.79464 4.63367L6.22797 5.40901C6.73664 6.32101 6.51997 7.47234 5.77464 8.22701C5.73061 8.29377 5.70655 8.37171 5.7053 8.45167C5.69664 8.62234 5.7573 9.01767 6.36997 9.62967C6.98197 10.2417 7.37664 10.303 7.54797 10.2943C7.62793 10.2931 7.70588 10.269 7.77264 10.225C8.52664 9.47967 9.67864 9.26301 10.5906 9.77167L11.366 10.205C12.7633 10.985 12.8486 12.875 11.7286 13.995C11.1293 14.5937 10.3326 15.1263 9.39664 15.1617C8.00997 15.2143 5.70597 14.8557 3.42464 12.575C1.14397 10.2937 0.785303 7.99034 0.83797 6.60301C0.873303 5.66634 1.40597 4.86967 2.00464 4.27101ZM4.92197 5.12101C4.52197 4.40501 3.44864 4.24101 2.71197 4.97834C2.1953 5.49501 1.85864 6.06501 1.8373 6.64034C1.7933 7.79767 2.0793 9.81501 4.13197 11.8677C6.1853 13.921 8.20197 14.2063 9.35864 14.1623C9.93397 14.1403 10.5053 13.8043 11.0213 13.2877C11.7586 12.5503 11.5946 11.477 10.8786 11.0777L10.1033 10.645C9.6213 10.3763 8.9433 10.4677 8.46864 10.9437C8.42197 10.9903 8.12397 11.2677 7.59664 11.293C7.05664 11.3197 6.40264 11.077 5.6633 10.337C4.92264 9.59701 4.67997 8.94301 4.70664 8.40234C4.73197 7.87501 5.00997 7.57767 5.05597 7.53167C5.53197 7.05567 5.6233 6.37834 5.35464 5.89634L4.92197 5.12101Z" fill="#666666" />
                                                </g>
                                                <defs>
                                                    <clipPath id="clip0_2239_43117">
                                                        <rect width="16" height="16" fill="white" />
                                                    </clipPath>
                                                </defs>
                                            </svg>
                                        <?php elseif ($breadcrumb['icon'] == 'dashboardpengadaan') : ?>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none" style="margin-right: 8px;">
                                                <path d="M12.6667 2H3.33333C2.6 2 2 2.6 2 3.33333V12.6667C2 13.4 2.6 14 3.33333 14H12.6667C13.4 14 14 13.4 14 12.6667V3.33333C14 2.6 13.4 2 12.6667 2ZM3.33333 12.6667V3.33333H7.33333V12.6667H3.33333ZM12.6667 12.6667H8.66667V8H12.6667V12.6667ZM12.6667 6.66667H8.66667V3.33333H12.6667V6.66667Z" fill="#272727" />
                                            </svg>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                    <?php if (!empty($breadcrumb['url']) && !$isLast) : ?>
                                        <a href="<?= $breadcrumb['url'] ?>"><?= $breadcrumb['title'] ?></a>
                                    <?php else : ?>
                                        <?= $breadcrumb['title'] ?>
                                    <?php endif; ?>
                                </li>
                                <?php if (!$isLast) : ?>
                                    <li class="breadcrumb-separator" style="display: inline; padding: 0 10px;">/</li>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </ol>
                    </nav>
                <?php endif; ?>



            </ol>
            <!-- End Breadcrumb -->
        </div>
    </div>
    <div class="container mx-auto">
        <!-- <div class="w-full md:w-[100%] mx-auto h-full lg:pl-72 pl-auto z-[1] overflow-clip"> -->
        <div class="w-full h-full lg:pl-64 z-[1] overflow-clip">
            <div class="px-10 py-4 hidden lg:block">
                <?php if (isset($breadcrumbs) && is_array($breadcrumbs)) : ?>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb" style="display: flex; flex-wrap: nowrap; list-style: none; padding: 0; margin: 0;">
                            <?php foreach ($breadcrumbs as $index => $breadcrumb) : ?>
                                <?php $isLast = ($index === count($breadcrumbs) - 1); ?>
                                <li class="breadcrumb-item" style="display: flex; align-items: center; <?= $isLast ? 'color: #6c757d; cursor: default;' : '' ?>">
                                    <?php if (!empty($breadcrumb['icon'])) : ?>
                                        <?php if ($breadcrumb['icon'] == 'medis') : ?>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none" style="margin-right: 8px;">
                                                <g clip-path="url(#clip0_1297_19722)">
                                                    <path d="M10.6666 3.99967C10.6666 2.74234 10.6666 2.11434 10.2759 1.72367C9.88525 1.33301 9.25725 1.33301 7.99992 1.33301C6.74258 1.33301 6.11459 1.33301 5.72392 1.72367C5.33325 2.11434 5.33325 2.74234 5.33325 3.99967M1.33325 9.33301C1.33325 6.81901 1.33325 5.56167 2.11459 4.78101C2.89525 3.99967 4.15259 3.99967 6.66658 3.99967H9.33325C11.8473 3.99967 13.1046 3.99967 13.8853 4.78101C14.6666 5.56167 14.6666 6.81901 14.6666 9.33301C14.6666 11.847 14.6666 13.1043 13.8853 13.885C13.1046 14.6663 11.8473 14.6663 9.33325 14.6663H6.66658C4.15259 14.6663 2.89525 14.6663 2.11459 13.885C1.33325 13.1043 1.33325 11.847 1.33325 9.33301Z" stroke="#666666" />
                                                    <path d="M9 9.33301H7M8 8.33301V10.333" stroke="#666666" stroke-linecap="round" />
                                                    <path d="M7.99992 11.9993C9.47268 11.9993 10.6666 10.8054 10.6666 9.33268C10.6666 7.85992 9.47268 6.66602 7.99992 6.66602C6.52716 6.66602 5.33325 7.85992 5.33325 9.33268C5.33325 10.8054 6.52716 11.9993 7.99992 11.9993Z" stroke="#666666" />
                                                </g>
                                                <defs>
                                                    <clipPath id="clip0_1297_19722">
                                                        <rect width="16" height="16" fill="white" />
                                                    </clipPath>
                                                </defs>
                                            </svg>
                                        <?php elseif ($breadcrumb['icon'] == 'inventarismedis') : ?>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none" style="margin-right: 8px;">
                                                <path d="M13.3333 1.33301H2.66659C1.99992 1.33301 1.33325 1.93301 1.33325 2.66634V4.67301C1.33325 5.15301 1.61992 5.56634 1.99992 5.79967V13.333C1.99992 14.0663 2.73325 14.6663 3.33325 14.6663H12.6666C13.2666 14.6663 13.9999 14.0663 13.9999 13.333V5.79967C14.3799 5.56634 14.6666 5.15301 14.6666 4.67301V2.66634C14.6666 1.93301 13.9999 1.33301 13.3333 1.33301ZM12.6666 13.333H3.33325V5.99967H12.6666V13.333ZM13.3333 4.66634H2.66659V2.66634L13.3333 2.65301V4.66634Z" fill="#666666" />
                                                <path d="M6 8H10V9.33333H6V8Z" fill="#666666" />
                                            </svg>
                                        <?php elseif ($breadcrumb['icon'] == 'pengadaanmedis') : ?>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none" style="margin-right: 8px;">
                                                <g clip-path="url(#clip0_2239_43117)">
                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M15.1667 4.66652C15.1664 4.08876 15.0355 3.51852 14.7839 2.99845C14.5322 2.47839 14.1662 2.02194 13.7133 1.66323C13.2603 1.30452 12.7322 1.05284 12.1683 0.926997C11.6044 0.801152 11.0193 0.804402 10.4569 0.936503C9.89441 1.0686 9.36909 1.32614 8.92017 1.68985C8.47126 2.05357 8.11037 2.51406 7.86448 3.03689C7.61859 3.55972 7.49407 4.13137 7.50022 4.70911C7.50637 5.28684 7.64304 5.85571 7.9 6.37319C7.90433 6.38154 7.90726 6.39055 7.90867 6.39986V6.40452L7.71067 7.14652C7.66839 7.3047 7.66847 7.47122 7.7109 7.62936C7.75333 7.7875 7.83661 7.93169 7.95239 8.04747C8.06816 8.16324 8.21236 8.24653 8.3705 8.28895C8.52864 8.33138 8.69515 8.33146 8.85333 8.28919L9.59533 8.09052C9.594 8.09052 9.59467 8.09052 9.59533 8.09052H9.6C9.60935 8.0921 9.61837 8.09526 9.62667 8.09985C10.2112 8.39048 10.8602 8.52703 11.5123 8.49656C12.1643 8.46609 12.7978 8.26961 13.3526 7.92575C13.9075 7.58189 14.3654 7.10203 14.6828 6.53166C15.0003 5.96128 15.1668 5.31929 15.1667 4.66652ZM11.3333 1.83319C11.7603 1.83347 12.1817 1.93025 12.5661 2.1163C12.9504 2.30235 13.2877 2.57286 13.5528 2.90762C13.8178 3.24237 14.0038 3.6327 14.0968 4.04945C14.1898 4.46619 14.1874 4.89856 14.0898 5.31424C13.9922 5.72993 13.8019 6.11818 13.5331 6.44997C13.2643 6.78176 12.9241 7.04851 12.5377 7.23029C12.1513 7.41206 11.7289 7.50416 11.3019 7.4997C10.8749 7.49524 10.4545 7.39433 10.072 7.20452C9.84504 7.08923 9.58379 7.06079 9.33733 7.12452L8.70667 7.29319L8.87533 6.66252C8.93913 6.41607 8.91068 6.1548 8.79533 5.92786C8.58054 5.49581 8.47963 5.01607 8.50218 4.53411C8.52473 4.05214 8.66999 3.58392 8.92418 3.17382C9.17837 2.76372 9.53309 2.42532 9.95469 2.19071C10.3763 1.95609 10.8508 1.83303 11.3333 1.83319Z" fill="#666666" />
                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M11.3333 2.83301C11.4659 2.83301 11.5931 2.88569 11.6869 2.97945C11.7806 3.07322 11.8333 3.2004 11.8333 3.33301V4.16634H12.6666C12.7992 4.16634 12.9264 4.21902 13.0202 4.31279C13.114 4.40656 13.1666 4.53373 13.1666 4.66634C13.1666 4.79895 13.114 4.92613 13.0202 5.01989C12.9264 5.11366 12.7992 5.16634 12.6666 5.16634H11.8333V5.99967C11.8333 6.13228 11.7806 6.25946 11.6869 6.35323C11.5931 6.447 11.4659 6.49967 11.3333 6.49967C11.2007 6.49967 11.0735 6.447 10.9797 6.35323C10.886 6.25946 10.8333 6.13228 10.8333 5.99967V5.16634H9.99997C9.86736 5.16634 9.74018 5.11366 9.64642 5.01989C9.55265 4.92613 9.49997 4.79895 9.49997 4.66634C9.49997 4.53373 9.55265 4.40656 9.64642 4.31279C9.74018 4.21902 9.86736 4.16634 9.99997 4.16634H10.8333V3.33301C10.8333 3.2004 10.886 3.07322 10.9797 2.97945C11.0735 2.88569 11.2007 2.83301 11.3333 2.83301ZM2.00464 4.27101C3.12464 3.15101 5.0153 3.23634 5.79464 4.63367L6.22797 5.40901C6.73664 6.32101 6.51997 7.47234 5.77464 8.22701C5.73061 8.29377 5.70655 8.37171 5.7053 8.45167C5.69664 8.62234 5.7573 9.01767 6.36997 9.62967C6.98197 10.2417 7.37664 10.303 7.54797 10.2943C7.62793 10.2931 7.70588 10.269 7.77264 10.225C8.52664 9.47967 9.67864 9.26301 10.5906 9.77167L11.366 10.205C12.7633 10.985 12.8486 12.875 11.7286 13.995C11.1293 14.5937 10.3326 15.1263 9.39664 15.1617C8.00997 15.2143 5.70597 14.8557 3.42464 12.575C1.14397 10.2937 0.785303 7.99034 0.83797 6.60301C0.873303 5.66634 1.40597 4.86967 2.00464 4.27101ZM4.92197 5.12101C4.52197 4.40501 3.44864 4.24101 2.71197 4.97834C2.1953 5.49501 1.85864 6.06501 1.8373 6.64034C1.7933 7.79767 2.0793 9.81501 4.13197 11.8677C6.1853 13.921 8.20197 14.2063 9.35864 14.1623C9.93397 14.1403 10.5053 13.8043 11.0213 13.2877C11.7586 12.5503 11.5946 11.477 10.8786 11.0777L10.1033 10.645C9.6213 10.3763 8.9433 10.4677 8.46864 10.9437C8.42197 10.9903 8.12397 11.2677 7.59664 11.293C7.05664 11.3197 6.40264 11.077 5.6633 10.337C4.92264 9.59701 4.67997 8.94301 4.70664 8.40234C4.73197 7.87501 5.00997 7.57767 5.05597 7.53167C5.53197 7.05567 5.6233 6.37834 5.35464 5.89634L4.92197 5.12101Z" fill="#666666" />
                                                </g>
                                                <defs>
                                                    <clipPath id="clip0_2239_43117">
                                                        <rect width="16" height="16" fill="white" />
                                                    </clipPath>
                                                </defs>
                                            </svg>
                                        <?php elseif ($breadcrumb['icon'] == 'dashboardpengadaan') : ?>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none" style="margin-right: 8px;">
                                                <path d="M12.6667 2H3.33333C2.6 2 2 2.6 2 3.33333V12.6667C2 13.4 2.6 14 3.33333 14H12.6667C13.4 14 14 13.4 14 12.6667V3.33333C14 2.6 13.4 2 12.6667 2ZM3.33333 12.6667V3.33333H7.33333V12.6667H3.33333ZM12.6667 12.6667H8.66667V8H12.6667V12.6667ZM12.6667 6.66667H8.66667V3.33333H12.6667V6.66667Z" fill="#272727" />
                                            </svg>

                                        <?php endif; ?>
                                    <?php endif; ?>
                                    <?php if (!empty($breadcrumb['url']) && !$isLast) : ?>
                                        <a href="<?= $breadcrumb['url'] ?>"><?= $breadcrumb['title'] ?></a>
                                    <?php else : ?>
                                        <?= $breadcrumb['title'] ?>
                                    <?php endif; ?>
                                </li>
                                <?php if (!$isLast) : ?>
                                    <li class="breadcrumb-separator" style="display: inline; padding: 0 10px;">/</li>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </ol>
                    </nav>
                <?php endif; ?>
            </div>
            <!-- Content -->
            <div id="modelLogout" class=" fixed hidden z-[100] inset-0 bg-gray-900 bg-opacity-60 overflow-y-auto h-full w-full px-4 ">
                <div class=" relative top-40 mx-auto shadow-xl rounded-md bg-white max-w-md">

                    <div class="flex justify-end p-2">
                        <button onclick="closeModal('modelLogout')" type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    </div>

                    <div class="p-6 pt-0 text-center">
                        <div class="flex justify-center mb-6">
                            <!-- Container for SVG, centered -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="43" height="42" viewBox="0 0 43 42" fill="none">
                                <path d="M19.3301 3.5C18.5076 3.5 17.8251 4.165 17.8251 5.005V37.0125C17.8251 37.835 18.4901 38.5175 19.3301 38.5175C29.6376 38.5175 36.8301 31.325 36.8301 21.0175C36.8301 10.71 29.6201 3.5 19.3301 3.5Z" fill="#FEE2E2" />
                                <path d="M6.55496 20.1943L11.525 15.2068C12.0325 14.6993 12.8725 14.6993 13.38 15.2068C13.8875 15.7143 13.8875 16.5543 13.38 17.0618L10.65 19.7918H27.3975C28.115 19.7918 28.71 20.3868 28.71 21.1043C28.71 21.8218 28.115 22.4168 27.3975 22.4168H10.65L13.38 25.1468C13.8875 25.6543 13.8875 26.4943 13.38 27.0018C13.1175 27.2643 12.785 27.3868 12.4525 27.3868C12.12 27.3868 11.7875 27.2643 11.525 27.0018L6.55496 22.0143C6.04746 21.5243 6.04746 20.7018 6.55496 20.1943Z" fill="#DA4141" />
                            </svg>
                        </div>
                        <h3 class="font-semibold">Konfirmasi keluar akun</h3>
                        <p class="text-wrap font-normal text-gray-500 mt-5 mb-6">Apakah Anda yakin ingin keluar dari akun Anda?</p>

                        <form action="<?= base_url('/logout') ?>" method="POST">
                            <div class="w-full sm:flex justify-center">

                                <a href="#" onclick="closeModal('modelLogout')" class="w-full text-gray-900 bg-white hover:bg-gray-100 focus:ring-4 border border-gray-200 font-medium inline-flex items-center justify-center rounded-lg text-base px-3 py-2.5 text-center mr-2" data-modal-toggle="delete-user-modal">
                                    Batal
                                </a>
                                <button onclick="closeModal('modelLogout')" class="w-full text-[#ACF2E7] bg-[#0A2D27] hover:bg-[#13594E] focus:ring-4 font-medium rounded-lg text-base inline-flex items-center justify-center px-3 py-2.5 text-center ">
                                    Keluar akun
                                </button>
                            </div>
                        </form>

                    </div>

                </div>
            </div>
            <?= $this->renderSection('content') ?>

            <!-- End Content -->
        </div>

        <!-- Flatpickr JS -->
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
        <script src="<?= base_url('/css/preline/preline.js') ?>"></script>
    </div>

    <script>
        window.openModal = function(modalId) {
            document.getElementById(modalId).style.display = 'block'
            document.getElementsByTagName('body')[0].classList.add('overflow-y-hidden')
        }

        window.closeModal = function(modalId) {
            document.getElementById(modalId).style.display = 'none'
            document.getElementsByTagName('body')[0].classList.remove('overflow-y-hidden')
        }
    </script>
</body>

</html>