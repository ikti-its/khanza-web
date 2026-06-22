<?= view('components/modal/modal-table', [
    'modalId'      => 'modalUnit',
    'modalTitle'   => 'Pilih Unit',
    'headers'      => ['Kode Unit', 'Nama Unit'],
    'tableId'      => 'unitTable',
    'searchInputs' => [
        ['id' => 'searchKodeUnit', 'placeholder' => 'Cari kode unit...'],
        ['id' => 'searchNamaUnit', 'placeholder' => 'Cari nama unit...'],
    ],
    'actions' => [
        ['type' => 'button', 'text' => 'Refresh',   'onclick' => 'open_modalUnit()', 'icon' => 'refresh'],
        ['type' => 'link',   'text' => 'Tambah Unit', 'href'  => '/unit/unit/tambah', 'icon' => 'plus'],
    ]
]) ?>

<?php
$_unitUrl = site_url('unit/unit/modal/list');
if (!empty($excludeIds)) {
    $_unitUrl .= '?exclude=' . implode(',', array_map('intval', $excludeIds));
}
?>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        initModalList({
            modalId:     'modalUnit',
            tableId:     'unitTable',
            url:         '<?= $_unitUrl ?>',
            fields:      ['kode_unit', 'nama_unit'],
            searchIds: {
                searchKodeUnit: 'kode_unit',
                searchNamaUnit: 'nama_unit',
            },
            rowsPerPage: 10,
            onSelect: (item) => {
                autofillUnitFields(item);
            }
        });
    });
</script>
