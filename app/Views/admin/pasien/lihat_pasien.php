<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<!-- Card Section -->
<div class="max-w-[85rem] py-6 lg:py-3 px-8 mx-auto">
    <!-- Card -->
    <div class="bg-white rounded-xl shadow p-4 sm:p-7 dark:bg-slate-900">
        <?= view('components/form/judul', [
            'judul' => 'Identitas Pasien'
        ]) ?>
        <form action="/registrasi/submittambah/" id="myForm" onsubmit="return validateForm()" method="post">
            <?= csrf_field() ?>

            <div class="mb-5 sm:block md:flex items-center"> 
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white w-1/5 lg:w-1/4">Nomor Rekam Medis</label>
                <input type="text" id="nomor_rm" name="nomor_rekam_medis" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" readonly value="<?= esc($pasien['no_rkm_medis'] ?? '') ?>">

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Nama</label>
                <input id="nama_pasien" name="nama" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" readonly value="<?= esc($pasien['nm_pasien'] ?? '') ?>">
            </div>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Jenis Kelamin</label>
                <input id="jenis_kelamin" name="jenis_kelamin" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" readonly value="<?= esc($pasien['jk'] ?? '') ?>">


                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Golongan Darah</label>
                <input id="umur" name="umur" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" readonly value="<?= esc($pasien['gol_darah'] ?? '') ?>">
            </div>
                        <div class="mb-5 sm:block md:flex items-center"> 
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white w-1/5 lg:w-1/4">Tempat Lahir</label>
                <input type="text" id="nomor_rm" name="nomor_rekam_medis" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" readonly value="<?= esc($pasien['tmp_lahir'] ?? '') ?>">

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Tanggal Lahir</label>
                <input id="nama_pasien" name="nama" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" readonly value="<?= esc($pasien['tgl_lahir'] ?? '') ?>">
            </div>
                        <div class="mb-5 sm:block md:flex items-center"> 
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white w-1/5 lg:w-1/4">Umur</label>
                <input type="text" id="nomor_rm" name="nomor_rekam_medis" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" readonly value="<?= esc($pasien['umur'] ?? '') ?>">

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Pendidikan</label>
                <input id="nama_pasien" name="nama" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" readonly value="<?= esc($pasien['pnd'] ?? '') ?>">
            </div>
                        <div class="mb-5 sm:block md:flex items-center"> 
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white w-1/5 lg:w-1/4">Nama Ibu</label>
                <input type="text" id="nomor_rm" name="nomor_rekam_medis" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" readonly value="<?= esc($pasien['nm_ibu'] ?? '') ?>">

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Penanggung Jawab</label>
                <input id="nama_pasien" name="nama" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" readonly value="<?= esc($pasien['keluarga'] ?? '') ?>">
            </div>
                        <div class="mb-5 sm:block md:flex items-center"> 
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white w-1/5 lg:w-1/4">Nama Penanggung Jawab</label>
                <input type="text" id="nomor_rm" name="nomor_rekam_medis" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" readonly value="<?= esc($pasien['namakeluarga'] ?? '') ?>">

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Pekerjaan Penanggung Jawab</label>
                <input id="nama_pasien" name="nama" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" readonly value="<?= esc($pasien['pekerjaanpj'] ?? '') ?>">
            </div>
                        <div class="mb-5 sm:block md:flex items-center"> 
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white w-1/5 lg:w-1/4">Suku/Bangsa</label>
                <input type="text" id="nomor_rm" name="nomor_rekam_medis" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" readonly value="<?= esc($pasien['suku_bangsa'] ?? '') ?>">

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Bahasa</label>
                <input id="nama_pasien" name="nama" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" readonly value="<?= esc($pasien['bahasa_pasien'] ?? '') ?>">
            </div>
                        <div class="mb-5 sm:block md:flex items-center"> 
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white w-1/5 lg:w-1/4">Cacat Fisik</label>
                <input type="text" id="nomor_rm" name="nomor_rekam_medis" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" readonly value="<?= esc($pasien['cacat_fisik'] ?? '') ?>">
            </div>
                        <div class="mb-5 sm:block md:flex items-center"> 
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white w-1/5 lg:w-1/4">Agama</label>
                <input type="text" id="nomor_rm" name="nomor_rekam_medis" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" readonly value="<?= esc($pasien['agama'] ?? '') ?>">

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Status Pernikahan</label>
                <input id="nama_pasien" name="nama" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" readonly value="<?= esc($pasien['stts_nikah'] ?? '') ?>">
            </div>
                        <div class="mb-5 sm:block md:flex items-center"> 
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white w-1/5 lg:w-1/4">Asuransi</label>
                <input type="text" id="nomor_rm" name="nomor_rekam_medis" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" readonly value="<?= esc($pasien['kd_pj'] ?? '') ?>">

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Nomor Asuransi</label>
                <input id="nama_pasien" name="nama" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" readonly value="<?= esc($pasien['kd_pj'] ?? '') ?>">
            </div>
                        <div class="mb-5 sm:block md:flex items-center"> 
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white w-1/5 lg:w-1/4">No. Peserta</label>
                <input type="text" id="nomor_rm" name="nomor_rekam_medis" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" readonly value="<?= esc($pasien['no_peserta'] ?? '') ?>">

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Email</label>
                <input id="nama_pasien" name="nama" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" readonly value="<?= esc($pasien['email'] ?? '') ?>">
            </div>
                        <div class="mb-5 sm:block md:flex items-center"> 
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white w-1/5 lg:w-1/4">No. Telepon</label>
                <input type="text" id="nomor_rm" name="nomor_rekam_medis" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" readonly value="<?= esc($pasien['no_tlp'] ?? '') ?>">

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Pertama Daftar</label>
                <input id="nama_pasien" name="nama" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" readonly value="<?= esc($pasien['tgl_daftar'] ?? '') ?>">
            </div>
                        <div class="mb-5 sm:block md:flex items-center"> 
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white w-1/5 lg:w-1/4">Pekerjaan</label>
                <input type="text" id="nomor_rm" name="nomor_rekam_medis" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" readonly value="<?= esc($pasien['pekerjaan'] ?? '') ?>">

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">No. KTP/SIM</label>
                <input id="nama_pasien" name="nama" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" readonly value="<?= esc($pasien['no_ktp'] ?? '') ?>">
            </div>
                        <div class="mb-5 sm:block md:flex items-center"> 
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white w-1/5 lg:w-1/4">Alamat</label>
                <input type="text" id="nomor_rm" name="nomor_rekam_medis" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" readonly value="<?= esc($pasien['alamat'] ?? '') ?>">
            </div>
                        <div class="mb-5 sm:block md:flex items-center"> 
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white w-1/5 lg:w-1/4">Kelurahan</label>
                <input type="text" id="nomor_rm" name="nomor_rekam_medis" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" readonly value="<?= esc($pasien['kd_kel'] ?? '') ?>">

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Kecamatan</label>
                <input id="nama_pasien" name="nama" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" readonly value="<?= esc($pasien['kd_kec'] ?? '') ?>">
            </div>
                        <div class="mb-5 sm:block md:flex items-center"> 
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white w-1/5 lg:w-1/4">Kabupaten</label>
                <input type="text" id="nomor_rm" name="nomor_rekam_medis" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" readonly value="<?= esc($pasien['kd_kab'] ?? '') ?>">

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Provinsi</label>
                <input id="nama_pasien" name="nama" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" readonly value="<?= esc($pasien['kd_prop'] ?? '') ?>">
            </div>
                        <div class="mb-5 sm:block md:flex items-center"> 
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white w-1/5 lg:w-1/4">Alamat PJ</label>
                <input type="text" id="nomor_rm" name="nomor_rekam_medis" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" readonly value="<?= esc($pasien['alamatpj'] ?? '') ?>">
            </div>
                        <div class="mb-5 sm:block md:flex items-center"> 
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white w-1/5 lg:w-1/4">Kelurahan</label>
                <input type="text" id="nomor_rm" name="nomor_rekam_medis" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" readonly value="<?= esc($pasien['kelurahanpj'] ?? '') ?>">

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Kecamatan</label>
                <input id="nama_pasien" name="nama" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" readonly value="<?= esc($pasien['kecamatanpj'] ?? '') ?>">
            </div>
                        <div class="mb-5 sm:block md:flex items-center"> 
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white w-1/5 lg:w-1/4">Kabupaten</label>
                <input type="text" id="nomor_rm" name="nomor_rekam_medis" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" readonly value="<?= esc($pasien['kabupatenpj'] ?? '') ?>">

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">Provinsi</label>
                <input id="nama_pasien" name="nama" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" readonly value="<?= esc($pasien['propinsipj'] ?? '') ?>">
            </div>
                                    <div class="mb-5 sm:block md:flex items-center"> 
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white w-1/5 lg:w-1/4">Instansi Pasien</label>
                <input type="text" id="nomor_rm" name="nomor_rekam_medis" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" readonly value="<?= esc($pasien['perusahaan_pasien'] ?? '') ?>">

                <label class="block mt-5 md:my-0 md:ml-10 mb-2 text-sm text-gray-900 dark:text-white w-1/5">NIP/NRP</label>
                <input id="nama_pasien" name="nama" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full md:w-1/4 dark:border-gray-600 dark:text-white" readonly value="<?= esc($pasien['nip'] ?? '') ?>">
            </div>
            

            <div class="mt-5 pt-5 border-t flex justify-end gap-x-2">
                <a href="javascript:history.back()" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-white dark:hover:bg-gray-800 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                    Kembali
                </a>
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