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
// Format baru: _autofill => ['target_field' => 'data_key', ...]
// Format lama: _target, _key (backward compat)
$autofill_map = [];
foreach ($opsi as $o) {
    if (!isset($o[2]) || !is_array($o[2])) continue;
    $meta = $o[2];
    if (isset($meta['_autofill']) && is_array($meta['_autofill'])) {
        $autofill_map = $meta['_autofill'];
    } elseif (isset($meta['_target'], $meta['_key'])) {
        $autofill_map[$meta['_target']] = $meta['_key'];
    }
    if (!empty($autofill_map)) break;
}
?>
<select
    id="<?= $id ?>"
    name="<?= $column ?>"
    class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white"
    <?= $req === 1 ? 'required' : '' ?>
    <?= !empty($autofill_map) ? 'data-autofill-map=\'' . json_encode($autofill_map, JSON_HEX_APOS) . '\'' : '' ?>>

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
