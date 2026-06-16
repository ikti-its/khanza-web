<?= view('components/modal/modal-table', [
    'modalId'      => 'modalTindakanOperasi',
    'modalTitle'   => 'Pilih Tindakan Operasi',
    'headers'      => ['Kode', 'Nama Tindakan'],
    'tableId'      => 'tindakanOperasiTable',
    'searchInputs' => [
        ['id' => 'searchKodeTindakan', 'placeholder' => 'Cari kode tindakan...'],
        ['id' => 'searchNamaTindakan', 'placeholder' => 'Cari nama tindakan...'],
    ],
    'actions' => [
        [
            'type'    => 'button',
            'text'    => 'Refresh',
            'onclick' => 'open_modalTindakanOperasi()',
            'icon'    => 'refresh'
        ],
    ]
]) ?>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        initModalList({
            modalId:    'modalTindakanOperasi',
            tableId:    'tindakanOperasiTable',
            url:        '<?= site_url('operasi/referensi-tindakan-operasi/modal/list') ?>',
            fields:     ['kode_tindakan', 'nama_tindakan'],
            searchIds: {
                searchKodeTindakan: 'kode_tindakan',
                searchNamaTindakan: 'nama_tindakan'
            },
            rowsPerPage: 10,
            onSelect: (item) => {
                autofillTindakan({
                    id_tindakan:   item.id_tindakan,
                    nama_tindakan: item.nama_tindakan,
                });
            }
        });
    });
</script>