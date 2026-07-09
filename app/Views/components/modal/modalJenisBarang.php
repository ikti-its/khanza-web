<?= view('components/modal/modal-table', [
    'modalId'      => 'modalJenisBarang',
    'modalTitle'   => 'Pilih Jenis Barang',
    'headers'      => ['Kode', 'Jenis Barang'],
    'tableId'      => 'jenisBarangTable',
    'searchInputs' => [
        ['id' => 'searchNamaJenisBarang', 'placeholder' => 'Cari jenis barang...'],
    ],
    'actions' => [
        ['type' => 'button', 'text' => 'Refresh', 'onclick' => 'open_modalJenisBarang()', 'icon' => 'refresh'],
    ],
]) ?>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        initModalList({
            modalId:     'modalJenisBarang',
            tableId:     'jenisBarangTable',
            url:         '<?= site_url('inventori-non-medis/jenis-barang/modal/list') ?>',
            fields:      ['kode_jenis_barang', 'nama_jenis_barang'],
            searchIds: {
                searchNamaJenisBarang: 'nama_jenis_barang',
            },
            rowsPerPage: 10,
            onSelect: (item) => {
                autofillJenisBarang(item);
            },
        });
    });
</script>
