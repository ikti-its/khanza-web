<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>
<?= $this->include('components/modal/modalpasien') ?>
<?= $this->include('components/modal/modaldokter') ?>


<!-- Card Section -->
<div class="max-w-[85rem] py-6 lg:py-3 px-8 mx-auto">
    <!-- Card -->
    <div class="bg-white rounded-xl shadow p-4 sm:p-7 dark:bg-slate-900">
        <?= view('components/form/judul', ['judul' => 'Input Pasien Meninggal']) ?>

        <form action="<?= base_url('/pasienmeninggal/simpanTambah') ?>" method="post" id="myForm" onsubmit="return validateForm()">
            <?= csrf_field() ?>

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
                <input id="nm_pasien" name="nm_pasien"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white"
                    readonly required>
            </div>


            <!-- Jenis Kelamin dan Golongan Darah -->
            <div class="mb-5 sm:block md:flex items-center">
                <?php
                $jk  = old('jk',  $form_data['jk']  ?? '');
                $gd  = old('gol_darah', $form_data['gol_darah'] ?? '');
                $jk_text = $jk === 'L' ? 'Laki-laki' : ($jk === 'P' ? 'Perempuan' : '');
                ?>

                <!-- Jenis Kelamin -->
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">
                    Jenis Kelamin<span class="text-red-600">*</span>
                </label>
                <input type="text" id="jk_display"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white"
                    value="<?= $jk_text ?>" readonly required>
                <input type="hidden" id="jk" name="jk" value="<?= $jk ?>">

                <!-- Golongan Darah -->
                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">
                    Golongan Darah<span class="text-red-600">*</span>
                </label>
                <input type="text" id="gd_display"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white"
                    value="<?= $gd ?>" readonly required>
                <input type="hidden" id="gol_darah" name="gol_darah" value="<?= $gd ?>">
            </div>


            <!-- Tanggal Lahir dan Umur -->
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Tanggal Lahir<span class="text-red-600">*</span></label>
                <input type="date" id="tgl_lahir" name="tgl_lahir"
                    value="<?= old('tgl_lahir', $form_data['tgl_lahir'] ?? '') ?>"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" readonly required>

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Umur<span class="text-red-600">*</span></label>
                <input type="text" id="umur" name="umur"
                    value="<?= old('umur', $form_data['umur'] ?? '') ?>"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" readonly required>
            </div>

            <!-- Status Nikah dan Agama -->
            <div class="mb-5 sm:block md:flex items-center">
                <?php
                $stts_nikah = old('stts_nikah', $form_data['stts_nikah'] ?? '');
                $agama = old('agama', $form_data['agama'] ?? '');
                ?>

                <!-- Status Pernikahan -->
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">
                    Status Pernikahan<span class="text-red-600">*</span></label>
                <input type="text" id="stts_nikah_display"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white"
                    value="<?= $stts_nikah ?>" readonly required>
                <input type="hidden" id="stts_nikah" name="stts_nikah" value="<?= $stts_nikah ?>">

                <!-- Agama -->
                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">
                    Agama<span class="text-red-600">*</span></label>
                <input type="text" id="agama_display"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white"
                    value="<?= $agama ?>" readonly required>
                <input type="hidden" id="agama" name="agama" value="<?= $agama ?>">
            </div>

            <!-- Tanggal & Jam Meninggal -->
            <div class="mb-5 sm:block md:flex items-center">
                <!-- Tanggal Meninggal -->
                <label for="tanggal" class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">
                    Tanggal Meninggal<span class="text-red-600">*</span>
                </label>
                <input type="date" id="tanggal" name="tanggal"
                    value="<?= old('tanggal', $form_data['tanggal'] ?? '') ?>"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white"
                    required data-error="Tanggal meninggal wajib diisi.">

                <!-- Jam Meninggal -->
                <label for="jam" class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">
                    Jam Meninggal<span class="text-red-600">*</span>
                </label>
                <input type="time" id="jam" name="jam"
                    value="<?= old('jam', $form_data['jam'] ?? '') ?>"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white"
                    required data-error="Jam meninggal tidak boleh kosong.">
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

            <!-- Kode Dokter dan Nama Dokter -->
            <div class="mb-5 sm:block md:flex items-center">
                <!-- Label Kode Dokter -->
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">
                    Kode Dokter<span class="text-red-600">*</span>
                </label>

                <!-- Input Kode Dokter + Tombol Modal -->
                <div class="relative w-full md:w-1/4">
                    <input type="text" id="kode_dokter" name="kode_dokter"
                        value="<?= old('kode_dokter', $form_data['kode_dokter'] ?? '') ?>"
                        class="block w-full p-2 text-sm text-gray-900 border border-gray-300 rounded-lg pr-10 dark:border-gray-600 dark:text-white"
                        placeholder="Pilih Dokter" readonly required>

                    <button type="button" onclick="openModalDokter()"
                        class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-black cursor-pointer"
                        title="Pilih Dokter">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M18 13v6a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2h6m5-3h5m0 0v5m0-5L10 14" />
                        </svg>
                    </button>
                </div>

                <!-- Label Nama Dokter -->
                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">
                    Nama Dokter<span class="text-red-600">*</span>
                </label>

                <input type="text" id="nama_dokter" name="nama_dokter"
                    value="<?= old('nama_dokter', $form_data['nama_dokter'] ?? '') ?>"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white"
                    readonly required>
            </div>

            <!-- Hidden Spesialis -->
            <input type="hidden" name="spesialis" id="spesialis" value="<?= esc($form_data['spesialis'] ?? '') ?>">

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

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const noRMInput = document.querySelector('input[name="no_rkm_medis"]');

        // elemen readonly + hidden
        const jkHidden = document.querySelector('input[name="jk"]');
        const jkDisplay = document.getElementById('jk_display');
        const gdHidden = document.querySelector('input[name="gol_darah"]');
        const gdDisplay = document.getElementById('gd_display');

        const sttsNikahHidden = document.querySelector('input[name="stts_nikah"]');
        const sttsNikahDisplay = document.getElementById('stts_nikah_display');

        const agamaHidden = document.querySelector('input[name="agama"]');
        const agamaDisplay = document.getElementById('agama_display');

        const token = "<?= session()->get('jwt_token') ?>";

        const jkText = v =>
            v === 'L' ? 'Laki-laki' :
            v === 'P' ? 'Perempuan' : '';

        async function fetchPasienByRM() {
            const noRM = noRMInput.value.trim();
            if (!noRM) return;

            try {
                const res = await fetch(
                    `http://127.0.0.1:8080/v1/pasien/${encodeURIComponent(noRM)}`, {
                        headers: {
                            Accept: 'application/json',
                            'Authorization': 'Bearer ' + token
                        }
                    }
                );
                const json = await res.json();
                const p = json.data;

                if (!p?.no_rkm_medis) return alert('Pasien tidak ditemukan.');

                document.querySelector('input[name="nm_pasien"]').value = p.nm_pasien || '';

                jkHidden.value = p.jk || '';
                jkDisplay.value = jkText(p.jk);

                gdHidden.value = p.gol_darah || '';
                gdDisplay.value = p.gol_darah || '';

                document.querySelector('input[name="tgl_lahir"]').value = (p.tgl_lahir || '').split('T')[0];
                document.querySelector('input[name="umur"]').value = p.umur || '';

                sttsNikahHidden.value = p.stts_nikah || '';
                sttsNikahDisplay.value = p.stts_nikah || '';

                agamaHidden.value = p.agama || '';
                agamaDisplay.value = p.agama || '';

            } catch (err) {
                console.error('❌ Gagal mengambil data pasien:', err);
            }
        }

        noRMInput.addEventListener('keydown', e => {
            if (e.key === 'Enter') {
                e.preventDefault();
                noRMInput.blur();
            }
        });

        noRMInput.addEventListener('blur', fetchPasienByRM);
        noRMInput.addEventListener('change', fetchPasienByRM);

        if (noRMInput.value.trim()) {
            noRMInput.dispatchEvent(new Event('blur'));
        }
    });
</script>


<?= $this->endSection(); ?>