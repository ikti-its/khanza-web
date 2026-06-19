<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<?= $this->include('components/modal/modalkota') ?>
<?= $this->include('components/modal/modalwilayah') ?>

<div class="max-w-[85rem] py-6 lg:py-3 px-8 mx-auto">
    <div class="bg-white rounded-xl shadow p-4 sm:p-7 dark:bg-slate-900">
        <?= view('components/form/judul', [
            'judul' => $judul
        ]) ?>
        
        <form action="<?= $modul_path . $form_action ?>" id="myForm" onsubmit="return validateForm()" method="post">
            <?= csrf_field() ?>

            <input type="hidden" name="id_orang" id="id_orang" value="<?= $baris['id_orang'] ?? '' ?>">

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">
                    Nomor Pendonor<span class="text-red-600">*</span>
                </label>
                <div class="w-full lg:w-1/4">
                    <input type="text" name="nomor_pendonor" value="<?= $baris['nomor_pendonor'] ?? '' ?>" readonly required
                           placeholder="Terisi otomatis..." 
                           class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full dark:border-gray-600 dark:text-white bg-gray-100 cursor-not-allowed h-[38px]">
                </div>

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">
                    NIK (No. KTP)<span class="text-red-600">*</span>
                </label>
                <input type="text" name="nik" id="nik" value="<?= $baris['nik'] ?? '' ?>" required
                       minlength="16" maxlength="16" inputmode="numeric" pattern="[0-9]{16}"
                       oninput="this.value = this.value.replace(/[^0-9]/g, '');"
                       class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white h-[38px]">
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">
                    Nama Lengkap<span class="text-red-600">*</span>
                </label>
                <div class="w-full lg:w-1/4">
                    <input type="text" name="nama" value="<?= $baris['nama'] ?? '' ?>" required
                           class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full dark:border-gray-600 dark:text-white h-[38px]">
                </div>

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">
                    Jenis Kelamin<span class="text-red-600">*</span>
                </label>
                <select name="id_jenis_kelamin" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white dark:bg-slate-800 h-[38px]" required>
                    <option value="">-- Pilih --</option>
                    <?php
                    $optionsJK = [];
                    foreach ($konfig as $field) { if ($field[2] === 'id_jenis_kelamin') { $optionsJK = $field[5] ?? []; break; } }
                    foreach ($optionsJK as $opt) : $selected = ((string)($baris['id_jenis_kelamin'] ?? '') === (string)$opt[1]) ? 'selected' : '';
                    ?>
                        <option value="<?= $opt[1] ?>" <?= $selected ?>><?= $opt[0] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">
                    Tempat Lahir<span class="text-red-600">*</span>
                </label>
                <div class="w-full lg:w-1/4 flex gap-x-2">
                    <input type="hidden" name="tempat_lahir_kota" id="tempat_lahir_kota" value="<?= $baris['tempat_lahir_kota'] ?? '' ?>" required>
                    <input type="text" id="nama_kota" name="nama_kota" readonly required
                           value="<?= $baris['nama_kota'] ?? '' ?>" placeholder="Klik cari..."
                           onclick="open_modalKota()"
                           class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full dark:border-gray-600 dark:text-white cursor-pointer bg-slate-50 h-[38px]">
                    
                    <button type="button" onclick="open_modalKota()"
                            class="inline-flex justify-center items-center p-2 text-sm font-medium text-white bg-blue-600 rounded-lg border border-transparent hover:bg-blue-700 focus:outline-none transition-all w-10 h-[38px] flex-shrink-0 shadow-sm">
                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </button>
                </div>

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">
                    Tanggal Lahir<span class="text-red-600">*</span>
                </label>
                <input type="date" name="tanggal_lahir" id="tanggal_lahir" value="<?= $baris['tanggal_lahir'] ?? '' ?>" max="<?= date('Y-m-d') ?>"
                       onchange="hitungUmurPendonor()"
                       class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-700 dark:text-white h-[38px]" required>
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">
                    Umur
                </label>
                <div class="w-full lg:w-1/4">
                    <input type="text" id="umur_pendonor" name="umur_pasien" readonly placeholder="Terisi otomatis..."
                           class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full dark:border-gray-600 dark:text-white bg-gray-100 cursor-not-allowed h-[38px]">
                </div>
                
                <div class="block mt-5 md:my-0 md:ml-10 mb-2 w-1/5"></div>
                <div class="w-full lg:w-1/4"></div>
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">
                    Golongan Darah<span class="text-red-600">*</span>
                </label>
                <select name="id_golongan_darah" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white dark:bg-slate-800 h-[38px]" required>
                    <option value="">-- Pilih --</option>
                    <?php
                    $optionsGol = [];
                    foreach ($konfig as $field) { if ($field[2] === 'id_golongan_darah') { $optionsGol = $field[5] ?? []; break; } }
                    foreach ($optionsGol as $opt) : $selected = ((string)($baris['id_golongan_darah'] ?? '') === (string)$opt[1]) ? 'selected' : '';
                    ?>
                        <option value="<?= $opt[1] ?>" <?= $selected ?>><?= $opt[0] ?></option>
                    <?php endforeach; ?>
                </select>

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">
                    Rhesus
                </label>
                <select name="id_rhesus" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white dark:bg-slate-800 h-[38px]">
                    <option value="">-- Pilih --</option>
                    <?php
                    $optionsRhe = [];
                    foreach ($konfig as $field) { if ($field[2] === 'id_rhesus') { $optionsRhe = $field[5] ?? []; break; } }
                    foreach ($optionsRhe as $opt) : $selected = ((string)($baris['id_rhesus'] ?? '') === (string)$opt[1]) ? 'selected' : '';
                    ?>
                        <option value="<?= $opt[1] ?>" <?= $selected ?>><?= $opt[0] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-4 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4 flex-shrink-0">
                    Alamat Lengkap<span class="text-red-600">*</span>
                </label>
                <div class="w-full lg:w-1/4">
                    <textarea name="alamat_lengkap" rows="2" required
                              class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full dark:border-gray-600 dark:bg-slate-800 dark:text-white resize-y focus:outline-none focus:border-blue-500"><?= $baris['alamat_lengkap'] ?? '' ?></textarea>
                </div>
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">
                    Kelurahan / Desa<span class="text-red-600">*</span>
                </label>
                <div class="w-full lg:w-1/4 flex gap-x-2">
                    <input type="hidden" name="id_provinsi" id="id_provinsi" value="<?= $baris['id_provinsi'] ?? '' ?>" required>
                    <input type="hidden" name="id_kota_lokal" id="id_kota_lokal" value="<?= $baris['id_kota_lokal'] ?? '' ?>" required>
                    <input type="hidden" name="id_kec_lokal" id="id_kec_lokal" value="<?= $baris['id_kec_lokal'] ?? '' ?>" required>
                    <input type="hidden" name="id_desa_lokal" id="id_desa_lokal" value="<?= $baris['id_desa_lokal'] ?? '' ?>" required>

                    <input type="text" id="nama_desa" name="nama_desa" readonly required
                           value="<?= $baris['nama_desa'] ?? '' ?>"
                           placeholder="Klik cari..."
                           onclick="open_modalWilayah()"
                           class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full dark:border-gray-600 dark:text-white cursor-pointer bg-slate-50 h-[38px]">
                    
                    <button type="button" onclick="open_modalWilayah()"
                            class="inline-flex justify-center items-center p-2 text-sm font-medium text-white bg-blue-600 rounded-lg border border-transparent hover:bg-blue-700 focus:outline-none transition-all w-10 h-[38px] flex-shrink-0 shadow-sm">
                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </button>
                </div>

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">
                    Kecamatan
                </label>
                <input type="text" id="nama_kecamatan" name="nama_kecamatan" readonly
                       value="<?= $baris['nama_kecamatan'] ?? '' ?>" placeholder="Terisi otomatis..."
                       class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white bg-gray-100 cursor-not-allowed h-[38px]">
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">
                    Kota / Kabupaten
                </label>
                <div class="w-full lg:w-1/4">
                    <input type="text" id="nama_kota_wilayah" name="nama_kota_wilayah" readonly
                           value="<?= $baris['nama_kota_wilayah'] ?? '' ?>" placeholder="Terisi otomatis..."
                           class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full dark:border-gray-600 dark:text-white bg-gray-100 cursor-not-allowed h-[38px]">
                </div>

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">
                    Provinsi
                </label>
                <input type="text" id="nama_provinsi" name="nama_provinsi" readonly
                       value="<?= $baris['nama_provinsi'] ?? '' ?>" placeholder="Terisi otomatis..."
                       class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-700 dark:text-white bg-gray-100 cursor-not-allowed h-[38px]">
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">
                    Agama<span class="text-red-600">*</span>
                </label>
                <div class="w-full lg:w-1/4">
                    <select name="id_agama" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full dark:border-gray-600 dark:text-white dark:bg-slate-800 h-[38px]" required>
                        <option value="">-- Pilih --</option>
                        <?php
                        $optionsAgama = [];
                        foreach ($konfig as $field) { 
                            if ($field[2] === 'id_agama') { 
                                $optionsAgama = $field[5] ?? []; 
                                break; 
                            } 
                        }
                        foreach ($optionsAgama as $opt) : 
                            $selected = ((string)($baris['id_agama'] ?? '') === (string)$opt[1]) ? 'selected' : '';
                        ?>
                            <option value="<?= $opt[1] ?>" <?= $selected ?>><?= esc($opt[0]) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">
                    Status Pernikahan<span class="text-red-600">*</span>
                </label>
                <select name="id_pernikahan" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white dark:bg-slate-800 h-[38px]" required>
                    <option value="">-- Pilih --</option>
                    <?php
                    $optionsStatus = [];
                    foreach ($konfig as $field) { 
                        if ($field[2] === 'id_pernikahan') { 
                            $optionsStatus = $field[5] ?? []; 
                            break; 
                        } 
                    }
                    foreach ($optionsStatus as $opt) : 
                        $selected = ((string)($baris['id_pernikahan'] ?? '') === (string)$opt[1]) ? 'selected' : '';
                    ?>
                        <option value="<?= $opt[1] ?>" <?= $selected ?>><?= esc($opt[0]) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">
                    Nomor Telepon<span class="text-red-600">*</span>
                </label>
                <div class="w-full lg:w-1/4">
                    <input type="text" name="nomor_telepon" value="<?= $baris['nomor_telepon'] ?? '' ?>" required
                           maxlength="13" inputmode="numeric"
                           oninput="this.value = this.value.replace(/[^0-9]/g, '');"
                           class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full dark:border-gray-600 dark:text-white h-[38px]">
                </div>

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">
                    Tanggal Donor Terakhir
                </label>
                <input type="date" name="tanggal_donor_terakhir" value="<?= $baris['tanggal_donor_terakhir'] ?? '' ?>" max="<?= date('Y-m-d') ?>"
                       class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-700 dark:text-white h-[38px]">
            </div>

            <?= view('components/form/submit_button') ?>
        </form>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const tglLahirInput = document.getElementById('tanggal_lahir');
        if (tglLahirInput && tglLahirInput.value !== '') {
            hitungUmurPendonor();
        }
    });

    function autofillKota(item) {
        document.getElementById('tempat_lahir_kota').value = item.id_kota;
        document.getElementById('nama_kota').value = item.nama_kota;
    }

    function autofillWilayah(item) {
        document.getElementById('id_provinsi').value   = item.id_provinsi;
        document.getElementById('id_kota_lokal').value = item.id_kota_lokal;
        document.getElementById('id_kec_lokal').value  = item.id_kec_lokal;
        document.getElementById('id_desa_lokal').value = item.id_desa_lokal;

        document.getElementById('nama_desa').value       = item.nama_desa;
        document.getElementById('nama_kecamatan').value  = item.nama_kecamatan;
        document.getElementById('nama_kota_wilayah').value = item.nama_kota;
        document.getElementById('nama_provinsi').value   = item.nama_provinsi;
    }

    function hitungUmurPendonor() {
        const tglLahirInput = document.getElementById('tanggal_lahir').value;
        const inputUmur = document.getElementById('umur_pendonor');

        if (tglLahirInput) {
            const tglLahir = new Date(tglLahirInput);
            const tglSekarang = new Date();
            
            let tahunUmur = tglSekarang.getFullYear() - tglLahir.getFullYear();
            let bulanUmur = tglSekarang.getMonth() - tglLahir.getMonth();
            
            if (bulanUmur < 0 || (bulanUmur === 0 && tglSekarang.getDate() < tglLahir.getDate())) {
                tahunUmur--;
                bulanUmur = 12 + bulanUmur;
            }
            
            inputUmur.value = `${tahunUmur} Tahun ${bulanUmur} Bulan`;
        } else {
            inputUmur.value = "-";
        }
    }

    function validateForm() {
        var requiredFields = document.querySelectorAll('select[required], input[required], textarea[required]');
        for (var i = 0; i < requiredFields.length; i++) {
            if (!requiredFields[i].value) {
                alert("Mohon isi semua field yang bertanda bintang.");
                return false;
            }
        }

        const inputNik = document.getElementById('nik').value;
        if (inputNik.length !== 16) {
            alert(`Gagal Menyimpan! NIK yang Anda masukkan saat ini berjumlah ${inputNik.length} digit.\nNIK wajib berjumlah tepat 16 digit angka.`);
            document.getElementById('nik').focus();
            return false;
        }

        const tglLahirInput = document.getElementById('tanggal_lahir').value;
        if (tglLahirInput) {
            const tglLahir = new Date(tglLahirInput);
            const tglSekarang = new Date();
            
            let tahunUmur = tglSekarang.getFullYear() - tglLahir.getFullYear();
            let bulanUmur = tglSekarang.getMonth() - tglLahir.getMonth();
            
            if (bulanUmur < 0 || (bulanUmur === 0 && tglSekarang.getDate() < tglLahir.getDate())) {
                tahunUmur--;
            }

            if (tahunUmur < 17) {
                alert(`Gagal Menyimpan! Usia calon pendonor saat ini adalah ${tahunUmur} Tahun.\nSyarat menjadi pendonor darah adalah minimal berusia 17 Tahun.`);
                document.getElementById('tanggal_lahir').focus();
                return false;
            }
        }
        
        const idKotaLahir = document.getElementById('tempat_lahir_kota').value;
        if (!idKotaLahir) {
            alert("Gagal Menyimpan! Anda wajib menentukan asal kota tempat lahir pendonor terlebih dahulu melalui modal.");
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