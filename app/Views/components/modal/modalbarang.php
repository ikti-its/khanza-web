<?= view('components/modal/modal-table', [
    'modalId'      => 'modalBarang',
    'modalTitle'   => 'Pilih Barang',
    'headers'      => ['Kode', 'Nama Barang', 'Satuan', 'Stok'],
    'tableId'      => 'barangTable',
    'searchInputs' => [
        ['id' => 'searchKodeBarang', 'placeholder' => 'Cari kode barang...'],
        ['id' => 'searchNamaBarang', 'placeholder' => 'Cari nama barang...'],
    ],
    'actions' => [
        ['type' => 'button', 'text' => 'Refresh', 'onclick' => 'open_modalBarang()', 'icon' => 'refresh'],
    ],
]) ?>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        initModalList({
            modalId:     'modalBarang',
            tableId:     'barangTable',
            url:         '<?= site_url('inventori-non-medis/barang/modal/list') ?>',
            fields:      ['kode_barang', 'nama_barang', 'nama_satuan', 'stok'],
            searchIds: {
                searchKodeBarang: 'kode_barang',
                searchNamaBarang: 'nama_barang',
            },
            rowsPerPage: 10,
            onSelect: (item) => {
                autofillFields({
                    id_barang:         item.id_barang,
                    id_barang_display: item.nama_barang,
                    nama_satuan:       item.nama_satuan,
                    kode_barang:       item.kode_barang,
                    stok_sistem:       item.stok,
                    harga:             item.harga_satuan,
                });
            },
        });
    });
</script>
