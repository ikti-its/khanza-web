<?= view('components/modal/modal-table', [
    'modalId'      => 'modalBarangMedis',
    'modalTitle'   => 'Pilih Barang / BHP Medis',
    'headers'      => ['Kode', 'Nama Barang'],
    'tableId'      => 'barangMedisTable',
    'searchInputs' => [
        ['id' => 'searchKodeBarangMedis', 'placeholder' => 'Cari kode barang...'],
        ['id' => 'searchNamaBarangMedis', 'placeholder' => 'Cari nama barang...'],
    ],
    'actions' => [
        ['type' => 'button', 'text' => 'Refresh', 'onclick' => 'open_modalBarangMedis()', 'icon' => 'refresh'],
    ],
]) ?>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        initModalList({
            modalId:     'modalBarangMedis',
            tableId:     'barangMedisTable',
            url:         '<?= site_url('inventori-medis/data-barang/modal/list') ?>',
            fields:      ['kode_barang', 'nama'],
            searchIds: {
                searchKodeBarangMedis: 'kode_barang',
                searchNamaBarangMedis: 'nama',
            },
            rowsPerPage: 10,
            onSelect: (item) => {
                autofillBarangMedis({
                    id_barang:   item.id_barang,
                    kode_barang: item.kode_barang,
                    nama_barang: item.nama,
                    harga:       item.h_dasar,
                });
            },
        });
    });
</script>
