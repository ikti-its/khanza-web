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
                    nama_pasien: item.nm_pasien,
                    jenis_kelamin: item.jk,
                    umur: item.umur,
                    alamat_pj: item.alamatpj,
                    no_telp: item.no_tlp,
                    no_asuransi: item.no_asuransi,

                    // modal di registrasi
                    jenis_bayar: item.asuransi,
                    jenis_bayar_display: item.asuransi,
                });
            }
        });

    });
</script>