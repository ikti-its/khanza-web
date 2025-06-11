<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<!-- Card Section -->
<div class="max-w-[85rem] py-6 lg:py-3 px-8 mx-auto">
    <!-- Card -->
    <div class="bg-white rounded-xl shadow p-4 sm:p-7 dark:bg-slate-900">
        <div class="mb-8">
            <h2 class="text-xl font-bold text-gray-800 dark:text-gray-200">
                Ubah Stok Keluar Barang Medis
            </h2>

        </div>

        <form action="/stokkeluarmedis/submitedit/<?= $stok_keluar_data['id'] ?>" id="formId" method="post">
            <input type="hidden" name="idstokkeluar" value="<?= $stok_keluar_data['id'] ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white">
            <!-- Grid -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Tanggal Stok Keluar</label>
                <input type="date" name="tglkeluar" value="<?= $stok_keluar_data['tanggal_stok_keluar'] ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" required>
            </div>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">No Keluar</label>
                <input type="text" name="nokeluar" value="<?= $stok_keluar_data['no_keluar'] ?>" class="border bg-[#F6F6F6] cursor-default text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" required readonly>
            </div>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Pegawai</label>
                <select name="pegawaistokkeluar" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" required>
                    <?php
                    foreach ($pegawai_data as $pegawai) {
                        $optionpegawai = [$pegawai['id'] => $pegawai['nama']];
                        foreach ($optionpegawai as $pegawaiid => $pegawainama) {
                            if ($pegawaiid === $stok_keluar_data['id_pegawai']) {
                                echo '<option value="' . $pegawai['id'] . '" selected>' . $pegawai['nama'] . '</option>';
                            } else {
                                echo '<option value="' . $pegawai['id'] . '">' . $pegawai['nama'] . '</option>';
                            }
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Asal Ruangan</label>
                <select name="asalruangan" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" required>
                    <?php
                    $optionsruangan = [
                        "1000" => "VIP 1",
                        "2000" => "VIP 2",
                        "3000" => "VVIP 1",
                        "4000" => "Apotek",
                        "5000" => "Gudang"
                    ];

                    foreach ($optionsruangan as $valueruangan => $textruangan) {
                        if ($valueruangan === $stok_keluar_data['asal_ruangan']) {
                            echo '<option value="' . $valueruangan . '" selected>' . $textruangan . '</option>';
                        } else {
                            echo '<option value="' . $valueruangan . '">' . $textruangan . '</option>';
                        }
                    }
                    ?>

                </select>
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Keterangan</label>
                <input type="text" name="keteranganstokkeluar" value="<?= $stok_keluar_data['keterangan'] ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white">
            </div>

            <!-- End Grid -->
            <div class="mt-5 flex flex-col">
                <div class="-m-1.5 overflow-x-auto">
                    <div class="p-1.5 min-w-full inline-block align-middle">
                        <div class="border rounded-lg overflow-hidden dark:border-neutral-700">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700" id="item-list">
                                <?php 
                                    $widths  = [30, 15, 15, 20, 20];
                                    echo view('components/data_tabel_colgroup',['widths' => $widths]);
                                    
                                    $columns = [
                                        'Barang',
                                        'Stok Saat Ini',
                                        'Jumlah Keluar',
                                        'No Faktur',
                                        'No Batch'
                                    ];
                                    // echo view('components/data_tabel_thead',['columns' => $columns]);
                                ?>
                    
                                <thead class="bg-[#DCDCDC]">
                                    <tr class="bg-navy disabled">
                                        <th class="text-center">Barang</th>
                                        <th class="text-center">Stok saat ini</th>
                                        <th class="text-center">Jumlah keluar</th>
                                        <th class="text-center">No Faktur</th>
                                        <th class="text-center">No Batch</th>
                                    </tr>
                                </thead>
                                <tbody class="tabelbodypesanan divide-y divide-gray-200 dark:divide-neutral-700">
                                    <?php foreach ($barang_stok_keluar_data as $brg) { ?>
                                        <input type="hidden" value="<?= $brg['id'] ?>" class="text-center w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)]" step="any" name="idtransaksibrgmedis[]" />
                                        <tr>
                                            <td class="align-middle p-1">
                                                <?php $total_stok = 0;
                                                foreach ($medis_data as $brgmedis) {
                                                    if ($brg['id_barang_medis'] === $brgmedis['id']) { ?>
                                                        <input type="hidden" value="<?= $brg['id_barang_medis'] ?>" class="text-center w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)]" step="any" name="idbrgmedis[]" />
                                                        <input type="text" value="<?= $brgmedis['nama'] ?>" data-stok="<?= $brgmedis['stok'] ?>" class="text-center w-full border bg-[#F6F6F6] cursor-default rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)]" step="any" name="idbrgmedis[]" readonly />

                                            </td>
                                            <td class="align-middle p-1 text-center">
                                                <input type="number" value="<?php $stok_barang_medis = $brgmedis['stok'];
                                                                            $total_stok = $stok_barang_medis;
                                                                            echo $total_stok;
                                                                        }
                                                                    } ?>" class="text-center w-full border bg-[#F6F6F6] cursor-default rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)]" step="any" name="stoksaatini[]" readonly />
                                            </td>
                                            <td class="align-middle p-1 text-center">
                                                <input type="number" value="<?= $brg['jumlah_keluar'] ?>" class="text-center w-full border bg-[#F6F6F6] rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)]" name="jlhkeluar[]" required readonly />
                                            </td>
                                            <td class="align-middle p-1 text-center">
                                                <input type="text" list="nofaktur" value="<?= $brg['no_faktur'] ?>" class="text-center w-full border bg-[#F6F6F6] rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)]" name="nofaktur[]" readonly />
                                                <datalist id="nofaktur">
                                                    <?php foreach ($penerimaan_data as $penerimaan) : ?>
                                                        <option value="<?= $penerimaan['no_faktur'] ?>"></option>
                                                    <?php endforeach; ?>
                                                </datalist>

                                            </td>
                                            <td class="align-middle p-1 text-center">
                                                <input type="text" list="nobatch" value="<?= $brg['no_batch'] ?>" class="text-center w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)]" name="nobatch[]" />
                                                <datalist id="nobatch">
                                                    <?php foreach ($pesanan_data as $pesanan) : ?>
                                                        <option value="<?= $pesanan['no_batch'] ?>"></option>
                                                    <?php endforeach; ?>
                                                </datalist>
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
    function validateJumlahKeluar(input) {
        // Get the input field for jumlah keluar
        var jumlahKeluar = parseInt(input.value) || 0; // Default to 0 if input value is not a number

        // Get the total stock from 'stoksaatini[]'
        var stokSaatIni = parseInt(input.closest('tr').querySelector('input[name="stoksaatini[]"]').value) || 0; // Default to 0 if value is not a number

        // Validate if jumlah keluar is greater than total stock
        if (jumlahKeluar > stokSaatIni) {
            // Display an error message or handle the validation as per your requirement
            alert('Jumlah keluar tidak boleh melebihi stok saat ini.');
            // Optionally, reset the value or take corrective action
            input.value = ''; // Clear the input value or set to 0
            input.focus(); // Optionally, focus back on the input field for correction
        }
        // Optionally, you can enable/disable submit button based on validation result
        // document.getElementById('submitBtn').disabled = (jumlahKeluar > stokSaatIni);
    }

    document.getElementById('formId').addEventListener('submit', function(event) {
        // Dapatkan semua input jumlah keluar
        var inputsJumlahKeluar = document.getElementsByName('jlhkeluar[]');

        // Flag untuk menentukan apakah form bisa di-submit atau tidak

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