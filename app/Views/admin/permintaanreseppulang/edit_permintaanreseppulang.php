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
            'judul' => 'Edit Permintaan Resep Pulang'
        ]) ?>
        <form action="<?= base_url('permintaanreseppulang/submitedit/' . ($permintaanreseppulang['no_permintaan'] ?? '')) ?>" method="post" id="myForm">
            <?= csrf_field() ?>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Nomor Permintaan</label>
                <input name="no_permintaan" value="<?= $permintaanreseppulang['no_permintaan'] ?? '' ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" required>
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Tanggal Permintaan</label>
                <input name="tgl_permintaan" value="<?= $permintaanreseppulang['tgl_permintaan'] ?? '' ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" required>

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Jam Permintaan</label>
                <input name="jam" value="<?= $permintaanreseppulang['jam'] ?? '' ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Nomor Rawat</label>
                <input name="no_rawat" value="<?= $permintaanreseppulang['no_rawat'] ?? '' ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" required>


                    <label for="kd_dokter" class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Kode Dokter</label>
                    <select id="kd_dokter" name="kd_dokter" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
                        <option disabled selected value="">Pilih Dokter</option>
                        <?php foreach ($dokter_list as $dokter): ?>
                            <option value="<?= $dokter['kode_dokter'] ?>"
                                <?= (isset($permintaanreseppulang['kode_dokter']) && $permintaanreseppulang['kode_dokter'] == $dokter['kode_dokter']) ? 'selected' : '' ?>>
                                <?= $dokter['kode_dokter'] . ' - ' . $dokter['nama_dokter'] ?>
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

<!-- ✅ This was missing -->
<div id="obat-input-container" class="mt-5"></div>
            <div class="mb-5 sm:block md:flex items-center">
                <label for="status" class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Status Validasi</label>
                <select id="status" name="status" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
                    <option value="Belum" <?= (isset($permintaanreseppulang['status']) && $permintaanreseppulang['status'] == 'Belum') ? 'selected' : '' ?>>Belum</option>
                    <option value="Sudah" <?= (isset($permintaanreseppulang['status']) && $permintaanreseppulang['status'] == 'Sudah') ? 'selected' : '' ?>>Sudah</option>
                </select>
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Tanggal Validasi</label>
                <input name="tgl_validasi" value="<?= $permintaanreseppulang['tgl_validasi'] ?? '' ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" required>

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Jam Validasi</label>
                <input name="jam_validasi" value="<?= $permintaanreseppulang['jam_validasi'] ?? '' ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
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
async function loadDokterOptions() {
    const token = "<?= session()->get('jwt_token') ?>";
    const selectDokter = document.querySelector('select[name="kd_dokter"]');

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

sessionStorage.setItem('jwt_token', "<?= session('jwt_token') ?>");

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
                const token = sessionStorage.getItem('jwt_token');
                fetch(`http://127.0.0.1:8080/v1/gudang-barang/${kode}`, {
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    const stok = data?.data?.stok ?? 'N/A';
                    const no_batch = data?.data?.no_batch ?? '-';
                    const no_faktur = data?.data?.no_faktur ?? '-';

                    const wrapper = document.createElement("div");
                    wrapper.id = inputId;
                    wrapper.classList.add("mb-6", "border", "p-4", "rounded-xl", "shadow-sm");

                    wrapper.innerHTML = `
                        <div class="flex items-center mb-2">
                            <input type="checkbox" id="checkbox-${kode}" class="checkbox-kode mr-2 w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded" checked>
                            <label for="checkbox-${kode}" class="text-sm font-bold text-gray-900 dark:text-white">
                                ${nama} <span class="text-xs text-gray-500">(Stok: ${stok})</span>
                            </label>
                        </div>

                        <div class="mb-5 sm:block md:flex items-center">
                            <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Jumlah<span class="text-red-600">*</span></label>
                            <input type="number" name="jumlah[${kode}]" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" required>

                            <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Aturan Pakai<span class="text-red-600">*</span></label>
                            <input type="text" name="aturan_pakai[${kode}]" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
                        </div>

                        <input type="hidden" name="kode_barang[]" value="${kode}">
                        <input type="hidden" name="no_batch[${kode}]" value="${no_batch}">
                        <input type="hidden" name="no_faktur[${kode}]" value="${no_faktur}">
                    `;

                    container.appendChild(wrapper);

                    document.getElementById(`checkbox-${kode}`).addEventListener("change", function () {
                        if (!this.checked) {
                            document.getElementById(inputId).remove();
                        }
                    });
                })
                .catch(error => {
                    console.error(`Error fetching stok for ${kode}:`, error);
                });
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
