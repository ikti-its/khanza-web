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
                                Permintaan Stok Obat Pasien
                            </h2>

                        </div>
                        <div class="flex gap-x-6 justify-center items-center">
                            <div class="relative">
                                <?= view('components/notif/icon') ?>

                                <!-- Notification Pop-up -->
                                <div id="notif-popup" style="width: 600px;" class="absolute right-0 mt-2 overflow-y-auto z-[2] bg-white rounded-lg shadow-lg hidden">

                                    <div class="px-4">
                                        <?= view('components/notif/header') ?>
                                    </div>
                                </div>
                            </div>
                            <div class="h-[1.375rem] border-r-4 bg-[#DCDCDC]"></div>
                            <?= view('components/tambah_button', [
                                'link' => '/permintaanstokobat/tambah'
                            ]) ?>
                            <?= view('components/audit_button', [
                                'link' => '/permintaanstokobat/audit'
                            ]) ?>
                        </div>
                    </div>
                    <!-- End Header -->
                    
                    <?php
                        echo view('components/header/search_bar');
                        
                        $modul_path = '/permintaanstokobat';
                        $tabel    = $permintaanstokobat_data;
                        $kolom_id = 'no_permintaan';
                        $aksi = [
                            'cetak'    => false,
                            'tindakan' => false,
                            'detail'   => true,
                            'ubah'     => true,
                            'hapus'    => true,
                        ];
                        $konfig = [
                            // [visible, Display, Kolom, Jenis]
                            [1, 'Nomor Permintaan'  , 'no_permintaan' , 'indeks'],
                            [1, 'Tanggal'           , 'tgl_permintaan', 'tanggal'],
                            [1, 'Jam'               , 'jam'           , 'jam'], 
                            // [0, 'Kamar'             , 'kd_bangsal'    , 'indeks'],
                            [0, 'Status'            , 'status'        , 'status'],
                            // [0, 'Nama Pasien'       , 'nama_pasien'   , 'nama'],
                            [1, 'Kode Dokter'       , 'kd_dokter'     , 'indeks'],
                            // [0, 'Kode Obat/BHP'     , 'kode_brng'    , 'indeks'], 
                            // [0, 'Nama Obat'         , 'nama_brng'    , 'teks'],
                            // [0, 'Jumlah'            , 'jumlah'       , 'jumlah'],
                            // [0, 'Jam Pemberian Obat', ],
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
                    <!-- Table -->
                    <div class="overflow-x-auto w-full">                       
                    <table id="myTable" class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
           
                        
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            <?php foreach ($permintaanstokobat_data as $permintaanstokobat) : ?>
                                <div id="hs-vertically-centered-scrollable-modal-<?= $permintaanstokobat['no_permintaan'] ?>" class="hs-overlay hidden size-full fixed top-0 start-0 z-[80] pointer-events-none">
                                    <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto h-[calc(100%-3.5rem)] min-h-[calc(100%-3.5rem)] flex items-center ">
                                        <div class="overflow-y-auto w-full max-h-full flex flex-col bg-white border shadow-sm rounded-xl pointer-events-auto dark:bg-neutral-800 dark:border-neutral-700 dark:shadow-neutral-700/70">
                                            </div>
                                            <div class="p-4">
                                                <div class="space-y-4">
                                                    <div>
                                                        <!-- Detail Obat -->
                                                        <?php foreach ($stok_obat as $obat): ?>
                                                            <div class="border-t pt-4 mt-4">
                                                                <div class="mb-3 sm:block">
                                                                    <label class="block mb-2 text-sm text-gray-900 dark:text-white">Kode Barang</label>
                                                                    <input type="text" name="" value="<?= $obat['kode_brng'] ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full dark:border-gray-600 dark:text-white" readonly>
                                                                </div>

                                                                <div class="mb-3 sm:block">
                                                                    <label class="block mb-2 text-sm text-gray-900 dark:text-white">Nama Barang</label>
                                                                    <input type="text" name="" value="<?= $obat['nama_barang'] ?? '' ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full dark:border-gray-600 dark:text-white" readonly>
                                                                </div>

                                                                <div class="mb-3 sm:block">
                                                                    <label class="block mb-2 text-sm text-gray-900 dark:text-white">Jumlah</label>
                                                                    <input type="text" name="" value="<?= $obat['jumlah'] ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full dark:border-gray-600 dark:text-white" readonly>
                                                                </div>

                                                                <div class="mb-3 sm:block">
                                                                    <label class="block mb-2 text-sm text-gray-900 dark:text-white">Jam Pemberian Obat</label>
                                                                    <input type="text" name="" value="<?php
                                                                        $jamList = [];
                                                                        for ($i = 0; $i <= 23; $i++) {
                                                                            $key = 'jam' . str_pad($i, 2, '0', STR_PAD_LEFT);
                                                                            if (!empty($obat[$key]) && $obat[$key] === true) {
                                                                                $jamList[] = $key;
                                                                            }
                                                                        }
                                                                        echo implode(', ', $jamList);
                                                                    ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full dark:border-gray-600 dark:text-white" readonly>
                                                                </div>
                                                            </div>
                                                        <?php endforeach; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    </div>

                    <!-- End Table -->
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Card -->

<!-- End Table Section -->
<script>
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