<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<?= $this->include('components/modal/modaldokter') ?>
<?= $this->include('components/modal/modalpetugas') ?>
<?= $this->include('components/modal/modaltindakanoperasi') ?>

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
foreach (['is_identitas_sesuai', 'is_tindakan_sesuai', 'is_area_insisi_sesuai', 'is_antibiotik', 'is_steril_dikonfirmasi', 'is_verifikasi_preop'] as $boolField) {
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
            <input type="hidden" name="id_tindakan"        id="id_tindakan"        value="<?= esc((string) ($baris['id_tindakan']        ?? '')) ?>">
            <input type="hidden" name="id_sn_cn"           id="id_sn_cn"           value="<?= esc((string) ($baris['id_sn_cn']           ?? '')) ?>">
            <input type="hidden" name="id_dokter_bedah"    id="id_dokter_bedah"    value="<?= esc((string) ($baris['id_dokter_bedah']    ?? '')) ?>">
            <input type="hidden" name="id_dokter_anestesi" id="id_dokter_anestesi" value="<?= esc((string) ($baris['id_dokter_anestesi'] ?? '')) ?>">
            <input type="hidden" name="id_perawat_ok"      id="id_perawat_ok"      value="<?= esc((string) ($baris['id_perawat_ok']      ?? '')) ?>">

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
                <label class="<?= $labelLeft ?>">Waktu Time Out <span class="text-red-500">*</span></label>
                <input type="datetime-local" name="waktu_timeout"
                       value="<?= esc(substr(str_replace(' ', 'T', $baris['waktu_timeout'] ?? ''), 0, 16)) ?>"
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
                <label class="<?= $labelLeft ?>">SN/CN <span class="text-red-500">*</span></label>
                <div class="flex gap-x-2 lg:w-1/4">
                    <input type="text" id="nama_sn_cn"
                           value="<?= esc($baris['nama_sn_cn'] ?? '') ?>"
                           readonly required placeholder="Klik cari petugas..."
                           onclick="openPetugasSnCn()"
                           class="<?= $inputClass ?>">
                    <button type="button" onclick="openPetugasSnCn()" class="<?= $btnClass ?>">
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

                <label class="<?= $labelRight ?>">Perawat OK <span class="text-red-500">*</span></label>
                <div class="flex gap-x-2 lg:w-1/4">
                    <input type="text" id="nama_perawat_ok"
                           value="<?= esc($baris['nama_perawat_ok'] ?? '') ?>"
                           readonly required placeholder="Klik cari petugas..."
                           onclick="openPetugasPerawatOk()"
                           class="<?= $inputClass ?>">
                    <button type="button" onclick="openPetugasPerawatOk()" class="<?= $btnClass ?>">
                        <?= $searchIcon ?>
                    </button>
                </div>
            </div>

            <!-- ── Kelengkapan ── -->
            <p class="<?= $sectionClass ?>">Kelengkapan</p>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="<?= $labelLeft ?>">Identitas Sesuai <span class="text-red-500">*</span></label>
                <select name="is_identitas_sesuai" required class="<?= $stdClass ?> lg:w-1/4">
                    <option value="">— Pilih —</option>
                    <option value="1" <?= ($baris['is_identitas_sesuai'] ?? '') == '1' ? 'selected' : '' ?>>Ya</option>
                    <option value="0" <?= isset($baris['is_identitas_sesuai']) && $baris['is_identitas_sesuai'] == '0' ? 'selected' : '' ?>>Tidak</option>
                </select>

                <label class="<?= $labelRight ?>">Tindakan Sesuai <span class="text-red-500">*</span></label>
                <select name="is_tindakan_sesuai" required class="<?= $stdClass ?> lg:w-1/4">
                    <option value="">— Pilih —</option>
                    <option value="1" <?= ($baris['is_tindakan_sesuai'] ?? '') == '1' ? 'selected' : '' ?>>Ya</option>
                    <option value="0" <?= isset($baris['is_tindakan_sesuai']) && $baris['is_tindakan_sesuai'] == '0' ? 'selected' : '' ?>>Tidak</option>
                </select>
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="<?= $labelLeft ?>">Area Insisi Sesuai <span class="text-red-500">*</span></label>
                <select name="is_area_insisi_sesuai" required class="<?= $stdClass ?> lg:w-1/4">
                    <option value="">— Pilih —</option>
                    <option value="1" <?= ($baris['is_area_insisi_sesuai'] ?? '') == '1' ? 'selected' : '' ?>>Ya</option>
                    <option value="0" <?= isset($baris['is_area_insisi_sesuai']) && $baris['is_area_insisi_sesuai'] == '0' ? 'selected' : '' ?>>Tidak</option>
                </select>

                <label class="<?= $labelRight ?>">Penandaan Area <span class="text-red-500">*</span></label>
                <select name="id_penandaan_area" required class="<?= $stdClass ?> lg:w-1/4">
                    <option value="">— Pilih —</option>
                    <?php foreach ($options['ketersediaan'] as $o): ?>
                        <option value="<?= esc($o['id_ketersediaan_status']) ?>"
                            <?= ($baris['id_penandaan_area'] ?? '') == $o['id_ketersediaan_status'] ? 'selected' : '' ?>>
                            <?= esc($o['nama_ketersediaan']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- ── Detail Operasi ── -->
            <p class="<?= $sectionClass ?>">Detail Operasi</p>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="<?= $labelLeft ?>">Perkiraan Waktu <span class="text-red-500">*</span></label>
                <div class="flex items-center gap-x-2 lg:w-1/4">
                    <input type="number" name="perkiraan_waktu_jam"
                           value="<?= esc($baris['perkiraan_waktu_jam'] ?? '') ?>"
                           required min="0" max="24" class="<?= $stdClass ?>">
                    <span class="text-sm text-gray-400 whitespace-nowrap">jam</span>
                </div>

                <label class="<?= $labelRight ?>">Antibiotik <span class="text-red-500">*</span></label>
                <select name="is_antibiotik" required class="<?= $stdClass ?> lg:w-1/4">
                    <option value="">— Pilih —</option>
                    <option value="1" <?= ($baris['is_antibiotik'] ?? '') == '1' ? 'selected' : '' ?>>Ya</option>
                    <option value="0" <?= isset($baris['is_antibiotik']) && $baris['is_antibiotik'] == '0' ? 'selected' : '' ?>>Tidak</option>
                </select>
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="<?= $labelLeft ?>">Nama Antibiotik</label>
                <input type="text" name="nama_antibiotik"
                       value="<?= esc($baris['nama_antibiotik'] ?? '') ?>"
                       class="<?= $inputClass ?> lg:w-1/4">

                <label class="<?= $labelRight ?>">Waktu Pemberian Antibiotik</label>
                <input type="time" name="waktu_antibiotik"
                       value="<?= esc(substr(str_replace(' ', 'T', $baris['waktu_antibiotik'] ?? ''), 0, 16)) ?>"
                       class="<?= $inputClass ?> lg:w-1/4">
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="<?= $labelLeft ?>">Antisipasi Hilang Darah <span class="text-red-500">*</span></label>
                <input type="text" name="antisipasi_hilang_darah"
                       value="<?= esc($baris['antisipasi_hilang_darah'] ?? '') ?>"
                       required class="<?= $inputClass ?> lg:w-1/4">
            </div>

            <!-- ── Hal Khusus & Sterilisasi ── -->
            <p class="<?= $sectionClass ?>">Hal Khusus & Sterilisasi</p>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="<?= $labelLeft ?>">Hal Khusus <span class="text-red-500">*</span></label>
                <select name="id_hal_khusus" required class="<?= $stdClass ?> lg:w-1/4">
                    <option value="">— Pilih —</option>
                    <?php foreach ($options['ketersediaan'] as $o): ?>
                        <option value="<?= esc($o['id_ketersediaan_status']) ?>"
                            <?= ($baris['id_hal_khusus'] ?? '') == $o['id_ketersediaan_status'] ? 'selected' : '' ?>>
                            <?= esc($o['nama_ketersediaan']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label class="<?= $labelRight ?>">Keterangan Hal Khusus</label>
                <input type="text" name="keterangan_hal_khusus"
                       value="<?= esc($baris['keterangan_hal_khusus'] ?? '') ?>"
                       class="<?= $inputClass ?> lg:w-1/4">
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="<?= $labelLeft ?>">Tanggal Steril <span class="text-red-500">*</span></label>
                <input type="date" name="tanggal_steril"
                       value="<?= esc($baris['tanggal_steril'] ?? '') ?>"
                       max="<?= date('Y-m-d') ?>"
                       required class="<?= $inputClass ?> lg:w-1/4">
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="<?= $labelLeft ?>">Steril Dikonfirmasi <span class="text-red-500">*</span></label>
                <select name="is_steril_dikonfirmasi" required class="<?= $stdClass ?> lg:w-1/4">
                    <option value="">— Pilih —</option>
                    <option value="1" <?= ($baris['is_steril_dikonfirmasi'] ?? '') == '1' ? 'selected' : '' ?>>Ya</option>
                    <option value="0" <?= isset($baris['is_steril_dikonfirmasi']) && $baris['is_steril_dikonfirmasi'] == '0' ? 'selected' : '' ?>>Tidak</option>
                </select>

                <label class="<?= $labelRight ?>">Verifikasi Pre Operatif<span class="text-red-500">*</span></label>
                <select name="is_verifikasi_preop" required class="<?= $stdClass ?> lg:w-1/4">
                    <option value="">— Pilih —</option>
                    <option value="1" <?= ($baris['is_verifikasi_preop'] ?? '') == '1' ? 'selected' : '' ?>>Ya</option>
                    <option value="0" <?= isset($baris['is_verifikasi_preop']) && $baris['is_verifikasi_preop'] == '0' ? 'selected' : '' ?>>Tidak</option>
                </select>
            </div>

            <!-- ── Penunjang ── -->
            <p class="<?= $sectionClass ?>">Penunjang</p>

            <?php
            $penunjangMap = [];
            foreach ($penunjang as $p) {
                $penunjangMap[$p['id_jenis_penunjang']] = $p;
            }
            foreach ($options['jenis_penunjang'] as $idx => $jenis):
                $existing = $penunjangMap[$jenis['id_jenis_penunjang']] ?? [];
            ?>
            <input type="hidden" name="penunjang[<?= $idx ?>][id_jenis_penunjang]" value="<?= esc($jenis['id_jenis_penunjang']) ?>">
            <div class="mb-5 sm:block md:flex items-center">
                <label class="<?= $labelLeft ?>"><?= esc($jenis['nama_jenis']) ?></label>
                <select name="penunjang[<?= $idx ?>][id_status]" class="<?= $stdClass ?> lg:w-1/4">
                    <option value="">— Pilih —</option>
                    <?php foreach ($options['status_penayangan'] as $o): ?>
                        <option value="<?= esc($o['id_status_penayangan']) ?>"
                            <?= ($existing['id_status'] ?? '') == $o['id_status_penayangan'] ? 'selected' : '' ?>>
                            <?= esc($o['nama_status']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <?php endforeach; ?>

            <?= view('components/form/submit_button') ?>
        </form>
    </div>
</div>

<script>
    // ── Dokter (slot-based: bedah / anestesi) ─────────────────────────────────
    let currentDokterSlot = null;

    function openDokterBedah()    { currentDokterSlot = 'bedah';    open_modalDokter(); }
    function openDokterAnestesi() { currentDokterSlot = 'anestesi'; open_modalDokter(); }

    function autofillFields(item) {
        if (currentDokterSlot === 'bedah') {
            document.getElementById('id_dokter_bedah').value   = item.id_dokter   ?? '';
            document.getElementById('nama_dokter_bedah').value = item.nama_dokter ?? '';
        } else if (currentDokterSlot === 'anestesi') {
            document.getElementById('id_dokter_anestesi').value   = item.id_dokter   ?? '';
            document.getElementById('nama_dokter_anestesi').value = item.nama_dokter ?? '';
        }
        currentDokterSlot = null;
    }

    // ── Petugas (slot-based: sn_cn / perawat_ok) ─────────────────────────────
    let currentPetugasSlot = null;

    function openPetugasSnCn()      { currentPetugasSlot = 'sn_cn';      open_modalPetugas(); }
    function openPetugasPerawatOk() { currentPetugasSlot = 'perawat_ok'; open_modalPetugas(); }

    function autofillPetugas(item) {
        if (currentPetugasSlot === 'sn_cn') {
            document.getElementById('id_sn_cn').value   = item.id_petugas ?? '';
            document.getElementById('nama_sn_cn').value = item.nama       ?? '';
        } else if (currentPetugasSlot === 'perawat_ok') {
            document.getElementById('id_perawat_ok').value   = item.id_petugas ?? '';
            document.getElementById('nama_perawat_ok').value = item.nama       ?? '';
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
        const reqs = [
            { id: 'id_tindakan', msg: 'Silakan pilih tindakan operasi terlebih dahulu.' },
            { id: 'id_sn_cn', msg: 'Silakan pilih SN/CN terlebih dahulu.' },
            { id: 'id_dokter_bedah', msg: 'Silakan pilih dokter bedah terlebih dahulu.' },
            { id: 'id_dokter_anestesi', msg: 'Silakan pilih dokter anestesi terlebih dahulu.' },
            { id: 'id_perawat_ok', msg: 'Silakan pilih perawat OK terlebih dahulu.' }
        ];

        for (let v of reqs) {
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
