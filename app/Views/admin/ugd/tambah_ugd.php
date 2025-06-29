@ -1,278 +1,278 @@
<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<!-- Card Section -->
<div class="max-w-[85rem] py-6 lg:py-3 px-8 mx-auto">
    <!-- Card -->
    <div class="bg-white rounded-xl shadow p-4 sm:p-7 dark:bg-slate-900">
        <?= view('components/form/judul', [
            'judul' => 'Tambah Pasien UGD'
        ]) ?>
        <form action="/ugd/submittambah/" id="myForm" onsubmit="return validateForm()" method="post">
            <?= csrf_field() ?>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Nomor Registrasi</label>
                <input type="text" name="nomor_reg" value="<?php function generateUniqueNumber($length = 15)
                                                        {
                                                            $characters = '1234567890';
                                                            $charactersLength = strlen($characters);
                                                            $randomString = '';

                                                            $uniqueLength = $length - 11;

                                                            if ($uniqueLength > 0) {
                                                                for ($i = 0; $i < $uniqueLength; $i++) {
                                                                    $randomString .= $characters[rand(0, $charactersLength - 1)];
                                                                }
                                                            } else {
                                                                return "Panjang maksimal terlalu pendek.";
                                                            }

                                                            return $randomString;
                                                        }

                                                        $tanggalHariIni = date('Ymd');

                                                        $nomor = "UGD" . $tanggalHariIni . generateUniqueNumber();
                                                        echo $nomor; ?>"class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" maxlength="80" required>
                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Nomor Rawat</label>
                <input name="nomor_rawat" value="<?php function generateUniqueNumber2($length = 15)
                                                        {
                                                            $characters = '1234567890';
                                                            $charactersLength = strlen($characters);
                                                            $randomString = '';

                                                            $uniqueLength = $length - 11;

                                                            if ($uniqueLength > 0) {
                                                                for ($i = 0; $i < $uniqueLength; $i++) {
                                                                    $randomString .= $characters[rand(0, $charactersLength - 1)];
                                                                }
                                                            } else {
                                                                return "Panjang maksimal terlalu pendek.";
                                                            }

                                                            return $randomString;
                                                        }

                                                        $tanggalHariIni = date('Ymd');

                                                        $nomor = "" . $tanggalHariIni . generateUniqueNumber();
                                                        echo $nomor; ?>"class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white">
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white w-1/5 lg:w-1/4">Tanggal</label>
                <input type="text" name="tanggal" value="<?php 

                                                        $tanggalHariIni = date('Y-m-d');

                                                        echo $tanggalHariIni; ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" maxlength="80" required>
                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Jam</label>
                <input type="text" name="jam" value="<?php 

                    $waktuHariIni = date('H:i:s');

                    echo $waktuHariIni; 
                    ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" maxlength="80" required>
            </div>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white w-1/5 lg:w-1/4">Nomor Rekam Medis<span class="text-red-600">*</span></label>
                <input type="text" name="nomor_rekam_medis" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white">
                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Nama<span class="text-red-600">*</span></label>
                <input name="nama" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white">
                </select>
            </div>

            <div class="mb-5 sm:block md:flex items-center">
            <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Jenis Kelamin<span class="text-red-600">*</span></label>
                <select name="jenis_kelamin" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
                    <option value="L">Laki-laki</option>
                    <option value="P">Perempuan</option>
                </select>
                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Umur<span class="text-red-600">*</span></label>
                <input name="umur" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white">
            </div>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Poliklinik<span class="text-red-600">*</span></label>
                <select name="poliklinik" id="poliklinikSelect" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
                    <option disabled selected value="">Pilih Poliklinik</option>
                </select>

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Dokter<span class="text-red-600">*</span></label>
                <select name="kode_dokter" id="dokterSelect" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
                    <option disabled selected value="">Pilih Dokter</option>
                </select>
            </div>

            <div class="mb-5 sm:block md:flex items-center">
            <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white w-1/5 lg:w-1/4">Penanggung Jawab<span class="text-red-600">*</span></label>
            <input type="text" name="penanggung_jawab" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white">
                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Hubungan Penanggung Jawab<span class="text-red-600">*</span></label>
                <select name="hubungan_penanggung_jawab" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
                    <option value="Diri Sendiri">Diri Sendiri</option>
                    <option value="Ayah">Ayah</option>
                    <option value="Ibu">Ibu</option>
                    <option value="Saudara">Saudara</option>
                    <option value="Teman">Teman</option>
                    <option value="Lainnya">Lainnya</option>
                </select>
            </div>

            <div class="mb-5 sm:block md:flex items-center">
            <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white w-1/5 lg:w-1/4">Alamat Penanggung Jawab<span class="text-red-600">*</span></label>
            <input type="text" name="alamat_penanggung_jawab" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white">
                
            </div>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Biaya Registrasi<span class="text-red-600">*</span></label>
                <input type="number" name="biaya_registrasi" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" placeholder="0">
                
            </div>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Status Rawat<span class="text-red-600">*</span></label>
                <select name="status_rawat" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
                    <option value="Belum">Belum</option>
                    <option value="Sudah">Sudah</option>
                </select>
                
            </div>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Jenis Bayar<span class="text-red-600">*</span></label>
                <select name="jenis_bayar" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
                    <option value="BPJS">BPJS</option>
                    <option value="non-BPJS">non-BPJS</option>
                </select>
                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Status Bayar<span class="text-red-600">*</span></label>
                <select name="status_bayar" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
                    <option value="Belum Bayar">Belum Bayar</option>
                    <option value="Sudah Bayar">Sudah Bayar</option>
                </select>
            </div>
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
document.addEventListener("DOMContentLoaded", function () {
    const poliklinikSelect = document.getElementById("poliklinikSelect");
    const dokterSelect = document.getElementById("dokterSelect");

    const token = sessionStorage.getItem("token");
    if (!token) {
        alert("Session expired. Silakan login ulang.");
        window.location.href = "/login";
        return;
    }

    // Populate poliklinik list
    fetch("http://127.0.0.1:8080/v1/dokterjaga/poliklinik-list", {
        headers: {
            "Authorization": `Bearer ${token}`,
            "Content-Type": "application/json"
        }
    })
    .then(res => res.json())
    .then(res => {
        if (res.status === "success") {
            res.data.forEach(poli => {
                const opt = document.createElement("option");
                opt.value = poli;
                opt.textContent = poli;
                poliklinikSelect.appendChild(opt);
            });
        }
    });

    // On poliklinik change
    poliklinikSelect.addEventListener("change", function () {
        const selectedPoli = this.value;
        dokterSelect.innerHTML = '<option disabled selected value="">Pilih Dokter</option>';

        fetch(`http://127.0.0.1:8080/v1/dokterjaga/poliklinik/${encodeURIComponent(selectedPoli)}`, {
            headers: {
                "Authorization": `Bearer ${token}`,
                "Content-Type": "application/json"
            }
        })
        .then(res => res.json())
        .then(res => {
            if (res.status === "success" && Array.isArray(res.data)) {
                const now = new Date();
                const currentDay = normalizeDay(now.toLocaleDateString("id-ID", { weekday: "long" }));

                let dokterAktifCount = 0;

                res.data.forEach(dokter => {
                    const hariKerja = normalizeDay(dokter.hari_kerja);
                    const jamMulai = padTime(dokter.jam_mulai);
                    const jamSelesai = padTime(dokter.jam_selesai);

                    const isActive = (hariKerja === currentDay) && isDoctorOnShiftTime(jamMulai, jamSelesai);

                    // Debug output
                    console.log("🩺 Dokter:", dokter.nama_dokter);
                    console.log("📅 Hari kerja:", hariKerja, "| Sekarang:", currentDay);
                    console.log("⏰ Shift:", jamMulai, "-", jamSelesai);
                    console.log("✅ Aktif sekarang:", isActive);

                    if (isActive) {
                        const opt = document.createElement("option");
                        opt.value = dokter.kode_dokter;
                        opt.textContent = dokter.nama_dokter;
                        dokterSelect.appendChild(opt);
                        dokterAktifCount++;
                    }
                });

                if (dokterAktifCount === 0) {
                    dokterSelect.innerHTML = '<option disabled selected value="">Tidak ada dokter aktif saat ini</option>';
                }
            } else {
                console.warn("⚠️ Format data tidak valid:", res);
            }
        })
        .catch(err => {
            console.error("❌ Gagal mengambil data dokter:", err);
        });
    });



    // Normalize hari (e.g., "Selasa ") → "selasa"
    function normalizeDay(day) {
        return day.trim().toLowerCase().replace(/[^a-z]/g, "");
    }

    // Pad time (e.g., "8:0:0" → "08:00:00")
    function padTime(timeStr) {
        const [h = "0", m = "0", s = "0"] = timeStr.split(":");
        return `${h.padStart(2, "0")}:${m.padStart(2, "0")}:${s.padStart(2, "0")}`;
    }

    // Compare current time with jam_mulai and jam_selesai (supports overnight shifts)
    function isDoctorOnShiftTime(startTime, endTime) {
        const now = new Date();
        const nowSec = now.getHours() * 3600 + now.getMinutes() * 60 + now.getSeconds();

        const [startH, startM, startS] = startTime.split(":").map(Number);
        const [endH, endM, endS] = endTime.split(":").map(Number);

        const startSec = startH * 3600 + startM * 60 + startS;
        const endSec = endH * 3600 + endM * 60 + endS;

        return startSec < endSec
            ? nowSec >= startSec && nowSec <= endSec
            : nowSec >= startSec || nowSec <= endSec;
    }
});

// Form validation logic
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