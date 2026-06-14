<?= view('components/modal/modal-table', [
    'modalId'      => 'modalPermintaanDarah',
    'modalTitle'   => 'Cari Data Permintaan Darah Pasien',
    'headers'      => ['Nomor Permintaan', 'Nomor RM / Rawat', 'Nama Pasien', 'Status'],
    'tableId'      => 'permintaanDarahTable',
    'searchInputs' => [
        ['id' => 'searchNoPermintaan', 'placeholder' => 'Cari No. Permintaan...'],
        ['id' => 'searchNamaPasien',     'placeholder' => 'Cari nama pasien...'],
    ],
    'actions' => [
        ['type' => 'button', 'text' => 'Refresh', 'onclick' => 'open_modalPermintaanDarah()', 'icon' => 'refresh'],
    ]
]) ?>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        initModalList({
            modalId: 'modalPermintaanDarah',
            tableId: 'permintaanDarahTable',
            url:     '<?= site_url('pelayanan-darah/permintaan-darah/modal/list') ?>',
            fields: ['no_permintaan', 'identitas_rawat', 'nama', 'status'],
            searchIds: {
                searchNoPermintaan: 'no_permintaan',
                searchNamaPasien: 'nama'
            },
            rowsPerPage: 10,
            onSelect: (item) => {
                autofillPermintaanDarah({
                    id_permintaan: item.id_permintaan,
                    no_permintaan: item.no_permintaan
                });
            }
        });
    });
</script>