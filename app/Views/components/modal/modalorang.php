<?= view('components/modal/modal-table', [
    'modalId'      => 'modalOrang',
    'modalTitle'   => 'Pilih Orang',
    'headers'      => ['NIK', 'Nama'],
    'tableId'      => 'orangTable',
    'searchInputs' => [
        ['id' => 'searchOrangNIK',  'placeholder' => 'Cari NIK...'],
        ['id' => 'searchOrangNama', 'placeholder' => 'Cari nama...'],
    ],
    'actions' => [
        ['type' => 'button', 'text' => 'Refresh',      'onclick' => 'open_modalOrang()', 'icon' => 'refresh'],
        ['type' => 'link',   'text' => 'Tambah Orang', 'href'    => '/person/orang/tambah', 'icon' => 'plus'],
    ]
]) ?>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        initModalList({
            modalId:     'modalOrang',
            tableId:     'orangTable',
            url:         '<?= site_url('person/orang/modal/list') ?>',
            fields:      ['nik', 'nama'],
            searchIds: {
                searchOrangNIK:  'nik',
                searchOrangNama: 'nama',
            },
            rowsPerPage: 10,
            onSelect: (item) => {
                autofillOrangFields(item);
            }
        });
    });
</script>
