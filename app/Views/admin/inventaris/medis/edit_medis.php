<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<!-- Card Section -->
<div class="max-w-[85rem] py-6 lg:py-3 px-8 mx-auto">
    <!-- Card -->
    <div class="bg-white rounded-xl shadow p-4 sm:p-7 dark:bg-slate-900">
        <?= view('components/form_judul', [
            'judul' => 'Ubah Data Barang Medis'
        ]) ?>
        <form action="/datamedis/submitedit/<?= $medis_data['id'] ?>" onsubmit="return validateForm()" method="post">
            <?= csrf_field() ?>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Kode Barang</label>
                <input type="text" name="kode" value="<?= $medis_data['kode_barang'] ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" maxlength="80" required>
                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Nama</label>
                <input name="nama" value="<?= $medis_data['nama'] ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white">
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white w-1/5 lg:w-1/4">Kandungan</label>
                <input type="text" value="<?= $medis_data['kandungan'] ?>" name="kandungan" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" maxlength="100" required>
                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">I.F</label>
                <select name="indusfarmasi" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
                    <?php
                    foreach ($industri_data as $industri) {
                        $option_industri = [$industri['id'] => $industri['nama']];
                        foreach ($option_industri as $industri_id => $industri_nama) {
                            if ($industri_id === $medis_data['id_industri']) {
                                echo '<option value="' . $industri['id'] . '" selected>' . $industri['nama'] . '</option>';
                            } else {
                                echo '<option value="' . $industri['id'] . '">' . $industri['nama'] . '</option>';
                            }
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white w-1/5 lg:w-1/4">Isi</label>
                <input type="text" value="<?= $medis_data['isi'] ?>" name="isi" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" maxlength="3">
                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Satuan Besar</label>
                <select name="satuan" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
                    <?php
                    foreach ($satuan_data as $satuan) {
                        $option_satuan = [$satuan['id'] => $satuan['nama']];
                        foreach ($option_satuan as $satuan_id => $satuan_nama) {
                            if ($satuan_id === $medis_data['id_satbesar']) {
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
                <input type="text" value="<?= $medis_data['kapasitas'] ?>" name="kapasitas" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" maxlength="3">
                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Satuan Kecil</label>
                <select name="satkecil" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
                    <?php
                    foreach ($satuan_data as $satuan) {
                        $option_satuan = [$satuan['id'] => $satuan['nama']];
                        foreach ($option_satuan as $satuan_id => $satuan_nama) {
                            if ($satuan_id === $medis_data['id_satuan']) {
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
                <select name="jenis" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
                    <?php
                    foreach ($jenis_data as $jenis) {
                        $option_jenis = [$jenis['id'] => $jenis['nama']];
                        foreach ($option_jenis as $jenis_id => $jenis_nama) {
                            if ($jenis_id === $medis_data['id_jenis']) {
                                echo '<option value="' . $jenis['id'] . '" selected>' . $jenis['nama'] . '</option>';
                            } else {
                                echo '<option value="' . $jenis['id'] . '">' . $jenis['nama'] . '</option>';
                            }
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Kategori</label>
                <select name="kategori" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
                    <?php
                    foreach ($kategori_data as $kategori) {
                        $option_kategori = [$kategori['id'] => $kategori['nama']];
                        foreach ($option_kategori as $kategori_id => $kategori_nama) {
                            if ($kategori_id === $medis_data['id_kategori']) {
                                echo '<option value="' . $kategori['id'] . '" selected>' . $kategori['nama'] . '</option>';
                            } else {
                                echo '<option value="' . $kategori['id'] . '">' . $kategori['nama'] . '</option>';
                            }
                        }
                    }
                    ?>
                </select>
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Golongan</label>
                <select name="golongan" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
                    <?php
                    foreach ($golongan_data as $golongan) {
                        $option_golongan = [$golongan['id'] => $golongan['nama']];
                        foreach ($option_golongan as $golongan_id => $golongan_nama) {
                            if ($golongan_id === $medis_data['id_golongan']) {
                                echo '<option value="' . $golongan['id'] . '" selected>' . $golongan['nama'] . '</option>';
                            } else {
                                echo '<option value="' . $golongan['id'] . '">' . $golongan['nama'] . '</option>';
                            }
                        }
                    }
                    ?>
                </select>
                <label class="block w-full mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white md:w-1/5">Harga Dasar</label>
                <input name="hargadasar" value="<?= $medis_data['h_dasar'] ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" placeholder="0">
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Harga Beli</label>
                <input  value="<?= $medis_data['h_beli'] ?>" name="hargabeli" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" placeholder="0">
                <label class="block w-full mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white md:w-1/5">Harga Ralan</label>
                <input name="hargaralan" value="<?= $medis_data['h_ralan'] ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" placeholder="0">
            </div>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Harga Rnp Kelas 1</label>
                <input  value="<?= $medis_data['h_kelas1'] ?>" name="hargakelas1" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" placeholder="0">
                <label class="block w-full mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white md:w-1/5">Harga Rnp Kelas 2</label>
                <input name="hargakelas2" value="<?= $medis_data['h_kelas2'] ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" placeholder="0">
            </div>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Harga Rnp Kelas 3</label>
                <input  value="<?= $medis_data['h_kelas3'] ?>" name="hargakelas3" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" placeholder="0">
                <label class="block w-full mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white md:w-1/5">Harga Rnp Utama/BPJS</label>
                <input name="hargautama" value="<?= $medis_data['h_utama'] ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" placeholder="0">
            </div>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Harga Rnp Kelas VIP</label>
                <input  value="<?= $medis_data['h_vip'] ?>" name="hargavip" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" placeholder="0">
                <label class="block w-full mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white md:w-1/5">Harga Rnp Kelas VVIP</label>
                <input name="hargavvip" value="<?= $medis_data['h_vvip'] ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" placeholder="0">
            </div>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Harga Apotek Luar</label>
                <input  value="<?= $medis_data['h_beliluar'] ?>" name="hargaapotekluar" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" placeholder="0">
                <label class="block w-full mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white md:w-1/5">Harga Jual Obat Bebas</label>
                <input name="hargaobatbebas" value="<?= $medis_data['h_jualbebas'] ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" placeholder="0">
            </div>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Harga Karyawan</label>
                <input  value="<?= $medis_data['h_karyawan'] ?>" name="hargakaryawan" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" placeholder="0">

            </div>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Stok minimum</label>
                <input  value="<?= $medis_data['stok_minimum'] ?>" name="stokminimum" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" placeholder="0">

            </div>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Tanggal Kadaluwarsa</label>
                <?php if ($medis_data['kadaluwarsa'] === '0001-01-01') : ?>
                    <input type="date" value="" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" name="kadaluwarsa" />
                <?php else : ?>
                    <input type="date" value="<?= $medis_data['kadaluwarsa'] ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" name="kadaluwarsa" />
                <?php endif; ?>

            </div>
            <?= view('components/form_submit_button') ?>
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