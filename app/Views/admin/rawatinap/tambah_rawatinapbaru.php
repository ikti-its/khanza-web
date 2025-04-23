<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<!-- Card Section -->
<div class="max-w-[85rem] py-6 lg:py-3 px-8 mx-auto">
    <!-- Card -->
    <div class="bg-white rounded-xl shadow p-4 sm:p-7 dark:bg-slate-900">
        <div class="mb-8">
            <h2 class="text-xl font-bold text-gray-800 dark:text-gray-200">
                Tambah Pasien Rawat Inap 
            </h2>
        </div>
        <form action="/rawatinap/submittambah/" id="myForm" onsubmit="return validateForm()" method="post">
            <?= csrf_field() ?>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Nomor Rekam Medis</label>
                <input type="text" name="nomor_rm" value="<?= $registrasi['nomor_rm'] ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" maxlength="80" required>
                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Nomor Rawat</label>
                <input name="nomor_rawat" value="<?php function generateUniqueNumber($length = 15)
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
                                                        echo $nomor; ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white">
            </div>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Nama Pasien</label>
                <input type="text" name="nama_pasien" value="<?= $registrasi['nama_pasien'] ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" maxlength="80" required>
                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Alamat Pasien</label>
                <input name="alamat_pasien" value="<?= $registrasi['alamat_pj'] ?? '' ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white">
            </div>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Penanggung Jawab</label>
                <input type="text" name="penanggung_jawab" value="<?= $registrasi['penanggung_jawab'] ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" maxlength="80" required>
                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Hubungan Penanggung Jawab</label>
                <input name="hubungan_pj" value="<?= $registrasi['hubungan_pj'] ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white">
            </div>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Jenis Bayar</label>
                <input type="text" name="jenis_bayar" value="<?= $registrasi['jenis_bayar'] ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" maxlength="80" required>
            </div>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Kamar</label>
                <select name="kamar" id="kamarSelect" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" required>
                    <option value="">Pilih Kamar</option>
                </select>

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Tarif Kamar</label>
                <input name="tarif_kamar" id="tarifKamarInput" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" readonly>
            </div>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Diagnosa Awal</label>
                <input type="text" name="diagnosa_awal" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" maxlength="80" required>
                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Diagnosa Akhir</label>
                <input name="diagnosa_akhir" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white">
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white w-1/5 lg:w-1/4">Tanggal Masuk</label>
                <input type="text" name="tanggal_masuk" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" maxlength="80" required>
                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Tanggal Keluar</label>
                <input type="text" name="tanggal_keluar" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" maxlength="80">
            </div>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white w-1/5 lg:w-1/4">Status Pulang</label>
                <select name="status_pulang" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
                    <option value="Belum">Belum</option>
                    <option value="Sudah">Sudah</option>
                </select>
                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Jam Keluar</label>
                <input type="text" name="jam_keluar" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" maxlength="80">
            </div>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white w-1/5 lg:w-1/4">Lama Rawat Inap</label>
                <input type="text" name="lama_ranap" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" maxlength="80">
                    <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Dokter Penanggung Jawab</label>
                    <select name="dokter_pj" id="dokterSelect" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" required>
                        <option value="">Pilih Dokter</option>
                    </select>

            </div>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white w-1/5 lg:w-1/4">Total Biaya</label>
                <input type="text" name="total_biaya" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" maxlength="80">
                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Status Bayar</label>
                <select name="status_pulang" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white">
                    <option value="Belum">Belum</option>
                    <option value="Sudah">Sudah</option>
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
        const dokterSelect = document.getElementById("dokterSelect");

        fetch("http://127.0.0.1:8080/v1/dokter") // ✅ Replace with your actual dokter endpoint
            .then(response => response.json())
            .then(data => {
                if (data.status === "success" && Array.isArray(data.data)) {
                    data.data.forEach(dokter => {
                        const option = document.createElement("option");
                        option.value = dokter.nama_dokter; // or dokter.nama_dokter if preferred
                        option.text = `${dokter.nama_dokter} (${dokter.kode_dokter})`;
                        dokterSelect.appendChild(option);
                    });
                } else {
                    console.error("Dokter response format error:", data);
                }
            })
            .catch(error => {
                console.error("Error fetching dokter list:", error);
            });
    });

    document.addEventListener("DOMContentLoaded", function () {
        const kamarSelect = document.getElementById("kamarSelect");
        const tarifInput = document.getElementById("tarifKamarInput");

        let kamarDataMap = {}; // Store kamar data for fast lookup by nomor_bed

        fetch("http://127.0.0.1:8080/v1/kamar/available")
            .then(response => response.json())
            .then(data => {
                if (data.data && Array.isArray(data.data)) {
                    data.data.forEach(kamar => {
                        // Store data in a map
                        kamarDataMap[kamar.nomor_bed] = kamar;

                        // Create and append option
                        const option = document.createElement("option");
                        option.value = kamar.nomor_bed;
                        option.text = `${kamar.nama_kamar} (${kamar.kelas}) - ${kamar.nomor_bed}`;
                        kamarSelect.appendChild(option);
                    });
                }
            })
            .catch(error => {
                console.error("Error fetching kamar list:", error);
            });

        // When a kamar is selected, update the tarif input
        kamarSelect.addEventListener("change", function () {
            const selectedBed = this.value;
            const kamar = kamarDataMap[selectedBed];

            if (kamar) {
                tarifInput.value = kamar.tarif_kamar.toLocaleString(); // Format as number
            } else {
                tarifInput.value = "";
            }
        });
    });

    document.addEventListener("DOMContentLoaded", function () {
        fetch("http://127.0.0.1:8080/v1/kamar/available")
            .then(response => response.json())
            .then(data => {
                const kamarSelect = document.getElementById("kamarSelect");
                if (data.data && Array.isArray(data.data)) {
                    data.data.forEach(kamar => {
                        const option = document.createElement("option");
                        option.value = kamar.nomor_bed; // Used as value to send
                        option.text = `${kamar.nama_kamar} (${kamar.kelas}) - ${kamar.nomor_bed}`;
                        kamarSelect.appendChild(option);
                    });
                } else {
                    console.error("Data not in expected format", data);
                }
            })
            .catch(error => {
                console.error("Error fetching kamar list:", error);
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