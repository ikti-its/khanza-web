<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<!-- Card Section -->
<div class="max-w-[85rem] py-6 lg:py-3 px-8 mx-auto">
    <!-- Card -->
    <div class="bg-white rounded-xl shadow p-4 sm:p-7 dark:bg-slate-900">
        <?= view('components/form_judul', [
            'judul' => 'Ubah Penerimaan Barang Medis'
        ]) ?>

        <form action="/penerimaanmedis/submitedit/<?= $penerimaan_data['id'] ?>" id="penerimaanform" method="post" onsubmit="return validateForm()">
            <?= csrf_field() ?>
            <!-- Grid -->

            <div class="sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Nomor Faktur</label>
                <input type="text" name="nofaktur" value="<?= $penerimaan_data['no_faktur'] ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white">
            </div>
            <div class="mt-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Nomor Pemesanan</label>
                <input type="text" name="nopemesanan" value="<?= $penerimaan_data['no_pemesanan'] ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" required>

            </div>
            <div class="mt-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Tanggal Penerimaan</label>
                <input type="date" id="tglpenerimaan" value="<?= $penerimaan_data['tanggal_datang'] ?>" name="tglpenerimaan" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" required>
            </div>

            <div class="mt-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Tanggal Faktur</label>
                <input type="date" id="tglfaktur" value="<?= $penerimaan_data['tanggal_faktur'] ?>" name="tglfaktur" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" required>
            </div>

            <div class="mt-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Tanggal Jatuh Tempo</label>
                <input type="date" id="tgljatuhtempo" value="<?= $penerimaan_data['tanggal_jthtempo'] ?>" name="tgljatuhtempo" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" required>
            </div>

            <div class="mt-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Supplier</label>
                <select name="supplier" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" required>
                    <?php
                    foreach ($supplier_data as $supplier) {
                        $option_supplier = [$supplier['id'] => $supplier['nama']];
                        foreach ($option_supplier as $supplier_id => $supplier_nama) {
                            if ($supplier_id === $penerimaan_data['id_supplier']) { // Assuming 'id_supplier' is the field in $pemesanan_data
                                echo '<option value="' . $supplier['id'] . '" selected>' . $supplier['nama'] . '</option>';
                            } else {
                                echo '<option value="' . $supplier['id'] . '">' . $supplier['nama'] . '</option>';
                            }
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="mt-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Pegawai</label>
                <select name="pegawaipenerimaan" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" required>
                    <?php
                    foreach ($pegawai_data as $pegawai) {
                        $option_pegawai = [$pegawai['id'] => $pegawai['nama']];
                        foreach ($option_pegawai as $pegawai_id => $pegawai_nama) {
                            if ($pegawai_id === $penerimaan_data['id_pegawai']) { // Assuming 'id_pegawai' is the field in $pemesanan_data
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
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Lokasi</label>
                <select name="idruangan" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" required>
                    <?php
                    foreach ($ruangan_data as $ruangan) {
                        $option_ruangan = [$ruangan['id'] => $ruangan['nama']];
                        foreach ($option_ruangan as $ruangan_id => $ruangan_nama) {
                            if ($ruangan_id === $penerimaan_data['id_ruangan']) { // Assuming 'id_ruangan' is the field in $pemesanan_data
                                echo '<option value="' . $ruangan['id'] . '" selected>' . $ruangan['nama'] . '</option>';
                            } else {
                                echo '<option value="' . $ruangan['id'] . '">' . $ruangan['nama'] . '</option>';
                            }
                        }
                    }
                    ?>
                </select>
            </div>


            <!-- End Grid -->
            <div class="mt-5 flex flex-col">
                <div class="-m-1.5 overflow-x-auto">
                    <div class="p-1.5 min-w-full inline-block align-middle">
                        <!-- <div class="border-t-[1px] flex justify-between p-2 text-sm text-gray-600 dark:text-neutral-500">
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
                        </div> -->
                        <div class="border rounded-lg overflow-hidden dark:border-neutral-700">
                            <table class="pt-5 min-w-full divide-y divide-gray-200 dark:divide-neutral-700" id="item-list">
                                <?php 
                                    $widths  = [4, 6, 12, 5, 4, 8, 8, 8, 4, 6, 8, 8];
                                    echo view('components/tabel_colgroup',['widths' => $widths]);
                                    
                                    $columns = [
                                        'Jumlah',
                                        'Satuan Beli',
                                        'Nama Barang',
                                        'Satuan',
                                        'G',
                                        'Kadaluwarsa',
                                        'Harga',
                                        'Subtotal',
                                        'Diskon (%)',
                                        'Diskon (Rp)',
                                        'Total',
                                        'No Batch'
                                    ];
                                    // echo view('components/tabel_thead',['kolom' => $columns]);
                                ?>

                                <thead class="bg-[#DCDCDC]">
                                    <tr class="bg-navy disabled">
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
                                    <?php foreach ($detail_data as $detail) {
                                        if ($penerimaan_data['id'] === $detail['id_penerimaan']) { ?>
                                            <tr>
                                                <td class="align-middle p-1 text-center">
                                                    <input type="hidden" min="0" value="<?= $detail['jumlah'] ?>" class="text-center w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#FDFDFD]" step="any" name="jumlah_pesanan_tetap[]" required />
                                                    <input type="number" min="0" value="<?= $detail['jumlah'] ?>" class="text-center w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#FDFDFD]" step="any" name="jumlah_pesanan[]" required />
                                                </td>
                                                <td class="align-middle p-1">
                                                    <select name="satuanbeli[]" class="w-full py-[1.5px] border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#FDFDFD] text-center" required>
                                                        <?php
                                                        foreach ($satuan_data as $satuan) {
                                                            $option_satuan = [$satuan['id'] => $satuan['nama']];
                                                            foreach ($option_satuan as $satuan_id => $satuan_nama) {
                                                                if ($satuan_id === $detail['id_satuan']) { // Assuming 'id_satuan' is the field in $pemesanan_data
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
                                                    <input type="hidden" step="any" value="<?= $detail['id_barang_medis'] ?>" class="text-center w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#FDFDFD]" name="idbrgmedis[]" />
                                                    <input type="text" step="any" value="<?php foreach ($medis_data as $medis) {
                                                                                                if ($medis['id'] === $detail['id_barang_medis']) {
                                                                                                    echo $medis['nama'];
                                                                                                }
                                                                                            } ?>" class="satuan-input text-center w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#FDFDFD]" name="" required />

                                                </td>
                                                <td class="align-middle p-1">
                                                    <input type="hidden" step="any" value="" class="satuanid-input text-center w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#FDFDFD]" name="satuan[]" required />
                                                    <input type="text" step="any" value="<?php foreach ($medis_data as $medis) {
                                                                                                foreach ($satuan_data as $satuan) {
                                                                                                    if ($medis['id'] === $detail['id_barang_medis'] && $satuan['id'] === $medis['id_satbesar']) {
                                                                                                        echo $satuan['nama'];
                                                                                                    }
                                                                                                }
                                                                                            } ?>" class="satuan-input text-center w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#FDFDFD]" name="" required />
                                                </td>

                                                <td class="align-middle p-1 text-center">
                                                    <input type="checkbox" name="ubahmaster" value="1" class="shrink-0 mt-0.5 border-gray-200 rounded text-blue-600 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800">
                                                </td>
                                                <td class="align-middle p-1 text-center">
                                                    <input type="date" value="<?= $detail['kadaluwarsa'] ?>" class="text-center w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#FDFDFD]" name="kadaluwarsa[]" />
                                                </td>
                                                <td class="align-middle p-1 text-center">
                                                    <input type="number" min="0" step="0.01" step="0.01" value="<?= $detail['h_pesan'] ?>" class="harga-input text-center w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#FDFDFD]" name="harga_satuan_pemesanan[]" required />
                                                </td>
                                                <td class="align-middle p-1 text-center">
                                                    <input type="number" min="0" step="0.01" value="<?= $detail['subtotal_per_item'] ?>" class="text-center w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#FDFDFD]" name="subtotalperitem[]" required />
                                                </td>
                                                <td class="align-middle p-1 text-center">
                                                    <input type="number" min="0" step="0.01" value="<?= $detail['diskon_persen'] ?>" class="text-center w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#FDFDFD]" name="diskonpersenperitem[]" required />
                                                </td>
                                                <td class="align-middle p-1 text-center">
                                                    <input type="number" min="0" step="0.01" value="<?= $detail['diskon_jumlah'] ?>" class="text-center w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#FDFDFD]" name="diskonjumlahperitem[]" required />
                                                </td>
                                                <td class="align-middle p-1 text-center">
                                                    <input type="number" min="0" step="0.01" value="<?= $detail['total_per_item'] ?>" class="text-center w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#FDFDFD]" name="totalperitem[]" required />
                                                </td>
                                                <td class="align-middle p-1 text-center">
                                                    <input type="text" value="<?= $detail['no_batch'] ?>" class="text-center w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#FDFDFD]" name="no_batch[]" />
                                                </td>

                                            </tr>
                                    <?php }
                                    } ?>
                                </tbody>
                                <tfoot>
                                    <tr>



                                    </tr>
                                    <tr>
                                        <th class="p-1 text-right" colspan="11">Total (Sebelum Pajak)</th>
                                        <th class="p-1 text-right">
                                            <input type="number" min="0" value="" step="any" name="totalsblmpajak" class="w-full text-center border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#F6F6F6]" required>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th class="p-1 text-right" colspan="11">Pajak (%)
                                            <input type="number" min="0" max="100" value="<?= $penerimaan_data['pajak_persen'] ?>" step="any" name="pajakpersenpemesanan" class="text-center border w-[5%] rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#FDFDFD]" required>
                                        </th>
                                        <th class="p-1 text-right">

                                            <input type="number" min="0" value="<?= $penerimaan_data['pajak_jumlah'] ?>" class="w-full text-center border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#F6F6F6] cursor-default" name="pajakjumlahpemesanan" readonly required>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th class="p-1 text-right" colspan="11">Materai</th>
                                        <th class="p-1 text-right">
                                            <input type="number" min="0" value="<?= $penerimaan_data['materai'] ?>" class="w-full text-center border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#FDFDFD]" name="materaipemesanan" required>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th class="p-1 text-right" colspan="11">Total</th>
                                        <th class="p-1" id="total">
                                            <input type="hidden" value="" class=" border text-center rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#F6F6F6] cursor-default" name="totalpengajuan" readonly>
                                            <input type="number" min="0" value="<?= $penerimaan_data['tagihan'] ?>" class="w-full border text-center rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#F6F6F6] cursor-default" name="totalpemesanan" readonly required>
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