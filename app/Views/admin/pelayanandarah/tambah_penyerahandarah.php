<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<?= $this->include('components/modal/modalpermintaandarah') ?>
<?= $this->include('components/modal/modalpetugas') ?>
<?= $this->include('components/modal/modalstokdarah') ?>

<div class="max-w-[85rem] py-6 lg:py-3 px-8 mx-auto">
    <div class="bg-white rounded-xl shadow p-4 sm:p-7 dark:bg-slate-900">
        <?= view('components/form/judul', [
            'judul' => $judul
        ]) ?>
        
        <form action="<?= $modul_path . $form_action ?>" id="myForm" onsubmit="return validateForm()" method="post">
            <?= csrf_field() ?>

            <input type="hidden" name="id_permintaan" id="id_permintaan" value="<?= $baris['id_permintaan'] ?? '' ?>" required>
            <input type="hidden" name="id_petugas_cross" id="id_petugas_cross" value="<?= $baris['id_petugas_cross'] ?? '' ?>" required>
            <input type="hidden" name="id_penanggung_jawab" id="id_penanggung_jawab" value="<?= $baris['id_penanggung_jawab'] ?? '' ?>" required>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">
                    Nomor Penyerahan<span class="text-red-600">*</span>
                </label>
                <?php $isEdit = (str_contains($judul, 'Ubah')); ?>
                <input type="text" name="no_penyerahan" value="<?= $baris['no_penyerahan'] ?? '' ?>"
                       class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white bg-gray-100" readonly required>

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">
                    Nomor Permintaan<span class="text-red-600">*</span>
                </label>
                <div class="w-full lg:w-1/4 flex gap-x-2">
                    <?php $isEdit = (str_contains($judul, 'Ubah')); ?>
                    <input type="text" id="nomor_permintaan" name="no_permintaan" readonly required
                           value="<?= $baris['no_permintaan'] ?? '' ?>"
                           placeholder="Klik cari..." 
                           <?= $isEdit ? 'disabled' : 'onclick="open_modalPermintaanDarah()"' ?> 
                           class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full dark:border-gray-600 dark:text-white <?= $isEdit ? 'cursor-not-allowed bg-gray-100' : 'cursor-pointer bg-slate-50' ?>">
                    
                    <?php if (!$isEdit) : ?>
                        <button type="button" onclick="open_modalPermintaanDarah()" 
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
                    Tanggal Penyerahan<span class="text-red-600">*</span>
                </label>
                <input type="datetime-local" name="tanggal_penyerahan" value="<?= isset($baris['tanggal_penyerahan']) && $baris['tanggal_penyerahan'] !== '' ? date('Y-m-d\TH:i', strtotime($baris['tanggal_penyerahan'])) : date('Y-m-d\TH:i') ?>"
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
                    Petugas Crossmatch<span class="text-red-600">*</span>
                </label>
                <div class="w-full lg:w-1/4 flex gap-x-2">
                    <input type="text" id="nama_petugas_cross" name="nama_petugas_cross" readonly required
                           value="<?= $baris['nama_petugas_cross'] ?? '' ?>"
                           placeholder="Klik cari..."
                           onclick="open_modalPetugasCrossmatch()"
                           class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full dark:border-gray-600 dark:text-white cursor-pointer bg-slate-50">
                    
                    <button type="button" onclick="open_modalPetugasCrossmatch()"
                            class="inline-flex justify-center items-center p-2 text-sm font-medium text-white bg-blue-600 rounded-lg border border-transparent hover:bg-blue-700 focus:outline-none transition-all w-10 h-[38px] flex-shrink-0 shadow-sm">
                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </button>
                </div>

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">
                    Keterangan
                </label>
                <input type="text" name="keterangan" value="<?= $baris['keterangan'] ?? '' ?>"
                       class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white">
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">
                    Metode Pembayaran<span class="text-red-600">*</span>
                </label>
                <select name="id_rekening" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" required>
                    <option value="">-- Pilih --</option>
                    <?php
                    $optionsRekening = [];
                    foreach ($konfig as $field) {
                        if ($field[2] === 'id_rekening') {
                            $optionsRekening = $field[5] ?? [];
                            break;
                        }
                    }
                    $optionsRekening = array_filter($optionsRekening, function ($opt) {
                        return (string)$opt[1] !== '0' && $opt[0] !== 'PT Sumber Makmur Abadi';
                    });
                    foreach ($optionsRekening as $opt) :
                        $selected = ((string)($baris['id_rekening'] ?? '') === (string)$opt[1]) ? 'selected' : '';
                    ?>
                        <option value="<?= $opt[1] ?>" <?= $selected ?>><?= $opt[0] ?></option>
                    <?php endforeach; ?>
                </select>

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">
                    Pengambil Darah<span class="text-red-600">*</span>
                </label>
                <input type="text" name="pengambil_darah" value="<?= $baris['pengambil_darah'] ?? '' ?>" placeholder="Nama perawat / keluarga..."
                       class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" required>
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">
                    Alamat Pengambil<span class="text-red-600">*</span>
                </label>
                <input type="text" name="alamat_pengambil" value="<?= $baris['alamat_pengambil'] ?? '' ?>" placeholder="Masukkan alamat lengkap..."
                       class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" required>

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">
                    Penanggung Jawab<span class="text-red-600">*</span>
                </label>
                <div class="w-full lg:w-1/4 flex gap-x-2">
                    <input type="text" id="nama_penanggung_jawab" name="nama_penanggung_jawab" readonly required
                           value="<?= $baris['nama_penanggung_jawab'] ?? '' ?>"
                           placeholder="Klik cari..."
                           onclick="open_modalPenanggungJawab()"
                           class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full dark:border-gray-600 dark:text-white cursor-pointer bg-slate-50">
                    
                    <button type="button" onclick="open_modalPenanggungJawab()"
                            class="inline-flex justify-center items-center p-2 text-sm font-medium text-white bg-blue-600 rounded-lg border border-transparent hover:bg-blue-700 focus:outline-none transition-all w-10 h-[38px] flex-shrink-0 shadow-sm">
                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </button>
                </div>
            </div>

            <div class="mb-8 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">
                    PPN (%) / Total Tagihan<span class="text-red-600">*</span>
                </label>
                <div class="w-full lg:w-1/4 flex items-center gap-x-2">
                    <input type="number" step="0.01" min="0" name="besar_ppn" id="besar_ppn" 
                           value="<?= $baris['besar_ppn'] ?? '11.00' ?>" placeholder="0.00"
                           oninput="hitungTagihanDinamis()"
                           class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full text-center dark:border-gray-600 dark:text-white" required>
        
                    <span class="text-gray-500 font-medium">/</span>
        
                    <input type="text" id="total_tagihan_tampilan" readonly value="Rp 0" placeholder="Rp 0"
                           class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full text-center bg-gray-100 dark:bg-slate-800 font-mono font-bold dark:border-gray-600 dark:text-white cursor-not-allowed">
                </div>

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">
                    Status Pembayaran<span class="text-red-600">*</span>
                </label>
                <select name="id_status_pembayaran" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" required>
                    <option value="">-- Pilih --</option>
                    <?php 
                    $optionsBayar = [];
                    foreach ($konfig as $field) {
                        if ($field[2] === 'id_status_pembayaran') {
                            $optionsBayar = $field[5] ?? [];
                            break;
                        }
                    }
                    foreach ($optionsBayar as $opt) : 
                        $selected = ((string)($baris['id_status_pembayaran'] ?? '') === (string)$opt[1]) ? 'selected' : '';
                    ?>
                        <option value="<?= $opt[1] ?>" <?= $selected ?>><?= $opt[0] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mt-10 mb-5">
                <div class="flex justify-between items-center mb-3">
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white">
                        Daftar Kantong Darah yang Diserahkan<span class="text-red-600">*</span>
                    </h3>
                    <button type="button" onclick="open_modalStokDarah()"
                            class="inline-flex items-center gap-x-1.5 py-2 px-3 text-sm font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700 shadow-sm transition-all flex-shrink-0">
                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                        </svg>
                        Pilih Stok Darah
                    </button>
                </div>

                <div class="overflow-hidden border border-gray-200 rounded-xl bg-white dark:bg-slate-900 dark:border-gray-700 mb-6">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400 table-fixed">
                        <thead class="bg-gray-100 text-sm text-gray-700 dark:bg-slate-800 dark:text-gray-400">
                            <tr>
                                <th class="p-3 w-[15%] text-center">No. Kantong</th>
                                <th class="p-3 w-[25%]">Komponen Darah</th>
                                <th class="p-3 w-[10%] text-center">Golongan Darah</th>
                                <th class="p-3 w-[10%] text-center">Rhesus</th>
                                <th class="p-3 w-[15%] text-center">Tanggal Kadaluwarsa</th>
                                <th class="p-3 w-[15%] text-center">Biaya</th>
                                <th class="p-3 w-[10%] text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="darahTableBody" class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-slate-900">
                            <tr id="emptyDarahRow">
                                <td colspan="7" class="text-center py-6 text-gray-400 italic dark:text-gray-500">
                                    Belum ada daftar kantong darah yang dipilih untuk diserahkan
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
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
    let tipePetugasAktif = '';
    let currentTab       = 'medis';
    const masterMedis    = <?= json_encode($bhp_medis_options ?? []) ?>;
    const masterNonMedis = <?= json_encode($bhp_non_options ?? []) ?>;

    document.addEventListener("DOMContentLoaded", function() {
        populateBhpDropdown(masterMedis);

        const penyerahanId = "<?= $baris['id_penyerahan'] ?? '' ?>";
        if (penyerahanId !== '') {
            document.getElementById('nomor_penyerahan').value = "<?= $baris['nomor_penyerahan'] ?? '' ?>";
        }

        const ppnInput = document.getElementById('besar_ppn');
        if (ppnInput) {
            ppnInput.addEventListener('keydown', function(e) {
                if (e.key === '-' || e.key === 'Subtract') {
                    e.preventDefault();
                }
            });

            ppnInput.addEventListener('input', function() {
                if (parseFloat(this.value) < 0) {
                    this.value = '';
                    alert("PPN tidak boleh negatif!");
                }
            });
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

    function autofillPermintaanDarah(item) {
        document.getElementById('id_permintaan').value = item.id_permintaan;
        document.getElementById('nomor_permintaan').value = item.no_permintaan;
    }

    function open_modalPetugasCrossmatch() {
        tipePetugasAktif = 'crossmatch';
        open_modalPetugas();
    }

    function open_modalPenanggungJawab() {
        tipePetugasAktif = 'penanggung_jawab';
        open_modalPetugas();
    }

    function autofillPetugas(item) {
        if (tipePetugasAktif === 'crossmatch') {
            document.getElementById('id_petugas_cross').value = item.id_petugas;
            document.getElementById('nama_petugas_cross').value = item.nama;
        } else if (tipePetugasAktif === 'penanggung_jawab') {
            document.getElementById('id_penanggung_jawab').value = item.id_petugas;
            document.getElementById('nama_penanggung_jawab').value = item.nama;
        }
    }

    function tambahBarisDarahPenyerahan(item) {
        const tbody = document.getElementById('darahTableBody');
        
        const emptyRow = document.getElementById('emptyDarahRow');
        if (emptyRow) emptyRow.remove();

        if (document.getElementById(`baris_kantong_${item.id_stok_darah}`)) {
            return; 
        }

        const row = document.createElement('tr');
        row.id = `baris_kantong_${item.id_stok_darah}`;
        row.className = "baris-kantong border-b text-sm dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-slate-800/30";
        
        const angkaBiayaMurni = parseFloat(item.total_biaya) || 0;
        const formatRupiahTampilan = 'Rp ' + Math.round(angkaBiayaMurni).toLocaleString('id-ID');

        row.innerHTML = `
            <td class="p-3 text-center font-medium text-gray-900 dark:text-white">
                ${item.no_kantong}
                <input type="hidden" name="id_stok_darah[]" value="${item.id_stok_darah}">
                <input type="hidden" name="jumlah[]" value="1">
            </td>
            <td class="p-3 font-medium text-gray-900 dark:text-white truncate">
                ${item.nama_komponen}
            </td>
            <td class="p-3 text-center">
                <span class="px-2 py-0.5 bg-red-100 text-red-800 rounded font-bold dark:bg-red-900/30 dark:text-red-400">${item.gol_darah}</span>
            </td>
            <td class="p-3 text-center font-semibold text-gray-900 dark:text-white">
                ${item.rhesus}
            </td>
            <td class="p-3 text-center text-gray-900 dark:text-gray-400 font-mono">
                ${item.tanggal_kadaluarsa}
            </td>
            <td class="p-3 text-center">
                <input type="text" readonly 
                       value="${formatRupiahTampilan}"
                       data-harga="${angkaBiayaMurni}"
                       class="border-0 bg-transparent text-center w-full focus:ring-0 p-0 text-gray-900 font-mono font-semibold dark:text-white cursor-not-allowed">
            </td>
            <td class="p-3 text-center">
                <button type="button" onclick="hapusBarisDarahPenyerahan(this)" class="text-red-600 font-semibold hover:underline dark:text-red-400">
                    Hapus
                </button>
            </td>
        `;

        tbody.appendChild(row);

        hitungTagihanDinamis();
    }

    function hapusBarisDarahPenyerahan(button) {
        button.closest('tr').remove();
        const tbody = document.getElementById('darahTableBody');

        if (tbody.children.length === 0) {
            tbody.innerHTML = `
                <tr id="emptyDarahRow">
                    <td colspan="7" class="text-center py-6 text-gray-400 italic dark:text-gray-500">
                        Belum ada daftar kantong darah yang dipilih untuk diserahkan
                    </td>
                </tr>`;
        }

        hitungTagihanDinamis();
    }

    function hitungTagihanDinamis() {
        const semuaInputBiaya = document.querySelectorAll('input[data-harga]');
        let totalBiayaDarahMurni = 0;

        semuaInputBiaya.forEach(input => {
            totalBiayaDarahMurni += parseFloat(input.getAttribute('data-harga')) || 0;
        });

        const persentasePpn = parseFloat(document.getElementById('besar_ppn').value) || 0;
        const nominalPpn  = (persentasePpn / 100) * totalBiayaDarahMurni;
        const totalTagihan = totalBiayaDarahMurni + nominalPpn;

        document.getElementById('total_tagihan_tampilan').value = 'Rp ' + Math.round(totalTagihan).toLocaleString('id-ID');
    }

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

    function validateForm() {
        var requiredFields = document.querySelectorAll('select[required], input[required]');
        for (var i = 0; i < requiredFields.length; i++) {
            if (!requiredFields[i].value) {
                alert("Mohon isi semua field yang bertanda bintang.");
                return false;
            }
        }

        const idPermintaan = document.getElementById('id_permintaan').value;
        if (!idPermintaan) {
            alert("Gagal Menyimpan! Anda wajib memilih data nomor permintaan terlebih dahulu.");
            return false;
        }

        const tbodyDarah = document.getElementById('darahTableBody');
        const jumlahBarisDarah = tbodyDarah.querySelectorAll('.baris-kantong').length;
        if (jumlahBarisDarah === 0) {
            alert("Gagal Menyimpan! Anda harus menambahkan minimal 1 kantong darah dari stok kulkas.");
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