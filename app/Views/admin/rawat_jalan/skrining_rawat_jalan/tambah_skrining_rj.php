<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>
<?= $this->include('components/modal/modalpasienrole') ?>

<div class="max-w-[85rem] py-6 lg:py-3 px-8 mx-auto">
    <div class="bg-white rounded-xl shadow p-4 sm:p-7 dark:bg-slate-900">
        <?= view('components/form/judul', ['judul' => $judul]) ?>

        <form action="<?= $modul_path . $form_action ?>" id="myForm" onsubmit="return validateForm()" method="post">
            <?= csrf_field() ?>

            <input type="hidden" name="no_rm" id="no_rm" value="<?= esc($baris['no_rm'] ?? '') ?>" required>

            <?php 
            $readonlyClass  = 'bg-gray-100 cursor-not-allowed';
            $pasienNama     = esc($data_pasien['nama'] ?? '');
            $pasienTglLahir = esc($data_pasien['tanggal_lahir'] ?? '');
            $pasienJK       = esc($data_pasien['jenis_kelamin'] ?? '');
            $noRmVal        = esc($baris['no_rm'] ?? '');
            ?>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">
                    No. Rekam Medis<span class="text-red-600">*</span>
                </label>
                <div class="w-full lg:w-1/4 flex gap-x-2">
                    <input type="text" id="no_rm_display" value="<?= $noRmVal ?>" readonly required
                           placeholder="Klik cari pasien..." onclick="open_modalPasienRole()"
                           class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full dark:border-gray-600 dark:text-white cursor-pointer bg-slate-50">
                    <button type="button" onclick="open_modalPasienRole()"
                            class="inline-flex justify-center items-center p-2 text-sm font-medium text-white bg-blue-600 rounded-lg border border-transparent hover:bg-blue-700 focus:outline-none transition-all w-10 h-[38px] flex-shrink-0 shadow-sm">
                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </button>
                </div>
                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">
                    Nama Pasien</label>
                <input type="text" id="nm_pasien" value="<?= $pasienNama ?>" readonly placeholder="Terisi otomatis..." class="<?= $readonlyClass ?> lg:w-1/4">
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">
                    Tanggal Lahir</label>
                <input type="text" id="tgl_lahir" value="<?= $pasienTglLahir ?>" readonly placeholder="Terisi otomatis..." class="<?= $readonlyClass ?> lg:w-1/4">
                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">
                    Jenis Kelamin</label>
                <input type="text" id="jenis_kelamin" value="<?= $pasienJK ?>" readonly placeholder="Terisi otomatis..." class="<?= $readonlyClass ?> lg:w-1/4">
            </div>

            <?php $konfigTanpaRM = array_values(array_filter($konfig, fn($f) => $f[2] !== 'no_rm' && $f[2] !== 'id_skrining')); ?>
            <?= view('components/form/isian', ['konfig' => $konfigTanpaRM, 'baris' => $baris]) ?>

            <div class="flex justify-end gap-x-2 mt-8 border-t border-gray-100 pt-4 dark:border-gray-700">
                <?= view('components/form/submit_button') ?>
            </div>
        </form>
    </div>
</div>

<script>
    function validateForm() {
        if (!document.getElementById('no_rm').value) {
            alert('Silakan pilih pasien terlebih dahulu melalui modal pencarian.');
            return false;
        }

        const requiredFields = document.querySelectorAll('select[required], input[required]');
        for (const field of requiredFields) {
            if (!field.value) {
                alert('Isi semua field yang wajib diisi.');
                return false;
            }
        }

        const submitButton = document.getElementById('submitButton');
        if (submitButton) {
            submitButton.disabled = true;
            submitButton.innerHTML = 'Menyimpan...';
        }
        return true;
    }
</script>

<?= $this->endSection(); ?>