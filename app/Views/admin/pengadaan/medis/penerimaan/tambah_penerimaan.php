<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<!-- Card Section -->
<div class="max-w-[85rem] py-6 lg:py-3 px-8 mx-auto">
    <!-- Card -->
    <div class="bg-white rounded-xl shadow p-4 sm:p-7 dark:bg-slate-900">
        <div class="mb-8">
            <h2 class="text-xl font-bold text-gray-800 dark:text-gray-200">
                Tambah Penerimaan Barang Medis
            </h2>

        </div>

        <form action="/penerimaanmedis/submittambah" id="formContainer" method="post" onsubmit="return validateForm()">
            <?= csrf_field() ?>
            <!-- Grid -->

            <div class="sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Nomor Faktur</label>
                <input type="text" name="nofaktur" value="<?php function generateUniqueNumber($length = 16)
                                                            {
                                                                $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                                                                $charactersLength = strlen($characters);
                                                                $randomString = '';

                                                                $uniqueLength = $length - 11;

                                                                if ($uniqueLength > 0) {
                                                                    for ($i = 0; $i < $uniqueLength; $i++) {
                                                                        $randomString .= $characters[rand(0, $charactersLength - 1)];
                                                                    }
                                                                } else {
                                                                    return "Panjang maksimal terlalu pendek.";
                                                                }

                                                                return $randomString;
                                                            }

                                                            $tanggalHariIni = date('Ymd');

                                                            $nomorFaktur = "INV" . $tanggalHariIni . generateUniqueNumber();
                                                            echo $nomorFaktur; ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white">
            </div>
            <div class="mt-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Nomor Pemesanan</label>
                <input type="text" name="nopemesanan" value="" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" required>

            </div>
            <div class="mt-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Tanggal Penerimaan</label>
                <input type="date" id="tglpenerimaan" name="tglpenerimaan" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" required>
            </div>

            <div class="mt-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Tanggal Faktur</label>
                <input type="date" id="tglfaktur" name="tglfaktur" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" required>
            </div>

            <div class="mt-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Tanggal Jatuh Tempo</label>
                <input type="date" id="tgljatuhtempo" name="tgljatuhtempo" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" required>
            </div>

            <div class="mt-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Supplier</label>
                <select name="supplier" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" required>
                    <option value="" selected>-</option>
                    <?php foreach ($supplier_data as $supplier) : ?>
                        <option value="<?= $supplier['id'] ?>"><?= $supplier['nama'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mt-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Pegawai</label>
                <select name="pegawaipenerimaan" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" required>
                    <option value="" selected>-</option>
                    <?php foreach ($pegawai_data as $pegawai) : ?>
                        <option value="<?= $pegawai['id'] ?>"><?= $pegawai['nama'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mt-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Lokasi</label>
                <select name="idruangan" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" required>
                    <option value="" selected>-</option>
                    <?php foreach ($ruangan_data as $ruangan) : ?>
                        <option value="<?= $ruangan['id'] ?>"><?= $ruangan['nama'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>


            <!-- End Grid -->
            <div class="mt-5 flex flex-col">
                <div class="-m-1.5 overflow-x-auto">
                    <div class="p-1.5 min-w-full inline-block align-middle">
                        <div class="border-t-[1px] flex justify-between p-2 text-sm text-gray-600 dark:text-neutral-500">
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
                        <div class="border rounded-lg overflow-hidden dark:border-neutral-700">
                            <table class="pt-5 min-w-full divide-y divide-gray-200 dark:divide-neutral-700" id="item-list">
                                <?php 
                                    $widths  = [4, 4, 4, 12, 5, 4, 8, 8, 8, 4, 8, 6, 6];
                                    echo view('components/data_tabel_colgroup',['widths' => $widths]);
                                    
                                    // $columns = [
                                    //     'Jumlah',
                                    //     'Satuan Beli',
                                    //     'Nama Barang',
                                    //     'Satuan',
                                    //     'G',
                                    //     'Kadaluarsa',
                                    //     'Harga',
                                    //     'Subtotal',
                                    //     'Diskon (%)',
                                    //     'Diskon (Rp)',
                                    //     'Total',
                                    //     'No Batch'
                                    // ];
                                    // echo view('components/data_tabel_thead',['columns' => $columns]);
                                ?>

                                <thead class="bg-[#DCDCDC]">
                                    <tr class="bg-navy disabled">
                                        <th class="px-1 py-1 text-center"></th>
                                        <th class="px-1 py-1 text-center">Jlh</th>
                                        <th class="px-1 py-1 text-center">Sat Beli</th>
                                        <th class="px-1 py-1">Nama Barang</th>
                                        <th class="px-1 py-1 text-center">Satuan</th>
                                        <th class="px-1 py-1 text-center">G</th>
                                        <th class="px-1 py-1 text-center">Kadaluwarsa</th>
                                        <th class="px-1 py-1 text-center">Harga</th>
                                        <th class="px-1 py-1 text-center">Subtotal</th>
                                        <th class="px-1 py-1 text-center">Disk(%)</th>
                                        <th class="px-1 py-1 text-center">Disk(Rp)</th>
                                        <th class="px-1 py-1 text-center">Total</th>
                                        <th class="px-1 py-1 text-center">No Batch</th>
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
                                            <input type="number" min="0" value="" class="text-center w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#FDFDFD]" step="any" name="jumlah_pesanan[]" required />
                                        </td>
                                        <td class="align-middle p-1">
                                            <select name="satuanbeli[]" class="w-full py-[1.5px] border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#FDFDFD] text-center" required>
                                                <option value="" selected></option>
                                                <?php foreach ($satuan_data as $satuan) : ?>
                                                    <option value="<?= $satuan['id'] ?>"><?= $satuan['nama'] ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </td>
                                        <td class="align-middle p-1">
                                            <input type="hidden" step="any" value="" class="text-center w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#FDFDFD]" name="" />
                                            <select name="idbrgmedis[]" class="w-full py-[1.5px] border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#FDFDFD] text-center" onchange="updateFields(this)" required>
                                                <option value="" selected></option>
                                                <?php foreach ($medis_data as $brgmedis) : ?>
                                                    <option value="<?= $brgmedis['id'] ?>" data-satuan="<?= $brgmedis['id_satbesar'] ?>" data-harga="<?= $brgmedis['h_beli'] ?>"><?= $brgmedis['nama'] ?></option>
                                                <?php endforeach; ?>
                                            </select>

                                        </td>
                                        <td class="align-middle p-1">
                                            <input type="hidden" step="any" value="" class="satuanid-input text-center w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#FDFDFD]" name="satuan[]" required />
                                            <input type="text" step="any" value="" class="satuan-input text-center w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#FDFDFD]" name="" required />
                                        </td>

                                        <td class="align-middle p-1 text-center">
                                            <input type="checkbox" name="ubahmaster[]" value="1" class="shrink-0 mt-0.5 border-gray-200 rounded text-blue-600 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800">
                                        </td>
                                        <td class="align-middle p-1 text-center">
                                            <input type="date" value="" class="text-center w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#FDFDFD]" name="kadaluwarsa[]" />
                                        </td>
                                        <td class="align-middle p-1 text-center">
                                            <input type="number" min="0" step="0.01" step="0.01" class="harga-input text-center w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#FDFDFD]" name="harga_satuan_pemesanan[]" required />
                                        </td>
                                        <td class="align-middle p-1 text-center">
                                            <input type="number" min="0" step="0.01" class="text-center w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#FDFDFD]" name="subtotalperitem[]" required />
                                        </td>
                                        <td class="align-middle p-1 text-center">
                                            <input type="number" min="0" step="0.01" class="text-center w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#FDFDFD]" name="diskonpersenperitem[]" required />
                                        </td>
                                        <td class="align-middle p-1 text-center">
                                            <input type="number" min="0" step="0.01" class="text-center w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#FDFDFD]" name="diskonjumlahperitem[]" required />
                                        </td>
                                        <td class="align-middle p-1 text-center">
                                            <input type="number" min="0" step="0.01" class="text-center w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#FDFDFD]" name="totalperitem[]" required />
                                        </td>
                                        <td class="align-middle p-1 text-center">
                                            <input type="text" class="text-center w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#FDFDFD]" name="no_batch[]" />
                                        </td>

                                    </tr>

                                </tbody>
                                <tfoot>
                                    <tr>



                                    </tr>
                                    <tr>
                                        <th class="p-1 text-right" colspan="12">Total (Sebelum Pajak)</th>
                                        <th class="p-1 text-right">
                                            <input type="number" min="0" value="" step="any" name="totalsblmpajak" class="w-full text-center border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#F6F6F6]" required>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th class="p-1 text-right" colspan="12">Pajak (%)
                                            <input type="number" min="0" max="100" value="0" step="any" name="pajakpersenpemesanan" class="text-center border w-[5%] rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#FDFDFD]" required>
                                        </th>
                                        <th class="p-1 text-right">

                                            <input type="number" min="0" value="0" class="w-full text-center border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#F6F6F6] cursor-default" name="pajakjumlahpemesanan" readonly required>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th class="p-1 text-right" colspan="12">Materai</th>
                                        <th class="p-1 text-right">
                                            <input type="number" min="0" value="0" class="w-full text-center border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#FDFDFD]" name="materaipemesanan" required>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th class="p-1 text-right" colspan="12">Total</th>
                                        <th class="p-1" id="total">
                                            <input type="hidden" value="" class=" border text-center rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#F6F6F6] cursor-default" name="totalpengajuan" readonly>
                                            <input type="number" min="0" value="" class="w-full border text-center rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#F6F6F6] cursor-default" name="totalpemesanan" readonly required>
                                        </th>
                                    </tr>
                                </tfoot>


                            </table>

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

<!-- End Card Section -->
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
                                            <input type="number" min="0" value="" class="text-center w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#FDFDFD]" step="any" name="jumlah_pesanan[]" required />
                                        </td>
                                        <td class="align-middle p-1">
                                            <select name="satuanbeli[]" class="w-full py-[1.5px] border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#FDFDFD] text-center" required>
                                                <option value="" selected></option>
                                                <?php foreach ($satuan_data as $satuan) : ?>
                                                    <option value="<?= $satuan['id'] ?>"><?= $satuan['nama'] ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </td>
                                        <td class="align-middle p-1">
                                            <input type="hidden" step="any" value="" class="text-center w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#FDFDFD]" name="" />
                                            <select name="idbrgmedis[]" class="w-full py-[1.5px] border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#FDFDFD] text-center" onchange="updateFields(this)" required>
                                                <option value="" selected></option>
                                                <?php foreach ($medis_data as $brgmedis) : ?>
                                                    <option value="<?= $brgmedis['id'] ?>" data-satuan="<?= $brgmedis['id_satbesar'] ?>" data-harga="<?= $brgmedis['h_beli'] ?>"><?= $brgmedis['nama'] ?></option>
                                                <?php endforeach; ?>
                                            </select>

                                        </td>
                                        <td class="align-middle p-1">
                                            <input type="hidden" step="any" value="" class="satuanid-input text-center w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#FDFDFD]" name="satuan[]" required />
                                            <input type="text" step="any" value="" class="satuan-input text-center w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#FDFDFD]" name="" required />
                                        </td>

                                        <td class="align-middle p-1 text-center">
                                            <input type="checkbox" name="ubahmaster[]" value="1" class="shrink-0 mt-0.5 border-gray-200 rounded text-blue-600 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800">
                                        </td>
                                        <td class="align-middle p-1 text-center">
                                            <input type="date" value="" class="text-center w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#FDFDFD]" name="kadaluwarsa[]" />
                                        </td>
                                        <td class="align-middle p-1 text-center">
                                            <input type="number" min="0" step="0.01" step="0.01" class="harga-input text-center w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#FDFDFD]" name="harga_satuan_pemesanan[]" required />
                                        </td>
                                        <td class="align-middle p-1 text-center">
                                            <input type="number" min="0" step="0.01" class="text-center w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#FDFDFD]" name="subtotalperitem[]" required />
                                        </td>
                                        <td class="align-middle p-1 text-center">
                                            <input type="number" min="0" step="0.01" class="text-center w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#FDFDFD]" name="diskonpersenperitem[]" required />
                                        </td>
                                        <td class="align-middle p-1 text-center">
                                            <input type="number" min="0" step="0.01" class="text-center w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#FDFDFD]" name="diskonjumlahperitem[]" required />
                                        </td>
                                        <td class="align-middle p-1 text-center">
                                            <input type="number" min="0" step="0.01" class="text-center w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#FDFDFD]" name="totalperitem[]" required />
                                        </td>
                                        <td class="align-middle p-1 text-center">
                                            <input type="text" class="text-center w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#FDFDFD]" name="no_batch[]" />
                                        </td>
        `;

        table.appendChild(newRow);
        var newJumlahPesananInput = newRow.querySelector('input[name="jumlah_pesanan[]"]');
        var newHargaSatuanPengajuanInput = newRow.querySelector('input[name="harga_satuan_pemesanan[]"]');
        var newDiskonPersenInput = newRow.querySelector('input[name="diskonpersenperitem[]"]');

        var newIndex = jumlahPesananInputs.length; // Get the new index

        newJumlahPesananInput.addEventListener('input', function() {
            hitungSubTotal(newIndex);
            hitungDiskon(newIndex);
            hitungTotalPerItem(newIndex);
            hitungTotalSblmPajak();
            hitungPajak();
        });

        newHargaSatuanPengajuanInput.addEventListener('input', function() {
            hitungSubTotal(newIndex);
            hitungDiskon(newIndex);
            hitungTotalPerItem(newIndex);
            hitungTotalSblmPajak();
            hitungPajak();
        });

        newDiskonPersenInput.addEventListener('input', function() {
            hitungDiskon(newIndex);
            hitungTotalPerItem(newIndex);
            hitungTotalSblmPajak();
            hitungPajak();
        });

        // Update the input collections to include the new inputs
        jumlahPesananInputs = document.querySelectorAll('input[name="jumlah_pesanan[]"]');
        hargaSatuanPengajuanInputs = document.querySelectorAll('input[name="harga_satuan_pemesanan[]"]');
        subtotalInputs = document.querySelectorAll('input[name="subtotalperitem[]"]');
        diskonPersenInput = document.querySelectorAll('input[name="diskonpersenperitem[]"]');
        diskonJumlahInput = document.querySelectorAll('input[name="diskonjumlahperitem[]"]');
        totalperitemInputs = document.querySelectorAll('input[name="totalperitem[]"]');
    }


    function removeRow(btn) {
        var row = btn.parentNode.parentNode;
        row.parentNode.removeChild(row);
    }

    var satuanData = <?= json_encode($satuan_data); ?>;

    function updateFields(selectElement) {
        const selectedOption = selectElement.options[selectElement.selectedIndex];

        // Get the data attributes
        const satuan = selectedOption.getAttribute('data-satuan');
        const harga = selectedOption.getAttribute('data-harga');

        // Find the corresponding input fields
        const row = selectElement.closest('td').parentNode;
        const satuanIdInput = row.querySelector('.satuanid-input');
        const satuanInput = row.querySelector('.satuan-input');
        const hargaInput = row.querySelector('.harga-input');

        // Update the input fields
        satuanIdInput.value = satuan;
        satuanInput.value = getSatuanName(satuan); // Assuming you want to display the same value in the text input
        hargaInput.value = harga;

        // Get the index of the current row
        const index = Array.from(document.querySelectorAll('select[name="idbrgmedis[]"]')).indexOf(selectElement);

        // Trigger recalculation for this row
        hitungSubTotal(index);
        hitungDiskon(index);
        hitungTotalPerItem(index);
        hitungTotalSblmPajak();
        hitungPajak();
    }

    function getSatuanName(id) {
        for (var key in satuanData) {
            if (satuanData[key]['id'] == id) {
                return satuanData[key]['nama'];
            }
        }
        return '';
    }

    var jumlahPesananInputs = document.querySelectorAll('input[name="jumlah_pesanan[]"]');
    var hargaSatuanPengajuanInputs = document.querySelectorAll('input[name="harga_satuan_pemesanan[]"]');
    var subtotalInputs = document.querySelectorAll('input[name="subtotalperitem[]"]');
    var diskonPersenInput = document.querySelectorAll('input[name="diskonpersenperitem[]"]');
    var diskonJumlahInput = document.querySelectorAll('input[name="diskonjumlahperitem[]"]');
    var totalperitemInputs = document.querySelectorAll('input[name="totalperitem[]"]');
    var totalSblmPajakInputs = document.querySelector('input[name="totalsblmpajak"]');
    var totalKeseluruhanInputs = document.querySelector('input[name="totalpemesanan"]');
    var pajakPersenInput = document.querySelector('input[name="pajakpersenpemesanan"]');
    var pajakJumlahInput = document.querySelector('input[name="pajakjumlahpemesanan"]');
    var materaiInput = document.querySelector('input[name="materaipemesanan"]');

    function hitungSubTotal(index) {
        var jumlahPesanan = parseFloat(jumlahPesananInputs[index].value) || 0;
        var hargaSatuanPengajuan = parseFloat(hargaSatuanPengajuanInputs[index].value) || 0;
        var total = jumlahPesanan * hargaSatuanPengajuan;
        subtotalInputs[index].value = total.toFixed(2); // Atur jumlah desimal yang diinginkan

        hitungDiskon(index);
    }

    function hitungDiskon(index) {
        var diskonPersen = parseFloat(diskonPersenInput[index].value) || 0;
        var subtotal = parseFloat(subtotalInputs[index].value) || 0;
        var diskonJumlah = subtotal * (diskonPersen / 100);
        diskonJumlahInput[index].value = diskonJumlah.toFixed(2);

        hitungTotalPerItem(index);
    }

    function hitungTotalPerItem(index) {
        var subtotal = parseFloat(subtotalInputs[index].value) || 0;
        var diskon = parseFloat(diskonJumlahInput[index].value) || 0;
        var totalperitem = subtotal - diskon;
        totalperitemInputs[index].value = totalperitem.toFixed(2);

        hitungTotalSblmPajak();
    }

    function hitungTotalSblmPajak() {
        var totalSblmPajak = 0;
        totalperitemInputs.forEach(function(input) {
            totalSblmPajak += parseFloat(input.value) || 0;
        });
        totalSblmPajakInputs.value = totalSblmPajak.toFixed(2);
        hitungPajak();
    }

    document.addEventListener('DOMContentLoaded', function() {
        hitungTotalSblmPajak();
    });

    function hitungPajak() {
        var totalSblmPajak = 0;
        totalperitemInputs.forEach(function(input) {
            totalSblmPajak += parseFloat(input.value) || 0;
        });

        var pajakPersen = parseFloat(pajakPersenInput.value) || 0;
        var pajakJumlah = totalSblmPajak * (pajakPersen / 100);
        pajakJumlahInput.value = pajakJumlah.toFixed(2);

        hitungTotalKeseluruhan();
    }

    jumlahPesananInputs.forEach(function(input, index) {
        input.addEventListener('input', function() {
            hitungSubTotal(index);
            hitungDiskon(index);
            hitungTotalPerItem(index);
            hitungTotalSblmPajak();
            hitungPajak();
        });
    });

    hargaSatuanPengajuanInputs.forEach(function(input, index) {
        input.addEventListener('input', function() {
            hitungSubTotal(index);
            hitungDiskon(index);
            hitungTotalPerItem(index);
            hitungTotalSblmPajak();
            hitungPajak();
        });
    });

    diskonPersenInput.forEach(function(input, index) {
        input.addEventListener('input', function() {
            hitungDiskon(index);
            hitungTotalPerItem(index);
            hitungTotalSblmPajak();
            hitungPajak();
        });
    });

    pajakPersenInput.addEventListener('input', function() {
        hitungPajak();
    });

    materaiInput.addEventListener('input', function() {
        hitungTotalKeseluruhan();
    });

    function hitungTotalKeseluruhan() {
        var totalSblmPajak = 0;
        totalperitemInputs.forEach(function(input) {
            totalSblmPajak += parseFloat(input.value) || 0;
        });

        var pajakPersen = parseFloat(pajakPersenInput.value) || 0;
        var pajakJumlah = totalSblmPajak * (pajakPersen / 100);

        var materai = parseFloat(materaiInput.value) || 0;
        var totalKeseluruhan = totalSblmPajak + pajakJumlah + materai;
        totalKeseluruhanInputs.value = totalKeseluruhan.toFixed(2);
    }
</script>
<?= $this->endSection(); ?>