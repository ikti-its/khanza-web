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
                        <?php if (!empty($resepobat_data)) : ?>
                            <div class="mb-4 text-xl font-black text-gray-800 dark:text-gray-200 space-y-1">
                                <div class="flex">
                                    <span class="w-48">Resep Obat</span>
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

                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="h-[1.375rem] border-r-4 bg-[#DCDCDC]"></div>
                            <?php if (!empty($resepobat_data)) : ?>
                                <?php $no_rawat = $resepobat[0]['nomor_rawat'] ?? ''; ?>
                                <div>
                                    <a href="<?= base_url('resepobat/tambah/' . $no_rawat) ?>"
                                    class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-[#0A2D27] text-[#ACF2E7] hover:bg-[#13594E] disabled:opacity-50 disabled:pointer-events-none dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                                        <svg class="flex-shrink-0 size-3" xmlns="http://www.w3.org/2000/svg" width="16" height="1" viewBox="0 0 16 16" fill="none">
                                            <path d="M2.63452 7.50001L13.6345 7.5M8.13452 13V2" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                                        </svg>
                                        Tambah
                                    </a>
                                </div>
                            <?php endif; ?>
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
                                'Nomor Resep',
                                'Tanggal Resep',
                                'Nomor Rawat',
                                'Dokter Peresep',
                                'Status',
                                'Aksi'
                            ];
                            echo view('components/data_tabel_thead',['columns' => $columns]);
                        ?>
                        
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    
                            <?php foreach ($resepobat_data as $i => $resepobat) : ?>
                                <div id="hs-vertically-centered-scrollable-modal-<?= $resepobat['no_resep'] ?>" class="hs-overlay hidden size-full fixed top-0 start-0 z-[80] pointer-events-none">
                                    <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto h-[calc(100%-3.5rem)] min-h-[calc(100%-3.5rem)] flex items-center ">
                                        <div class="overflow-y-auto w-full max-h-full flex flex-col bg-white border shadow-sm rounded-xl pointer-events-auto dark:bg-neutral-800 dark:border-neutral-700 dark:shadow-neutral-700/70">
                                            <div class="flex justify-between items-center py-3 px-4 border-b dark:border-neutral-700">
                                                <h3 class="font-bold text-gray-800 dark:text-white">
                                                    <?= $resepobat['no_resep'] ?>
                                                </h3>
                                                <button type="button" class="flex justify-center items-center size-7 text-sm font-semibold rounded-full border border-transparent text-gray-800 hover:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-neutral-700" data-hs-overlay="#hs-vertically-centered-scrollable-modal-<?= $resepobat['no_resep'] ?>">
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
                                                        <label class="block mb-2 text-sm text-gray-900 dark:text-white">Nomor Resep</label>
                                                        <input type="text" name="" value="<?= $resepobat['no_resep'] ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full dark:border-gray-600 dark:text-white" readonly>
                                                    </div>

                                                    <div class="mb-5 sm:block">
                                                        <label class="block mb-2 text-sm text-gray-900 dark:text-white">Tanggal Resep</label>
                                                        <input type="text" name="" value="<?= $resepobat['tgl_peresepan'] ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full dark:border-gray-600 dark:text-white" readonly>
                                                    </div>

                                                    <div class="mb-5 sm:block">
                                                        <label class="block mb-2 text-sm text-gray-900 dark:text-white">Jam Peresepan</label>
                                                        <input type="text" name="" value="<?= $resepobat['jam_peresepan'] ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full dark:border-gray-600 dark:text-white" readonly>
                                                    </div>

                                                    <div class="mb-5 sm:block">
                                                        <label class="block mb-2 text-sm text-gray-900 dark:text-white">Nomor Rawat</label>
                                                        <input type="text" name="" value="<?= $resepobat['no_rawat'] ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full dark:border-gray-600 dark:text-white" readonly>
                                                    </div>

                                                    <div class="mb-5 sm:block">
                                                        <label class="block mb-2 text-sm text-gray-900 dark:text-white">Nomor Rekam Medis</label>
                                                        <input type="text" name="" value="<?= $resepobat['nomor_rm'] ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full dark:border-gray-600 dark:text-white" readonly>
                                                    </div>

                                                    <div class="mb-5 sm:block">
                                                        <label class="block mb-2 text-sm text-gray-900 dark:text-white">Pasien</label>
                                                        <input type="text" name="" value="<?= $resepobat['nama_pasien'] ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full dark:border-gray-600 dark:text-white" readonly>
                                                    </div>

                                                    <div class="mb-5 sm:block">
                                                        <label class="block mb-2 text-sm text-gray-900 dark:text-white">Dokter Peresep</label>
                                                        <input type="text" name="" value="<?= $resepobat['kd_dokter'] ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full dark:border-gray-600 dark:text-white" readonly>
                                                    </div>

                                                    <div class="mb-5 sm:block">
                                                        <label class="block mb-2 text-sm text-gray-900 dark:text-white">Status</label>
                                                        <input type="text" name="" value="<?= $resepobat['status'] ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full dark:border-gray-600 dark:text-white" readonly>
                                                    </div>

                                                    <!-- <div class="mb-5 sm:block">
                                                        <label class="block mb-2 text-sm text-gray-900 dark:text-white">Poli/Unit</label>
                                                        <input type="text" name="" value="<?= $resepobat['poli'] ?? '' ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full dark:border-gray-600 dark:text-white" readonly>
                                                    </div>

                                                    <div class="mb-5 sm:block">
                                                        <label class="block mb-2 text-sm text-gray-900 dark:text-white">Jenis Bayar</label>
                                                        <input type="text" name="" value="<?= $resepobat['jenis_bayar'] ?? ''  ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full dark:border-gray-600 dark:text-white" readonly>
                                                    </div> -->

                                                    <div class="mb-5 sm:block">
                                                        <label class="block mb-2 text-sm text-gray-900 dark:text-white">Tanggal Validasi</label>
                                                        <input type="text" name="" value="<?= $resepobat['tgl_perawatan'] ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full dark:border-gray-600 dark:text-white" readonly>
                                                    </div>

                                                    <div class="mb-5 sm:block">
                                                        <label class="block mb-2 text-sm text-gray-900 dark:text-white">Jam Validasi</label>
                                                        <input type="text" name="" value="<?= $resepobat['jam'] ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full dark:border-gray-600 dark:text-white" readonly>
                                                    </div>

                                                    <div class="mb-5 sm:block">
                                                        <label class="block mb-2 text-sm text-gray-900 dark:text-white">Tanggal Penyerahan</label>
                                                        <input type="text" name="" value="<?= $resepobat['tgl_penyerahan'] ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full dark:border-gray-600 dark:text-white" readonly>
                                                    </div>

                                                    <div class="mb-5 sm:block">
                                                        <label class="block mb-2 text-sm text-gray-900 dark:text-white">Jam Penyerahan</label>
                                                        <input type="text" name="" value="<?= $resepobat['jam_penyerahan'] ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full dark:border-gray-600 dark:text-white" readonly>
                                                    </div>

                                                    <div class="mb-5 sm:block">
                                                        <?php if ($resepobat['validasi']): ?>
                                                            <span class="text-green-600 font-semibold">sudah divalidasi</span>
                                                        <?php else: ?>
                                                            <button
                                                                class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-800"
                                                                onclick="validateResep('<?= $resepobat['no_resep'] ?>')"
                                                            >
                                                                Validasi
                                                            </button>
                                                        <?php endif; ?>
                                                    </div>
                                                    <a href="/resepobat/cetak/<?= $resepobat['no_resep'] ?>" 
                                                        class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-800"
                                                        data-hs-overlay="#hs-vertically-centered-scrollable-modal-<?= $resepobat['no_resep'] ?>">
                                                        Cetak Surat
                                                    </a>
                                                </div>

                                                </div>
                                                <div class="flex justify-end items-center gap-x-2 py-3 px-4 border-t dark:border-neutral-700">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <tr>
                                    <td class="h-px w-64 whitespace-nowrap">
                                        <div class="px-6 py-3">
                                            <a href="<?= base_url('resepdokter/' . $resepobat['no_resep']) ?>" class="text-center block text-sm font-semibold text-gray-800 cursor-pointer dark:text-gray-200 hover:underline">
                                                <?= $resepobat['no_resep'] ?>
                                            </a>
                                        </div>
                                    </td>

                                    <td class="h-px w-64 whitespace-nowrap">
                                        <div class="px-6 py-3">
                                            <?php
                                                $tglRaw = $resepobat['tgl_peresepan'] ?? null;
                                                $tglFormatted = 'N/A';
                                                if ($tglRaw) {
                                                    try {
                                                        $date = new DateTime($tglRaw);
                                                        $tglFormatted = $date->format('d-m-Y');
                                                    } catch (Exception $e) {
                                                        $tglFormatted = 'Invalid Date';
                                                    }
                                                }
                                            ?>
                                            <span class="text-center block text-sm font-semibold text-gray-800 dark:text-gray-200 hover:underline"><?= $tglFormatted ?></span>
                                        </div>
                                    </td>

                                    <td class="h-px w-64 whitespace-nowrap">
                                        <div class="px-6 py-3">
                                            <a href="<?= base_url('resepobat/' . ($resepobat['no_rawat'] ?? 'N/A')) ?>" class="text-center block text-sm font-semibold text-gray-800 cursor-pointer dark:text-gray-200 hover:underline">
                                                <?= $resepobat['no_rawat'] ?? 'N/A' ?>
                                            </a>
                                        </div>
                                    </td>
                                    <td class="h-px w-64 whitespace-nowrap">
                                        <div class="px-6 py-3">
                                            <span class="text-center block text-sm font-semibold text-gray-800 dark:text-gray-200"><?= $resepobat['kd_dokter'] ?? 'N/A' ?></span>
                                        </div>
                                    </td>
                                    <td class="h-px w-64 whitespace-nowrap">
                                        <div class="px-6 py-3">
                                            <span class="text-center block text-sm font-semibold text-gray-800 dark:text-gray-200"><?php if ($resepobat['validasi'] === true || $resepobat['validasi'] === 1 || $resepobat['validasi'] === 'true'): ?>
                                        <span class="text-green-600 font-semibold">sudah divalidasi</span>
                                            <?php else: ?>
                                                <span class="text-red-600 font-semibold">belum divalidasi</span>
                                            <?php endif; ?></span>
                                        </div>
                                    </td>

                                    <td class="size-px whitespace-nowrap">
                                        <div class="px-3 py-1.5 text-center inline-flex">
                                            <div class="px-3 py-1.5">
                                            <button
                                                                        type="button"
                                                                        class="btn btn-info btn-tindakan gap-x-1 text-sm font-semibold"
                                                                        data-nomor-reg="<?= $resepobat['no_resep'] ?>"
                                                                        data-hs-overlay="#hs-vertically-centered-scrollable-modal-<?= $resepobat['no_resep'] ?>">
                                                                        Lihat Detail
                                                                    </button>
                                            </div>
                                            <!-- <div class="px-3 py-1.5">
                                                <?php if ($resepobat['validasi'] === true || $resepobat['validasi'] === 1 || $resepobat['validasi'] === 'true'): ?>
                                                    <span class="text-gray-400 text-sm font-semibold cursor-not-allowed" title="Sudah divalidasi, tidak dapat diubah">Ubah</span>
                                                <?php else: ?>
                                                    <a href="/resepobat/edit/<?= $resepobat['no_resep'] ?>" class="gap-x-1 text-sm text-blue-600 decoration-2 hover:underline font-semibold">Ubah</a>
                                                <?php endif; ?>
    
                                                <?php
                                                    $row_id  = $resepobat['no_resep'] . '-' . $i;
                                                    $api_url = '/resepobat';
                                                    echo view('components/data_lihat_detail',[
                                                        'row_id'  => $row_id,
                                                        'api_url' => $api_url   
                                                    ]);
                                                    echo view('components/data_hapus',[
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
                        <?php foreach ($resepobat_data as $i => $resepobat) : ?>
                            <div id="hs-vertically-centered-scrollable-modal-<?= $resepobat['no_resep'] . '-' . $i ?>" class="hs-overlay hidden fixed top-0 start-0 z-[80] w-full h-full bg-gray-800 bg-opacity-50 overflow-y-auto">
                                <div class="mt-20 mx-auto w-full max-w-lg p-6 bg-white dark:bg-neutral-800 rounded shadow">
                                    <div class="flex justify-between items-center border-b pb-2">
                                        <h3 class="text-lg font-bold"><?= $resepobat['no_resep'] ?></h3>
                                        <button data-hs-overlay="#hs-vertically-centered-scrollable-modal-<?= $resepobat['no_resep'] . '-' . $i ?>" class="text-gray-600 dark:text-white hover:text-red-600">
                                            &times;
                                        </button>
                                    </div>
                                    <div class="mt-4 space-y-3">
                                        <div><strong>Nomor Rawat:</strong> <?= $resepobat['no_resep'] ?></div>
                                        <!-- <div><strong>Nomor RM:</strong> <?= $resepobat['no_resep'] ?></div> -->
                                        <div><strong>Dokter:</strong> <?= $resepobat['no_resep'] ?? 'N/A' ?></div>
                                        <div><strong>Tanggal:</strong> <?= $resepobat['no_resep'] ?></div>
                                        <div><strong>Jam:</strong> <?= $resepobat['no_resep'] ?></div>
                                    </div>
                                    <div class="mt-6 text-end">
                                        <button class="text-sm text-gray-700 bg-gray-200 px-3 py-2 rounded hover:bg-gray-300 dark:text-white dark:bg-gray-700 dark:hover:bg-gray-600" data-hs-overlay="#hs-vertically-centered-scrollable-modal-<?= $resepobat['no_resep'] . '-' . $i ?>">Tutup</button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </table>
                    </div>

                    <!-- End Table -->
                    <?= view('components/data_footer.php', [
                        'meta_data' => $meta_data,
                        'api_url'   => $api_url
                    ]) ?>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Card -->

<!-- End Table Section -->
<script>
    function validateResep(noResep) {
    if (!confirm("Yakin ingin memvalidasi resep ini?")) return;

    fetch(`http://127.0.0.1:8080/v1/resep-obat/${noResep}/validasi`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer <?= session('jwt_token') ?>'
        },
        body: JSON.stringify({ validasi: true })
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            alert('Berhasil divalidasi');
            location.reload(); // ⏮ Refresh to see updated status
        } else {
            alert('Gagal memvalidasi resep');
        }
    })
    .catch(err => {
        console.error(err);
        alert('Terjadi kesalahan');
    });
}


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