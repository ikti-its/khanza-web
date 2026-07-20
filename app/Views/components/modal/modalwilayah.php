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
        const btnTambahWilayah = document.querySelector('#modalWilayah a[href="/lokasi/desa/tambah"]');
        if (btnTambahWilayah) {
            btnTambahWilayah.removeAttribute('target');
            btnTambahWilayah.href = '/lokasi/desa/tambah?redirect_to=' + encodeURIComponent(window.location.pathname + window.location.search);
        }
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
            serverSearch: true,
            rowsPerPage: 10,
            onSelect: (item) => {
                autofillWilayah(item);
            }
        });
    });
</script>