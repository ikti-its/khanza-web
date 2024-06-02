<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<!-- Card Section -->
<div class="max-w-4xl px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto">
    <!-- Card -->
    <div class="bg-white rounded-xl shadow p-4 sm:p-7 dark:bg-slate-900">
        <div class="mb-8">
            <h2 class="text-xl font-bold text-gray-800 dark:text-gray-200">
                Edit Pemesanan Barang Medis
            </h2>
            <p class="text-sm text-gray-600 dark:text-gray-400">
                Manage your name, password and account settings.
            </p>
        </div>

        <form action="/submiteditpemesananmedis/<?= $pemesanan_data['id'] ?>" method="post">
            <!-- Grid -->
            <div class="grid sm:grid-cols-12 gap-2 sm:gap-6">
                <div class="sm:col-span-3">
                    <label class="inline-block text-sm text-gray-800 mt-2.5 dark:text-gray-200">
                        Tanggal Pemesanan
                    </label>
                </div>
                <!-- End Col -->

                <div class="sm:col-span-9">
                    <input name="tglpemesanan" type="date" value="<?= $pemesanan_data['tanggal_pesan'] ?>" class="py-2 px-3 pe-11 block w-full border-gray-200 shadow-sm text-sm rounded-lg focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600" placeholder="Paracetamol">
                </div>
                <div class="sm:col-span-3">
                    <label class="inline-block text-sm text-gray-800 mt-2.5 dark:text-gray-200">
                        Nomor Pemesanan
                    </label>
                </div>
                <!-- End Col -->

                <div class="sm:col-span-9">
                    <input name="nopemesanan" type="text" value="<?= $pemesanan_data['no_pemesanan'] ?>" class="py-2 px-3 pe-11 block w-full border-gray-200 shadow-sm text-sm rounded-lg focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600" value="">
                </div>
                <!-- End Col -->

                <div class="sm:col-span-3">
                    <div class="inline-block">
                        <label class="inline-block text-sm text-gray-800 mt-2.5 dark:text-gray-200">
                            Nomor Pengajuan
                        </label>
                    </div>
                </div>
                <div class="sm:col-span-9">
                    <div class="sm:flex">
                        <select name="idpengajuan" class="py-2 px-3 pe-9 block w-full sm:w-auto border-gray-200 shadow-sm -mt-px -ms-px first:rounded-t-lg last:rounded-b-lg sm:first:rounded-s-lg sm:mt-0 sm:first:ms-0 sm:first:rounded-se-none sm:last:rounded-es-none sm:last:rounded-e-lg text-sm relative focus:z-10 focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600">
                            <option value="<?= $pemesanan_data['id_pengajuan'] ?>"><?= $pengajuan_data['nomor_pengajuan'] ?></option>
                        </select>
                    </div>
                </div>
                <!-- End Col -->

                <div class="sm:col-span-3">
                    <label class="inline-block text-sm text-gray-800 mt-2.5 dark:text-gray-200">
                        Pegawai
                    </label>
                </div>
                <!-- End Col -->

                <div class="sm:col-span-9">
                    <div class="sm:flex">
                        <select name="pegawaipemesanan" class="py-2 px-3 pe-9 block w-full sm:w-auto border-gray-200 shadow-sm -mt-px -ms-px first:rounded-t-lg last:rounded-b-lg sm:first:rounded-s-lg sm:mt-0 sm:first:ms-0 sm:first:rounded-se-none sm:last:rounded-es-none sm:last:rounded-e-lg text-sm relative focus:z-10 focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600">
                            <?php
                            foreach ($pegawai_data as $pegawai) {
                                $optionpegawai = [$pegawai['id'] => $pegawai['nama']];
                                foreach ($optionpegawai as $pegawaiid => $pegawainama) {
                                    if ($pegawaiid === $pemesanan_data['id_pegawai']) {
                                        echo '<option value="' . $pegawai['id'] . '" selected>' . $pegawai['nama'] . '</option>';
                                    } else {
                                        echo '<option value="' . $pegawai['id'] . '">' . $pegawai['nama'] . '</option>';
                                    }
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>

            </div>
            <!-- End Grid -->
            <div class="mt-5 flex flex-col">
                <div class="-m-1.5 overflow-x-auto">
                    <div class="p-1.5 min-w-full inline-block align-middle">
                        <div class="border rounded-lg overflow-hidden dark:border-neutral-700">
                            <table class="min-w-full divide-gray-200 dark:divide-neutral-700" id="item-list">
                                <colgroup>
                                    <col width="8%">
                                    <col width="30%">
                                    <col width="18%">
                                    <col width="22%">
                                    <col width="22%">
                                </colgroup>
                                <thead>
                                    <tr class="bg-navy disabled">
                                        <th class="px-1 py-1 text-center">Qty</th>
                                        <th class="px-1 py-1 text-center">Barang</th>
                                        <th class="px-1 py-1 text-center">Satuan</th>
                                        <th class="px-1 py-1 text-center">Harga</th>
                                        <th class="px-1 py-1 text-center">Total</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-neutral-700">
                                    <?php foreach ($pesanan_data as $pesanan) : ?>
                                        <tr>
                                            <td class="align-middle p-1 text-center">
                                                <input type="number" value="<?= $pesanan['jumlah_pesanan'] ?>" class="text-center w-full border" step="any" name="jumlah_pesanan[]" />
                                            </td>
                                            <td class="align-middle p-1">
                                                <?php
                                                foreach ($medis_data as $barang_medis) {
                                                    $optionbarang_medis = [$barang_medis['id'] => $barang_medis['nama']];
                                                    foreach ($optionbarang_medis as $barang_medisid => $barang_medisnama) {
                                                        if ($barang_medisid === $pesanan['id_barang_medis']) {
                                                            echo '<input type="text" step="any" value="' . $barang_medisnama . '" class="text-center w-full border" name="" />';
                                                        }
                                                    }
                                                }
                                                ?>
                                            </td>
                                            <td class="align-middle p-1">
                                                <input type="text" step="any" value="<?= $pesanan['satuan'] ?>" class="text-center w-full border" name="" />

                                            </td>
                                            <td class="align-middle p-1">
                                                <input type="text" id="harga" step="any" value="<?= $pesanan['harga_satuan'] ?>" class="text-center w-full border" name="harga_satuan[]" />
                                            </td>
                                            <td class="align-middle p-1 text-right">
                                                <?php
                                                $total = $pesanan['jumlah_pesanan'] * $pesanan['harga_satuan'];
                                                echo '<input type="text" class="text-center w-full border" value="' . $total . '" name="total[]" readonly />';
                                                ?>
                                            </td>

                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot>
                                    <tr class="pt-5">
                                        <input type="hidden" value="" step="any" name="tglpengajuan" class="text-center border mr-1" style="width: 20%;">
                                        <input type="hidden" value="" step="any" name="nopengajuan" class="text-center border mr-1" style="width: 20%;">
                                        <input type="hidden" value="" step="any" name="supplier" class="text-center border mr-1" style="width: 20%;">
                                        <input type="hidden" value="" step="any" name="pegawaipengajuan" class="text-center border mr-1" style="width: 20%;">
                                        <input type="hidden" value="" step="any" name="catatanpengajuan" class="text-center border mr-1" style="width: 20%;">

                                        <th class="p-1 pt-2" style="text-align: right;" colspan="4">
                                            <!-- <button type="button" onclick="addRow()" class="inline-flex items-center text-sm font-semibold rounded-lg border border-transparent text-blue-600 hover:text-blue-800 disabled:opacity-50 disabled:pointer-events-none dark:text-blue-500 dark:hover:text-blue-400">
                                                Add Row
                                            </button> -->
                                            Discount (%)
                                            <input type="number" value="<?= $pengajuan_data['diskon_persen'] ?>" step="any" name="diskonpersen" class="text-center border" style="width: 20%;">
                                        </th>

                                        <th class="p-1 pt-2 text-right">
                                            <input type="text" value="<?= $pengajuan_data['diskon_jumlah'] ?>" class="text-center w-full border border-gray-300" name="diskonjumlah">
                                        </th>
                                    </tr>

                                    <tr>
                                        <th class="p-1" style="text-align: right;" colspan="4">Tax Inclusive (%)
                                            <input type="number" value="<?= $pengajuan_data['pajak_persen'] ?>" step="any" name="pajakpersen" class=" text-center border" style="width: 20%;">
                                        </th>

                                        <th class="p-1 text-right">
                                            <input type="text" value="<?= $pengajuan_data['pajak_jumlah'] ?>" class="text-center w-full border border-gray-300" name="pajakjumlah">
                                        </th>
                                    </tr>
                                    <tr>
                                        <th class="p-1" style="text-align: right;" colspan="4">Materai</th>
                                        <th class="p-1 text-right">
                                            <input type="text" value="<?= $pengajuan_data['materai'] ?>" class="text-center w-full border border-gray-300" name="materai">
                                        </th>
                                    </tr>
                                    <tr>
                                        <th class="p-1" style="text-align: right;" colspan="4">Total</th>
                                        <th class="p-1" id="total"><input type="text" class="w-full border border-gray-300 text-center" name="" disabled></th>
                                    </tr>
                                </tfoot>
                            </table>

                            <center </div>
                        </div>

                    </div>
                    <div class="mt-5 flex justify-end gap-x-2">
                        <button type="button" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-white dark:hover:bg-gray-800 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                            Cancel
                        </button>
                        <button type="submit" value="3" name="statuspesanan" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                            Save changes
                        </button>
                    </div>
        </form>

    </div>
    <!-- End Card -->

</div>

<!-- End Card Section -->
<script>

</script>
<?= $this->endSection(); ?>