<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<?php
$kelas = strtolower($pemberianobat['kelas'] ?? 'dasar');
?>

<!-- Card Section -->
<div class="max-w-[85rem] py-6 lg:py-3 px-8 mx-auto">
    <!-- Card -->
    <div class="bg-white rounded-xl shadow p-4 sm:p-7 dark:bg-slate-900">
        <div class="mb-8">
            <h2 class="text-xl font-bold text-gray-800 dark:text-gray-200">
                Tambah Permintaan Resep Pulang
            </h2>
        </div>
        <form action="<?= base_url('permintaanreseppulang/submittambah') ?>" id="myForm" onsubmit="return validateForm()" method="post">
            <?= csrf_field() ?>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Nomor Permintaan</label>
                <input name="no_permintaan" value="<?= $prefill['no_permintaan'] ?? '' ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" required>
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Tanggal Permintaan</label>
                <input name="tgl_permintaan" value="<?= $prefill['tgl_permintaan'] ?? '' ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" required>

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Jam Permintaan</label>
                <input name="jam" value="<?= $prefill['jam'] ?? '' ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Nomor Rawat</label>
                <input name="nomor_rawat" value="<?= $prefill['no_rawat'] ?? '' ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" required>


                    <label for="kd_dokter" class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Kode Dokter</label>
                    <select id="kd_dokter" name="kode_dokter" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
                        <option disabled selected value="">Pilih Dokter</option>
                        <?php foreach ($dokter_list as $dokter): ?>
                            <option value="<?= $dokter['kode_dokter'] ?>"
                                <?= (isset($prefill['kode_dokter']) && $prefill['kode_dokter'] == $dokter['kode_dokter']) ? 'selected' : '' ?>>
                                <?= $dokter['kode_dokter'] . ' - ' . $dokter['nama_dokter'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label for="status" class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Status Validasi</label>
                <select id="status" name="status" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
                    <option value="Belum" <?= (isset($prefill['status']) && $prefill['status'] == 'Belum') ? 'selected' : '' ?>>Belum</option>
                    <option value="Sudah" <?= (isset($prefill['status']) && $prefill['status'] == 'Sudah') ? 'selected' : '' ?>>Sudah</option>
                </select>
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Tanggal Validasi</label>
                <input name="tgl_validasi" value="<?= $prefill['tgl_validasi'] ?? '' ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" required>

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Jam Validasi</label>
                <input name="jam_validasi" value="<?= $prefill['jam_validasi'] ?? '' ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
            </div>

            <div class="mt-5 pt-5 border-t flex justify-end gap-x-2">
                <a href="javascript:history.back()" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 dark:bg-slate-900 dark:border-gray-700 dark:text-white">
                    Kembali
                </a>
                <button type="submit" id="submitButton" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-[#0A2D27] text-[#ACF2E7] hover:bg-[#13594E]">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function updateHargaObat() {
    const selectedObat = document.getElementById("obatSelect").selectedOptions[0];
    const selectedKelas = document.getElementById("kelasSelect").value;

    const tarif = selectedObat.getAttribute("data-" + selectedKelas.toLowerCase()) || 0;
    const nama = selectedObat.getAttribute("data-nama") || "";
    const jumlah = document.querySelector("input[name='jumlah']").value || 1;

    document.getElementById("biayaObat").value = tarif;
    document.getElementById("namaObat").value = nama;
    document.getElementById("totalObat").value = parseInt(tarif) * parseInt(jumlah);
}

// Trigger on change
document.getElementById("kelasSelect").addEventListener("change", updateHargaObat);
document.getElementById("obatSelect").addEventListener("change", updateHargaObat);
document.querySelector("input[name='jumlah']").addEventListener("input", updateHargaObat);

document.getElementById("obatSelect").addEventListener("change", function () {
    const selected = this.options[this.selectedIndex];
    document.getElementById("namaObat").value = selected.getAttribute("data-nama");
    document.getElementById("biayaObat").value = selected.getAttribute("data-tarif");

    const jumlah = document.querySelector("input[name='jumlah']").value || 1;
    document.getElementById("totalObat").value = parseInt(selected.getAttribute("data-tarif")) * parseInt(jumlah);
});


document.getElementById("obatSelect").addEventListener("change", function () {
    const selected = this.options[this.selectedIndex];
    const nama = selected.getAttribute("data-nama") || "";
    const tarif = selected.getAttribute("data-tarif") || 0;

    document.getElementById("namaObat").value = nama;
    document.getElementById("biayaObat").value = tarif;

    const jumlah = document.querySelector("input[name='jumlah']").value || 1;
    document.getElementById("totalObat").value = parseInt(tarif) * parseInt(jumlah);
});

document.querySelector("input[name='jumlah']").addEventListener("input", function () {
    const jumlah = this.value || 1;
    const tarif = document.getElementById("biayaObat").value || 0;
    document.getElementById("totalObat").value = parseInt(tarif) * parseInt(jumlah);
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
    submitButton.innerHTML = 'Menyimpan...';
    return true;
}
</script>

<?= $this->endSection(); ?>
