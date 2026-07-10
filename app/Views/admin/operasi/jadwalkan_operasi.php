<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<?= $this->include('components/modal/modaldokter') ?>
<?= $this->include('components/modal/modalpetugas') ?>
<?= $this->include('components/modal/modalruangan') ?>

<?php
$baseInput     = 'border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full dark:border-gray-600 dark:text-white';
$inputClass    = "$baseInput cursor-pointer bg-slate-50";
$readonlyClass = "$baseInput bg-gray-100 cursor-not-allowed";
$timeClass     = "$baseInput bg-slate-50";

$labelLeft  = 'block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4';
$labelRight = 'block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5';
$btnClass    = 'inline-flex justify-center items-center p-2 text-sm font-medium text-white bg-blue-600 rounded-lg border border-transparent hover:bg-blue-700 focus:outline-none transition-all w-10 h-[38px] flex-shrink-0 shadow-sm';
$addBtnClass = 'inline-flex items-center gap-x-1.5 py-2 px-3 text-sm font-semibold rounded-lg border border-transparent bg-[#0A2D27] text-[#ACF2E7] hover:bg-[#13594E] transition-all shadow-sm';
$searchIcon  = '<svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>';
$plusIcon    = '<svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>';

$isCito = in_array($baris['is_cito'] ?? false, [true, 1, '1', 't'], true);

$peranSelectClass = "$baseInput bg-slate-50 tim-peran";
?>

<div class="max-w-[85rem] py-6 lg:py-3 px-8 mx-auto">
    <div class="bg-white rounded-xl shadow-sm p-4 sm:p-7 dark:bg-slate-900 border border-gray-100 dark:border-gray-800">
        <?= view('components/form/judul', ['judul' => $judul]) ?>

        <form action="<?= $modul_path . $form_action ?>" id="myForm" onsubmit="return validateForm()" method="post">
            <?= csrf_field() ?>

            <input type="hidden" name="id_ruangan"         id="id_ruangan"         value="<?= esc($baris['id_ruangan']         ?? '') ?>">
            <input type="hidden" name="id_dokter_bedah"    id="id_dokter_bedah"    value="<?= esc($baris['id_dokter_bedah']    ?? '') ?>">
            <input type="hidden" name="id_dokter_anestesi" id="id_dokter_anestesi" value="<?= esc($baris['id_dokter_anestesi'] ?? '') ?>">

            <?php if (!empty($baris['nomor_operasi'])): ?>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="<?= $labelLeft ?>">No. Operasi</label>
                <input type="text" value="<?= esc($baris['nomor_operasi']) ?>" readonly class="<?= $readonlyClass ?> lg:w-1/4 font-mono">
            </div>
            <?php endif; ?>

            <?php
            $fields = [
                ['No. Registrasi',   'nomor_reg',           'Nama Pasien',   'nama_pasien'],
                ['Dokter Peminta',   'nama_dokter_peminta', 'Tanggal Minta', 'tanggal_minta'],
                ['Tindakan Operasi', 'nama_tindakan',       null,            null],
            ];
            foreach ($fields as $f): ?>
                <div class="mb-5 sm:block md:flex items-center">
                    <label class="<?= $labelLeft ?>"><?= $f[0] ?></label>
                    <input type="text" value="<?= esc($baris[$f[1]] ?? '') ?>" readonly class="<?= $readonlyClass ?> lg:w-1/4">
                    <?php if ($f[2]): ?>
                        <label class="<?= $labelRight ?>"><?= $f[2] ?></label>
                        <input type="text" value="<?= esc($baris[$f[3]] ?? '') ?>" readonly class="<?= $readonlyClass ?> lg:w-1/4">
                    <?php elseif ($isCito): ?>
                        <label class="<?= $labelRight ?>">CITO</label>
                        <div class="lg:w-1/4">
                            <span class="inline-flex items-center py-1 px-3 text-sm font-semibold rounded-lg bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">CITO</span>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>

            <!-- Ruangan | Dokter Bedah -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="<?= $labelLeft ?>">Ruangan <span class="text-red-500">*</span></label>
                <div class="flex gap-x-2 lg:w-1/4">
                    <input type="text" id="nama_ruangan" value="<?= esc($baris['nama_ruangan'] ?? '') ?>"
                           readonly required placeholder="Klik cari ruangan..."
                           onclick="open_modalRuangan()" class="<?= $inputClass ?>">
                    <button type="button" onclick="open_modalRuangan()" class="<?= $btnClass ?>"><?= $searchIcon ?></button>
                </div>
                <label class="<?= $labelRight ?>">Dokter Bedah <span class="text-red-500">*</span></label>
                <div class="flex gap-x-2 lg:w-1/4">
                    <input type="text" id="nama_dokter_bedah" value="<?= esc($baris['nama_dokter_bedah'] ?? '') ?>"
                           readonly required placeholder="Klik cari dokter bedah..."
                           onclick="bukaDokter('bedah')" class="<?= $inputClass ?>">
                    <button type="button" onclick="bukaDokter('bedah')" class="<?= $btnClass ?>"><?= $searchIcon ?></button>
                </div>
            </div>

            <!-- Dokter Anestesi | Tanggal -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="<?= $labelLeft ?>">Dokter Anestesi <span class="text-red-500">*</span></label>
                <div class="flex gap-x-2 lg:w-1/4">
                    <input type="text" id="nama_dokter_anestesi" value="<?= esc($baris['nama_dokter_anestesi'] ?? '') ?>"
                           readonly required placeholder="Klik cari dokter anestesi..."
                           onclick="bukaDokter('anestesi')" class="<?= $inputClass ?>">
                    <button type="button" onclick="bukaDokter('anestesi')" class="<?= $btnClass ?>"><?= $searchIcon ?></button>
                </div>
                <label class="<?= $labelRight ?>">Tanggal Operasi <span class="text-red-500">*</span></label>
                <input type="date" name="tanggal" id="tanggal"
                       value="<?= esc($baris['tanggal'] ?? '') ?>"
                       required class="<?= $timeClass ?> lg:w-1/4"
                       onchange="updateBoardLink()">
            </div>

            <!-- Waktu Mulai | Waktu Selesai -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="<?= $labelLeft ?>">Waktu Mulai <span class="text-red-500">*</span></label>
                <input type="time" name="waktu_mulai" id="waktu_mulai"
                       value="<?= esc(substr($baris['waktu_mulai'] ?? '', 0, 5)) ?>"
                       required class="<?= $timeClass ?> lg:w-1/4">
                <label class="<?= $labelRight ?>">Waktu Selesai</label>
                <input type="time" name="waktu_selesai" id="waktu_selesai"
                       value="<?= esc(substr($baris['waktu_selesai'] ?? '', 0, 5)) ?>"
                       class="<?= $timeClass ?> lg:w-1/4">
            </div>

            <!-- Lihat Papan Jadwal -->
            <div class="mb-6 flex justify-end">
                <a id="boardLink" href="<?= site_url('operasi/papan-jadwal-operasi/data') ?>" target="_blank"
                   class="inline-flex items-center gap-1.5 text-sm text-blue-600 hover:underline dark:text-blue-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Lihat Papan Jadwal
                </a>
            </div>

            <!-- ── Tim Medis Tambahan ──────────────────────────────────────── -->
            <div class="mb-6 border-t border-gray-200 dark:border-gray-700 pt-5">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-sm font-semibold text-gray-700 dark:text-gray-300">Tim Medis Tambahan</p>
                    <div class="flex gap-x-2">
                        <button type="button" onclick="bukaDokterTim()" class="<?= $addBtnClass ?>">
                            <?= $plusIcon ?> Tambah Dokter
                        </button>
                        <button type="button" onclick="bukaPetugasTim()" class="<?= $addBtnClass ?>">
                            <?= $plusIcon ?> Tambah Petugas
                        </button>
                    </div>
                </div>

                <div class="border rounded-xl overflow-hidden dark:border-gray-700">
                    <table id="timTable" class="w-full text-sm text-gray-700 dark:text-gray-300 table-fixed">
                        <colgroup>
                            <col class="w-1/2">
                            <col class="w-2/5">
                            <col>
                        </colgroup>
                        <thead style="background-color:#E6F2EF;" class="text-gray-800 font-semibold text-base">
                            <tr>
                                <th class="p-4 border text-center text-base">Nama</th>
                                <th class="p-4 border text-center text-base">Peran</th>
                                <th class="p-4 border text-center text-base">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="timTableBody">
                        <?php if (empty($tim_terpilih)): ?>
                            <tr id="emptyTim">
                                <td colspan="3" class="text-center py-6 text-gray-400 italic dark:text-gray-500">Belum ada tim medis tambahan</td>
                            </tr>
                        <?php endif; ?>
                        <?php foreach ($tim_terpilih ?? [] as $tim):
                            $jenis = !empty($tim['id_dokter']) ? 'dokter' : 'petugas';
                            $id    = $jenis === 'dokter' ? (int) $tim['id_dokter'] : (int) $tim['id_petugas'];
                            $key   = ($jenis === 'dokter' ? 'd-' : 'p-') . $id;
                        ?>
                            <tr id="tim-row-<?= $key ?>">
                                <td class="p-2 pl-4 border dark:border-gray-700"><?= esc($tim['nama']) ?></td>
                                <td class="p-2 border dark:border-gray-700">
                                    <select name="tim[<?= $key ?>][id_peran]" class="<?= $peranSelectClass ?>" onchange="refreshPeranOptions()">
                                        <option value="">— Pilih peran —</option>
                                        <?php foreach ($peran_tim ?? [] as $val => $label): ?>
                                        <option value="<?= $val ?>" <?= (int) ($tim['id_peran'] ?? 0) === $val ? 'selected' : '' ?>><?= esc($label) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <input type="hidden" name="tim[<?= $key ?>][id_<?= $jenis ?>]" value="<?= $id ?>">
                                </td>
                                <td class="p-2 border text-center dark:border-gray-700">
                                    <button type="button" onclick="hapusTim('<?= $key ?>')"
                                            class="text-red-500 hover:underline text-xs">Hapus</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <?= view('components/form/submit_button') ?>
        </form>
    </div>
</div>

<script>
let _dokterTarget = null;
const _timAdded   = {};
<?php foreach ($tim_terpilih ?? [] as $t):
    $k = !empty($t['id_dokter']) ? 'd-' . (int) $t['id_dokter'] : 'p-' . (int) $t['id_petugas'];
?>
_timAdded['<?= $k ?>'] = true;
<?php endforeach; ?>

const PERAN_TIM          = <?= json_encode($peran_tim ?? []) ?>;
const PERAN_SELECT_CLASS = '<?= $peranSelectClass ?>';

function bukaDokter(target) { _dokterTarget = target; open_modalDokter(); }
function bukaDokterTim()    { _dokterTarget = 'tim';  open_modalDokter(); }
function bukaPetugasTim()   { open_modalPetugas(); }

function autofillRuangan(item) {
    document.getElementById('nama_ruangan').value = item.nama_ruangan ?? '';
    document.getElementById('id_ruangan').value   = item.id_ruangan   ?? '';
}

function autofillFields(item) {
    if (_dokterTarget === 'bedah') {
        document.getElementById('nama_dokter_bedah').value = item.nama_dokter ?? '';
        document.getElementById('id_dokter_bedah').value   = item.id_dokter   ?? '';
    } else if (_dokterTarget === 'anestesi') {
        document.getElementById('nama_dokter_anestesi').value = item.nama_dokter ?? '';
        document.getElementById('id_dokter_anestesi').value   = item.id_dokter   ?? '';
    } else if (_dokterTarget === 'tim') {
        tambahTim('dokter', item.id_dokter, item.nama_dokter ?? '');
    }
    _dokterTarget = null;
}

function autofillPetugas(item) {
    tambahTim('petugas', item.id_petugas, item.nama ?? '');
}

function tambahTim(jenis, id, nama) {
    const key = (jenis === 'dokter' ? 'd-' : 'p-') + id;

    if (jenis === 'dokter') {
        const idBedah    = document.getElementById('id_dokter_bedah').value;
        const idAnestesi = document.getElementById('id_dokter_anestesi').value;
        if (String(id) === String(idBedah) && idBedah)       { alert('Dokter ini sudah dipilih sebagai Dokter Bedah.');     return; }
        if (String(id) === String(idAnestesi) && idAnestesi) { alert('Dokter ini sudah dipilih sebagai Dokter Anestesi.'); return; }
    }
    if (_timAdded[key]) { alert('Orang ini sudah ada di tim.'); return; }
    _timAdded[key] = true;

    document.getElementById('emptyTim')?.remove();

    let options = '<option value="">— Pilih peran —</option>';
    for (const [val, label] of Object.entries(PERAN_TIM)) {
        options += `<option value="${val}">${label}</option>`;
    }

    const tr = document.createElement('tr');
    tr.id = `tim-row-${key}`;
    tr.innerHTML = `
        <td class="p-2 pl-4 border dark:border-gray-700">${nama}</td>
        <td class="p-2 border dark:border-gray-700">
            <select name="tim[${key}][id_peran]" class="${PERAN_SELECT_CLASS}" onchange="refreshPeranOptions()">${options}</select>
            <input type="hidden" name="tim[${key}][id_${jenis}]" value="${id}">
        </td>
        <td class="p-2 border text-center dark:border-gray-700">
            <button type="button" onclick="hapusTim('${key}')" class="text-red-500 hover:underline text-xs">Hapus</button>
        </td>`;
    document.getElementById('timTableBody').appendChild(tr);
    refreshPeranOptions();
}

function hapusTim(key) {
    document.getElementById(`tim-row-${key}`)?.remove();
    delete _timAdded[key];

    const tbody = document.getElementById('timTableBody');
    if (tbody.children.length === 0) {
        tbody.innerHTML = '<tr id="emptyTim"><td colspan="3" class="text-center py-6 text-gray-400 italic dark:text-gray-500">Belum ada tim medis tambahan</td></tr>';
    }
    refreshPeranOptions();
}

function refreshPeranOptions() {
    const selects = document.querySelectorAll('.tim-peran');
    const taken   = new Set();
    selects.forEach(s => { if (s.value) taken.add(s.value); });
    selects.forEach(s => {
        s.querySelectorAll('option').forEach(o => {
            o.disabled = o.value !== '' && o.value !== s.value && taken.has(o.value);
        });
    });
}

function updateBoardLink() {
    const tanggal = document.getElementById('tanggal').value;
    const base    = '<?= site_url('operasi/papan-jadwal-operasi/data') ?>';
    document.getElementById('boardLink').href = tanggal ? `${base}?tanggal=${tanggal}` : base;
}

function validateForm() {
    if (!document.getElementById('id_ruangan').value) {
        alert('Silakan pilih ruangan terlebih dahulu.'); return false;
    }
    if (!document.getElementById('id_dokter_bedah').value) {
        alert('Silakan pilih dokter bedah terlebih dahulu.'); return false;
    }
    if (!document.getElementById('id_dokter_anestesi').value) {
        alert('Silakan pilih dokter anestesi terlebih dahulu.'); return false;
    }
    for (const s of document.querySelectorAll('.tim-peran')) {
        if (!s.value) {
            alert('Silakan pilih peran untuk setiap anggota tim medis.');
            s.focus(); return false;
        }
    }
    const submitButton = document.getElementById('submitButton');
    if (submitButton) { submitButton.disabled = true; submitButton.innerHTML = 'Menyimpan...'; }
    return true;
}

document.addEventListener('DOMContentLoaded', () => {
    updateBoardLink();
    refreshPeranOptions();
});
</script>

<?= $this->endSection(); ?>