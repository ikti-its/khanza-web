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

            <!-- Section: Data Spesimen (khusus PA) -->
            <div class="mt-6 mb-5">
                <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4 pb-1 border-b dark:border-gray-700">
                    Data Spesimen
                </h4>

                <?php
                $inputClass    = 'border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full dark:border-gray-600 dark:text-white bg-slate-50';
                $readonlyClass = 'border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full dark:border-gray-600 dark:text-white bg-gray-100 cursor-not-allowed';
                $labelLeft     = 'block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4';
                $labelRight    = 'block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5';
                ?>

                <!-- Tgl Pengambilan | Metode Diperoleh -->
                <div class="mb-5 sm:block md:flex items-center">
                    <label class="<?= $labelLeft ?>">Tanggal Pengambilan Bahan</label>
                    <input type="datetime-local" name="tgl_pengambilan_bahan"
                           value="<?= esc($baris['tgl_pengambilan_bahan'] ?? '') ?>"
                           class="<?= $inputClass ?> lg:w-1/4">

                    <label class="<?= $labelRight ?>">Metode Diperoleh</label>
                    <input type="text" name="metode_diperoleh"
                           value="<?= esc($baris['metode_diperoleh'] ?? '') ?>"
                           placeholder="Contoh: Biopsi, Eksisi..."
                           class="<?= $inputClass ?> lg:w-1/4">
                </div>

                <!-- Lokasi Jaringan | Bahan Pengawet -->
                <div class="mb-5 sm:block md:flex items-center">
                    <label class="<?= $labelLeft ?>">Lokasi Jaringan</label>
                    <input type="text" name="lokasi_jaringan"
                           value="<?= esc($baris['lokasi_jaringan'] ?? '') ?>"
                           placeholder="Contoh: Lambung, Kolon..."
                           class="<?= $inputClass ?> lg:w-1/4">

                    <label class="<?= $labelRight ?>">Bahan Pengawet</label>
                    <input type="text" name="bahan_pengawet"
                           value="<?= esc($baris['bahan_pengawet'] ?? '') ?>"
                           placeholder="Contoh: Formalin 10%..."
                           class="<?= $inputClass ?> lg:w-1/4">
                </div>
            </div>

            <!-- Section: Riwayat PA Sebelumnya -->
            <div class="mt-2 mb-5">
                <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4 pb-1 border-b dark:border-gray-700">
                    Riwayat PA Sebelumnya
                    <span class="text-xs font-normal text-gray-400 ml-1">(opsional)</span>
                </h4>

                <!-- Lokasi Lab | Tgl Sebelumnya -->
                <div class="mb-5 sm:block md:flex items-center">
                    <label class="<?= $labelLeft ?>">Lokasi Lab Sebelumnya</label>
                    <input type="text" name="riwayat_lokasi_lab"
                           value="<?= esc($baris['riwayat_lokasi_lab'] ?? '') ?>"
                           placeholder="Nama lab / RS sebelumnya..."
                           class="<?= $inputClass ?> lg:w-1/4">

                    <label class="<?= $labelRight ?>">Tanggal Pemeriksaan Sebelumnya</label>
                    <input type="date" name="riwayat_tgl_sebelumnya"
                           value="<?= esc($baris['riwayat_tgl_sebelumnya'] ?? '') ?>"
                           class="<?= $inputClass ?> lg:w-1/4">
                </div>

                <!-- No. PA Sebelumnya | Diagnosa Sebelumnya -->
                <div class="mb-5 sm:block md:flex items-center">
                    <label class="<?= $labelLeft ?>">No. PA Sebelumnya</label>
                    <input type="text" name="riwayat_no_pa_sebelumnya"
                           value="<?= esc($baris['riwayat_no_pa_sebelumnya'] ?? '') ?>"
                           placeholder="Nomor PA dari lab sebelumnya..."
                           class="<?= $inputClass ?> lg:w-1/4">

                    <label class="<?= $labelRight ?>">Diagnosa Sebelumnya</label>
                    <input type="text" name="riwayat_diagnosa_sebelumnya"
                           value="<?= esc($baris['riwayat_diagnosa_sebelumnya'] ?? '') ?>"
                           placeholder="Diagnosa dari pemeriksaan sebelumnya..."
                           class="<?= $inputClass ?> lg:w-1/4">
                </div>
            </div>

            <!-- Item Pemeriksaan PA -->
            <div class="mb-5 mt-4">
                <div class="flex justify-between items-center mb-3">
                    <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                        Item Pemeriksaan <span class="text-red-500">*</span>
                    </h4>
                    <button type="button"
                            onclick="open_modalItemPemeriksaanLab(<?= ID_KATEGORI_PA ?>, 'Pilih Item Patologi Anatomi')"
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
                                <th class="p-3 border text-center dark:border-gray-700">Kode</th>
                                <th class="p-3 border text-left dark:border-gray-700">Nama Item</th>
                                <th class="p-3 border text-right dark:border-gray-700">Tarif</th>
                                <th class="p-3 border text-center dark:border-gray-700">Hapus</th>
                            </tr>
                        </thead>
                        <tbody id="itemLabTerpilihBody">
                            <tr id="emptyItemLabRow">
                                <td colspan="4" class="text-center py-6 text-gray-400 italic dark:text-gray-500">
                                    Belum ada item dipilih
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div id="hiddenItemLabInputs"></div>
            </div>

            <?= view('components/form/submit_button') ?>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const itemTerpilih = <?= json_encode($item_terpilih ?? []) ?>;
        if (itemTerpilih.length > 0) {
            itemTerpilih.forEach(item => {
                _itemLabSelected[item.id_item_pemeriksaan] = item;
            });
            renderItemLabTerpilih(Object.values(_itemLabSelected));
        }
    });

    function onItemLabSelected(selected) {
        selected.forEach(item => {
            _itemLabSelected[item.id_item_lab] = item;
        });
        renderItemLabTerpilih(Object.values(_itemLabSelected));
    }

    function renderItemLabTerpilih(selected) {
        const tbody     = document.getElementById('itemLabTerpilihBody');
        const hiddenDiv = document.getElementById('hiddenItemLabInputs');

        tbody.innerHTML     = '';
        hiddenDiv.innerHTML = '';

        if (selected.length === 0) {
            tbody.innerHTML = `
                <tr id="emptyItemLabRow">
                    <td colspan="4" class="text-center py-6 text-gray-400 italic dark:text-gray-500">
                        Belum ada item dipilih
                    </td>
                </tr>`;
            return;
        }

        selected.forEach(item => {
            const tarif = new Intl.NumberFormat('id-ID').format(item.tarif);

            const tr = document.createElement('tr');
            tr.className = 'hover:bg-gray-50 dark:hover:bg-slate-800';
            tr.innerHTML = `
                <td class="p-3 border text-center dark:border-gray-700">${item.kode_periksa}</td>
                <td class="p-3 border dark:border-gray-700">${item.nama_item}</td>
                <td class="p-3 border text-right dark:border-gray-700">Rp ${tarif}</td>
                <td class="p-3 border text-center dark:border-gray-700">
                    <button type="button" onclick="hapusItemLab(${item.id_item_lab}, this)"
                            class="text-red-600 hover:underline text-sm dark:text-red-400">Hapus</button>
                </td>`;
            tbody.appendChild(tr);

            const input = document.createElement('input');
            input.type       = 'hidden';
            input.name       = 'id_item[]';
            input.value      = item.id_item_lab;
            input.dataset.id = item.id_item_lab;
            hiddenDiv.appendChild(input);
        });
    }

    function hapusItemLab(idItem, btn) {
        delete _itemLabSelected[idItem];
        btn.closest('tr').remove();

        const hiddenDiv = document.getElementById('hiddenItemLabInputs');
        const input = hiddenDiv.querySelector(`input[data-id="${idItem}"]`);
        if (input) input.remove();

        const tbody = document.getElementById('itemLabTerpilihBody');
        if (tbody.children.length === 0) {
            tbody.innerHTML = `
                <tr id="emptyItemLabRow">
                    <td colspan="4" class="text-center py-6 text-gray-400 italic dark:text-gray-500">
                        Belum ada item dipilih
                    </td>
                </tr>`;
        }
    }

    function autofillRegistrasi(item) {
        document.getElementById('nomor_reg_display').value   = item.nomor_reg   ?? '';
        document.getElementById('nomor_rm_display').value    = item.nomor_rm    ?? '';
        document.getElementById('nama_pasien').value         = item.nama        ?? '';
        document.getElementById('kode_dokter').value         = item.kode_dokter ?? '';
        document.getElementById('nama_dokter').value         = item.nama_dokter ?? '';
        document.getElementById('nomor_reg').value           = item.nomor_reg   ?? '';
        document.getElementById('id_dokter_perujuk').value = item.id_dokter   ?? '';
    }

    function autofillFields(item) {
        document.getElementById('kode_dokter').value          = item.kode_dokter ?? '';
        document.getElementById('nama_dokter').value          = item.nama_dokter ?? '';
        document.getElementById('id_dokter_perujuk').value  = item.id_dokter   ?? '';
    }

    function validateForm() {
        if (!document.getElementById('nomor_reg').value) {
            alert('Silakan pilih registrasi pasien terlebih dahulu.');
            return false;
        }
        if (!document.getElementById('id_dokter_perujuk').value) {
            alert('Silakan pilih dokter perujuk terlebih dahulu.');
            return false;
        }
        const hiddenDiv = document.getElementById('hiddenItemLabInputs');
        if (!hiddenDiv.querySelector('input[name="id_item[]"]')) {
            alert('Pilih minimal satu item pemeriksaan.');
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