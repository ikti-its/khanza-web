<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<!-- Card Section -->
<div class="max-w-4xl px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto">
    <!-- Card -->
    <div class="bg-white rounded-xl shadow p-4 sm:p-7 dark:bg-slate-900">
        <div class="mb-8">
            <h2 class="text-xl font-bold text-gray-800 dark:text-gray-200">
                Tambah Stok Keluar Barang Medis
            </h2>

        </div>

        <form action="/submittambahstokkeluarmedis" method="post">
            <!-- Grid -->
            <input type="hidden" value="" name="idpengajuan" class="text-center border mr-1">
            <input type="hidden" value="" name="tglpengajuan" class="text-center border mr-1">
            <input type="hidden" value="" name="nopengajuan" class="text-center border mr-1">
            <input type="hidden" value="" name="supplier" class="text-center border mr-1">
            <input type="hidden" value="" name="pegawaipengajuan" class="text-center border mr-1">
            <input type="hidden" value="" name="catatanpengajuan" class="text-center border mr-1">
            <input type="hidden" value="" name="diskonpersen" class="text-center border" readonly>
            <input type="hidden" value="" name="diskonjumlah" class="text-center w-full border border-gray-300" readonly>
            <input type="hidden" value="" name="pajakpersen" class=" text-center border" readonly>
            <input type="hidden" value="" name="pajakjumlah" class="text-center w-full border border-gray-300" readonly>
            <input type="hidden" value="" name="materai" class="text-center w-full border border-gray-300" readonly>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Tanggal Stok Keluar</label>
                <input type="date" name="tglkeluar" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white">
            </div>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">No Keluar</label>
                <input type="text" name="nokeluar" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white">
            </div>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Pegawai</label>
                <select name="pegawaistokkeluar" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white">
                    <option value="">-</option>
                    <?php foreach ($pegawai_data as $pegawai) : ?>
                        <option value="<?= $pegawai['id'] ?>"><?= $pegawai['nama'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Keterangan</label>
                <input type="text" name="keteranganstokkeluar" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white">
            </div>
        
            <!-- End Grid -->
            <div class="mt-5 flex flex-col">
                <div class="-m-1.5 overflow-x-auto">
                    <div class="p-1.5 min-w-full inline-block align-middle">
                        <div class="border rounded-lg overflow-hidden dark:border-neutral-700">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700" id="item-list">
                                <colgroup>

                                    <col width="5%">
                                    <!-- 38% -->
                                    <col width="25%">
                                    <col width="15%">
                                    <col width="15%">
                                    <col width="20%">
                                    <col width="20%">
                                </colgroup>
                                <thead>
                                    <tr class="bg-navy disabled">

                                        <th class="text-center"></th>
                                        <th class="text-center">Item</th>
                                        <th class="text-center">Stok saat ini</th>
                                        <th class="text-center">Jumlah keluar</th>
                                        <th class="text-center">No Faktur</th>
                                        <th class="text-center">No Batch</th>
                                    </tr>
                                </thead>
                                <tbody class="tabelbodypesanan divide-y divide-gray-200 dark:divide-neutral-700">
                                    <tr>
                                        <td class="align-middle p-1 text-center">
                                            <button type="button" class="bg-red-500 text-white py-1 px-2 rounded-lg hover:bg-red-600" onclick="removeRow(this)">
                                                <i class="fas fa-user"></i>
                                            </button>
                                        </td>
                                        <td class="align-middle p-1">
                                            <select name="idbrgmedis[]" class="w-full border text-center" onchange="updateStok(this)">
                                                <option value="" selected></option>
                                                <?php foreach ($medis_data as $brgmedis) : ?>
                                                    <option value="<?= $brgmedis['id'] ?>" data-stok="<?= $brgmedis['stok'] ?>"><?= $brgmedis['nama'] ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </td>
                                        <td class="align-middle p-1 text-center">
                                            <input type="text" class="text-center w-full border" step="any" name="stoksaatini[]" />
                                        </td>
                                        <td class="align-middle p-1 text-center">
                                            <input type="text" class="text-center w-full border" name="jlhkeluar[]" />
                                        </td>
                                        <td class="align-middle p-1 text-center">
                                            <input type="text" list="nofaktur" class="text-center w-full border" name="nofaktur[]" />
                                            <datalist id="nofaktur">
                                                <?php foreach ($penerimaan_data as $penerimaan) : ?>
                                                    <option value="<?= $penerimaan['no_faktur'] ?>"></option>
                                                <?php endforeach; ?>
                                            </datalist>

                                        </td>
                                        <td class="align-middle p-1 text-center">
                                            <input type="text" list="nobatch" class="text-center w-full border" name="nobatch[]" />
                                            <datalist id="nobatch">
                                                <?php foreach ($pesanan_data as $pesanan) : ?>
                                                    <option value="<?= $pesanan['no_batch'] ?>"></option>
                                                <?php endforeach; ?>
                                            </datalist>
                                        </td>

                                    </tr>

                                </tbody>

                                <tfoot>
                                    <tr class="pt-5">
                                        <th class="px-2 py-1 text-right" colspan="6">
                                            <button type="button" onclick="addRow()" class="inline-flex items-center text-sm font-semibold rounded-lg border border-transparent text-blue-600 hover:text-blue-800 disabled:opacity-50 disabled:pointer-events-none dark:text-blue-500 dark:hover:text-blue-400">
                                                Add Row
                                            </button>

                                        </th>
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
                <button type="submit" value="4" name="statuspesanan" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                    Save changes
                </button>
            </div>
        </form>

    </div>
    <!-- End Card -->

</div>

<!-- End Card Section -->
<script>
    function addRow() {
        var newRow = '<td class="align-middle p-1 text-center">' +
            '<button type="button" class="bg-red-500 text-white py-1 px-2 rounded-lg hover:bg-red-600" onclick="removeRow(this)">' +
            '<i class="fas fa-user"></i>' +
            '</button>' +
            '</td>' +
            '<td class="align-middle p-1 text-center">' +
            '<input type="text" class="text-center w-full border" step="any" name="" />' +
            '</td>' +
            '<td class="align-middle p-1">' +
            '<input type="text" step="any" class="text-center w-full border" name="" />' +
            '</td>' +
            '<td class="align-middle p-1 text-center">' +
            '<input type="text" class="text-center w-full border" name="" readonly />' +
            '</td>' +
            '<td class="align-middle p-1 text-center">' +
            '<input type="text" class="text-center w-full border" name="" />' +
            '</td>' +
            '<td class="align-middle p-1 text-center">' +
            '<input type="text" class="text-center w-full border" name="" />' +
            '</td>' +
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

    function updateStok(select) {
        // Get the selected option
        var selectedOption = select.options[select.selectedIndex];
        // Get the value of the 'stok' attribute
        var stok = selectedOption.getAttribute('data-stok');
        // Find the input field and set its value to the stok
        var stokInput = select.closest('tr').querySelector('input[name="stoksaatini[]"]');
        stokInput.value = stok;
    }
</script>
<?= $this->endSection(); ?>