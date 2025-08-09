<div class="flex items-center gap-x-1">
    <?php
    $total_pages  = $meta_data['total'] ?? 1;
    $current_page = $meta_data['page'] ?? 1;
    $page_size    = $meta_data['size'] ?? 10;
    $range        = 2;
    $show_items   = ($range * 2) + 1;

    function renderPageButton($i, $current_page, $modul_path, $page_size) {
        $isActive = $current_page == $i;
        $classes = 'min-h-[38px] min-w-[38px] flex justify-center items-center py-2 px-3 text-sm rounded-lg ';
        $classes .= $isActive
            ? 'bg-gray-200 text-gray-800 dark:bg-neutral-600 dark:focus:bg-neutral-500'
            : 'text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:text-white dark:hover:bg-white/10 dark:focus:bg-white/10';
        $aria = $isActive ? 'aria-current="page"' : '';
        $href = "{$modul_path}?page={$i}&size={$page_size}";

        echo "<button type=\"button\" class=\"{$classes}\" {$aria} onclick=\"window.location.href='{$href}'\">{$i}</button>";
    }

    if ($total_pages <= $show_items) {
        for ($i = 1; $i <= $total_pages; $i++) {
            renderPageButton($i, $current_page, $modul_path, $page_size);
        }
    } else {
        if ($current_page > $range + 1) {
            renderPageButton(1, $current_page, $modul_path, $page_size);
            if ($current_page > $range + 2) {
                echo '<span class="py-2 px-3 text-sm text-gray-500">...</span>';
            }
        }

        for ($i = max(1, $current_page - $range); $i <= min($total_pages, $current_page + $range); $i++) {
            renderPageButton($i, $current_page, $modul_path, $page_size);
        }

        if ($current_page < $total_pages - $range) {
            if ($current_page < $total_pages - $range - 1) {
                echo '<span class="py-2 px-3 text-sm text-gray-500">...</span>';
            }
            renderPageButton($total_pages, $current_page, $modul_path, $page_size);
        }
    }
    ?>
</div>
