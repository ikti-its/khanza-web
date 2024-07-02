<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<!-- Card Section -->
<div class="max-w-4xl px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto">
    <!-- Card -->
    <div class="bg-white rounded-xl shadow p-4 sm:p-7 dark:bg-slate-900">
        <div class="mb-8">
            <h2 class="text-xl font-bold text-gray-800 dark:text-gray-200">
                Edit Stok Keluar Barang Medis
            </h2>

        </div>

        <form action="/submiteditstokkeluarmedis/<?= $stok_keluar_data['id'] ?>" method="post">
            <input type="hidden" name="idstokkeluar" value="<?= $stok_keluar_data['id'] ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white">
            <!-- Grid -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Tanggal Stok Keluar</label>
                <input type="date" name="tglkeluar" value="<?= $stok_keluar_data['tanggal_stok_keluar'] ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white">
            </div>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">No Keluar</label>
                <input type="text" name="nokeluar" value="<?= $stok_keluar_data['no_keluar'] ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white">
            </div>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Pegawai</label>
                <select name="pegawaistokkeluar" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white">
                    <?php
                    foreach ($pegawai_data as $pegawai) {
                        $optionpegawai = [$pegawai['id'] => $pegawai['nama']];
                        foreach ($optionpegawai as $pegawaiid => $pegawainama) {
                            if ($pegawaiid === $stok_keluar_data['id_pegawai']) {
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
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Keterangan</label>
                <input type="text" name="keteranganstokkeluar" value="<?= $stok_keluar_data['keterangan'] ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white">
            </div>

            <!-- End Grid -->
            <div class="mt-5 flex flex-col">
                <div class="-m-1.5 overflow-x-auto">
                    <div class="p-1.5 min-w-full inline-block align-middle">
                        <div class="border rounded-lg overflow-hidden dark:border-neutral-700">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700" id="item-list">
                                <colgroup>
                                    <col width="30%">
                                    <col width="15%">
                                    <col width="15%">
                                    <col width="20%">
                                    <col width="20%">
                                </colgroup>
                                <thead class="bg-[#DCDCDC]">
                                    <tr class="bg-navy disabled">
                                        <th class="text-center">Barang</th>
                                        <th class="text-center">Stok saat ini</th>
                                        <th class="text-center">Jumlah keluar</th>
                                        <th class="text-center">No Faktur</th>
                                        <th class="text-center">No Batch</th>
                                    </tr>
                                </thead>
                                <tbody class="tabelbodypesanan divide-y divide-gray-200 dark:divide-neutral-700">
                                    <?php foreach ($barang_stok_keluar_data as $brg) { ?>
                                        <input type="hidden" value="<?= $brg['id'] ?>" class="text-center w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)]" step="any" name="idtransaksibrgmedis[]" />
                                        <tr>
                                            <td class="align-middle p-1">
                                                <input type="hidden" value="<?= $brg['id_barang_medis'] ?>" class="text-center w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)]" step="any" name="idbrgmedis[]" />
                                                <input type="text" value="<?php foreach ($medis_data as $brgmedis) {
                                                                                if ($brg['id_barang_medis'] === $brgmedis['id']) {
                                                                                    echo $brgmedis['nama'];
                                                                                }
                                                                            } ?>" class="text-center w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)]" step="any" name="[]" />
                                            </td>
                                            <td class="align-middle p-1 text-center">
                                                <input type="text" value="<?php
                                                                            $total_stok = 0;

                                                                            // Loop through each barang medis
                                                                            foreach ($medis_data as $brgmedis) {
                                                                                if ($brgmedis['id'] === $brg['id_barang_medis']) {
                                                                                    $stok_barang_medis = $brgmedis['stok'];
                                                                                    $stok_pesanan = 0;

                                                                                    // Loop through each pesanan to find matching id_barang_medis
                                                                                    foreach ($pesanan_data as $pesanan) {
                                                                                        if ($brgmedis['id'] === $pesanan['id_barang_medis']) {
                                                                                            $stok_pesanan += $pesanan['jumlah_diterima'];
                                                                                        }
                                                                                    }

                                                                                    // Calculate total stok for this barang medis
                                                                                    $total_stok += ($stok_barang_medis + $stok_pesanan);
                                                                                }
                                                                            }

                                                                            echo $total_stok;
                                                                            ?>" class="text-center w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)]" step="any" name="" />
                                            </td>
                                            <td class="align-middle p-1 text-center">
                                                <input type="text" value="<?= $brg['jumlah_keluar'] ?>" class="text-center w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)]" name="jlhkeluar[]" />
                                            </td>
                                            <td class="align-middle p-1 text-center">
                                                <input type="text" list="nofaktur" value="<?= $brg['no_faktur'] ?>" class="text-center w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)]" name="nofaktur[]" />
                                                <datalist id="nofaktur">
                                                    <?php foreach ($penerimaan_data as $penerimaan) : ?>
                                                        <option value="<?= $penerimaan['no_faktur'] ?>"></option>
                                                    <?php endforeach; ?>
                                                </datalist>

                                            </td>
                                            <td class="align-middle p-1 text-center">
                                                <input type="text" list="nobatch" value="<?= $brg['no_batch'] ?>" class="text-center w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)]" name="nobatch[]" />
                                                <datalist id="nobatch">
                                                    <?php foreach ($pesanan_data as $pesanan) : ?>
                                                        <option value="<?= $pesanan['no_batch'] ?>"></option>
                                                    <?php endforeach; ?>
                                                </datalist>
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
                <button type="submit" name="" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-[#0A2D27] text-[#ACF2E7] disabled:opacity-50 disabled:pointer-events-none dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                    Simpan
                </button>
            </div>
        </form>

    </div>
    <!-- End Card -->

</div>

<!-- End Card Section -->
<script>
    // function addRow() {
    //     var newRow = '<tr>' +
    //         '<td class="align-middle px-1 text-center">' +
    //         '<button type="button" class="flex justify-center p-2" onclick="removeRow(this)">' +
    //         '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">' +
    //         '<path d="M16 0H4C1.79086 0 0 1.79086 0 4V16C0 18.2091 1.79086 20 4 20H16C18.2091 20 20 18.2091 20 16V4C20 1.79086 18.2091 0 16 0Z" fill="#0A2D27" />' +
    //         '<path d="M15 10.625H5C4.65833 10.625 4.375 10.3417 4.375 10C4.375 9.65833 4.65833 9.375 5 9.375H15C15.3417 9.375 15.625 9.65833 15.625 10C15.625 10.3417 15.3417 10.625 15 10.625Z" fill="#ACF2E7" />' +
    //         '</svg>' +
    //         '</button>' +
    //         '</td>' +
    //         '<td class="align-middle px-1">' +
    //         '<select name="idbrgmedis[]" class="w-full border text-center rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)]" onchange="updateStok(this)">' +
    //         '<option value="" selected></option>';

    //     // Menambahkan option dari PHP data
    //     <?php foreach ($medis_data as $brgmedis) : ?>
    //         newRow += '<option value="<?= $brgmedis['id'] ?>" data-stok="<?= $brgmedis['stok'] ?>"><?= $brgmedis['nama'] ?></option>';
    //     <?php endforeach; ?>

    //     newRow += '</select>' +
    //         '</td>' +
    //         '<td class="align-middle px-1 text-center">' +
    //         '<input type="text" class="text-center w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)]" step="any" name="stoksaatini[]" />' +
    //         '</td>' +
    //         '<td class="align-middle px-1 text-center">' +
    //         '<input type="text" class="text-center w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)]" name="jlhkeluar[]" />' +
    //         '</td>' +
    //         '<td class="align-middle px-1 text-center">' +
    //         '<input type="text" list="nofaktur" class="text-center w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)]" name="nofaktur[]" />' +
    //         '<datalist id="nofaktur">';

    //     // Menambahkan option dari PHP data
    //     <?php foreach ($penerimaan_data as $penerimaan) : ?>
    //         newRow += '<option value="<?= $penerimaan['no_faktur'] ?>"></option>';
    //     <?php endforeach; ?>

    //     newRow += '</datalist>' +
    //         '</td>' +
    //         '<td class="align-middle px-1 text-center">' +
    //         '<input type="text" list="nobatch" class="text-center w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)]" name="nobatch[]" />' +
    //         '<datalist id="nobatch">';

    //     // Menambahkan option dari PHP data
    //     <?php foreach ($pesanan_data as $pesanan) : ?>
    //         newRow += '<option value="<?= $pesanan['no_batch'] ?>"></option>';
    //     <?php endforeach; ?>

    //     newRow += '</datalist>' +
    //         '</td>' +
    //         '</tr>';
    //     document.getElementById('item-list').getElementsByTagName('tbody')[0].insertAdjacentHTML('beforeend', newRow);
    //     jumlahPesananInputs = document.querySelectorAll('input[name="jumlah_pesanan[]"]');
    //     hargaSatuanInputs = document.querySelectorAll('input[name="harga_satuan[]"]');
    //     totalInputs = document.querySelectorAll('input[name="total[]"]');

    //     // Tambahkan event listener untuk setiap input jumlah_pesanan[] dan harga_satuan[] yang baru
    //     jumlahPesananInputs.forEach(function(input, index) {
    //         input.addEventListener('input', function() {
    //             hitungTotal(index);
    //         });
    //     });

    //     hargaSatuanInputs.forEach(function(input, index) {
    //         input.addEventListener('input', function() {
    //             hitungTotal(index);
    //         });
    //     });
    // }

    // function removeRow(btn) {
    //     var row = btn.parentNode.parentNode;
    //     row.parentNode.removeChild(row);
    // }

    // function updateStok(select) {
    //     // Get the selected option
    //     var selectedOption = select.options[select.selectedIndex];
    //     // Get the value of the 'stok' attribute
    //     var stok = selectedOption.getAttribute('data-stok');
    //     // Find the input field and set its value to the stok
    //     var stokInput = select.closest('tr').querySelector('input[name="stoksaatini[]"]');
    //     stokInput.value = stok;
    // }
</script>
<?= $this->endSection(); ?>