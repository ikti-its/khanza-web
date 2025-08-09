<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<!-- Card Section -->
<div class="max-w-[85rem] py-6 lg:py-3 px-8 mx-auto">
    <!-- Card -->
    <div class="bg-white rounded-xl shadow p-4 sm:p-7 dark:bg-slate-900">
        <?= view('components/form/judul', [
            'judul' => 'Edit Penanganan'
        ]) ?>
        <form action="/tindakan/submitedit/<?= $tindakan['nomor_rawat'] ?>/<?= $tindakan['jam_rawat'] ?>" onsubmit="return validateForm()" method="post">
            <?= csrf_field() ?>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Nomor Rekam Medis</label>
                <input type="text" name="nomor_rm" value="<?= $tindakan['nomor_rm'] ?? '' ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" maxlength="80" required>
                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Nomor Rawat</label>
                <input name="nomor_rawat" value="<?= $tindakan['nomor_rawat'] ?? '' ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white">
            </div>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Nama Pasien</label>
                <input type="text" name="nama_pasien" value="<?= $tindakan['nama_pasien'] ?? '' ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" maxlength="80" required>
            </div>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Tindakan</label>
                    <!-- Select input -->
                    <select id="tindakanSelect" name="tindakan" value="<?= $tindakan['nama_tindakan'] ?? '' ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
                        <option disabled selected value="">Pilih Tindakan</option>
                        <?php if (!empty($jenis_tindakan) && is_array($jenis_tindakan)): ?>
                            <?php foreach ($jenis_tindakan as $t): ?>
                                <option 
                                    value="<?= esc($t['kode']) ?>" 
                                    data-tarif="<?= esc($t['tarif'] ?? 0) ?>"
                                >
                                    <?= esc($t['kode']) ?> - <?= esc($t['nama_tindakan']) ?> (<?= esc($t['kode_bangsal'] ?? '-') ?>) - Rp <?= number_format($t['tarif'] ?? 0, 0, ',', '.') ?>
                                </option>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <option disabled value="">Tidak ada data tindakan</option>
                        <?php endif; ?>
                    </select>

                    <!-- Hidden fields to submit kode and tarif -->
                    <input type="hidden" name="kode_tindakan" id="kodeTindakan">
                    <input type="hidden" name="tarif_tindakan" id="tarifTindakan">
            </div>
            <div class="mb-5 sm:block md:flex items-center">
                <!-- Dokter Select -->
                <label for="dokter-select" class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Dokter</label>
                <select id="dokter-select" name="kode_dokter" value="<?= $tindakan['nama_dokter'] ?? '' ?>" class="border border-gray-300 text-sm text-gray-900 rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white">
                    <option value="">Pilih Dokter</option>
                </select>
                <input type="hidden" name="nama_dokter" id="nama-dokter-hidden">

                <!-- Petugas Select -->
                <label for="pegawai-select" class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Petugas</label>
                <select id="pegawai-select" name="nip" value="<?= $tindakan['nama_petugas'] ?? '' ?>" class="border border-gray-300 text-sm text-gray-900 rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white">
                    <option value="">Pilih Petugas</option>
                </select>
                <input type="hidden" name="nama_petugas" id="nama-pegawai-hidden">
            </div>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Tanggal</label>
                <input type="text" name="tanggal_rawat" value="<?= $tindakan['tanggal_rawat'] ?? '' ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" maxlength="80" required>
                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Jam</label>
                <input name="jam_rawat" value="<?= $tindakan['jam_rawat'] ?? '' ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white">
            </div>
            <!-- <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white w-1/5 lg:w-1/4">Biaya</label>
                <input type="text" name="biaya" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" maxlength="80" required>
            </div> -->
            <?= view('components/form/submit_button') ?>
        </form>
    </div>
    <!-- End Card -->
</div>
<!-- End Card Section -->
<script>
document.addEventListener('DOMContentLoaded', async () => {
    const token = "<?= session()->get('jwt_token') ?>";

    // Populate Dokter
    const dokterSelect = document.getElementById("dokter-select");
    const namaDokterHidden = document.getElementById("nama-dokter-hidden");
    const prefillDokter = "<?= $prefill['kode_dokter'] ?? '' ?>";

    fetch("http://127.0.0.1:8080/v1/dokter", {
        headers: {
            "Authorization": "Bearer <?= session()->get('jwt_token') ?>",
            "Accept": "application/json"
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data?.data && Array.isArray(data.data)) {
            data.data.forEach(item => {
                const option = document.createElement("option");
                option.value = item.kode_dokter;
                option.textContent = item.kode_dokter + " - " + item.nama_dokter;

                if (item.kode_dokter === prefillDokter) {
                    option.selected = true;
                    namaDokterHidden.value = item.nama_dokter; // ⬅️ prefill hidden field
                }

                dokterSelect.appendChild(option);
            });
        }
    });

    dokterSelect.addEventListener("change", () => {
        const selectedOption = dokterSelect.options[dokterSelect.selectedIndex];
        const nama = selectedOption.textContent.split(" - ")[1] ?? "";
        namaDokterHidden.value = nama; // ⬅️ set hidden input
    });

    // Populate Pegawai
    const pegawaiSelect = document.getElementById("pegawai-select");
    const namaPegawaiHidden = document.getElementById("nama-pegawai-hidden");
    const prefillPegawai = "<?= $prefill['nip'] ?? '' ?>";

    fetch("http://127.0.0.1:8080/v1/pegawai", {
        headers: {
            "Authorization": "Bearer <?= session()->get('jwt_token') ?>",
            "Accept": "application/json"
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data?.data && Array.isArray(data.data)) {
            data.data.forEach(item => {
                const option = document.createElement("option");
                option.value = item.nip;
                option.textContent = item.nip + " - " + item.nama;

                if (item.nip === prefillPegawai) {
                    option.selected = true;
                    namaPegawaiHidden.value = item.nama;
                }

                pegawaiSelect.appendChild(option);
            });
        }
    });

    pegawaiSelect.addEventListener("change", () => {
        const selectedOption = pegawaiSelect.options[pegawaiSelect.selectedIndex];
        const nama = selectedOption.textContent.split(" - ")[1] ?? "";
        namaPegawaiHidden.value = nama;
    });
});

document.getElementById("tindakanSelect").addEventListener("change", function () {
    const selected = this.options[this.selectedIndex];
    const tarif = selected.getAttribute("data-tarif") || 0;

    // Set to the biaya input
    document.getElementById("biayaInput").value = tarif;
});

    document.getElementById("tindakanSelect").addEventListener("change", function() {
    const selected = this.options[this.selectedIndex];
    const kode = selected.value;
    const nama = selected.getAttribute("data-nama");
    const tarif = selected.getAttribute("data-tarif");

    document.getElementById("kodeTindakan").value = kode;
    document.getElementById("tarifTindakan").value = tarif;
});

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