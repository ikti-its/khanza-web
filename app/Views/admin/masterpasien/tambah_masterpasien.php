<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>
<?= $this->include('components/modal/modalinstansi') ?>

<!-- Card Section -->
<div class="max-w-[85rem] py-6 lg:py-3 px-8 mx-auto">
    <!-- Card -->
    <div class="bg-white rounded-xl shadow p-4 sm:p-7 dark:bg-slate-900">
        <?= view('components/form/judul', ['judul' => 'Input Data Pasien']) ?>

        <form action="<?= base_url('/masterpasien/simpanTambah') ?>" method="post" id="myForm" onsubmit="return validateForm()">
            <?= csrf_field() ?>


            <!-- Nomor Rekam Medis dan Nama Pasien -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white w-1/5 lg:w-1/4">Nomor Rekam Medis<span class="text-red-600">*</span></label>
                <input type="text" id="no_rkm_medis"
                    value="<?= old('no_rkm_medis', $no_rkm_medis) ?>"
                    name="no_rkm_medis"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white"
                    readonly required>

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Nama<span class="text-red-600">*</span></label>
                <input id="nm_pasien" name="nm_pasien"
                    value="<?= old('nm_pasien', $form_data['nm_pasien'] ?? '') ?>"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white"
                    required data-error="Nama Pasien wajib diisi.">
            </div>

            <!-- Jenis Kelamin dan Golongan Darah -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Jenis Kelamin<span class="text-red-600">*</span></label>
                <select id="jk" name="jk" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required data-error="Jenis Kelamin Wajib Dipilih.">
                    <option value="" disabled <?= old('jk', $form_data['jk'] ?? '') === '' ? 'selected' : '' ?>>-- Pilih --</option>
                    <option value="L" <?= old('jk', $form_data['jk'] ?? '') === 'L' ? 'selected' : '' ?>>Laki-laki</option>
                    <option value="P" <?= old('jk', $form_data['jk'] ?? '') === 'P' ? 'selected' : '' ?>>Perempuan</option>
                </select>

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Golongan Darah</label>
                <select id="gol_darah" name="gol_darah" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white">
                    <option value="" disabled <?= old('gol_darah', $form_data['gol_darah'] ?? '') === '' ? 'selected' : '' ?>>-- Pilih --</option>
                    <?php foreach (['A', 'B', 'AB', 'O'] as $gd): ?>
                        <option value="<?= $gd ?>" <?= old('gol_darah', $form_data['gol_darah'] ?? '') === $gd ? 'selected' : '' ?>><?= $gd ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Tempat Lahir dan Tanggal Lahir -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white w-1/5 lg:w-1/4">Tempat Lahir<span class="text-red-600">*</span></label>
                <input type="text" id="tmp_lahir" name="tmp_lahir"
                    value="<?= old('tmp_lahir', $form_data['tmp_lahir'] ?? '') ?>"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white"
                    required data-error="Tempat Lahir wajib diisi.">

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Tanggal Lahir<span class="text-red-600">*</span></label>
                <input type="date" id="tgl_lahir" name="tgl_lahir"
                    value="<?= old('tgl_lahir', $form_data['tgl_lahir'] ?? '') ?>"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white"
                    required data-error="Tanggal Lahir wajib diisi.">
            </div>

            <!-- Umur dan Pendidikan -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white w-1/5 lg:w-1/4">Umur<span class="text-red-600">*</span></label>
                <input type="text" id="umur" name="umur"
                    value="<?= old('umur', $form_data['umur'] ?? '') ?>"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white"
                    readonly required>

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Pendidikan<span class="text-red-600">*</span></label>
                <select id="pnd" name="pnd" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required data-error="Pendidikan wajib dipilih.">
                    <option value="" disabled <?= old('pnd', $form_data['pnd'] ?? '') === '' ? 'selected' : '' ?>>-- Pilih --</option>
                    <?php foreach (['TS', 'TK', 'SD', 'SMP', 'SMA', 'SLTA/SEDERAJAT', 'D1', 'D2', 'D3', 'D4', 'S1', 'S2', 'S3'] as $edu): ?>
                        <option value="<?= $edu ?>" <?= old('pnd', $form_data['pnd'] ?? '') === $edu ? 'selected' : '' ?>><?= $edu ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Nama Ibu dan Penanggung Jawab -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white w-1/5 lg:w-1/4">Nama Ibu<span class="text-red-600">*</span></label>
                <input type="text" id="nm_ibu" name="nm_ibu"
                    value="<?= old('nm_ibu', $form_data['nm_ibu'] ?? '') ?>"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white"
                    required data-error="Nama Ibu wajib diisi.">

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Penanggung Jawab<span class="text-red-600">*</span></label>
                <select id="keluarga" name="keluarga" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required data-error="PJ wajib dipilih.">
                    <option value="" disabled <?= old('keluarga', $form_data['keluarga'] ?? '') === '' ? 'selected' : '' ?>>-- Pilih --</option>
                    <?php
                    $options_keluarga = ['DIRI SENDIRI', 'AYAH', 'IBU', 'ISTRI', 'SUAMI', 'ANAK', 'SAUDARA', 'LAIN-LAIN'];
                    foreach ($options_keluarga as $opt): ?>
                        <option value="<?= $opt ?>" <?= old('keluarga', $form_data['keluarga'] ?? '') === $opt ? 'selected' : '' ?>><?= $opt ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Nama PJ dan Pekerjaan -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white w-1/5 lg:w-1/4">Nama Penanggung Jawab<span class="text-red-600">*</span></label>
                <input type="text" id="namakeluarga" name="namakeluarga"
                    value="<?= old('namakeluarga', $form_data['namakeluarga'] ?? '') ?>"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required data-error="Nama PJ wajib diisi.">

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Pekerjaan<span class="text-red-600">*</span></label>
                <input id="pekerjaan" name="pekerjaan"
                    value="<?= old('pekerjaan', $form_data['pekerjaan'] ?? '') ?>"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required data-error="Pekerjaan wajib diisi.">
            </div>

            <!-- Suku/Bangsa dan Bahasa -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white w-1/5 lg:w-1/4">Suku/Bangsa<span class="text-red-600">*</span></label>
                <input type="text" id="suku_bangsa" name="suku_bangsa"
                    value="<?= old('suku_bangsa', $form_data['suku_bangsa'] ?? '') ?>"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white"
                    required data-error="Suku/Bangsa wajib diisi.">

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Bahasa<span class="text-red-600">*</span></label>
                <input id="bahasa_pasien" name="bahasa_pasien"
                    value="<?= old('bahasa_pasien', $form_data['bahasa_pasien'] ?? '') ?>"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white"
                    required data-error="Bahasa Pasien wajib diisi.">
            </div>

            <!-- Cacat Fisik -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white w-1/5 lg:w-1/4">Cacat Fisik<span class="text-red-600">*</span></label>
                <input type="text" id="cacat_fisik" name="cacat_fisik"
                    value="<?= old('cacat_fisik', $form_data['cacat_fisik'] ?? '') ?>"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white"
                    required data-error="Cacat Fisik wajib diisi.">
            </div>

            <!-- Agama dan Status Pernikahan -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white w-1/5 lg:w-1/4">Agama<span class="text-red-600">*</span></label>
                <select id="agama" name="agama" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required data-error="agama wajib dipilih.">
                    <option value="" disabled <?= old('agama', $form_data['agama'] ?? '') === '' ? 'selected' : '' ?>>-- Pilih --</option>
                    <?php foreach (['ISLAM', 'KRISTEN', 'KATOLIK', 'HINDU', 'BUDHA', 'KONG HU CHU', '-'] as $agama): ?>
                        <option value="<?= $agama ?>" <?= old('agama', $form_data['agama'] ?? '') === $agama ? 'selected' : '' ?>><?= $agama ?></option>
                    <?php endforeach; ?>
                </select>

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Status Pernikahan<span class="text-red-600">*</span></label>
                <select id="stts_nikah" name="stts_nikah" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required data-error="Status Pernikahan wajib dipilih.">
                    <option value="" disabled <?= old('stts_nikah', $form_data['stts_nikah'] ?? '') === '' ? 'selected' : '' ?>>-- Pilih --</option>
                    <?php foreach (['MENIKAH', 'BELUM MENIKAH', 'JANDA', 'DUDA'] as $status): ?>
                        <option value="<?= $status ?>" <?= old('stts_nikah', $form_data['stts_nikah'] ?? '') === $status ? 'selected' : '' ?>><?= $status ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Asuransi -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white w-1/5 lg:w-1/4">Asuransi<span class="text-red-600">*</span></label>
                <input type="text" id="kd_pj" name="kd_pj"
                    value="<?= old('kd_pj', $form_data['kd_pj'] ?? '') ?>"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required data-error="Asuransi wajib diisi.">
            </div>

            <!-- No. Peserta dan Email -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white w-1/5 lg:w-1/4">No. Peserta</label>
                <input type="text" id="no_peserta" name="no_peserta"
                    value="<?= old('no_peserta', $form_data['no_peserta'] ?? '') ?>"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white">

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Email</label>
                <input id="email" name="email"
                    value="<?= old('email', $form_data['email'] ?? '') ?>"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white">
            </div>

            <!-- No. Telepon dan Tanggal Daftar -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white w-1/5 lg:w-1/4">No. Telepon<span class="text-red-600">*</span></label>
                <input type="text" id="no_tlp" name="no_tlp"
                    value="<?= old('no_tlp', $form_data['no_tlp'] ?? '') ?>"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white"
                    required data-error="No. Telepon wajib diisi.">

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Pertama Daftar<span class="text-red-600">*</span></label>
                <input type="date" id="tgl_daftar" name="tgl_daftar"
                    value="<?= old('tgl_daftar', $form_data['tgl_daftar'] ?? date('Y-m-d')) ?>"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white"
                    required data-error="Daftar wajib diisi.">
            </div>

            <!-- Pekerjaan PJ dan No. KTP/SIM -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white w-1/5 lg:w-1/4">Pekerjaan Penanggung Jawab<span class="text-red-600">*</span></label>
                <input id="pekerjaanpj" name="pekerjaanpj"
                    value="<?= old('pekerjaanpj', $form_data['pekerjaanpj'] ?? '') ?>"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white">

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">No. KTP/SIM<span class="text-red-600">*</span></label>
                <input id="no_ktp" name="no_ktp"
                    value="<?= old('no_ktp', $form_data['no_ktp'] ?? '') ?>"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required data-error="No. KTP/SIM wajib diisi.">
            </div>

            <!-- Alamat -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white w-1/5 lg:w-1/4">Alamat Pasien<span class="text-red-600"></span></label>
                <input type="text" id="alamat" name="alamat"
                    value="<?= old('alamat', $form_data['alamat'] ?? '') ?>"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required data-error="Alamat wajib diisi.">
            </div>

            <!-- Kelurahan dan Kecamatan -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white w-1/5 lg:w-1/4"></label>
                <input type="text" id="kd_kel" name="kd_kel" placeholder="Kelurahan"
                    value="<?= old('kd_kel', $form_data['kd_kel'] ?? '') ?>"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white">

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5"></label>
                <input id="kd_kec" name="kd_kec" placeholder="Kecamatan"
                    value="<?= old('kd_kec', $form_data['kd_kec'] ?? '') ?>"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white">
            </div>

            <!-- Kabupaten dan Provinsi -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white w-1/5 lg:w-1/4"></label>
                <input type="text" id="kd_kab" name="kd_kab" placeholder="Kabupaten/Kota"
                    value="<?= old('kd_kab', $form_data['kd_kab'] ?? '') ?>"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white">

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5"></label>
                <input id="kd_prop" name="kd_prop" placeholder="Provinsi"
                    value="<?= old('kd_prop', $form_data['kd_prop'] ?? '') ?>"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white">
            </div>

            <!-- Alamat PJ -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white w-1/5 lg:w-1/4">
                    Alamat PJ
                </label>

                <div class="relative w-full md:w-1/4">
                    <input type="text" id="alamatpj" name="alamatpj"
                        value="<?= old('alamatpj', $form_data['alamatpj'] ?? '') ?>"
                        class="pr-10 border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full dark:border-gray-600 dark:text-white">

                    <!-- Tombol salin -->
                    <button type="button" id="copyAlamatPJ"
                        class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-500 hover:text-black"
                        title="Salin alamat dari pasien">
                        <!-- Heroicon: Clipboard Document -->
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12h6m-6 4h6m2 4H7a2 2 0 01-2-2V6a2 2 0 012-2h3.5a.5.5 0 01.5.5V5a1 1 0 001 1h3a1 1 0 001-1v-.5a.5.5 0 01.5-.5H17a2 2 0 012 2v14a2 2 0 01-2 2z" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Kelurahan dan Kecamatan PJ -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white w-1/5 lg:w-1/4"></label>
                <input type="text" id="kelurahanpj" name="kelurahanpj" placeholder="Kelurahan PJ"
                    value="<?= old('kelurahanpj', $form_data['kelurahanpj'] ?? '') ?>"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white">

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5"></label>
                <input id="kecamatanpj" name="kecamatanpj" placeholder="Kecamatan PJ"
                    value="<?= old('kecamatanpj', $form_data['kecamatanpj'] ?? '') ?>"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white">
            </div>

            <!-- Kabupaten dan Provinsi PJ -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white w-1/5 lg:w-1/4"></label>
                <input type="text" id="kabupatenpj" name="kabupatenpj" placeholder="Kabupaten/Kota PJ"
                    value="<?= old('kabupatenpj', $form_data['kabupatenpj'] ?? '') ?>"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white">

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5"></label>
                <input id="propinsipj" name="propinsipj" placeholder="Provinsi PJ"
                    value="<?= old('propinsipj', $form_data['propinsipj'] ?? '') ?>"
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
                        value="<?= esc($form_data['perusahaan_pasien'] ?? '') ?>">

                    <!-- Display: hanya tampilkan nama instansi ke user -->
                    <input type="text" id="perusahaan_pasien_display"
                        value="<?= esc($form_data['nama_instansi'] ?? '') ?>"
                        class="block w-full p-2 text-sm text-gray-900 border border-gray-300 rounded-lg pr-10 dark:border-gray-600 dark:text-white"
                        readonly>

                    <button type="button"
                        onclick="openModalInstansi()"
                        class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-black cursor-pointer transition-colors duration-200"
                        title="Pilih Instansi">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M18 13v6a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2h6m5-3h5m0 0v5m0-5L10 14" />
                        </svg>
                    </button>

                </div>

                <!-- Label NIP -->
                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">
                    NIP/NRP
                </label>

                <!-- Input NIP -->
                <input id="nip" name="nip"
                    value="<?= old('nip', $form_data['nip'] ?? '') ?>"
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
<script src="<?= base_url('js/form-validation.js') ?>"></script>
<script src="<?= base_url('js/form-masterpasien.js') ?>"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const btn = document.getElementById('copyAlamatPJ');
        if (btn) {
            btn.addEventListener('click', function() {
                console.log("📋 Tombol diklik!");

                const alamat = document.getElementById('alamat')?.value || '';
                const kel = document.getElementById('kd_kel')?.value || '';
                const kec = document.getElementById('kd_kec')?.value || '';
                const kab = document.getElementById('kd_kab')?.value || '';
                const prop = document.getElementById('kd_prop')?.value || '';

                document.getElementById('alamatpj').value = alamat;
                document.getElementById('kelurahanpj').value = kel;
                document.getElementById('kecamatanpj').value = kec;
                document.getElementById('kabupatenpj').value = kab;
                document.getElementById('propinsipj').value = prop;
            });
        }
    });
</script>


<?= $this->endSection(); ?>