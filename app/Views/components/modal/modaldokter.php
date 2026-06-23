<?= view('components/modal/modal-table', [
    'modalId'      => 'modalDokter',
    'modalTitle'   => 'Pilih Dokter',
    'headers'      => ['Kode Dokter', 'Nama Dokter', 'Spesialis'],
    'tableId'      => 'dokterTable',
    'searchInputs' => [
        ['id' => 'searchKodeDokter', 'placeholder' => 'Cari kode dokter...'],
        ['id' => 'searchNamaDokter', 'placeholder' => 'Cari nama dokter...'],
    ],
    'actions' => [
        [
            'type'    => 'button',
            'text'    => 'Refresh',
            'onclick' => 'open_modalDokter()',
            'icon'    => 'refresh'
        ],
        [
            'type'    => 'link',
            'text'    => 'Tambah Daftar Dokter',
            'href'    => '/role/dokter/tambah',
            'icon'    => 'plus'
        ]
    ]
]) ?>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const btnTambahDokter = document.querySelector('#modalDokter a[href="/role/dokter/tambah"]');
        if (btnTambahDokter) {
            btnTambahDokter.removeAttribute('target');
            btnTambahDokter.href = '/role/dokter/tambah?redirect_to=' + encodeURIComponent(window.location.pathname + window.location.search);
        }
        initModalList({
            modalId:    'modalDokter',
            tableId:    'dokterTable',
            url:        '<?= site_url('role/dokter/modal/list') ?>',
            fields:     ['kode_dokter', 'nama_dokter', 'spesialis'],
            searchIds: {
                searchKodeDokter: 'kode_dokter',
                searchNamaDokter: 'nama_dokter'
            },
            rowsPerPage: 10,
            onSelect: (item) => {
                autofillFields({
                    id_dokter:            item.id_dokter,
                    kode_dokter:          item.kode_dokter,
                    nama_dokter:          item.nama_dokter,
                });
            }
        });
    });
</script>