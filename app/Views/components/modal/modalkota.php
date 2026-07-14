<?= view('components/modal/modal-table', [
    'modalId'      => 'modalKota',
    'modalTitle'   => 'Pilih Kota',
    'headers'      => ['Nama Kota', 'Provinsi'],
    'tableId'      => 'kotaTable',
    'searchInputs' => [
        ['id' => 'searchNamaKota', 'placeholder' => 'Cari nama kota...'],
        ['id' => 'searchProvinsiKota', 'placeholder' => 'Cari provinsi...'],
    ],
    'actions' => [
        ['type' => 'button', 'text' => 'Refresh', 'onclick' => 'open_modalKota()', 'icon' => 'refresh'],
    ],
]) ?>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        initModalList({
            modalId:     'modalKota',
            tableId:     'kotaTable',
            url:         '<?= site_url('lokasi/kota/modal/list') ?>',
            fields:      ['nama_kota', 'nama_provinsi'],
            searchIds: {
                searchNamaKota: 'nama_kota',
                searchProvinsiKota: 'nama_provinsi',
            },
            rowsPerPage: 10,
            onSelect: (item) => {
                autofillKota(item);
            },
        });
    });
</script>
