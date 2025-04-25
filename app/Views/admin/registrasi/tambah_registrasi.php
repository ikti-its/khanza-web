<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<!-- Card Section -->
<div class="max-w-[85rem] py-6 lg:py-3 px-8 mx-auto">
    <!-- Card -->
    <div class="bg-white rounded-xl shadow p-4 sm:p-7 dark:bg-slate-900">
        <div class="mb-8">
            <h2 class="text-xl font-bold text-gray-800 dark:text-gray-200">
                Tambah Registrasi Pasien 
            </h2>
        </div>
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
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white w-1/5 lg:w-1/4">Nomor Rekam Medis</label>
                <input type="text" id="nomor_rm" name="nomor_rekam_medis" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" maxlength="3">

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Nama</label>
                <input id="nama_pasien" name="nama" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white">
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Jenis Kelamin</label>
                <select id="jenis_kelamin" name="jenis_kelamin" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
                    <option value="">-- Pilih --</option>
                    <option value="L">Laki-laki</option>
                    <option value="P">Perempuan</option>
                </select>

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Umur</label>
                <input id="umur" name="umur" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white">
            </div>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Poliklinik</label>
                <select name="poliklinik" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
                    <option value="Poli Umum">Poli Umum</option>
                </select>
                <label for="dokter" class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Dokter</label>
                <select name="kode_dokter" id="dokter" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
                    <option value="">-- Pilih Dokter --</option>
                </select>
            </div>

            <div class="mb-5 sm:block md:flex items-center">
            <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white w-1/5 lg:w-1/4">Penanggung Jawab</label>
            <input type="text" name="penanggung_jawab" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white">
                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Hubungan Penanggung Jawab</label>
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
            <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white w-1/5 lg:w-1/4">Alamat Penanggung Jawab</label>
            <input type="text" name="alamat_penanggung_jawab" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white">
                <label class="block w-full mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white md:w-1/5">Nomor Telepon</label>
                <input name="nomor_telepon" class=" border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white">
            </div>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Biaya Registrasi</label>
                <input type="number" name="biaya_registrasi" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" placeholder="0">
                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Status Registrasi</label>
                <select name="status_registrasi" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
                    <option value="Baru">Baru</option>
                    <option value="Lama">Lama</option>
                </select>
            </div>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Status Rawat</label>
                <select name="status_rawat" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
                    <option value="Belum">Belum</option>
                    <option value="Sudah">Sudah</option>
                </select>
                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Status Poliklinik</label>
                <select name="status_poliklinik" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
                    <option value="Baru">Baru</option>
                    <option value="Lama">Lama</option>
                </select>
            </div>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Jenis Bayar</label>
                <select name="jenis_bayar" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" required>
                    <option value="BPJS">BPJS</option>
                    <option value="non-BPJS">non-BPJS</option>
                </select>
                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Status Bayar</label>
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

document.getElementById('nomor_rm').addEventListener('blur', function () {
    const nomorRM = this.value;
    if (!nomorRM) return;

    fetch(`http://localhost:8080/v1/registrasi/by-nomor-rm/${nomorRM}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success' && data.data) {
                document.getElementById('nama_pasien').value = data.data.nama_pasien || '';
                document.getElementById('jenis_kelamin').value = data.data.jenis_kelamin || '';
                document.getElementById('umur').value = data.data.umur || '';
            } else {
                alert('Pasien tidak ditemukan');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Gagal mengambil data pasien');
        });
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