<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<!-- Card Section -->
<div class="max-w-[85rem] py-6 lg:py-3 px-8 mx-auto">
    <!-- Card -->
    <div class="bg-white rounded-xl shadow p-4 sm:p-7 dark:bg-slate-900">
        <div class="mb-8">
            <h2 class="text-xl font-bold text-gray-800 dark:text-gray-200">
                Tambah Rujukan Masuk 
            </h2>
        </div>
        <form action="/rujukanmasuk/submittambah/" id="myForm" onsubmit="return validateForm()" method="post">
            <?= csrf_field() ?>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Nomor Rujuk</label>
                <input type="text" name="nomor_rujuk" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" maxlength="80" required>
                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Nomor Rawat</label>
                <input name="nomor_rawat" value="<?php function generateUniqueNumber($length = 15)
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

                                                        $nomor = "" . $tanggalHariIni . generateUniqueNumber();
                                                        echo $nomor; ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white">
            </div>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Perujuk</label>
                <input type="text" name="perujuk" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" maxlength="80" required>
                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Alamat Perujuk</label>
                <input name="alamat_perujuk" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white">
            </div>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Nomor Rekam Medis</label>
                <input type="text" name="nomor_rm" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" maxlength="80" required>
                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Nama Pasien</label>
                <input name="nama_pasien" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white">
            </div>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Alamat Pasien</label>
                <input type="text" name="alamat" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" maxlength="80" required>
                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Umur</label>
                <input name="umur" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white">
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white w-1/5 lg:w-1/4">Tanggal Masuk</label>
                <input type="text" name="tanggal_masuk" value="<?php 

                                                        $tanggalHariIni = date('Y-m-d');

                                                        echo $tanggalHariIni; ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" maxlength="80" required>
                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Tanggal Keluar</label>
                <input type="text" name="tanggal_keluar" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" maxlength="80" required>
            </div>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white w-1/5 lg:w-1/4">Diagnosa Awal</label>
                <input type="text" name="diagnosa_awal" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" maxlength="255">
            </div>
            <?= view('components/form_submit_button') ?>
        </form>
    </div>
    <!-- End Card -->
</div>
<!-- End Card Section -->
<script>

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