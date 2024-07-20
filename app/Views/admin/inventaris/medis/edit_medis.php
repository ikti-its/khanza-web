<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<!-- Card Section -->
<div class="max-w-[85rem] py-6 lg:py-3 px-8 mx-auto">
    <!-- Card -->
    <div class="bg-white rounded-xl shadow p-4 sm:p-7 dark:bg-slate-900">
        <div class="mb-8">
            <h2 class="text-xl font-bold text-gray-800 dark:text-gray-200">
                Ubah Data Barang Medis
            </h2>

        </div>

        <form action="/datamedis/submitedit/<?= $medis_data['id'] ?>" onsubmit="return validateForm()" method="post">
            <?= csrf_field() ?>
            <input name="idbrgmedis" value="<?= $medis_data['id'] ?>" type="hidden">


            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Nama</label>
                <input type="text" name="nama" value="<?= $medis_data['nama'] ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white">
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Kandungan</label>
                <input type="text" name="kandungan" value="<?= $medis_data['kandungan'] ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white">
                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">I.F</label>
                <select name="satuanobat" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
                    <?php
                    $optionsIF = ["1000" => "Kalbe Farma"];
                    foreach ($optionsIF as $valueIF => $textIF) {
                        // Periksa jika nilai opsi adalah sama dengan nilai medis_data
                        if ($valueIF === $medis_data['industri_farmasi']) {

                            echo '<option value="' . $valueIF . '" selected>' . $textIF . '</option>';
                        } else {
                            echo '<option value="' . $valueIF . '">' . $textIF . '</option>';
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white w-1/5 lg:w-1/4">Isi</label>
                <input type="text" name="isi" value="<?= $medis_data['isi'] ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white">
                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Satuan Besar</label>
                <select name="satuanbrgmedis" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
                    <?php
                    foreach ($satuan_data as $satuan) {
                        $optionsatuan = [$satuan['id'] => $satuan['nama']];
                        foreach ($optionsatuan as $satuanid => $satuannama) {
                            if ($satuanid === $medis_data['id_satbesar']) {
                                echo '<option value="' . $satuan['id'] . '" selected>' . $satuan['nama'] . '</option>';
                            } else {
                                echo '<option value="' . $satuan['id'] . '">' . $satuan['nama'] . '</option>';
                            }
                        }
                    }
                    ?>
                </select>
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white w-1/5 lg:w-1/4">Kapasitas</label>
                <input type="text" name="kapasitas" value="<?= $medis_data['kapasitas'] ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white">
                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Satuan Kecil</label>
                <select name="satuanobat" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
                    <?php
                    foreach ($satuan_data as $satuan) {
                        $optionsatuan = [$satuan['id'] => $satuan['nama']];
                        foreach ($optionsatuan as $satuanid => $satuannama) {
                            if ($satuanid === $medis_data['id_satuan']) {
                                echo '<option value="' . $satuan['id'] . '" selected>' . $satuan['nama'] . '</option>';
                            } else {
                                echo '<option value="' . $satuan['id'] . '">' . $satuan['nama'] . '</option>';
                            }
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Jenis</label>
                <select name="jenisobat" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
                    <?php
                    $optionsjenis = [
                        "1" => "-",
                        "1000" => "Obat Oral",
                        "2000" => "Obat Topikal",
                        "3000" => "Obat Injeksi",
                        "4000" => "Obat Sublingual"
                    ];

                    foreach ($optionsjenis as $valuejenis => $textjenis) {
                        if ($valuejenis === $medis_data['jenis']) {
                            echo '<option value="' . $valuejenis . '" selected>' . $textjenis . '</option>';
                        } else {
                            echo '<option value="' . $valuejenis . '">' . $textjenis . '</option>';
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Kategori</label>
                <select name="kategoriobat" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
                    <?php
                    $optionskategori = [
                        "1" => "-",
                        "1000" => "Obat Paten",
                        "2000" => "Obat Generik",
                        "3000" => "Obat Merek",
                        "4000" => "Obat Eksklusif"
                    ];

                    foreach ($optionskategori as $valuekategori => $textkategori) {
                        if ($valuekategori === $medis_data['kategori']) {
                            echo '<option value="' . $valuekategori . '" selected>' . $textkategori . '</option>';
                        } else {
                            echo '<option value="' . $valuekategori . '">' . $textkategori . '</option>';
                        }
                    }
                    ?>
                </select>
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Golongan</label>
                <select name="golonganobat" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
                    <?php
                    $optionsgolongan = [
                        "1" => "-",
                        "1000" => "Analgesik",
                        "2000" => "Antibiotik",
                        "3000" => "Antijamur",
                        "4000" => "Antivirus"
                    ];

                    foreach ($optionsgolongan as $valuegolongan => $textgolongan) {
                        if ($valuegolongan === $medis_data['golongan']) {
                            echo '<option value="' . $valuegolongan . '" selected>' . $textgolongan . '</option>';
                        } else {
                            echo '<option value="' . $valuegolongan . '">' . $textgolongan . '</option>';
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Tanggal Kadaluwarsa</label>
                <input type="date" name="kadaluwarsaobat" value="<?= $medis_data['kadaluwarsa'] ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white">
            </div>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Harga Dasar</label>
                <input type="number" name="hargadasar" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" placeholder="0">
                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Harga Beli</label>
                <input name="hargabeli" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" placeholder="0">
            </div>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Harga Ralan</label>
                <input type="number" name="hargaralan" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" placeholder="0">
                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Harga Rnp Kelas 2</label>
                <input name="hargakelas2" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" placeholder="0">
            </div>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Harga Rnp Kelas 3</label>
                <input type="number" name="hargakelas3" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" placeholder="0">
                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Harga Rnp Utama/BPJS</label>
                <input name="hargautama" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" placeholder="0">
            </div>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Harga Rnp Kelas VIP</label>
                <input type="number" name="hargavip" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" placeholder="0">
                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Harga Rnp Kelas VVIP</label>
                <input name="hargavvip" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" placeholder="0">
            </div>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Harga Apotek Luar</label>
                <input type="number" name="hargaapotekluar" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" placeholder="0">
                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Harga Jual Obat Bebas</label>
                <input name="hargaobatbebas" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" placeholder="0">
            </div>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Harga Karyawan</label>
                <input type="number" name="hargakaryawan" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" placeholder="0">

            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Harga</label>
                <input type="number" name="harga" value="<?= $medis_data['harga'] ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white">
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Stok minimum</label>
                <input type="number" name="stok_minimum" value="<?= $medis_data['stok_minimum'] ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white">
            </div>
            <!-- Grid -->

            <!-- End Grid -->

            <div class="mt-5 flex justify-end gap-x-2">
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
    function validateForm() {
        var submitButton = document.getElementById('submitButton');
        submitButton.setAttribute('disabled', true);
        // Ubah teks tombol menjadi sesuatu yang menunjukkan proses sedang berlangsung, misalnya "Menyimpan..."
        submitButton.innerHTML = 'Menyimpan...';
        return true;
    }
</script>
<?= $this->endSection(); ?>