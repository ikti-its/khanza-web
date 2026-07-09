<?= view('components/modal/modal-table', [
    'modalId'      => 'modalPengajuan',
    'modalTitle'   => 'Pilih Pengajuan',
    'headers'      => ['No. Pengajuan', 'Tanggal', 'Pengaju'],
    'tableId'      => 'pengajuanTable',
    'searchInputs' => [
        ['id' => 'searchNoPengajuan', 'placeholder' => 'Cari no. pengajuan...'],
        ['id' => 'searchNamaPengaju', 'placeholder' => 'Cari nama pengaju...'],
    ],
    'actions' => [
        ['type' => 'button', 'text' => 'Refresh', 'onclick' => 'open_modalPengajuan()', 'icon' => 'refresh'],
    ],
]) ?>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        initModalList({
            modalId:     'modalPengajuan',
            tableId:     'pengajuanTable',
            url:         '<?= site_url('inventori-non-medis/pengajuan-barang/modal/list') ?>',
            fields:      ['no_pengajuan', 'tanggal', 'nama'],
            searchIds: {
                searchNoPengajuan: 'no_pengajuan',
                searchNamaPengaju: 'nama',
            },
            rowsPerPage: 10,
            onSelect: (item) => {
                autofillFields({
                    id_pengajuan:         item.id_pengajuan,
                    id_pengajuan_display: item.no_pengajuan,
                });
            },
        });
    });
</script>
