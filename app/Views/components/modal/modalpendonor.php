<?= view('components/modal/modal-table', [
    'modalId' => 'modalPendonor',
    'modalTitle' => 'Pilih Anggota Pendonor',
    'headers' => ['No. Pendonor', 'Nama Pendonor', 'NIK', 'Tanggal Lahir'],
    'tableId' => 'pendonorTable',
    'searchInputs' => [
        ['id' => 'searchNoPendonor', 'placeholder' => 'Cari No. Pendonor...'],
        ['id' => 'searchNamaPendonor', 'placeholder' => 'Cari nama pendonor...'],
    ],
    'actions' => [
        ['type' => 'button', 'text' => 'Refresh', 'onclick' => 'open_modalPendonor()', 'icon' => 'refresh'],
        ['type' => 'link', 'text' => 'Tambah Pendonor', 'href' => '/role/pendonor/tambah', 'icon' => 'plus']
    ]
]) ?>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const btnTambah = document.querySelector('#modalPendonor a[href="/role/pendonor/tambah"]');
        if (btnTambah) {
            btnTambah.removeAttribute('target');
            btnTambah.href = '/role/pendonor/tambah?redirect_to=' + encodeURIComponent(window.location.pathname + window.location.search);
        }
        initModalList({
            modalId: 'modalPendonor',
            tableId: 'pendonorTable',
            url:     '<?= site_url('role/pendonor/modal/list') ?>',
            fields: ['nomor_pendonor', 'nama', 'nik', 'tanggal_lahir'],
            searchIds: {
                searchNoPendonor: 'nomor_pendonor',
                searchNamaPendonor: 'nama'
            },
            rowsPerPage: 10,
            onSelect: (item) => {
                autofillFields({
                    id_pendonor: item.id_pendonor,
                    nomor_pendonor: item.nomor_pendonor,
                    nama: item.nama,
                    nik: item.nik,
                    jenis_kelamin: item.nama_jenis_kelamin,
                    tanggal_lahir: item.tanggal_lahir,
                    golongan_darah: item.nama_golongan_darah,
                    rhesus:item.kode_rhesus
                });
            }
        });
    });
</script>