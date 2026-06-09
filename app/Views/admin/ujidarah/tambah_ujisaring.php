<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<?= $this->include('components/modal/modalpetugas') ?>

<div class="max-w-[85rem] py-6 lg:py-3 px-8 mx-auto">
    <div class="bg-white rounded-xl shadow p-4 sm:p-7 dark:bg-slate-900">
        <?= view('components/form/judul', [
            'judul' => $judul
        ]) ?>
        
        <form action="<?= $modul_path . $form_action ?>" id="myForm" onsubmit="return validateForm()" method="post">
            <?= csrf_field() ?>

            <input type="hidden" name="id_pengambilan_darah" id="id_pengambilan_darah" value="<?= $baris['id_pengambilan_darah'] ?? '' ?>" required>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">
                    Nomor Pengambilan<span class="text-red-600">*</span>
                </label>
                <div class="w-full lg:w-1/4">
                    <input type="text" id="nomor_pengambilan" name="nomor_pengambilan" readonly required
                           value="<?= $baris['nomor_pengambilan'] ?? '' ?>"
                           placeholder="Terisi otomatis..." 
                           class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full dark:border-gray-600 dark:text-white bg-gray-100 cursor-not-allowed">
                </div>

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">
                    Tanggal Uji<span class="text-red-600">*</span>
                </label>
                <input type="date" name="tanggal_uji" value="<?= $baris['tanggal_uji'] ?? date('Y-m-d') ?>" 
                       class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:bg-slate-800 dark:border-gray-700 dark:text-white" required>
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">
                    Metode Uji<span class="text-red-600">*</span>
                </label>
                <select name="id_metode_uji" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" required>
                    <option value="">-- Pilih --</option>
                    <?php 
                    $optionsMetode = [];
                    foreach ($konfig as $field) {
                        if ($field[2] === 'id_metode_uji') {
                            $optionsMetode = $field[5] ?? [];
                            break;
                        }
                    }
                    foreach ($optionsMetode as $opt) :
                        $selected = ((string)($baris['id_metode_uji'] ?? '') === (string)$opt[1]) ? 'selected' : '';
                    ?>
                        <option value="<?= $opt[1] ?>" <?= $selected ?>><?= $opt[0] ?></option>
                    <?php endforeach; ?>
                </select>

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">
                    Petugas<span class="text-red-600">*</span>
                </label>
                <input type="hidden" name="id_petugas" id="id_petugas" value="<?= $baris['id_petugas'] ?? '' ?>" required>

                <div class="w-full lg:w-1/4 flex gap-x-2">
                    <input type="text" id="nama_petugas" name="nama_petugas" readonly required
                           value="<?= $baris['nama_petugas'] ?? '' ?>"
                           placeholder="Klik cari..."
                           onclick="open_modalPetugas()"
                           class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full dark:border-gray-600 dark:text-white cursor-pointer bg-slate-50">
                    
                    <button type="button" onclick="open_modalPetugas()"
                            class="inline-flex justify-center items-center p-2 text-sm font-medium text-white bg-blue-600 rounded-lg border border-transparent hover:bg-blue-700 focus:outline-none transition-all w-10 h-[38px] flex-shrink-0 shadow-sm">
                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </button>
                </div>
            </div>

            <div class="mb-6 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">
                    HBsAg (Hepatitis B)<span class="text-red-600">*</span>
                </label>
                <div class="w-full lg:w-1/4 flex justify-between">
                    <label class="flex items-center text-sm text-gray-900 dark:text-gray-300 cursor-pointer">
                        <input type="radio" name="hbsag" value="0" <?= (isset($baris['hbsag']) && (string)$baris['hbsag'] === '0') ? 'checked' : '' ?> style="accent-color: #2563eb;" class="w-4 h-4 text-teal-600 border-gray-300 focus:ring-teal-500 mr-2" required>
                        Non Reaktif
                    </label>
                    <label class="flex items-center text-sm text-gray-900 dark:text-gray-300 cursor-pointer">
                        <input type="radio" name="hbsag" value="1" <?= (isset($baris['hbsag']) && (string)$baris['hbsag'] === '1') ? 'checked' : '' ?> style="accent-color: #2563eb;" class="w-4 h-4 text-teal-600 border-gray-300 focus:ring-teal-500 mr-2" required>
                        Reaktif
                    </label>
                </div>

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">
                    HCV (Hepatitis C)<span class="text-red-600">*</span>
                </label>
                <div class="w-full lg:w-1/4 flex justify-between">
                    <label class="flex items-center text-sm text-gray-900 dark:text-gray-300 cursor-pointer">
                        <input type="radio" name="hcv" value="0" <?= (isset($baris['hcv']) && (string)$baris['hcv'] === '0') ? 'checked' : '' ?> style="accent-color: #2563eb;" class="w-4 h-4 text-teal-600 border-gray-300 focus:ring-teal-500 mr-2" required>
                        Non Reaktif
                    </label>
                    <label class="flex items-center text-sm text-gray-900 dark:text-gray-300 cursor-pointer">
                        <input type="radio" name="hcv" value="1" <?= (isset($baris['hcv']) && (string)$baris['hcv'] === '1') ? 'checked' : '' ?> style="accent-color: #2563eb;" class="w-4 h-4 text-teal-600 border-gray-300 focus:ring-teal-500 mr-2" required>
                        Reaktif
                    </label>
                </div>
            </div>

            <div class="mb-6 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">
                    HIV<span class="text-red-600">*</span>
                </label>
                <div class="w-full lg:w-1/4 flex justify-between">
                    <label class="flex items-center text-sm text-gray-900 dark:text-gray-300 cursor-pointer">
                        <input type="radio" name="hiv" value="0" <?= (isset($baris['hiv']) && (string)$baris['hiv'] === '0') ? 'checked' : '' ?> style="accent-color: #2563eb;" class="w-4 h-4 text-teal-600 border-gray-300 focus:ring-teal-500 mr-2" required>
                        Non Reaktif
                    </label>
                    <label class="flex items-center text-sm text-gray-900 dark:text-gray-300 cursor-pointer">
                        <input type="radio" name="hiv" value="1" <?= (isset($baris['hiv']) && (string)$baris['hiv'] === '1') ? 'checked' : '' ?> style="accent-color: #2563eb;" class="w-4 h-4 text-teal-600 border-gray-300 focus:ring-teal-500 mr-2" required>
                        Reaktif
                    </label>
                </div>

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">
                    Sifilis<span class="text-red-600">*</span>
                </label>
                <div class="w-full lg:w-1/4 flex justify-between">
                    <label class="flex items-center text-sm text-gray-900 dark:text-gray-300 cursor-pointer">
                        <input type="radio" name="sifilis" value="0" <?= (isset($baris['sifilis']) && (string)$baris['sifilis'] === '0') ? 'checked' : '' ?> style="accent-color: #2563eb;" class="w-4 h-4 text-teal-600 border-gray-300 focus:ring-teal-500 mr-2" required>
                        Non Reaktif
                    </label>
                    <label class="flex items-center text-sm text-gray-900 dark:text-gray-300 cursor-pointer">
                        <input type="radio" name="sifilis" value="1" <?= (isset($baris['sifilis']) && (string)$baris['sifilis'] === '1') ? 'checked' : '' ?> style="accent-color: #2563eb;" class="w-4 h-4 text-teal-600 border-gray-300 focus:ring-teal-500 mr-2" required>
                        Reaktif
                    </label>
                </div>
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">
                    Malaria
                </label>
                <div class="w-full lg:w-1/4 flex justify-between">
                    <label class="flex items-center text-sm text-gray-900 dark:text-gray-300 cursor-pointer">
                        <input type="radio" name="malaria" value="0" <?= (isset($baris['malaria']) && (string)$baris['malaria'] === '0') ? 'checked' : '' ?> style="accent-color: #2563eb;" class="w-4 h-4 text-teal-600 border-gray-300 focus:ring-teal-500 mr-2">
                        Non Reaktif
                    </label>
                    <label class="flex items-center text-sm text-gray-900 dark:text-gray-300 cursor-pointer">
                        <input type="radio" name="malaria" value="1" <?= (isset($baris['malaria']) && (string)$baris['malaria'] === '1') ? 'checked' : '' ?> style="accent-color: #2563eb;" class="w-4 h-4 text-teal-600 border-gray-300 focus:ring-teal-500 mr-2">
                        Reaktif
                    </label>
                </div>
            </div>

            <?= view('components/form/submit_button') ?>
        </form>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const pengambilanId = "<?= $baris['id_pengambilan_darah'] ?? '' ?>";
        if (pengambilanId !== '') {
            document.getElementById('id_pengambilan_darah').value = pengambilanId;
            document.getElementById('nomor_pengambilan').value = "<?= $baris['nomor_pengambilan'] ?? '' ?>";
        }

        const petugasId = "<?= $baris['id_petugas'] ?? '' ?>";
        if (petugasId !== '') {
            document.getElementById('id_petugas').value = petugasId;
            document.getElementById('nama_petugas').value = "<?= $baris['nama_petugas'] ?? '' ?>";
        }

        const radioMalaria = document.querySelectorAll('input[name="malaria"]');
                        
        radioMalaria.forEach(function(radio) {
            radio.addEventListener('click', function() {
                if (this.previousState) {
                    this.checked = false;
                    this.previousState = false;
                } else {
                    radioMalaria.forEach(r => r.previousState = false);
                    this.previousState = true;
                }
            });
        });
    });
    
    function autofillPetugas(item) {
        document.getElementById('id_petugas').value = item.id_petugas;
        document.getElementById('nama_petugas').value = item.nama;
    }

    function validateForm() {
        var requiredFields = document.querySelectorAll('select[required], input[required]');
        for (var i = 0; i < requiredFields.length; i++) {
            if (!requiredFields[i].value) {
                alert("Mohon isi semua field yang bertanda bintang.");
                return false;
            }
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