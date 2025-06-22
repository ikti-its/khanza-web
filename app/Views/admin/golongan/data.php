@ -1,666 +1,658 @@
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
                                Golongan Pegawai
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
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="h-[1.375rem] border-r-4 bg-[#DCDCDC]"></div>
                            <?= view('components/tambah_button', [
                                'link' => '/bpjs/tambah'
                            ]) ?>

                        </div>
                    </div>

                    <!-- End Header -->
                    <?php
                        echo view('components/search_bar');
                        
                        $modul_path = '/golongan';
                        $kolom_id = 'no_golongan';
                        $aksi = [
                            'cetak'    => false,
                            'tindakan' => false,
                            'detail'   => true,
                            'ubah'     => true,
                            'hapus'    => true
                        ];
                        $data = [
                            // [visible, Display, Kolom, Jenis]
                            [1, 'Nomor Golongan', 'no_golongan'  , 'indeks'],
                            [1, 'Kode Golongan' , 'kode_golongan', 'teks'],
                            [1, 'Nama Golongan' , 'nama_golongan', 'teks'],
                            [1, 'Pendidikan'    , 'pendidikan'   , 'teks'],
                            [1, 'Gaji Pokok'    , 'gaji_pokok'   , 'uang']
                            
                        ];
                        echo view('components/tabel', [
                            'modul_path' => $modul_path,
                            'tabel'      => $tabel,
                            'kolom_id'   => $kolom_id,
                            'data'       => $data,
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
<?= $this->endSection(); ?>