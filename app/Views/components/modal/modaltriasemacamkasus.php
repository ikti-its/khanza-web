<?= view('components/modal/modal-table', [
    'modalId'      => 'modalTriaseMacamKasus',
    'modalTitle'   => 'Cari Data Master Triase Macam Kasus',
    'headers'      => ['Kode', 'Nama Macam Kasus'],
    'tableId'      => 'triaseMacamKasusTable',
    'searchInputs' => [
        ['id' => 'searchKodeKasus', 'placeholder' => 'Cari kode...'],
        ['id' => 'searchNamaKasus', 'placeholder' => 'Cari nama kasus...'],
    ],
    'actions' => [
        ['type' => 'button', 'text' => 'Refresh', 'onclick' => 'open_modalTriaseMacamKasus()', 'icon' => 'refresh'],
        ['type' => 'link', 'text' => 'Tambah Triase Macam Kasus', 'href' => '/triase-ugd/triase-macam-kasus/tambah', 'icon' => 'plus'],
    ]
]) ?>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const btnTambah = document.querySelector('#modalTriaseMacamKasus a[href="/triase-ugd/triase-macam-kasus/tambah"]');
        if (btnTambah) {
            btnTambah.removeAttribute('target');
            btnTambah.href = '/triase-ugd/triase-macam-kasus/tambah?redirect_to=' + encodeURIComponent(window.location.pathname + window.location.search);
        }
        initModalList({
            modalId: 'modalTriaseMacamKasus',
            tableId: 'triaseMacamKasusTable',
            url:     '<?= site_url('triase-ugd/triase-macam-kasus/modal/list') ?>',
            fields: ['kode_macam_kasus', 'nama_macam_kasus'],
            searchIds: {
                searchKodeKasus: 'kode_macam_kasus',
                searchNamaKasus: 'nama_macam_kasus'
            },
            rowsPerPage: 10,
            onSelect: (item) => {
                autofillMacamKasus(item);
            }
        });
    });
</script>