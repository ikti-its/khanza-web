<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<!-- Card Section -->
<div class="max-w-[85rem] py-6 lg:py-3 px-8 mx-auto">
    <!-- Card -->
    <div class="bg-white rounded-xl shadow p-4 sm:p-7 dark:bg-slate-900">
        <?= view('components/form/judul', [
            'judul' => 'Tambah Tagihan Barang Medis'
        ]) ?>
        <form action="/tagihanmedis/submittambah" method="post" onsubmit="return validateForm()">
            <!-- Grid -->
            <input type="hidden" value="<?= $penerimaan_data['id_pengajuan'] ?>" name="idpengajuan" class="text-center border mr-1 w-[20%]">
            <input type="hidden" value="<?= $penerimaan_data['id_pemesanan'] ?>" name="idpemesanan" class="text-center border mr-1 w-[20%]">
            <input type="hidden" id="statuspesanan" name="statuspesanan">
            <div class=" sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Nomor Faktur</label>
                <input type="hidden" name="idpenerimaan" value="<?= $penerimaan_data['id'] ?>" class="border bg-[#F6F6F6] cursor-default text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" required readonly>
                <input type="hidden" id="tglpenerimaan" value="<?= $penerimaan_data['tanggal_datang'] ?>" class="border bg-[#F6F6F6] cursor-default text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" required readonly>
                <input type="text" name="" value="<?= $penerimaan_data['no_faktur'] ?>" class="border bg-[#F6F6F6] cursor-default text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" required readonly>
            </div>
            <div class="mt-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Tanggal Bayar</label>
                <input type="date" id="tglbayar" name="tglbayar" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" required>
            </div>
            <div id="dateError" class="mt-2 hidden">
                <label class="text-sm text-gray-900 dark:text-white md:w-1/4"></label>
                <div class="flex items-center text-red-500 text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none">
                        <path d="M7 5.25V8.16667" stroke="#DA4141" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M7 12.4891H3.465C1.44083 12.4891 0.595001 11.0424 1.575 9.27492L3.395 5.99658L5.11 2.91658C6.14834 1.04408 7.85167 1.04408 8.89 2.91658L10.605 6.00242L12.425 9.28075C13.405 11.0482 12.5533 12.4949 10.535 12.4949H7V12.4891Z" stroke="#DA4141" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M6.99707 9.91675H7.00231" stroke="#DA4141" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg> Tanggal bayar harus setelah tanggal penerimaan dan maksimal 30 hari dari penerimaan.
                </div>
            </div>
            <div class="mt-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Pegawai</label>
                <select name="pegawaitagihan" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" required>
                    <option value="" selected>-</option>
                    <?php foreach ($pegawai_data as $pegawai) : ?>
                        <option value="<?= $pegawai['id'] ?>"><?= $pegawai['nama'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mt-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Jumlah (Sisa Bayar)</label>
                <input type="text" name="jlhbayar" id="jlhbayar" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" required>
                <input type="text" name="totalbayar" id="totalbayar" class="border bg-[#F6F6F6] cursor-default text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" readonly>
            </div>
            <div class="mt-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Nomor Bukti</label>
                <input type="text" name="nobukti" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" placeholder="Jika bayar menggunakan cash isi -" required>
            </div>
            <div class="mt-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Akun Bayar</label>
                <select name="akunbayar" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white" required>
                    <option value="" selected>-</option>
                    <option value="1000">Cash</option>
                    <option value="2000">Transfer lewat Mandiri</option>

                </select>
            </div>
            <div class="mt-5 sm:block md:flex items-center">
                <label class="block mb-2 md:mb-0 text-sm text-gray-900 dark:text-white md:w-1/4">Keterangan</label>
                <input type="text" name="keterangantagihan" class="border border-gray-300 text-gray-900 text-sm rounded-lg p-2 w-full lg:w-1/4 dark:border-gray-600 dark:text-white">
            </div>

            <!-- End Grid -->
            <div class="mt-5 flex flex-col">
                <div class="-m-1.5 overflow-x-auto">
                    <div class="p-1.5 min-w-full inline-block align-middle">
                        <div class="border rounded-lg overflow-hidden dark:border-neutral-700">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700" id="item-list">
                                <?php 
                                    $widths  = [8, 20, 12, 12, 12, 8, 14, 14];
                                    echo view('components/tabel/colgroup',['widths' => $widths]);
                                    
                                    $columns = [
                                        'Jumlah',
                                        'Barang',
                                        'Satuan',
                                        'Harga',
                                        'Subtotal',
                                        'Diskon (%)',
                                        'Diskon (Jumlah)',
                                        'Total per Item'
                                    ];
                                    // echo view('components/tabel/thead',['kolom' => $columns]);
                                ?>
                        
                                <thead class="bg-[#DCDCDC]">
                                    <tr class="bg-navy disabled">
                                        <th class="px-1 py-1 text-center">Jumlah</th>
                                        <th class="px-1 py-1 text-center">Barang</th>
                                        <th class="px-1 py-1 text-center">Satuan</th>
                                        <th class="px-1 py-1 text-center">Harga</th>
                                        <th class="px-1 py-1 text-center">Subtotal</th>
                                        <th class="px-1 py-1 text-center">Diskon (%)</th>
                                        <th class="px-1 py-1 text-center">Diskon (Jumlah)</th>
                                        <th class="px-1 py-1 text-center">Total per item</th>
                                    </tr>
                                </thead>
                                <tbody class="tabelbodypesanan divide-y divide-gray-200 dark:divide-neutral-700">
                                    <?php $totalsblmpajak = 0;
                                    foreach ($pesanan_data as $pesanan) {

                                        $totalsblmpajak += $pesanan['total_per_item']; ?>
                                        <tr>
                                            <td class="align-middle p-1 text-center">
                                                <input type="number" min="0" value="<?= $pesanan['jumlah_pesanan'] ?>" class="text-center w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#F6F6F6] cursor-default" name="" readonly />
                                            </td>
                                            <td class="align-middle p-1">
                                                <input type="text" value="<?php foreach ($medis_data as $medis) {
                                                                                if ($medis['id'] === $pesanan['id_barang_medis']) {
                                                                                    echo $medis['nama'];
                                                                                }
                                                                            } ?>" class="text-center w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#F6F6F6] cursor-default" name="" readonly />
                                            </td>
                                            <td class="align-middle p-1">
                                                <input type="text" value="<?php foreach ($satuan_data as $satuan) {
                                                                                if ($satuan['id'] === $pesanan['satuan'] && $pesanan['satuan'] !== 1) {
                                                                                    echo $satuan['nama'];
                                                                                } else {
                                                                                    echo '';
                                                                                }
                                                                            } ?>" class="text-center w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#F6F6F6] cursor-default" name="" readonly />
                                            </td>

                                            <td class="align-middle p-1">
                                                <input type="number" min="0" value="<?= $pesanan['harga_satuan_pemesanan'] ?>" class="text-center w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#F6F6F6] cursor-default" name="" readonly />
                                            </td>
                                            <td class="align-middle p-1">
                                                <input type="number" min="0" value="<?= $pesanan['subtotal_per_item'] ?>" class="text-center w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#F6F6F6] cursor-default" name="subtotalperitem[]" readonly />
                                            </td>
                                            <td class="align-middle p-1">
                                                <input type="number" min="0" value="<?= $pesanan['diskon_persen'] ?>" class="text-center w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#F6F6F6] cursor-default" name="diskonpersenperitem[]" readonly />
                                            </td>
                                            <td class="align-middle p-1">
                                                <input type="number" min="0" value="<?= $pesanan['diskon_jumlah'] ?>" class="text-center w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#F6F6F6] cursor-default" name="diskonjumlahperitem[]" readonly />
                                            </td>
                                            <td class="align-middle p-1 text-right">
                                                <input type="text" value="<?= $pesanan['total_per_item'] ?? 'Belum ada total' ?>" class="text-center w-full border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#F6F6F6] cursor-default" name="" readonly />
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                                <tfoot>
                                    <?php foreach ($pemesanan_data as $pemesanan) {
                                        if ($pemesanan['id_pengajuan'] === $penerimaan_data['id_pengajuan']) { ?>

                                            <tr>
                                                <th class="p-1 text-right" colspan="7">Total (Sebelum Pajak)</th>
                                                <th class="p-1 text-right">
                                                    <input type="number" min="0" value="<?= $totalsblmpajak ?>" step="any" name="totalsblmpajak" class=" text-center border rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#F6F6F6] cursor-default" readonly>
                                                </th>
                                            </tr>
                                            <tr>
                                                <th class="p-1 text-right" colspan="7">Pajak (%)
                                                    <input type="number" min="0" value="<?= $pemesanan['pajak_persen'] ?>" name="pajakpersen" class=" text-center border w-[15%] rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#F6F6F6] cursor-default" readonly>
                                                </th>
                                                <th class="p-1 text-right">
                                                    <input type="number" min="0" value="<?= $pemesanan['pajak_jumlah'] ?>" class="w-full border text-center rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#F6F6F6] cursor-default" name="pajakjumlah" readonly>
                                                </th>
                                            </tr>
                                            <tr>
                                                <th class="p-1 text-right" colspan="7">Materai</th>
                                                <th class="p-1 text-right">
                                                    <input type="number" min="0" value="<?= $pemesanan['materai'] ?>" class="w-full border text-center rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#F6F6F6] cursor-default" name="materai" readonly>
                                                </th>
                                            </tr>
                                            <tr>
                                                <th class="p-1 text-right" colspan="7">Total</th>
                                                <th class="p-1" id="total"><input type="number" min="0" value="<?= $pemesanan['total_pemesanan'] ?>" id="total_pemesanan" class="w-full border text-center rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#F6F6F6] cursor-default" name="" readonly></th>
                                            </tr>
                                    <?php }
                                    } ?>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-5 flex justify-end gap-x-2">
                <a href="javascript:history.back()" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-white dark:hover:bg-gray-800 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                    Kembali
                </a>
                <button type="submit" id="submitButton" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-[#0A2D27] text-[#ACF2E7] disabled:opacity-50 disabled:pointer-events-none dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                    Simpan
                </button>
            </div>
        </form>

    </div>
    <!-- End Card -->

</div>

<!-- End Card Section -->
<script>
    const totalPemesananInput = document.getElementById('total_pemesanan');
    const jlhBayarInput = document.getElementById('jlhbayar');
    const totalBayarInput = document.getElementById('totalbayar');
    const statusInput = document.getElementById('statuspesanan');

    totalBayarInput.value = totalPemesananInput.value;

    jlhBayarInput.addEventListener('input', function() {
        const jlhBayar = parseFloat(jlhBayarInput.value);
        const totalPemesanan = parseFloat(totalPemesananInput.value);

        if (!isNaN(jlhBayar)) {
            const totalBayar = totalPemesanan - jlhBayar;

            if (totalBayar >= 0) {
                totalBayarInput.value = totalBayar.toFixed(0);
            } else {
                totalBayarInput.value = "0";
            }

            if (jlhBayar < totalPemesanan) {
                statusInput.value = "6";
            } else if (jlhBayar === totalPemesanan) {
                statusInput.value = "7";
            }
        } else {
            totalBayarInput.value = totalPemesananInput.value;
        }
    });

    var tglpenerimaan = new Date(document.getElementById('tglpenerimaan').value);
    tglpenerimaan.setHours(0, 0, 0, 0);
    var minDate = new Date(tglpenerimaan);
    var maxDate = new Date(tglpenerimaan);
    maxDate.setDate(maxDate.getDate() + 30);
    document.getElementById('tglbayar').addEventListener('input', function() {
        var tglbayarInput = document.getElementById('tglbayar');
        var dateError = document.getElementById('dateError');
        var selectedDate = new Date(tglbayarInput.value);


        if (selectedDate < minDate || selectedDate > maxDate) {
            tglbayarInput.classList.add('border-red-500');
            dateError.classList.remove('hidden');
            dateError.classList.add('flex', 'items-center');
        } else {
            tglbayarInput.classList.remove('border-red-500');
            dateError.classList.add('hidden');
            dateError.classList.remove('block');
        }
    });

    function validateForm() {
        var tglbayarInput = document.getElementById('tglbayar');
        var dateError = document.getElementById('dateError');
        var selectedDate = new Date(tglbayarInput.value);


        if (selectedDate < minDate || selectedDate > maxDate) {
            tglbayarInput.classList.add('border-red-500');
            dateError.classList.remove('hidden');
            dateError.classList.add('block');
            alert("Tanggal bayar harus setelah tanggal penerimaan dan maksimal 30 hari dari penerimaan.");
            return false;
        }

        var jlhBayar = parseFloat(jlhBayarInput.value);
        var totalPemesanan = parseFloat(totalPemesananInput.value);

        if (jlhBayar > totalPemesanan) {
            alert("Jumlah bayar tidak boleh lebih besar dari total pemesanan.");
            return false;
        }

        var submitButton = document.getElementById('submitButton');
        submitButton.setAttribute('disabled', true);
        submitButton.innerHTML = 'Menyimpan...';
        return true;
    }
    // window.onload = function() {
    //     const dropdown = document.getElementById('dropdown-id-penerimaan');

    //     dropdown.addEventListener('change', function() {
    //         const selectedId = this.value;
    //         console.log('ID yang dipilih:', selectedId);

    //         // URL API berdasarkan nilai yang dipilih dari dropdown
    //         const penerimaanApiUrl = '<?php echo $_ENV["api_URL"]; ?>/pengadaan/penerimaan/' + selectedId;
    //         // Token yang digunakan untuk otentikasi
    //         const token = '<?= $token ?>'; // Ganti dengan token Anda
    //         const barangMedis = <?php echo json_encode($medis_data); ?>;
    //         const satuanMedis = <?php echo json_encode($satuan_data); ?>;

    //         fetch(penerimaanApiUrl, {
    //                 method: 'GET',
    //                 headers: {
    //                     'Authorization': 'Bearer ' + token,
    //                     'Content-Type': 'application/json'
    //                     // Tambahkan header lain yang diperlukan seperti Authorization jika diperlukan
    //                 }
    //             })
    //             .then(response => response.json())
    //             .then(data => {
    //                 document.querySelector('input[name="idpemesanan"]').value = data['data']['id_pemesanan'];

    //                 const pengajuanId = data.data.id_pengajuan; // Sesuaikan dengan kunci yang benar dalam respons API
    //                 const pesananApiUrl = '<?php echo $_ENV["api_URL"]; ?>/pengadaan/pesanan/pengajuan/' + pengajuanId;
    //                 const pengajuanApiUrl = '<?php echo $_ENV["api_URL"]; ?>/pengadaan/pengajuan/' + pengajuanId;
    //                 console.log('Pengajuan ID dari penerimaan:', pengajuanId);
    //                 fetch(pesananApiUrl, {
    //                         headers: {
    //                             'Authorization': 'Bearer ' + token,
    //                             'Content-Type': 'application/json'
    //                         }
    //                     })
    //                     .then(response => response.json())
    //                     .then(data => {
    //                         const tbody = document.querySelector('.tabelbodypesanan');
    //                         tbody.innerHTML = '';
    //                         data.data.forEach(pesanan => {
    //                             const tr = document.createElement('tr');

    //                             // Buat input untuk setiap kolom dalam baris
    //                             const input1 = document.createElement('input');
    //                             input1.type = 'number';
    //                             input1.className = 'text-center w-full rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#F6F6F6] cursor-default';
    //                             input1.name = 'input_name1'; // Ganti dengan nama yang sesuai
    //                             input1.value = pesanan.jumlah_pesanan; // Contoh: pesanan.id
    //                             const td1 = document.createElement('td');
    //                             td1.className = 'align-middle p-1 text-center';
    //                             td1.appendChild(input1);
    //                             tr.appendChild(td1);

    //                             satuanMedis.forEach(satuan => {
    //                                 if (pesanan.satuan === satuan.id) {
    //                                     const satuaninput = document.createElement('input');
    //                                     satuaninput.type = 'text';
    //                                     satuaninput.className = 'text-center w-full rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#F6F6F6] cursor-default';
    //                                     satuaninput.readOnly = true;
    //                                     satuaninput.name = ''; // Ganti dengan nama yang sesuai
    //                                     satuaninput.value = satuan.nama; // Contoh: pesanan.id
    //                                     const tdsatuan = document.createElement('td');
    //                                     tdsatuan.className = 'align-middle p-1 text-center';
    //                                     tdsatuan.appendChild(satuaninput);
    //                                     tr.appendChild(tdsatuan);
    //                                 }
    //                             });
    //                             // Buat input untuk kolom kedua
    //                             barangMedis.forEach(barang => {
    //                                 if (pesanan.id_barang_medis === barang.id) {
    //                                     const medis = document.createElement('input');
    //                                     medis.type = 'text';
    //                                     medis.className = 'text-center w-full rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#F6F6F6] cursor-default';
    //                                     medis.name = ''; // Ganti dengan nama yang sesuai
    //                                     medis.value = barang.nama; // Contoh: pesanan.jumlah_pesanan
    //                                     const tdmedis = document.createElement('td');
    //                                     tdmedis.className = 'align-middle p-1';
    //                                     tdmedis.appendChild(medis);
    //                                     tr.appendChild(tdmedis);
    //                                 }
    //                             });

    //                             // Buat input untuk kolom ketiga
    //                             const input3 = document.createElement('input');
    //                             input3.type = 'text';
    //                             input3.className = 'text-center w-full rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#F6F6F6] cursor-default';
    //                             input3.name = 'input_name3'; // Ganti dengan nama yang sesuai
    //                             input3.value = pesanan.harga_satuan_pemesanan; // Contoh: pesanan.harga_satuan
    //                             const td3 = document.createElement('td');
    //                             td3.className = 'align-middle p-1';
    //                             td3.appendChild(input3);
    //                             tr.appendChild(td3);

    //                             // Buat input untuk kolom keempat
    //                             const input4 = document.createElement('input');
    //                             input4.type = 'text';
    //                             input4.className = 'text-center w-full rounded-[0.5rem] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] border-[#DCDCDC] bg-[#F6F6F6] cursor-default';
    //                             input4.name = 'input_name4'; // Ganti dengan nama yang sesuai
    //                             input4.value = pesanan.jumlah_pesanan * pesanan.harga_satuan_pemesanan; // Contoh: pesanan.jumlah_diterima
    //                             const td4 = document.createElement('td');
    //                             td4.className = 'align-middle p-1 text-right';
    //                             td4.appendChild(input4);
    //                             tr.appendChild(td4);

    //                             // Tambahkan baris ke tbody
    //                             tbody.appendChild(tr);
    //                             document.querySelector('input[name="totalbayar"]').value = pesanan.jumlah_pesanan * pesanan._pemesanan;
    //                         });

    //                     })
    //                     .catch(error => {
    //                         console.error('Terjadi kesalahan:', error);
    //                     });
    //                 // Lakukan sesuatu dengan pengajuanId, misalnya melakukan permintaan API lain atau melakukan manipulasi DOM
    //                 fetch(pengajuanApiUrl, {
    //                         headers: {
    //                             'Authorization': 'Bearer ' + token,
    //                             'Content-Type': 'application/json'
    //                         }
    //                     })
    //                     .then(response => response.json())
    //                     .then(data => {
    //                         document.querySelector('input[name="idpengajuan"]').value = data['data']['id'];
    //                         document.querySelector('input[name="tglpengajuan"]').value = data['data']['tanggal_pengajuan'];
    //                         document.querySelector('input[name="nopengajuan"]').value = data['data']['nomor_pengajuan'];
    //                         document.querySelector('input[name="supplier"]').value = data['data']['id_supplier'];
    //                         document.querySelector('input[name="pegawaipengajuan"]').value = data['data']['id_pegawai'];
    //                         document.querySelector('input[name="catatanpengajuan"]').value = data['data']['catatan'];
    //                         document.querySelector('input[name="diskonpersen"]').value = data['data']['diskon_persen'];
    //                         document.querySelector('input[name="diskonjumlah"]').value = data['data']['diskon_jumlah'];
    //                         document.querySelector('input[name="pajakpersen"]').value = data['data']['pajak_persen'];
    //                         document.querySelector('input[name="pajakjumlah"]').value = data['data']['pajak_jumlah'];
    //                         document.querySelector('input[name="materai"]').value = data['data']['materai'];
    //                     })
    //                     .catch(error => {
    //                         console.error('Terjadi kesalahan pada pengajuan:', error);
    //                     });
    //             }) //data penerimaan

    //             .catch(error => {
    //                 console.error('Terjadi kesalahan:', error);
    //             });

    //         // Lakukan permintaan ke URL API yang baru

    //     })
    // };
</script>
<?= $this->endSection(); ?>