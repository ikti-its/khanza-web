<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<?= $this->include('components/modal/modalregistrasi') ?>
<?= $this->include('components/modal/modaldokter') ?>
<?= $this->include('components/modal/modalitempemeriksaanlab') ?>

<div class="max-w-[85rem] py-6 lg:py-3 px-8 mx-auto">
    <div class="bg-white rounded-xl shadow-sm p-4 sm:p-7 dark:bg-slate-900 border border-gray-100 dark:border-gray-800">
        <?= view('components/form/judul', ['judul' => $judul]) ?>

        <form action="<?= $modul_path . $form_action ?>" id="myForm" onsubmit="return validateForm()" method="post">
            <?= csrf_field() ?>

            <?= $this->include('admin/laboratorium/header_permintaan_lab') ?>

            <!-- Item Pemeriksaan PK -->
            <div class="mb-5 mt-4">
                <div class="flex justify-between items-center mb-3">
                    <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                        Item Pemeriksaan <span class="text-red-500">*</span>
                    </h4>
                    <button type="button"
                            onclick="open_modalItemPemeriksaanLab(<?= ID_KATEGORI_PK ?>, 'Pilih Item Patologi Klinik')"
                            class="inline-flex items-center gap-x-1.5 py-2 px-3 text-sm font-semibold rounded-lg border border-transparent bg-[#0A2D27] text-[#ACF2E7] hover:bg-[#13594E] transition-all shadow-sm">
                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                        </svg>
                        Pilih Item
                    </button>
                </div>

                <div class="border rounded-xl overflow-hidden dark:border-gray-700">
                    <table class="w-full text-sm text-gray-700 dark:text-gray-300">
                        <thead style="background-color: #E6F2EF;" class="text-gray-800 font-semibold">
                            <tr>
                                <th class="p-3 border text-center dark:border-gray-700 w-10"></th>
                                <th class="p-3 border text-center dark:border-gray-700">Kode</th>
                                <th class="p-3 border text-center dark:border-gray-700">Nama Item / Parameter</th>
                                <th class="p-3 border text-center dark:border-gray-700">Satuan</th>
                                <th class="p-3 border text-center dark:border-gray-700">Nilai Rujukan</th>
                                <th class="p-3 border text-center dark:border-gray-700">Tarif</th>
                                <th class="p-3 border text-center dark:border-gray-700">Hapus</th>
                            </tr>
                        </thead>
                        <tbody id="itemLabTerpilihBody">
                            <tr id="emptyItemLabRow">
                                <td colspan="7" class="text-center py-6 text-gray-400 italic dark:text-gray-500">
                                    Belum ada item dipilih
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <p id="err_items" class="hidden text-red-500 text-xs mt-2"></p>
                <div id="hiddenItemLabInputs"></div>
            </div>

            <?= view('components/form/submit_button') ?>
        </form>
    </div>
</div>

<script>
    // _itemLabSelected: { id_item_lab: { ...item, parameter: { id_parameter: {...param, checked: bool} } } }
    const _urlParameter = '<?= site_url('laboratorium/referensi-parameter-pemeriksaan-lab/modal/list') ?>';
    const itemTerpilihAwal = <?= json_encode($item_terpilih ?? []) ?>;

    document.addEventListener('DOMContentLoaded', function () {
        if (itemTerpilihAwal.length > 0) {
            itemTerpilihAwal.forEach(item => {
                // item dari controller sudah punya property 'parameter' berisi yang dipilih
                const selectedParamIds = new Set((item.parameter ?? []).map(p => String(p.id_parameter)));
                _itemLabSelected[item.id_item_pemeriksaan] = {
                    id_item_lab:  item.id_item_pemeriksaan,
                    kode_periksa: item.kode_periksa,
                    nama_item:    item.nama_item,
                    tarif:        item.tarif,
                    // semua parameter dari ref akan di-fetch, tapi yang sudah dipilih di-mark checked
                    _selectedParamIds: selectedParamIds,
                    parameter: {},
                };
            });
            // Fetch parameter untuk tiap item lalu render
            const fetchPromises = itemTerpilihAwal.map(item =>
                fetchParameterForItem(item.id_item_pemeriksaan)
            );
            Promise.all(fetchPromises).then(() => {
                renderItemLabTerpilih(Object.values(_itemLabSelected));
            });
        }
    });

    // Callback dari modalitempemeriksaanlab
    function onItemLabSelected(selected) {
        const newItems = selected.filter(item => !_itemLabSelected[item.id_item_lab]);
        newItems.forEach(item => {
            _itemLabSelected[item.id_item_lab] = {
                ...item,
                _selectedParamIds: new Set(),
                parameter: {},
            };
        });

        const fetchPromises = newItems.map(item => fetchParameterForItem(item.id_item_lab));
        Promise.all(fetchPromises).then(() => {
            renderItemLabTerpilih(Object.values(_itemLabSelected));
        });
    }

    function fetchParameterForItem(idItemLab) {
        return fetch(`${_urlParameter}?id_item_lab=${idItemLab}`)
            .then(res => res.json())
            .then(result => {
                const params = result.data || [];
                if (_itemLabSelected[idItemLab]) {
                    const selectedIds = _itemLabSelected[idItemLab]._selectedParamIds ?? new Set();
                    const isSingle = params.length === 1;
                    params.forEach(p => {
                        _itemLabSelected[idItemLab].parameter[p.id_parameter] = {
                            ...p,
                            checked: isSingle || selectedIds.has(String(p.id_parameter)),
                        };
                    });
                }
            })
            .catch(() => {});
    }

    function renderItemLabTerpilih(selected) {
        const tbody     = document.getElementById('itemLabTerpilihBody');
        const hiddenDiv = document.getElementById('hiddenItemLabInputs');

        tbody.innerHTML     = '';
        hiddenDiv.innerHTML = '';

        if (selected.length === 0) {
            tbody.innerHTML = `
                <tr id="emptyItemLabRow">
                    <td colspan="7" class="text-center py-6 text-gray-400 italic dark:text-gray-500">
                        Belum ada item dipilih
                    </td>
                </tr>`;
            return;
        }

        selected.forEach(item => {
            const idItem = item.id_item_lab;
            const tarif  = new Intl.NumberFormat('id-ID').format(item.tarif);

            // Baris item
            const trItem = document.createElement('tr');
            trItem.className = 'bg-gray-50 dark:bg-slate-800 font-medium';
            trItem.innerHTML = `
                <td class="p-3 border text-center dark:border-gray-700"></td>
                <td class="p-3 border text-center dark:border-gray-700">${item.kode_periksa}</td>
                <td class="p-3 border dark:border-gray-700 font-semibold">${item.nama_item}</td>
                <td class="p-3 border dark:border-gray-700"></td>
                <td class="p-3 border dark:border-gray-700"></td>
                <td class="p-3 border text-right dark:border-gray-700">Rp ${tarif}</td>
                <td class="p-3 border text-center dark:border-gray-700">
                    <button type="button" onclick="hapusItemLab(${idItem}, this)"
                            class="text-red-600 hover:underline text-sm dark:text-red-400">Hapus</button>
                </td>`;
            tbody.appendChild(trItem);

            // Hidden input untuk item
            const inputItem = document.createElement('input');
            inputItem.type       = 'hidden';
            inputItem.name       = 'id_item[]';
            inputItem.value      = idItem;
            inputItem.dataset.id = idItem;
            hiddenDiv.appendChild(inputItem);

            // Baris parameter
            const params = Object.values(item.parameter ?? {});
            if (params.length === 0) {
                const trEmpty = document.createElement('tr');
                trEmpty.innerHTML = `
                    <td colspan="7" class="px-8 py-2 text-xs text-gray-400 italic dark:text-gray-500">
                        Tidak ada parameter tersedia
                    </td>`;
                tbody.appendChild(trEmpty);
            } else {
                const isSingle = params.length === 1;
                params.forEach(param => {
                    const trParam = document.createElement('tr');
                    trParam.className = 'hover:bg-emerald-50 dark:hover:bg-slate-700';
                    // Jika hanya 1 parameter: auto terpilih, checkbox disabled, nilai tetap dikirim via hidden input
                    const checkboxCell = isSingle
                        ? `<td class="p-2 border text-center dark:border-gray-700 pl-8">
                               <input type="hidden" name="id_parameter[${idItem}][]" value="${param.id_parameter}">
                               <input type="checkbox" checked disabled class="opacity-50 cursor-not-allowed">
                           </td>`
                        : `<td class="p-2 border text-center dark:border-gray-700 pl-8">
                               <input type="checkbox"
                                      name="id_parameter[${idItem}][]"
                                      value="${param.id_parameter}"
                                      data-item="${idItem}"
                                      data-param="${param.id_parameter}"
                                      ${param.checked ? 'checked' : ''}
                                      class="cursor-pointer">
                           </td>`;
                    trParam.innerHTML = `
                        ${checkboxCell}
                        <td class="p-2 border text-center text-xs text-gray-400 dark:border-gray-700"></td>
                        <td class="p-2 border pl-8 dark:border-gray-700 text-xs">${param.nama_parameter}</td>
                        <td class="p-2 border dark:border-gray-700 text-xs">${param.satuan ?? '-'}</td>
                        <td class="p-2 border dark:border-gray-700 text-xs">${param.nilai_rujukan ?? '-'}</td>
                        <td class="p-2 border dark:border-gray-700 text-xs"></td>
                        <td class="p-2 border dark:border-gray-700"></td>`;
                    tbody.appendChild(trParam);
                });
            }
        });
    }

    function hapusItemLab(idItem, btn) {
        delete _itemLabSelected[idItem];

        // Hapus baris item + semua baris parameter di bawahnya
        // Parameter rows menyusul setelah baris item sampai ketemu baris item berikutnya
        const row = btn.closest('tr');
        let next  = row.nextElementSibling;
        while (next && !next.querySelector('button[onclick^="hapusItemLab"]')) {
            const toRemove = next;
            next = next.nextElementSibling;
            toRemove.remove();
        }
        row.remove();

        // Hapus hidden input item
        const hiddenDiv = document.getElementById('hiddenItemLabInputs');
        const input = hiddenDiv.querySelector(`input[data-id="${idItem}"]`);
        if (input) input.remove();

        const tbody = document.getElementById('itemLabTerpilihBody');
        if (tbody.children.length === 0) {
            tbody.innerHTML = `
                <tr id="emptyItemLabRow">
                    <td colspan="7" class="text-center py-6 text-gray-400 italic dark:text-gray-500">
                        Belum ada item dipilih
                    </td>
                </tr>`;
        }
    }

    function showError(fieldId, msg) {
        const errEl = document.getElementById('err_' + fieldId);
        if (errEl) { errEl.textContent = msg; errEl.classList.remove('hidden'); }
        const inputEl = document.getElementById(fieldId);
        if (inputEl) inputEl.classList.add('!border-red-500');
    }

    function clearError(fieldId) {
        const errEl = document.getElementById('err_' + fieldId);
        if (errEl) { errEl.textContent = ''; errEl.classList.add('hidden'); }
        const inputEl = document.getElementById(fieldId);
        if (inputEl) inputEl.classList.remove('!border-red-500');
    }

    function autofillRegistrasi(item) {
        document.getElementById('nomor_reg_display').value   = item.nomor_reg   ?? '';
        document.getElementById('nomor_rm_display').value    = item.nomor_rm    ?? '';
        document.getElementById('nama_pasien').value         = item.nama        ?? '';
        document.getElementById('kode_dokter').value         = item.kode_dokter ?? '';
        document.getElementById('nama_dokter').value         = item.nama_dokter ?? '';
        document.getElementById('nomor_reg').value           = item.nomor_reg   ?? '';
        document.getElementById('id_dokter_perujuk').value   = item.id_dokter   ?? '';
        clearError('nomor_reg_display');
        clearError('kode_dokter');
    }

    function autofillFields(item) {
        document.getElementById('kode_dokter').value         = item.kode_dokter ?? '';
        document.getElementById('nama_dokter').value         = item.nama_dokter ?? '';
        document.getElementById('id_dokter_perujuk').value   = item.id_dokter   ?? '';
        clearError('kode_dokter');
    }

    function validateForm() {
        let valid = true;

        clearError('nomor_reg_display');
        clearError('kode_dokter');
        clearError('items');

        if (!document.getElementById('nomor_reg').value) {
            showError('nomor_reg_display', 'Registrasi pasien wajib dipilih.');
            valid = false;
        }
        if (!document.getElementById('id_dokter_perujuk').value) {
            showError('kode_dokter', 'Dokter perujuk wajib dipilih.');
            valid = false;
        }
        const hiddenDiv = document.getElementById('hiddenItemLabInputs');
        if (!hiddenDiv.querySelector('input[name="id_item[]"]')) {
            showError('items', 'Pilih minimal satu item pemeriksaan.');
            valid = false;
        } else {
            hiddenDiv.querySelectorAll('input[name="id_item[]"]').forEach(itemInput => {
                const idItem    = itemInput.value;
                const hasHidden  = document.querySelector(`input[type="hidden"][name="id_parameter[${idItem}][]"]`);
                const hasChecked = document.querySelector(`input[type="checkbox"][name="id_parameter[${idItem}][]"]:checked`);
                if (!hasHidden && !hasChecked) {
                    const namaItem = _itemLabSelected[idItem]?.nama_item ?? `Item #${idItem}`;
                    showError('items', `${namaItem} belum memiliki parameter yang dipilih.`);
                    valid = false;
                }
            });
        }

        if (!valid) return false;

        if (!document.getElementById('myForm').checkValidity()) {
            document.getElementById('myForm').reportValidity();
            return false;
        }

        const submitButton = document.getElementById('submitButton');
        if (submitButton) {
            submitButton.disabled  = true;
            submitButton.innerHTML = 'Menyimpan...';
        }
        return true;
    }
</script>

<?= $this->endSection(); ?>