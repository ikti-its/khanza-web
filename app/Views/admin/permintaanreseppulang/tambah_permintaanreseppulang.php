<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<?php
$kelas = strtolower($pemberianobat['kelas'] ?? 'dasar');
?>
<!-- Tom Select CSS -->
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">

<!-- Tom Select JS -->
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>

<!-- Card Section -->
<div class="max-w-[85rem] py-6 lg:py-3 px-8 mx-auto">
    <!-- Card -->
    <div class="bg-white rounded-xl shadow p-4 sm:p-7 dark:bg-slate-900">
        <?= view('components/form/judul', [
            'judul' => 'Tambah Permintaan Resep Pulang'
        ]) ?>
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
                <label for="obat-select" class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">
                    Pilih Obat:
                </label>
                <div class="w-full md:w-3/4">
                    <select id="obat-select"
                            multiple
                            class="w-full border border-gray-300 text-gray-900 text-sm rounded-lg dark:border-gray-600 dark:text-white">
                        <?php foreach ($obat_list as $obat): ?>
                            <?php if (isset($obat['kode_obat'], $obat['nama_obat'])): ?>
                                <option value="<?= $obat['kode_obat'] ?>" data-nama="<?= $obat['nama_obat'] ?>">
                                    <?= $obat['nama_obat'] ?>
                                </option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>



            <!-- Input fields will be injected here -->
            <div id="obat-input-container" class="mt-4">
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

<!-- ✅ FINAL SCRIPT: Tom Select ONLY on #obat-select -->
<script>
document.addEventListener('DOMContentLoaded', () => {
  // 🔍 Make #obat-select searchable & multi-selectable
  new TomSelect('#obat-select', {
    maxItems: null,
    plugins: ['remove_button'],
    placeholder: 'Cari dan pilih obat...',
    persist: false,
    create: false
  });

  const obatSelect  = document.getElementById('obat-select');
  const container   = document.getElementById('obat-input-container');

  obatSelect.addEventListener('change', () => {
    const selectedValues = Array.from(obatSelect.selectedOptions).map(opt => opt.value);

    Array.from(obatSelect.options).forEach(option => {
      const kode     = option.value;
      const nama     = option.getAttribute('data-nama');
      const inputId  = `input-${kode}`;
      const existing = document.getElementById(inputId);

      if (selectedValues.includes(kode)) {
        if (!existing) {
          const wrapper = document.createElement('div');
          wrapper.id = inputId;
          wrapper.classList.add('mb-6', 'border', 'p-4', 'rounded-xl', 'shadow-sm');

          wrapper.innerHTML = `
            <div class="flex items-center mb-2">
              <input type="checkbox"
                     id="checkbox-${kode}"
                     class="checkbox-kode mr-2 w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded"
                     checked>
              <label for="checkbox-${kode}"
                     class="text-sm font-bold text-gray-900 dark:text-white">
                ${nama}
              </label>
            </div>

            <div class="mb-5 sm:block md:flex items-center">
              <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">
                Jumlah<span class="text-red-600">*</span>
              </label>
              <input type="number" name="jumlah[${kode}]"
                     class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white"
                     required>

              <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">
                Aturan Pakai<span class="text-red-600">*</span>
              </label>
              <input type="text" name="aturan_pakai[${kode}]"
                     class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white"
                     required>
            </div>

            <input type="hidden" name="kode_barang[]" value="${kode}">
          `;

          container.appendChild(wrapper);

          // remove the input set if the checkbox is unchecked
          document.getElementById(`checkbox-${kode}`).addEventListener('change', function () {
            if (!this.checked) {
              document.getElementById(inputId)?.remove();
              option.selected = false;
              obatSelect.dispatchEvent(new Event('change')); // re-trigger change
            }
          });
        }
      } else {
        const jumlahInput = document.querySelector(`input[name="jumlah[${kode}]"]`);
        if (jumlahInput && jumlahInput.value === '') {
          document.getElementById(inputId)?.remove();
        }
      }
    });
  });

  // 🔒 Form validation before submit
  window.validateForm = function () {
    const requiredFields = document.querySelectorAll('select[required], input[required]');
    for (let i = 0; i < requiredFields.length; i++) {
      if (!requiredFields[i].value) {
        alert('Isi semua field.');
        return false;
      }
    }
    const submitButton = document.getElementById('submitButton');
    if (submitButton) {
      submitButton.setAttribute('disabled', true);
      submitButton.innerHTML = 'Menyimpan...';
    }
    return true;
  };
});
</script>


<?= $this->endSection(); ?>
