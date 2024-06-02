<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<!-- Card Section -->
<div class="max-w-4xl px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto">
    <!-- Card -->
    <div class="bg-white rounded-xl shadow p-4 sm:p-7 dark:bg-slate-900">
        <div class="mb-8">
            <h2 class="text-xl font-bold text-gray-800 dark:text-gray-200">
                Edit Pengajuan Barang Medis
            </h2>
            <p class="text-sm text-gray-600 dark:text-gray-400">
                Manage your name, password and account settings.
            </p>
        </div>

        <form action="/submiteditpengajuanmedis/<?= $pengajuanId ?>" method="post">
            <!-- Grid -->
            <div class="grid sm:grid-cols-12 gap-2 sm:gap-6">
                <div class="sm:col-span-3">
                    <label class="inline-block text-sm text-gray-800 mt-2.5 dark:text-gray-200">
                        Tanggal Pengajuan
                    </label>
                </div>
                <!-- End Col -->

                <div class="sm:col-span-9">
                    <input name="tglpengajuan" value="<?= $pengajuan_data['tanggal_pengajuan'] ?>" type="date" class="py-2 px-3 pe-11 block w-full border-gray-200 shadow-sm text-sm rounded-lg focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600">
                </div>
                <div class="sm:col-span-3">
                    <label class="inline-block text-sm text-gray-800 mt-2.5 dark:text-gray-200">
                        Nomor Pengajuan
                    </label>
                </div>
                <!-- End Col -->

                <div class="sm:col-span-9">
                    <input name="nopengajuan" value="<?= $pengajuan_data['nomor_pengajuan'] ?>" type="text" class="py-2 px-3 pe-11 block w-full border-gray-200 shadow-sm text-sm rounded-lg focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600">
                </div>
                <!-- End Col -->

                <div class="sm:col-span-3">
                    <div class="inline-block">
                        <label class="inline-block text-sm text-gray-800 mt-2.5 dark:text-gray-200">
                            Supplier
                        </label>
                    </div>
                </div>
                <div class="sm:col-span-9">
                    <div class="sm:flex">
                        <select name="supplier" class="py-2 px-3 pe-9 block w-full sm:w-auto border-gray-200 shadow-sm -mt-px -ms-px first:rounded-t-lg last:rounded-b-lg sm:first:rounded-s-lg sm:mt-0 sm:first:ms-0 sm:first:rounded-se-none sm:last:rounded-es-none sm:last:rounded-e-lg text-sm relative focus:z-10 focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600">
                            <option value="<?= $pengajuan_data['id_supplier'] ?>" selected><?= $pengajuan_data['id_supplier'] ?></option>
                            <option value="1">Obat</option>
                            <option value="Alat Kesehatan">Alat Kesehatan</option>
                            <option value="Bahan Habis Pakai">Bahan Habis Pakai</option>
                            <option value="Darah">Darah</option>
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
                        <select name="pegawai" class="py-2 px-3 pe-9 block w-full sm:w-auto border-gray-200 shadow-sm -mt-px -ms-px first:rounded-t-lg last:rounded-b-lg sm:first:rounded-s-lg sm:mt-0 sm:first:ms-0 sm:first:rounded-se-none sm:last:rounded-es-none sm:last:rounded-e-lg text-sm relative focus:z-10 focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600">
                            <?php
                            foreach ($pegawai_data as $pegawai) {
                                $optionpegawai = [$pegawai['id'] => $pegawai['nama']];
                                foreach ($optionpegawai as $pegawaiid => $pegawainama) {
                                    if ($pegawaiid === $pengajuan_data['id_pegawai']) {
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

                <div class="sm:col-span-3">
                    <label class="inline-block text-sm text-gray-800 mt-2.5 dark:text-gray-200">
                        Catatan
                    </label>
                </div>
                <!-- End Col -->
                <div class="sm:col-span-9">
                    <input name="catatan" value="<?= $pengajuan_data['catatan'] ?>" type="text" class="py-2 px-3 pe-9 block w-full border-gray-200 shadow-sm text-sm rounded-lg focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600">
                </div>

            </div>
            <!-- End Grid -->
            <div class="mt-5 flex flex-col">
                <div class="-m-1.5 overflow-x-auto">
                    <div class="p-1.5 min-w-full inline-block align-middle">
                        <div class="border rounded-lg overflow-hidden dark:border-neutral-700">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700" id="item-list">
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
                                            <input type="hidden" value="<?= $pesanan['id'] ?>" class="text-center w-full border" name="idpesanan[]" />
                                            <input type="hidden" value="<?= $pesanan['kadaluwarsa'] ?>" class="text-center w-full border" name="kadaluwarsa[]" />

                                            <td class="align-middle p-1 text-center">
                                                <input type="number" value="<?= $pesanan['jumlah_pesanan'] ?>" class="text-center w-full border" step="any" name="jumlah_pesanan[]" />
                                            </td>
                                            <td class="align-middle p-1">
                                                <select name="idbrgmedis[]" class="w-full border text-center" onchange="updateHarga(this)">
                                                    <?php
                                                    foreach ($medis_data as $barang_medis) {
                                                        $optionbarang_medis = [$barang_medis['id'] => $barang_medis['nama']];
                                                        foreach ($optionbarang_medis as $barang_medisid => $barang_medisnama) {
                                                            if ($barang_medisid === $pesanan['id_barang_medis']) {
                                                                echo '<option value="' . $barang_medis['id'] . '" selected>' . $barang_medis['nama'] . '</option>';
                                                            } else {
                                                                echo '<option value="' . $barang_medis['id'] . '">' . $barang_medis['nama'] . '</option>';
                                                            }
                                                        }
                                                    }
                                                    ?>

                                                </select>
                                            </td>
                                            <td class="align-middle p-1">
                                                <select name="satuanbrgmedis[]" class="w-full border text-center">
                                                    <?php
                                                    foreach ($satuan_data as $satuan) {
                                                        $optionsatuan = [$satuan['id'] => $satuan['nama']];
                                                        foreach ($optionsatuan as $satuanid => $satuannama) {
                                                            if ($satuanid === $pesanan['satuan']) {
                                                                echo '<option value="' . $satuan['id'] . '" selected>' . $satuan['nama'] . '</option>';
                                                            } else {
                                                                echo '<option value="' . $satuan['id'] . '">' . $satuan['nama'] . '</option>';
                                                            }
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </td>
                                            <td class="align-middle p-1">
                                                <input type="text" id="harga" value="<?= $pesanan['harga_satuan'] ?>" step="any" class="text-center w-full border" name="harga_satuan[]" />
                                            </td>
                                            <td class="align-middle p-1 text-right">
                                                <input type="text" class="text-center w-full border" value="" name="total[]" />
                                            </td>

                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot>
                                    <tr class="pt-5">
                                        <th class="p-1 pt-2" style="text-align: right;" colspan="4">
                                            <!-- <button type="button" onclick="addRow()" class="inline-flex items-center text-sm font-semibold rounded-lg border border-transparent text-blue-600 hover:text-blue-800 disabled:opacity-50 disabled:pointer-events-none dark:text-blue-500 dark:hover:text-blue-400">
                                                Add Row
                                            </button> -->
                                            Discount (%)
                                            <input type="number" value="<?= $pengajuan_data['diskon_persen'] ?>" step="any" name="diskonpersen" class="text-center border" style="width: 20%;">
                                        </th>

                                        <th class="p-1 pt-2 text-center">
                                            <input type="text" value="<?= $pengajuan_data['diskon_jumlah'] ?>" class="text-center w-full border border-gray-300" name="diskonjumlah">
                                        </th>
                                    </tr>

                                    <tr>
                                        <th class="p-1" style="text-align: right;" colspan="4">Tax Inclusive (%)
                                            <input type="number" value="<?= $pengajuan_data['pajak_persen'] ?>" step="any" name="pajakpersen" class=" text-center border" style="width: 20%;">
                                        </th>

                                        <th class="p-1 text-center">
                                            <input type="text" value="<?= $pengajuan_data['pajak_jumlah'] ?>" class="text-center w-full border border-gray-300" name="pajakjumlah">
                                        </th>
                                    </tr>
                                    <tr>
                                        <th class="p-1 text-right" colspan="4">Materai</th>
                                        <th class="p-1 text-center">
                                            <input type="text" value="<?= $pengajuan_data['materai'] ?>" class="text-center w-full border border-gray-300" name="materai">
                                        </th>
                                    </tr>
                                    <tr>
                                        <th class="p-1 text-right" colspan="4">Total</th>
                                        <th class="p-1" id="total"><input type="text" class="w-full border border-gray-300" name="" disabled></th>
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
                <button type="submit" value="0" name="status" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                    Save changes
                </button>
            </div>
        </form>

    </div>
    <!-- End Card -->

</div>

<!-- End Card Section -->
<script>
    var jumlahPesananInputs = document.querySelectorAll('input[name="jumlah_pesanan[]"]');
    var hargaSatuanInputs = document.querySelectorAll('input[name="harga_satuan[]"]');
    var totalInputs = document.querySelectorAll('input[name="total[]"]');
    window.addEventListener('DOMContentLoaded', function() {
        // Tambahkan event listener untuk setiap input jumlah_pesanan[]
        jumlahPesananInputs.forEach(function(input, index) {
            input.addEventListener('input', function() {
                hitungTotal(index);
            });
        });

        // Tambahkan event listener untuk setiap input harga_satuan[]
        hargaSatuanInputs.forEach(function(input, index) {
            input.addEventListener('input', function() {
                hitungTotal(index);
            });
        });

        // Fungsi untuk menghitung total dan mengisi ke dalam input total[]
        function hitungTotal(index) {
            var jumlahPesanan = jumlahPesananInputs[index].value || 0;
            var hargaSatuan = hargaSatuanInputs[index].value || 0;
            var total = jumlahPesanan * hargaSatuan;
            totalInputs[index].value = total; // Atur jumlah desimal yang diinginkan
        }

        // Panggil fungsi hitungTotal() untuk setiap baris saat dokumen dimuat
        jumlahPesananInputs.forEach(function(input, index) {
            hitungTotal(index);
        });
    });

    // Fungsi untuk mengupdate harga saat pilihan produk diubah
    function updateHarga(select) {
        var hargaSatuanInput = select.parentNode.nextElementSibling.querySelector('input');
        var selectedOption = select.options[select.selectedIndex];
        var harga = selectedOption.getAttribute('data-harga') || 0;
        hargaSatuanInput.value = harga;
        // Panggil fungsi hitungTotal() untuk menghitung ulang total setelah harga diubah
        var index = Array.from(select.parentNode.parentNode.children).indexOf(select.parentNode);
        hitungTotal(index);
    }

    function addRow() {
        var newRow = '<tr>' +
            '<td class="align-middle p-1 text-center">' +
            '<button type="button" class="bg-red-500 text-white py-1 px-2 rounded-lg hover:bg-red-600" onclick="removeRow(this)">' +
            '<i class="fas fa-trash-alt"></i>' +
            '</button>' +
            '</td>' +
            '<td class="align-middle p-1 text-center">' +
            '<input type="number" class="text-center w-full border" step="any" name="jumlah_pesanan[]" />' +
            '</td>' +
            '<td class="align-middle p-1">' +
            '<select name="idbrgmedis[]" class="w-full border">' +
            '<option value="" selected></option>' +
            '<?php foreach ($medis_data as $brgmedis) : ?>' +
            '<option value="<?= $brgmedis['id'] ?>" data-harga="<?= $brgmedis['harga'] ?>"><?= $brgmedis['nama'] ?></option>' +
            '<?php endforeach; ?>' +
            '</select>' +
            '</td>' +
            '<td class="align-middle p-1">' +
            '<input type="text" step="any" class="text-center w-full border" name="harga_satuan[]" />' +
            '</td>' +
            '<td class="align-middle p-1 text-right">' +
            '<input type="text" class="text-center w-full border" name="total[]" readonly />' +
            '</td>' +
            '</tr>';
        document.getElementById('item-list').getElementsByTagName('tbody')[0].insertAdjacentHTML('beforeend', newRow);

        // Setel ulang variabel jumlahPesananInputs, hargaSatuanInputs, dan totalInputs setelah menambahkan baris baru
        jumlahPesananInputs = document.querySelectorAll('input[name="jumlah_pesanan[]"]');
        hargaSatuanInputs = document.querySelectorAll('input[name="harga_satuan[]"]');
        totalInputs = document.querySelectorAll('input[name="total[]"]');

        // Tambahkan event listener untuk setiap input jumlah_pesanan[] dan harga_satuan[] yang baru
        jumlahPesananInputs.forEach(function(input, index) {
            input.addEventListener('input', function() {
                hitungTotal(index);
            });
        });

        hargaSatuanInputs.forEach(function(input, index) {
            input.addEventListener('input', function() {
                hitungTotal(index);
            });
        });

        // Fungsi untuk menghitung total dan mengisi ke dalam input total[] untuk baris baru
        function hitungTotal(index) {
            var jumlahPesanan = jumlahPesananInputs[index].value || 0;
            var hargaSatuan = hargaSatuanInputs[index].value || 0;
            var total = jumlahPesanan * hargaSatuan;
            totalInputs[index].value = total; // Atur jumlah desimal yang diinginkan
        }
    }

    function removeRow(btn) {
        var row = btn.parentNode.parentNode;
        row.parentNode.removeChild(row);
    }
</script>
<?= $this->endSection(); ?>