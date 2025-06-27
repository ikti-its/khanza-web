<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<!-- Table Section -->
<div class="max-w-[85rem] py-6 lg:py-3 px-8 mx-auto">
    <!-- Card -->
    <div class="flex flex-col">
        <div class="-m-1.5 overflow-y-auto">
            <div class="sm:px-6 min-w-full inline-block align-middle">
                <div class="p-5 bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden dark:bg-slate-900 dark:border-gray-700">
                    <!-- Header -->
                    <div class="py-1 flex justify-between items-center border-gray-200 dark:border-gray-700">
                        <div>
                            <h2 class="mb-2 text-xl font-semibold text-gray-800 dark:text-gray-200">
                                Tagihan Barang Medis
                            </h2>

                        </div>


                    </div>
                    <?= view('components/search_bar') ?>

                    <div id="noDataFound" style="display: none;">Data tidak ditemukan</div>
                    <!-- End Header -->

                    <!-- Table -->
                    <table id="myTable" class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <?php 
                            $widths  = [18, 18, 18, 22, 24];
                            echo view('components/tabel/colgroup',['widths' => $widths]);
                            
                            $columns = [
                                'Tanggal Bayar',
                                'No Faktur',
                                'Jumlah Bayar',
                                'Status',
                                'Aksi'
                            ];
                            echo view('components/tabel/thead',['kolom' => $columns]);
                        ?>    

                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            <?php foreach ($pengajuan_data as $pengajuan) : ?>
                                <?php if ($pengajuan['status_pesanan'] === '6' || $pengajuan['status_pesanan'] === '7') { ?>
                                    <?php foreach ($pemesanan_data as $pemesanan) : ?>
                                        <?php foreach ($penerimaan_data as $penerimaan) : ?>
                                            <?php foreach ($tagihan_medis_data as $tagihan) : ?>
                                                <?php if ($tagihan['id_pengajuan'] === $pengajuan['id'] && $tagihan['id_penerimaan'] === $penerimaan['id'] && $pemesanan['id_pengajuan'] === $pengajuan['id']) : ?>
                                                    <div id="hs-vertically-centered-scrollable-modal-<?= $tagihan['id'] ?>" class="hs-overlay hidden size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto pointer-events-none">
                                                        <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto h-[calc(100%-3.5rem)] min-h-[calc(100%-3.5rem)] flex items-center">
                                                            <div class="w-full max-h-full overflow-hidden flex flex-col bg-white border shadow-sm rounded-xl pointer-events-auto dark:bg-neutral-800 dark:border-neutral-700 dark:shadow-neutral-700/70">
                                                                <div class="flex justify-between items-center py-3 px-4 border-b dark:border-neutral-700">
                                                                    <h3 class="font-bold text-gray-800 dark:text-white">
                                                                        <?= $penerimaan['no_faktur'] ?>
                                                                    </h3>
                                                                    <button type="button" class="flex justify-center items-center size-7 text-sm font-semibold rounded-full border border-transparent text-gray-800 hover:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-neutral-700" data-hs-overlay="#hs-vertically-centered-scrollable-modal-<?= $tagihan['id'] ?>">
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
                                                                                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Tanggal Pemesanan</label>
                                                                                <input type="text" name="" value="<?php
                                                                                                                    // Tanggal asli dari data
                                                                                                                    $original_date = $tagihan['tanggal_bayar'];


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
                                                                                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Jumlah Bayar</label>
                                                                                <input type="text" name="" value="<?= $tagihan['jumlah_bayar'] ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
                                                                            </div>
                                                                            <div class="mb-5 sm:block md:flex items-center">
                                                                                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Pegawai</label>
                                                                                <input type="text" name="" value="<?php foreach ($pegawai_data as $pegawai) {
                                                                                                                        if ($pegawai['id'] === $tagihan['id_pegawai']) {
                                                                                                                            echo $pegawai['nama'];
                                                                                                                        }
                                                                                                                    } ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
                                                                            </div>
                                                                            <div class="mb-5 sm:block md:flex items-center">
                                                                                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Keterangan</label>
                                                                                <input type="text" name="" value="<?= $tagihan['keterangan'] ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
                                                                            </div>
                                                                            <div class="mb-5 sm:block md:flex items-center">
                                                                                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Nomor Bukti</label>
                                                                                <input type="text" name="" value="<?= $tagihan['no_bukti'] ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
                                                                            </div>
                                                                            <div class="mb-5 sm:block md:flex items-center">
                                                                                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Akun Bayar</label>
                                                                                <input type="text" name="" value="<?= $tagihan['id_akun_bayar'] ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
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
                                                                                        <p class="font-bold text-center text-gray-900 text-sm rounded-lg w-full">Total/Item</p>
                                                                                    </div>
                                                                                </div>



                                                                                <?php $subtotal = 0;
                                                                                foreach ($pesanan_data as $pesanan) {

                                                                                    if ($pesanan['id_pengajuan'] === $pemesanan['id_pengajuan']) {
                                                                                        $subtotal += $pesanan['total_per_item'] ?>

                                                                                        <div class="flex items-center justify-between">
                                                                                            <div class="w-1/2 font-medium">
                                                                                                <?php foreach ($medis_data as $medis) {
                                                                                                    if ($medis['id'] === $pesanan['id_barang_medis']) {
                                                                                                        echo $medis['nama'];
                                                                                                    }
                                                                                                } ?>
                                                                                                <br>
                                                                                            </div>
                                                                                            <div class="flex justify-end w-1/2">
                                                                                                <input type="text" name="" value="<?= "Rp " . number_format($pesanan['harga_satuan_pemesanan'], 0, ',', '.') ?>" class="text-center mr-2 bg-gray-100 text-gray-900 text-sm rounded-lg px-2 py-1 w-full dark:border-gray-600 dark:text-white" readonly>
                                                                                                <input type="text" name="" value="<?= "Rp " . number_format($pesanan['total_per_item'], 0, ',', '.') ?? "Belum ada total" ?>" class="text-center bg-gray-100 font-[600] text-gray-900 text-sm rounded-lg px-2 py-1 w-full dark:border-gray-600 dark:text-white" readonly>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div><small>Jumlah:
                                                                                                <?= $pesanan['jumlah_pesanan'] ?> <?php foreach ($satuan_data as $satuan) {
                                                                                                                                        if ($satuan['id'] === $pesanan['satuan'] && $pesanan['satuan'] !== 1) {
                                                                                                                                            echo $satuan['nama'];
                                                                                                                                        } else {
                                                                                                                                            echo '';
                                                                                                                                        }
                                                                                                                                    } ?>
                                                                                            </small></div>
                                                                                        <div class="flex justify-between py-1">
                                                                                            <label class="block mb-2 md:mb-0 text-sm font-[600] text-gray-900 dark:text-white md:w-1/2">Diskon Persen (Jumlah)</label>
                                                                                            <label class="block mb-2 md:mb-0 text-sm font-[600] text-gray-900 dark:text-white"><?= number_format($pesanan['diskon_persen'], 0, ',', '.') . "% (Rp" . number_format($pesanan['diskon_jumlah'], 0, ',', '.') . ")" ?></label>
                                                                                        </div>
                                                                                        <div class="flex justify-between py-1">
                                                                                            <label class="block mb-2 md:mb-0 text-sm font-[600] text-gray-900 dark:text-white md:w-1/2">Total/Item</label>
                                                                                            <label class="block mb-2 md:mb-0 text-sm font-[600] text-gray-900 dark:text-white">Rp <?= number_format($pesanan['total_per_item'], 0, ',', '.'); ?></label>
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
                                                                                        <label class="block mb-2 md:mb-0 text-sm font-[600] text-gray-900 dark:text-white"> <?= $pemesanan['pajak_persen'] ?>% (<?= number_format($pemesanan['pajak_jumlah'], 0, ',', '.') ?>)</label>
                                                                                    </div>
                                                                                    <div class="flex justify-between py-1">
                                                                                        <label class="block mb-2 md:mb-0 text-sm font-[600] text-gray-900 dark:text-white md:w-1/2">Materai</label>
                                                                                        <label class="block mb-2 md:mb-0 text-sm font-[600] text-gray-900 dark:text-white">Rp <?= number_format($pemesanan['materai'], 0, ',', '.') ?></label>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="border-t border-[#F1F1F1] my-2">
                                                                                    <div class="flex justify-between pt-1">
                                                                                        <label class="block mb-2 md:mb-0 text-sm font-[600] text-gray-900 dark:text-white md:w-1/2">Total Tagihan</label>
                                                                                        <label class="block mb-2 md:mb-0 text-sm font-[600] text-gray-900 dark:text-white">Rp <?= number_format($pemesanan['total_pemesanan'], 0, ',', '.') ?></label>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                    <tr>
                                                        <td class="h-px w-64 whitespace-nowrap">
                                                            <div class="px-6 py-3">
                                                                <span class=" text-center block text-sm font-semibold text-gray-800 cursor-pointer dark:text-gray-200 hover:underline" data-id="<?= $tagihan['id'] ?>"><?php $original_date = $tagihan['tanggal_bayar'];
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
                                                        </td>
                                                        <td class="h-px w-72 whitespace-nowrap">
                                                            <div class="px-6 py-3">
                                                                <span class=" text-center block text-sm font-semibold cursor-pointer hover:underline text-gray-800 dark:text-gray-200" data-hs-overlay="#hs-vertically-centered-scrollable-modal-<?= $tagihan['id'] ?>"><?= $penerimaan['no_faktur'] ?? 'N/A' ?></span>
                                                            </div>
                                                        </td>
                                                        <td class="h-px w-72 whitespace-nowrap">
                                                            <div class="px-6 py-3">
                                                                <span class=" text-center block cursor-default text-sm font-semibold text-gray-800 dark:text-gray-200"><?= $tagihan['jumlah_bayar'] ?? 'N/A' ?></span>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="px-6 py-3 text-center ">
                                                                <?php
                                                                switch ($pengajuan['status_pesanan']) {

                                                                    case '6':
                                                                        echo '<span class="px-2 py-1.5 inline-flex items-center gap-x-1 text-xs font-semibold bg-[#FEF9C3] text-[#F49A35] rounded-full dark:bg-teal-500/10 dark:text-teal-500">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="13" viewBox="0 0 14 13" fill="none">
                                                                            <path d="M0 0V12.6667L2 11.3333L4 12.6667L6 11.3333L6.86667 11.9067C6.73333 11.52 6.66667 11.1 6.66667 10.6667C6.66708 10.0268 6.82086 9.39641 7.1151 8.82825C7.40935 8.26009 7.83549 7.77073 8.35782 7.40118C8.88014 7.03163 9.48344 6.79267 10.1171 6.70431C10.7509 6.61594 11.3965 6.68077 12 6.89333V0H0ZM9.33333 2.66667V4H2.66667V2.66667H9.33333ZM8 5.33333V6.66667H2.66667V5.33333H8ZM8.33333 10.6667L10.1667 12.6667L13.3333 9.48667L12.56 8.54667L10.1667 10.94L9.10667 9.88L8.33333 10.6667Z" fill="#D97706"/>
                                                                            </svg>
                                                                        Tagihan belum lunas
                                                                    </span>';
                                                                        break;
                                                                    case '7':
                                                                        echo '<span class="px-2 py-1.5 inline-flex items-center gap-x-1 text-xs font-semibold bg-[#D6F9F3] text-[#13594E] rounded-full dark:bg-teal-500/10 dark:text-teal-500">
                                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                                                        <path d="M10 11.1267V8.66667H11V10.5467L12.6267 11.4867L12.1267 12.3533L10 11.1267ZM2 14.6667V2H14V7.4C14.8267 8.24 15.3333 9.39333 15.3333 10.6667C15.3333 13.2467 13.2467 15.3333 10.6667 15.3333C10.0242 15.3351 9.38844 15.2033 8.79967 14.9462C8.21091 14.6891 7.68205 14.3124 7.24667 13.84L6 14.6667L4 13.3333L2 14.6667ZM6.44667 8.66667C6.68667 8.16667 7 7.71333 7.4 7.33333H4.66667V8.66667H6.44667ZM11.3333 6V4.66667H4.66667V6H11.3333ZM10.6667 14C12.5067 14 14 12.5067 14 10.6667C14 8.82667 12.5067 7.33333 10.6667 7.33333C8.82667 7.33333 7.33333 8.82667 7.33333 10.6667C7.33333 12.5067 8.82667 14 10.6667 14Z" fill="#13594E"/>
                                                                        </svg>
                                                                            Tagihan telah Dibayar
                                                                        </span>';
                                                                        break;
                                                                    default:
                                                                        echo '<span class="inline-flex items-center gap-x-1.5 py-1.5 px-3 rounded-full text-xs font-medium bg-[#F1F1F1]">
                                                                    <span class="size-1.5 inline-block rounded-full bg-[#535353]"></span>
                                                                         Tidak ada status
                                                                    </span>';
                                                                        break;
                                                                }
                                                                ?>
                                                            </div>
                                                        </td>
                                                        <td class="size-px whitespace-nowrap">
                                                            <div class="px-3 py-1.5 inline-flex">
                                                                <?php
                                                                    $row_id  = $tagihan['id'];
                                                                    $api_url = '/tagihanmedis';
                                                                    echo view('components/aksi/detail',[
                                                                        'row_id'  => $row_id,
                                                                        'api_url' => $api_url   
                                                                    ]);
                                                                    echo view('components/aksi/ubah',[
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

                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        <?php endforeach; ?>
                                    <?php endforeach; ?>
                                <?php } ?>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <!-- End Table -->

                    <!-- Footer -->
                    <div class="px-6 py-4 grid gap-3 md:flex md:justify-between md:items-center border-t border-gray-200 dark:border-neutral-700">
                        <!-- Pagination -->
                        <nav class="flex w-full justify-between items-center gap-x-1">
                            <!-- Previous Button -->
                            <div class="inline-flex gap-x-2">
                                <button type="button" class="min-h-[38px] min-w-[38px] py-2 px-2.5 inline-flex justify-center items-center gap-x-2 text-sm rounded-lg text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-white/10 dark:focus:bg-white/10" aria-label="Previous page" <?= $meta_data['page'] <= 1 ? 'disabled' : '' ?> onclick="window.location.href='/tagihanmedis?page=<?= $meta_data['page'] - 1 ?>&size=<?= $meta_data['size'] ?>'">
                                    <svg class="flex-shrink-0 size-3.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="m15 18-6-6 6-6"></path>
                                    </svg>
                                    <span aria-hidden="true" class="hidden sm:block">Previous</span>
                                </button>
                            </div>

                            <!-- Page Numbers -->
                            <div class="flex items-center gap-x-1">
                                <?php
                                $total_pages = $meta_data['total'];
                                $current_page = $meta_data['page'];
                                $range = 2; // Number of pages to show before and after the current page
                                $show_items = ($range * 2) + 1;

                                if ($total_pages <= $show_items) {
                                    for ($i = 1; $i <= $total_pages; $i++) {
                                        echo '<button type="button" class="min-h-[38px] min-w-[38px] flex justify-center items-center ' . ($current_page == $i ? 'bg-gray-200 text-gray-800 dark:bg-neutral-600 dark:focus:bg-neutral-500' : 'text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:text-white dark:hover:bg-white/10 dark:focus:bg-white/10') . ' py-2 px-3 text-sm rounded-lg" ' . ($current_page == $i ? 'aria-current="page"' : '') . ' onclick="window.location.href=\'/tagihanmedis?page=' . $i . '&size=' . $meta_data['size'] . '\'">' . $i . '</button>';
                                    }
                                } else {
                                    if ($current_page > $range + 1) {
                                        echo '<button type="button" class="min-h-[38px] min-w-[38px] flex justify-center items-center text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:text-white dark:hover:bg-white/10 dark:focus:bg-white/10 py-2 px-3 text-sm rounded-lg" onclick="window.location.href=\'/tagihanmedis?page=1&size=' . $meta_data['size'] . '\'">1</button>';
                                        if ($current_page > $range + 2) {
                                            echo '<span class="py-2 px-3 text-sm">...</span>';
                                        }
                                    }

                                    for ($i = max($current_page - $range, 1); $i <= min($current_page + $range, $total_pages); $i++) {
                                        echo '<button type="button" class="min-h-[38px] min-w-[38px] flex justify-center items-center ' . ($current_page == $i ? 'bg-gray-200 text-gray-800 dark:bg-neutral-600 dark:focus:bg-neutral-500' : 'text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:text-white dark:hover:bg-white/10 dark:focus:bg-white/10') . ' py-2 px-3 text-sm rounded-lg" ' . ($current_page == $i ? 'aria-current="page"' : '') . ' onclick="window.location.href=\'/tagihanmedis?page=' . $i . '&size=' . $meta_data['size'] . '\'">' . $i . '</button>';
                                    }

                                    if ($current_page < $total_pages - $range - 1) {
                                        if ($current_page < $total_pages - $range - 2) {
                                            echo '<span class="py-2 px-3 text-sm">...</span>';
                                        }
                                        echo '<button type="button" class="min-h-[38px] min-w-[38px] flex justify-center items-center text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:text-white dark:hover:bg-white/10 dark:focus:bg-white/10 py-2 px-3 text-sm rounded-lg" onclick="window.location.href=\'/tagihanmedis?page=' . $total_pages . '&size=' . $meta_data['size'] . '\'">' . $total_pages . '</button>';
                                    }
                                }
                                ?>
                            </div>

                            <!-- Next Button -->
                            <div class="inline-flex gap-x-2">
                                <button type="button" class="min-h-[38px] min-w-[38px] py-2 px-2.5 inline-flex justify-center items-center gap-x-2 text-sm rounded-lg text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-white/10 dark:focus:bg-white/10" aria-label="Next page" <?= $current_page >= $total_pages ? 'disabled' : '' ?> onclick="window.location.href='/tagihanmedis?page=<?= $current_page + 1 ?>&size=<?= $meta_data['size'] ?>'">
                                    <span aria-hidden="true" class="hidden sm:block">Next</span>
                                    <svg class="flex-shrink-0 size-3.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="m9 18 6-6-6-6"></path>
                                    </svg>
                                </button>
                            </div>
                        </nav>
                    </div>

                    <!-- End Footer -->
                </div>
            </div>
        </div>
    </div>
    <!-- End Card -->
</div>
<!-- End Table Section -->

<?= $this->endSection(); ?>