<?= view('components/modal/modal-table', [
    'modalId'      => 'modalSatuan',
    'modalTitle'   => 'Pilih Satuan',
    'headers'      => ['Kode', 'Nama Satuan'],
    'tableId'      => 'satuanTable',
    'searchInputs' => [
        ['id' => 'searchNamaSatuan', 'placeholder' => 'Cari nama satuan...'],
    ],
    'actions' => [
        ['type' => 'button', 'text' => 'Refresh', 'onclick' => 'open_modalSatuan()', 'icon' => 'refresh'],
    ],
]) ?>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        initModalList({
            modalId:     'modalSatuan',
            tableId:     'satuanTable',
            url:         '<?= site_url('inventori-non-medis/satuan/modal/list') ?>',
            fields:      ['kode_satuan', 'nama_satuan'],
            searchIds: {
                searchNamaSatuan: 'nama_satuan',
            },
            rowsPerPage: 10,
            onSelect: (item) => {
                autofillSatuan(item);
            },
        });
    });
</script>
