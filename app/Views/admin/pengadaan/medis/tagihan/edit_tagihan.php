<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<!-- Card Section -->
<div class="max-w-[85rem] py-6 lg:py-3 px-8 mx-auto">
    <!-- Card -->
    <div class="bg-white rounded-xl shadow p-4 sm:p-7 dark:bg-slate-900">
        <?= view('components/form_judul', [
            'judul' => 'Ubah Tagihan Barang Medis'
        ]) ?>

        <form action="/tagihanmedis/submitedit/<?= $tagihan_data['id'] ?>" method="post" onsubmit="return validateForm()">
            <!-- Grid -->
            <input type="hidden" id="statuspesanan" name="statuspesanan">
            <input type="hidden" name="idpengajuan" value="<?= $tagihan_data['id_pengajuan'] ?>" class="text-center border mr-1 w-[20%]">
            <input type="hidden" name="idpemesanan" value="<?= $tagihan_data['id_pemesanan'] ?>" class="text-center border mr-1 w-[20%]">
            <input type="hidden" name="idpenerimaan" value="<?= $tagihan_data['id_penerimaan'] ?>" class="text-center border mr-1 w-[20%]">
            <?php foreach ($penerimaan_data as $penerimaan) {
                if ($penerimaan['id'] === $tagihan_data['id_penerimaan']) { ?>
                    <div class="sm:block md:flex items-center">
                        <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Nomor Faktur</label>
                        <input type="hidden" name="" id="tglpenerimaan" class="border bg-[#F6F6F6] cursor-default text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" value="<?= $penerimaan['tanggal_datang']; ?>" readonly>
                        <input type="text" name="" class="border bg-[#F6F6F6] cursor-default text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" value="<?= $penerimaan['no_faktur']; ?>" readonly>

                    </div>
            <?php }
            } ?>
            <div class="mt-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Tanggal Bayar</label>
                <input type="date" id="tglbayar" name="tglbayar" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" value="<?= $tagihan_data['tanggal_bayar'] ?>" required>
            </div>
            <div id="dateError" class="mt-2 hidden">
                <label class="text-sm text-gray-900 dark:text-white md:w-1/4"></label>
                <div class="flex items-center text-red-500 text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none">
                        <path d="M7 5.25V8.16667" stroke="#DA4141" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M7 12.4891H3.465C1.44083 12.4891 0.595001 11.0424 1.575 9.27492L3.395 5.99658L5.11 2.91658C6.14834 1.04408 7.85167 1.04408 8.89 2.91658L10.605 6.00242L12.425 9.28075C13.405 11.0482 12.5533 12.4949 10.535 12.4949H7V12.4891Z" stroke="#DA4141" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M6.99707 9.91675H7.00231" stroke="#DA4141" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg> Tanggal bayar maksimal 1 bulan sebelum dari hari ini.
                </div>
            </div>
            <div class="mt-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Pegawai</label>
                <select name="pegawaitagihan" id="dropdown-id-penerimaan" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" required>
                    <?php
                    foreach ($pegawai_data as $pegawai) {
                        $optionpegawai = [$pegawai['id'] => $pegawai['nama']];
                        foreach ($optionpegawai as $pegawaiid => $pegawainama) {
                            if ($pegawaiid === $tagihan_data['id_pegawai']) {
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
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/2 lg:w-1/4">Jumlah (Sisa Bayar)</label>
                <input type="text" name="jlhbayar" id="jlhbayar" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" value="<?= $tagihan_data['jumlah_bayar'] ?>" required>
                <input type="text" name="totalbayar" id="totalbayar" class="border bg-[#F6F6F6] cursor-default text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" readonly>
            </div>
            <div class="mt-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Nomor Bukti</label>
                <input type="text" name="nobukti" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" value="<?= $tagihan_data['no_bukti'] ?>" required>
            </div>
            <div class="mt-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Akun Bayar</label>
                <select name="akunbayar" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" required>
                    <?php
                    $options = [
                        "Cash" => "1000",
                        "Transfer lewat Mandiri" => "2000",
                        // Add more options as needed
                    ];

                    foreach ($options as $label => $value) {
                        if ($value === $tagihan_data['id_akun_bayar']) {
                            echo '<option value="' . $value . '" selected>' . $label . '</option>';
                        } else {
                            echo '<option value="' . $value . '">' . $label . '</option>';
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="mt-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Keterangan</label>
                <input type="text" name="keterangantagihan" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" value="<?= $tagihan_data['keterangan'] ?>">
            </div>


            <!-- End Grid -->
            <div class="mt-5 flex flex-col">
                <div class="-m-1.5 overflow-x-auto">
                    <div class="p-1.5 min-w-full inline-block align-middle">
                        <div class="border rounded-lg overflow-hidden dark:border-neutral-700">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700" id="item-list">
                                <?php 
                                    $widths  = [8, 20, 12, 12, 12, 8, 14, 14];
                                    echo view('components/data_tabel_colgroup',['widths' => $widths]);
                                    
                                    // $columns = [
                                    //     'Jumlah',
                                    //     'Barang',
                                    //     'Satuan',
                                    //     'Harga',
                                    //     'Subtotal',
                                    //     'Diskon (%)',
                                    //     'Diskon (Jumlah)',
                                    //     'Total per Item'
                                    // ];
                                    // echo view('components/data_tabel_thead',['columns' => $columns]);
                                ?>    
                                <thead class="bg-[#DCDCDC]">
                                    <tr class="bg-navy disabled">
                                        <th class="px-1 py-1 text-center">Jumlah</th>
                                        <th class="px-1 py-1 text-center">Barang</th>
                                        <th class="px-1 py-1 text-center">Satuan</th>
                                        <th class="px-1 py-1 text-center">Harga</th>
                                        <th class="px-1 py-1 text-center">Subtotal</th>
                                        <th class="px-1 py-1 text-center">Diskon (%)</th>
                                        <th class="px-1 py-1 text-center">Diskon (Jumlah)</th>
                                        <th class="px-1 py-1 text-center">Total per item</th>
                                    </tr>
                                </thead>
                                <tbody class="tabelbodypesanan divide-y divide-gray-200 dark:divide-neutral-700">
                                    <?php $totalsblmpajak = 0;
                                    foreach ($pesanan_data as $pesanan) :

                                        $totalsblmpajak += $pesanan['total_per_item']; ?>
                                        <tr>
                                            <td class="align-middle p-1 text-center">
                                                <input type="number" min="0" value="<?= $pesanan['jumlah_pesanan'] ?>" class="text-center w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#F6F6F6] cursor-default" step="any" name="jumlah_pesanan[]" readonly />
                                            </td>
                                            <td class="align-middle p-1">
                                                <?php
                                                foreach ($medis_data as $barang_medis) {
                                                    $optionbarang_medis = [$barang_medis['id'] => $barang_medis['nama']];
                                                    foreach ($optionbarang_medis as $barang_medisid => $barang_medisnama) {
                                                        if ($barang_medisid === $pesanan['id_barang_medis']) {
                                                            echo '<input type="text" step="any" value="' . $barang_medisnama . '" class="text-center w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#F6F6F6] cursor-default" name="" readonly/>';
                                                        }
                                                    }
                                                }
                                                ?>

                                            </td>
                                            <td class="align-middle p-1">
                                                <input type="text" value="<?php foreach ($satuan_data as $satuan) {
                                                                                if ($satuan['id'] === $pesanan['satuan'] && $pesanan['satuan'] !== 1) {
                                                                                    echo $satuan['nama'];
                                                                                } else {
                                                                                    echo '';
                                                                                }
                                                                            } ?>" class="text-center w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#F6F6F6] cursor-default" name="" readonly />
                                            </td>
                                            <td class="align-middle p-1">
                                                <input type="number" min="0" id="harga" step="any" value="<?= $pesanan['harga_satuan_pemesanan'] ?>" class="text-center w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#F6F6F6] cursor-default" name="harga_satuan[]" readonly />
                                            </td>
                                            <td class="align-middle p-1">
                                                <input type="number" min="0" value="<?= $pesanan['subtotal_per_item'] ?>" class="text-center w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#F6F6F6] cursor-default" name="subtotalperitem[]" readonly />
                                            </td>
                                            <td class="align-middle p-1">
                                                <input type="number" min="0" value="<?= $pesanan['diskon_persen'] ?>" class="text-center w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#F6F6F6] cursor-default" name="diskonpersenperitem[]" readonly />
                                            </td>
                                            <td class="align-middle p-1">
                                                <input type="number" min="0" value="<?= $pesanan['diskon_jumlah'] ?>" class="text-center w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#F6F6F6] cursor-default" name="diskonjumlahperitem[]" readonly />
                                            </td>
                                            <td class="align-middle p-1 text-right">
                                                <input type="number" min="0" value="<?= $pesanan['total_per_item'] ?>" class="text-center w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#F6F6F6] cursor-default" value="" name="total[]" readonly />
                                            </td>

                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot>
                                    <?php foreach ($pemesanan_data as $pemesanan) {
                                        if ($pemesanan['id_pengajuan'] === $tagihan_data['id_pengajuan']) { ?>

                                            <tr>
                                                <th class="p-1 text-right" colspan="7">Total (Sebelum Pajak)</th>
                                                <th class="p-1 text-right">
                                                    <input type="number" min="0" value="<?= $totalsblmpajak ?>" step="any" name="totalsblmpajak" class=" text-center border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#F6F6F6] cursor-default" readonly>
                                                </th>
                                            </tr>
                                            <tr>
                                                <th class="p-1" style="text-align: right;" colspan="7">Pajak (%)
                                                    <input type="number" min="0" value="<?= $pemesanan['pajak_persen'] ?>" step="any" name="pajakpersen" class="text-center border w-[15%] rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#F6F6F6] cursor-default" readonly>
                                                </th>

                                                <th class="p-1 text-right">
                                                    <input type="number" min="0" value="<?= $pemesanan['pajak_jumlah'] ?>" class="w-full border text-center rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#F6F6F6] cursor-default" name="pajakjumlah" readonly>
                                                </th>
                                            </tr>
                                            <tr>
                                                <th class="p-1" style="text-align: right;" colspan="7">Materai</th>
                                                <th class="p-1 text-right">
                                                    <input type="number" min="0" value="<?= $pemesanan['materai'] ?>" class="text-center w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#F6F6F6] cursor-default" name="materai" readonly>
                                                </th>
                                            </tr>
                                            <tr>
                                                <th class="p-1" style="text-align: right;" colspan="7">Total</th>
                                                <th class="p-1"><input type="number" min="0" value="<?= $pemesanan['total_pemesanan'] ?>" id="total_pemesanan" class="w-full border text-center rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#F6F6F6] cursor-default" name="" readonly></th>
                                            </tr>
                                    <?php }
                                    } ?>
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
<script>
    const totalPemesananInput = document.getElementById('total_pemesanan');
    const jlhBayarInput = document.getElementById('jlhbayar');
    const totalBayarInput = document.getElementById('totalbayar');
    const statusInput = document.getElementById('statuspesanan');

    // Fungsi untuk memperbarui total bayar dan status
    function updateTotalBayar() {
        const jlhBayar = parseFloat(jlhBayarInput.value);
        const totalPemesanan = parseFloat(totalPemesananInput.value);

        if (!isNaN(jlhBayar)) {
            const totalBayar = totalPemesanan - jlhBayar;

            if (totalBayar >= 0) {
                totalBayarInput.value = totalBayar.toFixed(0);
            } else {
                totalBayarInput.value = "0";
            }

            if (jlhBayar < totalPemesanan) {
                statusInput.value = "6";
            } else if (jlhBayar === totalPemesanan) {
                statusInput.value = "7";
            }
        } else {
            totalBayarInput.value = totalPemesananInput.value;
        }
    }

    // Hitung total bayar saat halaman dimuat
    document.addEventListener('DOMContentLoaded', function() {
        updateTotalBayar();
    });

    // Tambahkan event listener untuk memantau perubahan pada input jlhbayar
    jlhBayarInput.addEventListener('input', function() {
        updateTotalBayar();
    });

    var tglpenerimaan = new Date(document.getElementById('tglpenerimaan').value);
    tglpenerimaan.setHours(0, 0, 0, 0);
    var minDate = new Date(tglpenerimaan);
    var maxDate = new Date(tglpenerimaan);
    maxDate.setDate(maxDate.getDate() + 30);

    document.getElementById('tglbayar').addEventListener('input', function() {
        var tglbayarInput = document.getElementById('tglbayar');
        var dateError = document.getElementById('dateError');
        var selectedDate = new Date(tglbayarInput.value);


        if (selectedDate < minDate || selectedDate > maxDate) {
            tglbayarInput.classList.add('border-red-500');
            dateError.classList.remove('hidden');
            dateError.classList.add('flex', 'items-center');
        } else {
            tglbayarInput.classList.remove('border-red-500');
            dateError.classList.add('hidden');
            dateError.classList.remove('block');
        }
    });

    function validateForm() {
        var tglbayarInput = document.getElementById('tglbayar');
        var dateError = document.getElementById('dateError');
        var selectedDate = new Date(tglbayarInput.value);


        if (selectedDate < minDate || selectedDate > maxDate) {
            tglbayarInput.classList.add('border-red-500');
            dateError.classList.remove('hidden');
            dateError.classList.add('block');
            alert("Tanggal bayar maksimal 1 bulan sebelum dari hari ini.");
            return false;
        }

        var jlhBayar = parseFloat(jlhBayarInput.value);
        var totalPemesanan = parseFloat(totalPemesananInput.value);

        if (jlhBayar > totalPemesanan) {
            alert("Jumlah bayar tidak boleh lebih besar dari total pemesanan.");
            return false;
        }

        var submitButton = document.getElementById('submitButton');
        submitButton.setAttribute('disabled', true);
        submitButton.innerHTML = 'Menyimpan...';
        return true;
    }
</script>
<!-- End Card Section -->
<?= $this->endSection(); ?>