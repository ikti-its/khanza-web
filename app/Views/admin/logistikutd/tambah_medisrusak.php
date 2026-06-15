<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<?= $this->include('components/modal/modalmedisutd') ?>
<?= $this->include('components/modal/modalpetugas') ?>

<div class="max-w-[85rem] py-6 lg:py-3 px-8 mx-auto">
    <div class="bg-white rounded-xl shadow p-4 sm:p-7 dark:bg-slate-900">
        <?= view('components/form/judul', [
            'judul' => $judul
        ]) ?>
        
        <form action="<?= $modul_path . $form_action ?>" id="myForm" onsubmit="return validateForm()" method="post">
            <?= csrf_field() ?>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">
                    Petugas Penanggung Jawab<span class="text-red-600">*</span>
                </label>
                <input type="hidden" name="id_petugas" id="id_petugas" value="<?= $baris['id_petugas'] ?? '' ?>" required>
                
                <div class="w-full lg:w-1/4 flex gap-x-2">
                    <input type="text" id="nama_petugas" name="nama_petugas" readonly required
                           value="<?= $baris['nama_petugas'] ?? '' ?>" placeholder="Klik cari..."
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
                    Tanggal Rusak<span class="text-red-600">*</span>
                </label>
                <input type="datetime-local" name="tanggal_rusak" value="<?= isset($baris['tanggal_rusak']) && $baris['tanggal_rusak'] !== '' ? date('Y-m-d\TH:i', strtotime($baris['tanggal_rusak'])) : date('Y-m-d\TH:i') ?>"
                       max="<?= date('Y-m-d\TH:i') ?>"
                       class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" required>
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">
                    Keterangan<span class="text-red-600">*</span>
                </label>
                <input type="text" name="keterangan" value="<?= $baris['keterangan'] ?? '' ?>" placeholder="Alasan kerusakan..."
                       class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
            </div>

            <div class="mt-10 mb-5">
                <div class="flex justify-between items-center mb-3">
                    <h3 class="text-base font-semibold text-gray-900 dark:text-white">
                        Daftar BHP Medis Rusak<span class="text-red-600">*</span>
                    </h3>
                    <button type="button" onclick="open_modalMedisUtd()"
                            class="inline-flex items-center gap-x-1.5 py-2 px-3 text-sm font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700 shadow-sm transition-all flex-shrink-0">
                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                        </svg>
                        Pilih Barang
                    </button>
                </div>

                <div class="overflow-hidden border border-gray-200 rounded-xl bg-white dark:bg-slate-900 dark:border-gray-700 mb-6">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="bg-gray-100 text-sm text-gray-700 dark:bg-slate-800 dark:text-gray-400">
                            <tr>
                                <th class="p-3 w-[18%] text-center">Kode Barang</th>
                                <th class="p-3 w-[25%]">Nama Barang</th>
                                <th class="p-3 w-[15%] text-center">Harga Beli (Rp)</th>
                                <th class="p-3 w-[12%] text-center">Jumlah</th>
                                <th class="p-3 w-[20%]">Total</th>
                                <th class="p-3 w-[12%] text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="komponenTableBody" class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-slate-900">
                            <tr id="emptyRow">
                                <td colspan="6" class="text-center py-6 text-gray-400 italic dark:text-gray-500">
                                    Belum ada daftar item barang rusak yang ditambahkan
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <?= view('components/form/submit_button') ?>
        </form>
    </div>
</div>

<script>
    const riwayatStokLokal = {};

    function autofillPetugas(item) {
        document.getElementById('id_petugas').value = item.id_petugas;
        document.getElementById('nama_petugas').value = item.nama;
    }

    function tambahBarisBhpRusak(item) {
        const tbody = document.getElementById('komponenTableBody');
        
        const emptyRow = document.getElementById('emptyRow');
        if (emptyRow) emptyRow.remove();

        if (document.getElementById(`baris_item_${item.id_barang}`)) {
            return; 
        }

        riwayatStokLokal[item.id_barang] = parseInt(item.stok) || 0;

        const row = document.createElement('tr');
        row.id = `baris_item_${item.id_barang}`;
        row.className = "baris-medis border-b text-sm dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-slate-800/30";

        row.innerHTML = `
            <td class="p-3 text-center font-medium text-gray-900 dark:text-white">
                ${item.kode_barang}
                <input type="hidden" name="id_barang[]" value="${item.id_barang}">
            </td>
            <td class="p-3 font-medium text-gray-900 dark:text-white">
                ${item.nama_barang}
            </td>
            <td class="p-3 text-center">
                <input type="number" name="harga_beli[]" id="input_harga_${item.id_barang}" readonly value="${Math.round(item.harga)}"
                       class="border-0 bg-transparent text-center w-full focus:ring-0 p-0 text-gray-900 cursor-not-allowed">
            </td>
            <td class="p-3">
                <input type="number" name="jumlah[]" id="input_jumlah_${item.id_barang}" min="1" max="${item.stok}" value="1"
                       oninput="hitungTotalBaris('${item.id_barang}'); cekBatasStokBaris(this, '${item.id_barang}')"
                       class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full text-center dark:border-gray-600 dark:text-white dark:bg-slate-800 focus:ring-blue-500 focus:border-blue-500" required>
            </td>
            <td class="p-3 text-center">
                <input type="text" name="total_harga[]" id="input_total_${item.id_barang}" readonly
                       value="${Math.round(item.harga)}" 
                       class="border border-gray-200 text-gray-500 text-sm rounded-lg p-2 w-full text-center bg-gray-50 cursor-not-allowed font-mono font-semibold dark:bg-slate-900 dark:border-gray-700">
            </td>
            <td class="p-3 text-center">
                <button type="button" onclick="hapusBarisBhpRusak(this)" class="text-red-600 font-semibold hover:underline dark:text-red-400">
                    Hapus
                </button>
            </td>
        `;

        tbody.appendChild(row);
    }

    function hapusBarisBhpRusak(button) {
        button.closest('tr').remove();
        const tbody = document.getElementById('komponenTableBody');
        if (tbody.children.length === 0) {
            tbody.innerHTML = `
                <tr id="emptyRow">
                    <td colspan="6" class="text-center py-6 text-gray-400 italic dark:text-gray-500">
                        Belum ada daftar item barang rusak yang ditambahkan
                    </td>
                </tr>`;
        }
    }

    function hitungTotalBaris(idBarang) {
        const harga = parseFloat(document.getElementById(`input_harga_${idBarang}`).value) || 0;
        const jumlah = parseInt(document.getElementById(`input_jumlah_${idBarang}`).value) || 0;
        const total = harga * jumlah;
        
        document.getElementById(`input_total_${idBarang}`).value = total;
    }

    function cekBatasStokBaris(inputNode, idBarang) {
        const valueInput = parseInt(inputNode.value) || 0;
        const batasMax   = riwayatStokLokal[idBarang];

        if (valueInput > batasMax) {
            alert(`Input tidak valid! Stok maksimal ruangan UTD untuk barang ini hanya tersisa ${batasMax} Pcs.`);
            inputNode.value = batasMax;
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
        
        const idPetugas = document.getElementById('id_petugas').value;
        if (!idPetugas) {
            alert("Gagal Menyimpan! Anda wajib menentukan petugas penanggung jawab terlebih dahulu.");
            return false;
        }

        const tbody = document.getElementById('komponenTableBody');
        const jumlahBaris = tbody.querySelectorAll('.baris-medis').length;
        if (jumlahBaris === 0) {
            alert("Gagal Menyimpan! Anda harus menambahkan minimal 1 item detail barang medis yang rusak.");
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