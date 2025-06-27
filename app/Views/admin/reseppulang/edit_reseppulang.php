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
            'judul' => 'Edit Resep Pulang'
        ]) ?>
        <form action="<?= base_url('reseppulang/submittambah') ?>" id="myForm" onsubmit="return validateForm()" method="post">
            <?= csrf_field() ?>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Nomor Rawat</label>
                <input name="nomor_rawat" value="<?= $prefill['no_rawat'] ?? '' ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" required>
            
                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Kamar</label>
                <input name="kd_bangsal" value="<?= $prefill['kd_bangsal'] ?? '' ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Kode Obat</label>
                <select name="kode_brng" id="kode_brng" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" required onchange="updateHarga()">
                    <option value="">-- Pilih Obat --</option>
                    <?php foreach ($obat_list as $obat): ?>
                        <option 
                            value="<?= esc($obat['kode_obat']) ?>"
                            data-harga-dasar="<?= esc($obat['Dasar']) ?>"
                            data-harga-kelas1="<?= esc($obat['Kelas1']) ?>"
                            data-harga-kelas2="<?= esc($obat['Kelas2']) ?>"
                            data-harga-kelas3="<?= esc($obat['Kelas3']) ?>"
                            data-harga-utama="<?= esc($obat['Utama']) ?>"
                            data-harga-vip="<?= esc($obat['VIP']) ?>"
                            data-harga-vvip="<?= esc($obat['VVIP']) ?>"
                            <?= isset($prefill['kode_brng']) && $prefill['kode_brng'] == $obat['kode_obat'] ? 'selected' : '' ?>
                        >
                            <?= esc($obat['nama_obat']) ?> (<?= esc($obat['kode_obat']) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>

                <input type="hidden" id="kd_bangsal" value="<?= esc($prefill['kd_bangsal'] ?? '') ?>">

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Jumlah Obat</label>
                <input name="jml_barang" value="<?= $prefill['jml_barang'] ?? '' ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Harga</label>
                <input name="harga" value="<?= $prefill['harga'] ?? '' ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" required>

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Total Harga</label>
                <input name="total" value="<?= $prefill['total'] ?? '' ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
            </div>


            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Dosis</label>
                <input name="dosis" value="<?= $prefill['dosis'] ?? '' ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" required>
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Tanggal</label>
                <input name="tanggal" value="<?= $prefill['tanggal'] ?? '' ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" required>

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Jam</label>
                <input name="jam" value="<?= $prefill['jam'] ?? '' ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">No. Batch</label>
                <input name="no_batch" value="<?= $prefill['no_batch'] ?? '' ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" required>

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">No. Faktur</label>
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

    const kdBangsal = document.getElementById('kd_bangsal').value.toLowerCase();

    let harga = selected.getAttribute('data-harga-dasar') || 0; // Default ke harga dasar

    if (kdBangsal.startsWith('vvip')) {
        harga = selected.getAttribute('data-harga-vvip') || harga;
    } else if (kdBangsal.startsWith('VUP')) {
        harga = selected.getAttribute('data-harga-vip') || harga;
    } else if (kdBangsal.startsWith('utama')) {
        harga = selected.getAttribute('data-harga-utama') || harga;
    } else if (kdBangsal.startsWith('K1')) {
        harga = selected.getAttribute('data-harga-kelas1') || harga;
    } else if (kdBangsal.startsWith('K2')) {
        harga = selected.getAttribute('data-harga-kelas2') || harga;
    } else if (kdBangsal.startsWith('K3')) {
        harga = selected.getAttribute('data-harga-kelas3') || harga;
    }

    document.querySelector('input[name="harga"]').value = harga;
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
