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

        <form action="/penerimaanmedis/submittambah" id="penerimaanform" method="post" onsubmit="return validateForm()">
            <?= csrf_field() ?>
            <!-- Grid -->
            <input type="hidden" value="<?= $pengajuan_data['id'] ?>" name="idpengajuan" class="text-center border mr-1">

            <input type="hidden" name="statuspesanan">

            <div class="sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Nomor Pemesanan</label>
                <input type="hidden" name="idpemesanan" value="<?= $pemesanan_data['id'] ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white">
                <input type="hidden" id="tglpesan" value="<?= $pemesanan_data['tanggal_pesan'] ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white">
                <input type="text" name="" value="<?= $pemesanan_data['no_pemesanan'] ?>" class="border bg-[#F6F6F6] cursor-default text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" required readonly>

            </div>
            <div class="mt-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Tanggal Penerimaan</label>
                <input type="date" id="tglpenerimaan" name="tglpenerimaan" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" required>
            </div>
            <div id="dateError1" class="mt-2 hidden">
                <label class="text-sm text-gray-900 dark:text-white md:w-1/4"></label>
                <div class="flex items-center text-red-500 text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none">
                        <path d="M7 5.25V8.16667" stroke="#DA4141" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M7 12.4891H3.465C1.44083 12.4891 0.595001 11.0424 1.575 9.27492L3.395 5.99658L5.11 2.91658C6.14834 1.04408 7.85167 1.04408 8.89 2.91658L10.605 6.00242L12.425 9.28075C13.405 11.0482 12.5533 12.4949 10.535 12.4949H7V12.4891Z" stroke="#DA4141" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M6.99707 9.91675H7.00231" stroke="#DA4141" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg> Tanggal penerimaan harus setelah tanggal pemesanan dan maksimal 14 hari dari pemesanan.

                </div>
            </div>
            <div class="mt-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Tanggal Faktur</label>
                <input type="date" id="tglfaktur" name="tglfaktur" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" required>
            </div>
            <div id="dateError2" class="mt-2 hidden">
                <label class="text-sm text-gray-900 dark:text-white md:w-1/4"></label>
                <div class="flex items-center text-red-500 text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none">
                        <path d="M7 5.25V8.16667" stroke="#DA4141" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M7 12.4891H3.465C1.44083 12.4891 0.595001 11.0424 1.575 9.27492L3.395 5.99658L5.11 2.91658C6.14834 1.04408 7.85167 1.04408 8.89 2.91658L10.605 6.00242L12.425 9.28075C13.405 11.0482 12.5533 12.4949 10.535 12.4949H7V12.4891Z" stroke="#DA4141" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M6.99707 9.91675H7.00231" stroke="#DA4141" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg> Tanggal faktur harus setelah tanggal pemesanan dan maksimal 14 hari dari pemesanan.
                </div>
            </div>
            <div class="mt-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Tanggal Jatuh Tempo</label>
                <input type="date" id="tgljatuhtempo" name="tgljatuhtempo" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" required>
            </div>
            <div id="dateError3" class="mt-2 hidden">
                <label class="text-sm text-gray-900 dark:text-white md:w-1/4"></label>
                <div class="flex items-center text-red-500 text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none">
                        <path d="M7 5.25V8.16667" stroke="#DA4141" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M7 12.4891H3.465C1.44083 12.4891 0.595001 11.0424 1.575 9.27492L3.395 5.99658L5.11 2.91658C6.14834 1.04408 7.85167 1.04408 8.89 2.91658L10.605 6.00242L12.425 9.28075C13.405 11.0482 12.5533 12.4949 10.535 12.4949H7V12.4891Z" stroke="#DA4141" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M6.99707 9.91675H7.00231" stroke="#DA4141" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg> Tanggal jatuh tempo harus setelah tanggal pemesanan dan maksimal 14 hari dari pemesanan.
                </div>
            </div>

            <div class="mt-5 sm:block md:flex items-center">
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
                                                            echo $nomorFaktur; ?>" class="border bg-[#F6F6F6] text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" readonly required>
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
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Ruangan</label>
                <select name="idruangan" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" required>
                    <option selected>-</option>
                    <option value="1000">VIP 1</option>
                    <option value="2000">VIP 2</option>
                    <option value="3000">VVIP 1</option>
                    <option value="4000">Apotek</option>
                    <option value="5000" selected>Gudang</option>
                </select>
            </div>


            <!-- End Grid -->
            <div class="mt-5 flex flex-col">
                <div class="-m-1.5 overflow-x-auto">
                    <div class="p-1.5 min-w-full inline-block align-middle">
                        <div class="border rounded-lg overflow-hidden dark:border-neutral-700">
                            <table class="pt-5 min-w-full divide-y divide-gray-200 dark:divide-neutral-700" id="item-list">
                                <?php 
                                    $widths  = [10, 21, 15, 18, 18, 18];
                                    echo view('components/data_tabel_colgroup',['widths' => $widths]);
                                    
                                    // $columns = [
                                    //     'Jumlah',
                                    //     'Barang',
                                    //     'Satuan',
                                    //     'Jumlah Diterima',
                                    //     'Kadaluwarsa',
                                    //     'No Batch'
                                    // ];
                                    // echo view('components/data_tabel_thead',['columns' => $columns]);
                                ?>
                                
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
                                            <input type="hidden" value="<?= $pesanan['subtotal_per_item'] ?>" class="text-center w-full" step="any" name="subtotalperitem[]" readonly />
                                            <input type="hidden" value="<?= $pesanan['total_per_item'] ?>" class="text-center w-full" step="any" name="totalperitem[]" readonly />
                                            <input type="hidden" value="<?= $pesanan['diskon_persen'] ?>" class="text-center w-full" step="any" name="diskonpersenperitem[]" readonly />
                                            <input type="hidden" value="<?= $pesanan['diskon_jumlah'] ?>" class="text-center w-full" step="any" name="diskonjumlahperitem[]" readonly />

                                            <td class="align-middle p-1 text-center">
                                                <input type="number" min="0" value="<?= $pesanan['jumlah_pesanan'] ?>" class="text-center w-full" step="any" name="jumlah_pesanan[]" readonly required />
                                            </td>
                                            <td class="align-middle p-1">
                                                <input type="hidden" step="any" value="<?= $pesanan['id_barang_medis'] ?>" class="text-center w-full" name="idbrgmedis[]" readonly required />
                                                <input type="text" step="any" value="<?php foreach ($medis_data as $medis) {
                                                                                            if ($medis['id'] === $pesanan['id_barang_medis']) {
                                                                                                echo $medis['nama'];
                                                                                            }
                                                                                        } ?>" class="text-center w-full" name="" readonly />

                                            </td>
                                            <td class="align-middle p-1">
                                                <input type="hidden" step="any" value="<?= $pesanan['satuan'] ?>" class="text-center w-full" name="satuan[]" readonly required />
                                                <input type="text" step="any" value="<?php foreach ($satuan_data as $satuan) {
                                                                                            if ($satuan['id'] === $pesanan['satuan']) {
                                                                                                echo $satuan['nama'];
                                                                                            }
                                                                                        } ?>" class="text-center w-full" name="" readonly />

                                            </td>

                                            <td class="align-middle p-1 text-center">
                                                <input type="number" min="1" class="text-center w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#FDFDFD]" name="jumlah_diterima[]" required />
                                            </td>
                                            <td class="align-middle p-1 text-center">
                                                <input type="date" value="" class="text-center w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#FDFDFD]" name="kadaluwarsa[]" />
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
    document.getElementById('tglpenerimaan').addEventListener('input', function() {
        validateDate('tglpenerimaan', 'dateError1');
    });

    document.getElementById('tglfaktur').addEventListener('input', function() {
        validateDate('tglfaktur', 'dateError2');
    });

    document.getElementById('tgljatuhtempo').addEventListener('input', function() {
        validateDate('tgljatuhtempo', 'dateError3');
    });

    var tglpemesanan = new Date(document.getElementById('tglpesan').value);
    tglpemesanan.setHours(0, 0, 0, 0);
    var minDate = new Date(tglpemesanan);
    var maxDate = new Date(tglpemesanan);
    maxDate.setDate(maxDate.getDate() + 14);

    function validateDate(inputId, errorId) {
        var inputElement = document.getElementById(inputId);
        var errorElement = document.getElementById(errorId);
        var selectedDate = new Date(inputElement.value);
        selectedDate.setHours(0, 0, 0, 0);

        if (selectedDate < minDate || selectedDate > maxDate) {
            inputElement.classList.add('border-red-500');
            errorElement.classList.remove('hidden');
            errorElement.classList.add('flex', 'items-center');
        } else {
            inputElement.classList.remove('border-red-500');
            errorElement.classList.add('hidden');
            errorElement.classList.remove('flex', 'items-center');
        }
    }

    function validateForm() {
        const jumlahPesananInputs = document.querySelectorAll('.tabelbodypesanan input[name="jumlah_pesanan[]"]');
        const jumlahDiterimaInputs = document.querySelectorAll('.tabelbodypesanan input[name="jumlah_diterima[]"]');

        let allMatch = true;
        let isValid = true;

        // Loop through each 'jumlah_diterima[]' input
        jumlahDiterimaInputs.forEach((input, index) => {
            const jumlahPesananValue = parseFloat(jumlahPesananInputs[index].value);
            const jumlahDiterimaValue = parseFloat(input.value);

            // Check if 'jumlah_diterima[]' is greater than 'jumlah_pesanan[]'
            if (jumlahDiterimaValue > jumlahPesananValue) {
                alert("Jumlah diterima tidak boleh lebih besar dari jumlah pesanan");
                isValid = false;
                return;
            }

            // Compare the value with corresponding 'jumlah_pesanan[]' input
            if (jumlahDiterimaValue !== jumlahPesananValue) {
                allMatch = false;
            }
        });

        if (!isValid) {
            // Prevent form submission if validation fails
            event.preventDefault();
            return;
        }

        const statuspesanan = document.querySelector('input[name="statuspesanan"]');
        statuspesanan.value = allMatch ? '5' : '4';


        var inputs = [{
                id: 'tglpenerimaan',
                errorId: 'dateError1',
                namaTanggal: 'Penerimaan',
            },
            {
                id: 'tglfaktur',
                errorId: 'dateError2',
                namaTanggal: 'Faktur',
            },
            {
                id: 'tgljatuhtempo',
                errorId: 'dateError3',
                namaTanggal: 'Jatuh Tempo',
            }
        ];

        inputs.forEach(function(input) {
            var inputElement = document.getElementById(input.id);
            var errorElement = document.getElementById(input.errorId);
            var selectedDate = new Date(inputElement.value);
            selectedDate.setHours(0, 0, 0, 0);

            if (selectedDate < minDate || selectedDate > maxDate) {
                inputElement.classList.add('border-red-500');
                errorElement.classList.remove('hidden');
                errorElement.classList.add('flex', 'items-center');
                alert("Tanggal " + input.namaTanggal + " harus setelah tanggal pemesanan dan maksimal 14 hari dari pemesanan.");
                isValid = false;
            } else {
                inputElement.classList.remove('border-red-500');
                errorElement.classList.add('hidden');
                errorElement.classList.remove('flex', 'items-center');
            }
        });

        if (isValid) {
            var submitButton = document.getElementById('submitButton');
            submitButton.setAttribute('disabled', true);
            submitButton.innerHTML = 'Menyimpan...'; // Adjust text as needed
        }

        return isValid;
    }
</script>
<?= $this->endSection(); ?>