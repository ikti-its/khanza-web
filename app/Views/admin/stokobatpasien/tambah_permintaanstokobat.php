<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<!-- Card Section -->
<div class="max-w-[85rem] py-6 lg:py-3 px-8 mx-auto">
    <!-- Card -->
    <div class="bg-white rounded-xl shadow p-4 sm:p-7 dark:bg-slate-900">
        <div class="mb-8">
            <h2 class="text-xl font-bold text-gray-800 dark:text-gray-200">
                Tambah Permintaan Stok Obat
            </h2>
        </div>
        <form action="/permintaanstokobat/submittambah/" id="myForm" onsubmit="return validateForm()" method="post">
            <?= csrf_field() ?>

            <div class="mb-5 sm:block md:flex items-center">
                <?php
                    $generated_no_resep = 'RSP' . date('Ymd') . rand(1000, 9999);
                ?>
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Nomor Permintaan</label>
                <input type="text" name="no_permintaan" value="<?= $generated_no_resep ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" maxlength="80" required>
                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Nomor Rawat</label>
                <input name="no_rawat" value="<?= $no_rawat ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white">
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
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Dokter</label>
                <input type="text" name="kode_dokter" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" maxlength="80" required>
                <!-- <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Status</label>
                <input name="nomor_rawat" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white"> -->
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

                // Generate 24-hour checkboxes for this obat
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

                jamCheckboxes += `
                        </div>
                    </div>
                `;

                wrapper.innerHTML = `
                    <div class="mb-6 border-t pt-4 mt-4">
                        <label class="block mb-2 text-sm font-bold text-gray-900 dark:text-white">${nama}</label>

                        <!-- Row 1: Jumlah & Aturan Pakai -->
                        <div class="mb-5 sm:block md:flex items-center">
                            <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Jumlah</label>
                            <input
                              type="number"
                              name="jumlah[${kode}]"
                              class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white"
                              required
                            >

                            <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Aturan Pakai</label>
                            <input
                              type="text"
                              name="aturan_pakai[${kode}]"
                              class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white"
                              required
                            >
                        </div>

                        <!-- Row 2: Embalase & Tuslah -->
                        <div class="mb-5 sm:block md:flex items-center">
                            <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Embalase</label>
                            <input
                              type="number"
                              step="0.01"
                              name="embalase[${kode}]"
                              class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white"
                            >

                            <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Tuslah</label>
                            <input
                              type="number"
                              step="0.01"
                              name="tuslah[${kode}]"
                              class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white"
                            >
                        </div>

                        ${jamCheckboxes}

                        <input type="hidden" name="kode_barang[]" value="${kode}">
                    </div>
                `;

                container.appendChild(wrapper);
            }

        } else {
            // Only remove if the jumlah field is still empty
            const jumlahInput = document.querySelector(`input[name="jumlah[${kode}]"]`);
            if (jumlahInput && jumlahInput.value === '') {
                const toRemove = document.getElementById(inputId);
                if (toRemove) toRemove.remove();
            }
        }
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