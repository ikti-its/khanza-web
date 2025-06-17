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
                                Observasi Rawat Inap Post Partum
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
                                                <?php if (isset($catatan_requests) && count($catatan_requests) > 0): ?>
                                                    <p>Total request: <?= isset($catatan_requests) ? count($catatan_requests) : 0 ?></p>
                                                    <div class="font-semibold text-black flex items-center space-x-1 px-3">
                                                        <span class="text-red-600">🚨</span>
                                                        <span>Permintaan Ambulans</span>
                                                    </div>
                                                    <?php foreach ($catatan_requests as $req): ?>
                                                        <div class="flex items-center justify-between p-4 hover:bg-gray-100 border-l-4 border-blue-500">
                                                            <div>
                                                                Ambulans <strong><?= esc($req['no_rawat']) ?></strong> sedang diminta (<?= esc($req['status']) ?>)
                                                            </div>
                                                            <a href="/ambulans/terima/<?= esc($req['no_rawat']) ?>" 
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
                            <?= view('components/tambah_button', [
                                'link' => '/catatanobservasipostpartum/tambah'
                            ]) ?>
                        </div>
                    </div>
                    <!-- End Header -->
                    <?php
                        echo view('components/search_bar');
                        
                        $api_url  = '/catatanobservasipostpartum';
                        $tabel    = $catatan_data;
                        $kolom_id = 'no_rawat';
                        $aksi = [
                            'cetak'    => false,
                            'tindakan' => false,
                            'detail'   => true,
                            'ubah'     => true,
                            'hapus'    => true
                        ];
                        $data = [
                            // [visible, Display, Kolom, Jenis]
                            [1, 'Nomor Rawat'      , 'no_rawat'     , 'indeks'],
                            [1, 'Nama Pasien'      , 'nama_pasien'  , 'nama'  ],
                            [1, 'Umur'             , 'umur'         , 'jumlah'],
                            [1, 'Jenis Kelamin'    , 'jenis_kelamin', 'status'], 
                            // [1, 'Tanggal Observasi', 'tanggal'      , 'tanggal'],
                            // [0, 'Jam Observasi'    , 'jam'          , 'jam'   ],
                            [0, 'GCS (E, V, M)'    , 'gcs'          , 'jumlah'],
                            [0, 'TD (mmHG)'        , 'td'           , ],
                            [0, 'HR (x/menit)'     , 'hr'           , ],
                            [0, 'RR (x/menit)'     , 'rr'           , ],
                            [0, 'Suhu (C)'         , 'suhu'         , ],
                            [0, 'SpO2(%)'          , 'spo2'         , ],
                            [0, 'TFU'              , 'tfu'          , ],
                            [0, 'Kontraksi/HIS'    , 'kontraksi'    , ],
                            [0, 'Perdarahan'       , 'perdarahan'   , ],
                            [0, 'Keterangan'       , 'keterangan'   , ], 
                            // [0, 'BJJ'              , 'bjj'          , ],
                            // [0, 'PPV'              , 'ppv'          , ],
                            // [0, 'VT'               , 'vt'           , ],
                            [0, 'NIP'              , 'nip'          , ],
                            [0, 'Nama Petugas'     , 'nama_petugas' , ],

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