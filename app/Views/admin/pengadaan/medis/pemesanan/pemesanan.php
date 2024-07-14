<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<!-- Card Section -->
<div class="max-w-[85rem] py-6 lg:py-3 px-8 mx-auto">
    <!-- Card -->
    <div class="bg-white rounded-xl shadow p-4 sm:p-7 dark:bg-slate-900">
        <div class="mb-8">
            <h2 class="text-xl font-bold text-gray-800 dark:text-gray-200">
                Tambah Pemesanan Barang Medis
            </h2>

        </div>

        <form action="/pemesananmedis/submittambah" method="post" onsubmit="return validateForm()">
            <?= csrf_field() ?>
            <input type="hidden" value="3" name="statuspesanan">
            <!-- Grid -->
            <input type="hidden" name="idpengajuan" value="<?= $pengajuan_data['id'] ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white">
            <div class="sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Nomor Pengajuan</label>
                <input type="text" name="" value="<?= $pengajuan_data['nomor_pengajuan'] ?>" class="border bg-[#F6F6F6] text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" readonly required>
            </div>
            <div class="mt-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Tanggal Pemesanan</label>
                <input type="hidden" id="tglpengajuan" value="<?= $pengajuan_data['tanggal_pengajuan'] ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" required>
                <input type="date" id="tglpemesanan" name="tglpemesanan" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" required>
            </div>
            <div id="dateError" class="mt-2 hidden">
                <label class="text-sm text-gray-900 dark:text-white md:w-1/4"></label>
                <div class="flex items-center text-red-500 text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none">
                        <path d="M7 5.25V8.16667" stroke="#DA4141" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M7 12.4891H3.465C1.44083 12.4891 0.595001 11.0424 1.575 9.27492L3.395 5.99658L5.11 2.91658C6.14834 1.04408 7.85167 1.04408 8.89 2.91658L10.605 6.00242L12.425 9.28075C13.405 11.0482 12.5533 12.4949 10.535 12.4949H7V12.4891Z" stroke="#DA4141" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M6.99707 9.91675H7.00231" stroke="#DA4141" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg> Tanggal pemesanan harus setelah tanggal pengajuan dan maksimal 10 hari dari pengajuan.
                </div>
            </div>
            <div class="mt-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Nomor Pemesanan</label>
                <input type="text" name="nopemesanan" value="<?php function generateUniqueNumber($length = 16)
                                                                {
                                                                    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                                                                    $charactersLength = strlen($characters);
                                                                    $randomString = '';

                                                                    $uniqueLength = $length - 10;

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

                                                                $nomorPemesanan = "PO" . $tanggalHariIni . generateUniqueNumber();
                                                                echo $nomorPemesanan; ?>" class="border bg-[#F6F6F6] text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" required readonly>
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
                <select name="pegawaipemesanan" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" required>
                    <option value="" selected>-</option>
                    <?php foreach ($pegawai_data as $pegawai) : ?>
                        <option value="<?= $pegawai['id'] ?>"><?= $pegawai['nama'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- End Grid -->
            <div class="mt-5 flex flex-col">
                <div class="-m-1.5 overflow-x-auto">
                    <div class="p-1.5 min-w-full inline-block align-middle">
                        <div class="border-x-0 border-b-0 overflow-hidden dark:border-neutral-700">
                            <div class="border rounded-lg">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700" id="item-list">
                                    <colgroup>
                                        <col width="8%">
                                        <col width="20%">
                                        <col width="12%">
                                        <col width="12%">
                                        <col width="12%">
                                        <col width="8%">
                                        <col width="14%">
                                        <col width="14%">
                                    </colgroup>
                                    <thead class="border-b bg-[#DCDCDC]">
                                        <tr class="bg-navy disabled">
                                            <th class="px-1 py-1 text-center">Jumlah</th>
                                            <th class="px-1 py-1 text-center">Barang</th>
                                            <th class="px-1 py-1 text-center">Satuan</th>
                                            <th class="px-1 py-1 text-center">Harga Pemesanan</th>
                                            <th class="px-1 py-1 text-center">Subtotal</th>
                                            <th class="px-1 py-1 text-center">Diskon (%)</th>
                                            <th class="px-1 py-1 text-center">Diskon (Jumlah)</th>
                                            <th class="px-1 py-1 text-center">Total per item</th>
                                        </tr>
                                    </thead>
                                    <tbody class="tabelbodypesanan divide-y divide-gray-200 dark:divide-neutral-700">
                                        <?php foreach ($pesanan_data as $pesanan) { ?>
                                            <tr>
                                                <td class="align-middle p-1 text-center">
                                                    <input type="number" min="0" value="<?= $pesanan['jumlah_pesanan'] ?>" class="text-center w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#F6F6F6]" name="jumlah_pesanan[]" readonly/>
                                                </td>
                                                <td class="align-middle p-1">
                                                    <input type="hidden" value="<?= $pesanan['id'] ?>" name="idpesanan[]" class="text-center border mr-1 w-[20%]">
                                                    <input type="hidden" value="<?= $pesanan['id_barang_medis'] ?>" name="idbrgmedis[]" class="text-center border mr-1 w-[20%]">
                                                    <input type="hidden" value="<?= $pesanan['kadaluwarsa'] ?>" name="kadaluwarsa[]" class="text-center border mr-1 w-[20%]">
                                                    <input type="hidden" value="<?= $pesanan['harga_satuan_pengajuan'] ?>" name="harga_satuan_pengajuan[]" />
                                                    <input type="hidden" value="<?= $pesanan['jumlah_diterima'] ?>" name="jumlah_diterima[]" class="text-center border mr-1 w-[20%]">
                                                    <input type="hidden" value="<?= $pesanan['no_batch'] ?>" name="no_batch[]" class="text-center border mr-1 w-[20%]">
                                                    <input type="text" value="<?php foreach ($medis_data as $medis) {
                                                                                    if ($medis['id'] === $pesanan['id_barang_medis']) {
                                                                                        echo $medis['nama'];
                                                                                    }
                                                                                } ?>" class="text-center w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#F6F6F6] cursor-default" name="" readonly />
                                                </td>
                                                <td class="align-middle p-1">
                                                    <select name="satuanbrgmedis[]" class="py-[1.5px] w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#FDFDFD] text-center" required>
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
                                                    <input type="number" min="0" value="<?= $pesanan['harga_satuan_pengajuan'] ?>" class="text-center w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#FDFDFD]" name="harga_satuan_pemesanan[]" required />
                                                </td>
                                                <td class="align-middle p-1">
                                                    <input type="number" min="0" value="<?= $pesanan['subtotal_per_item'] ?>" class="text-center w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#F6F6F6]" name="subtotalperitem[]" readonly required />
                                                </td>
                                                <td class="align-middle p-1">
                                                    <input type="number" min="0" max="100" value="<?= $pesanan['diskon_persen'] ?>" class="text-center w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#FDFDFD]" name="diskonpersenperitem[]" required />
                                                </td>
                                                <td class="align-middle p-1">
                                                    <input type="number" min="0" value="<?= $pesanan['diskon_jumlah'] ?>" class="text-center w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#F6F6F6]" name="diskonjumlahperitem[]" readonly required />
                                                </td>
                                                <td class="align-middle p-1 text-right">
                                                    <input type="number" min="0" value="<?= $pesanan['total_per_item'] ?>" class="text-center w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#F6F6F6] cursor-default" name="totalperitem[]" readonly required>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>

                                    <tfoot>
                                        <tr>
                                            <input type="hidden" value="<?= $pengajuan_data['tanggal_pengajuan'] ?>" step="any" name="tglpengajuan" class="text-center border mr-1 w-[20%]">
                                            <input type="hidden" value="<?= $pengajuan_data['nomor_pengajuan'] ?>" step="any" name="nopengajuan" class="text-center border mr-1 w-[20%]">
                                            <input type="hidden" value="<?= $pengajuan_data['id_pegawai'] ?>" step="any" name="pegawaipengajuan" class="text-center border mr-1 w-[20%]">
                                            <input type="hidden" value="<?= $pengajuan_data['catatan'] ?>" step="any" name="catatanpengajuan" class="text-center border mr-1 w-[20%]">


                                        </tr>
                                        <tr>
                                            <th class="p-1 text-right" colspan="7">Total (Sebelum Pajak)</th>
                                            <th class="p-1 text-right">
                                                <input type="number" min="0" value="" step="any" name="totalsblmpajak" class=" text-center border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#F6F6F6]" required>
                                            </th>
                                        </tr>
                                        <tr>
                                            <th class="p-1 text-right" colspan="7">Pajak (%)
                                                <input type="number" min="0" max="100" value="0" step="any" name="pajakpersenpemesanan" class=" text-center border w-[15%] rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#FDFDFD]" required>
                                            </th>
                                            <th class="p-1 text-right">

                                                <input type="number" min="0" value="0" class="text-center w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#F6F6F6] cursor-default" name="pajakjumlahpemesanan" readonly required>
                                            </th>
                                        </tr>
                                        <tr>
                                            <th class="p-1 text-right" colspan="7">Materai</th>
                                            <th class="p-1 text-right">
                                                <input type="number" min="0" value="0" class="text-center w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#FDFDFD]" name="materaipemesanan" required>
                                            </th>
                                        </tr>
                                        <tr>
                                            <th class="p-1 text-right" colspan="7">Total</th>
                                            <th class="p-1" id="total">
                                                <input type="hidden" value="<?= $pengajuan_data['total_pengajuan'] ?>" class="w-full border text-center rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#F6F6F6] cursor-default" name="totalpengajuan" readonly>
                                                <input type="number" min="0" value="<?= $pengajuan_data['total_pengajuan'] ?>" class="w-full border text-center rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#F6F6F6] cursor-default" name="totalpemesanan" readonly required>
                                            </th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-5 flex justify-end gap-x-2">
                <a href="javascript:history.back()" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-white dark:hover:bg-gray-800 ">
                    Kembali
                </a>
                <button type="submit" id="submitButton" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-[#0A2D27] text-[#ACF2E7] disabled:opacity-50 disabled:pointer-events-none ">
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
        subtotalInputs[index].value = total.toFixed(0); // Atur jumlah desimal yang diinginkan

        hitungDiskon(index);
    }

    function hitungDiskon(index) {
        var diskonPersen = parseFloat(diskonPersenInput[index].value) || 0;
        var subtotal = parseFloat(subtotalInputs[index].value) || 0;
        var diskonJumlah = subtotal * (diskonPersen / 100);
        diskonJumlahInput[index].value = diskonJumlah.toFixed(0);

        hitungTotalPerItem(index);
    }

    function hitungTotalPerItem(index) {
        var subtotal = parseFloat(subtotalInputs[index].value) || 0;
        var diskon = parseFloat(diskonJumlahInput[index].value) || 0;
        var totalperitem = subtotal - diskon;
        totalperitemInputs[index].value = totalperitem.toFixed(0);

        hitungTotalSblmPajak();
    }

    function hitungTotalSblmPajak() {
        var totalSblmPajak = 0;
        totalperitemInputs.forEach(function(input) {
            totalSblmPajak += parseFloat(input.value) || 0;
        });
        totalSblmPajakInputs.value = totalSblmPajak.toFixed(0);
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
        pajakJumlahInput.value = pajakJumlah.toFixed(0);

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
        totalKeseluruhanInputs.value = totalKeseluruhan.toFixed(0);
    }


    var tglpengajuan = new Date('<?= $pengajuan_data['tanggal_pengajuan'] ?>');
    tglpengajuan.setHours(0, 0, 0, 0);
    var minDate = new Date(tglpengajuan);
    var maxDate = new Date(tglpengajuan);
    maxDate.setDate(maxDate.getDate() + 10);
    document.getElementById('tglpemesanan').addEventListener('input', function() {
        var tglpemesananInput = document.getElementById('tglpemesanan');
        var dateError = document.getElementById('dateError');
        var selectedDate = new Date(tglpemesananInput.value);
        selectedDate.setHours(0, 0, 0, 0);

        if (selectedDate < minDate || selectedDate > maxDate) {
            tglpemesananInput.classList.add('border-red-500');
            dateError.classList.remove('hidden');
            dateError.classList.add('flex', 'items-center');
        } else {
            tglpemesananInput.classList.remove('border-red-500');
            dateError.classList.add('hidden');
            dateError.classList.remove('block');
        }
    });

    function validateForm() {
        var tglpemesananInput = document.getElementById('tglpemesanan');
        var dateError = document.getElementById('dateError');
        var selectedDate = new Date(tglpemesananInput.value);
        var maxDate = new Date();
        selectedDate.setHours(0, 0, 0, 0);
        if (selectedDate < minDate || selectedDate > maxDate) {
            tglpemesananInput.classList.add('border-red-500');
            dateError.classList.remove('hidden');
            dateError.classList.add('block');
            alert("Tanggal pemesanan harus setelah tanggal pengajuan dan maksimal 10 hari dari pengajuan.");
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