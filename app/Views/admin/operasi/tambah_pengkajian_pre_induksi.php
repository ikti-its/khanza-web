<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<?= $this->include('components/modal/modaldokter') ?>

<?php
$baseInput     = 'border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full dark:border-gray-600 dark:text-white';
$inputClass    = "$baseInput cursor-pointer bg-slate-50";
$readonlyClass = "$baseInput bg-gray-100 cursor-not-allowed lg:w-1/4";
$stdClass      = "$baseInput bg-slate-50";

$labelLeft     = 'block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4';
$labelRight    = 'block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5';
$btnClass      = 'inline-flex justify-center items-center p-2 text-sm font-medium text-white bg-blue-600 rounded-lg border border-transparent hover:bg-blue-700 focus:outline-none transition-all w-10 h-[38px] flex-shrink-0';
$searchIcon    = '<svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>';
$sectionClass  = 'text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400 border-b border-gray-200 dark:border-gray-700 pb-1 mb-4 mt-6';
$noMinus       = 'onkeydown="if(event.key===\'-\'||event.key===\'e\')event.preventDefault()" oninput="if(this.value<0)this.value=0"';

// Kolom BOOLEAN Postgres dibaca kembali sebagai 't'/'f', bukan '1'/'0' seperti saat disimpan dari form.
$boolToStr = static function (mixed $v): string {
    return match (true) {
        $v === true, $v === 1, $v === '1', $v === 't'  => '1',
        $v === false, $v === 0, $v === '0', $v === 'f' => '0',
        default                                        => '',
    };
};
foreach ([
    'is_sesuai_asesmen',
    'is_intubasi_sesudah_tidur',
    'is_intubasi_oral',
    'is_intubasi_tracheostomi',
    'is_kateter',
] as $boolField) {
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
            <input type="hidden" name="id_jadwal"          value="<?= esc((string) ($baris['id_jadwal']          ?? '')) ?>">
            <input type="hidden" name="id_dokter_anestesi" id="id_dokter_anestesi" value="<?= esc((string) ($baris['id_dokter_anestesi'] ?? '')) ?>">

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
                <label class="<?= $labelLeft ?>">Dokter Anestesi <span class="text-red-500">*</span></label>
                <div class="flex gap-x-2 lg:w-1/4">
                    <input type="text" id="nama_dokter_anestesi"
                           value="<?= esc($baris['nama_dokter_anestesi'] ?? '') ?>"
                           readonly required placeholder="Klik cari dokter..."
                           onclick="open_modalDokter()"
                           class="<?= $inputClass ?>">
                    <button type="button" onclick="open_modalDokter()" class="<?= $btnClass ?>">
                        <?= $searchIcon ?>
                    </button>
                </div>

                <label class="<?= $labelRight ?>">Waktu Pengkajian <span class="text-red-500">*</span></label>
                <input type="datetime-local" name="waktu_pengkajian"
                       value="<?= esc(substr(str_replace(' ', 'T', $baris['waktu_pengkajian'] ?? ''), 0, 16)) ?>"
                       max="<?= date('Y-m-d\TH:i') ?>"
                       required class="<?= $inputClass ?> lg:w-1/4">
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="<?= $labelLeft ?>">Tindakan Operasi</label>
                <input type="text" value="<?= esc($baris['nama_tindakan'] ?? '-') ?>"
                       readonly class="<?= $readonlyClass ?> lg:w-1/4">
            </div>

            <!-- ── Tanda Vital ── -->
            <p class="<?= $sectionClass ?>">Tanda Vital</p>

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
                <label class="<?= $labelLeft ?>">Elektrokardiogram <span class="text-red-500">*</span></label>
                <input type="text" name="elektrokardiogram" value="<?= esc($baris['elektrokardiogram'] ?? '') ?>"
                       required class="<?= $inputClass ?> lg:w-1/4">
            </div>

            <div class="mb-5">
                <label class="block mb-2 text-sm text-gray-900 dark:text-white">
                    Vital Lain-lain <span class="text-red-500">*</span>
                </label>
                <textarea name="vital_lain_lain" rows="2" required
                          class="<?= $stdClass ?>"><?= esc($baris['vital_lain_lain'] ?? '') ?></textarea>
            </div>

            <!-- ── Assessment ── -->
            <p class="<?= $sectionClass ?>">Assessment</p>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="<?= $labelLeft ?>">Sesuai Asesmen <span class="text-red-500">*</span></label>
                <select name="is_sesuai_asesmen" required class="<?= $stdClass ?> lg:w-1/4">
                    <option value="">— Pilih —</option>
                    <option value="1" <?= ($baris['is_sesuai_asesmen'] ?? '') == '1' ? 'selected' : '' ?>>Ya</option>
                    <option value="0" <?= isset($baris['is_sesuai_asesmen']) && $baris['is_sesuai_asesmen'] == '0' ? 'selected' : '' ?>>Tidak</option>
                </select>
            </div>

            <div class="mb-5">
                <label class="block mb-2 text-sm text-gray-900 dark:text-white">
                    Perencanaan <span class="text-red-500">*</span>
                </label>
                <textarea name="perencanaan" rows="3" required
                          class="<?= $stdClass ?>"><?= esc($baris['perencanaan'] ?? '') ?></textarea>
            </div>

            <!-- ── Infus & Kateter ── -->
            <p class="<?= $sectionClass ?>">Infus & Kateter</p>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="<?= $labelLeft ?>">Infus Perifer <span class="text-red-500">*</span></label>
                <input type="text" name="infus_perifer" value="<?= esc($baris['infus_perifer'] ?? '') ?>"
                       required class="<?= $inputClass ?> lg:w-1/4">

                <label class="<?= $labelRight ?>">Kateter Vena Sentral (CVC) <span class="text-red-500">*</span></label>
                <input type="text" name="kateter_vena_sentral_cvc" value="<?= esc($baris['kateter_vena_sentral_cvc'] ?? '') ?>"
                       required class="<?= $inputClass ?> lg:w-1/4">
            </div>

            <!-- ── Posisi & Premedikasi ── -->
            <p class="<?= $sectionClass ?>">Posisi & Premedikasi</p>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="<?= $labelLeft ?>">Posisi <span class="text-red-500">*</span></label>
                <select name="id_posisi" required class="<?= $stdClass ?> lg:w-1/4">
                    <option value="">— Pilih —</option>
                    <?php foreach ($options['posisi'] as $o): ?>
                        <option value="<?= esc($o['id_posisi']) ?>"
                            <?= ($baris['id_posisi'] ?? '') == $o['id_posisi'] ? 'selected' : '' ?>>
                            <?= esc($o['nama_posisi']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="<?= $labelLeft ?>">Premedikasi <span class="text-red-500">*</span></label>
                <select name="id_premedikasi" required class="<?= $stdClass ?> lg:w-1/4">
                    <option value="">— Pilih —</option>
                    <?php foreach ($options['premedikasi'] as $o): ?>
                        <option value="<?= esc($o['id_premedikasi']) ?>"
                            <?= ($baris['id_premedikasi'] ?? '') == $o['id_premedikasi'] ? 'selected' : '' ?>>
                            <?= esc($o['nama_premedikasi']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label class="<?= $labelRight ?>">Keterangan Premedikasi <span class="text-red-500">*</span></label>
                <input type="text" name="ket_premedikasi" value="<?= esc($baris['ket_premedikasi'] ?? '') ?>"
                       required class="<?= $inputClass ?> lg:w-1/4">
            </div>

            <!-- ── Induksi ── -->
            <p class="<?= $sectionClass ?>">Induksi</p>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="<?= $labelLeft ?>">Induksi <span class="text-red-500">*</span></label>
                <select name="id_induksi" required class="<?= $stdClass ?> lg:w-1/4">
                    <option value="">— Pilih —</option>
                    <?php foreach ($options['induksi'] as $o): ?>
                        <option value="<?= esc($o['id_induksi']) ?>"
                            <?= ($baris['id_induksi'] ?? '') == $o['id_induksi'] ? 'selected' : '' ?>>
                            <?= esc($o['nama_induksi']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label class="<?= $labelRight ?>">Keterangan Induksi <span class="text-red-500">*</span></label>
                <input type="text" name="ket_induksi" value="<?= esc($baris['ket_induksi'] ?? '') ?>"
                       required class="<?= $inputClass ?> lg:w-1/4">
            </div>

            <!-- ── Airway ── -->
            <p class="<?= $sectionClass ?>">Airway</p>

            <?php
            $airwayMap = [];
            foreach ($airway as $a) { $airwayMap[$a['id_jenis_airway']] = $a; }
            $awFind = function(string $kw) use ($options, $airwayMap): ?array {
                foreach ($options['jenis_airway'] as $idx => $j) {
                    if (str_contains(strtolower($j['nama_jenis']), $kw)) {
                        return ['idx' => $idx, 'id' => $j['id_jenis'], 'ex' => $airwayMap[$j['id_jenis']] ?? [], 'nama' => $j['nama_jenis']];
                    }
                }
                return null;
            };
            $fm  = $awFind('facemask') ?? $awFind('face');
            $oro = $awFind('oro');
            $ett = $awFind('ett');
            $lma = $awFind('lma');
            $knownIds = array_filter(array_column(array_filter([$fm, $oro, $ett, $lma]), 'id'));
            $others   = array_values(array_filter($options['jenis_airway'], fn($j) => !in_array($j['id_jenis'], $knownIds)));
            ?>

            <?php if ($fm): ?><input type="hidden" name="airway[<?= $fm['idx'] ?>][id_jenis_airway]" value="<?= esc($fm['id']) ?>"><?php endif; ?>
            <?php if ($oro): ?><input type="hidden" name="airway[<?= $oro['idx'] ?>][id_jenis_airway]" value="<?= esc($oro['id']) ?>"><?php endif; ?>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="<?= $labelLeft ?>"><?= esc($fm['nama'] ?? 'Facemask') ?></label>
                <input type="text" name="airway[<?= $fm['idx'] ?? 0 ?>][nomor]"
                       value="<?= esc($fm['ex']['nomor'] ?? '') ?>"
                       placeholder="Nomor" class="<?= $inputClass ?> lg:w-1/4">
                <label class="<?= $labelRight ?>"><?= esc($oro['nama'] ?? 'ORO') ?></label>
                <input type="text" name="airway[<?= $oro['idx'] ?? 1 ?>][nomor]"
                       value="<?= esc($oro['ex']['nomor'] ?? '') ?>"
                       placeholder="Nomor" class="<?= $inputClass ?> lg:w-1/4">
            </div>

            <?php if ($ett): ?><input type="hidden" name="airway[<?= $ett['idx'] ?>][id_jenis_airway]" value="<?= esc($ett['id']) ?>"><?php endif; ?>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="<?= $labelLeft ?>"><?= esc($ett['nama'] ?? 'ETT') ?></label>
                <input type="text" name="airway[<?= $ett['idx'] ?? 2 ?>][nomor]"
                       value="<?= esc($ett['ex']['nomor'] ?? '') ?>"
                       placeholder="Nomor" class="<?= $inputClass ?> lg:w-1/4">
                <label class="<?= $labelRight ?>">ETT Jenis</label>
                <input type="text" name="airway[<?= $ett['idx'] ?? 2 ?>][jenis]"
                       value="<?= esc($ett['ex']['jenis'] ?? '') ?>"
                       class="<?= $inputClass ?> lg:w-1/4">
            </div>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="<?= $labelLeft ?>">ETT Fiksasi</label>
                <div class="flex items-center gap-x-2 lg:w-1/4">
                    <input type="number" name="airway[<?= $ett['idx'] ?? 2 ?>][fiksasi_cm]"
                           value="<?= esc($ett['ex']['fiksasi_cm'] ?? '') ?>"
                           min="0" max="100" class="<?= $stdClass ?>">
                    <span class="text-sm text-gray-400 whitespace-nowrap">cm</span>
                </div>
            </div>

            <?php if ($lma): ?><input type="hidden" name="airway[<?= $lma['idx'] ?>][id_jenis_airway]" value="<?= esc($lma['id']) ?>"><?php endif; ?>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="<?= $labelLeft ?>"><?= esc($lma['nama'] ?? 'LMA') ?></label>
                <input type="text" name="airway[<?= $lma['idx'] ?? 3 ?>][nomor]"
                       value="<?= esc($lma['ex']['nomor'] ?? '') ?>"
                       class="<?= $inputClass ?> lg:w-1/4">
                <label class="<?= $labelRight ?>">LMA Jenis</label>
                <input type="text" name="airway[<?= $lma['idx'] ?? 3 ?>][jenis]"
                       value="<?= esc($lma['ex']['jenis'] ?? '') ?>"
                       class="<?= $inputClass ?> lg:w-1/4">
            </div>

            <?php for ($i = 0, $n = count($others); $i < $n; $i += 2):
                $l    = $others[$i];
                $r    = $others[$i + 1] ?? null;
                $lIdx = array_search($l, $options['jenis_airway']);
                $rIdx = $r ? array_search($r, $options['jenis_airway']) : null;
            ?>
            <input type="hidden" name="airway[<?= $lIdx ?>][id_jenis_airway]" value="<?= esc($l['id_jenis']) ?>">
            <?php if ($r): ?><input type="hidden" name="airway[<?= $rIdx ?>][id_jenis_airway]" value="<?= esc($r['id_jenis']) ?>"><?php endif; ?>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="<?= $labelLeft ?>"><?= esc($l['nama_jenis']) ?></label>
                <input type="text" name="airway[<?= $lIdx ?>][keterangan]"
                       value="<?= esc($airwayMap[$l['id_jenis']]['keterangan'] ?? '') ?>"
                       class="<?= $inputClass ?> lg:w-1/4">
                <?php if ($r): ?>
                <label class="<?= $labelRight ?>"><?= esc($r['nama_jenis']) ?></label>
                <input type="text" name="airway[<?= $rIdx ?>][keterangan]"
                       value="<?= esc($airwayMap[$r['id_jenis']]['keterangan'] ?? '') ?>"
                       class="<?= $inputClass ?> lg:w-1/4">
                <?php endif; ?>
            </div>
            <?php endfor; ?>

            <!-- ── Intubasi ── -->
            <p class="<?= $sectionClass ?>">Intubasi</p>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="<?= $labelLeft ?>">Sesudah Tidur <span class="text-red-500">*</span></label>
                <select name="is_intubasi_sesudah_tidur" required class="<?= $stdClass ?> lg:w-1/4">
                    <option value="">— Pilih —</option>
                    <option value="1" <?= ($baris['is_intubasi_sesudah_tidur'] ?? '') == '1' ? 'selected' : '' ?>>Ya</option>
                    <option value="0" <?= isset($baris['is_intubasi_sesudah_tidur']) && $baris['is_intubasi_sesudah_tidur'] == '0' ? 'selected' : '' ?>>Tidak</option>
                </select>

                <label class="<?= $labelRight ?>">Oral <span class="text-red-500">*</span></label>
                <select name="is_intubasi_oral" required class="<?= $stdClass ?> lg:w-1/4">
                    <option value="">— Pilih —</option>
                    <option value="1" <?= ($baris['is_intubasi_oral'] ?? '') == '1' ? 'selected' : '' ?>>Ya</option>
                    <option value="0" <?= isset($baris['is_intubasi_oral']) && $baris['is_intubasi_oral'] == '0' ? 'selected' : '' ?>>Tidak</option>
                </select>
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="<?= $labelLeft ?>">Tracheostomi <span class="text-red-500">*</span></label>
                <select name="is_intubasi_tracheostomi" required class="<?= $stdClass ?> lg:w-1/4">
                    <option value="">— Pilih —</option>
                    <option value="1" <?= ($baris['is_intubasi_tracheostomi'] ?? '') == '1' ? 'selected' : '' ?>>Ya</option>
                    <option value="0" <?= isset($baris['is_intubasi_tracheostomi']) && $baris['is_intubasi_tracheostomi'] == '0' ? 'selected' : '' ?>>Tidak</option>
                </select>

                <label class="<?= $labelRight ?>">Keterangan Intubasi <span class="text-red-500">*</span></label>
                <input type="text" name="intubasi_keterangan" value="<?= esc($baris['intubasi_keterangan'] ?? '') ?>"
                       required class="<?= $inputClass ?> lg:w-1/4">
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="<?= $labelLeft ?>">Sulit Ventilasi <span class="text-red-500">*</span></label>
                <input type="text" name="sulit_ventilasi" value="<?= esc($baris['sulit_ventilasi'] ?? '') ?>"
                       required class="<?= $inputClass ?> lg:w-1/4">

                <label class="<?= $labelRight ?>">Sulit Intubasi <span class="text-red-500">*</span></label>
                <input type="text" name="sulit_intubasi" value="<?= esc($baris['sulit_intubasi'] ?? '') ?>"
                       required class="<?= $inputClass ?> lg:w-1/4">
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="<?= $labelLeft ?>">Keterangan Ventilasi <span class="text-red-500">*</span></label>
                <input type="text" name="ventilasi_keterangan" value="<?= esc($baris['ventilasi_keterangan'] ?? '') ?>"
                       required class="<?= $inputClass ?> lg:w-1/4">
            </div>

            <!-- ── Teknik Regional ── -->
            <p class="<?= $sectionClass ?>">Teknik Regional</p>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="<?= $labelLeft ?>">Jenis <span class="text-red-500">*</span></label>
                <input type="text" name="teknik_regional_jenis" value="<?= esc($baris['teknik_regional_jenis'] ?? '') ?>"
                       required class="<?= $inputClass ?> lg:w-1/4">

                <label class="<?= $labelRight ?>">Lokasi <span class="text-red-500">*</span></label>
                <input type="text" name="teknik_regional_lokasi" value="<?= esc($baris['teknik_regional_lokasi'] ?? '') ?>"
                       required class="<?= $inputClass ?> lg:w-1/4">
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="<?= $labelLeft ?>">Jenis Jarum/ No <span class="text-red-500">*</span></label>
                <input type="text" name="teknik_regional_jarum" value="<?= esc($baris['teknik_regional_jarum'] ?? '') ?>"
                       required class="<?= $inputClass ?> lg:w-1/4">
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="<?= $labelLeft ?>">Kateter <span class="text-red-500">*</span></label>
                <select name="is_kateter" required class="<?= $stdClass ?> lg:w-1/4">
                    <option value="">— Pilih —</option>
                    <option value="1" <?= ($baris['is_kateter'] ?? '') == '1' ? 'selected' : '' ?>>Ya</option>
                    <option value="0" <?= isset($baris['is_kateter']) && $baris['is_kateter'] == '0' ? 'selected' : '' ?>>Tidak</option>
                </select>

                <label class="<?= $labelRight ?>">Fiksasi <span class="text-red-500">*</span></label>
                <div class="flex items-center gap-x-2 lg:w-1/4">
                    <input type="number" name="kateter_fiksasi_cm" value="<?= esc($baris['kateter_fiksasi_cm'] ?? '') ?>"
                           required min="0" max="100" class="<?= $stdClass ?>">
                    <span class="text-sm text-gray-400 whitespace-nowrap">cm</span>
                </div>
            </div>

            <div class="mb-5">
                <label class="block mb-2 text-sm text-gray-900 dark:text-white">
                    Obat-obatan <span class="text-red-500">*</span>
                </label>
                <textarea name="obat_obatan" rows="3" required
                          class="<?= $stdClass ?>"><?= esc($baris['obat_obatan'] ?? '') ?></textarea>
            </div>

            <div class="mb-5">
                <label class="block mb-2 text-sm text-gray-900 dark:text-white">
                    Komplikasi <span class="text-red-500">*</span>
                </label>
                <textarea name="komplikasi" rows="3" required
                          class="<?= $stdClass ?>"><?= esc($baris['komplikasi'] ?? '') ?></textarea>
            </div>

            <div class="mb-5">
                <label class="block mb-2 text-sm text-gray-900 dark:text-white">
                    Hasil <span class="text-red-500">*</span>
                </label>
                <textarea name="hasil" rows="3" required
                          class="<?= $stdClass ?>"><?= esc($baris['hasil'] ?? '') ?></textarea>
            </div>

            <?= view('components/form/submit_button') ?>
        </form>
    </div>
</div>

<script>
    function autofillFields(item) {
        document.getElementById('id_dokter_anestesi').value   = item.id_dokter   ?? '';
        document.getElementById('nama_dokter_anestesi').value = item.nama_dokter ?? '';
    }

    function validateForm() {
        if (!document.getElementById('id_dokter_anestesi').value) {
            alert('Silakan pilih dokter anestesi terlebih dahulu.');
            return false;
        }
        const btn = document.getElementById('submitButton');
        if (btn) { btn.disabled = true; btn.innerHTML = 'Menyimpan...'; }
        return true;
    }
</script>

<?= $this->endSection(); ?>
