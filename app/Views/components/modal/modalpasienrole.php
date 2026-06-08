<?= view('components/modal/modal-table', [
    'modalId'      => 'modalPasienRole',
    'modalTitle'   => 'Pilih Pasien',
    'headers'      => ['No. RM', 'Nama Pasien', 'NIK'],
    'tableId'      => 'pasienRoleTable',
    'searchInputs' => [
        ['id' => 'searchNoRM', 'placeholder' => 'Cari No. RM...'],
        ['id' => 'searchNama', 'placeholder' => 'Cari nama pasien...'],
    ],
    'actions' => [
        ['type' => 'button', 'text' => 'Refresh', 'onclick' => 'open_modalPasienRole()', 'icon' => 'refresh'],
        ['type' => 'link',   'text' => 'Tambah',  'href' => '/masterpasien/tambah-pasien', 'icon' => 'plus'],
    ]
]) ?>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        initModalList({
            modalId:     'modalPasienRole',
            tableId:     'pasienRoleTable',
            url:         '<?= site_url('role/pasien/modal/list') ?>',
            fields:      ['nomor_rm', 'nama', 'nik'],
            searchIds: {
                searchNoRM:  'nomor_rm',
                searchNama:  'nama',
            },
            rowsPerPage: 10,
            onSelect: (item) => {
                document.getElementById('no_rm').value         = item.nomor_rm      ?? '';
                document.getElementById('no_rm_display').value = item.nomor_rm      ?? '';
                document.getElementById('nm_pasien').value     = item.nama          ?? '';
                document.getElementById('tgl_lahir').value     = item.tanggal_lahir ?? '';
                document.getElementById('jenis_kelamin').value = item.jenis_kelamin ?? '';
            }
        });
    });
</script>