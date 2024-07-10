<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<!-- Card Section -->
<div class="max-w-4xl px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto">
    <!-- Card -->
    <div class="bg-white rounded-xl shadow p-4 sm:p-7 dark:bg-slate-900">
        <div class="mb-8">
            <h2 class="text-xl font-bold text-gray-800 dark:text-gray-200">
                Tambah Penerimaan Barang Medis
            </h2>

        </div>

        <form action="/submittambahpenerimaanmedis" id="penerimaanform" method="post">
            <!-- Grid -->
            <input type="hidden" value="" name="idpengajuan" class="text-center border mr-1">
            <input type="hidden" value="" name="tglpengajuan" class="text-center border mr-1">
            <input type="hidden" value="" name="nopengajuan" class="text-center border mr-1">
            <input type="hidden" value="" name="supplier" class="text-center border mr-1">
            <input type="hidden" value="" name="pegawaipengajuan" class="text-center border mr-1">
            <input type="hidden" value="" name="catatanpengajuan" class="text-center border mr-1">
            <input type="hidden" value="" name="diskonpersen" class="text-center border" readonly>
            <input type="hidden" value="" name="diskonjumlah" class="w-full border border-gray-300 text-center" readonly>
            <input type="hidden" value="" name="pajakpersen" class=" text-center border" readonly>
            <input type="hidden" value="" name="pajakjumlah" class="w-full border border-gray-300 text-center" readonly>
            <input type="hidden" value="" name="materai" class="w-full border border-gray-300 text-center" readonly>

            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Nomor Pemesanan</label>
                <select name="idpemesanan" id="dropdown-id-pemesanan" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white">
                    <option value="">-</option>
                    <?php
                    foreach ($pengajuan_data as $pengajuan) {
                        foreach ($pemesanan_data as $pemesanan) {
                            if ($pengajuan['id'] === $pemesanan['id_pengajuan'] && $pengajuan['status_pesanan'] === '3') { ?>
                                <option value="<?= $pemesanan['id'] ?>"><?= $pemesanan['no_pemesanan'] ?></option>
                    <?php }
                        }
                    } ?>
                </select>
            </div>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Tanggal Penerimaan</label>
                <input type="date" name="tgldatang" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white">
            </div>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Tanggal Faktur</label>
                <input type="date" name="tglfaktur" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white">
            </div>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Tanggal Jatuh Tempo</label>
                <input type="date" name="tgljatuhtempo" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white">
            </div>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Nomor Faktur</label>
                <input type="text" name="nofaktur" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white">
            </div>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Pegawai</label>
                <select name="pegawaipenerimaan" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white">
                    <option value="" selected>-</option>
                    <?php foreach ($pegawai_data as $pegawai) : ?>
                        <option value="<?= $pegawai['id'] ?>"><?= $pegawai['nama'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Ruangan</label>
                <select name="idruangan" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white">
                    <option selected>-</option>
                    <option value="1000">VIP 1</option>
                    <option value="2000">VIP 2</option>
                    <option value="3000">VVIP 1</option>
                    <option value="4000">VVIP 2</option>
                    <option value="5000">Gudang</option>
                </select>
            </div>


            <!-- End Grid -->
            <div class="mt-5 flex flex-col">
                <div class="-m-1.5 overflow-x-auto">
                    <div class="p-1.5 min-w-full inline-block align-middle">
                        <div class="border rounded-lg overflow-hidden dark:border-neutral-700">
                            <table class="pt-5 min-w-full divide-y divide-gray-200 dark:divide-neutral-700" id="item-list">
                                <colgroup>

                                    <col width="10%">
                                    <!-- 38% -->
                                    <col width="15%">
                                    <col width="25%">
                                    <col width="18%">
                                    <col width="18%">
                                    <col width="18%">

                                </colgroup>
                                <thead class="bg-[#DCDCDC]">
                                    <tr class="bg-navy disabled">

                                        <th class="px-1 py-1 text-center">Jumlah</th>
                                        <th class="px-1 py-1 text-center">Satuan</th>
                                        <th class="px-1 py-1">Item</th>
                                        <th class="px-1 py-1 text-center">Jumlah Diterima</th>
                                        <th class="px-1 py-1 text-center">Kadaluwarsa</th>
                                        <th class="px-1 py-1 text-center">No Batch</th>
                                    </tr>
                                </thead>
                                <tbody class="tabelbodypesanan divide-y divide-gray-200 dark:divide-neutral-700">
                                    <tr>

                                        <td class="align-middle p-1 text-center">
                                            <input type="text" class="text-center w-full" step="any" name="" readonly />
                                        </td>
                                        <td class="align-middle p-1">
                                            <input type="text" step="any" class="text-center w-full" name="" readonly />

                                        </td>
                                        <td class="align-middle p-1">
                                            <input type="text" step="any" class="text-center w-full" name="" readonly />

                                        </td>

                                        <td class="align-middle p-1 text-center">
                                            <input type="text" class="text-center w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#FDFDFD]" name="" readonly />
                                        </td>
                                        <td class="align-middle p-1 text-center">
                                            <input type="date" class="text-center w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#FDFDFD]" name="" readonly />
                                        </td>
                                        <td class="align-middle p-1 text-center">
                                            <input type="text" class="text-center w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#FDFDFD]" name="" readonly />
                                        </td>

                                    </tr>

                                </tbody>



                            </table>

                        </div>

                    </div>
                </div>

            </div>
            <div class="mt-5 flex justify-end gap-x-2">
                <button type="button" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-white dark:hover:bg-gray-800 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                    Kembali
                </button>
                <button type="submit" name="statuspesanan" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-[#0A2D27] text-[#ACF2E7] disabled:opacity-50 disabled:pointer-events-none dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                    Simpan
                </button>
            </div>
        </form>

    </div>
    <!-- End Card -->

</div>

<!-- End Card Section -->
<script>
    window.onload = function() {
        const dropdown = document.getElementById('dropdown-id-pemesanan');

        dropdown.addEventListener('change', function() {
            const selectedId = this.value;

            // URL API berdasarkan nilai yang dipilih dari dropdown
            const pemesananApiUrl = '<?php echo $_ENV["api_URL"]; ?>/pengadaan/pemesanan/' + selectedId;
            // Token yang digunakan untuk otentikasi
            const token = '<?= $token ?>'; 
            const barangMedis = <?php echo json_encode($medis_data); ?>;
            const satuanMedis = <?php echo json_encode($satuan_data); ?>;

            fetch(pemesananApiUrl, {
                    method: 'GET',
                    headers: {
                        'Authorization': 'Bearer ' + token,
                        'Content-Type': 'application/json'
                        // Tambahkan header lain yang diperlukan seperti Authorization jika diperlukan
                    }
                })
                .then(response => response.json())
                .then(data => {
                    // Jika respons API mengandung pengajuanId, Anda dapat mengekstraknya dari data respons
                    const pengajuanId = data.data.id_pengajuan; // Sesuaikan dengan kunci yang benar dalam respons API
                    const pesananApiUrl = '<?php echo $_ENV["api_URL"]; ?>/pengadaan/pesanan/pengajuan/' + pengajuanId;
                    const pengajuanApiUrl = '<?php echo $_ENV["api_URL"]; ?>/pengadaan/pengajuan/' + pengajuanId;
                    console.log('Pengajuan ID dari pemesanan:', pengajuanId);
                    fetch(pesananApiUrl, {
                            headers: {
                                'Authorization': 'Bearer ' + token,
                                'Content-Type': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            const tbody = document.querySelector('.tabelbodypesanan');
                            tbody.innerHTML = '';
                            data.data.forEach(pesanan => {
                                const tr = document.createElement('tr');

                                // Buat input untuk setiap kolom dalam baris
                                const idpesanan = document.createElement('input');
                                idpesanan.type = 'hidden';
                                idpesanan.className = 'text-center w-full';
                                idpesanan.readOnly = true;
                                idpesanan.name = 'idpesanan[]'; // Ganti dengan nama yang sesuai
                                idpesanan.value = pesanan.id; // Contoh: pesanan.id
                                const tdidpesanan = document.createElement('td');
                                tdidpesanan.className = 'align-middle p-1 text-center';
                                tdidpesanan.appendChild(idpesanan);
                                tr.appendChild(tdidpesanan);
                                tdidpesanan.style.display = 'none';

                                const input1 = document.createElement('input');
                                input1.type = 'text';
                                input1.className = 'text-center w-full rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] bg-[#F6F6F6]';
                                input1.readOnly = true;
                                input1.name = 'jumlah_pesanan[]'; // Ganti dengan nama yang sesuai
                                input1.value = pesanan.jumlah_pesanan; // Contoh: pesanan.id
                                const td1 = document.createElement('td');
                                td1.className = 'align-middle p-1 text-center';
                                td1.appendChild(input1);
                                tr.appendChild(td1);

                                satuanMedis.forEach(satuan => {
                                    if (pesanan.satuan === satuan.id) {
                                        const satuanIdInput = document.createElement('input');
                                        satuanIdInput.type = 'hidden'; // Hide the input as it's just for sending data
                                        satuanIdInput.name = 'satuan[]';
                                        satuanIdInput.value = satuan.id;
                                        const satuaninput = document.createElement('input');
                                        satuaninput.type = 'text';
                                        satuaninput.className = 'text-center w-full rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] bg-[#F6F6F6]';
                                        satuaninput.readOnly = true;
                                        satuaninput.name = ''; // Ganti dengan nama yang sesuai
                                        satuaninput.value = satuan.nama; // Contoh: pesanan.id
                                        const tdsatuan = document.createElement('td');
                                        tdsatuan.className = 'align-middle p-1 text-center';
                                        tdsatuan.appendChild(satuanIdInput);
                                        tdsatuan.appendChild(satuaninput);
                                        tr.appendChild(tdsatuan);
                                    }
                                });
                                // Buat input untuk kolom kedua
                                barangMedis.forEach(barang => {
                                    if (pesanan.id_barang_medis === barang.id) {
                                        const medisIdInput = document.createElement('input');
                                        medisIdInput.type = 'hidden'; // Hide the input as it's just for sending data
                                        medisIdInput.name = 'idbrgmedis[]';
                                        medisIdInput.value = pesanan.id_barang_medis;
                                        const medis = document.createElement('input');
                                        medis.type = 'text';
                                        medis.className = 'text-center w-full rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] bg-[#F6F6F6]';
                                        medis.name = ''; // Ganti dengan nama yang sesuai
                                        medis.value = barang.nama; // Contoh: pesanan.jumlah_pesanan
                                        const tdmedis = document.createElement('td');
                                        tdmedis.className = 'align-middle p-1';
                                        tdmedis.appendChild(medisIdInput);
                                        tdmedis.appendChild(medis);
                                        tr.appendChild(tdmedis);
                                    }
                                });

                                // Buat input untuk kolom ketiga
                                const input3 = document.createElement('input');
                                input3.type = 'hidden';
                                input3.className = 'text-center w-full';
                                input3.readOnly = true;
                                input3.name = 'harga_satuan_pengajuan[]'; // Ganti dengan nama yang sesuai
                                input3.value = pesanan.harga_satuan_pengajuan; // Contoh: pesanan.harga_satuan
                                const td3 = document.createElement('td');
                                td3.className = 'align-middle p-1';
                                td3.appendChild(input3);
                                tr.appendChild(td3);
                                td3.style.display = 'none';

                                const hargapemesanan = document.createElement('input');
                                hargapemesanan.type = 'hidden';
                                hargapemesanan.className = 'text-center w-full';
                                hargapemesanan.readOnly = true;
                                hargapemesanan.name = 'harga_satuan_pemesanan[]'; // Ganti dengan nama yang sesuai
                                hargapemesanan.value = pesanan.harga_satuan_pemesanan; // Contoh: pesanan.harga_satuan
                                const tdhargapemesanan = document.createElement('td');
                                tdhargapemesanan.className = 'align-middle p-1';
                                tdhargapemesanan.appendChild(hargapemesanan);
                                tr.appendChild(tdhargapemesanan);
                                tdhargapemesanan.style.display = 'none';

                                const input5 = document.createElement('input');
                                input5.type = 'text';
                                input5.className = 'text-center w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#FDFDFD]';
                                input5.name = 'jumlah_diterima[]'; // Ganti dengan nama yang sesuai
                                const td5 = document.createElement('td');
                                td5.className = 'align-middle p-1 text-center';
                                td5.appendChild(input5);
                                tr.appendChild(td5);

                                const input6 = document.createElement('input');
                                input6.type = 'date';
                                input6.className = 'text-center w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#FDFDFD]';
                                input6.name = 'kadaluwarsa[]'; // Ganti dengan nama yang sesuai
                                const td6 = document.createElement('td');
                                td6.className = 'align-middle p-1 text-center';
                                td6.appendChild(input6);
                                tr.appendChild(td6);

                                const input7 = document.createElement('input');
                                input7.type = 'text';
                                input7.className = 'text-center w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#FDFDFD]';
                                input7.name = 'no_batch[]'; // Ganti dengan nama yang sesuai
                                const td7 = document.createElement('td');
                                td7.className = 'align-middle p-1 text-center';
                                td7.appendChild(input7);
                                tr.appendChild(td7);

                                // Tambahkan baris ke tbody
                                tbody.appendChild(tr);
                            });

                        })
                        .catch(error => {
                            console.error('Terjadi kesalahan pada pesanan:', error);
                        });
                    // Lakukan sesuatu dengan pengajuanId, misalnya melakukan permintaan API lain atau melakukan manipulasi DOM
                    fetch(pengajuanApiUrl, {
                            headers: {
                                'Authorization': 'Bearer ' + token,
                                'Content-Type': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(pengajuan => {
                            var idPengajuanInput = document.querySelector('input[name="idpengajuan"]');
                            var diskonPersenInput = document.querySelector('input[name="diskonpersen"]');
                            var diskonJumlahInput = document.querySelector('input[name="diskonjumlah"]');
                            var pajakPersenInput = document.querySelector('input[name="pajakpersen"]');
                            var pajakJumlahInput = document.querySelector('input[name="pajakjumlah"]');
                            var materaiInput = document.querySelector('input[name="materai"]');
                            var tglPengajuanInput = document.querySelector('input[name="tglpengajuan"]');
                            var noPengajuanInput = document.querySelector('input[name="nopengajuan"]');
                            var supplierInput = document.querySelector('input[name="supplier"]');
                            var pegawaiPengajuanInput = document.querySelector('input[name="pegawaipengajuan"]');
                            var catatanPengajuanInput = document.querySelector('input[name="catatanpengajuan"]');

                            // Memasukkan data pengajuan ke dalam input
                            idPengajuanInput.value = pengajuan['data']['id'];
                            diskonPersenInput.value = pengajuan['data']['diskon_persen'];
                            diskonJumlahInput.value = pengajuan['data']['diskon_jumlah'];
                            pajakPersenInput.value = pengajuan['data']['pajak_persen'];
                            pajakJumlahInput.value = pengajuan['data']['pajak_jumlah'];
                            materaiInput.value = pengajuan['data']['materai'];
                            tglPengajuanInput.value = pengajuan['data']['tanggal_pengajuan'];
                            noPengajuanInput.value = pengajuan['data']['nomor_pengajuan'];
                            supplierInput.value = pengajuan['data']['id_supplier'];
                            pegawaiPengajuanInput.value = pengajuan['data']['id_pegawai'];
                            catatanPengajuanInput.value = pengajuan['data']['catatan'];



                        })
                        .catch(error => {
                            console.error('Terjadi kesalahan pada pengajuan:', error);
                        });
                }) //data pemesanan

                .catch(error => {
                    console.error('Terjadi kesalahan:', error);
                });

            // Lakukan permintaan ke URL API yang baru

        })
    };

    document.getElementById('penerimaanform').addEventListener('submit', function(event) {
        const jumlahPesananInputs = document.querySelectorAll('.tabelbodypesanan input[name="jumlah_pesanan[]"]');
        const jumlahDiterimaInputs = document.querySelectorAll('.tabelbodypesanan input[name="jumlah_diterima[]"]');

        // Check if both arrays have the same length
        // if (jumlahPesananInputs.length !== jumlahDiterimaInputs.length) {
        //     console.error("Number of 'jumlah_pesanan[]' inputs doesn't match number of 'jumlah_diterima[]' inputs.");
        //     return; // Exit the function
        // }

        let allMatch = true;

        // Loop through each 'jumlah_diterima[]' input
        jumlahDiterimaInputs.forEach((input, index) => {
            // Compare the value with corresponding 'jumlah_pesanan[]' input
            if (input.value !== jumlahPesananInputs[index].value) {
                allMatch = false;
                return; // Exit the loop early if a mismatch is found
            }
        });

        const submitButton = document.querySelector('button[name="statuspesanan"]');
        submitButton.value = allMatch ? '5' : '4';
    });
</script>
<?= $this->endSection(); ?>