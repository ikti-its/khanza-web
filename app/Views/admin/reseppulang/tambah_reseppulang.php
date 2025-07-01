<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<?php
$kelas = strtolower($pemberianobat['kelas'] ?? 'dasar');
?>

<!-- Card Section -->
<div class="max-w-[85rem] py-6 lg:py-3 px-8 mx-auto">
    <!-- Card -->
    <div class="bg-white rounded-xl shadow p-4 sm:p-7 dark:bg-slate-900">
        <?= view('components/form/judul', [
            'judul' => 'Tambah Resep Pulang'
        ]) ?>
        <form action="<?= base_url('reseppulang/submittambah') ?>" id="myForm" onsubmit="return validateForm()" method="post">
            <?= csrf_field() ?>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Nomor Rawat<span class="text-red-600">*</span></label>
                <input name="nomor_rawat" value="<?= esc($no_rawat) ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" required>
            
                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Kamar<span class="text-red-600">*</span></label>
                <input name="kd_bangsal" value="<?= esc($kamar) ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
            </div>

            <div class="mb-5 sm:block md:flex items-center">
    <!-- Kode Obat -->
    <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Kode Obat<span class="text-red-600">*</span></label>
    <select name="kode_brng" id="kode_brng"
        class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white"
        required onchange="updateHarga()">
        <?php foreach ($obat_list as $obat): ?>
            <option 
                value="<?= esc($obat['kode_brng']) ?>"
                data-harga-dasar="<?= esc($obat['harga_dasar']) ?>"
                data-harga-kelas1="<?= esc($obat['kelas1']) ?>"
                data-harga-kelas2="<?= esc($obat['kelas2']) ?>"
                data-harga-kelas3="<?= esc($obat['kelas3']) ?>"
                data-harga-utama="<?= esc($obat['utama']) ?>"
                data-harga-vip="<?= esc($obat['vip']) ?>"
                data-harga-vvip="<?= esc($obat['vvip']) ?>"
                <?= isset($prefill['kode_brng']) && $prefill['kode_brng'] == $obat['kode_brng'] ? 'selected' : '' ?>
            >
                <?= esc($obat['nama_obat']) ?> (<?= esc($obat['kode_brng']) ?>)
            </option>
        <?php endforeach; ?>
    </select>

    <!-- Hidden KD Bangsal -->
    <input type="hidden" id="kd_bangsal" value="<?= esc($prefill['kd_bangsal'] ?? '') ?>">
            
    <!-- Jumlah Obat -->
    <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Jumlah Obat<span class="text-red-600">*</span></label>
    <input name="jml_barang" id="jumlah_<?= esc($prefill['kode_brng'] ?? '') ?>" value="<?= esc($prefill['jumlah'] ?? '') ?>"
    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white"
    required>

</div>


            <!-- <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Harga</label>
                <input name="harga" value="<?= $prefill['harga'] ?? '' ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" required>

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Total Harga</label>
                <input name="total" value="<?= $prefill['total'] ?? '' ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
            </div> -->


            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Dosis<span class="text-red-600">*</span></label>
                <input name="dosis" value="<?= $prefill['dosis'] ?? ($obat_list[0]['aturan_pakai'] ?? '') ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" required>
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Tanggal<span class="text-red-600">*</span></label>
                <input type="date" name="tanggal" value="<?= $prefill['tanggal'] ?? date('Y-m-d') ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" required>

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Jam<span class="text-red-600">*</span></label>
                <input type="time" name="jam" value="<?= $prefill['jam'] ?? date('H:i:s') ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">No. Batch<span class="text-red-600">*</span></label>
                <input name="no_batch" value="<?= $prefill['no_batch'] ?? '' ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" required>

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">No. Faktur<span class="text-red-600">*</span></label>
                <input name="no_faktur" value="<?= $prefill['no_faktur'] ?? '' ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
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

function updateHarga() { 
    const select = document.getElementById('kode_brng');
    const selected = select.options[select.selectedIndex];

    const kdBangsalRaw = document.getElementById('kd_bangsal');
    const hargaInput = document.querySelector('input[name="harga"]');

    if (!kdBangsalRaw || !hargaInput || !selected) {
        console.warn("Missing kd_bangsal, harga input, or selected option");
        return;
    }

    const kdBangsal = kdBangsalRaw.value.toLowerCase();

    // Start with dasar as fallback
    let harga = selected.getAttribute('data-harga-dasar') || 0;

    if (kdBangsal.startsWith('vvip')) {
        harga = selected.getAttribute('data-harga-vvip') || harga;
    } else if (kdBangsal.startsWith('vup')) {
        harga = selected.getAttribute('data-harga-vip') || harga;
    } else if (kdBangsal.startsWith('utama')) {
        harga = selected.getAttribute('data-harga-utama') || harga;
    } else if (kdBangsal.startsWith('k1')) {
        harga = selected.getAttribute('data-harga-kelas1') || harga;
    } else if (kdBangsal.startsWith('k2')) {
        harga = selected.getAttribute('data-harga-kelas2') || harga;
    } else if (kdBangsal.startsWith('k3')) {
        harga = selected.getAttribute('data-harga-kelas3') || harga;
    } else {
        console.warn('🔁 Unknown kd_bangsal:', kdBangsal, '→ Using harga dasar');
        harga = selected.getAttribute('data-harga-dasar') || 0;
    }

    hargaInput.value = harga;
    updateTotal();
}


function updateTotal() {
    const harga = parseFloat(document.querySelector('input[name="harga"]').value) || 0;
    const jumlah = parseFloat(document.querySelector('input[name="jml_barang"]').value) || 0;
    document.querySelector('input[name="total"]').value = harga * jumlah;
}

// Auto update total harga kalau jumlah obat berubah
document.addEventListener('DOMContentLoaded', function() {
    const jmlInput = document.querySelector('input[name="jml_barang"]');
    jmlInput.addEventListener('input', updateTotal);
});

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
