<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<?= $this->include('components/modal/modalpendonor') ?>

<div class="max-w-[85rem] py-6 lg:py-3 px-8 mx-auto">
    <div class="bg-white rounded-xl shadow p-4 sm:p-7 dark:bg-slate-900">
        <?= view('components/form/judul', [
            'judul' => $judul
        ]) ?>
        
        <form action="<?= $modul_path . $form_action ?>" id="myForm" onsubmit="return validateForm()" method="post">
            <?= csrf_field() ?>

            <input type="hidden" name="id_pendonor" id="id_pendonor" value="<?= $baris['id_pendonor'] ?? '' ?>" required>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">
                    Nomor Antrian<span class="text-red-600">*</span>
                </label>
                <input type="text" name="nomor_antrian" value="<?= $baris['nomor_antrian'] ?? '' ?>" 
                       class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white bg-gray-100" readonly required>

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">
                    Nomor Kunjungan<span class="text-red-600">*</span>
                </label>
                <input type="text" name="nomor_kunjungan" value="<?= $baris['nomor_kunjungan'] ?? '' ?>" 
                       class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white bg-gray-100" readonly required>
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">
                    Tanggal Kunjungan<span class="text-red-600">*</span>
                </label>
                <input type="datetime-local" name="tanggal_kunjungan" value="<?= isset($baris['tanggal_kunjungan']) && $baris['tanggal_kunjungan'] !== '' ? date('Y-m-d\TH:i', strtotime($baris['tanggal_kunjungan'])) : date('Y-m-d\TH:i') ?>"
                       max="<?= date('Y-m-d\TH:i') ?>"
                       class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" required>

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">
                    Nomor Pendonor<span class="text-red-600">*</span>
                </label>
                <div class="w-full lg:w-1/4 flex gap-x-2">
                    <input type="text" id="nomor_pendonor" name="nomor_pendonor" readonly required
                           placeholder="Klik cari..." onclick="open_modalPendonor()" 
                           class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full dark:border-gray-600 dark:text-white cursor-pointer bg-slate-50">
                    
                    <button type="button" onclick="open_modalPendonor()" 
                            class="inline-flex justify-center items-center p-2 text-sm font-medium text-white bg-blue-600 rounded-lg border border-transparent hover:bg-blue-700 focus:outline-none transition-all w-10 h-[38px] flex-shrink-0 shadow-sm">
                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </button>
                </div>
            </div>

            <div id="cardPendonor" class="hidden mt-6 bg-slate-50 border border-slate-200 rounded-xl p-5 dark:bg-slate-800 dark:border-slate-700 transition-all duration-300 shadow-sm">
                <div class="flex items-center gap-x-2 mb-3 border-b border-slate-200 pb-2 dark:border-slate-700">
                    <svg class="w-4 h-4 text-red-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                    </svg>
                    <h4 class="text-xs font-bold text-slate-500 uppercase tracking-wider dark:text-slate-400">
                        Profil Terpilih Anggota Pendonor
                    </h4>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-3 gap-x-6 text-sm text-slate-700 dark:text-slate-300">
                    <div class="flex justify-between border-b border-slate-100 pb-1 dark:border-slate-700/50">
                        <span class="text-slate-400">Nama Lengkap:</span>
                        <span id="nama" class="font-semibold text-slate-800 dark:text-white">-</span>
                    </div>
                    <div class="flex justify-between border-b border-slate-100 pb-1 dark:border-slate-700/50">
                        <span class="text-slate-400">NIK:</span>
                        <span id="nik" class="font-semibold text-slate-800 dark:text-white">-</span>
                    </div>
                    <div class="flex justify-between border-b border-slate-100 pb-1 dark:border-slate-700/50">
                        <span class="text-slate-400">Jenis Kelamin:</span>
                        <span id="jenis_kelamin" class="font-semibold text-slate-800 dark:text-white">-</span>
                    </div>
                    <div class="flex justify-between border-b border-slate-100 pb-1 dark:border-slate-700/50">
                        <span class="text-slate-400">Tanggal Lahir:</span>
                        <span id="tanggal_lahir" class="font-semibold text-slate-800 dark:text-white">-</span>
                    </div>
                    <div class="flex justify-between border-b border-slate-100 pb-1 dark:border-slate-700/50">
                        <span class="text-slate-400">Golongan Darah:</span>
                        <span id="golongan_darah" class="px-2 py-0.5 text-xs bg-red-100 text-red-800 rounded font-bold dark:bg-red-900/30 dark:text-red-400">-</span>
                    </div>
                    <div class="flex justify-between border-b border-slate-100 pb-1 dark:border-slate-700/50">
                        <span class="text-slate-400">Rhesus:</span>
                        <span id="rhesus" class="px-2 py-0.5 text-xs bg-red-100 text-red-800 rounded font-bold dark:bg-red-900/30 dark:text-red-400">-</span>
                    </div>
                </div>
            </div>

            <?= view('components/form/submit_button') ?>
        </form>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const pendonorId = "<?= $baris['id_pendonor'] ?? '' ?>";

        if (pendonorId !== '') {
            const inputHidden = document.getElementById('id_pendonor');
            if (inputHidden) {
                inputHidden.value = pendonorId;
            }

            const savedItem = {
                id_pendonor: pendonorId,
                nomor_pendonor: "<?= $card['nomor_pendonor'] ?? '' ?>",
                nama: "<?= $card['nama'] ?? '' ?>",
                nik: "<?= $card['nik'] ?? '' ?>",
                jenis_kelamin: "<?= $card['id_jenis_kelamin'] ?? '' ?>",
                tanggal_lahir: "<?= $card['tanggal_lahir'] ?? '' ?>",
                golongan_darah: "<?= $card['id_golongan_darah'] ?? '' ?>",
                rhesus: "<?= $card['id_rhesus'] ?? '' ?>"
            };
            
            autofillFields(savedItem);
        }
    });

    function autofillFields(item) {
        document.getElementById('id_pendonor').value = item.id_pendonor;
        document.getElementById('nomor_pendonor').value = item.nomor_pendonor;
        document.getElementById('nama').innerText = item.nama;
        document.getElementById('nik').innerText = item.nik;
        document.getElementById('jenis_kelamin').innerText = item.jenis_kelamin;
        document.getElementById('tanggal_lahir').innerText = item.tanggal_lahir;
        document.getElementById('golongan_darah').innerText = item.golongan_darah;
        document.getElementById('rhesus').innerText = item.rhesus;

        const card = document.getElementById('cardPendonor');
        if (card) card.classList.remove('hidden');
    }

    function validateForm() {
        var requiredFields = document.querySelectorAll('select[required], input[required]');
        for (var i = 0; i < requiredFields.length; i++) {
            if (!requiredFields[i].value) {
                alert("Isi semua field.");
                return false;
            }
        }

        const idPendonor = document.getElementById('id_pendonor').value;
        if (!idPendonor) {
            alert("Silakan tentukan Anggota Pendonor terlebih dahulu melalui modal pencarian.");
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