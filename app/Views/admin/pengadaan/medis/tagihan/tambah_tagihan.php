<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<!-- Card Section -->
<div class="max-w-4xl px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto">
    <!-- Card -->
    <div class="bg-white rounded-xl shadow p-4 sm:p-7 dark:bg-slate-900">
        <div class="mb-8">
            <h2 class="text-xl font-bold text-gray-800 dark:text-gray-200">
                Tambah Tagihan Barang Medis
            </h2>
            <p class="text-sm text-gray-600 dark:text-gray-400">
                Manage your name, password and account settings.
            </p>
        </div>

        <form action="/submittambahtagihanmedis" method="post">
            <!-- Grid -->
            <input type="hidden" value="" step="any" name="idpemesanan" class="text-center border mr-1 w-[20%]">
            <div class="grid sm:grid-cols-12 gap-2 sm:gap-6">
                <div class="sm:col-span-3">
                    <div class="inline-block">
                        <label class="inline-block text-sm text-gray-800 mt-2.5 dark:text-gray-200">
                            Nomor Faktur
                        </label>
                    </div>
                </div>
                <div class="sm:col-span-9">
                    <div class="sm:flex">
                        <select name="idpenerimaan" id="dropdown-id-penerimaan" class="py-2 px-3 pe-9 block w-full sm:w-auto border-gray-200 shadow-sm -mt-px -ms-px first:rounded-t-lg last:rounded-b-lg sm:first:rounded-s-lg sm:mt-0 sm:first:ms-0 sm:first:rounded-se-none sm:last:rounded-es-none sm:last:rounded-e-lg text-sm relative focus:z-10 focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600">
                            <option value="">-</option>
                            <?php foreach ($penerimaan_data as $penerimaan) : ?>
                                <option value="<?= $penerimaan['id'] ?>"><?= $penerimaan['no_faktur'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="sm:col-span-3">
                    <label class="inline-block text-sm text-gray-800 mt-2.5 dark:text-gray-200">
                        Tanggal Bayar
                    </label>
                </div>
                <!-- End Col -->

                <div class="sm:col-span-9">
                    <input name="tglbayar" type="date" class="py-2 px-3 pe-11 block w-full border-gray-200 shadow-sm text-sm rounded-lg focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600" placeholder="Paracetamol">
                </div>

                <div class="sm:col-span-3">
                    <label class="inline-block text-sm text-gray-800 mt-2.5 dark:text-gray-200">
                        Pegawai
                    </label>
                </div>
                <!-- End Col -->

                <div class="sm:col-span-9">
                    <div class="sm:flex">
                        <select name="pegawaitagihan" class="py-2 px-3 pe-9 block w-full sm:w-auto border-gray-200 shadow-sm -mt-px -ms-px first:rounded-t-lg last:rounded-b-lg sm:first:rounded-s-lg sm:mt-0 sm:first:ms-0 sm:first:rounded-se-none sm:last:rounded-es-none sm:last:rounded-e-lg text-sm relative focus:z-10 focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600">
                            <option value="" selected>-</option>
                            <?php foreach ($pegawai_data as $pegawai) : ?>
                                <option value="<?= $pegawai['id'] ?>"><?= $pegawai['nama'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="sm:col-span-3">
                    <label class="inline-block text-sm text-gray-800 mt-2.5 dark:text-gray-200">
                        Jumlah Bayar / Total
                    </label>
                </div>
                <div class="sm:col-span-9 flex">
                    <input name="jlhbayar" type="text" class="py-2 px-3 mr-1 block w-[30%] border-gray-200 shadow-sm text-sm rounded-lg focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600"> 
                    <input name="totalbayar" type="text" class="py-2 px-3 block w-[30%] border-gray-200 shadow-sm text-sm rounded-lg focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600">
                </div>
                <!-- End Col -->
                <div class="sm:col-span-3">
                    <label class="inline-block text-sm text-gray-800 mt-2.5 dark:text-gray-200">
                        Nomor Bukti
                    </label>
                </div>
                <!-- End Col -->
                <div class="sm:col-span-9">
                    <input name="nobukti" type="text" class="py-2 px-3 pe-11 block w-full border-gray-200 shadow-sm text-sm rounded-lg focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600" placeholder="0">
                </div>
                <div class="sm:col-span-3">
                    <label class="inline-block text-sm text-gray-800 mt-2.5 dark:text-gray-200">
                        Akun Bayar
                    </label>
                </div>
                <!-- End Col -->
                <div class="sm:col-span-9">
                    <input name="akunbayar" type="text" class="py-2 px-3 pe-11 block w-full border-gray-200 shadow-sm text-sm rounded-lg focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600" placeholder="0">
                </div>



                <div class="sm:col-span-3">
                    <label class="inline-block text-sm text-gray-800 mt-2.5 dark:text-gray-200">
                        Keterangan
                    </label>
                </div>
                <!-- End Col -->
                <div class="sm:col-span-9">
                    <input name="keterangantagihan" type="text" class="py-2 px-3 pe-11 block w-full border-gray-200 shadow-sm text-sm rounded-lg focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600" placeholder="0">
                </div>

            </div>
            <!-- End Grid -->
            <div class="mt-5 flex flex-col">
                <div class="-m-1.5 overflow-x-auto">
                    <div class="p-1.5 min-w-full inline-block align-middle">
                        <div class="border rounded-lg overflow-hidden dark:border-neutral-700">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700" id="item-list">
                                <colgroup>
                                    <col width="8%">
                                    <col width="30%">
                                    <col width="18%">
                                    <col width="22%">
                                    <col width="22%">
                                </colgroup>
                                <thead>
                                    <tr class="bg-navy disabled">
                                        <th class="px-1 py-1 text-center">Qty</th>
                                        <th class="px-1 py-1 text-center">Barang</th>
                                        <th class="px-1 py-1 text-center">Satuan</th>
                                        <th class="px-1 py-1 text-center">Harga</th>
                                        <th class="px-1 py-1 text-center">Total</th>
                                    </tr>
                                </thead>
                                <tbody class="tabelbodypesanan divide-y divide-gray-200 dark:divide-neutral-700">
                                    <tr>
                                        <td class="align-middle p-1 text-center">
                                            <input type="number" class="text-center w-full border" name="" />
                                        </td>
                                        <td class="align-middle p-1">
                                            <input type="text" step="any" class="text-center w-full border" name="" />
                                        </td>
                                        <td class="align-middle p-1">
                                            <input type="text" step="any" class="text-center w-full border" name="" />
                                        </td>
                                        <td class="align-middle p-1">
                                            <input type="text" id="harga" step="any" class="text-center w-full border" name="" />
                                        </td>
                                        <td class="align-middle p-1 text-right">
                                            <input type="text" class="text-center w-full border" value="" name="" />
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr class="pt-5">
                                        <input type="hidden" value="" step="any" name="idpengajuan" class="text-center border mr-1 w-[20%]">
                                        <input type="hidden" value="" step="any" name="tglpengajuan" class="text-center border mr-1 w-[20%]">
                                        <input type="hidden" value="" step="any" name="nopengajuan" class="text-center border mr-1 w-[20%]">
                                        <input type="hidden" value="" step="any" name="supplier" class="text-center border mr-1 w-[20%]">
                                        <input type="hidden" value="" step="any" name="pegawaipengajuan" class="text-center border mr-1 w-[20%]">
                                        <input type="hidden" value="" step="any" name="catatanpengajuan" class="text-center border mr-1 w-[20%]">

                                        <th class="p-1 pt-2 text-right" colspan="4">
                                            Discount (%)
                                            <input type="number" value="" step="any" name="diskonpersen" class="text-center border w-[20%]">
                                        </th>
                                        <th class="p-1 pt-2 text-right">
                                            <input type="text" value="" class="text-center w-full border border-gray-300 text-center" name="diskonjumlah">
                                        </th>
                                    </tr>
                                    <tr>
                                        <th class="p-1 text-right" colspan="4">Tax Inclusive (%)
                                            <input type="number" value="" step="any" name="pajakpersen" class=" text-center border w-[20%]">
                                        </th>
                                        <th class="p-1 text-right">
                                            <input type="text" value="" class="text-center w-full border border-gray-300 text-center" name="pajakjumlah">
                                        </th>
                                    </tr>
                                    <tr>
                                        <th class="p-1 text-right" colspan="4">Materai</th>
                                        <th class="p-1 text-right">
                                            <input type="text" value="" class="text-center w-full border border-gray-300 text-center" name="materai">
                                        </th>
                                    </tr>
                                    <tr>
                                        <th class="p-1 text-right" colspan="4">Total</th>
                                        <th class="p-1" id="total"><input type="text" class="w-full border border-gray-300 text-center" name="" disabled></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-5 flex justify-end gap-x-2">
                <button type="button" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-white dark:hover:bg-gray-800 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                    Cancel
                </button>
                <button type="submit" value="5" name="statuspesanan" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
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
        const dropdown = document.getElementById('dropdown-id-penerimaan');

        dropdown.addEventListener('change', function() {
            const selectedId = this.value;
            console.log('ID yang dipilih:', selectedId);

            // URL API berdasarkan nilai yang dipilih dari dropdown
            const penerimaanApiUrl = '<?php echo $_ENV["api_URL"]; ?>/pengadaan/penerimaan/' + selectedId;
            // Token yang digunakan untuk otentikasi
            const token = '<?= $token ?>'; // Ganti dengan token Anda
            const barangMedis = <?php echo json_encode($medis_data); ?>;
            const satuanMedis = <?php echo json_encode($satuan_data); ?>;

            fetch(penerimaanApiUrl, {
                    method: 'GET',
                    headers: {
                        'Authorization': 'Bearer ' + token,
                        'Content-Type': 'application/json'
                        // Tambahkan header lain yang diperlukan seperti Authorization jika diperlukan
                    }
                })
                .then(response => response.json())
                .then(data => {
                    document.querySelector('input[name="idpemesanan"]').value = data['data']['id_pemesanan'];

                    const pengajuanId = data.data.id_pengajuan; // Sesuaikan dengan kunci yang benar dalam respons API
                    const pesananApiUrl = '<?php echo $_ENV["api_URL"]; ?>/pengadaan/pesanan/pengajuan/' + pengajuanId;
                    const pengajuanApiUrl = '<?php echo $_ENV["api_URL"]; ?>/pengadaan/pengajuan/' + pengajuanId;
                    console.log('Pengajuan ID dari penerimaan:', pengajuanId);
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
                                const input1 = document.createElement('input');
                                input1.type = 'number';
                                input1.className = 'text-center w-full';
                                input1.name = 'input_name1'; // Ganti dengan nama yang sesuai
                                input1.value = pesanan.jumlah_pesanan; // Contoh: pesanan.id
                                const td1 = document.createElement('td');
                                td1.className = 'align-middle p-1 text-center';
                                td1.appendChild(input1);
                                tr.appendChild(td1);

                                satuanMedis.forEach(satuan => {
                                    if (pesanan.satuan === satuan.id) {
                                        const satuaninput = document.createElement('input');
                                        satuaninput.type = 'text';
                                        satuaninput.className = 'text-center w-full';
                                        satuaninput.readOnly = true;
                                        satuaninput.name = ''; // Ganti dengan nama yang sesuai
                                        satuaninput.value = satuan.nama; // Contoh: pesanan.id
                                        const tdsatuan = document.createElement('td');
                                        tdsatuan.className = 'align-middle p-1 text-center';
                                        tdsatuan.appendChild(satuaninput);
                                        tr.appendChild(tdsatuan);
                                    }
                                });
                                // Buat input untuk kolom kedua
                                barangMedis.forEach(barang => {
                                    if (pesanan.id_barang_medis === barang.id) {
                                        const medis = document.createElement('input');
                                        medis.type = 'text';
                                        medis.className = 'text-center w-full';
                                        medis.name = ''; // Ganti dengan nama yang sesuai
                                        medis.value = barang.nama; // Contoh: pesanan.jumlah_pesanan
                                        const tdmedis = document.createElement('td');
                                        tdmedis.className = 'align-middle p-1';
                                        tdmedis.appendChild(medis);
                                        tr.appendChild(tdmedis);
                                    }
                                });

                                // Buat input untuk kolom ketiga
                                const input3 = document.createElement('input');
                                input3.type = 'text';
                                input3.className = 'text-center w-full';
                                input3.name = 'input_name3'; // Ganti dengan nama yang sesuai
                                input3.value = pesanan.harga_satuan; // Contoh: pesanan.harga_satuan
                                const td3 = document.createElement('td');
                                td3.className = 'align-middle p-1';
                                td3.appendChild(input3);
                                tr.appendChild(td3);

                                // Buat input untuk kolom keempat
                                const input4 = document.createElement('input');
                                input4.type = 'text';
                                input4.className = 'text-center w-full';
                                input4.name = 'input_name4'; // Ganti dengan nama yang sesuai
                                input4.value = pesanan.jumlah_pesanan * pesanan.harga_satuan; // Contoh: pesanan.jumlah_diterima
                                const td4 = document.createElement('td');
                                td4.className = 'align-middle p-1 text-right';
                                td4.appendChild(input4);
                                tr.appendChild(td4);

                                // Tambahkan baris ke tbody
                                tbody.appendChild(tr);
                                document.querySelector('input[name="totalbayar"]').value = pesanan.jumlah_pesanan * pesanan.harga_satuan;
                            });

                        })
                        .catch(error => {
                            console.error('Terjadi kesalahan:', error);
                        });
                    // Lakukan sesuatu dengan pengajuanId, misalnya melakukan permintaan API lain atau melakukan manipulasi DOM
                    fetch(pengajuanApiUrl, {
                            headers: {
                                'Authorization': 'Bearer ' + token,
                                'Content-Type': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            document.querySelector('input[name="idpengajuan"]').value = data['data']['id'];
                            document.querySelector('input[name="tglpengajuan"]').value = data['data']['tanggal_pengajuan'];
                            document.querySelector('input[name="nopengajuan"]').value = data['data']['nomor_pengajuan'];
                            document.querySelector('input[name="supplier"]').value = data['data']['id_supplier'];
                            document.querySelector('input[name="pegawaipengajuan"]').value = data['data']['id_pegawai'];
                            document.querySelector('input[name="catatanpengajuan"]').value = data['data']['catatan'];
                            document.querySelector('input[name="diskonpersen"]').value = data['data']['diskon_persen'];
                            document.querySelector('input[name="diskonjumlah"]').value = data['data']['diskon_jumlah'];
                            document.querySelector('input[name="pajakpersen"]').value = data['data']['pajak_persen'];
                            document.querySelector('input[name="pajakjumlah"]').value = data['data']['pajak_jumlah'];
                            document.querySelector('input[name="materai"]').value = data['data']['materai'];
                        })
                        .catch(error => {
                            console.error('Terjadi kesalahan pada pengajuan:', error);
                        });
                }) //data penerimaan

                .catch(error => {
                    console.error('Terjadi kesalahan:', error);
                });

            // Lakukan permintaan ke URL API yang baru

        })
    };
</script>
<?= $this->endSection(); ?>