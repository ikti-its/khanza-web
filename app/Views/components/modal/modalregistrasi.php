<?= view('components/modal/modal-table', [
    'modalId'      => 'modalRegistrasi',
    'modalTitle'   => 'Pilih Registrasi Pasien',
    'headers'      => ['No. Registrasi', 'No. RM', 'Nama Pasien', 'Tanggal'],
    'tableId'      => 'registrasiTable',
    'searchInputs' => [
        ['id' => 'searchNomorReg',   'placeholder' => 'Cari No. Registrasi...'],
        ['id' => 'searchNamaPasReg', 'placeholder' => 'Cari nama pasien...'],
    ],
    'actions' => [
        [
            'type'    => 'button',
            'text'    => 'Refresh',
            'onclick' => 'open_modalRegistrasi()',
            'icon'    => 'refresh',
        ],
        [
            'type'    => 'link',
            'text'    => 'Registrasi Pasien',
            'href'    => '/rekam-medis/registrasi/tambah',
            'icon'    => 'plus'
        ]
    ],
]) ?>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        initModalList({
            modalId:     'modalRegistrasi',
            tableId:     'registrasiTable',
            url:         '<?= site_url('rekam-medis/registrasi/modal/list') ?>',
            fields:      ['nomor_reg', 'nomor_rm', 'nama', 'tanggal_reg'],
            searchIds: {
                searchNomorReg:   'nomor_reg',
                searchNamaPasReg: 'nama',
            },
            rowsPerPage: 10,
            onSelect: (item) => {
                if (typeof autofillRegistrasi === 'function') {
                    autofillRegistrasi(item);
                }
                // document.getElementById('modalRegistrasi').classList.add('hidden');
            }
        });
    });
</script>