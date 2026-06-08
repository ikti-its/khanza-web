<?php
    /**
     * @var int|string $id
     * @var string $column
     * @var string|int|float $value
     * @var 0|1 $req
     */
?>
<input
    id="<?= $id ?>"
    type="text"
    name="<?= $column ?>"
    value="<?= esc((string) $value) ?>"
    class="border border-gray-200 text-gray-400 text-sm rounded-lg p-2 w-full md:w-1/4 bg-gray-50 cursor-not-allowed dark:border-gray-700 dark:text-gray-500 dark:bg-slate-800"
    readonly
    tabindex="-1">
