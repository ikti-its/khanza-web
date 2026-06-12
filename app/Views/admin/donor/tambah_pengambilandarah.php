<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<?= $this->include('components/modal/modalkunjungan') ?>
<?= $this->include('components/modal/modalpetugas') ?>

<div class="max-w-[85rem] py-6 lg:py-3 px-8 mx-auto">
    <div class="bg-white rounded-xl shadow p-4 sm:p-7 dark:bg-slate-900">
        <?= view('components/form/judul', [
            'judul' => $judul
        ]) ?>
        
        <form action="<?= $modul_path . $form_action ?>" id="myForm" onsubmit="return validateForm()" method="post">
            <?= csrf_field() ?>

            <input type="hidden" name="id_kunjungan" id="id_kunjungan" value="<?= $baris['id_kunjungan'] ?? '' ?>" required>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">
                    Nomor Pengambilan<span class="text-red-600">*</span>
                </label>
                <?php $isEdit = (str_contains($judul, 'Ubah')); ?>
                <input type="text" name="nomor_pengambilan" id="nomor_pengambilan" readonly
                       value="<?= $baris['nomor_pengambilan'] ?? '' ?>"
                       class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white bg-gray-100 cursor-not-allowed" required>

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">
                    Nomor Kunjungan<span class="text-red-600">*</span>
                </label>
                <div class="w-full lg:w-1/4 flex gap-x-2">
                    <input type="text" id="nomor_kunjungan" name="nomor_kunjungan" readonly required
                           value="<?= $baris['nomor_kunjungan'] ?? '' ?>"
                           placeholder="Klik cari..." 
                           <?= $isEdit ? 'disabled' : 'onclick="open_modalKunjungan()"' ?> 
                           class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full dark:border-gray-600 dark:text-white <?= $isEdit ? 'cursor-not-allowed bg-gray-100' : 'cursor-pointer bg-slate-50' ?>">
                    
                    <?php if (!$isEdit) : ?>
                        <button type="button" onclick="open_modalKunjungan()" 
                                class="inline-flex justify-center items-center p-2 text-sm font-medium text-white bg-blue-600 rounded-lg border border-transparent hover:bg-blue-700 focus:outline-none transition-all w-10 h-[38px] flex-shrink-0 shadow-sm">
                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </button>
                    <?php endif; ?>
                </div>
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">
                    Nomor Pendonor
                </label>
                <input type="text" id="nomor_pendonor" name="nomor_pendonor" readonly placeholder="Terisi otomatis..."
                       value="<?= $baris['nomor_pendonor'] ?? '' ?>"
                       class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white bg-gray-100 cursor-not-allowed">

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">
                    Nama Lengkap
                </label>
                <input type="text" id="nama" name="nama" readonly placeholder="Terisi otomatis..."
                       value="<?= $baris['nama'] ?? '' ?>"
                       class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white bg-gray-100 cursor-not-allowed">
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">
                    Tanggal Pengambilan<span class="text-red-600">*</span>
                </label>
                <input type="date" name="tanggal_pengambilan" value="<?= $baris['tanggal_pengambilan'] ?? '' ?>"
                       class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" required>

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">
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
                        $selected = ((string)($baris['id_shift'] ?? '') === (string)$opt[1]) ? 'selected' : '';
                    ?>
                        <option value="<?= $opt[1] ?>" <?= $selected ?>><?= $opt[0] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">
                    Nomor Bag<span class="text-red-600">*</span>
                </label>
                <input type="text" name="no_bag" value="<?= $baris['no_bag'] ?? '' ?>" placeholder="Masukkan nomor kantong..."
                       class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" required>

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">
                    Jenis Bag<span class="text-red-600">*</span>
                </label>
                <select name="id_jenis_bag" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" required>
                    <option value="">-- Pilih --</option>
                    <?php 
                    $optionsJenisBag = [];
                    foreach ($konfig as $field) {
                        if ($field[2] === 'id_jenis_bag') {
                            $optionsJenisBag = $field[5] ?? [];
                            break;
                        }
                    }
                    foreach ($optionsJenisBag as $opt) : 
                        $selected = ((string)($baris['id_jenis_bag'] ?? '') === (string)$opt[1]) ? 'selected' : '';
                    ?>
                        <option value="<?= $opt[1] ?>" <?= $selected ?>><?= $opt[0] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">
                    Jenis Donor<span class="text-red-600">*</span>
                </label>
                <select name="id_jenis_donor" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" required>
                    <option value="">-- Pilih --</option>
                    <?php 
                    $optionsJenisDonor = [];
                    foreach ($konfig as $field) {
                        if ($field[2] === 'id_jenis_donor') {
                            $optionsJenisDonor = $field[5] ?? [];
                            break;
                        }
                    }
                    foreach ($optionsJenisDonor as $opt) : 
                        $selected = ((string)($baris['id_jenis_donor'] ?? '') === (string)$opt[1]) ? 'selected' : '';
                    ?>
                        <option value="<?= $opt[1] ?>" <?= $selected ?>><?= $opt[0] ?></option>
                    <?php endforeach; ?>
                </select>

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">
                    Lokasi Pengambilan<span class="text-red-600">*</span>
                </label>
                <select name="id_lokasi_pengambilan" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" required>
                    <option value="">-- Pilih --</option>
                    <?php 
                    $optionsLokasi = [];
                    foreach ($konfig as $field) {
                        if ($field[2] === 'id_lokasi_pengambilan') {
                            $optionsLokasi = $field[5] ?? [];
                            break;
                        }
                    }
                    foreach ($optionsLokasi as $opt) : 
                        $selected = ((string)($baris['id_lokasi_pengambilan'] ?? '') === (string)$opt[1]) ? 'selected' : '';
                    ?>
                        <option value="<?= $opt[1] ?>" <?= $selected ?>><?= $opt[0] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">
                    Petugas<span class="text-red-600">*</span>
                </label>
                <input type="hidden" name="id_petugas" id="id_petugas" value="<?= $baris['id_petugas'] ?? '' ?>" required>
                
                <div class="w-full lg:w-1/4 flex gap-x-2">
                    <input type="text" id="nama_petugas" name="nama_petugas" readonly required
                           value="<?= $baris['nama_petugas'] ?? '' ?>"
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

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">
                    Status Pengambilan
                </label>
                <select name="id_status_pengambilan" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white">
                    <option value="">-- Pilih --</option>
                    <?php 
                    $optionsStatus = [];
                    foreach ($konfig as $field) {
                        if ($field[2] === 'id_status_pengambilan') {
                            $optionsStatus = $field[5] ?? [];
                            break;
                        }
                    }
                    foreach ($optionsStatus as $opt) : 
                        $selected = ((string)($baris['id_status_pengambilan'] ?? '') === (string)$opt[1]) ? 'selected' : '';
                    ?>
                        <option value="<?= $opt[1] ?>" <?= $selected ?>><?= $opt[0] ?></option>
                    <?php endforeach; ?>
                </select>
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

            <div class="overflow-hidden border border-gray-200 rounded-xl bg-white dark:bg-slate-900 dark:border-gray-700">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="bg-gray-100 text-sm text-gray-700 dark:bg-slate-800 dark:text-gray-400">
                        <tr>
                            <th class="p-3">Nama Barang</th>
                            <th class="p-3 w-24 text-center">Jumlah</th>
                            <th class="p-3 w-20 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="bhpTableBody">
                        <tr id="emptyBhpRow">
                            <td colspan="3" class="text-center py-6 text-gray-400 italic dark:text-gray-500">
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
    let currentTab = 'medis';

    document.addEventListener("DOMContentLoaded", function() {
        populateBhpDropdown(masterMedis);

        const currentEditId = "<?= $baris['id_pengambilan_darah'] ?? '' ?>";

        const kunjunganId = "<?= $baris['id_kunjungan'] ?? '' ?>";
        if (kunjunganId !== '') {
            const savedItem = {
                id_kunjungan: kunjunganId,
                nomor_kunjungan: "<?= $baris['nomor_kunjungan'] ?? '' ?>",
                nomor_pendonor: "<?= $baris['nomor_pendonor'] ?? '' ?>",
                nama: "<?= $baris['nama'] ?? '' ?>"
            };

            autofillKunjungan(savedItem);
        }

        const petugasId = "<?= $baris['id_petugas'] ?? '' ?>";
        if (petugasId !== '') {
            const savedItem = {
                id_petugas: petugasId,
                nama: "<?= $baris['nama_petugas'] ?? '' ?>"
            };

            autofillPetugas(savedItem);
        }

        const savedMedisItems = <?= json_encode($saved_medis ?? []) ?>;
        if (savedMedisItems.length > 0) {
            const tbody = document.getElementById('bhpTableBody');
            
            savedMedisItems.forEach(item => {
                if (String(item.id_pengambilan_darah) !== String(currentEditId)) {
                    return;
                }

                const emptyRow = document.getElementById('emptyBhpRow');
                if (emptyRow) emptyRow.remove();

                const row = document.createElement('tr');
                row.className = "border-b text-sm dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-slate-800/30";
                row.innerHTML = `
                    <td class="p-3 font-medium text-gray-900 dark:text-white">
                        ${item.nama_barang}
                        <input type="hidden" name="harga_medis[${item.id_barang}]" value="${item.harga}">
                    </td>
                    <td class="p-3 text-center">
                        <input type="number" name="id_medis_donor[${item.id_barang}]" data-id="${item.id_barang}" data-type="medis" value="${item.jumlah}" min="1" class="w-16 text-center border border-gray-300 rounded p-1 dark:bg-slate-900 dark:text-white dark:border-gray-700">
                    </td>
                    <td class="p-3 text-center">
                        <button type="button" onclick="removeBhpItem(this)" class="text-red-600 font-semibold hover:underline dark:text-red-400">Hapus</button>
                    </td>
                `;
                tbody.appendChild(row);
            });
        }

        const savedNonMedisItems = <?= json_encode($saved_non_medis ?? []) ?>;
        if (savedNonMedisItems.length > 0) {
            const tbody = document.getElementById('bhpTableBody');

            savedNonMedisItems.forEach(item => {
                if (String(item.id_pengambilan_darah) !== String(currentEditId)) {
                    return;
                }

                const emptyRow = document.getElementById('emptyBhpRow');
                if (emptyRow) emptyRow.remove();

                const row = document.createElement('tr');
                row.className = "border-b text-sm dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-slate-800/30";
                row.innerHTML = `
                    <td class="p-3 font-medium text-gray-900 dark:text-white">
                        ${item.nama_barang}
                        <input type="hidden" name="harga_penunjang[${item.id_barang}]" value="${item.harga}">
                    </td>
                    <td class="p-3 text-center">
                        <input type="number" name="id_penunjang_donor[${item.id_barang}]" data-id="${item.id_barang}" data-type="nonmedis" value="${item.jumlah}" min="1" class="w-16 text-center border border-gray-300 rounded p-1 dark:bg-slate-900 dark:text-white dark:border-gray-700">
                    </td>
                    <td class="p-3 text-center">
                        <button type="button" onclick="removeBhpItem(this)" class="text-red-600 font-semibold hover:underline dark:text-red-400">Hapus</button>
                    </td>
                `;
                tbody.appendChild(row);
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
        
        if (!items || items.length === 0) {
            console.warn("Katalog master barang dari server kosong.");
            return;
        }
        
        items.forEach(item => {
            const opt = document.createElement('option');
            opt.value = item.id_barang;
            opt.text  = `${item.nama_barang} (Stok: ${item.stok})`;
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

        let hargaSnapshot = 0;
        const daftarMaster = currentTab === 'medis' ? masterMedis : masterNonMedis;
        
        const selectedItem = daftarMaster.find(item => String(item.id_barang) === String(idBarangTerpilih));
        if (selectedItem && selectedItem.harga) {
            hargaSnapshot = selectedItem.harga;
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
            <td class="p-3 font-medium text-gray-900 dark:text-white">
                ${select.options[select.selectedIndex].text}
                <input type="hidden" name="${priceName}[${idBarangTerpilih}]" value="${hargaSnapshot}">
            </td>
            <td class="p-3 text-center">
                <input type="number" name="${inputName}[${idBarangTerpilih}]" data-id="${idBarangTerpilih}" data-type="${currentTab}" value="1" min="1" class="w-16 text-center border border-gray-300 rounded p-1 dark:bg-slate-900 dark:text-white dark:border-gray-700">
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
                    <td colspan="3" class="text-center py-6 text-gray-400 italic dark:text-gray-500">Belum ada BHP terpilih</td>
                </tr>`;
        }
    }

    function autofillKunjungan(item) {
        document.getElementById('id_kunjungan').value = item.id_kunjungan;
        document.getElementById('nomor_kunjungan').value = item.nomor_kunjungan;
        document.getElementById('nomor_pendonor').value = item.nomor_pendonor;
        document.getElementById('nama').value = item.nama;

        const inputNomorPengambilan = document.getElementById('nomor_pengambilan');
        if (inputNomorPengambilan) {
            const waktuSekarang = new Date();
            const tahun = waktuSekarang.getFullYear(); 
            const bulan = String(waktuSekarang.getMonth() + 1).padStart(2, '0'); 
            const komponenIdentik = item.nomor_pendonor || 'ANON';
            
            inputNomorPengambilan.value = `${tahun}/${bulan}/${komponenIdentik}`;
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

        const idKunjungan = document.getElementById('id_kunjungan').value;
        if (!idKunjungan) {
            alert("Silakan tentukan data Kunjungan terlebih dahulu melalui modal pendaftaran.");
            return false;
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