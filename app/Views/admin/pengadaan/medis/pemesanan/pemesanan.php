<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<!-- Card Section -->
<div class="max-w-4xl px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto">
    <!-- Card -->
    <div class="bg-white rounded-xl shadow p-4 sm:p-7 dark:bg-slate-900">
        <div class="mb-8">
            <h2 class="text-xl font-bold text-gray-800 dark:text-gray-200">
                Tambah Pemesanan Barang Medis
            </h2>

        </div>

        <form action="/submittambahpemesananmedis" method="post">
            <!-- Grid -->
            <input type="hidden" name="idpengajuan" value="<?= $pengajuan_data['id'] ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white">
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Nomor Pengajuan</label>
                <input type="text" name="" value="<?= $pengajuan_data['nomor_pengajuan'] ?>" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white">
            </div>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Tanggal Pemesanan</label>
                <input type="date" name="tglpemesanan" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white">
            </div>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Nomor Pemesanan</label>
                <input type="text" name="nopemesanan" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white">
            </div>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Supplier</label>
                <select name="supplier" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white">
                    <option value="" selected>-</option>
                    <?php foreach ($supplier_data as $supplier) : ?>
                        <option value="<?= $supplier['id'] ?>"><?= $supplier['nama'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Pegawai</label>
                <select name="pegawaipemesanan" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white">
                    <option value="" selected>-</option>
                    <?php foreach ($pegawai_data as $pegawai) : ?>
                        <option value="<?= $pegawai['id'] ?>"><?= $pegawai['nama'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- End Grid -->
            <div class="mt-5 flex flex-col">
                <div class="-m-1.5 overflow-x-auto">
                    <div class="p-1.5 min-w-full inline-block align-middle">
                        <div class="border-x-0 border-b-0 overflow-hidden dark:border-neutral-700">
                            <div class="border rounded-lg">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700" id="item-list">
                                    <colgroup>
                                        <col width="8%">
                                        <col width="30%">
                                        <col width="18%">
                                        <col width="22%">
                                        <col width="22%">
                                    </colgroup>
                                    <thead class="border-b bg-[#DCDCDC]">
                                        <tr class="bg-navy disabled">
                                            <th class="px-1 py-1 text-center">Jumlah</th>
                                            <th class="px-1 py-1 text-center">Barang</th>
                                            <th class="px-1 py-1 text-center">Satuan</th>
                                            <th class="px-1 py-1 text-center">Harga Pemesanan</th>
                                            <th class="px-1 py-1 text-center">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody class="tabelbodypesanan divide-y divide-gray-200 dark:divide-neutral-700">
                                        <?php foreach ($pesanan_data as $pesanan) { ?>
                                            <tr>
                                                <td class="align-middle p-1 text-center">
                                                    <input type="number" value="<?= $pesanan['jumlah_pesanan'] ?>" class="text-center w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#FDFDFD]" name="jumlah_pesanan[]" />
                                                </td>
                                                <td class="align-middle p-1">
                                                    <input type="hidden" value="<?= $pesanan['id'] ?>" name="idpesanan[]" class="text-center border mr-1 w-[20%]">
                                                    <input type="hidden" value="<?= $pesanan['id_barang_medis'] ?>" name="idbrgmedis[]" class="text-center border mr-1 w-[20%]">
                                                    <input type="hidden" value="<?= $pesanan['kadaluwarsa'] ?>" name="kadaluwarsa[]" class="text-center border mr-1 w-[20%]">
                                                    <input type="hidden" value="<?= $pesanan['harga_satuan_pengajuan'] ?>" name="harga_satuan_pengajuan[]" />
                                                    <input type="hidden" value="<?= $pesanan['jumlah_diterima'] ?>" name="jumlah_diterima[]" class="text-center border mr-1 w-[20%]">
                                                    <input type="hidden" value="<?= $pesanan['no_batch'] ?>" name="no_batch[]" class="text-center border mr-1 w-[20%]">
                                                    <input type="text" value="<?php foreach ($medis_data as $medis) {
                                                                                    if ($medis['id'] === $pesanan['id_barang_medis']) {
                                                                                        echo $medis['nama'];
                                                                                    }
                                                                                } ?>" class="text-center w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#FDFDFD]" name="" />
                                                </td>
                                                <td class="align-middle p-1">
                                                    <input type="hidden" value="<?= $pesanan['satuan'] ?>" step="any" name="satuanbrgmedis[]" class="text-center border mr-1 w-[20%]">
                                                    <input type="text" step="any" value="<?php foreach ($satuan_data as $satuan) {
                                                                                                if ($satuan['id'] === $pesanan['satuan']) {
                                                                                                    echo $satuan['nama'];
                                                                                                }
                                                                                            } ?>" class="text-center w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#FDFDFD]" name="" />
                                                </td>
                                                <td class="align-middle p-1">
                                                    <input type="text" value="<?= $pesanan['harga_satuan_pengajuan'] ?>" class="text-center w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#FDFDFD]" name="harga_satuan_pemesanan[]" />
                                                </td>
                                                <td class="align-middle p-1 text-right">
                                                    <input type="text" value="" class="text-center w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#FDFDFD]" value="" name="" />
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>

                                    <tfoot>
                                        <tr>
                                            <input type="hidden" value="<?= $pengajuan_data['tanggal_pengajuan'] ?>" step="any" name="tglpengajuan" class="text-center border mr-1 w-[20%]">
                                            <input type="hidden" value="<?= $pengajuan_data['nomor_pengajuan'] ?>" step="any" name="nopengajuan" class="text-center border mr-1 w-[20%]">
                                            <input type="hidden" value="<?= $pengajuan_data['id_pegawai'] ?>" step="any" name="pegawaipengajuan" class="text-center border mr-1 w-[20%]">
                                            <input type="hidden" value="<?= $pengajuan_data['catatan'] ?>" step="any" name="catatanpengajuan" class="text-center border mr-1 w-[20%]">

                                            <th class="p-1 text-right" colspan="4">
                                                Discount (%)
                                                <input type="hidden" value="<?= $pengajuan_data['diskon_persen'] ?>" step="any" name="diskonpersenpengajuan" class="text-center border w-[20%]">
                                                <input type="number" value="<?= $pengajuan_data['diskon_persen'] ?>" step="any" name="diskonpersenpemesanan" class="text-center border w-[20%] rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#FDFDFD]">
                                            </th>
                                            <th class="p-1 text-right">
                                                <input type="hidden" value="<?= $pengajuan_data['diskon_jumlah'] ?>" class="text-center w-full border border-gray-300" name="diskonjumlahpengajuan">
                                                <input type="text" value="<?= $pengajuan_data['diskon_jumlah'] ?>" class="text-center w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#FDFDFD]" name="diskonjumlahpemesanan">
                                            </th>
                                        </tr>
                                        <tr>
                                            <th class="p-1 text-right" colspan="4">Tax Inclusive (%)
                                                <input type="hidden" value="<?= $pengajuan_data['pajak_persen'] ?>" step="any" name="pajakpersenpengajuan" class=" text-center border w-[20%]">
                                                <input type="number" value="<?= $pengajuan_data['pajak_persen'] ?>" step="any" name="pajakpersenpemesanan" class=" text-center border w-[20%] rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#FDFDFD]">
                                            </th>
                                            <th class="p-1 text-right">
                                                <input type="hidden" value="<?= $pengajuan_data['pajak_jumlah'] ?>" class="text-center w-full border border-gray-300" name="pajakjumlahpengajuan">
                                                <input type="text" value="<?= $pengajuan_data['pajak_jumlah'] ?>" class="text-center w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#FDFDFD]" name="pajakjumlahpemesanan">
                                            </th>
                                        </tr>
                                        <tr>
                                            <th class="p-1 text-right" colspan="4">Materai</th>
                                            <th class="p-1 text-right">
                                                <input type="hidden" value="<?= $pengajuan_data['materai'] ?>" class="text-center w-full border border-gray-300" name="materaipengajuan">
                                                <input type="text" value="<?= $pengajuan_data['materai'] ?>" class="text-center w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#FDFDFD]" name="materaipemesanan">
                                            </th>
                                        </tr>
                                        <tr>
                                            <th class="p-1 text-right" colspan="4">Total</th>
                                            <th class="p-1" id="total"><input type="text" class="w-full border text-center rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#FDFDFD]" name="" disabled></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-5 flex justify-end gap-x-2">
                <button type="button" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-white dark:hover:bg-gray-800 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                    Batal
                </button>
                <button type="submit" value="3" name="statuspesanan" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-[#0A2D27] text-[#ACF2E7] disabled:opacity-50 disabled:pointer-events-none dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                    Tambah
                </button>
            </div>
        </form>
    </div>
    <!-- End Card -->
</div>

<!-- End Card Section -->
<script>
    // window.onload = function() {
    //     const dropdown = document.getElementById('dropdown-id-pengajuan');

    //     dropdown.addEventListener('change', function() {
    //         const selectedId = this.value;
    //         console.log('ID yang dipilih:', selectedId);

    //         // URL API berdasarkan nilai yang dipilih dari dropdown
    //         const pesananApiUrl = '<?= $api_url ?>/pengadaan/pesanan/pengajuan/' + selectedId;
    //         const pengajuanApiUrl = '<?= $api_url ?>/pengadaan/pengajuan/' + selectedId;

    //         // Token yang digunakan untuk otentikasi
    //         const token = '<?= $token ?>'; // Ganti dengan token Anda
    //         const barangMedis = <?php echo json_encode($medis_data); ?>;
    //         // Lakukan permintaan ke URL API yang baru
    //         fetch(pesananApiUrl, {
    //                 headers: {
    //                     'Authorization': 'Bearer ' + token,
    //                     'Content-Type': 'application/json'
    //                 }
    //             })
    //             .then(response => response.json())
    //             .then(data => {
    //                 const tbody = document.querySelector('.tabelbodypesanan');
    //                 tbody.innerHTML = '';
    //                 data.data.forEach(pesanan => {
    //                     const tr = document.createElement('tr');

    //                     // Buat input untuk setiap kolom dalam baris
    //                     const idpesanan = document.createElement('input');
    //                     idpesanan.type = 'hidden';
    //                     idpesanan.className = 'text-center w-full border';
    //                     idpesanan.name = 'idpesanan[]'; // Ganti dengan nama yang sesuai
    //                     idpesanan.value = pesanan.id; // Contoh: pesanan.id
    //                     const tdpesanan = document.createElement('td');
    //                     tdpesanan.className = 'align-middle p-1 text-center';
    //                     tdpesanan.appendChild(idpesanan);
    //                     tr.appendChild(tdpesanan);
    //                     tdpesanan.style.display = 'none';

    //                     const input1 = document.createElement('input');
    //                     input1.type = 'number';
    //                     input1.className = 'text-center w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#FDFDFD]';
    //                     input1.name = 'jumlah_pesanan[]'; // Ganti dengan nama yang sesuai
    //                     input1.value = pesanan.jumlah_pesanan; // Contoh: pesanan.id
    //                     const td1 = document.createElement('td');
    //                     td1.className = 'align-middle p-1 text-center';
    //                     td1.appendChild(input1);
    //                     tr.appendChild(td1);

    //                     // Buat input untuk kolom kedua
    //                     barangMedis.forEach(barang => {
    //                         if (pesanan.id_barang_medis === barang.id) {
    //                             const idbrgmedis = document.createElement('input');
    //                             idbrgmedis.type = 'hidden';
    //                             idbrgmedis.className = 'text-center w-full border';
    //                             idbrgmedis.name = 'idbrgmedis[]'; // Ganti dengan nama yang sesuai
    //                             idbrgmedis.value = barang.id; // Contoh: pesanan.jumlah_pesanan
    //                             const tdidbrgmedis = document.createElement('td');
    //                             tdidbrgmedis.className = 'align-middle p-1';
    //                             tdidbrgmedis.appendChild(idbrgmedis);
    //                             tr.appendChild(tdidbrgmedis);
    //                             tdidbrgmedis.style.display = 'none';

    //                             const input2 = document.createElement('input');
    //                             input2.type = 'text';
    //                             input2.className = 'text-center w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#FDFDFD]';
    //                             input2.name = 'input_name2'; // Ganti dengan nama yang sesuai
    //                             input2.value = barang.nama; // Contoh: pesanan.jumlah_pesanan
    //                             const td2 = document.createElement('td');
    //                             td2.className = 'align-middle p-1';
    //                             td2.appendChild(input2);
    //                             tr.appendChild(td2);
    //                         }
    //                     });
    //                     const satuan = document.createElement('input');
    //                     satuan.type = 'text';
    //                     satuan.className = 'text-center w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#FDFDFD]';
    //                     satuan.name = 'satuanbrgmedis[]'; // Ganti dengan nama yang sesuai
    //                     satuan.value = pesanan.satuan; // Contoh: pesanan.jumlah_pesanan
    //                     const tdsatuan = document.createElement('td');
    //                     tdsatuan.className = 'align-middle p-1';
    //                     tdsatuan.appendChild(satuan);
    //                     tr.appendChild(tdsatuan);

    //                     // Buat input untuk kolom ketiga
    //                     const hargapengajuan = document.createElement('input');
    //                     hargapengajuan.type = 'hidden';
    //                     hargapengajuan.className = 'text-center w-full border';
    //                     hargapengajuan.name = 'harga_satuan_pengajuan[]'; // Ganti dengan nama yang sesuai
    //                     hargapengajuan.value = pesanan.harga_satuan_pengajuan; // Contoh: pesanan.harga_satuan
    //                     const tdhrgpengajuan = document.createElement('td');
    //                     tdhrgpengajuan.className = 'align-middle p-1';
    //                     tdhrgpengajuan.appendChild(hargapengajuan);
    //                     tr.appendChild(tdhrgpengajuan);
    //                     tdhrgpengajuan.style.display = 'none';

    //                     const input3 = document.createElement('input');
    //                     input3.type = 'text';
    //                     input3.className = 'text-center w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#FDFDFD]';
    //                     input3.name = 'harga_satuan_pemesanan[]'; // Ganti dengan nama yang sesuai
    //                     input3.value = pesanan.harga_satuan_pengajuan; // Contoh: pesanan.harga_satuan
    //                     const td3 = document.createElement('td');
    //                     td3.className = 'align-middle p-1';
    //                     td3.appendChild(input3);
    //                     tr.appendChild(td3);

    //                     // Buat input untuk kolom keempat
    //                     const input4 = document.createElement('input');
    //                     input4.type = 'text';
    //                     input4.className = 'text-center w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#FDFDFD]';
    //                     input4.name = 'totalperitem[]'; // Ganti dengan nama yang sesuai
    //                     input4.value = pesanan.jumlah_pesanan * pesanan.harga_satuan; // Contoh: pesanan.jumlah_diterima
    //                     const td4 = document.createElement('td');
    //                     td4.className = 'align-middle p-1 text-right';
    //                     td4.appendChild(input4);
    //                     tr.appendChild(td4);

    //                     const kadaluwarsa = document.createElement('input');
    //                     kadaluwarsa.type = 'date';
    //                     kadaluwarsa.className = 'text-center w-full border';
    //                     kadaluwarsa.name = 'kadaluwarsa[]'; // Ganti dengan nama yang sesuai
    //                     kadaluwarsa.value = pesanan.kadaluwarsa; // Contoh: pesanan.harga_satuan
    //                     const tdkadaluwarsa = document.createElement('td');
    //                     tdkadaluwarsa.className = 'align-middle p-1';
    //                     tdkadaluwarsa.appendChild(kadaluwarsa);
    //                     tr.appendChild(tdkadaluwarsa);
    //                     tdkadaluwarsa.style.display = 'none';

    //                     // Tambahkan baris ke tbody
    //                     tbody.appendChild(tr);
    //                 });

    //             })
    //             .catch(error => {
    //                 console.error('Terjadi kesalahan:', error);
    //             });

    //         fetch(pengajuanApiUrl, {
    //                 headers: {
    //                     'Authorization': 'Bearer ' + token,
    //                     'Content-Type': 'application/json'
    //                 }
    //             })
    //             .then(response => response.json())
    //             .then(data => {
    //                 document.querySelector('input[name="tglpengajuan"]').value = data['data']['tanggal_pengajuan'];
    //                 document.querySelector('input[name="nopengajuan"]').value = data['data']['nomor_pengajuan'];
    //                 document.querySelector('input[name="pegawaipengajuan"]').value = data['data']['id_pegawai'];
    //                 document.querySelector('input[name="catatanpengajuan"]').value = data['data']['catatan'];
    //                 document.querySelector('input[name="diskonpersenpengajuan"]').value = data['data']['diskon_persen'];
    //                 document.querySelector('input[name="diskonjumlahpengajuan"]').value = data['data']['diskon_jumlah'];
    //                 document.querySelector('input[name="pajakpersenpengajuan"]').value = data['data']['pajak_persen'];
    //                 document.querySelector('input[name="pajakjumlahpengajuan"]').value = data['data']['pajak_jumlah'];
    //                 document.querySelector('input[name="materaipengajuan"]').value = data['data']['materai'];
    //                 document.querySelector('input[name="diskonpersenpemesanan"]').value = data['data']['diskon_persen'];
    //                 document.querySelector('input[name="diskonjumlahpemesanan"]').value = data['data']['diskon_jumlah'];
    //                 document.querySelector('input[name="pajakpersenpemesanan"]').value = data['data']['pajak_persen'];
    //                 document.querySelector('input[name="pajakjumlahpemesanan"]').value = data['data']['pajak_jumlah'];
    //                 document.querySelector('input[name="materaipemesanan"]').value = data['data']['materai'];

    //             })
    //             .catch(error => {
    //                 console.error('Terjadi kesalahan:', error);
    //             });
    //     })
    // };
</script>
<?= $this->endSection(); ?>