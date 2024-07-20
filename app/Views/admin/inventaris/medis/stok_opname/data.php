<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>
<!-- Table Section -->
<div class="max-w-[85rem] py-6 lg:py-3 px-8 mx-auto">
    <!-- Card -->
    <div class="flex flex-col">

        <div class="-m-1.5 overflow-y-auto">
            <div class="sm:px-6 min-w-full inline-block align-middle">
             
              
                <div class="p-5 bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-slate-900 dark:border-gray-700">
                    <!-- Header -->
                    <div class="py-1 flex justify-between items-center border-gray-200 dark:border-gray-700">
                        <div>
                            <h2 class="mb-2 text-xl font-extrabold text-gray-800 dark:text-gray-200">
                                Stok Opname
                            </h2>


                        </div>
                        <div>
                            <a href='/stokopnamemedis/tambah' class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-[#0A2D27] text-[#ACF2E7] hover:bg-[#13594E] disabled:opacity-50 disabled:pointer-events-none dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" href="#">
                                <svg class="flex-shrink-0 size-3" xmlns="http://www.w3.org/2000/svg" width="16" height="1" viewBox="0 0 16 16" fill="none">
                                    <path d="M2.63452 7.50001L13.6345 7.5M8.13452 13V2" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                                </svg>
                                Tambah
                            </a>
                        </div>
                    </div>


                    <div class="py-4 grid gap-3 md:items-start">
                        <div class="sm:col-span-1">
                            <label for="hs-as-table-product-review-search" class="sr-only">Search</label>
                            <div class="relative">
                                <input type="text" class="py-2 px-4 ps-11 block border w-full xl:w-96 border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600" placeholder="Search">
                                <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none ps-4">
                                    <svg class="size-4 text-gray-400 dark:text-neutral-500" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z" />
                                    </svg>
                                </div>

                            </div>
                        </div>
                    </div>


                    <!-- End Header -->

                    <!-- Table -->

                    <table id="myTable" class="overflow-x-auto min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <colgroup>
                            <!-- <col width="5%"> -->
                            <col width="20%">
                            <col width="25%">
                            <!-- <col width="20%"> -->
                            <col width="30%">
                            <col width="25%">
                        </colgroup>
                        <thead class="bg-gray-50 dark:bg-slate-800">
                            <tr>

                                <th scope="col" class="px-6 py-3 text-center">
                                    <div class="flex items-center justify-center">
                                        <span class="text-xs tracking-wide text-[#666]">
                                            Tanggal
                                        </span>
                                    </div>
                                </th>

                                <th scope="col" class="px-6 py-3">
                                    <div class="flex items-center justify-center">
                                        <span class="text-xs tracking-wide text-[#666]">
                                            Nomor Pengajuan
                                        </span>
                                    </div>
                                </th>

                                <th scope="col" class="px-6 py-3 text-center">
                                    <div class="flex items-center justify-center">
                                        <span class="text-xs tracking-wide text-[#666]">
                                            Status
                                        </span>
                                    </div>
                                </th>

                                <th scope="col" class="px-6 py-3 text-center">
                                    <div class="flex items-center justify-center">
                                        <span class="text-xs tracking-wide text-[#666]">
                                            Aksi
                                        </span>
                                    </div>
                                </th>

                            </tr>
                        </thead>




                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            
                                    <div id="hs-vertically-centered-scrollable-modal-" class="hs-overlay hidden size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto pointer-events-none">
                                        <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto h-[calc(100%-3.5rem)] min-h-[calc(100%-3.5rem)] flex items-center">
                                            <div class="w-full max-h-full overflow-hidden flex flex-col bg-white border shadow-sm rounded-xl pointer-events-auto dark:bg-neutral-800 dark:border-neutral-700 dark:shadow-neutral-700/70">
                                                <div class="flex justify-between items-center py-3 px-4  dark:border-neutral-700">
                                                    <h3 class="font-bold text-gray-800 dark:text-white">

                                                    </h3>
                                                    <button type="button" class="flex justify-center items-center size-7 text-sm font-semibold rounded-full border border-transparent text-gray-800 hover:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-neutral-700" data-hs-overlay="#hs-vertically-centered-scrollable-modal-">
                                                        <span class="sr-only">Close</span>
                                                        <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                            <path d="M18 6 6 18"></path>
                                                            <path d="m6 6 12 12"></path>
                                                        </svg>
                                                    </button>
                                                </div>
                                                <div class="p-4 overflow-y-auto">
                                                    <div class="space-y-12">
                                                        <div>
                                                            <div class="mb-5 sm:block md:flex items-center">
                                                                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Tanggal Pengajuan</label>
                                                                <input type="text" name="" value="" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
                                                            </div>
                                                            <div class="mb-5 sm:block md:flex items-center">
                                                                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Nomor
                                                                    Pengajuan</label>
                                                                <input type="text" name="" value="" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
                                                            </div>
                                                            <div class="mb-5 sm:block md:flex items-center">
                                                                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Pegawai</label>
                                                                <input type="text" name="" value="" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
                                                            </div>
                                                            <div class="mb-5 sm:block md:flex items-center">
                                                                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Status Apoteker</label>
                                                                <input type="text" name="" value="" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
                                                            </div>
                                                            <div class="mb-5 sm:block md:flex items-center">
                                                                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Status Keuangan</label>
                                                                <input type="text" name="" value="" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
                                                            </div>
                                                            <div class="mb-5 sm:block md:flex items-center">
                                                                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Catatan</label>
                                                                <input type="text" name="" value="" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
                                                            </div>
                                                        </div>

                                                        <div class="pt-2 border-t border-[#F1F1F1]">
                                                            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Pesanan</h3>
                                                            <div>

                                                                <div class="flex items-center justify-between mb-2">
                                                                    <div class="w-1/2">


                                                                    </div>
                                                                    <div class="flex justify-end w-1/2">
                                                                        <p class="font-bold mr-2 text-center text-gray-900 text-sm rounded-lg w-full">Harga/Item</p>
                                                                        <p class="font-bold text-center text-gray-900 text-sm rounded-lg w-full">Total/Item</p>
                                                                    </div>
                                                                </div>



                                                     
                                                                                <br>
                                                                            </div>
                                                                            <div class="flex justify-end w-1/2">
                                                                                <input type="text" name="" value="" class="text-center mr-2 bg-gray-100 text-gray-900 text-sm rounded-lg px-2 py-1 w-full dark:border-gray-600 dark:text-white" readonly>
                                                                                <input type="text" name="" value="" class="text-center bg-gray-100 font-[600] text-gray-900 text-sm rounded-lg px-2 py-1 w-full dark:border-gray-600 dark:text-white" readonly>
                                                                            </div>
                                                                        </div>
                                                                        <div><small>Jumlah:
                                                                               
                                                                            </small></div>
                                                                        <br>

                                                              

                                                                <div class="border-t border-[#F1F1F1] my-2">
                                                                    <div class="flex justify-between pt-1">
                                                                        <label class="block mb-2 md:mb-0 text-sm font-[600] text-gray-900 dark:text-white md:w-1/2">Total</label>
                                                                        <label class="block mb-2 md:mb-0 text-sm font-[600] text-gray-900 dark:text-white">Rp </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="flex justify-end items-center gap-x-2 py-3 px-4 w-full border-t dark:border-neutral-700">
                                                    
                                                        <a href="/pemesananmedis/tambah/" class="w-full py-2 px-3 flex justify-center items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-[#0A2D27] text-[#ACF2E7] shadow-sm hover:bg-[#13594E] disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-800">
                                                            Lanjutkan Pemesanan
                                                        </a>
                                             
                                                        <button class="w-full py-2 px-3 flex justify-center items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-[#CCD3D2] text-[#EDFBF9] shadow-sm cursor-default">
                                                            Lanjutkan Pemesanan
                                                        </button>
                                         
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <tr>
                                        <td>
                                            <div class="px-6 py-3">
                                                <div class="flex items-center justify-center gap-x-3">
                                                    <span class="block text-sm font-semibold text-gray-800 dark:text-gray-200"></span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="px-6 py-3 text-center">
                                                <span class="text-center block text-sm font-semibold cursor-pointer hover:underline text-gray-800 dark:text-gray-200" data-hs-overlay="#hs-vertically-centered-scrollable-modal-"></span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="px-6 py-3 text-center">
                                                
                                                
                                            </div>
                                        </td>
                                        <td>
                                            <div class="pl-6 py-1.5 inline-flex">
                                                <div class="pr-3 py-1.5">
                                                    <button type="button" class="gap-x-1 text-sm decoration-2 hover:underline font-semibold dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" data-hs-overlay="#hs-vertically-centered-scrollable-modal-">
                                                        Lihat Detail
                                                    </button>
                                                </div>
                                               
                                                  
                                            
                                                    <div class="px-3 py-1.5">
                                                        <a href="/pengajuanmedis/edit/" class="gap-x-1 text-sm text-blue-600 decoration-2 hover:underline font-semibold dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" href="#">
                                                            Ubah 
                                                        </a>
                                                    </div>

                                                    <div class="px-3 py-1.5">
                                                        <button class="gap-x-1 text-sm text-red-600 decoration-2 hover:underline font-semibold dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" onclick="openModal('modelConfirm-')" href="#">
                                                            Hapus
                                                        </button>
                                                        <div id="modelConfirm-" class="fixed hidden z-[70] inset-0 bg-gray-900 bg-opacity-60 overflow-y-auto h-full w-full px-4 ">
                                                            <div class="relative top-40 mx-auto shadow-xl rounded-md bg-white max-w-md">

                                                                <div class="flex justify-end p-2">
                                                                    <button onclick="closeModal('modelConfirm-')" type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center">
                                                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                                                        </svg>
                                                                    </button>
                                                                </div>

                                                                <div class="p-6 pt-0 text-center">
                                                                    <div class="flex justify-center mb-6">
                                                                        <!-- Container for SVG, centered -->
                                                                        <svg xmlns="http://www.w3.org/2000/svg" width="49" height="48" viewBox="0 0 49 48" fill="none">
                                                                            <path d="M8.5 11H40.5" stroke="#DA4141" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                                                                            <path d="M18.5 5H30.5" stroke="#DA4141" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                                                                            <path d="M12.5 17H36.5V40C36.5 41.6569 35.1569 43 33.5 43H15.5C13.8431 43 12.5 41.6569 12.5 40V17Z" fill="#FEE2E2" stroke="#DA4141" stroke-width="4" stroke-linejoin="round" />
                                                                            <path d="M20.5 25L28.5 33" stroke="#DA4141" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                                                                            <path d="M28.5 25L20.5 33" stroke="#DA4141" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                                                                        </svg>
                                                                    </div>
                                                                    Hapus data
                                                                    <h3 class="text-xl text-wrap font-normal text-gray-500 mt-5 mb-6">Apakah anda yakin
                                                                        untuk menghapus data ini?</h3>
                                                                    <form action="/pengajuanmedis/hapus/" method="POST">
                                                                        <?= csrf_field() ?>
                                                                        <div class="w-full sm:flex justify-center">
                                                                            <input type="hidden" name="_method" value="DELETE">
                                                                            <button onclick="closeModal('modelConfirm-')" class="w-full text-white bg-red-600 hover:bg-red-800 focus:ring-4 font-medium rounded-lg text-base inline-flex items-center justify-center px-3 py-2.5 text-center mr-2">
                                                                                Hapus
                                                                            </button>

                                                                            <a href="#" onclick="closeModal('modelConfirm-')" class="w-full text-gray-900 bg-white hover:bg-gray-100 focus:ring-4 border border-gray-200 font-medium inline-flex items-center justify-center rounded-lg text-base px-3 py-2.5 text-center" data-modal-toggle="delete-user-modal">
                                                                                Batal
                                                                            </a>
                                                                        </div>
                                                                    </form>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                
                                                    <div class="px-3 py-1.5">
                                                        <button class="cursor-default gap-x-1 text-sm text-[#C4C4C4] decoration-2 font-semibold dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                                                            Ubah
                                                        </button>
                                                    </div>

                                                    <div class="px-3 py-1.5">
                                                        <button class="cursor-default gap-x-1 text-sm text-[#C4C4C4] decoration-2 font-semibold dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                                                            Hapus
                                                        </button>

                                                    </div>
                                               
                                            </div>
                                        </td>

                                    </tr>
                           
                        </tbody>
                    </table>
                    <!-- End Table -->

                    <!-- Footer -->
                    <div class="px-6 py-4 grid gap-3 md:flex md:justify-between md:items-center border-t border-gray-200 dark:border-neutral-700">
                        <!-- Pagination -->

                        <nav class="flex w-full justify-between items-center gap-x-1">
                            <!-- Previous Button -->
                            <div class="inline-flex gap-x-2">
                                <button type="button" class="min-h-[38px] min-w-[38px] py-2 px-2.5 inline-flex justify-center items-center gap-x-2 text-sm rounded-lg text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-white/10 dark:focus:bg-white/10" aria-label="Previous page"">
                                    <svg class="flex-shrink-0 size-3.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="m15 18-6-6 6-6"></path>
                                    </svg>
                                    <span aria-hidden="true" class="hidden sm:block">Previous</span>
                                </button>
                            </div>

                            <!-- Page Numbers -->
                            <div class="flex items-center gap-x-1">
                               
                            </div>

                            <!-- Next Button -->
                            <div class="inline-flex gap-x-2">
                                <button type="button" class="min-h-[38px] min-w-[38px] py-2 px-2.5 inline-flex justify-center items-center gap-x-2 text-sm rounded-lg text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-white/10 dark:focus:bg-white/10" aria-label="Next page"">
                                    <span aria-hidden="true" class="hidden sm:block">Next</span>
                                    <svg class="flex-shrink-0 size-3.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="m9 18 6-6-6-6"></path>
                                    </svg>
                                </button>
                            </div>
                        </nav>

                    </div>


                    <!-- End Footer -->
                </div>
            </div>
        </div>
    </div>
    <!-- End Card -->
</div>
<!-- End Table Section -->
<?= $this->endSection(); ?>