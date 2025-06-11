<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<!-- Card Section -->
<div class="max-w-[85rem] py-6 lg:py-3 px-8 mx-auto">
    <!-- Card -->
    <div class="bg-white rounded-xl shadow p-4 sm:p-7 dark:bg-slate-900">
        <div class="mb-8">
            <h2 class="text-xl font-bold text-gray-800 dark:text-gray-200">
                Edit Pasien UGD 
            </h2>
        </div>
        <form action="<?= site_url('ugd/submitedit/' . $ugd['nomor_reg']) ?>" onsubmit="return validateForm()" method="post">
            <?= csrf_field() ?>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Nomor Registrasi</label>
                <input type="text" name="nomor_reg" value="<?= $ugd['nomor_reg'] ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" maxlength="80" required>
                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Nomor Rawat</label>
                <input name="nomor_rawat" value="<?= $ugd['nomor_rawat'] ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white">
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white w-1/5 lg:w-1/4">Tanggal</label>
                <input type="text" name="tanggal" value="<?= $ugd['tanggal'] ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" maxlength="80" required>
                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Jam</label>
                <input type="text" name="jam" value="<?= $ugd['jam'] ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" maxlength="80" required>
            </div>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white w-1/5 lg:w-1/4">Nomor Rekam Medis</label>
                <input type="text" name="nomor_rekam_medis" value="<?= $ugd['nomor_rm'] ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" maxlength="3">
                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Nama</label>
                <input name="nama" value="<?= $ugd['nama_pasien'] ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white">
                </select>
            </div>

            <div class="mb-5 sm:block md:flex items-center">
            <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Jenis Kelamin</label>
                <select name="jenis_kelamin" value="<?= $ugd['jenis_kelamin'] ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
                    <option value="L">Laki-laki</option>
                    <option value="P">Perempuan</option>
                </select>
                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Umur</label>
                <input name="umur" value="<?= $ugd['umur'] ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white">
            </div>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Poliklinik</label>
                <select name="poliklinik" value="<?= $ugd['poliklinik'] ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
                    <option value="Poli Umum">Poli Umum</option>
                </select>
                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Dokter</label>
                <select name="dokter" id="dokter" value="<?= $ugd['dokter_dituju'] ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
                    <option value="Dr. Ahmad">Dr. Ahmad</option>
                    <option value="Dr. Budi">Dr. Budi</option>
                </select>
            </div>

            <div class="mb-5 sm:block md:flex items-center">
            <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white w-1/5 lg:w-1/4">Penanggung Jawab</label>
            <input type="text" name="penanggung_jawab" value="<?= $ugd['penanggung_jawab'] ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white">
                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Hubungan Penanggung Jawab</label>
                <select name="hubungan_penanggung_jawab" value="<?= $ugd['hubungan_pj'] ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
                    <option value="Diri Sendiri">Diri Sendiri</option>
                    <option value="Ayah">Ayah</option>
                    <option value="Ibu">Ibu</option>
                    <option value="Saudara">Saudara</option>
                    <option value="Teman">Teman</option>
                    <option value="Lainnya">Lainnya</option>
                </select>
            </div>

            <div class="mb-5 sm:block md:flex items-center">
            <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white w-1/5 lg:w-1/4">Alamat Penanggung Jawab</label>
            <input type="text" name="alamat_penanggung_jawab" value="<?= $ugd['alamat_pj'] ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white">
    
            </div>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Biaya Registrasi</label>
                <input type="number" name="biaya_registrasi" value="<?= $ugd['biaya_registrasi'] ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" placeholder="0">

                
            </div>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Status Rawat</label>
                <select name="status_rawat" value="<?= $ugd['status_rawat'] ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
                    <option value="Belum">Belum</option>
                    <option value="Sudah">Sudah</option>
                </select>
                
            </div>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Jenis Bayar</label>
                <select name="jenis_bayar" value="<?= $ugd['jenis_bayar'] ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
                    <option value="BPJS">BPJS</option>
                    <option value="non-BPJS">non-BPJS</option>
                </select>
                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Status Bayar</label>
                <select name="status_bayar" value="<?= $ugd['status_bayar'] ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
                    <option value="Belum Bayar">Belum Bayar</option>
                    <option value="Sudah Bayar">Sudah Bayar</option>
                </select>
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