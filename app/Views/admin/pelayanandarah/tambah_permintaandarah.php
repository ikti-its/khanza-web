<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<?= $this->include('components/modal/modalregistrasi') ?>
<?= $this->include('components/modal/modaldokter') ?>

<div class="max-w-[85rem] py-6 lg:py-3 px-8 mx-auto">
    <div class="bg-white rounded-xl shadow p-4 sm:p-7 dark:bg-slate-900">
        <?= view('components/form/judul', [
            'judul' => $judul
        ]) ?>
        
        <form action="<?= $modul_path . $form_action ?>" id="myForm" onsubmit="return validateForm()" method="post">
            <?= csrf_field() ?>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">
                    Nomor Permintaan<span class="text-red-600">*</span>
                </label>
                <?php $isEdit = (str_contains($judul, 'Ubah')); ?>
                <input type="text" name="no_permintaan" value="<?= $baris['no_permintaan'] ?? '' ?>"
                       class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white bg-gray-100" readonly required>

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">
                    Nomor Rawat<span class="text-red-600">*</span>
                </label>
                <input type="hidden" name="id_registrasi" id="id_registrasi" value="<?= $baris['id_registrasi'] ?? '' ?>" required>

                <div class="w-full lg:w-1/4 flex gap-x-2">
                    <input type="text" id="nomor_rawat" name="nomor_rawat" readonly required
                           value="<?= $baris['nomor_rawat'] ?? '' ?>"
                           placeholder="Klik cari..." 
                           <?= $isEdit ? 'disabled' : 'onclick="open_modalRegistrasi()"' ?> 
                           class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full dark:border-gray-600 dark:text-white <?= $isEdit ? 'cursor-not-allowed bg-gray-100' : 'cursor-pointer bg-slate-50' ?>">
                    
                    <?php if (!$isEdit) : ?>
                        <button type="button" onclick="open_modalRegistrasi()" 
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
                    Nomor Rekam Medis
                </label>
                <input type="text" id="nomor_rm" name="nomor_rm" readonly placeholder="Terisi otomatis..."
                       value="<?= $baris['nomor_rm'] ?? '' ?>"
                       class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white bg-gray-100 cursor-not-allowed">

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">
                    Nama Pasien
                </label>
                <input type="text" id="nama" name="nama" readonly placeholder="Terisi otomatis..."
                       value="<?= $baris['nama'] ?? '' ?>"
                       class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white bg-gray-100 cursor-not-allowed">
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">
                    Kamar
                </label>
                <input type="text" id="kamar" name="kamar" readonly placeholder="Terisi otomatis..."
                       value="<?= $baris['kamar'] ?? '-' ?>"
                       class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white bg-gray-100 cursor-not-allowed">

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">
                    Dokter Pengirim<span class="text-red-600">*</span>
                </label>
                <input type="hidden" name="id_dokter_pengirim" id="id_dokter_pengirim" value="<?= $baris['id_dokter_pengirim'] ?? '' ?>" required>

                <div class="w-full lg:w-1/4 flex gap-x-2">
                    <input type="text" id="nama_dokter" name="nama_dokter" readonly required
                           value="<?= $baris['nama_dokter'] ?? '' ?>"
                           placeholder="Klik cari..."
                           onclick="open_modalDokter()"
                           class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full dark:border-gray-600 dark:text-white cursor-pointer bg-slate-50">
                    
                    <button type="button" onclick="open_modalDokter()"
                            class="inline-flex justify-center items-center p-2 text-sm font-medium text-white bg-blue-600 rounded-lg border border-transparent hover:bg-blue-700 focus:outline-none transition-all w-10 h-[38px] flex-shrink-0 shadow-sm">
                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </button>
                </div>
            </div>

            <div class="mb-8 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">
                    Tanggal Permintaan<span class="text-red-600">*</span>
                </label>
                <input type="datetime-local" name="tanggal_permintaan" value="<?= isset($baris['tanggal_permintaan']) && $baris['tanggal_permintaan'] !== '' ? date('Y-m-d\TH:i', strtotime($baris['tanggal_permintaan'])) : date('Y-m-d\TH:i') ?>"
                       max="<?= date('Y-m-d\TH:i') ?>"
                       class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" required>
            </div>

            <div class="mt-8 mb-5">
                <div class="flex justify-between items-center mb-3">
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white">
                        Detail Permintaan Darah<span class="text-red-600">*</span>
                    </h3>
                    <button type="button" onclick="tambahBarisKomponen()"
                            class="inline-flex items-center gap-x-1.5 py-2 px-3 text-sm font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700 shadow-sm transition-all flex-shrink-0">
                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                        </svg>
                        Tambah
                    </button>
                </div>

                <div class="overflow-hidden border border-gray-200 rounded-xl bg-white dark:bg-slate-900 dark:border-gray-700 mb-6">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="bg-gray-100 text-sm text-gray-700 dark:bg-slate-800 dark:text-gray-400">
                            <tr>
                                <th class="p-3 w-1/3">Komponen Darah</th>
                                <th class="p-3">Golongan Darah</th>
                                <th class="p-3">Rhesus</th>
                                <th class="p-3 w-28 text-center">Jumlah (Bag)</th>
                                <th class="p-3 w-20 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="komponenTableBody" class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-slate-900">
                            <tr id="emptyRow">
                                <td colspan="5" class="text-center py-6 text-gray-400 italic dark:text-gray-500">
                                    Belum ada komponen darah yang ditambahkan
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
    document.addEventListener("DOMContentLoaded", function() {
        const currentEditId = "<?= $baris['id_permintaan'] ?? '' ?>";
        
        const savedDetailItems = <?= json_encode($detail_tersimpan ?? []) ?>;

        if (savedDetailItems.length > 0) {
            const tbody = document.getElementById('komponenTableBody');
            
            savedDetailItems.forEach(item => {
                if (String(item.id_permintaan) !== String(currentEditId)) {
                    return;
                }

                const emptyRow = document.getElementById('emptyRow');
                if (emptyRow) emptyRow.remove();

                const row = document.createElement('tr');
                row.className = "baris-komponen border-b text-sm dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-slate-800/30";
                
                row.innerHTML = `
                    <td class="p-3">
                        <select name="id_komponen[]" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full dark:border-gray-600 dark:text-white dark:bg-slate-800 focus:ring-blue-500 focus:border-blue-500" required>
                            <option value="">-- Pilih Komponen --</option>
                            <?php foreach ($master_komponen as $komponen) : ?>
                                <option value="<?= $komponen['id_komponen'] ?>"><?= $komponen['nama_komponen'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td class="p-3">
                        <select name="id_golongan_darah[]" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full dark:border-gray-600 dark:text-white dark:bg-slate-800 focus:ring-blue-500 focus:border-blue-500" required>
                            <option value="">-- Pilih --</option>
                            <?php foreach ($master_gol_darah as $gol) : ?>
                                <option value="<?= $gol['id_golongan_darah'] ?>"><?= $gol['nama_golongan_darah'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td class="p-3">
                        <select name="id_rhesus[]" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full dark:border-gray-600 dark:text-white dark:bg-slate-800 focus:ring-blue-500 focus:border-blue-500" required>
                            <option value="">-- Pilih --</option>
                            <?php foreach ($master_rhesus as $rhe) : ?>
                                <option value="<?= $rhe['id_rhesus'] ?>"><?= $rhe['kode_rhesus'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td class="p-3">
                        <input type="number" name="jumlah[]" min="1" value="${item.jumlah}" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full text-center dark:border-gray-600 dark:text-white dark:bg-slate-800 focus:ring-blue-500 focus:border-blue-500" required>
                    </td>
                    <td class="p-3 text-center">
                        <button type="button" onclick="hapusBarisKomponen(this)" class="text-red-600 font-semibold hover:underline dark:text-red-400">
                            Hapus
                        </button>
                    </td>
                `;

                row.querySelector('select[name="id_komponen[]"]').value = item.id_komponen;
                row.querySelector('select[name="id_golongan_darah[]"]').value = item.id_golongan_darah;
                row.querySelector('select[name="id_rhesus[]"]').value = item.id_rhesus;

                tbody.appendChild(row);
            });
        }
    });
    
    function autofillRegistrasi(item) {
        document.getElementById('id_registrasi').value = item.id_registrasi;
        document.getElementById('nomor_rawat').value = item.nomor_rawat;
        document.getElementById('nomor_rm').value = item.nomor_rm;
        document.getElementById('nama').value = item.nama;
        document.getElementById('kamar').value = item.kamar;
        document.getElementById('id_dokter_pengirim').value = item.id_dokter;
        document.getElementById('nama_dokter').value = item.nama_dokter;
    }

    function autofillFields(item) {
        document.getElementById('id_dokter_pengirim').value = item.id_dokter;
        document.getElementById('nama_dokter').value = item.nama_dokter;
    }

    function tambahBarisKomponen() {
        const tbody = document.getElementById('komponenTableBody');
        
        const emptyRow = document.getElementById('emptyRow');
        if (emptyRow) {
            emptyRow.remove();
        }

        const row = document.createElement('tr');
        row.className = "baris-komponen border-b text-sm dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-slate-800/30";

        row.innerHTML = `
            <td class="p-3">
                <select name="id_komponen[]" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full dark:border-gray-600 dark:text-white dark:bg-slate-800" required>
                    <option value="">-- Pilih Komponen --</option>
                    <?php foreach ($master_komponen as $komponen) : ?>
                        <option value="<?= $komponen['id_komponen'] ?>"><?= $komponen['nama_komponen'] ?></option>
                    <?php endforeach; ?>
                </select>
            </td>
            <td class="p-3">
                <select name="id_golongan_darah[]" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full dark:border-gray-600 dark:text-white dark:bg-slate-800" required>
                    <option value="">-- Pilih --</option>
                    <?php foreach ($master_gol_darah as $gol) : ?>
                        <option value="<?= $gol['id_golongan_darah'] ?>"><?= $gol['nama_golongan_darah'] ?></option>
                    <?php endforeach; ?>
                </select>
            </td>
            <td class="p-3">
                <select name="id_rhesus[]" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full dark:border-gray-600 dark:text-white dark:bg-slate-800" required>
                    <option value="">-- Pilih --</option>
                    <?php foreach ($master_rhesus as $rhe) : ?>
                        <option value="<?= $rhe['id_rhesus'] ?>"><?= $rhe['kode_rhesus'] ?></option>
                    <?php endforeach; ?>
                </select>
            </td>
            <td class="p-3">
                <input type="number" name="jumlah[]" min="1" placeholder="0" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full text-center dark:border-gray-600 dark:text-white dark:bg-slate-800" required>
            </td>
            <td class="p-3 text-center">
                <button type="button" onclick="hapusBarisKomponen(this)" class="text-red-600 font-semibold hover:underline dark:text-red-400">
                    Hapus
                </button>
            </td>
        `;

        tbody.appendChild(row);
    }

    function hapusBarisKomponen(button) {
        button.closest('tr').remove();
        const tbody = document.getElementById('komponenTableBody');
        if (tbody.children.length === 0) {
            tbody.innerHTML = `
                <tr id="emptyRow">
                    <td colspan="5" class="text-center py-6 text-gray-400 italic dark:text-gray-500">
                        Belum ada komponen darah yang ditambahkan
                    </td>
                </tr>`;
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

        const tbody = document.getElementById('komponenTableBody');
        const jumlahBaris = tbody.querySelectorAll('.baris-komponen').length;
        
        if (jumlahBaris === 0) {
            alert("Gagal Menyimpan! Anda harus menambahkan minimal 1 detail komponen darah yang diminta.");
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