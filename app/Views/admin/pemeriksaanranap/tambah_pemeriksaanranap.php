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
                Tambah Pemeriksaan Rawat Inap
            </h2>
        </div>
        <form action="<?= base_url('pemeriksaanranap/submittambah') ?>" id="myForm" onsubmit="return validateForm()" method="post">
            <?= csrf_field() ?>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Nomor Rawat<span class="text-red-600">*</span></label>
                <input name="nomor_rawat" value="<?= esc($prefill['nomor_rawat'] ?? '') ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" required>

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Nama Pasien<span class="text-red-600">*</span></label>
                <input name="nama_pasien" value="<?= esc($prefill['nama_pasien'] ?? '') ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Tanggal<span class="text-red-600">*</span></label>
                <input type="date" name="tgl_perawatan" value="<?= date('Y-m-d') ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" required>

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Jam<span class="text-red-600">*</span></label>
                <input type="time" name="jam_rawat" value="<?= date('H:i:s') ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
            </div>

            <!-- NIP Select + Autofill -->
            <div class="mb-5 sm:block md:flex items-center">
                <!-- NIP Input -->
                <label for="nip-input" class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Kode Dokter/Perawat<span class="text-red-600">*</span></label>
                <input list="nip-list" id="nip-input" name="nip" type="text"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white"
                    required>
                
                <!-- NIP Suggestions -->
                <datalist id="nip-list">
                    <!-- Example: <option value="1234567890"> -->
                    <?php foreach ($nip_list as $nip): ?>
                        <option value="<?= esc($nip) ?>">
                    <?php endforeach; ?>
                </datalist>

                <!-- Petugas Name Output -->
                <label for="petugas-input" class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Nama</label>
                <input id="petugas-input" name="nama_petugas" type="text"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white"
                     readonly>
            </div>

            <!-- Profesi/Jabatan Output -->
            <div class="mb-5 sm:block md:flex items-center">
                <label for="profesi-input" class="block mb-2 text-sm text-gray-900 dark:text-white md:w-1/4">Profesi/Jabatan</label>
                <input id="profesi-input" name="profesi" type="text"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white"
                     readonly>
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 text-sm text-gray-900 dark:text-white md:w-1/4">GCS (E,V,M)<span class="text-red-600">*</span></label>
                <input name="gcs" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">TD (mmHg)<span class="text-red-600">*</span></label>
                <input name="tensi" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 text-sm text-gray-900 dark:text-white md:w-1/4">HR (x/menit)<span class="text-red-600">*</span></label>
                <input name="nadi" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">RR (x/menit)<span class="text-red-600">*</span></label>
                <input name="respirasi" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 text-sm text-gray-900 dark:text-white md:w-1/4">Suhu (°C)<span class="text-red-600">*</span></label>
                <input name="suhu_tubuh" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
                
                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">SpO2 (%)<span class="text-red-600">*</span></label>
                <input name="spo2" id="totalObat" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 text-sm text-gray-900 dark:text-white md:w-1/4">Tinggi (cm)<span class="text-red-600">*</span></label>
                <input name="tinggi" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
                
                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Berat (kg)<span class="text-red-600">*</span></label>
                <input name="berat" id="totalObat" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 text-sm text-gray-900 dark:text-white md:w-1/4">Subjek<span class="text-red-600">*</span></label>
                <input name="keluhan" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
                
                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Objek<span class="text-red-600">*</span></label>
                <input name="pemeriksaan" id="totalObat" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 text-sm text-gray-900 dark:text-white md:w-1/4">Kesadaran<span class="text-red-600">*</span></label>
                <select name="kesadaran" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
                    <option value="Compos Mentis">Compos Mentis</option>
                    <option value="Somnolence">Somnolence</option>
                    <option value="Sopor">Sopor</option>
                    <option value="Coma">Coma</option>
                </select>

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Alergi<span class="text-red-600">*</span></label>
                <input name="alergi" id="totalObat" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 text-sm text-gray-900 dark:text-white md:w-1/4">Asesmen<span class="text-red-600">*</span></label>
                <input name="penilaian" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
                
                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Plan<span class="text-red-600">*</span></label>
                <input name="rtl" id="totalObat" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 text-sm text-gray-900 dark:text-white md:w-1/4">Instruksi</label>
                <input name="instruksi" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white">
                
                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Evaluasi</label>
                <input name="evaluasi" id="totalObat" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white">
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
async function fetchFromBothEndpoints(nip) {
  const token = "<?= session()->get('jwt_token') ?>";

  try {
    // 🔍 Check pegawai first
    const pegawaiRes = await fetch(`http://127.0.0.1:8080/v1/pegawai/nip/${encodeURIComponent(nip)}`, {
      headers: {
        "Authorization": `Bearer ${token}`,
        "Accept": "application/json"
      }
    });

    if (pegawaiRes.ok) {
      const data = await pegawaiRes.json();
      console.log("🧪 Pegawai API response:", data);
      return {
        nama: data.nama || data.data?.Nama || '',
        profesi: data.data?.Jabatan || 'Pegawai'
      };
    }

    // 🔍 If pegawai not found, try dokter
    if (pegawaiRes.status === 404) {
      const dokterRes = await fetch(`http://127.0.0.1:8080/v1/dokter/${encodeURIComponent(nip)}`, {
        headers: {
          "Authorization": `Bearer ${token}`,
          "Accept": "application/json"
        }
      });

      if (dokterRes.ok) {
        const data = await dokterRes.json();
        console.log("🧪 Dokter API response:", data);
        return {
          nama: data.nama || data.data?.nama_dokter || '',
          profesi: data.data?.Jabatan || 'Dokter'
        };
      }

      if (dokterRes.status === 404) {
        alert("Tolong masukkan kode dokter atau petugas.");
        return null;
      }

      // Unexpected dokter error
      throw new Error(`Unexpected error from dokter: ${dokterRes.status}`);
    }

    // Unexpected pegawai error
    throw new Error(`Unexpected error from pegawai: ${pegawaiRes.status}`);

  } catch (err) {
    console.error("❌ Error in fetchFromBothEndpoints:", err);
    alert("Terjadi kesalahan saat mencari data NIP.");
    return null;
  }
}



document.addEventListener("DOMContentLoaded", function () {
  const nipInput = document.getElementById("nip-input");
  const petugasInput = document.getElementById("petugas-input");
  const profesiInput = document.getElementById("profesi-input");

  let debounceTimer;

  nipInput.addEventListener("input", function () {
    clearTimeout(debounceTimer);

    debounceTimer = setTimeout(async () => {
      const nip = nipInput.value.trim();
      console.log("👀 NIP typed:", nip);
      if (!nip) return;

      const data = await fetchFromBothEndpoints(nip);
      if (data) {
        console.log("💡 Setting petugasInput to:", data.nama);
        petugasInput.value = data.nama;
        profesiInput.value = data.profesi;
      } else {
        petugasInput.value = '';
        profesiInput.value = '';
      }

    }, 500); // Debounce delay
  });
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
