<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<?php
    $formatterTanggal = new IntlDateFormatter('id_ID', IntlDateFormatter::LONG, IntlDateFormatter::NONE);

    $formatTanggal = static function ($tanggal) use ($formatterTanggal): string {
        return !empty($tanggal) ? $formatterTanggal->format(strtotime((string) $tanggal)) : '-';
    };

    $nilai = static function (array $baris, string $key): string {
        return !empty($baris[$key]) ? (string) $baris[$key] : '-';
    };
?>

<div class="max-w-[85rem] py-6 lg:py-3 px-8 mx-auto animate-fade-in">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-7 dark:bg-slate-900 dark:border-gray-800">

        <div class="flex flex-col items-center text-center pb-5 mb-5 border-b border-gray-200 dark:border-gray-800">
            <div>
                <?= view('components/form/judul', [
                    'judul' => $judul ?? 'Detail Kasus Reaktif'
                ]) ?>
            </div>

            <div class="flex flex-col items-center gap-y-2">
                <div>
                    <span class="text-sm font-medium text-gray-500 dark:text-gray-500 block mb-1">
                        Nomor Kasus
                    </span>
                    <span class="inline-flex items-center px-4 py-1.5 rounded-lg text-sm font-mono font-bold bg-red-50 text-red-700 border border-red-200 dark:bg-red-950/40 dark:text-red-400 dark:border-red-900 shadow-sm">
                        <?= esc($nilai($baris, 'nomor_kasus')) ?>
                    </span>
                </div>

                <div class="flex flex-wrap items-center justify-center gap-3 mt-1">
                    <span class="inline-flex items-center gap-x-1.5 py-1 px-2.5 rounded-lg text-sm font-semibold bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-400">
                        <span class="w-1.5 h-1.5 inline-block rounded-lg bg-red-500"></span>
                        <?= esc($nilai($baris, 'id_status_kasus')) ?>
                    </span>

                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">
                        Ditetapkan: 
                        <span class="font-semibold text-gray-900 dark:text-white">
                            <?= esc($formatTanggal($baris['tanggal_ditetapkan'] ?? null)) ?>
                        </span>
                    </span>
                </div>
            </div>
        </div>

        <div class="mt-5">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-5">

                <div class="space-y-4 p-4 rounded-xl bg-gray-50/50 dark:bg-slate-800/30 border border-gray-100 dark:border-gray-800">
                    <h3 class="text-sm font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500">
                        Data Pendonor
                    </h3>

                    <div>
                        <span class="block text-sm font-medium text-gray-500 dark:text-gray-400">Nomor Kunjungan</span>
                        <span class="text-sm font-semibold text-gray-900 dark:text-white">
                            <?= esc($nilai($baris, 'nomor_kunjungan')) ?>
                        </span>
                    </div>

                    <div>
                        <span class="block text-sm font-medium text-gray-500 dark:text-gray-400">Nomor Pendonor</span>
                        <span class="text-sm font-mono font-semibold text-gray-900 dark:text-white">
                            <?= esc($nilai($baris, 'nomor_pendonor')) ?>
                        </span>
                    </div>

                    <div>
                        <span class="block text-sm font-medium text-gray-500 dark:text-gray-400">Nama Pendonor</span>
                        <span class="text-sm font-semibold text-gray-900 dark:text-white">
                            <?= esc($nilai($baris, 'nama')) ?>
                        </span>
                    </div>
                </div>

                <div class="space-y-4 p-4 rounded-xl bg-gray-50/50 dark:bg-slate-800/30 border border-gray-100 dark:border-gray-800">
                    <h3 class="text-sm font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500">
                        Data Pengambilan Darah
                    </h3>

                    <div>
                        <span class="block text-sm font-medium text-gray-500 dark:text-gray-400">Nomor Pengambilan</span>
                        <span class="text-sm font-semibold text-gray-900 dark:text-white">
                            <?= esc($nilai($baris, 'nomor_pengambilan')) ?>
                        </span>
                    </div>

                    <div>
                        <span class="block text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Pengambilan</span>
                        <span class="text-sm font-semibold text-gray-900 dark:text-white">
                            <?= esc($formatTanggal($baris['tanggal_pengambilan'] ?? null)) ?>
                        </span>
                    </div>

                    <div>
                        <span class="block text-sm font-medium text-gray-500 dark:text-gray-400">Nomor Kantong</span>
                        <span class="text-sm font-mono font-semibold text-gray-900 dark:text-white">
                            <?= esc($nilai($baris, 'no_bag')) ?>
                        </span>
                    </div>
                </div>

                <div class="space-y-4 p-4 rounded-xl bg-gray-50/50 dark:bg-slate-800/30 border border-gray-100 dark:border-gray-800">
                    <h3 class="text-sm font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500">
                        Hasil Uji Saring IMLTD
                    </h3>

                    <div>
                        <span class="block text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Hasil Uji</span>
                        <span class="text-sm font-semibold text-gray-900 dark:text-white">
                            <?= esc($formatTanggal($baris['tanggal_uji'] ?? null)) ?>
                        </span>
                    </div>

                    <div>
                        <span class="block text-sm font-medium text-gray-500 dark:text-gray-400">Metode Uji</span>
                        <span class="text-sm font-semibold text-gray-900 dark:text-white">
                            <?= esc($nilai($baris, 'id_metode_uji')) ?>
                        </span>
                    </div>

                    <div class="pt-2 border-t border-gray-200 dark:border-gray-700">
                        <span class="block text-sm font-medium text-gray-500 dark:text-gray-400">Parameter Reaktif</span>
                        <span class="text-sm font-bold text-red-700 dark:text-red-400">
                            <?= esc($nilai($baris, 'parameter_reaktif')) ?>
                        </span>
                    </div>
                </div>

            </div>
        </div>

        <div class="mt-5 pt-5 border-t border-gray-200 dark:border-gray-800 flex justify-end gap-x-2">
            <a href="javascript:history.back()" class="py-2 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-white dark:hover:bg-gray-800 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                Kembali
            </a>
        </div>

    </div>
</div>

<?= $this->endSection(); ?>