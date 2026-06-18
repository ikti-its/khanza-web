<?= view('components/modal/modal-table', [
    'modalId'      => 'modalKota',
    'modalTitle'   => 'Cari Data Kota / Kabupaten',
    'headers'      => ['Nama Kota/Kabupaten'],
    'tableId'      => 'kotaTable',
    'searchInputs' => [
        ['id' => 'searchNamaKota',     'placeholder' => 'Cari nama kota/kabupaten...'],
    ],
    'actions' => [
        ['type' => 'button', 'text' => 'Refresh', 'onclick' => 'open_modalKota()', 'icon' => 'refresh'],
    ]
]) ?>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        initModalList({
            modalId:     'modalKota',
            tableId:     'kotaTable',
            url:         '<?= site_url('lokasi/kota/modal/list') ?>',
            fields:      ['nama_kota'],
            searchIds: {
                searchNamaKota:     'nama_kota',
            },
            rowsPerPage: 10,
            onSelect: (item) => {
                autofillKota(item);
            }
        });
    });
</script>