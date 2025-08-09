<?= view('components/modal/modal-table', [
    'modalId' => 'modalInstansi',
    'modalTitle' => 'Pilih Instansi',
    'headers' => ['Kode Instansi', 'Nama Instansi'],
    'tableId' => 'instansiTable',
    'searchInputs' => [
        ['id' => 'searchKodeInstansi', 'placeholder' => 'Cari kode instansi...'],
        ['id' => 'searchNamaInstansi', 'placeholder' => 'Cari nama instansi...'],
    ],
    'actions' => [
        ['type' => 'button', 'text' => 'Refresh', 'onclick' => 'open_modalInstansi()', 'icon' => 'refresh'],
        ['type' => 'link', 'text' => 'Cek Data Instansi', 'href' => '/instansi', 'icon' => 'search']
    ]
]) ?>

<script>
    initModalList({
        modalId: 'modalInstansi',
        tableId: 'instansiTable',
        url: '/modalinstansi/list',
        fields: ['kode_instansi', 'nama_instansi'],
        searchIds: {
            searchKodeInstansi: 'kode_instansi',
            searchNamaInstansi: 'nama_instansi'
        },
        rowsPerPage: 10,
        onSelect: (item) => {
            autofillFields({
                perusahaan_pasien: item.kode_instansi,
                perusahaan_pasien_display: item.nama_instansi
            });
        }
    });
</script>