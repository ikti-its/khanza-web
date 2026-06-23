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
        ['type' => 'link',   'text' => 'Tambah',  'href' => '/role/pasien/tambah', 'icon' => 'plus'],
    ]
]) ?>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const btnTambahPasien = document.querySelector('#modalPasienRole a[href="/role/pasien/tambah"]');
        if (btnTambahPasien) {
            btnTambahPasien.removeAttribute('target');
            btnTambahPasien.href = '/role/pasien/tambah?redirect_to=' + encodeURIComponent(window.location.pathname + window.location.search);
        }
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
                const el = (id) => document.getElementById(id);
                if (el('id_pasien'))    el('id_pasien').value    = item.id_pasien      ?? '';
                if (el('no_rm'))        el('no_rm').value        = item.nomor_rm       ?? '';
                if (el('no_rm_display'))el('no_rm_display').value= item.nomor_rm       ?? '';
                if (el('nm_pasien'))    el('nm_pasien').value    = item.nama           ?? '';
                if (el('tgl_lahir'))    el('tgl_lahir').value    = item.tanggal_lahir  ?? '';
                if (el('jenis_kelamin'))el('jenis_kelamin').value = item.jenis_kelamin ?? '';
            }
        });
    });
</script>