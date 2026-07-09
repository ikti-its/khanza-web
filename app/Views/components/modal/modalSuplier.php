<?= view('components/modal/modal-table', [
    'modalId'      => 'modalSuplier',
    'modalTitle'   => 'Pilih Suplier',
    'headers'      => ['Kode', 'Nama Suplier'],
    'tableId'      => 'suplierTable',
    'searchInputs' => [
        ['id' => 'searchKodeSuplier', 'placeholder' => 'Cari kode suplier...'],
        ['id' => 'searchNamaSuplier', 'placeholder' => 'Cari nama suplier...'],
    ],
    'actions' => [
        ['type' => 'button', 'text' => 'Refresh', 'onclick' => 'open_modalSuplier()', 'icon' => 'refresh'],
    ],
]) ?>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        initModalList({
            modalId:     'modalSuplier',
            tableId:     'suplierTable',
            url:         '<?= site_url('inventori-non-medis/suplier/modal/list') ?>',
            fields:      ['kode_suplier', 'nama_suplier'],
            searchIds: {
                searchKodeSuplier: 'kode_suplier',
                searchNamaSuplier: 'nama_suplier',
            },
            rowsPerPage: 10,
            onSelect: (item) => {
                autofillSuplier(item);
            },
        });
    });
</script>
