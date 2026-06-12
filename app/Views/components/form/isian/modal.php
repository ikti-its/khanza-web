<?php
/**
 * @var string       $column - FK column name (e.g., 'id_barang')
 * @var string|int   $value  - Current FK value
 * @var 0|1          $req    - Required flag
 * @var array        $opsi   - ['modal' => 'modalBarang', 'display_column' => 'nama_barang']
 * @var array        $row    - Full row data for edit mode (may contain display column value)
 */
$modal_name     = $opsi['modal'] ?? '';
$display_column = $opsi['display_column'] ?? '';
$display_value  = $row[$display_column] ?? '';
?>
<input type="hidden"
    id="<?= $column ?>"
    name="<?= $column ?>"
    value="<?= esc((string) $value) ?>"
    <?= $req ? 'required' : '' ?>>
<input type="text"
    id="<?= $column ?>_display"
    value="<?= esc((string) $display_value) ?>"
    class="py-2 px-3 border border-gray-200 rounded-lg text-sm bg-gray-50 w-full md:w-1/4 cursor-pointer hover:border-emerald-500 hover:bg-emerald-50 dark:border-gray-700 dark:bg-slate-800"
    placeholder="-- Klik untuk memilih --"
    onclick="open_<?= $modal_name ?>()"
    readonly>
