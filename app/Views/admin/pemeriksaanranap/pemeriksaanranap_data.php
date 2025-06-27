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
                                Pemeriksaan Rawat Inap
                            </h2>

                        </div>
                        <div class="flex gap-x-6 justify-center items-center">
                            <div class="relative">
                                <?= view('components/notif/icon') ?>

                                <!-- Notification Pop-up -->
                                <div id="notif-popup" style="width: 600px;" class="absolute right-0 mt-2 overflow-y-auto z-[2] bg-white rounded-lg shadow-lg hidden">

                                    <div class="px-4">
                                        <?= view('components/notif/header') ?>
                                        <div class="flex">
                                            <div class="flex flex-col gap-2 w-full" id="ambulansNotifSection">
                                                <!-- 🚑 Ambulans Notification -->
                                                <?php if (isset($pemeriksaanranap_requests) && count($pemeriksaanranap_requests) > 0): ?>
                                                    <p>Total request: <?= isset($pemeriksaanranap_requests) ? count($pemeriksaanranap_requests) : 0 ?></p>
                                                    <div class="font-semibold text-black flex items-center space-x-1 px-3">
                                                        <span class="text-red-600">🚨</span>
                                                        <span>Permintaan Ambulans</span>
                                                    </div>
                                                    <?php foreach ($pemeriksaanranap_requests as $req): ?>
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
                            <?= view('components/header/tambah_button', [
                                'link' => '/pemeriksaanranap/tambah'
                            ]) ?>
                            <?= view('components/header/audit_button', [
                                'link' => '/pemeriksaanranap/audit'
                            ]) ?>
                        </div>
                    </div>
                    <!-- End Header -->
                    <?php
                        echo view('components/header/search_bar');
                        
                        $modul_path = '/pemberianobat';
                        $tabel    = $pemeriksaanranap_data;
                        $kolom_id = 'no_rawat';
                        $aksi = [
                            'cetak'    => false,
                            'tindakan' => false,
                            'detail'   => true,
                            'ubah'     => true,
                            'hapus'    => false,
                        ];
                        $konfig = [
                            // [visible, Display, Kolom, Jenis]
                            [1, 'Nomor Rawat'  , 'no_rawat'    , 'indeks'],
                            [1, 'Nomor RM'     , 'nomor_rm'    , 'indeks'],
                            [1, 'Nama Pasien'  , 'nama_pasien' , 'nama'],
                            [1, 'Tanggal Rawat', 'tanggal'     , 'tanggal'],
                            [1, 'Jam'          , 'jam'         , 'jam'],
                            [1, 'Suhu'         , 'suhu_tubuh'  , 'suhu'],
                            [0, 'TD (mmHG)'    , 'tensi'       ],
                            [0, 'HR (x/menit)' , 'nadi'        ],
                            [0, 'RR (x/menit)' , 'respirasi'   ],
                            [0, 'Tinggi (cm)'  , 'tinggi'      ],
                            [0, 'Berat (kg)'   , 'berat'       ],
                            [0, 'SpO2(%)'      , 'spo2'        ],
                            [0, 'GCS (E, V, M)', 'gcs'         ],
                            [0, 'Kesadaran'    , 'kesadaran'   ],
                            [0, 'Alergi'       , 'alergi'      ],
                            [0, 'Subjek'       , 'keluhan'     ],
                            [0, 'Objek'        , 'pemeriksaan' ],
                            [0, 'Asesmen'      , 'penilaian'   ],
                            [0, 'Plan'         , 'rtl'         ],
                            [0, 'Instruksi'    , 'instruksi'   ],
                            [0, 'Evaluasi'     , 'evaluasi'    ],
                            [0, 'NIP'          , 'nip'         ],
                            [0, 'Nama Petugas' , 'nama_petugas'],
                            [0, 'Profesi'      , 'nama_petugas']
                        ];
                        echo view('components/tabel/data', [
                            'modul_path' => $modul_path,
                            'tabel'      => $tabel,
                            'kolom_id'   => $kolom_id,
                            'konfig'     => $konfig,
                            'aksi'       => $aksi
                        ]);
                        
                        echo view('components/footer/footer', [
                            'meta_data'  => $meta_data,
                            'modul_path' => $modul_path
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
              🚑 Ambulans <strong>${item.kode_dokter}</strong> sedang diminta (${item.status})
              <a href="/ambulans/terima/${item.kode_dokter}" class="bg-blue-600 text-white text-sm px-3 py-1 rounded hover:bg-blue-700">Terima</a>
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