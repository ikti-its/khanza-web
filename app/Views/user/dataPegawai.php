<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>





<!-- Table Section -->
<div class="overflow overflow-auto px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto">
    <!-- Card -->
    <div class="flex flex-col">
        <div class="-m-3.5 overflow-y-auto">
            <div class="p-1.5 w-full inline-block align-middle">
                <div class="border bg-white border-gray-200 rounded-xl shadow-sm overflow-hidden dark:bg-neutral-900 dark:border-neutral-700">
                    <!-- Header -->
                    <div class="px-6 py-5 grid gap-3 md:flex md:justify-between md:items-center">
                        <div class="sm:col-span-12">
                            <h2 class="text-lg font-bold text-gray-800 dark:text-neutral-200">
                                Ketersediaan Pegawai
                            </h2>
                        </div>

                    </div>
                    <!-- End Header -->

                    <div class="px-6 py-4 grid gap-3 md:flex md:justify-between md:items-center border-gray-200 dark:border-neutral-700">
                        <div class="sm:col-span-1">
                            <label for="hs-as-table-product-review-search" class="sr-only">Search</label>
                            <div class="relative">
                                <input type="text" id="searchInput" onkeyup="searchFunction()" class="py-2 px-4 ps-11 block border w-full xl:w-96 border-gray-200 rounded-lg text-sm focus:border-teal-500 focus:ring-teal-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600" placeholder="Search">
                                <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none ps-4">
                                    <svg class="size-4 text-gray-400 dark:text-neutral-500" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- Card Section -->
                    <div class="max-w-[85rem] px-6 py-2 sm:px-6 lg:px-8 lg:py-4 mx-auto">
                        <!-- Grid -->
                        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-2 sm:gap-4"> <!-- Adjusted gap here -->
                            <?php foreach ($ketersediaan_data as $ketersediaanEntry) : ?>
                                <!-- Card -->
                                <div class="employee-card flex flex-col gap-y-2 lg:gap-y-3 p-3 md:p-4 bg-white border shadow-sm rounded-lg dark:bg-neutral-900 dark:border-neutral-800"> <!-- Adjusted padding here -->
                                    <!-- Grid -->
                                    <div class="mb-1 pb-3 flex justify-between items-center border-b border-gray-200 dark:border-neutral-700"> <!-- Adjusted padding and margin here -->

                                        <!-- Col -->
                                        <div class="inline-flex gap-x-2">
                                            <img class="inline-block size-[38px] rounded-full ring-2 ring-white dark:ring-gray-800" src="<?= $ketersediaanEntry['foto'] ?>" alt="Image Description">
                                            <div class="employee-name py-1 px-2 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-white text-black"> <!-- Adjusted padding here -->
                                                <?= $ketersediaanEntry['nama'] ?? 'N/A' ?>
                                            </div>
                                        </div>
                                        <!-- Col -->

                                        <div class="inline-flex items-center gap-x-1 text-[#24A793]">
                                            <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M20.992,9.98A8.991,8.991,0,0,0,3.01,9.932a13.95,13.95,0,0,0,8.574,12.979A1,1,0,0,0,12,23a1.012,1.012,0,0,0,.419-.09A13.948,13.948,0,0,0,20.992,9.98ZM12,20.9A11.713,11.713,0,0,1,5.008,10a6.992,6.992,0,1,1,13.984,0c0,.021,0,.045,0,.065A11.7,11.7,0,0,1,12,20.9ZM12,6a4,4,0,1,0,4,4A4,4,0,0,0,12,6Zm0,6a2,2,0,1,1,2-2A2,2,0,0,1,12,12Z" />
                                            </svg>
                                            <h2 class="text-m font-semibold dark:text-neutral-200"><?= number_format($ketersediaanEntry['distance'], 2) ?? 'N/A' ?> km</h2>
                                        </div>
                                    </div>
                                    <!-- End Grid -->

                                    <!-- Grid -->
                                    <div class="grid md:grid-cols-2 gap-2 lg:h-60 sm:h-96 md:h-96"> <!-- Adjusted height here -->
                                        <div>
                                            <div class="grid space-y-2">
                                                <div class="grid sm:flex gap-x-2 text-xs">
                                                    <div class="py-4 first:pt-0 last:pb-0 border-t first:border-transparent border-gray-200 dark:border-neutral-700 dark:first:border-transparent"> <!-- Adjusted padding here -->
                                                        <label for="af-payment-billing-contact" class="inline-block font-normal dark:text-white">
                                                            NIP
                                                        </label>

                                                        <div class="mt-2 space-y-3">
                                                            <div id="af-payment-billing-contact" class="py-1 pe-11 block w-full font-medium border-gray-200 rounded-lg disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600" placeholder="First Name"><?= $ketersediaanEntry['nip'] ?? 'N/A' ?></div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="grid sm:flex gap-x-2 text-xs">
                                                    <div class="py-4 first:pt-0 last:pb-0 border-t first:border-transparent border-gray-200 dark:border-neutral-700 dark:first:border-transparent"> <!-- Adjusted padding here -->
                                                        <label for="af-payment-billing-contact" class="inline-block font-normal dark:text-white">
                                                            No. Telepon
                                                        </label>

                                                        <div class="mt-2 space-y-3">
                                                            <div id="af-payment-billing-contact" class="py-1 pe-11 block w-full font-medium border-gray-200 rounded-lg disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600" placeholder="First Name"><?= $ketersediaanEntry['telepon'] ?? 'N/A' ?></div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="grid sm:flex gap-x-2 text-xs">
                                                    <div class="py-4 first:pt-0 last:pb-0 border-t first:border-transparent border-gray-200 dark:border-neutral-700 dark:first:border-transparent"> <!-- Adjusted padding here -->
                                                        <label for="af-payment-billing-contact" class="inline-block font-normal dark:text-white">
                                                            Alamat
                                                        </label>

                                                        <div class="mt-2 space-y-3">
                                                            <div id="af-payment-billing-contact" class="py-1 pe-11 block w-full font-medium border-gray-200 rounded-lg disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600" placeholder="First Name"><?= $ketersediaanEntry['alamat'] ?? 'N/A' ?></div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        <!-- Col -->

                                        <div>
                                            <div class="grid space-y-2">
                                                <div class="grid sm:flex gap-x-2 text-xs">
                                                    <div class="py-4 first:pt-0 last:pb-0 border-t first:border-transparent border-gray-200 dark:border-neutral-700 dark:first:border-transparent"> <!-- Adjusted padding here -->
                                                        <label for="af-payment-billing-contact" class="inline-block font-normal dark:text-white">
                                                            Jabatan
                                                        </label>

                                                        <div class="mt-2 space-y-3">
                                                            <div id="af-payment-billing-contact" class="py-1 pe-11 block w-full font-medium border-gray-200 rounded-lg disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600" placeholder="First Name"><?= $ketersediaanEntry['jabatan'] ?? 'N/A' ?></div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="grid sm:flex gap-x-2 text-xs">
                                                    <div class="py-4 first:pt-0 last:pb-0 border-t first:border-transparent border-gray-200 dark:border-neutral-700 dark:first:border-transparent"> <!-- Adjusted padding here -->
                                                        <label for="af-payment-billing-contact" class="inline-block font-normal dark:text-white">
                                                            Departemen
                                                        </label>

                                                        <div class="mt-2 space-y-3">
                                                            <div id="af-payment-billing-contact" class="py-1 pe-11 block w-full font-medium border-gray-200 rounded-lg disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600" placeholder="First Name"><?= $ketersediaanEntry['departemen'] ?? 'N/A' ?></div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        <!-- Col -->
                                    </div>

                                    <div class="relative py-3">
                                        <!-- Container with fixed height to ensure consistent spacing -->
                                        <div class="flex flex-col items-center mb-3 h-10 md:h-12 lg:h-14 xl:h-16"> <!-- Adjusted height here -->
                                            <?php if ($ketersediaanEntry['available'] === false) : ?>
                                                <!-- Pegawai Sedang Cuti Message -->
                                                <div class="mb-2">
                                                    <div class="text-red-500 text-sm text-center">
                                                        Pegawai Sedang Cuti
                                                    </div>
                                                </div>
                                            <?php endif; ?>

                                            <!-- Spacer for consistent positioning when message is not present -->
                                            <?php if ($ketersediaanEntry['available'] !== false) : ?>
                                                <div class="mb-2 invisible">
                                                    <div class="text-red-500 text-sm text-center">
                                                        Placeholder
                                                    </div>
                                                </div>
                                            <?php endif; ?>

                                            <!-- Grid -->
                                            <div class="flex justify-center items-center">
                                                <!-- Col -->
                                                <?php
                                                $phoneNumber2 = $ketersediaanEntry['telepon'];
                                                $message2 = "Kami butuh Anda di rumah sakit, apakah Anda bersedia?";
                                                $whatsappLink2 = "https://wa.me/$phoneNumber2?text=" . urlencode($message2);
                                                ?>
                                                <a id="hubungiLink" class="py-2 px-12 md:px-14 lg:px-16 xl:px-20 inline-flex justify-center items-center gap-2 rounded-lg border font-medium bg-[#0A2D27] text-[#ACF2E7] shadow-sm align-middle hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-white focus:ring-teal-600 transition-all text-sm dark:bg-neutral-900 dark:hover:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-400 dark:hover:text-white dark:focus:ring-offset-gray-800" href="<?= $whatsappLink2 ?>" onclick="sendWhatsAppAndRedirect(event)">
                                                    Hubungi
                                                </a>

                                            </div>
                                            <!-- End Grid -->

                                            <script>
                                                function sendWhatsAppAndRedirect(event) {
                                                    event.preventDefault(); // Prevent the default action of the link

                                                    var phoneNumber = <?= json_encode($phoneNumber2) ?>; // Replace with your dynamic phone number
                                                    var message = "Kami butuh bantuan Anda di rumah sakit, apakah bersedia?"; // Replace with your desired message
                                                    var whatsappLink = "https://wa.me/" + phoneNumber + "?text=" + encodeURIComponent(message);

                                                    // Open WhatsApp link in a new window
                                                    window.open(whatsappLink, '_blank');

                                                    // Submit the hidden form
                                                    document.getElementById('notificationForm').submit();

                                                    // Redirect to another page after a short delay (adjust delay as needed)
                                                    setTimeout(function() {
                                                        window.location.href = '/kirimnotifikasi'; // Replace with your desired redirect URL
                                                    }, 1000); // 1000 milliseconds = 1 second
                                                }
                                            </script>

                                            <form id="notificationForm" action="/kirimnotifikasi" method="POST" style="display: none;">
                                                <input type="hidden" id="id_penerima" name="id_penerima" value="<?= $ketersediaanEntry['pegawai'] ?>">
                                                <input type="hidden" id="judul" name="judul" value="INI JUDUL">
                                                <input type="hidden" id="pesan" name="pesan" value="INI PESAN">
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Card -->
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div id="footer" class="px-6 py-4 grid gap-3 md:flex md:justify-between md:items-center border-t border-gray-200 dark:border-neutral-700">
                        <!-- Pagination -->
                        <nav class="flex w-full justify-between items-center gap-x-1">
                            <!-- Previous Button -->
                            <div class="inline-flex gap-x-2">
                                <button type="button" class="min-h-[38px] min-w-[38px] py-2 px-2.5 inline-flex justify-center items-center gap-x-2 text-sm rounded-lg text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-white/10 dark:focus:bg-white/10" aria-label="Previous page" <?= $meta_ketersediaan_data['page'] <= 1 ? 'disabled' : '' ?> onclick="window.location.href='/datauserpegawai?page=<?= $meta_ketersediaan_data['page'] - 1 ?>&size=<?= $meta_ketersediaan_data['size'] ?>'">
                                    <svg class="flex-shrink-0 size-3.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="m15 18-6-6 6-6"></path>
                                    </svg>
                                    <span aria-hidden="true" class="hidden sm:block">Kembali</span>
                                </button>
                            </div>

                            <!-- Page Numbers -->
                            <div class="flex items-center gap-x-1">
                                <?php for ($i = 1; $i <= $meta_ketersediaan_data['total']; $i++) : ?>
                                    <button type="button" class="min-h-[38px] min-w-[38px] flex justify-center items-center <?= $meta_ketersediaan_data['page'] == $i ? 'bg-gray-200 text-gray-800 dark:bg-neutral-600 dark:focus:bg-neutral-500' : 'text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:text-white dark:hover:bg-white/10 dark:focus:bg-white/10' ?> py-2 px-3 text-sm rounded-lg" <?= $meta_ketersediaan_data['page'] == $i ? 'aria-current="page"' : '' ?> onclick="window.location.href='/datauserpegawai?page=<?= $i ?>&size=<?= $meta_ketersediaan_data['size'] ?>'">
                                        <?= $i ?>
                                    </button>
                                <?php endfor; ?>
                            </div>

                            <!-- Next Button -->
                            <div class="inline-flex gap-x-2">
                                <button type="button" class="min-h-[38px] min-w-[38px] py-2 px-2.5 inline-flex justify-center items-center gap-x-2 text-sm rounded-lg text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-white/10 dark:focus:bg-white/10" aria-label="Next page" <?= $meta_ketersediaan_data['page'] >= $meta_ketersediaan_data['total'] ? 'disabled' : '' ?> onclick="window.location.href='/datauserpegawai?page=<?= $meta_ketersediaan_data['page'] + 1 ?>&size=<?= $meta_ketersediaan_data['size'] ?>'">
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
    function searchFunction() {
        // Declare variables
        var input, filter, cards, card, name, i, txtValue;
        input = document.getElementById('searchInput');
        filter = input.value.toUpperCase();
        cards = document.getElementsByClassName('employee-card'); // Add a class 'employee-card' to each card element

        // Loop through all employee cards, and hide those who don't match the search query
        for (i = 0; i < cards.length; i++) {
            card = cards[i];
            name = card.querySelector('.employee-name'); // Assuming you add a class 'employee-name' to the element containing employee name
            if (name) {
                txtValue = name.textContent || name.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    card.style.display = "";
                } else {
                    card.style.display = "none";
                }
            }
        }
    }
</script>



<?= $this->endSection(); ?>