<?= view('components/modal/modal-table', [
    'modalId' => 'modalDokter',
    'modalTitle' => 'Pilih Dokter',
    'headers' => ['Kode Dokter', 'Nama Dokter', 'Spesialis'],
    'tableId' => 'dokterTable',
    'searchInputs' => [
        ['id' => 'searchKodeDokter', 'placeholder' => 'Cari kode dokter...'],
        ['id' => 'searchNamaDokter', 'placeholder' => 'Cari nama dokter...'],
    ],
    'actions' => [
        ['type' => 'button', 'text' => 'Refresh', 'onclick' => 'open_modalDokter()', 'icon' => 'refresh'],
        ['type' => 'link', 'text' => 'Cek Daftar Dokter', 'href' => '/dokter', 'icon' => 'search']
    ]
]) ?>


<script>
    document.addEventListener("DOMContentLoaded", function() {
        initModalList({
            modalId: 'modalDokter',
            tableId: 'dokterTable',
            url: '/modaldokter/list',
            fields: ['kode_dokter', 'nama_dokter', 'spesialis'],
            searchIds: {
                searchKodeDokter: 'kode_dokter',
                searchNamaDokter: 'nama_dokter'
            },
            rowsPerPage: 10,
            onSelect: (item) => {
                autofillFields({
                    kode_dokter: item.kode_dokter,
                    nama_dokter: item.nama_dokter,
                });
            }
        });
    });
</script>