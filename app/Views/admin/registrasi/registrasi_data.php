<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<script src="https://unpkg.com/preline@latest/dist/preline.js"></script>

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
                                Registrasi Pasien
                            </h2>

                        </div>
                        <div class="flex gap-x-6 justify-center items-center">
                            <div class="relative">
                                <?= view('components/notif/icon') ?>

                                <!-- Notification Pop-up -->
                                <div id="notif-popup" class="absolute right-0 mt-2 w-[30rem] overflow-y-auto z-[2] bg-white rounded-lg shadow-lg hidden">
                                    <div class="px-4">
                                        <?= view('components/notif/header') ?>
                                        <div class="flex">                                    
                                        </div>
                                    </div>
                                    <div>
                                        <div id="stok-content" class="max-h-[15rem] overflow-y-auto">
                                        <div id="kamarpenuh-content" class="max-h-[15rem] overflow-y-auto hidden">
                                            <!-- This will be dynamically filled by JS -->
                                        </div>
                                        </div>
                                        <div class="flex justify-center items-center w-3/4">
                                            <button id="kamarpenuh-tab" class="flex items-center justify-center text-center w-full py-2 border-b-2">Kamar Penuh
                                                <span class="ml-1">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 15 15" fill="none">
                                                        <circle cx="7.75" cy="7.5" r="7" fill="#EF4444" />
                                                        <text x="50%" y="45%" text-anchor="middle" dominant-baseline="central" fill="#FFF" font-size="10px"></text>
                                                    </svg>
                                                </span>
                                            </button>
                                        </div>
                                        <script>
                                        window.addEventListener("DOMContentLoaded", function () {
                                            const container = document.getElementById("kamarpenuh-content");
                                            const notifList = JSON.parse(localStorage.getItem("kamarPenuhList")) || [];

                                            if (!container) return;

                                            container.innerHTML = ""; // Clear previous content

                                            if (notifList.length === 0) {
                                                container.innerHTML = `<div class="p-4 text-gray-500">Tidak ada notifikasi kamar penuh.</div>`;
                                            } else {
                                                notifList.forEach(notif => {
                                                    container.innerHTML += `
                                                        <div class="p-4 mb-2 ml-2 border-b border-gray-200 rounded hover:bg-gray-50">
                                                            <div class="flex items-center gap-2">
                                                                <span class="w-3 h-3 rounded-full bg-red-500 inline-block"></span>
                                                                <div>
                                                                    <p class="text-base font-semibold text-gray-800">
                                                                        Kamar penuh untuk ${notif.nama_pasien}
                                                                    </p>
                                                                    <p class="text-sm text-gray-500">
                                                                        Nomor Registrasi: ${notif.nomor_reg}
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    `;
                                                });
                                            }
                                        });
                                        </script>
                                    </div>
                                </div>
                            </div>
                            <div class="h-[1.375rem] border-r-4 bg-[#DCDCDC]"></div>
                            <?= view('components/header/tambah_button', [
                                'link' => '/registrasi/tambah'
                            ]) ?>
                            <?= view('components/header/audit_button', [
                                'link' => '/registrasi/audit'
                            ]) ?>
                        </div>
                    </div>
                    <!-- End Header -->
                    <?php
                        echo view('components/header/search_bar');
                        
                        $modul_path = '/registrasi';
                        $tabel    = $registrasi_data;
                        $kolom_id = 'nomor_reg';
                        $aksi = [
                            'cetak'    => false,
                            'tindakan' => true,
                            'detail'   => false,
                            'ubah'     => true,
                            'hapus'    => true,
                        ];
                        $konfig = [
                            // [visible, Display, Kolom, Jenis, Required, *Opsi]
                            [1, 'Nomor Registrasi' , 'nomor_reg'    , 'indeks'],
                            [0, 'Nomor Rawat'      , 'nomor_rawat'  , 'indeks'],
                            [1, 'Tanggal'          , 'tanggal'      , 'tanggal'],
                            [1, 'Jam'              , 'jam'          , 'jam'],
                            [0, 'Nomor Rekam Medis', 'nomor_rm'     , 'indeks'],
                            [1, 'Nama'             , 'nama_pasien'  , 'nama'],
                            [1, 'Jenis Kelamin'    , 'jenis_kelamin', 'status'],
                            [1, 'Umur'             , 'umur'         , 'jumlah'],
                            [1, 'Poliklinik'       , 'poliklinik'   , 'status'],
                            [1, 'Dokter'           , 'nama_dokter'  , 'nama'],
                            [0, 'Penanggung Jawab'         , 'penanggung_jawab', 'nama'],
                            [0, 'Hubungan Penanggung Jawab', 'hubungan_pj'     , 'teks'],
                            [0, 'Alamat Penanggung Jawab'  , 'alamat_pj'       , 'teks'],
                            [0, 'Nomor Telepon'    , 'no_telepon'       , 'teks'],
                            [0, 'Biaya Registrasi' , 'biaya_registrasi' , 'uang'],
                            [0, 'Status Registrasi', 'status_registrasi', 'status'],
                            [0, 'Status Rawat'     , 'status_rawat'     , 'status'], 
                            [0, 'Status Poliklinik', 'status_poli'      , 'status'],
                            [0, 'Jenis Bayar'      , 'jenis_bayar'      , 'status'],
                            [0, 'Status Bayar'     , 'status_bayar'     , 'status'],
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
                    <!-- End Table -->
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Card -->

<!-- End Table Section -->
<script>
document.addEventListener('DOMContentLoaded', function () {
  document.querySelectorAll('[data-hs-overlay]').forEach(btn => {
    btn.addEventListener('click', function () {
      // Close all open modals
      document.querySelectorAll('.hs-overlay').forEach(modal => {
        modal.classList.add('hidden');
      });

      // Then open the correct one
      const selector = btn.getAttribute('data-hs-overlay');
      const targetModal = document.querySelector(selector);
      if (targetModal) {
        targetModal.classList.remove('hidden');
      }
    });
  });
});
    
</script>
<?= $this->endSection(); ?>