@ -1,666 +1,658 @@
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
                                Pasien Unit Gawat Darurat
                            </h2>

                        </div>
                        <div class="flex gap-x-6 justify-center items-center">
                            <div class="relative">
                                <?= view('components/notif_icon') ?>

                                <!-- Notification Pop-up -->
                                <div id="notif-popup" class="absolute right-0 mt-2 w-[30rem] overflow-y-auto z-[2] bg-white rounded-lg shadow-lg hidden">
                                    <?= view('components/notif') ?>
                                    <div>
                                        <div id="stok-content" class="max-h-[15rem] overflow-y-auto">
                                            <?php
                                            
                                            $count_notif_stok = 0;
                                            $today = new DateTime();
                                            $today->setTime(0, 0, 0);
                                            if ($count_notif_stok !== 0) {
                                                foreach ($ugd_tanpa_params_data as $ugd_stok) {
                                                    if ($ugd_stok['stok'] <= $ugd_stok['stok_minimum']) {
                                                        $count_notif_stok++; ?>
                                                        <a href="/datamedis/edit/<?= $ugd_stok['id'] ?>" class="p-4 flex items-center border-b-2 border-b-[#F1F1F1] border-l-2 border-l-[#DA4141] hover:bg-gray-100">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32" fill="none">
                                                                <path d="M12.5358 6.667C14.0754 4.00033 17.9244 4.00033 19.464 6.66699L27.8356 21.167C29.3752 23.8337 27.4507 27.167 24.3715 27.167H7.62834C4.54914 27.167 2.62464 23.8337 4.16424 21.167L12.5358 6.667Z" fill="#DA4141" />
                                                                <path d="M16 18.333C15.4533 18.333 15 17.8797 15 17.333V10.333C15 9.78634 15.4533 9.33301 16 9.33301C16.5467 9.33301 17 9.78634 17 10.333V17.333C17 17.8797 16.5467 18.333 16 18.333Z" fill="#FEE2E2" />
                                                                <path d="M15.9998 23.0001C15.8265 23.0001 15.6532 22.9601 15.4932 22.8934C15.3198 22.8268 15.1865 22.7335 15.0531 22.6135C14.9331 22.4802 14.8398 22.3335 14.7598 22.1735C14.6932 22.0135 14.6665 21.8401 14.6665 21.6668C14.6665 21.3201 14.7998 20.9734 15.0531 20.7201C15.1865 20.6001 15.3198 20.5068 15.4932 20.4402C15.9865 20.2268 16.5732 20.3468 16.9465 20.7201C17.0665 20.8534 17.1598 20.9868 17.2265 21.1601C17.2931 21.3201 17.3332 21.4935 17.3332 21.6668C17.3332 21.8401 17.2931 22.0135 17.2265 22.1735C17.1598 22.3335 17.0665 22.4802 16.9465 22.6135C16.6932 22.8668 16.3598 23.0001 15.9998 23.0001Z" fill="#FEE2E2" />
                                                            </svg>
                                                            <div class="mx-2">
                                                                <span>Stok <span class="font-semibold"><?= $ugd_stok['nama'] ?></span> telah mencapai jumlah minimum</span>
                                                                <div class="py-1 font-semibold text-sm text-[#DA4141]">Sisa stok: <?= $ugd_stok['stok'] ?></div>
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
                                'link' => '/ugd/tambah'
                            ]) ?>

                        </div>
                    </div>
                    <!-- End Header -->
                    
                    <?php
                        echo view('components/search_bar');
                        
                        $api_url  = '/ugd';
                        $tabel    = $ugd_data;
                        $kolom_id = 'nomor_reg';
                        $aksi = [
                            'cetak'    => false,
                            'tindakan' => false,
                            'detail'   => true,
                            'ubah'     => true,
                            'hapus'    => false,
                        ];
                        $data = [
                            // [visible, Display, Kolom, Jenis]
                            [1, 'No. Registrasi'  , 'nomor_reg'       , 'indeks'],
                            [0, 'Nomor Rawat'     , 'nomor_rawat'     , 'indeks'],
                            [0, 'Tanggal'         , 'tanggal'         , 'tanggal'],
                            [0, 'Jam'             , 'jam'             , 'jam'],
                            [1, 'No. Rekam Medis' , 'nomor_rm'        , 'indeks'],
                            [1, 'Nama'            , 'nama_pasien'     , 'nama'],
                            [0, 'Jenis Kelamin'   , 'jenis_kelamin'   , 'status'],
                            [0, 'Umur'            , 'umur'            , 'jumlah'],
                            [1, 'Poliklinik'      , 'poliklinik'      , 'status'],
                            [1, 'Dokter'          , 'dokter_dituju'   , 'nama'],
                            [0, 'Penanggung Jawab'         , 'penanggung_jawab', 'nama'],
                            [0, 'Hubungan Penanggung Jawab', 'hubungan_pj'     , 'status'],
                            [0, 'Alamat Penanggung Jawab'  , 'alamat_pj'       , 'teks'],
                            [0, 'Biaya Registrasi', 'biaya_registrasi', 'uang'],
                            [0, 'Status Rawat'    , 'status_rawat'    , 'status'],
                            [0, 'Jenis Bayar'     , 'jenis_bayar'     , 'status'],
                            [0, 'Status Bayar'    , 'status_bayar'    , 'status'],

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
                    <!-- Table -->
                    <div class="overflow-x-auto w-full">                       
                    <table id="myTable" class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        
    

                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            <?php foreach ($ugd_data as $ugd) : ?>
                                <div id="hs-vertically-centered-scrollable-modal-<?= $ugd['nomor_reg'] ?>" class="hs-overlay hidden size-full fixed top-0 start-0 z-[80] pointer-events-none">
                                    <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto h-[calc(100%-3.5rem)] min-h-[calc(100%-3.5rem)] flex items-center ">
                                        <div class="overflow-y-auto w-full max-h-full flex flex-col bg-white border shadow-sm rounded-xl pointer-events-auto dark:bg-neutral-800 dark:border-neutral-700 dark:shadow-neutral-700/70">
                                            <div class="p-4">
                                                <div class="space-y-4">
                                                <div>
                                                    
                                                    <!-- <div class="p-4 space-y-4"> -->
                                                        <!-- <div class="px-3 py-1.5">
                                                            <a href="/tindakan/<?= $ugd['nomor_rawat'] ?>" 
                                                            class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border bg-white text-gray-800 hover:bg-gray-50 dark:bg-neutral-900 dark:text-white dark:hover:bg-neutral-800">
                                                                Lihat Tindakan
                                                            </a>
                                                        </div> -->
                                                        <div class="px-3 py-1.5">
                                                            <form method="GET" action="/tindakan/submit-ugd/<?= $ugd['nomor_rawat'] ?>">
                                                                <?= csrf_field() ?>
                                                                <button type="submit" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border bg-white text-gray-800 hover:bg-gray-50 dark:bg-neutral-900 dark:text-white dark:hover:bg-neutral-800">
                                                                    Tambah Tindakan
                                                                </button>
                                                            </form>
                                                        </div>

                                                        <div class="px-3 py-1.5">
                                                        <form method="POST" action="/rawatinap/tambah/<?= $ugd['nomor_rawat'] ?>">
                                                            <?= csrf_field() ?>
                                                            <button type="submit" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border bg-white text-gray-800 hover:bg-gray-50 dark:bg-neutral-900 dark:text-white dark:hover:bg-neutral-800">
                                                                Rawat Inap
                                                            </button>
                                                        </form>
                                                            </div>
                                                        <!-- Add more fields if needed -->
                                                    <!-- </div> -->

                                                    <div class="mb-5 flex items-end gap-4">
                                                        <!-- Select input -->
                                                        <!-- <div class="w-full">
                                                            <label class="block mb-2 text-sm text-gray-900 dark:text-white">Kelas Kamar</label>
                                                            <select name="status_poliklinik"
                                                                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full dark:border-gray-600 dark:text-white"
                                                                    required>
                                                                <option value="Umum">Umum</option>
                                                                <option value="VIP">VIP</option>
                                                                <option value="VVIP">VVIP</option>
                                                            </select>
                                                        </div> -->

                                                        <!-- Rawat Inap button -->
                                                        <!-- <form id="formButuhKamar" action="/registrasi/trigger-notif" method="post" class="self-end">
                                                            <input type="hidden" name="nomor_reg" id="formNomorReg">
                                                            <button type="submit"
                                                                    class="btn btn-warning whitespace-nowrap py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-800">
                                                                Rawat Inap
                                                            </button>
                                                        </form> -->
                                                    </div>


                                                </div>

                                                </div>
                                                <div class="flex justify-end items-center gap-x-2 py-3 px-4 border-t dark:border-neutral-700">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <tr>
                                    <td class="size-px whitespace-nowrap">
                                        <div class="px-3 py-1.5 text-center inline-flex">
                                            <div class="px-3 py-1.5">
                                            <button
                                                type="button"
                                                class="btn btn-info btn-tindakan gap-x-1 text-sm font-semibold"
                                                data-nomor-reg="<?= $ugd['nomor_reg'] ?>"
                                                data-hs-overlay="#hs-vertically-centered-scrollable-modal-<?= $ugd['nomor_reg'] ?>">
                                                Tindakan
                                            </button>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
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

    

    document.addEventListener("DOMContentLoaded", function () {
    const tindakanButtons = document.querySelectorAll(".btn-tindakan");

    tindakanButtons.forEach(button => {
        button.addEventListener("click", function () {
            const nomorReg = this.getAttribute("data-nomor-reg");
            document.getElementById("formNomorReg").value = nomorReg;
        });
    });
});
</script>
<?= $this->endSection(); ?>