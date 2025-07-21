<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>
<?= $this->include('components/modal/modalpasien') ?>
<?= $this->include('components/modal/modaldokterjaga') ?>

<!-- Card Section -->
<div class="max-w-[85rem] py-6 lg:py-3 px-8 mx-auto">
    <!-- Card -->
    <div class="bg-white rounded-xl shadow p-4 sm:p-7 dark:bg-slate-900">
        <?= view('components/form/judul', [
            'judul' => 'Tambah Registrasi Pasien'
        ]) ?>
        <form action="/registrasi/submittambah/" id="myForm" onsubmit="return validateForm()" method="post">
            <?= csrf_field() ?>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Nomor Registrasi</label>
                <input type="text" name="nomor_reg" value="<?php function generateUniqueNumber($length = 15)
                                                            {
                                                                $characters = '1234567890';
                                                                $charactersLength = strlen($characters);
                                                                $randomString = '';

                                                                $uniqueLength = $length - 11;

                                                                if ($uniqueLength > 0) {
                                                                    for ($i = 0; $i < $uniqueLength; $i++) {
                                                                        $randomString .= $characters[rand(0, $charactersLength - 1)];
                                                                    }
                                                                } else {
                                                                    return "Panjang maksimal terlalu pendek.";
                                                                }

                                                                return $randomString;
                                                            }

                                                            $tanggalHariIni = date('Ymd');

                                                            $nomor = "REG" . $tanggalHariIni . generateUniqueNumber();
                                                            echo $nomor; ?>" class="bg-gray-200 border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" maxlength="80" required readonly>
                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Nomor Rawat</label>
                <input name="nomor_rawat" value="<?php function generateUniqueNumber2($length = 15)
                                                    {
                                                        $characters = '1234567890';
                                                        $charactersLength = strlen($characters);
                                                        $randomString = '';

                                                        $uniqueLength = $length - 11;

                                                        if ($uniqueLength > 0) {
                                                            for ($i = 0; $i < $uniqueLength; $i++) {
                                                                $randomString .= $characters[rand(0, $charactersLength - 1)];
                                                            }
                                                        } else {
                                                            return "Panjang maksimal terlalu pendek.";
                                                        }

                                                        return $randomString;
                                                    }

                                                    $tanggalHariIni = date('Ymd');

                                                    $nomor = "" . $tanggalHariIni . generateUniqueNumber();
                                                    echo $nomor; ?>" class="bg-gray-200 border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required readonly>
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white w-1/5 lg:w-1/4">Tanggal</label>
                <input type="date" name="tanggal" value="<?php

                                                            $tanggalHariIni = date('Y-m-d');

                                                            echo $tanggalHariIni; ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" maxlength="80" required>
                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Jam</label>
                <input type="time" name="jam" value="<?php

                                                        $waktuHariIni = date('H:i:s');

                                                        echo $waktuHariIni;
                                                        ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" maxlength="80" required>
            </div>

            <!-- Nomor Rekam Medis dan Nama -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white w-1/5 lg:w-1/4">
                    Nomor Rekam Medis<span class="text-red-600">*</span>
                </label>
                <div class="relative w-full md:w-1/4">
                    <input type="text" id="no_rkm_medis" name="no_rkm_medis"
                        class="block w-full p-2 text-sm text-gray-900 border border-gray-300 rounded-lg pr-10 dark:border-gray-600 dark:text-white"
                        placeholder="Nomor RM" required>
                    <button type="button" onclick="openModalPasien()"
                        class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-black cursor-pointer transition-colors duration-200"
                        title="Pilih Pasien">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M18 13v6a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2h6m5-3h5m0 0v5m0-5L10 14" />
                        </svg>
                    </button>
                </div>

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">
                    Nama<span class="text-red-600">*</span>
                </label>
                <input id="nama_pasien" name="nama"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white"
                    readonly required>
            </div>

            <!-- Jenis Kelamin dan Umur -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">
                    Jenis Kelamin<span class="text-red-600">*</span>
                </label>
                <select id="jenis_kelamin" name="jenis_kelamin"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
                    <option value="">-- Pilih --</option>
                    <option value="L">Laki-laki</option>
                    <option value="P">Perempuan</option>
                </select>

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">
                    Umur<span class="text-red-600">*</span>
                </label>
                <input id="umur" name="umur"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white">
            </div>

            <!-- Nomor Telepon -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">
                    Nomor Telepon<span class="text-red-600">*</span>
                </label>
                <input name="nomor_telepon" id="no_telp"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white">
            </div>

            <!-- Dokter dan Poliklinik -->
            <div class="mb-5 sm:block md:flex items-center">
                <!-- Label Dokter -->
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white w-1/5 lg:w-1/4">
                    Dokter<span class="text-red-600">*</span>
                </label>

                <!-- Input Dokter + Tombol Modal -->
                <div class="relative w-full md:w-1/4">
                    <!-- Display: nama dokter -->
                    <input type="text" id="nama_dokter" name="nama_dokter"
                        value="<?= esc($form_data['nama_dokter'] ?? '') ?>"
                        class="block w-full p-2 text-sm text-gray-900 border border-gray-300 rounded-lg pr-10 dark:border-gray-600 dark:text-white" placeholder="Pilih Dokter"
                        readonly required>

                    <!-- Tombol buka modal -->
                    <button type="button" onclick="openModalDokterJaga()"
                        class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-black cursor-pointer transition-colors duration-200"
                        title="Pilih Dokter">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M18 13v6a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2h6m5-3h5m0 0v5m0-5L10 14" />
                        </svg>
                    </button>
                </div>

                <!-- Label Poli -->
                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">
                    Poliklinik<span class="text-red-600">*</span>
                </label>

                <!-- Display Poliklinik -->
                <input type="text" id="poliklinik" name="poliklinik"
                    value="<?= esc($form_data['poliklinik'] ?? '') ?>"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white"
                    readonly required>
            </div>

            <!-- Hidden: kode dokter (yang dikirim ke backend) -->
            <input type="hidden" id="kode_dokter" name="kode_dokter" value="<?= esc($form_data['kode_dokter'] ?? '') ?>">



            <!-- Penanggung Jawab dan Hubungan -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white w-1/5 lg:w-1/4">
                    Nama Penanggung Jawab
                </label>
                <input type="text" id="penanggung_jawab" name="penanggung_jawab"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white">

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">
                    Hubungan Penanggung Jawab
                </label>
                <select name="hubungan_penanggung_jawab" id="hubungan_pj"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white"
                    required>
                    <option value="">-- Pilih --</option>
                    <option value="DIRI SENDIRI">DIRI SENDIRI</option>
                    <option value="AYAH">AYAH</option>
                    <option value="IBU">IBU</option>
                    <option value="ISTRI">ISTRI</option>
                    <option value="SUAMI">SUAMI</option>
                    <option value="ANAK">ANAK</option>
                    <option value="SAUDARA">SAUDARA</option>
                    <option value="LAIN-LAIN">LAIN-LAIN</option>
                </select>
            </div>

            <!-- Pekerjaan dan No. Telp PJ -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">
                    Pekerjaan Penanggung Jawab
                </label>
                <input type="text" name="pekerjaanpj" value="<?= old('pekerjaanpj') ?>"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white">

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">
                    No. Telepon Penanggung Jawab
                </label>
                <input type="text" name="notelp_pj" value="<?= old('notelp_pj') ?>"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white">
            </div>

            <!-- Alamat Penanggung Jawab -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white w-1/5 lg:w-1/4">
                    Alamat Penanggung Jawab
                </label>
                <input type="text" id="alamat_pj" name="alamat_penanggung_jawab" value="<?= old('alamat_pj') ?>" placeholder="Alamat PJ"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white">
            </div>

            <!-- Wilayah PJ -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4"></label>
                <input type="text" name="kelurahanpj" value="<?= old('kelurahanpj') ?>" placeholder="Kelurahan PJ"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white">

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5"></label>
                <input type="text" name="kecamatanpj" value="<?= old('kecamatanpj') ?>" placeholder="Kecamatan PJ"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white">
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4"></label>
                <input type="text" name="kabupatenpj" value="<?= old('kabupatenpj') ?>" placeholder="Kabupaten PJ"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white">

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5"></label>
                <input type="text" name="propinsipj" value="<?= old('propinsipj') ?>" placeholder="Propinsi PJ"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white">
            </div>

            <!-- Registrasi -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">
                    Biaya Registrasi<span class="text-red-600">*</span>
                </label>
                <input type="number" name="biaya_registrasi" placeholder="0"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white"
                    required>

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">
                    Status Registrasi<span class="text-red-600">*</span>
                </label>
                <select name="status_registrasi"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white"
                    required>
                    <option value="Baru">Baru</option>
                    <option value="Lama">Lama</option>
                </select>
            </div>

            <!-- Status Rawat dan Poliklinik -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">
                    Status Rawat<span class="text-red-600">*</span>
                </label>
                <select name="status_rawat"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white"
                    required>
                    <option value="Belum">Belum</option>
                    <option value="Sudah">Sudah</option>
                </select>

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">
                    Status Poliklinik<span class="text-red-600">*</span>
                </label>
                <select name="status_poliklinik"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white"
                    required>
                    <option value="Baru">Baru</option>
                    <option value="Lama">Lama</option>
                </select>
            </div>

            <!-- Jenis Bayar dan Status Bayar -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">
                    Jenis Bayar<span class="text-red-600">*</span>
                </label>
                <select name="jenis_bayar"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white"
                    required>
                    <option value="BPJS">BPJS</option>
                    <option value="non-BPJS">non-BPJS</option>
                </select>

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">
                    Status Bayar<span class="text-red-600">*</span>
                </label>
                <select name="status_bayar"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white"
                    required>
                    <option value="Belum Bayar">Belum Bayar</option>
                    <option value="Sudah Bayar">Sudah Bayar</option>
                </select>
            </div>

            <?= view('components/form/submit_button') ?>
        </form>
    </div>
    <!-- End Card -->
</div>
<!-- End Card Section -->

<!-- Script -->
<script src="<?= base_url('js/form-validation.js') ?>"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // ========== Bagian 1: Auto-Fill Pasien dari No RM ==========
        const nomorRMInput = document.querySelector('input[name="no_rkm_medis"]');
        const token = "<?= session()->get('jwt_token') ?>"; // Render token dari backend (PHP, dsb.)

        async function fetchAndFillPasien(noRM) {
            if (!noRM) return;

            try {
                const response = await fetch(`http://127.0.0.1:8080/v1/pasien/${encodeURIComponent(noRM)}`, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': 'Bearer ' + token
                    }
                });

                const result = await response.json();

                if (result.status === 'success' && result.data) {
                    const pasien = result.data;

                    document.getElementById('nama_pasien').value = pasien.nm_pasien || '';
                    document.getElementById('jenis_kelamin').value = pasien.jk || '';
                    document.getElementById('umur').value = pasien.umur || '';
                    document.getElementById('penanggung_jawab').value = pasien.namakeluarga || '';
                    document.getElementById('alamat_pj').value = pasien.alamatpj || '';
                    document.getElementById('no_telp').value = pasien.no_tlp || '';
                    document.getElementById('hubungan_pj').value = pasien.keluarga || '';

                    const statusSelect = document.querySelector('select[name="status_registrasi"]');
                    if (statusSelect) {
                        statusSelect.value = 'Lama';
                    }
                } else {
                    console.warn("⚠️ Pasien tidak ditemukan.");
                }
            } catch (err) {
                console.error("❌ Error fetching pasien:", err);
            }
        }

        nomorRMInput.addEventListener('blur', function() {
            const nomorRM = nomorRMInput.value.trim();
            if (nomorRM) fetchAndFillPasien(nomorRM);
        });

        if (nomorRMInput.value.trim()) {
            nomorRMInput.dispatchEvent(new Event('blur'));
        }

        // ========== Bagian 2: Hitung Harga Jual Otomatis ==========
        const inputHargaBeli = document.querySelector('input[name="hargabeli"]');
        const hargaInputs = [
            'hargaralan', 'hargakelas1', 'hargakelas2', 'hargakelas3',
            'hargautama', 'hargavip', 'hargavvip',
            'hargaapotekluar', 'hargaobatbebas', 'hargakaryawan'
        ].map(name => document.querySelector(`input[name="${name}"]`));

        inputHargaBeli.addEventListener('input', function() {
            const hargaBeli = parseFloat(inputHargaBeli.value);

            if (!isNaN(hargaBeli)) {
                const hargaJual = (hargaBeli * 1.15).toFixed(0);
                hargaInputs.forEach(input => input.value = hargaJual);
            } else {
                hargaInputs.forEach(input => input.value = '');
            }
        });
    });
</script>

<?= $this->endSection(); ?>