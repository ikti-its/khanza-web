<?= view('components/modal/modal-table', [
    'modalId'      => 'modalKasusReaktif',
    'modalTitle'   => 'Cari Data Kasus Reaktif',
    'headers'      => ['No. Kasus', 'Tanggal Ditetapkan', 'No. Pengambilan', 'Nama Pendonor', 'Parameter Reaktif'],
    'tableId'      => 'kasusReaktifTable',
    'searchInputs' => [
        ['id' => 'searchNoKasus', 'placeholder' => 'Cari No. Kasus...'],
        ['id' => 'searchNamaPendonor', 'placeholder' => 'Cari nama pendonor...'],
    ],
    'actions' => [
        ['type' => 'button', 'text' => 'Refresh', 'onclick' => 'open_modalKasusReaktif()', 'icon' => 'refresh'],
    ]
]) ?>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        initModalList({
            modalId: 'modalKasusReaktif',
            tableId: 'kasusReaktifTable',
            url:     '<?= site_url('penanganan-donor/kasus-reaktif/modal/list') ?>',
            fields: ['nomor_kasus', 'tanggal_ditetapkan', 'nomor_pengambilan', 'nama_pendonor', 'parameter_reaktif'],
            searchIds: {
                searchNoKasus: 'nomor_kasus',
                searchNamaPendonor: 'nama_pendonor'
            },
            rowsPerPage: 10,
            onSelect: (item) => {
                autofillKasusReaktif(item);
            }
        });
    });
</script>