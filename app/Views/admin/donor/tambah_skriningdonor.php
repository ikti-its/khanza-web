<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<?= $this->include('components/modal/modalkunjungan') ?>

<div class="max-w-[85rem] py-6 lg:py-3 px-8 mx-auto">
    <div class="bg-white rounded-xl shadow p-4 sm:p-7 dark:bg-slate-900">
        <?= view('components/form/judul', [
            'judul' => $judul
        ]) ?>
        
        <form action="<?= $modul_path . $form_action ?>" id="myForm" onsubmit="return validateForm()" method="post">
            <?= csrf_field() ?>

            <input type="hidden" name="id_kunjungan" id="id_kunjungan" value="<?= $baris['id_kunjungan'] ?? '' ?>" required>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">
                    Nomor Kunjungan<span class="text-red-600">*</span>
                </label>
                <div class="w-full lg:w-1/4 flex gap-x-2">
                    <?php $isEdit = (str_contains($judul, 'Ubah')); ?>
                    <input type="text" id="nomor_kunjungan" name="nomor_kunjungan" readonly required
                           placeholder="Klik cari..."
                           <?= $isEdit ? 'disabled' : 'onclick="open_modalKunjungan()"' ?>
                           class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full dark:border-gray-600 dark:text-white <?= $isEdit ? 'cursor-not-allowed bg-gray-200' : 'cursor-pointer bg-slate-50' ?>">
                    
                    <?php if (!$isEdit) : ?>
                        <button type="button" onclick="open_modalKunjungan()"
                                class="inline-flex justify-center items-center p-2 text-sm font-medium text-white bg-blue-600 rounded-lg border border-transparent hover:bg-blue-700 focus:outline-none transition-all w-10 h-[38px] flex-shrink-0 shadow-sm">
                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </button>
                    <?php endif; ?>
                </div>

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">
                    Nomor Pendonor
                </label>
                <input type="text" id="nomor_pendonor" name="nomor_pendonor" readonly placeholder="Terisi otomatis..."
                       value="<?= $baris['nomor_pendonor'] ?? '' ?>"
                       class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white bg-gray-100 cursor-not-allowed">
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">
                    Nama Lengkap
                </label>
                <input type="text" id="nama" name="nama" readonly placeholder="Terisi otomatis..."
                       value="<?= $baris['nama'] ?? '' ?>"
                       class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white bg-gray-100 cursor-not-allowed">
                
                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">
                    Berat Badan (Kg)<span class="text-red-600">*</span>
                </label>
                <input type="number" step="0.01" name="berat_badan" min="20" max="200" placeholder="0.0" value="<?= $baris['berat_badan'] ?? '' ?>"
                       class="input-vital border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" required>
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">
                    Tekanan Darah (mmHg)<span class="text-red-600">*</span>
                </label>
                <div class="w-full lg:w-1/4 flex items-center gap-x-2">
                    <input type="number" name="sistolik" min="70" max="250" placeholder="Sistolik" value="<?= $baris['sistolik'] ?? '' ?>"
                           class="input-vital border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full text-center dark:border-gray-600 dark:text-white" required>
        
                    <span class="text-gray-500 font-medium">/</span>
        
                    <input type="number" name="diastolik" min="40" max="150" placeholder="Diastolik" value="<?= $baris['diastolik'] ?? '' ?>"
                           class="input-vital border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full text-center dark:border-gray-600 dark:text-white" required>
                </div>

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">
                    Denyut Nadi (x/menit)<span class="text-red-600">*</span>
                </label>
                <input type="number" name="nadi" min="30" max="200" value="<?= $baris['nadi'] ?? '' ?>"
                       class="input-vital border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" required>
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">
                    Suhu Tubuh (°C)<span class="text-red-600">*</span>
                </label>
                <input type="number" step="0.1" name="suhu_tubuh" min="34.0" max="43.0" placeholder="0.0" value="<?= $baris['suhu_tubuh'] ?? '' ?>"
                       class="input-vital border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" required>

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">
                    Kadar Hemoglobin (g/dL)<span class="text-red-600">*</span>
                </label>
                <input type="number" step="0.1" name="kadar_hemoglobin" min="5.0" max="25.0" placeholder="0.0" value="<?= $baris['kadar_hemoglobin'] ?? '' ?>"
                       class="input-vital border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" required>
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">
                    Hasil Anamnesis<span class="text-red-600">*</span>
                </label>
                <select name="id_hasil_anamnesis" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" required>
                    <option value="">-- Pilih --</option>
                    <?php
                    $optionsAnamnesis = [];
                    foreach ($konfig as $field) {
                        if ($field[2] === 'id_hasil_anamnesis') {
                            $optionsAnamnesis = $field[5] ?? [];
                            break;
                        }
                    }
                    foreach ($optionsAnamnesis as $opt) :
                        $selected = ((string)($baris['id_hasil_anamnesis'] ?? '') === (string)$opt[1]) ? 'selected' : '';
                    ?>
                        <option value="<?= $opt[1] ?>" <?= $selected ?>><?= $opt[0] ?></option>
                    <?php endforeach; ?>
                </select>

                <div class="block mt-5 md:my-0 md:ml-10 mb-2 w-1/5"></div>
                <div class="w-full lg:w-1/4"></div>
            </div>

            <?= view('components/form/submit_button') ?>
        </form>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const kunjunganId = "<?= $baris['id_kunjungan'] ?? '' ?>";
        if (kunjunganId !== '') {
            const savedItem = {
                id_kunjungan: kunjunganId,
                nomor_kunjungan: "<?= $baris['nomor_kunjungan'] ?? '' ?>",
                nomor_pendonor: "<?= $baris['nomor_pendonor'] ?? '' ?>",
                nama: "<?= $baris['nama'] ?? '' ?>"
            };
            
            autofillKunjungan(savedItem);
        }

        const vitalFields = document.querySelectorAll('.input-vital');
        vitalFields.forEach(function(input) {
            input.addEventListener('keydown', function(e) {
                if (e.key === '-' || e.key === 'Subtract') {
                    e.preventDefault();
                }
            });

            input.addEventListener('input', function() {
                if (parseFloat(this.value) < 0) {
                    this.value = '';
                    alert("Nilai pemeriksaan skrining tidak boleh negatif!");
                }
            });
        });
    });
    
    function autofillKunjungan(item) {
        document.getElementById('id_kunjungan').value = item.id_kunjungan;
        document.getElementById('nomor_kunjungan').value = item.nomor_kunjungan;
        document.getElementById('nomor_pendonor').value = item.nomor_pendonor;
        document.getElementById('nama').value = item.nama;
    }

    function validateForm() {
        var requiredFields = document.querySelectorAll('select[required], input[required]');
        for (var i = 0; i < requiredFields.length; i++) {
            if (!requiredFields[i].value) {
                alert("Mohon isi semua field yang bertanda bintang.");
                return false;
            }
        }

        const idKunjungan = document.getElementById('id_kunjungan').value;
        if (!idKunjungan) {
            alert("Silakan tentukan data Kunjungan terlebih dahulu melalui modal pencarian.");
            return false;
        }
        
        var submitButton = document.getElementById('submitButton');
        if (submitButton) {
            submitButton.setAttribute('disabled', true);
            submitButton.innerHTML = 'Menyimpan...';
        }
        return true;
    }
</script>

<?= $this->endSection(); ?>