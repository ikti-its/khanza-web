<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<?= $this->include('components/modal/modaldokter') ?>
<?= $this->include('components/modal/modalpetugas') ?>

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

$penunjangMap = array_column($penunjang, null, 'id_jenis_penunjang');
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
            <input type="hidden" name="id_petugas_anestesi" id="id_petugas_anestesi" value="<?= esc((string) ($baris['id_petugas_anestesi'] ?? '')) ?>">
            <input type="hidden" name="id_petugas_ok"      id="id_petugas_ok"      value="<?= esc((string) ($baris['id_petugas_ok']      ?? '')) ?>">

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
                <label class="<?= $labelLeft ?>">Waktu Checklist <span class="text-red-500">*</span></label>
                <input type="datetime-local" name="waktu_checklist"
                       value="<?= esc(substr(str_replace(' ', 'T', $baris['waktu_checklist'] ?? ''), 0, 16)) ?>"
                       max="<?= date('Y-m-d\TH:i') ?>" required class="<?= $inputClass ?> lg:w-1/4">

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
            </div>

            <!-- ── Kondisi Pasien ── -->
            <p class="<?= $sectionClass ?>">Kondisi Pasien</p>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="<?= $labelLeft ?>">Kesadaran Pasca Op <span class="text-red-500">*</span></label>
                <select name="id_kesadaran_pascaop" required class="<?= $stdClass ?> lg:w-1/4">
                    <option value="">— Pilih —</option>
                    <?php foreach ($options['kesadaran_pascaop'] as $o): ?>
                        <option value="<?= esc($o['id_kesadaran']) ?>"
                            <?= ($baris['id_kesadaran_pascaop'] ?? '') == $o['id_kesadaran'] ? 'selected' : '' ?>>
                            <?= esc($o['nama_kesadaran']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label class="<?= $labelRight ?>">Jenis Cairan Infus <span class="text-red-500">*</span></label>
                <input type="text" name="jenis_cairan_infus"
                       value="<?= esc($baris['jenis_cairan_infus'] ?? '') ?>"
                       required class="<?= $inputClass ?> lg:w-1/4">
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="<?= $labelLeft ?>">Jaringan PA/VC <span class="text-red-500">*</span></label>
                <select name="id_jaringan_pa_vc" required class="<?= $stdClass ?> lg:w-1/4">
                    <option value="">— Pilih —</option>
                    <?php foreach ($options['ketersediaan'] as $o): ?>
                        <option value="<?= esc($o['id_ketersediaan_status']) ?>"
                            <?= ($baris['id_jaringan_pa_vc'] ?? '') == $o['id_ketersediaan_status'] ? 'selected' : '' ?>>
                            <?= esc($o['nama_ketersediaan']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- ── Kateter & Urine ── -->
            <p class="<?= $sectionClass ?>">Kateter & Urine</p>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="<?= $labelLeft ?>">Kateter Urine <span class="text-red-500">*</span></label>
                <select name="id_kateter_urine" required class="<?= $stdClass ?> lg:w-1/4">
                    <option value="">— Pilih —</option>
                    <?php foreach ($options['ketersediaan'] as $o): ?>
                        <option value="<?= esc($o['id_ketersediaan_status']) ?>"
                            <?= ($baris['id_kateter_urine'] ?? '') == $o['id_ketersediaan_status'] ? 'selected' : '' ?>>
                            <?= esc($o['nama_ketersediaan']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label class="<?= $labelRight ?>">Waktu Pasang Kateter <span class="text-red-500">*</span></label>
                <input type="datetime-local" name="waktu_pasang_kateter"
                       value="<?= esc(substr(str_replace(' ', 'T', $baris['waktu_pasang_kateter'] ?? ''), 0, 16)) ?>"
                       max="<?= date('Y-m-d\TH:i') ?>" required class="<?= $inputClass ?> lg:w-1/4">
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="<?= $labelLeft ?>">Warna Urine <span class="text-red-500">*</span></label>
                <select name="id_warna_urine" required class="<?= $stdClass ?> lg:w-1/4">
                    <option value="">— Pilih —</option>
                    <?php foreach ($options['warna_urine'] as $o): ?>
                        <option value="<?= esc($o['id_warna_urine']) ?>"
                            <?= ($baris['id_warna_urine'] ?? '') == $o['id_warna_urine'] ? 'selected' : '' ?>>
                            <?= esc($o['nama_warna']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                
                <label class="<?= $labelRight ?>">Jumlah Urine<span class="text-red-500">*</span></label>
                <div class="flex items-center gap-x-2 lg:w-1/4">
                    <input type="number" name="jumlah_urine_cc" min="0" max="10000"
                           value="<?= esc($baris['jumlah_urine_cc'] ?? '') ?>"
                           required class="<?= $inputClass ?>">
                    <span class="text-sm text-gray-400 whitespace-nowrap">cc</span>
                </div>
            </div>

            <!-- ── Luka Operasi ── -->
            <p class="<?= $sectionClass ?>">Luka Operasi</p>

            <div class="mb-5">
                <label class="block mb-2 text-sm text-gray-900 dark:text-white">
                    Catatan Luka Operasi <span class="text-red-500">*</span>
                </label>
                <textarea name="catatan_luka_operasi" rows="3" required
                          class="<?= $stdClass ?>"><?= esc($baris['catatan_luka_operasi'] ?? '') ?></textarea>
            </div>

            <!-- ── Drain ── -->
            <p class="<?= $sectionClass ?>">Drain</p>

            <?php $drainRow = $drain[0] ?? []; ?>
            <div class="mb-3 sm:block md:flex items-center">
                <label class="<?= $labelLeft ?>">Drain <span class="text-red-500">*</span></label>
                <select name="drain[0][id_ketersediaan]" required class="<?= $stdClass ?> lg:w-1/4">
                    <option value="">— Pilih —</option>
                    <?php foreach ($options['ketersediaan'] as $o): ?>
                        <option value="<?= esc($o['id_ketersediaan_status']) ?>"
                            <?= ($drainRow['id_ketersediaan'] ?? '') == $o['id_ketersediaan_status'] ? 'selected' : '' ?>>
                            <?= esc($o['nama_ketersediaan']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label class="<?= $labelRight ?>">Jumlah <span class="text-red-500">*</span></label>
                <div class="flex items-center gap-x-2 lg:w-1/4">
                    <input type="number" name="drain[0][jumlah]" min="0" max="10000" required
                           value="<?= esc($drainRow['jumlah'] ?? '') ?>" class="<?= $inputClass ?>">
                    <span class="text-sm text-gray-400 whitespace-nowrap">buah</span>
                </div>
            </div>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="<?= $labelLeft ?>">Letak <span class="text-red-500">*</span></label>
                <input type="text" name="drain[0][letak]" required
                       value="<?= esc($drainRow['letak'] ?? '') ?>" class="<?= $inputClass ?> lg:w-1/4">
                <label class="<?= $labelRight ?>">Warna <span class="text-red-500">*</span></label>
                <input type="text" name="drain[0][warna]" required
                       value="<?= esc($drainRow['warna'] ?? '') ?>" class="<?= $inputClass ?> lg:w-1/4">
            </div>

            <!-- ── Penunjang ── -->
            <p class="<?= $sectionClass ?>">Penunjang</p>

            <?php foreach ($options['jenis_penunjang'] as $idx => $jenis):
                $existing = $penunjangMap[$jenis['id_jenis_penunjang']] ?? [];
            ?>
            <input type="hidden" name="penunjang[<?= $idx ?>][id_jenis_penunjang]"
                   value="<?= esc($jenis['id_jenis_penunjang']) ?>">
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

            <!-- ── Petugas ── -->
            <p class="<?= $sectionClass ?>">Petugas</p>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="<?= $labelLeft ?>">Petugas Anestesi <span class="text-red-500">*</span></label>
                <div class="flex gap-x-2 lg:w-1/4">
                    <input type="text" id="nama_petugas_anestesi"
                           value="<?= esc($baris['nama_petugas_anestesi'] ?? '') ?>"
                           readonly required placeholder="Klik cari petugas..."
                           onclick="openPetugasAnestesi()"
                           class="<?= $inputClass ?>">
                    <button type="button" onclick="openPetugasAnestesi()" class="<?= $btnClass ?>">
                        <?= $searchIcon ?>
                    </button>
                </div>

                <label class="<?= $labelRight ?>">Petugas OK <span class="text-red-500">*</span></label>
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
            document.getElementById('id_dokter_bedah').value   = item.id_dokter   ?? '';
            document.getElementById('nama_dokter_bedah').value = item.nama_dokter ?? '';
        } else if (currentDokterSlot === 'anestesi') {
            document.getElementById('id_dokter_anestesi').value   = item.id_dokter   ?? '';
            document.getElementById('nama_dokter_anestesi').value = item.nama_dokter ?? '';
        }
        currentDokterSlot = null;
    }

    // ── Petugas (slot-based: sn_cn / anestesi / ok) ──────────────────────────
    let currentPetugasSlot = null;

    function openPetugasSnCn()    { currentPetugasSlot = 'sn_cn';    open_modalPetugas(); }
    function openPetugasAnestesi(){ currentPetugasSlot = 'anestesi'; open_modalPetugas(); }
    function openPetugasOk()      { currentPetugasSlot = 'ok';       open_modalPetugas(); }

    function autofillPetugas(item) {
        if (currentPetugasSlot === 'sn_cn') {
            document.getElementById('id_sn_cn').value   = item.id_petugas ?? '';
            document.getElementById('nama_sn_cn').value = item.nama       ?? '';
        } else if (currentPetugasSlot === 'anestesi') {
            document.getElementById('id_petugas_anestesi').value   = item.id_petugas ?? '';
            document.getElementById('nama_petugas_anestesi').value = item.nama       ?? '';
        } else if (currentPetugasSlot === 'ok') {
            document.getElementById('id_petugas_ok').value   = item.id_petugas ?? '';
            document.getElementById('nama_petugas_ok').value = item.nama       ?? '';
        }
        currentPetugasSlot = null;
    }

    // ── Validasi ─────────────────────────────────────────────────────────────
    function validateForm() {
        const validations = [
            { id: 'id_sn_cn', msg: 'Silakan pilih SN/CN terlebih dahulu.' },
            { id: 'id_dokter_bedah', msg: 'Silakan pilih dokter bedah terlebih dahulu.' },
            { id: 'id_dokter_anestesi', msg: 'Silakan pilih dokter anestesi terlebih dahulu.' },
            { id: 'id_petugas_anestesi', msg: 'Silakan pilih petugas anestesi terlebih dahulu.' },
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
