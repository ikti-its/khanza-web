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