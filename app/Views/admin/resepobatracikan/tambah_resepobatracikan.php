<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

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
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Nomor Rekam Medis</label>
                <input type="text" name="nomor_rm" value="<?= $resepobat['nomor_rm'] ?? '' ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" maxlength="80" required>
                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Nomor Rawat</label>
                <input name="nomor_rawat" value="<?= $resepobat['no_rawat'] ?? '' ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white">
            </div>
            
            <div class="mb-5 sm:block md:flex items-center">
                <label for="kode_dokter" class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Dokter Peresep</label>
                    <select id="kode_dokter" name="kode_dokter" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" required>
                        <option value="">Pilih Dokter</option>
                        <!-- Options akan diisi lewat JavaScript -->
                    </select>
                <?php
                    $generated_no_resep = 'RSP' . date('Ymd') . rand(1000, 9999);
                ?>
                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Nomor Resep</label>
                <input name="no_resep" value="<?= $generated_no_resep ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white">
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">No. Racik</label>
                <input type="text" name="no_racik" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" maxlength="80" required>
                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Nama Racikan</label>
                <input name="nama_racik" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white">
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Metode Racik</label>
                <input type="text" name="kd_racik" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" maxlength="80" required>
                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Jumlah Racik</label>
                <input name="jml_dr" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white">
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Aturan Pakai</label>
                <input type="text" name="aturan_pakai" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" maxlength="80" required>
                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Keterangan</label>
                <input name="keterangan" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white">
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label for="obat-select" class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Pilih Obat:</label>
                <select id="obat-select" multiple class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-3/4 dark:border-gray-600 dark:text-white">
                    <?php foreach ($obat_list as $obat): ?>
                        <?php if (isset($obat['kode_obat'], $obat['nama_obat'])): ?>
                            <option value="<?= $obat['kode_obat'] ?>" data-nama="<?= $obat['nama_obat'] ?>" data-kode="<?= $obat['kode_obat'] ?>">
                                <?= $obat['nama_obat'] ?>
                            </option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </select>
            </div>


            <!-- Input fields will be injected here -->
            <div id="obat-input-container" class="mt-4">
</div>


            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Tanggal</label>
                <input type="text" name="diagnosa_awal" value="<?php 
                    $tanggalHariIni = date('Y-m-d');
                    echo $tanggalHariIni; ?>"class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" maxlength="80" required>
                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Jam</label>
                <input name="diagnosa_akhir" value="<?php 
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

document.addEventListener("DOMContentLoaded", loadDokterOptions);

const obatSelect = document.getElementById("obat-select");
const container = document.getElementById("obat-input-container");

// fetch(`http://127.0.0.1:8080/v1/inventory/gudang`)
//         .then(res => res.json())
//         .then(data => {
//             const stok = data?.data?.stok ?? 'N/A';});
sessionStorage.setItem('jwt_token', "<?= session('jwt_token') ?>");
obatSelect.addEventListener("change", function () {
    const selectedValues = Array.from(obatSelect.selectedOptions).map(opt => opt.value); 

    Array.from(obatSelect.options).forEach(option => {
        const kode = option.value; // kode_obat
        const nama = option.getAttribute('data-nama');
        const inputId = `input-${kode}`;
        const existing = document.getElementById(inputId);

        if (selectedValues.includes(kode)) {
            if (selectedValues.includes(kode)) {
                if (!existing) {
                    const wrapper = document.createElement("div");
                    wrapper.id = inputId;
                    const token = sessionStorage.getItem('jwt_token');
                    console.log(sessionStorage.getItem('jwt_token'));
                    fetch(`http://127.0.0.1:8080/v1/inventory/gudang/barang/kode/${kode}`, {
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json'
                    }
                    })
                    .then(res => res.json())
                    .then(data => {
                        console.log('API response:', data); // 👈 log the whole response
                        const stok = data?.data?.stok ?? 'N/A';

                    wrapper.innerHTML = `
                    <div class="mb-6">
                        <label class="block mb-2 text-sm font-bold text-gray-900 dark:text-white">${nama}<span class="text-xs text-gray-500">(Stok: ${stok})</span></label>

                        <!-- Row 1: Jumlah & Aturan Pakai -->
                        <div class="mb-5 sm:block md:flex items-center">
                        <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">P1</label>
                        <input type="number" name="p1[${kode}]" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" required>

                        <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">P2</label>
                        <input type="text" name="p2[${kode}]" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
                        </div>

                        <!-- Row 2: Embalase & Tuslah -->
                        <div class="mb-5 sm:block md:flex items-center">
                        <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Kandungan</label>
                        <input type="number" step="0.01" name="kandungan[${kode}]" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white">

                        <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Jumlah</label>
                        <input type="number" step="0.01" name="jml[${kode}]" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white">
                        </div>

                        <input type="hidden" name="kode_barang[]" value="${kode}">
                    </div>
                    `;

                    container.appendChild(wrapper);
                })}
            }

        } else {
            // Only remove if input field is empty
            const jumlahInput = document.querySelector(`input[name="jumlah[${kode}]"]`);
            if (jumlahInput && jumlahInput.value === '') {
                const toRemove = document.getElementById(inputId);
                if (toRemove) toRemove.remove();
            }
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