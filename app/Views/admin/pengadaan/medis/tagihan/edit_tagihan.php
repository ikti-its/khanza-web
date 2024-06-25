<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<!-- Card Section -->
<div class="max-w-4xl px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto">
    <!-- Card -->
    <div class="bg-white rounded-xl shadow p-4 sm:p-7 dark:bg-slate-900">
        <div class="mb-8">
            <h2 class="text-xl font-bold text-gray-800 dark:text-gray-200">
                Edit Tagihan Barang Medis
            </h2>
           
        </div>

        <form action="/submitedittagihanmedis/<?= $tagihan_data['id'] ?>" method="post">
            <!-- Grid -->
            <input type="hidden" name="idpengajuan" value="<?= $tagihan_data['id_pengajuan'] ?>" class="text-center border mr-1 w-[20%]">
            <input type="hidden" name="idpemesanan" value="<?= $tagihan_data['id_pemesanan'] ?>" class="text-center border mr-1 w-[20%]">
            <input type="hidden" name="idpenerimaan" value="<?= $tagihan_data['id_penerimaan'] ?>" class="text-center border mr-1 w-[20%]">
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Nomor Faktur</label>
                <input type="text" name="" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" value="<?php foreach ($penerimaan_data as $penerimaan) {
                                                                                                                                                                                                                                        if ($penerimaan['id'] === $tagihan_data['id_penerimaan']) {
                                                                                                                                                                                                                                            echo $penerimaan['no_faktur'];
                                                                                                                                                                                                                                            break; // Stop looping once the value is found
                                                                                                                                                                                                                                        }
                                                                                                                                                                                                                                    } ?>">

            </div>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Tanggal Bayar</label>
                <input type="text" name="tglbayar" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" value="<?= $tagihan_data['tanggal_bayar'] ?>">
            </div>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Pegawai</label>
                <select name="pegawaitagihan" id="dropdown-id-penerimaan" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white">
                    <?php
                    foreach ($pegawai_data as $pegawai) {
                        $optionpegawai = [$pegawai['id'] => $pegawai['nama']];
                        foreach ($optionpegawai as $pegawaiid => $pegawainama) {
                            if ($pegawaiid === $tagihan_data['id_pegawai']) {
                                echo '<option value="' . $pegawai['id'] . '" selected>' . $pegawai['nama'] . '</option>';
                            } else {
                                echo '<option value="' . $pegawai['id'] . '">' . $pegawai['nama'] . '</option>';
                            }
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Jumlah Bayar / Total</label>
                <input type="text" name="jlhbayar" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" value="<?= $tagihan_data['jumlah_bayar'] ?>">
                <input type="text" name="totalbayar" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white">
            </div>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Nomor Bukti</label>
                <input type="text" name="nobukti" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" value="<?= $tagihan_data['no_bukti'] ?>">
            </div>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Akun Bayar</label>
                <select name="akunbayar" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white">
                    <?php
                    $options = [
                        "Cash" => "1000",
                        "Transfer lewat Mandiri" => "2000",
                        // Add more options as needed
                    ];

                    foreach ($options as $label => $value) {
                        if ($value === $tagihan_data['id_akun_bayar']) {
                            echo '<option value="' . $value . '" selected>' . $label . '</option>';
                        } else {
                            echo '<option value="' . $value . '">' . $label . '</option>';
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Keterangan</label>
                <input type="text" name="keterangantagihan" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" value="<?= $tagihan_data['keterangan'] ?>">
            </div>


            <!-- End Grid -->
            <div class="mt-5 flex flex-col">
                <div class="-m-1.5 overflow-x-auto">
                    <div class="p-1.5 min-w-full inline-block align-middle">
                        <div class="border rounded-lg overflow-hidden dark:border-neutral-700">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700" id="item-list">
                                <colgroup>
                                    <col width="10%">
                                    <col width="40%">
                                    <col width="25%">
                                    <col width="25%">
                                </colgroup>
                                <thead>
                                    <tr class="bg-navy disabled">
                                        <th class="px-1 py-1 text-center">Qty</th>
                                        <th class="px-1 py-1 text-center">Item</th>
                                        <th class="px-1 py-1 text-center">Price</th>
                                        <th class="px-1 py-1 text-center">Total</th>
                                    </tr>
                                </thead>
                                <tbody class="tabelbodypesanan divide-y divide-gray-200 dark:divide-neutral-700">
                                    <?php foreach ($pesanan_data as $pesanan) : ?>
                                        <tr>
                                            <td class="align-middle p-1 text-center">
                                                <input type="number" value="<?= $pesanan['jumlah_pesanan'] ?>" class="text-center w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#F6F6F6]" step="any" name="jumlah_pesanan[]" />
                                            </td>
                                            <td class="align-middle p-1">
                                                <input type="text" step="any" value="<?= $pesanan['id_barang_medis'] ?>" class="text-center w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#F6F6F6]" name="" />

                                            </td>
                                            <td class="align-middle p-1">
                                                <input type="text" id="harga" step="any" value="<?= $pesanan['harga_satuan_pemesanan'] ?>" class="text-center w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#F6F6F6]" name="harga_satuan[]" />
                                            </td>
                                            <td class="align-middle p-1 text-right">
                                                <input type="text" class="text-center w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#F6F6F6]" value="" name="total[]" />
                                            </td>

                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot>
                                    <tr class="pt-5">
                                        <input type="hidden" value="" step="any" name="tglpengajuan" class="text-center border mr-1 w-[20%]">
                                        <input type="hidden" value="" step="any" name="nopengajuan" class="text-center border mr-1 w-[20%]">
                                        <input type="hidden" value="" step="any" name="supplier" class="text-center border mr-1 w-[20%]">
                                        <input type="hidden" value="" step="any" name="pegawaipengajuan" class="text-center border mr-1 w-[20%]">
                                        <input type="hidden" value="" step="any" name="catatanpengajuan" class="text-center border mr-1 w-[20%]">

                                        <th class="p-1 pt-2 text-right" colspan="3">
                                            Discount (%)
                                            <input type="number" value="<?= $pemesanan_data['diskon_persen'] ?>" step="any" name="diskonpersen" class="text-center border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#F6F6F6]" style="width: 20%;">
                                        </th>

                                        <th class="p-1 pt-2 text-right">
                                            <input type="text" value="<?= $pemesanan_data['diskon_jumlah'] ?>" class="w-full border text-center rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#F6F6F6]" name="diskonjumlah">
                                        </th>
                                    </tr>

                                    <tr>
                                        <th class="p-1" style="text-align: right;" colspan="3">Tax Inclusive (%)
                                            <input type="number" value="<?= $pemesanan_data['pajak_persen'] ?>" step="any" name="pajakpersen" class=" text-center border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#F6F6F6]" style="width: 20%;">
                                        </th>

                                        <th class="p-1 text-right">
                                            <input type="text" value="<?= $pemesanan_data['pajak_jumlah'] ?>" class="w-full border text-center rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#F6F6F6]" name="pajakjumlah">
                                        </th>
                                    </tr>
                                    <tr>
                                        <th class="p-1" style="text-align: right;" colspan="3">Materai</th>
                                        <th class="p-1 text-right">
                                            <input type="text" value="<?= $pemesanan_data['materai'] ?>" class="text-center w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#F6F6F6]" name="materai">
                                        </th>
                                    </tr>
                                    <tr>
                                        <th class="p-1" style="text-align: right;" colspan="3">Total</th>
                                        <th class="p-1" id="total"><input type="text" class="w-full border text-center rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#F6F6F6]" name="" disabled></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-5 flex justify-end gap-x-2">
                <button type="button" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-white dark:hover:bg-gray-800 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                    Batal
                </button>
                <button type="submit" value="5" name="status" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-[#0A2D27] text-[#ACF2E7] disabled:opacity-50 disabled:pointer-events-none dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                    Simpan
                </button>
            </div>
        </form>

    </div>
    <!-- End Card -->

</div>

<!-- End Card Section -->
<?= $this->endSection(); ?>