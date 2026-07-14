<?php
    /**
     * @var int|string $id
     * @var string $column
     * @var 0|1 $req
     * @var array<string, mixed> $row
     */
    // Nilai tidak pernah di-prefill agar hash tidak bocor ke HTML.
    // Pada form ubah, kosong berarti password tidak diganti.
    $is_edit = ($row ?? []) !== [];
?>

<input
    id="<?= $id ?>"
    type="password"
    name="<?= $column ?>"
    value=""
    autocomplete="new-password"
    placeholder="<?= $is_edit ? 'Kosongkan jika tidak diganti' : '' ?>"
    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white"
    <?= $is_edit ? '' : 'required'; ?>
>