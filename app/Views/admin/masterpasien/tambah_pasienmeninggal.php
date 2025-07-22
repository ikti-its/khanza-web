<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>
<?= $this->include('components/modal/modalpasien') ?>
<?= $this->include('components/modal/modaldokter') ?>

<!-- Card Section -->
<div class="max-w-[85rem] py-6 lg:py-3 px-8 mx-auto">
    <!-- Card -->
    <div class="bg-white rounded-xl shadow p-4 sm:p-7 dark:bg-slate-900">
        <?= view('components/form/judul', ['judul' => $title ?? 'Input Pasien Meninggal']) ?>

        <form action="<?= base_url(
                            ($mode ?? 'tambah') === 'ubah'
                                ? "/pasienmeninggal/simpanUbah/{$no_rkm_medis}"
                                : "/pasienmeninggal/simpanTambah"
                        ) ?>" method="post" id="myForm" onsubmit="return validateForm()">
            <?= csrf_field() ?>

            <!-- Nomor Rekam Medis dan Nama -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white w-1/5 lg:w-1/4">
                    Nomor Rekam Medis<span class="text-red-600">*</span>
                </label>
                <div class="relative w-full md:w-1/4">
                    <input type="text" id="no_rkm_medis" name="no_rkm_medis"
                        value="<?= old('no_rkm_medis', $no_rkm_medis ?? '') ?>"
                        class="block w-full p-2 text-sm text-gray-900 border border-gray-300 rounded-lg pr-10 dark:border-gray-600 dark:text-white"
                        placeholder="Nomor RM" required <?= ($mode ?? 'tambah') === 'ubah' ? 'readonly' : '' ?>>
                    <?php if (($mode ?? 'tambah') !== 'ubah'): ?>
                        <button type="button" onclick="open_modalPasien()"
                            class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-black cursor-pointer transition-colors duration-200"
                            title="Pilih Pasien">
                            <?= rendericon('openmodal') ?>
                        </button>
                    <?php endif; ?>

                </div>

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">
                    Nama<span class="text-red-600">*</span>
                </label>
                <input id="nm_pasien" name="nm_pasien"
                    value="<?= old('nm_pasien', $pasien['nm_pasien'] ?? '') ?>"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white"
                    readonly required>
            </div>

            <!-- Jenis Kelamin dan Golongan Darah -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Jenis Kelamin<span class="text-red-600">*</span></label>
                <select id="jk" name="jk" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required data-error="Jenis Kelamin Wajib Dipilih.">
                    <option value="" disabled <?= old('jk', $pasien['jk'] ?? '') === '' ? 'selected' : '' ?>>-- Pilih --</option>
                    <option value="L" <?= old('jk', $pasien['jk'] ?? '') === 'L' ? 'selected' : '' ?>>Laki-laki</option>
                    <option value="P" <?= old('jk', $pasien['jk'] ?? '') === 'P' ? 'selected' : '' ?>>Perempuan</option>
                </select>

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Golongan Darah</label>
                <select id="gol_darah" name="gol_darah" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white">
                    <option value="" disabled <?= old('gol_darah', $pasien['gol_darah'] ?? '') === '' ? 'selected' : '' ?>>-- Pilih --</option>
                    <?php foreach (['A', 'B', 'AB', 'O'] as $gd): ?>
                        <option value="<?= $gd ?>" <?= old('gol_darah', $pasien['gol_darah'] ?? '') === $gd ? 'selected' : '' ?>><?= $gd ?></option>
                    <?php endforeach; ?>
                </select>
            </div>


            <!-- Tanggal Lahir dan Umur -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Tanggal Lahir<span class="text-red-600">*</span></label>
                <input type="date" id="tgl_lahir" name="tgl_lahir"
                    value="<?= old('tgl_lahir', $pasien['tgl_lahir'] ?? '') ?>"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" readonly required>

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Umur<span class="text-red-600">*</span></label>
                <input type="text" id="umur" name="umur"
                    value="<?= old('umur', $pasien['umur'] ?? '') ?>"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" readonly required>
            </div>

            <!-- Agama dan Status Pernikahan -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white w-1/5 lg:w-1/4">Agama<span class="text-red-600">*</span></label>
                <select id="agama" name="agama" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required data-error="agama wajib dipilih.">
                    <option value="" disabled <?= old('agama', $pasien['agama'] ?? '') === '' ? 'selected' : '' ?>>-- Pilih --</option>
                    <?php foreach (['ISLAM', 'KRISTEN', 'KATOLIK', 'HINDU', 'BUDHA', 'KONG HU CHU', '-'] as $agama): ?>
                        <option value="<?= $agama ?>" <?= old('agama', $pasien['agama'] ?? '') === $agama ? 'selected' : '' ?>><?= $agama ?></option>
                    <?php endforeach; ?>
                </select>

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Status Pernikahan<span class="text-red-600">*</span></label>
                <select id="stts_nikah" name="stts_nikah" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required data-error="Status Pernikahan wajib dipilih.">
                    <option value="" disabled <?= old('stts_nikah', $pasien['stts_nikah'] ?? '') === '' ? 'selected' : '' ?>>-- Pilih --</option>
                    <?php foreach (['MENIKAH', 'BELUM MENIKAH', 'JANDA', 'DUDA'] as $status): ?>
                        <option value="<?= $status ?>" <?= old('stts_nikah', $pasien['stts_nikah'] ?? '') === $status ? 'selected' : '' ?>><?= $status ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Tanggal & Jam Meninggal -->
            <div class="mb-5 sm:block md:flex items-center">
                <!-- Tanggal Meninggal -->
                <label for="tanggal" class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">
                    Tanggal Meninggal<span class="text-red-600">*</span>
                </label>
                <input type="date" id="tanggal" name="tanggal"
                    value="<?= old('tanggal', $pasien['tanggal'] ?? '') ?>"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white"
                    required data-error="Tanggal meninggal wajib diisi.">

                <!-- Jam Meninggal -->
                <label for="jam" class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">
                    Jam Meninggal<span class="text-red-600">*</span>
                </label>
                <input type="time" id="jam" name="jam"
                    value="<?= old('jam', $pasien['jam'] ?? '') ?>"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white"
                    required data-error="Jam meninggal tidak boleh kosong.">
            </div>


            <!-- ICDX Utama dan Sebab Langsung -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">ICDX Utama</label>
                <input type="text" id="icdx" name="icdx"
                    value="<?= old('icdx', $pasien['icdx'] ?? '') ?>"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white">

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">ICDX Langsung</label>
                <input type="text" id="icdx_langsung" name="icdx_langsung"
                    value="<?= old('icdx_langsung', $pasien['icdx_langsung'] ?? '') ?>"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white">
            </div>

            <!-- ICDX Antara 1 & 2 -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">ICDX Antara 1</label>
                <input type="text" id="icdx_antara1" name="icdx_antara1"
                    value="<?= old('icdx_antara1', $pasien['icdx_antara1'] ?? '') ?>"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white">

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">ICDX Antara 2</label>
                <input type="text" id="icdx_antara2" name="icdx_antara2"
                    value="<?= old('icdx_antara2', $pasien['icdx_antara2'] ?? '') ?>"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white">
            </div>

            <!-- Keterangan -->
            <div class="mb-5">
                <label class="block mb-2 text-sm text-gray-900 dark:text-white">Keterangan Tambahan</label>
                <textarea id="keterangan" name="keterangan" rows="3"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full dark:border-gray-600 dark:text-white"><?= old('keterangan', $pasien['keterangan'] ?? '') ?></textarea>
            </div>

            <!-- Kode Dokter dan Nama Dokter -->
            <div class="mb-5 sm:block md:flex items-center">
                <!-- Label Kode Dokter -->
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">
                    Kode Dokter<span class="text-red-600">*</span>
                </label>

                <!-- Input Kode Dokter + Tombol Modal -->
                <div class="relative w-full md:w-1/4">
                    <input type="text" id="kode_dokter" name="kode_dokter"
                        value="<?= old('kode_dokter', $pasien['kode_dokter'] ?? '') ?>"
                        class="block w-full p-2 text-sm text-gray-900 border border-gray-300 rounded-lg pr-10 dark:border-gray-600 dark:text-white"
                        placeholder="Pilih Dokter" readonly required>

                    <button type="button" onclick="open_modalDokter()"
                        class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-black cursor-pointer"
                        title="Pilih Dokter">
                        <?= rendericon('openmodal') ?>
                    </button>
                </div>

                <!-- Label Nama Dokter -->
                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">
                    Nama Dokter<span class="text-red-600">*</span>
                </label>

                <input type="text" id="nama_dokter" name="nama_dokter"
                    value="<?= old('nama_dokter', $pasien['nama_dokter'] ?? '') ?>"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white"
                    readonly required>
            </div>

            <!-- Button -->
            <div class="mt-5 pt-5 border-t flex justify-end gap-x-2">
                <a href="javascript:history.back()" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-white dark:hover:bg-gray-800 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                    Kembali
                </a>
                <button type="submit" id="submitButton" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-[#0A2D27] text-[#ACF2E7] hover:bg-[#13594E]">
                    <?= ($mode ?? 'tambah') === 'ubah' ? 'Perbarui' : 'Simpan' ?>
                </button>
            </div>

        </form>
    </div>
    <!-- End Card -->
</div>
<!-- End Card Section -->

<!-- Script Validasi -->
<script src="<?= base_url('js/form-validation.js') ?>"></script>

<?= $this->endSection(); ?>