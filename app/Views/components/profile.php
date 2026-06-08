<?php
    $userDetails = session()->get('user_details');

    // dd($userDetails);
    // Initialize defaults
    $role = null;
    $foto = '/img/akun-icon.png';
    $email = 'Guest';

    if (is_array($userDetails)) {
        $role  = $userDetails['role']  ?? null;
        $foto  = $userDetails['foto']  ?? $foto;
        $email = $userDetails['email'] ?? $email;
    }
?>

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