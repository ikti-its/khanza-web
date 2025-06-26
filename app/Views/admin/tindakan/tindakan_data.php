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
                        <?php if (!empty($tindakan_data)) : ?>
                            <div class="mb-4 text-xl font-black text-gray-800 dark:text-gray-200 space-y-1">
                                <div class="flex">
                                    <span class="w-48">Nomor Rawat</span> : <?= $tindakan_data[0]['nomor_rawat'] ?>
                                </div>
                                <div class="flex">
                                    <span class="w-48">Nama Pasien</span> : <?= $tindakan_data[0]['nama_pasien'] ?>
                                </div>
                                <div class="flex">
                                    <span class="w-48">Kelas</span> : <?= esc($kelas ?? '-') ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        </div>
                        <div class="flex gap-x-6 justify-center items-center">
                            <div class="relative">
                                <?= view('components/notif_icon') ?>

                                <!-- Notification Pop-up -->
                                <div id="notif-popup" class="absolute right-0 mt-2 w-[30rem] overflow-y-auto z-[2] bg-white rounded-lg shadow-lg hidden">
                                    <div class="px-4">
                                        <?= view('components/notif_header') ?>
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

                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="h-[1.375rem] border-r-4 bg-[#DCDCDC]"></div>
                            <?php if (!empty($tindakan_data)) : ?>
                                <div>
                                    <a href='/tindakan/tambah/<?= $tindakan_data[0]['nomor_rawat'] ?>' class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-[#0A2D27] text-[#ACF2E7] hover:bg-[#13594E] disabled:opacity-50 disabled:pointer-events-none dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                                        <svg class="flex-shrink-0 size-3" xmlns="http://www.w3.org/2000/svg" width="16" height="1" viewBox="0 0 16 16" fill="none">
                                            <path d="M2.63452 7.50001L13.6345 7.5M8.13452 13V2" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                                        </svg>
                                        Tambah
                                    </a>
                                </div>
                            <?php endif; ?>
                            <?= view('components/audit_button', [
                                'link' => '/tindakan/audit'
                            ]) ?>
                        </div>
                    </div>
                    
                    <!-- End Header -->
                    <?php
                        echo view('components/search_bar');
                        
                        $modul_path = '/tindakan';
                        $tabel    = $tindakan_data;
                        $kolom_id = 'nomor_rawat';
                        $aksi = [
                            'cetak'    => false,
                            'tindakan' => false,
                            'detail'   => true,
                            'ubah'     => true,
                            'hapus'    => false,
                        ];
                        $konfig = [
                            // [visible, Display, Kolom, Jenis]
                            [1, 'Tindakan', 'tindakan'     , 'teks'],
                            [1, 'Dokter'  , 'nama_dokter'  , 'nama'],
                            [1, 'Petugas' , 'nama_petugas' , 'nama'],
                            [1, 'Tanggal' , 'tanggal_rawat', 'tanggal'],
                            [1, 'Jam'     , 'jam_rawat'    , 'jam'],
                            [1, 'Biaya'   , 'biaya'        , 'uang'],
                        ];
                        echo view('components/tabel', [
                            'modul_path' => $modul_path,
                            'tabel'      => $tabel,
                            'kolom_id'   => $kolom_id,
                            'konfig'     => $konfig,
                            'aksi'       => $aksi
                        ]);
                        
                        echo view('components/footer', [
                            'meta_data'  => $meta_data,
                            'modul_path' => $modul_path
                        ]);      
                    ?>
                    <!-- Table -->
                    <div class="overflow-x-auto w-full">                       
                    <table id="myTable" class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        <?php 
                            $total_biaya = 0; 

                            foreach ($tindakan_data as $item) {
                                $total_biaya += intval($item['biaya'] ?? 0);
                            }
                            ?>

                            <tfoot class="bg-gray-100 dark:bg-slate-800">
                                <tr>
                                    <td colspan="4"></td>
                                    <td class="px-6 py-4 font-bold text-right text-gray-800 dark:text-gray-200">Total Biaya</td>
                                    <td class="px-6 py-4 font-bold text-gray-800 dark:text-gray-200">
                                        <?= 'Rp ' . number_format($total_biaya, 0, ',', '.') ?>
                                    </td>
                                    <td></td>
                                </tr>
                            </tfoot>

                            <?php
                                $namaTindakanMap = [];
                                foreach ($jenis_tindakan as $jt) {
                                    $namaTindakanMap[$jt['kode']] = $jt['nama_tindakan'];
                                }
                            ?>                       
                                
                            </tbody>
                            <?php foreach ($tindakan_data as $i => $tindakan) : ?>
                                <div id="hs-vertically-centered-scrollable-modal-<?= $tindakan['nomor_rawat'] . '-' . $i ?>" class="hs-overlay hidden fixed top-0 start-0 z-[80] w-full h-full bg-gray-800 bg-opacity-50 overflow-y-auto">
                                    <div class="mt-20 mx-auto w-full max-w-lg p-6 bg-white dark:bg-neutral-800 rounded shadow">
                                        <div class="flex justify-between items-center border-b pb-2">
                                            <h3 class="text-lg font-bold"><?= $tindakan['nama_pasien'] ?></h3>
                                            <button data-hs-overlay="#hs-vertically-centered-scrollable-modal-<?= $tindakan['nomor_rawat'] . '-' . $i ?>" class="text-gray-600 dark:text-white hover:text-red-600">
                                                &times;
                                            </button>
                                        </div>
                                        <div class="mt-4 space-y-3">
                                            <div><strong>Nomor Rawat:</strong> <?= $tindakan['nomor_rawat'] ?></div>
                                            <div><strong>Nomor RM:</strong> <?= $tindakan['nomor_rm'] ?></div>
                                            <div><strong>Dokter:</strong> <?= $tindakan['nama_dokter'] ?? 'N/A' ?></div>
                                            <div><strong>Tanggal:</strong> <?= $tindakan['tanggal_rawat'] ?></div>
                                            <div><strong>Jam:</strong> <?= $tindakan['jam_rawat'] ?></div>
                                        </div>
                                        <div class="mt-6 text-end">
                                            <button class="text-sm text-gray-700 bg-gray-200 px-3 py-2 rounded hover:bg-gray-300 dark:text-white dark:bg-gray-700 dark:hover:bg-gray-600" data-hs-overlay="#hs-vertically-centered-scrollable-modal-<?= $tindakan['nomor_rawat'] . '-' . $i ?>">Tutup</button>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
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