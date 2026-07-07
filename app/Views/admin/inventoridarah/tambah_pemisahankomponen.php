<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<?= $this->include('components/modal/modalpetugas') ?>

<div class="max-w-[85rem] py-6 lg:py-3 px-8 mx-auto">
    <div class="bg-white rounded-xl shadow p-4 sm:p-7 dark:bg-slate-900">
        <?= view('components/form/judul', [
            'judul' => $judul
        ]) ?>
        
        <form action="<?= $modul_path . $form_action ?>" id="formPemisahan" onsubmit="return validateForm()" method="post">
            <?= csrf_field() ?>

            <input type="hidden" name="id_pengambilan_darah" id="id_pengambilan_darah" value="<?= $baris['id_pengambilan_darah'] ?? '' ?>" required>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">
                    Nomor Pengambilan<span class="text-red-600">*</span>
                </label>
                <div class="w-full lg:w-1/4">
                    <input type="text" id="nomor_pengambilan" name="nomor_pengambilan" readonly required
                           value="<?= $baris['nomor_pengambilan'] ?? '' ?>"
                           placeholder="Terisi otomatis..." 
                           class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full dark:border-gray-600 dark:text-white bg-gray-100 cursor-not-allowed">
                </div>

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">
                    Tanggal Pemisahan<span class="text-red-600">*</span>
                </label>
                <input type="date" name="tanggal_pemisahan"
                       value="<?= old('tanggal_pemisahan', $baris['tanggal_pemisahan'] ?? date('Y-m-d')) ?>"
                       max="<?= date('Y-m-d') ?>"
                       class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" required>
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">
                    Shift<span class="text-red-600">*</span>
                </label>
                <select name="id_shift" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" required>
                    <option value="">-- Pilih --</option>
                    <?php 
                    $optionsShift = [];
                    foreach ($konfig as $field) {
                        if ($field[2] === 'id_shift') {
                            $optionsShift = $field[5] ?? [];
                            break;
                        }
                    }
                    foreach ($optionsShift as $opt) : 
                        $valLama = old('id_shift', $baris['id_shift'] ?? '');
                        $selected = ((string)$valLama === (string)$opt[1]) ? 'selected' : '';
                    ?>
                        <option value="<?= $opt[1] ?>" <?= $selected ?>><?= $opt[0] ?></option>
                    <?php endforeach; ?>
                </select>

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">
                    Petugas<span class="text-red-600">*</span>
                </label>
                <input type="hidden" name="id_petugas" id="id_petugas"
                       value="<?= old('id_petugas', $baris['id_petugas'] ?? '') ?>" required>
                
                <div class="w-full lg:w-1/4 flex gap-x-2">
                    <input type="text" id="nama_petugas" name="nama_petugas" readonly required
                           value="<?= old('nama_petugas', $baris['nama_petugas'] ?? '') ?>"
                           placeholder="Klik cari..."
                           onclick="open_modalPetugas()"
                           class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full dark:border-gray-600 dark:text-white cursor-pointer bg-slate-50">
                    
                    <button type="button" onclick="open_modalPetugas()"
                            class="inline-flex justify-center items-center p-2 text-sm font-medium text-white bg-blue-600 rounded-lg border border-transparent hover:bg-blue-700 focus:outline-none transition-all w-10 h-[38px] flex-shrink-0 shadow-sm">
                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </button>
                </div>
            </div>

            <div class="mt-8 mb-4 sm:block md:flex items-center">
                <label class="block text-sm font-medium text-gray-900 dark:text-white md:w-1/4">
                    Hasil Komponen Darah<span class="text-red-600">*</span>
                </label>
                <?php if (!empty($batas_komponen['batas'])) : ?>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        Jenis Bag: <span class="font-semibold"><?= esc($batas_komponen['nama_jenis_bag']) ?></span>
                        &mdash; maksimal pilih <span class="font-semibold"><?= $batas_komponen['batas'] ?></span> komponen.
                    </p>
                <?php endif; ?>
            </div>
            
            <div class="overflow-hidden border border-gray-200 rounded-xl bg-white dark:bg-slate-900 dark:border-gray-700 mb-6">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="bg-gray-100 text-sm text-gray-700 dark:bg-slate-800 dark:text-gray-400">
                        <tr>
                            <th class="p-3 w-12 text-center">Pilih</th>
                            <th class="p-3 w-24">Kode</th>
                            <th class="p-3">Nama Komponen</th>
                            <th class="p-3 w-32 text-center">Masa Berlaku (Hari)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($master_komponen)) : ?>
                            <?php foreach ($master_komponen as $komponen) : ?>
                                <tr class="border-b text-sm dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-slate-800/30">
                                    <td class="p-3 text-center">
                                        <input type="checkbox" name="id_komponen[]" value="<?= $komponen['id_komponen'] ?>"
                                               style="accent-color: #2563eb;"
                                               class="w-4 h-4 bg-gray-100 border-gray-300 rounded cursor-pointer">
                                    </td>
                                    <td class="p-3 font-semibold text-gray-900 dark:text-white">
                                        <?= $komponen['kode_komponen'] ?>
                                    </td>
                                    <td class="p-3 text-gray-700 dark:text-gray-300">
                                        <?= $komponen['nama_komponen'] ?>
                                    </td>
                                    <td class="p-3 text-center text-gray-600 dark:text-gray-400">
                                        <?= $komponen['masa_berlaku_hari'] ?> Hari
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="4" class="text-center py-6 text-gray-400 italic">Data master komponen darah tidak ditemukan.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <h4 class="text-xl font-bold text-gray-800 dark:text-white mt-10 mb-4">Penggunaan BHP</h4>

            <div class="flex border-b border-gray-200 dark:border-gray-700 mb-5 text-sm font-medium">
                <button type="button" id="tabMedis" onclick="switchBhpTab('medis')" 
                        class="w-1/2 py-2.5 text-center border-b-2 border-gray-600 text-gray-800 font-bold outline-none focus:outline-none focus:ring-0 transition-all duration-150">
                    BHP Medis
                </button>
                <button type="button" id="tabNonMedis" onclick="switchBhpTab('nonmedis')" 
                        class="w-1/2 py-2.5 text-center border-b-2 border-transparent text-gray-900 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300 font-medium outline-none focus:outline-none focus:ring-0 transition-all duration-150">
                    BHP Non Medis
                </button>
            </div>

            <div class="space-y-2 mb-4">
                <div class="flex gap-x-3">
                    <select id="selectBarang" class="border border-gray-300 text-sm rounded-lg p-2 flex-1 bg-white dark:bg-slate-900 dark:border-gray-700 dark:text-white focus:ring-emerald-500 focus:border-emerald-500">
                        <option value="">-- Pilih Barang --</option>
                    </select>
                    <button type="button" onclick="addBhpItem()"
                            class="inline-flex items-center gap-x-1.5 py-2 px-3 text-sm font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700 shadow-sm transition-all flex-shrink-0">
                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                        </svg>
                        Tambah
                    </button>
                </div>
            </div>

            <div class="overflow-hidden border border-gray-200 rounded-xl bg-white dark:bg-slate-900 dark:border-gray-700 mb-6">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="bg-gray-100 text-sm text-gray-700 dark:bg-slate-800 dark:text-gray-400">
                        <tr>
                            <th class="p-3 w-1/4 text-center">Kode Barang</th>
                            <th class="p-3 w-2/5">Nama Barang</th>
                            <th class="p-3 w-1/5 text-center">Jumlah</th>
                            <th class="p-3 w-32 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="bhpTableBody">
                        <tr id="emptyBhpRow">
                            <td colspan="4" class="text-center py-6 text-gray-400 italic dark:text-gray-500">
                                Belum ada BHP terpilih
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <?= view('components/form/submit_button') ?>
        </form>
    </div>
</div>

<script>
    const masterMedis    = <?= json_encode($bhp_medis_options ?? []) ?>;
    const masterNonMedis = <?= json_encode($bhp_non_options ?? []) ?>;
    const batasKomponen  = <?= json_encode($batas_komponen['batas'] ?? null) ?>;
    const namaJenisBag   = <?= json_encode($batas_komponen['nama_jenis_bag'] ?? '-') ?>;
    let currentTab = 'medis';

    document.addEventListener("DOMContentLoaded", function() {
        populateBhpDropdown(masterMedis);

        const petugasId = "<?= $baris['id_petugas'] ?? '' ?>";
        if (petugasId !== '') {
            const savedItem = {
                id_petugas: petugasId,
                nama: "<?= $baris['nama_petugas'] ?? '' ?>"
            };
            autofillPetugas(savedItem);
        }


        const tabelBhpBody = document.getElementById('bhpTableBody');
        if (tabelBhpBody) {
            tabelBhpBody.addEventListener('keydown', function(e) {
                if (e.target.matches('input[type="number"]') && (e.key === '-' || e.key === 'Subtract')) {
                    e.preventDefault();
                }
            });

            tabelBhpBody.addEventListener('input', function(e) {
                if (e.target.matches('input[type="number"]')) {
                    let input = e.target;
                    
                    if (input.value === '') return; 

                    if (parseInt(input.value) < 1) {
                        input.value = 1;
                        alert("Jumlah penggunaan BHP minimal adalah 1.");
                    }
                }
            });
        }
    });

    function switchBhpTab(type) {
        currentTab = type;
        const tabMedis = document.getElementById('tabMedis');
        const tabNonMedis = document.getElementById('tabNonMedis');

        if (type === 'medis') {
            tabMedis.className = "w-1/2 py-2.5 text-center border-b-2 border-gray-600 text-gray-800 font-bold outline-none focus:outline-none focus:ring-0 transition-all duration-150";
            tabNonMedis.className = "w-1/2 py-2.5 text-center border-b-2 border-transparent text-gray-900 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300 font-medium outline-none focus:outline-none focus:ring-0 transition-all duration-150";
            populateBhpDropdown(masterMedis);
        } else {
            tabNonMedis.className = "w-1/2 py-2.5 text-center border-b-2 border-gray-600 text-gray-800 font-bold outline-none focus:outline-none focus:ring-0 transition-all duration-150";
            tabMedis.className = "w-1/2 py-2.5 text-center border-b-2 border-transparent text-gray-900 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300 font-medium outline-none focus:outline-none focus:ring-0 transition-all duration-150";
            populateBhpDropdown(masterNonMedis);
        }
    }

    function populateBhpDropdown(items) {
        const select = document.getElementById('selectBarang');
        if (!select) return;
        select.innerHTML = '<option value="">-- Pilih Barang --</option>';
        items.forEach(item => {
            const opt = document.createElement('option');
            opt.value = item.id_barang;
            opt.text  = `[${item.kode_barang || '-'}] ${item.nama_barang} (Stok: ${item.stok})`;
            select.appendChild(opt);
        });
    }

    function addBhpItem() {
        const select = document.getElementById('selectBarang');
        if (!select.value) {
            alert("Silakan pilih barang terlebih dahulu.");
            return;
        }

        const idBarangTerpilih = select.value;

        let kodeSnapshot  = '-';
        let namaSnapshot  = '';
        let hargaSnapshot = 0;
        let stokSnapshot  = 0;

        const daftarMaster = currentTab === 'medis' ? masterMedis : masterNonMedis;
        const selectedItem = daftarMaster.find(item => String(item.id_barang) === String(idBarangTerpilih));

        if (selectedItem) {
            namaSnapshot  = selectedItem.nama_barang;
            kodeSnapshot  = selectedItem.kode_barang;
            hargaSnapshot = selectedItem.harga;
            stokSnapshot  = parseInt(selectedItem.stok) || 0;
        }

        const emptyRow = document.getElementById('emptyBhpRow');
        if (emptyRow) emptyRow.remove();

        const tbody = document.getElementById('bhpTableBody');
        
        const existingInput = document.querySelector(`input[data-id="${idBarangTerpilih}"][data-type="${currentTab}"]`);
        if (existingInput) {
            existingInput.value = parseInt(existingInput.value) + 1;
            return;
        }

        const row = document.createElement('tr');
        row.className = "border-b text-sm dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-slate-800/30";
        
        const inputName = currentTab === 'medis' ? 'id_medis_donor' : 'id_penunjang_donor';
        const priceName = currentTab === 'medis' ? 'harga_medis' : 'harga_penunjang';

        row.innerHTML = `
            <td class="p-3 text-center font-medium text-gray-900 dark:text-white">
                ${kodeSnapshot}
            </td>
            <td class="p-3 font-medium text-gray-900 dark:text-white">
                ${namaSnapshot}
                <input type="hidden" name="${priceName}[${idBarangTerpilih}]" value="${hargaSnapshot}">
            </td>
            <td class="p-3 text-center">
                <input type="number"
                       name="${inputName}[${idBarangTerpilih}]"
                       data-id="${idBarangTerpilih}"
                       data-type="${currentTab}"
                       value="1"
                       min="1"
                       max="${stokSnapshot}"
                       oninput="cekBatasStokBhp(this, ${stokSnapshot}, '${namaSnapshot}')"
                       class="w-full max-w-[80px] text-center border border-gray-300 rounded p-1 dark:bg-slate-900 dark:text-white dark:border-gray-700">
            </td>
            <td class="p-3 text-center">
                <button type="button" onclick="removeBhpItem(this)" class="text-red-600 font-semibold hover:underline dark:text-red-400">Hapus</button>
            </td>
        `;
        tbody.appendChild(row);
    }

    function removeBhpItem(button) {
        button.closest('tr').remove();
        const tbody = document.getElementById('bhpTableBody');
        if (tbody.children.length === 0) {
            tbody.innerHTML = `
                <tr id="emptyBhpRow">
                    <td colspan="4" class="text-center py-6 text-gray-400 italic dark:text-gray-500">Belum ada BHP terpilih</td>
                </tr>`;
        }
    }

    function cekBatasStokBhp(inputElement, maksimalStok, namaBarang) {
        let nilaiInput = parseInt(inputElement.value);

        if (nilaiInput > maksimalStok) {
            alert(`Jumlah input melampaui batas sisa logistik.\nStok maksimal untuk ${namaBarang} saat ini adalah ${maksimalStok}.`);
            inputElement.value = maksimalStok;
        }
    }

    function autofillPetugas(item) {
        document.getElementById('id_petugas').value = item.id_petugas;
        document.getElementById('nama_petugas').value = item.nama;
    }

    function validateForm() {
        var requiredFields = document.querySelectorAll('select[required], input[required]');
        for (var i = 0; i < requiredFields.length; i++) {
            if (!requiredFields[i].value) {
                alert("Mohon isi semua field yang bertanda bintang.");
                return false;
            }
        }

        if (batasKomponen !== null) {
            var jumlahTerpilih = document.querySelectorAll('input[name="id_komponen[]"]:checked').length;
            if (jumlahTerpilih > batasKomponen) {
                alert("Jenis bag " + namaJenisBag + " maksimal " + batasKomponen + " komponen darah. Saat ini terpilih " + jumlahTerpilih + ".");
                return false;
            }
        }

        var submitButton = document.getElementById('submitButton');
        if (submitButton) {
            submitButton.setAttribute('disabled', true);
            submitButton.innerHTML = 'Menyimpan...';
        }
        return true;
    }
</script>

<?= $this->endSection(); ?>