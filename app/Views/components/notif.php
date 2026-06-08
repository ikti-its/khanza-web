<div class="hs-dropdown relative inline-flex [--placement:bottom-right]">
    <button id="hs-dropdown-with-header" type="button" class="w-[2.375rem] h-[2.375rem] inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-full border border-transparent text-gray-800 hover:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-gray-700 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
        <svg width="36" height="36" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M18 12.4438V15.7738" stroke="#666666" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" />
            <path d="M18.02 8.00391C14.34 8.00391 11.36 10.9839 11.36 14.6639V16.7639C11.36 17.4439 11.08 18.4639 10.73 19.0439L9.46002 21.1639C8.68002 22.4739 9.22002 23.9339 10.66 24.4139C15.44 26.0039 20.61 26.0039 25.39 24.4139C26.74 23.9639 27.32 22.3839 26.59 21.1639L25.32 19.0439C24.97 18.4639 24.69 17.4339 24.69 16.7639V14.6639C24.68 11.0039 21.68 8.00391 18.02 8.00391Z" stroke="#666666" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" />
            <path d="M21.33 24.8237C21.33 26.6537 19.83 28.1537 18 28.1537C17.09 28.1537 16.25 27.7737 15.65 27.1737C15.05 26.5737 14.67 25.7337 14.67 24.8237" stroke="#666666" stroke-width="1.5" stroke-miterlimit="10" />
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
                                    <svg class="flex-shrink-0 size-4 text-teal-600 mt-1" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <path d="M12 16v-4"></path>
                                        <path d="M12 8h.01"></path>
                                    </svg>
                                </div>
                                <div class="ms-3">
                                    <h2 class="text-gray-800 font-bold dark:text-white">
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