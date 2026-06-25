<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<div class="max-w-[85rem] py-6 lg:py-3 px-8 mx-auto animate-fade-in">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-7 dark:bg-slate-900 dark:border-gray-800">
        
        <div>
            <?= view('components/form/judul', [
                'judul' => $judul
            ]) ?>
        </div>
        
        <div class="space-y-6">
            
            <div class="sm:block md:flex items-center py-3">
                <span class="block mb-1 md:mb-0 text-sm font-medium text-gray-500 dark:text-gray-500 md:w-1/4">Nomor Antrian</span>
                <div class="w-full lg:w-1/4">
                    <span class="text-sm font-semibold text-gray-900 dark:text-white">
                        <?= esc($baris['nomor_antrian'] ?? '-') ?>
                    </span>
                </div>

                <span class="block mt-4 md:my-0 md:ml-10 md:mr-12 mb-1 text-sm font-medium text-gray-500 dark:text-gray-500 md:w-1/4">Nomor Kunjungan</span>
                <div class="w-full lg:w-1/4">
                    <span class="text-sm font-semibold text-gray-900 dark:text-white">
                        <?= esc($baris['nomor_kunjungan'] ?? '-') ?>
                    </span>
                </div>
            </div>

            <div class="sm:block md:flex items-center py-3">
                <span class="block mb-1 md:mb-0 text-sm font-medium text-gray-500 dark:text-gray-500 md:w-1/4">Tanggal Kunjungan</span>
                <div class="w-full lg:w-1/4">
                    <span class="text-sm font-semibold text-gray-900 dark:text-white">
                        <?= !empty($baris['tanggal_kunjungan']) ? (new IntlDateFormatter('id_ID', IntlDateFormatter::LONG, IntlDateFormatter::SHORT))->format(strtotime($baris['tanggal_kunjungan'])) : '-' ?> WIB
                    </span>
                </div>

                <span class="block mt-4 md:my-0 md:ml-10 md:mr-12 mb-1 text-sm font-medium text-gray-500 dark:text-gray-500 md:w-1/4">Nomor Pendonor</span>
                <div class="w-full lg:w-1/4">
                    <span class="text-sm font-mono font-bold text-teal-700 bg-teal-50 border border-teal-200 px-2.5 py-1 rounded-md dark:bg-teal-950/40 dark:text-teal-400 dark:border-teal-900 shadow-sm">
                        <?= esc($baris['nomor_pendonor'] ?? '-') ?>
                    </span>
                </div>
            </div>

            <div id="cardPendonor" class="mt-6 bg-slate-50 border border-slate-200 rounded-xl p-5 dark:bg-slate-800 dark:border-slate-700 shadow-sm">
                <div class="flex items-center gap-x-2 mb-3 border-b border-slate-200 pb-2 dark:border-slate-700">
                    <svg class="w-4 h-4 text-red-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                    </svg>
                    <h4 class="text-xs font-bold text-slate-500 uppercase tracking-wider dark:text-slate-400">
                        Profil Pendonor
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

        </div>

        <div class="mt-5 pt-5 border-t border-gray-200 dark:border-gray-800 flex justify-end">
            <a href="javascript:history.back()" class="py-3 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-white dark:hover:bg-gray-800 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                Kembali
            </a>
        </div>

    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const item = {
            nama: "<?= $baris['nama'] ?? '-' ?>",
            nik: "<?= $baris['nik'] ?? '-' ?>",
            jenis_kelamin: "<?= $baris['id_jenis_kelamin'] ?? '-' ?>",
            tanggal_lahir: "<?= !empty($baris['tanggal_lahir']) ? (new IntlDateFormatter('id_ID', IntlDateFormatter::LONG, IntlDateFormatter::NONE))->format(strtotime($baris['tanggal_lahir'])) : '-' ?>",
            golongan_darah: "<?= $baris['id_golongan_darah'] ?? '-' ?>",
            rhesus: "<?= $baris['id_rhesus'] ?? '-' ?>"
        };
        
        document.getElementById('nama').innerText = item.nama;
        document.getElementById('nik').innerText = item.nik;
        document.getElementById('jenis_kelamin').innerText = item.jenis_kelamin;
        document.getElementById('tanggal_lahir').innerText = item.tanggal_lahir;
        document.getElementById('golongan_darah').innerText = item.golongan_darah;
        document.getElementById('rhesus').innerText = item.rhesus;
    });
</script>

<?= $this->endSection(); ?>