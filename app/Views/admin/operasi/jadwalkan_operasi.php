<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<?= $this->include('components/modal/modaldokter') ?>
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

$isCito = filter_var($baris['is_cito'] ?? false, FILTER_VALIDATE_BOOLEAN);
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

            <!-- ── Tim Dokter Tambahan ──────────────────────────────────────── -->
            <div class="mb-6 border-t border-gray-200 dark:border-gray-700 pt-5">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-sm font-semibold text-gray-700 dark:text-gray-300">Tim Dokter Tambahan</p>
                    <button type="button" onclick="bukaDokterTim()" class="<?= $addBtnClass ?>">
                        <?= $plusIcon ?> Tambah Dokter
                    </button>
                </div>

                <div id="timTableWrap">
                    <?php if (!empty($tim_terpilih)): ?>
                    <table class="w-full text-sm text-gray-700 dark:text-gray-300">
                        <tbody id="timTableBody">
                        <?php foreach ($tim_terpilih as $tim): ?>
                            <tr id="tim-row-<?= $tim['id_dokter'] ?>" class="border-b dark:border-gray-700">
                                <td class="py-2"><?= esc($tim['nama_dokter']) ?></td>
                                <td class="py-2 text-right">
                                    <button type="button" onclick="hapusTim(<?= $tim['id_dokter'] ?>)"
                                            class="text-red-500 hover:underline text-xs">Hapus</button>
                                </td>
                                <input type="hidden" name="tim_dokter[]" value="<?= $tim['id_dokter'] ?>">
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                    <?php else: ?>
                    <p id="emptyTim" class="text-xs text-gray-400 italic dark:text-gray-500">Belum ada tim dokter tambahan</p>
                    <?php endif; ?>
                </div>
                <div id="hiddenTimInputs"></div>
            </div>

            <?= view('components/form/submit_button') ?>
        </form>
    </div>
</div>

<script>
let _dokterTarget = null;
const _timAdded   = {};
<?php foreach ($tim_terpilih ?? [] as $t): ?>
_timAdded[<?= $t['id_dokter'] ?>] = true;
<?php endforeach; ?>

function bukaDokter(target) { _dokterTarget = target; open_modalDokter(); }
function bukaDokterTim()    { _dokterTarget = 'tim';  open_modalDokter(); }

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
        tambahTim(item);
    }
    _dokterTarget = null;
}

function tambahTim(item) {
    const id         = item.id_dokter;
    const idBedah    = document.getElementById('id_dokter_bedah').value;
    const idAnestesi = document.getElementById('id_dokter_anestesi').value;

    if (String(id) === String(idBedah) && idBedah)    { alert('Dokter ini sudah dipilih sebagai Dokter Bedah.');     return; }
    if (String(id) === String(idAnestesi) && idAnestesi) { alert('Dokter ini sudah dipilih sebagai Dokter Anestesi.'); return; }
    if (_timAdded[id]) { alert('Dokter sudah ada di tim.'); return; }
    _timAdded[id] = true;

    document.getElementById('emptyTim')?.remove();

    let tbody = document.getElementById('timTableBody');
    if (!tbody) {
        const tbl = document.createElement('table');
        tbl.className = 'w-full text-sm text-gray-700 dark:text-gray-300';
        tbody = document.createElement('tbody');
        tbody.id = 'timTableBody';
        tbl.appendChild(tbody);
        document.getElementById('timTableWrap').appendChild(tbl);
    }

    const tr = document.createElement('tr');
    tr.id = `tim-row-${id}`;
    tr.className = 'border-b dark:border-gray-700';
    tr.innerHTML = `
        <td class="py-2">${item.nama_dokter ?? ''}</td>
        <td class="py-2 text-right">
            <button type="button" onclick="hapusTim(${id})" class="text-red-500 hover:underline text-xs">Hapus</button>
        </td>`;
    tbody.appendChild(tr);

    const inp = document.createElement('input');
    inp.type  = 'hidden';
    inp.name  = 'tim_dokter[]';
    inp.value = id;
    inp.id    = `tim-inp-${id}`;
    document.getElementById('hiddenTimInputs').appendChild(inp);
}

function hapusTim(id) {
    document.getElementById(`tim-row-${id}`)?.remove();
    document.getElementById(`tim-inp-${id}`)?.remove();
    delete _timAdded[id];

    const tbody = document.getElementById('timTableBody');
    if (tbody && tbody.children.length === 0) {
        tbody.closest('table')?.remove();
        const empty = document.createElement('p');
        empty.id = 'emptyTim';
        empty.className = 'text-xs text-gray-400 italic dark:text-gray-500';
        empty.textContent = 'Belum ada tim dokter tambahan';
        document.getElementById('timTableWrap').appendChild(empty);
    }
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
    const submitButton = document.getElementById('submitButton');
    if (submitButton) { submitButton.disabled = true; submitButton.innerHTML = 'Menyimpan...'; }
    return true;
}

document.addEventListener('DOMContentLoaded', updateBoardLink);
</script>

<?= $this->endSection(); ?>