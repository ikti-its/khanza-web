<div class="inline-flex gap-x-2">
    <?php 
        $total_pages  = $meta_data['total'] ?? 1; // Ensure 'total' always has a value
        $current_page = $meta_data['page'] ?? 1;
    ?>
    <button type="button" class="min-h-[38px] min-w-[38px] py-2 px-2.5 inline-flex justify-center items-center gap-x-2 text-sm rounded-lg text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-white/10 dark:focus:bg-white/10" 
        aria-label="Next page" 
        <?= $current_page >= $total_pages ? 'disabled' : '' ?> 
        onclick="window.location.href='<?= $modul_path ?>?page=<?= $current_page + 1 ?>&size=<?= $meta_data['size'] ?>'">

        <span aria-hidden="true" class="hidden sm:block">
            Next
        </span>
        <img src="<?= base_url('svg/footer_next.svg') ?>">
    </button>
</div>