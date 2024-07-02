<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<!-- Card Section -->
<div class="max-w-4xl px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto">
    <!-- Card -->
    <div class="bg-white rounded-xl shadow p-4 sm:p-7 dark:bg-slate-900">
        <div class="mb-8">
            <h2 class="text-xl font-bold text-gray-800 dark:text-gray-200">
                Tambah Penerimaan Barang Medis
            </h2>

        </div>

        <form action="/submittambahpenerimaanmedis" id="penerimaanform" method="post">
            <!-- Grid -->
            <input type="hidden" value="<?= $pengajuan_data['id'] ?>" name="idpengajuan" class="text-center border mr-1">
            <input type="hidden" value="<?= $pengajuan_data['tanggal_pengajuan'] ?>" name="tglpengajuan" class="text-center border mr-1">
            <input type="hidden" value="<?= $pengajuan_data['nomor_pengajuan'] ?>" name="nopengajuan" class="text-center border mr-1">
            <input type="hidden" value="<?= $pengajuan_data['id_pegawai'] ?>" name="pegawaipengajuan" class="text-center border mr-1">
            <input type="hidden" value="<?= $pengajuan_data['catatan'] ?>" name="catatanpengajuan" class="text-center border mr-1">
            <input type="hidden" value="<?= $pengajuan_data['diskon_persen'] ?>" name="diskonpersen" class="text-center border" readonly>
            <input type="hidden" value="<?= $pengajuan_data['diskon_jumlah'] ?>" name="diskonjumlah" class="w-full border border-gray-300 text-center" readonly>
            <input type="hidden" value="<?= $pengajuan_data['pajak_persen'] ?>" name="pajakpersen" class=" text-center border" readonly>
            <input type="hidden" value="<?= $pengajuan_data['pajak_jumlah'] ?>" name="pajakjumlah" class="w-full border border-gray-300 text-center" readonly>
            <input type="hidden" value="<?= $pengajuan_data['total_pengajuan'] ?>" name="totalpengajuan" class="w-full border border-gray-300 text-center" readonly>
            <input type="hidden" value="<?= $pengajuan_data['materai'] ?>" name="materai" class="w-full border border-gray-300 text-center" readonly>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Nomor Pemesanan</label>
                <input type="hidden" name="idpemesanan" value="<?= $pemesanan_data['id'] ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white">
                <input type="text" name="" value="<?= $pemesanan_data['no_pemesanan'] ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white">

            </div>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Tanggal Penerimaan</label>
                <input type="date" name="tgldatang" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white">
            </div>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Tanggal Faktur</label>
                <input type="date" name="tglfaktur" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white">
            </div>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Tanggal Jatuh Tempo</label>
                <input type="date" name="tgljatuhtempo" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white">
            </div>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Nomor Faktur</label>
                <input type="text" name="nofaktur" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white">
            </div>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Pegawai</label>
                <select name="pegawaipenerimaan" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white">
                    <option value="" selected>-</option>
                    <?php foreach ($pegawai_data as $pegawai) : ?>
                        <option value="<?= $pegawai['id'] ?>"><?= $pegawai['nama'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Ruangan</label>
                <select name="idruangan" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white">
                    <option selected>-</option>
                    <option value="1000">VIP 1</option>
                    <option value="2000">VIP 2</option>
                    <option value="3000">VVIP 1</option>
                    <option value="4000">VVIP 2</option>
                    <option value="5000">Gudang</option>
                </select>
            </div>


            <!-- End Grid -->
            <div class="mt-5 flex flex-col">
                <div class="-m-1.5 overflow-x-auto">
                    <div class="p-1.5 min-w-full inline-block align-middle">
                        <div class="border rounded-lg overflow-hidden dark:border-neutral-700">
                            <table class="pt-5 min-w-full divide-y divide-gray-200 dark:divide-neutral-700" id="item-list">
                                <colgroup>

                                    <col width="10%">
                                    <!-- 38% -->
                                    <col width="25%">
                                    <col width="15%">
                                    <col width="18%">
                                    <col width="18%">
                                    <col width="18%">

                                </colgroup>
                                <thead class="bg-[#DCDCDC]">
                                    <tr class="bg-navy disabled">

                                        <th class="px-1 py-1 text-center">Jumlah</th>
                                        <th class="px-1 py-1">Barang</th>
                                        <th class="px-1 py-1 text-center">Satuan</th>
                                        <th class="px-1 py-1 text-center">Jumlah Diterima</th>
                                        <th class="px-1 py-1 text-center">Kadaluwarsa</th>
                                        <th class="px-1 py-1 text-center">No Batch</th>
                                    </tr>
                                </thead>
                                <tbody class="tabelbodypesanan divide-y divide-gray-200 dark:divide-neutral-700">
                                    <?php foreach ($pesanan_data as $pesanan) { ?>
                                        <tr>
                                            <input type="hidden" value="<?= $pesanan['id'] ?>" class="text-center w-full" step="any" name="idpesanan[]" readonly />
                                            <input type="hidden" value="<?= $pesanan['harga_satuan_pengajuan'] ?>" class="text-center w-full" step="any" name="harga_satuan_pengajuan[]" readonly />
                                            <input type="hidden" value="<?= $pesanan['harga_satuan_pemesanan'] ?>" class="text-center w-full" step="any" name="harga_satuan_pemesanan[]" readonly />
                                            <input type="hidden" value="<?= $pesanan['total_per_item'] ?>" class="text-center w-full" step="any" name="totalperitem[]" readonly />

                                            <td class="align-middle p-1 text-center">
                                                <input type="text" value="<?= $pesanan['jumlah_pesanan'] ?>" class="text-center w-full" step="any" name="jumlah_pesanan[]" readonly />
                                            </td>
                                            <td class="align-middle p-1">
                                                <input type="hidden" step="any" value="<?= $pesanan['id_barang_medis'] ?>" class="text-center w-full" name="idbrgmedis[]" readonly />
                                                <input type="text" step="any" value="<?php foreach ($medis_data as $medis) {
                                                                                            if ($medis['id'] === $pesanan['id_barang_medis']) {
                                                                                                echo $medis['nama'];
                                                                                            }
                                                                                        } ?>" class="text-center w-full" name="" readonly />

                                            </td>
                                            <td class="align-middle p-1">
                                                <input type="hidden" step="any" value="<?= $pesanan['satuan'] ?>" class="text-center w-full" name="satuan[]" readonly />
                                                <input type="text" step="any" value="<?php foreach ($satuan_data as $satuan) {
                                                                                            if ($satuan['id'] === $pesanan['satuan']) {
                                                                                                echo $satuan['nama'];
                                                                                            }
                                                                                        } ?>" class="text-center w-full" name="" readonly />

                                            </td>

                                            <td class="align-middle p-1 text-center">
                                                <input type="text" class="text-center w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#FDFDFD]" name="jumlah_diterima[]" />
                                            </td>
                                            <td class="align-middle p-1 text-center">
                                                <input type="date" class="text-center w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#FDFDFD]" name="kadaluwarsa[]" />
                                            </td>
                                            <td class="align-middle p-1 text-center">
                                                <input type="text" class="text-center w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#FDFDFD]" name="no_batch[]" />
                                            </td>

                                        </tr>
                                    <?php } ?>
                                </tbody>



                            </table>

                        </div>

                    </div>
                </div>

            </div>
            <div class="mt-5 flex justify-end gap-x-2">
                <button type="button" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-white dark:hover:bg-gray-800 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                    Batal
                </button>
                <button type="submit" name="statuspesanan" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-[#0A2D27] text-[#ACF2E7] disabled:opacity-50 disabled:pointer-events-none dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                    Simpan
                </button>
            </div>
        </form>

    </div>
    <!-- End Card -->

</div>

<!-- End Card Section -->
<script>
    document.getElementById('penerimaanform').addEventListener('submit', function(event) {
        const jumlahPesananInputs = document.querySelectorAll('.tabelbodypesanan input[name="jumlah_pesanan[]"]');
        const jumlahDiterimaInputs = document.querySelectorAll('.tabelbodypesanan input[name="jumlah_diterima[]"]');

        // Check if both arrays have the same length
        // if (jumlahPesananInputs.length !== jumlahDiterimaInputs.length) {
        //     console.error("Number of 'jumlah_pesanan[]' inputs doesn't match number of 'jumlah_diterima[]' inputs.");
        //     return; // Exit the function
        // }

        let allMatch = true;

        // Loop through each 'jumlah_diterima[]' input
        jumlahDiterimaInputs.forEach((input, index) => {
            // Compare the value with corresponding 'jumlah_pesanan[]' input
            if (input.value !== jumlahPesananInputs[index].value) {
                allMatch = false;
                return; // Exit the loop early if a mismatch is found
            }
        });

        const submitButton = document.querySelector('button[name="statuspesanan"]');
        submitButton.value = allMatch ? '5' : '4';
    });
</script>
<?= $this->endSection(); ?>