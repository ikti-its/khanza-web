<?= view('components/modal/modal-table', [
    'modalId'      => 'modalPermintaanLab',
    'modalTitle'   => 'Pilih Permintaan Laboratorium',
    'headers'      => ['No. Permintaan', 'No. Registrasi', 'Nama Pasien', 'Tanggal', 'Status'],
    'tableId'      => 'permintaanLabTable',
    'searchInputs' => [
        ['id' => 'searchNoPermintaanLab', 'placeholder' => 'Cari no. permintaan...'],
        ['id' => 'searchNamaPasienLab',   'placeholder' => 'Cari nama pasien...'],
    ],
    'actions' => [
        [
            'type'    => 'button',
            'text'    => 'Refresh',
            'onclick' => 'open_modalPermintaanLab(_modalPermintaanLabKategori)',
            'icon'    => 'refresh',
        ],
    ],
]) ?>

<script>
    let _modalPermintaanLabKategori = null;

    function open_modalPermintaanLab(idKategori = null) {
        _modalPermintaanLabKategori = idKategori;

        const url = new URL('<?= site_url('laboratorium/permintaan-lab/modal/list') ?>', location.origin);
        url.searchParams.set('status', '2');
        if (idKategori !== null) {
            url.searchParams.set('kategori', idKategori);
        }

        initModalList({
            modalId:     'modalPermintaanLab',
            tableId:     'permintaanLabTable',
            url:         url.toString(),
            fields:      ['no_permintaan', 'nomor_reg', 'nama', 'tgl_jam_permintaan', 'nama_status'],
            searchIds: {
                searchNoPermintaanLab: 'no_permintaan',
                searchNamaPasienLab:   'nama',
            },
            rowsPerPage: 10,
            onSelect: (item) => {
                if (typeof autofillPermintaanLab === 'function') {
                    autofillPermintaanLab(item);
                }
                document.getElementById('modalPermintaanLab').classList.add('hidden');
            }
        });

        document.getElementById('modalPermintaanLab').classList.remove('hidden');
    }

    document.addEventListener('DOMContentLoaded', function () {
        ['searchNoPermintaanLab', 'searchNamaPasienLab'].forEach(id => {
            const el = document.getElementById(id);
            if (el) el.addEventListener('input', () => open_modalPermintaanLab(_modalPermintaanLabKategori));
        });
    });
</script>