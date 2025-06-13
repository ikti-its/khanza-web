<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<!-- Card Section -->
<div class="max-w-[85rem] py-6 lg:py-3 px-8 mx-auto">
    <!-- Card -->
    <div class="bg-white rounded-xl shadow p-4 sm:p-7 dark:bg-slate-900">
        <?= view('components/form_judul', [
            'judul' => 'Tambah Permintaan Stok Obat'
        ]) ?>
        <form action="/permintaanstokobat/submittambah/" id="myForm" onsubmit="return validateForm()" method="post">
            <?= csrf_field() ?>

            <div class="mb-5 sm:block md:flex items-center">
                <?php
                    $generated_no_resep = 'SOP' . date('Ymd') . rand(1000, 9999);
                ?>
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Nomor Permintaan</label>
                <input type="text" name="no_permintaan" value="<?= $generated_no_resep ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" maxlength="80" required>
                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Nomor Rawat</label>
                <input name="no_rawat" value="<?= $permintaanstokobat['no_rawat'] ?? '' ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white">

            </div>
            
            <?php
                $tanggal_sekarang = date('Y-m-d'); // Format: 2025-05-02
                $jam_sekarang = date('H:i:s');     // Format: 17:45:32
            ?>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Tanggal</label>
                <input type="text" name="tgl_permintaan" value="<?= $tanggal_sekarang ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" maxlength="80" required>

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Jam</label>
                <input name="jam" value="<?= $jam_sekarang ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white">
            </div>

            <div class="mb-5 sm:block md:flex items-center">
              <label for="dokter-select" class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Dokter Peresep</label>
              <select name="kode_dokter" id="dokter-select" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" required>
                  <option value="">Pilih Dokter</option>
                  <?php foreach ($dokterList as $dokter): ?>
                      <option value="<?= esc($dokter['kode_dokter']) ?>">
                          <?= esc($dokter['nama_dokter']) ?>
                      </option>
                  <?php endforeach; ?>
              </select>
          </div>


            <div class="mb-5 sm:block md:flex items-center">
                <label for="obat-select" class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Pilih Obat:</label>
                <select id="obat-select" multiple class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-3/4 dark:border-gray-600 dark:text-white">
                    <?php foreach ($obat_list as $obat): ?>
                        <?php if (isset($obat['kode_obat'], $obat['nama_obat'])): ?>
                            <option value="<?= $obat['kode_obat'] ?>" data-nama="<?= $obat['nama_obat'] ?>">
                                <?= $obat['nama_obat'] ?>
                            </option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </select>
            </div>


            <!-- Input fields will be injected here -->
            <div id="obat-input-container" class="mt-4">
</div>
            <!-- <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white w-1/5 lg:w-1/4">Biaya</label>
                <input type="text" name="biaya" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" maxlength="80" required>
            </div> -->
            <?= view('components/form_submit_button') ?>
        </form>
    </div>
    <!-- End Card -->
</div>
<!-- End Card Section -->
<script>
document.addEventListener("DOMContentLoaded", function () {
  const submitBtn = document.getElementById("submitButton");
  if (!submitBtn) {
    console.error("❌ submitButton not found");
    return;
  }

  

  const obatSelect = document.getElementById("obat-select");
  const container = document.getElementById("obat-input-container");

  obatSelect.addEventListener("change", function () {
    const selectedValues = Array.from(obatSelect.selectedOptions).map(opt => opt.value);

    Array.from(obatSelect.options).forEach(option => {
      const kode = option.value;
      const nama = option.getAttribute('data-nama');
      const inputId = `input-${kode}`;
      const existing = document.getElementById(inputId);

      if (selectedValues.includes(kode)) {
        if (!existing) {
          const wrapper = document.createElement("div");
          wrapper.id = inputId;
          wrapper.classList.add("obat-row");

          let jamCheckboxes = `
            <div class="mb-5">
              <label class="block mb-2 text-sm text-gray-900 dark:text-white">Checklist Jam</label>
              <div class="flex flex-wrap gap-2">
          `;

          for (let i = 0; i < 24; i++) {
            const jam = i.toString().padStart(2, '0');
            jamCheckboxes += `
              <label class="inline-flex items-center space-x-1">
                <input
                  type="checkbox"
                  name="jam_obat[${kode}][]"
                  value="${jam}"
                  class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                >
                <span class="text-xs">${jam}</span>
              </label>
            `;
          }

          jamCheckboxes += `</div></div>`;

          wrapper.innerHTML = `
            <div class="mb-6 border-t pt-4 mt-4">
              <label class="block mb-2 text-sm font-bold text-gray-900 dark:text-white">${nama}</label>
              <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Jumlah</label>
                <input type="number" name="jumlah[${kode}]" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" required>
                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Aturan Pakai</label>
                <input type="text" name="aturan_pakai[${kode}]" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
              </div>
              <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Embalase</label>
                <input type="number" step="0.01" name="embalase[${kode}]" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white">
                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Tuslah</label>
                <input type="number" step="0.01" name="tuslah[${kode}]" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white">
              </div>
              ${jamCheckboxes}
              <input type="hidden" name="kode_barang[]" value="${kode}">
              <input type="hidden" name="kd_bangsal[${kode}]" value="B001">
              <input type="hidden" name="no_batch[${kode}]" value="BTCH001">
              <input type="hidden" name="no_faktur[${kode}]" value="FKT20250502">
            </div>
          `;

          container.appendChild(wrapper);
        }
      } else {
        const jumlahInput = document.querySelector(`input[name="jumlah[${kode}]"]`);
        if (jumlahInput && jumlahInput.value === '') {
          const toRemove = document.getElementById(inputId);
          if (toRemove) toRemove.remove();
        }
      }
    });
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