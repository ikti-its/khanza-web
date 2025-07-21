<?= view('components/modal/modal-table', [
    'modalId' => 'modalAsuransi',
    'modalTitle' => 'Pilih Asuransi',
    'headers' => ['Kode Asuransi', 'Tipe Asuransi', 'Nama Asuransi'],
    'tableId' => 'asuransiTable',
    'searchInputs' => [
        ['id' => 'searchKodeAsuransi', 'placeholder' => 'Cari kode asuransi...'],
        ['id' => 'searchNamaAsuransi', 'placeholder' => 'Cari nama asuransi...'],
    ],
    'actions' => [
        ['type' => 'button', 'text' => 'Refresh', 'onclick' => 'open_modalAsuransi()', 'icon' => 'refresh'],
        ['type' => 'link', 'text' => 'Cek Data Asuransi', 'href' => '/asuransi', 'icon' => 'search']
    ]
]) ?>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        initModalList({
            modalId: 'modalAsuransi',
            tableId: 'asuransiTable',
            url: '/modalasuransi/list',
            fields: ['kode_asuransi', 'tipe_asuransi', 'nama_asuransi'],
            searchIds: {
                searchKodeAsuransi: 'kode_asuransi',
                searchNamaAsuransi: 'nama_asuransi'
            },
            rowsPerPage: 10,
            onSelect: (item) => {
                autofillFields({
                    //modal di masterpasien
                    asuransi: item.kode_asuransi,
                    asuransi_display: item.nama_asuransi,

                    //modal di registrasi
                    jenis_bayar: item.kode_asuransi,
                    jenis_bayar_display: item.nama_asuransi
                });
            }
        });
    });
</script>