<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<!-- Card Section -->
<div class="max-w-[85rem] py-6 lg:py-3 px-8 mx-auto">
    <!-- Card -->
    <div class="bg-white rounded-xl shadow p-4 sm:p-7 dark:bg-slate-900">
        <div class="mb-8">
            <h2 class="text-xl font-bold text-gray-800 dark:text-gray-200">
                Ubah Pengajuan Barang Medis
            </h2>

        </div>

        <form action="/pengajuanmedis/submitedit/<?= $pengajuanId ?>" method="post" onsubmit="return validateForm()">
        <?= csrf_field() ?> 
        <input type="hidden" name="status" value="<?= $pengajuan_data['status_pesanan'] ?>">   
            <!-- Grid -->
            <div class="sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Nomor Pengajuan</label>
                <input type="text" name="nopengajuan" class="border bg-[#F6F6F6] cursor-default text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" value="<?= $pengajuan_data['nomor_pengajuan'] ?>" readonly>
            </div>
            <div class="mt-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Tanggal Pengajuan</label>
                <input type="hidden" id="tglpengajuantetap" value="<?= $pengajuan_data['tanggal_pengajuan'] ?>">
                <input type="date" id="tglpengajuan" name="tglpengajuan" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" value="<?= $pengajuan_data['tanggal_pengajuan'] ?>" required> 
            </div>
            <div id="dateError" class="mt-2 hidden">
                <label class="text-sm text-gray-900 dark:text-white md:w-1/4"></label>
                <div class="flex items-center text-red-500 text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none">
                        <path d="M7 5.25V8.16667" stroke="#DA4141" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M7 12.4891H3.465C1.44083 12.4891 0.595001 11.0424 1.575 9.27492L3.395 5.99658L5.11 2.91658C6.14834 1.04408 7.85167 1.04408 8.89 2.91658L10.605 6.00242L12.425 9.28075C13.405 11.0482 12.5533 12.4949 10.535 12.4949H7V12.4891Z" stroke="#DA4141" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M6.99707 9.91675H7.00231" stroke="#DA4141" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg> Tanggal pengajuan harus dari tanggal pengajuan yang dilakukan atau setelahnya.
                </div>
            </div>
            <div class="mt-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Pegawai</label>
                <select name="pegawai" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" required>
                    <option value="">-</option>
                    <?php
                    foreach ($pegawai_data as $pegawai) {
                        $optionpegawai = [$pegawai['id'] => $pegawai['nama']];
                        foreach ($optionpegawai as $pegawaiid => $pegawainama) {
                            if ($pegawaiid === $pengajuan_data['id_pegawai']) {
                                echo '<option value="' . $pegawai['id'] . '" selected>' . $pegawai['nama'] . '</option>';
                            } else {
                                echo '<option value="' . $pegawai['id'] . '">' . $pegawai['nama'] . '</option>';
                            }
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="mt-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Catatan</label>
                <input type="text" name="catatan" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" value="<?= $pengajuan_data['catatan'] ?>">
            </div>


            <!-- End Grid -->
            <div class="mt-5 flex flex-col">
                <div class="-m-1.5 overflow-x-auto">
                    <div class="p-1.5 min-w-full inline-block align-middle">
                        <div class="border-t-[1px] border-x-0 border-b-0 overflow-hidden dark:border-neutral-700">
                            <div class="flex justify-between p-2 text-sm text-gray-600 dark:text-neutral-500">
                                <div class="inline-flex items-center text-[1.25rem] font-[400] leading-[normal] tracking-[0.00625rem]">
                                    Pesanan
                                </div>
                            </div>

                            <div class="border rounded-lg overflow-hidden">
                                <table class="min-w-full divide-gray-200  dark:divide-neutral-700" id="item-list">
                                    <colgroup>
                                        <col width="8%">
                                        <col width="30%">
                                        <col width="18%">
                                        <col width="22%">
                                        <col width="22%">
                                    </colgroup>
                                    <thead class="border-b bg-[#DCDCDC]">
                                        <tr class="bg-navy disabled">
                                            <th class="px-1 py-1 text-center">Jumlah</th>
                                            <th class="px-1 py-1 text-center">Barang</th>
                                            <th class="px-1 py-1 text-center">Satuan</th>
                                            <th class="px-1 py-1 text-center">Harga Pengajuan</th>
                                            <th class="px-1 py-1 text-center">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 dark:divide-neutral-700">
                                        <?php foreach ($pesanan_data as $pesanan) : ?>
                                            <tr>
                                                <input type="hidden" value="<?= $pesanan['id'] ?>" class="text-center w-full border" name="idpesanan[]" />
                                                <input type="hidden" value="<?= $pesanan['kadaluwarsa'] ?>" class="text-center w-full border" name="kadaluwarsa[]" />

                                                <td class="align-middle p-1 text-center">
                                                    <input type="number" min="0" class="rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#FDFDFD] text-center w-full border" step="any" name="jumlah_pesanan[]" value="<?= $pesanan['jumlah_pesanan'] ?>" required/>
                                                </td>
                                                <td class="align-middle p-1">
                                                    <select name="idbrgmedis[]" class="py-[0.5px] w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#FDFDFD] text-center" required>
                                                        <option value="" selected></option>
                                                        <?php
                                                        foreach ($medis_data as $barang_medis) {
                                                            $optionbarang_medis = [$barang_medis['id'] => $barang_medis['nama']];
                                                            foreach ($optionbarang_medis as $barang_medisid => $barang_medisnama) {
                                                                if ($barang_medisid === $pesanan['id_barang_medis']) {
                                                                    echo '<option value="' . $barang_medis['id'] . '" selected>' . $barang_medis['nama'] . '</option>';
                                                                } else {
                                                                    echo '<option value="' . $barang_medis['id'] . '">' . $barang_medis['nama'] . '</option>';
                                                                }
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </td>
                                                <td class="align-middle p-1">
                                                    <select name="satuanbrgmedis[]" class="py-[0.5px] w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#FDFDFD] text-center" required>
                                                        <option value="" selected></option>
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
                                                    <input type="number" min="0" step="any" class="rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#FDFDFD] text-center w-full border" name="harga_satuan_pengajuan[]" value="<?= $pesanan['harga_satuan_pengajuan'] ?>" required/>
                                                </td>
                                                <td class="align-middle p-1 text-right">
                                                    <input type="number" min="0" class="rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#F6F6F6] cursor-default text-center w-full border" name="subtotalperitem[]" value="<?= $pesanan['total_per_item'] ?>" readonly />
                                                </td>
                                            </tr>
                                            <!-- <tr>

                                                <td class="align-middle p-1 text-center">
                                                    <input type="number" min="0" value="<?= $pesanan['jumlah_pesanan'] ?>" class="text-center w-full border" step="any" name="jumlah_pesanan[]" />
                                                </td>
                                                <td class="align-middle p-1">
                                                    <select name="idbrgmedis[]" class="w-full border text-center" onchange="updateHarga(this)">
                                                        <?php
                                                        foreach ($medis_data as $barang_medis) {
                                                            $optionbarang_medis = [$barang_medis['id'] => $barang_medis['nama']];
                                                            foreach ($optionbarang_medis as $barang_medisid => $barang_medisnama) {
                                                                if ($barang_medisid === $pesanan['id_barang_medis']) {
                                                                    echo '<option value="' . $barang_medis['id'] . '" selected>' . $barang_medis['nama'] . '</option>';
                                                                } else {
                                                                    echo '<option value="' . $barang_medis['id'] . '">' . $barang_medis['nama'] . '</option>';
                                                                }
                                                            }
                                                        }
                                                        ?>

                                                    </select>
                                                </td>
                                                <td class="align-middle p-1">
                                                    <select name="satuanbrgmedis[]" class="w-full border text-center">
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
                                                    <input type="text" id="harga" value="<?= $pesanan['harga_satuan_pengajuan'] ?>" step="any" class="text-center w-full border" name="harga_satuan[]" />
                                                </td>
                                                <td class="align-middle p-1 text-right">
                                                    <input type="text" class="text-center w-full border" value="" name="total[]" />
                                                </td>

                                            </tr> -->
                                        <?php endforeach; ?>
                                    </tbody>
                                    <tfoot class="border-t">
                                        <!-- <tr class="pt-5">
                                            <th class="p-1 pt-2" style="text-align: right;" colspan="4">

                                                Diskon (%)
                                                <input type="number" min="0" step="any" name="diskonpersen" class="border w-[20%] rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#FDFDFD] text-center" value="">
                                            </th>

                                            <th class="p-1 pt-2 text-right">
                                                <input type="number" min="0" class="w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#F6F6F6] cursor-default text-center" name="diskonjumlah" value="">
                                            </th>
                                        </tr>

                                        <tr>
                                            <th class="p-1" style="text-align: right;" colspan="4">Pajak (%)
                                                <input type="number" min="0" step="any" name="pajakpersen" class="border w-[20%] rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#FDFDFD] text-center" value="">
                                            </th>

                                            <th class="p-1 text-right">
                                                <input type="number" min="0" class="w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#F6F6F6] cursor-default text-center" name="pajakjumlah" value="">
                                            </th>
                                        </tr>
                                        <tr>
                                            <th class="p-1" style="text-align: right;" colspan="4">Materai</th>
                                            <th class="p-1 text-right">
                                                <input type="number" min="0" class="w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#FDFDFD] text-center" name="materai" value="">
                                            </th>
                                        </tr> -->
                                        <tr>
                                            <th class="p-1" style="text-align: right;" colspan="4">Total Keseluruhan</th>
                                            <th class="p-1" id="total"><input type="number" min="0" class="w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#F6F6F6] cursor-default text-center" name="totalkeseluruhan" value="<?= $pengajuan_data['total_pengajuan'] ?>" required> </th>
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

    function removeRow(btn) {
        var row = btn.parentNode.parentNode;
        row.parentNode.removeChild(row);
    }

    document.getElementById('tglpengajuan').addEventListener('input', function() {
        var tglpengajuanInput = document.getElementById('tglpengajuan');
        var tglpengajuantetapInput = document.getElementById('tglpengajuantetap');
        var dateError = document.getElementById('dateError');
        var selectedDate = new Date(tglpengajuanInput.value);
        var selectedDatetetap = new Date(tglpengajuantetapInput.value);

        selectedDate.setHours(0, 0, 0, 0); // Clear the time part
        selectedDatetetap.setHours(0, 0, 0, 0); // Clear the time part

        if (selectedDate.getTime() != selectedDatetetap.getTime()) {
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
        var tglpengajuantetapInput = document.getElementById('tglpengajuantetap');
        var dateError = document.getElementById('dateError');
        var selectedDate = new Date(tglpengajuanInput.value);
        var selectedDatetetap = new Date(tglpengajuantetapInput.value);

        selectedDate.setHours(0, 0, 0, 0); // Clear the time part
        selectedDatetetap.setHours(0, 0, 0, 0); // Clear the time part

        if (selectedDate.getTime() != selectedDatetetap.getTime()) {
            tglpengajuanInput.classList.add('border-red-500');
            dateError.classList.remove('hidden');
            dateError.classList.add('block');
            alert("Tanggal pengajuan harus dari tanggal pengajuan yang dilakukan atau setelahnya.");
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