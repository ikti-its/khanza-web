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
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Jenis</label>
                <select id="jenis" name="jenisbrgmedis" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" required>
                    <option value="" selected>-</option>
                    <option value="Obat">Obat</option>
                    <option value="Alat Kesehatan">Alat Kesehatan</option>
                    <option value="Bahan Habis Pakai">Bahan Habis Pakai</option>
                    <option value="Darah">Darah</option>
                </select>
            </div>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Nama</label>
                <input type="text" name="nama" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" maxlength="50" required>
            </div>


            <!-- Obat -->
            <div class="additionalInputObat hidden">
                <div class="mb-5 sm:block md:flex items-center">
                    <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Industri Farmasi</label>
                    <select name="industrifarmasi" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" required>
                        <option value="" selected>-</option>
                        <option value="1000">Kalbe Farma</option>
                    </select>
                </div>

                <div class="mb-5 sm:block md:flex items-center">
                    <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Kandungan</label>
                    <input type="text" name="kandungan" value="-" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" maxlength="100" required>
                </div>
                <div class="mb-5 sm:block md:flex items-center">
                    <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white w-1/5 lg:w-1/4">Isi</label>
                    <input type="text" name="isi" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" maxlength="3">
                    <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Satuan Besar</label>
                    <select name="satuanobat" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
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
                    <select name="jenisobat" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
                        <option value="1" selected>-</option>
                        <option value="1000">Obat Oral</option>
                        <option value="2000">Obat Topikal</option>
                        <option value="3000">Obat Injeksi</option>
                        <option value="4000">Obat Sublingual</option>
                    </select>
                </div>
                <div class="mb-5 sm:block md:flex items-center">
                    <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Kategori</label>
                    <select name="kategoriobat" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
                        <option value="1" selected>-</option>
                        <option value="1000">Obat Paten</option>
                        <option value="2000">Obat Generik</option>
                        <option value="3000">Obat Merek</option>
                        <option value="4000">Obat Eksklusif</option>
                    </select>
                </div>

                <div class="mb-5 sm:block md:flex items-center">
                    <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Golongan</label>
                    <select name="golonganobat" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
                        <option value="1" selected>-</option>
                        <option value="1000">Analgesik</option>
                        <option value="2000">Antibiotik</option>
                        <option value="3000">Antijamur</option>
                        <option value="4000">Antivirus</option>
                    </select>
                </div>
                <div class="mb-5 sm:block md:flex items-center">
                    <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Tanggal Kadaluwarsa</label>
                    <input type="date" name="kadaluwarsaobat" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white">
                </div>
            </div>

            <div class="additionalInputAlkes hidden">
                <div class="mb-5 sm:block md:flex items-center">
                    <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Satuan</label>
                    <select name="satuanalkes" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
                        <?php foreach ($satuan_data as $satuan) : ?>
                            <option value="<?= $satuan['id'] ?>"><?= $satuan['nama'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-5 sm:block md:flex items-center">
                    <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Merek</label>
                    <select name="merekalkes" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
                        <option value="" selected>-</option>
                        <?php
                        $companies = array('Omron', 'Philips', 'GE Healthcare', 'Siemens Healthineers', 'Medtronic', 'Johnson & Johnson', 'Becton', 'Dickinson and Company (BD)', 'Stryker', 'Boston Scientific', 'Olympus Corporation', 'Roche Diagnostics');
                        foreach ($companies as $company) : ?>
                            <option value="<?= $company ?>"><?= $company ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="additionalInputBHP hidden">
                <div class="mb-5 sm:block md:flex items-center">
                    <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Jumlah</label>
                    <input type="text" name="jumlahbhp" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" maxlength="3">
                </div>

                <div class="mb-5 sm:block md:flex items-center">
                    <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Satuan</label>
                    <select name="satuanbhp" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
                        <?php foreach ($satuan_data as $satuan) : ?>
                            <option value="<?= $satuan['id'] ?>"><?= $satuan['nama'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-5 sm:block md:flex items-center">
                    <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Tanggal Kadaluwarsa</label>
                    <input type="date" name="kadaluwarsabhp" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white">
                </div>
            </div>

            <div class="additionalInputDarah hidden">
                <div class="mb-5 sm:block md:flex items-center">
                    <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Satuan</label>
                    <select name="satuandarah" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
                        <?php foreach ($satuan_data as $satuan) : ?>
                            <option value="<?= $satuan['id'] ?>"><?= $satuan['nama'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-5 sm:block md:flex items-center">
                    <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Jenis Darah</label>
                    <select name="jenisdarah" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white">
                        <option value="" selected>-</option>
                        <option value="Whole Blood (WB)">Whole Blood (WB)</option>
                        <option value="Packed Red Cell (PRC)">Packed Red Cell (PRC)</option>
                        <option value="Thrombocyte Concentrate (TC)">Thrombocyte Concentrate (TC)</option>
                        <option value="Fresh Frozen Plasma (FFP)">Fresh Frozen Plasma (FFP)</option>
                    </select>
                </div>
                <div class="mb-5 sm:block md:flex items-center">
                    <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Keterangan</label>
                    <input type="text" name="keterangandarah" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" maxlength="100" placeholder="Jika kosong diisi -">
                </div>
                <div class="mb-5 sm:block md:flex items-center">
                    <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Tanggal Kadaluwarsa</label>
                    <input type="date" name="kadaluwarsadarah" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white">
                </div>
            </div>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Harga</label>
                <input type="number" name="harga" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" placeholder="0">
            </div>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Stok</label>
                <input type="number" name="stok" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" maxlength="4" placeholder="0">
            </div>
            <div class="mb-5 sm:block md:flex items-center">

                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Notifikasi kadaluwarsa (hari)</label>
                <input type="number" name="notifkadaluwarsa" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" placeholder="30">
                <div class="hs-tooltip [--trigger:hover] [--placement:right] inline-block">
                    <button type="button" class="hs-tooltip-toggle flex justify-center items-center size-10 text-sm font-semibold rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-800">
                        <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m9 18 6-6-6-6"></path>
                        </svg>
                        <span class="hs-tooltip-content hs-tooltip-shown:opacity-100 hs-tooltip-shown:visible opacity-0 transition-opacity inline-block absolute invisible z-10 py-3 px-4 max-w-[200px] whitespace-normal bg-white border text-sm text-gray-600 rounded-lg shadow-md dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400" role="tooltip">
                            Notifikasi akan dikirim sesuai dengan jumlah hari yang ditentukan sebelum tanggal kadaluwarsa. Defaultnya adalah 30 hari sebelum tanggal kadaluwarsa.
                        </span>
                    </button>
                </div>
            </div>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Stok minimum</label>
                <input type="number" name="stokminimum" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" placeholder="0">
                <div class="hs-tooltip [--trigger:hover] [--placement:right] inline-block">
                    <button type="button" class="hs-tooltip-toggle flex justify-center items-center size-10 text-sm font-semibold rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-800">
                        <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m9 18 6-6-6-6"></path>
                        </svg>
                        <span class="hs-tooltip-content hs-tooltip-shown:opacity-100 hs-tooltip-shown:visible opacity-0 transition-opacity inline-block absolute invisible z-10 py-3 px-4 max-w-[200px] whitespace-normal bg-white border text-sm text-gray-600 rounded-lg shadow-md dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400" role="tooltip">
                            Notifikasi akan dikirim sesuai dengan jumlah minimum stok. Defaultnya adalah 0.
                        </span>
                    </button>
                </div>
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