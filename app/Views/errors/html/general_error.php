<!DOCTYPE html>

<?php
    /**
     * @var int $code
     * @var string $title
     * @var string $error
     * @var string $message
     */
?>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
    <title><?= esc($title) ?></title>
</head>

<body>
    <main class="container mx-auto ml-0">
        <div class="w-screen h-screen">
            <div class="min-h-screen bg-gradient-to-b from-white via-[#D6F9F3] to-[#24A793] flex flex-col justify-center items-center relative">
                <div class="text-center z-10 pb-16">
                    <h1 class="text-6xl font-bold text-black mb-4"><?= esc((string) $code) ?></h1>
                    <h2 class="text-6xl font-bold text-black mb-4"><?= esc($error) ?></h2>
                    <p class="text-2xl text-black"><?= esc($message) ?></p>
                </div>

                <img src="<?= base_url('svg/error/error.svg') ?>"
                    alt="Error illustration"
                    class="w-full h-auto absolute bottom-0"
                >

                <div class="flex justify-center items-center z-10">
                    <div class="w-full md:w-auto sm:w-auto lg:w-auto">
                        <a class="py-2 px-12 sm:px-6 inline-flex justify-center items-center gap-2 rounded-lg border font-medium bg-[#0A2D27] text-[#ACF2E7] shadow-sm align-middle hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-white focus:ring-teal-600 transition-all text-sm dark:bg-neutral-900 dark:hover:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-400 dark:hover:text-white dark:focus:ring-offset-gray-800" 
                            href="<?= site_url('/dashboard') ?>">
                            Kembali ke Beranda
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>