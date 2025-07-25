<?php

/** @var array $pasien */ ?>

<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<div class="max-w-[85rem] py-6 lg:py-3 mx-auto text-[15px]">
    <div class="flex flex-col">
        <div class="-m-1.5 overflow-y-hidden">
            <div class="sm:px-20 min-w-full inline-block align-middle">
                <div class="p-5 bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-slate-900 dark:border-gray-700">

                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-2xl font-black text-gray-800">Informasi Pasien</h2>
                        <a href="<?= base_url('rekam-medis') ?>" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-[#0A2D27] text-[#ACF2E7] hover:bg-[#13594E]">
                            Kembali
                        </a>
                    </div>

                    <!-- Header -->
                    <div class="py-1 flex justify-between items-center border-gray-200 dark:border-gray-700">
                        <div>
                            <?php if (!empty($pasien)) : ?>
                                <div class="mb-4 text-[15px] font-medium text-gray-800 dark:text-gray-200 space-y-1">
                                    <div class="flex">
                                        <span class="w-48 font-semibold">No. RM</span>
                                        <span class="w-1">:</span>
                                        <span class="ml-2"><?= esc($pasien['no_rkm_medis']) ?></span>
                                    </div>
                                    <div class="flex">
                                        <span class="w-48 font-semibold">Nama Pasien</span>
                                        <span class="w-1">:</span>
                                        <span class="ml-2"><?= esc($pasien['nm_pasien']) ?></span>
                                    </div>
                                    <div class="flex">
                                        <span class="w-48 font-semibold">Jenis Kelamin</span>
                                        <span class="w-1">:</span>
                                        <span class="ml-2"><?= esc($pasien['jk']) ?></span>
                                    </div>
                                    <div class="flex">
                                        <span class="w-48 font-semibold">Tanggal Lahir</span>
                                        <span class="w-1">:</span>
                                        <span class="ml-2"><?= date('j F Y', strtotime($pasien['tgl_lahir'])) ?></span>
                                    </div>
                                    <div class="flex">
                                        <span class="w-48 font-semibold">Umur</span>
                                        <span class="w-1">:</span>
                                        <span class="ml-2"><?= esc($pasien['umur']) ?></span>
                                    </div>
                                    <div class="flex items-center">
                                        <span class="w-48 font-semibold">Status Pasien</span>
                                        <span class="w-1">:</span>
                                        <span class="ml-2 <?= badgeStatusPasien($pasien['stts_pasien']) ?> text-white text-xs font-semibold rounded-full px-3 py-1">
                                            <?= esc($pasien['stts_pasien']) ?>
                                        </span>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <!-- End Header -->

                    <div class="border-t border-gray-300 my-4"></div>

                    <h4 class="text-xl font-semibold mb-3 pt-2">Riwayat Kunjungan</h4>

                    <?php if (!empty($pasien['registrasi'])): ?>
                        <div class="overflow-x-auto rounded-md border border-gray-200">
                            <table class="min-w-full divide-y divide-gray-200 text-sm text-left text-gray-800">
                                <thead class="bg-gray-50 text-xs font-semibold text-gray-600 uppercase">
                                    <tr>
                                        <th class="px-4 py-3">Tanggal</th>
                                        <th class="px-4 py-3">Jam</th>
                                        <th class="px-4 py-3">No. Registrasi</th>
                                        <th class="px-4 py-3">No. Rawat</th>
                                        <th class="px-4 py-3">Dokter</th>
                                        <th class="px-4 py-3">Poliklinik</th>
                                        <th class="px-4 py-3">Jenis Bayar</th>
                                        <th class="px-4 py-3">Status Rawat</th>
                                        <th class="px-4 py-3">Status Bayar</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    <?php foreach ($pasien['registrasi'] as $r): ?>
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-4 py-2 text-[14px]"><?= date('d-m-Y', strtotime($r['tanggal'])) ?></td>
                                            <td class="px-4 py-2 text-[14px]"><?= date('H:i', strtotime($r['jam'])) ?> WIB</td>
                                            <td class="px-4 py-2 text-[14px]"><?= esc($r['nomor_reg']) ?></td>
                                            <td class="px-4 py-2 text-[14px]"><?= esc($r['nomor_rawat']) ?></td>
                                            <td class="px-4 py-2 text-[14px]"><?= esc($r['nama_dokter']) ?></td>
                                            <td class="px-4 py-2 text-[14px]"><?= esc($r['poliklinik']) ?></td>
                                            <td class="px-4 py-2 text-[14px]"><?= esc($r['jenis_bayar']) ?></td>
                                            <td class="px-4 py-2 text-[14px]"><?= esc($r['status_rawat']) ?></td>
                                            <td class="px-4 py-2 text-[14px]"><?= esc($r['status_bayar']) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-yellow-800 bg-yellow-100 px-4 py-2 rounded border border-yellow-300 text-[15px]">
                            Belum ada data registrasi ditemukan.
                        </div>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>