<?= view('components/modal/modal-table', [
    'modalId'      => 'modalPilihRuangan',
    'modalTitle'   => 'Pilih Ruangan',
    'headers'      => ['Kode', 'Nama Ruangan'],
    'tableId'      => 'pilihRuanganTable',
    'searchInputs' => [
        ['id' => 'searchKodePilihRuangan', 'placeholder' => 'Cari kode ruangan...'],
        ['id' => 'searchNamaPilihRuangan', 'placeholder' => 'Cari nama ruangan...'],
    ],
    'actions' => [
        ['type' => 'button', 'text' => 'Refresh', 'onclick' => 'open_modalPilihRuangan()', 'icon' => 'refresh'],
    ],
]) ?>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        initModalList({
            modalId:     'modalPilihRuangan',
            tableId:     'pilihRuanganTable',
            url:         '<?= site_url('ruangan/ruangan/modal/list') ?>',
            fields:      ['kode_ruangan', 'nama_ruangan'],
            searchIds: {
                searchKodePilihRuangan: 'kode_ruangan',
                searchNamaPilihRuangan: 'nama_ruangan',
            },
            rowsPerPage: 10,
            onSelect: (item) => {
                autofillFields({
                    master_ruangan:         item.id_ruangan,
                    master_ruangan_display: item.nama_ruangan,
                });
            },
        });
    });
</script>
