<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<!-- Card Section -->
<div class="max-w-[85rem] py-6 lg:py-3 mx-auto">
    <!-- Card -->
    <div class="bg-white rounded-xl shadow p-4 sm:p-7 dark:bg-slate-900">
        <div class="mb-8">
            <h2 class="text-xl font-bold text-gray-800 dark:text-gray-200">
                Edit Data Barang Medis
            </h2>

        </div>

        <form action="/submiteditmedis/<?= $medis_data['id'] ?>" method="post">
        <?= csrf_field() ?>   
        <input name="idbrgmedis" value="<?= $medis_data['id'] ?>" type="hidden">
            <input name="idjenisbrgmedis" value="<?= $jenis_data['id'] ?>" type="hidden">

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Jenis</label>
                <input type="text" name="jenisbrgmedis" value="<?= $medis_data['jenis'] ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" readonly>
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Nama</label>
                <input type="text" name="nama" value="<?= $medis_data['nama'] ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white">
            </div>

            <?php if ($medis_data['jenis'] === 'Obat') : ?>
                <div class="mb-5 sm:block md:flex items-center">
                    <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Industri Farmasi</label>
                    <select name="industrifarmasi" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" required>
                        <?php
                        $optionsIF = ["1000" => "Kalbe Farma"];
                        foreach ($optionsIF as $valueIF => $textIF) {
                            // Periksa jika nilai opsi adalah sama dengan nilai medis_data
                            if ($valueIF === $jenis_data['industri_farmasi']) {

                                echo '<option value="' . $valueIF . '" selected>' . $textIF . '</option>';
                            } else {
                                echo '<option value="' . $valueIF . '">' . $textIF . '</option>';
                            }
                        }
                        ?>
                    </select>
                </div>

                <div class="mb-5 sm:block md:flex items-center">
                    <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Kandungan</label>
                    <input type="text" name="kandungan" value="<?= $jenis_data['kandungan'] ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white">
                </div>
                <div class="mb-5 sm:block md:flex items-center">
                    <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white w-1/5 lg:w-1/4">Isi</label>
                    <input type="text" name="isi" value="<?= $jenis_data['isi'] ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white">
                    <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Satuan Besar</label>
                    <select name="satuanbrgmedis" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
                        <?php
                        foreach ($satuan_data as $satuan) {
                            $optionsatuan = [$satuan['id'] => $satuan['nama']];
                            foreach ($optionsatuan as $satuanid => $satuannama) {
                                if ($satuanid === $jenis_data['satuan']) {
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
                    <input type="text" name="kapasitas" value="<?= $jenis_data['kapasitas'] ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white">
                    <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Satuan Kecil</label>
                    <select name="satuanobat" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
                        <?php
                        foreach ($satuan_data as $satuan) {
                            $optionsatuan = [$satuan['id'] => $satuan['nama']];
                            foreach ($optionsatuan as $satuanid => $satuannama) {
                                if ($satuanid === $jenis_data['satuan']) {
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
                    <select name="jenisobat" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
                        <?php
                        $optionsjenis = [
                            "1000" => "Obat Oral",
                            "2000" => "Obat Topikal",
                            "3000" => "Obat Injeksi",
                            "4000" => "Obat Sublingual"
                        ];

                        foreach ($optionsjenis as $valuejenis => $textjenis) {
                            if ($valuejenis === $jenis_data['jenis']) {
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
                    <select name="kategoriobat" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
                        <?php
                        $optionskategori = [
                            "1000" => "Obat Paten",
                            "2000" => "Obat Generik",
                            "3000" => "Obat Merek",
                            "4000" => "Obat Eksklusif"
                        ];

                        foreach ($optionskategori as $valuekategori => $textkategori) {
                            if ($valuekategori === $jenis_data['kategori']) {
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
                    <select name="golonganobat" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
                        <?php
                        $optionsgolongan = [
                            "1000" => "Analgesik",
                            "2000" => "Antibiotik",
                            "3000" => "Antijamur",
                            "4000" => "Antivirus"
                        ];

                        foreach ($optionsgolongan as $valuegolongan => $textgolongan) {
                            if ($valuegolongan === $jenis_data['golongan']) {
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
                    <input type="date" name="kadaluwarsaobat" value="<?= $jenis_data['kadaluwarsa'] ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white">
                </div>
                <div class="mb-5 sm:block md:flex items-center">
                    <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Notifikasi kadaluwarsa</label>
                    <input type="number" name="notif_kadaluwarsa" value="<?= $medis_data['notifikasi_kadaluwarsa_hari'] ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white">
                </div>
            <?php elseif ($medis_data['jenis'] === 'Alat Kesehatan') : ?>
                <div class="mb-5 sm:block md:flex items-center">
                    <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Satuan</label>
                    <select name="satuanbrgmedis" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
                        <?php
                        foreach ($satuan_data as $satuan) {
                            $optionsatuan = [$satuan['id'] => $satuan['nama']];
                            foreach ($optionsatuan as $satuanid => $satuannama) {
                                if ($satuanid === $medis_data['satuan']) {
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
                    <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Merek</label>
                    <select name="merekalkes" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
                        <?php
                        $companies = array(
                            'Omron', 'Philips', 'GE Healthcare', 'Siemens Healthineers', 'Medtronic',
                            'Johnson & Johnson', 'Becton', 'Dickinson and Company (BD)', 'Stryker',
                            'Boston Scientific', 'Olympus Corporation', 'Roche Diagnostics'
                        );
                        foreach ($companies as $company) {
                            if ($company === $jenis_data['merek']) {
                                echo '<option value="' . $company . '" selected>' . $company . '</option>';
                            } else {
                                echo '<option value="' . $company . '">' . $company . '</option>';
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="mb-5 sm:block md:flex items-center">
                    <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Notifikasi kadaluwarsa</label>
                    <input type="number" name="notif_kadaluwarsa" value="<?= $medis_data['notifikasi_kadaluwarsa_hari'] ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white">
                </div>
            <?php elseif ($medis_data['jenis'] === 'Bahan Habis Pakai') : ?>
                <div class="mb-5 sm:block md:flex items-center">
                    <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Jumlah</label>
                    <input type="text" name="jumlahbhp" value="<?= $jenis_data['jumlah'] ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white">
                </div>

                <div class="mb-5 sm:block md:flex items-center">
                    <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Satuan</label>
                    <select name="satuanbrgmedis" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
                        <?php
                        foreach ($satuan_data as $satuan) {
                            $optionsatuan = [$satuan['id'] => $satuan['nama']];
                            foreach ($optionsatuan as $satuanid => $satuannama) {
                                if ($satuanid === $medis_data['satuan']) {
                                    echo '<option value="' . $satuan['id'] . '" selected>' . $satuan['nama'] . '</option>';
                                } else {
                                    echo '<option value="' . $satuan['id'] . '">' . $satuan['nama'] . '</option>';
                                }
                            }
                        }
                        ?>
                    </select>
                </div>
                <?php if ($jenis_data['kadaluwarsa'] !== '0001-01-01') { ?>
                    <div class="mb-5 sm:block md:flex items-center">
                        <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Tanggal Kadaluwarsa</label>
                        <input type="date" name="kadaluwarsabhp" value="<?= $jenis_data['kadaluwarsa'] ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white">
                    </div>
                <?php } else { ?>
                    <input type="hidden" name="kadaluwarsabhp" value="<?= $jenis_data['kadaluwarsa'] ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white">
                <?php } ?>
                <div class="mb-5 sm:block md:flex items-center">
                    <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Notifikasi kadaluwarsa</label>
                    <input type="number" name="notif_kadaluwarsa" value="<?= $medis_data['notifikasi_kadaluwarsa_hari'] ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white">
                </div>
            <?php elseif ($medis_data['jenis'] === 'Darah') : ?>
                <div class="mb-5 sm:block md:flex items-center">
                    <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Satuan</label>
                    <select name="satuanbrgmedis" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
                        <?php
                        foreach ($satuan_data as $satuan) {
                            $optionsatuan = [$satuan['id'] => $satuan['nama']];
                            foreach ($optionsatuan as $satuanid => $satuannama) {
                                if ($satuanid === $medis_data['satuan']) {
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
                    <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Jenis Darah</label>
                    <select name="jenisdarah" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
                        <?php
                        $options = [
                            "Whole Blood (WB)",
                            "Packed Red Cell (PRC)",
                            "Thrombocyte Concentrate (TC)",
                            "Fresh Frozen Plasma (FFP)"
                        ];

                        foreach ($options as $value) {
                            if ($value === $jenis_data['jenis']) {
                                echo '<option value="' . $value . '" selected>' . $value . '</option>';
                            } else {
                                echo '<option value="' . $value . '">' . $value . '</option>';
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="mb-5 sm:block md:flex items-center">
                    <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Keterangan</label>
                    <input type="text" name="keterangandarah" value="<?= $jenis_data['keterangan'] ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white">
                </div>
                <div class="mb-5 sm:block md:flex items-center">
                    <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Tanggal Kadaluwarsa</label>
                    <input type="date" name="kadaluwarsadarah" value="<?= $jenis_data['kadaluwarsa'] ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white">
                </div>
                <div class="mb-5 sm:block md:flex items-center">
                    <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Notifikasi kadaluwarsa</label>
                    <input type="number" name="notif_kadaluwarsa" value="<?= $medis_data['notifikasi_kadaluwarsa_hari'] ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white">
                </div>
            <?php endif; ?>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Harga</label>
                <input type="number" name="harga" value="<?= $medis_data['harga'] ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white">
            </div>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Stok</label>
                <input type="number" name="stok" value="<?= $medis_data['stok'] ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white">
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Stok minimum</label>
                <input type="number" name="stok_minimum" value="<?= $medis_data['stok_minimum'] ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white">
            </div>
            <!-- Grid -->

            <!-- End Grid -->

            <div class="mt-5 flex justify-end gap-x-2">
                <a href="javascript:history.back()" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-white dark:hover:bg-gray-800 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                    Kembali
                </a>
                <button type="submit" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-[#0A2D27] text-[#ACF2E7] hover:bg-[#13594E] disabled:opacity-50 disabled:pointer-events-none dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                    Simpan
                </button>
            </div>
        </form>
    </div>
    <!-- End Card -->
</div>
<!-- End Card Section -->

<?= $this->endSection(); ?>