<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<!-- Card Section -->
<div class="max-w-[85rem] py-6 lg:py-3 px-8 mx-auto">
    <!-- Card -->
    <div class="bg-white rounded-xl shadow p-4 sm:p-7 dark:bg-slate-900">
        <div class="mb-8">
            <h2 class="text-xl font-bold text-gray-800 dark:text-gray-200">
                Tambah Data Barang Medis
            </h2>
        </div>
        <form action="/datamedis/submittambah/" id="myForm" onsubmit="return validateForm()" method="post">
            <?= csrf_field() ?>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Kode Barang</label>
                <input type="text" name="kode" value="<?php function generateUniqueNumber($length = 15)
                                                        {
                                                            $characters = '1234567890';
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

                                                        $nomor = "B" . $tanggalHariIni . generateUniqueNumber();
                                                        echo $nomor; ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" maxlength="80" required>
                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Nama</label>
                <input name="nama" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white">
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white w-1/5 lg:w-1/4">Kandungan</label>
                <input type="text" name="kandungan" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" maxlength="100" required>
                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">I.F</label>
                <select name="indusfarmasi" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
                    <?php foreach ($if_data as $if) : ?>
                        <option value="<?= $if['id'] ?>"><?= $if['nama'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white w-1/5 lg:w-1/4">Isi</label>
                <input type="text" name="isi" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" maxlength="3">
                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Satuan Besar</label>
                <select name="satuan" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
                    <?php foreach ($satuan_data as $satuan) : ?>
                        <option value="<?= $satuan['id'] ?>"><?= $satuan['nama'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white w-1/5 lg:w-1/4">Kapasitas</label>
                <input type="text" name="kapasitas" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" maxlength="3">
                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Satuan Kecil</label>
                <select name="satkecil" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
                    <?php foreach ($satuan_data as $satuan) : ?>
                        <option value="<?= $satuan['id'] ?>"><?= $satuan['nama'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Jenis</label>
                <select name="jenis" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
                    <?php foreach ($jenis_data as $jenis) : ?>
                        <option value="<?= $jenis['id'] ?>"><?= $jenis['nama'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Kategori</label>
                <select name="kategori" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
                    <?php foreach ($kategori_data as $kategori) : ?>
                        <option value="<?= $kategori['id'] ?>"><?= $kategori['nama'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Golongan</label>
                <select name="golongan" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
                    <?php foreach ($golongan_data as $golongan) : ?>
                        <option value="<?= $golongan['id'] ?>"><?= $golongan['nama'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Harga Dasar</label>
                <input type="number" name="hargadasar" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" placeholder="0">
                <label class="block w-full mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white md:w-1/5">Harga Beli</label>
                <input name="hargabeli" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" placeholder="0">
            </div>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Harga Ralan</label>
                <input type="number" name="hargaralan" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" placeholder="0">
                <label class="block w-full mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white md:w-1/5">Harga Rnp Kelas 2</label>
                <input name="hargakelas2" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" placeholder="0">
            </div>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Harga Rnp Kelas 3</label>
                <input type="number" name="hargakelas3" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" placeholder="0">
                <label class="block w-full mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white md:w-1/5">Harga Rnp Utama/BPJS</label>
                <input name="hargautama" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" placeholder="0">
            </div>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Harga Rnp Kelas VIP</label>
                <input type="number" name="hargavip" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" placeholder="0">
                <label class="block w-full mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white md:w-1/5">Harga Rnp Kelas VVIP</label>
                <input name="hargavvip" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" placeholder="0">
            </div>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Harga Apotek Luar</label>
                <input type="number" name="hargaapotekluar" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" placeholder="0">
                <label class="block w-full mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white md:w-1/5">Harga Jual Obat Bebas</label>
                <input name="hargaobatbebas" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" placeholder="0">
            </div>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Harga Karyawan</label>
                <input type="number" name="hargakaryawan" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" placeholder="0">

            </div>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Stok minimum</label>
                <input type="number" name="stokminimum" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" placeholder="0">

            </div>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Tanggal Kadaluwarsa</label>
                <input type="date" name="kadaluwarsa" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white">
            </div>
            <div class="mt-5 pt-5 border-t flex justify-end gap-x-2">
                <a href="javascript:history.back()" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-white dark:hover:bg-gray-800 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                    Kembali
                </a>
                <button type="submit" id="submitButton" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-[#0A2D27] text-[#ACF2E7] hover:bg-[#13594E] disabled:opacity-50 disabled:pointer-events-none dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                    Simpan
                </button>
            </div>
        </form>
    </div>
    <!-- End Card -->
</div>
<!-- End Card Section -->
<script>
    document.getElementById('jenis').addEventListener('change', function() {
        var jenisValue = this.value;
        var additionalInputObats = document.getElementsByClassName('additionalInputObat');
        var additionalInputAlkess = document.getElementsByClassName('additionalInputAlkes');
        var additionalInputBHPs = document.getElementsByClassName('additionalInputBHP');
        var additionalInputDarahs = document.getElementsByClassName('additionalInputDarah');

        // Utility function to toggle required attribute
        function toggleRequired(fields, isRequired) {
            for (var i = 0; i < fields.length; i++) {
                var inputs = fields[i].querySelectorAll('select, input');
                for (var j = 0; j < inputs.length; j++) {
                    inputs[j].required = isRequired;
                }
            }
        }

        // Handle Obat fields
        for (var i = 0; i < additionalInputObats.length; i++) {
            var additionalInputObat = additionalInputObats[i];
            if (jenisValue === 'Obat') {
                additionalInputObat.style.display = 'block';
                toggleRequired([additionalInputObat], true);
            } else {
                additionalInputObat.style.display = 'none';
                toggleRequired([additionalInputObat], false);
            }
        }

        // Handle Alat Kesehatan fields
        for (var j = 0; j < additionalInputAlkess.length; j++) {
            var additionalInputAlkes = additionalInputAlkess[j];
            if (jenisValue === 'Alat Kesehatan') {
                additionalInputAlkes.style.display = 'block';
                toggleRequired([additionalInputAlkes], true);
            } else {
                additionalInputAlkes.style.display = 'none';
                toggleRequired([additionalInputAlkes], false);
            }
        }

        // Handle Bahan Habis Pakai fields
        for (var k = 0; k < additionalInputBHPs.length; k++) {
            var additionalInputBHP = additionalInputBHPs[k];
            if (jenisValue === 'Bahan Habis Pakai') {
                additionalInputBHP.style.display = 'block';

            } else {
                additionalInputBHP.style.display = 'none';

            }
        }

        // Handle Darah fields
        for (var l = 0; l < additionalInputDarahs.length; l++) {
            var additionalInputDarah = additionalInputDarahs[l];
            if (jenisValue === 'Darah') {
                additionalInputDarah.style.display = 'block';
                toggleRequired([additionalInputDarah], true);
            } else {
                additionalInputDarah.style.display = 'none';
                toggleRequired([additionalInputDarah], false);
            }
        }
    });

    function validateForm() {
        var requiredFields = document.querySelectorAll('select[required], input[required]');
        for (var i = 0; i < requiredFields.length; i++) {
            if (!requiredFields[i].value) {
                alert("Isi semua field.");
                return false;
            }
        }
        var submitButton = document.getElementById('submitButton');
        submitButton.setAttribute('disabled', true);
        // Ubah teks tombol menjadi sesuatu yang menunjukkan proses sedang berlangsung, misalnya "Menyimpan..."
        submitButton.innerHTML = 'Menyimpan...';
        return true;
    }
</script>
<?= $this->endSection(); ?>