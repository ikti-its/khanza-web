<?= view('components/modal/modal-table', [
    'modalId'      => 'modalWilayah',
    'modalTitle'   => 'Cari Data Wilayah Administratif (Kelurahan/Desa)',
    'headers'      => ['Kelurahan / Desa', 'Kecamatan', 'Kota / Kabupaten', 'Provinsi'],
    'tableId'      => 'wilayahTable',
    'searchInputs' => [
        ['id' => 'searchNamaDesa',      'placeholder' => 'Cari nama desa/kelurahan...'],
        ['id' => 'searchNamaKecamatan', 'placeholder' => 'Cari Kecamatan...'],
        ['id' => 'searchKota',          'placeholder' => 'Cari Kota/Kabupaten...'],
    ],
    'actions' => [
        ['type' => 'button', 'text' => 'Refresh', 'onclick' => 'open_modalWilayah()', 'icon' => 'refresh'],
        ['type' => 'link', 'text' => 'Tambah', 'href' => '/lokasi/desa/tambah', 'icon' => 'plus']
    ]
]) ?>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        initModalList({
            modalId:     'modalWilayah',
            tableId:     'wilayahTable',
            url:         '<?= site_url('lokasi/desa/modal/list') ?>',
            fields:      ['nama_desa', 'nama_kecamatan', 'nama_kota', 'nama_provinsi'],
            searchIds: {
                searchNamaDesa: 'nama_desa',
                searchNamaKecamatan: 'nama_kecamatan',
                searchKota: 'nama_kota'
            },
            rowsPerPage: 10,
            onSelect: (item) => {
                autofillWilayah(item);
            }
        });
    });
</script>