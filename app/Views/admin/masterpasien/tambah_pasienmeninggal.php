<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<!-- Card Section -->
<div class="max-w-[85rem] py-6 lg:py-3 px-8 mx-auto">
    <!-- Card -->
    <div class="bg-white rounded-xl shadow p-4 sm:p-7 dark:bg-slate-900">
        <?= view('components/form/judul', ['judul' => 'Input Pasien Meninggal']) ?>

        <form action="<?= base_url('/pasienmeninggal/simpanTambah') ?>" method="post" id="myForm" onsubmit="return validateForm()">
            <?= csrf_field() ?>

            <!-- Modal Pilih Pasien -->
            <div id="modalPasien" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center">
                <div class="bg-white rounded-xl px-6 py-4 w-full max-w-md max-h-[75vh] overflow-y-auto shadow-lg">
                    <div class="flex justify-between items-center mb-3">
                        <h2 class="text-base font-semibold text-gray-800">Pilih Pasien</h2>
                        <button onclick="closeModalPasien()" class="text-gray-500 hover:text-red-600 text-xl font-bold">&times;</button>
                    </div>
                    <table class="w-full text-sm text-center text-gray-700 border border-gray-300">
                        <thead style="background-color: #E6F2EF;">
                            <tr>
                                <th class="p-2 border">No. RM</th>
                                <th class="p-2 border">Nama Pasien</th>
                                <th class="p-2 border">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="pasienTable">
                            <!-- Data dari AJAX -->
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Nomor RM dan Nama -->
            <div class="mb-5 sm:block md:flex items-center">
                <!-- Label No. RM -->
                <label for="no_rkm_medis" class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">
                    No. Rekam Medis <span class="text-red-600">*</span>
                </label>

                <!-- Input RM + Icon Modal -->
                <div class="relative w-full md:w-1/4">
                    <input type="text" id="no_rkm_medis" name="no_rkm_medis"
                        value="<?= old('no_rkm_medis', $form_data['no_rkm_medis'] ?? '') ?>"
                        class="block w-full p-2 text-sm text-gray-900 border border-gray-300 rounded-lg pr-10 dark:border-gray-600 dark:text-white"
                        readonly required>
                    <button type="button" onclick="openModalPasien()" title="Pilih Pasien"
                        class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-blue-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M18 13v6a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2h6m5-3h5m0 0v5m0-5L10 14" />
                        </svg>
                    </button>
                </div>

                <!-- Label + Input Nama Pasien -->
                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Nama Pasien<span class="text-red-600">*</span></label>
                <input type="text" id="nm_pasien" name="nm_pasien"
                    value="<?= old('nm_pasien', $form_data['nm_pasien'] ?? '') ?>"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white"
                    readonly required>
            </div>


            <!-- Jenis Kelamin dan Golongan Darah -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Jenis Kelamin<span class="text-red-600">*</span></label>
                <select id="jk" name="jk"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" readonly required>
                    <option value="" disabled <?= old('jk', $form_data['jk'] ?? '') === '' ? 'selected' : '' ?>>-- Pilih --</option>
                    <option value="L" <?= old('jk', $form_data['jk'] ?? '') === 'L' ? 'selected' : '' ?>>Laki-laki</option>
                    <option value="P" <?= old('jk', $form_data['jk'] ?? '') === 'P' ? 'selected' : '' ?>>Perempuan</option>
                </select>

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Golongan Darah<span class="text-red-600">*</span></label>
                <select id="gol_darah" name="gol_darah"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" readonly required>
                    <option value="" disabled <?= old('gol_darah', $form_data['gol_darah'] ?? '') === '' ? 'selected' : '' ?>>-- Pilih --</option>
                    <?php foreach (['A', 'B', 'AB', 'O'] as $gd): ?>
                        <option value="<?= $gd ?>" <?= old('gol_darah', $form_data['gol_darah'] ?? '') === $gd ? 'selected' : '' ?>><?= $gd ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Tanggal Lahir dan Umur -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Tanggal Lahir</label>
                <input type="date" id="tgl_lahir" name="tgl_lahir"
                    value="<?= old('tgl_lahir', $form_data['tgl_lahir'] ?? '') ?>"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" readonly required>

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Umur</label>
                <input type="text" id="umur" name="umur"
                    value="<?= old('umur', $form_data['umur'] ?? '') ?>"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" readonly required>
            </div>

            <!-- Status Nikah dan Agama -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Status Pernikahan</label>
                <select id="stts_nikah" name="stts_nikah"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" readonly required>
                    <option value="" disabled <?= old('stts_nikah', $form_data['stts_nikah'] ?? '') === '' ? 'selected' : '' ?>>-- Pilih --</option>
                    <?php foreach (['MENIKAH', 'BELUM MENIKAH', 'JANDA', 'DUDA'] as $opt): ?>
                        <option value="<?= $opt ?>" <?= old('stts_nikah', $form_data['stts_nikah'] ?? '') === $opt ? 'selected' : '' ?>><?= $opt ?></option>
                    <?php endforeach; ?>
                </select>

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Agama</label>
                <select id="agama" name="agama"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" readonly required>
                    <option value="" disabled <?= old('agama', $form_data['agama'] ?? '') === '' ? 'selected' : '' ?>>-- Pilih --</option>
                    <?php foreach (['ISLAM', 'KRISTEN', 'KATOLIK', 'HINDU', 'BUDHA', 'KONG HU CHU', '-'] as $opt): ?>
                        <option value="<?= $opt ?>" <?= old('agama', $form_data['agama'] ?? '') === $opt ? 'selected' : '' ?>><?= $opt ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Tanggal & Jam Meninggal -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Tanggal Meninggal</label>
                <input type="date" id="tanggal" name="tanggal"
                    value="<?= old('tanggal', $form_data['tanggal'] ?? '') ?>"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white">

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Jam Meninggal</label>
                <input type="time" id="jam" name="jam"
                    value="<?= old('jam', $form_data['jam'] ?? '') ?>"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white">
            </div>

            <!-- ICDX Utama dan Sebab Langsung -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">ICDX Utama</label>
                <input type="text" id="icdx" name="icdx"
                    value="<?= old('icdx', $form_data['icdx'] ?? '') ?>"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white">

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">ICDX Langsung</label>
                <input type="text" id="icdx_langsung" name="icdx_langsung"
                    value="<?= old('icdx_langsung', $form_data['icdx_langsung'] ?? '') ?>"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white">
            </div>

            <!-- ICDX Antara 1 & 2 -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">ICDX Antara 1</label>
                <input type="text" id="icdx_antara1" name="icdx_antara1"
                    value="<?= old('icdx_antara1', $form_data['icdx_antara1'] ?? '') ?>"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white">

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">ICDX Antara 2</label>
                <input type="text" id="icdx_antara2" name="icdx_antara2"
                    value="<?= old('icdx_antara2', $form_data['icdx_antara2'] ?? '') ?>"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white">
            </div>

            <!-- Keterangan -->
            <div class="mb-5">
                <label class="block mb-2 text-sm text-gray-900 dark:text-white">Keterangan Tambahan</label>
                <textarea id="keterangan" name="keterangan" rows="3"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full dark:border-gray-600 dark:text-white"><?= old('keterangan', $form_data['keterangan'] ?? '') ?></textarea>
            </div>

            <!-- Dokter -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Kode Dokter</label>
                <input type="text" id="kode_dokter" name="kode_dokter"
                    value="<?= old('kode_dokter', $form_data['kode_dokter'] ?? '') ?>"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white">

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Nama Dokter</label>
                <input type="text" id="nama_dokter" name="nama_dokter"
                    value="<?= old('nama_dokter', $form_data['nama_dokter'] ?? '') ?>"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white">
            </div>

            <!-- Button -->
            <div class="mt-5 pt-5 border-t flex justify-end gap-x-2">
                <a href="javascript:history.back()" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-white dark:hover:bg-gray-800 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                    Kembali
                </a>
                <button type="submit" id="submitButton" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-[#0A2D27] text-[#ACF2E7] hover:bg-[#13594E]">
                    Simpan
                </button>
            </div>

        </form>
    </div>
    <!-- End Card -->
</div>
<!-- End Card Section -->

<!-- Script Validasi -->
<script>
    function validateForm() {
        const requiredFields = document.querySelectorAll('select[required], input[required]');
        for (let i = 0; i < requiredFields.length; i++) {
            if (!requiredFields[i].value) {
                alert("Isi semua field.");
                return false;
            }
        }
        const submitButton = document.getElementById('submitButton');
        submitButton.setAttribute('disabled', true);
        submitButton.innerHTML = 'Menyimpan...';
        return true;
    }

    document.addEventListener('DOMContentLoaded', function() {
        const noRMInput = document.querySelector('input[name="no_rkm_medis"]');

        async function fetchPasienByRM() {
            const noRM = noRMInput.value.trim();
            if (!noRM) return;

            try {
                const response = await fetch(`<?= base_url('index.php/api/fetch-pasien-by-rm') ?>?no_rkm_medis=${encodeURIComponent(noRM)}`);
                const result = await response.json();
                const pasien = result.data;

                if (!pasien || !pasien.no_rkm_medis) {
                    alert("Pasien tidak ditemukan.");
                    return;
                }

                // Autofill field
                document.querySelector('input[name="nm_pasien"]').value = pasien.nm_pasien || '';
                document.querySelector('select[name="jk"]').value = pasien.jk || '';
                document.querySelector('select[name="gol_darah"]').value = pasien.gol_darah || '';
                document.querySelector('input[name="tgl_lahir"]').value = pasien.tgl_lahir?.split('T')[0] || '';
                document.querySelector('input[name="umur"]').value = pasien.umur || '';
                const normalize = str => (str || '').trim().toUpperCase();
                document.querySelector('select[name="stts_nikah"]').value = normalize(pasien.stts_nikah);
                document.querySelector('select[name="agama"]').value = normalize(pasien.agama);


            } catch (err) {
                console.error('Gagal mengambil data pasien:', err);
            }
        }

        // Trigger blur saat tekan Enter
        noRMInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                noRMInput.blur();
            }
        });

        // Trigger autofill saat blur atau manual set
        noRMInput.addEventListener('blur', fetchPasienByRM);
        noRMInput.addEventListener('change', fetchPasienByRM); // ← penting
    });

    // ----------------- Modal -----------------
    function openModalPasien() {
        const modal = document.getElementById('modalPasien');
        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');

        fetch("/pasienmeninggal/listpasien")
            .then(res => res.json())
            .then(data => {
                const tbody = document.getElementById('pasienTable');
                tbody.innerHTML = "";

                if (data.data && Array.isArray(data.data)) {
                    data.data.forEach(pasien => {
                        const row = `
                            <tr class="border-b">
                                <td class="p-2 border">${pasien.no_rkm_medis}</td>
                                <td class="p-2 border">${pasien.nm_pasien}</td>
                                <td class="p-2 border text-center">
                                    <button type="button" onclick="selectPasien('${pasien.no_rkm_medis}')" style="color:#0A2D27" class="hover:underline">Pilih</button>
                                </td>
                            </tr>`;
                        tbody.insertAdjacentHTML('beforeend', row);
                    });
                } else {
                    tbody.innerHTML = `<tr><td colspan="3" class="text-center p-2 text-red-500">Data tidak ditemukan</td></tr>`;
                }
            })
            .catch(err => {
                console.error(err);
                document.getElementById('pasienTable').innerHTML =
                    `<tr><td colspan="3" class="text-center p-2 text-red-500">Gagal memuat data</td></tr>`;
            });
    }

    function closeModalPasien() {
        document.getElementById('modalPasien').classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }

    function selectPasien(nomorRM) {
        const inputRM = document.getElementById('no_rkm_medis');
        inputRM.value = nomorRM;

        // ✅ Trigger autofill fetch
        inputRM.dispatchEvent(new Event('change'));

        closeModalPasien();
    }
</script>


<?= $this->endSection(); ?>