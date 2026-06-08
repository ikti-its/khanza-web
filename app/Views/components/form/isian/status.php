<?php
    /**
     * @var int|string $id
     * @var string $column
     * @var string|int|float $value
     * @var 0|1 $req
     * @var ?list<list<string>> $opsi
     */

if ($opsi === null) {
    echo view('components/form/isian/teks', [
        'id'     => $id,
        'column' => $column,
        'value'  => $value,
        'req'    => $req,
    ]);
    return;
}

// Deteksi autofill metadata dari elemen ke-3 option
$autofill_target = null;
$autofill_key    = null;
foreach ($opsi as $o) {
    if (isset($o[2]['_target'], $o[2]['_key'])) {
        $autofill_target = $o[2]['_target'];
        $autofill_key    = $o[2]['_key'];
        break;
    }
}
?>
<select
    id="<?= $id ?>"
    name="<?= $column ?>"
    class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white"
    <?= $req === 1 ? 'required' : '' ?>
    <?= $autofill_target ? 'data-autofill-target="' . esc($autofill_target) . '" data-autofill-key="' . esc($autofill_key) . '"' : '' ?>>

    <?php
        array_unshift($opsi, ["-- Pilih --", '']);
        foreach ($opsi as $o) {
            $selected   = ((string) $value === (string) $o[1]) ? 'selected' : '';
            $data_attrs = '';
            if (isset($o[2]) && is_array($o[2])) {
                foreach ($o[2] as $k => $v) {
                    if (str_starts_with($k, '_')) continue; // skip internal keys
                    $data_attrs .= ' data-' . esc($k) . '="' . esc((string) $v) . '"';
                }
            }
            echo '<option value="' . esc((string) $o[1]) . '" ' . $selected . $data_attrs . '>' . esc($o[0]) . '</option>';
        }
    ?>
</select>
