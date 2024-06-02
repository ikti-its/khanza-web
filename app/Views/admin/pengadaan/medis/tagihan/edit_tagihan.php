<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<!-- Card Section -->
<div class="max-w-4xl px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto">
    <!-- Card -->
    <div class="bg-white rounded-xl shadow p-4 sm:p-7 dark:bg-slate-900">
        <div class="mb-8">
            <h2 class="text-xl font-bold text-gray-800 dark:text-gray-200">
                Tambah Tagihan Barang Medis
            </h2>
            <p class="text-sm text-gray-600 dark:text-gray-400">
                Manage your name, password and account settings.
            </p>
        </div>

        <form action="/submitedittagihanmedis/<?= $tagihan_data['id'] ?>" method="post">
            <!-- Grid -->
            <input type="hidden" name="idpengajuan" value="<?= $tagihan_data['id_pengajuan'] ?>" class="text-center border mr-1 w-[20%]">
            <input type="hidden" name="idpemesanan" value="<?= $tagihan_data['id_pemesanan'] ?>" class="text-center border mr-1 w-[20%]">
            <div class="grid sm:grid-cols-12 gap-2 sm:gap-6">
                <div class="sm:col-span-3">
                    <div class="inline-block">
                        <label class="inline-block text-sm text-gray-800 mt-2.5 dark:text-gray-200">
                            Nomor Faktur
                        </label>
                    </div>
                </div>
                <div class="sm:col-span-9">
                    <div class="sm:flex">
                        <select name="idpenerimaan" id="dropdown-id-penerimaan" class="py-2 px-3 pe-9 block w-full sm:w-auto border-gray-200 shadow-sm -mt-px -ms-px first:rounded-t-lg last:rounded-b-lg sm:first:rounded-s-lg sm:mt-0 sm:first:ms-0 sm:first:rounded-se-none sm:last:rounded-es-none sm:last:rounded-e-lg text-sm relative focus:z-10 focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600">
                            <option value="">-</option>

                            <option value="<?= $tagihan_data['id_penerimaan'] ?>" selected><?= $tagihan_data['id_penerimaan'] ?></option>

                        </select>
                    </div>
                </div>
                <div class="sm:col-span-3">
                    <label class="inline-block text-sm text-gray-800 mt-2.5 dark:text-gray-200">
                        Tanggal Bayar
                    </label>
                </div>
                <!-- End Col -->

                <div class="sm:col-span-9">
                    <input name="tglbayar" value="<?= $tagihan_data['tanggal_bayar'] ?>" type="date" class="py-2 px-3 pe-11 block w-full border-gray-200 shadow-sm text-sm rounded-lg focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600" placeholder="Paracetamol">
                </div>

                <div class="sm:col-span-3">
                    <label class="inline-block text-sm text-gray-800 mt-2.5 dark:text-gray-200">
                        Pegawai
                    </label>
                </div>
                <!-- End Col -->

                <div class="sm:col-span-9">
                    <div class="sm:flex">
                        <select name="pegawaitagihan" class="py-2 px-3 pe-9 block w-full sm:w-auto border-gray-200 shadow-sm -mt-px -ms-px first:rounded-t-lg last:rounded-b-lg sm:first:rounded-s-lg sm:mt-0 sm:first:ms-0 sm:first:rounded-se-none sm:last:rounded-es-none sm:last:rounded-e-lg text-sm relative focus:z-10 focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600">
                            <option value="">-</option>

                            <option value="<?= $tagihan_data['id_pegawai'] ?>" selected><?= $tagihan_data['id_pegawai'] ?></option>

                        </select>
                    </div>
                </div>

                <div class="sm:col-span-3">
                    <label class="inline-block text-sm text-gray-800 mt-2.5 dark:text-gray-200">
                        Jumlah Bayar
                    </label>
                </div>
                <div class="sm:col-span-9">
                    <input name="jlhbayar" value="<?= $tagihan_data['jumlah_bayar'] ?>" type="text" class="py-2 px-3 pe-11 block w-full border-gray-200 shadow-sm text-sm rounded-lg focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600" value="">
                </div>
                <!-- End Col -->
                <div class="sm:col-span-3">
                    <label class="inline-block text-sm text-gray-800 mt-2.5 dark:text-gray-200">
                        Nomor Bukti
                    </label>
                </div>
                <!-- End Col -->
                <div class="sm:col-span-9">
                    <input name="nobukti" type="text" value="<?= $tagihan_data['no_bukti'] ?>" class="py-2 px-3 pe-11 block w-full border-gray-200 shadow-sm text-sm rounded-lg focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600" placeholder="0">
                </div>
                <div class="sm:col-span-3">
                    <label class="inline-block text-sm text-gray-800 mt-2.5 dark:text-gray-200">
                        Akun Bayar
                    </label>
                </div>
                <!-- End Col -->
                <div class="sm:col-span-9">
                    <input name="akunbayar" type="text" value="<?= $tagihan_data['id_akun_bayar'] ?>" class="py-2 px-3 pe-11 block w-full border-gray-200 shadow-sm text-sm rounded-lg focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600" placeholder="0">
                </div>



                <div class="sm:col-span-3">
                    <label class="inline-block text-sm text-gray-800 mt-2.5 dark:text-gray-200">
                        Keterangan
                    </label>
                </div>
                <!-- End Col -->
                <div class="sm:col-span-9">
                    <input name="keterangantagihan" type="text" value="<?= $tagihan_data['keterangan'] ?>" class="py-2 px-3 pe-11 block w-full border-gray-200 shadow-sm text-sm rounded-lg focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600" placeholder="0">
                </div>

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
                                                <input type="number" value="<?= $pesanan['jumlah_pesanan'] ?>" class="text-center w-full border" step="any" name="jumlah_pesanan[]" />
                                            </td>
                                            <td class="align-middle p-1">
                                                <input type="text" step="any" value="<?= $pesanan['id_barang_medis'] ?>" class="text-center w-full border" name="" />

                                            </td>
                                            <td class="align-middle p-1">
                                                <input type="text" id="harga" step="any" value="<?= $pesanan['harga_satuan'] ?>" class="text-center w-full border" name="harga_satuan[]" />
                                            </td>
                                            <td class="align-middle p-1 text-right">
                                                <input type="text" class="text-center w-full border" value="" name="total[]" />
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
                                            <input type="number" value="<?= $pengajuan_data['diskon_persen'] ?>" step="any" name="diskonpersen" class="text-center border" style="width: 20%;">
                                        </th>

                                        <th class="p-1 pt-2 text-right">
                                            <input type="text" value="<?= $pengajuan_data['diskon_jumlah'] ?>" class="text-center w-full border border-gray-300 text-center" name="diskonjumlah">
                                        </th>
                                    </tr>

                                    <tr>
                                        <th class="p-1" style="text-align: right;" colspan="3">Tax Inclusive (%)
                                            <input type="number" value="<?= $pengajuan_data['pajak_persen'] ?>" step="any" name="pajakpersen" class=" text-center border" style="width: 20%;">
                                        </th>

                                        <th class="p-1 text-right">
                                            <input type="text" value="<?= $pengajuan_data['pajak_jumlah'] ?>" class="text-center w-full border border-gray-300 text-center" name="pajakjumlah">
                                        </th>
                                    </tr>
                                    <tr>
                                        <th class="p-1" style="text-align: right;" colspan="3">Materai</th>
                                        <th class="p-1 text-right">
                                            <input type="text" value="<?= $pengajuan_data['materai'] ?>" class="text-center w-full border border-gray-300" name="materai">
                                        </th>
                                    </tr>
                                    <tr>
                                        <th class="p-1" style="text-align: right;" colspan="3">Total</th>
                                        <th class="p-1" id="total"><input type="text" class="w-full border border-gray-300 text-center" name="" disabled></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-5 flex justify-end gap-x-2">
                <button type="button" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-white dark:hover:bg-gray-800 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                    Cancel
                </button>
                <button type="submit" value="5" name="statuspesanan" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                    Save changes
                </button>
            </div>
        </form>

    </div>
    <!-- End Card -->

</div>

<!-- End Card Section -->
<?= $this->endSection(); ?>