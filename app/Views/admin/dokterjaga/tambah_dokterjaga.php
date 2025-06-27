<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<!-- Card Section -->
<div class="max-w-[85rem] py-6 lg:py-3 px-8 mx-auto">
    <!-- Card -->
    <div class="bg-white rounded-xl shadow p-4 sm:p-7 dark:bg-slate-900">
        <?= view('components/form/judul', [
            'judul' => 'Tambah Dokter Jaga'
        ]) ?>
        <form action="/dokterjaga/submittambah/" id="myForm" onsubmit="return validateForm()" method="post">

            <?= csrf_field() ?>
            <?php
            $kodeDokter = 'D' . str_pad(mt_rand(0, 999), 3, '0', STR_PAD_LEFT);
            ?>
            <div class="mb-5 sm:block md:flex items-center">
            <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Kode Dokter</label>
                <input name="kode_dokter" id="kode_dokter" value="<?= $kodeDokter ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" required>
                    

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Nama Dokter</label>
                <input type="text" name="nama_dokter" id="namaDokterInput" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" maxlength="80" required>
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white w-1/5 lg:w-1/4">Hari Kerja</label>
                <select type="text" name="hari_kerja" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" maxlength="80" required>
                    <option value="">Pilih Hari</option>
                    <option value="Senin">Senin</option>
                    <option value="Selasa" >Selasa</option>
                    <option value="Rabu" >Rabu</option>
                    <option value="Kamis">Kamis</option>
                    <option value="Jumat" >Jumat</option>
                    <option value="Sabtu" >Sabtu</option>
                </select>
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <!-- Jam Mulai -->
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white w-1/5 lg:w-1/4">Jam Mulai</label>
                <input type="time" name="jam_mulai" step="1" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" required>

                <!-- Jam Selesai -->
                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Jam Selesai</label>
                <input type="time" name="jam_selesai" step="1" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" required>
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white w-1/5 lg:w-1/4">Poliklinik</label>
                <input type="text" name="poliklinik" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" maxlength="80" required>
            
                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Status</label>
                <select type="time" name="status" step="1" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" required>
                    <option value="aktif" >Aktif</option>
                    <option value="tidak aktif" >Tidak Aktif</option>
                </select>
            </div>
            
            <?= view('components/form/submit_button') ?>
        </form>
    </div>
    <!-- End Card -->
</div>
<!-- End Card Section -->
<script>

    document.getElementById('kodeDokterSelect').addEventListener('change', function () {
    const selectedOption = this.options[this.selectedIndex];
    const namaDokter = selectedOption.getAttribute('data-nama');
    document.getElementById('namaDokterInput').value = namaDokter || '';
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