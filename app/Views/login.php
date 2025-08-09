<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= base_url() ?>css/style.css?v=1.0">
    <title>Halaman Login</title>

    <style>
        .bg-svg {
            background-image: url('/svg/background.svg'); /* Adjust the path based on your directory structure */
            background-size: cover; /* Adjust as necessary */
            background-repeat: no-repeat;
            background-position: center;
        }
    </style>
</head>

<body class="bg-svg">

    <?php if (session()->getFlashdata('error')) : ?>
        <div class="mt-2 bg-[#FEE2E2] text-sm text-[#DA4141] rounded-lg p-4" role="alert">
            <span class="font-bold"></span><?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>
    <div class="min-h-screen flex items-center justify-center">
        <form action="dashboard" method="post" class="w-full max-w-md">
            <div class="px-8 py-10 bg-white shadow-lg rounded-xl">
                <h2 class="text-3xl font-bold text-center mb-6">Masuk ke akun Anda</h2>
                <div class="mb-6">
                    <?php if (session()->getFlashdata('passwordsalah')) : ?>
                        <div id="warningMessage" class="flex items-center my-2 bg-[#FEE2E2] text-sm font-semibold text-[#DA4141] rounded-lg p-4" role="alert">
                            <span class="mx-1 font-semibold"></span><?= session()->getFlashdata('passwordsalah') ?>
                        </div>
                    <?php elseif (session()->getFlashdata('akunsalah')) : ?>
                        <div id="warningMessage" class="flex items-center my-2 bg-[#FEE2E2] text-sm font-semibold text-[#DA4141] rounded-lg p-4" role="alert">
                            <span class="mx-1 font-semibold"></span><?= session()->getFlashdata('akunsalah') ?>
                        </div>
                    <?php endif; ?>
                    <label for="nip" class="block text-gray-600 mb-1">E-mail</label>
                    <input id="email" name="email" type="email" placeholder="E-mail" pattern="[a-z0-9._%+\-]+@[a-z0-9.-]+\.[a-z]{2,}$" class="w-full px-4 py-2 border rounded-md focus:outline-none focus:border-black" required title="Masukkan email sesuai dengan format nama@fathoor.dev.">

                </div>
                <div class="mb-6">
                    <label for="password" class="block text-gray-600 mb-1">Password</label>
                    <input id="password" name="password" type="password" placeholder="Kata Sandi" class="w-full px-4 py-2 border rounded-md focus:outline-none focus:border-black " required>

                </div>
                <div class="text-center">
                    <button type="submit" class="w-full px-6 py-3 bg-teal-800  text-white font-semibold rounded-md hover:bg-teal-600 transition duration-200 ease">Masuk</button>
                </div>
            </div>
        </form>
    </div>

</body>

</html>
