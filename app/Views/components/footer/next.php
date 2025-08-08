<div class="inline-flex gap-x-2">
    <?php 
        $total_pages  = $meta_data['total'] ?? 1;
        $current_page = $meta_data['page'] ?? 1;
        $page_size    = $meta_data['size'] ?? 10;

        $is_disabled = $current_page >= $total_pages;
        $next_page_url = $modul_path . '?page=' . ($current_page + 1) . '&size=' . $page_size;
    ?>
    <button type="button"
        class="min-h-[38px] min-w-[38px] py-2 px-2.5 inline-flex justify-center items-center gap-x-2 text-sm rounded-lg text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-white/10 dark:focus:bg-white/10"
        aria-label="Next page"
        aria-disabled="<?= $is_disabled ? 'true' : 'false' ?>"
        <?= $is_disabled ? 'disabled' : '' ?>
        onclick="<?= !$is_disabled ? "window.location.href='{$next_page_url}'" : '' ?>">
        
        <span aria-hidden="true" class="hidden sm:block">Next</span>
        <img src="<?= base_url('svg/footer_next.svg') ?>" alt="Next Icon">
    </button>
</div>
