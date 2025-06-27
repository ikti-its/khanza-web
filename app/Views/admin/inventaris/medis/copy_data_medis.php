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
                                Barang Medis
                            </h2>

                        </div>
                        <div class="flex gap-x-6 justify-center items-center">
                            <div class="relative">
                                <?= view('components/notif/icon') ?>

                                <!-- Notification Pop-up -->
                                <div id="notif-popup" class="absolute right-0 mt-2 w-[30rem] overflow-y-auto z-[2] bg-white rounded-lg shadow-lg hidden">
                                    <?= view('components/notif/notif') ?>
                                    <div>
                                        <div id="stok-content" class="max-h-[15rem] overflow-y-auto">
                                            <?php
                                            $count_notif_stok = 0;
                                            $today = new DateTime();
                                            $today->setTime(0, 0, 0);
                                            if ($count_notif_stok !== 0) {
                                                foreach ($medis_tanpa_params_data as $medis_stok) {
                                                    if ($medis_stok['stok'] <= $medis_stok['stok_minimum']) {
                                                        $count_notif_stok++; ?>
                                                        <a href="/datamedis/edit/<?= $medis_stok['id'] ?>" class="p-4 flex items-center border-b-2 border-b-[#F1F1F1] border-l-2 border-l-[#DA4141] hover:bg-gray-100">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32" fill="none">
                                                                <path d="M12.5358 6.667C14.0754 4.00033 17.9244 4.00033 19.464 6.66699L27.8356 21.167C29.3752 23.8337 27.4507 27.167 24.3715 27.167H7.62834C4.54914 27.167 2.62464 23.8337 4.16424 21.167L12.5358 6.667Z" fill="#DA4141" />
                                                                <path d="M16 18.333C15.4533 18.333 15 17.8797 15 17.333V10.333C15 9.78634 15.4533 9.33301 16 9.33301C16.5467 9.33301 17 9.78634 17 10.333V17.333C17 17.8797 16.5467 18.333 16 18.333Z" fill="#FEE2E2" />
                                                                <path d="M15.9998 23.0001C15.8265 23.0001 15.6532 22.9601 15.4932 22.8934C15.3198 22.8268 15.1865 22.7335 15.0531 22.6135C14.9331 22.4802 14.8398 22.3335 14.7598 22.1735C14.6932 22.0135 14.6665 21.8401 14.6665 21.6668C14.6665 21.3201 14.7998 20.9734 15.0531 20.7201C15.1865 20.6001 15.3198 20.5068 15.4932 20.4402C15.9865 20.2268 16.5732 20.3468 16.9465 20.7201C17.0665 20.8534 17.1598 20.9868 17.2265 21.1601C17.2931 21.3201 17.3332 21.4935 17.3332 21.6668C17.3332 21.8401 17.2931 22.0135 17.2265 22.1735C17.1598 22.3335 17.0665 22.4802 16.9465 22.6135C16.6932 22.8668 16.3598 23.0001 15.9998 23.0001Z" fill="#FEE2E2" />
                                                            </svg>
                                                            <div class="mx-2">
                                                                <span>Stok <span class="font-semibold"><?= $medis_stok['nama'] ?></span> telah mencapai jumlah minimum</span>
                                                                <div class="py-1 font-semibold text-sm text-[#DA4141]">Sisa stok: <?= $medis_stok['stok'] ?></div>
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
                                        <div id="kadaluwarsa-content" class="hidden max-h-[15rem] overflow-y-auto">
                                            <?php
                                            foreach ($medis_tanpa_params_data as $medis_tanpa_params) {
                                                $combined_data = array_combine(['obat', 'bhp', 'alkes', 'darah'], [$obat_data, $bhp_data, $alkes_data, $darah_data]);
                                                foreach ($combined_data as $jenis => $data) {
                                                    foreach ($data as $item) {
                                                        if ($medis_tanpa_params['id'] === $item['id_barang_medis']) {
                                                            if ($jenis !== 'alkes') {
                                                                // Check item expiration
                                                                $item_kadaluwarsa = new DateTime($item['kadaluwarsa']);
                                                                $tanggalnull = new DateTime('0001-01-01');
                                                                $interval = $today->diff($item_kadaluwarsa);
                                                                $days_left_item = (int)$interval->format('%r%a');

                                                                if ($today < $item_kadaluwarsa && $days_left_item <= $medis_tanpa_params['notifikasi_kadaluwarsa_hari'] && $medis_tanpa_params['id'] === $item['id_barang_medis'] && $item_kadaluwarsa->format('Y-m-d') !== $tanggalnull->format('Y-m-d')) {
                                            ?>
                                                                    <a href="/datamedis/edit/<?= $medis_tanpa_params['id'] ?>" class="kadaluwarsabaris p-4 flex items-center justify-between border-b-2 border-b-[#F1F1F1] border-l-2 border-l-[#F49A35] hover:bg-gray-100">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" width="33" height="32" viewBox="0 0 33 32" fill="none">
                                                                            <path d="M13.0359 6.667C14.5755 4.00033 18.4245 4.00033 19.9641 6.66699L28.3357 21.167C29.8753 23.8337 27.9508 27.167 24.8716 27.167H8.12846C5.04926 27.167 3.12476 23.8337 4.66436 21.167L13.0359 6.667Z" fill="#F09834" />
                                                                            <path d="M16.5 18.333C15.9533 18.333 15.5 17.8797 15.5 17.333V10.333C15.5 9.78634 15.9533 9.33301 16.5 9.33301C17.0467 9.33301 17.5 9.78634 17.5 10.333V17.333C17.5 17.8797 17.0467 18.333 16.5 18.333Z" fill="#FEF9C3" />
                                                                            <path d="M16.5001 23.0001C16.3267 23.0001 16.1534 22.9601 15.9934 22.8934C15.8201 22.8268 15.6867 22.7335 15.5534 22.6135C15.4334 22.4802 15.3401 22.3335 15.2601 22.1735C15.1934 22.0135 15.1667 21.8401 15.1667 21.6668C15.1667 21.3201 15.3001 20.9734 15.5534 20.7201C15.6867 20.6001 15.8201 20.5068 15.9934 20.4402C16.4867 20.2268 17.0734 20.3468 17.4468 20.7201C17.5668 20.8534 17.6601 20.9868 17.7267 21.1601C17.7934 21.3201 17.8334 21.4935 17.8334 21.6668C17.8334 21.8401 17.7934 22.0135 17.7267 22.1735C17.6601 22.3335 17.5668 22.4802 17.4468 22.6135C17.1934 22.8668 16.8601 23.0001 16.5001 23.0001Z" fill="#FEF9C3" />
                                                                        </svg>
                                                                        <div class="w-full ml-2">

                                                                            <p class="w-full">Barang <span class="font-semibold"><?= $medis_tanpa_params['nama'] ?></span> telah mendekati kadaluwarsa</p>
                                                                            <p class="pt-1 font-semibold text-[#F49A35]"><?php $original_date = $item['kadaluwarsa'];
                                                                                                                            $day = date("d", strtotime($original_date));
                                                                                                                            $month = date("m", strtotime($original_date));
                                                                                                                            $year = date("Y", strtotime($original_date));

                                                                                                                            $bulan = array(
                                                                                                                                1 => "Januari", 2 => "Februari", 3 => "Maret", 4 => "April", 5 => "Mei", 6 => "Juni",
                                                                                                                                7 => "Juli", 8 => "Agustus", 9 => "September", 10 => "Oktober", 11 => "November", 12 => "Desember"
                                                                                                                            );

                                                                                                                            $formatted_date = $day . ' ' . $bulan[(int)$month] . ' ' . $year;

                                                                                                                            echo $formatted_date; ?></p>
                                                                        </div>
                                                                        <div class="flex justify-end w-1/4">
                                                                            <div class="flex items-center ml-1 p-[0.375rem] bg-[#FEF9C3] rounded-[62.4375rem]">
                                                                                <p class="font-semibold text-center px-1 text-[#F49A35]"><?= $days_left_item ?> hari</p>
                                                                            </div>
                                                                        </div>
                                                                    </a>


                                                                <?php


                                                                } elseif ($today >= $item_kadaluwarsa && $days_left_item <= $medis_tanpa_params['notifikasi_kadaluwarsa_hari'] && $medis_tanpa_params['id'] === $item['id_barang_medis'] && $item_kadaluwarsa->format('Y-m-d') !== $tanggalnull->format('Y-m-d')) { ?>
                                                                    <a href="/datamedis/edit/<?= $medis_tanpa_params['id'] ?>" class="kadaluwarsabaris p-4 flex items-center justify-between border-b-2 border-b-[#F1F1F1] border-l-2 border-l-[#DA4141] hover:bg-gray-100">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32" fill="none">
                                                                            <path d="M12.5358 6.667C14.0754 4.00033 17.9244 4.00033 19.464 6.66699L27.8356 21.167C29.3752 23.8337 27.4507 27.167 24.3715 27.167H7.62834C4.54914 27.167 2.62464 23.8337 4.16424 21.167L12.5358 6.667Z" fill="#DA4141" />
                                                                            <path d="M16 18.333C15.4533 18.333 15 17.8797 15 17.333V10.333C15 9.78634 15.4533 9.33301 16 9.33301C16.5467 9.33301 17 9.78634 17 10.333V17.333C17 17.8797 16.5467 18.333 16 18.333Z" fill="#FEE2E2" />
                                                                            <path d="M15.9998 23.0001C15.8265 23.0001 15.6532 22.9601 15.4932 22.8934C15.3198 22.8268 15.1865 22.7335 15.0531 22.6135C14.9331 22.4802 14.8398 22.3335 14.7598 22.1735C14.6932 22.0135 14.6665 21.8401 14.6665 21.6668C14.6665 21.3201 14.7998 20.9734 15.0531 20.7201C15.1865 20.6001 15.3198 20.5068 15.4932 20.4402C15.9865 20.2268 16.5732 20.3468 16.9465 20.7201C17.0665 20.8534 17.1598 20.9868 17.2265 21.1601C17.2931 21.3201 17.3332 21.4935 17.3332 21.6668C17.3332 21.8401 17.2931 22.0135 17.2265 22.1735C17.1598 22.3335 17.0665 22.4802 16.9465 22.6135C16.6932 22.8668 16.3598 23.0001 15.9998 23.0001Z" fill="#FEE2E2" />
                                                                        </svg>
                                                                        <div class="w-full ml-2">
                                                                            <p class="w-full">Barang <span class="font-semibold"><?= $medis_tanpa_params['nama'] ?></span> telah melewati kadaluwarsa</p>
                                                                            <p class="pt-1 font-semibold text-[#A71E1E]"><?php $original_date = $item['kadaluwarsa'];
                                                                                                                            $day = date("d", strtotime($original_date));
                                                                                                                            $month = date("m", strtotime($original_date));
                                                                                                                            $year = date("Y", strtotime($original_date));

                                                                                                                            $bulan = array(
                                                                                                                                1 => "Januari", 2 => "Februari", 3 => "Maret", 4 => "April", 5 => "Mei", 6 => "Juni",
                                                                                                                                7 => "Juli", 8 => "Agustus", 9 => "September", 10 => "Oktober", 11 => "November", 12 => "Desember"
                                                                                                                            );

                                                                                                                            $formatted_date = $day . ' ' . $bulan[(int)$month] . ' ' . $year;

                                                                                                                            echo $formatted_date; ?></p>
                                                                        </div>
                                                                        <div class="flex justify-end w-1/5">
                                                                            <div class="flex items-center ml-1 p-2 bg-[#FEE2E2] rounded-[62.4375rem]">
                                                                                <p class="font-semibold text-center px-1 text-[#DA4141]">Lewat <?= abs($days_left_item) ?> hari</p>
                                                                            </div>
                                                                        </div>
                                                                    </a>
                                                                    <?php }


                                                                // Check pesanan expiration
                                                                foreach ($penerimaan_data as $penerimaan) {
                                                                    foreach ($pesanan_data as $pesanan) {
                                                                        if ($penerimaan['id_pengajuan'] === $pesanan['id_pengajuan']) {
                                                                            $pesanan_kadaluwarsa = new DateTime($pesanan['kadaluwarsa']);
                                                                            $interval_pesanan = $today->diff($pesanan_kadaluwarsa);
                                                                            $days_left_pesanan = ceil($interval_pesanan->days);

                                                                            if ($today < $pesanan_kadaluwarsa && $item['id_barang_medis'] === $pesanan['id_barang_medis'] && $days_left_pesanan <= $medis_tanpa_params['notifikasi_kadaluwarsa_hari'] && $pesanan_kadaluwarsa->format('Y-m-d') !== $tanggalnull->format('Y-m-d')) {

                                                                                $show_div = true;

                                                                                foreach ($transaksi_keluar_data as $transaksi_keluar) {
                                                                                    if ($transaksi_keluar['no_faktur'] === $penerimaan['no_faktur'] && $transaksi_keluar['jumlah_keluar'] === $pesanan['jumlah_diterima'] && $item['id_barang_medis'] === $transaksi_keluar['id_barang_medis']) {
                                                                                        $show_div = false;
                                                                                        break;
                                                                                    }
                                                                                }

                                                                                if ($show_div) {
                                                                    ?>
                                                                                    <!-- butuh penyesuaian jumlah_diterima === jumlah_keluar(transaksi) -->

                                                                                    <div class="kadaluwarsabaris p-4 flex items-center justify-between border-b-2 border-b-[#F1F1F1] border-l-2 border-l-[#F49A35]">
                                                                                        <svg xmlns="http://www.w3.org/2000/svg" width="33" height="32" viewBox="0 0 33 32" fill="none">
                                                                                            <path d="M13.0359 6.667C14.5755 4.00033 18.4245 4.00033 19.9641 6.66699L28.3357 21.167C29.8753 23.8337 27.9508 27.167 24.8716 27.167H8.12846C5.04926 27.167 3.12476 23.8337 4.66436 21.167L13.0359 6.667Z" fill="#F09834" />
                                                                                            <path d="M16.5 18.333C15.9533 18.333 15.5 17.8797 15.5 17.333V10.333C15.5 9.78634 15.9533 9.33301 16.5 9.33301C17.0467 9.33301 17.5 9.78634 17.5 10.333V17.333C17.5 17.8797 17.0467 18.333 16.5 18.333Z" fill="#FEF9C3" />
                                                                                            <path d="M16.5001 23.0001C16.3267 23.0001 16.1534 22.9601 15.9934 22.8934C15.8201 22.8268 15.6867 22.7335 15.5534 22.6135C15.4334 22.4802 15.3401 22.3335 15.2601 22.1735C15.1934 22.0135 15.1667 21.8401 15.1667 21.6668C15.1667 21.3201 15.3001 20.9734 15.5534 20.7201C15.6867 20.6001 15.8201 20.5068 15.9934 20.4402C16.4867 20.2268 17.0734 20.3468 17.4468 20.7201C17.5668 20.8534 17.6601 20.9868 17.7267 21.1601C17.7934 21.3201 17.8334 21.4935 17.8334 21.6668C17.8334 21.8401 17.7934 22.0135 17.7267 22.1735C17.6601 22.3335 17.5668 22.4802 17.4468 22.6135C17.1934 22.8668 16.8601 23.0001 16.5001 23.0001Z" fill="#FEF9C3" />
                                                                                        </svg>
                                                                                        <div class="w-full ml-2">
                                                                                            <p class="font-bold"><?= $penerimaan['no_faktur'] ?></p>
                                                                                            <p class="w-full">Barang <span class="font-semibold"><?= $medis_tanpa_params['nama'] ?></span> telah mendekati kadaluwarsa</p>
                                                                                            <p class="pt-1 font-semibold text-[#F49A35]"><?php $original_date = $pesanan['kadaluwarsa'];
                                                                                                                                            $day = date("d", strtotime($original_date));
                                                                                                                                            $month = date("m", strtotime($original_date));
                                                                                                                                            $year = date("Y", strtotime($original_date));

                                                                                                                                            $bulan = array(
                                                                                                                                                1 => "Januari", 2 => "Februari", 3 => "Maret", 4 => "April", 5 => "Mei", 6 => "Juni",
                                                                                                                                                7 => "Juli", 8 => "Agustus", 9 => "September", 10 => "Oktober", 11 => "November", 12 => "Desember"
                                                                                                                                            );

                                                                                                                                            $formatted_date = $day . ' ' . $bulan[(int)$month] . ' ' . $year;

                                                                                                                                            echo $formatted_date; ?></p>
                                                                                        </div>
                                                                                        <div class="flex justify-end w-1/4">
                                                                                            <div class="flex items-center ml-1 p-[0.375rem] bg-[#FEF9C3] rounded-[62.4375rem]">
                                                                                                <p class="font-semibold text-center px-1 text-[#F49A35]"><?= $days_left_pesanan ?> hari</p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>

                                                                                <?php
                                                                                }
                                                                            } elseif ($today >= $pesanan_kadaluwarsa && $item['id_barang_medis'] === $pesanan['id_barang_medis'] && $days_left_pesanan <= $medis_tanpa_params['notifikasi_kadaluwarsa_hari'] && $pesanan_kadaluwarsa->format('Y-m-d') !== $tanggalnull->format('Y-m-d')) {

                                                                                $show_div = true;

                                                                                foreach ($transaksi_keluar_data as $transaksi_keluar) {
                                                                                    if ($transaksi_keluar['no_faktur'] === $penerimaan['no_faktur'] && $transaksi_keluar['jumlah_keluar'] === $pesanan['jumlah_diterima'] && $item['id_barang_medis'] === $transaksi_keluar['id_barang_medis']) {
                                                                                        $show_div = false;
                                                                                        break;
                                                                                    }
                                                                                }

                                                                                if ($show_div) { ?>
                                                                                    <div class="kadaluwarsabaris p-4 flex items-center justify-between border-b-2 border-b-[#F1F1F1] border-l-2 border-l-[#DA4141]">
                                                                                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32" fill="none">
                                                                                            <path d="M12.5358 6.667C14.0754 4.00033 17.9244 4.00033 19.464 6.66699L27.8356 21.167C29.3752 23.8337 27.4507 27.167 24.3715 27.167H7.62834C4.54914 27.167 2.62464 23.8337 4.16424 21.167L12.5358 6.667Z" fill="#DA4141" />
                                                                                            <path d="M16 18.333C15.4533 18.333 15 17.8797 15 17.333V10.333C15 9.78634 15.4533 9.33301 16 9.33301C16.5467 9.33301 17 9.78634 17 10.333V17.333C17 17.8797 16.5467 18.333 16 18.333Z" fill="#FEE2E2" />
                                                                                            <path d="M15.9998 23.0001C15.8265 23.0001 15.6532 22.9601 15.4932 22.8934C15.3198 22.8268 15.1865 22.7335 15.0531 22.6135C14.9331 22.4802 14.8398 22.3335 14.7598 22.1735C14.6932 22.0135 14.6665 21.8401 14.6665 21.6668C14.6665 21.3201 14.7998 20.9734 15.0531 20.7201C15.1865 20.6001 15.3198 20.5068 15.4932 20.4402C15.9865 20.2268 16.5732 20.3468 16.9465 20.7201C17.0665 20.8534 17.1598 20.9868 17.2265 21.1601C17.2931 21.3201 17.3332 21.4935 17.3332 21.6668C17.3332 21.8401 17.2931 22.0135 17.2265 22.1735C17.1598 22.3335 17.0665 22.4802 16.9465 22.6135C16.6932 22.8668 16.3598 23.0001 15.9998 23.0001Z" fill="#FEE2E2" />
                                                                                        </svg>
                                                                                        <div class="w-full ml-2">
                                                                                            <p class="font-bold"><?= $penerimaan['no_faktur'] ?></p>
                                                                                            <p class="w-full">Barang <span class="font-semibold"><?= $medis_tanpa_params['nama'] ?></span> telah melewati kadaluwarsa</p>
                                                                                            <p class="pt-1 font-semibold text-[#A71E1E]"><?php $original_date = $pesanan['kadaluwarsa'];
                                                                                                                                            $day = date("d", strtotime($original_date));
                                                                                                                                            $month = date("m", strtotime($original_date));
                                                                                                                                            $year = date("Y", strtotime($original_date));

                                                                                                                                            $bulan = array(
                                                                                                                                                1 => "Januari", 2 => "Februari", 3 => "Maret", 4 => "April", 5 => "Mei", 6 => "Juni",
                                                                                                                                                7 => "Juli", 8 => "Agustus", 9 => "September", 10 => "Oktober", 11 => "November", 12 => "Desember"
                                                                                                                                            );

                                                                                                                                            $formatted_date = $day . ' ' . $bulan[(int)$month] . ' ' . $year;

                                                                                                                                            echo $formatted_date; ?></p>
                                                                                        </div>
                                                                                        <div class="flex justify-end w-1/5">
                                                                                            <div class="flex items-center ml-1 p-2 bg-[#FEE2E2] rounded-[62.4375rem]">
                                                                                                <p class="font-semibold text-center px-1 text-[#DA4141]">Lewat <?= abs($days_left_pesanan) ?> hari</p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                            <?php   }
                                                                            }
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                            ?>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="h-[1.375rem] border-r-4 bg-[#DCDCDC]"></div>
                            <?= view('components/tambah_button', [
                                'link' => '/datamedis/tambah'
                            ]) ?>
                            <?= view('components/audit_button', [
                                'link' => '/datamedis/audit'
                            ]) ?>

                        </div>
                    </div>
                    <?= view('components/header/search_bar') ?>

                    <!-- End Header -->

                    <!-- Table -->

                    <table id="myTable" class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <?php 
                            $widths  = [30, 25, 20, 25];
                            echo view('components/tabel/colgroup',['widths' => $widths]);
                            
                            $columns = [
                                'Nama',
                                'Jenis Barang Medis',
                                'Stok',
                                'Aksi'
                            ];
                            echo view('components/tabel/thead',['kolom' => $columns]);
                        ?>    

                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            <?php foreach ($medis_data as $medis) : ?>
                                <div id="hs-vertically-centered-scrollable-modal-<?= $medis['id'] ?>" class="hs-overlay hidden size-full fixed top-0 start-0 z-[80] pointer-events-none">
                                    <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto h-[calc(100%-3.5rem)] min-h-[calc(100%-3.5rem)] flex items-center ">
                                        <div class="overflow-y-auto w-full max-h-full flex flex-col bg-white border shadow-sm rounded-xl pointer-events-auto dark:bg-neutral-800 dark:border-neutral-700 dark:shadow-neutral-700/70">
                                            <div class="flex justify-between items-center py-3 px-4 border-b dark:border-neutral-700">
                                                <h3 class="font-bold text-gray-800 dark:text-white">
                                                    <?= $medis['nama'] ?>
                                                </h3>
                                                <button type="button" class="flex justify-center items-center size-7 text-sm font-semibold rounded-full border border-transparent text-gray-800 hover:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-neutral-700" data-hs-overlay="#hs-vertically-centered-scrollable-modal-<?= $medis['id'] ?>">
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
                                                        <div class="mb-5 sm:block md:flex items-center">
                                                            <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Jenis Barang Medis</label>
                                                            <input type="text" name="" value="<?= $medis['jenis'] ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
                                                        </div>
                                                        <?php foreach ($obat_data as $jenis) {
                                                            if ($medis['id'] === $jenis['id_barang_medis']) {
                                                                if ($medis['jenis'] === 'Obat') { ?>
                                                                    <div class="mb-5 sm:block md:flex items-center">
                                                                        <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Industri Farmasi</label>
                                                                        <input type="text" name="" value="<?php
                                                                                                            $optionsIF = [
                                                                                                                "1000" => "Kalbe Farma"
                                                                                                            ];
                                                                                                            foreach ($optionsIF as $valueIF => $textIF) {
                                                                                                                if ($valueIF === $jenis['industri_farmasi']) {
                                                                                                                    echo $textIF;
                                                                                                                    break; // Stop the loop once a match is found
                                                                                                                }
                                                                                                            }
                                                                                                            ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
                                                                    </div>

                                                                    <div class="mb-5 sm:block md:flex items-center">
                                                                        <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Kandungan</label>
                                                                        <input type="text" name="kandungan" value="<?= $jenis['kandungan'] ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
                                                                    </div>
                                                                    <div class="mb-5 sm:block md:flex items-center">
                                                                        <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Isi</label>
                                                                        <input type="text" name="" value="<?= $jenis['isi'] ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>

                                                                    </div>


                                                                    <div class="mb-5 sm:block md:flex items-center">
                                                                        <label class="block mt-5 md:my-0 text-sm text-gray-900 dark:text-white md:w-1/2">Satuan Besar</label>
                                                                        <input type="text" name="" value="<?php
                                                                                                            foreach ($satuan_data as $satuan) {
                                                                                                                if ($satuan['id'] === $medis['satuan']) {
                                                                                                                    echo $satuan['nama'];
                                                                                                                }
                                                                                                            }
                                                                                                            ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
                                                                    </div>
                                                                    <div class="mb-5 sm:block md:flex items-center">
                                                                        <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Kapasitas</label>
                                                                        <input type="text" name="" value="<?= $jenis['kapasitas'] ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>

                                                                    </div>
                                                                    <div class="mb-5 sm:block md:flex items-center">
                                                                        <label class="block mt-5 md:my-0 text-sm text-gray-900 dark:text-white md:w-1/2">Satuan Kecil</label>
                                                                        <input type="text" name="" value="<?php
                                                                                                            foreach ($satuan_data as $satuan) {
                                                                                                                if ($satuan['id'] === $jenis['satuan']) {
                                                                                                                    echo $satuan['nama'];
                                                                                                                }
                                                                                                            }
                                                                                                            ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
                                                                    </div>
                                                                    <div class="mb-5 sm:block md:flex items-center">
                                                                        <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Jenis</label>
                                                                        <input type="text" name="" value="<?php
                                                                                                            $optionsjenis = [
                                                                                                                "1000" => "Obat Oral",
                                                                                                                "2000" => "Obat Topikal",
                                                                                                                "3000" => "Obat Injeksi",
                                                                                                                "4000" => "Obat Sublingual"
                                                                                                            ];
                                                                                                            foreach ($optionsjenis as $valuejenis => $textjenis) {
                                                                                                                if ($valuejenis === $jenis['jenis']) {
                                                                                                                    echo $textjenis;
                                                                                                                }
                                                                                                            }
                                                                                                            ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
                                                                    </div>
                                                                    <div class="mb-5 sm:block md:flex items-center">
                                                                        <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Kategori</label>
                                                                        <input type="text" name="" value="<?php
                                                                                                            $optionskategori = [
                                                                                                                "1000" => "Obat Paten",
                                                                                                                "2000" => "Obat Generik",
                                                                                                                "3000" => "Obat Merek",
                                                                                                                "4000" => "Obat Eksklusif"
                                                                                                            ];
                                                                                                            foreach ($optionskategori as $valuekategori => $textkategori) {
                                                                                                                if ($valuekategori === $jenis['kategori']) {
                                                                                                                    echo $textkategori;
                                                                                                                    break; // Stop the loop once a match is found
                                                                                                                }
                                                                                                            }
                                                                                                            ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
                                                                    </div>

                                                                    <div class="mb-5 sm:block md:flex items-center">
                                                                        <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Golongan</label>
                                                                        <input type="text" name="" value="<?php
                                                                                                            $optionsgolongan = [
                                                                                                                "1000" => "Analgesik",
                                                                                                                "2000" => "Antibiotik",
                                                                                                                "3000" => "Antijamur",
                                                                                                                "4000" => "Antivirus"
                                                                                                            ];
                                                                                                            foreach ($optionsgolongan as $valuegolongan => $textgolongan) {
                                                                                                                if ($valuegolongan === $jenis['golongan']) {
                                                                                                                    echo $textgolongan;
                                                                                                                    break; // Stop the loop once a match is found
                                                                                                                }
                                                                                                            }
                                                                                                            ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
                                                                    </div>
                                                                    <div class="mb-5 sm:block md:flex items-center">
                                                                        <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Tanggal Kadaluwarsa</label>
                                                                        <input type="date" name="" value="<?= $jenis['kadaluwarsa'] ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
                                                                    </div>
                                                                    <div class="mb-5 sm:block md:flex items-center">
                                                                        <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Notifikasi Kadaluwarsa (hari)</label>
                                                                        <input type="text" name="" value="<?= $medis['notifikasi_kadaluwarsa_hari'] ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
                                                                    </div>

                                                        <?php }
                                                            }
                                                        } ?>
                                                        <?php foreach ($alkes_data as $jenis) {
                                                            if ($medis['id'] === $jenis['id_barang_medis']) {
                                                                if ($medis['jenis'] === 'Alat Kesehatan') { ?>

                                                                    <div class="mb-5 sm:block md:flex items-center">
                                                                        <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Satuan</label>
                                                                        <input type="text" name="" value="<?php
                                                                                                            foreach ($satuan_data as $satuan) {
                                                                                                                if ($satuan['id'] === $medis['satuan']) {
                                                                                                                    echo $satuan['nama'];
                                                                                                                }
                                                                                                            }
                                                                                                            ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
                                                                    </div>

                                                                    <div class="mb-5 sm:block md:flex items-center">
                                                                        <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Merek</label>
                                                                        <input type="text" name="merekalkes" value="<?php
                                                                                                                    $companies = array(
                                                                                                                        'Omron', 'Philips', 'GE Healthcare', 'Siemens Healthineers', 'Medtronic',
                                                                                                                        'Johnson & Johnson', 'Becton', 'Dickinson and Company (BD)', 'Stryker',
                                                                                                                        'Boston Scientific', 'Olympus Corporation', 'Roche Diagnostics'
                                                                                                                    );
                                                                                                                    foreach ($companies as $company) {
                                                                                                                        if ($company === $jenis['merek']) {
                                                                                                                            echo $company;
                                                                                                                        }
                                                                                                                    }
                                                                                                                    ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
                                                                    </div>

                                                        <?php }
                                                            }
                                                        } ?>
                                                        <?php foreach ($bhp_data as $jenis) {
                                                            if ($medis['id'] === $jenis['id_barang_medis']) {
                                                                if ($medis['jenis'] === 'Bahan Habis Pakai') { ?>

                                                                    <div class="mb-5 sm:block md:flex items-center">
                                                                        <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Jumlah</label>
                                                                        <input type="text" name="" value="<?= $jenis['jumlah'] ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
                                                                    </div>
                                                                    <div class="mb-5 sm:block md:flex items-center">
                                                                        <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Satuan</label>
                                                                        <input type="text" name="" value="<?php
                                                                                                            foreach ($satuan_data as $satuan) {
                                                                                                                if ($satuan['id'] === $medis['satuan']) {
                                                                                                                    echo $satuan['nama'];
                                                                                                                }
                                                                                                            }
                                                                                                            ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
                                                                    </div>
                                                                    <?php if ($jenis['kadaluwarsa'] !== '0001-01-01') { ?>
                                                                        <div class="mb-5 sm:block md:flex items-center">
                                                                            <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Kadaluwarsa</label>
                                                                            <input type="text" name="" value="<?php $bhp_date = $jenis['kadaluwarsa'];
                                                                                                                $day = date("d", strtotime($bhp_date));
                                                                                                                $month = date("m", strtotime($bhp_date));
                                                                                                                $year = date("Y", strtotime($bhp_date));

                                                                                                                $bulan = array(
                                                                                                                    1 => "Januari", 2 => "Februari", 3 => "Maret", 4 => "April", 5 => "Mei", 6 => "Juni",
                                                                                                                    7 => "Juli", 8 => "Agustus", 9 => "September", 10 => "Oktober", 11 => "November", 12 => "Desember"
                                                                                                                );

                                                                                                                $bhp_date = $day . ' ' . $bulan[(int)$month] . ' ' . $year;

                                                                                                                echo $bhp_date; ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
                                                                        </div>
                                                                        <div class="mb-5 sm:block md:flex items-center">
                                                                            <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Notifikasi Kadaluwarsa (hari)</label>
                                                                            <input type="text" name="" value="<?= $medis['notifikasi_kadaluwarsa_hari'] ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
                                                                        </div>
                                                                    <?php } else { ?>
                                                                        <input type="hidden" name="kadaluwarsabhp" value="<?= $jenis['kadaluwarsa'] ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white">
                                                                    <?php } ?>

                                                        <?php }
                                                            }
                                                        } ?>
                                                        <?php foreach ($darah_data as $jenis) {
                                                            if ($medis['id'] === $jenis['id_barang_medis']) {
                                                                if ($medis['jenis'] === 'Darah') { ?>

                                                                    <div class="mb-5 sm:block md:flex items-center">
                                                                        <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Satuan</label>
                                                                        <input type="text" name="" value="<?php
                                                                                                            foreach ($satuan_data as $satuan) {
                                                                                                                if ($satuan['id'] === $medis['satuan']) {
                                                                                                                    echo $satuan['nama'];
                                                                                                                }
                                                                                                            }
                                                                                                            ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
                                                                    </div>
                                                                    <div class="mb-5 sm:block md:flex items-center">
                                                                        <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Jenis Darah</label>
                                                                        <input type="text" name="" value="<?= $jenis['jenis'] ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
                                                                    </div>
                                                                    <div class="mb-5 sm:block md:flex items-center">
                                                                        <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Keterangan</label>
                                                                        <input type="text" name="" value="<?= $jenis['keterangan'] ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
                                                                    </div>
                                                                    <div class="mb-5 sm:block md:flex items-center">
                                                                        <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Tanggal Kadaluwarsa</label>
                                                                        <input type="text" name="" value="<?php
                                                                                                            // Tanggal asli dari data
                                                                                                            $original_date = $jenis['kadaluwarsa'];

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
                                                                        <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Notifikasi Kadaluwarsa (hari)</label>
                                                                        <input type="text" name="" value="<?= $medis['notifikasi_kadaluwarsa_hari'] ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
                                                                    </div>

                                                        <?php }
                                                            }
                                                        } ?>
                                                        <div class="mb-5 sm:block md:flex items-center">
                                                            <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Stok</label>
                                                            <input type="text" name="" value="<?= $medis['stok'] ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
                                                        </div>
                                                        <div class="mb-5 sm:block md:flex items-center">
                                                            <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Harga</label>
                                                            <input type="text" name="" value="<?= $medis['harga'] ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
                                                        </div>

                                                        <div class="mb-5 sm:block md:flex items-center">
                                                            <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2">Stok minimum</label>
                                                            <input type="text" name="" value="<?= $medis['stok_minimum'] ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/2 dark:border-gray-600 dark:text-white" readonly>
                                                        </div>

                                                    </div>
                                                </div>
                                                <div class="flex justify-end items-center gap-x-2 py-3 px-4 border-t dark:border-neutral-700">
                                                    <button type="button" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-800" data-hs-overlay="#hs-vertically-centered-scrollable-modal-<?= $medis['id'] ?>">
                                                        Tutup
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <tr>
                                    <?php
                                        $tabel  = $medis;
                                        $row_id = 'id';
                                        $data   = [
                                            'nama'  => 'teks',
                                            'jenis' => 'status',
                                            'stok'  => 'jumlah',
                                        ];
                                        echo view('components/tabel/td', [
                                            'tabel'  => $tabel,
                                            'row_id' => $row_id,
                                            'data'   => $data
                                        ]);
                                    ?>
                                    
                                    <td class="size-px whitespace-nowrap">
                                        <div class="px-3 py-1.5 text-center inline-flex">
                                            <?php
                                                $row_id  = $medis['id'];
                                                $api_url = '/datamedis';
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
                                <button type="button" class="min-h-[38px] min-w-[38px] py-2 px-2.5 inline-flex justify-center items-center gap-x-2 text-sm rounded-lg text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-white/10 dark:focus:bg-white/10" aria-label="Previous page" <?= $meta_data['page'] <= 1 ? 'disabled' : '' ?> onclick="window.location.href='/datamedis?page=<?= $meta_data['page'] - 1 ?>&size=<?= $meta_data['size'] ?>'">
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
                                        echo '<button type="button" class="min-h-[38px] min-w-[38px] flex justify-center items-center ' . ($current_page == $i ? 'bg-gray-200 text-gray-800 dark:bg-neutral-600 dark:focus:bg-neutral-500' : 'text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:text-white dark:hover:bg-white/10 dark:focus:bg-white/10') . ' py-2 px-3 text-sm rounded-lg" ' . ($current_page == $i ? 'aria-current="page"' : '') . ' onclick="window.location.href=\'/datamedis?page=' . $i . '&size=' . $meta_data['size'] . '\'">' . $i . '</button>';
                                    }
                                } else {
                                    if ($current_page > $range + 1) {
                                        echo '<button type="button" class="min-h-[38px] min-w-[38px] flex justify-center items-center text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:text-white dark:hover:bg-white/10 dark:focus:bg-white/10 py-2 px-3 text-sm rounded-lg" onclick="window.location.href=\'/datamedis?page=1&size=' . $meta_data['size'] . '\'">1</button>';
                                        if ($current_page > $range + 2) {
                                            echo '<span class="py-2 px-3 text-sm">...</span>';
                                        }
                                    }

                                    for ($i = max($current_page - $range, 1); $i <= min($current_page + $range, $total_pages); $i++) {
                                        echo '<button type="button" class="min-h-[38px] min-w-[38px] flex justify-center items-center ' . ($current_page == $i ? 'bg-gray-200 text-gray-800 dark:bg-neutral-600 dark:focus:bg-neutral-500' : 'text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:text-white dark:hover:bg-white/10 dark:focus:bg-white/10') . ' py-2 px-3 text-sm rounded-lg" ' . ($current_page == $i ? 'aria-current="page"' : '') . ' onclick="window.location.href=\'/datamedis?page=' . $i . '&size=' . $meta_data['size'] . '\'">' . $i . '</button>';
                                    }

                                    if ($current_page < $total_pages - $range - 1) {
                                        if ($current_page < $total_pages - $range - 2) {
                                            echo '<span class="py-2 px-3 text-sm">...</span>';
                                        }
                                        echo '<button type="button" class="min-h-[38px] min-w-[38px] flex justify-center items-center text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:text-white dark:hover:bg-white/10 dark:focus:bg-white/10 py-2 px-3 text-sm rounded-lg" onclick="window.location.href=\'/datamedis?page=' . $total_pages . '&size=' . $meta_data['size'] . '\'">' . $total_pages . '</button>';
                                    }
                                }
                                ?>
                            </div>

                            <!-- Next Button -->
                            <div class="inline-flex gap-x-2">
                                <button type="button" class="min-h-[38px] min-w-[38px] py-2 px-2.5 inline-flex justify-center items-center gap-x-2 text-sm rounded-lg text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-white/10 dark:focus:bg-white/10" aria-label="Next page" <?= $current_page >= $total_pages ? 'disabled' : '' ?> onclick="window.location.href='/datamedis?page=<?= $current_page + 1 ?>&size=<?= $meta_data['size'] ?>'">
                                    <span aria-hidden="true" class="hidden sm:block">Next</span>
                                    <svg class="flex-shrink-0 size-3.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="m9 18 6-6-6-6"></path>
                                    </svg>
                                </button>
                            </div>
                        </nav>
                    </div>

                </div>
                <!-- End Footer -->
            </div>
        </div>
    </div>
</div>
</div>
<!-- End Card -->

<!-- End Table Section -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var count_notif_stok = <?= $count_notif_stok ?>;
        document.querySelector('#stok-tab svg text').textContent = count_notif_stok;
    });  
</script>
<?= $this->endSection(); ?>