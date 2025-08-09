<script src="https://unpkg.com/preline@latest/dist/preline.js"></script>
<!-- Tindakan Modal -->
<div class="px-3 py-1.5">
    <button
        type="button"
        class="btn btn-info btn-tindakan gap-x-1 text-sm font-semibold"
        data-nomor-reg="<?= $id ?>"
        data-hs-overlay="#modal-tindakan-<?= $baris['nomor_rawat'] ?>">
        Tindakan
    </button>
</div>
<div id="modal-tindakan-<?= $baris['nomor_rawat'] ?>" class="hs-overlay hidden size-full fixed top-0 start-0 z-[80] pointer-events-none text-left">
    <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto h-[calc(100%-3.5rem)] min-h-[calc(100%-3.5rem)] flex items-center">
        <div class="overflow-y-auto w-full max-h-full flex flex-col bg-white border shadow-sm rounded-xl pointer-events-auto dark:bg-neutral-800 dark:border-neutral-700 dark:shadow-neutral-700/70">
            <div class="flex justify-between items-center py-3 px-4 border-b dark:border-neutral-700">
                <h3 class="font-bold text-gray-800 dark:text-white">
                    Form Tindakan - <?= $baris['nama_pasien'] ?>
                </h3>
                <button type="button" class="size-7 rounded-full text-gray-800 dark:text-white hover:bg-gray-100 dark:hover:bg-neutral-700" data-hs-overlay-close>
                    <svg class="size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div class="p-4 space-y-4">
                <div>
                    <label class="block text-sm text-gray-900 dark:text-white">Nomor Rawat</label>
                    <input type="text" value="<?= $baris['nomor_rawat'] ?>" readonly class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full dark:bg-neutral-700 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm text-gray-900 dark:text-white">Nomor Rekam Medis</label>
                    <input type="text" value="<?= $baris['nomor_rm'] ?>" readonly class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full dark:bg-neutral-700 dark:text-white">
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
                            <a href="/pemeriksaanranap/by-rawat/<?= $baris['nomor_rawat'] ?>" class="block py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 dark:text-white dark:hover:bg-neutral-800 text-left">
                                Lihat Pemeriksaan
                            </a>
                            </li>
                            <li>
                            <a href="/pemeriksaanranap/from-rawatinap/<?= $baris['nomor_rawat'] ?>" class="block py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 dark:text-white dark:hover:bg-neutral-800 text-left">
                                Tambah Pemeriksaan
                            </a>
                            </li>
                        </ul>
                    </div>
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
                            <a href="/tindakan/<?= $baris['nomor_rawat'] ?>" class="block py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 dark:text-white dark:hover:bg-neutral-800">
                                Lihat Tindakan
                            </a>
                            </li>
                            <li>
                            <a href="/tindakan/submit-ranap/<?= $baris['nomor_rawat'] ?>" class="block py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 dark:text-white dark:hover:bg-neutral-800">
                                Tambah Tindakan
                            </a>
                            </li>
                        </ul>
                    </div>
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
                                <a href="/resepobat/<?= $baris['nomor_rawat'] ?>" class="block py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 dark:text-white dark:hover:bg-neutral-800">
                                    Lihat Resep Obat
                                </a>
                            </li>
                            <li>
                                <a href="/resepobat/submit/<?= $baris['nomor_rawat'] ?>" class="block py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 dark:text-white dark:hover:bg-neutral-800">
                                    Buat Resep Obat Umum
                                </a>
                            </li>
                            <li>
                                <a href="/resepobatracikan/submit/<?= $baris['nomor_rawat'] ?>" class="block py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 dark:text-white dark:hover:bg-neutral-800">
                                    Buat Resep Obat Racikan
                                </a>
                            </li>                                                   
                        </ul>
                    </div>
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
                                <a href="/permintaanstokobat/submit/<?= $baris['nomor_rawat'] ?>" class="block py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 dark:text-white dark:hover:bg-neutral-800">
                                    Permintaan Stok Obat Pasien
                                </a>
                            </li>
                            <li>
                                <a href="/stokobatpasien/<?= $baris['nomor_rawat'] ?>" class="block py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 dark:text-white dark:hover:bg-neutral-800">
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
                                <a href="/pemberianobat/submit/<?= $baris['nomor_rawat'] ?>" class="block py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 dark:text-white dark:hover:bg-neutral-800">
                                    Tambah Pemberian Obat
                                </a>
                            </li>
                            <li>
                                <a href="/pemberianobat/<?= $baris['nomor_rawat'] ?>" class="block py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 dark:text-white dark:hover:bg-neutral-800">
                                    Lihat Pemberian Obat
                                </a>
                            </li>
                        </ul>
                    </div>
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
                                <a href="/rujukankeluar/fromrawatinap/<?= $baris['nomor_rawat'] ?>" 
                                class="block py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 dark:text-white dark:hover:bg-neutral-800">
                                    Rujuk Keluar
                                </a>

                            </li>
                        </ul>
                    </div>
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
                                <a href="/pasien/rekam-medis/<?= $baris['nomor_rm'] ?>" class="block py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 dark:text-white dark:hover:bg-neutral-800">
                                    Identitas Pasien
                                </a>
                            </li>
                            <li>
                                <a href="/catatanobservasiranap/<?= $baris['nomor_rawat'] ?>" class="block py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 dark:text-white dark:hover:bg-neutral-800">
                                    Lihat Observasi Rawat Inap
                                </a>
                            </li>
                            <li>
                                <a href="/catatanobservasiranap/from-rawatinap/<?= $baris['nomor_rawat'] ?>" class="block py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 dark:text-white dark:hover:bg-neutral-800">
                                    Tambah Observasi Rawat Inap
                                </a>
                            </li>
                            <li>
                                <a href="/catatanobservasikebidanan/<?= $baris['nomor_rawat'] ?>" class="block py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 dark:text-white dark:hover:bg-neutral-800">
                                    Lihat Observasi Rawat Inap Kebidanan
                                </a>
                            </li>
                            <li>
                                <a href="/catatanobservasikebidanan/from-rawatinap/<?= $baris['nomor_rawat'] ?>" class="block py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 dark:text-white dark:hover:bg-neutral-800">
                                    Tambah Observasi Rawat Inap Kebidanan
                                </a>
                            </li>
                            <li>
                                <a href="/catatanobservasipostpartum/<?= $baris['nomor_rawat'] ?>" class="block py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 dark:text-white dark:hover:bg-neutral-800">
                                    Lihat Observasi Rawat Inap Post Partum
                                </a>
                            </li>
                            <li>
                                <a href="/catatanobservasipostpartum/from-rawatinap/<?= $baris['nomor_rawat'] ?>" class="block py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 dark:text-white dark:hover:bg-neutral-800">
                                    Tambah Observasi Rawat Inap Post Partum
                                </a>
                            </li>
                        </ul>
                    </div>
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
                                <a href="/permintaanreseppulang/submit/<?= $baris['nomor_rawat'] ?>" class="block py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 dark:text-white dark:hover:bg-neutral-800">
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
                    <button class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border bg-white text-gray-800 hover:bg-gray-50 dark:bg-neutral-900 dark:text-white dark:hover:bg-neutral-800" data-hs-overlay-close>
                    Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
function toggleAccordionContent(content) {
  const isOpen = content.classList.contains('open');

  if (isOpen) {
    content.style.height = content.scrollHeight + 'px';
    requestAnimationFrame(() => {
      content.style.height = '0px';
    });
    content.classList.remove('open');
  } else {
    content.classList.remove('hidden');
    content.style.height = 'auto';
    const height = content.scrollHeight + 'px';
    content.style.height = '0px';
    requestAnimationFrame(() => {
      content.style.height = height;
    });
    content.classList.add('open');
  }

  content.addEventListener('transitionend', () => {
    if (!content.classList.contains('open')) {
      content.classList.add('hidden');
    }
    content.style.height = '';
  }, { once: true });
}

function setupAccordions() {
  document.querySelectorAll('.hs-accordion-toggle').forEach((button) => {
    if (!button.dataset.listenerAttached) {
      button.addEventListener('click', () => {
        const accordion = button.closest('.hs-accordion');
        const content = accordion?.querySelector('.hs-accordion-content');

        if (content) {
          toggleAccordionContent(content);
          console.log(`✅ Toggled accordion for:`, button);
        } else {
          console.warn('❌ No .hs-accordion-content found for button:', button);
        }
      });
      button.dataset.listenerAttached = 'true';
    }
  });
}

document.addEventListener('DOMContentLoaded', setupAccordions);

document.querySelectorAll('[data-hs-overlay]').forEach(button => {
  button.addEventListener('click', () => {
    const modalId = button.getAttribute('data-hs-overlay');
    const modal = document.querySelector(modalId);
    if (modal) {
      modal.classList.remove('pointer-events-none');
    }

    setTimeout(() => {
      setupAccordions();
    }, 300);
  });
});

document.querySelectorAll('[data-hs-overlay-close]').forEach(btn => {
  btn.addEventListener('click', () => {
    const modal = btn.closest('.hs-overlay');
    if (modal) {
      setTimeout(() => {
        modal.classList.add('hidden');
        modal.classList.remove('hs-overlay-open:mt-7', 'hs-overlay-open:opacity-100', 'hs-overlay-open:duration-500');
        modal.style.pointerEvents = 'none';
      }, 100);
    }
  });
});
</script>
