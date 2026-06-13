<?= view('components/modal/modal-table', [
    'modalId'      => 'modalPermintaanRad',
    'modalTitle'   => 'Pilih Permintaan Radiologi',
    'headers'      => ['No. Permintaan', 'No. Registrasi', 'Nama Pasien', 'Tanggal', 'Status'],
    'tableId'      => 'permintaanRadTable',
    'searchInputs' => [
        ['id' => 'searchNoPermintaan', 'placeholder' => 'Cari no. permintaan...'],
        ['id' => 'searchNamaPasien',   'placeholder' => 'Cari nama pasien...'],
    ],
    'actions' => [
        [
            'type'    => 'button',
            'text'    => 'Refresh',
            'onclick' => 'open_modalPermintaanRad()',
            'icon'    => 'refresh',
        ],
    ],
]) ?>

<script>
    function open_modalPermintaanRad() {
        initModalList({
            modalId:     'modalPermintaanRad',
            tableId:     'permintaanRadTable',
            url:         '<?= site_url('radiologi/permintaan-radiologi/modal/list?status=2') ?>',
            fields:      ['no_permintaan', 'nomor_reg', 'nama', 'tgl_jam_permintaan', 'nama_status'],
            searchIds: {
                searchNoPermintaan: 'no_permintaan',
                searchNamaPasien:   'nama',
            },
            rowsPerPage: 10,
            onSelect: (item) => {
                if (typeof autofillPermintaanRad === 'function') {
                    autofillPermintaanRad(item);
                }
                document.getElementById('modalPermintaanRad').classList.add('hidden');
            }
        });

        document.getElementById('modalPermintaanRad').classList.remove('hidden');
    }

    document.addEventListener('DOMContentLoaded', function () {
        ['searchNoPermintaan', 'searchNamaPasien'].forEach(id => {
            const el = document.getElementById(id);
            if (el) el.addEventListener('input', open_modalPermintaanRad);
        });
    });
</script>