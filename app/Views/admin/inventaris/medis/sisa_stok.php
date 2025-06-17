<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<!-- Table Section -->
<div class="max-w-[85rem] py-6 lg:py-3 px-8 mx-auto">
    <!-- Card -->
    <div class="flex flex-col">
        <div class="-m-1.5 overflow-y-auto">
            <div class="sm:px-6 min-w-full inline-block align-middle">

                <div class="p-5 bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden dark:bg-slate-900 dark:border-gray-700">
                    <!-- Header -->
                    <div class="py-1 flex justify-between items-center border-gray-200 dark:border-gray-700">
                        <div>
                            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">
                                Sisa Stok Barang Medis
                            </h2>

                        </div>

                    </div>
                    <!-- End Header -->
                    <?= view('components/search_bar') ?>

                    <div id="noDataFound" style="display: none;">Data tidak ditemukan</div>
                    <!-- Table -->

                    <table id="myTable" class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-slate-800">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-center">
                                    <div class="flex items-center justify-center">
                                        <span class="text-xs tracking-wide text-[#666666]">
                                            Nama Barang
                                        </span>
                                    </div>
                                </th>
                                <?php foreach ($ruangan_data as $ruangan) { ?>
                                    <th scope="col" class="px-6 py-3 text-center">
                                        <div class="flex items-center justify-center">
                                            <span class="text-xs tracking-wide text-[#666666]">
                                                <?= $ruangan['nama'] ?>
                                            </span>
                                        </div>
                                    </th>
                                <?php } ?>
                                <th scope="col" class="px-6 py-3 text-center">
                                    <div class="flex items-center justify-center">
                                        <span class="text-xs tracking-wide text-[#666666]">
                                            Total
                                        </span>
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-3 text-center">
                                    <div class="flex items-center justify-center">
                                        <span class="text-xs tracking-wide text-[#666666]">
                                            Nilai Aset
                                        </span>
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <div class="overflow-x-auto">
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                <?php foreach ($medis_data as $medis) { ?>
                                    <tr>
                                        <td class="h-px w-64 whitespace-nowrap">
                                            <div class="px-6 py-3">
                                                <span class="text-center block text-sm font-semibold text-gray-800 dark:text-gray-200"><?= $medis['nama'] ?></span>
                                            </div>
                                        </td>
                                        <?php $stok_keseluruhan = 0;
                                        foreach ($ruangan_data as $ruangan) { ?>
                                            <td class="h-px w-64 whitespace-nowrap">
                                                <div class="px-6 py-3">
                                                    <span class="text-center block text-sm font-semibold text-gray-800 dark:text-gray-200">
                                                        <?php
                                                        // Find the stock for this room and this medical item
                                                        $stock_found = false;
                                                        foreach ($gudang_data as $gudang) {
                                                            if ($gudang['id_barang_medis'] === $medis['id'] && $gudang['id_ruangan'] === $ruangan['id']) {
                                                                $stok_keseluruhan += $gudang['stok'];
                                                                echo $gudang['stok'];
                                                                $stock_found = true;
                                                            }
                                                        }
                                                        if (!$stock_found) {
                                                            echo '0';
                                                        }
                                                        ?>
                                                    </span>
                                                </div>
                                            </td>
                                        <?php } ?>
                                        <td class="h-px w-64 whitespace-nowrap">
                                            <div class="px-6 py-3">
                                                <span class="text-center block text-sm font-semibold text-gray-800 dark:text-gray-200"><?= $stok_keseluruhan ?></span>
                                            </div>
                                        </td>
                                        <td class="h-px w-64 whitespace-nowrap">
                                            <div class="px-6 py-3">
                                                <span class="text-center block text-sm font-semibold text-gray-800 dark:text-gray-200"><?php $nilai = $stok_keseluruhan * $medis['h_beli'];
                                                                                                                                        echo $nilai; ?></span>
                                            </div>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </div>
                    </table>

                    <!-- End Table -->

                    <!-- Footer -->
                    <div class="px-6 py-4 grid gap-3 md:flex md:justify-between md:items-center border-t border-gray-200 dark:border-neutral-700">
                        <!-- Pagination -->

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
        var input, filter, table, tr, td, i, j, txtValue;
        input = document.getElementById("myInput");
        filter = input.value.toUpperCase();
        table = document.getElementById("myTable"); // Pastikan ini mengacu pada ID tabel yang benar

        if (!table) return; // Pastikan tabel ada sebelum melanjutkan

        tr = table.getElementsByTagName("tr");
        var dataFound = false;

        // Iterate over all table rows (including header row)
        for (i = 0; i < tr.length; i++) {
            var found = false;

            // Check if it's a regular row (skip header row)
            if (i > 0) {
                td = tr[i].getElementsByTagName("td");

                // Iterate over all td elements in the row
                for (j = 0; j < td.length; j++) {
                    txtValue = td[j].textContent || td[j].innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        found = true;
                        break; // Break out of inner loop if match found
                    }
                }

                // Show or hide row based on search result
                if (found) {
                    tr[i].style.display = "";
                    dataFound = true;
                } else {
                    tr[i].style.display = "none";
                }
            }
        }
    }

    

    document.addEventListener("DOMContentLoaded", function() {
        // Select the warning message element
        var warningMessage = document.getElementById('warningMessage');

        // Check if the warning message exists
        if (warningMessage) {
            // Fade out the warning message after 5 seconds (5000 milliseconds)
            setTimeout(function() {
                warningMessage.style.opacity = '0';
                // Optionally, remove the element from the DOM after fading out
                setTimeout(function() {
                    warningMessage.remove();
                }, 1000); // 1 second delay after fading out
            }, 5000); // 5 seconds delay before fading out
        }
    });
</script>
<?= $this->endSection(); ?>