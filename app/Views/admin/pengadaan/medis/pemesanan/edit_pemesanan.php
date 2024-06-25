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
        </div>

        <form action="/submiteditpemesananmedis/<?= $pemesanan_data['id'] ?>" method="post">
            <!-- Grid -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Nomor Pengajuan</label>
                <select name="idpengajuan" id="dropdown-id-pengajuan" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white">
                    <option value="<?= $pemesanan_data['id_pengajuan'] ?>"><?= $pengajuan_data['nomor_pengajuan'] ?></option>
                </select>
            </div>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Tanggal Pemesanan</label>
                <input type="date" name="tglpemesanan" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" value="<?= $pemesanan_data['tanggal_pesan'] ?>">
            </div>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Nomor Pemesanan</label>
                <input type="text" name="nopemesanan" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" value="<?= $pemesanan_data['no_pemesanan'] ?>">
            </div>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Supplier</label>
                <select name="supplier" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white">
                    <?php
                    foreach ($supplier_data as $supplier) {
                        $option_supplier = [$supplier['id'] => $supplier['nama']];
                        foreach ($option_supplier as $supplier_id => $supplier_nama) {
                            if ($supplier_id === $pemesanan_data['id_supplier']) { // Assuming 'id_supplier' is the field in $pemesanan_data
                                echo '<option value="' . $supplier['id'] . '" selected>' . $supplier['nama'] . '</option>';
                            } else {
                                echo '<option value="' . $supplier['id'] . '">' . $supplier['nama'] . '</option>';
                            }
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Pegawai</label>
                <select name="pegawaipemesanan" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white">
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
                                <thead class="bg-[#DCDCDC]">
                                    <tr class="bg-navy disabled">
                                        <th class="px-1 py-1 text-center">Jumlah</th>
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
                                                <input type="number" value="<?= $pesanan['jumlah_pesanan'] ?>" class="text-center w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#FDFDFD]" step="any" name="jumlah_pesanan[]" />
                                            </td>
                                            <td class="align-middle p-1">
                                                <?php
                                                foreach ($medis_data as $barang_medis) {
                                                    $optionbarang_medis = [$barang_medis['id'] => $barang_medis['nama']];
                                                    foreach ($optionbarang_medis as $barang_medisid => $barang_medisnama) {
                                                        if ($barang_medisid === $pesanan['id_barang_medis']) {
                                                            echo '<input type="text" step="any" value="' . $barang_medisnama . '" class="text-center w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#FDFDFD]" name="" />';
                                                        }
                                                    }
                                                }
                                                ?>
                                            </td>
                                            <td class="align-middle p-1">
                                                <input type="text" step="any" value="<?= $pesanan['satuan'] ?>" class="text-center w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#FDFDFD]" name="" />

                                            </td>
                                            <td class="align-middle p-1">
                                                <input type="text" id="harga" step="any" value="<?= $pesanan['harga_satuan_pemesanan'] ?>" class="text-center w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#FDFDFD]" name="harga_satuan[]" />
                                            </td>
                                            <td class="align-middle p-1 text-right">
                                                <?php
                                                $total = $pesanan['jumlah_pesanan'] * $pesanan['harga_satuan_pemesanan'];
                                                echo '<input type="text" class="text-center w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#FDFDFD]" value="' . $total . '" name="total[]" readonly />';
                                                ?>
                                            </td>

                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot>
                                    <tr class="pt-5">
                                        <input type="hidden" value="" step="any" name="tglpengajuan" class="text-center border mr-1" style="width: 20%;">
                                        <input type="hidden" value="" step="any" name="nopengajuan" class="text-center border mr-1" style="width: 20%;">
                                        <input type="hidden" value="" step="any" name="pegawaipengajuan" class="text-center border mr-1" style="width: 20%;">
                                        <input type="hidden" value="" step="any" name="catatanpengajuan" class="text-center border mr-1" style="width: 20%;">

                                        <th class="p-1 pt-2" style="text-align: right;" colspan="4">
                                            <!-- <button type="button" onclick="addRow()" class="inline-flex items-center text-sm font-semibold rounded-lg border border-transparent text-blue-600 hover:text-blue-800 disabled:opacity-50 disabled:pointer-events-none dark:text-blue-500 dark:hover:text-blue-400">
                                                Add Row
                                            </button> -->
                                            Discount (%)
                                            <input type="number" value="<?= $pemesanan_data['diskon_persen'] ?>" step="any" name="diskonpersenpemesanan" class="text-center border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#FDFDFD]" style="width: 20%;">
                                        </th>

                                        <th class="p-1 pt-2 text-right">
                                            <input type="text" value="<?= $pemesanan_data['diskon_jumlah'] ?>" class="text-center w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#FDFDFD]" name="diskonjumlahpemesanan">
                                        </th>
                                    </tr>

                                    <tr>
                                        <th class="p-1" style="text-align: right;" colspan="4">Tax Inclusive (%)
                                            <input type="number" value="<?= $pemesanan_data['pajak_persen'] ?>" step="any" name="pajakpersenpemesanan" class=" text-center border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#FDFDFD]" style="width: 20%;">
                                        </th>

                                        <th class="p-1 text-right">
                                            <input type="text" value="<?= $pemesanan_data['pajak_jumlah'] ?>" class="text-center w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#FDFDFD]" name="pajakjumlahpemesanan">
                                        </th>
                                    </tr>
                                    <tr>
                                        <th class="p-1" style="text-align: right;" colspan="4">Materai</th>
                                        <th class="p-1 text-right">
                                            <input type="text" value="<?= $pemesanan_data['materai'] ?>" class="text-center w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#FDFDFD]" name="materaipemesanan">
                                        </th>
                                    </tr>
                                    <tr>
                                        <th class="p-1" style="text-align: right;" colspan="4">Total</th>
                                        <th class="p-1" id="total"><input type="text" class="w-full border text-center rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#FDFDFD]" name="" disabled></th>
                                    </tr>
                                </tfoot>
                            </table>

                        </div>
                    </div>

                </div>
                <div class="mt-5 flex justify-end gap-x-2">
                    <button type="button" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-white dark:hover:bg-gray-800 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                        Batal
                    </button>
                    <button type="submit" value="3" name="status" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-[#0A2D27] text-[#ACF2E7] disabled:opacity-50 disabled:pointer-events-none dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                        Simpan
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