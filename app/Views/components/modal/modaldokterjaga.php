<?= view('components/modal/modal-table', [
    'modalId' => 'modalDokterJaga',
    'modalTitle' => 'Pilih Dokter Jaga',
    'headers' => ['Kode Dokter', 'Nama Dokter', 'Poliklinik'],
    'tableId' => 'dokterJagaTable',
    'searchInputs' => [
        ['id' => 'searchNama', 'placeholder' => 'Cari nama dokter...'],
        ['id' => 'searchPoli', 'placeholder' => 'Cari poliklinik...'],
    ],
    'actions' => [
        ['type' => 'button', 'text' => 'Refresh', 'onclick' => 'open_modalDokterJaga()', 'icon' => 'refresh'],
        ['type' => 'link', 'text' => 'Cek Dokter Jaga', 'href' => '/dokterjaga', 'icon' => 'search']
    ]
]) ?>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        initModalList({
            modalId: 'modalDokterJaga',
            tableId: 'dokterJagaTable',
            url: '/modaldokterjaga/list',
            fields: ['kode_dokter', 'nama_dokter', 'poliklinik'],
            searchIds: {
                searchNama: 'nama_dokter',
                searchPoli: 'poliklinik'
            },
            rowsPerPage: 10,
            onSelect: (item) => {
                autofillFields({
                    kode_dokter: item.kode_dokter,
                    nama_dokter: item.nama_dokter,
                    poliklinik: item.poliklinik
                });
            }
        });
    });
</script>