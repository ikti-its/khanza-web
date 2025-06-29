<button type="button" class="hs-accordion-toggle hs-accordion-active:bg-gray-100 w-full flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-lg hover:bg-gray-100 dark:hover:bg-teal-900 dark:text-slate-400 dark:hover:text-slate-300 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
    <img src="<?= base_url('svg/' . $icon . '.svg') ?>">
    <?php
        echo $teks;
        echo view('components/menu/dropdown_icon');
    ?>
</button>