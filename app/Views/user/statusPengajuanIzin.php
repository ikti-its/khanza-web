<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>




<!-- Table Section -->
<div class="overflow overflow-auto px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto">
    <!-- Card -->
    <div class="flex flex-col">
        <div class="-m-3.5 overflow-x-auto">
            <div class="p-1.5 w-full inline-block align-middle">
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden dark:bg-neutral-900 dark:border-neutral-700">


                    <div class="px-6 pt-2 grid gap-3 md:flex md:justify-between md:items-center dark:border-neutral-700">


                        <div class="grid gap-3 md:flex md:justify-between md:items-center">
                            <div class="sm:col-span-12">
                                <h2 class="text-lg font-semibold text-gray-800 dark:text-neutral-200">
                                    Status Pengajuan Izin
                                </h2>
                            </div>

                        </div>

                        <div class="justify-end items-center">
                            <button type="button" class="py-2 px-4 my-2 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-[#0A2D27] text-[#ACF2E7] hover:bg-teal-900 disabled:opacity-50 disabled:pointer-events-none">

                                <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M20.9888 4.28491L19.6405 2.93089C18.4045 1.6897 16.4944 1.6897 15.2584 2.93089L13.0112 5.30042L18.7416 11.055L21.1011 8.68547C21.6629 8.1213 22 7.33145 22 6.54161C22 5.75176 21.5506 4.84908 20.9888 4.28491Z" fill="#030D45" />
                                    <path d="M16.2697 10.9422L11.7753 6.42877L2.89888 15.3427C2.33708 15.9069 2 16.6968 2 17.5994V21.0973C2 21.5487 2.33708 22 2.89888 22H6.49438C7.2809 22 8.06742 21.6615 8.74157 21.0973L17.618 12.1834L16.2697 10.9422Z" fill="#030D45" />
                                </svg>
                                Ubah Data Pegawai
                            </button>
                        </div>




                    </div>

                    <div class="px-6 py-4 grid gap-3 md:flex md:justify-between md:items-center border-gray-200 dark:border-neutral-700">
                        <div class="sm:col-span-1">
                            <label for="hs-as-table-product-review-search" class="sr-only">Search</label>
                            <div class="relative">
                                <input type="text" id="myInput" onkeyup="myFunction()" class="py-2 px-4 ps-11 block border w-full xl:w-96 border-gray-200 rounded-lg text-sm focus:outline-none focus:border-teal-500 focus:ring-teal-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600" placeholder="Search">
                                <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none ps-4">
                                    <svg class="size-4 text-gray-400 dark:text-neutral-500" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>



                    <!-- Table -->
                    <table id="myTable" class="min-w-full divide-y divide-gray-50 dark:divid e-neutral-700 text-xs">
                        <thead class="bg-gray-50 divide-y divide-gray-200 dark:bg-neutral-800 dark:divide-neutral-700">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-start border-s border-gray-200 dark:border-neutral-700">
                                    <span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200">
                                        Status Izin
                                    </span>
                                </th>

                                <th scope="col" class="px-6 py-3 text-start">
                                    <span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200">
                                        Tanggal Mulai
                                    </span>
                                </th>

                                <th scope="col" class="px-6 py-3 text-start">
                                    <span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200">
                                        Tanggal Selesai
                                    </span>
                                </th>

                                <th scope="col" class="px-6 py-3 text-start">
                                    <span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200">
                                        Alasan Cuti
                                    </span>
                                </th>

                                <th scope="col" class="px-6 py-3 text-start">
                                    <span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200">
                                        Aksi
                                    </span>
                                </th>

                                <th scope="col" class="px-6 py-3 text-end"></th>

                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-200 dark:divide-neutral-700 text-xs">
                            <? //php foreach ($kehadiran_data as $kehadiranEntry) : 
                            ?>
                            <tr>
                                <td class="h-px w-auto whitespace-nowrap">
                                    <div class="px-6 py-2 flex items-center gap-x-3">
                                        <a class="flex items-center gap-x-2" href="">
                                            <span class="font-semibold hover:underline"><? //= $kehadiranEntry['tanggal'] ?? 'N/A' 
                                                                                        ?>

                                                <span class="py-1 px-1.5 inline-flex items-center gap-x-1 text-xs font-medium bg-teal-100 text-teal-800 rounded-full dark:bg-teal-500/10 dark:text-teal-500">
                                                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="size-2.5">
                                                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                                        <g id="SVGRepo_iconCarrier">
                                                            <circle cx="12" cy="12" r="9" fill="#13594E"></circle>
                                                        </g>
                                                    </svg>
                                                    <?= $kehadiranEntry['keterangan'] ?? 'N/A' ?>
                                                </span>

                                            </span>
                                        </a>
                                    </div>
                                </td>
                                <td class="h-px w-auto whitespace-nowrap">
                                    <div class="px-6 py-2">
                                        <span class="font-semibold text-gray-800 dark:text-neutral-200"><? //= $kehadiranEntry['jam_masuk'] ?? 'N/A' 
                                                                                                        ?>21 May 2024</span>
                                    </div>
                                </td>

                                <td class="h-px w-auto whitespace-nowrap">
                                    <div class="px-6 py-2">
                                        <span class="font-semibold text-gray-800 dark:text-neutral-200"><? //= $kehadiranEntry['jam_masuk'] ?? 'N/A' 
                                                                                                        ?>30 May 2024</span>
                                    </div>
                                </td>
                                <td class="h-px w-auto whitespace-nowrap">
                                    <div class="px-6 py-2">
                                        <span class="font-semibold text-gray-800 dark:text-neutral-200"><? //= $kehadiranEntry['jam_masuk'] ?? 'N/A' 
                                                                                                        ?>Mager</span>
                                    </div>
                                </td>


                                <td class="size-px whitespace-nowrap">
                                    <div class="px-6 py2">
                                        <a href="/hapuspegawai/<? //= $pegawaiEntry['id'] 
                                                                ?>" class="inline-flex items-center gap-x-1 text-sm text-black decoration-2 hover:underline font-medium dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" href="#">
                                            Lihat Detail
                                        </a>
                                    </div>
                                </td>

                                <td class="size-px whitespace-nowrap">
                                    <div class="px-6 py-2">
                                        <a href="/editpegawai/<? //= $pegawaiEntry['id'] 
                                                                ?>" class="inline-flex items-center gap-x-1 text-sm text-teal-600 decoration-2 hover:underline font-medium dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" href="#">
                                            Ubah
                                        </a>
                                    </div>
                                </td>


                            </tr>
                            <? //php endforeach; 
                            ?>
                        </tbody>
                    </table>
                    <!-- End Table -->


                    <!-- Footer -->
                    <div class="px-6 py-4 grid gap-3 md:flex md:justify-between md:items-center border-t border-gray-200 dark:border-neutral-700">
                        <!-- Pagination -->
                        <nav class="flex w-full justify-between items-center gap-x-1">
                            <!-- Previous Button -->
                            <div class="inline-flex gap-x-2">
                                <button type="button" class="min-h-[38px] min-w-[38px] py-2 px-2.5 inline-flex justify-center items-center gap-x-2 text-sm rounded-lg text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-white/10 dark:focus:bg-white/10" aria-label="Previous page" <? //= $meta_data['page'] <= 1 ? 'disabled' : '' 
                                                                                                                                                                                                                                                                                                                                                                                        ?> onclick="window.location.href='/datauserpegawai?page=<? //= $meta_data['page'] - 1 
                                                                                                                                                                                                                                                                                                                                                                                                                                                ?>&size=<? //= $meta_data['size'] 
                                                                                                                                                                                                                                                                                                                                                                                                                                                        ?>'">
                                    <svg class="flex-shrink-0 size-3.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="m15 18-6-6 6-6"></path>
                                    </svg>
                                    <span aria-hidden="true" class="hidden sm:block">Kembali</span>
                                </button>
                            </div>

                            <!-- Page Numbers -->
                            <div class="flex items-center gap-x-1">
                                <? //php for ($i = 1; $i <= $meta_data['total']; $i++) : 
                                ?>
                                <button type="button" class="min-h-[38px] min-w-[38px] flex justify-center items-center <? //= $meta_data['page'] == $i ? 'bg-gray-200 text-gray-800 dark:bg-neutral-600 dark:focus:bg-neutral-500' : 'text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:text-white dark:hover:bg-white/10 dark:focus:bg-white/10' 
                                                                                                                        ?> py-2 px-3 text-sm rounded-lg" <? //= $meta_data['page'] == $i ? 'aria-current="page"' : '' 
                                                                                                                                                            ?> onclick="window.location.href='/datauserpegawai?page=<? //= $i 
                                                                                                                                                                                                                    ?>&size=<? //= $meta_data['size'] 
                                                                                                                                                                                                                            ?>'">
                                    <? //= $i 
                                    ?>
                                </button>
                                <? //php endfor; 
                                ?>
                            </div>

                            <!-- Next Button -->
                            <div class="inline-flex gap-x-2">
                                <button type="button" class="min-h-[38px] min-w-[38px] py-2 px-2.5 inline-flex justify-center items-center gap-x-2 text-sm rounded-lg text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-white/10 dark:focus:bg-white/10" aria-label="Next page" <? //= $meta_data['page'] >= $meta_data['total'] ? 'disabled' : '' 
                                                                                                                                                                                                                                                                                                                                                                                    ?> onclick="window.location.href='/datauserpegawai?page=<? //= $meta_data['page'] + 1 
                                                                                                                                                                                                                                                                                                                                                                                                                                            ?>&size=<? //= $meta_data['size'] 
                                                                                                                                                                                                                                                                                                                                                                                                                                                    ?>'">
                                    <span aria-hidden="true" class="hidden sm:block">Lanjut</span>
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

<script>
    function myFunction() {
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById("myInput");
        filter = input.value.toUpperCase();
        table = document.getElementById("myTable");
        tr = table.getElementsByTagName("tr");


        for (i = 0; i < tr.length; i++) {
            td = tr[i].getElementsByTagName("td")[0];
            if (td) {
                txtValue = td.textContent || td.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }
        }
    }
</script>

<?= $this->endSection(); ?>