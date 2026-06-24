<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<?= $this->include('components/modal/modalpetugas') ?>
<?= $this->include('components/modal/modalruangan') ?>

<?php
$baseInput     = 'border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full dark:border-gray-600 dark:text-white';
$inputClass    = "$baseInput cursor-pointer bg-slate-50";
$readonlyClass = "$baseInput bg-gray-100 cursor-not-allowed lg:w-1/4";
$stdClass      = "$baseInput bg-slate-50";

$labelLeft  = 'block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4';
$labelRight = 'block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5';
$btnClass   = 'inline-flex justify-center items-center p-2 text-sm font-medium text-white bg-blue-600 rounded-lg border border-transparent hover:bg-blue-700 focus:outline-none transition-all w-10 h-[38px] flex-shrink-0';
$searchIcon = '<svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>';
?>

<div class="max-w-[85rem] py-6 lg:py-3 px-8 mx-auto">
    <div class="bg-white rounded-xl shadow-sm p-4 sm:p-7 dark:bg-slate-900 border border-gray-100 dark:border-gray-800">
        <?= view('components/form/judul', ['judul' => $judul]) ?>

        <form action="<?= $modul_path . $form_action ?>" id="myForm" onsubmit="return validateForm()" method="post">
            <?= csrf_field() ?>
            <input type="hidden" name="id_jadwal"                value="<?= esc((string) ($baris['id_jadwal']                ?? '')) ?>">
            <input type="hidden" name="id_ruang_asal"            id="id_ruang_asal"            value="<?= esc((string) ($baris['id_ruang_asal']            ?? '')) ?>">
            <input type="hidden" name="id_ruang_selanjutnya"     id="id_ruang_selanjutnya"     value="<?= esc((string) ($baris['id_ruang_selanjutnya']     ?? '')) ?>">
            <input type="hidden" name="id_perawat_menyerahkan"   id="id_perawat_menyerahkan"   value="<?= esc((string) ($baris['id_perawat_menyerahkan']   ?? '')) ?>">
            <input type="hidden" name="id_perawat_menerima"      id="id_perawat_menerima"      value="<?= esc((string) ($baris['id_perawat_menerima']      ?? '')) ?>">

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
                <label class="<?= $labelLeft ?>">Tindakan Operasi</label>
                <input type="text" value="<?= esc($baris['nama_tindakan'] ?? $jadwal['nama_tindakan'] ?? '-') ?>"
                       readonly class="<?= $readonlyClass ?> lg:w-1/4">
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="<?= $labelLeft ?>">Waktu Masuk Asal <span class="text-red-500">*</span></label>
                <input type="datetime-local" name="waktu_masuk_asal"
                       value="<?= esc(substr(str_replace(' ', 'T', $baris['waktu_masuk_asal'] ?? ''), 0, 16)) ?>"
                       max="<?= date('Y-m-d\TH:i') ?>" required class="<?= $inputClass ?> lg:w-1/4">

                <label class="<?= $labelRight ?>">Waktu Pindah <span class="text-red-500">*</span></label>
                <input type="datetime-local" name="waktu_pindah"
                       value="<?= esc(substr(str_replace(' ', 'T', $baris['waktu_pindah'] ?? ''), 0, 16)) ?>"
                       max="<?= date('Y-m-d\TH:i') ?>" required class="<?= $inputClass ?> lg:w-1/4">
            </div>

            <!-- ── Info Transfer ── -->
            <div class="border-t border-gray-200 dark:border-gray-700 my-5 pt-5">
                <p class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4">Informasi Transfer</p>

                <div class="mb-5 sm:block md:flex items-center">
                    <label class="<?= $labelLeft ?>">Indikasi Pindah <span class="text-red-500">*</span></label>
                    <select name="id_indikasi" required class="<?= $inputClass ?> lg:w-1/4">
                        <option value="">-- Pilih Indikasi --</option>
                        <?php foreach ($options['indikasi'] as $item): ?>
                        <option value="<?= esc((string) $item['id_indikasi']) ?>"
                                <?= (int) ($baris['id_indikasi'] ?? 0) === (int) $item['id_indikasi'] ? 'selected' : '' ?>>
                            <?= esc($item['nama_indikasi']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>

                    <label class="<?= $labelRight ?>">Keterangan <span class="text-red-500">*</span></label>
                    <input type="text" name="ket_indikasi"
                           value="<?= esc($baris['ket_indikasi'] ?? '') ?>"
                           required class="<?= $inputClass ?> lg:w-1/4">
                </div>

                <div class="mb-5 sm:block md:flex items-center">
                    <label class="<?= $labelLeft ?>">Ruang Asal <span class="text-red-500">*</span></label>
                    <div class="flex gap-x-2 lg:w-1/4">
                        <input type="text" id="nama_ruang_asal"
                               value="<?= esc($baris['nama_ruang_asal'] ?? '') ?>"
                               readonly required placeholder="Klik cari ruangan..."
                               onclick="openRuanganAsal()"
                               class="<?= $inputClass ?>">
                        <button type="button" onclick="openRuanganAsal()" class="<?= $btnClass ?>">
                            <?= $searchIcon ?>
                        </button>
                    </div>

                    <label class="<?= $labelRight ?>">Ruang Selanjutnya <span class="text-red-500">*</span></label>
                    <div class="flex gap-x-2 lg:w-1/4">
                        <input type="text" id="nama_ruang_selanjutnya"
                               value="<?= esc($baris['nama_ruang_selanjutnya'] ?? '') ?>"
                               readonly required placeholder="Klik cari ruangan..."
                               onclick="openRuanganSelanjutnya()"
                               class="<?= $inputClass ?>">
                        <button type="button" onclick="openRuanganSelanjutnya()" class="<?= $btnClass ?>">
                            <?= $searchIcon ?>
                        </button>
                    </div>
                </div>

                <div class="mb-5 sm:block md:flex items-center">
                    <label class="<?= $labelLeft ?>">Metode Transfer <span class="text-red-500">*</span></label>
                    <select name="id_metode" required class="<?= $inputClass ?> lg:w-1/4">
                        <option value="">-- Pilih Metode --</option>
                        <?php foreach ($options['metode'] as $item): ?>
                        <option value="<?= esc((string) $item['id_metode']) ?>"
                                <?= (int) ($baris['id_metode'] ?? 0) === (int) $item['id_metode'] ? 'selected' : '' ?>>
                            <?= esc($item['nama_metode']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <!-- ── Diagnosa & Tindakan ── -->
            <div class="border-t border-gray-200 dark:border-gray-700 my-5 pt-5">
                <p class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4">Diagnosa & Tindakan</p>

                <div class="mb-5 sm:block md:flex items-center">
                    <label class="<?= $labelLeft ?>">Diagnosa Utama <span class="text-red-500">*</span></label>
                    <input type="text" name="diagnosa_utama"
                           value="<?= esc($baris['diagnosa_utama'] ?? '') ?>"
                           required class="<?= $inputClass ?>">
                </div>

                <div class="mb-5 sm:block md:flex items-center">
                    <label class="<?= $labelLeft ?>">Diagnosa Sekunder <span class="text-red-500">*</span></label>
                    <input type="text" name="diagnosa_sekunder"
                           value="<?= esc($baris['diagnosa_sekunder'] ?? '') ?>"
                           required class="<?= $inputClass ?>">
                </div>

                <div class="mb-5">
                    <label class="block mb-2 text-sm text-gray-900 dark:text-white">
                        Prosedur Dilakukan <span class="text-red-500">*</span>
                    </label>
                    <textarea name="prosedur_dilakukan" rows="3" required
                              class="<?= $stdClass ?>"><?= esc($baris['prosedur_dilakukan'] ?? '') ?></textarea>
                </div>

                <div class="mb-5">
                    <label class="block mb-2 text-sm text-gray-900 dark:text-white">
                        Obat Diberikan <span class="text-red-500">*</span>
                    </label>
                    <textarea name="obat_diberikan" rows="3" required
                              class="<?= $stdClass ?>"><?= esc($baris['obat_diberikan'] ?? '') ?></textarea>
                </div>

                <div class="mb-5">
                    <label class="block mb-2 text-sm text-gray-900 dark:text-white">
                        Pemeriksaan Penunjang <span class="text-red-500">*</span>
                    </label>
                    <textarea name="pemeriksaan_penunjang" rows="3" required
                              class="<?= $stdClass ?>"><?= esc($baris['pemeriksaan_penunjang'] ?? '') ?></textarea>
                </div>
            </div>

            <!-- ── Peralatan Transfer ── -->
            <div class="border-t border-gray-200 dark:border-gray-700 my-5 pt-5">
                <p class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4">Peralatan Transfer <span class="text-red-500">*</span></p>

                <?php
                $peralatanUtama   = array_values(array_filter($options['peralatan'], fn($p) => $p['nama_peralatan'] !== 'Tidak Ada'));
                $peralatanTidakAda = array_values(array_filter($options['peralatan'], fn($p) => $p['nama_peralatan'] === 'Tidak Ada'));
                ?>

                <?php foreach ($peralatanUtama as $idx => $p):
                    $idPeralatan = (int) $p['id_peralatan'];
                    $existing    = $peralatan_map[$idPeralatan] ?? null;
                ?>
                <div class="mb-3 sm:block md:flex items-center">
                    <div class="flex items-center <?= $labelLeft ?>">
                        <input type="checkbox"
                               id="cb_peralatan_<?= $idx ?>"
                               name="peralatan[<?= $idx ?>][selected]"
                               value="1"
                               <?= $existing !== null ? 'checked' : '' ?>
                               onchange="onPeralatanUtamaChange(this)"
                               class="cb-peralatan-utama w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <input type="hidden" name="peralatan[<?= $idx ?>][id_peralatan]" value="<?= $idPeralatan ?>">
                        <label for="cb_peralatan_<?= $idx ?>" class="ml-2 text-sm text-gray-900 dark:text-white cursor-pointer">
                            <?= esc($p['nama_peralatan']) ?>
                        </label>
                    </div>
                    <label class="<?= $labelRight ?>">Keterangan</label>
                    <input type="text" name="peralatan[<?= $idx ?>][keterangan]"
                           value="<?= esc($existing['keterangan'] ?? '') ?>"
                           class="<?= $inputClass ?> lg:w-1/4">
                </div>
                <?php endforeach; ?>

                <?php if (!empty($peralatanTidakAda)):
                    $p            = $peralatanTidakAda[0];
                    $idxTidakAda  = count($peralatanUtama);
                    $idPeralatan  = (int) $p['id_peralatan'];
                    $existing     = $peralatan_map[$idPeralatan] ?? null;
                ?>
                <div class="mt-3 mb-3 sm:block md:flex items-center">
                    <div class="flex items-center <?= $labelLeft ?>">
                        <input type="checkbox"
                               id="cb_tidak_ada"
                               name="peralatan[<?= $idxTidakAda ?>][selected]"
                               value="1"
                               <?= $existing !== null ? 'checked' : '' ?>
                               onchange="onTidakAdaChange(this)"
                               class="w-4 h-4 text-gray-500 border-gray-300 rounded focus:ring-gray-400">
                        <input type="hidden" name="peralatan[<?= $idxTidakAda ?>][id_peralatan]" value="<?= $idPeralatan ?>">
                        <label for="cb_tidak_ada" class="ml-2 text-sm text-gray-500 dark:text-gray-400 cursor-pointer italic">
                            <?= esc($p['nama_peralatan']) ?>
                        </label>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <!-- ── Persetujuan ── -->
            <div class="border-t border-gray-200 dark:border-gray-700 my-5 pt-5">
                <p class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4">Persetujuan</p>

                <div class="mb-5 sm:block md:flex items-center">
                    <label class="<?= $labelLeft ?>">Disetujui <span class="text-red-500">*</span></label>
                    <select name="is_disetujui" required class="<?= $inputClass ?> lg:w-1/4">
                        <option value="1" <?= ($baris['is_disetujui'] ?? '') !== '0' ? 'selected' : '' ?>>Ya</option>
                        <option value="0" <?= ($baris['is_disetujui'] ?? '') === '0' ? 'selected' : '' ?>>Tidak</option>
                    </select>

                    <label class="<?= $labelRight ?>">Nama Pemberi <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_pemberi_persetujuan"
                           value="<?= esc($baris['nama_pemberi_persetujuan'] ?? '') ?>"
                           required class="<?= $inputClass ?> lg:w-1/4">
                </div>

                <div class="mb-5 sm:block md:flex items-center">
                    <label class="<?= $labelLeft ?>">Hubungan Keluarga <span class="text-red-500">*</span></label>
                    <select name="id_hubungan" required class="<?= $inputClass ?> lg:w-1/4">
                        <option value="">-- Pilih Hubungan --</option>
                        <?php foreach ($options['hubungan'] as $item): ?>
                        <option value="<?= esc((string) $item['id_hubungan_keluarga']) ?>"
                                <?= (int) ($baris['id_hubungan'] ?? 0) === (int) $item['id_hubungan_keluarga'] ? 'selected' : '' ?>>
                            <?= esc($item['nama_hubungan']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <!-- ── Kondisi Saat Berangkat ── -->
            <div class="border-t border-gray-200 dark:border-gray-700 my-5 pt-5">
                <p class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4">Kondisi Saat Berangkat</p>

                <div class="mb-5 sm:block md:flex items-center">
                    <label class="<?= $labelLeft ?>">Keadaan Umum <span class="text-red-500">*</span></label>
                    <select name="asal_id_keadaan" required class="<?= $inputClass ?> lg:w-1/4">
                        <option value="">-- Pilih Keadaan --</option>
                        <?php foreach ($options['keadaan_umum'] as $item): ?>
                        <option value="<?= esc((string) $item['id_keadaan_umum']) ?>"
                                <?= (int) ($baris['asal_id_keadaan'] ?? 0) === (int) $item['id_keadaan_umum'] ? 'selected' : '' ?>>
                            <?= esc($item['nama_keadaan']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-5 sm:block md:flex items-center">
                    <label class="<?= $labelLeft ?>">Tekanan Darah <span class="text-red-500">*</span></label>
                    <div class="flex items-center gap-x-2 lg:w-1/4">
                        <input type="number" name="asal_sistolik"
                               value="<?= esc($baris['asal_sistolik'] ?? '') ?>"
                               placeholder="Sistolik" required min="0" max="300"
                               class="<?= $inputClass ?>">
                        <span class="text-gray-500 font-semibold flex-shrink-0">/</span>
                        <input type="number" name="asal_diastolik"
                               value="<?= esc($baris['asal_diastolik'] ?? '') ?>"
                               placeholder="Diastolik" required min="0" max="200"
                               class="<?= $inputClass ?>">
                        <span class="text-sm text-gray-400 whitespace-nowrap">mmHg</span>
                    </div>

                    <label class="<?= $labelRight ?>">Nadi <span class="text-red-500">*</span></label>
                    <div class="flex items-center gap-x-2 lg:w-1/4">
                        <input type="number" name="asal_nadi"
                               value="<?= esc($baris['asal_nadi'] ?? '') ?>"
                               required min="0" max="300" class="<?= $inputClass ?>">
                        <span class="text-sm text-gray-400 whitespace-nowrap">x/mnt</span>
                    </div>
                </div>

                <div class="mb-5 sm:block md:flex items-center">
                    <label class="<?= $labelLeft ?>">Respiratory Rate <span class="text-red-500">*</span></label>
                    <div class="flex items-center gap-x-2 lg:w-1/4">
                        <input type="number" name="asal_respiratory_rate"
                               value="<?= esc($baris['asal_respiratory_rate'] ?? '') ?>"
                               required min="0" max="100" class="<?= $inputClass ?>">
                        <span class="text-sm text-gray-400 whitespace-nowrap">x/mnt</span>
                    </div>

                    <label class="<?= $labelRight ?>">Suhu <span class="text-red-500">*</span></label>
                    <div class="flex items-center gap-x-2 lg:w-1/4">
                        <input type="number" name="asal_suhu"
                               value="<?= esc($baris['asal_suhu'] ?? '') ?>"
                               required step="0.1" min="30" max="45" class="<?= $inputClass ?>">
                        <span class="text-sm text-gray-400 whitespace-nowrap">°C</span>
                    </div>
                </div>

                <div class="mb-5">
                    <label class="block mb-2 text-sm text-gray-900 dark:text-white">
                        Keluhan <span class="text-red-500">*</span>
                    </label>
                    <textarea name="asal_keluhan" rows="2" required
                              class="<?= $stdClass ?>"><?= esc($baris['asal_keluhan'] ?? '') ?></textarea>
                </div>
            </div>

            <!-- ── Kondisi Saat Tiba ── -->
            <div class="border-t border-gray-200 dark:border-gray-700 my-5 pt-5">
                <p class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4">Kondisi Saat Tiba</p>

                <div class="mb-5 sm:block md:flex items-center">
                    <label class="<?= $labelLeft ?>">Keadaan Umum <span class="text-red-500">*</span></label>
                    <select name="tiba_id_keadaan" required class="<?= $inputClass ?> lg:w-1/4">
                        <option value="">-- Pilih Keadaan --</option>
                        <?php foreach ($options['keadaan_umum'] as $item): ?>
                        <option value="<?= esc((string) $item['id_keadaan_umum']) ?>"
                                <?= (int) ($baris['tiba_id_keadaan'] ?? 0) === (int) $item['id_keadaan_umum'] ? 'selected' : '' ?>>
                            <?= esc($item['nama_keadaan']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-5 sm:block md:flex items-center">
                    <label class="<?= $labelLeft ?>">Tekanan Darah <span class="text-red-500">*</span></label>
                    <div class="flex items-center gap-x-2 lg:w-1/4">
                        <input type="number" name="tiba_sistolik"
                               value="<?= esc($baris['tiba_sistolik'] ?? '') ?>"
                               placeholder="Sistolik" required min="0" max="300"
                               class="<?= $inputClass ?>">
                        <span class="text-gray-500 font-semibold flex-shrink-0">/</span>
                        <input type="number" name="tiba_diastolik"
                               value="<?= esc($baris['tiba_diastolik'] ?? '') ?>"
                               placeholder="Diastolik" required min="0" max="200"
                               class="<?= $inputClass ?>">
                        <span class="text-sm text-gray-400 whitespace-nowrap">mmHg</span>
                    </div>

                    <label class="<?= $labelRight ?>">Nadi <span class="text-red-500">*</span></label>
                    <div class="flex items-center gap-x-2 lg:w-1/4">
                        <input type="number" name="tiba_nadi"
                               value="<?= esc($baris['tiba_nadi'] ?? '') ?>"
                               required min="0" max="300" class="<?= $inputClass ?>">
                        <span class="text-sm text-gray-400 whitespace-nowrap">x/mnt</span>
                    </div>
                </div>

                <div class="mb-5 sm:block md:flex items-center">
                    <label class="<?= $labelLeft ?>">Respiratory Rate <span class="text-red-500">*</span></label>
                    <div class="flex items-center gap-x-2 lg:w-1/4">
                        <input type="number" name="tiba_respiratory_rate"
                               value="<?= esc($baris['tiba_respiratory_rate'] ?? '') ?>"
                               required min="0" max="100" class="<?= $inputClass ?>">
                        <span class="text-sm text-gray-400 whitespace-nowrap">x/mnt</span>
                    </div>

                    <label class="<?= $labelRight ?>">Suhu <span class="text-red-500">*</span></label>
                    <div class="flex items-center gap-x-2 lg:w-1/4">
                        <input type="number" name="tiba_suhu"
                               value="<?= esc($baris['tiba_suhu'] ?? '') ?>"
                               required step="0.1" min="30" max="45" class="<?= $inputClass ?>">
                        <span class="text-sm text-gray-400 whitespace-nowrap">°C</span>
                    </div>
                </div>

                <div class="mb-5">
                    <label class="block mb-2 text-sm text-gray-900 dark:text-white">
                        Keluhan <span class="text-red-500">*</span>
                    </label>
                    <textarea name="tiba_keluhan" rows="2" required
                              class="<?= $stdClass ?>"><?= esc($baris['tiba_keluhan'] ?? '') ?></textarea>
                </div>
            </div>

            <!-- ── Perawat ── -->
            <div class="border-t border-gray-200 dark:border-gray-700 my-5 pt-5">
                <p class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4">Perawat</p>

                <div class="mb-5 sm:block md:flex items-center">
                    <label class="<?= $labelLeft ?>">Perawat Menyerahkan <span class="text-red-500">*</span></label>
                    <div class="flex gap-x-2 lg:w-1/4">
                        <input type="text" id="nama_perawat_menyerahkan"
                               value="<?= esc($baris['nama_perawat_menyerahkan'] ?? '') ?>"
                               readonly required placeholder="Klik cari perawat..."
                               onclick="openPerawatMenyerahkan()"
                               class="<?= $inputClass ?>">
                        <button type="button" onclick="openPerawatMenyerahkan()" class="<?= $btnClass ?>">
                            <?= $searchIcon ?>
                        </button>
                    </div>

                    <label class="<?= $labelRight ?>">Perawat Menerima <span class="text-red-500">*</span></label>
                    <div class="flex gap-x-2 lg:w-1/4">
                        <input type="text" id="nama_perawat_menerima"
                               value="<?= esc($baris['nama_perawat_menerima'] ?? '') ?>"
                               readonly required placeholder="Klik cari perawat..."
                               onclick="openPerawatMenerima()"
                               class="<?= $inputClass ?>">
                        <button type="button" onclick="openPerawatMenerima()" class="<?= $btnClass ?>">
                            <?= $searchIcon ?>
                        </button>
                    </div>
                </div>
            </div>

            <?= view('components/form/submit_button') ?>
        </form>
    </div>
</div>

<script>
    // ── Ruangan (slot-based) ───────────────────────────────────────────────────
    let currentRuanganSlot = null;

    function openRuanganAsal()        { currentRuanganSlot = 'asal';        open_modalRuangan(); }
    function openRuanganSelanjutnya() { currentRuanganSlot = 'selanjutnya'; open_modalRuangan(); }

    function autofillRuangan(item) {
        if (currentRuanganSlot === 'asal') {
            document.getElementById('id_ruang_asal').value    = item.id_ruangan   ?? '';
            document.getElementById('nama_ruang_asal').value  = item.nama_ruangan ?? '';
        } else if (currentRuanganSlot === 'selanjutnya') {
            document.getElementById('id_ruang_selanjutnya').value   = item.id_ruangan   ?? '';
            document.getElementById('nama_ruang_selanjutnya').value = item.nama_ruangan ?? '';
        }
        currentRuanganSlot = null;
    }

    // ── Perawat (slot-based) ───────────────────────────────────────────────────
    let currentPetugasSlot = null;

    function openPerawatMenyerahkan() { currentPetugasSlot = 'menyerahkan'; open_modalPetugas(); }
    function openPerawatMenerima()    { currentPetugasSlot = 'menerima';    open_modalPetugas(); }

    function autofillPetugas(item) {
        if (currentPetugasSlot === 'menyerahkan') {
            document.getElementById('id_perawat_menyerahkan').value    = item.id_petugas ?? '';
            document.getElementById('nama_perawat_menyerahkan').value  = item.nama       ?? '';
        } else if (currentPetugasSlot === 'menerima') {
            document.getElementById('id_perawat_menerima').value    = item.id_petugas ?? '';
            document.getElementById('nama_perawat_menerima').value  = item.nama       ?? '';
        }
        currentPetugasSlot = null;
    }

    // ── Peralatan mutual exclusive ─────────────────────────────────────────────
    function onPeralatanUtamaChange(cb) {
        if (cb.checked) {
            const tidakAda = document.getElementById('cb_tidak_ada');
            if (tidakAda) tidakAda.checked = false;
        }
    }

    function onTidakAdaChange(cb) {
        if (cb.checked) {
            document.querySelectorAll('.cb-peralatan-utama').forEach(el => el.checked = false);
        }
    }

    // ── Validate ───────────────────────────────────────────────────────────────
    function validateForm() {
        const anyChecked = document.querySelectorAll('input[name*="[selected]"]:checked').length > 0;
        if (!anyChecked) {
            alert('Silakan pilih minimal satu peralatan transfer.');
            return false;
        }
        
        const reqs = [
            { id: 'id_ruang_asal', msg: 'Silakan pilih ruang asal terlebih dahulu.' },
            { id: 'id_ruang_selanjutnya', msg: 'Silakan pilih ruang selanjutnya terlebih dahulu.' },
            { id: 'id_perawat_menyerahkan', msg: 'Silakan pilih perawat menyerahkan terlebih dahulu.' },
            { id: 'id_perawat_menerima', msg: 'Silakan pilih perawat menerima terlebih dahulu.' }
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
