<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<!-- Card Section -->
<div class="max-w-[85rem] py-6 lg:py-3 px-8 mx-auto">
    <!-- Card -->
    <div class="bg-white rounded-xl shadow p-4 sm:p-7 dark:bg-slate-900">
        <div class="mb-8">
            <h2 class="text-xl font-bold text-gray-800 dark:text-gray-200">
                Tambah Tindakan
            </h2>
        </div>
        <form action="/tindakan/submittambah/" id="myForm" onsubmit="return validateForm()" method="post">
            <?= csrf_field() ?>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Nomor Rekam Medis</label>
                <input type="text" name="nomor_rm" vvalue="<?= $tindakan['nomor_rm'] ?? '' ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" maxlength="80" required>
                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Nomor Rawat</label>
                <input name="nomor_rawat" value="<?= $tindakan['nomor_rawat'] ?? '' ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white">
            </div>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Nama Pasien</label>
                <input type="text" name="nama_pasien" value="<?= $tindakan['nama_pasien'] ?? '' ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" maxlength="80" required>
            </div>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Tindakan</label>
                <input type="text" name="tindakan" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" maxlength="80" required>
            </div>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Dokter</label>
                <input type="text" name="nama_dokter" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" maxlength="80" required>
                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Petugas</label>
                <input name="nama_petugas" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white">
            </div>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Tanggal</label>
                <input type="text" name="diagnosa_awal" value="<?php 
                    $tanggalHariIni = date('Y-m-d');
                    echo $tanggalHariIni; ?>"class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" maxlength="80" required>
                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Jam</label>
                <input name="diagnosa_akhir" value="<?php 
    $jamSekarang = date('H:i:s');
    echo $jamSekarang; ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white">
            </div>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white w-1/5 lg:w-1/4">Biaya</label>
                <input type="text" name="biaya" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" maxlength="80" required>
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
    const inputHargaBeli = document.querySelector('input[name="hargabeli"]');

    // Ambil semua input yang perlu diisi dan diubah
    const inputHargaRalan = document.querySelector('input[name="hargaralan"]');
    const inputHargaKelas1 = document.querySelector('input[name="hargakelas1"]');
    const inputHargaKelas2 = document.querySelector('input[name="hargakelas2"]');
    const inputHargaKelas3 = document.querySelector('input[name="hargakelas3"]');
    const inputHargaUtama = document.querySelector('input[name="hargautama"]');
    const inputHargaVIP = document.querySelector('input[name="hargavip"]');
    const inputHargaVVIP = document.querySelector('input[name="hargavvip"]');
    const inputHargaApotekLuar = document.querySelector('input[name="hargaapotekluar"]');
    const inputHargaObatBebas = document.querySelector('input[name="hargaobatbebas"]');
    const inputHargaKaryawan = document.querySelector('input[name="hargakaryawan"]');

    // Tambahkan event listener untuk input harga beli
    inputHargaBeli.addEventListener('input', function() {
        // Ambil nilai harga beli
        const hargaBeli = parseFloat(inputHargaBeli.value);

        // Pastikan nilai harga beli valid (numerik dan tidak NaN)
        if (!isNaN(hargaBeli)) {
            // Hitung harga ralan dan setiap harga rnp dengan tambahan 15%
            const hargaRalan = hargaBeli * 1.15;
            const hargaKelas1 = hargaBeli * 1.15;
            const hargaKelas2 = hargaBeli * 1.15;
            const hargaKelas3 = hargaBeli * 1.15;
            const hargaUtama = hargaBeli * 1.15;
            const hargaVIP = hargaBeli * 1.15;
            const hargaVVIP = hargaBeli * 1.15;
            const hargaApotekLuar = hargaBeli * 1.15;
            const hargaObatBebas = hargaBeli * 1.15;
            const hargaKaryawan = hargaBeli * 1.15;

            // Masukkan nilai yang dihitung ke dalam masing-masing input
            inputHargaRalan.value = hargaRalan.toFixed(0);
            inputHargaKelas1.value = hargaKelas1.toFixed(0);
            inputHargaKelas2.value = hargaKelas2.toFixed(0);
            inputHargaKelas3.value = hargaKelas3.toFixed(0);
            inputHargaUtama.value = hargaUtama.toFixed(0);
            inputHargaVIP.value = hargaVIP.toFixed(0);
            inputHargaVVIP.value = hargaVVIP.toFixed(0);
            inputHargaApotekLuar.value = hargaApotekLuar.toFixed(0);
            inputHargaObatBebas.value = hargaObatBebas.toFixed(0);
            inputHargaKaryawan.value = hargaKaryawan.toFixed(0);
        } else {
            // Jika harga beli tidak valid, atur nilai input lainnya menjadi kosong
            inputHargaRalan.value = '';
            inputHargaKelas1.value = '';
            inputHargaKelas2.value = '';
            inputHargaKelas3.value = '';
            inputHargaUtama.value = '';
            inputHargaVIP.value = '';
            inputHargaVVIP.value = '';
            inputHargaApotekLuar.value = '';
            inputHargaObatBebas.value = '';
            inputHargaKaryawan.value = '';
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