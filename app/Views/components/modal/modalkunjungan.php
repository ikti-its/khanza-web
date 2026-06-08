<?= view('components/modal/modal-table', [
    'modalId'      => 'modalKunjungan',
    'modalTitle'   => 'Cari Data Kunjungan Donor',
    'headers'      => ['Nomor Kunjungan', 'Nomor Pendonor', 'Nama Pendonor'],
    'tableId'      => 'kunjunganTable',
    'searchInputs' => [
        ['id' => 'searchNoKunjungan', 'placeholder' => 'Cari No. Kunjungan...'],
        ['id' => 'searchNamaKunjungan', 'placeholder' => 'Cari nama pendonor...'],
    ],
    'actions' => [
        ['type' => 'button', 'text' => 'Refresh', 'onclick' => 'open_modalKunjunganPasien()', 'icon' => 'refresh'],
    ]
]) ?>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        initModalList({
            modalId: 'modalKunjungan',
            tableId: 'kunjunganTable',
            url:     '<?= site_url('donor/kunjungan/modal/list') ?>',
            fields: ['nomor_kunjungan', 'nomor_pendonor', 'nama'],
            searchIds: {
                searchNoKunjungan: 'nomor_kunjungan',
                searchNamaKunjungan: 'nama'
            },
            rowsPerPage: 10,
            onSelect: (item) => {
                autofillKunjungan({
                    id_kunjungan: item.id_kunjungan,
                    nomor_kunjungan: item.nomor_kunjungan,
                    nomor_pendonor: item.nomor_pendonor,
                    nama: item.nama
                });
            }
        });
    });
</script>