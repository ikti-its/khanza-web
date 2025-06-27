<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>
<script src="https://unpkg.com/preline@latest/dist/preline.js"></script>
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
                                Pasien Rawat Inap
                            </h2>

                        </div>
                        <div class="flex gap-x-6 justify-center items-center">
                            <!-- <?= view('components/header/tambah_button', [
                                'link' => '/rawatinap/tambah'
                            ]) ?> -->
                            <?= view('components/header/audit_button', [
                                'link' => '/rawatinap/audit'
                            ]) ?>
                        </div>
                    </div>
                    <!-- End Header -->
                    <?php
                        echo view('components/header/search_bar');
                        
                        $modul_path = '/rawatinap';
                        $tabel    = $rawatinap_data;
                        $kolom_id = 'nomor_rawat';
                        $aksi = [
                            'cetak'    => false,
                            'tindakan' => true,
                            'detail'   => true,
                            'ubah'     => true,
                            'hapus'    => true,
                        ];
                        $konfig = [
                            // [visible, Display, Kolom, Jenis, Required, *Opsi]
                            [1, 'Nomor Rawat'      , 'nomor_rawat'   , 'indeks'],
                            [0, 'Nomor Rekam Medis', 'nomor_rm'      , 'indeks'],
                            [1, 'Nama Pasien'      , 'nama_pasien'   , 'nama'],
                            [0, 'Alamat Pasien'    , 'alamat_pasien' , 'teks'],
                            [0, 'Penanggung Jawab' , 'penanggung_jawab'   , 'nama'],
                            [0, 'Hubungan Penanggung Jawab', 'hubungan_pj', 'teks'],
                            [0, 'Jenis Bayar'      , 'jenis_bayar'   , 'status'],
                            [0, 'Kamar'            , 'kamar'         , 'teks'],
                            [0, 'Tarif Kamar'      , 'tarif_kamar'   , 'uang'],
                            [1, 'Diagnosa Awal'    , 'diagnosa_awal' , 'teks'],
                            [0, 'Diagnosa Akhir'   , 'diagnosa_akhir','teks'],
                            [0, 'Tanggal Masuk'    , 'tanggal_masuk' , 'tanggal'],
                            [0, 'Jam Masuk'        , 'jam_masuk'     , 'jam'],
                            [0, 'Tanggal Keluar'   , 'tanggal_keluar', 'tanggal'],
                            [0, 'Jam Keluar'       , 'jam_keluar'    , 'jam'],
                            [0, 'Total Biaya Kamar'      , 'total_biaya_kamar'   , 'uang'],
                            [0, 'Total Biaya Tindakan'      , 'total_biaya_tindakan'   , 'uang'],
                            [0, 'Total Biaya Obat'      , 'total_biaya_obat'   , 'uang'],
                            [0, 'Total Biaya'      , 'total_biaya'   , 'uang'],
                            [0, 'Status Pulang'    , 'status_pulang' , 'status'],
                            [0, 'Lama'             , 'lama_ranap'    , 'teks'],
                            [1, 'Dokter'           , 'dokter_pj'     , 'indeks'],
                            [0, 'Status Bayar'     , 'status_bayar'  , 'status']
                            
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