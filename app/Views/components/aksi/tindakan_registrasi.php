<div class="px-3 py-1.5">
    <button
        type="button"
        class="btn btn-info btn-tindakan gap-x-1 text-sm font-semibold"
        data-nomor-reg="<?= $id ?>"
        data-hs-overlay="#modal-tindakan-<?= $baris['nomor_reg'] ?>">
        Tindakan
    </button>
</div>
<div id="modal-tindakan-<?= $baris['nomor_reg'] ?>" class="hs-overlay hidden size-full fixed top-0 start-0 z-[80] pointer-events-none text-left">
    <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto h-[calc(100%-3.5rem)] min-h-[calc(100%-3.5rem)] flex items-center">
        <div class="overflow-y-auto w-full max-h-full flex flex-col bg-white border shadow-sm rounded-xl pointer-events-auto dark:bg-neutral-800 dark:border-neutral-700 dark:shadow-neutral-700/70">
            <div class="flex justify-between items-center py-3 px-4 border-b dark:border-neutral-700">
                <h3 class="font-bold text-gray-800 dark:text-white">
                    Form Tindakan - <?= $baris['nomor_rawat'] ?>
                </h3>
                <button type="button" class="size-7 rounded-full text-gray-800 dark:text-white hover:bg-gray-100 dark:hover:bg-neutral-700" data-hs-overlay="#modal-tindakan-<?= $baris['nomor_reg'] ?>">
                    <svg class="size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div class="p-4 space-y-4">
                <div>
                    <label class="block text-sm text-gray-900 dark:text-white">Nomor Registrasi</label>
                    <input type="text" value="<?= $baris['nomor_reg'] ?>" readonly class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full dark:bg-neutral-700 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm text-gray-900 dark:text-white">Nomor Rawat</label>
                    <input type="text" value="<?= $baris['nomor_rawat'] ?>" readonly class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full dark:bg-neutral-700 dark:text-white">
                </div>
                <div class="hs-accordion" id="aksi-accordion">
                    <button type="button" class="font-bold text-gray-800 dark:text-white hs-accordion-toggle hs-accordion-active:bg-gray-100 w-1000 flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-lg hover:bg-gray-100 dark:hover:bg-teal-900 dark:text-slate-400 dark:hover:text-slate-300 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600 shadow-md">
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
                                <a href="/tindakan/submit-registrasi/<?= $baris['nomor_reg'] ?>" class="block py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 dark:text-white dark:hover:bg-neutral-800">
                                    Tambah Tindakan
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="hs-accordion" id="aksi-accordion">
                    <button type="button" class="font-bold text-gray-800 dark:text-white hs-accordion-toggle hs-accordion-active:bg-gray-100 w-1000 flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-lg hover:bg-gray-100 dark:hover:bg-teal-900 dark:text-slate-400 dark:hover:text-slate-300 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600 shadow-md">
                        <svg class="h-8 w-8 text-slate-950" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/>
                        </svg>
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
                                <a href="/pemeriksaanranap/by-rawat/<?= $baris['nomor_rawat'] ?>" class="block py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 dark:text-white dark:hover:bg-neutral-800">
                                    Lihat Pemeriksaan
                                </a>
                            </li>
                            <li>
                                <a href="/pemeriksaanranap/tambah-dari-registrasi/<?= $baris['nomor_reg'] ?>" class="block py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 dark:text-white dark:hover:bg-neutral-800">
                                    Tambah Pemeriksaan
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="mt-3">
                    <form method="POST" action="/rawatinap/tambah/<?= $baris['nomor_reg'] ?>">
                        <?= csrf_field() ?>
                        <button type="submit" class="font-bold text-gray-800 dark:text-white w-1000 flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-lg hover:bg-gray-100 dark:hover:bg-teal-900 dark:text-slate-400 dark:hover:text-slate-300 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-800 shadow-md">
                        <svg class="h-8 w-8 text-slate-950"  width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">  <path stroke="none" d="M0 0h24v24H0z"/>  <path d="M3 7v11m0 -4h18m0 4v-8a2 2 0 0 0 -2 -2h-8v6" />  <circle cx="7" cy="10" r="1" /></svg>
                        Rawat Inap
                        </button>
                    </form>
                </div>
            </div>
            <div class="flex justify-end gap-x-2 p-4 border-t dark:border-neutral-700">
                <button class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border bg-white text-gray-800 hover:bg-gray-50 dark:bg-neutral-900 dark:text-white dark:hover:bg-neutral-800" data-hs-overlay="#modal-tindakan-<?= $baris['nomor_reg'] ?>">
                Tutup
                </button>
            </div>
        </div>
    </div>
        <!-- Add more fields if needed -->    
</div>

