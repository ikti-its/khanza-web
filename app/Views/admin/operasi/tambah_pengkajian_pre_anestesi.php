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

// Kolom BOOLEAN Postgres dibaca kembali sebagai 't'/'f', bukan '1'/'0' seperti saat disimpan dari form.
$boolToStr = static function (mixed $v): string {
    return match (true) {
        $v === true, $v === 1, $v === '1', $v === 't'  => '1',
        $v === false, $v === 0, $v === '0', $v === 'f' => '0',
        default                                        => '',
    };
};
foreach (['is_merokok', 'is_alkohol'] as $boolField) {
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
            <input type="hidden" name="id_jadwal"          value="<?= esc((string) ($baris['id_jadwal']         ?? '')) ?>">
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
                           readonly required
                           placeholder="Klik cari dokter..."
                           onclick="open_modalDokter()"
                           class="<?= $inputClass ?>">
                    <button type="button" onclick="open_modalDokter()" class="<?= $btnClass ?>">
                        <?= $searchIcon ?>
                    </button>
                </div>

                <label class="<?= $labelRight ?>">
                    Waktu Pengkajian <span class="text-red-500">*</span>
                </label>
                <input type="datetime-local" name="waktu_pengkajian" id="waktu_pengkajian"
                       value="<?= esc(substr(str_replace(' ', 'T', $baris['waktu_pengkajian'] ?? ''), 0, 16)) ?>"
                       max="<?= date('Y-m-d\TH:i') ?>"
                       required class="<?= $inputClass ?> lg:w-1/4">
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="<?= $labelLeft ?>">
                    Tanggal Operasi <span class="text-red-500">*</span>
                </label>
                <div class="flex gap-x-2 lg:w-1/4">
                    <input type="date"
                        name="tanggal_operasi"
                        value="<?= esc($baris['tanggal_operasi'] ?? '') ?>"
                        required
                        class="<?= $inputClass ?>">
                </div>
            </div>

            <!-- ── Klinis ── -->
            <p class="<?= $sectionClass ?>">Klinis</p>

            <div class="mb-5">
                <label class="block mb-2 text-sm text-gray-900 dark:text-white">
                    Diagnosa <span class="text-red-500">*</span>
                </label>
                <textarea name="diagnosa" rows="3" required class="<?= $stdClass ?>"><?= esc($baris['diagnosa'] ?? '') ?></textarea>
            </div>
            <div class="mb-5">
                <label class="block mb-2 text-sm text-gray-900 dark:text-white">
                    Rencana Tindakan <span class="text-red-500">*</span>
                </label>
                <textarea name="rencana_tindakan" rows="3" required class="<?= $stdClass ?>"><?= esc($baris['rencana_tindakan'] ?? '') ?></textarea>
            </div>

            <!-- ── Tanda Vital ── -->
            <p class="<?= $sectionClass ?>">Tanda Vital</p>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="<?= $labelLeft ?>">Tinggi Badan <span class="text-red-500">*</span></label>
                <div class="flex items-center gap-x-2 lg:w-1/4">
                    <input type="number" name="tinggi_badan" value="<?= esc($baris['tinggi_badan'] ?? '') ?>"
                           required min="0" max="300" class="<?= $stdClass ?>">
                    <span class="text-sm text-gray-400 whitespace-nowrap">cm</span>
                </div>

                <label class="<?= $labelRight ?>">
                    Berat Badan <span class="text-red-500">*</span>
                </label>
                <div class="flex items-center gap-x-2 lg:w-1/4">
                    <input type="number" name="berat_badan" value="<?= esc($baris['berat_badan'] ?? '') ?>"
                           required min="0" max="700" class="<?= $stdClass ?>">
                    <span class="text-sm text-gray-400 whitespace-nowrap">kg</span>
                </div>
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

                <label class="<?= $labelRight ?>">
                    Saturasi O₂ <span class="text-red-500">*</span>
                </label>
                <div class="flex items-center gap-x-2 lg:w-1/4">
                    <input type="number" name="saturasi_o2" value="<?= esc($baris['saturasi_o2'] ?? '') ?>"
                           required min="0" max="100" class="<?= $stdClass ?>">
                    <span class="text-sm text-gray-400 whitespace-nowrap">%</span>
                </div>
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="<?= $labelLeft ?>">Nadi <span class="text-red-500">*</span></label>
                <div class="flex items-center gap-x-2 lg:w-1/4">
                    <input type="number" name="nadi" value="<?= esc($baris['nadi'] ?? '') ?>"
                           required min="0" max="300" class="<?= $stdClass ?>">
                    <span class="text-sm text-gray-400 whitespace-nowrap">x/mnt</span>
                </div>

                <label class="<?= $labelRight ?>">
                    Suhu <span class="text-red-500">*</span>
                </label>
                <div class="flex items-center gap-x-2 lg:w-1/4">
                    <input type="number" name="suhu" value="<?= esc($baris['suhu'] ?? '') ?>"
                           required step="0.1" min="30" max="45" class="<?= $stdClass ?>">
                    <span class="text-sm text-gray-400 whitespace-nowrap">°C</span>
                </div>
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="<?= $labelLeft ?>">Pernapasan <span class="text-red-500">*</span></label>
                <div class="flex items-center gap-x-2 lg:w-1/4">
                    <input type="number" name="pernapasan" value="<?= esc($baris['pernapasan'] ?? '') ?>"
                           required min="0" max="100" class="<?= $stdClass ?>">
                    <span class="text-sm text-gray-400 whitespace-nowrap">x/mnt</span>
                </div>
            </div>

            <!-- ── Pemeriksaan Fisik ── -->
            <p class="<?= $sectionClass ?>">Pemeriksaan Fisik</p>

            <?php
            $fisikFields = [
                'fisik_cardiovascular' => 'Cardiovascular',
                'fisik_paru'           => 'Paru',
                'fisik_abdomen'        => 'Abdomen',
                'fisik_extrimitas'     => 'Ekstrimitas',
                'fisik_endokrin'       => 'Endokrin',
                'fisik_ginjal'         => 'Ginjal',
                'fisik_obat_obatan'    => 'Obat-obatan',
                'fisik_laboratorium'   => 'Laboratorium',
                'fisik_penunjang'      => 'Penunjang',
            ];
            foreach ($fisikFields as $name => $label):
            ?>
                <div class="mb-4">
                    <label class="block mb-2 text-sm text-gray-900 dark:text-white">
                        <?= esc($label) ?> <span class="text-red-500">*</span>
                    </label>
                    <textarea name="<?= $name ?>" rows="2" required
                              class="<?= $stdClass ?>"><?= esc($baris[$name] ?? '') ?></textarea>
                </div>
            <?php endforeach; ?>

            <!-- ── Alergi ── -->
            <p class="<?= $sectionClass ?>">Alergi</p>

            <div class="mb-5 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block mb-2 text-sm text-gray-900 dark:text-white">
                        Alergi Obat <span class="text-red-500">*</span>
                    </label>
                    <textarea name="alergi_obat" rows="3" required class="<?= $stdClass ?>"><?= esc($baris['alergi_obat'] ?? '') ?></textarea>
                </div>
                <div>
                    <label class="block mb-2 text-sm text-gray-900 dark:text-white">
                        Alergi Lainnya <span class="text-red-500">*</span>
                    </label>
                    <textarea name="alergi_lainnya" rows="3" required class="<?= $stdClass ?>"><?= esc($baris['alergi_lainnya'] ?? '') ?></textarea>
                </div>
            </div>

            <div class="mb-5">
                <label class="block mb-2 text-sm text-gray-900 dark:text-white">
                    Riwayat Terapi <span class="text-red-500">*</span>
                </label>
                <textarea name="riwayat_terapi" rows="3" required class="<?= $stdClass ?>"><?= esc($baris['riwayat_terapi'] ?? '') ?></textarea>
            </div>

            <!-- ── Kebiasaan Sosial ── -->
            <p class="<?= $sectionClass ?>">Kebiasaan Sosial</p>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="<?= $labelLeft ?>">Merokok <span class="text-red-500">*</span></label>
                <select name="is_merokok" required class="<?= $stdClass ?> lg:w-1/4">
                    <option value="">— Pilih —</option>
                    <option value="1" <?= ($baris['is_merokok'] ?? '') == '1' ? 'selected' : '' ?>>Ya</option>
                    <option value="0" <?= isset($baris['is_merokok']) && $baris['is_merokok'] == '0' ? 'selected' : '' ?>>Tidak</option>
                </select>

                <label class="<?= $labelRight ?>">
                    Jumlah Rokok
                </label>
                <div class="flex items-center gap-x-2 lg:w-1/4">
                    <input type="number" name="jumlah_rokok" value="<?= esc($baris['jumlah_rokok'] ?? '') ?>"
                           min="0" max="200" class="<?= $stdClass ?>">
                    <span class="text-sm text-gray-400 whitespace-nowrap">btg/hari</span>
                </div>
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="<?= $labelLeft ?>">Alkohol <span class="text-red-500">*</span></label>
                <select name="is_alkohol" required class="<?= $stdClass ?> lg:w-1/4">
                    <option value="">— Pilih —</option>
                    <option value="1" <?= ($baris['is_alkohol'] ?? '') == '1' ? 'selected' : '' ?>>Ya</option>
                    <option value="0" <?= isset($baris['is_alkohol']) && $baris['is_alkohol'] == '0' ? 'selected' : '' ?>>Tidak</option>
                </select>

                <label class="<?= $labelRight ?>">
                    Jumlah Alkohol
                </label>
                <div class="flex items-center gap-x-2 lg:w-1/4">
                    <input type="number" name="jumlah_alkohol" value="<?= esc($baris['jumlah_alkohol'] ?? '') ?>"
                           min="0" max="2000" class="<?= $stdClass ?>">
                    <span class="text-sm text-gray-400 whitespace-nowrap">ml/hari</span>
                </div>
            </div>

            <!-- ── Obat Bebas ── -->
            <p class="<?= $sectionClass ?>">Obat Bebas</p>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="<?= $labelLeft ?>">Obat Bebas <span class="text-red-500">*</span></label>
                <select name="id_obat_bebas" required class="<?= $stdClass ?> lg:w-1/4">
                    <option value="">— Pilih —</option>
                    <?php foreach ($options['obat_bebas'] as $o): ?>
                        <option value="<?= esc($o['id_obat_bebas']) ?>"
                            <?= ($baris['id_obat_bebas'] ?? '') == $o['id_obat_bebas'] ? 'selected' : '' ?>>
                            <?= esc($o['nama_kategori']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label class="<?= $labelRight ?>">
                    Keterangan Obat
                </label>
                <input type="text" name="ket_obat" value="<?= esc($baris['ket_obat'] ?? '') ?>"
                       class="<?= $inputClass ?> lg:w-1/4">
            </div>

            <!-- ── Riwayat Penyakit ── -->
            <p class="<?= $sectionClass ?>">Riwayat Penyakit</p>

            <?php
            $riwayatFields = [
                'rw_cardiovascular' => 'Cardiovascular',
                'rw_respiratory'    => 'Respiratory',
                'rw_endocrine'      => 'Endocrine',
                'rw_lainnya'        => 'Lainnya',
            ];
            foreach ($riwayatFields as $name => $label):
            ?>
                <div class="mb-4">
                    <label class="block mb-2 text-sm text-gray-900 dark:text-white">
                        <?= esc($label) ?> <span class="text-red-500">*</span>
                    </label>
                    <textarea name="<?= $name ?>" rows="2" required
                              class="<?= $stdClass ?>"><?= esc($baris[$name] ?? '') ?></textarea>
                </div>
            <?php endforeach; ?>

            <!-- ── Rencana Anestesi ── -->
            <p class="<?= $sectionClass ?>">Rencana Anestesi</p>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="<?= $labelLeft ?>">Rencana Anestesi <span class="text-red-500">*</span></label>
                <select name="id_rencana_anestesi" required class="<?= $stdClass ?> lg:w-1/4">
                    <option value="">— Pilih —</option>
                    <?php foreach ($options['rencana_anestesi'] as $o): ?>
                        <option value="<?= esc($o['id_rencana_anestesi']) ?>"
                            <?= ($baris['id_rencana_anestesi'] ?? '') == $o['id_rencana_anestesi'] ? 'selected' : '' ?>>
                            <?= esc($o['nama_rencana']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label class="<?= $labelRight ?>">
                    ASA <span class="text-red-500">*</span>
                </label>
                <select name="id_asa" required class="<?= $stdClass ?> lg:w-1/4">
                    <option value="">— Pilih —</option>
                    <?php foreach ($options['asa'] as $o): ?>
                        <option value="<?= esc($o['id_asa']) ?>"
                            <?= ($baris['id_asa'] ?? '') == $o['id_asa'] ? 'selected' : '' ?>>
                            <?= esc($o['nama_asa']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="<?= $labelLeft ?>">Waktu Puasa <span class="text-red-500">*</span></label>
                <input type="datetime-local" name="waktu_puasa" value="<?= esc(substr(str_replace(' ', 'T', $baris['waktu_puasa'] ?? ''), 0, 16)) ?>"
                       required class="<?= $inputClass ?> lg:w-1/4">
            </div>

            <!-- ── Perawatan & Catatan ── -->
            <p class="<?= $sectionClass ?>">Perawatan & Catatan</p>

            <div class="mb-5">
                <label class="block mb-2 text-sm text-gray-900 dark:text-white">
                    Rencana Perawatan <span class="text-red-500">*</span>
                </label>
                <textarea name="rencana_perawatan" rows="3" required class="<?= $stdClass ?>"><?= esc($baris['rencana_perawatan'] ?? '') ?></textarea>
            </div>
            <div class="mb-5">
                <label class="block mb-2 text-sm text-gray-900 dark:text-white">
                    Catatan Khusus <span class="text-red-500">*</span>
                </label>
                <textarea name="catatan_khusus" rows="3" required class="<?= $stdClass ?>"><?= esc($baris['catatan_khusus'] ?? '') ?></textarea>
            </div>

            <?= view('components/form/submit_button') ?>
        </form>
    </div>
</div>

<script>
    function autofillFields(item) {
        document.getElementById('id_dokter_anestesi').value    = item.id_dokter   ?? '';
        document.getElementById('nama_dokter_anestesi').value  = item.nama_dokter ?? '';
    }

    // Cegah user mengetik "-", "+", atau "e" di semua input tanda vital (type=number),
    // karena atribut min/max HTML saja bisa dilewati (devtools, paste, dsb).
    document.querySelectorAll('input[type="number"]').forEach(function (input) {
        input.addEventListener('keydown', function (e) {
            if (e.key === '-' || e.key === '+' || e.key === 'e') {
                e.preventDefault();
            }
        });
        input.addEventListener('input', function () {
            if (input.value !== '' && parseFloat(input.value) < 0) {
                input.value = '';
            }
        });
    });

    function validateForm() {
        if (!document.getElementById('id_dokter_anestesi').value) {
            alert('Silakan pilih dokter anestesi terlebih dahulu.');
            return false;
        }

        for (const input of document.querySelectorAll('input[type="number"]')) {
            if (input.value === '') continue;
            const value = parseFloat(input.value);
            const min   = input.min !== '' ? parseFloat(input.min) : null;
            const max   = input.max !== '' ? parseFloat(input.max) : null;
            if ((min !== null && value < min) || (max !== null && value > max)) {
                alert('Nilai pada field "' + (input.name || input.id) + '" harus di antara ' + input.min + ' dan ' + input.max + '.');
                return false;
            }
        }

        const btn = document.getElementById('submitButton');
        if (btn) { btn.disabled = true; btn.innerHTML = 'Menyimpan...'; }
        return true;
    }
</script>

<?= $this->endSection(); ?>
