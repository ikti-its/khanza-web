<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<!-- Table Section -->
<div class="max-w-[85rem] py-6 lg:py-3 mx-auto">
    <!-- <div class="max-w-[85rem] w-full py-6 lg:py-3"> -->
    <!-- Card -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <div class="flex flex-col">
        <div class="-m-1.5 overflow-y-hidden">
            <div class="sm:px-20 min-w-full inline-block align-middle">
                <div class="p-5 bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-slate-900 dark:border-gray-700">

                    <!-- Header -->
                    <div class="py-1 flex justify-between items-center border-gray-200 dark:border-gray-700">
                        <div>
                            <h2 class="mb-2 text-xl font-black text-gray-800 dark:text-gray-200">
                                Pengelolaan ambulans
                            </h2>

                        </div>
                        <div class="flex gap-x-6 justify-center items-center">
                            <div class="relative">
                                <?= view('components/notif_icon') ?>

                                <!-- Notification Pop-up -->
                                <div id="notif-popup" style="width: 600px;" class="absolute right-0 mt-2 overflow-y-auto z-[2] bg-white rounded-lg shadow-lg hidden">

                                    <div class="px-4">
                                        <div class="pt-4 flex justify-between items-center">
                                            <div class="text-lg font-semibold">Notifikasi</div>
                                            <svg id="close-popup" class="cursor-pointer" xmlns="http://www.w3.org/2000/svg" width="37" height="36" viewBox="0 0 37 36" fill="none">
                                                <path d="M20.09 18L23.54 14.55C23.975 14.115 23.975 13.395 23.54 12.96C23.105 12.525 22.385 12.525 21.95 12.96L18.5 16.41L15.05 12.96C14.615 12.525 13.895 12.525 13.46 12.96C13.025 13.395 13.025 14.115 13.46 14.55L16.91 18L13.46 21.45C13.025 21.885 13.025 22.605 13.46 23.04C13.685 23.265 13.97 23.37 14.255 23.37C14.54 23.37 14.825 23.265 15.05 23.04L18.5 19.59L21.95 23.04C22.175 23.265 22.46 23.37 22.745 23.37C23.03 23.37 23.315 23.265 23.54 23.04C23.975 22.605 23.975 21.885 23.54 21.45L20.09 18Z" fill="#272727" />
                                            </svg>
                                        </div>
                                        <div class="flex">
                                            <div class="flex flex-col gap-2 w-full" id="ambulansNotifSection">
                                                <!-- 🚑 Ambulans Notification -->
                                                <?php if (isset($ambulans_requests) && count($ambulans_requests) > 0): ?>
                                                    <p>Total request: <?= isset($ambulans_requests) ? count($ambulans_requests) : 0 ?></p>
                                                    <div class="font-semibold text-black flex items-center space-x-1 px-3">
                                                        <span class="text-red-600">🚨</span>
                                                        <span>Permintaan Ambulans</span>
                                                    </div>
                                                    <?php foreach ($ambulans_requests as $req): ?>
                                                        <div class="flex items-center justify-between p-4 hover:bg-gray-100 border-l-4 border-blue-500">
                                                            <div>
                                                                Ambulans <strong><?= esc($req['no_ambulans']) ?></strong> sedang diminta <?php
                                                                    $statusLabels = [
                                                                        'pending'   => 'Menunggu',
                                                                        'available'   => 'Tersedia',
                                                                        'accepted'   => 'Diterima'
                                                                    ];

                                                                    $status = $req['status'] ?? null;
                                                                    $label = $statusLabels[$status] ?? 'Tidak Diketahui';
                                                                    ?>

                                                                    <?= esc($label) ?>
                                                            </div>
                                                            <a href="/ambulans/terima/<?= esc($req['no_ambulans']) ?>" 
                                                            class="bg-blue-600 text-white text-sm px-3 py-1 rounded hover:bg-blue-700">
                                                                Terima
                                                            </a>
                                                        </div>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div>       
                                    </div>
                                </div>
                            </div>
                            <div class="h-[1.375rem] border-r-4 bg-[#DCDCDC]"></div>
                            <?= view('components/data_tambah_button', [
                                'link' => '/ambulans/tambah'
                            ]) ?>

                        </div>
                    </div>
                    <?= view('components/data_search_bar') ?>

                    <!-- End Header -->

                    <!-- Table -->
                    <div class="overflow-x-auto w-full">                       
                    <table id="myTable" class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <?php 
                            $widths  = [30, 25, 20, 25];
                            echo view('components/data_tabel_colgroup',['widths' => $widths]);
                            
                            $columns = [
                                'Nomor Ambulans',
                                'Supir',
                                'Status Ambulans',
                                'Aksi'
                            ];
                            echo view('components/data_tabel_thead',['columns' => $columns]);
                        ?>

                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            <?php foreach ($ambulans_data as $ambulans) : ?>
                                <div id="hs-vertically-centered-scrollable-modal-<?= $ambulans['no_ambulans'] ?>" class="hs-overlay hidden size-full fixed top-0 start-0 z-[80] pointer-events-none">
                                    <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto h-[calc(100%-3.5rem)] min-h-[calc(100%-3.5rem)] flex items-center ">
                                        <div class="overflow-y-auto w-full max-h-full flex flex-col bg-white border shadow-sm rounded-xl pointer-events-auto dark:bg-neutral-800 dark:border-neutral-700 dark:shadow-neutral-700/70">
                                            <div class="flex justify-between items-center py-3 px-4 border-b dark:border-neutral-700">
                                                <h3 class="font-bold text-gray-800 dark:text-white">
                                                    <?= $ambulans['no_ambulans'] ?>
                                                </h3>
                                                <button type="button" class="flex justify-center items-center size-7 text-sm font-semibold rounded-full border border-transparent text-gray-800 hover:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-neutral-700" data-hs-overlay="#hs-vertically-centered-scrollable-modal-<?= $ambulans['no_ambulans'] ?>">
                                                    <span class="sr-only">Close</span>
                                                    <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <path d="M18 6 6 18"></path>
                                                        <path d="m6 6 12 12"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                            <div class="p-4">
                                                <div class="space-y-4">
                                                <div>
                                                    <div class="mb-5 sm:block">
                                                        <label class="block mb-2 text-sm text-gray-900 dark:text-white">Nomor Ambulans</label>
                                                        <input type="text" name="" value="<?= $ambulans['no_ambulans'] ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full dark:border-gray-600 dark:text-white" readonly>
                                                    </div>

                                                    <div class="mb-5 sm:block">
                                                        <label class="block mb-2 text-sm text-gray-900 dark:text-white">Supir</label>
                                                        <input type="text" name="" value="<?= $ambulans['supir'] ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full dark:border-gray-600 dark:text-white" readonly>
                                                    </div>

                                                    <div class="mb-5 sm:block">
                                                        <label class="block mb-2 text-sm text-gray-900 dark:text-white">Status Ambulans</label>
                                                        <input type="text" name="" value="<?= $ambulans['status'] ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full dark:border-gray-600 dark:text-white" readonly>
                                                    </div>
                                                </div>

                                                </div>
                                                <div class="flex justify-end items-center gap-x-2 py-3 px-4 border-t dark:border-neutral-700">
                                                    <button type="button" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-800" data-hs-overlay="#hs-vertically-centered-scrollable-modal-<?= $ambulans['no_ambulans'] ?>">
                                                        Tutup
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <tr>
                                    <td class="h-px w-64 whitespace-nowrap">
                                        <div class="px-6 py-3">
                                            <span class="text-center block text-sm font-semibold text-gray-800 cursor-pointer dark:text-gray-200 hover:underline" data-hs-overlay="#hs-vertically-centered-scrollable-modal-<?= $ambulans['no_ambulans'] ?>" data-id="<?= $ambulans['no_ambulans'] ?>"><?= $ambulans['no_ambulans'] ?? 'N/A' ?></span>
                                        </div>
                                    </td>
                                    <td class="h-px w-64 whitespace-nowrap">
                                        <div class="px-6 py-3">
                                            <span class="text-center block text-sm font-semibold text-gray-800 cursor-pointer dark:text-gray-200 hover:underline" data-hs-overlay="#hs-vertically-centered-scrollable-modal-<?= $ambulans['no_ambulans'] ?>" data-id="<?= $ambulans['no_ambulans'] ?>"><?= $ambulans['supir'] ?? 'N/A' ?></span>
                                        </div>
                                    </td>
                                    <td class="size-px w-48 whitespace-nowrap">
                                        <div class="px-6 py-3 text-center">
                                            <span class="inline-flex items-center gap-x-1.5 py-1.5 px-3 rounded-full text-xs font-semibold bg-[#F1F1F1]">
                                                <span class="size-1.5 inline-block rounded-full bg-[#535353]"></span>
                                                <?php
                                                $status = $ambulans['status'] ?? null;

                                                switch ($status) {
                                                    case 'pending':
                                                        $statusText = 'Menunggu';
                                                        break;
                                                    case 'available':
                                                        $statusText = 'Tersedia';
                                                        break;
                                                    case 'accepted':
                                                        $statusText = 'Diterima';
                                                        break;
                                                    default:
                                                        $statusText = 'Tidak Diketahui';
                                                }
                                                ?>

                                                <?= $statusText ?>
                                            </span>
                                        </div>
                                    </td>
                                    <td class="size-px whitespace-nowrap">
                                        <div class="px-3 py-1.5 text-center inline-flex">
                                            <div class="px-3 py-1.5">
                                                <button type="button" class="gap-x-1 text-sm decoration-2 hover:underline font-semibold dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" data-hs-overlay="#hs-vertically-centered-scrollable-modal-<?= $ambulans['no_ambulans'] ?>">
                                                    Lihat Detail
                                                </button>
                                            </div>
                                            <div class="px-3 py-1.5">
                                                <a href="/ambulans/edit/<?= $ambulans['no_ambulans'] ?>" class="gap-x-1 text-sm text-blue-600 decoration-2 hover:underline font-semibold dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" href="#">
                                                    Ubah
                                                </a>
                                            </div>
                                            <div class="px-3 py-1.5">
                                                <button class="gap-x-1 text-sm text-red-600 decoration-2 hover:underline font-semibold dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" onclick="openModal('modelConfirm-<?= $ambulans['no_ambulans'] ?>')" href="#">
                                                    Hapus
                                                </button>
                                                <?php
                                                    $row_id  = $ambulans['no_ambulans'];
                                                    $api_url = '/ambulans/hapus/';
                                                    echo view('components/data_hapus_form',[
                                                        'row_id'  => $row_id,
                                                        'api_url' => $api_url   
                                                    ]) 
                                                ?>
                                            </div>
                                        </div>
                                    </td>


                                </tr>

                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    </div>

                    <!-- End Table -->

                    <!-- Footer -->
                    <div class="px-6 py-4 grid gap-3 md:flex md:justify-between md:items-center border-t border-gray-200 dark:border-neutral-700">
                        <!-- Pagination -->
                        <nav class="flex w-full justify-between items-center gap-x-1">
                            <!-- Previous Button -->
                            <div class="inline-flex gap-x-2">
                                <button type="button" class="min-h-[38px] min-w-[38px] py-2 px-2.5 inline-flex justify-center items-center gap-x-2 text-sm rounded-lg text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-white/10 dark:focus:bg-white/10" aria-label="Previous page" <?= $meta_data['page'] <= 1 ? 'disabled' : '' ?> onclick="window.location.href='/datamedis?page=<?= $meta_data['page'] - 1 ?>&size=<?= $meta_data['size'] ?>'">
                                    <svg class="flex-shrink-0 size-3.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="m15 18-6-6 6-6"></path>
                                    </svg>
                                    <span aria-hidden="true" class="hidden sm:block">Previous</span>
                                </button>
                            </div>

                            <!-- Page Numbers -->
                            <div class="flex items-center gap-x-1">
                                <?php
                                $total_pages = $meta_data['total'] ?? 1; // Ensure 'total' always has a value
                                $current_page = $meta_data['page'] ?? 1;

                                $range = 2; // Number of pages to show before and after the current page
                                $show_items = ($range * 2) + 1;

                                if ($total_pages <= $show_items) {
                                    for ($i = 1; $i <= $total_pages; $i++) {
                                        echo '<button type="button" class="min-h-[38px] min-w-[38px] flex justify-center items-center ' . ($current_page == $i ? 'bg-gray-200 text-gray-800 dark:bg-neutral-600 dark:focus:bg-neutral-500' : 'text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:text-white dark:hover:bg-white/10 dark:focus:bg-white/10') . ' py-2 px-3 text-sm rounded-lg" ' . ($current_page == $i ? 'aria-current="page"' : '') . ' onclick="window.location.href=\'/datamedis?page=' . $i . '&size=' . $meta_data['size'] . '\'">' . $i . '</button>';
                                    }
                                } else {
                                    if ($current_page > $range + 1) {
                                        echo '<button type="button" class="min-h-[38px] min-w-[38px] flex justify-center items-center text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:text-white dark:hover:bg-white/10 dark:focus:bg-white/10 py-2 px-3 text-sm rounded-lg" onclick="window.location.href=\'/datamedis?page=1&size=' . $meta_data['size'] . '\'">1</button>';
                                        if ($current_page > $range + 2) {
                                            echo '<span class="py-2 px-3 text-sm">...</span>';
                                        }
                                    }

                                    for ($i = max($current_page - $range, 1); $i <= min($current_page + $range, $total_pages); $i++) {
                                        echo '<button type="button" class="min-h-[38px] min-w-[38px] flex justify-center items-center ' . ($current_page == $i ? 'bg-gray-200 text-gray-800 dark:bg-neutral-600 dark:focus:bg-neutral-500' : 'text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:text-white dark:hover:bg-white/10 dark:focus:bg-white/10') . ' py-2 px-3 text-sm rounded-lg" ' . ($current_page == $i ? 'aria-current="page"' : '') . ' onclick="window.location.href=\'/datamedis?page=' . $i . '&size=' . $meta_data['size'] . '\'">' . $i . '</button>';
                                    }

                                    if ($current_page < $total_pages - $range - 1) {
                                        if ($current_page < $total_pages - $range - 2) {
                                            echo '<span class="py-2 px-3 text-sm">...</span>';
                                        }
                                        echo '<button type="button" class="min-h-[38px] min-w-[38px] flex justify-center items-center text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:text-white dark:hover:bg-white/10 dark:focus:bg-white/10 py-2 px-3 text-sm rounded-lg" onclick="window.location.href=\'/datamedis?page=' . $total_pages . '&size=' . $meta_data['size'] . '\'">' . $total_pages . '</button>';
                                    }
                                }
                                ?>
                            </div>

                            <!-- Next Button -->
                            <div class="inline-flex gap-x-2">
                                <button type="button" class="min-h-[38px] min-w-[38px] py-2 px-2.5 inline-flex justify-center items-center gap-x-2 text-sm rounded-lg text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-white/10 dark:focus:bg-white/10" aria-label="Next page" <?= $current_page >= $total_pages ? 'disabled' : '' ?> onclick="window.location.href='/datamedis?page=<?= $current_page + 1 ?>&size=<?= $meta_data['size'] ?>'">
                                    <span aria-hidden="true" class="hidden sm:block">Next</span>
                                    <svg class="flex-shrink-0 size-3.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="m9 18 6-6-6-6"></path>
                                    </svg>
                                </button>
                            </div>
                        </nav>
                    </div>

                </div>
                <!-- End Footer -->
            </div>
        </div>
    </div>
</div>
</div>
<!-- End Card -->

<!-- End Table Section -->
<script>
function fetchAmbulansRequests() {
  $.ajax({
    url: "http://127.0.0.1:8080/v1/ambulans/request/pending",
    method: "GET",
    success: function (res) {
      console.log("Ambulance response:", res); // Check if this logs in browser
      let notifHtml = "";

      if (res.data && res.data.length > 0) {
        res.data
        .filter(item => item.status === 'pending')
        .forEach(function (item) {
          notifHtml += `
            <div class="flex items-center justify-between p-4 hover:bg-gray-100 border-l-4 border-blue-500">
              🚑 Ambulans <strong>${item.no_ambulans}</strong> sedang diminta
              <a href="/ambulans/terima/${item.no_ambulans}" class="bg-blue-600 text-white text-sm px-3 py-1 rounded hover:bg-blue-700">Terima</a>
            </div>
          `;
        });
      } else {
        notifHtml = ""; // Clear section if no data
      }

      $("#ambulansNotifSection").html(notifHtml);
    },
    error: function () {
      $("#ambulansNotifSection").html(`<div class="text-red-500 p-2">Gagal memuat data permintaan ambulans.</div>`);
    }
  });
}

// Call initially and every 10s
fetchAmbulansRequests();
setInterval(fetchAmbulansRequests, 10000);


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

    function closeNotificationPopup() {
        document.getElementById('notif-popup').classList.add('hidden');
    }

    // Event listener untuk menutup pop up saat mengklik di luar pop up
    document.addEventListener('click', function(event) {
        const notifPopup = document.getElementById('notif-popup');
        const notifIcon = document.getElementById('notif-icon');

        // Periksa apakah yang diklik bukan bagian dari pop up notifikasi
        if (!notifPopup.contains(event.target) && event.target !== notifIcon) {
            closeNotificationPopup();
        }
    });

    // Event listener untuk menghindari menutup pop up saat mengklik ikon notifikasi
    document.getElementById('notif-icon').addEventListener('click', function(event) {
        event.stopPropagation(); // Menghentikan penyebaran event ke elemen lain
        document.getElementById('notif-popup').classList.toggle('hidden');
    });

    // Event listener untuk menutup pop up saat mengklik ikon X di dalam pop up
    document.getElementById('close-popup').addEventListener('click', function(event) {
        event.stopPropagation(); // Menghentikan penyebaran event ke elemen lain
        closeNotificationPopup();
    });
    document.addEventListener('DOMContentLoaded', function() {
        var count_notif_stok = <?= isset($count_notif_stok) ? $count_notif_stok : 0 ?>;
        document.querySelector('#stok-tab svg text').textContent = count_notif_stok;
    });

    document.addEventListener('DOMContentLoaded', function() {
        // Menghitung jumlah div elemen di dalam kadaluwarsa-content
        var kadaluwarsaContent = document.getElementById('kadaluwarsa-content');
        var divCount = kadaluwarsaContent.querySelectorAll('div.kadaluwarsabaris, a.kadaluwarsabaris').length;

        // Memperbarui teks dalam SVG
        var svgText = document.querySelector('#kadaluwarsa-tab svg text');
        svgText.textContent = divCount.toString();
    });
    // JavaScript to toggle between tabs
    document.getElementById('stok-tab').addEventListener('click', function() {
        document.getElementById('stok-content').classList.remove('hidden');
        document.getElementById('kadaluwarsa-content').classList.add('hidden');
        this.classList.add('border-[#272727]');
        document.getElementById('kadaluwarsa-tab').classList.remove('border-[#272727]');
    });

    document.getElementById('kadaluwarsa-tab').addEventListener('click', function() {
        document.getElementById('stok-content').classList.add('hidden');
        document.getElementById('kadaluwarsa-content').classList.remove('hidden');
        this.classList.add('border-[#272727]');
        document.getElementById('stok-tab').classList.remove('border-[#272727]');
    });

    window.openModal = function(modalId) {
        document.getElementById(modalId).style.display = 'block'
        document.getElementsByTagName('body')[0].classList.add('overflow-y-hidden')
    }

    window.closeModal = function(modalId) {
        document.getElementById(modalId).style.display = 'none'
        document.getElementsByTagName('body')[0].classList.remove('overflow-y-hidden')
    }

    document.addEventListener('DOMContentLoaded', function () {
        const notifText = document.querySelector('#stok-tab svg text');
        const count = <?= $count_notif_kamar ?? 0 ?>;

        if (notifText) {
            notifText.textContent = count > 0 ? count : '';
        }

        if (count > 0) {
            document.getElementById('notif-popup').classList.remove('hidden');
        }
    });
    document.querySelectorAll('.assign-room-btn').forEach(button => {
    button.addEventListener('click', async () => {
        const nomorReg = button.getAttribute('data-nomor-reg');
        
        try {
            const response = await fetch(`http://127.0.0.1:8080/v1/registrasi/${nomorReg}/assign-room`, {
                method: 'PUT',
            });

            const result = await response.json();

            if (response.ok) {
                alert('✅ ' + result.message);
                button.closest('div').remove(); // remove the item from list
            } else {
                alert('❌ Failed: ' + result.message);
            }
        } catch (err) {
            alert('❌ Error: ' + err.message);
        }
    });
});

</script>
<?= $this->endSection(); ?>