<?= view('components/modal/modal-table', [
    'modalId'      => 'modalPemohon',
    'modalTitle'   => 'Cari Data Pemohon',
    'headers'      => ['Nama', 'Deskripsi'],
    'tableId'      => 'pemohonTable',
    'searchInputs' => [
        ['id' => 'searchNamaPemohon', 'placeholder' => 'Cari nama pemohon...'],
        ['id' => 'searchDeskripsiPemohon', 'placeholder' => 'Cari deskripsi...'],
    ],
    'actions' => [
        ['type' => 'button', 'text' => 'Refresh', 'onclick' => 'open_modalPemohon()', 'icon' => 'refresh'],
    ],
]) ?>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        initModalList({
            modalId:     'modalPemohon',
            tableId:     'pemohonTable',
            url:         '<?= site_url('role/petugas/modal/list') ?>',
            fields:      ['nama', 'deskripsi'],
            searchIds: {
                searchNamaPemohon:      'nama',
                searchDeskripsiPemohon: 'deskripsi',
            },
            rowsPerPage: 10,
            onSelect: (item) => {
                // Cari field petugas yang ada di halaman (petugas atau petugas_gudang atau id_petugas)
                var targets = ['petugas', 'petugas_gudang', 'id_petugas', 'atasan_logistik'];
                for (var i = 0; i < targets.length; i++) {
                    var el = document.getElementById(targets[i]);
                    if (el) {
                        el.value = item.id_petugas ?? '';
                        var display = document.getElementById(targets[i] + '_display');
                        if (display) display.value = item.nama ?? '';
                        break;
                    }
                }
            },
        });
    });
</script>
