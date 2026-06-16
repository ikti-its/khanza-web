<?= view('components/modal/modal-table', [
    'modalId'      => 'modalRuangan',
    'modalTitle'   => 'Pilih Ruangan',
    'headers'      => ['Kode', 'Nama Ruangan'],
    'tableId'      => 'ruanganTable',
    'searchInputs' => [
        ['id' => 'searchKodeRuangan', 'placeholder' => 'Cari kode ruangan...'],
        ['id' => 'searchNamaRuangan', 'placeholder' => 'Cari nama ruangan...'],
    ],
    'actions' => [
        [
            'type'    => 'button',
            'text'    => 'Refresh',
            'onclick' => 'open_modalRuangan()',
            'icon'    => 'refresh'
        ],
    ]
]) ?>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        initModalList({
            modalId:    'modalRuangan',
            tableId:    'ruanganTable',
            url:        '<?= site_url('ruangan/ruangan/modal/list') ?>',
            fields:     ['kode_ruangan', 'nama_ruangan'],
            searchIds: {
                searchKodeRuangan: 'kode_ruangan',
                searchNamaRuangan: 'nama_ruangan'
            },
            rowsPerPage: 10,
            onSelect: (item) => {
                autofillRuangan({
                    id_ruangan:   item.id_ruangan,
                    nama_ruangan: item.nama_ruangan,
                });
            }
        });
    });
</script>