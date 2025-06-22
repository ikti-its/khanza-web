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
                            <!-- <?= view('components/tambah_button', [
                                'link' => '/rawatinap/tambah'
                            ]) ?> -->
                        </div>
                    </div>
                    <!-- End Header -->
                    <?php
                        echo view('components/search_bar');
                        
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
                            // [visible, Display, Kolom, Jenis]
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
                            <?php foreach ($rawatinap_data as $rawatinap) : ?>
                                
                                <!-- Tindakan Modal -->
                                <div id="modal-tindakan-<?= $rawatinap['nomor_rawat'] ?>" class="hs-overlay hidden size-full fixed top-0 start-0 z-[80] pointer-events-none">
                                <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto h-[calc(100%-3.5rem)] min-h-[calc(100%-3.5rem)] flex items-center">
                                    <div class="overflow-y-auto w-full max-h-full flex flex-col bg-white border shadow-sm rounded-xl pointer-events-auto dark:bg-neutral-800 dark:border-neutral-700 dark:shadow-neutral-700/70">
                                    <div class="flex justify-between items-center py-3 px-4 border-b dark:border-neutral-700">
                                        <h3 class="font-bold text-gray-800 dark:text-white">
                                        Form Tindakan - <?= $rawatinap['nama_pasien'] ?>
                                        </h3>
                                        <button type="button" class="size-7 rounded-full text-gray-800 dark:text-white hover:bg-gray-100 dark:hover:bg-neutral-700" data-hs-overlay="#modal-tindakan-<?= $rawatinap['nomor_rawat'] ?>">
                                        <svg class="size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                        </button>
                                    </div>
                                    <div class="p-4 space-y-4">
                                        <div>
                                            <label class="block text-sm text-gray-900 dark:text-white">Nomor Rawat</label>
                                            <input type="text" value="<?= $rawatinap['nomor_rawat'] ?>" readonly class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full dark:bg-neutral-700 dark:text-white">
                                        </div>
                                        <div>
                                            <label class="block text-sm text-gray-900 dark:text-white">Nomor Rekam Medis</label>
                                            <input type="text" value="<?= $rawatinap['nomor_rm'] ?>" readonly class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full dark:bg-neutral-700 dark:text-white">
                                        </div>

                                            <div class="hs-accordion mb-4" id="aksi-accordion">
                                                <button type="button" class="shadow-md font-bold text-gray-800 dark:text-white hs-accordion-toggle hs-accordion-active:bg-gray-100 w-100 flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-lg hover:bg-gray-100 dark:hover:bg-teal-900 dark:text-slate-400 dark:hover:text-slate-300 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                                                    <svg class="h-8 w-8 text-slate-950"  width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">  <path stroke="none" d="M0 0h24v24H0z"/>  <path d="M8 13.5a4 4 0 1 0 4 0v-8.5a2 2 0 0 0 -4 0v8.5" />  <line x1="8" y1="9" x2="12" y2="9" />  <line x1="16" y1="9" x2="22" y2="9" />  <line x1="19" y1="6" x2="19" y2="12" /></svg>
                                                Pemeriksaan
                                                    <svg class="hs-accordion-active:hidden size-4 text-gray-600 dark:text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path d="m6 9 6 6 6-6" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                                                    </svg>
                                                    <svg class="hs-accordion-active:block hidden size-4 text-gray-600 dark:text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path d="m18 15-6-6-6 6" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                                                    </svg>
                                                </button>

                                                <div class="hs-accordion-content hidden w-full mt-2 transition-[height] duration-300">
                                                <ul class="ps-3 space-y-1 border-l-2 border-gray-100 dark:border-gray-700">
                                                    <li>
                                                    <a href="/pemeriksaanranap/by-rawat/<?= $rawatinap['nomor_rawat'] ?>" class="block py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 dark:text-white dark:hover:bg-neutral-800">
                                                        Lihat Pemeriksaan
                                                    </a>
                                                    </li>
                                                    <li>
                                                    <a href="/pemeriksaanranap/from-rawatinap/<?= $rawatinap['nomor_rawat'] ?>" class="block py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 dark:text-white dark:hover:bg-neutral-800">
                                                        Tambah Pemeriksaan
                                                    </a>
                                                    </li>
                                                </ul>
                                            </div>

                                            <div class="hs-accordion mb-4" id="aksi-accordion">
                                                <button type="button" class="shadow-md font-bold text-gray-800 dark:text-white hs-accordion-toggle hs-accordion-active:bg-gray-100 w-100 flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-lg hover:bg-gray-100 dark:hover:bg-teal-900 dark:text-slate-400 dark:hover:text-slate-300 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                                                    <svg class="h-8 w-8 text-slate-950" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/>
                        </svg>
                                                Tindakan
                                                    <svg class="hs-accordion-active:hidden size-4 text-gray-600 dark:text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path d="m6 9 6 6 6-6" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                                                    </svg>
                                                    <svg class="hs-accordion-active:block hidden size-4 text-gray-600 dark:text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path d="m18 15-6-6-6 6" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                                                    </svg>
                                                </button>

                                                <div class="hs-accordion-content hidden w-full mt-2 transition-[height] duration-300">
                                                <ul class="ps-3 space-y-1 border-l-2 border-gray-100 dark:border-gray-700">
                                                    <li>
                                                    <a href="/tindakan/<?= $rawatinap['nomor_rawat'] ?>" class="block py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 dark:text-white dark:hover:bg-neutral-800">
                                                        Lihat Tindakan
                                                    </a>
                                                    </li>
                                                    <li>
                                                    <a href="/tindakan/submit-ranap/<?= $rawatinap['nomor_rawat'] ?>" class="block py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 dark:text-white dark:hover:bg-neutral-800">
                                                        Tambah Tindakan
                                                    </a>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="hs-accordion mb-4" id="aksi-accordion">
                                                <button type="button" class="shadow-md font-bold text-gray-800 dark:text-white hs-accordion-toggle hs-accordion-active:bg-gray-100 w-100 flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-lg hover:bg-gray-100 dark:hover:bg-teal-900 dark:text-slate-400 dark:hover:text-slate-300 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                                                    <svg class="h-8 w-8 text-slate-950"  width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">  <path stroke="none" d="M0 0h24v24H0z"/>  <path d="M16 6h3a1 1 0 0 1 1 1v11a2 2 0 0 1 -4 0v-13a1 1 0 0 0 -1 -1h-10a1 1 0 0 0 -1 1v12a3 3 0 0 0 3 3h11" />  <line x1="8" y1="8" x2="12" y2="8" />  <line x1="8" y1="12" x2="12" y2="12" />  <line x1="8" y1="16" x2="12" y2="16" /></svg>
                                                Resep Obat
                                                    <svg class="hs-accordion-active:hidden size-4 text-gray-600 dark:text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path d="m6 9 6 6 6-6" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                                                    </svg>
                                                    <svg class="hs-accordion-active:block hidden size-4 text-gray-600 dark:text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path d="m18 15-6-6-6 6" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                                                    </svg>
                                                </button>

                                                <div class="hs-accordion-content hidden w-full mt-2 transition-[height] duration-300">
                                                <ul class="ps-3 space-y-1 border-l-2 border-gray-100 dark:border-gray-700">
                                                    <li>
                                                        <a href="/resepobat/<?= $rawatinap['nomor_rawat'] ?>" class="block py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 dark:text-white dark:hover:bg-neutral-800">
                                                            Lihat Resep Obat
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="/resepobat/submit/<?= $rawatinap['nomor_rawat'] ?>" class="block py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 dark:text-white dark:hover:bg-neutral-800">
                                                            Buat Resep Obat Umum
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="/resepobatracikan/submit/<?= $rawatinap['nomor_rawat'] ?>" class="block py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 dark:text-white dark:hover:bg-neutral-800">
                                                            Buat Resep Obat Racikan
                                                        </a>
                                                    </li>                                                   
                                                </ul>
                                            </div>
                                            <!-- <div class="hs-accordion mb-4" id="aksi-accordion">
                                                <button type="button" class="shadow-md font-bold text-gray-800 dark:text-white hs-accordion-toggle hs-accordion-active:bg-gray-100 w-100 flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-lg hover:bg-gray-100 dark:hover:bg-teal-900 dark:text-slate-400 dark:hover:text-slate-300 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                                                    <svg class="h-8 w-8 text-slate-950"  width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">  <path stroke="none" d="M0 0h24v24H0z"/>  <rect x="4" y="4" width="16" height="16" rx="2" />  <path d="M4 13h3l3 3h4l3 -3h3" /></svg>
                                                Stok Obat Pasien
                                                    <svg class="hs-accordion-active:hidden size-4 text-gray-600 dark:text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path d="m6 9 6 6 6-6" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                                                    </svg>
                                                    <svg class="hs-accordion-active:block hidden size-4 text-gray-600 dark:text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path d="m18 15-6-6-6 6" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                                                    </svg>
                                                </button>

                                                <div class="hs-accordion-content hidden w-full mt-2 transition-[height] duration-300">
                                                <ul class="ps-3 space-y-1 border-l-2 border-gray-100 dark:border-gray-700">
                                                    <li>
                                                        <a href="/permintaanstokobat/submit/<?= $rawatinap['nomor_rawat'] ?>" class="block py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 dark:text-white dark:hover:bg-neutral-800">
                                                            Permintaan Stok Obat Pasien
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="/stokobatpasien/<?= $rawatinap['nomor_rawat'] ?>" class="block py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 dark:text-white dark:hover:bg-neutral-800">
                                                            Lihat Stok Obat Pasien
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div> -->
                                            <div class="hs-accordion mb-4" id="aksi-accordion-pemberian-obat">
                                                <button type="button" class="shadow-md font-bold text-gray-800 dark:text-white hs-accordion-toggle hs-accordion-active:bg-gray-100 w-100 flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-lg hover:bg-gray-100 dark:hover:bg-teal-900 dark:text-slate-400 dark:hover:text-slate-300 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                                                    <svg class="h-8 w-8 text-slate-950"  width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">  <path stroke="none" d="M0 0h24v24H0z"/>  <path d="M4.5 12.5l8 -8a4.94 4.94 0 0 1 7 7l-8 8a4.94 4.94 0 0 1 -7 -7" />  <path d="M8.5 8.5l7 7" /></svg>
                                                Pemberian Obat
                                                    <svg class="hs-accordion-active:hidden size-4 text-gray-600 dark:text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path d="m6 9 6 6 6-6" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                                                    </svg>
                                                    <svg class="hs-accordion-active:block hidden size-4 text-gray-600 dark:text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path d="m18 15-6-6-6 6" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                                                    </svg>
                                                </button>

                                                <div class="hs-accordion-content hidden w-full mt-2 transition-[height] duration-300">
                                                <ul class="ps-3 space-y-1 border-l-2 border-gray-100 dark:border-gray-700">
                                                    <li>
                                                        <a href="/pemberianobat/submit/<?= $rawatinap['nomor_rawat'] ?>" class="block py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 dark:text-white dark:hover:bg-neutral-800">
                                                            Tambah Pemberian Obat
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="/pemberianobat/<?= $rawatinap['nomor_rawat'] ?>" class="block py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 dark:text-white dark:hover:bg-neutral-800">
                                                            Lihat Pemberian Obat
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="hs-accordion mb-4" id="aksi-accordion-pemberian-obat">
                                                <button type="button" class="shadow-md font-bold text-gray-800 dark:text-white hs-accordion-toggle hs-accordion-active:bg-gray-100 w-100 flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-lg hover:bg-gray-100 dark:hover:bg-teal-900 dark:text-slate-400 dark:hover:text-slate-300 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                                                    <svg class="h-8 w-8 text-slate-950"  width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">  <path stroke="none" d="M0 0h24v24H0z"/>  <path d="M11 7h-5a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-5" />  <line x1="10" y1="14" x2="20" y2="4" />  <polyline points="15 4 20 4 20 9" /></svg>
                                                Rujukan
                                                    <svg class="hs-accordion-active:hidden size-4 text-gray-600 dark:text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path d="m6 9 6 6 6-6" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                                                    </svg>
                                                    <svg class="hs-accordion-active:block hidden size-4 text-gray-600 dark:text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path d="m18 15-6-6-6 6" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                                                    </svg>
                                                </button>

                                                <div class="hs-accordion-content hidden w-full mt-2 transition-[height] duration-300">
                                                <ul class="ps-3 space-y-1 border-l-2 border-gray-100 dark:border-gray-700">
                                                    <li>
                                                        <a href="/rujukankeluar/fromrawatinap/<?= $rawatinap['nomor_rawat'] ?>" 
                                                        class="block py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 dark:text-white dark:hover:bg-neutral-800">
                                                            Rujuk Keluar
                                                        </a>

                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="hs-accordion mb-4" id="aksi-accordion-rekam-medis">
                                                <button type="button" class="shadow-md font-bold text-gray-800 dark:text-white hs-accordion-toggle hs-accordion-active:bg-gray-100 w-100 flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-lg hover:bg-gray-100 dark:hover:bg-teal-900 dark:text-slate-400 dark:hover:text-slate-300 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                                                    <svg class="h-8 w-8 text-slate-950"  width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">  <path stroke="none" d="M0 0h24v24H0z"/>  <rect x="4" y="4" width="16" height="16" rx="2" />  <path d="M4 13h3l3 3h4l3 -3h3" /></svg>
                                                Rekam Medis
                                                    <svg class="hs-accordion-active:hidden size-4 text-gray-600 dark:text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path d="m6 9 6 6 6-6" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                                                    </svg>
                                                    <svg class="hs-accordion-active:block hidden size-4 text-gray-600 dark:text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path d="m18 15-6-6-6 6" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                                                    </svg>
                                                </button>
                                                <div class="hs-accordion-content hidden w-full mt-2 transition-[height] duration-300">
                                                <ul class="ps-3 space-y-1 border-l-2 border-gray-100 dark:border-gray-700">
                                                    <li>
                                                        <a href="/pasien/rekam-medis/<?= $rawatinap['nomor_rm'] ?>" class="block py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 dark:text-white dark:hover:bg-neutral-800">
                                                            Identitas Pasien
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="/catatanobservasiranap/<?= $rawatinap['nomor_rawat'] ?>" class="block py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 dark:text-white dark:hover:bg-neutral-800">
                                                            Lihat Observasi Rawat Inap
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="/catatanobservasiranap/from-rawatinap/<?= $rawatinap['nomor_rawat'] ?>" class="block py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 dark:text-white dark:hover:bg-neutral-800">
                                                            Tambah Observasi Rawat Inap
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="/catatanobservasikebidanan/<?= $rawatinap['nomor_rawat'] ?>" class="block py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 dark:text-white dark:hover:bg-neutral-800">
                                                            Lihat Observasi Rawat Inap Kebidanan
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="/catatanobservasikebidanan/from-rawatinap/<?= $rawatinap['nomor_rawat'] ?>" class="block py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 dark:text-white dark:hover:bg-neutral-800">
                                                            Tambah Observasi Rawat Inap Kebidanan
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="/catatanobservasipostpartum/<?= $rawatinap['nomor_rawat'] ?>" class="block py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 dark:text-white dark:hover:bg-neutral-800">
                                                            Lihat Observasi Rawat Inap Post Partum
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="/catatanobservasipostpartum/from-rawatinap/<?= $rawatinap['nomor_rawat'] ?>" class="block py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 dark:text-white dark:hover:bg-neutral-800">
                                                            Tambah Observasi Rawat Inap Post Partum
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="/diagnosa/from-rawatinap/<?= $rawatinap['nomor_rawat'] ?>" class="block py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 dark:text-white dark:hover:bg-neutral-800">
                                                            Tambah Diagnosa
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="hs-accordion mb-4 " id="aksi-accordion-resep-pulang">
                                                <button type="button" class="shadow-md font-bold text-gray-800 dark:text-white hs-accordion-toggle hs-accordion-active:bg-gray-100 w-100 flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-lg hover:bg-gray-100 dark:hover:bg-teal-900 dark:text-slate-400 dark:hover:text-slate-300 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                                                    <svg class="h-8 w-8 text-slate-950"  width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">  <path stroke="none" d="M0 0h24v24H0z"/>  <path d="M14 8v-2a2 2 0 0 0 -2 -2h-7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2 -2v-2" />  <path d="M7 12h14l-3 -3m0 6l3 -3" /></svg>
                                                Resep Pulang
                                                    <svg class="hs-accordion-active:hidden size-4 text-gray-600 dark:text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path d="m6 9 6 6 6-6" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                                                    </svg>
                                                    <svg class="hs-accordion-active:block hidden size-4 text-gray-600 dark:text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path d="m18 15-6-6-6 6" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                                                    </svg>
                                                </button>

                                                <div class="hs-accordion-content hidden w-full mt-2 transition-[height] duration-300">
                                                <ul class="ps-3 space-y-1 border-l-2 border-gray-100 dark:border-gray-700">
                                                    <li>
                                                        <a href="/permintaanreseppulang/submit/<?= $rawatinap['nomor_rawat'] ?>" class="block py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 dark:text-white dark:hover:bg-neutral-800">
                                                            Permintaan Resep Pulang
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="/reseppulang" class="block py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 dark:text-white dark:hover:bg-neutral-800">
                                                            Resep Pulang
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        <!-- Add more fields if needed -->
                                    </div>
                                    <div class="flex justify-end gap-x-2 p-4 border-t dark:border-neutral-700">
                                        <button class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border bg-white text-gray-800 hover:bg-gray-50 dark:bg-neutral-900 dark:text-white dark:hover:bg-neutral-800" data-hs-overlay="#modal-tindakan-<?= $rawatinap['nomor_rawat'] ?>">
                                        Tutup
                                        </button>
                                    </div>
                                    </div>
                                </div>
                                </div>

                                <tr>
                                    
                                    
                                    <td class="size-px whitespace-nowrap">
                                        <div class="px-3 py-1.5 text-center inline-flex">
                                            <div class="px-3 py-1.5">
                                            <button type="button" class="gap-x-1 text-sm decoration-2 hover:underline font-semibold ..." data-hs-overlay="#modal-tindakan-<?= $rawatinap['nomor_rawat'] ?>">
                                                Tindakan
                                            </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
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