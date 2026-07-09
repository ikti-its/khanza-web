<?php
    /**
     * @var int|string $id
     * @var string $column
     * @var string|int|float $value
     * @var 0|1 $req
     */

    $displayValue = $value;
    if ($value !== '' && $value !== null && is_numeric($value)) {
        $displayValue = rtrim(rtrim(sprintf('%.4F', (float) $value), '0'), '.');
        if ($displayValue === '') {
            $displayValue = '0';
        }
    }
?>

<input
    id="<?= $id ?>"
    type="number"
    name="<?= $column ?>"
    value="<?= $displayValue ?>"
    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white"
    placeholder="0" v
    <?= $req  === 1   ? 'required' : ''; ?>
>