<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<?= $this->include('components/modal/modaldokter') ?>
<?= $this->include('components/modal/modalpetugas') ?>
<?= $this->include('components/modal/modaltindakanoperasi') ?>

<?php
$baseInput     = 'border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full dark:border-gray-600 dark:text-white';
$inputClass    = "$baseInput cursor-pointer bg-slate-50";
$readonlyClass = "$baseInput bg-gray-100 cursor-not-allowed";
$stdClass      = "$baseInput bg-slate-50";

$labelLeft     = 'block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4';
$labelRight    = 'block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5';
$btnClass      = 'inline-flex justify-center items-center p-2 text-sm font-medium text-white bg-blue-600 rounded-lg border border-transparent hover:bg-blue-700 focus:outline-none transition-all w-10 h-[38px] flex-shrink-0';
$searchIcon    = '<svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>';
$sectionClass  = 'text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400 border-b border-gray-200 dark:border-gray-700 pb-1 mb-4 mt-6';

// Kolom BOOLEAN Postgres dibaca kembali sebagai 't'/'f', bukan '1'/'0' seperti saat disimpan dari form.
$boolToStr = static function (mixed $v): string {
    return match (true) {
        $v === true, $v === 1, $v === '1', $v === 't'  => '1',
        $v === false, $v === 0, $v === '0', $v === 'f' => '0',
        default                                        => '',
    };
};
foreach (['is_alergi', 'is_lanjut_tindakan', 'is_epidural', 'is_spinal', 'is_anestesi_umum', 'is_blok_perifer', 'is_batal_tindakan'] as $boolField) {
    if (array_key_exists($boolField, $baris)) {
        $baris[$boolField] = $boolToStr($baris[$boolField]);
    }
}
?>

<div class="max-w-[85rem] py-6 lg:py-3 px-8 mx-auto">
    <div class="bg-white rounded-xl shadow-sm p-4 sm:p-7 dark:bg-slate-900 border border-gray-100 dark:border-gray-800">
        <?= view('components/form/judul', ['judul' => $judul]) ?>

        <form action="<?= $modul_path . $form_action ?>" id="myForm" onsubmit="return validateForm()" method="post">
            <?= csrf_field() ?>
            <input type="hidden" name="id_jadwal"           value="<?= esc((string) ($baris['id_jadwal']           ?? '')) ?>">
            <input type="hidden" name="id_tindakan"         id="id_tindakan"         value="<?= esc((string) ($baris['id_tindakan']         ?? '')) ?>">
            <input type="hidden" name="id_dokter_anestesi"  id="id_dokter_anestesi"  value="<?= esc((string) ($baris['id_dokter_anestesi']  ?? '')) ?>">
            <input type="hidden" name="id_dokter_bedah"     id="id_dokter_bedah"     value="<?= esc((string) ($baris['id_dokter_bedah']     ?? '')) ?>">
            <input type="hidden" name="id_perawat_anestesi" id="id_perawat_anestesi" value="<?= esc((string) ($baris['id_perawat_anestesi'] ?? '')) ?>">
            <input type="hidden" name="id_perawat_bedah"    id="id_perawat_bedah"    value="<?= esc((string) ($baris['id_perawat_bedah']    ?? '')) ?>">

            <!-- ── Info Pasien ── -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="<?= $labelLeft ?>">No. Registrasi</label>
                <input type="text" value="<?= esc($jadwal['nomor_reg'] ?? '-') ?>"
                       readonly class="<?= $readonlyClass ?> lg:w-1/4">

                <label class="<?= $labelRight ?>">Nama Pasien</label>
                <input type="text" value="<?= esc($jadwal['nama_pasien'] ?? '-') ?>"
                       readonly class="<?= $readonlyClass ?> lg:w-1/4">
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="<?= $labelLeft ?>">Waktu Catatan <span class="text-red-500">*</span></label>
                <input type="datetime-local" name="waktu_catatan"
                       value="<?= esc(substr(str_replace(' ', 'T', $baris['waktu_catatan'] ?? ''), 0, 16)) ?>"
                       max="<?= date('Y-m-d\TH:i') ?>" required class="<?= $inputClass ?> lg:w-1/4">

                <label class="<?= $labelRight ?>">Tindakan Operasi <span class="text-red-500">*</span></label>
                <div class="flex gap-x-2 lg:w-1/4">
                    <input type="text" id="nama_tindakan"
                           value="<?= esc($baris['nama_tindakan'] ?? '') ?>"
                           readonly required placeholder="Klik cari tindakan..."
                           onclick="open_modalTindakanOperasi()"
                           class="<?= $inputClass ?>">
                    <button type="button" onclick="open_modalTindakanOperasi()" class="<?= $btnClass ?>">
                        <?= $searchIcon ?>
                    </button>
                </div>
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="<?= $labelLeft ?>">Diagnosa Pra Bedah <span class="text-red-500">*</span></label>
                <input type="text" name="diagnosa_pra_bedah"
                       value="<?= esc($baris['diagnosa_pra_bedah'] ?? '') ?>"
                       required class="<?= $inputClass ?> lg:w-1/4">

                <label class="<?= $labelRight ?>">Diagnosa Paska Bedah <span class="text-red-500">*</span></label>
                <input type="text" name="diagnosa_paska_bedah"
                       value="<?= esc($baris['diagnosa_paska_bedah'] ?? '') ?>"
                       required class="<?= $inputClass ?> lg:w-1/4">
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="<?= $labelLeft ?>">Dokter Anestesi <span class="text-red-500">*</span></label>
                <div class="flex gap-x-2 lg:w-1/4">
                    <input type="text" id="nama_dokter_anestesi"
                           value="<?= esc($baris['nama_dokter_anestesi'] ?? '') ?>"
                           readonly required placeholder="Klik cari dokter..."
                           onclick="openDokterAnestesi()"
                           class="<?= $inputClass ?>">
                    <button type="button" onclick="openDokterAnestesi()" class="<?= $btnClass ?>">
                        <?= $searchIcon ?>
                    </button>
                </div>

                <label class="<?= $labelRight ?>">Dokter Bedah <span class="text-red-500">*</span></label>
                <div class="flex gap-x-2 lg:w-1/4">
                    <input type="text" id="nama_dokter_bedah"
                           value="<?= esc($baris['nama_dokter_bedah'] ?? '') ?>"
                           readonly required placeholder="Klik cari dokter..."
                           onclick="openDokterBedah()"
                           class="<?= $inputClass ?>">
                    <button type="button" onclick="openDokterBedah()" class="<?= $btnClass ?>">
                        <?= $searchIcon ?>
                    </button>
                </div>
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="<?= $labelLeft ?>">Perawat Anestesi <span class="text-red-500">*</span></label>
                <div class="flex gap-x-2 lg:w-1/4">
                    <input type="text" id="nama_perawat_anestesi"
                           value="<?= esc($baris['nama_perawat_anestesi'] ?? '') ?>"
                           readonly required placeholder="Klik cari petugas..."
                           onclick="openPetugasAnestesi()"
                           class="<?= $inputClass ?>">
                    <button type="button" onclick="openPetugasAnestesi()" class="<?= $btnClass ?>">
                        <?= $searchIcon ?>
                    </button>
                </div>

                <label class="<?= $labelRight ?>">Perawat Bedah <span class="text-red-500">*</span></label>
                <div class="flex gap-x-2 lg:w-1/4">
                    <input type="text" id="nama_perawat_bedah"
                           value="<?= esc($baris['nama_perawat_bedah'] ?? '') ?>"
                           readonly required placeholder="Klik cari petugas..."
                           onclick="openPetugasBedah()"
                           class="<?= $inputClass ?>">
                    <button type="button" onclick="openPetugasBedah()" class="<?= $btnClass ?>">
                        <?= $searchIcon ?>
                    </button>
                </div>
            </div>

            <!-- ── Pengkajian Pra Induksi ── -->
            <p class="<?= $sectionClass ?>">Pengkajian Pra Induksi</p>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="<?= $labelLeft ?>">Jam Pengkajian <span class="text-red-500">*</span></label>
                <input type="time" name="jam_pengkajian"
                       value="<?= esc($baris['jam_pengkajian'] ?? '') ?>"
                       required class="<?= $inputClass ?> lg:w-1/4">

                <label class="<?= $labelRight ?>">Kesadaran <span class="text-red-500">*</span></label>
                <select name="id_kesadaran" required class="<?= $stdClass ?> lg:w-1/4">
                    <option value="">— Pilih —</option>
                    <?php foreach ($options['kesadaran'] as $o): ?>
                        <option value="<?= esc($o['id_kesadaran']) ?>"
                            <?= ($baris['id_kesadaran'] ?? '') == $o['id_kesadaran'] ? 'selected' : '' ?>>
                            <?= esc($o['nama_kesadaran']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="<?= $labelLeft ?>">Tekanan Darah <span class="text-red-500">*</span></label>
                <div class="flex items-center gap-x-2 lg:w-1/4">
                    <input type="number" name="sistolik" value="<?= esc($baris['sistolik'] ?? '') ?>"
                           placeholder="Sistolik" required min="0" max="300" class="<?= $stdClass ?>">
                    <span class="text-gray-500 font-semibold flex-shrink-0">/</span>
                    <input type="number" name="diastolik" value="<?= esc($baris['diastolik'] ?? '') ?>"
                           placeholder="Diastolik" required min="0" max="200" class="<?= $stdClass ?>">
                    <span class="text-sm text-gray-400 whitespace-nowrap">mmHg</span>
                </div>

                <label class="<?= $labelRight ?>">Nadi <span class="text-red-500">*</span></label>
                <div class="flex items-center gap-x-2 lg:w-1/4">
                    <input type="number" name="nadi" value="<?= esc($baris['nadi'] ?? '') ?>"
                           required min="0" max="300" class="<?= $stdClass ?>">
                    <span class="text-sm text-gray-400 whitespace-nowrap">x/mnt</span>
                </div>
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="<?= $labelLeft ?>">Respiratory Rate <span class="text-red-500">*</span></label>
                <div class="flex items-center gap-x-2 lg:w-1/4">
                    <input type="number" name="respiratory_rate" value="<?= esc($baris['respiratory_rate'] ?? '') ?>"
                           required min="0" max="100" class="<?= $stdClass ?>">
                    <span class="text-sm text-gray-400 whitespace-nowrap">x/mnt</span>
                </div>

                <label class="<?= $labelRight ?>">Suhu <span class="text-red-500">*</span></label>
                <div class="flex items-center gap-x-2 lg:w-1/4">
                    <input type="number" name="suhu" value="<?= esc($baris['suhu'] ?? '') ?>"
                           required step="0.1" min="30" max="45" class="<?= $stdClass ?>">
                    <span class="text-sm text-gray-400 whitespace-nowrap">°C</span>
                </div>
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="<?= $labelLeft ?>">Saturasi O2 <span class="text-red-500">*</span></label>
                <div class="flex items-center gap-x-2 lg:w-1/4">
                    <input type="number" name="saturasi_o2" value="<?= esc($baris['saturasi_o2'] ?? '') ?>"
                           required min="0" max="100" class="<?= $stdClass ?>">
                    <span class="text-sm text-gray-400 whitespace-nowrap">%</span>
                </div>

                <label class="<?= $labelRight ?>">Tinggi Badan <span class="text-red-500">*</span></label>
                <div class="flex items-center gap-x-2 lg:w-1/4">
                    <input type="number" name="tinggi_badan_cm" value="<?= esc($baris['tinggi_badan_cm'] ?? '') ?>"
                           required min="0" max="300" class="<?= $stdClass ?>">
                    <span class="text-sm text-gray-400 whitespace-nowrap">cm</span>
                </div>
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="<?= $labelLeft ?>">Berat Badan <span class="text-red-500">*</span></label>
                <div class="flex items-center gap-x-2 lg:w-1/4">
                    <input type="number" name="berat_badan_kg" value="<?= esc($baris['berat_badan_kg'] ?? '') ?>"
                           required min="0" max="700" step="0.1" class="<?= $stdClass ?>">
                    <span class="text-sm text-gray-400 whitespace-nowrap">kg</span>
                </div>

                <label class="<?= $labelRight ?>">Golongan Darah <span class="text-red-500">*</span></label>
                <div class="flex items-center gap-x-2 lg:w-1/4">
                    <select name="id_golongan_darah" required class="<?= $stdClass ?>">
                        <option value="">— Pilih —</option>
                        <?php foreach ($options['golongan_darah'] as $o): ?>
                            <option value="<?= esc($o['id_golongan_darah']) ?>"
                                <?= ($baris['id_golongan_darah'] ?? '') == $o['id_golongan_darah'] ? 'selected' : '' ?>>
                                <?= esc($o['nama_golongan_darah']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <select name="id_rhesus" required class="<?= $stdClass ?> w-auto">
                        <option value="">— Pilih —</option>
                        <?php foreach ($options['rhesus'] as $o): ?>
                            <option value="<?= esc($o['id_rhesus']) ?>"
                                <?= ($baris['id_rhesus'] ?? '') == $o['id_rhesus'] ? 'selected' : '' ?>>
                                <?= esc($o['kode_rhesus']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="<?= $labelLeft ?>">Hemoglobin <span class="text-red-500">*</span></label>
                <div class="flex items-center gap-x-2 lg:w-1/4">
                    <input type="number" name="hemoglobin" value="<?= esc($baris['hemoglobin'] ?? '') ?>"
                           required step="0.1" min="0" max="30" class="<?= $stdClass ?>">
                    <span class="text-sm text-gray-400 whitespace-nowrap">g/dL</span>
                </div>

                <label class="<?= $labelRight ?>">Hematokrit <span class="text-red-500">*</span></label>
                <div class="flex items-center gap-x-2 lg:w-1/4">
                    <input type="number" name="hematokrit" value="<?= esc($baris['hematokrit'] ?? '') ?>"
                           required step="0.1" min="0" max="100" class="<?= $stdClass ?>">
                    <span class="text-sm text-gray-400 whitespace-nowrap">%</span>
                </div>
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="<?= $labelLeft ?>">Leukosit <span class="text-red-500">*</span></label>
                <div class="flex items-center gap-x-2 lg:w-1/4">
                    <input type="number" name="leukosit" value="<?= esc($baris['leukosit'] ?? '') ?>"
                           required min="0" max="500000" class="<?= $stdClass ?>">
                    <span class="text-sm text-gray-400 whitespace-nowrap">/µL</span>
                </div>

                <label class="<?= $labelRight ?>">Trombosit <span class="text-red-500">*</span></label>
                <div class="flex items-center gap-x-2 lg:w-1/4">
                    <input type="number" name="trombosit" value="<?= esc($baris['trombosit'] ?? '') ?>"
                           required min="0" max="1000000" class="<?= $stdClass ?>">
                    <span class="text-sm text-gray-400 whitespace-nowrap">/µL</span>
                </div>
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="<?= $labelLeft ?>">Bleeding Time (BT) <span class="text-red-500">*</span></label>
                <div class="flex items-center gap-x-2 lg:w-1/4">
                    <input type="number" name="bleeding_time_bt" value="<?= esc($baris['bleeding_time_bt'] ?? '') ?>"
                           required step="0.1" min="0" max="60" class="<?= $stdClass ?>">
                    <span class="text-sm text-gray-400 whitespace-nowrap">menit</span>
                </div>

                <label class="<?= $labelRight ?>">Clotting Time (CT) <span class="text-red-500">*</span></label>
                <div class="flex items-center gap-x-2 lg:w-1/4">
                    <input type="number" name="clotting_time_ct" value="<?= esc($baris['clotting_time_ct'] ?? '') ?>"
                           required step="0.1" min="0" max="60" class="<?= $stdClass ?>">
                    <span class="text-sm text-gray-400 whitespace-nowrap">menit</span>
                </div>
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="<?= $labelLeft ?>">Gula Darah Sewaktu <span class="text-red-500">*</span></label>
                <div class="flex items-center gap-x-2 lg:w-1/4">
                    <input type="number" name="gula_darah_sewaktu" value="<?= esc($baris['gula_darah_sewaktu'] ?? '') ?>"
                           required min="0" max="1000" class="<?= $stdClass ?>">
                    <span class="text-sm text-gray-400 whitespace-nowrap">mg/dL</span>
                </div>
            </div>

            <div class="mb-5">
                <label class="block mb-2 text-sm text-gray-900 dark:text-white">
                    Klinis Lain-lain <span class="text-red-500">*</span>
                </label>
                <textarea name="klinis_lain_lain" rows="2" required
                          class="<?= $stdClass ?>"><?= esc($baris['klinis_lain_lain'] ?? '') ?></textarea>
            </div>

            <!-- ── Anestesi ── -->
            <p class="<?= $sectionClass ?>">Anestesi</p>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="<?= $labelLeft ?>">ASA <span class="text-red-500">*</span></label>
                <select name="id_asa" required class="<?= $stdClass ?> lg:w-1/4">
                    <option value="">— Pilih —</option>
                    <?php foreach ($options['asa'] as $o): ?>
                        <option value="<?= esc($o['id_asa']) ?>"
                            <?= ($baris['id_asa'] ?? '') == $o['id_asa'] ? 'selected' : '' ?>>
                            <?= esc($o['nama_asa']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label class="<?= $labelRight ?>">Alergi <span class="text-red-500">*</span></label>
                <select name="is_alergi" required class="<?= $stdClass ?> lg:w-1/4">
                    <option value="">— Pilih —</option>
                    <option value="1" <?= ($baris['is_alergi'] ?? '') == '1' ? 'selected' : '' ?>>Ya</option>
                    <option value="0" <?= isset($baris['is_alergi']) && $baris['is_alergi'] == '0' ? 'selected' : '' ?>>Tidak</option>
                </select>
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="<?= $labelLeft ?>">Keterangan Alergi <span class="text-red-500">*</span></label>
                <input type="text" name="ket_alergi"
                       value="<?= esc($baris['ket_alergi'] ?? '') ?>"
                       required class="<?= $inputClass ?> lg:w-1/4">
            </div>

            <div class="mb-5">
                <label class="block mb-2 text-sm text-gray-900 dark:text-white">
                    Penyulit Pra <span class="text-red-500">*</span>
                </label>
                <textarea name="penyulit_pra" rows="2" required
                          class="<?= $stdClass ?>"><?= esc($baris['penyulit_pra'] ?? '') ?></textarea>
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="<?= $labelLeft ?>">Lanjut Tindakan <span class="text-red-500">*</span></label>
                <select name="is_lanjut_tindakan" required class="<?= $stdClass ?> lg:w-1/4">
                    <option value="">— Pilih —</option>
                    <option value="1" <?= ($baris['is_lanjut_tindakan'] ?? '') == '1' ? 'selected' : '' ?>>Ya</option>
                    <option value="0" <?= isset($baris['is_lanjut_tindakan']) && $baris['is_lanjut_tindakan'] == '0' ? 'selected' : '' ?>>Tidak</option>
                </select>
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="<?= $labelLeft ?>">Jenis Sedasi <span class="text-red-500">*</span></label>
                <select name="id_jenis_sedasi" required class="<?= $stdClass ?> lg:w-1/4">
                    <option value="">— Pilih —</option>
                    <?php foreach ($options['jenis_sedasi'] as $o): ?>
                        <option value="<?= esc($o['id_jenis_sedasi']) ?>"
                            <?= ($baris['id_jenis_sedasi'] ?? '') == $o['id_jenis_sedasi'] ? 'selected' : '' ?>>
                            <?= esc($o['nama_sedasi']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label class="<?= $labelRight ?>">Keterangan Sedasi <span class="text-red-500">*</span></label>
                <input type="text" name="ket_sedasi"
                       value="<?= esc($baris['ket_sedasi'] ?? '') ?>"
                       required class="<?= $inputClass ?> lg:w-1/4">
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="<?= $labelLeft ?>">Epidural <span class="text-red-500">*</span></label>
                <select name="is_epidural" required class="<?= $stdClass ?> lg:w-1/4">
                    <option value="">— Pilih —</option>
                    <option value="1" <?= ($baris['is_epidural'] ?? '') == '1' ? 'selected' : '' ?>>Ya</option>
                    <option value="0" <?= isset($baris['is_epidural']) && $baris['is_epidural'] == '0' ? 'selected' : '' ?>>Tidak</option>
                </select>

                <label class="<?= $labelRight ?>">Spinal <span class="text-red-500">*</span></label>
                <select name="is_spinal" required class="<?= $stdClass ?> lg:w-1/4">
                    <option value="">— Pilih —</option>
                    <option value="1" <?= ($baris['is_spinal'] ?? '') == '1' ? 'selected' : '' ?>>Ya</option>
                    <option value="0" <?= isset($baris['is_spinal']) && $baris['is_spinal'] == '0' ? 'selected' : '' ?>>Tidak</option>
                </select>
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="<?= $labelLeft ?>">Anestesi Umum <span class="text-red-500">*</span></label>
                <select name="is_anestesi_umum" required class="<?= $stdClass ?> lg:w-1/4">
                    <option value="">— Pilih —</option>
                    <option value="1" <?= ($baris['is_anestesi_umum'] ?? '') == '1' ? 'selected' : '' ?>>Ya</option>
                    <option value="0" <?= isset($baris['is_anestesi_umum']) && $baris['is_anestesi_umum'] == '0' ? 'selected' : '' ?>>Tidak</option>
                </select>

                <label class="<?= $labelRight ?>">Keterangan Anestesi Umum</label>
                <input type="text" name="ket_anestesi_umum"
                       value="<?= esc($baris['ket_anestesi_umum'] ?? '') ?>"
                       class="<?= $inputClass ?> lg:w-1/4">
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="<?= $labelLeft ?>">Blok Perifer <span class="text-red-500">*</span></label>
                <select name="is_blok_perifer" required class="<?= $stdClass ?> lg:w-1/4">
                    <option value="">— Pilih —</option>
                    <option value="1" <?= ($baris['is_blok_perifer'] ?? '') == '1' ? 'selected' : '' ?>>Ya</option>
                    <option value="0" <?= isset($baris['is_blok_perifer']) && $baris['is_blok_perifer'] == '0' ? 'selected' : '' ?>>Tidak</option>
                </select>

                <label class="<?= $labelRight ?>">Keterangan Blok Perifer</label>
                <input type="text" name="ket_blok_perifer"
                       value="<?= esc($baris['ket_blok_perifer'] ?? '') ?>"
                       class="<?= $inputClass ?> lg:w-1/4">
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="<?= $labelLeft ?>">Batal Tindakan <span class="text-red-500">*</span></label>
                <select name="is_batal_tindakan" required class="<?= $stdClass ?> lg:w-1/4">
                    <option value="">— Pilih —</option>
                    <option value="1" <?= ($baris['is_batal_tindakan'] ?? '') == '1' ? 'selected' : '' ?>>Ya</option>
                    <option value="0" <?= isset($baris['is_batal_tindakan']) && $baris['is_batal_tindakan'] == '0' ? 'selected' : '' ?>>Tidak</option>
                </select>

                <label class="<?= $labelRight ?>">Alasan Batal</label>
                <input type="text" name="alasan_batal"
                       value="<?= esc($baris['alasan_batal'] ?? '') ?>"
                       class="<?= $inputClass ?> lg:w-1/4">
            </div>

            <!-- ── Alat Anestesi ── -->
            <p class="<?= $sectionClass ?>">Alat Anestesi</p>

            <?php
            $alatMap  = [];
            foreach ($alat as $a) { $alatMap[$a['id_alat']] = $a; }
            $alatPairs = [];
            $buf       = [];
            foreach ($options['alat'] as $idx => $item) {
                $ex     = $alatMap[$item['id_alat']] ?? [];
                $isLain = str_contains(strtolower($item['nama_alat']), 'lain');
                $entry  = compact('idx', 'item', 'ex', 'isLain');
                if ($isLain) {
                    if ($buf) { $alatPairs[] = [$buf[0], null]; $buf = []; }
                    $alatPairs[] = [$entry, null];
                } else {
                    $buf[] = $entry;
                    if (count($buf) === 2) { $alatPairs[] = [$buf[0], $buf[1]]; $buf = []; }
                }
            }
            if ($buf) { $alatPairs[] = [$buf[0], null]; }
            ?>
            <?php foreach ($alatPairs as [$l, $r]): ?>
                <input type="hidden" name="alat[<?= $l['idx'] ?>][id_alat]" value="<?= esc($l['item']['id_alat']) ?>">
                <?php if ($r): ?><input type="hidden" name="alat[<?= $r['idx'] ?>][id_alat]" value="<?= esc($r['item']['id_alat']) ?>"><?php endif; ?>
            
                <div class="mb-5 sm:block md:flex items-center">
                    <label class="<?= $labelLeft ?>"><?= esc($l['item']['nama_alat']) ?></label>
                    <?php if ($l['isLain']): ?>
                    <input type="text" name="alat[<?= $l['idx'] ?>][keterangan]"
                        value="<?= esc($l['ex']['keterangan'] ?? '') ?>"
                        class="<?= $inputClass ?> lg:w-1/4" placeholder="Keterangan">
                    <?php else: ?>
                    <select name="alat[<?= $l['idx'] ?>][is_digunakan]" class="<?= $stdClass ?> lg:w-1/4">
                        <option value="">— Pilih —</option>
                        <option value="1" <?= ($l['ex']['is_digunakan'] ?? '') == '1' ? 'selected' : '' ?>>Ya</option>
                        <option value="0" <?= isset($l['ex']['is_digunakan']) && $l['ex']['is_digunakan'] == '0' ? 'selected' : '' ?>>Tidak</option>
                    </select>
                    <?php if ($r): ?>
                    <label class="<?= $labelRight ?>"><?= esc($r['item']['nama_alat']) ?></label>
                    <select name="alat[<?= $r['idx'] ?>][is_digunakan]" class="<?= $stdClass ?> lg:w-1/4">
                        <option value="">— Pilih —</option>
                        <option value="1" <?= ($r['ex']['is_digunakan'] ?? '') == '1' ? 'selected' : '' ?>>Ya</option>
                        <option value="0" <?= isset($r['ex']['is_digunakan']) && $r['ex']['is_digunakan'] == '0' ? 'selected' : '' ?>>Tidak</option>
                    </select>
                    <?php endif; ?>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>

            <!-- ── Monitoring ── -->
            <p class="<?= $sectionClass ?>">Monitoring</p>

            <?php
            $monitoringMap    = [];
            foreach ($monitoring as $m) { $monitoringMap[$m['id_monitoring']] = $m; }
            $ketMonitoringSet = ['CVP', 'Arteri Line', 'EKG Lead'];
            $monPairs         = [];
            $buf              = [];
            foreach ($options['monitoring'] as $idx => $item) {
                $ex     = $monitoringMap[$item['id_monitoring']] ?? [];
                $isLain = str_contains(strtolower($item['nama_monitoring']), 'lain');
                $hasKet = in_array($item['nama_monitoring'], $ketMonitoringSet);
                $entry  = compact('idx', 'item', 'ex', 'isLain', 'hasKet');
                if ($isLain || $hasKet) {
                    if ($buf) { $monPairs[] = [$buf[0], null]; $buf = []; }
                    $monPairs[] = [$entry, null];
                } else {
                    $buf[] = $entry;
                    if (count($buf) === 2) { $monPairs[] = [$buf[0], $buf[1]]; $buf = []; }
                }
            }
            if ($buf) { $monPairs[] = [$buf[0], null]; }
            ?>
            <?php foreach ($monPairs as [$l, $r]): ?>
                <input type="hidden" name="monitoring[<?= $l['idx'] ?>][id_monitoring]" value="<?= esc($l['item']['id_monitoring']) ?>">
                <?php if ($r): ?><input type="hidden" name="monitoring[<?= $r['idx'] ?>][id_monitoring]" value="<?= esc($r['item']['id_monitoring']) ?>"><?php endif; ?>
                <div class="mb-5 sm:block md:flex items-center">
                    <label class="<?= $labelLeft ?>"><?= esc($l['item']['nama_monitoring']) ?></label>
                    <?php if ($l['isLain']): ?>
                    <input type="text" name="monitoring[<?= $l['idx'] ?>][keterangan]"
                        value="<?= esc($l['ex']['keterangan'] ?? '') ?>"
                        class="<?= $inputClass ?> lg:w-1/4" placeholder="Keterangan">
                    <?php elseif ($l['hasKet']): ?>
                    <select name="monitoring[<?= $l['idx'] ?>][is_digunakan]" class="<?= $stdClass ?> lg:w-1/4">
                        <option value="">— Pilih —</option>
                        <option value="1" <?= ($l['ex']['is_digunakan'] ?? '') == '1' ? 'selected' : '' ?>>Ya</option>
                        <option value="0" <?= isset($l['ex']['is_digunakan']) && $l['ex']['is_digunakan'] == '0' ? 'selected' : '' ?>>Tidak</option>
                    </select>
                    <label class="<?= $labelRight ?>">Keterangan</label>
                    <input type="text" name="monitoring[<?= $l['idx'] ?>][keterangan]"
                        value="<?= esc($l['ex']['keterangan'] ?? '') ?>"
                        class="<?= $inputClass ?> lg:w-1/4" placeholder="Keterangan">
                    <?php else: ?>
                    <select name="monitoring[<?= $l['idx'] ?>][is_digunakan]" class="<?= $stdClass ?> lg:w-1/4">
                        <option value="">— Pilih —</option>
                        <option value="1" <?= ($l['ex']['is_digunakan'] ?? '') == '1' ? 'selected' : '' ?>>Ya</option>
                        <option value="0" <?= isset($l['ex']['is_digunakan']) && $l['ex']['is_digunakan'] == '0' ? 'selected' : '' ?>>Tidak</option>
                    </select>
                    <?php if ($r): ?>
                    <label class="<?= $labelRight ?>"><?= esc($r['item']['nama_monitoring']) ?></label>
                    <select name="monitoring[<?= $r['idx'] ?>][is_digunakan]" class="<?= $stdClass ?> lg:w-1/4">
                        <option value="">— Pilih —</option>
                        <option value="1" <?= ($r['ex']['is_digunakan'] ?? '') == '1' ? 'selected' : '' ?>>Ya</option>
                        <option value="0" <?= isset($r['ex']['is_digunakan']) && $r['ex']['is_digunakan'] == '0' ? 'selected' : '' ?>>Tidak</option>
                    </select>
                    <?php endif; ?>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>

            <?= view('components/form/submit_button') ?>
        </form>
    </div>
</div>

<script>
    // ── Dokter (slot-based: anestesi / bedah) ─────────────────────────────────
    let currentDokterSlot = null;

    function openDokterAnestesi() { currentDokterSlot = 'anestesi'; open_modalDokter(); }
    function openDokterBedah()    { currentDokterSlot = 'bedah';    open_modalDokter(); }

    function autofillFields(item) {
        if (currentDokterSlot === 'anestesi') {
            document.getElementById('id_dokter_anestesi').value   = item.id_dokter   ?? '';
            document.getElementById('nama_dokter_anestesi').value = item.nama_dokter ?? '';
        } else if (currentDokterSlot === 'bedah') {
            document.getElementById('id_dokter_bedah').value   = item.id_dokter   ?? '';
            document.getElementById('nama_dokter_bedah').value = item.nama_dokter ?? '';
        }
        currentDokterSlot = null;
    }

    // ── Petugas (slot-based: anestesi / bedah) ────────────────────────────────
    let currentPetugasSlot = null;

    function openPetugasAnestesi() { currentPetugasSlot = 'anestesi'; open_modalPetugas(); }
    function openPetugasBedah()    { currentPetugasSlot = 'bedah';    open_modalPetugas(); }

    function autofillPetugas(item) {
        if (currentPetugasSlot === 'anestesi') {
            document.getElementById('id_perawat_anestesi').value   = item.id_petugas ?? '';
            document.getElementById('nama_perawat_anestesi').value = item.nama       ?? '';
        } else if (currentPetugasSlot === 'bedah') {
            document.getElementById('id_perawat_bedah').value   = item.id_petugas ?? '';
            document.getElementById('nama_perawat_bedah').value = item.nama       ?? '';
        }
        currentPetugasSlot = null;
    }

    // ── Tindakan ─────────────────────────────────────────────────────────────
    function autofillTindakan(item) {
        document.getElementById('id_tindakan').value   = item.id_tindakan   ?? '';
        document.getElementById('nama_tindakan').value = item.nama_tindakan ?? '';
    }

    // ── Validasi ─────────────────────────────────────────────────────────────
    function validateForm() {
        const validations = [
            { id: 'id_tindakan', msg: 'Silakan pilih tindakan operasi terlebih dahulu.' },
            { id: 'id_dokter_anestesi', msg: 'Silakan pilih dokter anestesi terlebih dahulu.' },
            { id: 'id_dokter_bedah', msg: 'Silakan pilih dokter bedah terlebih dahulu.' },
            { id: 'id_perawat_anestesi', msg: 'Silakan pilih perawat anestesi terlebih dahulu.' },
            { id: 'id_perawat_bedah', msg: 'Silakan pilih perawat bedah terlebih dahulu.' }
        ];

        for (let v of validations) {
            if (!document.getElementById(v.id).value) {
                alert(v.msg);
                return false;
            }
        }

        const btn = document.getElementById('submitButton');
        if (btn) { btn.disabled = true; btn.innerHTML = 'Menyimpan...'; }
        return true;
    }
</script>

<?= $this->endSection(); ?>
