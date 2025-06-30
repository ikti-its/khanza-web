<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<!-- Table Section -->
<div class="max-w-[85rem] py-6 lg:py-3 mx-auto">
    <!-- <div class="max-w-[85rem] w-full py-6 lg:py-3"> -->
    <!-- Card -->
    <div class="flex flex-col">
        <div class="-m-1.5 overflow-y-hidden">
            <div class="sm:px-20 min-w-full inline-block align-middle">
                <div class="p-5 bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-slate-900 dark:border-gray-700">

                    <!-- Header -->
                    <div class="py-1 flex justify-between items-center border-gray-200 dark:border-gray-700">
                        <div>
                        <?php if (!empty($resepobatracikan_data)) : ?>
                            <div class="mb-4 text-xl font-black text-gray-800 dark:text-gray-200 space-y-1">
                                <div class="flex">
                                    <span class="w-48">Resep Obat Racikan</span>
                                </div>
                            </div>
                        <?php endif; ?>

                        </div>
                        <div class="flex gap-x-6 justify-center items-center">
                            <div class="relative">
                                <?= view('components/notif/icon') ?>

                                <!-- Notification Pop-up -->
                                <div id="notif-popup" class="absolute right-0 mt-2 w-[30rem] overflow-y-auto z-[2] bg-white rounded-lg shadow-lg hidden">
                                    <?= view('components/notif/notif') ?>
                                    <div>
                                        <div id="stok-content" class="max-h-[15rem] overflow-y-auto">

                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="h-[1.375rem] border-r-4 bg-[#DCDCDC]"></div>
                            <?php if (!empty($resepobatracikan_data)) : ?>
                                <?php $nomor_rawat = $resepobatracikan_data['nomor_rawat'] ?? ''; ?>
                                <div>
                                    <a href="<?= base_url('resepobat/tambah') . '?nomor_rawat=' . $nomor_rawat ?>"
                                    class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-[#0A2D27] text-[#ACF2E7] hover:bg-[#13594E] disabled:opacity-50 disabled:pointer-events-none dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                                        <svg class="flex-shrink-0 size-3" xmlns="http://www.w3.org/2000/svg" width="16" height="1" viewBox="0 0 16 16" fill="none">
                                            <path d="M2.63452 7.50001L13.6345 7.5M8.13452 13V2" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                                        </svg>
                                        Tambah
                                    </a>
                                </div>
                            <?php endif; ?>
                            <?= view('components/header/audit_button', [
                                'link' => '/resepobat/audit'
                            ]) ?>
                        </div>
                    </div>

                    <!-- End Header -->
                    <?php
                        echo view('components/header/search_bar');
                        
                        $modul_path = '/resepobat';
                        $tabel    = $resepobatracikan_data;
                        $kolom_id = 'no_racik';
                        $aksi = [
                            'cetak'    => false,
                            'tindakan' => false,
                            'detail'   => true,
                            'ubah'     => true,
                            'hapus'    => true,
                        ];
                        $konfig = [
                            // [visible, Display, Kolom, Jenis, Required, *Opsi]
                            [1, 'Nomor Resep' , 'no_resep'    , 'indeks'],
                            [1, 'Nomor Racik' , 'no_racik'    , 'indeks'],                            
                            [0, 'Kode Barang' , 'kode_brng'   , 'indeks'],
                            [1, 'Nama Barang' , 'nama_barang' , 'teks'],
                            [1, 'Nama Racikan', 'nama_racik'  , 'teks'],
                            [0, 'Satuan'      , 'kode_sat'    , 'teks'],
                            [0, 'Harga'       , 'kelas1'      , 'uang'], 
                            [0, 'Jenis Obat'  , 'kdjns'       , 'status'],
                            [0, 'Stok'        , 'stokminimal' , 'jumlah'], 
                            [0, 'Kps'         , 'kapasitas'   , 'jumlah'], 
                            [0, 'P1'          , 'p1'          , 'teks'], 
                            [0, 'P2'          , 'p2'          , 'teks'],
                            [0, 'Kandungan'   , 'kandungan'   , 'teks'], 
                            [1, 'Kode Racik'  , 'kd_racik'    , 'indeks'],
                            [1, 'Jumlah Racik', 'jml_dr'      , 'jumlah'],
                            [1, 'Jumlah'      , 'jml'         , 'jumlah'],
                            [1, 'Aturan Pakai', 'aturan_pakai', 'teks'],
                            [1, 'Keterangan'  , 'keterangan'  , 'teks'],
                        ];
                        echo view('components/tabel/data', [
                            'modul_path' => $modul_path,
                            'tabel'      => $tabel,
                            'kolom_id'   => $kolom_id,
                            'konfig'     => $konfig,
                            'aksi'       => $aksi
                        ]);
                        
                        echo view('components/footer/footer', [
                            'meta_data'  => $meta_data,
                            'modul_path' => $modul_path
                        ]);      
                    ?>

                    <!-- Table -->
                    <div class="overflow-x-auto w-full">                       
                    <table id="myTable" class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        
                        
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    
                            <?php foreach ($resepobatracikan_data as $i => $resepobatracikan) : ?>
                                <div id="hs-vertically-centered-scrollable-modal-<?= $resepobatracikan['no_racik'] ?>" class="hs-overlay hidden size-full fixed top-0 start-0 z-[80] pointer-events-none">
                                    <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto h-[calc(100%-3.5rem)] min-h-[calc(100%-3.5rem)] flex items-center ">
                                        <div class="overflow-y-auto w-full max-h-full flex flex-col bg-white border shadow-sm rounded-xl pointer-events-auto dark:bg-neutral-800 dark:border-neutral-700 dark:shadow-neutral-700/70">                                                                                                     
                                            <a href="/resepobat/cetak/<?= $resepobatracikan['no_racik'] ?>" 
                                                class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-800"
                                                data-hs-overlay="#hs-vertically-centered-scrollable-modal-<?= $resepobatracikan['no_racik'] ?>">
                                                Cetak Surat
                                            </a>
                                        </div>
                                    </div>
                                    <div class="flex justify-end items-center gap-x-2 py-3 px-4 border-t dark:border-neutral-700">
                                    </div>
                                </div>
                                <tr>
                                    <!-- <td class="h-px w-64 whitespace-nowrap">
                                        <div class="px-6 py-3">
                                            <a href="<?= base_url('resepdokter/' . $resepobatracikan['no_racik']) ?>" class="text-center block text-sm font-semibold text-gray-800 cursor-pointer dark:text-gray-200 hover:underline">
                                                <?= $resepobatracikan['no_racik'] ?>
                                            </a>
                                        </div>
                                    </td>

                                    
                                    <td class="h-px w-64 whitespace-nowrap">
                                        <div class="px-6 py-3">
                                            <a href="<?= base_url('resepobat/' . ($resepobatracikan['no_rawat'] ?? 'N/A')) ?>" class="text-center block text-sm font-semibold text-gray-800 cursor-pointer dark:text-gray-200 hover:underline">
                                                <?= $resepobatracikan['kd_racik'] ?? 'N/A' ?>
                                            </a>
                                        </div>
                                    </td> -->
                                    

                                    <td class="size-px whitespace-nowrap">
                                        <div class="px-3 py-1.5 text-center inline-flex">
                                            <div class="px-3 py-1.5">
                                                <button
                                                    type="button"
                                                    class="btn btn-info btn-tindakan gap-x-1 text-sm font-semibold"
                                                    data-nomor-reg="<?= $resepobatracikan['no_racik'] ?>"
                                                    data-hs-overlay="#hs-vertically-centered-scrollable-modal-<?= $resepobatracikan['no_racik'] ?>">
                                                    Lihat Detail
                                                </button>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <?php foreach ($resepobatracikan_data as $i => $resepobatracikan) : ?>
                            <div id="hs-vertically-centered-scrollable-modal-<?= $resepobatracikan['no_racik'] . '-' . $i ?>" class="hs-overlay hidden fixed top-0 start-0 z-[80] w-full h-full bg-gray-800 bg-opacity-50 overflow-y-auto">
                                <div class="mt-20 mx-auto w-full max-w-lg p-6 bg-white dark:bg-neutral-800 rounded shadow">
                                    <div class="flex justify-between items-center border-b pb-2">
                                        <h3 class="text-lg font-bold"><?= $resepobatracikan['no_racik'] ?></h3>
                                        <button data-hs-overlay="#hs-vertically-centered-scrollable-modal-<?= $resepobatracikan['no_racik'] . '-' . $i ?>" class="text-gray-600 dark:text-white hover:text-red-600">
                                            &times;
                                        </button>
                                    </div>
                                    <div class="mt-4 space-y-3">
                                        <div><strong>Nomor Rawat:</strong> <?= $resepobatracikan['no_racik'] ?></div>
                                        <!-- <div><strong>Nomor RM:</strong> <?= $resepobatracikan['no_racik'] ?></div> -->
                                        <div><strong>Dokter:</strong> <?= $resepobatracikan['no_racik'] ?? 'N/A' ?></div>
                                        <div><strong>Tanggal:</strong> <?= $resepobatracikan['no_racik'] ?></div>
                                        <div><strong>Jam:</strong> <?= $resepobatracikan['no_racik'] ?></div>
                                    </div>
                                    <div class="mt-6 text-end">
                                        <button class="text-sm text-gray-700 bg-gray-200 px-3 py-2 rounded hover:bg-gray-300 dark:text-white dark:bg-gray-700 dark:hover:bg-gray-600" data-hs-overlay="#hs-vertically-centered-scrollable-modal-<?= $resepobatracikan['no_racik'] . '-' . $i ?>">Tutup</button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </table>
                    </div>

                    <!-- End Table -->
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Card -->

<!-- End Table Section -->
<script>
    function validateResep(noResep) {
    if (!confirm("Yakin ingin memvalidasi resep ini?")) return;

    fetch(`http://127.0.0.1:8080/v1/resep-obat/${noResep}/validasi`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer <?= session('jwt_token') ?>'
        },
        body: JSON.stringify({ validasi: true })
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            alert('Berhasil divalidasi');
            location.reload(); // ⏮ Refresh to see updated status
        } else {
            alert('Gagal memvalidasi resep');
        }
    })
    .catch(err => {
        console.error(err);
        alert('Terjadi kesalahan');
    });
}
</script>
<?= $this->endSection(); ?>