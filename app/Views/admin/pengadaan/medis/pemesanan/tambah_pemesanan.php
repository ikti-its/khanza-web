<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<!-- Card Section -->
<div class="max-w-4xl px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto">
    <!-- Card -->
    <div class="bg-white rounded-xl shadow p-4 sm:p-7 dark:bg-slate-900">
        <div class="mb-8">
            <h2 class="text-xl font-bold text-gray-800 dark:text-gray-200">
                Tambah Pemesanan Barang Medis
            </h2>

        </div>

        <form action="/submittambahpemesananmedis" method="post">
            <!-- Grid -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Nomor Pengajuan</label>
                <select name="idpengajuan" id="dropdown-id-pengajuan" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white">
                    <option value="">-</option>
                    <?php
                    foreach ($pengajuan_data as $pengajuan) :
                        if ($pengajuan['status_pesanan'] === '2') { ?>
                            <option value="<?= $pengajuan['id'] ?>"><?= $pengajuan['nomor_pengajuan'] ?></option>
                    <?php }
                    endforeach;
                    ?>
                </select>
            </div>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Tanggal Pemesanan</label>
                <input type="date" name="tglpemesanan" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white">
            </div>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Nomor Pemesanan</label>
                <input type="text" name="nopemesanan" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white">
            </div>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Supplier</label>
                <select name="supplier" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white">
                    <option value="" selected>-</option>
                    <?php foreach ($supplier_data as $supplier) : ?>
                        <option value="<?= $supplier['id'] ?>"><?= $supplier['nama'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Pegawai</label>
                <select name="pegawaipemesanan" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white">
                    <option value="" selected>-</option>
                    <?php foreach ($pegawai_data as $pegawai) : ?>
                        <option value="<?= $pegawai['id'] ?>"><?= $pegawai['nama'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- End Grid -->
            <div class="mt-5 flex flex-col">
                <div class="-m-1.5 overflow-x-auto">
                    <div class="p-1.5 min-w-full inline-block align-middle">
                        <div class="border-t-[1px] border-x-0 border-b-0 overflow-hidden dark:border-neutral-700">
                            <div class="flex justify-between p-2 text-sm text-gray-600 dark:text-neutral-500">
                                <div class="inline-flex items-center text-[1.25rem] font-[400] leading-[normal] tracking-[0.00625rem]">
                                    Pesanan
                                </div>
                                <div>
                                    <button type="button" onclick="addRow()" class="inline-flex items-center justify-center text-sm font-semibold tracking-[0.00625rem] rounded-lg border border-transparent w-[140px] h-[36px] bg-[#0A2D27] text-[#ACF2E7] disabled:opacity-50 disabled:pointer-events-none dark:text-blue-500 dark:hover:text-blue-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                            <path d="M15 10.625H5C4.65833 10.625 4.375 10.3417 4.375 10C4.375 9.65833 4.65833 9.375 5 9.375H15C15.3417 9.375 15.625 9.65833 15.625 10C15.625 10.3417 15.3417 10.625 15 10.625Z" fill="#ACF2E7" />
                                            <path d="M10 15.625C9.65833 15.625 9.375 15.3417 9.375 15V5C9.375 4.65833 9.65833 4.375 10 4.375C10.3417 4.375 10.625 4.65833 10.625 5V15C10.625 15.3417 10.3417 15.625 10 15.625Z" fill="#ACF2E7" />
                                        </svg>
                                        Tambah Baris
                                    </button>
                                </div>
                            </div>
                            <div class="border rounded-lg">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700" id="item-list">
                                    <colgroup>
                                        <col width="6%">
                                        <col width="7%">
                                        <col width="15%">
                                        <col width="12%">
                                        <col width="12%">
                                        <col width="12%">
                                        <col width="8%">
                                        <col width="14%">
                                        <col width="14%">
                                    </colgroup>
                                    <thead class="border-b bg-[#DCDCDC]">
                                        <tr class="bg-navy disabled">
                                            <th class="px-1 py-1 text-center"></th>
                                            <th class="px-1 py-1 text-center">Jumlah</th>
                                            <th class="px-1 py-1 text-center">Barang</th>
                                            <th class="px-1 py-1 text-center">Satuan</th>
                                            <th class="px-1 py-1 text-center">Harga Pemesanan</th>
                                            <th class="px-1 py-1 text-center">Subtotal</th>
                                            <th class="px-1 py-1 text-center">Diskon (%)</th>
                                            <th class="px-1 py-1 text-center">Diskon (Jumlah)</th>
                                            <th class="px-1 py-1 text-center">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody class="tabelbodypesanan divide-y divide-gray-200 dark:divide-neutral-700">

                                        <tr>
                                            <td>
                                                <button type="button" class="flex justify-center p-2" onclick="removeRow(this)">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                                        <path d="M16 0H4C1.79086 0 0 1.79086 0 4V16C0 18.2091 1.79086 20 4 20H16C18.2091 20 20 18.2091 20 16V4C20 1.79086 18.2091 0 16 0Z" fill="#0A2D27" />
                                                        <path d="M15 10.625H5C4.65833 10.625 4.375 10.3417 4.375 10C4.375 9.65833 4.65833 9.375 5 9.375H15C15.3417 9.375 15.625 9.65833 15.625 10C15.625 10.3417 15.3417 10.625 15 10.625Z" fill="#ACF2E7" />
                                                    </svg>
                                                </button>
                                            </td>
                                            <td class="align-middle p-1 text-center">
                                                <input type="number" min="0" value="" class="text-center w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#F6F6F6]" name="jumlah_pesanan[]" readonly />
                                            </td>
                                            <td class="align-middle p-1">


                                                <input type="text" value="" class="text-center w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#F6F6F6] cursor-default" name="" readonly />
                                            </td>
                                            <td class="align-middle p-1">
                                                <select name="satuanbrgmedis[]" class="py-[1.5px] w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#FDFDFD] text-center" required>

                                                </select>
                                            </td>
                                            <td class="align-middle p-1">
                                                <input type="number" min="0" value="" class="text-center w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#FDFDFD]" name="harga_satuan_pemesanan[]" required />
                                            </td>
                                            <td class="align-middle p-1">
                                                <input type="number" min="0" value="" class="text-center w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#F6F6F6]" name="subtotalperitem[]" readonly required />
                                            </td>
                                            <td class="align-middle p-1">
                                                <input type="number" min="0" max="100" value="" class="text-center w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#FDFDFD]" name="diskonpersenperitem[]" required />
                                            </td>
                                            <td class="align-middle p-1">
                                                <input type="number" min="0" value="" class="text-center w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#F6F6F6]" name="diskonjumlahperitem[]" readonly required />
                                            </td>
                                            <td class="align-middle p-1 text-right">
                                                <input type="number" min="0" value="" class="text-center w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#F6F6F6] cursor-default" name="totalperitem[]" readonly required>
                                            </td>
                                        </tr>

                                    </tbody>

                                    <tfoot>
                                        <tr>



                                        </tr>
                                        <tr>
                                            <th class="p-1 text-right" colspan="7">Total (Sebelum Pajak)</th>
                                            <th class="p-1 text-right">
                                                <input type="number" min="0" value="" step="any" name="totalsblmpajak" class=" text-center border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#F6F6F6]" required>
                                            </th>
                                        </tr>
                                        <tr>
                                            <th class="p-1 text-right" colspan="7">Pajak (%)
                                                <input type="number" min="0" max="100" value="0" step="any" name="pajakpersenpemesanan" class=" text-center border w-[15%] rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#FDFDFD]" required>
                                            </th>
                                            <th class="p-1 text-right">

                                                <input type="number" min="0" value="0" class="text-center w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#F6F6F6] cursor-default" name="pajakjumlahpemesanan" readonly required>
                                            </th>
                                        </tr>
                                        <tr>
                                            <th class="p-1 text-right" colspan="7">Materai</th>
                                            <th class="p-1 text-right">
                                                <input type="number" min="0" value="0" class="text-center w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#FDFDFD]" name="materaipemesanan" required>
                                            </th>
                                        </tr>
                                        <tr>
                                            <th class="p-1 text-right" colspan="7">Total</th>
                                            <th class="p-1" id="total">
                                                <input type="hidden" value="" class="w-full border text-center rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#F6F6F6] cursor-default" name="totalpengajuan" readonly>
                                                <input type="number" min="0" value="" class="w-full border text-center rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#F6F6F6] cursor-default" name="totalpemesanan" readonly required>
                                            </th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-5 flex justify-end gap-x-2">
                <a href="javascript:history.back()" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-white dark:hover:bg-gray-800 ">
                    Kembali
                </a>
                <button type="submit" id="submitButton" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-[#0A2D27] text-[#ACF2E7] disabled:opacity-50 disabled:pointer-events-none ">
                    Simpan
                </button>
            </div>
        </form>
    </div>
    <!-- End Card -->
</div>

<!-- End Card Section -->
<script>
    function removeRow(btn) {
        var row = btn.parentNode.parentNode;
        row.parentNode.removeChild(row);
    }
</script>
<?= $this->endSection(); ?>