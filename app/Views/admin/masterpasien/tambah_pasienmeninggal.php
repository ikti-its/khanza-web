<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<!-- Card Section -->
<div class="max-w-[85rem] py-6 lg:py-3 px-8 mx-auto">
    <!-- Card -->
    <div class="bg-white rounded-xl shadow p-4 sm:p-7 dark:bg-slate-900">
        <?= view('components/form/judul', ['judul' => 'Input Pasien Meninggal']) ?>

        <form action="<?= base_url('/pasienmeninggal/simpanTambah') ?>" method="post" id="myForm" onsubmit="return validateForm()">
            <?= csrf_field() ?>




            <!-- Nomor RM dan Nama -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">No. Rekam Medis</label>
                <input type="text" id="no_rkm_medis" name="no_rkm_medis"
                    value="<?= old('no_rkm_medis', $form_data['no_rkm_medis'] ?? '') ?>"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Nama Pasien<span class="text-red-600">*</span></label>
                <input type="text" id="nm_pasien" name="nm_pasien"
                    value="<?= old('nm_pasien', $form_data['nm_pasien'] ?? '') ?>"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" readonly required>
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
        var requiredFields = document.querySelectorAll('select[required], input[required]');
        for (var i = 0; i < requiredFields.length; i++) {
            if (!requiredFields[i].value) {
                alert("Isi semua field.");
                return false;
            }
        }
        var submitButton = document.getElementById('submitButton');
        submitButton.setAttribute('disabled', true);
        submitButton.innerHTML = 'Menyimpan...';
        return true;
    }
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const noRMInput = document.querySelector('input[name="no_rkm_medis"]');
        const form = document.getElementById('myForm');

        // Enter langsung trigger blur
        noRMInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                noRMInput.blur();
            }
        });

        noRMInput.addEventListener('blur', async function() {
            const noRM = this.value.trim();
            if (!noRM) return;

            try {
                const response = await fetch(`<?= base_url('index.php/api/fetch-pasien-by-rm') ?>?no_rkm_medis=${encodeURIComponent(noRM)}`);
                const result = await response.json();
                const pasien = result.data;

                if (!pasien || !pasien.no_rkm_medis) {
                    alert("Pasien tidak ditemukan.");
                    return;
                }

                // Isi field dasar
                document.querySelector('input[name="nm_pasien"]').value = pasien.nm_pasien || '';
                document.querySelector('select[name="jk"]').value = pasien.jk || '';
                document.querySelector('select[name="gol_darah"]').value = pasien.gol_darah || '';
                document.querySelector('input[name="tgl_lahir"]').value = pasien.tgl_lahir?.split('T')[0] || '';

                // Perbandingan teks stts_nikah
                const nikahSelect = document.querySelector('select[name="stts_nikah"]');
                for (const opt of nikahSelect.options) {
                    if (opt.textContent.trim().toLowerCase() === (pasien.stts_nikah || '').toLowerCase()) {
                        nikahSelect.value = opt.value;
                        break;
                    }
                }

                // Perbandingan teks agama
                const agamaSelect = document.querySelector('select[name="agama"]');
                for (const opt of agamaSelect.options) {
                    if (opt.textContent.trim().toLowerCase() === (pasien.agama || '').toLowerCase()) {
                        agamaSelect.value = opt.value;
                        break;
                    }
                }

                // Hitung umur otomatis
                const umurField = document.querySelector('input[name="umur"]');
                if (umurField && pasien.tgl_lahir) {
                    const tglLahir = new Date(pasien.tgl_lahir);
                    const today = new Date();
                    let tahun = today.getFullYear() - tglLahir.getFullYear();
                    let bulan = today.getMonth() - tglLahir.getMonth();
                    let hari = today.getDate() - tglLahir.getDate();
                    if (hari < 0) {
                        bulan--;
                        hari += 30;
                    }
                    if (bulan < 0) {
                        tahun--;
                        bulan += 12;
                    }
                    umurField.value = `${tahun} Th ${bulan} Bl ${hari} Hr`;
                } else if (umurField) {
                    umurField.value = pasien.umur || '';
                }

            } catch (err) {
                alert("Pasien tidak ditemukan.");
                console.error(err);
            }
        });
    });
</script>







<?= $this->endSection(); ?>