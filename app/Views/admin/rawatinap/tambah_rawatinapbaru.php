<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<!-- Card Section -->
<div class="max-w-[85rem] py-6 lg:py-3 px-8 mx-auto">
    <!-- Card -->
    <div class="bg-white rounded-xl shadow p-4 sm:p-7 dark:bg-slate-900">
        <?= view('components/form/judul', [
            'judul' => 'Tambah Pasien Rawat Inap'
        ]) ?>
        <form action="/rawatinap/submittambah/" id="myForm" onsubmit="return validateForm()" method="post">
            <?= csrf_field() ?>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Nomor Rekam Medis</label>
                <input type="text" name="nomor_rm" value="<?= $registrasi['nomor_rm'] ?>" class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" maxlength="80" required readonly>
                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Nomor Rawat</label>
                <input name="nomor_rawat" value="<?= $registrasi['nomor_rawat'] ?>" class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required readonly>
            </div>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Nama Pasien</label>
                <input type="text" name="nama_pasien" value="<?= $registrasi['nama_pasien'] ?>" class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" maxlength="80" required readonly>
                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Alamat Pasien</label>
                <input name="alamat_pasien" value="<?= $registrasi['alamat_pj'] ?? '' ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white">
            </div>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Penanggung Jawab</label>
                <input type="text" name="penanggung_jawab" value="<?= $registrasi['penanggung_jawab'] ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" maxlength="80">
                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">
                    Hubungan Penanggung Jawab
                </label>
                <select name="hubungan_pj" id="hubungan_pj"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
                    
                    <?php $selectedValue = $registrasi['hubungan_pj'] ?? ''; ?>

                    <option value="DIRI SENDIRI" <?= $selectedValue === 'DIRI SENDIRI'?>>DIRI SENDIRI</option>
                    <option value="AYAH" <?= $selectedValue === 'AYAH'?>>AYAH</option>
                    <option value="IBU" <?= $selectedValue === 'IBU'?>>IBU</option>
                    <option value="ISTRI" <?= $selectedValue === 'ISTRI'?>>ISTRI</option>
                    <option value="SUAMI" <?= $selectedValue === 'SUAMI'?>>SUAMI</option>
                    <option value="ANAK" <?= $selectedValue === 'ANAK'?>>ANAK</option>
                    <option value="SAUDARA" <?= $selectedValue === 'SAUDARA'?>>SAUDARA</option>
                    <option value="LAIN-LAIN" <?= $selectedValue === 'LAIN-LAIN'?>>LAIN-LAIN</option>
                </select>

                </select>
            </div>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Jenis Bayar<span class="text-red-600">*</span></label>
                <select name="jenis_bayar" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
                    <option value="BPJS">BPJS</option>
                    <option value="non-BPJS">Non-BPJS</option>
                </select>
            </div>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Kamar<span class="text-red-600">*</span></label>
                <select name="kamar" id="kamarSelect" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" required>
                    <option value="">Pilih Kamar</option>
                </select>

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Tarif Kamar</label>
                <input name="tarif_kamar" id="tarifKamarInput" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" readonly>
            </div>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Diagnosa Awal<span class="text-red-600">*</span></label>
                <input type="text" name="diagnosa_awal" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" maxlength="80" required>
                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Diagnosa Akhir</label>
                <input name="diagnosa_akhir" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" readonly>
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white w-1/5 lg:w-1/4">
    Tanggal Masuk<span class="text-red-600">*</span>
</label>
<input type="date"
       name="tanggal_masuk"
       value="<?= date('Y-m-d') ?>"
       class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white"
       maxlength="80"
       required readonly>
                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Tanggal Keluar</label>
                <input type="text" name="tanggal_keluar" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" maxlength="80" readonly>
            </div>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white w-1/5 lg:w-1/4">Status Pulang<span class="text-red-600">*</span></label>
                <select name="status_pulang" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
                    <option value="Belum">Belum</option>
                    <option value="Sudah">Sudah</option>
                </select>
                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Jam Keluar</label>
                <input type="text" name="jam_keluar" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" maxlength="80" readonly>
            </div>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white w-1/5 lg:w-1/4">Lama Rawat Inap</label>
                <input type="text" name="lama_ranap" id="lamaRanapInput" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" placeholder="1" maxlength="80" readonly>
                    <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Dokter Penanggung Jawab<span class="text-red-600">*</span></label>
                    <select name="dokter_pj" id="dokterSelect" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" required>
                        <option value="">Pilih Dokter</option>
                    </select>

            </div>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white w-1/5 lg:w-1/4">Total Biaya</label>
                <input type="text" name="total_biaya" id="totalBiayaInput" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" maxlength="80">
                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Status Bayar<span class="text-red-600">*</span></label>
                <select name="status_bayar" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white">
                    <option value="Belum">Belum</option>
                    <option value="Sudah">Sudah</option>
                </select>
            </div>
            <?= view('components/form/submit_button') ?>
        </form>
    </div>
    <!-- End Card -->
</div>
<!-- End Card Section -->
<script>

    document.addEventListener("DOMContentLoaded", function () {
        const dokterSelect = document.getElementById("dokterSelect");

        fetch("http://127.0.0.1:8080/v1/dokter") // ✅ Replace with your actual dokter endpoint
            .then(response => response.json())
            .then(data => {
                if (data.status === "success" && Array.isArray(data.data)) {
                    data.data.forEach(dokter => {
                        const option = document.createElement("option");
                        option.value = dokter.nama_dokter; // or dokter.nama_dokter if preferred
                        option.text = `${dokter.nama_dokter} (${dokter.kode_dokter})`;
                        dokterSelect.appendChild(option);
                    });
                } else {
                    console.error("Dokter response format error:", data);
                }
            })
            .catch(error => {
                console.error("Error fetching dokter list:", error);
            });
    });

    document.addEventListener("DOMContentLoaded", function () {
        const kamarSelect = document.getElementById("kamarSelect");
        const tarifInput = document.getElementById("tarifKamarInput");

        let kamarDataMap = {}; // Store kamar data for fast lookup by nomor_bed
        const token = sessionStorage.getItem("token");

        fetch("http://127.0.0.1:8080/v1/kamar/available", {
            headers: {
                "Authorization": `Bearer ${token}`,
                "Content-Type": "application/json"
            }
        })
            .then(response => response.json())
            .then(data => {
                if (data.data && Array.isArray(data.data)) {
                    data.data.forEach(kamar => {
                        // Store data in a map
                        kamarDataMap[kamar.nomor_bed] = kamar;

                        // Create and append option
                        const option = document.createElement("option");
                        option.value = kamar.nomor_bed;
                        option.text = `${kamar.nomor_bed} - ${kamar.nama_kamar} (${kamar.kelas})`;
                        kamarSelect.appendChild(option);
                    });
                }
            })
            .catch(error => {
                console.error("Error fetching kamar list:", error);
            });

        // When a kamar is selected, update the tarif input
        kamarSelect.addEventListener("change", function () {
            const selectedBed = this.value;
            const kamar = kamarDataMap[selectedBed];

            if (kamar) {
                tarifInput.value = kamar.tarif_kamar.toLocaleString(); // Format as number
            } else {
                tarifInput.value = "";
            }
        });
    });
    
// Recalculate total biaya every time lama ranap or tarif kamar is changed
function updateTotalBiaya() {
    const tarifInput = document.getElementById("tarifKamarInput");
    const lamaInput = document.getElementById("lamaRanapInput");
    const totalInput = document.getElementById("totalBiayaInput");

    const tarif = parseInt(tarifInput.value.replace(/,/g, '')) || 0;
    const lama = parseInt(lamaInput.value) || 0;

    const total = tarif * lama;
    totalInput.value = total; // ✅ No formatting with commas
}


    // Trigger when tarif kamar changes (from kamar selection)
    document.getElementById("kamarSelect").addEventListener("change", updateTotalBiaya);

    // Trigger when lama rawat inap is typed
    document.getElementById("lamaRanapInput").addEventListener("input", updateTotalBiaya);

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
        // Ubah teks tombol menjadi sesuatu yang menunjukkan proses sedang berlangsung, misalnya "Menyimpan..."
        submitButton.innerHTML = 'Menyimpan...';
        return true;
    }
</script>
<?= $this->endSection(); ?>