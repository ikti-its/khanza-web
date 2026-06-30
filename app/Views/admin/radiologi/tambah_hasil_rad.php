<?php
$inputClass    = 'border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full dark:border-gray-600 dark:text-white cursor-pointer bg-slate-50';
$readonlyClass = 'border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full dark:border-gray-600 dark:text-white bg-gray-100 cursor-not-allowed';
$labelLeft     = 'block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4';
$labelRight    = 'block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5';
$btnClass      = 'inline-flex justify-center items-center p-2 text-sm font-medium text-white bg-blue-600 rounded-lg border border-transparent hover:bg-blue-700 focus:outline-none transition-all w-10 h-[38px] flex-shrink-0 shadow-sm';
$addBtnClass   = 'inline-flex items-center gap-x-1.5 py-2 px-3 text-sm font-semibold rounded-lg border border-transparent bg-[#0A2D27] text-[#ACF2E7] hover:bg-[#13594E] transition-all shadow-sm';
$searchIcon    = '<svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>';
$plusIcon      = '<svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>';
$isEdit        = str_contains($judul, 'Ubah');
$val           = fn($k) => esc($baris[$k] ?? '');
$sectionHead   = fn($t) => "<h4 class=\"text-sm font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wide mb-4 mt-8 border-b pb-2 dark:border-gray-700\">$t</h4>";
?>

<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<?= $this->include('components/modal/modalpermintaanrad') ?>
<?= $this->include('components/modal/modaldokter') ?>
<?= $this->include('components/modal/modalpetugas') ?>

<div class="max-w-[85rem] py-6 lg:py-3 px-8 mx-auto">
    <div class="bg-white rounded-xl shadow-sm p-4 sm:p-7 dark:bg-slate-900 border border-gray-100 dark:border-gray-800">
        <?= view('components/form/judul', ['judul' => $judul]) ?>

        <form action="<?= $modul_path . $form_action ?>" id="myForm" onsubmit="return validateForm()" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>

            <input type="hidden" name="id_permintaan_rad" id="id_permintaan_rad" value="<?= $val('id_permintaan_rad') ?>">
            <input type="hidden" name="id_dokter_pj"      id="id_dokter_pj"      value="<?= $val('id_dokter_pj') ?>">
            <input type="hidden" name="id_petugas_rad"    id="id_petugas_rad"    value="<?= $val('id_petugas_rad') ?>">
            <input type="hidden" name="id_dokter_perujuk" id="id_dokter_perujuk" value="<?= $val('id_dokter_perujuk') ?>">

            <!-- No. Permintaan -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="<?= $labelLeft ?>">No. Permintaan <span class="text-red-500">*</span></label>
                <div class="flex flex-col lg:w-1/4">
                    <div class="flex gap-x-2">
                        <input type="text" id="no_permintaan_display" value="<?= $val('no_permintaan') ?>"
                               readonly placeholder="Klik cari permintaan..."
                               <?= $isEdit ? '' : 'onclick="open_modalPermintaanRad()"' ?>
                               class="<?= $isEdit ? $readonlyClass : $inputClass ?>">
                        <?php if (!$isEdit) : ?>
                            <button type="button" onclick="open_modalPermintaanRad()" class="<?= $btnClass ?>"><?= $searchIcon ?></button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- No. Registrasi + Nama Pasien -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="<?= $labelLeft ?>">No. Registrasi</label>
                <input type="text" id="nomor_reg_display"   value="<?= $val('nomor_reg') ?>"   readonly placeholder="Terisi otomatis..." class="<?= $readonlyClass ?> lg:w-1/4">
                <label class="<?= $labelRight ?>">Nama Pasien</label>
                <input type="text" id="nama_pasien_display" value="<?= $val('nama_pasien') ?>" readonly placeholder="Terisi otomatis..." class="<?= $readonlyClass ?> lg:w-1/4">
            </div>

            <!-- Dokter Perujuk -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="<?= $labelLeft ?>">Dokter Perujuk</label>
                <input type="text" id="nama_dokter_perujuk_display" value="<?= $val('nama_dokter_perujuk') ?>"
                       readonly placeholder="Terisi otomatis..." class="<?= $readonlyClass ?> lg:w-1/4">
            </div>

            <!-- Dokter PJ + Petugas -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="<?= $labelLeft ?>">Dokter PJ <span class="text-red-500">*</span></label>
                <div class="flex flex-col lg:w-1/4">
                    <div class="flex gap-x-2">
                        <input type="text" id="nama_dokter_pj_display" value="<?= $val('nama_dokter_pj') ?>"
                               readonly placeholder="Klik cari dokter..." onclick="open_modalDokterPJ()" class="<?= $inputClass ?>">
                        <button type="button" onclick="open_modalDokterPJ()" class="<?= $btnClass ?>"><?= $searchIcon ?></button>
                    </div>
                </div>
                <label class="<?= $labelRight ?>">Petugas <span class="text-red-500">*</span></label>
                <div class="flex flex-col lg:w-1/4">
                    <div class="flex gap-x-2">
                        <input type="text" id="nama_petugas_display" value="<?= $val('nama_petugas') ?>"
                               readonly placeholder="Klik cari petugas..." onclick="open_modalPetugas()" class="<?= $inputClass ?>">
                        <button type="button" onclick="open_modalPetugas()" class="<?= $btnClass ?>"><?= $searchIcon ?></button>
                    </div>
                </div>
            </div>

            <?= view('components/form/isian', ['konfig' => $konfig, 'baris' => $baris]) ?>

            <!-- SECTION 2: Tindakan -->
            <?= $sectionHead('Tindakan Radiologi') ?>
            <div id="tindakanContainer">
                <div class="text-center py-6 text-gray-400 italic text-sm dark:text-gray-500">
                    Pilih permintaan terlebih dahulu untuk mengisi hasil tindakan.
                </div>
            </div>

            <!-- SECTION 3: BHP -->
            <?= $sectionHead('BHP Radiologi') ?>
            <div class="flex justify-end mb-3">
                <button type="button" onclick="open_modalBhpRad()" class="<?= $addBtnClass ?>">
                    <?= $plusIcon ?> Tambah BHP
                </button>
            </div>

            <div class="border rounded-xl overflow-hidden dark:border-gray-700 mb-8">
                <table class="w-full text-sm text-gray-700 dark:text-gray-300 table-fixed">
                    <colgroup><col class="w-1/6"><col class="w-1/3"><col class="w-1/6"><col class="w-1/6"><col class="w-1/6"></colgroup>
                    <thead style="background-color: #E6F2EF;" class="text-gray-800 font-semibold text-base">
                        <tr>
                            <th class="p-4 border text-center text-base">Kode Barang</th>
                            <th class="p-4 border text-center text-base">Nama Barang</th>
                            <th class="p-4 border text-center text-base">Satuan</th>
                            <th class="p-4 border text-center text-base">Jumlah Pakai</th>
                            <th class="p-4 border text-center text-base">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="bhpRadBody">
                        <tr id="emptyBhpRow">
                            <td colspan="5" class="text-center py-6 text-gray-400 italic dark:text-gray-500">Belum ada BHP dipilih</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div id="hiddenBhpInputs"></div>

            <?php if (!$isEdit) : ?>
            <!-- SECTION 4: Foto (Tambah — preview + submit) -->
            <?= $sectionHead('Foto Pemeriksaan') ?>
            <div class="flex justify-end mb-3">
                <label class="<?= $addBtnClass ?> cursor-pointer">
                    <?= $plusIcon ?> Tambah Foto
                    <input type="file" id="inputFotoTambah" name="foto[]" multiple accept="image/*" class="hidden" onchange="pilihFoto(this)">
                </label>
            </div>
            <div id="fotoPreviewGrid" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3 mb-4">
                <div style="grid-column:1/-1;text-align:center;" class="py-4 text-gray-400 italic text-sm dark:text-gray-500">Belum ada foto dipilih</div>
            </div>
            <?php endif; ?>

            <?php if ($isEdit) : ?>
            <!-- SECTION 4: Foto (Ubah — upload AJAX) -->
            <?= $sectionHead('Foto Pemeriksaan') ?>
            <div class="flex justify-end mb-3">
                <label class="<?= $addBtnClass ?> cursor-pointer">
                    <?= $plusIcon ?> Tambah Foto
                    <input type="file" id="inputFoto" name="foto_upload[]" multiple accept="image/*" class="hidden" onchange="uploadFoto(this)">
                </label>
                <span id="uploadStatus" class="self-center ml-3 text-sm text-gray-500 dark:text-gray-400"></span>
            </div>

            <div id="fotoGrid" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3 mb-4">
                <?php foreach ($foto_terpilih ?? [] as $foto) : ?>
                <div class="relative" style="position:relative;" id="foto-<?= $foto['id_rad_foto'] ?>">
                    <img src="<?= base_url('uploads/radiologi/' . esc($foto['nama_file'])) ?>" alt="Foto"
                         class="w-full h-24 object-cover rounded-lg border border-gray-200 dark:border-gray-700">
                    <button type="button" onclick="hapusFoto(<?= $foto['id_rad_foto'] ?>)"
                            style="position:absolute; top:4px; right:4px; z-index:10; width:20px; height:20px; font-size:16px; line-height:1;"
                            class="flex items-center justify-center bg-red-600 text-white rounded-full shadow hover:bg-red-700">&times;</button>
                </div>
                <?php endforeach; ?>
                <?php if (empty($foto_terpilih)) : ?>
                <div id="emptyFotoMsg" style="grid-column: 1 / -1; text-align: center;"
                     class="py-4 text-gray-400 italic text-sm dark:text-gray-500">Belum ada foto</div>
                <?php endif; ?>
            </div>
            <?php endif; ?>

            <div class="flex justify-end gap-x-2 mt-8">
                <?= view('components/form/submit_button') ?>
            </div>
        </form>
    </div>
</div>

<!-- Modal BHP -->
<div id="modalBhpRad" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center">
    <div class="bg-white rounded-xl px-6 py-6 w-full max-w-4xl min-h-[32rem] max-h-[90vh] shadow-2xl flex flex-col dark:bg-slate-900">
        <div class="flex justify-between items-center mb-4 border-b pb-3 dark:border-gray-700">
            <h2 class="text-lg font-bold text-gray-800 dark:text-white">Pilih BHP Radiologi</h2>
            <button onclick="close_modalBhpRad()" class="text-gray-400 hover:text-red-600 text-2xl font-bold leading-none">&times;</button>
        </div>

        <div class="flex gap-3 mb-4">
            <input type="text" id="searchNamaBhp" placeholder="Cari nama barang..."
                   class="w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-emerald-500 text-sm dark:border-gray-600 dark:bg-slate-800 dark:text-white">
        </div>

        <div class="border rounded-md overflow-auto grow dark:border-gray-700">
            <table class="w-full text-sm text-gray-700 dark:text-gray-300 table-fixed">
                <colgroup><col class="w-1/6"><col class="w-1/3"><col class="w-1/6"><col class="w-1/6"><col class="w-1/6"></colgroup>
                <thead style="background-color: #E6F2EF;" class="text-gray-800 font-semibold text-base">
                    <tr>
                        <th class="p-4 border text-center text-base">Kode</th>
                        <th class="p-4 border text-center text-base">Nama Barang</th>
                        <th class="p-4 border text-center text-base">Satuan</th>
                        <th class="p-4 border text-center text-base">Stok</th>
                        <th class="p-4 border text-center text-base">Aksi</th>
                    </tr>
                </thead>
                <tbody id="bhpRadTableBody">
                    <tr><td colspan="5" class="text-center p-4 text-gray-400">Memuat data...</td></tr>
                </tbody>
            </table>
        </div>

        <div class="mt-4 flex justify-between items-center text-sm text-gray-600 dark:text-gray-400">
            <button id="prevBhpBtn" onclick="prevBhpPage()" disabled
                    class="px-4 py-1 bg-gray-100 rounded hover:bg-gray-200 disabled:opacity-50 dark:bg-slate-700">Sebelumnya</button>
            <span id="pageInfoBhp" class="text-gray-500">Halaman 1</span>
            <button id="nextBhpBtn" onclick="nextBhpPage()"
                    class="px-4 py-1 bg-gray-100 rounded hover:bg-gray-200 dark:bg-slate-700">Berikutnya</button>
        </div>
    </div>
</div>

<script>
    const _templateRad    = <?= json_encode($template_rad    ?? []) ?>;
    const _barangNonMedis = <?= json_encode($barang_non_medis ?? []) ?>;
    let _bhpFiltered = [..._barangNonMedis];
    let _bhpPage     = 1;
    const _bhpPerPage = 10;

    // ════════════════════════════════════════════
    // AUTOFILL
    // ════════════════════════════════════════════
    function autofillPermintaanRad(item) {
        document.getElementById('no_permintaan_display').value       = item.no_permintaan       ?? '';
        document.getElementById('nomor_reg_display').value           = item.nomor_reg           ?? '';
        document.getElementById('nama_pasien_display').value         = item.nama                ?? '';
        document.getElementById('nama_dokter_perujuk_display').value = item.nama_dokter_perujuk ?? '';
        document.getElementById('id_permintaan_rad').value           = item.id_permintaan       ?? '';
        document.getElementById('id_dokter_perujuk').value           = item.id_dokter_perujuk   ?? '';
        fetchTindakanItems(item.id_permintaan);
    }

    let _modalDokterTarget = null;
    function open_modalDokterPJ() { _modalDokterTarget = 'pj'; open_modalDokter(); }

    function autofillFields(item) {
        if (_modalDokterTarget === 'pj') {
            document.getElementById('nama_dokter_pj_display').value = item.nama_dokter ?? '';
            document.getElementById('id_dokter_pj').value           = item.id_dokter   ?? '';
        }
        _modalDokterTarget = null;
    }

    function autofillPetugas(item) {
        document.getElementById('nama_petugas_display').value = item.nama       ?? '';
        document.getElementById('id_petugas_rad').value       = item.id_petugas ?? '';
    }

    // ════════════════════════════════════════════
    // TINDAKAN
    // ════════════════════════════════════════════
    function fetchTindakanItems(idPermintaan) {
        fetch(`/radiologi/permintaan-rad-item/modal/list?id_permintaan=${idPermintaan}`)
            .then(res => res.json())
            .then(result => renderTindakanTable(result.data || []))
            .catch(() => {
                document.getElementById('tindakanContainer').innerHTML =
                    '<div class="text-center py-4 text-red-500 text-sm">Gagal memuat item tindakan.</div>';
            });
    }

    function renderTindakanTable(items) {
        const container = document.getElementById('tindakanContainer');
        if (items.length === 0) {
            container.innerHTML = '<div class="text-center py-4 text-gray-400 italic text-sm">Tidak ada item pada permintaan ini.</div>';
            return;
        }

        const templateOptions = _templateRad.map(t =>
            `<option value="${t.id_template}" data-teks="${encodeURIComponent(t.isi_teks_ekspertise)}">${t.nama_template}</option>`
        ).join('');

        const tdCls  = 'p-2 border dark:border-gray-700';
        const inpCls = 'w-full border border-gray-300 rounded p-1 text-sm dark:bg-slate-900 dark:border-gray-600 dark:text-white';
        const inp = (name, type = 'text', extra = '', value = '') => {
            const oninput = type === 'number' ? 'oninput="validateRange(this)"' : '';
            return `<td class="${tdCls}"><input type="${type}" ${extra} ${oninput} name="${name}" value="${value}" class="${inpCls}"></td>`;
        };

        let html = `
            <div class="border rounded-xl overflow-hidden dark:border-gray-700 mb-4">
                <table class="w-full text-sm text-gray-700 dark:text-gray-300">
                    <thead style="background-color: #E6F2EF;" class="text-gray-800 font-semibold text-base">
                        <tr>
                            <th class="p-4 border text-center text-base">Item</th>
                            <th class="p-4 border text-center text-base">Proyeksi</th>
                            <th class="p-4 border text-center text-base">kV</th>
                            <th class="p-4 border text-center text-base">mAs</th>
                            <th class="p-4 border text-center text-base">FFD</th>
                            <th class="p-4 border text-center text-base">BSF</th>
                            <th class="p-4 border text-center text-base">Inaktivasi</th>
                            <th class="p-4 border text-center text-base">Jml Penyinaran</th>
                            <th class="p-4 border text-center text-base">Dosis Radiasi</th>
                            <th class="p-4 border text-center text-base">Detail</th>
                        </tr>
                    </thead>
                    <tbody>`;

        items.forEach((item, idx) => {
            const p          = `tindakan[${idx}]`;
            const isBacaSaja = !!item.is_baca_saja && item.is_baca_saja !== 'f';

            const teknisCells = isBacaSaja
                ? `<td colspan="8" class="p-2 border text-center text-sm text-gray-400 italic dark:border-gray-700 dark:text-gray-500">Parameter teknis tidak diperlukan</td>`
                : `${inp(`${p}[proyeksi]`,               'text',   'maxlength="20" placeholder="Contoh: AP, PA"',         item.proyeksi               ?? '')}
                   ${inp(`${p}[kilovoltage_kv]`,         'number', 'step="0.01" min="20"  max="160"  placeholder="kV"',   item.kilovoltage_kv         ?? '')}
                   ${inp(`${p}[milliampere_second_mas]`, 'number', 'step="0.01" min="0.1" max="1000" placeholder="mAs"',  item.milliampere_second_mas ?? '')}
                   ${inp(`${p}[focus_film_distance_ffd]`,'number', 'step="1"    min="40"  max="300"  placeholder="cm"',   item.focus_film_distance_ffd ?? '')}
                   ${inp(`${p}[back_scatter_factor_bsf]`,'number', 'step="0.01" min="1.0" max="2.0"  placeholder="BSF"',  item.back_scatter_factor_bsf ?? '')}
                   ${inp(`${p}[inaktivasi]`,             'text',   'maxlength="50" placeholder="Contoh: manual"',         item.inaktivasi             ?? '')}
                   ${inp(`${p}[jumlah_penyinaran]`,      'number', 'step="1"    min="1"   max="50"   placeholder="kali"', item.jumlah_penyinaran      ?? '')}
                   ${inp(`${p}[dosis_radiasi]`,          'text',   'maxlength="50" placeholder="Contoh: 1.2 mGy"',        item.dosis_radiasi          ?? '')}`;

            const bakaSajaBadge = isBacaSaja
                ? `<span class="ml-1 px-1.5 py-0.5 text-xs rounded bg-teal-100 text-teal-800 dark:bg-teal-800/30 dark:text-teal-300">Baca Saja</span>`
                : '';

            html += `
                <tr class="hover:bg-gray-50 dark:hover:bg-slate-800">
                    <td class="p-2 border text-center dark:border-gray-700">
                        ${item.nama_pemeriksaan}${bakaSajaBadge}
                        <input type="hidden" name="${p}[id_permintaan_item]" value="${item.id_permintaan_item}">
                    </td>
                    ${teknisCells}
                    <td class="p-2 border text-center dark:border-gray-700">
                        <button type="button" onclick="toggleEkspertise(${idx})" class="text-blue-600 hover:underline text-sm">▼</button>
                    </td>
                </tr>
                <tr id="ekspertise-row-${idx}" class="hidden">
                    <td colspan="10" class="p-4 border bg-gray-50 dark:bg-slate-800 dark:border-gray-700">
                        <div class="mb-2 flex items-center gap-x-3">
                            <label class="text-sm text-gray-700 dark:text-gray-300 whitespace-nowrap">Pilih Template:</label>
                            <select onchange="applyTemplate(this, ${idx})"
                                    class="border border-gray-300 text-sm rounded-lg p-2 dark:border-gray-600 dark:bg-slate-800 dark:text-white">
                                <option value="">-- Tanpa Template --</option>
                                ${templateOptions}
                            </select>
                        </div>
                        <textarea name="${p}[hasil_ekspertise]" rows="8" id="ekspertise-text-${idx}"
                                  placeholder="Tuliskan hasil ekspertise di sini..."
                                  class="w-full border border-gray-300 text-sm rounded-lg p-2 dark:border-gray-600 dark:bg-slate-900 dark:text-white">${item.hasil_ekspertise ?? ''}</textarea>
                        <input type="hidden" name="${p}[id_template_rad]" id="template-id-${idx}" value="${item.id_template_rad ?? ''}">
                    </td>
                </tr>`;
        });

        html += `</tbody></table></div>`;
        container.innerHTML = html;
    }

    function validateRange(el) {
        if (el.value === '' || el.validity.valid) { el.setCustomValidity(''); return; }
        let msg = '';
        if (el.validity.rangeUnderflow)     msg = `Nilai minimal ${el.min}`;
        else if (el.validity.rangeOverflow) msg = `Nilai maksimal ${el.max}`;
        else if (el.validity.stepMismatch)  msg = `Kelipatan ${el.step}`;
        el.setCustomValidity(msg);
        if (msg) el.reportValidity();
    }

    function toggleEkspertise(idx) {
        const row = document.getElementById(`ekspertise-row-${idx}`);
        const btn = row.previousElementSibling.querySelector('button');
        row.classList.toggle('hidden');
        btn.textContent = row.classList.contains('hidden') ? '▼' : '▲';
    }

    function applyTemplate(select, idx) {
        const teks = select.options[select.selectedIndex].dataset.teks
            ? decodeURIComponent(select.options[select.selectedIndex].dataset.teks) : '';
        document.getElementById(`ekspertise-text-${idx}`).value = teks;
        document.getElementById(`template-id-${idx}`).value     = select.value;
    }

    // ════════════════════════════════════════════
    // BHP
    // ════════════════════════════════════════════
    function open_modalBhpRad()  { _bhpFiltered = [..._barangNonMedis]; _bhpPage = 1; renderBhpTable(); document.getElementById('modalBhpRad').classList.remove('hidden'); }
    function close_modalBhpRad() { document.getElementById('modalBhpRad').classList.add('hidden'); }

    function renderBhpTable() {
        const tbody = document.getElementById('bhpRadTableBody');
        const start = (_bhpPage - 1) * _bhpPerPage;
        const paged = _bhpFiltered.slice(start, start + _bhpPerPage);

        if (paged.length === 0) {
            tbody.innerHTML = `<tr><td colspan="5" class="text-center p-4 text-gray-400">Tidak ada data</td></tr>`;
            updateBhpPagination();
            return;
        }

        tbody.innerHTML = paged.map(item => `
            <tr class="hover:bg-emerald-50 dark:hover:bg-slate-700">
                <td class="p-2 border text-center">${item.kode_barang}</td>
                <td class="p-2 border">${item.nama_barang}</td>
                <td class="p-2 border text-center">${item.nama_satuan ?? '-'}</td>
                <td class="p-2 border text-center">${item.stok}</td>
                <td class="p-2 border text-center">
                    <button type="button" onclick='pilihBhp(${JSON.stringify(item)})' class="text-emerald-700 hover:underline text-sm">Pilih</button>
                </td>
            </tr>`).join('');

        updateBhpPagination();
    }

    function updateBhpPagination() {
        const total = Math.ceil(_bhpFiltered.length / _bhpPerPage);
        document.getElementById('pageInfoBhp').textContent = `Halaman ${_bhpPage} dari ${total || 1}`;
        document.getElementById('prevBhpBtn').disabled = _bhpPage === 1;
        document.getElementById('nextBhpBtn').disabled = _bhpPage >= total;
    }

    function prevBhpPage() { if (_bhpPage > 1)                                            { _bhpPage--; renderBhpTable(); } }
    function nextBhpPage() { if (_bhpPage < Math.ceil(_bhpFiltered.length / _bhpPerPage)) { _bhpPage++; renderBhpTable(); } }

    function pilihBhp(item) {
        const hiddenDiv = document.getElementById('hiddenBhpInputs');
        if (hiddenDiv.querySelector(`input[data-id="${item.id_barang}"]`)) { alert('Barang sudah ditambahkan.'); return; }

        document.getElementById('emptyBhpRow')?.remove();

        const tr = document.createElement('tr');
        tr.className = 'border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-slate-800';
        tr.innerHTML = `
            <td class="p-2 border text-center dark:border-gray-700">${item.kode_barang}</td>
            <td class="p-2 border dark:border-gray-700">${item.nama_barang}</td>
            <td class="p-2 border text-center dark:border-gray-700">${item.nama_satuan ?? '-'}</td>
            <td class="p-2 border text-center dark:border-gray-700">
                <input type="number" name="bhp[${item.id_barang}][jumlah_pakai]"
                       data-id="${item.id_barang}" value="${item.jumlah_pakai ?? 1}" min="1"
                       class="w-16 text-center border border-gray-300 rounded p-1 dark:bg-slate-900 dark:text-white dark:border-gray-700">
            </td>
            <td class="p-2 border text-center dark:border-gray-700">
                <button type="button" onclick="hapusBhp(${item.id_barang}, this)" class="text-red-600 hover:underline text-sm dark:text-red-400">Hapus</button>
            </td>`;
        document.getElementById('bhpRadBody').appendChild(tr);

        const input = document.createElement('input');
        input.type = 'hidden'; input.name = `bhp[${item.id_barang}][id_barang]`;
        input.value = item.id_barang; input.dataset.id = item.id_barang;
        hiddenDiv.appendChild(input);

        close_modalBhpRad();
    }

    function hapusBhp(idBarang, btn) {
        btn.closest('tr').remove();
        document.getElementById('hiddenBhpInputs').querySelector(`input[data-id="${idBarang}"]`)?.remove();
        const tbody = document.getElementById('bhpRadBody');
        if (tbody.children.length === 0) {
            tbody.innerHTML = `<tr id="emptyBhpRow"><td colspan="5" class="text-center py-6 text-gray-400 italic dark:text-gray-500">Belum ada BHP dipilih</td></tr>`;
        }
    }

    // ════════════════════════════════════════════
    // INIT
    // ════════════════════════════════════════════
    document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('searchNamaBhp')?.addEventListener('input', function () {
            _bhpFiltered = _barangNonMedis.filter(i => i.nama_barang.toLowerCase().includes(this.value.toLowerCase()));
            _bhpPage = 1;
            renderBhpTable();
        });

        const itemTerpilih = <?= json_encode($item_terpilih ?? []) ?>;
        if (itemTerpilih.length > 0) renderTindakanTable(itemTerpilih);

        const bhpTerpilih = <?= json_encode($bhp_terpilih ?? []) ?>;
        bhpTerpilih.forEach(item => pilihBhp(item));
    });

    // ════════════════════════════════════════════
    // FOTO
    // ════════════════════════════════════════════
    <?php if (!$isEdit) : ?>
    let _fotoDataTransfer = new DataTransfer();
    let _renderGen = 0;

    function pilihFoto(input) {
        [...input.files].forEach(f => {
            const exists = [..._fotoDataTransfer.files].some(ef => ef.name === f.name && ef.size === f.size);
            if (!exists) _fotoDataTransfer.items.add(f);
        });
        input.value = '';
        document.getElementById('inputFotoTambah').files = _fotoDataTransfer.files;
        renderFotoPreview();
    }

    function hapusFotoPreview(idx) {
        const newDT = new DataTransfer();
        [..._fotoDataTransfer.files].forEach((f, i) => { if (i !== idx) newDT.items.add(f); });
        _fotoDataTransfer = newDT;
        document.getElementById('inputFotoTambah').files = _fotoDataTransfer.files;
        renderFotoPreview();
    }

    function renderFotoPreview() {
        const grid = document.getElementById('fotoPreviewGrid');
        grid.innerHTML = '';

        if (!_fotoDataTransfer.files.length) {
            grid.innerHTML = '<div style="grid-column:1/-1;text-align:center;" class="py-4 text-gray-400 italic text-sm dark:text-gray-500">Belum ada foto dipilih</div>';
            return;
        }

        const gen = ++_renderGen;
        [..._fotoDataTransfer.files].forEach((file, idx) => {
            const div = document.createElement('div');
            div.className = 'relative'; div.style.position = 'relative';
            div.innerHTML = `
                <div class="w-full h-24 bg-gray-100 rounded-lg border border-gray-200 dark:border-gray-600 flex items-center justify-center text-xs text-gray-400">Memuat...</div>
                <button type="button" onclick="hapusFotoPreview(${idx})"
                        style="position:absolute;top:4px;right:4px;z-index:10;width:20px;height:20px;font-size:16px;line-height:1;"
                        class="flex items-center justify-center bg-red-600 text-white rounded-full shadow hover:bg-red-700">&times;</button>`;
            grid.appendChild(div);

            const reader = new FileReader();
            reader.onload = e => {
                if (_renderGen !== gen) return;
                const img = document.createElement('img');
                img.src = e.target.result;
                img.alt = file.name;
                img.className = 'w-full h-24 object-cover rounded-lg border border-gray-200 dark:border-gray-700';
                div.replaceChild(img, div.firstChild);
            };
            reader.readAsDataURL(file);
        });
    }
    <?php else : ?>
    const _idHasilRad    = <?= json_encode($id_hasil_rad ?? null) ?>;
    const _uploadPath    = '/radiologi/foto-hasil-radiologi/upload/';
    const _hapusFotoPath = '/radiologi/foto-hasil-radiologi/hapus-foto/';

    function uploadFoto(input) {
        if (!input.files.length || !_idHasilRad) return;
        const status   = document.getElementById('uploadStatus');
        const formData = new FormData();
        [...input.files].forEach(f => formData.append('foto[]', f));
        const csrf = document.querySelector('input[name^="csrf"]');
        if (csrf) formData.append(csrf.name, csrf.value);

        status.textContent = 'Mengupload...';
        fetch(_uploadPath + _idHasilRad, { method: 'POST', body: formData })
            .then(r => r.json())
            .then(res => {
                if (!res.success) throw new Error();
                res.data.forEach(foto => appendFotoCard(foto.id_rad_foto, foto.url));
                status.textContent = `${res.data.length} foto berhasil diupload.`;
                input.value = '';
                setTimeout(() => { status.textContent = ''; }, 3000);
            })
            .catch(() => { status.textContent = 'Gagal mengupload foto.'; });
    }

    function appendFotoCard(id, url) {
        document.getElementById('emptyFotoMsg')?.remove();
        const div = document.createElement('div');
        div.className = 'relative'; div.style.position = 'relative'; div.id = `foto-${id}`;
        div.innerHTML = `
            <img src="${url}" alt="Foto" class="w-full h-24 object-cover rounded-lg border border-gray-200 dark:border-gray-700">
            <button type="button" onclick="hapusFoto(${id})"
                    style="position:absolute; top:4px; right:4px; z-index:10; width:20px; height:20px; font-size:16px; line-height:1;"
                    class="flex items-center justify-center bg-red-600 text-white rounded-full shadow hover:bg-red-700">&times;</button>`;
        document.getElementById('fotoGrid').appendChild(div);
    }

    function hapusFoto(id) {
        if (!confirm('Apakah anda yakin ingin menghapus gambar ini?')) return;
        const csrf = document.querySelector('input[name^="csrf"]');
        const body = new FormData();
        if (csrf) body.append(csrf.name, csrf.value);

        fetch(_hapusFotoPath + id, { method: 'POST', body })
            .then(r => r.json())
            .then(res => {
                if (!res.success) throw new Error();
                document.getElementById(`foto-${id}`)?.remove();
                const grid = document.getElementById('fotoGrid');
                if (!grid?.children.length) {
                    grid.innerHTML = '<p id="emptyFotoMsg" style="grid-column:1/-1; text-align:center;" class="py-4 text-gray-400 italic text-sm">Belum ada foto</p>';
                }
            })
            .catch(() => alert('Gagal menghapus foto.'));
    }
    <?php endif; ?>

    // ════════════════════════════════════════════
    // VALIDASI FORM
    // ════════════════════════════════════════════
    function validateForm() {
        const headerReqs = [
            { id: 'id_permintaan_rad', msg: 'Permintaan radiologi wajib dipilih.' },
            { id: 'id_dokter_pj',      msg: 'Dokter PJ wajib dipilih.' },
            { id: 'id_petugas_rad',    msg: 'Petugas radiologi wajib dipilih.' },
        ];
        for (const f of headerReqs) {
            if (!document.getElementById(f.id)?.value) {
                alert(f.msg);
                return false;
            }
        }

        if (!document.getElementById('myForm').checkValidity()) {
            document.getElementById('myForm').reportValidity();
            return false;
        }

        const btn = document.getElementById('submitButton');
        if (btn) { btn.disabled = true; btn.innerHTML = 'Menyimpan...'; }
        return true;
    }
</script>

<?= $this->endSection(); ?>
