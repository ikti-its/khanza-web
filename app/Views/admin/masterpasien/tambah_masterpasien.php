<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<!-- Card Section -->
<div class="max-w-[85rem] py-6 lg:py-3 px-8 mx-auto">
    <!-- Card -->
    <div class="bg-white rounded-xl shadow p-4 sm:p-7 dark:bg-slate-900">
        <?= view('components/form/judul', ['judul' => 'Tambah Pasien Manual']) ?>

        <form action="<?= base_url('/masterpasien/simpanTambah') ?>" method="post" id="myForm" onsubmit="return validateForm()">
            <?= csrf_field() ?>


            <!-- Nomor Rekam Medis dan Nama Pasien -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white w-1/5 lg:w-1/4">Nomor Rekam Medis</label>
                <input type="text" id="no_rkm_medis" value="<?= old('no_rkm_medis', $no_rkm_medis) ?>" name="no_rkm_medis" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" readonly required>

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Nama<span class="text-red-600">*</span></label>
                <input id="nm_pasien" name="nm_pasien" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
            </div>

            <!-- Jenis Kelamin -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Jenis Kelamin<span class="text-red-600">*</span></label>
                <select id="jk" name="jk" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
                    <option value="" selected disabled>-- Pilih --</option>
                    <option value="L">Laki-laki</option>
                    <option value="P">Perempuan</option>
                </select>

                <!-- Golongan Darah -->
                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Golongan Darah<span class="text-red-600">*</span></label>
                <select id="gol_darah" name="gol_darah" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
                    <option value="" selected disabled>-- Pilih --</option>
                    <option value="A">A</option>
                    <option value="B">B</option>
                    <option value="AB">AB</option>
                    <option value="O">O</option>
                </select>
            </div>

            <!-- Tempat Lahir dan Tanggal Lahir -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white w-1/5 lg:w-1/4">Tempat Lahir<span class="text-red-600">*</span></label>
                <input type="text" id="tmp_lahir" name="tmp_lahir" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Tanggal Lahir<span class="text-red-600">*</span></label>
                <input type="date" id="tgl_lahir" name="tgl_lahir" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
            </div>

            <!-- Umur dan Pendidikan -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white w-1/5 lg:w-1/4">Umur<span class="text-red-600">*</span></label>
                <input type="text" id="umur" name="umur" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" readonly required>

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Pendidikan<span class="text-red-600">*</span></label>
                <select id="pnd" name="pnd" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
                    <option value="" selected disabled>-- Pilih --</option>
                    <option value="TS">TS</option>
                    <option value="TK">TK</option>
                    <option value="SD">SD</option>
                    <option value="SMP">SMP</option>
                    <option value="SMA">SMA</option>
                    <option value="SLTA/SEDERAJAT">SLTA/SEDERAJAT</option>
                    <option value="D1">D1</option>
                    <option value="D2">D2</option>
                    <option value="D3">D3</option>
                    <option value="D4">D4</option>
                    <option value="S1">S1</option>
                    <option value="S2">S2</option>
                    <option value="S3">S3</option>
                </select>
            </div>

            <!-- Nama Ibu dan Penanggung Jawab -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white w-1/5 lg:w-1/4">Nama Ibu<span class="text-red-600">*</span></label>
                <input type="text" id="nm_ibu" name="nm_ibu" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Penanggung Jawab<span class="text-red-600">*</span></label>
                <select id="keluarga" name="keluarga" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
                    <option value="" selected disabled>-- Pilih --</option>
                    <option value="DIRI SENDIRI">DIRI SENDIRI</option>
                    <option value="AYAH">AYAH</option>
                    <option value="IBU">IBU</option>
                    <option value="ISTRI">ISTRI</option>
                    <option value="SUAMI">SUAMI</option>
                    <option value="ANAK">KAKAK</option>
                    <option value="SAUDARA">ADIK</option>
                    <option value="ISTRI">PAMAN</option>
                    <option value="SUAMI">BIBI</option>
                    <option value="ANAK">KAKEK</option>
                    <option value="SAUDARA">NENEK</option>
                    <option value="SAUDARA">SAUDARA</option>
                    <option value="LAIN-LAIN">LAIN-LAIN</option>
                </select>
            </div>

            <!-- Nama PJ dan Pekerjaan PJ -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white w-1/5 lg:w-1/4">Nama Penanggung Jawab</label>
                <input type="text" id="namakeluarga" name="namakeluarga" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white">

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Pekerjaan Penanggung Jawab</label>
                <input id="pekerjaanpj" name="pekerjaanpj" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white">
            </div>

            <!-- Suku/Bangsa dan Bahasa -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white w-1/5 lg:w-1/4">Suku/Bangsa<span class="text-red-600">*</span></label>
                <input type="text" id="suku_bangsa" name="suku_bangsa" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Bahasa<span class="text-red-600">*</span></label>
                <input id="bahasa_pasien" name="bahasa_pasien" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
            </div>

            <!-- Cacat Fisik -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white w-1/5 lg:w-1/4">Cacat Fisik<span class="text-red-600">*</span></label>
                <input type="text" id="cacat_fisik" name="cacat_fisik" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
            </div>

            <!-- Agama dan Status Pernikahan -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white w-1/5 lg:w-1/4">Agama<span class="text-red-600">*</span></label>
                <select type="text" id="agama" name="agama" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
                    <option value="" selected disabled>-- Pilih --</option>
                    <option value="ISLAM">ISLAM</option>
                    <option value="KRISTEN">KRISTEN</option>
                    <option value="KATOLIK">KATOLIK</option>
                    <option value="HINDU">HINDU</option>
                    <option value="BUDHA">BUDHA</option>
                    <option value="KONG HU CHU">KONG HU CHU</option>
                    <option value="-">-</option>
                </select>

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Status Pernikahan<span class="text-red-600">*</span></label>
                <select id="stts_nikah" name="stts_nikah" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
                    <option value="" selected disabled>-- Pilih --</option>
                    <option value="MENIKAH">MENIKAH</option>
                    <option value="BELUM MENIKAH">BELUM MENIKAH</option>
                    <option value="JANDA">JANDA</option>
                    <option value="DUDA">DUDA</option>
                </select>
            </div>

            <!-- Asuransi -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white w-1/5 lg:w-1/4">Asuransi</label>
                <input type="text" id="kd_pj" name="kd_pj" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white">
            </div>

            <!-- No. Peserta dan Email -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white w-1/5 lg:w-1/4">No. Peserta</label>
                <input type="text" id="no_peserta" name="no_peserta" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white">

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Email</label>
                <input id="email" name="email" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white">
            </div>

            <!-- No. Telepon dan Tanggal Daftar -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white w-1/5 lg:w-1/4">No. Telepon<span class="text-red-600">*</span></label>
                <input type="text" id="no_tlp" name="no_tlp" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Pertama Daftar<span class="text-red-600">*</span></label>
                <input type="date" value="<?php

                                            $tanggalHariIni = date('Y-m-d');

                                            echo $tanggalHariIni; ?>" id="tgl_daftar" name="tgl_daftar" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
            </div>

            <!-- Pekerjaan dan No. KTP/SIM -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white w-1/5 lg:w-1/4">Pekerjaan</label>
                <input type="text" id="pekerjaan" name="pekerjaan" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white">

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">No. KTP/SIM</label>
                <input id="no_ktp" name="no_ktp" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white">
            </div>

            <!-- Alamat -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white w-1/5 lg:w-1/4">Alamat</label>
                <input type="text" id="alamat" name="alamat" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white">
            </div>

            <!-- Kelurahan dan Kecamatan -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white w-1/5 lg:w-1/4">Kelurahan</label>
                <input type="text" id="kd_kel" name="kd_kel" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white">

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Kecamatan</label>
                <input id="kd_kec" name="kd_kec" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white">
            </div>

            <!-- Kabupaten dan Provinsi -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white w-1/5 lg:w-1/4">Kabupaten</label>
                <input type="text" id="kd_kab" name="kd_kab" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white">

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Provinsi</label>
                <input id="kd_prop" name="kd_prop" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white">
            </div>

            <!-- Alamat PJ -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white w-1/5 lg:w-1/4">Alamat PJ</label>
                <input type="text" id="alamatpj" name="alamatpj" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white">
            </div>

            <!-- Kelurahan dan Kecamatan -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white w-1/5 lg:w-1/4">Kelurahan</label>
                <input type="text" id="kelurahanpj" name="kelurahanpj" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white">

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Kecamatan</label>
                <input id="kecamatanpj" name="kecamatanpj" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white">
            </div>

            <!-- Kabupaten dan Provinsi -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white w-1/5 lg:w-1/4">Kabupaten</label>
                <input type="text" id="kabupatenpj" name="kabupatenpj" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white">

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Provinsi</label>
                <input id="propinsipj" name="propinsipj" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white">
            </div>

            <!-- Instansi dan NIP/NRP -->
            <div class="mb-5 sm:block md:flex items-center">
                <!-- Label Instansi -->
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white w-1/5 lg:w-1/4">Instansi Pasien</label>

                <!-- Input Instansi + Link -->
                <div class="relative w-full md:w-1/4">
                    <input type="text" id="perusahaan_pasien" name="perusahaan_pasien"
                        class="block w-full p-2 text-sm text-gray-900 border border-gray-300 rounded-lg pr-10 dark:border-gray-600 dark:text-white">
                    <a href="/datainstansi"
                        class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-blue-600"
                        title="Tambah Instansi">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M18 13v6a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2h6m5-3h5m0 0v5m0-5L10 14" />
                        </svg>
                    </a>
                </div>

                <!-- Label NIP -->
                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">NIP/NRP</label>

                <!-- Input NIP -->
                <input id="nip" name="nip"
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
    document.addEventListener('DOMContentLoaded', function() {
        // 🔁 Reset Form + Simpan Nomor RM
        const form = document.getElementById('myForm');
        const nomorRM = document.getElementById('no_rkm_medis')?.value;
        if (form) {
            form.reset();
            if (nomorRM) {
                document.getElementById('no_rkm_medis').value = nomorRM;
            }
        }

        // 📅 Auto Hitung Umur
        const tglLahirInput = document.getElementById('tgl_lahir');
        const umurInput = document.getElementById('umur');
        if (tglLahirInput && umurInput) {
            tglLahirInput.addEventListener('change', function() {
                const tglLahir = new Date(this.value);
                const today = new Date();

                if (isNaN(tglLahir.getTime())) {
                    umurInput.value = '';
                    return;
                }

                let tahun = today.getFullYear() - tglLahir.getFullYear();
                let bulan = today.getMonth() - tglLahir.getMonth();
                let hari = today.getDate() - tglLahir.getDate();

                if (hari < 0) {
                    bulan--;
                    hari += new Date(today.getFullYear(), today.getMonth(), 0).getDate();
                }

                if (bulan < 0) {
                    tahun--;
                    bulan += 12;
                }

                umurInput.value = `${tahun} Th ${bulan} Bl ${hari} Hr`;
            });

            // 🔒 Lock field umur
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
        onlyNumber('#no_ktp');
        onlyNumber('#no_tlp');
        onlyNumber('#no_peserta');
        onlyNumber('#nip');

        // 👤 Autofill Nama PJ
        const pjSelect = document.getElementById('keluarga');
        const namaPasien = document.getElementById('nm_pasien');
        const namaPJ = document.getElementById('namakeluarga');

        if (pjSelect && namaPasien && namaPJ) {
            pjSelect.addEventListener('change', function() {
                if (this.value === 'DIRI SENDIRI') {
                    namaPJ.value = namaPasien.value;
                    namaPJ.setAttribute('readonly', true);
                } else {
                    namaPJ.removeAttribute('readonly');
                    namaPJ.value = '';
                }
            });

            namaPasien.addEventListener('input', function() {
                if (pjSelect.value === 'DIRI SENDIRI') {
                    namaPJ.value = this.value;
                }
            });
        }

        // 📥 Ambil kode instansi dari localStorage
        const kodeInstansi = localStorage.getItem('kode_instansi_terpilih');
        if (kodeInstansi) {
            const inputInstansi = document.getElementById('perusahaan_pasien');
            if (inputInstansi) {
                inputInstansi.value = kodeInstansi;
                inputInstansi.setAttribute('readonly', true);
            }
            localStorage.removeItem('kode_instansi_terpilih');
        }


        console.log('[✔] All form scripts loaded.');
    });
</script>



<?= $this->endSection(); ?>