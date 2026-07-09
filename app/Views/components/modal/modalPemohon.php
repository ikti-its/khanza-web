<?= view('components/modal/modal-table', [
    'modalId'      => 'modalPemohon',
    'modalTitle'   => 'Cari Data Pemohon',
    'headers'      => ['Nama', 'Deskripsi'],
    'tableId'      => 'pemohonTable',
    'searchInputs' => [
        ['id' => 'searchNamaPemohon', 'placeholder' => 'Cari nama pemohon...'],
        ['id' => 'searchDeskripsiPemohon', 'placeholder' => 'Cari deskripsi...'],
    ],
    'actions' => [
        ['type' => 'button', 'text' => 'Refresh', 'onclick' => 'open_modalPemohon()', 'icon' => 'refresh'],
    ],
]) ?>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        initModalList({
            modalId:     'modalPemohon',
            tableId:     'pemohonTable',
            url:         '<?= site_url('role/petugas/modal/list') ?>',
            fields:      ['nama', 'deskripsi'],
            searchIds: {
                searchNamaPemohon:      'nama',
                searchDeskripsiPemohon: 'deskripsi',
            },
            rowsPerPage: 10,
            onSelect: (item) => {
                autofillPemohon(item);
            },
        });
    });
</script>
