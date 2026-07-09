<?= view('components/modal/modal-table', [
    'modalId'      => 'modalRegistrasiUgd',
    'modalTitle'   => 'Pilih Data Registrasi Pasien UGD',
    'headers'      => ['No. Registrasi', 'No. RM', 'Nama Pasien', 'Tanggal Registrasi'],
    'tableId'      => 'registrasiUgdTable',
    'searchInputs' => [
        ['id' => 'searchNomorRegUgd', 'placeholder' => 'Cari No. Registrasi...'],
        ['id' => 'searchNamaPasienUgd', 'placeholder' => 'Cari nama pasien...'],
    ],
    'actions' => [
        [
            'type'    => 'button',
            'text'    => 'Refresh',
            'onclick' => 'open_modalRegistrasiUgd()',
            'icon'    => 'refresh',
        ],
        [
            'type'    => 'link',
            'text'    => 'Registrasi Pasien UGD',
            'href'    => '/ugd/registrasi/tambah',
            'icon'    => 'plus'
        ]
    ],
]) ?>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const btnTambahRegistrasiUgd = document.querySelector('#modalRegistrasiUgd a[href="/ugd/registrasi/tambah"]');
        if (btnTambahRegistrasiUgd) {
            btnTambahRegistrasiUgd.removeAttribute('target');
            btnTambahRegistrasiUgd.href = '/unit-gawat-darurat/registrasi/tambah?redirect_to=' + encodeURIComponent(window.location.pathname + window.location.search);
        }

        const modalFilterInput = document.getElementById('modalRegistrasiUgdFilterType');
        let fallbackUrl = '<?= site_url('unit-gawat-darurat/registrasi/modal/list') ?>';
        
        if (modalFilterInput && modalFilterInput.value !== '') {
            fallbackUrl += '?filter=' + modalFilterInput.value;
        }
        
        initModalList({
            modalId:     'modalRegistrasiUgd',
            tableId:     'registrasiUgdTable',
            url:         fallbackUrl,
            fields:      ['nomor_reg', 'nomor_rm', 'nama_pasien', 'tanggal_reg'],
            searchIds: {
                searchNomorRegUgd:   'nomor_reg',
                searchNamaPasienUgd: 'nama_pasien',
            },
            rowsPerPage: 10,
            onSelect: (item) => {
                autofillRegistrasiUgd(item);
            }
        });
    });
</script>