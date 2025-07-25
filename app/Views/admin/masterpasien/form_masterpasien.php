<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>
<?= $this->include('components/modal/modalinstansi') ?>
<?= $this->include('components/modal/modalasuransi') ?>

<!-- Card Section -->
<div class="max-w-[85rem] py-6 lg:py-3 px-8 mx-auto">
    <!-- Card -->
    <div class="bg-white rounded-xl shadow p-4 sm:p-7 dark:bg-slate-900">
        <?= view('components/form/judul', ['judul' => $title ?? 'Form Pasien']) ?>

        <form action="<?= base_url(
                            ($mode ?? 'tambah') === 'ubah'
                                ? "/masterpasien/simpanUbah/{$no_rkm_medis}"
                                : "/masterpasien/simpanTambah"
                        ) ?>" method="post" id="myForm" onsubmit="return validateForm()">
            <?= csrf_field() ?>

            <!-- Nomor Rekam Medis dan Nama Pasien -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white w-1/5 lg:w-1/4">Nomor Rekam Medis<span class="text-red-600">*</span></label>
                <input type="text" id="no_rkm_medis"
                    value="<?= old('no_rkm_medis', $no_rkm_medis) ?>"
                    name="no_rkm_medis"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white"
                    <?= ($mode ?? 'tambah') === 'ubah' ? 'readonly' : '' ?> required readonly>

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Nama<span class="text-red-600">*</span></label>
                <input id="nm_pasien" name="nm_pasien"
                    value="<?= old('nm_pasien', $pasien['nm_pasien'] ?? '') ?>"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white"
                    required data-error="Nama Pasien wajib diisi.">
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

            <!-- Tempat Lahir dan Tanggal Lahir -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white w-1/5 lg:w-1/4">Tempat Lahir<span class="text-red-600">*</span></label>
                <input type="text" id="tmp_lahir" name="tmp_lahir"
                    value="<?= old('tmp_lahir', $pasien['tmp_lahir'] ?? '') ?>"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white"
                    required data-error="Tempat Lahir wajib diisi.">

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Tanggal Lahir<span class="text-red-600">*</span></label>
                <input type="date" id="tgl_lahir" name="tgl_lahir"
                    value="<?= old('tgl_lahir', $pasien['tgl_lahir'] ?? '') ?>"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white"
                    required data-error="Tanggal Lahir wajib diisi.">
            </div>

            <!-- Umur dan Pendidikan -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white w-1/5 lg:w-1/4">Umur<span class="text-red-600">*</span></label>
                <input type="text" id="umur" name="umur"
                    value="<?= old('umur', $pasien['umur'] ?? '') ?>"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white"
                    readonly required>

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Pendidikan<span class="text-red-600">*</span></label>
                <select id="pnd" name="pnd" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required data-error="Pendidikan wajib dipilih.">
                    <option value="" disabled <?= old('pnd', $pasien['pnd'] ?? '') === '' ? 'selected' : '' ?>>-- Pilih --</option>
                    <?php foreach (['Tidak Sekolah', 'TK', 'SD', 'SMP', 'SMA', 'SLTA/SEDERAJAT', 'D1', 'D2', 'D3', 'D4', 'S1', 'S2', 'S3'] as $edu): ?>
                        <option value="<?= $edu ?>" <?= old('pnd', $pasien['pnd'] ?? '') === $edu ? 'selected' : '' ?>><?= $edu ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Nama Ibu dan Pekerjaan -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white w-1/5 lg:w-1/4">Nama Ibu</label>
                <input type="text" id="nm_ibu" name="nm_ibu"
                    value="<?= old('nm_ibu', $pasien['nm_ibu'] ?? '') ?>"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white"
                    required data-error="Nama Ibu wajib diisi.">

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Pekerjaan</label>
                <input id="pekerjaan" name="pekerjaan"
                    value="<?= old('pekerjaan', $pasien['pekerjaan'] ?? '') ?>"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white">
            </div>

            <!-- Suku/Bangsa dan Bahasa -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white w-1/5 lg:w-1/4">Suku / Bangsa<span class="text-red-600">*</span></label>
                <input type="text" id="suku_bangsa" name="suku_bangsa"
                    value="<?= old('suku_bangsa', $pasien['suku_bangsa'] ?? '') ?>"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white"
                    required data-error="Suku/Bangsa wajib diisi.">

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Bahasa<span class="text-red-600">*</span></label>
                <input id="bahasa_pasien" name="bahasa_pasien"
                    value="<?= old('bahasa_pasien', $pasien['bahasa_pasien'] ?? '') ?>"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white"
                    required data-error="Bahasa Pasien wajib diisi.">
            </div>

            <!-- Cacat Fisik -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white w-1/5 lg:w-1/4">Cacat Fisik<span class="text-red-600">*</span></label>
                <input type="text" id="cacat_fisik" name="cacat_fisik"
                    value="<?= old('cacat_fisik', $pasien['cacat_fisik'] ?? '') ?>"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white"
                    required data-error="Cacat Fisik wajib diisi.">
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

            <!-- Asuransi dan No. Asuransi-->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white w-1/5 lg:w-1/4">
                    Asuransi<span class="text-red-600">*</span>
                </label>

                <!-- Input Hidden: hanya untuk backend -->
                <input type="hidden" id="asuransi" name="asuransi"
                    value="<?= old('asuransi', $pasien['asuransi'] ?? '') ?>">

                <!-- Input Display: untuk tampilkan nama asuransi -->
                <div class="relative w-full md:w-1/4">
                    <input type="text" id="asuransi_display"
                        value="<?= esc($pasien['asuransi'] ?? '') ?>"
                        class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 pr-10 w-full dark:border-gray-600 dark:text-white"
                        placeholder="Pilih Asuransi" readonly required data-error="Asuransi wajib diisi.">

                    <!-- Tombol buka modal -->
                    <button type="button"
                        onclick="open_modalAsuransi()"
                        class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-black cursor-pointer transition-colors duration-200"
                        title="Pilih Asuransi">
                        <?= rendericon('openmodal') ?>
                    </button>
                </div>

                <!-- Nomor Asuransi -->
                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">No. Asuransi / Polis</label>
                <input type="text" id="no_asuransi" name="no_asuransi" placeholder="Opsional, diisi jika ada"
                    value="<?= old('no_asuransi', $pasien['no_asuransi'] ?? '') ?>"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white">

            </div>

            <!-- Email -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block text-sm text-gray-900 dark:text-white w-1/5 lg:w-1/4 mb-2 md:mb-0">Email</label>
                <input id="email" name="email" placeholder="Opsional, diisi jika ada"

                    value="<?= old('email', $pasien['email'] ?? '') ?>"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white">
            </div>

            <!-- No. Telepon dan Tanggal Daftar -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white w-1/5 lg:w-1/4">No. Telepon<span class="text-red-600">*</span></label>
                <input type="text" id="no_tlp" name="no_tlp"
                    value="<?= old('no_tlp', $pasien['no_tlp'] ?? '') ?>"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white"
                    required data-error="No. Telepon wajib diisi.">

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Pertama Daftar<span class="text-red-600">*</span></label>
                <input type="date" id="tgl_daftar" name="tgl_daftar"
                    value="<?= old('tgl_daftar', $pasien['tgl_daftar'] ?? date('Y-m-d')) ?>"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white"
                    required data-error="Daftar wajib diisi.">
            </div>

            <!-- No. KTP/SIM -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block text-sm text-gray-900 dark:text-white w-1/5 lg:w-1/4 mb-2 md:mb-0">No. KTP / SIM</label>
                <input id="no_ktp" name="no_ktp"
                    value="<?= old('no_ktp', $pasien['no_ktp'] ?? '') ?>"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white">
            </div>


            <!-- Alamat -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white w-1/5 lg:w-1/4">Alamat Pasien<span class="text-red-600"></span></label>
                <input type="text" id="alamat" name="alamat" placeholder="Alamat"
                    value="<?= old('alamat', $pasien['alamat'] ?? '') ?>"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required data-error="Alamat wajib diisi.">
            </div>

            <!-- Kelurahan dan Kecamatan -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white w-1/5 lg:w-1/4"></label>
                <input type="text" id="kd_kel" name="kd_kel" placeholder="Kelurahan"
                    value="<?= old('kd_kel', $pasien['kd_kel'] ?? '') ?>"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white">

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5"></label>
                <input id="kd_kec" name="kd_kec" placeholder="Kecamatan"
                    value="<?= old('kd_kec', $pasien['kd_kec'] ?? '') ?>"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white">
            </div>

            <!-- Kabupaten dan Provinsi -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white w-1/5 lg:w-1/4"></label>
                <input type="text" id="kd_kab" name="kd_kab" placeholder="Kabupaten/Kota"
                    value="<?= old('kd_kab', $pasien['kd_kab'] ?? '') ?>"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white">

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5"></label>
                <input id="kd_prop" name="kd_prop" placeholder="Provinsi"
                    value="<?= old('kd_prop', $pasien['kd_prop'] ?? '') ?>"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white">
            </div>

            <!-- Instansi dan NIP/NRP -->
            <div class="mb-5 sm:block md:flex items-center">
                <!-- Label Instansi -->
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white w-1/5 lg:w-1/4">
                    Instansi Pasien
                </label>

                <!-- Input Instansi + Tombol Modal -->
                <div class="relative w-full md:w-1/4">
                    <!-- Hidden: dikirim ke backend (kode_instansi) -->
                    <input type="hidden" name="perusahaan_pasien" id="perusahaan_pasien"
                        value="<?= esc($pasien['perusahaan_pasien'] ?? '') ?>">

                    <!-- Display: hanya tampilkan nama instansi ke user -->
                    <input type="text" id="perusahaan_pasien_display"
                        value="<?= esc($pasien['perusahaan_pasien'] ?? '') ?>"
                        class="block w-full p-2 text-sm text-gray-900 border border-gray-300 rounded-lg pr-10 dark:border-gray-600 dark:text-white" placeholder="Pilih Instansi"
                        readonly>

                    <button type="button"
                        onclick="open_modalInstansi()"
                        class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-black cursor-pointer transition-colors duration-200"
                        title="Pilih Instansi">
                        <?= rendericon('openmodal') ?>
                    </button>

                </div>

                <!-- Nomor Induk Instansi -->
                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">
                    Nomor Induk Instansi
                </label>
                <input id="nip" name="nip" placeholder="Opsional, diisi jika ada"
                    value="<?= old('nip', $pasien['nip'] ?? '') ?>"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white">
            </div>

            <!-- Status Pasien -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white w-1/5 lg:w-1/4">Status Pasien<span class="text-red-600">*</span></label>
                <select id="stts_pasien" name="stts_pasien"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white"
                    required data-error="Status Pasien wajib dipilih."
                    <?= ($mode ?? 'tambah') === 'tambah' ? 'disabled' : '' ?>>
                    <option value="Aktif" <?= ($mode ?? 'tambah') === 'tambah' || old('stts_pasien', $pasien['stts_pasien'] ?? '') === 'Aktif' ? 'selected' : '' ?>>Aktif</option>
                    <?php if (($mode ?? 'tambah') === 'ubah'): ?>
                        <option value="Nonaktif" <?= old('stts_pasien', $pasien['stts_pasien'] ?? '') === 'Nonaktif' ? 'selected' : '' ?>>Nonaktif</option>
                        <option value="Meninggal" <?= old('stts_pasien', $pasien['stts_pasien'] ?? '') === 'Meninggal' ? 'selected' : '' ?>>Meninggal</option>
                    <?php endif; ?>
                </select>
            </div>

            <?php if (($mode ?? 'tambah') === 'tambah'): ?>
                <input type="hidden" name="stts_pasien" value="Aktif">
            <?php endif; ?>


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
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // 📅 Auto Hitung Umur
        const tglLahirInput = document.getElementById('tgl_lahir');
        const umurInput = document.getElementById('umur');

        function hitungUmur(tanggal) {
            const tgl = new Date(tanggal);
            const now = new Date();
            if (isNaN(tgl.getTime())) return '';
            let tahun = now.getFullYear() - tgl.getFullYear();
            let bulan = now.getMonth() - tgl.getMonth();
            let hari = now.getDate() - tgl.getDate();
            if (hari < 0) {
                bulan--;
                hari += new Date(now.getFullYear(), now.getMonth(), 0).getDate();
            }
            if (bulan < 0) {
                tahun--;
                bulan += 12;
            }
            return `${tahun} Th ${bulan} Bl ${hari} Hr`;
        }

        if (tglLahirInput && umurInput) {
            tglLahirInput.addEventListener('change', () => {
                umurInput.value = hitungUmur(tglLahirInput.value);
            });
            umurInput.addEventListener('keydown', e => e.preventDefault());
            umurInput.addEventListener('paste', e => e.preventDefault());
        }

        // 🔢 Validasi Angka
        function onlyNumber(selector) {
            const input = document.querySelector(selector);
            if (input) {
                input.addEventListener('input', function() {
                    this.value = this.value.replace(/[^0-9]/g, '');
                });
            }
        }
        ['#no_ktp', '#no_tlp', '#nip'].forEach(sel => onlyNumber(sel));
    });
</script>

<?= $this->endSection(); ?>