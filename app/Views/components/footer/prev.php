<div class="inline-flex gap-x-2">
    <button type="button" class="min-h-[38px] min-w-[38px] py-2 px-2.5 inline-flex justify-center items-center gap-x-2 text-sm rounded-lg text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-white/10 dark:focus:bg-white/10" 
        aria-label="Previous page" 
        <?php
            /**
             * @var array{'page':int, 'size':int, 'total':int} $meta_data
             * @var string $modul_path
             */
        ?>
        <?= $meta_data['page'] <= 1 ? 'disabled' : '' ?> 
        onclick="window.location.href='<?= $modul_path ?>/data?page=<?= $meta_data['page'] - 1 ?>&size=<?= $meta_data['size'] ?>'">
        
        <img src="<?= base_url('svg/footer/footer_prev.svg') ?>">
        <span aria-hidden="true" class="hidden sm:block">Previous</span>
    </button>
</div>