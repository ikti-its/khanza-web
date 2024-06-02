<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<!-- Card Section -->
<div class="max-w-4xl px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto">
    <!-- Card -->
    <div class="bg-white rounded-xl shadow p-4 sm:p-7 dark:bg-slate-900">
        <div class="mb-8">
            <h2 class="text-xl font-bold text-gray-800 dark:text-gray-200">
                Tambah Pengajuan Barang Medis
            </h2>
            <p class="text-sm text-gray-600 dark:text-gray-400">
                Manage your name, password and account settings.
            </p>
        </div>

        <form action="/submittambahpengajuanmedis" method="post">
            <!-- Grid -->
            <!-- <input name="statusapoteker" type="hidden" value="Ditolak">
            <input name="statuskeuangan" type="hidden" value="Ditolak">
            <input name="statuspersetujuan" type="hidden" value="Ditolak"> -->
            <div class="grid sm:grid-cols-12 gap-2 sm:gap-6">
                <div class="sm:col-span-3">
                    <label class="inline-block text-sm text-gray-800 mt-2.5 dark:text-gray-200">
                        Tanggal Pengajuan
                    </label>
                </div>
                <!-- End Col -->

                <div class="sm:col-span-9">
                    <input name="tglpengajuan" type="date" class="py-2 px-3 pe-11 block w-full border-gray-200 shadow-sm text-sm rounded-lg focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600" placeholder="Paracetamol">
                </div>
                <div class="sm:col-span-3">
                    <label class="inline-block text-sm text-gray-800 mt-2.5 dark:text-gray-200">
                        Nomor Pengajuan
                    </label>
                </div>
                <!-- End Col -->

                <div class="sm:col-span-9">
                    <input name="nopengajuan" type="text" class="py-2 px-3 pe-11 block w-full border-gray-200 shadow-sm text-sm rounded-lg focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600" value="PBM<?php echo date('Ymd') . sprintf('%03d', count($pengajuan_data) + 1); ?>">
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
                            <?php foreach ($supplier_data as $supplier) : ?>
                                <option value="<?= $supplier['id'] ?>"><?= $supplier['nama'] ?></option>
                            <?php endforeach; ?>
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
                            <option value="" selected>-</option>
                            <?php foreach ($pegawai_data as $pegawai) : ?>
                                <option value="<?= $pegawai['id'] ?>"><?= $pegawai['nama'] ?></option>
                            <?php endforeach; ?>
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
                    <input name="catatan" type="text" class="py-2 px-3 pe-11 block w-full border-gray-200 shadow-sm text-sm rounded-lg focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600" placeholder="0">
                </div>

            </div>
            <!-- End Grid -->
            <div class="mt-5 flex flex-col">
                <div class="-m-1.5 overflow-x-auto">
                    <div class="p-1.5 min-w-full inline-block align-middle">
                        <div class="border rounded-lg overflow-hidden dark:border-neutral-700">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700" id="item-list">
                                <colgroup>
                                    <col width="5%">
                                    <col width="7%">
                                    <col width="30%">
                                    <col width="18%">
                                    <col width="20%">
                                    <col width="20%">
                                </colgroup>
                                <thead>
                                    <tr class="bg-navy disabled">
                                        <th class="px-1 py-1 text-center"></th>
                                        <th class="px-1 py-1 text-center">Qty</th>
                                        <th class="px-1 py-1 text-center">Barang</th>
                                        <th class="px-1 py-1 text-center">Satuan</th>
                                        <th class="px-1 py-1 text-center">Harga</th>
                                        <th class="px-1 py-1 text-center">Total</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-neutral-700">
                                    <tr>
                                        <td class="align-middle p-1 text-center">
                                            <button type="button" class="bg-red-500 text-white py-1 px-2 rounded-lg hover:bg-red-600" onclick="removeRow(this)">
                                                <i class="fas fa-user"></i>
                                            </button>
                                        </td>
                                        <td class="align-middle p-1 text-center">
                                            <input type="number" class="text-center w-full border" step="any" name="jumlah_pesanan[]" />
                                        </td>
                                        <td class="align-middle p-1">
                                            <select name="idbrgmedis[]" class="w-full border text-center">
                                                <option value="" selected></option>
                                                <?php foreach ($barang_medis as $brgmedis) : ?>
                                                    <option value="<?= $brgmedis['id'] ?>" data-harga="<?= $brgmedis['harga'] ?>"><?= $brgmedis['nama'] ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </td>
                                        <td class="align-middle p-1">
                                            <select name="satuanbrgmedis[]" class="w-full border text-center">
                                                <option value="" selected></option>
                                                <?php foreach ($satuan_data as $satuan) : ?>
                                                    <option value="<?= $satuan['id'] ?>"><?= $satuan['nama'] ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </td>
                                        <td class="align-middle p-1">
                                            <input type="text" step="any" class="text-center w-full border" name="harga_satuan[]" />
                                        </td>
                                        <td class="align-middle p-1 text-right">
                                            <input type="text" class="text-center w-full border" name="total[]" readonly />
                                        </td>

                                    </tr>

                                </tbody>
                                <tfoot>
                                    <tr class="pt-5">
                                        <th class="p-1 pt-2" style="text-align: right;" colspan="5">
                                            <button type="button" onclick="addRow()" class="inline-flex items-center text-sm font-semibold rounded-lg border border-transparent text-blue-600 hover:text-blue-800 disabled:opacity-50 disabled:pointer-events-none dark:text-blue-500 dark:hover:text-blue-400">
                                                Add Row
                                            </button>
                                            Discount (%)
                                            <input type="number" step="any" name="diskonpersen" class="border text-center" style="width: 20%;">
                                        </th>

                                        <th class="p-1 pt-2 text-right">
                                            <input type="text" class="w-full border border-gray-300 text-center" name="diskonjumlah">
                                        </th>
                                    </tr>

                                    <tr>
                                        <th class="p-1" style="text-align: right;" colspan="5">Tax Inclusive (%)
                                            <input type="number" step="any" name="pajakpersen" class="border text-center" style="width: 20%;">
                                        </th>

                                        <th class="p-1 text-right">
                                            <input type="text" class="w-full border border-gray-300 text-center" name="pajakjumlah">
                                        </th>
                                    </tr>
                                    <tr>
                                        <th class="p-1" style="text-align: right;" colspan="5">Materai</th>
                                        <th class="p-1 text-right">
                                            <input type="text" class="w-full border border-gray-300 text-center" name="materai">
                                        </th>
                                    </tr>
                                    <tr>
                                        <th class="p-1" style="text-align: right;" colspan="5">Total</th>
                                        <th class="p-1" id="total"><input type="text" class="w-full border border-gray-300 text-center" name="totalkeseluruhan" disabled></th>
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
    var totalKeseluruhanInputs = document.querySelector('input[name="totalkeseluruhan"]');
    var diskonPersenInput = document.querySelector('input[name="diskonpersen"]');
    var diskonJumlahInput = document.querySelector('input[name="diskonjumlah"]');

    // Tambahkan event listener untuk setiap input jumlah_pesanan[]
    function hitungTotal(index) {
        var jumlahPesanan = jumlahPesananInputs[index].value || 0;
        var hargaSatuan = hargaSatuanInputs[index].value || 0;
        var total = jumlahPesanan * hargaSatuan;
        totalInputs[index].value = total; // Atur jumlah desimal yang diinginkan

        hitungDiskon();
    }

    function hitungDiskon() {
        var totalSemua = 0;
        totalInputs.forEach(function(input) {
            totalSemua += parseFloat(input.value) || 0;
        });

        var diskonPersen = parseFloat(diskonPersenInput.value) || 0;
        var diskonJumlah = totalSemua * (diskonPersen / 100);
        diskonJumlahInput.value = diskonJumlah.toFixed(0);
    }

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

    diskonPersenInput.addEventListener('input', function() {
        hitungDiskon();
    });

    // Fungsi untuk menghitung total dan mengisi ke dalam input total[]

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
            '<select name="idbrgmedis[]" class="w-full border text-center">' +
            '<option value="" selected></option>' +
            '<?php foreach ($barang_medis as $brgmedis) : ?>' +
            '<option value="<?= $brgmedis['id'] ?>" data-harga="<?= $brgmedis['harga'] ?>"><?= $brgmedis['nama'] ?></option>' +
            '<?php endforeach; ?>' +
            '</select>' +
            '</td>' +
            '<td class="align-middle p-1">' +
            '<select name="satuanbrgmedis[]" class="w-full border text-center">' +
            '<option value="" selected></option>' +
            '<?php foreach ($satuan_data as $satuan) : ?>' +
            ' <option value="<?= $satuan['id'] ?>"><?= $satuan['nama'] ?></option>' +
            '<?php endforeach; ?>' +
            '</select>' +
            '</td>' +
            '<td class="align-middle p-1">' +
            '<input type="text" step="any" class="text-center w-full border" name="harga_satuan[]" />' +
            '</td>' +
            '<td class="align-middle p-1 text-right">' +
            '<input type="text" class="text-center w-full border" name="total[]" readonly />' +
            '</td>';
        '</tr>';
        document.getElementById('item-list').getElementsByTagName('tbody')[0].insertAdjacentHTML('beforeend', newRow);
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
    }

    function removeRow(btn) {
        var row = btn.parentNode.parentNode;
        row.parentNode.removeChild(row);
    }
</script>
<?= $this->endSection(); ?>