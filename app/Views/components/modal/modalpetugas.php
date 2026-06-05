<?= view('components/modal/modal-table', [
    'modalId'      => 'modalPetugas',
    'modalTitle'   => 'Cari Data Petugas',
    'headers'      => ['Nama Petugas', 'Deskripsi'],
    'tableId'      => 'petugasTable',
    'searchInputs' => [
        ['id' => 'searchNamaPetugas', 'placeholder' => 'Cari nama petugas...'],
        ['id' => 'searchDeskripsi', 'placeholder' => 'Cari deskripsi...'],
    ],
    'actions' => [
        ['type' => 'button', 'text' => 'Refresh', 'onclick' => 'open_modalPetugas()', 'icon' => 'refresh'],
        ['type' => 'link', 'text' => 'Tambah Petugas', 'href' => '/role/petugas/tambah', 'icon' => 'plus']
    ]
]) ?>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const btnTambah = document.querySelector('#modalPetugas a[href="/role/petugas/tambah"]');
        if (btnTambah) {
            btnTambah.removeAttribute('target');
        }
        initModalList({
            modalId: 'modalPetugas',
            tableId: 'petugasTable',
            url: '<?= base_url('role/petugas/modal/list') ?>',
            fields: ['nama', 'deskripsi'],
            searchIds: {
                searchNamaPetugas: 'nama',
                searchDeskripsi: 'deskripsi'
            },
            rowsPerPage: 10,
            onSelect: (item) => {
                autofillPetugas({
                    id_petugas: item.id_petugas,
                    nama: item.nama
                });
            }
        });
    });
</script>