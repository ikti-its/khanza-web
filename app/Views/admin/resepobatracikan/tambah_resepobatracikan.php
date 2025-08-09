@ -1,255 +1,276 @@
<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>
<!-- Tom Select CSS -->
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">

<!-- Tom Select JS -->
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
<!-- Card Section -->
<div class="max-w-[85rem] py-6 lg:py-3 px-8 mx-auto">
    <!-- Card -->
    <div class="bg-white rounded-xl shadow p-4 sm:p-7 dark:bg-slate-900">
        <div class="mb-8">
            <h2 class="text-xl font-bold text-gray-800 dark:text-gray-200">
                Tambah Resep Obat Racikan
            </h2>
        </div>
        <form action="/resepobatracikan/submittambah/" id="myForm" onsubmit="return validateForm()" method="post">
            <?= csrf_field() ?>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Nomor Rekam Medis<span class="text-red-600">*</span></label>
                <input type="text" name="nomor_rm" value="<?= $resepobatracikan['nomor_rm'] ?? '' ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" maxlength="80" required>
                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Nomor Rawat<span class="text-red-600">*</span></label>
                <input name="nomor_rawat" value="<?= $resepobatracikan['no_rawat'] ?? '' ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white">
            </div>
            
            <div class="mb-5 sm:block md:flex items-center">
                <label for="kode_dokter" class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Dokter Peresep<span class="text-red-600">*</span></label>
                    <select id="kode_dokter" name="kode_dokter" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" required>
                        <option value="">Pilih Dokter</option>
                        <!-- Options akan diisi lewat JavaScript -->
                    </select>
                <?php
                    $generated_no_resep = 'RSP' . date('Ymd') . rand(1000, 9999);
                ?>
                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Nomor Resep</label>
                <input name="no_resep" value="<?= $generated_no_resep ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" readonly>
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <?php
                    $generated_no_racik = 'RSR' . date('Ymd') . rand(1000, 9999);
                ?>
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">No. Racik</label>
                <input type="text" name="no_racik" value="<?= $generated_no_racik ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" maxlength="80" required readonly>
                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Nama Racikan<span class="text-red-600">*</span></label>
                <input name="nama_racik" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Metode Racik<span class="text-red-600">*</span></label>
                <input type="text" name="kd_racik" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" maxlength="80" required>
                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Jumlah Racik<span class="text-red-600">*</span></label>
                <input name="jml_dr" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Aturan Pakai<span class="text-red-600">*</span></label>
                <input type="text" name="aturan_pakai" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" maxlength="80" required>
                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Keterangan</label>
                <input name="keterangan" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white">
            </div>

<div class="mb-5 sm:block md:flex items-center">
    <label for="obat-select" class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">
        Pilih Obat:
    </label>
    <div class="w-full md:w-3/4">
        <select id="obat-select" multiple
            class="w-full text-gray-900 text-sm rounded-lg p-2 dark:border-gray-600 dark:text-white">
            <?php foreach ($obat_list as $obat): ?>
                <option 
                    value="<?= $obat['kode_obat'] ?>"
                    data-nama="<?= $obat['nama_obat'] ?>"
                    data-stok="<?= $obat['stok'] ?? 0 ?>"
                    data-kapasitas="<?= $obat['kapasitas'] ?? 0 ?>"
                >
                    <?= $obat['nama_obat'] ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
</div>



            <!-- Input fields will be injected here -->
            <div id="obat-input-container" class="mt-4">
</div>


            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Tanggal<span class="text-red-600">*</span></label>
                <input type="date" name="diagnosa_awal" value="<?php 
                    $tanggalHariIni = date('Y-m-d');
                    echo $tanggalHariIni; ?>"class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" maxlength="80" required>
                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Jam<span class="text-red-600">*</span></label>
                <input type="time" name="diagnosa_akhir" value="<?php 
    $jamSekarang = date('H:i:s');
    echo $jamSekarang; ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white">
            </div>
            <!-- <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white w-1/5 lg:w-1/4">Biaya</label>
                <input type="text" name="biaya" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" maxlength="80" required>
            </div> -->
            <div class="mt-5 pt-5 border-t flex justify-end gap-x-2">
                <a href="javascript:history.back()" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-white dark:hover:bg-gray-800 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                    Kembali
                </a>
                <button type="submit" id="submitButton" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-[#0A2D27] text-[#ACF2E7] hover:bg-[#13594E] disabled:opacity-50 disabled:pointer-events-none dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                    Simpan
                </button>
            </div>
        </form>
    </div>
    <!-- End Card -->
</div>
<!-- End Card Section -->
<script>
async function loadDokterOptions() {
  const token = "<?= session()->get('jwt_token') ?>";
  const selectDokter = document.querySelector('select[name="kode_dokter"]');

  try {
    const response = await fetch('http://127.0.0.1:8080/v1/dokter', {
      headers: {
        'Authorization': `Bearer ${token}`,
        'Accept': 'application/json'
      }
    });

    const json = await response.json();

    if (!json.data || !Array.isArray(json.data)) {
      console.error("❌ Unexpected response structure:", json);
      return;
    }

    json.data.forEach(dokter => {
      const option = document.createElement("option");
      option.value = dokter.kode_dokter;
      option.textContent = `${dokter.kode_dokter} - ${dokter.nama_dokter}`;
      selectDokter.appendChild(option);
    });

  } catch (err) {
    console.error("❌ Failed to load dokter list:", err);
  }
}

document.addEventListener("DOMContentLoaded", () => {
  loadDokterOptions();

  // ✅ Apply Tom Select for searchable multi-obat selection
  new TomSelect("#obat-select", {
    maxItems: null,
    plugins: ['remove_button'],
    placeholder: "Cari dan pilih obat...",
    persist: false,
    create: false
  });

  sessionStorage.setItem('jwt_token', "<?= session('jwt_token') ?>");

  const obatSelect = document.getElementById("obat-select");
  const container = document.getElementById("obat-input-container");

  obatSelect.addEventListener("change", function () {
    const selectedValues = Array.from(obatSelect.selectedOptions).map(opt => opt.value);

    selectedValues.forEach(kode => {
      const inputId = `group-${kode}`;
      const existing = document.getElementById(inputId);

      if (!existing) {
        const option = Array.from(obatSelect.options).find(opt => opt.value === kode);
        const nama = option?.getAttribute("data-nama") || '';

        fetch(`http://127.0.0.1:8080/v1/gudang-barang/${kode}`, {
          headers: {
            "Authorization": `Bearer ${sessionStorage.getItem('token')}`,
            "Accept": "application/json"
          }
        })
        .then(res => res.json())
        .then(data => {
          const stok = data?.data?.stok ?? '0';
          const kapasitas = data?.data?.kapasitas ?? '0';

          const wrapper = document.createElement("div");
          wrapper.id = `group-${kode}`;
          wrapper.classList.add("mb-6", "border", "p-4", "rounded-xl", "shadow-sm");

          wrapper.innerHTML = `
            <div class="flex items-center mb-2">
              <input type="checkbox" id="checkbox-${kode}" class="checkbox-kode mr-2 w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded" checked>
              <label for="checkbox-${kode}" class="text-sm font-bold text-gray-900 dark:text-white">
                ${nama} <span class="text-xs text-gray-500">(Stok: ${stok})</span>
              </label>
            </div>

            <div class="mb-5 sm:block md:flex items-center">
              <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Stok</label>
              <input type="number" step="0.01" name="stok[${kode}]" value="${stok}" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" readonly>

              <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Kapasitas</label>
              <input type="number" step="0.01" name="kapasitas[${kode}]" value="${kapasitas}" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" readonly>
            </div>

            <div class="mb-5 sm:block md:flex items-center">
              <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">P1/P2<span class="text-red-600">*</span></label>
              <div class="flex gap-4">
                <input type="text" name="p1[${kode}]" placeholder="P1" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-1/4 dark:border-gray-600 dark:text-white" required>
                /
                <input type="text" name="p2[${kode}]" placeholder="P2" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-1/4 dark:border-gray-600 dark:text-white" required>
              </div>
            </div>

            <div class="mb-5 sm:block md:flex items-center">
              <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Kandungan<span class="text-red-600">*</span></label>
              <input type="number" step="0.01" name="kandungan_input[${kode}]" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" required>

              <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Jumlah</label>
              <input type="number" step="0.01" name="jml_input[${kode}]" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" readonly>
            </div>

            <input type="hidden" name="kode_barang[]" value="${kode}">
          `;

          container.appendChild(wrapper);

          document.getElementById(`checkbox-${kode}`).addEventListener("change", function () {
            if (!this.checked) {
              document.getElementById(`group-${kode}`)?.remove();
              option.selected = false;
              obatSelect.dispatchEvent(new Event('change'));
            }
          });
        })
        .catch(err => {
          console.error(`Failed to fetch data for kode ${kode}:`, err);
        });
      }
    });

    // 🔄 Remove wrappers for unselected items
    Array.from(obatSelect.options).forEach(option => {
      const kode = option.value;
      if (!selectedValues.includes(kode)) {
        document.getElementById(`group-${kode}`)?.remove();
      }
    });
  });
});


document.addEventListener("DOMContentLoaded", function () {
    const container = document.getElementById("obat-input-container"); // ✅ make sure this is defined
    const jumlahRacikInput = document.querySelector('input[name="jml_dr"]');

    container.addEventListener("input", function (e) {
        const name = e.target.name;
        if (!name.startsWith("kandungan_input") && !name.startsWith("p1[") && !name.startsWith("p2[")) return;

        const kodeMatch = name.match(/\[(.*?)\]/);
        if (!kodeMatch) return;
        const kode = kodeMatch[1];

        const kandungan = parseFloat(document.querySelector(`input[name="kandungan_input[${kode}]"]`)?.value) || 0;
        const kapasitas = parseFloat(document.querySelector(`input[name="kapasitas[${kode}]"]`)?.value) || 0;
        const jumlahRacik = parseFloat(jumlahRacikInput?.value) || 0;
        const p1 = parseFloat(document.querySelector(`input[name="p1[${kode}]"]`)?.value) || 1;
        const p2 = parseFloat(document.querySelector(`input[name="p2[${kode}]"]`)?.value) || 1;

        const jumlahInput = document.querySelector(`input[name="jml_input[${kode}]"]`);

        if (jumlahInput && kapasitas > 0 && kandungan > 0 && jumlahRacik > 0 && p2 !== 0) {
            const jumlah = (kandungan / kapasitas) * jumlahRacik * (p1 / p2);
            jumlahInput.value = jumlah.toFixed(2);
        }
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
        // Ubah teks tombol menjadi sesuatu yang menunjukkan proses sedang berlangsung, misalnya "Menyimpan..."
        submitButton.innerHTML = 'Menyimpan...';
        return true;
    }
</script>
<?= $this->endSection(); ?>