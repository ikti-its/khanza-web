<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= base_url() ?>css/style.css?v=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <title><?= $title ?? "Omnia" ?></title>
    <link rel="icon" type="image/png" href="<?= base_url('img/omnia.jpeg') ?>">
</head>

<body>
    <!-- Modal helper: dimuat sekali di sini (paling awal di body), bukan per-
         komponen modal, supaya halaman dengan >1 modal picker (mis.
         modalPengajuan + modalSuplier) tidak me-redeclare `const modalConfigs`
         dua kali. Harus dimuat SEBELUM section 'content' supaya autofillFields
         generik di sini kalah timpa oleh override khusus tiap halaman -->
    <script src="<?= base_url('js/modal-helper.js') ?>?v=<?= filemtime(FCPATH . 'js/modal-helper.js') ?>"></script>

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
            <?= $this->renderSection('content') ?>
            <!-- End Content -->
        </div>

        <!-- Flatpickr JS -->
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
        <script src="<?= base_url('/css/preline/preline.js') ?>"></script>
    </div>

    <!-- Script SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        window.openModal = function(modalId) {
            document.getElementById(modalId).style.display = 'block'
            document.getElementsByTagName('body')[0].classList.add('overflow-y-hidden')
        }

        window.closeModal = function(modalId) {
            document.getElementById(modalId).style.display = 'none'
            document.getElementsByTagName('body')[0].classList.remove('overflow-y-hidden')
        }

        document.addEventListener('click', function (e) {
            const btn = e.target.closest('button[type="submit"], input[type="submit"]');
            if (!btn) return;
            const form = btn.closest('form');
            if (!form) return;

            for (const field of form.querySelectorAll('[required]')) {
                if (field.disabled || field.value) continue;
                const wasReadonly = field.readOnly;
                if (wasReadonly) field.readOnly = false;
                const valid = field.reportValidity();
                if (wasReadonly) {
                    setTimeout(() => { field.readOnly = true; }, 900);
                }
                if (!valid) {
                    e.preventDefault();
                    e.stopPropagation();
                    return;
                }
            }
        }, true);
    </script>

    <!-- SweetAlert Flash Message -->
    <?= view('components/alerts') ?>
</body>

</html>