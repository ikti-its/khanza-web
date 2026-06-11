<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<?= $this->include('components/modal/modalkunjungan') ?>
<?= $this->include('components/modal/modalpetugas') ?>

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
                    <?php
                    $isEdit = (str_contains($judul, 'Ubah'));
                    ?>
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
                       class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white bg-gray-200 cursor-not-allowed">
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">
                    Nama Lengkap
                </label>
                <input type="text" id="nama" name="nama" readonly placeholder="Terisi otomatis..."
                       class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white bg-gray-200 cursor-not-allowed">
                
                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">
                    Jenis Pencekalan<span class="text-red-600">*</span>
                </label>
                <select name="id_jenis_pencekalan" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" required>
                    <option value="">-- Pilih --</option>
                    <?php 
                    $optionsJenisPencekalan = [];
                    foreach ($konfig as $field) {
                        if ($field[2] === 'id_jenis_pencekalan') {
                            $optionsJenisPencekalan = $field[5] ?? [];
                            break;
                        }
                    }
                    foreach ($optionsJenisPencekalan as $opt) : 
                        $selected = ((string)($baris['id_jenis_pencekalan'] ?? '') === (string)$opt[1]) ? 'selected' : '';
                    ?>
                        <option value="<?= $opt[1] ?>" <?= $selected ?>><?= $opt[0] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">
                    Tanggal Mulai<span class="text-red-600">*</span>
                </label>
                <input type="date" name="tanggal_mulai" value="<?= !empty($baris['tanggal_mulai']) ? $baris['tanggal_mulai'] : date('Y-m-d') ?>"
                       class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" required>
                
                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">
                    Tanggal Selesai<span class="text-red-600">*</span>
                </label>
                <input type="date" name="tanggal_selesai" value="<?= $baris['tanggal_selesai'] ?? date('Y-m-d') ?>"
                       class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" required>
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">
                    Shift<span class="text-red-600">*</span>
                </label>
                <select name="id_shift" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" required>
                    <option value="">-- Pilih --</option>
                    <?php 
                    $optionsShift = [];
                    foreach ($konfig as $field) {
                        if ($field[2] === 'id_shift') {
                            $optionsShift = $field[5] ?? [];
                            break;
                        }
                    }
                    foreach ($optionsShift as $opt) : 
                        $selected = ((string)($baris['id_shift'] ?? '') === (string)$opt[1]) ? 'selected' : '';
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

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">
                    Keterangan<span class="text-red-600">*</span>
                </label>
                <input type="text" name="keterangan" value="<?= $baris['keterangan'] ?? '' ?>"
                       class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
            </div>

            <?= view('components/form/submit_button') ?>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
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

        const petugasId = "<?= $baris['id_petugas'] ?? '' ?>";
        if (petugasId !== '') {
            const savedItem = {
                id_petugas: petugasId,
                nama: "<?= $baris['nama_petugas'] ?? '' ?>"
            };

            autofillPetugas(savedItem);
        }
    });

    function autofillKunjungan(item) {
        document.getElementById('id_kunjungan').value = item.id_kunjungan;
        document.getElementById('nomor_kunjungan').value = item.nomor_kunjungan;
        document.getElementById('nomor_pendonor').value = item.nomor_pendonor;
        document.getElementById('nama').value = item.nama;
    }

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