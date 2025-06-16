<div class="flex items-center gap-x-1">
    <?php
    $total_pages  = $meta_data['total'] ?? 1; // Ensure 'total' always has a value
    $current_page = $meta_data['page'] ?? 1;

    $range = 2; // Number of pages to show before and after the current page
    $show_items = ($range * 2) + 1;

    if ($total_pages <= $show_items) {
        for ($i = 1; $i <= $total_pages; $i++) {
            echo '<button type="button" class="min-h-[38px] min-w-[38px] flex justify-center items-center ' . ($current_page == $i ? 'bg-gray-200 text-gray-800 dark:bg-neutral-600 dark:focus:bg-neutral-500' : 'text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:text-white dark:hover:bg-white/10 dark:focus:bg-white/10') . ' py-2 px-3 text-sm rounded-lg" ' . ($current_page == $i ? 'aria-current="page"' : '') . ' onclick="window.location.href=\'' . $api_url . '?page=' . $i . '&size=' . $meta_data['size'] . '\'">' . $i . '</button>';
        }
    } else {
        if ($current_page > $range + 1) {
            echo '<button type="button" class="min-h-[38px] min-w-[38px] flex justify-center items-center text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:text-white dark:hover:bg-white/10 dark:focus:bg-white/10 py-2 px-3 text-sm rounded-lg" onclick="window.location.href=\'' . $api_url . '?page=1&size=' . $meta_data['size'] . '\'">1</button>';
            if ($current_page > $range + 2) {
                echo '<span class="py-2 px-3 text-sm">...</span>';
            }
        }

        for ($i = max($current_page - $range, 1); $i <= min($current_page + $range, $total_pages); $i++) {
            echo '<button type="button" class="min-h-[38px] min-w-[38px] flex justify-center items-center ' . ($current_page == $i ? 'bg-gray-200 text-gray-800 dark:bg-neutral-600 dark:focus:bg-neutral-500' : 'text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:text-white dark:hover:bg-white/10 dark:focus:bg-white/10') . ' py-2 px-3 text-sm rounded-lg" ' . ($current_page == $i ? 'aria-current="page"' : '') . ' onclick="window.location.href=\'' . $api_url . '?page=' . $i . '&size=' . $meta_data['size'] . '\'">' . $i . '</button>';
        }

        if ($current_page < $total_pages - $range - 1) {
            if ($current_page < $total_pages - $range - 2) {
                echo '<span class="py-2 px-3 text-sm">...</span>';
            }
            echo '<button type="button" class="min-h-[38px] min-w-[38px] flex justify-center items-center text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:text-white dark:hover:bg-white/10 dark:focus:bg-white/10 py-2 px-3 text-sm rounded-lg" onclick="window.location.href=\'' . $api_url . '?page=' . $total_pages . '&size=' . $meta_data['size'] . '\'">' . $total_pages . '</button>';
        }
    }
    ?>
</div>