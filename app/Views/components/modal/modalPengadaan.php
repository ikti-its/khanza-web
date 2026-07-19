<?= view('components/modal/modal-table', [
    'modalId'      => 'modalPengadaan',
    'modalTitle'   => 'Pilih Pengadaan',
    'headers'      => ['No. Pengadaan', 'Tanggal', 'Suplier', 'Total Harga'],
    'tableId'      => 'pengadaanTable',
    'searchInputs' => [
        ['id' => 'searchNoPengadaan', 'placeholder' => 'Cari no. pengadaan...'],
        ['id' => 'searchNamaSuplier', 'placeholder' => 'Cari suplier...'],
    ],
    'actions' => [
        ['type' => 'button', 'text' => 'Refresh', 'onclick' => 'open_modalPengadaan()', 'icon' => 'refresh'],
    ],
]) ?>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        initModalList({
            modalId:     'modalPengadaan',
            tableId:     'pengadaanTable',
            url:         '<?= site_url('inventori-non-medis/pengadaan-barang/modal/list') ?>?mode=available',
            fields:      ['no_pengadaan', 'tanggal', 'nama_suplier', 'total_harga_fmt'],
            searchIds: {
                searchNoPengadaan: 'no_pengadaan',
                searchNamaSuplier: 'nama_suplier',
            },
            rowsPerPage: 10,
            onSelect: (item) => {
                autofillFields({
                    id_pengadaan:         item.id_pengadaan,
                    id_pengadaan_display: item.no_pengadaan,
                });
                // trigger load items after selection
                if (typeof loadPengadaanItems === 'function') {
                    loadPengadaanItems(item.id_pengadaan);
                }
            },
        });
    });
</script>
