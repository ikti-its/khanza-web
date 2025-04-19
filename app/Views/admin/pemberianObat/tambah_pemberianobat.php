<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<!-- Card Section -->
<div class="max-w-[85rem] py-6 lg:py-3 px-8 mx-auto">
    <!-- Card -->
    <div class="bg-white rounded-xl shadow p-4 sm:p-7 dark:bg-slate-900">
        <div class="mb-8">
            <h2 class="text-xl font-bold text-gray-800 dark:text-gray-200">
                Tambah Pemberian Obat
            </h2>
        </div>
        <form action="/pemberianobat/submit" id="myForm" onsubmit="return validateForm()" method="post">
            <?= csrf_field() ?>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Nomor Rawat</label>
                <input type="text" name="nomor_rawat" value="<?= $pemberianobat['nomor_rawat'] ?? '' ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" required>

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Nama Pasien</label>
                <input name="nama_pasien" value="<?= $pemberianobat['nama_pasien'] ?? '' ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Kelas</label>
                <input type="text" name="kelas" value="<?= $pemberianobat['kelas'] ?? 'd' ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white">
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Obat</label>
                <select id="obatSelect" name="kode_obat" class="..." required>
                    <option disabled selected value="">Pilih Obat</option>
                    <?php foreach ($obat_data as $obat): ?>
                        <option 
                            value="<?= esc($obat['kode_obat']) ?>" 
                            data-nama="<?= esc($obat['nama_obat']) ?>"
                            data-tarif="<?= esc($obat['biaya_obat']) ?>"
                        >
                            <?= esc($obat['nama_obat']) ?> - Rp <?= number_format($obat['biaya_obat'], 0, ',', '.') ?>
                        </option>
                    <?php endforeach; ?>
                </select>


                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Jumlah</label>
                <input name="jumlah" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
            </div>

            <input type="hidden" name="nama_obat" id="namaObat">
            <input type="hidden" name="biaya_obat" id="biayaObat">

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 text-sm text-gray-900 dark:text-white md:w-1/4">Gudang</label>
                <input name="gudang" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">No Batch</label>
                <input name="no_batch" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 text-sm text-gray-900 dark:text-white md:w-1/4">No Faktur</label>
                <input name="no_faktur" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Tanggal Beri</label>
                <input name="tanggal_beri" value="<?= date('Y-m-d') ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 text-sm text-gray-900 dark:text-white md:w-1/4">Jam Beri</label>
                <input name="jam_beri" value="<?= date('H:i:s') ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Total</label>
                <input name="total" id="totalObat" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" readonly>
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
