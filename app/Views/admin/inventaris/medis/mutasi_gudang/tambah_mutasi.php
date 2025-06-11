<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<!-- Card Section -->
<div class="max-w-[85rem] py-6 lg:py-3 px-8 mx-auto">
    <!-- Card -->
    <div class="bg-white rounded-xl shadow p-4 sm:p-7 dark:bg-slate-900">
        <div class="mb-8">
            <h2 class="text-xl font-bold text-gray-800 dark:text-gray-200">
                Mutasi Antar Gudang Barang Medis
            </h2>

        </div>

        <form action="/mutasimedis/submittambah" method="post">
            <?= csrf_field() ?>

            <div class="mt-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Tanggal</label>
                <input type="date" id="tanggal" name="tanggal" class="border border-gray-300 text-gray-900 text-sm rounded-lg  p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" required>
            </div>


            <div class="mt-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Asal Lokasi</label>
                <select name="asallokasi" class="border border-gray-300 text-gray-900 text-sm rounded-lg  p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" required>
                    <option value="">-</option>
                    <?php foreach ($ruangan_data as $ruangan) { ?>
                        <option value="<?= $ruangan['id'] ?>"><?= $ruangan['nama'] ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="mt-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Tujuan Lokasi</label>
                <select name="tujuanlokasi" class="border border-gray-300 text-gray-900 text-sm rounded-lg  p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" required>
                    <option value="">-</option>
                    <?php foreach ($ruangan_data as $ruangan) { ?>
                        <option value="<?= $ruangan['id'] ?>"><?= $ruangan['nama'] ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="mt-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Keterangan</label>
                <input type="text" name="catatan" class="border border-gray-300 text-gray-900 text-sm rounded-lg  p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white">
            </div>


            <!-- End Grid -->
            <div class="mt-5 flex flex-col">
                <div class="-m-1.5 overflow-x-auto">
                    <div class="p-1.5 min-w-full inline-block align-middle">
                        <div class="border-t-[1px] border-x-0 border-b-0 overflow-hidden dark:border-neutral-700">
                            <div class="flex justify-between p-2 text-sm text-gray-600 dark:text-neutral-500">
                                <div class="inline-flex items-center text-[1.25rem] font-[400] leading-[normal] tracking-[0.00625rem]">

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
                                <table class="min-w-full divide-y divide-gray-200  dark:divide-neutral-700" id="item-list">
                                    <?php 
                                        $widths  = [3, 5, 15, 8, 10, 7, 7, 10, 10, 10];
                                        echo view('components/data_tabel_colgroup',['widths' => $widths]);
                                        
                                        // $columns = [
                                            
                                        // ];
                                        // echo view('components/data_tabel_thead',['columns' => $columns]);
                                    ?>
                                    <thead class="bg-[#DCDCDC]">
                                        <tr>
                                            <th class="px-1 py-1 text-[0.9375rem] leading-[normal] tracking-[0.00469rem] text-center"></th>
                                            <th class="px-1 py-1 text-[0.9375rem] leading-[normal] tracking-[0.00469rem] text-center">Jml</th>
                                            <th class="px-1 py-1 text-[0.9375rem] leading-[normal] tracking-[0.00469rem] text-center">Barang</th>
                                            <th class="px-1 py-1 text-[0.9375rem] leading-[normal] tracking-[0.00469rem] text-center">Satuan</th>
                                            <th class="px-1 py-1 text-[0.9375rem] leading-[normal] tracking-[0.00469rem] text-center">Harga</th>
                                            <th class="px-1 py-1 text-[0.9375rem] leading-[normal] tracking-[0.00469rem] text-center">Stok Asal</th>
                                            <th class="px-1 py-1 text-[0.9375rem] leading-[normal] tracking-[0.00469rem] text-center">Stok Tujuan</th>
                                            <th class="px-1 py-1 text-[0.9375rem] leading-[normal] tracking-[0.00469rem] text-center">Kadaluwarsa</th>
                                            <th class="px-1 py-1 text-[0.9375rem] leading-[normal] tracking-[0.00469rem] text-center">No batch</th>
                                            <th class="px-1 py-1 text-[0.9375rem] leading-[normal] tracking-[0.00469rem] text-center">No faktur</th>
                                        </tr>
                                    </thead>
                                    <tbody class="max-h-[0.1px] overflow-y-auto divide-y divide-gray-200 dark:divide-neutral-700">
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
                                                <input type="number" min="0" class="rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#FDFDFD] text-center w-full border" step="any" name="jumlah[]" required />
                                            </td>
                                            <td class="align-middle p-1">
                                                <select name="idbrgmedis[]" class="w-full py-[1.5px] border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#FDFDFD] text-center" onchange="updateFields(this)" required>
                                                    <option value="" selected></option>
                                                    <?php foreach ($barang_data as $barang) { ?>
                                                        <option value="<?= $barang['id'] ?>" data-satuan="<?= $barang['id_satbesar'] ?>" data-harga="<?= $barang['h_beli'] ?>" data-kadaluwarsa="<?= $barang['kadaluwarsa'] ?>"><?= $barang['nama'] ?></option>
                                                    <?php } ?>
                                                </select>
                                            </td>
                                            <td class="align-middle p-1">
                                                <input type="text" step="any" class="satuan-input rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#F6F6F6] text-center w-full border" name="satuan[]" readonly required />
                                            </td>
                                            <td class="align-middle p-1">
                                                <input type="number" min="0" step="any" class="harga-input rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#F6F6F6] text-center w-full border" name="harga[]" readonly required />
                                            </td>
                                            <td class="align-middle p-1 text-right">
                                                <input type="number" min="0" class="rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#F6F6F6] cursor-default text-center w-full border" name="stokasal[]" readonly required />
                                            </td>
                                            <td class="align-middle p-1">
                                                <input type="number" min="0" step="any" class="rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#F6F6F6] text-center w-full border" name="stoktujuan[]" readonly required />
                                            </td>

                                            <td class="align-middle p-1">
                                                <input type="date" step="any" class="kadaluwarsa-input rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#FDFDFD] text-center w-full border" name="kadaluwarsa[]" />
                                            </td>
                                            <td class="align-middle p-1">
                                                <input type="text" min="0" step="any" class="rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#FDFDFD] text-center w-full border" name="nobatch[]" />
                                            </td>
                                            <td class="align-middle p-1">
                                                <input type="text" step="any" class="rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#FDFDFD] text-center w-full border" name="nofaktur[]" />
                                            </td>
                                        </tr>

                                    </tbody>

                                </table>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
            <div class="mt-5 flex justify-end gap-x-2">
                <a href="javascript:history.back()" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-white dark:hover:bg-gray-800 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                    Kembali
                </a>
                <button type="submit" id="submitButton" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-[#0A2D27] text-[#ACF2E7] disabled:opacity-50 disabled:pointer-events-none dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                    Simpan
                </button>
            </div>
        </form>

    </div>
    <!-- End Card -->

</div>

<script>
    function addRow() {
        var table = document.querySelector('table tbody'); // Adjust this selector to target your table body
        var newRow = document.createElement('tr');

        newRow.innerHTML = `
        <td>
                                            <button type="button" class="flex justify-center p-2" onclick="removeRow(this)">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                                    <path d="M16 0H4C1.79086 0 0 1.79086 0 4V16C0 18.2091 1.79086 20 4 20H16C18.2091 20 20 18.2091 20 16V4C20 1.79086 18.2091 0 16 0Z" fill="#0A2D27" />
                                                    <path d="M15 10.625H5C4.65833 10.625 4.375 10.3417 4.375 10C4.375 9.65833 4.65833 9.375 5 9.375H15C15.3417 9.375 15.625 9.65833 15.625 10C15.625 10.3417 15.3417 10.625 15 10.625Z" fill="#ACF2E7" />
                                                </svg>
                                            </button>
                                        </td>
                                        <td class="align-middle p-1 text-center">
                                                <input type="number" min="0" class="rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#FDFDFD] text-center w-full border" step="any" name="jumlah[]" required />
                                            </td>
                                            <td class="align-middle p-1">
                                                <select name="idbrgmedis[]" class="w-full py-[1.5px] border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#FDFDFD] text-center" onchange="updateFields(this)" required>
                                                    <option value="" selected></option>
                                                    <?php foreach ($barang_data as $barang) { ?>
                                                        <option value="<?= $barang['id'] ?>" data-satuan="<?= $barang['id_satbesar'] ?>" data-harga="<?= $barang['h_beli'] ?>" data-kadaluwarsa="<?= $barang['kadaluwarsa'] ?>"><?= $barang['nama'] ?></option>
                                                    <?php } ?>
                                                </select>
                                            </td>
                                            <td class="align-middle p-1">
                                                <input type="text" step="any" class="satuan-input rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#F6F6F6] text-center w-full border" name="satuan[]" readonly required />
                                            </td>
                                            <td class="align-middle p-1">
                                                <input type="number" min="0" step="any" class="harga-input rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#F6F6F6] text-center w-full border" name="harga[]" readonly required />
                                            </td>
                                            <td class="align-middle p-1 text-right">
                                                <input type="number" min="0" class="rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#F6F6F6] cursor-default text-center w-full border" name="stokasal[]" readonly required />
                                            </td>
                                            <td class="align-middle p-1">
                                                <input type="number" min="0" step="any" class="rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#F6F6F6] text-center w-full border" name="stoktujuan[]" readonly required />
                                            </td>

                                            <td class="align-middle p-1">
                                                <input type="date" step="any" class="kadaluwarsa-input rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#FDFDFD] text-center w-full border" name="kadaluwarsa[]" />
                                            </td>
                                            <td class="align-middle p-1">
                                                <input type="text" min="0" step="any" class="rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#FDFDFD] text-center w-full border" name="nobatch[]" />
                                            </td>
                                            <td class="align-middle p-1">
                                                <input type="text" step="any" class="rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#FDFDFD] text-center w-full border" name="nofaktur[]" />
                                            </td>
        `;

        table.appendChild(newRow);
    }

    function removeRow(btn) {
        var row = btn.parentNode.parentNode;
        row.parentNode.removeChild(row);
    }
    var gudangData = <?= json_encode($gudang_data); ?>;
    var satuanData = <?= json_encode($satuan_data); ?>;

    document.addEventListener("DOMContentLoaded", function() {
        // Attach event listeners for change events on relevant elements
        document.querySelectorAll('select[name="idbrgmedis[]"]').forEach(function(selectElement) {
            selectElement.addEventListener('change', function() {
                updateFields(selectElement);
            });
        });

        document.querySelector('select[name="asallokasi"]').addEventListener('change', function() {
            updateStok();
        });

        document.querySelector('select[name="tujuanlokasi"]').addEventListener('change', function() {
            updateStok();
        });
    });

    function updateFields(selectElement) {
        var selectedOption = selectElement.options[selectElement.selectedIndex];
        var satuanId = selectedOption.getAttribute('data-satuan');
        var harga = selectedOption.getAttribute('data-harga');
        var kadaluwarsa = selectedOption.getAttribute('data-kadaluwarsa');

        var satuanInput = selectElement.closest('td').nextElementSibling.querySelector('.satuan-input');
        var hargaInput = selectElement.closest('td').nextElementSibling.nextElementSibling.querySelector('.harga-input');
        var kadaluwarsaInput = selectElement.closest('tr').querySelector('.kadaluwarsa-input');

        satuanInput.value = getSatuanName(satuanId);
        hargaInput.value = harga;
        kadaluwarsaInput.value = kadaluwarsa;

        updateStok();
    }

    function getSatuanName(id) {
        for (var key in satuanData) {
            if (satuanData[key]['id'] == id) {
                return satuanData[key]['nama'];
            }
        }
        return '';
    }

    function updateStok() {
        var asalLokasiId = document.querySelector('select[name="asallokasi"]').value;
        var tujuanLokasiId = document.querySelector('select[name="tujuanlokasi"]').value;
        var idbrgmedisElements = document.querySelectorAll('select[name="idbrgmedis[]"]');
        var asalStokInputs = document.querySelectorAll('input[name="stokasal[]"]');
        var tujuanStokInputs = document.querySelectorAll('input[name="stoktujuan[]"]');
        var selectedIdbrgmedis = [];

        idbrgmedisElements.forEach(function(element) {
            if (element.value !== '') {
                selectedIdbrgmedis.push(element.value);
            }
        });

        selectedIdbrgmedis.forEach(function(idbrgmedis, index) {
            var asalStokInput = asalStokInputs[index];
            var tujuanStokInput = tujuanStokInputs[index];

            if (asalLokasiId && idbrgmedis) {
                var asalStokValue = getStokValue(asalLokasiId, idbrgmedis);
                asalStokInput.value = asalStokValue;
            } else {
                asalStokInput.value = '';
            }

            if (tujuanLokasiId && idbrgmedis) {
                var tujuanStokValue = getStokValue(tujuanLokasiId, idbrgmedis);
                tujuanStokInput.value = tujuanStokValue;
            } else {
                tujuanStokInput.value = '';
            }
        });
    }

    function getStokValue(lokasiId, idbrgmedis) {
        for (var key in gudangData) {
            if (gudangData[key]['id_ruangan'] == lokasiId && gudangData[key]['id_barang_medis'] == idbrgmedis) {
                return gudangData[key]['stok'];
            }
        }
        return 0;
    }
</script>
<?= $this->endSection(); ?>