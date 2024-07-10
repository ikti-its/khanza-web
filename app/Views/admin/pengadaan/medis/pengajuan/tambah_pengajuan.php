<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<!-- Card Section -->
<div class="max-w-[85rem] py-6 lg:py-3 mx-auto">
    <!-- Card -->
    <div class="bg-white rounded-xl shadow p-4 sm:p-7 dark:bg-slate-900">
        <div class="mb-8">
            <h2 class="text-xl font-bold text-gray-800 dark:text-gray-200">
                Tambah Pengajuan Barang Medis
            </h2>

        </div>

        <form action="/submittambahpengajuanmedis" id="myForm" method="post" onsubmit="return validateForm()">
            <?= csrf_field() ?>
            <input type="hidden" value="0" name="status">
            <div class="sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Nomor Pengajuan</label>
                <input type="text" name="nopengajuan" value="<?php function generateUniqueNumber($length = 16)
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

                                                                $nomorPengajuan = "PBM" . $tanggalHariIni . generateUniqueNumber();
                                                                echo $nomorPengajuan; ?>" class="border bg-[#F6F6F6] text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" readonly>
            </div>
            <div class="mt-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Tanggal Pengajuan</label>
                <input type="date" id="tglpengajuan" name="tglpengajuan" class="border border-gray-300 text-gray-900 text-sm rounded-lg  p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" required>

            </div>
            <div id="dateError" class="mt-2 hidden">
                <label class="text-sm text-gray-900 dark:text-white md:w-1/4"></label>
                <div class="flex items-center text-red-500 text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none">
                        <path d="M7 5.25V8.16667" stroke="#DA4141" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M7 12.4891H3.465C1.44083 12.4891 0.595001 11.0424 1.575 9.27492L3.395 5.99658L5.11 2.91658C6.14834 1.04408 7.85167 1.04408 8.89 2.91658L10.605 6.00242L12.425 9.28075C13.405 11.0482 12.5533 12.4949 10.535 12.4949H7V12.4891Z" stroke="#DA4141" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M6.99707 9.91675H7.00231" stroke="#DA4141" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg> Tanggal pengajuan harus hari ini atau setelah hari ini.
                </div>
            </div>

            <div class="mt-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Pegawai</label>
                <select name="pegawai" class="border border-gray-300 text-gray-900 text-sm rounded-lg  p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" required>
                    <option value="">-</option>
                    <?php foreach ($pegawai_data as $pegawai) : ?>
                        <option value="<?= $pegawai['id'] ?>"><?= $pegawai['nama'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mt-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Catatan</label>
                <input type="text" name="catatan" class="border border-gray-300 text-gray-900 text-sm rounded-lg  p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white">
            </div>
            <!-- Grid -->
            <!-- <input name="statusapoteker" type="hidden" value="Ditolak">
            <input name="statuskeuangan" type="hidden" value="Ditolak">
            <input name="statuspersetujuan" type="hidden" value="Ditolak"> -->

            <!-- End Grid -->
            <div class="mt-5 flex flex-col">
                <div class="-m-1.5 overflow-x-auto">
                    <div class="p-1.5 min-w-full inline-block align-middle">
                        <div class="border-t-[1px] border-x-0 border-b-0 overflow-hidden dark:border-neutral-700">
                            <div class="flex justify-between p-2 text-sm text-gray-600 dark:text-neutral-500">
                                <div class="inline-flex items-center text-[1.25rem] font-[400] leading-[normal] tracking-[0.00625rem]">
                                    Pesanan
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

                                    <colgroup>
                                        <col width="5%">
                                        <col width="7%">
                                        <col width="30%">
                                        <col width="18%">
                                        <col width="20%">
                                        <col width="20%">
                                    </colgroup>
                                    <thead class="bg-[#DCDCDC]">
                                        <tr>
                                            <th class="px-1 py-1 text-center"></th>
                                            <th class="px-1 py-1 text-[0.9375rem] leading-[normal] tracking-[0.00469rem] text-center">Jumlah</th>
                                            <th class="px-1 py-1 text-[0.9375rem] leading-[normal] tracking-[0.00469rem] text-center">Barang</th>
                                            <th class="px-1 py-1 text-[0.9375rem] leading-[normal] tracking-[0.00469rem] text-center">Satuan</th>
                                            <th class="px-1 py-1 text-[0.9375rem] leading-[normal] tracking-[0.00469rem] text-center">Harga Pengajuan</th>
                                            <th class="px-1 py-1 text-[0.9375rem] leading-[normal] tracking-[0.00469rem] text-center">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 dark:divide-neutral-700">
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
                                                <input type="number" min="0" class="rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#FDFDFD] text-center w-full border" step="any" name="jumlah_pesanan[]" />
                                            </td>
                                            <td class="align-middle p-1">
                                                <select name="idbrgmedis[]" class="w-full py-[1.5px] border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#FDFDFD] text-center" required>
                                                    <option value="" selected></option>
                                                    <?php foreach ($barang_medis as $brgmedis) : ?>
                                                        <option value="<?= $brgmedis['id'] ?>" data-harga="<?= $brgmedis['harga'] ?>"><?= $brgmedis['nama'] ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </td>
                                            <td class="align-middle p-1">
                                                <select name="satuanbrgmedis[]" class="w-full py-[1.5px] border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#FDFDFD] text-center">
                                                    <option value="" selected></option>
                                                    <?php foreach ($satuan_data as $satuan) : ?>
                                                        <option value="<?= $satuan['id'] ?>"><?= $satuan['nama'] ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </td>
                                            <td class="align-middle p-1">
                                                <input type="number" min="0" step="any" class="rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#FDFDFD] text-center w-full border" name="harga_satuan_pengajuan[]" />
                                            </td>
                                            <td class="align-middle p-1 text-right">
                                                <input type="number" min="0" class="rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#F6F6F6] cursor-default text-center w-full border" name="subtotalperitem[]" readonly />
                                            </td>
                                        </tr>

                                    </tbody>
                                    <tfoot>
                                        <!-- <tr class="pt-5">
                                            <th class="p-1 pt-2" style="text-align: right;" colspan="5">

                                                Diskon (%)
                                                <input type="number" min="0" step="any" name="diskonpersen" class="border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#FDFDFD] text-center" style="width: 20%;">
                                            </th>

                                            <th class="p-1 pt-2 text-right">
                                                <input type="number" min="0" class="w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#F6F6F6] cursor-default text-center" name="diskonjumlah" readonly>
                                            </th>
                                        </tr>

                                        <tr>
                                            <th class="p-1" style="text-align: right;" colspan="5">Pajak (%)
                                                <input type="number" min="0" step="any" name="pajakpersen" class="border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#FDFDFD] text-center" style="width: 20%;">
                                            </th>

                                            <th class="p-1 text-right">
                                                <input type="number" min="0" class="w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#F6F6F6] cursor-default text-center" name="pajakjumlah" readonly>
                                            </th>
                                        </tr>
                                        <tr>
                                            <th class="p-1" style="text-align: right;" colspan="5">Materai</th>
                                            <th class="p-1 text-right">
                                                <input type="number" min="0" class="w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#FDFDFD] text-center" name="materai">
                                            </th>
                                        </tr> -->
                                        <tr>
                                            <th class="p-1" style="text-align: right;" colspan="5">Total Keseluruhan</th>
                                            <th class="p-1"><input type="number" min="0" class="w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#F6F6F6] cursor-default text-center" name="totalkeseluruhan" readonly></th>
                                        </tr>
                                    </tfoot>
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

<!-- End Card Section -->
<script>
    var jumlahPesananInputs = document.querySelectorAll('input[name="jumlah_pesanan[]"]');
    var hargaSatuanPengajuanInputs = document.querySelectorAll('input[name="harga_satuan_pengajuan[]"]');
    var totalInputs = document.querySelectorAll('input[name="subtotalperitem[]"]');
    var totalKeseluruhanInputs = document.querySelector('input[name="totalkeseluruhan"]');
    var diskonPersenInput = document.querySelector('input[name="diskonpersen"]');
    var diskonJumlahInput = document.querySelector('input[name="diskonjumlah"]');
    var pajakPersenInput = document.querySelector('input[name="pajakpersen"]');
    var pajakJumlahInput = document.querySelector('input[name="pajakjumlah"]');
    var materaiInput = document.querySelector('input[name="materai"]');

    function hitungTotal(index) {
        var jumlahPesanan = jumlahPesananInputs[index].value || 0;
        var hargaSatuanPengajuan = hargaSatuanPengajuanInputs[index].value || 0;
        var total = jumlahPesanan * hargaSatuanPengajuan;
        totalInputs[index].value = total.toFixed(0); // Atur jumlah desimal yang diinginkan

        hitungTotalKeseluruhan();
    }

    jumlahPesananInputs.forEach(function(input, index) {
        input.addEventListener('input', function() {
            hitungTotal(index);
        });
    });

    hargaSatuanPengajuanInputs.forEach(function(input, index) {
        input.addEventListener('input', function() {
            hitungTotal(index);
        });
    });


    function hitungTotalKeseluruhan() {
        var totalSemua = 0;
        totalInputs.forEach(function(input) {
            totalSemua += parseFloat(input.value) || 0;
        });

        var totalKeseluruhan = totalSemua;
        totalKeseluruhanInputs.value = totalKeseluruhan.toFixed(0);
    }

    // Fungsi untuk mengecek duplikat saat perubahan
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('select[name="idbrgmedis[]"]').forEach(function(select) {
            select.addEventListener('change', function() {
                if (isDuplicate(select)) {
                    alert('Barang medis ini sudah dipilih. Pilih barang medis lain.');
                    select.value = "";
                }
            });
        });
    });

    // Fungsi untuk menambah baris baru
    function addRow() {
        var newRow = '<tr>' +
            '<td>' +
            '<button type="button" class="flex justify-center p-2" onclick="removeRow(this)">' +
            '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">' +
            '<path d="M16 0H4C1.79086 0 0 1.79086 0 4V16C0 18.2091 1.79086 20 4 20H16C18.2091 20 20 18.2091 20 16V4C20 1.79086 18.2091 0 16 0Z" fill="#0A2D27" />' +
            '<path d="M15 10.625H5C4.65833 10.625 4.375 10.3417 4.375 10C4.375 9.65833 4.65833 9.375 5 9.375H15C15.3417 9.375 15.625 9.65833 15.625 10C15.625 10.3417 15.3417 10.625 15 10.625Z" fill="#ACF2E7" />' +
            '</svg>' +
            '</button>' +
            '</td>' +
            '<td class="align-middle p-1 text-center">' +
            '<input type="number" min="0" class="rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#FDFDFD] text-center w-full border" step="any" name="jumlah_pesanan[]" />' +
            '</td>' +
            '<td class="align-middle p-1">' +
            '<select name="idbrgmedis[]" class="w-full py-[1.5px] border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#FDFDFD] text-center" required>' +
            '<option value="" selected></option>' +
            '<?php foreach ($barang_medis as $brgmedis) : ?>' +
            '<option value="<?= $brgmedis['id'] ?>" data-harga="<?= $brgmedis['harga'] ?>"><?= $brgmedis['nama'] ?></option>' +
            '<?php endforeach; ?>' +
            '</select>' +
            '</td>' +
            '<td class="align-middle p-1">' +
            '<select name="satuanbrgmedis[]" class="w-full py-[1.5px] border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#FDFDFD] text-center">' +
            '<option value="" selected></option>' +
            '<?php foreach ($satuan_data as $satuan) : ?>' +
            '<option value="<?= $satuan['id'] ?>"><?= $satuan['nama'] ?></option>' +
            '<?php endforeach; ?>' +
            '</select>' +
            '</td>' +
            '<td class="align-middle p-1">' +
            '<input type="text" step="any" class="rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#FDFDFD] text-center w-full border" name="harga_satuan_pengajuan[]" />' +
            '</td>' +
            '<td class="align-middle p-1 text-right">' +
            '<input type="text" class="rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#F6F6F6] cursor-default text-center w-full border" name="subtotalperitem[]" readonly />' +
            '</td>' +
            '</tr>';
        document.getElementById('item-list').getElementsByTagName('tbody')[0].insertAdjacentHTML('beforeend', newRow);

        // Perbarui variabel setelah menambah baris baru
        jumlahPesananInputs = document.querySelectorAll('input[name="jumlah_pesanan[]"]');
        hargaSatuanPengajuanInputs = document.querySelectorAll('input[name="harga_satuan_pengajuan[]"]');
        totalInputs = document.querySelectorAll('input[name="subtotalperitem[]"]');

        // Tambahkan event listener untuk setiap input jumlah_pesanan[] dan harga_satuan_pengajuan[] yang baru
        jumlahPesananInputs.forEach(function(input, index) {
            input.addEventListener('input', function() {
                hitungTotal(index);
            });
        });

        hargaSatuanPengajuanInputs.forEach(function(input, index) {
            input.addEventListener('input', function() {
                hitungTotal(index);
            });
        });

        var newSelectInputs = document.querySelectorAll('select[name="idbrgmedis[]"]');

        newSelectInputs[newSelectInputs.length - 1].addEventListener('change', function() {
            if (isDuplicate(this)) {
                alert('Barang medis ini sudah dipilih. Pilih barang medis lain.');
                this.value = "";
            }
        });

        newJumlahPesananInput[newJumlahPesananInput.length - 1].addEventListener('input', function() {
            hitungTotal(newJumlahPesananInput.length - 1);
        });

        newHargaSatuanPengajuanInput[newHargaSatuanPengajuanInput.length - 1].addEventListener('input', function() {
            hitungTotal(newHargaSatuanPengajuanInput.length - 1);
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

    document.getElementById('tglpengajuan').addEventListener('input', function() {
        var tglpengajuanInput = document.getElementById('tglpengajuan');
        var dateError = document.getElementById('dateError');
        var selectedDate = new Date(tglpengajuanInput.value);
        var today = new Date();
        today.setHours(0, 0, 0, 0); // Clear the time part

        if (selectedDate <= today) {
            tglpengajuanInput.classList.add('border-red-500');
            dateError.classList.remove('hidden');
            dateError.classList.add('flex', 'items-center');
        } else {
            tglpengajuanInput.classList.remove('border-red-500');
            dateError.classList.add('hidden');
            dateError.classList.remove('block');
        }
    });

    function validateForm() {
        var tglpengajuanInput = document.getElementById('tglpengajuan');
        var dateError = document.getElementById('dateError');
        var selectedDate = new Date(tglpengajuanInput.value);
        var today = new Date();
        today.setHours(0, 0, 0, 0); // Clear the time part

        if (selectedDate <= today) {
            tglpengajuanInput.classList.add('border-red-500');
            dateError.classList.remove('hidden');
            dateError.classList.add('block');
            alert("Tanggal pengajuan harus hari ini atau setelah hari ini.");
            return false;
        }
        var submitButton = document.getElementById('submitButton');
        submitButton.setAttribute('disabled', true);
        // Ubah teks tombol menjadi sesuatu yang menunjukkan proses sedang berlangsung, misalnya "Menyimpan..."
        submitButton.innerHTML = 'Menyimpan...';
        return true;
    }
</script>
<?= $this->endSection(); ?>