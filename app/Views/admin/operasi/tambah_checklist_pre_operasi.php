<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<?= $this->include('components/modal/modaldokter') ?>
<?= $this->include('components/modal/modalpetugas') ?>

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
?>

<div class="max-w-[85rem] py-6 lg:py-3 px-8 mx-auto">
    <div class="bg-white rounded-xl shadow-sm p-4 sm:p-7 dark:bg-slate-900 border border-gray-100 dark:border-gray-800">
        <?= view('components/form/judul', ['judul' => $judul]) ?>

        <form action="<?= $modul_path . $form_action ?>" id="myForm" onsubmit="return validateForm()" method="post">
            <?= csrf_field() ?>
            <input type="hidden" name="id_jadwal"          value="<?= esc((string) ($baris['id_jadwal']        ?? '')) ?>">
            <input type="hidden" name="id_tindakan"        id="id_tindakan"        value="<?= esc((string) ($baris['id_tindakan']        ?? '')) ?>">
            <input type="hidden" name="id_sn_cn"           id="id_sn_cn"           value="<?= esc((string) ($baris['id_sn_cn']           ?? '')) ?>">
            <input type="hidden" name="id_dokter_bedah"    id="id_dokter_bedah"    value="<?= esc((string) ($baris['id_dokter_bedah']    ?? '')) ?>">
            <input type="hidden" name="id_dokter_anestesi" id="id_dokter_anestesi" value="<?= esc((string) ($baris['id_dokter_anestesi'] ?? '')) ?>">
            <input type="hidden" name="id_petugas_ruangan" id="id_petugas_ruangan" value="<?= esc((string) ($baris['id_petugas_ruangan'] ?? '')) ?>">
            <input type="hidden" name="id_petugas_ok"      id="id_petugas_ok"      value="<?= esc((string) ($baris['id_petugas_ok']      ?? '')) ?>">

            <!-- ── Info Pasien | Tindakan ── -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="<?= $labelLeft ?>">No. Registrasi</label>
                <input type="text" value="<?= esc($jadwal['nomor_reg'] ?? '-') ?>"
                       readonly class="<?= $readonlyClass ?> lg:w-1/4">

                <label class="<?= $labelRight ?>">Nama Pasien</label>
                <input type="text" value="<?= esc($jadwal['nama_pasien'] ?? '-') ?>"
                       readonly class="<?= $readonlyClass ?> lg:w-1/4">
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="<?= $labelLeft ?>">Waktu Checklist <span class="text-red-500">*</span></label>
                <input type="datetime-local" name="waktu_checklist"
                       value="<?= esc(substr(str_replace(' ', 'T', $baris['waktu_checklist'] ?? ''), 0, 16)) ?>"
                       max="<?= date('Y-m-d H:i') ?>" required class="<?= $inputClass ?> lg:w-1/4">
            
                <label class="<?= $labelRight ?>">Tindakan Operasi</label>
                <input type="text" value="<?= esc($baris['nama_tindakan'] ?? '-') ?>"
                       readonly class="<?= $readonlyClass ?> lg:w-1/4">
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
                <label class="<?= $labelLeft ?>">
                    Dokter Anestesi <span class="text-red-500">*</span>
                </label>
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
            </div>

            <!-- ── Assessment ── -->
            <p class="<?= $sectionClass ?>">Assessment</p>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="<?= $labelLeft ?>">Identitas Sesuai <span class="text-red-500">*</span></label>
                <select name="is_identitas_sesuai" required class="<?= $stdClass ?> lg:w-1/4">
                    <option value="">— Pilih —</option>
                    <option value="1" <?= ($baris['is_identitas_sesuai'] ?? '') == '1' ? 'selected' : '' ?>>Ya</option>
                    <option value="0" <?= isset($baris['is_identitas_sesuai']) && $baris['is_identitas_sesuai'] == '0' ? 'selected' : '' ?>>Tidak</option>
                </select>

                <label class="<?= $labelRight ?>">
                    Keadaan Umum <span class="text-red-500">*</span>
                </label>
                <select name="id_keadaan_umum" required class="<?= $stdClass ?> lg:w-1/4">
                    <option value="">— Pilih —</option>
                    <?php foreach ($options['keadaan_umum'] as $o): ?>
                        <option value="<?= esc($o['id_keadaan_umum']) ?>"
                            <?= ($baris['id_keadaan_umum'] ?? '') == $o['id_keadaan_umum'] ? 'selected' : '' ?>>
                            <?= esc($o['nama_keadaan']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="<?= $labelLeft ?>">Penandaan Area <span class="text-red-500">*</span></label>
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

            <div class="mb-5 sm:block md:flex items-center">
                <label class="<?= $labelLeft ?>">Ijin Bedah <span class="text-red-500">*</span></label>
                <select name="is_ijin_bedah" required class="<?= $stdClass ?> lg:w-1/4">
                    <option value="">— Pilih —</option>
                    <option value="1" <?= ($baris['is_ijin_bedah'] ?? '') == '1' ? 'selected' : '' ?>>Ada</option>
                    <option value="0" <?= isset($baris['is_ijin_bedah']) && $baris['is_ijin_bedah'] == '0' ? 'selected' : '' ?>>Tidak Ada</option>
                </select>

                <label class="<?= $labelRight ?>">
                    Ijin Anestesi <span class="text-red-500">*</span>
                </label>
                <select name="is_ijin_anestesi" required class="<?= $stdClass ?> lg:w-1/4">
                    <option value="">— Pilih —</option>
                    <option value="1" <?= ($baris['is_ijin_anestesi'] ?? '') == '1' ? 'selected' : '' ?>>Ada</option>
                    <option value="0" <?= isset($baris['is_ijin_anestesi']) && $baris['is_ijin_anestesi'] == '0' ? 'selected' : '' ?>>Tidak Ada</option>
                </select>
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="<?= $labelLeft ?>">Ijin Transfusi <span class="text-red-500">*</span></label>
                <select name="id_ijin_transfusi" required class="<?= $stdClass ?> lg:w-1/4">
                    <option value="">— Pilih —</option>
                    <?php foreach ($options['ketersediaan'] as $o): ?>
                        <option value="<?= esc($o['id_ketersediaan_status']) ?>"
                            <?= ($baris['id_ijin_transfusi'] ?? '') == $o['id_ketersediaan_status'] ? 'selected' : '' ?>>
                            <?= esc($o['nama_ketersediaan']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- ── Persiapan Darah & Perlengkapan ── -->
            <p class="<?= $sectionClass ?>">Persiapan Darah & Perlengkapan</p>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="<?= $labelLeft ?>">Persiapan Darah <span class="text-red-500">*</span></label>
                <select name="id_persiapan_darah" required class="<?= $stdClass ?> lg:w-1/4">
                    <option value="">— Pilih —</option>
                    <?php foreach ($options['ketersediaan'] as $o): ?>
                        <option value="<?= esc($o['id_ketersediaan_status']) ?>"
                            <?= ($baris['id_persiapan_darah'] ?? '') == $o['id_ketersediaan_status'] ? 'selected' : '' ?>>
                            <?= esc($o['nama_ketersediaan']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label class="<?= $labelRight ?>">
                    Keterangan Darah <span class="text-red-500">*</span>
                </label>
                <input type="text" name="ket_persiapan_darah"
                       value="<?= esc($baris['ket_persiapan_darah'] ?? '') ?>"
                       required class="<?= $inputClass ?> lg:w-1/4">
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="<?= $labelLeft ?>">Perlengkapan Khusus <span class="text-red-500">*</span></label>
                <select name="id_perlengkapan_khusus" required class="<?= $stdClass ?> lg:w-1/4">
                    <option value="">— Pilih —</option>
                    <?php foreach ($options['ketersediaan'] as $o): ?>
                        <option value="<?= esc($o['id_ketersediaan_status']) ?>"
                            <?= ($baris['id_perlengkapan_khusus'] ?? '') == $o['id_ketersediaan_status'] ? 'selected' : '' ?>>
                            <?= esc($o['nama_ketersediaan']) ?>
                        </option>
                    <?php endforeach; ?>
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
                <select name="penunjang[<?= $idx ?>][id_ketersediaan]" class="<?= $stdClass ?> lg:w-1/4">
                    <option value="">— Pilih —</option>
                    <?php foreach ($options['ketersediaan'] as $o): ?>
                        <option value="<?= esc($o['id_ketersediaan_status']) ?>"
                            <?= ($existing['id_ketersediaan'] ?? '') == $o['id_ketersediaan_status'] ? 'selected' : '' ?>>
                            <?= esc($o['nama_ketersediaan']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <label class="<?= $labelRight ?>">Keterangan</label>
                <input type="text" name="penunjang[<?= $idx ?>][keterangan]"
                       value="<?= esc($existing['keterangan'] ?? '') ?>"
                       class="<?= $inputClass ?> lg:w-1/4" placeholder="Keterangan">
            </div>
            <?php endforeach; ?>

            <!-- ── Petugas Pelaksana ── -->
            <p class="<?= $sectionClass ?>">Petugas Pelaksana</p>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="<?= $labelLeft ?>">Petugas Ruangan <span class="text-red-500">*</span></label>
                <div class="flex gap-x-2 lg:w-1/4">
                    <input type="text" id="nama_petugas_ruangan"
                           value="<?= esc($baris['nama_petugas_ruangan'] ?? '') ?>"
                           readonly required placeholder="Klik cari petugas..."
                           onclick="openPetugasRuangan()"
                           class="<?= $inputClass ?>">
                    <button type="button" onclick="openPetugasRuangan()" class="<?= $btnClass ?>">
                        <?= $searchIcon ?>
                    </button>
                </div>

                <label class="<?= $labelRight ?>">
                    Petugas OK <span class="text-red-500">*</span>
                </label>
                <div class="flex gap-x-2 lg:w-1/4">
                    <input type="text" id="nama_petugas_ok"
                           value="<?= esc($baris['nama_petugas_ok'] ?? '') ?>"
                           readonly required placeholder="Klik cari petugas..."
                           onclick="openPetugasOk()"
                           class="<?= $inputClass ?>">
                    <button type="button" onclick="openPetugasOk()" class="<?= $btnClass ?>">
                        <?= $searchIcon ?>
                    </button>
                </div>
            </div>

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
            document.getElementById('id_dokter_bedah').value     = item.id_dokter   ?? '';
            document.getElementById('nama_dokter_bedah').value   = item.nama_dokter ?? '';
        } else if (currentDokterSlot === 'anestesi') {
            document.getElementById('id_dokter_anestesi').value   = item.id_dokter   ?? '';
            document.getElementById('nama_dokter_anestesi').value = item.nama_dokter ?? '';
        }
        currentDokterSlot = null;
    }

    // ── Petugas (slot-based: sn_cn / ruangan / ok) ───────────────────────────
    let currentPetugasSlot = null;

    function openPetugasSnCn()    { currentPetugasSlot = 'sn_cn';   open_modalPetugas(); }
    function openPetugasRuangan() { currentPetugasSlot = 'ruangan'; open_modalPetugas(); }
    function openPetugasOk()      { currentPetugasSlot = 'ok';      open_modalPetugas(); }

    function autofillPetugas(item) {
        if (currentPetugasSlot === 'sn_cn') {
            document.getElementById('id_sn_cn').value      = item.id_petugas ?? '';
            document.getElementById('nama_sn_cn').value    = item.nama       ?? '';
        } else if (currentPetugasSlot === 'ruangan') {
            document.getElementById('id_petugas_ruangan').value   = item.id_petugas ?? '';
            document.getElementById('nama_petugas_ruangan').value = item.nama       ?? '';
        } else if (currentPetugasSlot === 'ok') {
            document.getElementById('id_petugas_ok').value   = item.id_petugas ?? '';
            document.getElementById('nama_petugas_ok').value = item.nama       ?? '';
        }
        currentPetugasSlot = null;
    }

    // ── Validasi ─────────────────────────────────────────────────────────────
    function validateForm() {
        const validations = [
            { id: 'id_dokter_bedah', msg: 'Silakan pilih dokter bedah terlebih dahulu.' },
            { id: 'id_dokter_anestesi', msg: 'Silakan pilih dokter anestesi terlebih dahulu.' },
            { id: 'id_sn_cn', msg: 'Silakan pilih SN/CN terlebih dahulu.' },
            { id: 'id_petugas_ruangan', msg: 'Silakan pilih petugas ruangan terlebih dahulu.' },
            { id: 'id_petugas_ok', msg: 'Silakan pilih petugas OK terlebih dahulu.' }
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
