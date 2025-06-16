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
                                Rujukan Masuk
                            </h2>

                        </div>
                        <div class="flex gap-x-6 justify-center items-center">
                            <div class="relative">
                                <?= view('components/notif_icon') ?>

                                <!-- Notification Pop-up -->
                                <div id="notif-popup" class="absolute right-0 mt-2 w-[30rem] overflow-y-auto z-[2] bg-white rounded-lg shadow-lg hidden">
                                    <div class="px-4">
                                        <div class="pt-4 flex justify-between items-center">
                                            <div class="text-lg font-semibold">Notifikasi</div>
                                            <svg id="close-popup" class="cursor-pointer" xmlns="http://www.w3.org/2000/svg" width="37" height="36" viewBox="0 0 37 36" fill="none">
                                                <path d="M20.09 18L23.54 14.55C23.975 14.115 23.975 13.395 23.54 12.96C23.105 12.525 22.385 12.525 21.95 12.96L18.5 16.41L15.05 12.96C14.615 12.525 13.895 12.525 13.46 12.96C13.025 13.395 13.025 14.115 13.46 14.55L16.91 18L13.46 21.45C13.025 21.885 13.025 22.605 13.46 23.04C13.685 23.265 13.97 23.37 14.255 23.37C14.54 23.37 14.825 23.265 15.05 23.04L18.5 19.59L21.95 23.04C22.175 23.265 22.46 23.37 22.745 23.37C23.03 23.37 23.315 23.265 23.54 23.04C23.975 22.605 23.975 21.885 23.54 21.45L20.09 18Z" fill="#272727" />
                                            </svg>
                                        </div>
                                        <div class="flex">
                                            <div class="flex justify-center items-center w-3/4">
                                                <button id="stok-tab" class="flex items-center justify-center text-center w-full py-2 border-b-2 border-[#272727]">
                                                    Stok
                                                    <span class="ml-1"> <!-- Add margin-left for spacing -->
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 15 15" fill="none">
                                                            <circle cx="7.75" cy="7.5" r="7" fill="#EF4444" />
                                                            <text x="50%" y="45%" text-anchor="middle" dominant-baseline="central" fill="#FFF" font-size="10px"></text>
                                                        </svg>
                                                    </span>
                                                </button>
                                            </div>
                                            <div class="flex justify-center items-center w-3/4">
                                                <button id="kadaluwarsa-tab" class="flex items-center justify-center text-center w-full py-2 border-b-2">Kadaluwarsa
                                                    <span class="ml-1">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 15 15" fill="none">
                                                            <circle cx="7.75" cy="7.5" r="7" fill="#EF4444" />
                                                            <text x="50%" y="45%" text-anchor="middle" dominant-baseline="central" fill="#FFF" font-size="10px"></text>
                                                        </svg>
                                                    </span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <div id="stok-content" class="max-h-[15rem] overflow-y-auto">
                                            <?php
                                            $count_notif_stok = 0;
                                            $today = new DateTime();
                                            $today->setTime(0, 0, 0);
                                            if ($count_notif_stok !== 0) {
                                                foreach ($rujukanmasuk_tanpa_params_data as $rujukanmasuk_stok) {
                                                    if ($rujukanmasuk_stok['nomor_rujuk'] <= $rujukanmasuk_stok['nomor_rujuk']) {
                                                        $count_notif_stok++; ?>
                                                        <a href="/datamedis/edit/<?= $rujukanmasuk_stok['nomor_rujuk'] ?>" class="p-4 flex items-center border-b-2 border-b-[#F1F1F1] border-l-2 border-l-[#DA4141] hover:bg-gray-100">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32" fill="none">
                                                                <path d="M12.5358 6.667C14.0754 4.00033 17.9244 4.00033 19.464 6.66699L27.8356 21.167C29.3752 23.8337 27.4507 27.167 24.3715 27.167H7.62834C4.54914 27.167 2.62464 23.8337 4.16424 21.167L12.5358 6.667Z" fill="#DA4141" />
                                                                <path d="M16 18.333C15.4533 18.333 15 17.8797 15 17.333V10.333C15 9.78634 15.4533 9.33301 16 9.33301C16.5467 9.33301 17 9.78634 17 10.333V17.333C17 17.8797 16.5467 18.333 16 18.333Z" fill="#FEE2E2" />
                                                                <path d="M15.9998 23.0001C15.8265 23.0001 15.6532 22.9601 15.4932 22.8934C15.3198 22.8268 15.1865 22.7335 15.0531 22.6135C14.9331 22.4802 14.8398 22.3335 14.7598 22.1735C14.6932 22.0135 14.6665 21.8401 14.6665 21.6668C14.6665 21.3201 14.7998 20.9734 15.0531 20.7201C15.1865 20.6001 15.3198 20.5068 15.4932 20.4402C15.9865 20.2268 16.5732 20.3468 16.9465 20.7201C17.0665 20.8534 17.1598 20.9868 17.2265 21.1601C17.2931 21.3201 17.3332 21.4935 17.3332 21.6668C17.3332 21.8401 17.2931 22.0135 17.2265 22.1735C17.1598 22.3335 17.0665 22.4802 16.9465 22.6135C16.6932 22.8668 16.3598 23.0001 15.9998 23.0001Z" fill="#FEE2E2" />
                                                            </svg>
                                                            <div class="mx-2">
                                                                <span>Stok <span class="font-semibold"><?= $rujukanmasuk_stok['nomor_rujuk'] ?></span> telah mencapai jumlah minimum</span>
                                                                <div class="py-1 font-semibold text-sm text-[#DA4141]">Sisa stok: <?= $rujukanmasuk_stok['nomor_rujuk'] ?></div>
                                                            </div>
                                                        </a>

                                                <?php }
                                                }
                                            } else { ?>
                                                <button class="p-4 flex items-center border-b-2 border-b-[#F1F1F1] border-l-2 border-l-[#DA4141] hover:bg-gray-100">

                                                    <div class="mx-2">
                                                        <span>Belum ada notifikasi stok</span>

                                                    </div>
                                                </button>
                                            <?php
                                            } ?>

                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="h-[1.375rem] border-r-4 bg-[#DCDCDC]"></div>
                            <?= view('components/tambah_button', [
                                'link' => '/rujukanmasuk/tambah'
                            ]) ?>

                        </div>
                    </div>
                    <!-- End Header -->
                    <?php
                        echo view('components/search_bar');
                        
                        $api_url  = '/rujukanmasuk';
                        $tabel    = $rujukanmasuk_data;
                        $kolom_id = 'nomor_rujuk';
                        $aksi = [
                            'cetak'    => false,
                            'tindakan' => false,
                            'detail'   => true,
                            'ubah'     => true,
                            'hapus'    => true,
                        ];
                        $data = [
                            // [visible, Display, Kolom, Jenis]
                            [1, 'Nomor Rujuk'      , 'nomor_rujuk'   , 'indeks'],
                            [1, 'Perujuk'          , 'perujuk'       , 'nama'],
                            [0, 'Alamat Perujuk'   , 'alamat_perujuk', 'teks'],
                            [0, 'Nomor Rawat'      , 'nomor_rawat'   , 'indeks'],
                            [0, 'Nomor Rekam Medis', 'nomor_rm'      ,'indeks'],
                            [1, 'Nama Pasien'      , 'nama_pasien'   , 'nama'],
                            [0, 'Alamat'           , 'alamat'        , 'teks'],
                            [0, 'Umur'             , 'umur'          , 'jumlah'], 
                            [1, 'Tanggal Masuk'    , 'tanggal_masuk' , 'tanggal'],
                            [0, 'Tanggal Keluar'   , 'tanggal_keluar', 'tanggal'],
                            [1, 'Diagnosa Awal'    , 'diagnosa_awal' , 'teks'],
                        ];
                        echo view('components/tabel', [
                            'api_url'   => $api_url,
                            'tabel'     => $tabel,
                            'kolom_id'  => $kolom_id,
                            'data'      => $data,
                            'aksi'      => $aksi
                        ]);
                        
                        echo view('components/footer', [
                            'meta_data' => $meta_data,
                            'api_url'   => $api_url
                        ]);      
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Card -->

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
        var count_notif_stok = <?= $count_notif_stok ?>;
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

    
</script>
<?= $this->endSection(); ?>