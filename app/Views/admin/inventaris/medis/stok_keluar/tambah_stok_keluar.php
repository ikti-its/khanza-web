<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<!-- Card Section -->
<div class="max-w-[85rem] py-6 mx-auto">
    <!-- Card -->
    <div class="bg-white rounded-xl shadow p-4 sm:p-7 dark:bg-slate-900">
        <div class="mb-8">
            <h2 class="text-xl font-bold text-gray-800 dark:text-gray-200">
                Tambah Stok Keluar Barang Medis
            </h2>

        </div>

        <form action="/stokkeluarmedis/submittambah" id="formId" method="post">
            <!-- Grid -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Tanggal Stok Keluar</label>
                <input type="date" name="tglkeluar" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" required>
            </div>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">No Keluar</label>
                <input type="text" name="nokeluar" value="<?php function generateUniqueNumber($length = 16)
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

                                                                $nomorKeluar = "OB" . $tanggalHariIni . generateUniqueNumber();
                                                                echo $nomorKeluar; ?>" class="border bg-[#F6F6F6] cursor-default text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" required readonly>
            </div>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Pegawai</label>
                <select name="pegawaistokkeluar" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" required>
                    <option value="">-</option>
                    <?php foreach ($pegawai_data as $pegawai) : ?>
                        <option value="<?= $pegawai['id'] ?>"><?= $pegawai['nama'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Asal Ruangan</label>
                <select name="asalruangan" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" required>
                    <option selected>-</option>
                    <option value="1000">VIP 1</option>
                    <option value="2000">VIP 2</option>
                    <option value="3000">VVIP 1</option>
                    <option value="4000">VVIP 2</option>
                    <option value="5000">Gudang</option>
                </select>
            </div>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Tujuan Ruangan</label>
                <select name="tujuanruangan" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" required>
                    <option selected>-</option>
                    <option value="1000">VIP 1</option>
                    <option value="2000">VIP 2</option>
                    <option value="3000">VVIP 1</option>
                    <option value="4000">VVIP 2</option>
                    <option value="5000">Gudang</option>
                </select>
            </div>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Keterangan</label>
                <input type="text" name="keteranganstokkeluar" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white">
            </div>

            <!-- End Grid -->
            <div class="mt-5 flex flex-col">
                <div class="-m-1.5 overflow-x-auto">
                    <div class="p-1.5 min-w-full inline-block align-middle">
                        <div class="border rounded-lg overflow-hidden dark:border-neutral-700">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700" id="item-list">
                                <colgroup>
                                    <col width="5%">
                                    <col width="25%">
                                    <col width="15%">
                                    <col width="15%">
                                    <col width="20%">
                                    <col width="20%">
                                </colgroup>
                                <thead class="bg-[#DCDCDC]">
                                    <tr class="bg-navy disabled">
                                        <th class="text-center"></th>
                                        <th class="text-center">Barang</th>
                                        <th class="text-center">Stok saat ini</th>
                                        <th class="text-center">Jumlah keluar</th>
                                        <th class="text-center">No Faktur</th>
                                        <th class="text-center">No Batch</th>
                                    </tr>
                                </thead>
                                <tbody class="tabelbodypesanan divide-y divide-gray-200 dark:divide-neutral-700">
                                    <tr>
                                        <td class="align-middle px-1 text-center">
                                            <button type="button" class="flex justify-center p-2" onclick="removeRow(this)">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                                    <path d="M16 0H4C1.79086 0 0 1.79086 0 4V16C0 18.2091 1.79086 20 4 20H16C18.2091 20 20 18.2091 20 16V4C20 1.79086 18.2091 0 16 0Z" fill="#0A2D27" />
                                                    <path d="M15 10.625H5C4.65833 10.625 4.375 10.3417 4.375 10C4.375 9.65833 4.65833 9.375 5 9.375H15C15.3417 9.375 15.625 9.65833 15.625 10C15.625 10.3417 15.3417 10.625 15 10.625Z" fill="#ACF2E7" />
                                                </svg>
                                            </button>
                                        </td>
                                        <td class="align-middle px-1">
                                            <select name="idbrgmedis[]" class="py-[1.5px] w-full border text-center rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)]" onchange="updateStok(this)" required>
                                                <option value="" selected></option>
                                                <?php foreach ($medis_data as $brgmedis) { ?>
                                                    <option value="<?= $brgmedis['id'] ?>" data-stok="<?= $brgmedis['stok'] ?>">
                                                        <?= $brgmedis['nama'] ?>
                                                    </option>
                                                <?php } ?>

                                            </select>
                                        </td>
                                        <td class="align-middle px-1 text-center">
                                            <input type="number" class="text-center w-full border bg-[#F6F6F6] cursor-default rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)]" step="any" name="stoksaatini[]" readonly />
                                        </td>
                                        <td class="align-middle px-1 text-center">
                                            <input type="number" class="text-center w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)]" name="jlhkeluar[]" onchange="updateStok(this)" required/>
                                        </td>
                                        <td class="align-middle px-1 text-center">
                                            <input type="text" list="nofaktur" class="text-center w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)]" name="nofaktur[]" />
                                            <datalist id="nofaktur">
                                                <?php foreach ($penerimaan_data as $penerimaan) : ?>
                                                    <option value="<?= $penerimaan['no_faktur'] ?>"></option>
                                                <?php endforeach; ?>
                                            </datalist>

                                        </td>
                                        <td class="align-middle px-1 text-center">
                                            <input type="text" list="nobatch" class="text-center w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)]" name="nobatch[]" />
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
                                            <button type="button" onclick="addRow()" class="inline-flex items-center justify-center text-sm font-semibold tracking-[0.00625rem] rounded-lg border border-transparent w-[140px] h-[36px] bg-[#0A2D27] text-[#ACF2E7] disabled:opacity-50 disabled:pointer-events-none dark:text-blue-500 dark:hover:text-blue-400">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                                    <path d="M15 10.625H5C4.65833 10.625 4.375 10.3417 4.375 10C4.375 9.65833 4.65833 9.375 5 9.375H15C15.3417 9.375 15.625 9.65833 15.625 10C15.625 10.3417 15.3417 10.625 15 10.625Z" fill="#ACF2E7" />
                                                    <path d="M10 15.625C9.65833 15.625 9.375 15.3417 9.375 15V5C9.375 4.65833 9.65833 4.375 10 4.375C10.3417 4.375 10.625 4.65833 10.625 5V15C10.625 15.3417 10.3417 15.625 10 15.625Z" fill="#ACF2E7" />
                                                </svg>
                                                Tambah Baris
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
                <a href="javascript:history.back()" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-white dark:hover:bg-gray-800 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                    Kembali
                </a>
                <button type="submit" name="" id="submitButton" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-[#0A2D27] text-[#ACF2E7] disabled:opacity-50 disabled:pointer-events-none dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
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
        document.querySelectorAll('select[name="idbrgmedis[]"]').forEach(function(select) {
            select.addEventListener('change', function() {
                if (isDuplicate(select)) {
                    alert('Barang medis ini sudah dipilih. Pilih barang medis lain.');
                    select.value = "";
                } else {
                    updateStok(select);
                }
            });
        });
    });

    function addRow() {
        var newRow = '<tr>' +
            '<td class="align-middle px-1 text-center">' +
            '<button type="button" class="flex justify-center p-2" onclick="removeRow(this)">' +
            '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">' +
            '<path d="M16 0H4C1.79086 0 0 1.79086 0 4V16C0 18.2091 1.79086 20 4 20H16C18.2091 20 20 18.2091 20 16V4C20 1.79086 18.2091 0 16 0Z" fill="#0A2D27" />' +
            '<path d="M15 10.625H5C4.65833 10.625 4.375 10.3417 4.375 10C4.375 9.65833 4.65833 9.375 5 9.375H15C15.3417 9.375 15.625 9.65833 15.625 10C15.625 10.3417 15.3417 10.625 15 10.625Z" fill="#ACF2E7" />' +
            '</svg>' +
            '</button>' +
            '</td>' +
            '<td class="align-middle px-1">' +
            '<select name="idbrgmedis[]" class="py-[1.5px] w-full border text-center rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)]" onchange="updateStok(this)" required>' +
            '<option value="" selected></option>' +
            '<?php foreach ($medis_data as $brgmedis) : ?>' +
            '<option value="<?= $brgmedis['id'] ?>" data-stok="<?= $brgmedis['stok'] ?>"><?= $brgmedis['nama'] ?></option>' +
            '<?php endforeach; ?>' +
            '</select>' +
            '</td>' +
            '<td class="align-middle px-1 text-center">' +
            '<input type="number" class="text-center w-full border bg-[#F6F6F6] cursor-default rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)]" step="any" name="stoksaatini[]" readonly/>' +
            '</td>' +
            '<td class="align-middle px-1 text-center">' +
            '<input type="number" class="text-center w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)]" name="jlhkeluar[]" required/>' +
            '</td>' +
            '<td class="align-middle px-1 text-center">' +
            '<input type="text" list="nofaktur" class="text-center w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)]" name="nofaktur[]" />' +
            '<datalist id="nofaktur">' +
            '<?php foreach ($penerimaan_data as $penerimaan) : ?>' +
            '<option value="<?= $penerimaan['no_faktur'] ?>"></option>' +
            '<?php endforeach; ?>' +
            '</datalist>' +
            '</td>' +
            '<td class="align-middle px-1 text-center">' +
            '<input type="text" list="nobatch" class="text-center w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)]" name="nobatch[]" />' +
            '<datalist id="nobatch">' +
            '<?php foreach ($pesanan_data as $pesanan) : ?>' +
            '<option value="<?= $pesanan['no_batch'] ?>"></option>' +
            '<?php endforeach; ?>' +
            '</datalist>' +
            '</td>' +
            '</tr>';
        document.getElementById('item-list').getElementsByTagName('tbody')[0].insertAdjacentHTML('beforeend', newRow);
        var newSelectInputs = document.querySelectorAll('select[name="idbrgmedis[]"]');

        newSelectInputs[newSelectInputs.length - 1].addEventListener('change', function() {
            if (isDuplicate(this)) {
                alert('Barang medis ini sudah dipilih. Pilih barang medis lain.');
                this.value = "";
            } else {
                updateStok(this);
            }
        });
    }

    function isDuplicate(select) {
        var selectedValues = Array.from(document.querySelectorAll('select[name="idbrgmedis[]"]')).map(s => s.value);
        var currentValue = select.value;
        return selectedValues.filter(value => value === currentValue).length > 1;
    }

    function removeRow(btn) {
        var row = btn.parentNode.parentNode;
        row.parentNode.removeChild(row);
    }

    function updateStok(select) {
        if (isDuplicate(select)) {
            return;
        }

        // Get the selected option
        var selectedOption = select.options[select.selectedIndex];

        // Get the value of the 'data-stok' attribute
        var stok = parseInt(selectedOption.getAttribute('data-stok')) || 0; // Default to 0 if attribute is missing or not valid

        // Find the input field and set its value to the total stock
        var stokInput = select.closest('tr').querySelector('input[name="stoksaatini[]"]');
        stokInput.value = stok;

        // Optional: Validate the input of 'jlhkeluar[]'
        validateJumlahKeluar(select);
    }

    function validateJumlahKeluar(select) {
        // Get the input field for jumlah keluar
        var inputJumlahKeluar = select.closest('tr').querySelector('input[name="jlhkeluar[]"]');
        var jumlahKeluar = parseInt(inputJumlahKeluar.value) || 0; // Default to 0 if input value is not a number

        // Get the total stock from 'stoksaatini[]'
        var stokSaatIni = parseInt(select.closest('tr').querySelector('input[name="stoksaatini[]"]').value) || 0; // Default to 0 if value is not a number

        // Validate if jumlah keluar is greater than total stock
        if (jumlahKeluar > stokSaatIni) {
            // Display an error message or handle the validation as per your requirement
            alert('Jumlah keluar tidak boleh melebihi stok saat ini.');
            // Optionally, reset the value or take corrective action
            inputJumlahKeluar.value = ''; // Clear the input value or set to 0
            inputJumlahKeluar.focus(); // Optionally, focus back on the input field for correction
        }
        // Optionally, you can enable/disable submit button based on validation result
        // document.getElementById('submitBtn').disabled = (jumlahKeluar > stokSaatIni);
    }

    document.getElementById('formId').addEventListener('submit', function(event) {
        // Dapatkan semua input jumlah keluar
        var inputsJumlahKeluar = document.getElementsByName('jlhkeluar[]');


        var canSubmit = true;

        // Iterasi semua input jumlah keluar untuk melakukan validasi
        for (var i = 0; i < inputsJumlahKeluar.length; i++) {
            var inputJumlahKeluar = inputsJumlahKeluar[i];
            var jumlahKeluar = parseInt(inputJumlahKeluar.value) || 0; // Ambil nilai jumlah keluar atau default ke 0 jika kosong

            // Dapatkan nilai stok saat ini untuk input ini
            var stokSaatIni = parseInt(inputJumlahKeluar.closest('tr').querySelector('input[name="stoksaatini[]"]').value) || 0;

            // Validasi jika jumlah keluar lebih besar dari stok saat ini
            if (jumlahKeluar > stokSaatIni) {
                // Tampilkan pesan kesalahan
                alert('Jumlah keluar tidak boleh melebihi stok saat ini.');
                // Berhenti submit form
                event.preventDefault();
                // Set flag canSubmit menjadi false
                canSubmit = false;
                // Keluar dari loop karena sudah ada kesalahan
                break;
            }
        }
        if (canSubmit) {
            var submitButton = document.getElementById('submitButton');
            submitButton.setAttribute('disabled', true);
            submitButton.innerHTML = 'Menyimpan...';
        }

    });
</script>
<?= $this->endSection(); ?>