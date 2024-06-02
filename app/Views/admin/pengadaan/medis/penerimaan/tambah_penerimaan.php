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
            <p class="text-sm text-gray-600 dark:text-gray-400">
                Manage your name, password and account settings.
            </p>
        </div>

        <form action="/submittambahpenerimaanmedis" method="post">
            <!-- Grid -->
            <input type="hidden" value="" name="idpengajuan" class="text-center border mr-1">
            <input type="hidden" value="" name="tglpengajuan" class="text-center border mr-1">
            <input type="hidden" value="" name="nopengajuan" class="text-center border mr-1">
            <input type="hidden" value="" name="supplier" class="text-center border mr-1">
            <input type="hidden" value="" name="pegawaipengajuan" class="text-center border mr-1">
            <input type="hidden" value="" name="catatanpengajuan" class="text-center border mr-1">
            <input type="hidden" value="" name="diskonpersen" class="text-center border" readonly>
            <input type="hidden" value="" name="diskonjumlah" class="text-center w-full border border-gray-300 text-center" readonly>
            <input type="hidden" value="" name="pajakpersen" class=" text-center border" readonly>
            <input type="hidden" value="" name="pajakjumlah" class="text-center w-full border border-gray-300 text-center" readonly>
            <input type="hidden" value="" name="materai" class="text-center w-full border border-gray-300 text-center" readonly>

            <div class="grid sm:grid-cols-12 gap-2 sm:gap-6">
                <div class="sm:col-span-3">
                    <label class="inline-block text-sm text-gray-800 mt-2.5 dark:text-gray-200">
                        Tanggal Penerimaan
                    </label>
                </div>
                <!-- End Col -->

                <div class="sm:col-span-9">
                    <input name="tgldatang" type="date" class="py-2 px-3 pe-11 block w-full border-gray-200 shadow-sm text-sm rounded-lg focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600" placeholder="Paracetamol">
                </div>
                <div class="sm:col-span-3">
                    <label class="inline-block text-sm text-gray-800 mt-2.5 dark:text-gray-200">
                        Tanggal Faktur
                    </label>
                </div>
                <!-- End Col -->

                <div class="sm:col-span-9">
                    <input name="tglfaktur" type="date" class="py-2 px-3 pe-11 block w-full border-gray-200 shadow-sm text-sm rounded-lg focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600" placeholder="Paracetamol">
                </div>
                <div class="sm:col-span-3">
                    <label class="inline-block text-sm text-gray-800 mt-2.5 dark:text-gray-200">
                        Tanggal Jatuh Tempo
                    </label>
                </div>
                <!-- End Col -->

                <div class="sm:col-span-9">
                    <input name="tgljatuhtempo" type="date" class="py-2 px-3 pe-11 block w-full border-gray-200 shadow-sm text-sm rounded-lg focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600" placeholder="Paracetamol">
                </div>
                <div class="sm:col-span-3">
                    <label class="inline-block text-sm text-gray-800 mt-2.5 dark:text-gray-200">
                        Nomor Faktur
                    </label>
                </div>
                <!-- End Col -->

                <div class="sm:col-span-9">
                    <input name="nofaktur" type="text" class="py-2 px-3 pe-11 block w-full border-gray-200 shadow-sm text-sm rounded-lg focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600" value="">
                </div>
                <!-- End Col -->

                <div class="sm:col-span-3">
                    <div class="inline-block">
                        <label class="inline-block text-sm text-gray-800 mt-2.5 dark:text-gray-200">
                            Nomor Pemesanan
                        </label>
                    </div>
                </div>
                <div class="sm:col-span-9">
                    <div class="sm:flex">
                        <select name="idpemesanan" id="dropdown-id-pemesanan" class="py-2 px-3 block w-full border-gray-200 shadow-sm text-sm rounded-lg focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600">
                            <option value="">-</option>

                            <?php foreach ($pemesanan_data as $pemesanan) : ?>

                                <option value="<?= $pemesanan['id'] ?>"><?= $pemesanan['no_pemesanan'] ?></option>

                            <?php endforeach; ?>

                        </select>
                    </div>
                </div>
                <!-- End Col -->

                <div class="sm:col-span-3">
                    <label class="inline-block text-sm text-gray-800 mt-2.5 dark:text-gray-200">
                        Pegawai
                    </label>
                </div>
                <!-- End Col -->

                <div class="sm:col-span-9">
                    <div class="sm:flex">
                        <select name="pegawaipenerimaan" class="py-2 px-3 block w-full border-gray-200 shadow-sm text-sm rounded-lg focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600">
                            <option value="" selected>-</option>
                            <?php foreach ($pegawai_data as $pegawai) : ?>
                                <option value="<?= $pegawai['id'] ?>"><?= $pegawai['nama'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="sm:col-span-3">
                    <label class="inline-block text-sm text-gray-800 mt-2.5 dark:text-gray-200">
                        Ruangan
                    </label>
                </div>
                <!-- End Col -->
                <div class="sm:col-span-9">
                    <select name="idruangan" class="py-2 px-3 block w-full border-gray-200 shadow-sm text-sm rounded-lg focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600">
                        <option selected>-</option>
                        <option value="1000">VIP 1</option>
                        <option value="2000">VIP 2</option>
                        <option value="3000">VVIP 1</option>
                        <option value="4000">VVIP 2</option>
                        <option value="5000">Gudang</option>
                    </select>
                </div>

            </div>
            <!-- End Grid -->
            <div class="mt-5 flex flex-col">
                <div class="-m-1.5 overflow-x-auto">
                    <div class="p-1.5 min-w-full inline-block align-middle">
                        <div class="border rounded-lg overflow-hidden dark:border-neutral-700">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700" id="item-list">
                                <colgroup>

                                    <col width="7%">
                                    <!-- 38% -->
                                    <col width="13%">
                                    <col width="25%">
                                    <col width="10%">
                                    <col width="20%">
                                    <col width="25%">
                                </colgroup>
                                <thead>
                                    <tr class="bg-navy disabled">

                                        <th class="px-1 py-1 text-center">Qty</th>
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
                                            <input type="text" class="text-center w-full border" name="" readonly />
                                        </td>
                                        <td class="align-middle p-1 text-center">
                                            <input type="date" class="text-center w-full border" name="" readonly />
                                        </td>
                                        <td class="align-middle p-1 text-center">
                                            <input type="text" class="text-center w-full border" name="" readonly />
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
                    Cancel
                </button>
                <button type="submit" value="4" name="statuspesanan" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                    Save changes
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
            console.log('ID yang dipilih:', selectedId);

            // URL API berdasarkan nilai yang dipilih dari dropdown
            const pemesananApiUrl = '<?php echo $_ENV["api_URL"]; ?>/pengadaan/pemesanan/' + selectedId;
            // Token yang digunakan untuk otentikasi
            const token = '<?= $token ?>'; // Ganti dengan token Anda
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
                                input1.className = 'text-center w-full';
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
                                        satuaninput.className = 'text-center w-full';
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
                                        medis.className = 'text-center w-full';
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
                                input3.name = 'harga_satuan[]'; // Ganti dengan nama yang sesuai
                                input3.value = pesanan.harga_satuan; // Contoh: pesanan.harga_satuan
                                const td3 = document.createElement('td');
                                td3.className = 'align-middle p-1';
                                td3.appendChild(input3);
                                tr.appendChild(td3);
                                td3.style.display = 'none';

                                const input5 = document.createElement('input');
                                input5.type = 'text';
                                input5.className = 'text-center w-full border';
                                input5.name = 'jumlah_diterima[]'; // Ganti dengan nama yang sesuai
                                const td5 = document.createElement('td');
                                td5.className = 'align-middle p-1 text-center';
                                td5.appendChild(input5);
                                tr.appendChild(td5);

                                const input6 = document.createElement('input');
                                input6.type = 'date';
                                input6.className = 'text-center w-full border';
                                input6.name = 'kadaluwarsa[]'; // Ganti dengan nama yang sesuai
                                const td6 = document.createElement('td');
                                td6.className = 'align-middle p-1 text-center';
                                td6.appendChild(input6);
                                tr.appendChild(td6);

                                const input7 = document.createElement('input');
                                input7.type = 'text';
                                input7.className = 'text-center w-full border';
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
</script>
<?= $this->endSection(); ?>