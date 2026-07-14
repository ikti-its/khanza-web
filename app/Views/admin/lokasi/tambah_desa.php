<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<?= $this->include('components/modal/modalkecamatan') ?>

<div class="max-w-[85rem] py-6 lg:py-3 px-8 mx-auto">
    <div class="bg-white rounded-xl shadow p-4 sm:p-7 dark:bg-slate-900">
        <?= view('components/form/judul', [
            'judul' => $judul
        ]) ?>

        <form action="<?= $modul_path . $form_action ?>" id="myForm" method="post">
            <?= csrf_field() ?>

            <input type="hidden" name="redirect_to" value="<?= esc($baris['redirect_to'] ?? '') ?>">
            <input type="hidden" name="id_provinsi" id="id_provinsi" value="<?= $baris['id_provinsi'] ?? '' ?>" required>
            <input type="hidden" name="id_kota_lokal" id="id_kota_lokal" value="<?= $baris['id_kota_lokal'] ?? '' ?>" required>
            <input type="hidden" name="id_kec_lokal" id="id_kec_lokal" value="<?= $baris['id_kec_lokal'] ?? '' ?>" required>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">
                    Kecamatan<span class="text-red-600">*</span>
                </label>
                <div class="w-full lg:w-1/4 flex gap-x-2">
                    <input type="text" id="nama_kecamatan" name="nama_kecamatan" readonly required
                           value="<?= $baris['nama_kecamatan'] ?? '' ?>" placeholder="Klik cari..."
                           onclick="open_modalKecamatan()"
                           class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full dark:border-gray-600 dark:text-white cursor-pointer bg-slate-50 h-[38px]">

                    <button type="button" onclick="open_modalKecamatan()"
                            class="inline-flex justify-center items-center p-2 text-sm font-medium text-white bg-blue-600 rounded-lg border border-transparent hover:bg-blue-700 focus:outline-none transition-all w-10 h-[38px] flex-shrink-0 shadow-sm">
                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </button>
                </div>

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">
                    Kota / Kabupaten
                </label>
                <input type="text" id="nama_kota" name="nama_kota" readonly placeholder="Terisi otomatis..."
                       value="<?= $baris['nama_kota'] ?? '' ?>"
                       class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white bg-gray-100 cursor-not-allowed h-[38px]">
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">
                    Provinsi
                </label>
                <div class="w-full lg:w-1/4">
                    <input type="text" id="nama_provinsi" name="nama_provinsi" readonly placeholder="Terisi otomatis..."
                           value="<?= $baris['nama_provinsi'] ?? '' ?>"
                           class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full dark:border-gray-700 dark:text-white bg-gray-100 cursor-not-allowed h-[38px]">
                </div>

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">
                    Kode Lokal<span class="text-red-600">*</span>
                </label>
                <input type="number" name="id_desa_lokal" id="id_desa_lokal" value="<?= $baris['id_desa_lokal'] ?? '' ?>" required
                       min="1001" max="2999"
                       class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white h-[38px]">
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">
                    Nama Desa / Kelurahan<span class="text-red-600">*</span>
                </label>
                <div class="w-full lg:w-1/4">
                    <input type="text" name="nama_desa" id="nama_desa" value="<?= $baris['nama_desa'] ?? '' ?>" required
                           maxlength="30"
                           class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full dark:border-gray-600 dark:text-white h-[38px]">
                </div>
            </div>

            <?= view('components/form/submit_button') ?>
        </form>
    </div>
</div>

<script>
    function autofillKecamatan(item) {
        document.getElementById('id_provinsi').value   = item.id_provinsi;
        document.getElementById('id_kota_lokal').value = item.id_kota_lokal;
        document.getElementById('id_kec_lokal').value  = item.id_kec_lokal;

        document.getElementById('nama_kecamatan').value = item.nama_kecamatan;
        document.getElementById('nama_kota').value      = item.nama_kota;
        document.getElementById('nama_provinsi').value  = item.nama_provinsi;
    }
</script>

<?= $this->endSection(); ?>