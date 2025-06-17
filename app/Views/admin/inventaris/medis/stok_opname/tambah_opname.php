<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<!-- Card Section -->
<div class="max-w-[85rem] py-6 lg:py-3 px-8 mx-auto">
    <!-- Card -->
    <div class="bg-white rounded-xl shadow p-4 sm:p-7 dark:bg-slate-900">
        <?= view('components/form_judul', [
            'judul' => 'Stok Opname Barang Medis'
        ]) ?>

        <form action="/stokopnamemedis/submittambah" id="myForm" method="post" onsubmit="return validateForm()">
            <?= csrf_field() ?>

            <div class="mt-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Tanggal</label>
                <input type="date" name="tanggal" class="border border-gray-300 text-gray-900 text-sm rounded-lg  p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" required>
            </div>

            <div class="mt-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Lokasi</label>
                <select name="lokasi" class="border border-gray-300 text-gray-900 text-sm rounded-lg  p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" onchange="updateStok()" required>
                    <option value="" selected>-</option>
                    <?php foreach ($ruangan_data as $ruangan) { ?>
                        <option value="<?= $ruangan['id'] ?>"><?= $ruangan['nama'] ?></option>
                    <?php } ?>

                </select>
            </div>
            <div class="mt-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Keterangan</label>
                <input type="text" name="catatan" class="border border-gray-300 text-gray-900 text-sm rounded-lg  p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" required>
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
                                        $widths  = [3, 5, 15, 8, 10, 6, 6, 6, 10, 10, 10, 10];
                                        echo view('components/tabel_colgroup',['widths' => $widths]);
                                        
                                        $columns = [
                                            'Real',
                                            'Barang',
                                            'Satuan',
                                            'Harga',
                                            'Stok',
                                            'Selisih',
                                            'Lebih',
                                            'Nominal Hilang',
                                            'Nominal Lebih',
                                            'No Batch',
                                            'No Faktur'
                                        ];
                                        // echo view('components/tabel_thead',['kolom' => $columns]);
                                    ?>
                                    
                                    <thead class="bg-[#DCDCDC]">
                                        <tr>
                                            <th class="px-1 py-1 text-[0.9375rem] leading-[normal] tracking-[0.00469rem] text-center"></th>
                                            <th class="px-1 py-1 text-[0.9375rem] leading-[normal] tracking-[0.00469rem] text-center">Real</th>
                                            <th class="px-1 py-1 text-[0.9375rem] leading-[normal] tracking-[0.00469rem] text-center">Barang</th>
                                            <th class="px-1 py-1 text-[0.9375rem] leading-[normal] tracking-[0.00469rem] text-center">Satuan</th>
                                            <th class="px-1 py-1 text-[0.9375rem] leading-[normal] tracking-[0.00469rem] text-center">Harga</th>
                                            <th class="px-1 py-1 text-[0.9375rem] leading-[normal] tracking-[0.00469rem] text-center">Stok</th>
                                            <th class="px-1 py-1 text-[0.9375rem] leading-[normal] tracking-[0.00469rem] text-center">Selisih</th>
                                            <th class="px-1 py-1 text-[0.9375rem] leading-[normal] tracking-[0.00469rem] text-center">Lebih</th>
                                            <th class="px-1 py-1 text-[0.9375rem] leading-[normal] tracking-[0.00469rem] text-center">Nominal Hilang</th>
                                            <th class="px-1 py-1 text-[0.9375rem] leading-[normal] tracking-[0.00469rem] text-center">Nominal Lebih</th>
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
                                                <input type="number" min="0" class="real-input rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#FDFDFD] text-center w-full border" step="any" name="real[]" oninput="updateCalculations(this)" required />
                                            </td>
                                            <td class="align-middle p-1">
                                                <select name="idbrgmedis[]" class="w-full py-[1.5px] border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#FDFDFD] text-center" onchange="updateFields(this)" required>
                                                    <option value="" selected></option>
                                                    <?php foreach ($barang_data as $barang) { ?>
                                                        <option value="<?= $barang['id'] ?>" data-satuan="<?= $barang['id_satbesar'] ?>" data-harga="<?= $barang['h_beli'] ?>"><?= $barang['nama'] ?></option>
                                                    <?php } ?>
                                                </select>
                                            </td>
                                            <td class="align-middle p-1">
                                                <input type="text" step="any" class="satuan-input rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#FDFDFD] text-center w-full border" name="satuan[]" required />
                                            </td>
                                            <td class="align-middle p-1">
                                                <input type="number" min="0" step="any" class="harga-input rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#FDFDFD] text-center w-full border" name="harga[]" required oninput="updateCalculations(this)" required />
                                            </td>
                                            <td class="align-middle p-1 text-right">
                                                <input type="number" min="0" class="rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#F6F6F6] cursor-default text-center w-full border" name="stok[]" readonly required />
                                            </td>
                                            <td class="align-middle p-1">
                                                <input type="number" min="0" step="any" class="lebih-input rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#F6F6F6] text-center w-full border" name="selisih[]" readonly required />
                                            </td>
                                            <td class="align-middle p-1">
                                                <input type="number" min="0" step="any" class="selisih-input rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#F6F6F6] text-center w-full border" name="lebih[]" readonly required />
                                            </td>
                                            <td class="align-middle p-1">
                                                <input type="number" min="0" step="any" class="nominalhilang-input rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#F6F6F6] text-center w-full border" name="nominalhilang[]" readonly required />
                                            </td>
                                            <td class="align-middle p-1">
                                                <input type="number" min="0" step="any" class="nominallebih-input rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#F6F6F6] text-center w-full border" name="nominallebih[]" readonly required />
                                            </td>
                                            <td class="align-middle p-1">
                                                <input type="number" min="0" step="any" class="rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#FDFDFD] text-center w-full border" name="nobatch[]" />
                                            </td>
                                            <td class="align-middle p-1">
                                                <input type="number" min="0" step="any" class="rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#FDFDFD] text-center w-full border" name="nofaktur[]" />
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
    document.addEventListener('DOMContentLoaded', function() {
        // Add event listener to the submit button
        document.getElementById('submitButton').addEventListener('click', function(event) {
            if (hasDuplicateSelections()) {
                alert('Duplicate items selected in idbrgmedis. Please select unique items.');
                event.preventDefault(); // Prevent form submission
            }
        });

        function hasDuplicateSelections() {
            var idbrgmedisElements = document.querySelectorAll('select[name="idbrgmedis[]"]');
            var values = [];
            for (var i = 0; i < idbrgmedisElements.length; i++) {
                var value = idbrgmedisElements[i].value;
                if (values.includes(value) && value !== '') {
                    return true; // Duplicate found
                }
                values.push(value);
            }
            return false; // No duplicates
        }
    });

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
                <input type="number" min="0" class="real-input rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#FDFDFD] text-center w-full border" step="any" name="real[]" oninput="updateCalculations(this)" required />
            </td>
            <td class="align-middle p-1">
                <select name="idbrgmedis[]" class="w-full py-[1.5px] border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#FDFDFD] text-center" onchange="updateFields(this)" required>
                    <option value="" selected></option>
                    <?php foreach ($barang_data as $barang) { ?>
                        <option value="<?= $barang['id'] ?>" data-satuan="<?= $barang['id_satbesar'] ?>" data-harga="<?= $barang['h_beli'] ?>"><?= $barang['nama'] ?></option>
                    <?php } ?>
                </select>
            </td>
            <td class="align-middle p-1">
                <input type="text" step="any" class="satuan-input rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#FDFDFD] text-center w-full border" name="satuan[]" required />
            </td>
            <td class="align-middle p-1">
                <input type="number" min="0" step="any" class="harga-input rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#FDFDFD] text-center w-full border" name="harga[]" required oninput="updateCalculations(this)" required />
            </td>
            <td class="align-middle p-1 text-right">
                <input type="number" min="0" class="rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#F6F6F6] cursor-default text-center w-full border" name="stok[]" readonly required />
            </td>
            <td class="align-middle p-1">
                <input type="number" min="0" step="any" class="lebih-input rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#F6F6F6] text-center w-full border" name="selisih[]" readonly required />
            </td>
            <td class="align-middle p-1">
                <input type="number" min="0" step="any" class="selisih-input rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#F6F6F6] text-center w-full border" name="lebih[]" readonly required />
            </td>
            <td class="align-middle p-1">
                <input type="number" min="0" step="any" class="nominalhilang-input rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#F6F6F6] text-center w-full border" name="nominalhilang[]" readonly required />
            </td>
            <td class="align-middle p-1">
                <input type="number" min="0" step="any" class="nominallebih-input rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#F6F6F6] text-center w-full border" name="nominallebih[]" readonly required />
            </td>
            <td class="align-middle p-1">
                <input type="number" min="0" step="any" class="rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#FDFDFD] text-center w-full border" name="nobatch[]" />
            </td>
            <td class="align-middle p-1">
                <input type="number" min="0" step="any" class="rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#FDFDFD] text-center w-full border" name="nofaktur[]" />
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

    function updateFields(selectElement) {
        var selectedOption = selectElement.options[selectElement.selectedIndex];
        var satuanId = selectedOption.getAttribute('data-satuan');
        var harga = selectedOption.getAttribute('data-harga');

        var satuanInput = selectElement.closest('td').nextElementSibling.querySelector('.satuan-input');
        var hargaInput = selectElement.closest('td').nextElementSibling.nextElementSibling.querySelector('.harga-input');

        satuanInput.value = getSatuanName(satuanId);
        hargaInput.value = harga;

        updateStok();
        updateCalculations(hargaInput);
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
        var lokasiId = document.querySelector('select[name="lokasi"]').value;
        var idbrgmedisElements = document.querySelectorAll('select[name="idbrgmedis[]"]');
        var stokInputs = document.querySelectorAll('input[name="stok[]"]');
        var selectedIdbrgmedis = [];

        idbrgmedisElements.forEach(function(element) {
            if (element.value !== '') {
                selectedIdbrgmedis.push(element.value);
            }
        });

        selectedIdbrgmedis.forEach(function(idbrgmedis, index) {
            var stokInput = stokInputs[index];

            if (lokasiId && idbrgmedis) {
                var stokValue = getStokValue(lokasiId, idbrgmedis);
                stokInput.value = stokValue;
                updateCalculations(stokInput);
            } else {
                stokInput.value = '';
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

    function updateCalculations(inputElement) {
        var row = inputElement.closest('tr');
        var realInput = row.querySelector('input[name="real[]"]');
        var stokInput = row.querySelector('input[name="stok[]"]');
        var hargaInput = row.querySelector('input[name="harga[]"]');
        var selisihInput = row.querySelector('input[name="selisih[]"]');
        var lebihInput = row.querySelector('input[name="lebih[]"]');
        var nominalhilangInput = row.querySelector('input[name="nominalhilang[]"]');
        var nominallebihInput = row.querySelector('input[name="nominallebih[]"]');

        var realValue = parseFloat(realInput.value) || 0;
        var stokValue = parseFloat(stokInput.value) || 0;
        var hargaValue = parseFloat(hargaInput.value) || 0;

        if (realValue < stokValue) {
            selisihInput.value = stokValue - realValue;
            lebihInput.value = 0;
            nominalhilangInput.value = (stokValue - realValue) * hargaValue;
            nominallebihInput.value = 0;
        } else {
            selisihInput.value = 0;
            lebihInput.value = realValue - stokValue;
            nominalhilangInput.value = 0;
            nominallebihInput.value = (realValue - stokValue) * hargaValue;
        }
    }

    // Add an event listener to the lokasi select element
    document.querySelector('select[name="lokasi"]').addEventListener('change', function() {
        updateStok();
    });
</script>

<?= $this->endSection(); ?>