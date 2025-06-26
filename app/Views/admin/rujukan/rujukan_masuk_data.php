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
                            <h2 class="mb-2 text-xl font-black text-gray-800 dark:text-gray-200">
                                Rujukan Masuk
                            </h2>

                        </div>
                        <div class="flex gap-x-6 justify-center items-center">
                            <div class="relative">
                                <?= view('components/notif_icon') ?>

                                <!-- Notification Pop-up -->
                                <div id="notif-popup" class="absolute right-0 mt-2 w-[30rem] overflow-y-auto z-[2] bg-white rounded-lg shadow-lg hidden">
                                    <?= view('components/notif') ?>
                                    <div>
                                        <div id="stok-content" class="max-h-[15rem] overflow-y-auto">
                                            <?php
                                            $count_notif_stok = 0;
                                            $today = new DateTime();
                                            $today->setTime(0, 0, 0);
                                            if ($count_notif_stok !== 0) {
                                                foreach ($rujukanmasuk_tanpa_params_data as $rujukanmasuk_stok) {
                                                    if ($rujukanmasuk_stok['nomor_rujuk'] <= $rujukanmasuk_stok['nomor_rujuk']) {
                                                        $count_notif_stok++; ?>
                                                        <a href="/datamedis/edit/<?= $rujukanmasuk_stok['nomor_rujuk'] ?>" class="p-4 flex items-center border-b-2 border-b-[#F1F1F1] border-l-2 border-l-[#DA4141] hover:bg-gray-100">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32" fill="none">
                                                                <path d="M12.5358 6.667C14.0754 4.00033 17.9244 4.00033 19.464 6.66699L27.8356 21.167C29.3752 23.8337 27.4507 27.167 24.3715 27.167H7.62834C4.54914 27.167 2.62464 23.8337 4.16424 21.167L12.5358 6.667Z" fill="#DA4141" />
                                                                <path d="M16 18.333C15.4533 18.333 15 17.8797 15 17.333V10.333C15 9.78634 15.4533 9.33301 16 9.33301C16.5467 9.33301 17 9.78634 17 10.333V17.333C17 17.8797 16.5467 18.333 16 18.333Z" fill="#FEE2E2" />
                                                                <path d="M15.9998 23.0001C15.8265 23.0001 15.6532 22.9601 15.4932 22.8934C15.3198 22.8268 15.1865 22.7335 15.0531 22.6135C14.9331 22.4802 14.8398 22.3335 14.7598 22.1735C14.6932 22.0135 14.6665 21.8401 14.6665 21.6668C14.6665 21.3201 14.7998 20.9734 15.0531 20.7201C15.1865 20.6001 15.3198 20.5068 15.4932 20.4402C15.9865 20.2268 16.5732 20.3468 16.9465 20.7201C17.0665 20.8534 17.1598 20.9868 17.2265 21.1601C17.2931 21.3201 17.3332 21.4935 17.3332 21.6668C17.3332 21.8401 17.2931 22.0135 17.2265 22.1735C17.1598 22.3335 17.0665 22.4802 16.9465 22.6135C16.6932 22.8668 16.3598 23.0001 15.9998 23.0001Z" fill="#FEE2E2" />
                                                            </svg>
                                                            <div class="mx-2">
                                                                <span>Stok <span class="font-semibold"><?= $rujukanmasuk_stok['nomor_rujuk'] ?></span> telah mencapai jumlah minimum</span>
                                                                <div class="py-1 font-semibold text-sm text-[#DA4141]">Sisa stok: <?= $rujukanmasuk_stok['nomor_rujuk'] ?></div>
                                                            </div>
                                                        </a>

                                                <?php }
                                                }
                                            } else { ?>
                                                <button class="p-4 flex items-center border-b-2 border-b-[#F1F1F1] border-l-2 border-l-[#DA4141] hover:bg-gray-100">

                                                    <div class="mx-2">
                                                        <span>Belum ada notifikasi stok</span>

                                                    </div>
                                                </button>
                                            <?php
                                            } ?>

                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="h-[1.375rem] border-r-4 bg-[#DCDCDC]"></div>
                            <?= view('components/tambah_button', [
                                'link' => '/rujukanmasuk/tambah'
                            ]) ?>
                            <?= view('components/audit_button', [
                                'link' => '/rujukanmasuk/audit'
                            ]) ?>
                        </div>
                    </div>
                    <!-- End Header -->
                    <?php
                        echo view('components/search_bar');
                        
                        $modul_path = '/rujukanmasuk';
                        $tabel    = $rujukanmasuk_data;
                        $kolom_id = 'nomor_rujuk';
                        $aksi = [
                            'cetak'    => false,
                            'tindakan' => false,
                            'detail'   => true,
                            'ubah'     => true,
                            'hapus'    => true,
                        ];
                        $konfig = [
                            // [visible, Display, Kolom, Jenis]
                            [1, 'Nomor Rujuk'      , 'nomor_rujuk'   , 'indeks'],
                            [1, 'Perujuk'          , 'perujuk'       , 'nama'],
                            [0, 'Alamat Perujuk'   , 'alamat_perujuk', 'teks'],
                            [0, 'Nomor Rawat'      , 'nomor_rawat'   , 'indeks'],
                            [0, 'Nomor Rekam Medis', 'nomor_rm'      ,'indeks'],
                            [1, 'Nama Pasien'      , 'nama_pasien'   , 'nama'],
                            [0, 'Alamat'           , 'alamat'        , 'teks'],
                            [0, 'Umur'             , 'umur'          , 'jumlah'], 
                            [1, 'Tanggal Masuk'    , 'tanggal_masuk' , 'tanggal'],
                            [0, 'Tanggal Keluar'   , 'tanggal_keluar', 'tanggal'],
                            [1, 'Diagnosa Awal'    , 'diagnosa_awal' , 'teks'],
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
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Card -->

<!-- End Table Section -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var count_notif_stok = <?= $count_notif_stok ?>;
        document.querySelector('#stok-tab svg text').textContent = count_notif_stok;
    });
</script>
<?= $this->endSection(); ?>