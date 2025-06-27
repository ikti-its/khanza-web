<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<!-- Table Section -->
<div class="max-w-[85rem] py-6 lg:py-3 mx-auto">
    <!-- <div class="max-w-[85rem] w-full py-6 lg:py-3"> -->
    <!-- Card -->
    <div class="flex flex-col">
        <div class="-m-1.5 overflow-y-hidden">
            <div class="sm:px-20 min-w-full inline-block align-middle">
                <div class="p-5 bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-slate-900 dark:border-gray-700">

                    <!-- Header -->
                    <div class="py-1 flex justify-between items-center border-gray-200 dark:border-gray-700">
                        <div>
                        <?php if (!empty($permintaanreseppulang_data)) : ?>
                            <div class="mb-4 text-xl font-black text-gray-800 dark:text-gray-200 space-y-1">
                                <div class="flex">
                                    <span class="w-64">Permintaan Resep Pulang</span>
                                </div>
                            </div>
                        <?php endif; ?>

                        </div>
                        <div class="flex gap-x-6 justify-center items-center">
                            <div class="relative">
                                <?= view('components/notif/icon') ?>

                                <!-- Notification Pop-up -->
                                <div id="notif-popup" class="absolute right-0 mt-2 w-[30rem] overflow-y-auto z-[2] bg-white rounded-lg shadow-lg hidden">
                                    <?= view('components/notif/notif') ?>
                                    <div>
                                        <div id="stok-content" class="max-h-[15rem] overflow-y-auto">

                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="h-[1.375rem] border-r-4 bg-[#DCDCDC]"></div>
                            <?php if (!empty($permintaanreseppulang_data)) : ?>
                                <?php $nomor_rawat = $permintaanreseppulang_data['nomor_rawat'] ?? ''; ?>
                                <!-- <div>
                                    <a href="<?= base_url('permintaanreseppulang/tambah') . '?nomor_rawat=' . $nomor_rawat ?>"
                                    class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-[#0A2D27] text-[#ACF2E7] hover:bg-[#13594E] disabled:opacity-50 disabled:pointer-events-none dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                                        <svg class="flex-shrink-0 size-3" xmlns="http://www.w3.org/2000/svg" width="16" height="1" viewBox="0 0 16 16" fill="none">
                                            <path d="M2.63452 7.50001L13.6345 7.5M8.13452 13V2" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                                        </svg>
                                        Tambah
                                    </a>
                                </div> -->
                            <?php endif; ?>
                            <?= view('components/header/audit_button', [
                                'link' => '/permintaanreseppulang/audit'
                            ]) ?>
                        </div>
                    </div>
                    <!-- End Header -->
                    <?php
                        echo view('components/header/search_bar');
                        
                        $modul_path = '/pemberianobat';
                        $tabel    = $permintaanreseppulang_data;
                        $kolom_id = 'no_rawat';
                        $aksi = [
                            'cetak'    => false,
                            'tindakan' => false,
                            'detail'   => true,
                            'ubah'     => true,
                            'hapus'    => true,
                        ];
                        $konfig = [
                            // [visible, Display, Kolom, Jenis]
                            [1, 'Nomor Permintaan'   , 'no_permintaan' , 'indeks'],
                            [1, 'Tanggal Permintaan' , 'tgl_permintaan', 'tanggal'],
                            [1, 'Jam Permintaan'     , 'jam'           , 'jam'],
                            [0, 'Kamar'              , 'kamar'         , 'teks'],
                            [1, 'Nomor Rawat'        , 'no_rawat'      , 'indeks'],
                            [1, 'Dokter Peresep'     , 'kd_dokter'     , 'indeks'],
                            [1, 'Status'             , 'status'        , 'status'],
                            [1, 'Nama Pasien'        , 'nama_pasien'   , 'nama']
                        ];
                        echo view('components/tabel/data', [
                            'modul_path' => $modul_path,
                            'tabel'      => $tabel,
                            'kolom_id'   => $kolom_id,
                            'konfig'     => $konfig,
                            'aksi'       => $aksi
                        ]);
                        
                        echo view('components/footer/footer', [
                            'meta_data'  => $meta_data,
                            'modul_path' => $modul_path
                        ]);      
                    ?>

                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    
                            <?php foreach ($permintaanreseppulang_data as $i => $permintaanreseppulang) : ?>
                                <div id="hs-vertically-centered-scrollable-modal-<?= $permintaanreseppulang['no_permintaan'] ?>" class="hs-overlay hidden size-full fixed top-0 start-0 z-[80] pointer-events-none">
                                    <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto h-[calc(100%-3.5rem)] min-h-[calc(100%-3.5rem)] flex items-center ">
                                        <div class="overflow-y-auto w-full max-h-full flex flex-col bg-white border shadow-sm rounded-xl pointer-events-auto dark:bg-neutral-800 dark:border-neutral-700 dark:shadow-neutral-700/70">
                                            
                                            <div class="p-4">
                                                <div class="space-y-4">
                                                <div>
                                                    <div class="mb-5 sm:block">
                                                        <label class="block mb-2 text-sm text-gray-900 dark:text-white">Nomor Permintaan</label>
                                                        <input type="text" name="" value="<?= $permintaanreseppulang['no_permintaan'] ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full dark:border-gray-600 dark:text-white" readonly>
                                                    </div>

                                                    <div class="mb-5 sm:block">
                                                        <label class="block mb-2 text-sm text-gray-900 dark:text-white">Tanggal</label>
                                                        <input type="text" name="" value="<?= $permintaanreseppulang['tgl_permintaan'] ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full dark:border-gray-600 dark:text-white" readonly>
                                                    </div>

                                                    <div class="mb-5 sm:block">
                                                        <label class="block mb-2 text-sm text-gray-900 dark:text-white">Jam</label>
                                                        <input type="text" name="" value="<?= $permintaanreseppulang['jam'] ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full dark:border-gray-600 dark:text-white" readonly>
                                                    </div>

                                                    <div class="mb-5 sm:block">
                                                        <label class="block mb-2 text-sm text-gray-900 dark:text-white">Kamar</label>
                                                        <input type="text" name="" value="<?= $permintaanreseppulang['kamar'] ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full dark:border-gray-600 dark:text-white" readonly>
                                                    </div>

                                                    <div class="mb-5 sm:block">
                                                        <label class="block mb-2 text-sm text-gray-900 dark:text-white">Status</label>
                                                        <input type="text" name="" value="<?= $permintaanreseppulang['status'] ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full dark:border-gray-600 dark:text-white" readonly>
                                                    </div>

                                                    <div class="mb-5 sm:block">
                                                        <label class="block mb-2 text-sm text-gray-900 dark:text-white">Pasien</label>
                                                        <input type="text" name="" value="<?= $permintaanreseppulang['nama_pasien'] ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full dark:border-gray-600 dark:text-white" readonly>
                                                    </div>

                                                    <div class="mb-5 sm:block">
                                                        <label class="block mb-2 text-sm text-gray-900 dark:text-white">Dokter Yang Meminta</label>
                                                        <input type="text" name="" value="<?= $permintaanreseppulang['kd_dokter'] ?>" class="bg-gray-100 text-gray-900 text-sm rounded-lg p-2 w-full dark:border-gray-600 dark:text-white" readonly>
                                                    </div>

                                                    <button 
                                                        onclick="validatePermintaan(this)" 
                                                        data-no_permintaan="<?= $permintaanreseppulang['no_permintaan'] ?>" 
                                                        data-no_rawat="<?= $permintaanreseppulang['no_rawat'] ?>" 
                                                        class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-800">
                                                        Buat Resep Pulang
                                                    </button>
                                                </div>

                                                </div>
                                                <div class="flex justify-end items-center gap-x-2 py-3 px-4 border-t dark:border-neutral-700">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <tr>
                                    <!-- <td class="h-px w-64 whitespace-nowrap">
                                        <div class="px-6 py-3">
                                            <a href="<?= base_url('resepobat/' . ($permintaanreseppulang['no_rawat'] ?? 'N/A')) ?>" class="text-center block text-sm font-semibold text-gray-800 cursor-pointer dark:text-gray-200 hover:underline">
                                                <?= $permintaanreseppulang['no_rawat'] ?? 'N/A' ?>
                                            </a>
                                        </div>
                                    </td> -->
                                   
                                    
                                    
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <?php foreach ($permintaanreseppulang_data as $i => $permintaanreseppulang) : ?>
                            <div id="hs-vertically-centered-scrollable-modal-<?= $permintaanreseppulang['no_permintaan'] . '-' . $i ?>" class="hs-overlay hidden fixed top-0 start-0 z-[80] w-full h-full bg-gray-800 bg-opacity-50 overflow-y-auto">
                                <div class="mt-20 mx-auto w-full max-w-lg p-6 bg-white dark:bg-neutral-800 rounded shadow">
                                    <div class="flex justify-between items-center border-b pb-2">
                                        <h3 class="text-lg font-bold"><?= $permintaanreseppulang['no_permintaan'] ?></h3>
                                        <button data-hs-overlay="#hs-vertically-centered-scrollable-modal-<?= $permintaanreseppulang['no_permintaan'] . '-' . $i ?>" class="text-gray-600 dark:text-white hover:text-red-600">
                                            &times;
                                        </button>
                                    </div>
                                    <div class="mt-4 space-y-3">
                                        <div><strong>Nomor Rawat:</strong> <?= $permintaanreseppulang['no_permintaan'] ?></div>
                                        <!-- <div><strong>Nomor RM:</strong> <?= $permintaanreseppulang['no_permintaan'] ?></div> -->
                                        <div><strong>Dokter:</strong> <?= $permintaanreseppulang['no_permintaan'] ?? 'N/A' ?></div>
                                        <div><strong>Tanggal:</strong> <?= $permintaanreseppulang['no_permintaan'] ?></div>
                                        <div><strong>Jam:</strong> <?= $permintaanreseppulang['no_permintaan'] ?></div>
                                    </div>
                                    <div class="mt-6 text-end">
                                        <button class="text-sm text-gray-700 bg-gray-200 px-3 py-2 rounded hover:bg-gray-300 dark:text-white dark:bg-gray-700 dark:hover:bg-gray-600" data-hs-overlay="#hs-vertically-centered-scrollable-modal-<?= $permintaanreseppulang['no_permintaan'] . '-' . $i ?>">Tutup</button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </table>
                    </div>

                    <!-- End Table -->
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Card -->

<!-- End Table Section -->
<script>
function validatePermintaan(button) {
    const noPermintaan = button.getAttribute('data-no_permintaan');
    const noRawat = button.getAttribute('data-no_rawat');

    if (!noPermintaan || !noRawat) {
        alert("Data permintaan tidak lengkap.");
        return;
    }

    // Step 1: Fetch kamar info from rawatinap endpoint
    fetch(`http://127.0.0.1:8080/v1/rawatinap/${encodeURIComponent(noRawat)}`, {
        method: 'GET',
        headers: {
            'Accept': 'application/json'
            // Uncomment below if using auth
            // 'Authorization': 'Bearer ' + token
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Gagal mengambil data rawat inap');
        }
        return response.json();
    })
    .then(rawat => {
        const kamar = rawat?.data?.kamar || '-';

        // Step 2: Update status of permintaan to 'Sudah'
        return fetch(`http://127.0.0.1:8080/v1/permintaan-resep-pulang/status/${encodeURIComponent(noPermintaan)}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json'
                // Uncomment below if using auth
                // 'Authorization': 'Bearer ' + token
            },
            body: JSON.stringify({ status: 'Sudah' })
        })
        .then(updateRes => {
            if (!updateRes.ok) {
                throw new Error('Gagal mengubah status permintaan');
            }

            // Step 3: Redirect to tambah reseppulang view
            const targetUrl = `/reseppulang/tambah?no_rawat=${encodeURIComponent(noRawat)}&kamar=${encodeURIComponent(kamar)}&no_permintaan=${encodeURIComponent(noPermintaan)}`;
            window.location.href = targetUrl;
        });
    })
    .catch(error => {
        console.error('❌ Error during validation:', error);
        alert('Terjadi kesalahan saat memproses permintaan resep pulang.');
    });
}
</script>
<?= $this->endSection(); ?>