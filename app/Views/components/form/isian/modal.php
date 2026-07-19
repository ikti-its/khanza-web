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
$placeholder    = $opsi['placeholder'] ?? 'Klik untuk memilih...';
?>
<input type="hidden"
    id="<?= $column ?>"
    name="<?= $column ?>"
    value="<?= esc((string) $value) ?>"
    <?= $req ? 'required' : '' ?>>
<div class="flex items-center gap-2 w-full md:w-1/4">
    <input type="text"
        id="<?= $column ?>_display"
        value="<?= esc((string) $display_value) ?>"
        class="py-2 px-3 border border-gray-200 rounded-lg text-sm bg-white flex-1 min-w-0 cursor-pointer hover:border-blue-400 dark:border-gray-700 dark:bg-slate-800"
        placeholder="<?= esc($placeholder) ?>"
        onclick="open_<?= $modal_name ?>()"
        readonly>
    <button type="button"
        onclick="open_<?= $modal_name ?>()"
        class="inline-flex justify-center items-center p-2 text-sm font-medium text-white bg-blue-600 rounded-lg border border-transparent hover:bg-blue-700 focus:outline-none transition-all w-10 h-[38px] flex-shrink-0 shadow-sm">
        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
        </svg>
    </button>
</div>
