<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<?= $this->include('components/modal/modalKota') ?>
<?= $this->include('components/modal/modalBank') ?>

<div class="max-w-[85rem] py-6 lg:py-3 px-8 mx-auto">
    <div class="bg-white rounded-xl shadow p-4 sm:p-7 dark:bg-slate-900">
        <?= view('components/form/judul', [
            'judul' => $judul
        ]) ?>

        <form action="<?= $modul_path . $form_action ?>" id="myForm" onsubmit="return validateForm()" method="post">
            <?= csrf_field() ?>

            <input type="hidden" name="id_kota" id="id_kota" value="">
            <input type="hidden" name="id_bank" id="id_bank" value="">

            <!-- Kode Suplier + Nama Suplier -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">
                    Kode Suplier<span class="text-red-600">*</span>
                </label>
                <input type="text" name="kode_suplier" id="kode_suplier"
                       value="<?= esc($kode_suplier ?? '') ?>" maxlength="10"
                       class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white dark:bg-slate-800" required>

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">
                    Nama Suplier<span class="text-red-600">*</span>
                </label>
                <input type="text" name="nama_suplier" id="nama_suplier" placeholder="Masukkan nama suplier..." maxlength="100"
                       class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white dark:bg-slate-800" required>
            </div>

            <!-- Kota + Alamat -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">
                    Kota
                </label>
                <div class="w-full lg:w-1/4 flex gap-x-2">
                    <input type="text" id="id_kota_display"
                           placeholder="Klik cari kota..."
                           onclick="open_modalKota()"
                           onkeydown="return false"
                           class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full dark:border-gray-600 dark:text-white cursor-pointer bg-slate-50">
                    <button type="button" onclick="open_modalKota()"
                            class="inline-flex justify-center items-center p-2 text-sm font-medium text-white bg-blue-600 rounded-lg border border-transparent hover:bg-blue-700 focus:outline-none transition-all w-10 h-[38px] flex-shrink-0 shadow-sm">
                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </button>
                </div>

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">
                    Alamat
                </label>
                <input type="text" name="alamat" id="alamat" placeholder="Alamat suplier..." maxlength="255"
                       class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white dark:bg-slate-800">
            </div>

            <!-- No. Telepon + Bank -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">
                    No. Telepon
                </label>
                <input type="tel" name="no_telp" id="no_telp" placeholder="No. telepon..." maxlength="20"
                       oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                       class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white dark:bg-slate-800">

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">
                    Bank
                </label>
                <div class="w-full lg:w-1/4 flex gap-x-2">
                    <input type="text" id="id_bank_display"
                           placeholder="Klik cari bank..."
                           onclick="open_modalBank()"
                           onkeydown="return false"
                           class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full dark:border-gray-600 dark:text-white cursor-pointer bg-slate-50">
                    <button type="button" onclick="open_modalBank()"
                            class="inline-flex justify-center items-center p-2 text-sm font-medium text-white bg-blue-600 rounded-lg border border-transparent hover:bg-blue-700 focus:outline-none transition-all w-10 h-[38px] flex-shrink-0 shadow-sm">
                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- No. Rekening + Nama Akun -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">
                    No. Rekening
                </label>
                <input type="text" name="nomor_rekening" id="nomor_rekening" placeholder="Nomor rekening..." maxlength="30"
                       oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                       class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white dark:bg-slate-800">

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">
                    Nama Akun
                </label>
                <input type="text" name="nama_akun" id="nama_akun" placeholder="Nama pemilik rekening..." maxlength="100"
                       class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white dark:bg-slate-800">
            </div>

            <?= view('components/form/submit_button') ?>
        </form>
    </div>
</div>

<script>
    function autofillKota(item) {
        document.getElementById('id_kota').value = item.id_kota ?? '';
        document.getElementById('id_kota_display').value = item.nama_kota ?? '';
    }

    function autofillBank(item) {
        document.getElementById('id_bank').value = item.id_bank ?? '';
        document.getElementById('id_bank_display').value = item.nama_bank ?? '';
    }

    function validateForm() {
        var form = document.getElementById('myForm');
        if (!form.reportValidity()) {
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
