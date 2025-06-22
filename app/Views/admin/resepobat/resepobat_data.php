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
                        <?php if (!empty($resepobat_data)) : ?>
                            <div class="mb-4 text-xl font-black text-gray-800 dark:text-gray-200 space-y-1">
                                <div class="flex">
                                    <span class="w-48">Resep Obat</span>
                                </div>
                            </div>
                        <?php endif; ?>

                        </div>
                        <div class="flex gap-x-6 justify-center items-center">
                            <div class="relative">
                                <?= view('components/notif_icon') ?>

                                <!-- Notification Pop-up -->
                                <div id="notif-popup" class="absolute right-0 mt-2 w-[30rem] overflow-y-auto z-[2] bg-white rounded-lg shadow-lg hidden">
                                    <?= view('components/notif') ?>
                                    <div>
                                        <div id="stok-content" class="max-h-[15rem] overflow-y-auto">

                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="h-[1.375rem] border-r-4 bg-[#DCDCDC]"></div>
                            <?php if (!empty($resepobat_data)) : ?>
                                <?php $no_rawat = $resepobat[0]['nomor_rawat'] ?? ''; ?>
                                <div>
                                    <a href="<?= base_url('resepobat/tambah/' . $no_rawat) ?>"
                                    class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-[#0A2D27] text-[#ACF2E7] hover:bg-[#13594E] disabled:opacity-50 disabled:pointer-events-none dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                                        <svg class="flex-shrink-0 size-3" xmlns="http://www.w3.org/2000/svg" width="16" height="1" viewBox="0 0 16 16" fill="none">
                                            <path d="M2.63452 7.50001L13.6345 7.5M8.13452 13V2" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                                        </svg>
                                        Tambah
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- End Header -->
                    <?php
                        echo view('components/search_bar');
                        
                        $modul_path = '/resepobat';
                        $tabel    = $resepobat_data;
                        $kolom_id = 'no_resep';
                        $aksi = [
                            'cetak'    => false,
                            'tindakan' => false,
                            'detail'   => true,
                            'ubah'     => false,
                            'hapus'    => true,
                        ];
                        $konfig = [
                            // [visible, Display, Kolom, Jenis]
                            [1, 'Nomor Resep'   , 'no_resep'     , 'indeks'],
                            [1, 'Tanggal Resep' , 'tgl_peresepan', 'tanggal'],
                            [0, 'Jam Peresepan' , 'jam_peresepan', 'jam'],
                            [1, 'Nomor Rawat'   , 'no_rawat'     , 'indeks'],
                            // [0, 'Nomor RM'      , 'nomor_rm'     , 'indeks'],
                            // [0, 'Pasien'        , 'nama_pasien'  , 'nama'],
                            [1, 'Dokter Peresep', 'kd_dokter'    , 'indeks'],
                            [1, 'Status'        , 'status'       , 'status'],
                            [0, 'Tanggal Validasi'  , 'tgl_perawatan' , 'tanggal'],
                            [0, 'Jam Validasi'      , 'jam'           , 'jam'],
                            [0, 'Tanggal Penyerahan', 'tgl_penyerahan', 'tanggal'],
                            [0, 'Jam Penyerahan'    , 'jam_penyerahan', 'jam']
                        ];
                        echo view('components/tabel', [
                            'modul_path' => $modul_path,
                            'tabel'      => $tabel,
                            'kolom_id'   => $kolom_id,
                            'konfig'     => $konfig,
                            'aksi'       => $aksi
                        ]);
                        
                        echo view('components/footer', [
                            'meta_data'  => $meta_data,
                            'modul_path' => $modul_path
                        ]);      
                    ?>

                    <!-- Table -->
                    <div class="overflow-x-auto w-full">                       
                    <table id="myTable" class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    
                            <?php foreach ($resepobat_data as $i => $resepobat) : ?>
                                <div id="hs-vertically-centered-scrollable-modal-<?= $resepobat['no_resep'] ?>" class="hs-overlay hidden size-full fixed top-0 start-0 z-[80] pointer-events-none">
                                    <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto h-[calc(100%-3.5rem)] min-h-[calc(100%-3.5rem)] flex items-center ">
                                        <div class="overflow-y-auto w-full max-h-full flex flex-col bg-white border shadow-sm rounded-xl pointer-events-auto dark:bg-neutral-800 dark:border-neutral-700 dark:shadow-neutral-700/70">   
                                            <div class="p-4">
                                                <div class="space-y-4">
                                                <div>
                                                    <div class="mb-5 sm:block">
                                                        <?php if ($resepobat['validasi']): ?>
                                                            <span class="text-green-600 font-semibold">sudah divalidasi</span>
                                                        <?php else: ?>
                                                            <button
                                                                class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-800"
                                                                onclick="validateResep('<?= $resepobat['no_resep'] ?>')"
                                                            >
                                                                Validasi
                                                            </button>
                                                        <?php endif; ?>
                                                    </div>
                                                    <a href="/resepobat/cetak/<?= $resepobat['no_resep'] ?>" 
                                                        class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-800"
                                                        data-hs-overlay="#hs-vertically-centered-scrollable-modal-<?= $resepobat['no_resep'] ?>">
                                                        Cetak Surat
                                                    </a>
                                                </div>

                                                </div>
                                                <div class="flex justify-end items-center gap-x-2 py-3 px-4 border-t dark:border-neutral-700">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <tr>
                                    <!-- <td class="h-px w-64 whitespace-nowrap">
                                        <div class="px-6 py-3">
                                            <a href="<?= base_url('resepdokter/' . $resepobat['no_resep']) ?>" class="text-center block text-sm font-semibold text-gray-800 cursor-pointer dark:text-gray-200 hover:underline">
                                                <?= $resepobat['no_resep'] ?>
                                            </a>
                                        </div>
                                    </td> -->
                                    <!-- <td class="h-px w-64 whitespace-nowrap">
                                        <div class="px-6 py-3">
                                            <a href="<?= base_url('resepobat/' . ($resepobat['no_rawat'] ?? 'N/A')) ?>" class="text-center block text-sm font-semibold text-gray-800 cursor-pointer dark:text-gray-200 hover:underline">
                                                <?= $resepobat['no_rawat'] ?? 'N/A' ?>
                                            </a>
                                        </div>
                                    </td> -->
                                    <?php 
                                        if ($resepobat['validasi'] === true || $resepobat['validasi'] === 1 || $resepobat['validasi'] === 'true'){
                                            $statusText = 'Sudah Divalidasi';
                                        } else {
                                            $statusText = 'Belum Divalidasi';
                                        }
                                        $resepobat['status'] = $statusText;
                                    ?>

                                    <td class="size-px whitespace-nowrap">
                                        <div class="px-3 py-1.5 text-center inline-flex">
                                            <div class="px-3 py-1.5">
                                                <button
                                                    type="button"
                                                    class="btn btn-info btn-tindakan gap-x-1 text-sm font-semibold"
                                                    data-nomor-reg="<?= $resepobat['no_resep'] ?>"
                                                    data-hs-overlay="#hs-vertically-centered-scrollable-modal-<?= $resepobat['no_resep'] ?>">
                                                    Lihat Detail
                                                </button>
                                            </div>
                                            <!-- <div class="px-3 py-1.5"> -->
                                                <!-- <?php if ($resepobat['validasi'] === true || $resepobat['validasi'] === 1 || $resepobat['validasi'] === 'true'): ?>
                                                    <span class="text-gray-400 text-sm font-semibold cursor-not-allowed" title="Sudah divalidasi, tidak dapat diubah">Ubah</span>
                                                <?php else: ?>
                                                    <a href="/resepobat/edit/<?= $resepobat['no_resep'] ?>" class="gap-x-1 text-sm text-blue-600 decoration-2 hover:underline font-semibold">Ubah</a>
                                                <?php endif; ?> -->
    
                                               
                                            <!-- </div> -->
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <?php foreach ($resepobat_data as $i => $resepobat) : ?>
                            <div id="hs-vertically-centered-scrollable-modal-<?= $resepobat['no_resep'] . '-' . $i ?>" class="hs-overlay hidden fixed top-0 start-0 z-[80] w-full h-full bg-gray-800 bg-opacity-50 overflow-y-auto">
                                <div class="mt-20 mx-auto w-full max-w-lg p-6 bg-white dark:bg-neutral-800 rounded shadow">
                                    <div class="flex justify-between items-center border-b pb-2">
                                        <h3 class="text-lg font-bold"><?= $resepobat['no_resep'] ?></h3>
                                        <button data-hs-overlay="#hs-vertically-centered-scrollable-modal-<?= $resepobat['no_resep'] . '-' . $i ?>" class="text-gray-600 dark:text-white hover:text-red-600">
                                            &times;
                                        </button>
                                    </div>
                                    <div class="mt-4 space-y-3">
                                        <div><strong>Nomor Rawat:</strong> <?= $resepobat['no_resep'] ?></div>
                                        <!-- <div><strong>Nomor RM:</strong> <?= $resepobat['no_resep'] ?></div> -->
                                        <div><strong>Dokter:</strong> <?= $resepobat['no_resep'] ?? 'N/A' ?></div>
                                        <div><strong>Tanggal:</strong> <?= $resepobat['no_resep'] ?></div>
                                        <div><strong>Jam:</strong> <?= $resepobat['no_resep'] ?></div>
                                    </div>
                                    <div class="mt-6 text-end">
                                        <button class="text-sm text-gray-700 bg-gray-200 px-3 py-2 rounded hover:bg-gray-300 dark:text-white dark:bg-gray-700 dark:hover:bg-gray-600" data-hs-overlay="#hs-vertically-centered-scrollable-modal-<?= $resepobat['no_resep'] . '-' . $i ?>">Tutup</button>
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