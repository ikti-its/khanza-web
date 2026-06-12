<?= view('components/modal/modal-table', [
    'modalId'      => 'modalRawatInap',
    'modalTitle'   => 'Cari Data Pasien Rawat Inap',
    'headers'      => ['Nomor Rawat', 'No. Rekam Medis', 'Nama Pasien', 'Kamar'],
    'tableId'      => 'rawatInapTable',
    'searchInputs' => [
        ['id' => 'searchNoRawat',   'placeholder' => 'Cari No. Rawat...'],
        ['id' => 'searchNamaPasien', 'placeholder' => 'Cari nama pasien...'],
        ['id' => 'searchKamar',      'placeholder' => 'Cari kamar...'],
    ],
    'actions' => [
        ['type' => 'button', 'text' => 'Refresh', 'onclick' => 'open_modalRawatInap()', 'icon' => 'refresh'],
    ]
]) ?>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        initModalList({
            modalId: 'modalRawatInap',
            tableId: 'rawatInapTable',
            url:     '<?= site_url('rawat-inap/registrasi/modal/list') ?>', // Endpoint list data registrasi rawat inap kelompokmu
            fields:  ['nomor_rawat', 'nomor_rm', 'nama', 'kamar'],
            searchIds: {
                searchNoRawat:   'nomor_rawat',
                searchNamaPasien: 'nama',
                searchKamar:      'kamar'
            },
            rowsPerPage: 10,
            onSelect: (item) => {
                autofillRawatInap({
                    id_rawat_inap: item.id_rawat_inap,
                    nomor_rawat:   item.nomor_rawat,
                    nomor_rm:      item.nomor_rm,
                    nama:          item.nama,
                    kamar:         item.kamar,
                    id_dokter:     item.id_dokter,
                    nama_dokter:   item.nama_dokter
                });
            }
        });
    });
</script>