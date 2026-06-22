<?= view('components/modal/modal-table', [
    'modalId'      => 'modalRegistrasiUgd',
    'modalTitle'   => 'Pilih Data Registrasi Kunjungan UGD Pasien',
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
        ]
    ],
]) ?>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        initModalList({
            modalId:     'modalRegistrasiUgd',
            tableId:     'registrasiUgdTable',
            url:         '<?= site_url('ugd/registrasi/modal/list') ?>',
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