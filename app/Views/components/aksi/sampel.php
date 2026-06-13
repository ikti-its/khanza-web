<div class="px-3 py-1.5">
    <form action="<?= $modul_path ?>/sampel/<?= $id ?>" method="post"
          onsubmit="return confirm('Konfirmasi pengambilan sampel sekarang?')">
        <?= csrf_field() ?>
        <button type="submit"
                class="gap-x-1 text-sm text-amber-600 decoration-2 hover:underline font-semibold dark:text-amber-400">
            Sampel
        </button>
    </form>
</div>
