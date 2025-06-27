<!-- Header -->
<div class="py-1 flex justify-between items-center border-gray-200 dark:border-gray-700">
    <?= view('components/header/judul', [
            'judul' => $judul
        ]) ?>
    <div class="flex gap-x-6 justify-center items-center">
        <div class="relative">
            <?= view('components/notif/icon') ?>

            <!-- Notification Pop-up -->
            <div id="notif-popup" class="absolute right-0 mt-2 w-[30rem] overflow-y-auto z-[2] bg-white rounded-lg shadow-lg hidden">
                <?= view('components/notif/notif') ?>
                <div>
                    <div id="stok-content" class="max-h-[15rem] overflow-y-auto">
                    </div>
                </div>
            </div>
        </div>
        <div class="h-[1.375rem] border-r-4 bg-[#DCDCDC]"></div>
        <?= view('components/header/tambah_button', [
            'link' => $modul_path . '/tambah'
        ]) ?>
        <?= view('components/header/audit_button', [
            'link' => $modul_path . '/audit'
        ]) ?>
    </div>
</div>

<!-- End Header -->