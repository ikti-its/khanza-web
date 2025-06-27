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
                        <?php if (!empty($reseppulang_data)) : ?>
                            <div class="mb-4 text-xl font-black text-gray-800 dark:text-gray-200 space-y-1">
                                <div class="flex">
                                    <span class="w-64">Resep Pulang</span>
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
                            <?php if (!empty($reseppulang_data)) : ?>
                                <?php $nomor_rawat = $reseppulang_data['nomor_rawat'] ?? ''; ?>
                                <div>
                                    <a href="<?= base_url('reseppulang/tambah') . '?nomor_rawat=' . $nomor_rawat ?>"
                                    class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-[#0A2D27] text-[#ACF2E7] hover:bg-[#13594E] disabled:opacity-50 disabled:pointer-events-none dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                                        <svg class="flex-shrink-0 size-3" xmlns="http://www.w3.org/2000/svg" width="16" height="1" viewBox="0 0 16 16" fill="none">
                                            <path d="M2.63452 7.50001L13.6345 7.5M8.13452 13V2" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                                        </svg>
                                        Tambah
                                    </a>
                                </div>
                            <?php endif; ?>
                            <?= view('components/audit_button', [
                                'link' => '/reseppulang/audit'
                            ]) ?>
                        </div>
                    </div>
                    <!-- End Header -->
                    <?php
                        echo view('components/search_bar');
                        
                        $modul_path = '/reseppulang';
                        $tabel    = $reseppulang_data;
                        $kolom_id = 'no_rawat';
                        $aksi = [
                            'cetak'    => false,
                            'tindakan' => false,
                            'detail'   => true,
                            'ubah'     => true,
                            'hapus'    => true,
                        ];
                        $konfig = [
                            // [visible, Display, Kolom, Jenis]
                            [1, 'Nomor Rawat'  , 'no_rawat'   , 'indeks'],
                            [1, 'Tanggal Resep', 'tanggal'    , 'tanggal'],
                            [1, 'Jam'          , 'jam'        , 'jam'],
                            [1, 'Pasien'       , 'nama_pasien', 'nama'],
                            [1, 'Obat'         , 'kode_brng'  , 'indeks'],
                            [1, 'Jumlah'       , 'jml_barang' , 'jumlah'],
                            [0, 'Harga'        , 'harga'      , 'uang'],
                            [0, 'Total'        , 'total'      , 'uang'],
                            [0, 'Dosis'        , 'dosis'      , 'jumlah'], 
                            [0, 'No. Batch'    , 'no_batch'   , 'indeks'],
                            [0, 'No. Faktur'   , 'no_faktur'  , 'indeks'],
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
                    
                            <?php foreach ($reseppulang_data as $i => $reseppulang) : ?>
                                <div id="hs-vertically-centered-scrollable-modal-<?= $reseppulang['no_rawat'] ?>" class="hs-overlay hidden size-full fixed top-0 start-0 z-[80] pointer-events-none">
                                    <td class="size-px whitespace-nowrap">
                                        <div class="px-3 py-1.5 text-center inline-flex">
                                            <div class="px-3 py-1.5">
                                            <button
                                                type="button"
                                                class="btn btn-info btn-tindakan gap-x-1 text-sm font-semibold"
                                                data-nomor-reg="<?= $reseppulang['no_rawat'] ?>"
                                                data-hs-overlay="#hs-vertically-centered-scrollable-modal-<?= $reseppulang['no_rawat'] ?>">
                                                Lihat Detail
                                            </button>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <?php foreach ($reseppulang_data as $i => $reseppulang) : ?>
                            <div id="hs-vertically-centered-scrollable-modal-<?= $reseppulang['no_rawat'] . '-' . $i ?>" class="hs-overlay hidden fixed top-0 start-0 z-[80] w-full h-full bg-gray-800 bg-opacity-50 overflow-y-auto">
                                <div class="mt-20 mx-auto w-full max-w-lg p-6 bg-white dark:bg-neutral-800 rounded shadow">
                                    <div class="flex justify-between items-center border-b pb-2">
                                        <h3 class="text-lg font-bold"><?= $reseppulang['no_rawat'] ?></h3>
                                        <button data-hs-overlay="#hs-vertically-centered-scrollable-modal-<?= $reseppulang['no_rawat'] . '-' . $i ?>" class="text-gray-600 dark:text-white hover:text-red-600">
                                            &times;
                                        </button>
                                    </div>
                                    <div class="mt-4 space-y-3">
                                        <div><strong>Nomor Rawat:</strong> <?= $reseppulang['no_rawat'] ?></div>
                                        <!-- <div><strong>Nomor RM:</strong> <?= $reseppulang['no_rawat'] ?></div> -->
                                        <div><strong>Dokter:</strong> <?= $reseppulang['no_rawat'] ?? 'N/A' ?></div>
                                        <div><strong>Tanggal:</strong> <?= $reseppulang['no_rawat'] ?></div>
                                        <div><strong>Jam:</strong> <?= $reseppulang['no_rawat'] ?></div>
                                    </div>
                                    <div class="mt-6 text-end">
                                        <button class="text-sm text-gray-700 bg-gray-200 px-3 py-2 rounded hover:bg-gray-300 dark:text-white dark:bg-gray-700 dark:hover:bg-gray-600" data-hs-overlay="#hs-vertically-centered-scrollable-modal-<?= $reseppulang['no_rawat'] . '-' . $i ?>">Tutup</button>
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
<?= $this->endSection(); ?>