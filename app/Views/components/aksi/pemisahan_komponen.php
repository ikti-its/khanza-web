<div class="w-full">
    <?php if (isset($baris['id_status_pengambilan']) && (int)$baris['id_status_pengambilan'] === 1): ?>
        <div class="px-3 py-1.5">
            <a href="<?= base_url('inventori-darah/pemisahan-komponen/tambah?pengambilan=' . $id) ?>" 
               class="gap-x-1 text-sm text-teal-600 hover:underline font-semibold dark:text-teal-400">
                Pisahkan
            </a>
        </div>
    <?php else: ?>
        <div class="px-3 py-1.5">
            <a class="text-sm text-teal-600 hover:underline font-semibold" style="visibility:hidden">Pisahkan</a>
        </div>
    <?php endif; ?>
</div>