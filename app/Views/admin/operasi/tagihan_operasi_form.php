<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<?= $this->include('components/modal/modaldokter') ?>
<?= $this->include('components/modal/modalpetugas') ?>
<?= $this->include('components/modal/modalbarangmedis') ?>
<?= $this->include('components/modal/modaltindakanoperasi') ?>

<?php
$base          = 'border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full dark:border-gray-600 dark:text-white';
$inputClass    = "$base cursor-pointer bg-slate-50";
$readonlyClass = "$base bg-gray-100 cursor-not-allowed";
$stdClass      = "$base bg-slate-50";
$btnClass      = 'inline-flex justify-center items-center p-2 text-sm font-medium text-white bg-blue-600 rounded-lg border border-transparent hover:bg-blue-700 focus:outline-none transition-all w-10 h-[38px] flex-shrink-0 shadow-sm';
$labelLeft     = 'block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4';
$labelRight    = 'block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5';
$sectionDiv    = 'border-t border-gray-200 dark:border-gray-700 my-5 pt-5 pb-5';
$addBtnClass   = 'inline-flex items-center gap-x-1.5 py-2 px-3 text-sm font-semibold rounded-lg border border-transparent bg-[#0A2D27] text-[#ACF2E7] hover:bg-[#13594E] transition-all shadow-sm';
$plusIcon      = '<svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>';
$sectionH      = 'text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4';
$subHead       = 'text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-3';

$searchIcon = '<svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>';
$xIcon      = '<svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>';

$dokterFields = [
    ['id_operator_1',      'Operator 1'],
    ['id_operator_2',      'Operator 2'],
    ['id_operator_3',      'Operator 3'],
    ['id_dokter_anestesi', 'Dokter Anestesi'],
    ['id_dokter_anak',     'Dokter Anak'],
    ['id_dokter_pj_anak',  'Dokter Pj. Anak'],
    ['id_dokter_umum',     'Dokter Umum'],
    ['id_ast_operator_1',  'Asisten Operator 1'],
    ['id_ast_operator_2',  'Asisten Operator 2'],
    ['id_ast_operator_3',  'Asisten Operator 3'],
];
$petugasFields = [
    ['id_bidan_1',        'Bidan 1'],
    ['id_bidan_2',        'Bidan 2'],
    ['id_bidan_3',        'Bidan 3'],
    ['id_perawat_luar',   'Perawat Luar'],
    ['id_instrumen',      'Instrumen'],
    ['id_ast_anestesi_1', 'Asisten Anestesi 1'],
    ['id_ast_anestesi_2', 'Asisten Anestesi 2'],
    ['id_perawat_resus',  'Perawat Resusitasi'],
    ['id_onloop_1',       'Onloop 1'],
    ['id_onloop_2',       'Onloop 2'],
    ['id_onloop_3',       'Onloop 3'],
    ['id_onloop_4',       'Onloop 4'],
    ['id_onloop_5',       'Onloop 5'],
];

$infoGrid = [
    'No. Registrasi'   => $baris['nomor_reg']        ?? '-',
    'Nama Pasien'      => $baris['nama_pasien']       ?? '-',
    'Tindakan Operasi' => $baris['nama_tindakan']     ?? '-',
    'Tanggal Operasi'  => $baris['tanggal']           ?? '-',
    'Dokter Bedah'     => $baris['nama_dokter_bedah'] ?? '-',
];

?>

<div class="max-w-[85rem] py-6 lg:py-3 px-8 mx-auto">
  <div class="bg-white rounded-xl shadow-sm p-4 sm:p-7 dark:bg-slate-900 border border-gray-100 dark:border-gray-800">
    <?= view('components/form/judul', ['judul' => $judul]) ?>

    <form action="<?= $modul_path . $form_action ?>" id="myForm" onsubmit="return validateForm()" method="post">
      <?= csrf_field() ?>

      <!-- Hidden: id_jadwal + semua FK tim medis -->
      <input type="hidden" name="id_jadwal" id="id_jadwal" value="<?= esc((string) ($baris['id_jadwal'] ?? '')) ?>">
      <?php foreach ([...$dokterFields, ...$petugasFields] as [$col, $_]): ?>
      <input type="hidden" name="<?= $col ?>" id="<?= $col ?>" value="<?= esc((string) ($baris[$col] ?? '')) ?>">
      <?php endforeach; ?>

      <!-- ── Info Operasi (readonly) ────────────────────────────────────── -->
      <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-x-6 gap-y-4 mb-5 pb-5 border-b border-gray-200 dark:border-gray-700">
        <?php foreach ($infoGrid as $label => $value): ?>
        <div>
          <p class="text-xs text-gray-500 dark:text-gray-400"><?= $label ?></p>
          <p class="text-sm font-medium text-gray-900 dark:text-white mt-0.5"><?= esc($value) ?></p>
        </div>
        <?php endforeach; ?>
      </div>

      <!-- ── Tindakan yang Dilakukan ──────────────────────────────────── -->
      <div class="<?= $sectionDiv ?>">
        <p class="<?= $sectionH ?>">Tindakan yang Dilakukan</p>
        <p class="text-xs text-gray-500 dark:text-gray-400 mb-3">
          Termasuk tindakan utama dan tindakan tambahan yang dilakukan selama operasi berlangsung.
        </p>

        <div class="flex justify-end mb-3">
          <button type="button" onclick="open_modalTindakanOperasi()" class="<?= $addBtnClass ?>">
            <?= $plusIcon ?>
            Tambah Tindakan
          </button>
        </div>

        <div class="border rounded-xl overflow-hidden dark:border-gray-700 mb-8">
          <table class="w-full text-sm text-gray-700 dark:text-gray-300 table-fixed">
            <colgroup>
              <col class="w-1/6">
              <col class="w-3/6">
              <col class="w-1/6">
              <col class="w-1/6">
            </colgroup>
            <thead style="background-color:#E6F2EF;" class="text-gray-800 font-semibold text-base">
              <tr>
                <th class="p-4 border text-center text-base">Kode</th>
                <th class="p-4 border text-center text-base">Nama Tindakan</th>
                <th class="p-4 border text-center text-base">Tarif</th>
                <th class="p-4 border text-center text-base">Aksi</th>
              </tr>
            </thead>
            <tbody id="tindakanTableBody">
              <tr id="emptyTindakanRow">
                <td colspan="4" class="text-center py-6 text-gray-400 italic dark:text-gray-500">
                  Belum ada tindakan ditambahkan
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <div id="hiddenTindakanInputs"></div>
      </div>
      
      <!-- ── Data Utama ─────────────────────────────────────────────────── -->
      <div class="<?= $sectionDiv ?>">
        <p class="<?= $sectionH ?>">Data Utama</p>

        <div class="mb-5 sm:block md:flex items-center">
          <label class="<?= $labelLeft ?>">Kategori Operasi <span class="text-red-500">*</span></label>
          <select name="id_kategori" required class="<?= $inputClass ?> lg:w-1/4">
            <option value="">-- Pilih Kategori --</option>
            <?php foreach ($kategori as $k): ?>
            <option value="<?= esc((string) $k['id_kategori']) ?>"
                    <?= (int) ($baris['id_kategori'] ?? 0) === (int) $k['id_kategori'] ? 'selected' : '' ?>>
              <?= esc($k['nama_kategori']) ?>
            </option>
            <?php endforeach; ?>
          </select>
          <label class="<?= $labelRight ?>">Jenis Anestesi <span class="text-red-500">*</span></label>
          <input type="text" name="jenis_anestesi"
                 value="<?= esc($baris['jenis_anestesi'] ?? '') ?>"
                 required placeholder="Contoh: Spinal, General Anestesi"
                 class="<?= $inputClass ?> lg:w-1/4">
        </div>

        <div class="mb-5 sm:block md:flex items-center">
          <label class="<?= $labelLeft ?>">Tanggal Mulai</label>
          <input type="datetime-local" name="tanggal_mulai"
                 value="<?= esc(substr(str_replace(' ', 'T', $baris['tanggal_mulai'] ?? ''), 0, 16)) ?>"
                 class="<?= $inputClass ?> lg:w-1/4">
          <label class="<?= $labelRight ?>">Tanggal Selesai</label>
          <input type="datetime-local" name="tanggal_selesai"
                 value="<?= esc(substr(str_replace(' ', 'T', $baris['tanggal_selesai'] ?? ''), 0, 16)) ?>"
                 class="<?= $inputClass ?> lg:w-1/4">
        </div>
      </div>

      <!-- ── Diagnosis & Laporan ────────────────────────────────────────── -->
      <div class="<?= $sectionDiv ?>">
        <p class="<?= $sectionH ?>">Diagnosis & Laporan</p>

        <div class="mb-5 sm:block md:flex items-center">
          <label class="<?= $labelLeft ?>">Diagnosis Pre-Op <span class="text-red-500">*</span></label>
          <input type="text" name="diagnosis_pre"
                 value="<?= esc($baris['diagnosis_pre'] ?? '') ?>"
                 required class="<?= $inputClass ?> lg:w-1/4">
          <label class="<?= $labelRight ?>">Diagnosis Post-Op <span class="text-red-500">*</span></label>
          <input type="text" name="diagnosis_post"
                 value="<?= esc($baris['diagnosis_post'] ?? '') ?>"
                 required class="<?= $inputClass ?> lg:w-1/4">
        </div>

        <div class="mb-5 sm:block md:flex items-center">
          <label class="<?= $labelLeft ?>">Jaringan <span class="text-red-500">*</span></label>
          <input type="text" name="jaringan"
                 value="<?= esc($baris['jaringan'] ?? '') ?>"
                 required class="<?= $inputClass ?> lg:w-1/4">
        </div>

        <div class="mb-5">
          <label class="block mb-2 text-sm text-gray-900 dark:text-white">
            Laporan Operasi <span class="text-red-500">*</span>
          </label>
          <textarea name="laporan" rows="4" required
                    class="<?= $stdClass ?>"><?= esc($baris['laporan'] ?? '') ?></textarea>
        </div>

        <div class="flex items-center gap-x-3">
          <input type="checkbox" name="is_pa" id="is_pa" value="1"
                 <?= filter_var($baris['is_pa'] ?? false, FILTER_VALIDATE_BOOLEAN) ? 'checked' : '' ?>
                 class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
          <label for="is_pa" class="text-sm text-gray-900 dark:text-white cursor-pointer">
            Dikirim Pemeriksaan Patologi Anatomi (PA)
          </label>
        </div>
      </div>

      <!-- ── Tim Medis ──────────────────────────────────────────────────── -->
      <div class="<?= $sectionDiv ?>">
        <p class="<?= $sectionH ?>">Tim Medis</p>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
          <?php foreach ($dokterFields as [$col, $label]):
            $slot      = substr($col, 3);
            $nameField = 'nama_' . $slot;
          ?>
          <div>
            <label class="block mb-1 text-sm text-gray-700 dark:text-gray-300"><?= $label ?></label>
            <div class="flex gap-x-2">
              <input type="text" id="<?= $nameField ?>"
                     value="<?= esc($baris[$nameField] ?? '') ?>"
                     readonly placeholder="Klik cari dokter..."
                     onclick="openDokter('<?= $slot ?>')"
                     class="<?= $inputClass ?>">
              <button type="button" onclick="openDokter('<?= $slot ?>')" class="<?= $btnClass ?>">
                <?= $searchIcon ?>
              </button>
            </div>
          </div>
          <?php endforeach; ?>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <?php foreach ($petugasFields as [$col, $label]):
            $slot      = substr($col, 3);
            $nameField = 'nama_' . $slot;
          ?>
          <div>
            <label class="block mb-1 text-sm text-gray-700 dark:text-gray-300"><?= $label ?></label>
            <div class="flex gap-x-2">
              <input type="text" id="<?= $nameField ?>"
                     value="<?= esc($baris[$nameField] ?? '') ?>"
                     readonly placeholder="Klik cari petugas..."
                     onclick="openPetugas('<?= $slot ?>')"
                     class="<?= $inputClass ?>">
              <button type="button" onclick="openPetugas('<?= $slot ?>')" class="<?= $btnClass ?>">
                <?= $searchIcon ?>
              </button>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- ── Obat & BHP ────────────────────────────────────────────────── -->
      <div class="<?= $sectionDiv ?>">
        <p class="<?= $sectionH ?>">Obat & BHP</p>

        <div class="flex justify-end mb-3">
          <button type="button" onclick="open_modalBarangMedis()" class="<?= $addBtnClass ?>">
            <?= $plusIcon ?>
            Tambah Obat/BHP
          </button>
        </div>

        <div class="border rounded-xl overflow-hidden dark:border-gray-700 mb-8">
          <table class="w-full text-sm text-gray-700 dark:text-gray-300 table-fixed">
            <colgroup>
              <col class="w-1/6">
              <col class="w-2/6">
              <col class="w-1/6">
              <col class="w-1/6">
              <col class="w-1/6">
            </colgroup>
            <thead style="background-color:#E6F2EF;" class="text-gray-800 font-semibold text-base">
              <tr>
                <th class="p-4 border text-center text-base">Kode Barang</th>
                <th class="p-4 border text-center text-base">Nama Barang</th>
                <th class="p-4 border text-center text-base">Jumlah Pakai</th>
                <th class="p-4 border text-center text-base">Subtotal</th>
                <th class="p-4 border text-center text-base">Aksi</th>
              </tr>
            </thead>
            <tbody id="obatTableBody">
              <tr id="emptyObatRow">
                <td colspan="5" class="text-center py-6 text-gray-400 italic dark:text-gray-500">Belum ada obat/BHP dipilih</td>
              </tr>
            </tbody>
          </table>
        </div>
        <div id="hiddenObatInputs"></div>
      </div>

      <!-- ── Ringkasan Biaya ────────────────────────────────────────────── -->
      <div class="<?= $sectionDiv ?>">
        <p class="<?= $sectionH ?>">Ringkasan Biaya</p>

        <?php
        $timMedis = array_filter([
            'Operator 1'         => $baris['nama_operator_1']      ?? '',
            'Operator 2'         => $baris['nama_operator_2']      ?? '',
            'Operator 3'         => $baris['nama_operator_3']      ?? '',
            'Dokter Anestesi'    => $baris['nama_dokter_anestesi'] ?? '',
            'Dokter Anak'        => $baris['nama_dokter_anak']     ?? '',
            'Ast. Operator 1'    => $baris['nama_ast_operator_1']  ?? '',
            'Ast. Operator 2'    => $baris['nama_ast_operator_2']  ?? '',
            'Ast. Operator 3'    => $baris['nama_ast_operator_3']  ?? '',
        ]);
        ?>
        <?php if ($timMedis): ?>
        <div class="mb-4 p-3 bg-gray-50 dark:bg-slate-800 rounded-lg">
          <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-2">Tim Medis</p>
          <div class="grid grid-cols-2 sm:grid-cols-3 gap-x-6 gap-y-1 text-sm">
            <?php foreach ($timMedis as $peran => $nama): ?>
            <div class="flex gap-x-2">
              <span class="text-gray-400 dark:text-gray-500 flex-shrink-0"><?= esc($peran) ?>:</span>
              <span class="text-gray-800 dark:text-gray-200 font-medium"><?= esc($nama) ?></span>
            </div>
            <?php endforeach; ?>
          </div>
        </div>
        <?php endif; ?>

        <div class="flex flex-col gap-y-2 text-sm max-w-sm ml-auto">
          <div class="flex justify-between text-gray-600 dark:text-gray-400">
            <span>Total Tindakan</span>
            <span id="totalTindakan" class="font-medium text-gray-900 dark:text-white">Rp 0</span>
          </div>
          <div class="flex justify-between text-gray-600 dark:text-gray-400">
            <span>Total Obat & BHP</span>
            <span id="totalObat" class="font-medium text-gray-900 dark:text-white">Rp 0</span>
          </div>
          <div class="flex justify-between text-base font-bold text-gray-900 dark:text-white border-t border-gray-200 dark:border-gray-700 pt-2 mt-1">
            <span>Grand Total</span>
            <span id="grandTotal">Rp 0</span>
          </div>
        </div>
      </div>

      <?= view('components/form/submit_button') ?>
    </form>
  </div>
</div>

<script>
// ── Dokter ────────────────────────────────────────────────────────────────
let currentDokterSlot = null;

function openDokter(slot) {
    currentDokterSlot = slot;
    open_modalDokter();
}

function autofillFields(item) {
    if (currentDokterSlot === null) return;
    document.getElementById('id_'   + currentDokterSlot).value = item.id_dokter  ?? '';
    document.getElementById('nama_' + currentDokterSlot).value = item.nama_dokter ?? '';
    currentDokterSlot = null;
}

// ── Petugas ───────────────────────────────────────────────────────────────
let currentPetugasSlot = null;

function openPetugas(slot) {
    currentPetugasSlot = slot;
    open_modalPetugas();
}

function autofillPetugas(item) {
    if (currentPetugasSlot === null) return;
    document.getElementById('id_'   + currentPetugasSlot).value = item.id_petugas ?? '';
    document.getElementById('nama_' + currentPetugasSlot).value = item.nama        ?? '';
    currentPetugasSlot = null;
}

// ── Helpers ───────────────────────────────────────────────────────────────
function formatRupiah(n) {
    return 'Rp ' + Math.round(parseFloat(n) || 0).toLocaleString('id-ID');
}

function updateTotal() {
    let totalT = 0;
    document.querySelectorAll('[data-tindakan-tarif]').forEach(el => {
        totalT += parseFloat(el.dataset.tindakanTarif) || 0;
    });
    let totalO = 0;
    document.querySelectorAll('[data-obat-subtotal]').forEach(el => {
        totalO += parseFloat(el.dataset.obatSubtotal) || 0;
    });
    document.getElementById('totalTindakan').textContent = formatRupiah(totalT);
    document.getElementById('totalObat').textContent     = formatRupiah(totalO);
    document.getElementById('grandTotal').textContent    = formatRupiah(totalT + totalO);
}

// ── Barang Medis ──────────────────────────────────────────────────────────
const _obatAdded = {};
const _obatHarga = {};

function autofillBarangMedis(item) {
    if (_obatAdded[item.id_barang]) {
        alert('Barang ini sudah ditambahkan.');
        return;
    }
    addObatToTable(item, 1);
}

function addObatToTable(item, jumlah) {
    _obatAdded[item.id_barang] = true;
    _obatHarga[item.id_barang] = parseFloat(item.harga) || 0;
    const subtotal = _obatHarga[item.id_barang] * parseInt(jumlah);
    document.getElementById('emptyObatRow')?.remove();

    const tr = document.createElement('tr');
    tr.id = `obat-row-${item.id_barang}`;
    tr.className = 'border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-slate-800';
    tr.innerHTML = `
        <td class="p-2 border text-center dark:border-gray-700">${item.kode_barang ?? '-'}</td>
        <td class="p-2 border dark:border-gray-700">${item.nama_barang ?? ''}</td>
        <td class="p-2 border text-center dark:border-gray-700">
            <input type="number" name="obat[${item.id_barang}][jumlah]"
                   value="${jumlah}" min="1" max="10000" required
                   oninput="updateObatSubtotal(${item.id_barang}, this.value)"
                   class="w-16 text-center border border-gray-300 rounded p-1 dark:bg-slate-900 dark:text-white dark:border-gray-700">
        </td>
        <td id="subtotal-obat-${item.id_barang}" class="p-2 border text-right pr-4 dark:border-gray-700"
            data-obat-subtotal="${subtotal}">${formatRupiah(subtotal)}</td>
        <td class="p-2 border text-center dark:border-gray-700">
            <button type="button" onclick="hapusObat(${item.id_barang})"
                    class="text-red-600 hover:underline text-sm dark:text-red-400">
                Hapus
            </button>
        </td>`;
    document.getElementById('obatTableBody').appendChild(tr);

    const inp = document.createElement('input');
    inp.type  = 'hidden';
    inp.name  = `obat[${item.id_barang}][id_barang]`;
    inp.value = item.id_barang;
    inp.dataset.id = item.id_barang;
    document.getElementById('hiddenObatInputs').appendChild(inp);

    updateTotal();
}

function updateObatSubtotal(idBarang, qty) {
    const harga    = _obatHarga[idBarang] || 0;
    const subtotal = harga * (parseInt(qty) || 0);
    const cell = document.getElementById(`subtotal-obat-${idBarang}`);
    if (cell) {
        cell.textContent          = formatRupiah(subtotal);
        cell.dataset.obatSubtotal = subtotal;
    }
    updateTotal();
}

function hapusObat(idBarang) {
    document.getElementById(`obat-row-${idBarang}`)?.remove();
    document.getElementById('hiddenObatInputs').querySelector(`[data-id="${idBarang}"]`)?.remove();
    delete _obatAdded[idBarang];
    delete _obatHarga[idBarang];

    const tbody = document.getElementById('obatTableBody');
    if (!tbody.children.length) {
        tbody.innerHTML = '<tr id="emptyObatRow"><td colspan="5" class="text-center py-6 text-gray-400 italic dark:text-gray-500">Belum ada obat/BHP dipilih</td></tr>';
    }
    updateTotal();
}

// Pre-fill pada mode edit / create
document.addEventListener('DOMContentLoaded', function () {
    const tindakanTerpilih = <?= json_encode(array_values($tindakan_terpilih ?? [])) ?>;
    tindakanTerpilih.forEach(item => addTindakanToTable(item));

    const obatTerpilih = <?= json_encode(array_values($obat)) ?>;
    obatTerpilih.forEach(item => addObatToTable(item, item.jumlah));
});

// ── Tindakan ──────────────────────────────────────────────────────────────
const _tindakanAdded = {};

function autofillTindakan(item) {
    if (_tindakanAdded[item.id_tindakan]) {
        alert('Tindakan ini sudah ditambahkan.');
        return;
    }
    addTindakanToTable(item);
}

async function addTindakanToTable(item) {
    _tindakanAdded[item.id_tindakan] = true;
    document.getElementById('emptyTindakanRow')?.remove();

    const tbody = document.getElementById('tindakanTableBody');

    // Fetch komponen paket untuk tindakan ini
    let komponen = [];
    try {
        const res = await fetch(`/operasi/paket-tindakan-operasi/modal/list?id_tindakan=${item.id_tindakan}`);
        const json = await res.json();
        komponen = json.data ?? [];
    } catch(e) {}

    if (komponen.length > 0) {
        // Baris header tindakan (tanpa tarif — subtotal dari komponen)
        const hRow = document.createElement('tr');
        hRow.id = `tindakan-row-${item.id_tindakan}`;
        hRow.style.backgroundColor = '#E6F2EF';
        hRow.className = 'border-b dark:border-gray-700 dark:bg-slate-700';
        hRow.innerHTML = `
            <td class="p-2 border text-center text-xs font-mono dark:border-gray-700">${item.kode_tindakan ?? '-'}</td>
            <td class="p-2 border font-semibold text-gray-900 dark:text-white dark:border-gray-700">${item.nama_tindakan ?? ''}</td>
            <td class="p-2 border text-center text-gray-400 dark:border-gray-700 italic text-xs">—</td>
            <td class="p-2 border text-center dark:border-gray-700">
                <button type="button" onclick="hapusTindakan(${item.id_tindakan})"
                        class="text-red-600 hover:underline text-sm dark:text-red-400">Hapus</button>
            </td>`;
        tbody.appendChild(hRow);

        // Baris per komponen
        komponen.forEach(k => {
            const tr = document.createElement('tr');
            tr.className = 'border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-slate-800';
            tr.dataset.tindakanId = item.id_tindakan;
            const tarif = parseFloat(k.tarif_kelas_3) || 0;
            tr.innerHTML = `
                <td class="p-2 border dark:border-gray-700"></td>
                <td class="p-2 pl-8 border text-gray-500 dark:text-gray-400 dark:border-gray-700 text-xs">${k.nama_komponen ?? ''}</td>
                <td class="p-2 border text-right pr-4 dark:border-gray-700 text-xs"
                    data-tindakan-tarif="${tarif}">${formatRupiah(tarif)}</td>
                <td class="p-2 border dark:border-gray-700"></td>`;
            tbody.appendChild(tr);
        });
    } else {
        // Fallback: tidak ada komponen, tampilkan tarif tindakan langsung
        const tarif = parseFloat(item.tarif) || 0;
        const tr = document.createElement('tr');
        tr.id = `tindakan-row-${item.id_tindakan}`;
        tr.className = 'border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-slate-800';
        tr.innerHTML = `
            <td class="p-2 border text-center dark:border-gray-700">${item.kode_tindakan ?? '-'}</td>
            <td class="p-2 border dark:border-gray-700">${item.nama_tindakan ?? ''}</td>
            <td class="p-2 border text-right pr-4 dark:border-gray-700"
                data-tindakan-tarif="${tarif}">${formatRupiah(tarif)}</td>
            <td class="p-2 border text-center dark:border-gray-700">
                <button type="button" onclick="hapusTindakan(${item.id_tindakan})"
                        class="text-red-600 hover:underline text-sm dark:text-red-400">Hapus</button>
            </td>`;
        tbody.appendChild(tr);
    }

    // Hidden input untuk submit
    const inp = document.createElement('input');
    inp.type = 'hidden';
    inp.name = `tindakan[${item.id_tindakan}][id_tindakan]`;
    inp.value = item.id_tindakan;
    inp.dataset.id = item.id_tindakan;
    document.getElementById('hiddenTindakanInputs').appendChild(inp);

    updateTotal();
}

function hapusTindakan(idTindakan) {
    document.getElementById(`tindakan-row-${idTindakan}`)?.remove();
    document.querySelectorAll(`[data-tindakan-id="${idTindakan}"]`).forEach(el => el.remove());
    document.getElementById('hiddenTindakanInputs').querySelector(`[data-id="${idTindakan}"]`)?.remove();
    delete _tindakanAdded[idTindakan];

    const tbody = document.getElementById('tindakanTableBody');
    if (!tbody.children.length) {
        tbody.innerHTML = '<tr id="emptyTindakanRow"><td colspan="4" class="text-center py-6 text-gray-400 italic dark:text-gray-500">Belum ada tindakan ditambahkan</td></tr>';
    }
    updateTotal();
}

// ── Validasi ──────────────────────────────────────────────────────────────
function validateForm() {
    if (!document.getElementById('id_jadwal').value) {
        alert('Jadwal operasi tidak ditemukan. Buka form ini dari halaman Lembar Operasi.');
        return false;
    }
    const btn = document.getElementById('submitButton');
    if (btn) { btn.disabled = true; btn.innerHTML = 'Menyimpan...'; }
    return true;
}
</script>

<?= $this->endSection(); ?>
