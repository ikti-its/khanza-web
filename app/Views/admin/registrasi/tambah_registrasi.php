<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<!-- Card Section -->
<div class="max-w-[85rem] py-6 lg:py-3 px-8 mx-auto">
    <!-- Card -->
    <div class="bg-white rounded-xl shadow p-4 sm:p-7 dark:bg-slate-900">
        <?= view('components/form/judul', [
            'judul' => 'Tambah Registrasi Pasien'
        ]) ?>
        <form action="/registrasi/submittambah/" id="myForm" onsubmit="return validateForm()" method="post">
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

                                                            $nomor = "REG" . $tanggalHariIni . generateUniqueNumber();
                                                            echo $nomor; ?>" class="bg-gray-200 border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" maxlength="80" required readonly>
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
                                                    echo $nomor; ?>" class="bg-gray-200 border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required readonly>
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white w-1/5 lg:w-1/4">Tanggal</label>
                <input type="date" name="tanggal" value="<?php

                                                            $tanggalHariIni = date('Y-m-d');

                                                            echo $tanggalHariIni; ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" maxlength="80" required>
                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Jam</label>
                <input type="time" name="jam" value="<?php

                                                        $waktuHariIni = date('H:i:s');

                                                        echo $waktuHariIni;
                                                        ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" maxlength="80" required>
            </div>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white w-1/5 lg:w-1/4">
                    Nomor Rekam Medis<span class="text-red-600">*</span>
                </label>
                <div class="relative w-full md:w-1/4">
                    <input type="text" id="no_rkm_medis" name="no_rkm_medis"
                        class="block w-full p-2 text-sm text-gray-900 border border-gray-300 rounded-lg pr-10 dark:border-gray-600 dark:text-white"
                        placeholder="Nomor RM">
                    <a href="/pasien/tambah"
                        class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-blue-600"
                        title="Tambah Pasien">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M18 13v6a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2h6m5-3h5m0 0v5m0-5L10 14" />
                        </svg>
                    </a>

                </div>

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">
                    Nama<span class="text-red-600">*</span>
                </label>
                <input id="nama_pasien" name="nama"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white">
            </div>


            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Jenis Kelamin<span class="text-red-600">*</span></label>
                <select id="jenis_kelamin" name="jenis_kelamin" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
                    <option value="">-- Pilih --</option>
                    <option value="L">Laki-laki</option>
                    <option value="P">Perempuan</option>
                </select>

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Umur<span class="text-red-600">*</span></label>
                <input id="umur" name="umur" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white">
            </div>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">
                    Poliklinik<span class="text-red-600">*</span>
                </label>
                <select name="poliklinik" id="poliklinikSelect"
                    class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white"
                    required>
                    <option value="">Memuat...</option>
                </select>
                <label for="dokter" class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Dokter<span class="text-red-600">*</span></label>
                <select name="kode_dokter" id="dokterSelect" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
                    <option value="">-- Pilih Dokter --</option>
                </select>
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white w-1/5 lg:w-1/4">Penanggung Jawab</label>
                <input type="text" id="penanggung_jawab" name="penanggung_jawab" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white">
                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Hubungan Penanggung Jawab</label>
                <select name="hubungan_penanggung_jawab" id="hubungan_pj" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
                    <option value="DIRI SENDIRI">DIRI SENDIRI</option>
                    <option value="AYAH">AYAH</option>
                    <option value="IBU">IBU</option>
                    <option value="ISTRI">ISTRI</option>
                    <option value="SUAMI">SUAMI</option>
                    <option value="ANAK">ANAK</option>
                    <option value="SAUDARA">SAUDARA</option>
                    <option value="LAIN-LAIN">LAIN-LAIN</option>
                </select>
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white w-1/5 lg:w-1/4">Alamat Penanggung Jawab</label>
                <input type="text" id="alamat_pj" name="alamat_penanggung_jawab" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white">
                <label class="block w-full mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white md:w-1/5">Nomor Telepon<span class="text-red-600">*</span></label>
                <input name="nomor_telepon" id="no_telp" class=" border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white">
            </div>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Biaya Registrasi<span class="text-red-600">*</span></label>
                <input type="number" name="biaya_registrasi" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" placeholder="0" required>
                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Status Registrasi<span class="text-red-600">*</span></label>
                <select name="status_registrasi" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
                    <option value="Baru">Baru</option>
                    <option value="Lama">Lama</option>
                </select>
            </div>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Status Rawat<span class="text-red-600">*</span></label>
                <select name="status_rawat" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
                    <option value="Belum">Belum</option>
                    <option value="Sudah">Sudah</option>
                </select>
                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Status Poliklinik<span class="text-red-600">*</span></label>
                <select name="status_poliklinik" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
                    <option value="Baru">Baru</option>
                    <option value="Lama">Lama</option>
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
            <?= view('components/form/submit_button') ?>
        </form>
    </div>
    <!-- End Card -->
</div>
<!-- End Card Section -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
    const poliklinikSelect = document.getElementById("poliklinikSelect");
    const dokterSelect = document.getElementById("dokterSelect");

    const token = sessionStorage.getItem("token"); // Atau localStorage jika Anda menyimpan di sana

    fetch("http://127.0.0.1:8080/v1/dokterjaga/poliklinik-list", {
        headers: {
            "Authorization": `Bearer ${token}`,
            "Content-Type": "application/json"
        }
    })
    .then(res => {
        if (!res.ok) {
            throw new Error("Gagal fetch poliklinik: " + res.status);
        }
        return res.json();
    })
    .then(res => {
        if (res.status === "success") {
            res.data.forEach(poli => {
                const opt = document.createElement("option");
                opt.value = poli;
                opt.textContent = poli;
                poliklinikSelect.appendChild(opt);
            });
        }
    })
    .catch(err => {
        console.error("❌ Error:", err.message);
    });

        // On poliklinik change
        poliklinikSelect.addEventListener("change", function() {
            const selectedPoli = this.value;
            dokterSelect.innerHTML = '<option disabled selected value="">Pilih Dokter</option>';
            const token = sessionStorage.getItem("token");

            fetch(`http://127.0.0.1:8080/v1/dokterjaga/poliklinik/${encodeURIComponent(selectedPoli)}`, {
                headers: {
                    "Authorization": `Bearer ${token}`,
                    "Content-Type": "application/json"
                }
            })
            .then(res => {
                if (!res.ok) throw new Error(`Gagal fetch dokter: ${res.status}`);
                return res.json();
            })
            .then(res => {
                if (res.status === "success" && Array.isArray(res.data)) {
                    const now = new Date();
                    const currentDay = normalizeDay(now.toLocaleDateString("id-ID", {
                        weekday: "long"
                    }));

                    let dokterAktifCount = 0;

                    res.data.forEach(dokter => {
                        const hariKerja = normalizeDay(dokter.hari_kerja);
                        const jamMulai = padTime(dokter.jam_mulai);
                        const jamSelesai = padTime(dokter.jam_selesai);
                        const isActive = (hariKerja === currentDay) && isDoctorOnShiftTime(jamMulai, jamSelesai);

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

            return startSec < endSec ?
                nowSec >= startSec && nowSec <= endSec :
                nowSec >= startSec || nowSec <= endSec;
        }
    })});

    document.addEventListener("DOMContentLoaded", async function() {
        const select = document.querySelector("select[name='poliklinik']");

        try {
            const response = await fetch("http://127.0.0.1:8080/v1/dokterjaga/poliklinik-list");
            const result = await response.json();

            select.innerHTML = '<option value="">Pilih Poliklinik</option>';

            if (Array.isArray(result.data)) {
                result.data.forEach(poli => {
                    const option = document.createElement("option");
                    option.value = poli;
                    option.textContent = poli;
                    select.appendChild(option);
                });
            }
        } catch (error) {
            console.error("Error fetching poliklinik list:", error);
            select.innerHTML = '<option value="">Gagal memuat data</option>';
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        const nomorRMInput = document.querySelector('input[name="no_rkm_medis"]');
        const token = "<?= session()->get('jwt_token') ?>"; // ensure this prints a token

        // ✅ Trigger API when input loses focus
        nomorRMInput.addEventListener('blur', function() {
            const nomorRM = nomorRMInput.value.trim();
            if (!nomorRM) {
                console.log("❌ No nomor RM entered.");
                return;
            }

            console.log("📡 Fetching data for RM:", nomorRM);

            fetch(`http://127.0.0.1:8080/v1/pasien/${encodeURIComponent(nomorRM)}`, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': 'Bearer ' + token
                    }
                })
                .then(response => {
                    console.log("🔁 HTTP status:", response.status);
                    return response.json();
                })
                .then(data => {
                    console.log("✅ API response:", data);
                    if (data.status === 'success' && data.data) {
                        const pasien = data.data;

                        document.getElementById('nama_pasien').value = pasien.nm_pasien || '';
                        document.getElementById('jenis_kelamin').value = pasien.jk || '';
                        document.getElementById('umur').value = pasien.umur || '';
                        document.getElementById('penanggung_jawab').value = pasien.namakeluarga || '';
                        document.getElementById('alamat_pj').value = pasien.alamatpj || '';
                        document.getElementById('no_telp').value = pasien.no_tlp || '';
                        document.getElementById('hubungan_pj').value = pasien.keluarga || '';

                        const statusSelect = document.querySelector('select[name="status_registrasi"]');
                        if (statusSelect) {
                            statusSelect.value = 'Lama';
                        }

                    } else {
                        console.warn("⚠️ Data pasien tidak ditemukan atau format salah.");
                    }
                })
                .catch(error => {
                    console.error("❌ Error calling API:", error);
                });
        });

        // ✅ Optional: auto-trigger if input already has value
        if (nomorRMInput.value.trim()) {
            nomorRMInput.dispatchEvent(new Event('blur'));
        }
    });

    fetch("http://127.0.0.1:8080/v1/registrasi/dokter")
        .then(res => res.json())
        .then(data => {
            const select = document.getElementById("dokter");

            data.data.forEach(dokter => {
                const option = document.createElement("option");
                option.value = dokter.kode_dokter; // value = kode
                option.textContent = dokter.nama_dokter; // shown = name
                select.appendChild(option);
            });
        });

    document.addEventListener('DOMContentLoaded', () => {
        fetch('http://127.0.0.1:8080/v1/registrasi/dokter')
            .then(res => res.json())
            .then(data => {
                const select = document.getElementById("dokter");

                if (!data.data || data.data.length === 0) return;

                data.data.forEach(dokter => {
                    const option = document.createElement("option");
                    option.value = dokter.kode_dokter;
                    option.textContent = dokter.nama_dokter;
                    select.appendChild(option);
                });
            })
            .catch(err => {
                console.error("❌ Failed to load dokter list:", err);
            });
    });
    const inputHargaBeli = document.querySelector('input[name="hargabeli"]');

    // Ambil semua input yang perlu diisi dan diubah
    const inputHargaRalan = document.querySelector('input[name="hargaralan"]');
    const inputHargaKelas1 = document.querySelector('input[name="hargakelas1"]');
    const inputHargaKelas2 = document.querySelector('input[name="hargakelas2"]');
    const inputHargaKelas3 = document.querySelector('input[name="hargakelas3"]');
    const inputHargaUtama = document.querySelector('input[name="hargautama"]');
    const inputHargaVIP = document.querySelector('input[name="hargavip"]');
    const inputHargaVVIP = document.querySelector('input[name="hargavvip"]');
    const inputHargaApotekLuar = document.querySelector('input[name="hargaapotekluar"]');
    const inputHargaObatBebas = document.querySelector('input[name="hargaobatbebas"]');
    const inputHargaKaryawan = document.querySelector('input[name="hargakaryawan"]');

    // Tambahkan event listener untuk input harga beli
    inputHargaBeli.addEventListener('input', function() {
        // Ambil nilai harga beli
        const hargaBeli = parseFloat(inputHargaBeli.value);

        // Pastikan nilai harga beli valid (numerik dan tidak NaN)
        if (!isNaN(hargaBeli)) {
            // Hitung harga ralan dan setiap harga rnp dengan tambahan 15%
            const hargaRalan = hargaBeli * 1.15;
            const hargaKelas1 = hargaBeli * 1.15;
            const hargaKelas2 = hargaBeli * 1.15;
            const hargaKelas3 = hargaBeli * 1.15;
            const hargaUtama = hargaBeli * 1.15;
            const hargaVIP = hargaBeli * 1.15;
            const hargaVVIP = hargaBeli * 1.15;
            const hargaApotekLuar = hargaBeli * 1.15;
            const hargaObatBebas = hargaBeli * 1.15;
            const hargaKaryawan = hargaBeli * 1.15;

            // Masukkan nilai yang dihitung ke dalam masing-masing input
            inputHargaRalan.value = hargaRalan.toFixed(0);
            inputHargaKelas1.value = hargaKelas1.toFixed(0);
            inputHargaKelas2.value = hargaKelas2.toFixed(0);
            inputHargaKelas3.value = hargaKelas3.toFixed(0);
            inputHargaUtama.value = hargaUtama.toFixed(0);
            inputHargaVIP.value = hargaVIP.toFixed(0);
            inputHargaVVIP.value = hargaVVIP.toFixed(0);
            inputHargaApotekLuar.value = hargaApotekLuar.toFixed(0);
            inputHargaObatBebas.value = hargaObatBebas.toFixed(0);
            inputHargaKaryawan.value = hargaKaryawan.toFixed(0);
        } else {
            // Jika harga beli tidak valid, atur nilai input lainnya menjadi kosong
            inputHargaRalan.value = '';
            inputHargaKelas1.value = '';
            inputHargaKelas2.value = '';
            inputHargaKelas3.value = '';
            inputHargaUtama.value = '';
            inputHargaVIP.value = '';
            inputHargaVVIP.value = '';
            inputHargaApotekLuar.value = '';
            inputHargaObatBebas.value = '';
            inputHargaKaryawan.value = '';
        }
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