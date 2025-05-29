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
                Edit Pemeriksaan Rawat Inap
            </h2>
        </div>
        <form action="/pemeriksaanranap/submitedit/<?= $pemeriksaan['no_rawat'] ?>" id="myForm" onsubmit="return validateForm()" method="post">
            <?= csrf_field() ?>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Nomor Rawat</label>
                <input name="nomor_rawat" value="<?= esc($pemeriksaan['no_rawat']) ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" required>

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Nama Pasien</label>
                <input name="nama_pasien" value="<?= esc($nama_pasien) ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Tanggal</label>
                <input name="tanggal" value="<?= date('Y-m-d') ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" required>

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Jam</label>
                <input name="jam" value="<?= date('H:i:s') ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">NIP</label>
                <input id="nip-input" value="<?= esc($pemeriksaan['nip']) ?>" name="nip" type="text"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" 
                    required>

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Petugas</label>
                <input id="petugas-input" name="nama_petugas"  type="text" value="<?= esc($nama_petugas) ?>"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" 
                    required>
            </div>


            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 text-sm text-gray-900 dark:text-white md:w-1/4">GCS (E,V,M)</label>
                <input name="gudang" value="<?= esc($pemeriksaan['gcs']) ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">TD (mmHg)</label>
                <input name="no_batch" value="<?= esc($pemeriksaan['tensi']) ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 text-sm text-gray-900 dark:text-white md:w-1/4">HR (x/menit)</label>
                <input name="no_faktur" value="<?= esc($pemeriksaan['nadi']) ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">RR (x/menit)</label>
                <input name="tanggal_beri" value="<?= esc($pemeriksaan['respirasi']) ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 text-sm text-gray-900 dark:text-white md:w-1/4">Suhu</label>
                <input name="jam_beri" value="<?= esc($pemeriksaan['suhu_tubuh']) ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
                
                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">SpO2</label>
                <input name="total" id="totalObat" value="<?= esc($pemeriksaan['spo2']) ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" readonly>
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
document.addEventListener("DOMContentLoaded", function () {
    const nipInput = document.getElementById("nip-input");
    const petugasInput = document.getElementById("petugas-input");

    let debounceTimer;

    nipInput.addEventListener("input", function () {
        clearTimeout(debounceTimer);

        debounceTimer = setTimeout(() => {
            const nip = nipInput.value.trim();
            console.log("👀 NIP typed:", nip); // Debug log

            if (!nip) return;

            fetch(`http://127.0.0.1:8080/v1/pegawai/nip/${encodeURIComponent(nip)}`, {
                method: "GET",
                headers: {
                    "Accept": "application/json",
                    "Authorization": "Bearer <?= session()->get('jwt_token') ?>", // ensure this is not empty
                }
            })
            .then(res => {
                console.log("✅ API response status:", res.status); // Debug log
                if (!res.ok) throw new Error(`HTTP error: ${res.status}`);
                return res.json();
            })
            .then(data => {
                console.log("📦 API returned:", data); // keep this for debugging

                // ✅ Fix this line:
                if (data && data.data && data.data.Nama) {
                    petugasInput.value = data.data.Nama;
                } else {
                    petugasInput.value = '';
                }
            })
            .catch(err => {
                console.error("❌ API fetch error:", err);
                petugasInput.value = '';
            });

        }, 500); // debounce wait
    });
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
