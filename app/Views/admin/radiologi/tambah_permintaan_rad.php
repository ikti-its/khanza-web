<?php
$inputClass    = 'border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full dark:border-gray-600 dark:text-white cursor-pointer bg-slate-50';
$readonlyClass = 'border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full dark:border-gray-600 dark:text-white bg-gray-100 cursor-not-allowed';
$labelLeft     = 'block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4';
$labelRight    = 'block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5';
$btnClass      = 'inline-flex justify-center items-center p-2 text-sm font-medium text-white bg-blue-600 rounded-lg border border-transparent hover:bg-blue-700 focus:outline-none transition-all w-10 h-[38px] flex-shrink-0 shadow-sm';
$searchIcon    = '<svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>';
?>

<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<?= $this->include('components/modal/modalregistrasi') ?>
<?= $this->include('components/modal/modaldokter') ?>
<?= $this->include('components/modal/modalitemrad') ?>

<div class="max-w-[85rem] py-6 lg:py-3 px-8 mx-auto">
    <div class="bg-white rounded-xl shadow-sm p-4 sm:p-7 dark:bg-slate-900 border border-gray-100 dark:border-gray-800">
        <?= view('components/form/judul', ['judul' => $judul]) ?>

        <form action="<?= $modul_path . $form_action ?>" id="myForm" onsubmit="return validateForm()" method="post">
            <?= csrf_field() ?>

            <input type="hidden" name="no_permintaan"       id="no_permintaan"       value="<?= esc($no_permintaan ?? '') ?>">
            <input type="hidden" name="nomor_reg"           id="nomor_reg"           value="<?= esc($baris['nomor_reg'] ?? '') ?>">
            <input type="hidden" name="kode_dokter_perujuk" id="kode_dokter_perujuk" value="<?= esc($baris['kode_dokter_perujuk'] ?? '') ?>">

            <?php $isEdit = str_contains($judul, 'Ubah'); ?>
            
            <div class="mb-5 sm:block md:flex items-center">
                <label class="<?= $labelLeft ?>">No. Permintaan</label>
                <input type="text" value="<?= esc($no_permintaan ?? '') ?>"
                    readonly
                    class="<?= $readonlyClass ?> lg:w-1/4">
                    
                <label class="<?= $labelRight ?>">
                    No. Registrasi <span class="text-red-500">*</span>
                </label>
                <div class="flex gap-x-2 lg:w-1/4">
                    <input type="text" id="nomor_reg_display"
                           value="<?= esc($baris['nomor_reg'] ?? '') ?>"
                           readonly required
                           placeholder="Klik cari registrasi..."
                           <?= $isEdit ? '' : 'onclick="open_modalRegistrasi()"' ?>
                           class="<?= $isEdit ? $readonlyClass : $inputClass ?>">
                        <?php if (!$isEdit) : ?>
                            <button type="button" onclick="open_modalRegistrasi()" class="<?= $btnClass ?>">
                                <?= $searchIcon ?>
                            </button>
                        <?php endif; ?>
                </div>
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="<?= $labelLeft ?>">No. Rekam Medis</label>
                <input type="text" id="nomor_rm_display"
                       value="<?= esc($baris['nomor_rm'] ?? '') ?>"
                       readonly placeholder="Terisi otomatis..."
                       class="<?= $readonlyClass ?> lg:w-1/4">

                <label class="<?= $labelRight ?>">Nama Pasien</label>
                <input type="text" id="nama_pasien"
                       value="<?= esc($baris['nama'] ?? '') ?>"
                       readonly placeholder="Terisi otomatis..."
                       class="<?= $readonlyClass ?> lg:w-1/4">
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="<?= $labelLeft ?>">
                    Kode Dokter Perujuk <span class="text-red-500">*</span>
                </label>
                <div class="flex gap-x-2 lg:w-1/4">
                    <input type="text" id="kode_dokter"
                           value="<?= esc($baris['kode_dokter_perujuk'] ?? '') ?>"
                           readonly required
                           placeholder="Klik cari dokter..."
                           onclick="open_modalDokter()"
                           class="<?= $inputClass ?>">
                    <button type="button" onclick="open_modalDokter()" class="<?= $btnClass ?>">
                        <?= $searchIcon ?>
                    </button>
                </div>

                <label class="<?= $labelRight ?>">Nama Dokter Perujuk</label>
                <input type="text" id="nama_dokter"
                       value="<?= esc($baris['nama_dokter'] ?? '') ?>"
                       readonly placeholder="Terisi otomatis..."
                       class="<?= $readonlyClass ?> lg:w-1/4">
            </div>

            <?= view('components/form/isian', ['konfig' => $konfig, 'baris' => $baris]) ?>
            
            <div class="mb-5 mt-4">
                <div class="flex justify-between items-center mb-3">
                    <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                        Item Radiologi <span class="text-red-500">*</span>
                    </h4>
                    <button type="button" onclick="open_modalItemRad()"
                            class="inline-flex items-center gap-x-1.5 py-2 px-3 text-sm font-semibold rounded-lg border border-transparent bg-[#0A2D27] text-[#ACF2E7] hover:bg-[#13594E] transition-all shadow-sm">
                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                        </svg>
                        Pilih Item
                    </button>
                </div>
 
                <div id="itemRadTerpilihContainer" class="border rounded-xl overflow-hidden dark:border-gray-700">
                    <table class="w-full text-sm text-gray-700 dark:text-gray-300">
                        <thead style="background-color: #E6F2EF;" class="text-gray-800 font-semibold">
                            <tr>
                                <th class="p-3 border text-center dark:border-gray-700">Kode</th>
                                <th class="p-3 border text-left dark:border-gray-700">Nama Pemeriksaan</th>
                                <th class="p-3 border text-right dark:border-gray-700">Tarif Dasar</th>
                                <th class="p-3 border text-center dark:border-gray-700">Hapus</th>
                            </tr>
                        </thead>
                        <tbody id="itemRadTerpilihBody">
                            <tr id="emptyItemRow">
                                <td colspan="4" class="text-center py-6 text-gray-400 italic dark:text-gray-500">
                                    Belum ada item dipilih
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div id="hiddenItemInputs"></div>
            </div>

            <?= view('components/form/submit_button') ?>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const itemTerpilih = <?= json_encode($item_terpilih ?? []) ?>;
        console.log('item terpilih:', itemTerpilih);
        console.log('_itemRadSelected:', _itemRadSelected);
        if (itemTerpilih.length > 0) {
            itemTerpilih.forEach(item => {
                _itemRadSelected[item.id_item] = item;
            });
            renderItemRadTerpilih(Object.values(_itemRadSelected));
        }
    });

    function autofillRegistrasi(item) {
        document.getElementById('nomor_reg_display').value = item.nomor_reg   ?? '';
        document.getElementById('nomor_rm_display').value  = item.nomor_rm    ?? '';
        document.getElementById('nama_pasien').value       = item.nama        ?? '';
        document.getElementById('kode_dokter').value       = item.kode_dokter ?? '';
        document.getElementById('nama_dokter').value       = item.nama_dokter ?? '';
        document.getElementById('nomor_reg').value           = item.nomor_reg   ?? '';
        document.getElementById('kode_dokter_perujuk').value = item.kode_dokter ?? '';
    }

    function autofillFields(item) {
        document.getElementById('kode_dokter').value         = item.kode_dokter ?? '';
        document.getElementById('nama_dokter').value         = item.nama_dokter ?? '';
        document.getElementById('kode_dokter_perujuk').value = item.kode_dokter ?? '';
    }
    
    function renderItemRadTerpilih(selected) {
        const tbody       = document.getElementById('itemRadTerpilihBody');
        const hiddenDiv   = document.getElementById('hiddenItemInputs');
 
        // Hapus baris lama (kecuali empty row)
        tbody.innerHTML = '';
        hiddenDiv.innerHTML = '';
 
        if (selected.length === 0) {
            tbody.innerHTML = `
                <tr id="emptyItemRow">
                    <td colspan="4" class="text-center py-6 text-gray-400 italic dark:text-gray-500">
                        Belum ada item dipilih
                    </td>
                </tr>`;
            return;
        }
 
        selected.forEach(item => {
            const tarif = new Intl.NumberFormat('id-ID').format(item.tarif_dasar);
 
            // Baris tabel
            const tr = document.createElement('tr');
            tr.className = 'hover:bg-gray-50 dark:hover:bg-slate-800';
            tr.innerHTML = `
                <td class="p-3 border text-center dark:border-gray-700">${item.kode_periksa}</td>
                <td class="p-3 border dark:border-gray-700">${item.nama_pemeriksaan}</td>
                <td class="p-3 border text-right dark:border-gray-700">Rp ${tarif}</td>
                <td class="p-3 border text-center dark:border-gray-700">
                    <button type="button" onclick="hapusItemRad(${item.id_item}, this)"
                            class="text-red-600 hover:underline text-sm dark:text-red-400">Hapus</button>
                </td>`;
            tbody.appendChild(tr);
 
            // Hidden input
            const input = document.createElement('input');
            input.type  = 'hidden';
            input.name  = 'id_item[]';
            input.value = item.id_item;
            input.dataset.id = item.id_item;
            hiddenDiv.appendChild(input);
        });
    }

    function hapusItemRad(idItem, btn) {
        // Hapus dari _itemRadSelected
        delete _itemRadSelected[idItem];
 
        // Hapus baris tabel
        btn.closest('tr').remove();
 
        // Hapus hidden input
        const hiddenDiv = document.getElementById('hiddenItemInputs');
        const input = hiddenDiv.querySelector(`input[data-id="${idItem}"]`);
        if (input) input.remove();
 
        // Tampilkan empty row jika tidak ada lagi
        const tbody = document.getElementById('itemRadTerpilihBody');
        if (tbody.children.length === 0) {
            tbody.innerHTML = `
                <tr id="emptyItemRow">
                    <td colspan="4" class="text-center py-6 text-gray-400 italic dark:text-gray-500">
                        Belum ada item dipilih
                    </td>
                </tr>`;
        }
 
        updateSelectedCount();
    }

    function validateForm() {
        if (!document.getElementById('nomor_reg').value) {
            alert('Silakan pilih registrasi pasien terlebih dahulu.');
            return false;
        }
        if (!document.getElementById('kode_dokter_perujuk').value) {
            alert('Silakan pilih dokter perujuk terlebih dahulu.');
            return false;
        }
        const hiddenDiv = document.getElementById('hiddenItemInputs');
        if (!hiddenDiv.querySelector('input[name="id_item[]"]')) {
            alert('Pilih minimal satu item radiologi.');
            return false;
        }
        const requiredFields = document.querySelectorAll('select[required], input[required]');
        for (const field of requiredFields) {
            if (!field.value) {
                alert('Isi semua field yang wajib diisi.');
                field.focus();
                return false;
            }
        }
        const submitButton = document.getElementById('submitButton');
        if (submitButton) {
            submitButton.disabled = true;
            submitButton.innerHTML = 'Menyimpan...';
        }
        return true;
    }
</script>

<?= $this->endSection(); ?>