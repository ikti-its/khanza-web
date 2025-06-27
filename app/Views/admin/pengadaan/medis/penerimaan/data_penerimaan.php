<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<!-- Table Section -->
<div class="max-w-[85rem] py-6 lg:py-3 px-8 mx-auto">
    <!-- Card -->
    <div class="flex flex-col">
        <div class="-m-1.5 overflow-y-auto">
            <div class="sm:px-6 min-w-full inline-block align-middle">
                <?php if (session()->getFlashdata('warning')) : ?>
                    <div id="warningMessage" class="flex items-center my-2 bg-[#FFF5CF] text-sm font-semibold text-[#D97706] rounded-lg p-4" role="alert">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                            <path d="M10 7.5V11.6667" stroke="#D97706" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M9.99986 17.8414H4.94986C2.0582 17.8414 0.849863 15.7747 2.24986 13.2497L4.84986 8.56641L7.29986 4.16641C8.7832 1.49141 11.2165 1.49141 12.6999 4.16641L15.1499 8.57474L17.7499 13.2581C19.1499 15.7831 17.9332 17.8497 15.0499 17.8497H9.99986V17.8414Z" stroke="#D97706" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M9.99561 14.166H10.0031" stroke="#D97706" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <span class="mx-1 font-semibold"></span><?= session()->getFlashdata('warning') ?>
                    </div>
                <?php endif; ?>
                <div class="p-5 bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden dark:bg-slate-900 dark:border-gray-700">
                    <!-- Header -->
                    <div class="py-1 flex justify-between items-center border-gray-200 dark:border-gray-700">
                        <div>
                            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">
                                Penerimaan Barang Medis
                            </h2>

                        </div>
                        <div>
                            <?= view('components/tambah_button', [
                                'link' => '/penerimaanmedis/tambah'
                            ]) ?>
                            <?= view('components/audit_button', [
                                'link' => '/penerimaanmedis/audit'
                            ]) ?>
                        </div>
                    </div>
                    <!-- End Header -->
                    <?= view('components/header/search_bar') ?>

                    <div id="noDataFound" style="display: none;">Data tidak ditemukan</div>
                    <!-- Table -->
                    <table id="myTable" class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <?php 
                            $widths  = [17, 18, 18, 23, 24];
                            echo view('components/tabel/colgroup',['widths' => $widths]);
                            
                            $columns = [
                                'Tanggal Datang',
                                'Tanggal Jatuh Tempo',
                                'Nomor Faktur',
                                'Supplier',
                                'Aksi'
                            ];
                            echo view('components/tabel/thead',['kolom' => $columns]);
                        ?>

                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">

                            <?php foreach ($penerimaan_data as $penerimaan) : ?>


                                <div id="hs-vertically-centered-scrollable-modal-<?= $penerimaan['id'] ?>" class="hs-overlay hidden size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto pointer-events-none">
                                    <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto h-[calc(100%-3.5rem)] min-h-[calc(100%-3.5rem)] flex items-center">
                                        <div class="w-full max-h-full overflow-hidden flex flex-col bg-white border shadow-sm rounded-xl pointer-events-auto dark:bg-neutral-800 dark:border-neutral-700 dark:shadow-neutral-700/70">
                                            <div class="flex justify-between items-center py-3 px-4 border-b dark:border-neutral-700">
                                                <h3 class="font-bold text-gray-800 dark:text-white">
                                                    <?= $penerimaan['no_faktur'] ?>
                                                </h3>
                                                <button type="button" class="flex justify-center items-center size-7 text-sm font-semibold rounded-full border border-transparent text-gray-800 hover:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-neutral-700" data-hs-overlay="#hs-vertically-centered-scrollable-modal-<?= $penerimaan['id'] ?>">
                                                    <span class="sr-only">Close</span>
                                                    <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <path d="M18 6 6 18"></path>
                                                        <path d="m6 6 12 12"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                            <div class="p-4 overflow-y-auto">
                                                <div class="space-y-12">
                                                    <div>
                                                        <div class="mb-5 sm:block md:flex items-center">
                                                            <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Nomor Faktur</label>
                                                            <input type="text" name="" value="<?= $penerimaan['no_faktur'] ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
                                                        </div>
                                                        <div class="mb-5 sm:block md:flex items-center">
                                                            <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Nomor Pemesanan</label>
                                                            <input type="text" name="" value="<?= $penerimaan['no_pemesanan'] ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
                                                        </div>

                                                        <div class="mb-5 sm:block md:flex items-center">
                                                            <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Tanggal Penerimaan</label>
                                                            <input type="text" name="" value="<?php
                                                                                                // Tanggal asli dari data
                                                                                                $original_date = $penerimaan['tanggal_datang'];

                                                                                                // Jika tanggal adalah "0001-01-01", tampilkan tanda hubung "-"
                                                                                                if ($original_date === "0001-01-01") {
                                                                                                    echo "-";
                                                                                                } else {
                                                                                                    // Format tanggal sebagai dd-Bulan-yyyy (misal: 27 Juni 2024)

                                                                                                    // Pisahkan tanggal, bulan, dan tahun dari tanggal asli
                                                                                                    $day = date("d", strtotime($original_date));
                                                                                                    $month = date("m", strtotime($original_date));
                                                                                                    $year = date("Y", strtotime($original_date));

                                                                                                    // Daftar nama bulan dalam bahasa Indonesia
                                                                                                    $bulan = array(
                                                                                                        1 => "Januari", 2 => "Februari", 3 => "Maret", 4 => "April", 5 => "Mei", 6 => "Juni",
                                                                                                        7 => "Juli", 8 => "Agustus", 9 => "September", 10 => "Oktober", 11 => "November", 12 => "Desember"
                                                                                                    );

                                                                                                    // Format tanggal sesuai dengan format yang diinginkan
                                                                                                    $formatted_date = $day . ' ' . $bulan[(int)$month] . ' ' . $year;

                                                                                                    echo $formatted_date;
                                                                                                }
                                                                                                ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
                                                        </div>
                                                        <div class="mb-5 sm:block md:flex items-center">
                                                            <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Tanggal Faktur</label>
                                                            <input type="text" name="" value="<?php
                                                                                                // Tanggal asli dari data
                                                                                                $original_date = $penerimaan['tanggal_faktur'];


                                                                                                // Format tanggal sebagai dd-Bulan-yyyy (misal: 27 Juni 2024)

                                                                                                // Pisahkan tanggal, bulan, dan tahun dari tanggal asli
                                                                                                $day = date("d", strtotime($original_date));
                                                                                                $month = date("m", strtotime($original_date));
                                                                                                $year = date("Y", strtotime($original_date));

                                                                                                // Daftar nama bulan dalam bahasa Indonesia
                                                                                                $bulan = array(
                                                                                                    1 => "Januari", 2 => "Februari", 3 => "Maret", 4 => "April", 5 => "Mei", 6 => "Juni",
                                                                                                    7 => "Juli", 8 => "Agustus", 9 => "September", 10 => "Oktober", 11 => "November", 12 => "Desember"
                                                                                                );

                                                                                                // Format tanggal sesuai dengan format yang diinginkan
                                                                                                $formatted_date = $day . ' ' . $bulan[(int)$month] . ' ' . $year;

                                                                                                echo $formatted_date;

                                                                                                ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
                                                        </div>
                                                        <div class="mb-5 sm:block md:flex items-center">
                                                            <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Tanggal Jatuh Tempo</label>
                                                            <input type="text" name="" value="<?php
                                                                                                // Tanggal asli dari data
                                                                                                $original_date = $penerimaan['tanggal_jthtempo'];


                                                                                                // Format tanggal sebagai dd-Bulan-yyyy (misal: 27 Juni 2024)

                                                                                                // Pisahkan tanggal, bulan, dan tahun dari tanggal asli
                                                                                                $day = date("d", strtotime($original_date));
                                                                                                $month = date("m", strtotime($original_date));
                                                                                                $year = date("Y", strtotime($original_date));

                                                                                                // Daftar nama bulan dalam bahasa Indonesia
                                                                                                $bulan = array(
                                                                                                    1 => "Januari", 2 => "Februari", 3 => "Maret", 4 => "April", 5 => "Mei", 6 => "Juni",
                                                                                                    7 => "Juli", 8 => "Agustus", 9 => "September", 10 => "Oktober", 11 => "November", 12 => "Desember"
                                                                                                );

                                                                                                // Format tanggal sesuai dengan format yang diinginkan
                                                                                                $formatted_date = $day . ' ' . $bulan[(int)$month] . ' ' . $year;

                                                                                                echo $formatted_date;

                                                                                                ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
                                                        </div>


                                                        <div class="mb-5 sm:block md:flex items-center">
                                                            <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Pegawai</label>
                                                            <input type="text" name="" value="<?php foreach ($pegawai_data as $pegawai) {
                                                                                                    if ($pegawai['id'] === $penerimaan['id_pegawai']) {
                                                                                                        echo $pegawai['nama'];
                                                                                                    }
                                                                                                } ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
                                                        </div>
                                                        <div class="mb-5 sm:block md:flex items-center">
                                                            <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Ruangan</label>
                                                            <input type="text" name="" value="<?php foreach ($ruangan_data as $ruangan) {
                                                                                                    if ($ruangan['id'] === $penerimaan['id_ruangan']) {
                                                                                                        echo $ruangan['nama'];
                                                                                                    }
                                                                                                } ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>

                                                        </div>
                                                    </div>
                                                    <div class="pt-2 border-t border-[#F1F1F1]">
                                                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Pesanan</h3>
                                                        <div>

                                                            <div class="flex items-center justify-between mb-2">
                                                                <div class="w-1/2">


                                                                </div>
                                                                <div class="flex justify-end w-1/2">
                                                                    <p class="font-bold mr-2 text-center text-gray-900 text-sm rounded-lg w-full">Harga/Item</p>
                                                                    <p class="font-bold text-center text-gray-900 text-sm rounded-lg w-full">Subtotal/Item</p>
                                                                </div>
                                                            </div>

                                                            <?php $subtotal = 0;
                                                            foreach ($detail_data as $detail) {
                                                                if ($detail['id_penerimaan'] === $penerimaan['id']) {
                                                                    $subtotal += $detail['total_per_item'] ?>

                                                                    <div class="flex items-center justify-between">
                                                                        <div class="w-1/2 font-medium">
                                                                            <?php foreach ($medis_data as $medis) {
                                                                                if ($medis['id'] === $detail['id_barang_medis']) {
                                                                                    echo $medis['nama'];
                                                                                }
                                                                            } ?>
                                                                            <br>
                                                                        </div>
                                                                        <div class="flex justify-end w-1/2">
                                                                            <input type="text" name="" value="<?= "Rp " . number_format($detail['h_pesan'], 0, ',', '.') ?? "Belum ada total" ?>" class="text-center mr-2 bg-gray-100 text-gray-900 text-sm rounded-lg px-2 py-1 w-full dark:border-gray-600 dark:text-white" readonly>
                                                                            <input type="text" name="" value="<?= "Rp " . number_format($detail['subtotal_per_item'], 0, ',', '.') ?? "Belum ada total" ?>" class="text-center bg-gray-100 font-[600] text-gray-900 text-sm rounded-lg px-2 py-1 w-full dark:border-gray-600 dark:text-white" readonly>
                                                                        </div>
                                                                    </div>
                                                                    <div><small>Jumlah:
                                                                            <?= $detail['jumlah'] ?> <?php foreach ($satuan_data as $satuan) {
                                                                                                            if ($satuan['id'] === $detail['id_satuan'] && $detail['id_satuan'] !== 1) {
                                                                                                                echo $satuan['nama'];
                                                                                                            } else {
                                                                                                                echo '';
                                                                                                            }
                                                                                                        } ?>
                                                                        </small></div>
                                                                    <div class="flex justify-between py-1">
                                                                        <label class="block mb-2 md:mb-0 text-sm font-[600] text-gray-900 dark:text-white md:w-1/2">Diskon Persen (Jumlah)</label>
                                                                        <label class="block mb-2 md:mb-0 text-sm font-[600] text-gray-900 dark:text-white"><?= number_format($detail['diskon_persen'], 0, ',', '.') . "% (Rp" . number_format($detail['diskon_jumlah'], 0, ',', '.') . ")" ?></label>
                                                                    </div>
                                                                    <div class="flex justify-between py-1">
                                                                        <label class="block mb-2 md:mb-0 text-sm font-[600] text-gray-900 dark:text-white md:w-1/2">Total/Item</label>
                                                                        <label class="block mb-2 md:mb-0 text-sm font-[600] text-gray-900 dark:text-white">Rp <?= number_format($detail['total_per_item'], 0, ',', '.'); ?></label>
                                                                    </div>
                                                                    <br>

                                                            <?php }
                                                            } ?>
                                                            <div>
                                                                <div class="flex justify-between py-1">
                                                                    <label class="block mb-2 md:mb-0 text-sm font-[600] text-gray-900 dark:text-white md:w-1/2">Total (Sblm Pajak)</label>
                                                                    <label class="block mb-2 md:mb-0 text-sm font-[600] text-gray-900 dark:text-white">Rp <?= number_format($subtotal, 0, ',', '.') ?> </label>
                                                                </div>

                                                                <div class="flex justify-between py-1">
                                                                    <label class="block mb-2 md:mb-0 text-sm font-[600] text-gray-900 dark:text-white md:w-1/2">Pajak Persen (Jumlah)</label>
                                                                    <label class="block mb-2 md:mb-0 text-sm font-[600] text-gray-900 dark:text-white"> <?= $penerimaan['pajak_persen'] ?>% (<?= number_format($penerimaan['pajak_jumlah'], 0, ',', '.') ?>)</label>
                                                                </div>
                                                                <div class="flex justify-between py-1">
                                                                    <label class="block mb-2 md:mb-0 text-sm font-[600] text-gray-900 dark:text-white md:w-1/2">Materai</label>
                                                                    <label class="block mb-2 md:mb-0 text-sm font-[600] text-gray-900 dark:text-white">Rp <?= number_format($penerimaan['materai'], 0, ',', '.') ?></label>
                                                                </div>
                                                            </div>
                                                            <div class="border-t border-[#F1F1F1] my-2">
                                                                <div class="flex justify-between pt-1">
                                                                    <label class="block mb-2 md:mb-0 text-sm font-[600] text-gray-900 dark:text-white md:w-1/2">Total Keseluruhan</label>
                                                                    <label class="block mb-2 md:mb-0 text-sm font-[600] text-gray-900 dark:text-white">Rp <?= number_format($penerimaan['tagihan'], 0, ',', '.') ?></label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex justify-end items-center gap-x-2 py-3 px-4 border-t dark:border-neutral-700">


                                        </div>
                                    </div>
                                </div>
                </div>
                <tr>
                    <td>
                        <div class="px-6 py-3">
                            <div class="flex items-center justify-center gap-x-3">
                                <span class="block text-sm font-semibold text-gray-800 dark:text-gray-200"><?php $original_date = $penerimaan['tanggal_datang'];
                                                                                                            $day = date("d", strtotime($original_date));
                                                                                                            $month = date("m", strtotime($original_date));
                                                                                                            $year = date("Y", strtotime($original_date));

                                                                                                            // Daftar nama bulan dalam bahasa Indonesia
                                                                                                            $bulan = array(
                                                                                                                1 => "Januari", 2 => "Februari", 3 => "Maret", 4 => "April", 5 => "Mei", 6 => "Juni",
                                                                                                                7 => "Juli", 8 => "Agustus", 9 => "September", 10 => "Oktober", 11 => "November", 12 => "Desember"
                                                                                                            );

                                                                                                            // Format tanggal sesuai dengan format yang diinginkan
                                                                                                            $formatted_date = $day . ' ' . $bulan[(int)$month] . ' ' . $year;

                                                                                                            echo $formatted_date; ?></span>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="px-6 py-3">
                            <span class="text-center block text-sm font-semibold text-gray-800 dark:text-gray-200"><?php $original_jthtempo_date = $penerimaan['tanggal_jthtempo'];
                                                                                                                    $day = date("d", strtotime($original_jthtempo_date));
                                                                                                                    $month = date("m", strtotime($original_jthtempo_date));
                                                                                                                    $year = date("Y", strtotime($original_jthtempo_date));

                                                                                                                    // Daftar nama bulan dalam bahasa Indonesia
                                                                                                                    $bulan = array(
                                                                                                                        1 => "Januari", 2 => "Februari", 3 => "Maret", 4 => "April", 5 => "Mei", 6 => "Juni",
                                                                                                                        7 => "Juli", 8 => "Agustus", 9 => "September", 10 => "Oktober", 11 => "November", 12 => "Desember"
                                                                                                                    );

                                                                                                                    // Format tanggal sesuai dengan format yang diinginkan
                                                                                                                    $formatted_jthtempo_date = $day . ' ' . $bulan[(int)$month] . ' ' . $year;

                                                                                                                    echo $formatted_jthtempo_date; ?></span>
                        </div>
                    </td>
                    <td>
                        <div class="px-6 py-3">
                            <span class="text-center block text-sm font-semibold cursor-pointer hover:underline text-gray-800 dark:text-gray-200" data-hs-overlay="#hs-vertically-centered-scrollable-modal-<?= $penerimaan['id'] ?>"><?= $penerimaan['no_faktur'] ?? '-' ?></span>
                        </div>
                    </td>

                    <td>
                        <div class="px-6 py-3 text-center">
                            <span class="text-center block text-sm font-semibold cursor-pointer hover:underline text-gray-800 dark:text-gray-200"><?php foreach ($supplier_data as $supplier) {
                                                                                                                                                        if ($supplier['id'] === $penerimaan['id_supplier']) {
                                                                                                                                                            echo $supplier['nama'];
                                                                                                                                                        }
                                                                                                                                                    } ?></span>
                        </div>
                    </td>
                    <td>
                        <div class="pl-6 py-1.5 flex justify-center">
                            <?php
                                $row_id  = $penerimaan['id'];
                                $api_url = '/penerimaanmedis';
                                echo view('components/aksi/detail',[
                                    'row_id'  => $row_id,
                                    'api_url' => $api_url   
                                ]);
                                echo view('components/aksi_ubah',[
                                    'row_id'  => $row_id,
                                    'api_url' => $api_url   
                                ]);
                                echo view('components/aksi/hapus',[
                                    'row_id'  => $row_id,
                                    'api_url' => $api_url   
                                ]); 
                            ?>
                        </div>
                    </td>

                </tr>


            <?php endforeach; ?>


            </tbody>


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