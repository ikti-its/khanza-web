<?= view('components/modal/modal-table', [
    'modalId'      => 'modalAlamat',
    'modalTitle'   => 'Pilih Alamat',
    'headers'      => ['Kota/Kabupaten', 'Alamat Lengkap'],
    'tableId'      => 'alamatTable',
    'searchInputs' => [
        ['id' => 'searchAlamatKota',    'placeholder' => 'Cari kota/kabupaten...'],
        ['id' => 'searchAlamatLengkap', 'placeholder' => 'Cari alamat...'],
    ],
    'actions' => [
        ['type' => 'button', 'text' => 'Refresh',      'onclick' => 'open_modalAlamat()', 'icon' => 'refresh'],
        ['type' => 'link',   'text' => 'Tambah Alamat', 'href'   => '/lokasi/alamat/tambah', 'icon' => 'plus'],
    ]
]) ?>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const btnTambahAlamat = document.querySelector('#modalAlamat a[href="/lokasi/alamat/tambah"]');
        if (btnTambahAlamat) {
            btnTambahAlamat.removeAttribute('target');
            btnTambahAlamat.href = '/lokasi/alamat/tambah?redirect_to=' + encodeURIComponent(window.location.pathname + window.location.search);
        }
        initModalList({
            modalId:     'modalAlamat',
            tableId:     'alamatTable',
            url:         '<?= site_url('lokasi/alamat/modal/list') ?>',
            fields:      ['nama_kota', 'alamat_lengkap'],
            searchIds: {
                searchAlamatKota:    'nama_kota',
                searchAlamatLengkap: 'alamat_lengkap',
            },
            rowsPerPage: 10,
            onSelect: (item) => {
                autofillAlamatFields(item);
            }
        });
    });
</script>
