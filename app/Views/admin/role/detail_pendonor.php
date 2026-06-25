<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<div class="max-w-[85rem] py-6 lg:py-3 px-8 mx-auto animate-fade-in">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-7 dark:bg-slate-900 dark:border-gray-800">
        
        <div>
            <?= view('components/form/judul', [
                'judul' => 'Detail Data Pendonor'
            ]) ?>
        </div>
        
        <div class="space-y-6">
            
            <div class="sm:block md:flex items-center py-3">
                <span class="block mb-1 md:mb-0 text-sm font-medium text-gray-500 dark:text-gray-500 md:w-1/4">Nomor Pendonor</span>
                <div class="w-full lg:w-1/4">
                    <span class="text-sm font-mono font-bold text-teal-700 bg-teal-50 border border-teal-200 px-2.5 py-1 rounded-md dark:bg-teal-950/40 dark:text-teal-400 dark:border-teal-900 shadow-sm">
                        <?= esc($baris['nomor_pendonor'] ?? '-') ?>
                    </span>
                </div>

                <span class="block mt-4 md:my-0 md:ml-10 md:mr-12 mb-1 text-sm font-medium text-gray-500 dark:text-gray-500 md:w-1/4">NIK (No. KTP)</span>
                <div class="w-full lg:w-1/4">
                    <span class="text-sm font-mono font-semibold text-gray-900 dark:text-white"><?= esc($baris['nik'] ?? '-') ?></span>
                </div>
            </div>

            <div class="sm:block md:flex items-center py-3">
                <span class="block mb-1 md:mb-0 text-sm font-medium text-gray-500 dark:text-gray-500 md:w-1/4">Nama Lengkap</span>
                <div class="w-full lg:w-1/4">
                    <span class="text-sm font-semibold text-gray-900 dark:text-white"><?= esc($baris['nama'] ?? '-') ?></span>
                </div>

                <span class="block mt-4 md:my-0 md:ml-10 md:mr-12 mb-1 text-sm font-medium text-gray-500 dark:text-gray-500 md:w-1/4">Jenis Kelamin</span>
                <div class="w-full lg:w-1/4">
                    <span class="text-sm font-semibold text-gray-900 dark:text-white"><?= esc($baris['id_jenis_kelamin'] ?? '-') ?></span>
                </div>
            </div>

            <div class="sm:block md:flex items-center py-3">
                <span class="block mb-1 md:mb-0 text-sm font-medium text-gray-500 dark:text-gray-500 md:w-1/4">Tempat Lahir</span>
                <div class="w-full lg:w-1/4">
                    <span class="text-sm font-semibold text-gray-900 dark:text-white"><?= esc($baris['nama_kota_lahir'] ?? '-') ?></span>
                </div>

                <span class="block mt-4 md:my-0 md:ml-10 md:mr-12 mb-1 text-sm font-medium text-gray-500 dark:text-gray-500 md:w-1/4">Tanggal Lahir</span>
                <div class="w-full lg:w-1/4">
                    <span class="text-sm font-semibold text-gray-900 dark:text-white">
                        <?= !empty($baris['tanggal_lahir']) ? (new IntlDateFormatter('id_ID', IntlDateFormatter::LONG, IntlDateFormatter::NONE))->format(strtotime($baris['tanggal_lahir'])) : '-' ?>
                    </span>
                </div>
            </div>

            <div class="sm:block md:flex items-center py-3">
                <span class="block mb-1 md:mb-0 text-sm font-medium text-gray-500 dark:text-gray-500 md:w-1/4">Golongan Darah</span>
                <div class="w-full lg:w-1/4">
                    <span class="inline-flex items-center justify-center px-3 py-1 rounded-md font-bold bg-red-50 text-red-700 border border-red-200 dark:bg-red-950/40 dark:text-red-400 dark:border-red-900 shadow-sm text-sm">
                        <?= esc($baris['id_golongan_darah'] ?? '-') ?>
                    </span>
                </div>

                <span class="block mt-4 md:my-0 md:ml-10 md:mr-12 mb-1 text-sm font-medium text-gray-500 dark:text-gray-500 md:w-1/4">Rhesus</span>
                <div class="w-full lg:w-1/4">
                    <span class="inline-flex items-center justify-center px-3 py-1 rounded-md font-bold bg-teal-50 text-teal-700 border border-teal-200 dark:bg-teal-950/40 dark:text-teal-400 dark:border-teal-900 shadow-sm text-sm">
                        <?= esc($baris['id_rhesus'] ?? '-') ?>
                    </span>
                </div>
            </div>

            <div class="sm:block md:flex items-start py-3">
                <span class="block mb-1 md:mb-0 text-sm font-medium text-gray-500 dark:text-gray-500 md:w-1/4">Alamat Lengkap</span>
                <div class="w-full md:w-1/4">
                    <span class="text-sm font-semibold text-gray-900 dark:text-white">
                        <?= esc($baris['alamat_lengkap'] ?? '-') ?>
                    </span>
                </div>

                <span class="block md:ml-10 md:w-1/4"></span>
                <div class="lg:w-1/4 hidden lg:block"></div>
            </div>

            <div class="sm:block md:flex items-center py-3">
                <span class="block mb-1 md:mb-0 text-sm font-medium text-gray-500 dark:text-gray-500 md:w-1/4">Kelurahan / Desa</span>
                <div class="w-full lg:w-1/4">
                    <span class="text-sm font-semibold text-gray-900 dark:text-white"><?= esc($baris['nama_desa'] ?? '-') ?></span>
                </div>

                <span class="block mt-4 md:my-0 md:ml-10 md:mr-12 mb-1 text-sm font-medium text-gray-500 dark:text-gray-500 md:w-1/4">Kecamatan</span>
                <div class="w-full lg:w-1/4">
                    <span class="text-sm font-semibold text-gray-900 dark:text-white"><?= esc($baris['nama_kecamatan'] ?? '-') ?></span>
                </div>
            </div>

            <div class="sm:block md:flex items-center py-3">
                <span class="block mb-1 md:mb-0 text-sm font-medium text-gray-500 dark:text-gray-500 md:w-1/4">Kota / Kabupaten</span>
                <div class="w-full lg:w-1/4">
                    <span class="text-sm font-semibold text-gray-900 dark:text-white"><?= esc($baris['nama_kota_wilayah'] ?? '-') ?></span>
                </div>

                <span class="block mt-4 md:my-0 md:ml-10 md:mr-12 mb-1 text-sm font-medium text-gray-500 dark:text-gray-500 md:w-1/4">Provinsi</span>
                <div class="w-full lg:w-1/4">
                    <span class="text-sm font-semibold text-gray-900 dark:text-white"><?= esc($baris['nama_provinsi'] ?? '-') ?></span>
                </div>
            </div>

            <div class="sm:block md:flex items-center py-3">
                <span class="block mb-1 md:mb-0 text-sm font-medium text-gray-500 dark:text-gray-500 md:w-1/4">Agama</span>
                <div class="w-full lg:w-1/4">
                    <span class="text-sm font-semibold text-gray-900 dark:text-white"><?= esc($baris['id_agama'] ?? '-') ?></span>
                </div>

                <span class="block mt-4 md:my-0 md:ml-10 md:mr-12 mb-1 text-sm font-medium text-gray-500 dark:text-gray-500 md:w-1/4">Status Pernikahan</span>
                <div class="w-full lg:w-1/4">
                    <span class="text-sm font-semibold text-gray-900 dark:text-white"><?= esc($baris['id_pernikahan'] ?? '-') ?></span>
                </div>
            </div>

            <div class="sm:block md:flex items-center py-3">
                <span class="block mb-1 md:mb-0 text-sm font-medium text-gray-500 dark:text-gray-500 md:w-1/4">Nomor Telepon</span>
                <div class="w-full lg:w-1/4">
                    <span class="text-sm font-semibold text-gray-900 dark:text-white"><?= esc($baris['nomor_telepon'] ?? '-') ?></span>
                </div>

                <span class="block mt-4 md:my-0 md:ml-10 md:mr-12 mb-1 text-sm font-medium text-gray-500 dark:text-gray-500 md:w-1/4">Tanggal Donor Terakhir</span>
                <div class="w-full lg:w-1/4">
                    <?php if (!empty($baris['tanggal_donor_terakhir'])): ?>
                        <span class="text-sm font-semibold text-gray-900 dark:text-white">
                            <?= (new IntlDateFormatter('id_ID', IntlDateFormatter::LONG, IntlDateFormatter::NONE))->format(strtotime($baris['tanggal_donor_terakhir'])) ?>
                        </span>
                    <?php else: ?>
                        <span class="text-sm font-semibold text-gray-900 dark:text-white">-</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="mt-8 pt-5 border-t border-gray-200 dark:border-gray-800 flex justify-end">
            <a href="javascript:history.back()" class="py-3 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-white dark:hover:bg-gray-800 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                Kembali
            </a>
        </div>

    </div>
</div>

<?= $this->endSection(); ?>