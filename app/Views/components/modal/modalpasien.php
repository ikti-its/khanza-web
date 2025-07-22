<?= view('components/modal/modal-table', [
    'modalId' => 'modalPasien',
    'modalTitle' => 'Pilih Pasien',
    'headers' => ['No. RM', 'Nama Pasien'],
    'tableId' => 'pasienTable',
    'searchInputs' => [
        ['id' => 'searchNoRM', 'placeholder' => 'Cari No. RM...'],
        ['id' => 'searchNamaPasien', 'placeholder' => 'Cari nama pasien...'],
    ],
    'actions' => [
        ['type' => 'button', 'text' => 'Refresh', 'onclick' => 'open_modalPasien()', 'icon' => 'refresh'],
        ['type' => 'link', 'text' => 'Tambah', 'href' => '/masterpasien/tambah-pasien', 'icon' => 'plus']
    ]

]) ?>


<script>
    document.addEventListener("DOMContentLoaded", function() {
        initModalList({
            modalId: 'modalPasien',
            tableId: 'pasienTable',
            url: '/modalpasien/list',
            fields: ['no_rkm_medis', 'nm_pasien'],
            searchIds: {
                searchNoRM: 'no_rkm_medis',
                searchNamaPasien: 'nm_pasien'
            },
            rowsPerPage: 10,
            onSelect: (item) => {
                autofillFields({
                    // general
                    no_rkm_medis: item.no_rkm_medis,
                    nm_pasien: item.nm_pasien,
                    jk: item.jk,
                    tgl_lahir: formatToDateInput(item.tgl_lahir),
                    gol_darah: item.gol_darah,
                    stts_nikah: item.stts_nikah,
                    agama: item.agama,
                    umur: item.umur,
                    no_asuransi: item.no_asuransi,

                    // id di registrasi
                    jenis_kelamin: item.jk,
                    nama_pasien: item.nm_pasien,
                    no_telp: item.no_tlp,
                    jenis_bayar: item.asuransi,
                    jenis_bayar_display: item.asuransi,
                });
            }
        });

    });
</script>