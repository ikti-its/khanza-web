<?= view('components/modal/modal-table', [
    'modalId'      => 'modalKecamatan',
    'modalTitle'   => 'Cari Data Kecamatan',
    'headers'      => ['Kecamatan', 'Kota / Kabupaten', 'Provinsi'],
    'tableId'      => 'kecamatanTable',
    'searchInputs' => [
        ['id' => 'searchNamaKecamatan', 'placeholder' => 'Cari nama kecamatan...'],
        ['id' => 'searchKota',          'placeholder' => 'Cari Kota/Kabupaten...'],
    ],
    'actions' => [
        ['type' => 'button', 'text' => 'Refresh', 'onclick' => 'open_modalKecamatan()', 'icon' => 'refresh'],
        ['type' => 'link', 'text' => 'Tambah', 'href' => '/lokasi/kecamatan/tambah', 'icon' => 'plus']
    ]
]) ?>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        initModalList({
            modalId:     'modalKecamatan',
            tableId:     'kecamatanTable',
            url:         '<?= site_url('lokasi/kecamatan/modal/list') ?>',
            fields:      ['nama_kecamatan', 'nama_kota', 'nama_provinsi'],
            searchIds: {
                searchNamaKecamatan: 'nama_kecamatan',
                searchKota: 'nama_kota'
            },
            rowsPerPage: 10,
            onSelect: (item) => {
                autofillKecamatan(item);
            }
        });
    });
</script>
